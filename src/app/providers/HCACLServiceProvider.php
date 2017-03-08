<?php

namespace interactivesolutions\honeycombacl\app\providers;

use Cache;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use interactivesolutions\honeycombacl\app\console\commands\HCForms;
use interactivesolutions\honeycombacl\app\console\commands\HCPermissions;
use interactivesolutions\honeycombacl\app\console\commands\HCAdminMenu;
use interactivesolutions\honeycombacl\app\http\middleware\HCACLAdminMenu;
use interactivesolutions\honeycombacl\app\http\middleware\HCACLAuthenticate;
use interactivesolutions\honeycombacl\app\http\middleware\HCACLPermissionsMiddleware;

class HCACLServiceProvider extends ServiceProvider
{
    /**
     * Register commands
     *
     * @var array
     */
    protected $commands = [
        HCPermissions::class,
        HCAdminMenu::class,
        HCForms::class,
    ];

    protected $namespace = 'interactivesolutions\honeycombacl\app\http\controllers';

    /**
     * Bootstrap the application services.
     * @param GateContract $gate
     * @param Router $router
     */
    public function boot (GateContract $gate, Router $router)
    {
        // register artisan commands
        $this->commands ($this->commands);

        // loading views
        $this->loadViewsFrom (__DIR__ . '/../../resources/views', 'HCACL');

        // loading translations
        $this->loadTranslationsFrom (__DIR__ . '/../../resources/lang', 'HCACL');

        // registering elements to publish
        $this->registerPublishElements ();

        // registering helpers
        $this->registerHelpers ();

        // registering routes
        $this->registerRoutes ();

        // registering middleware
        $this->registerMiddleware ($gate, $router);
    }

    /**
     * Register helper function
     */
    private function registerHelpers ()
    {
        $filePath = __DIR__ . '/../http/helpers.php';

        if (\File::isFile ($filePath))
            require_once $filePath;
    }

    /**
     *  Registering all vendor items which needs to be published
     */
    private function registerPublishElements ()
    {
        $directory = __DIR__ . '/../../database/migrations/';

        // Publish your migrations
        if (file_exists ($directory))
            $this->publishes ([
                __DIR__ . '/../../database/migrations/' => database_path ('/migrations'),
            ], 'migrations');

        $directory = __DIR__ . '/../public';

        // Publishing assets
        if (file_exists ($directory))
            $this->publishes ([
                __DIR__ . '/../public' => public_path ('honeycomb'),
            ], 'public');
    }

    /**
     * Registering routes
     */
    private function registerRoutes ()
    {
        $filePath = __DIR__ . '/../../app/honeycomb/routes.php';

        if ($filePath)
            \Route::group (['namespace' => $this->namespace], function ($router) use ($filePath) {
                require $filePath;
            });
    }

    /**
     * @param $gate
     * @param $router
     */
    private function registerMiddleware (GateContract $gate, Router $router)
    {
        $this->registerACLPermissions ($gate);
        $router->middleware ('acl', HCACLPermissionsMiddleware::class);
        $router->middleware ('auth', HCACLAuthenticate::class);
        $router->pushMiddleWareToGroup ('web', HCACLAdminMenu::class);
    }

    /**
     * Register acl permissions
     *
     * @param $gate
     */
    protected function registerACLPermissions (GateContract $gate)
    {
        $gate->before (function ($user, $ability) {
            if ($user->isSuperAdmin ()) {
                return true;
            }
        });

        $permissions = getHCPermissions (true);

        if (!is_null ($permissions)) {
            foreach ($permissions as $permission) {
                $gate->define ($permission->action, function ($user) use ($permission) {
                    return $user->hasPermission ($permission);
                });
            }
        }
    }
}