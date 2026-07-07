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
        Schema::create('availability_slots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('availability_id')
                ->constrained('doctor_availabilities')
                ->cascadeOnDelete();

            $table->time('start_time');

            $table->time('end_time');

            $table->timestamps();

            $table->unique([
                'availability_id',
                'start_time',
                'end_time'
            ], 'availability_slot_unique');

            $table->index('availability_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability_slots');
    }
};
