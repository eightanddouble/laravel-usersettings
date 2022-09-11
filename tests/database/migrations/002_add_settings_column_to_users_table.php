<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$users_table = (new (config('usersettings.users')))->getTable();
		$settings_column = config('usersettings.settings_column', 'settings');

		Schema::table($users_table, function($table) use($settings_column) {
			$table->json($settings_column)->after('remember_token')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		$users_table = (new (config('usersettings.users')))->getTable();
		$settings_column = config('usersettings.settings_column', 'settings');

        Schema::table($users_table, function($table) use($settings_column) {
			$table->dropColumn($settings_column);
		});
    }
};
