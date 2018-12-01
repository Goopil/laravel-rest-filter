<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('test')) {
            Schema::create('test', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('bool');

                $table->char('char');
                $table->string('string');
                $table->text('text');

                $table->integer('int');
                $table->double('double');
                $table->decimal('decimal');

                $table->dateTime('datetime');
                $table->date('date');
                $table->time('time');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test');
    }
}
