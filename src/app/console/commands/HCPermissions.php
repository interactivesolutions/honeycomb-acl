<?php namespace interactivesolutions\honeycombacl\app\console\commands;

use interactivesolutions\honeycombacl\app\models\acl\Permissions;
use interactivesolutions\honeycombacl\app\models\acl\Roles;
use interactivesolutions\honeycombacl\app\models\acl\RolesPermissionsConnections;
use interactivesolutions\honeycombcore\commands\HCCommand;

class HCPermissions extends HCCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Go through all packages, find HoneyComb configuration file and store all permissions / roles / connections';

    /**
     * Permissions id list
     *
     * @var
     */
    private $permissionsIdList;

    /**
     * Acl data holder
     *
     * @var
     */
    private $aclData;

    /**
     * Roles list holder
     *
     * @var array
     */
    private $rolesList = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Scanning permissions..');

        $this->scanRolesAndPermissions();
        $this->generateRolesAndPermissions();

        $this->comment('-');
    }

    /**
     * Scans roles and permissions and create roles, permissions and roles_permissions
     */
    private function scanRolesAndPermissions()
    {
        $files = $this->getConfigFiles();

        if( ! empty($files) )
            foreach ( $files as $filePath ) {
                $config = json_decode(file_get_contents($filePath), true);

                if( is_null($config) )
                    $this->info('Invalid json file: ' . $filePath);
                else {
                    $packageName = array_get($config, 'general.serviceProviderNameSpace');

                    if( is_null($packageName) || $packageName == '' ) {
                        $this->error('SKIPPING! Package must have a name! file: ' . $filePath);
                        continue;
                    }

                    if( array_key_exists('acl', $config) ) {
                        $this->aclData[] = [
                            'packageName' => $packageName,
                            'acl'         => array_get($config, 'acl'),
                        ];
                    }
                }
            }
    }

    /**
     * Create roles, permissions and roles_permissions
     */
    private function generateRolesAndPermissions()
    {
        if( empty($this->aclData) ) {
            $this->error('empty roles and permissions in "generateRolesAndPermissions" method');

            return;
        }

        foreach ( $this->aclData as $acl ) {
            $this->createPermissions($acl['acl']);
            $this->createRoles($acl['acl']);
        }

        $this->createRolesPermissions($this->aclData);
    }

    /**
     * Create permissions
     *
     * @param $aclData
     */
    private function createPermissions(array $aclData)
    {
        if( array_key_exists('permissions', $aclData) ) {
            foreach ( $aclData['permissions'] as $permission ) {
                $this->removeDeletedPermissions($permission);

                foreach ( $permission['actions'] as $action ) {
                    $permissionId = Permissions::firstOrCreate([
                        'name'       => $permission['name'],
                        'controller' => $permission['controller'],
                        'action'     => $action,
                    ]);

                    $this->permissionsIdList[$action] = $permissionId->id;
                }
            }
        }
    }

    /**
     * Check if there is any deleted permission actions from config file. If it is than deleted them from role_permissions connection and from permission actions
     *
     * @param $permission
     */
    private function removeDeletedPermissions(array $permission)
    {
        $configActions = collect($permission['actions']);

        $actions = Permissions::where('name', $permission['name'])->pluck('action');

        $removedActions = $actions->diff($configActions);

        if( ! $removedActions->isEmpty() )
            foreach ( $removedActions as $action )
                Permissions::deletePermission($action);
    }

    /**
     * Create roles
     *
     * @param array $aclData
     */
    private function createRoles(array $aclData)
    {
        if( array_key_exists('rolesActions', $aclData) ) {
            foreach ( $aclData['rolesActions'] as $role => $actions ) {
                $roleRecord = Roles::firstOrCreate([
                    'slug' => $role,
                    'name' => ucfirst(str_replace(['-', '_'], ' ', $role)),
                ]);

                $this->rolesList[$roleRecord->id] = $roleRecord;
            }
        }
    }

    /**
     * Creating roles permissions
     *
     * @param $aclData
     * @internal param $acl
     */
    private function createRolesPermissions(array $aclData)
    {
        $allRolesActions = $this->extractAllActions($aclData);

        $uncheckedActionsOutput = [];

        foreach ( $this->rolesList as $roleRecord ) {
            // load current role permissions
            $roleRecord->load('permissions');

            // get current role permissions
            $currentRolePermissions = $roleRecord->permissions->pluck('action')->toArray();

            // if role already has permissions
            if( count($currentRolePermissions) ) {
                // unchecked actions
                $uncheckedActions = array_diff($allRolesActions[$roleRecord->slug], $currentRolePermissions);

                if( ! empty($uncheckedActions) ) {
                    $uncheckedActionsOutput[] = [$roleRecord->name, implode("\n", $uncheckedActions)];
                }
                
                continue;
            }


            // if role doesn't have any permissions than create it

            // get all permissions
            $permissions = Permissions::whereIn('action', $allRolesActions[$roleRecord->slug])->get();

            // sync permissions
            $roleRecord->permissions()->sync($permissions->pluck('id'));
        }

        // if role has unchecked actions than show which actions is unchecked
        if( $uncheckedActionsOutput ) {
            $this->table(['Role', 'Unchecked actions'], $uncheckedActionsOutput);
        }
    }

    /**
     * Extract all actions from roles config
     *
     * @param array $aclData
     * @return array
     */
    private function extractAllActions(array $aclData): array
    {
        $allRolesActions = [];

        // get all role actions available
        foreach ( $aclData as $acl ) {
            if( isset($acl['acl']['rolesActions']) && ! empty ($acl['acl']['rolesActions']) ) {
                foreach ( $acl['acl']['rolesActions'] as $role => $actions ) {
                    if( array_key_exists($role, $allRolesActions) ) {
                        $allRolesActions[$role] = array_merge($allRolesActions[$role], $actions);
                    } else {
                        $allRolesActions[$role] = array_merge([], $actions);
                    }
                }
            }
        }

        return $allRolesActions;
    }
}
