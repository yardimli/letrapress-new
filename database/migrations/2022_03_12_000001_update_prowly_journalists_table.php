<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {

    Schema::table('prowly_journalists', function (Blueprint $table) {

      // Add created_at column.
      if(!Schema::hasColumn('prowly_journalists', 'created_at')){
        $table->timestamp('created_at')->after('update_time')->nullable()->default(null);
      }

      // Add updated_at column.
      if(!Schema::hasColumn('prowly_journalists', 'updated_at')){
        $table->timestamp('updated_at')->after('update_time')->nullable()->default(null);
      }

      // Add user_id column.
      if(!Schema::hasColumn('prowly_journalists', 'user_id')){
        $table->integer('user_id')->after('id')->default(0);
        $table->index('user_id');
      }

    });

    DB::table('prowly_journalists')->where('user_id', 0)->update(['user_id' => 1]);

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {

    Schema::table('prowly_journalists', function (Blueprint $table) {
      $table->dropColumn('created_at');
      $table->dropColumn('updated_at');
      $table->dropColumn('user_id');
    });

  }

};