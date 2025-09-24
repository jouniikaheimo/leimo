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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('badge_number')->nullable()->after('phone');
            $table->string('personal_identity_number')->nullable()->after('badge_number');
            $table->date('employment_started_at')->nullable()->after('personal_identity_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'badge_number',
                'personal_identity_number',
                'employment_started_at',
            ]);
        });
    }
};
