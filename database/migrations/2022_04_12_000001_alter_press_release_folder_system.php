<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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

    Schema::dropIfExists('press_releases_folder_ref');

    Schema::table('press_releases', function (Blueprint $table) {

      // Add folder_id column.
      if (!Schema::hasColumn('press_releases', 'folder_id')) {
        $table->integer('folder_id')->after('user_id')->default(0);
        $table->index('folder_id');
      }

    });

    // Set press release folder.
    $press_releases = DB::table('press_releases')->get('*')->toArray();

    foreach ($press_releases as $key => $release) {
      $folder = DB::table('press_releases_folder')->where('user_id', $release->user_id)->where('folder_name', 'Press Releases')->first();
      DB::table('press_releases')->where('id', $release->id)->update(['folder_id' => $folder->id]);
    }

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    //
  }

};


