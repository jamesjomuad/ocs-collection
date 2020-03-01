<?php namespace Ocs\Collection\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateDebtsTable extends Migration
{
    public function up()
    {
        Schema::create('ocs_collection_debts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('collection_id')->unsigned()->index()->nullable();
            $table->integer('client_id')->unsigned()->index()->nullable();
            $table->string('name')->nullable();
            $table->decimal('debt_volume', 15, 2)->nullable();
            $table->decimal('debt_computed', 15, 2)->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ocs_collection_debts');
    }
}
