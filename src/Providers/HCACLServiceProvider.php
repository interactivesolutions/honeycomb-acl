<?php
/**
 * @copyright 2017 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InteractiveSolutions:
 * E-mail: info@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace InteractiveSolutions\HoneycombAcl\Providers;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Routing\Router;
use InteractiveSolutions\HoneycombAcl\Console\HCAdminURL;
use InteractiveSolutions\HoneycombAcl\Console\HCForms;
use InteractiveSolutions\HoneycombAcl\Console\HCPermissions;
use InteractiveSolutions\HoneycombAcl\Console\HCAdminMenu;
use InteractiveSolutions\HoneycombAcl\Console\HCSuperAdmin;
use InteractiveSolutions\HoneycombAcl\Http\Middleware\HCACLAdminMenu;
use InteractiveSolutions\HoneycombAcl\Http\Middleware\HCACLAuthenticate;
use InteractiveSolutions\HoneycombAcl\Http\Middleware\HCACLPermissionsMiddleware;
use InteractiveSolutions\HoneycombAcl\Http\Middleware\HCLogLastActivity;
use InteractiveSolutions\HoneycombAcl\Models\HCUsers;
use interactivesolutions\honeycombcore\providers\HCBaseServiceProvider;

/**
 * Class HCACLServiceProvider
 * @package InteractiveSolutions\HoneycombAcl\Providers
 */
class HCACLServiceProvider extends HCBaseServiceProvider
{
    /**
     * @var string
     */
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
        HCSuperAdmin::class,
    ];

    /**
     * Namespace
     *
     * @var string
     */
    protected $namespace = 'InteractiveSolutions\HoneycombAcl\Http\Controllers';

    /**
     * Provider facade name
     *
     * @var string
     */
    public $serviceProviderNameSpace = 'HCACL';

    public function boot(Gate $gate, Router $router)
    {
        parent::boot($gate, $router);

        $this->loadViewsFrom ($this->homeDirectory . '/../resources/views', $this->serviceProviderNameSpace);
        $this->loadTranslationsFrom ($this->homeDirectory . '/../resources/lang', $this->serviceProviderNameSpace);
    }

    /**
     * @param Router $router
     */
    protected function registerRouterItems(Router $router): void
    {
        parent::registerRouterItems($router);

        $router->middleware('acl', HCACLPermissionsMiddleware::class);
        $router->middleware('auth', HCACLAuthenticate::class);
        $router->pushMiddleWareToGroup('web', HCACLAdminMenu::class);
        $router->pushMiddleWareToGroup('web', HCLogLastActivity::class);
    }

    /**
     * Register acl permissions
     *
     * @param Gate $gate
     * @throws \Exception
     */
    protected function registerGateItems(Gate $gate): void
    {
        parent::registerGateItems($gate);

        $gate->before(function(HCUsers $user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        $permissions = getHCPermissions();

        if (!is_null($permissions)) {
            foreach ($permissions as $permission) {
                $gate->define($permission->action, function(HCUsers $user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }
    }

    /**
     *
     */
    protected function registerHelpers(): void
    {
        include_once $this->homeDirectory . '/../Helpers/helpers.php';
    }

    /**
     * @param Router $router
     */
    protected function registerRoutes(Router $router): void
    {
        $routes = [
            $this->modulePath('Routes/Admin/01_routes.acl.permissions.php'),
            $this->modulePath('Routes/Admin/02_routes.acl.roles.php'),
            $this->modulePath('Routes/Admin/03_routes.access.php'),
            $this->modulePath('Routes/Admin/04__routes.users.groups.php'),
            $this->modulePath('Routes/Admin/04_routes.users.php'),

            $this->modulePath('Routes/Api/01_routes.acl.permissions.php'),
            $this->modulePath('Routes/Api/02_routes.acl.roles.php'),
            $this->modulePath('Routes/Api/04__routes.users.groups.php'),
            $this->modulePath('Routes/Api/04_routes.users.php'),

            $this->modulePath('Routes/Public/01_routes.auth.php'),
            $this->modulePath('Routes/Public/02_routes.password.php'),
        ];

        foreach ($routes as $route) {
            $router->group(['namespace' => $this->namespace], function($router) use ($route) {
                require $route;
            });
        }
    }

    /**
     * @param string $path
     * @return string
     */
    private function modulePath(string $path): string
    {
        return __DIR__ . '/../' . $path;
    }
}