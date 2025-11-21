<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_guests', function (Blueprint $table) {
            // Foreign key to the events table
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            
            // Foreign key to the users table (the guests)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Define the primary key as the combination of both columns
            $table->primary(['event_id', 'user_id']);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_guests');
    }
};
