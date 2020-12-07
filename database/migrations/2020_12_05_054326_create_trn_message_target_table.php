<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrnMessageTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trn_message_target', function (Blueprint $table) {
            $table->id();
            $table->integer('message_id');
            $table->integer('target_id');
            $table->smallInteger('read_count')->nullable();
            $table->string('status')->nullable()->comment('READ|UNREAD|PUSHED|UNPUSHED|SENT|UNSENT');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('message_id')->references('id')->on('trn_message')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trn_message_target');
    }
}
