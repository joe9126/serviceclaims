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
        Schema::create('claimstoprint', function (Blueprint $table) {
            $table->id();
            $table->string('userid');
            $table->string('ticketno');
            $table->string('jobcardno');
            $table->string('billrefno');
            $table->string('client');
            $table->string('task');
            $table->string('location');
            $table->string('date');
            $table->string('time');
            $table->float('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claimstoprint');
    }
};
