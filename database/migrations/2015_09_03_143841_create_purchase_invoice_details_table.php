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
			$table->unsignedInteger('purchase_invoice_id');
			$table->foreign('purchase_invoice_id')->references('id')->on('purchase_invoices');
			$table->integer('quantity');
			$table->float('price');
			$table->text('remarks');
			$table->unsignedInteger('created_by');
			$table->foreign('created_by')->references('id')->on('users');
			$table->timestamps();
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
