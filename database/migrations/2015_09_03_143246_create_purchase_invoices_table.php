<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_invoices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('invoice_id',255);
			$table->unsignedInteger('party_id');
			$table->foreign('party_id')->references('id')->on('parties');
			$table->string('status',255);
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
		Schema::drop('purchase_invoices');
	}

}