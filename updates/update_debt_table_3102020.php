<?php namespace Ocs\Collection\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateDebtTable3102020 extends Migration
{
    public function up()
    {
        Schema::table('ocs_collection_debt', function(Blueprint $table){
            $table->integer('sheet_id')->nullable()->after('collection_id');
        });
    }

    public function down()
    {
        
    }
}