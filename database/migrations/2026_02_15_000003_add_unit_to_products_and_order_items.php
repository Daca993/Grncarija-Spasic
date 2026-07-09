<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('unit', 10)->default('cm')->after('currency');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('unit', 10)->nullable()->after('size_cm');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }
};
