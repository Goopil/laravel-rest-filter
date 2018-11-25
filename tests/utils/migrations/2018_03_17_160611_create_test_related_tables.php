<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('test_related')) {
            Schema::create('test_related', function (Blueprint $table) {
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

                $table->unsignedInteger('test_model_id');
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
