<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHcAclRolesPermissionsConnectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hc_acl_roles_permissions_connections', function(Blueprint $table)
		{
			$table->integer('count', true);
			$table->timestamps();
			$table->string('role_id', 36)->index('fk_hc_roles_permissions_connections_hc_acl_roles_idx');
			$table->string('permission_id', 36)->index('fk_hc_roles_permissions_connections_hc_acl_permissions1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hc_acl_roles_permissions_connections');
	}

}
