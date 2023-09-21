<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            
            $table->string('credit_hours')->nullable();
            $table->string('assistant_name')->nullable();
            $table->string('room_name')->nullable();
            $table->string('room')->nullable();
            $table->string('class')->nullable();
            $table->string('class_hours')->nullable();
            $table->string('class_credit_hours')->nullable();
            $table->string('to')->nullable();
            $table->string('from')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('last_update')->nullable();
            $table->string('subject_status')->nullable();
            $table->string('subject_name')->nullable();
            $table->string('subject')->nullable();
            $table->string('time')->nullable();
            $table->string('day')->nullable();
            $table->string('student_name')->nullable();
            $table->string('student_id')->nullable();
            $table->string('training_program')->nullable();
            $table->string('training_department')->nullable();
            $table->string('level')->nullable();
            $table->string('training_place')->nullable();
            $table->string('training_semester')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
