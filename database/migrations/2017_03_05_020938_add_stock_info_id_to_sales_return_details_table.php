<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStockInfoIdToSalesReturnDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sales_return_details', function(Blueprint $table)
		{
            $table->unsignedInteger('stock_info_id')->nullable();
            $table->foreign('stock_info_id')->references('id')->on('stock_infos');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sales_return_details', function(Blueprint $table)
		{
            Schema::drop('sales_return_details');
		});
	}

}
