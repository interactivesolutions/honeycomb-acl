<?php

namespace interactivesolutions\honeycombacl\app\http\controllers;

use DB;
use Carbon\Carbon;
use HCLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use interactivesolutions\honeycombacl\app\validators\HCUsersValidator;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombacl\app\models\HCUsers;

class HCUsersController extends HCBaseController
{
    /**
     * Returning configured admin view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminIndex()
    {
        $config = [
            'title'       => trans('HCACL::users.page_title'),
            'listURL'     => route('admin.api.users'),
            'newFormUrl'  => route('admin.api.form-manager', ['users-new']),
            'editFormUrl' => route('admin.api.form-manager', ['users-edit']),
            'imagesUrl'   => route('resource.get', ['/']),
            'headers'     => $this->getAdminListHeader(),
        ];

        $config['actions'][] = 'search';

        if( auth()->user()->can('interactivesolutions_honeycomb_acl_users_create') )
            $config['actions'][] = 'new';

        if( auth()->user()->can('interactivesolutions_honeycomb_acl_users_update') ) {
            $config['actions'][] = 'update';
            $config['actions'][] = 'restore';
        }

        if( auth()->user()->can('interactivesolutions_honeycomb_acl_users_delete') )
            $config['actions'][] = 'delete';

        return hcview('HCCoreUI::admin.content.list', ['config' => $config]);
    }

    /**
     * Creating Admin List Header based on Main Table
     *
     * @return array
     */
    public function getAdminListHeader()
    {
        return [
            'email'         => [
                "type"  => "text",
                "label" => trans('HCACL::users.email'),
            ],
            'last_login'    => [
                "type"  => "text",
                "label" => trans('HCACL::users.last_login'),
            ],
            'last_activity' => [
                "type"  => "text",
                "label" => trans('HCACL::users.last_activity'),
            ],
            'activated_at'  => [
                "type"  => "text",
                "label" => trans('HCACL::users.activation.activated_at'),
            ],
        ];
    }

    /**
     * Create item
     *
     * @param array|null $data
     * @return mixed
     */
    protected function __apiStore(array $data = null)
    {
        if( is_null($data) ) {
            $data = $this->getInputData();
        }

        (new HCUsersValidator())->validateForm();

        $record = createHCUser(
            array_get($data, 'record.email'),
            array_get($data, 'roles'),
            request()->has('is_active'),
            array_get($data, 'record.password'),
            [],
            request()->has('send_welcome_email'),
            request()->has('send_password')
        );

        return $this->apiShow($record->id);
    }

    /**
     * Updates existing item based on ID
     *
     * @param $id
     * @return mixed
     */
    protected function __apiUpdate(string $id)
    {
        //TODO read request parameters only once fo getting data and validating it
        $data = $this->getInputData();

        (new HCUsersValidator())->setId($id)->validateForm();

        $record = HCUsers::findOrFail($id);

        // password changing
        if( array_get($data, 'record.password') ) {
            if( Hash::check(array_get($data, 'old_password'), $record->password) ) {
                array_set($data, 'record.password', Hash::make($data['new_password']));

                array_forget($data, ['new_password', 'old_password']);
            } else {
                return HCLog::info('USERS-003', trans('HCACL::users.errors.badOldPass'));
            }
        } else {
            array_forget($data, ['new_password', 'old_password', 'record.password']);
        }

        $record->update(array_get($data, 'record'));
        $record->assignRoles(array_get($data, 'roles'));

        // activate user if you want
        if( request()->has('is_active') && $record->isNotActivated() ) {
            $record->activate();
        }

        return $this->apiShow($record->id);
    }

    /**
     * Delete records table
     *
     * @param $list
     * @return mixed
     */
    protected function __apiDestroy(array $list)
    {
        HCUsers::destroy($list);

        return hcSuccess();
    }

    /**
     * Delete records table
     *
     * @param $list
     * @return mixed
     */
    protected function __apiForceDelete(array $list)
    {
        HCUsers::onlyTrashed()->whereIn('id', $list)->forceDelete();

        return hcSuccess();
    }

    /**
     * Restore multiple records
     *
     * @param $list
     * @return mixed
     */
    protected function __apiRestore(array $list)
    {
        HCUsers::whereIn('id', $list)->restore();

        return hcSuccess();
    }

    /**
     * Creating data query
     *
     * @param array $select
     * @return mixed
     */
    protected function createQuery(array $select = null)
    {
        $with = [];

        if( $select == null )
            $select = HCUsers::getFillableFields();

        $list = HCUsers::with($with)->select($select)
            // add filters
            ->where(function ($query) use ($select) {
                $query = $this->getRequestParameters($query, $select);
            });

        // enabling check for deleted
        $list = $this->checkForDeleted($list);

        // add search items
        $list = $this->search($list);

        // ordering data
        $list = $this->orderData($list, $select);

        return $list;
    }

    /**
     * List search elements
     * @param Builder $query
     * @param string $phrase
     * @return Builder
     */
    protected function searchQuery(Builder $query, string $phrase)
    {
        return $query->where(function (Builder $query) use ($phrase) {
            $query->where('activated_at', 'LIKE', '%' . $phrase . '%')
                ->orWhere('remember_token', 'LIKE', '%' . $phrase . '%')
                ->orWhere('last_login', 'LIKE', '%' . $phrase . '%')
                ->orWhere('last_visited', 'LIKE', '%' . $phrase . '%')
                ->orWhere('last_activity', 'LIKE', '%' . $phrase . '%');
        });
    }

    /**
     * Getting user data on POST call
     *
     * @return mixed
     */
    protected function getInputData()
    {
        $_data = request()->all();

        array_set($data, 'record.email', array_get($_data, 'email'));
        array_set($data, 'record.password', array_get($_data, 'password'));
        array_set($data, 'new_password', array_get($_data, 'new_password'));
        array_set($data, 'old_password', array_get($_data, 'old_password'));
        array_set($data, 'roles', array_get($_data, 'roles', []));

        return $data;
    }

    /**
     * Getting single record
     *
     * @param $id
     * @return mixed
     */
    public function apiShow(string $id)
    {
        $with = ['roles' => function ($query) {
            $query->select('id', 'name as label');
        }];

        $select = HCUsers::getFillableFields();

        $record = HCUsers::with($with)
            ->select($select)
            ->where('id', $id)
            ->firstOrFail();

        $record->is_active = [
            ['id' => $record->activated_at ? 1 : 0],
        ];

        return $record;
    }

}
