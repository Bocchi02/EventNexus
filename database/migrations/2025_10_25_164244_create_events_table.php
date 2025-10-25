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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Relationship to organizer (user who created the event)
            $table->foreignId('organizer_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Event details
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('venue')->nullable();

            // Event schedule
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            // Image storage
            $table->string('cover_image')->nullable(); // main image
            $table->json('gallery_images')->nullable(); // additional images

            // Event status
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])
                  ->default('upcoming');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
