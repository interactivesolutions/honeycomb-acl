<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHcUsersActivationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hc_users_activations', function (Blueprint $table) {
            $table->integer('count', true);
            $table->string('user_id', 36)->index('fk_hc_users_activations_hc_users_idx');
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        Schema::table('hc_users_activations', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_hc_users_activations_hc_users')->references('id')->on('hc_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hc_users_activations', function (Blueprint $table) {
            $table->dropForeign('fk_hc_users_activations_hc_users');
        });

        Schema::drop('hc_users_activations');
    }
}
