<?php

namespace interactivesolutions\honeycombacl\app\http\controllers\users;

use Illuminate\Database\Eloquent\Builder;
use interactivesolutions\honeycombacl\app\models\users\HCGroups;
use interactivesolutions\honeycombacl\app\validators\users\HCGroupsValidator;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;

class HCGroupsController extends HCBaseController
{

    //TODO recordsPerPage setting

    /**
     * Returning configured admin view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminIndex ()
    {
        $config = [
            'title'       => trans ('HCACL::users_groups.page_title'),
            'listURL'     => route ('admin.api.routes.users.groups'),
            'newFormUrl'  => route ('admin.api.form-manager', ['users-groups-new']),
            'editFormUrl' => route ('admin.api.form-manager', ['users-groups-edit']),
            'imagesUrl'   => route ('resource.get', ['/']),
            'headers'     => $this->getAdminListHeader (),
        ];

        if (auth ()->user ()->can ('interactivesolutions_honeycomb_acl_routes_users_groups_create'))
            $config['actions'][] = 'new';

        if (auth ()->user ()->can ('interactivesolutions_honeycomb_acl_routes_users_groups_update')) {
            $config['actions'][] = 'update';
            $config['actions'][] = 'restore';
        }

        if (auth ()->user ()->can ('interactivesolutions_honeycomb_acl_routes_users_groups_delete'))
            $config['actions'][] = 'delete';

        $config['actions'][] = 'search';
        $config['filters']   = $this->getFilters ();

        return hcview ('HCCoreUI::admin.content.list', ['config' => $config]);
    }

    /**
     * Creating Admin List Header based on Main Table
     *
     * @return array
     */
    public function getAdminListHeader ()
    {
        return [
            'label'      => [
                "type"  => "text",
                "label" => trans ('HCACL::users_groups.label'),
            ],
            'creator_id' => [
                "type"  => "text",
                "label" => trans ('HCACL::users_groups.creator_id'),
            ],

        ];
    }

    /**
     * Generating filters required for admin view
     *
     * @return array
     */
    public function getFilters ()
    {
        $filters = [];

        return $filters;
    }

    /**
     * Create item
     *
     * @return mixed
     */
    protected function __apiStore ()
    {
        $data = $this->getInputData ();

        $record = HCGroups::create (array_get ($data, 'record'));
        $record->users()->sync(array_get($data, 'users'));

        return $this->apiShow ($record->id);
    }

    /**
     * Getting user data on POST call
     *
     * @return mixed
     */
    protected function getInputData ()
    {
        (new HCGroupsValidator())->validateForm ();

        $_data = request ()->all ();

        if (array_has ($_data, 'id'))
            array_set ($data, 'record.id', array_get ($_data, 'id'));

        array_set ($data, 'record.label', array_get ($_data, 'label'));
        array_set ($data, 'record.creator_id', auth()->id());

        array_set ($data, 'users', array_get ($_data, 'users'));

        return $data;
    }

    /**
     * Getting single record
     *
     * @param $id
     * @return mixed
     */
    public function apiShow (string $id)
    {
        $with = ['users'];

        $select = HCGroups::getFillableFields ();

        $record = HCGroups::with ($with)
                          ->select ($select)
                          ->where ('id', $id)
                          ->firstOrFail ();

        return $record;
    }

    /**
     * Updates existing item based on ID
     *
     * @param $id
     * @return mixed
     */
    protected function __apiUpdate (string $id)
    {
        $record = HCGroups::findOrFail ($id);

        $data = $this->getInputData ();

        $record->update (array_get ($data, 'record', []));
        $record->users()->sync(array_get($data, 'users'));

        return $this->apiShow ($record->id);
    }

    /**
     * Updates existing specific items based on ID
     *
     * @param string $id
     * @return mixed
     */
    protected function __apiUpdateStrict (string $id)
    {
        HCGroups::where ('id', $id)->update (request ()->all ());

        return $this->apiShow ($id);
    }

    /**
     * Delete records table
     *
     * @param $list
     * @return mixed
     */
    protected function __apiDestroy (array $list)
    {
        HCGroups::destroy ($list);

        return hcSuccess ();
    }

    /**
     * Delete records table
     *
     * @param $list
     * @return mixed
     */
    protected function __apiForceDelete (array $list)
    {
        HCGroups::onlyTrashed ()->whereIn ('id', $list)->forceDelete ();

        return hcSuccess ();
    }

    /**
     * Restore multiple records
     *
     * @param $list
     * @return mixed
     */
    protected function __apiRestore (array $list)
    {
        HCGroups::whereIn ('id', $list)->restore ();

        return hcSuccess ();
    }

    /**
     * Creating data query
     *
     * @param array $select
     * @return mixed
     */
    protected function createQuery (array $select = null)
    {
        $with = ['users'];

        if ($select == null)
            $select = HCGroups::getFillableFields ();

        $list = HCGroups::with ($with)->select ($select)
            // add filters
                        ->where (function ($query) use ($select) {
                $query = $this->getRequestParameters ($query, $select);
            });

        // enabling check for deleted
        $list = $this->checkForDeleted ($list);

        // add search items
        $list = $this->search ($list);

        // ordering data
        $list = $this->orderData ($list, $select);

        return $list;
    }

    /**
     * List search elements
     * @param Builder $query
     * @param string $phrase
     * @return Builder
     */
    protected function searchQuery (Builder $query, string $phrase)
    {
        return $query->where (function (Builder $query) use ($phrase) {
            $query->where ('label', 'LIKE', '%' . $phrase . '%')
                  ->orWhere ('creator_id', 'LIKE', '%' . $phrase . '%');
        });
    }
}
