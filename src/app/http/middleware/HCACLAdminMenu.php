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
                $menuA = $this->filterAvailableMenuItems($menu);

                // format set menu items which have parent path to their parent as child
                $menu = $this->buildMenuTree($menuA);

                // find without normal parent
                $withoutExistingParent = [];

                foreach ( $menuA as $key => $item ) {
                    if( array_key_exists('parent', $item) && ! $this->existInArray($menu, $item['parent']) ) {
                        $withoutExistingParent[] = $item;
                    }
                }

                // sort menu
                $menu = $this->sortByAsc(array_merge($menu, $this->formatWithIncorrectMenu($withoutExistingParent)));

                // add admin menu as global variable in blades
                view()->share('adminMenu', $menu);
            }
        }

        return $next($request);
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

    /**
     * Check if exists in array
     *
     * @param $items
     * @param $routeName
     * @return bool
     */
    private function existInArray($items, $routeName)
    {
        foreach ( $items as $item ) {

            if( $item['route'] == $routeName ) {
                return true;
            }

            if( array_key_exists('children', $item) ) {
                $found = $this->existInArray($item['children'], $routeName);

                if( $found ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Add child menu items to their parents. Only two levels
     *
     * @param $menu
     * @return array
     */
    private function formatWithIncorrectMenu($menu)
    {
        $children = [];

        if( ! empty($menu) ) {
            foreach ( $menu as $key => $menuItem ) {
                if( array_key_exists('parent', $menuItem) ) {

                    $children[] = $menuItem;

                    array_forget($menu, $key);
                }
            }
        }

        if( ! empty($children) ) {
            foreach ( $children as $child ) {
                $parentFound = false;

                foreach ( $menu as &$menuItem ) {

                    if( $child['parent'] == $menuItem['route'] ) {
                        $menuItem['children'][] = $child;
                        $parentFound = true;

                        continue;
                    }
                }

                if( ! $parentFound ) {
                    $menu[] = $child;
                }
            }
        }

        return array_values($menu);
    }
}