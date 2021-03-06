<?php namespace Ocs\Collection\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateDebtsTable extends Migration
{
    public function up()
    {
        Schema::create('ocs_collection_debt', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('debtor_id')->unsigned()->index()->nullable();
            $table->integer('collection_id')->unsigned()->index()->nullable();
            $table->string('number', 40)->nullable();
            $table->timestamp('placement')->nullable();
            $table->string('duration')->nullable();
            $table->decimal('volume', 15, 2)->nullable();
            $table->decimal('balance', 15, 2)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('payment_plan')->nullable();
            $table->string('terms')->nullable();
            $table->string('monthly_amortization')->nullable();
            $table->string('assignee')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ocs_collection_debt');
    }
}