<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('size_mala_cm', 50)->nullable()->after('currency');
            $table->string('size_srednja_cm', 50)->nullable()->after('size_mala_cm');
            $table->string('size_velika_cm', 50)->nullable()->after('size_srednja_cm');
        });

        Schema::table('cart', function (Blueprint $table) {
            $table->string('size', 20)->default('srednja')->after('product_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('size', 20)->nullable()->after('product_name');
            $table->string('size_cm', 50)->nullable()->after('size');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['size_mala_cm', 'size_srednja_cm', 'size_velika_cm']);
        });
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('size');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['size', 'size_cm']);
        });
    }
};
