<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePressReleasesFolderRefTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('press_releases_folder_ref', function (Blueprint $table) {
      $table->id();
      $table->integer('press_releases_id')->index();
      $table->integer('press_releases_folder_id')->index();
    });

    // Put press releases to default 'Press Releases' folder under this user.
    $press_releases = DB::table('press_releases')->get('*')->toArray();

    foreach ($press_releases as $key => $release) {

      $folder = DB::table('press_releases_folder')->where('user_id', $release->user_id)->where('folder_name', 'Press Releases')->first();

      DB::table('press_releases_folder_ref')->insert(array(
        'press_releases_id' => $release->id,
        'press_releases_folder_id' => $folder->id
      ));

    }

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('press_releases_folder_ref');
  }
}
