<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateContactListJournalistRefTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('contact_list_journalist_ref', function (Blueprint $table) {
      $table->id();
      $table->integer('contact_list_id')->index();
      $table->integer('journalist_id')->index();
    });

    // Move journalist_ids data from contact_list table to the contact_list_journalist_ref table.
    if (Schema::hasColumn('contact_list', 'journalist_ids')) {

      $contact_lists = DB::table('contact_list')->get('*')->toArray();

      foreach ($contact_lists as $key => $list) {

        $journalist_ids = json_decode($list->journalist_ids);

        foreach ($journalist_ids as $i => $j_id) {
          DB::table('contact_list_journalist_ref')->insert(array(
            'contact_list_id' => $list->id,
            'journalist_id' => $j_id
          ));
        }

      }
    }

    // Drop journalist_ids column in contact_list table
    Schema::table('contact_list', function (Blueprint $table) {
      $table->dropColumn('journalist_ids');
    });

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {

    // Add journalist_ids column in contact_list table.
    Schema::table('contact_list', function (Blueprint $table) {
      $table->text('journalist_ids');
    });

    Schema::dropIfExists('contact_list_journalist_ref');

  }
}
