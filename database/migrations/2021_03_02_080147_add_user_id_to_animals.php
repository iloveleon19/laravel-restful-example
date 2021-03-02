<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToAnimals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->default(1)->comment('使用者ID');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animals', function (Blueprint $table) {
            // 刪除外建約束
            $table->dropForeign('animals_user_id_foreign');

            // 刪除 user_id 欄位
            $table->dropColumn('user_id');
        });
    }
}
