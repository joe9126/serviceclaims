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
        Schema::create('issuedparts', function (Blueprint $table) {
            $table->id();
            $table->string("client");
            $table->string("partno");
            $table->integer("quantity");
            $table->string("ticketno");
            $table->string("issuedto");
            $table->string("issuedby");
           $table->datetime("issuedon");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issuedparts');
    }
};
