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
            $table->boolean('is_down_payment')->default(false)->after('payment_method');
            $table->decimal('down_payment_amount', 10, 2)->nullable()->after('is_down_payment');
            $table->decimal('remaining_amount', 10, 2)->nullable()->after('down_payment_amount');
            $table->string('phone')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_down_payment', 'down_payment_amount', 'remaining_amount', 'phone']);
        });
    }
};
