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
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace InteractiveSolutions\HoneycombAcl\Http\Controllers\Users;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use InteractiveSolutions\HoneycombAcl\Models\Users\HCGroups;
use InteractiveSolutions\HoneycombAcl\Models\Users\HCGroupsUsers;
use InteractiveSolutions\HoneycombAcl\Validators\Users\HCGroupsValidator;
use InteractiveSolutions\HoneycombCore\Http\Controllers\HCBaseController;

/**
 * Class HCGroupsController
 * @package InteractiveSolutions\HoneycombAcl\Http\Controllers\Users
 */
class HCGroupsController extends HCBaseController
{

    //TODO recordsPerPage setting

    /**
     * Returning configured admin view
     *
     * @return View
     */
    public function adminIndex(): View
    {
        $config = [
            'title' => trans('HCACL::users_groups.page_title'),
            'listURL' => route('admin.api.routes.users.groups'),
            'newFormUrl' => route('admin.api.form-manager', ['users-groups-new']),
            'editFormUrl' => route('admin.api.form-manager', ['users-groups-edit']),
            'imagesUrl' => route('resource.get', ['/']),
            'headers' => $this->getAdminListHeader(),
        ];

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_routes_users_groups_create')) {
            $config['actions'][] = 'new';
        }

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_routes_users_groups_update')) {
            $config['actions'][] = 'update';
            $config['actions'][] = 'restore';
        }

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_routes_users_groups_delete')) {
            $config['actions'][] = 'delete';
        }

        $config['actions'][] = 'search';
        $config['filters'] = $this->getFilters();

        return hcview('HCCoreUI::admin.content.list', ['config' => $config]);
    }

    /**
     * Creating Admin List Header based on Main Table
     *
     * @return array
     */
    public function getAdminListHeader(): array
    {
        return [
            'label' => [
                "type" => "text",
                "label" => trans('HCACL::users_groups.label'),
            ],
            'creator_id' => [
                "type" => "text",
                "label" => trans('HCACL::users_groups.creator_id'),
            ],

        ];
    }

    /**
     * Generating filters required for admin view
     *
     * @return array
     */
    public function getFilters(): array
    {
        $filters = [];

        return $filters;
    }

    /**
     * Create item
     * @return mixed
     * @throws \Exception
     */
    protected function __apiStore()
    {
        $data = $this->getInputData();

        $record = HCGroups::create(array_get($data, 'record'));
        $record->users()->sync(array_get($data, 'users'));

        return $this->apiShow($record->id);
    }

    /**
     * Getting user data on POST call
     * @return array
     * @throws \Exception
     */
    protected function getInputData(): array
    {
        (new HCGroupsValidator())->validateForm();

        $data = [];
        $_data = request()->all();

        if (array_has($_data, 'id')) {
            array_set($data, 'record.id', array_get($_data, 'id'));
        }

        array_set($data, 'record.label', array_get($_data, 'label'));
        array_set($data, 'record.creator_id', auth()->id());
        array_set($data, 'users', array_get($_data, 'users'));

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
        $with = ['users'];

        $select = HCGroups::getFillableFields();

        $record = HCGroups::with($with)
            ->select($select)
            ->where('id', $id)
            ->firstOrFail();

        return $record;
    }

    /**
     * Updates existing item based on ID
     *
     * @param string $id
     * @return mixed
     * @throws \Exception
     */
    protected function __apiUpdate(string $id)
    {
        $record = HCGroups::findOrFail($id);

        $data = $this->getInputData();

        $record->update(array_get($data, 'record', []));
        $record->users()->sync(array_get($data, 'users'));

        return $this->apiShow($record->id);
    }

    /**
     * Updates existing specific items based on ID
     *
     * @param string $id
     * @return mixed
     */
    protected function __apiUpdateStrict(string $id)
    {
        HCGroups::where('id', $id)->update(request()->all());

        return $this->apiShow($id);
    }

    /**
     * Delete records table
     *
     * @param array $list
     * @return array
     */
    protected function __apiDestroy(array $list): array
    {
        HCGroups::destroy($list);

        return hcSuccess();
    }

    /**
     * Delete records table
     *
     * @param array $list
     * @return array
     */
    protected function __apiForceDelete(array $list): array
    {
        HCGroupsUsers::whereIn('group_id', $list)->forceDelete();
        HCGroups::onlyTrashed()->whereIn('id', $list)->forceDelete();

        return hcSuccess();
    }

    /**
     * Restore multiple records
     *
     * @param array $list
     * @return array
     */
    protected function __apiRestore(array $list): array
    {
        HCGroups::whereIn('id', $list)->restore();

        return hcSuccess();
    }

    /**
     * Creating data query
     *
     * @param array $select
     * @return mixed
     */
    protected function createQuery(array $select = null)
    {
        $with = ['users'];

        if ($select == null) {
            $select = HCGroups::getFillableFields();
        }

        $list = HCGroups::with($with)->select($select)
            // add filters
            ->where(function($query) use ($select) {
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
    protected function searchQuery(Builder $query, string $phrase): Builder
    {
        return $query->where(function(Builder $query) use ($phrase) {
            $query->where('label', 'LIKE', '%' . $phrase . '%')
                ->orWhere('creator_id', 'LIKE', '%' . $phrase . '%');
        });
    }
}
