<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHcAclRolesPermissionsConnectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hc_acl_roles_permissions_connections', function(Blueprint $table)
		{
			$table->foreign('permission_id', 'fk_hc_roles_permissions_connections_hc_acl_permissions1')->references('id')->on('hc_acl_permissions')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('role_id', 'fk_hc_roles_permissions_connections_hc_acl_roles')->references('id')->on('hc_acl_roles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hc_acl_roles_permissions_connections', function(Blueprint $table)
		{
			$table->dropForeign('fk_hc_roles_permissions_connections_hc_acl_permissions1');
			$table->dropForeign('fk_hc_roles_permissions_connections_hc_acl_roles');
		});
	}

}
