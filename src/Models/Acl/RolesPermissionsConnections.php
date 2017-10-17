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

namespace InteractiveSolutions\HoneycombAcl\Models\Acl;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use interactivesolutions\honeycombcore\models\HCModel;

/**
 * Class RolesPermissionsConnections
 *
 * @package InteractiveSolutions\HoneycombAcl\Models\Acl
 * @property int $count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $role_id
 * @property string $permission_id
 * @method static Builder|RolesPermissionsConnections whereCount($value)
 * @method static Builder|RolesPermissionsConnections whereCreatedAt($value)
 * @method static Builder|RolesPermissionsConnections wherePermissionId($value)
 * @method static Builder|RolesPermissionsConnections whereRoleId($value)
 * @method static Builder|RolesPermissionsConnections whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RolesPermissionsConnections extends HCModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_acl_roles_permissions_connections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'role_id',
        'permission_id',
    ];

}