<?php namespace interactivesolutions\honeycombacl\app\http\controllers\acl;

use Illuminate\Database\Eloquent\Builder;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombacl\app\models\acl\Roles;
use interactivesolutions\honeycombacl\app\validators\acl\RolesValidator;

class RolesController extends HCBaseController
{

    /**
     * Returning configured admin view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminIndex()
    {
        $config = [
            'title'       => trans('HCACL::acl_roles.page_title'),
            'listURL'     => route('admin.api.acl.roles'),
            'newFormUrl'  => route('admin.api.form-manager', ['acl-roles-new']),
            'editFormUrl' => route('admin.api.form-manager', ['acl-roles-edit']),
            //    'imagesUrl'   => route ('resource.get', ['/']),
            'headers'     => $this->getAdminListHeader(),
        ];

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_acl_roles_create'))
            $config['actions'][] = 'new';

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_acl_roles_update')) {
            $config['actions'][] = 'update';
            $config['actions'][] = 'restore';
        }

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_acl_roles_delete'))
            $config['actions'][] = 'delete';

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_acl_roles_search'))
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
            'name' => [
                "type"  => "text",
                "label" => trans('HCACL::acl_roles.name'),
            ],
            'slug' => [
                "type"  => "text",
                "label" => trans('HCACL::acl_roles.slug'),
            ],
        ];
    }

    /**
     * Create item
     *
     * @param array|null $data
     * @return mixed
     */
    protected function __apiStore(array $data = null)
    {
        if (is_null($data))
            $data = $this->getInputData();

        $record = Roles::create(array_get($data, 'record'));

        return $this->apiShow($record->id);
    }

    /**
     * Updates existing item based on ID
     *
     * @param $id
     * @return mixed
     */
    protected function __apiUpdate(string $id)
    {
        $record = Roles::findOrFail($id);

        //TODO read request parameters only once fo getting data and validating it
        $data = $this->getInputData();

        $record->update(array_get($data, 'record'));

        return $this->apiShow($record->id);
    }

    /**
     * Delete records table
     *
     * @param $list
     * @return mixed|void
     */
    protected function __apiDestroy(array $list)
    {
        Roles::destroy($list);
    }

    /**
     * Delete records table
     *
     * @param $list
     * @return mixed|void
     */
    protected function __apiForceDelete(array $list)
    {
        Roles::onlyTrashed()->whereIn('id', $list)->forceDelete();
    }

    /**
     * Restore multiple records
     *
     * @param $list
     * @return mixed|void
     */
    protected function __apiRestore(array $list)
    {
        Roles::whereIn('id', $list)->restore();
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
            $select = Roles::getFillableFields();

        $list = Roles::with($with)->select($select)
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
     * @param $list
     * @return mixed
     */
    protected function listSearch(Builder $list)
    {
        if (request()->has('q')) {
            $parameter = request()->input('q');

            $list = $list->where(function ($query) use ($parameter) {
                $query->where('name', 'LIKE', '%' . $parameter . '%')
                    ->orWhere('slug', 'LIKE', '%' . $parameter . '%');
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
        (new RolesValidator())->validateForm();

        $_data = request()->all();

        $data = [];
        array_set($data, 'record.name', array_get($_data, 'name'));
        array_set($data, 'record.slug', array_get($_data, 'slug'));

        return $data;
    }

    /**
     * Getting single record
     *
     * @param $id
     * @return mixed
     */
    public function apiShow(string $id)
    {
        $with = [];

        $select = Roles::getFillableFields();

        $record = Roles::with($with)
            ->select($select)
            ->where('id', $id)
            ->firstOrFail();

        return $record;
    }
}
