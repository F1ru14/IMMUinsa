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
        Schema::table('users', function (Blueprint $table): void {
            $table->string('focus_area')->nullable()->after('is_active');
            $table->foreignId('fasilitator_id')->nullable()->after('focus_area')->constrained('users')->nullOnDelete();
            $table->foreignId('mentor_id')->nullable()->after('fasilitator_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['fasilitator_id']);
            $table->dropForeign(['mentor_id']);
            $table->dropColumn(['focus_area', 'fasilitator_id', 'mentor_id']);
        });
    }
};
