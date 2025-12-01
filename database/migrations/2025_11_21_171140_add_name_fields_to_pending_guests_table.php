<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pending_guests', function (Blueprint $table) {
            // Only add 'firstname' if it doesn't exist yet
            if (!Schema::hasColumn('pending_guests', 'firstname')) {
                $table->string('firstname')->after('event_id');
            }

            // Only add 'middlename' if it doesn't exist yet
            if (!Schema::hasColumn('pending_guests', 'middlename')) {
                $table->string('middlename')->nullable()->after('firstname');
            }

            // Only add 'lastname' if it doesn't exist yet
            if (!Schema::hasColumn('pending_guests', 'lastname')) {
                $table->string('lastname')->after('middlename');
            }
        });
    }

};
