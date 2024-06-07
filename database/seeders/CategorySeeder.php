<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Incendios',
            'url' => 'https://static.nationalgeographicla.com/files/styles/image_3200/public/nationalgeographic_2720812.jpg?w=1600&h=1061',
        ]);

        Category::create([
            'name' => 'Derrumbes',
            'url' => 'https://cdn-e360.s3-sa-east-1.amazonaws.com/cms_derrumbes-deslizamientos-de-tierrajpg__ksB2oHFi2ySMWyqOtGhAYuYAQhI8OquLio98LzAI.jpeg',
        ]);

        Category::create([
            'name' => 'Inundaciones',
            'url' => 'https://cdn.forbes.com.mx/2021/07/Inundaciones-China2.jpg',
        ]);
    }
}
