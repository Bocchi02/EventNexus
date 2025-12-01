<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Add seats to the main link between Event and User
        Schema::table('event_guest', function (Blueprint $table) {
            $table->integer('seats')->default(1)->after('status');
        });

        // 2. Add seats to the temporary pending invitations
        Schema::table('pending_guests', function (Blueprint $table) {
            $table->integer('seats')->default(1)->after('email');
        });
    }
};
