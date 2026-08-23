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

    $folders = DB::table('press_releases_folder')
      ->where('folder_name', 'Press Releases')
      ->update(['folder_name' => 'Go Public']);

    $folders = DB::table('news_rooms_folder')
      ->where('folder_name', 'News Rooms')
      ->update(['folder_name' => 'Go Public']);

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
