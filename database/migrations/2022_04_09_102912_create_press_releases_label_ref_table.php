<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePressReleasesLabelRefTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('press_releases_label_ref', function (Blueprint $table) {
      $table->id();
      $table->integer('press_releases_id')->index();
      $table->integer('press_releases_label_id')->index();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('press_releases_label_ref');
  }
}
