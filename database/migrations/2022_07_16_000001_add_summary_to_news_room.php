<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {

    // Add type to press releases folder. ---------------------------------
    Schema::table('news_rooms', function (Blueprint $table) {
      if (!Schema::hasColumn('news_rooms', 'summary')) {
      $table->text('summary')->after('subject')->default('');
      }
    });

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {

  }

};
