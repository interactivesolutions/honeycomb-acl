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
 * E-mail: info@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace InteractiveSolutions\HoneycombAcl\Http\Controllers\Acl;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use InteractiveSolutions\HoneycombAcl\Models\Acl\Roles;
use InteractiveSolutions\HoneycombAcl\Validators\Acl\RolesValidator;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;

/**
 * Class RolesController
 * @package InteractiveSolutions\HoneycombAcl\Http\Controllers\Acl
 */
class RolesController extends HCBaseController
{

    /**
     * Returning configured admin view
     *
     * @return View
     */
    public function adminIndex(): View
    {
        $config = [
            'title' => trans('HCACL::acl_roles.page_title'),
            'listURL' => route('admin.api.acl.roles'),
            'newFormUrl' => route('admin.api.form-manager', ['acl-roles-new']),
            'editFormUrl' => route('admin.api.form-manager', ['acl-roles-edit']),
            //    'imagesUrl'   => route ('resource.get', ['/']),
            'headers' => $this->getAdminListHeader(),
        ];

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_acl_roles_create')) {
            $config['actions'][] = 'new';
        }

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_acl_roles_update')) {
            $config['actions'][] = 'update';
            $config['actions'][] = 'restore';
        }

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_acl_roles_delete')) {
            $config['actions'][] = 'delete';
        }

        $config['actions'][] = 'search';

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
            'name' => [
                "type" => "text",
                "label" => trans('HCACL::acl_roles.name'),
            ],
            'slug' => [
                "type" => "text",
                "label" => trans('HCACL::acl_roles.slug'),
            ],
        ];
    }

    /**
     * Create item
     *
     * @param array|null $data
     * @return mixed
     * @throws \Exception
     */
    protected function __apiStore(array $data = null)
    {
        if (is_null($data)) {
            $data = $this->getInputData();
        }

        $record = Roles::create(array_get($data, 'record'));

        return $this->apiShow($record->id);
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
     * @return mixed
     */
    protected function __apiDestroy(array $list)
    {
        Roles::destroy($list);

        return hcSuccess();
    }

    /**
     * Delete records table
     *
     * @param $list
     * @return mixed
     */
    protected function __apiForceDelete(array $list)
    {
        Roles::onlyTrashed()->whereIn('id', $list)->forceDelete();

        return hcSuccess();
    }

    /**
     * Restore multiple records
     *
     * @param $list
     * @return mixed
     */
    protected function __apiRestore(array $list)
    {
        Roles::whereIn('id', $list)->restore();

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
        $with = [];

        if ($select == null) {
            $select = Roles::getFillableFields();
        }

        $list = Roles::with($with)->select($select)
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
            $query->where('name', 'LIKE', '%' . $phrase . '%')
                ->orWhere('slug', 'LIKE', '%' . $phrase . '%');
        });
    }

    /**
     * Getting user data on POST call
     * @return array
     * @throws \Exception
     */
    protected function getInputData(): array
    {
        (new RolesValidator())->validateForm();

        $data = [];
        $_data = request()->all();

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
