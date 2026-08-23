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

    // Update outlet_id prowly_id to table row id.
    $prowly_outlet = DB::table('prowly_outlets')
      ->where('prowly_id', '=', null)
      ->orWhere('prowly_id', '=', '')->get('*')->toArray();

    foreach ($prowly_outlet as $key => $outlet) {
      $outlet = DB::table('prowly_outlets')->where('id', $outlet->id)->update(['prowly_id' => $outlet->id]);
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
