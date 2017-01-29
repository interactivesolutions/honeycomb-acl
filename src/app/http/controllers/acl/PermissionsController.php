<?php namespace interactivesolutions\honeycombacl\http\controllers\acl;

use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombacl\models\acl\Permissions;
use interactivesolutions\honeycombacl\forms\acl\PermissionsForm;

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
            'newFormUrl'  => route('admin.api.form-manager', ['acl-permissions-new']),
            'editFormUrl' => route('admin.api.form-manager', ['acl-permissions-edit']),
            'imagesUrl'   => route('resource.get', ['/']),
            'headers'     => $this->getAdminListHeader(),
        ];

        if ($this->user->can('interactivesolutions_honeycomb_acl_acl_permissions_create'))
            $config['actions'][] = 'new';

        if ($this->user->can('interactivesolutions_honeycomb_acl_acl_permissions_update'))
        {
            $config['actions'][] = 'update';
            $config['actions'][] = 'restore';
        }

        if ($this->user->can('interactivesolutions_honeycomb_acl_acl_permissions_delete'))
            $config['actions'][] = 'delete';

        if ($this->user->can('interactivesolutions_honeycomb_acl_acl_permissions_search'))
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
    * Create item
    *
    * @param null $data
    * @return mixed
    */
    protected function __create($data = null)
    {
        if(is_null($data))
            $data = $this->getInputData();

        (new PermissionsForm())->validateForm();

        $record = Permissions::create(array_get($data, 'record'));

        return $this->getSingleRecord($record->id);
    }

    /**
    * Updates existing item based on ID
    *
    * @param $id
    * @return mixed
    */
    protected function __update($id)
    {
        $record = Permissions::findOrFail($id);

        //TODO read request parameters only once fo getting data and validating it
        $data = $this->getInputData();
        (new PermissionsForm())->validateForm();

        $record->update(array_get($data, 'record'));

        return $this->getSingleRecord($record->id);
    }

    /**
    * Delete records table
    *
    * @param $list
    * @return mixed|void
    */
    protected function __delete(array $list)
    {
        Permissions::destroy($list);
    }

    /**
    * Delete records table
    *
    * @param $list
    * @return mixed|void
    */
    protected function __forceDelete(array $list)
    {
        Permissions::onlyTrashed()->whereIn('id', $list)->forceDelete();
    }

    /**
    * Restore multiple records
    *
    * @param $list
    * @return mixed|void
    */
    protected function __restore(array $list)
    {
        Permissions::whereIn('id', $list)->restore();
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
            $query->where($this->getRequestParameters($select));
        });

        $list = $this->checkForDeleted($list);

        // add search items
        $list = $this->listSearch($list);

        $orderData = request()->input('_order');

        if ($orderData)
            foreach($orderData as $column => $direction)
                if (strtolower($direction) == 'asc' || strtolower($direction) == 'desc')
                    $list = $list->orderBy($column, $direction);

        // setOrdering
        $list = $list->orderBy($this->field, $this->ordering);

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
                      ->orWhere('action', 'LIKE', '%' . $parameter . '%')
            });
        }

        return $list;
    }

    /**
     * Getting user data on POST call
     *
     * @return mixed
     */
    protected function getInputData()
    {
        $_data = request()->all();

        $data = [];
        array_set($data, 'record.name', array_get($_data, 'name'));
        array_set($data, 'record.controller', array_get($_data, 'controller'));
        array_set($data, 'record.action', array_get($_data, 'action'));

        return $data;
    }

    /**
     * Getting single record
     *
     * @param $id
     * @return mixed
     */
    public function getSingleRecord($id)
    {
        $with = [];

        $select = Permissions::getFillableFields();

        $record = Permissions::with($with)
            ->select($select)
            ->where('id', $id)
            ->firstOrFail();

        return $record;
    }
}
