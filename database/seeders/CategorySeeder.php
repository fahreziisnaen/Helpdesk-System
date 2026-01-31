<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hardware',
                'slug' => 'hardware',
                'description' => 'Masalah terkait perangkat keras',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Software',
                'slug' => 'software',
                'description' => 'Masalah terkait perangkat lunak',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Network',
                'slug' => 'network',
                'description' => 'Masalah terkait jaringan',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'description' => 'Kategori lainnya',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
