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

    DB::table('prowly_outlet_types')->insert(['outlet_type' => 'Youtube', 'record_count' => 0]);
    DB::table('prowly_outlet_types')->insert(['outlet_type' => 'Instagram', 'record_count' => 0]);

    // Find the last row.
    $last_media = DB::table('prowly_outlet_types')->orderBy('id', 'desc')->first();

    // Find distinct media type.
    $media_types = DB::table('prowly_outlet_types')
      ->select('outlet_type')
      ->where('outlet_type', '!=', null)->where('outlet_type', '!=', '')
      ->groupBy('outlet_type')
      ->get()->toArray();

    foreach ($media_types as $key => $media_type) {
      // Insert distinct new rows.
      DB::table('prowly_outlet_types')->insert(['outlet_type' => $media_type->outlet_type, 'record_count' => 0]);
    }

    // Find all rows before the last row.
    $media_types = DB::table('prowly_outlet_types')
      ->where('id', '<=', $last_media->id)
      ->get('*')->toArray();

    foreach ($media_types as $key => $media_type) {
      // Use the new max id, update media_type_id for journalists and outlets.
      if ($media_type->outlet_type == null || $media_type->outlet_type == '') {
        DB::table('prowly_journalists')->where('media_type_id', $media_type->id)->update(['media_type_id' => 0]);
        DB::table('prowly_outlets')->where('media_type_id', $media_type->id)->update(['media_type_id' => 0]);
      } else {
        $media = DB::table('prowly_outlet_types')->where('outlet_type', $media_type->outlet_type)->orderBy('id', 'desc')->first();
        DB::table('prowly_journalists')->where('media_type_id', $media_type->id)->update(['media_type_id' => $media->id]);
        DB::table('prowly_outlets')->where('media_type_id', $media_type->id)->update(['media_type_id' => $media->id]);
      }
    }

    // Delete old rows.
    DB::table('prowly_outlet_types')->where('id', '<=', $last_media->id)->delete();

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
