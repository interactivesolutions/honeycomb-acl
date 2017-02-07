<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHcAclRolesUsersConnectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hc_acl_roles_users_connections', function(Blueprint $table)
		{
			$table->foreign('role_id', 'fk_hc_acl_roles_users_connections_hc_acl_roles1')->references('id')->on('hc_acl_roles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('user_id', 'fk_hc_acl_roles_users_connections_hc_users1')->references('id')->on('hc_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hc_acl_roles_users_connections', function(Blueprint $table)
		{
			$table->dropForeign('fk_hc_acl_roles_users_connections_hc_acl_roles1');
			$table->dropForeign('fk_hc_acl_roles_users_connections_hc_users1');
		});
	}

}
