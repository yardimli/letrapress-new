<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsRoomsFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_rooms_folder', function (Blueprint $table) {
          $table->id();
          $table->integer('user_id')->default(0);
          $table->string('folder_name');
          $table->integer('order')->default(0);
        });

      // add default folder to each user.
      $users = DB::table('users')->get('*')->toArray();
      $news_rooms_default_folder = array('News Rooms', 'Draft', 'Trash', 'My News Rooms');

      foreach ($users as $key => $user) {
        foreach ($news_rooms_default_folder as $key => $folder) {
          DB::table('news_rooms_folder')->insert(array(
            'user_id' => $user->id,
            'folder_name' => $folder,
            'order' => $key
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
        Schema::dropIfExists('news_rooms_folder');
    }
}
