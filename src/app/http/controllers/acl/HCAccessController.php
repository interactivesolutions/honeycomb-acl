<?php

namespace interactivesolutions\honeycombacl\app\http\controllers\acl;

use interactivesolutions\honeycombacl\app\models\acl\Permissions;
use interactivesolutions\honeycombacl\app\models\acl\Roles;
use interactivesolutions\honeycombacl\app\models\acl\RolesPermissionsConnections;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;

class HCAccessController extends HCBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function adminIndex()
    {
        $config = [
            'title'       => trans('HCACL::acl_access.page_title'),
            'roles'       => $this->getRolesWithPermissions(),
            'permissions' => $this->getAllPermissions(),
            'updateUrl'   => route('admin.api.acl.access.update'),
        ];

        return view('HCACL::admin.roles', ['config' => $config]);
    }

    /**
     * Update permissions
     *
     * @return array
     */
    public function updateAccess()
    {
        $data = request()->only('role_id', 'permission_id');

        $superAdmin = Roles::superAdmin()->first();

        if( $data['role_id'] == $superAdmin->id ) {
            return \HCLog::info('ACCESS-0001', trans('HCACL::validator.roles.cant_update_super'));
        }

        if( ! auth()->user()->hasRole('admin') && ! auth()->user()->isSuperAdmin() ) {
            return \HCLog::info('ACCESS-0001', trans('HCACL::validator.roles.cant_update_roles'));
        }

        $record = RolesPermissionsConnections::where($data)->first();

        if( is_null($record) ) {
            RolesPermissionsConnections::create($data);
            $message = 'created';
        } else {
            RolesPermissionsConnections::where($data)->delete();
            $message = 'deleted';
        }

        cache()->forget('adminMenu');
        cache()->forget('permissions');

        return ['success' => true, 'message' => $message];
    }

    /**
     * Get roles with permissions
     *
     * @return array
     */
    public function getRolesWithPermissions()
    {
        $roles = Roles::with('permissions')
            ->notSuperAdmin()
            ->orderBy('name')
            ->get()->map(function ($role) {
                return [
                    'id'          => $role->id,
                    'role'        => $role->name,
                    'slug'        => $role->slug,
                    'permissions' => $role->permissions->pluck('id')->all(),
                ];
            });

        return json_encode($roles);
    }

    /**
     * Get roles and permissions
     *
     * @return mixed
     */
    private function getAllPermissions()
    {
        $user = auth()->user();

        if( $user->hasRole('admin') ) {
            $permissions = Permissions::select('id', 'name', 'action', 'created_at')->where('name', '!=', 'admin.acl.roles')->get();
        } elseif( $user->isSuperAdmin() ) {
            $permissions = Permissions::select('id', 'name', 'action', 'created_at')->get();
        } else {
            $permissions = collect([]);
        }

        $permissions = $permissions->sortBy('name')->groupBy('name');

        return json_encode($permissions);
    }

}
