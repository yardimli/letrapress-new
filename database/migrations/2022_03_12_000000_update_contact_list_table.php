<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {

    Schema::table('contact_list', function (Blueprint $table) {

      // Change column type.
      if(Schema::hasColumn('contact_list', 'description')){
        $table->text('description')->change();
      }

      if(Schema::hasColumn('contact_list', 'journalist_ids')){
        $table->text('journalist_ids')->change();
      }

      if(Schema::hasColumn('contact_list', 'outlet_ids')){
        $table->text('outlet_ids')->change();
      }

      // Add user_id column.
      if(!Schema::hasColumn('contact_list', 'user_id')){
        $table->integer('user_id')->after('id')->default(0);
        $table->index('user_id');
      }

    });

    DB::table('contact_list')->where('user_id', 0)->update(['user_id' => 1]);

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {

    Schema::table('contact_list', function (Blueprint $table) {
      $table->dropColumn('user_id');
    });

  }

};