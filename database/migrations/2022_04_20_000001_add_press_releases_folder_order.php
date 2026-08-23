<?php
/**
 * Created by PhpStorm.
 * User: Chung
 * Date: 2022-04-20
 * Time: 15:18
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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

		Schema::table('press_releases_folder', function (Blueprint $table) {

			// Add folder_id column.
			if (!Schema::hasColumn('press_releases_folder', 'order')) {
				$table->integer('order')->after('folder_name')->default(0);
			}

		});

		// Set folder order.
		$users = DB::table('users')->get('*')->toArray();

		foreach ($users as $key => $user) {

			// Set default folder order.
			DB::table('press_releases_folder')->where('user_id', $user->id)->where('folder_name', 'Press Releases')->update(['order' => 0]);
			DB::table('press_releases_folder')->where('user_id', $user->id)->where('folder_name', 'Draft')->update(['order' => 1]);
			DB::table('press_releases_folder')->where('user_id', $user->id)->where('folder_name', 'Trash')->update(['order' => 2]);
			DB::table('press_releases_folder')->where('user_id', $user->id)->where('folder_name', 'My Press Releases')->update(['order' => 3]);

			// Set order to other folders.
			$folders = DB::table('press_releases_folder')->where('user_id', $user->id)->where(function ($query) {
				$query->where('folder_name', '!=', 'Press Releases')->where('folder_name', '!=', 'Draft')->where('folder_name', '!=', 'Trash')->where('folder_name', '!=', 'My Press Releases');
			})->orderBy('folder_name', 'asc')->get('*')->toArray();

			foreach ($folders as $key => $folder) {
				DB::table('press_releases_folder')->where('user_id', $user->id)->where('folder_name', $folder->folder_name)->update(['order' => $key + 4]);
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
		//
	}

};
