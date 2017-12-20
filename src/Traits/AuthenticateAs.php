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

namespace InteractiveSolutions\HoneycombAcl\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use InteractiveSolutions\HoneycombAcl\Models\Acl\Roles;
use InteractiveSolutions\HoneycombAcl\Models\HCUsers;
use InteractiveSolutions\HoneycombAcl\Models\Traits\UserRoles;
use InteractiveSolutions\HoneycombAcl\Repositories\Acl\RolesRepository;

trait AuthenticateAs
{
    public function authenticateAs(
        array $roles = [RolesRepository::ROLE_SA],
        bool $remember = false,
        array $data = []
    ): Authenticatable {
        /** @var Authenticatable|UserRoles $user */
        $user = factory(HCUsers::class)->create($data);

        if ($roles) {
            $this->createRoles($user, $roles);
        }

        auth()->login($user, $remember);

        return $user;
    }

    /**
     * @param Authenticatable|UserRoles $user
     * @param array $roles
     */
    private function createRoles(Authenticatable $user, array &$roles): void
    {
        /** @var Collection|UserRoles[] $createdRoles */
        $createdRoles = collect();
        foreach ($roles as $role) {
            $createdRoles->offsetSet(
                $role,
                factory(Roles::class)->create([
                    'name' => ucwords(str_replace('-', ' ', $role), ' '),
                    'slug' => $role,
                ])
            );
        }

        $user->roles()->sync($createdRoles->pluck('id'));
    }
}
