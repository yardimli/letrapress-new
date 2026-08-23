<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsRoomsLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_rooms_label', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('label_name');
            $table->string('label_color');
        });

        // add default label to each user.
        $users = DB::table('users')->get('*')->toArray();
        $news_rooms_label = array(
          array('name' => 'News', 'color' => '#fd625e'),
          array('name' => 'Focus', 'color' => '#FFBF53'),
          array('name' => 'Updates', 'color' => '#2AB57D')
        );

        foreach ($users as $key => $user) {
            foreach ($news_rooms_label as $key => $label) {
                DB::table('news_rooms_label')->insert(array(
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
        Schema::dropIfExists('news_rooms_label');
    }
}
