<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('auth_provider')->nullable()->after('password');
            $table->string('provider_id')->nullable()->after('auth_provider');
            $table->string('avatar')->nullable()->after('provider_id');
            $table->timestamp('profile_completed_at')->nullable()->after('avatar');
            $table->timestamp('business_requested_at')->nullable()->after('profile_completed_at');
            $table->timestamp('business_approved_at')->nullable()->after('business_requested_at');
            $table->unique(['auth_provider', 'provider_id']);
        });

        $timestamp = now();

        DB::table('users')->update([
            'profile_completed_at' => $timestamp,
        ]);

        DB::table('users')
            ->where('role', 'business')
            ->update([
                'business_requested_at' => $timestamp,
                'business_approved_at' => $timestamp,
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_auth_provider_provider_id_unique');
            $table->dropColumn([
                'phone',
                'auth_provider',
                'provider_id',
                'avatar',
                'profile_completed_at',
                'business_requested_at',
                'business_approved_at',
            ]);
        });
    }
};
