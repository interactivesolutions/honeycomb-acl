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

namespace InteractiveSolutions\HoneycombAcl\Models\Acl;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use InteractiveSolutions\HoneycombAcl\Models\HCUsers;
use InteractiveSolutions\HoneycombCore\Models\HCUuidModel;

/**
 * Class Roles
 *
 * @package InteractiveSolutions\HoneycombAcl\Models\Acl
 * @property string $id
 * @property int $count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string $name
 * @property string $slug
 * @property-read Collection|Permissions[] $permissions
 * @property-read Collection|HCUsers[] $users
 * @method static Builder|Roles notSuperAdmin()
 * @method static Builder|Roles superAdmin()
 * @method static Builder|Roles whereCount($value)
 * @method static Builder|Roles whereCreatedAt($value)
 * @method static Builder|Roles whereDeletedAt($value)
 * @method static Builder|Roles whereId($value)
 * @method static Builder|Roles whereName($value)
 * @method static Builder|Roles whereSlug($value)
 * @method static Builder|Roles whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Roles extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_acl_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'slug',
    ];

    /**
     * A role may be given various permissions.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permissions::class,
            RolesPermissionsConnections::getTableName(),
            'role_id',
            'permission_id'
        );
    }

    /**
     * Grant the given permission to a role.
     *
     * @param Permissions $permission
     * @return mixed
     */
    public function givePermissionTo(Permissions $permission)
    {
        return $this->permissions()->save($permission);
    }

    /**
     * A role may be given various users.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(HCUsers::class, RolesUsersConnections::getTableName(), 'role_id', 'user_id');
    }

    /**
     * Get super admin
     *
     * @param $query
     */
    public function scopeSuperAdmin($query)
    {
        return $query->select('id', 'slug', 'name')->where('slug', 'super-admin');
    }

    /**
     * Get super admin
     *
     * @param $query
     */
    public function scopeNotSuperAdmin($query)
    {
        return $query->select('id', 'slug', 'name')->where('slug', '!=', 'super-admin');
    }

}
