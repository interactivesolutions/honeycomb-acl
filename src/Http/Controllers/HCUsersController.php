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

namespace InteractiveSolutions\HoneycombAcl\Http\Controllers;

use DB;
use HCLog;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use InteractiveSolutions\HoneycombAcl\Validators\HCUsersValidator;
use InteractiveSolutions\HoneycombAcl\Models\HCUsers;
use InteractiveSolutions\HoneycombCore\Http\Controllers\HCBaseController;

/**
 * Class HCUsersController
 * @package InteractiveSolutions\HoneycombAcl\Http\Controllers
 */
class HCUsersController extends HCBaseController
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * HCUsersController constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Returning configured admin view
     *
     * @return View
     */
    public function adminIndex(): View
    {
        $config = [
            'title' => trans('HCACL::users.page_title'),
            'listURL' => route('admin.api.users'),
            'newFormUrl' => route('admin.api.form-manager', ['users-new']),
            'editFormUrl' => route('admin.api.form-manager', ['users-edit']),
            'imagesUrl' => route('resource.get', ['/']),
            'headers' => $this->getAdminListHeader(),
        ];

        $config['actions'][] = 'search';

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_users_create')) {
            $config['actions'][] = 'new';
        }

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_users_update')) {
            $config['actions'][] = 'update';
            $config['actions'][] = 'restore';
        }

        if (auth()->user()->can('interactivesolutions_honeycomb_acl_users_delete')) {
            $config['actions'][] = 'delete';
        }

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
            'email' => [
                "type" => "text",
                "label" => trans('HCACL::users.email'),
            ],
            'last_login' => [
                "type" => "text",
                "label" => trans('HCACL::users.last_login'),
            ],
            'last_activity' => [
                "type" => "text",
                "label" => trans('HCACL::users.last_activity'),
            ],
            'activated_at' => [
                "type" => "text",
                "label" => trans('HCACL::users.activation.activated_at'),
            ],
        ];
    }

    /**
     * Create item
     *
     * @param array|null $data
     * @return mixed
     * @throws \Exception
     * @throws \Illuminate\Support\Facades\Exception
     */
    protected function __apiStore(array $data = null)
    {
        if (is_null($data)) {
            $data = $this->getInputData();
        }

        (new HCUsersValidator())->validateForm();

        try {
            $this->connection->beginTransaction();

            $record = createHCUser(
                array_get($data, 'record.email'),
                array_get($data, 'roles'),
                request()->filled('is_active'),
                array_get($data, 'record.password'),
                [],
                request()->filled('send_welcome_email'),
                request()->filled('send_password')
            );
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            throw new \Exception('Activation code or mail sending failed');
        }

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
        //TODO read request parameters only once fo getting data and validating it
        $data = $this->getInputData();

        (new HCUsersValidator())->setId($id)->validateForm();

        $record = HCUsers::findOrFail($id);

        // password changing
        if (array_get($data, 'record.password')) {
            if (Hash::check(array_get($data, 'old_password'), $record->password)) {
                array_set($data, 'record.password', Hash::make($data['new_password']));

                array_forget($data, ['new_password', 'old_password']);
            } else {
                return HCLog::info('USERS-003', trans('HCACL::users.errors.badOldPass'));
            }
        } else {
            array_forget($data, ['new_password', 'old_password', 'record.password']);
        }

        $record->update(array_get($data, 'record'));
        $record->assignRoles(array_get($data, 'roles'));

        // activate user if you want
        if (request()->filled('is_active') && $record->isNotActivated()) {
            $record->activate();
        }

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
        HCUsers::destroy($list);

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
        HCUsers::onlyTrashed()->whereIn('id', $list)->forceDelete();

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
        HCUsers::whereIn('id', $list)->restore();

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
            $select = HCUsers::getFillableFields();
        }

        $list = HCUsers::with($with)->select($select)
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
            $query->where('activated_at', 'LIKE', '%' . $phrase . '%')
                ->orWhere('remember_token', 'LIKE', '%' . $phrase . '%')
                ->orWhere('last_login', 'LIKE', '%' . $phrase . '%')
                ->orWhere('last_visited', 'LIKE', '%' . $phrase . '%')
                ->orWhere('last_activity', 'LIKE', '%' . $phrase . '%');
        });
    }

    /**
     * Getting user data on POST call
     *
     * @return array
     */
    protected function getInputData(): array
    {
        $data = [];
        $_data = request()->all();

        array_set($data, 'record.email', array_get($_data, 'email'));
        array_set($data, 'record.password', array_get($_data, 'password'));
        array_set($data, 'new_password', array_get($_data, 'new_password'));
        array_set($data, 'old_password', array_get($_data, 'old_password'));
        array_set($data, 'roles', array_get($_data, 'roles', []));

        return $data;
    }

    /**
     * Getting single record
     *
     * @param $id
     * @return mixed
     * todo: return DTO
     */
    public function apiShow(string $id)
    {
        $with = [
            'roles' => function($query) {
                $query->select('id', 'name as label');
            },
        ];

        $select = HCUsers::getFillableFields();

        $record = HCUsers::with($with)
            ->select($select)
            ->where('id', $id)
            ->firstOrFail();

        $record->is_active = [
            ['id' => $record->activated_at ? 1 : 0],
        ];

        return $record;
    }

}
