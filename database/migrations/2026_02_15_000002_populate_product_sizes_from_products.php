<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $offsets = config('app.size_price_offset', ['mala' => -200, 'srednja' => 0, 'velika' => 200]);
        $products = DB::table('products')->get();
        foreach ($products as $p) {
            $basePrice = (float) $p->price;
            $rows = [
                ['product_id' => $p->id, 'size' => 'mala', 'size_cm' => $p->size_mala_cm ?? null, 'price' => max(0, $basePrice + ($offsets['mala'] ?? -200))],
                ['product_id' => $p->id, 'size' => 'srednja', 'size_cm' => $p->size_srednja_cm ?? null, 'price' => max(0, $basePrice + ($offsets['srednja'] ?? 0))],
                ['product_id' => $p->id, 'size' => 'velika', 'size_cm' => $p->size_velika_cm ?? null, 'price' => max(0, $basePrice + ($offsets['velika'] ?? 200))],
            ];
            foreach ($rows as $row) {
                DB::table('product_sizes')->insert(array_merge($row, ['created_at' => now(), 'updated_at' => now()]));
            }
        }
    }

    public function down(): void
    {
        DB::table('product_sizes')->truncate();
    }
};
