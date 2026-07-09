<?php

namespace Database\Seeders;

use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\CategoryTranslation;
use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Models\ProductTranslation;
use App\Domain\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'misa.spale@gmail.com'],
            [
                'name' => 'Miloš Spasić',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $catUpotrebna = Category::firstOrCreate(
            ['slug' => 'upotrebna'],
            ['sort_order' => 0, 'is_active' => true]
        );
        foreach (['en' => 'Functional pottery', 'sr' => 'Upotrebna grnčarija', 'mk' => 'Функционална грнчарија', 'bg' => 'Функционална керамика', 'sq' => 'Pottershmëri funksionale'] as $locale => $name) {
            CategoryTranslation::updateOrCreate(
                ['category_id' => $catUpotrebna->id, 'locale' => $locale],
                ['name' => $name]
            );
        }

        $catVrtna = Category::firstOrCreate(
            ['slug' => 'vrtna'],
            ['sort_order' => 1, 'is_active' => true]
        );
        foreach (['en' => 'Garden pottery', 'sr' => 'Vrtna grnčarija', 'mk' => 'Градинска грнчарија', 'bg' => 'Градинска керамика', 'sq' => 'Grerësi e kopshtit'] as $locale => $name) {
            CategoryTranslation::updateOrCreate(
                ['category_id' => $catVrtna->id, 'locale' => $locale],
                ['name' => $name]
            );
        }

        $catDekorativna = Category::firstOrCreate(
            ['slug' => 'dekorativna'],
            ['sort_order' => 2, 'is_active' => true]
        );
        foreach (['en' => 'Decorative pottery', 'sr' => 'Dekorativna grnčarija', 'mk' => 'Декоративна грнчарија', 'bg' => 'Декоративна керамика', 'sq' => 'Grerësi dekorative'] as $locale => $name) {
            CategoryTranslation::updateOrCreate(
                ['category_id' => $catDekorativna->id, 'locale' => $locale],
                ['name' => $name]
            );
        }

        // Kopiranje slika iz seedera u public storage (da budu dostupne na sajtu)
        foreach (['djuvec.png', 'cinija.png'] as $filename) {
            $source = database_path('seeders/images/' . $filename);
            if (file_exists($source)) {
                Storage::disk('public')->put('products/' . $filename, file_get_contents($source));
            }
        }

        $products = [
            [
                'category' => $catUpotrebna,
                'slug' => 'djuvec',
                'name' => 'Đuveč – tradicionalna posuda',
                'description' => 'Ručno rađen đuveč od terakote, sa poklopcem. Pogodan za pečenje u rerni i serviranje. Dekorativni motiv u bež i tamno braon, otporan na pranje.',
                'price' => 1000,
                'image' => 'products/djuvec.png',
            ],
            [
                'category' => $catUpotrebna,
                'slug' => 'cinija',
                'name' => 'Činija za serviranje',
                'description' => 'Tradicionalna činija od gline, ručno ukrašena zeleno-belim motivom. Pogodna za pečenje i serviranje jela, ili kao dekorativna posuda.',
                'price' => 1200,
                'image' => 'products/cinija.png',
            ],
            [
                'category' => $catUpotrebna,
                'slug' => 'solja-rukohvat',
                'name' => 'Šolja sa ručnim drškom',
                'description' => 'Ručno rađena šolja, pogodna za svakodnevnu upotrebu. Držak izrađen posebno za udoban zahvat.',
                'price' => 850,
                'image' => null,
            ],
            [
                'category' => $catUpotrebna,
                'slug' => 'tanjir-plitak',
                'name' => 'Plitki tanjir',
                'description' => 'Tradicionalan plitki tanjir za predjelo ili glavno jelo. Pečen u rerni, otporan na pranje u mašini.',
                'price' => 1200,
                'image' => null,
            ],
            [
                'category' => $catUpotrebna,
                'slug' => 'vaza-voda',
                'name' => 'Vaza za vodu',
                'description' => 'Vaza za vodu ili sok. Elegantan oblik, ručna izrada. Može i kao dekoracija.',
                'price' => 2100,
                'image' => null,
            ],
            [
                'category' => $catVrtna,
                'slug' => 'saksija-mala',
                'name' => 'Mala saksija za cveće',
                'description' => 'Saksija za balkon ili prozor. Sa drenažnom rupom. Pogodna za sukulente i manje biljke.',
                'price' => 650,
                'image' => null,
            ],
            [
                'category' => $catVrtna,
                'slug' => 'saksija-velika',
                'name' => 'Velika vrtna saksija',
                'description' => 'Velika saksija za terasu ili vrt. Izdržava mraz. Ručno oblikovana, prirodna boja gline.',
                'price' => 3500,
                'image' => null,
            ],
            [
                'category' => $catDekorativna,
                'slug' => 'figurica-ptica',
                'name' => 'Dekorativna figurica ptice',
                'description' => 'Mala keramička figurica za policu ili stoni ukras. Ručno farbana, jedinstven primerak.',
                'price' => 950,
                'image' => null,
            ],
            [
                'category' => $catDekorativna,
                'slug' => 'posuda-plafonjera',
                'name' => 'Posuda u stilu plafonjere',
                'description' => 'Dekorativna posuda inspirisana tradicionalnom plafonjerom. Idealan poklon.',
                'price' => 1800,
                'image' => null,
            ],
        ];

        foreach ($products as $i => $data) {
            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $data['category']->id,
                    'price' => $data['price'],
                    'currency' => 'RSD',
                    'sort_order' => $i,
                    'is_active' => true,
                    'image' => $data['image'] ?? null,
                ]
            );
            ProductTranslation::updateOrCreate(
                ['product_id' => $product->id, 'locale' => 'sr'],
                ['name' => $data['name'], 'description' => $data['description']]
            );
        }
    }
}
