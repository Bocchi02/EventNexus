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
            $table->string('firstname')->after('event_id');
            $table->string('middlename')->nullable()->after('firstname');
            $table->string('lastname')->after('middlename');
        });
    }

    public function down()
    {
        Schema::table('pending_guests', function (Blueprint $table) {
            $table->dropColumn(['firstname', 'middlename', 'lastname']);
        });
    }

};
