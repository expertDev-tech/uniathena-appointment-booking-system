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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('availability_slot_id')
                ->constrained('availability_slots')
                ->restrictOnDelete();

            $table->string('reference_number')->unique();

            $table->enum('status', [
                'BOOKED',
                'CANCELLED'
            ])->default('BOOKED');

            $table->text('cancel_reason')
                ->nullable();

            $table->timestamps();

            $table->unique(
                'availability_slot_id',
                'appointment_slot_unique'
            );

            $table->index('patient_id');

            $table->index('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
