<?php

namespace interactivesolutions\honeycombacl\app\http\controllers\acl;

use Illuminate\Database\Eloquent\Builder;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombacl\app\models\acl\Permissions;

class PermissionsController extends HCBaseController
{

    /**
     * Returning configured admin view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminView()
    {
        $config = [
            'title'   => trans('HCACL::acl_permissions.page_title'),
            'listURL' => route('admin.api.acl.permissions'),
            'headers' => $this->getAdminListHeader(),
        ];

        if ($this->user()->can('interactivesolutions_honeycomb_acl_acl_permissions_search'))
            $config['actions'][] = 'search';

        return view('HCCoreUI::admin.content.list', ['config' => $config]);
    }

    /**
     * Creating Admin List Header based on Main Table
     *
     * @return array
     */
    public function getAdminListHeader()
    {
        return [
            'name'       => [
                "type"  => "text",
                "label" => trans('HCACL::acl_permissions.name'),
            ],
            'controller' => [
                "type"  => "text",
                "label" => trans('HCACL::acl_permissions.controller'),
            ],
            'action'     => [
                "type"  => "text",
                "label" => trans('HCACL::acl_permissions.action'),
            ],
        ];
    }

    /**
     * Creating data query
     *
     * @param array $select
     * @return mixed
     */
    public function createQuery(array $select = null)
    {
        $with = [];

        if ($select == null)
            $select = Permissions::getFillableFields();

        $list = Permissions::with($with)->select($select)
            // add filters
            ->where(function ($query) use ($select) {
                $query = $this->getRequestParameters($query, $select);
            });

        // enabling check for deleted
        $list = $this->checkForDeleted($list);

        // add search items
        $list = $this->listSearch($list);

        // ordering data
        $list = $this->orderData($list, $select);

        return $list;
    }

    /**
     * Creating data list
     * @return mixed
     */
    public function pageData()
    {
        return $this->createQuery()->paginate($this->recordsPerPage);
    }

    /**
     * Creating data list based on search
     * @return mixed
     */
    public function search()
    {
        if (!request('q'))
            return [];

        //TODO set limit to start search

        return $this->list();
    }

    /**
     * Creating data list
     * @return mixed
     */
    public function list()
    {
        return $this->createQuery()->get();
    }

    /**
     * List search elements
     * @param $list
     * @return mixed
     */
    protected function listSearch(Builder $list)
    {
        if (request()->has('q')) {
            $parameter = request()->input('q');

            $list = $list->where(function ($query) use ($parameter) {
                $query->where('name', 'LIKE', '%' . $parameter . '%')
                    ->orWhere('controller', 'LIKE', '%' . $parameter . '%')
                    ->orWhere('action', 'LIKE', '%' . $parameter . '%');
            });
        }

        return $list;
    }
}
