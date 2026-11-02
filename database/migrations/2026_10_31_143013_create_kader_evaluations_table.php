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
        Schema::create('kader_evaluations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('kader_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('fasilitator_id')->constrained('users')->cascadeOnDelete();
            $table->text('note');
            $table->string('category')->default('Catatan Bimbingan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kader_evaluations');
    }
};
