<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 30)->nullable()->after('name');
        });

        // Lengkapi akun lama dengan username yang diturunkan dari email/nama.
        DB::table('users')->orderBy('id')->lazyById()->each(function (object $user): void {
            $localPart = Str::replace('.', '_', Str::before((string) ($user->email ?? $user->name), '@'));
            $base = Str::slug($localPart, '_');
            $base = $base !== '' ? Str::limit($base, 24, '') : 'user'.$user->id;

            $username = $base;
            $suffix = 2;

            while (DB::table('users')->where('username', $username)->exists()) {
                $username = $base.'_'.$suffix++;
            }

            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 30)->nullable(false)->change();
            $table->unique('username');
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
