<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePressReleasesFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('press_releases_folder', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('folder_name');
        });

        // add default folder to each user.
        $users = DB::table('users')->get('*')->toArray();
        $press_releases_default_folder = array('Press Releases', 'Draft', 'Trash', 'My Press Releases');

        foreach ($users as $key => $user) {
            foreach ($press_releases_default_folder as $key => $folder) {
                DB::table('press_releases_folder')->insert(array(
                    'user_id' => $user->id,
                    'folder_name' => $folder
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
        Schema::dropIfExists('press_releases_folder');
    }
}
