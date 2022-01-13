<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedMediumInteger('quota');
            $table->foreignId('gift_event_id')->constrained('gift_events')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('participant_participant_group', function (Blueprint $table) {
            $table->primary(['participant_id', 'participant_group_id']);
            $table->unsignedInteger('participant_id')->constrained('participants')->onDelete('cascade');
            $table->unsignedBigInteger('participant_group_id')->constrained('participant_groups')->onDelete('cascade');
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
        Schema::dropIfExists('participant_participant_group');
        Schema::dropIfExists('participant_groups');
    }
}
