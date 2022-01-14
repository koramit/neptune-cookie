<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('slug');
            $table->string('title');
            $table->tinyText('description')->nullable();
            $table->unsignedTinyInteger('method')->default(1); // by participants, by VIP, by program
            $table->unsignedTinyInteger('status')->default(1); // planning, live, ended
            $table->string('no_luck_label')->default('ไม่มีตังค์ค่ะ');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // organizer
            $table->timestamps();
        });

        Schema::create('gift_event_user', function (Blueprint $table) {
            $table->primary(['gift_event_id', 'user_id']);
            $table->unsignedSmallInteger('gift_event_id')->constrained('gift_events')->onDelete('cascade');
            $table->unsignedSmallInteger('user_id')->constrained('users')->onDelete('cascade');
            $table->string('gift_title');
            $table->unsignedSmallInteger('label_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_event_user');
        Schema::dropIfExists('gift_events');
    }
}
