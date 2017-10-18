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

namespace InteractiveSolutions\HoneycombAcl\Database\Seeds;

use Illuminate\Database\Seeder;
use InteractiveSolutions\HoneycombAcl\Models\Acl\Roles;

/**
 * Class UserRolesSeeder
 * @package InteractiveSolutions\HoneycombAcl\Database\Seeds
 */
class UserRolesSeeder extends Seeder
{

    // Todo: it really need?
    const ROLE_SA = 'super-admin';
    const ROLE_PA = 'project-admin';
    const ROLE_E = 'editor';
    const ROLE_A = 'author';
    const ROLE_C = 'contributor';
    const ROLE_M = 'moderator';
    const ROLE_ME = 'member';
    const ROLE_S = 'subscriber';
    const ROLE_U = 'user';

    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        // http://stackoverflow.com/q/1598411
        $list = [
            ["name" => "Super Admin", "slug" => "super-admin"], // Manage everything
            ["name" => "Project Admin", "slug" => "project-admin"], // Manage most aspects of the site
            ["name" => "Editor", "slug" => "editor"], // Scheduling and managing content
            ["name" => "Author", "slug" => "author"], // Write important content
            ["name" => "Contributor", "slug" => "contributor"], // Authors with limited rights
            ["name" => "Moderator", "slug" => "moderator"], // Moderate user content
            ["name" => "Member", "slug" => "member"], // Special user access
            ["name" => "Subscriber", "slug" => "subscriber"], // Paying Average Joe
            ["name" => "User", "slug" => "user"], // Average Joe
        ];

        foreach ($list as $roleData) {
            $role = Roles::where('slug', $roleData['slug'])->first();

            if (!$role) {
                Roles::create($roleData);
            }
        }
    }
}