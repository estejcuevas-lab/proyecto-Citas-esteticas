<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->decimal('service_price', 10, 2)->default(0)->after('end_time');
            $table->decimal('advance_percentage', 5, 2)->default(50)->after('service_price');
            $table->decimal('advance_amount', 10, 2)->default(0)->after('advance_percentage');
            $table->string('payment_status')->default('pending_advance')->after('advance_amount');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'service_price',
                'advance_percentage',
                'advance_amount',
                'payment_status',
            ]);
        });
    }
};
