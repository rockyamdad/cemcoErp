<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('product_id');
			$table->foreign('product_id')->references('id')->on('products');
			$table->bigInteger('invoice_id');
			$table->integer('quantity');
			$table->float('price');
			$table->text('remarks');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sale_details');
	}

}
