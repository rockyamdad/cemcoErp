<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImportNumToImportDetails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('import_details', function(Blueprint $table){
			$table->dropForeign('import_details_import_id_foreign');
			$table->dropColumn('import_id');
		});
	}

}
