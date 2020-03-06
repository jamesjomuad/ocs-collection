<?php namespace Ocs\Collection\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('ocs_collection_payment', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('debt_id')->unsigned()->index();
            $table->timestamp('payment_date')->nullable();
            $table->string('receipt_number',40)->nullable();
            $table->string('particulars')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('balance', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->string('status',25)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ocs_collection_payment');
    }
}
