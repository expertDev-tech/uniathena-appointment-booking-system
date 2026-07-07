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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('appointment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', [
                'BOOKED',
                'CANCELLED',
                'RESCHEDULED'
            ]);

            $table->text('message');

            $table->enum('status', [
                'QUEUED',
                'SENT',
                'FAILED'
            ])->default('QUEUED');

            $table->unsignedTinyInteger('retry_count')
                ->default(0);

            $table->text('last_error')
                ->nullable();

            $table->timestamp('sent_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'appointment_id',
                'status'
            ], 'notification_lookup');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
