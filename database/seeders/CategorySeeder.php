<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    private $category;

    public function __construct(Category $category){
        $this->category = $category;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        /**
         * No duplicates of category
         */
        $categories = [
            [
                'name' => 'Cars',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Motorcycle',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bicycles',
                'created_at' => now(), //current date and time
                'updated_at' => now()
            ]
        ];

        $this->category->insert($categories);
    }
}
