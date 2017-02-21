<?php namespace interactivesolutions\honeycombacl\http\controllers\acl;

use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombacl\models\acl\Permissions;
use interactivesolutions\honeycombacl\validators\acl\PermissionsValidator;

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
            'title'       => trans('HCACL::acl_permissions.page_title'),
            'listURL'     => route('admin.api.acl.permissions'),
            'headers'     => $this->getAdminListHeader(),
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
            'name'     => [
                "type"  => "text",
                "label" => trans('HCACL::acl_permissions.name'),
                ],
            'controller'     => [
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
    * @return mixed
    */
    public function listData()
    {
        $with = [];
        $select = Permissions::getFillableFields();

        $list = Permissions::with($with)->select($select)
        // add filters
        ->where(function ($query) use ($select) {
            $query = $this->getRequestParameters($query, $select);
        });

        $list = $this->checkForDeleted($list);

        // add search items
        $list = $this->listSearch($list);

        $list = $this->orderData($list, $select);

        return $list->paginate($this->recordsPerPage)->toArray();
    }

    /**
    * List search elements

    * @param $list
    * @return mixed
    */
    protected function listSearch($list)
    {
        if(request()->has('q'))
        {
            $parameter = request()->input('q');

            $list = $list->where(function ($query) use ($parameter)
            {
                $query->where('name', 'LIKE', '%' . $parameter . '%')
                      ->orWhere('controller', 'LIKE', '%' . $parameter . '%')
                      ->orWhere('action', 'LIKE', '%' . $parameter . '%');
            });
        }

        return $list;
    }
}
