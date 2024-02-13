<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Products::create([
            'name' => 'Blue Eyes White Dragon',
            'description' => 'This legendary dragon is a powerful engine of destruction. Virtually invincible, very few have faced this awesome creature and lived to tell the tale.',
            'product_photo' => 'https://m.media-amazon.com/images/I/71GJbOMs5LL._AC_UF894,1000_QL80_.jpg',
            'price' => 1000
        ]);

        Products::create([
            'name' => 'Dark Magician',
            'description' => 'The ultimate wizards in terms of attack and defense.',
            'product_photo' => 'https://cdn.cardsrealm.com/images/cartas/ct13-2016-mega-tins/en/med/dark-magician-en003.png?7631',
            'price' => 2000
        ]);
    }
}
