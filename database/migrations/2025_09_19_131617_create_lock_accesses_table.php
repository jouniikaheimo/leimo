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
        Schema::create('lock_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('badge_number')->nullable();
            $table->string('lock_number');
            $table->string('location')->nullable();
            $table->timestamp('opened_at');
            $table->string('result')->default('granted'); // granted / denied
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['badge_number']);
            $table->index(['lock_number']);
            $table->index(['opened_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lock_accesses');
    }
};
