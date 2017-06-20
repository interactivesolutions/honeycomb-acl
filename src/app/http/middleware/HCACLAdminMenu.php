<?php

namespace interactivesolutions\honeycombacl\app\http\middleware;

use Artisan;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class HCACLAdminMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if( auth()->check() ) {
            if( $request->segment(1) == config('hc.admin_url') ) {
                if( ! Cache::has('hc-admin-menu') ) {
                    Artisan::call('hc:admin-menu');
                }

                // get menu items from cache
                $menu = Cache::get('hc-admin-menu');

                // filter available menu items
                $menu = $this->filterAvailableMenuItems($menu);

                // format set menu items which have parent path to their parent as child
                $menu = $this->buildMenuTree($menu);

                // sort menu
                $menu = $this->sortByAsc($menu);

                // add admin menu as global variable in blades
                view()->share('adminMenu', $menu);
            }
        }

        return $next($request);
    }

    /**
     * Filters admins menu by role
     *
     * @param $menuItems
     * @return array
     */
    private function filterAdminMenuHolder(array $menuItems)
    {
        $user = auth()->user();

        if( ! is_null($menuItems) ) {
            foreach ( $menuItems as $key => &$item ) {
                if( array_key_exists('aclPermission', $item) && $user->can($item['aclPermission']) ) {
                    // user has access to this menu item

                    if( isset($item['children']) ) {
                        //has children
                        $filteredItems = $this->filterAdminMenuHolder($item['children']);

                        array_forget($item, 'children');

                        if( ! empty($filteredItems) ) {
                            $item['children'] = $filteredItems;
                        }
                    }

                } else {
                    // user doesn't have access to this menu item

                    // unset item
                    array_forget($menuItems, $key);

                    if( isset($item['children']) ) {
                        // has children
                        $filteredItems = $this->filterAdminMenuHolder($item['children']);

                        if( ! empty($filteredItems) ) {
                            $menuItems = array_merge($menuItems, $filteredItems);
                        }
                    }
                }
            }
        }

        return $menuItems;
    }

    /**
     * Sort admin menu
     *
     * @param $adminMenu
     * @return array
     */
    private function sortByAsc(array $adminMenu)
    {
        if( is_null($adminMenu) ) {
            return $adminMenu;
        }

        foreach ( $adminMenu as &$menuItem ) {
            if( isset($menuItem['children']) && isset($menuItem['children']) ) {
                $menuItem['children'] = collect($menuItem['children'])->sortBy('route')->values()->toArray();
            }
        }

        return collect($adminMenu)->sortBy('route', SORT_LOCALE_STRING)->values()->toArray();
    }

    /**
     * Build menu tree
     *
     * @param array $elements
     * @param string $parentRoute
     * @return array
     */
    private function buildMenuTree(array $elements, $parentRoute = '')
    {
        $branch = [];

        foreach ( $elements as $element ) {
            if( array_get($element, 'parent') == $parentRoute ) {

                $children = $this->buildMenuTree($elements, $element['route']);

                if( $children ) {
                    $element['children'] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * Filter acl permissions
     *
     * @param $menus
     * @return mixed
     */
    private function filterAvailableMenuItems($menus)
    {
        foreach ( $menus as $key => $menu ) {
            if( ! array_key_exists('aclPermission', $menu) || auth()->user()->cannot($menu['aclPermission']) ) {
                unset($menus[$key]);
            }
        }

        return array_values($menus);
    }
}