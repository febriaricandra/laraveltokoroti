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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('customer_province_id')->nullable()->after('phone');
            $table->integer('customer_city_id')->nullable()->after('customer_province_id');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('customer_city_id');
            $table->string('shipping_courier')->nullable()->after('shipping_cost');
            $table->string('shipping_service')->nullable()->after('shipping_courier');
            $table->integer('shipping_weight')->default(1000)->after('shipping_service'); // weight in grams
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_province_id',
                'customer_city_id', 
                'shipping_cost',
                'shipping_courier',
                'shipping_service',
                'shipping_weight'
            ]);
        });
    }
};
