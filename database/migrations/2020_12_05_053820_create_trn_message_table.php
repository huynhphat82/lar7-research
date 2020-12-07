<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrnMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trn_message', function (Blueprint $table) {
            $table->id();
            $table->string('message_type')->nullable()->comment('INAPP|NOTIFICATION|EMAIL');
            $table->string('template_type')->nullable()->comment('MODEL|IMAGE|HTML|EMAIL');
            $table->string('application_type')->comment('CUSTOMER|MERCHANT');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trn_message');
    }
}
