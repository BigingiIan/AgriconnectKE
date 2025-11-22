<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdToBidsTable extends Migration
{
    public function up()
    {
        Schema::table('bids', function (Blueprint $table) {
            if (!Schema::hasColumn('bids', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            }
            
        });
    }

    public function down()
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
    }
}