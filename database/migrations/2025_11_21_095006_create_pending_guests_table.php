<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_guests', function (Blueprint $table) {
            $table->id();

            // The fields that caused the error:
            $table->string('firstname'); 
            $table->string('middlename')->nullable();
            $table->string('lastname'); 

            $table->string('email')->unique(); // Unique email for verification
            $table->string('token')->unique(); // Token for the guest to register/confirm attendance
            $table->foreignId('event_id')->constrained()->onDelete('cascade'); // Which event they are invited to

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_guests');
    }
};
