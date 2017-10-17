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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToHcAclRolesPermissionsConnectionsTable
 */
class AddForeignKeysToHcAclRolesPermissionsConnectionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('hc_acl_roles_permissions_connections', function(Blueprint $table) {
            $table->foreign('permission_id', 'fk_hc_roles_permissions_connections_hc_acl_permissions1')
                ->references('id')
                ->on('hc_acl_permissions')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('role_id', 'fk_hc_roles_permissions_connections_hc_acl_roles')
                ->references('id')
                ->on('hc_acl_roles')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('hc_acl_roles_permissions_connections', function(Blueprint $table) {
            $table->dropForeign('fk_hc_roles_permissions_connections_hc_acl_permissions1');
            $table->dropForeign('fk_hc_roles_permissions_connections_hc_acl_roles');
        });
    }

}
