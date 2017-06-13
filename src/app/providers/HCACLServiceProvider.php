<?php

namespace interactivesolutions\honeycombacl\app\providers;

use Cache;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Routing\Router;
use interactivesolutions\honeycombacl\app\console\commands\HCAdminURL;
use interactivesolutions\honeycombacl\app\console\commands\HCForms;
use interactivesolutions\honeycombacl\app\console\commands\HCPermissions;
use interactivesolutions\honeycombacl\app\console\commands\HCAdminMenu;
use interactivesolutions\honeycombacl\app\console\commands\HCSuperAdmin;
use interactivesolutions\honeycombacl\app\http\middleware\HCACLAdminMenu;
use interactivesolutions\honeycombacl\app\http\middleware\HCACLAuthenticate;
use interactivesolutions\honeycombacl\app\http\middleware\HCACLPermissionsMiddleware;
use interactivesolutions\honeycombacl\app\models\HCUsers;
use interactivesolutions\honeycombcore\providers\HCBaseServiceProvider;

class HCACLServiceProvider extends HCBaseServiceProvider
{
    protected $homeDirectory = __DIR__;

    /**
     * Console commands
     *
     * @var array
     */
    protected $commands = [
        HCPermissions::class,
        HCAdminMenu::class,
        HCForms::class,
        HCAdminURL::class,
        HCSuperAdmin::class
    ];

    /**
     * Namespace
     *
     * @var string
     */
    protected $namespace = 'interactivesolutions\honeycombacl\app\http\controllers';

    /**
     * Provider facade name
     *
     * @var string
     */
    public $serviceProviderNameSpace = 'HCACL';

    /**
     * @param Router $router
     */
    protected function registerRouterItems(Router $router)
    {
        parent::registerRouterItems($router);

        $router->middleware ('acl', HCACLPermissionsMiddleware::class);
        $router->middleware ('auth', HCACLAuthenticate::class);
        $router->pushMiddleWareToGroup ('web', HCACLAdminMenu::class);
    }

    /**
     * Register acl permissions
     *
     * @param $gate
     */
    protected function registerGateItems (Gate $gate)
    {
        parent::registerGateItems($gate);

        $gate->before (function (HCUsers $user, $ability) {
            if ($user->isSuperAdmin ())
                return true;

            return false;
        });

        $permissions = getHCPermissions ();

        if (!is_null ($permissions)) {
            foreach ($permissions as $permission) {
                $gate->define ($permission->action, function (HCUsers $user) use ($permission) {
                    return $user->hasPermission ($permission);
                });
            }
        }
    }
}