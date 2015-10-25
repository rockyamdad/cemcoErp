<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedImportNumToImportDetails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('import_details', function(Blueprint $table)
		{
			$table->string('import_num',255);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('import_details', function(Blueprint $table)
		{
			Schema::drop('import_details');
		});
	}

}
