<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Participant name
            $table->string('email')->unique(); // Participant email, unique
            $table->string('phone'); // Participant phone
            $table->timestamps(); // Created at and updated at
        });
    }

    // Drop table on rollback
    public function down(): void
    {
        // Drop the table if it exists
        Schema::dropIfExists('participants');
    }
};
