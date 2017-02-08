<?php

namespace interactivesolutions\honeycombacl\providers;

use Cache;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use interactivesolutions\honeycombacl\console\commands\GenerateACLPermissions;
use interactivesolutions\honeycombacl\console\commands\GenerateAdminMenu;
use interaktyvussprendimai\ocv3acl\http\middleware\HCACLPermissionsMiddleware;

class HCACLServiceProvider extends ServiceProvider
{
    /**
     * Register commands
     *
     * @var array
     */
    protected $commands = [
        GenerateACLPermissions::class,
        GenerateAdminMenu::class
    ];

    protected $namespace = 'interactivesolutions\honeycombacl\http\controllers';

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
        $filePath = __DIR__ . '/../Http/helpers.php';

        if (\File::isFile ($filePath)) {
            require_once $filePath;
        }
    }

    /**
     *  Registering all vendor items which needs to be published
     */
    private function registerPublishElements ()
    {
        // Publish your migrations
        $this->publishes ([
            __DIR__ . '/../../database/migrations/' => database_path ('/migrations'),
        ], 'migrations');

        // Publishing assets
        $this->publishes ([
            __DIR__ . '/../public' => public_path ('honeycomb'),
        ], 'public');
    }

    /**
     * Registering routes
     */
    private function registerRoutes ()
    {
        \Route::group (['namespace' => $this->namespace], function ($router) {
            require __DIR__ . '/../../app/honeycomb/routes.php';
        });
    }

    /**
     * @param $gate
     * @param $router
     */
    private function registerMiddleware ($gate, $router)
    {
        $this->registerACLPermissions($gate);
        $router->middleware ('acl', HCACLPermissionsMiddleware::class);
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

        $permissions = getHCPermissions(true);

        if (!is_null ($permissions)) {
            foreach ($permissions as $permission) {
                $gate->define ($permission->action, function ($user) use ($permission) {
                    return $user->hasPermission ($permission);
                });
            }
        }
    }
}


