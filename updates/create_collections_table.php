<?php namespace Ocs\Collection\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCollectionsTable extends Migration
{
    public function up()
    {
        Schema::create('ocs_collection', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('client_id')->unsigned()->index();
            $table->string('number')->nullable();
            $table->string('status')->nullable();
            $table->boolean('paid')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ocs_collection');
    }
}
