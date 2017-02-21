<?php

namespace interactivesolutions\honeycombacl\http\middleware;

use Artisan;
use Closure;
use Illuminate\Support\Facades\Cache;

class HCACLAdminMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle ($request, Closure $next)
    {
        if (auth ()->check ()) {
            if ($request->segment (1) == 'admin') {
                if (!Cache::has ('hc-admin-menu'))
                    Artisan::call ('hc:admin-menu');

                // get menu items from cache
                $menu = Cache::get ('hc-admin-menu');

                // format set menu items which have parent path to their parent as child
                $menu = $this->formatParentMenu ($menu);

                //filter and sort admin menu
                $menu = $this->filterAdminMenuHolder ($menu);

                // sort menu
                $menu = $this->sortByAsc ($menu);

                // add admin menu as global variable in blades
                view ()->share ('adminMenu', $menu);
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
    private function filterAdminMenuHolder ($menuItems)
    {
        $user = auth ()->user ();

        if (!is_null ($menuItems)) {
            foreach ($menuItems as $key => &$item) {
                if (array_key_exists ('aclPermission', $item) && $user->can ($item['aclPermission'])) {
                    // user has access to this menu item

                    if (isset($item['children'])) {
                        //has children
                        $filteredItems = $this->filterAdminMenuHolder ($item['children']);

                        array_forget ($item, 'children');

                        if (!empty($filteredItems)) {
                            $item['children'] = $filteredItems;
                        }
                    }

                } else {
                    // user doesn't have access to this menu item

                    // unset item
                    array_forget ($menuItems, $key);

                    if (isset($item['children'])) {
                        // has children
                        $filteredItems = $this->filterAdminMenuHolder ($item['children']);

                        if (!empty($filteredItems)) {
                            $menuItems = array_merge ($menuItems, $filteredItems);
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
    private function sortByAsc ($adminMenu)
    {
        if (is_null ($adminMenu)) {
            return $adminMenu;
        }

        foreach ($adminMenu as &$menuItem) {
            if (isset($menuItem['children']) && isset($menuItem['children'])) {
                $menuItem['children'] = collect ($menuItem['children'])->sortBy ('path')->values ()->toArray ();
            }
        }

        return collect ($adminMenu)->sortBy ('path', SORT_LOCALE_STRING)->values ()->toArray ();
    }

    /**
     * Add child menu items to their parents
     *
     * @param $menu
     * @return array
     */
    private function formatParentMenu ($menu)
    {
        $children = [];

        if (!empty($menu)) {
            foreach ($menu as $key => $menuItem) {
                if (array_key_exists ('parent', $menuItem)) {

                    $children[] = $menuItem;

                    array_forget ($menu, $key);
                }
            }
        }

        if (!empty($children)) {
            foreach ($children as $child) {
                $parentFound = false;

                foreach ($menu as &$menuItem) {

                    if ($child['parent'] == $menuItem['path']) {
                        $menuItem['children'][] = $child;
                        $parentFound = true;

                        continue;
                    }
                }

                if (!$parentFound) {
                    $menu[] = $child;
                }
            }
        }

        return array_values ($menu);
    }

}