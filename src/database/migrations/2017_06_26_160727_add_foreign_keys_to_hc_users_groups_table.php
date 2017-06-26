<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHcUsersGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hc_users_groups', function(Blueprint $table)
		{
			$table->foreign('creator_id', 'fk_hc_users_groups_hc_users1')->references('id')->on('hc_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hc_users_groups', function(Blueprint $table)
		{
			$table->dropForeign('fk_hc_users_groups_hc_users1');
		});
	}

}
