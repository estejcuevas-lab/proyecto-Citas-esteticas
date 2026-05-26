<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->string('primary_color', 7)->default('#994b35')->after('address');
        });

        $usedSlugs = [];

        DB::table('businesses')
            ->select(['id', 'name'])
            ->orderBy('id')
            ->get()
            ->each(function (object $business) use (&$usedSlugs): void {
                $baseSlug = Str::slug($business->name) ?: 'negocio';
                $slug = $baseSlug;
                $suffix = 2;

                while (in_array($slug, $usedSlugs, true)) {
                    $slug = "{$baseSlug}-{$suffix}";
                    $suffix++;
                }

                $usedSlugs[] = $slug;

                DB::table('businesses')
                    ->where('id', $business->id)
                    ->update(['slug' => $slug]);
            });

        Schema::table('businesses', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropUnique('businesses_slug_unique');
            $table->dropColumn([
                'slug',
                'primary_color',
            ]);
        });
    }
};
