<?php namespace Ocs\Collection\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateBillsTable extends Migration
{
    public function up()
    {
        Schema::create('ocs_collection_bills', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ocs_collection_bills');
    }
}
