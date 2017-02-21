<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHcAclRolesUsersConnectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hc_acl_roles_users_connections', function(Blueprint $table)
		{
			$table->integer('count', true);
			$table->timestamps();
			$table->string('role_id', 36)->index('fk_hc_acl_roles_users_connections_hc_acl_roles1_idx');
			$table->string('user_id', 36)->index('fk_hc_acl_roles_users_connections_hc_users1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hc_acl_roles_users_connections');
	}

}
