<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHcUsersPasswordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hc_users_passwords', function(Blueprint $table)
		{
			$table->foreign('user_id', 'fk_hc_users_passwords_hc_users')->references('id')->on('hc_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hc_users_passwords', function(Blueprint $table)
		{
			$table->dropForeign('fk_hc_users_passwords_hc_users');
		});
	}

}
