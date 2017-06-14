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
    public function adminIndex()
    {
        $config = [
            'title'   => trans('HCACL::acl_permissions.page_title'),
            'listURL' => route('admin.api.acl.permissions'),
            'headers' => $this->getAdminListHeader(),
        ];

        $config['actions'][] = 'search';

        return hcview('HCCoreUI::admin.content.list', ['config' => $config]);
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
    protected function createQuery(array $select = null)
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
        $list = $this->search($list);

        // ordering data
        $list = $this->orderData($list, $select);

        return $list;
    }

    /**
     * List search elements
     * @param Builder $query
     * @param string $phrase
     * @return Builder
     */
    protected function searchQuery(Builder $query, string $phrase)
    {
        return $query->where(function (Builder $query) use ($phrase) {
                $query->where('name', 'LIKE', '%' . $phrase . '%')
                    ->orWhere('controller', 'LIKE', '%' . $phrase . '%')
                    ->orWhere('action', 'LIKE', '%' . $phrase . '%');
            });
    }
}
