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
    Schema::table('press_releases_folder', function (Blueprint $table) {
      if (!Schema::hasColumn('press_releases_folder', 'type')) {
        $table->string('type')->after('order')->default('user');
      }
    });

    // set folder type.
    $folders = DB::table('press_releases_folder')->get('*')->toArray();
    $releases_system_folder = array('Press Releases', 'Draft', 'Trash', 'My Press Releases');

    foreach ($folders as $key => $folder) {
      $type = (in_array($folder->folder_name, $releases_system_folder)) ? 'system' : 'user';
      DB::table('press_releases_folder')->where('id', $folder->id)->update(['type' => $type]);
    }


    // Add type to news rooms folder. ---------------------------------
    Schema::table('news_rooms_folder', function (Blueprint $table) {
      if (!Schema::hasColumn('news_rooms_folder', 'type')) {
        $table->string('type')->after('order')->default('user');
      }
    });

    // set folder type.
    $folders = DB::table('news_rooms_folder')->get('*')->toArray();
    $rooms_system_folder = array('News Rooms', 'Draft', 'Trash', 'My News Rooms');

    foreach ($folders as $key => $folder) {
      $type = (in_array($folder->folder_name, $rooms_system_folder)) ? 'system' : 'user';
      DB::table('news_rooms_folder')->where('id', $folder->id)->update(['type' => $type]);
    }


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
