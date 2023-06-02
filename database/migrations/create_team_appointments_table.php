<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_appointments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('notes')->nullable();
            $table->date('scheduled_at')->nullable();
            $table->string('start_at')->nullable();
            $table->string('end_at')->nullable();
            $table->string('duration')->nullable();
            $table->string('work_duration')->nullable();
            $table->string('pause')->nullable();
            $table->foreignId('client_id')->nullable();
            $table->foreignId('task_id')->nullable();
            $table->string('priority');
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->index();
            $table->foreignId('project_id')->index()->nullable();
            $table->foreignId('created_by')->nullable();
            $table->string('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable();
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
        Schema::dropIfExists('team_appointments');
    }
}
