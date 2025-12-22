<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Domain;
use App\Models\Category;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all categories
        $categories = Category::all();
        
        $domains = [
            [
                'name' => 'TechInnovate',
                'description' => 'Perfect for technology startups and innovation companies',
                'category_name' => 'Technology',
                'character_length' => 12
            ],
            [
                'name' => 'GlobalTrade',
                'description' => 'Ideal for international business and trading companies',
                'category_name' => 'Business',
                'character_length' => 11
            ],
            [
                'name' => 'HealthPlus',
                'description' => 'Great for healthcare providers and wellness businesses',
                'category_name' => 'Health',
                'character_length' => 10
            ],
            [
                'name' => 'EduFuture',
                'description' => 'Excellent for educational institutions and e-learning platforms',
                'category_name' => 'Education',
                'character_length' => 9
            ],
            [
                'name' => 'GreenEnergy',
                'description' => 'Perfect for renewable energy and environmental companies',
                'category_name' => 'Environment',
                'character_length' => 10
            ],
            [
                'name' => 'FoodieDelight',
                'description' => 'Ideal for restaurants, food blogs, and culinary businesses',
                'category_name' => 'Food',
                'character_length' => 12
            ],
            [
                'name' => 'FashionTrend',
                'description' => 'Perfect for fashion retailers and style influencers',
                'category_name' => 'Fashion',
                'character_length' => 12
            ],
            [
                'name' => 'TravelGuru',
                'description' => 'Ideal for travel agencies and tour operators',
                'category_name' => 'Travel',
                'character_length' => 10
            ],
            [
                'name' => 'FinancePro',
                'description' => 'Great for financial advisors and investment firms',
                'category_name' => 'Finance',
                'character_length' => 10
            ],
            [
                'name' => 'HomeDesign',
                'description' => 'Excellent for interior designers and home decor businesses',
                'category_name' => 'Home',
                'character_length' => 10
            ],
            [
                'name' => 'AutoExpert',
                'description' => 'Perfect for auto repair shops and car dealerships',
                'category_name' => 'Automotive',
                'character_length' => 10
            ],
            [
                'name' => 'PetCare',
                'description' => 'Ideal for veterinarians and pet care services',
                'category_name' => 'Pets',
                'character_length' => 7
            ],
            [
                'name' => 'FitnessZone',
                'description' => 'Great for gyms and personal trainers',
                'category_name' => 'Health',
                'character_length' => 11
            ],
            [
                'name' => 'RealEstate',
                'description' => 'Perfect for real estate agents and property managers',
                'category_name' => 'Real Estate',
                'character_length' => 9
            ],
            [
                'name' => 'BeautySalon',
                'description' => 'Ideal for beauty salons and cosmetic brands',
                'category_name' => 'Beauty',
                'character_length' => 11
            ],
            [
                'name' => 'LegalAid',
                'description' => 'Great for law firms and legal services',
                'category_name' => 'Legal',
                'character_length' => 8
            ],
            [
                'name' => 'EventPlanner',
                'description' => 'Perfect for event planning companies',
                'category_name' => 'Events',
                'character_length' => 12
            ],
            [
                'name' => 'BookStore',
                'description' => 'Ideal for bookstores and literary services',
                'category_name' => 'Education',
                'character_length' => 9
            ]
        ];
        
        foreach ($domains as $domainData) {
            // Find the category by name
            $category = $categories->firstWhere('name', $domainData['category_name']);
            
            if ($category) {
                // Create the domain
                $domain = Domain::create([
                    'name' => $domainData['name'],
                    'description' => $domainData['description'],
                    'category_id' => $category->id,
                    'character_length' => $domainData['character_length']
                ]);
            }
        }
    }
}
