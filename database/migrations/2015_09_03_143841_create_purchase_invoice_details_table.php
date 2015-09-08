<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseInvoiceDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_invoice_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('product_id');
			$table->foreign('product_id')->references('id')->on('products');
			$table->unsignedInteger('invoice_id');
			$table->foreign('invoice_id')->references('invoice_id')->on('purchase_invoices');
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
		Schema::drop('purchase_invoice_details');
	}

}
