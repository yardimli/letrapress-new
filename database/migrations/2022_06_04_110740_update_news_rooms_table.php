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
      // Add order column.
      Schema::table('news_rooms', function (Blueprint $table) {
        if (!Schema::hasColumn('news_rooms', 'order')) {
          $table->integer('order')->after('featured_image')->default(0);
        }
      });

      // Set default feature image.
      $featured_image = 'assets/images/news_room.jpg';
      DB::table('news_rooms')->update(['featured_image' => $featured_image]);

      // Set user first newsroom.
      $users = DB::table('users')->get('*')->toArray();

      foreach ($users as $key => $user) {

        $first_room = DB::table('news_rooms')->where('user_id', $user->id)->first();
        if ($first_room === null) {

          // Find default folder id.
          $folder = DB::table('news_rooms_folder')->where('user_id', $user->id)->where('folder_name', 'News Rooms')->first();

          DB::table('news_rooms')->insert([
            'user_id' => $user->id,
            'folder_id' => $folder->id,
            'subject' => 'My News Room',
            'featured_image' => $featured_image,
            'order' => 0
          ]);

        }

      }

      // Set user newsroom order.
      $users = DB::table('users')->get('*')->toArray();

      foreach ($users as $key => $user) {
        $rooms = DB::table('news_rooms')->where('user_id', $user->id)->orderBy('id', 'asc')->get('*')->toArray();
        foreach ($rooms as $index => $room){
          DB::table('news_rooms')->where('id', $room->id)->update([
            'order' => $index
          ]);
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

    }
};
