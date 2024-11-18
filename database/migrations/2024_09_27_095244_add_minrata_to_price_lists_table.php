<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinrataToPriceListsTable extends Migration
{
    public function up()
    {
        Schema::table('price_lists', function (Blueprint $table) {
            $table->integer('minrata')->nullable()->after('paymentExampleText');
        });
    }

    public function down()
    {
        Schema::table('price_lists', function (Blueprint $table) {
            $table->dropColumn('minrata');
        });
    }
}
