<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Technology',
                'icon' => 'fa-laptop',
                'description' => 'Technology and software related domains'
            ],
            [
                'name' => 'Business',
                'icon' => 'fa-building',
                'description' => 'Business and corporate domains'
            ],
            [
                'name' => 'Health',
                'icon' => 'fa-heart',
                'description' => 'Healthcare and wellness domains'
            ],
            [
                'name' => 'Education',
                'icon' => 'fa-graduation-cap',
                'description' => 'Educational and learning domains'
            ],
            [
                'name' => 'Environment',
                'icon' => 'fa-leaf',
                'description' => 'Environmental and green energy domains'
            ],
            [
                'name' => 'Food',
                'icon' => 'fa-utensils',
                'description' => 'Food and restaurant domains'
            ],
            [
                'name' => 'Fashion',
                'icon' => 'fa-tshirt',
                'description' => 'Fashion and clothing domains'
            ],
            [
                'name' => 'Travel',
                'icon' => 'fa-plane',
                'description' => 'Travel and tourism domains'
            ],
            [
                'name' => 'Finance',
                'icon' => 'fa-money-bill-wave',
                'description' => 'Financial and investment domains'
            ],
            [
                'name' => 'Home',
                'icon' => 'fa-home',
                'description' => 'Home and interior design domains'
            ],
            [
                'name' => 'Automotive',
                'icon' => 'fa-car',
                'description' => 'Automotive and car related domains'
            ],
            [
                'name' => 'Pets',
                'icon' => 'fa-paw',
                'description' => 'Pet care and animal domains'
            ],
            [
                'name' => 'Real Estate',
                'icon' => 'fa-house-user',
                'description' => 'Real estate and property domains'
            ],
            [
                'name' => 'Beauty',
                'icon' => 'fa-spa',
                'description' => 'Beauty and cosmetics domains'
            ],
            [
                'name' => 'Legal',
                'icon' => 'fa-gavel',
                'description' => 'Legal and law domains'
            ],
            [
                'name' => 'Events',
                'icon' => 'fa-calendar-alt',
                'description' => 'Event planning and celebration domains'
            ]
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
