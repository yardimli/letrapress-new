<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePressReleasesLabelTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('press_releases_label', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id')->default(0);
      $table->string('label_name');
      $table->string('label_color');
    });


    // add default label to each user.
    $users = DB::table('users')->get('*')->toArray();
    $press_releases_default_label = array(
      array('name' => 'Starred', 'color' => '#fd625e'),
      array('name' => 'Social', 'color' => '#5156BE'),
      array('name' => 'Finance', 'color' => '#FFBF53'),
      array('name' => 'Politics', 'color' => '#2AB57D')
    );

    foreach ($users as $key => $user) {
      foreach ($press_releases_default_label as $key => $label) {
        DB::table('press_releases_label')->insert(array(
          'user_id' => $user->id,
          'label_name' => $label['name'],
          'label_color' => $label['color']
        ));
      }
    }

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('press_releases_label');
  }
}
