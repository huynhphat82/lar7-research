<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrnMessageTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trn_message_transaltion', function (Blueprint $table) {
            $table->id();
            $table->integer('message_id');
            $table->string('title')->nullable();
            $table->string('content')->nullable();
            $table->string('content_short')->nullable();
            $table->string('url')->nullable();
            $table->string('navigation')->nullable();
            $table->json('metadata')->nullable();
            $table->string('locale', 2);
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
        Schema::dropIfExists('trn_message_transaltion');
    }
}
