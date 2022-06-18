<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_receives', function (Blueprint $table) {
            $table->id();
            $table->string('receive_no',6);
            $table->date('receive_date');
            $table->integer('item_id');
            $table->integer('supplier_id');
            $table->double('quantity', 8, 2);
            $table->double('price', 8, 2);
            $table->double('used_qty', 8, 2)->default(0);
            $table->integer('entry_by');
            $table->timestamps();
            $table->unique(["item_id", "receive_no"], 'item_receive_unique');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // drop quique index
        Schema::table('user_projects', function (Blueprint $table) {
            $table->dropUnique('item_receive_unique');
        });

        // drop table
        Schema::dropIfExists('stock_receives');
    }
}
