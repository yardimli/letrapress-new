<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactListOutletRefTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('contact_list_outlet_ref', function (Blueprint $table) {
      $table->id();
      $table->integer('contact_list_id')->index();
      $table->integer('outlet_id')->index();
    });

    // Move outlet_ids data from contact_list table to the contact_list_outlet_ref table.
    if (Schema::hasColumn('contact_list', 'outlet_ids')) {

      $contact_lists = DB::table('contact_list')->get('*')->toArray();

      foreach ($contact_lists as $key => $list) {

        $outlet_ids = json_decode($list->outlet_ids);

        foreach ($outlet_ids as $i => $o_id) {
          DB::table('contact_list_outlet_ref')->insert(array(
            'contact_list_id' => $list->id,
            'outlet_id' => $o_id
          ));
        }

      }
    }

    // Drop outlet_ids column in contact_list table
    Schema::table('contact_list', function (Blueprint $table) {
      $table->dropColumn('outlet_ids');
    });

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {

    // Add outlet_ids column in contact_list table.
    Schema::table('contact_list', function (Blueprint $table) {
      $table->text('outlet_ids');
    });

    Schema::dropIfExists('contact_list_outlet_ref');
  }
}
