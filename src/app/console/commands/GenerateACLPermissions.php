<?php namespace interactivesolutions\honeycombacl\console\commands;

use Cache;
use Illuminate\Filesystem\Filesystem;
use interactivesolutions\honeycombacl\models\acl\Permissions;
use interactivesolutions\honeycombacl\models\acl\Roles;
use interactivesolutions\honeycombacl\models\acl\RolesPermissionsConnections;
use interactivesolutions\honeycombcore\commands\HCCommand;

class GenerateACLPermissions extends HCCommand
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        $this->comment ('Scanning permissions..');

        $this->scanRolesAndPermissions ();
        $this->generateRolesAndPermissions ();

        Cache::forget ('permissions');

        $this->comment ('-');
    }

    /**
     * Scans roles and permissions and create roles, permissions and roles_permissions
     */
    private function scanRolesAndPermissions ()
    {
        $files = $this->getConfigFiles ();

        if (!empty($files))
            foreach ($files as $filePath) {
                $config = json_decode (file_get_contents ($filePath), true);

                if (is_null ($config))
                    $this->info ('Invalid json file: ' . $filePath);
                else {
                    $packageName = array_get ($config, 'general.serviceProviderNameSpace');

                    if (is_null ($packageName) || $packageName == '') {
                        $this->error ('SKIPPING! Package must have a name! file: ' . $filePath);
                        continue;
                    }

                    if (array_key_exists ('acl', $config)) {
                        $this->aclData[] = [
                            'packageName' => $packageName,
                            'acl'         => array_get ($config, 'acl'),
                        ];
                    }
                }
            }
    }

    /**
     * Create roles, permissions and roles_permissions
     */
    private function generateRolesAndPermissions ()
    {
        if (!empty($this->aclData))
            foreach ($this->aclData as $acl) {
                $this->createPermissions ($acl['acl']);
                $this->createRolesPermissions ($acl['acl']);
            }
    }

    /**
     * Create permissions
     *
     * @param $aclData
     */
    private function createPermissions ($aclData)
    {
        if (array_key_exists ('permissions', $aclData)) {
            foreach ($aclData['permissions'] as $permission) {
                $this->removeDeletedPermissions ($permission);

                foreach ($permission['actions'] as $action) {
                    $permissionId = Permissions::firstOrCreate ([
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
    private function removeDeletedPermissions ($permission)
    {
        $configActions = collect ($permission['actions']);

        $actions = Permissions::where ('name', $permission['name'])->pluck ('action');

        $removedActions = $actions->diff ($configActions);

        if (!$removedActions->isEmpty ())
            foreach ($removedActions as $action)
                Permissions::deletePermission ($action);
    }

    /**
     * Creating roles permissions
     *
     * @param $aclData
     * @internal param $acl
     */
    private function createRolesPermissions ($aclData)
    {
        //TODO extend DB with "manual" option which stands if user has created this role action connection
        //TODO gather all actions
        //TODO if manual connection leave it else delete all connections

        if (array_key_exists ('rolesActions', $aclData)) {
            foreach ($aclData['rolesActions'] as $role => $actions) {
                $roleRecord = Roles::firstOrCreate (['slug' => $role, 'name' => ucfirst (str_replace (['-', '_'], ' ', $role))]);

                foreach ($actions as $action) {
                    $permission = Permissions::where ('action', $action)->first ();
                    $connection = RolesPermissionsConnections::firstOrCreate (['role_id' => $roleRecord->id, 'permission_id' => $permission->id]);
                }
            }
        }
    }
}
