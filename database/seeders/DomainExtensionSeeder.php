<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DomainExtension;
use App\Models\Domain;

class DomainExtensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all domains
        $domains = Domain::all();
        
        // Define extensions and prices for each domain
        foreach ($domains as $domain) {
            $extensions = [];
            
            switch ($domain->name) {
                case 'TechInnovate':
                    $extensions = [
                        ['.com', 15.99],
                        ['.net', 12.99],
                        ['.org', 11.99],
                        ['.info', 9.99],
                        ['.biz', 8.99]
                    ];
                    break;
                case 'GlobalTrade':
                    $extensions = [
                        ['.com', 19.99],
                        ['.co', 14.99],
                        ['.global', 16.99],
                        ['.world', 13.99],
                        ['.market', 11.99]
                    ];
                    break;
                case 'HealthPlus':
                    $extensions = [
                        ['.com', 17.99],
                        ['.health', 22.99],
                        ['.care', 13.99],
                        ['.wellness', 15.99],
                        ['.medical', 18.99]
                    ];
                    break;
                case 'EduFuture':
                    $extensions = [
                        ['.com', 14.99],
                        ['.edu', 25.99],
                        ['.learning', 12.99],
                        ['.school', 13.99],
                        ['.online', 10.99]
                    ];
                    break;
                case 'GreenEnergy':
                    $extensions = [
                        ['.com', 21.99],
                        ['.green', 18.99],
                        ['.energy', 16.99],
                        ['.eco', 14.99],
                        ['.solar', 19.99]
                    ];
                    break;
                case 'FoodieDelight':
                    $extensions = [
                        ['.com', 13.99],
                        ['.food', 15.99],
                        ['.restaurant', 17.99],
                        ['.cooking', 12.99],
                        ['.delivery', 14.99]
                    ];
                    break;
                case 'FashionTrend':
                    $extensions = [
                        ['.com', 16.99],
                        ['.fashion', 19.99],
                        ['.style', 14.99],
                        ['.boutique', 17.99],
                        ['.clothing', 12.99]
                    ];
                    break;
                case 'TravelGuru':
                    $extensions = [
                        ['.com', 18.99],
                        ['.travel', 22.99],
                        ['.voyage', 16.99],
                        ['.trip', 13.99],
                        ['.adventure', 15.99]
                    ];
                    break;
                case 'FinancePro':
                    $extensions = [
                        ['.com', 20.99],
                        ['.finance', 24.99],
                        ['.money', 17.99],
                        ['.invest', 19.99],
                        ['.bank', 22.99]
                    ];
                    break;
                case 'HomeDesign':
                    $extensions = [
                        ['.com', 15.99],
                        ['.design', 18.99],
                        ['.interior', 14.99],
                        ['.house', 12.99],
                        ['.home', 11.99]
                    ];
                    break;
                case 'AutoExpert':
                    $extensions = [
                        ['.com', 17.99],
                        ['.auto', 14.99],
                        ['.cars', 12.99],
                        ['.repair', 13.99],
                        ['.motor', 15.99]
                    ];
                    break;
                case 'PetCare':
                    $extensions = [
                        ['.com', 14.99],
                        ['.pets', 12.99],
                        ['.animals', 11.99],
                        ['.vet', 16.99],
                        ['.dog', 10.99]
                    ];
                    break;
                case 'FitnessZone':
                    $extensions = [
                        ['.com', 16.99],
                        ['.fitness', 19.99],
                        ['.gym', 14.99],
                        ['.workout', 13.99],
                        ['.health', 17.99]
                    ];
                    break;
                case 'RealEstate':
                    $extensions = [
                        ['.com', 22.99],
                        ['.realestate', 25.99],
                        ['.property', 19.99],
                        ['.homes', 17.99],
                        ['.estate', 20.99]
                    ];
                    break;
                case 'BeautySalon':
                    $extensions = [
                        ['.com', 13.99],
                        ['.beauty', 16.99],
                        ['.salon', 14.99],
                        ['.spa', 15.99],
                        ['.cosmetics', 18.99]
                    ];
                    break;
                case 'LegalAid':
                    $extensions = [
                        ['.com', 24.99],
                        ['.law', 27.99],
                        ['.legal', 22.99],
                        ['.attorney', 25.99],
                        ['.lawyer', 23.99]
                    ];
                    break;
                case 'EventPlanner':
                    $extensions = [
                        ['.com', 15.99],
                        ['.events', 18.99],
                        ['.party', 12.99],
                        ['.wedding', 19.99],
                        ['.celebrate', 14.99]
                    ];
                    break;
                case 'BookStore':
                    $extensions = [
                        ['.com', 12.99],
                        ['.books', 14.99],
                        ['.read', 11.99],
                        ['.library', 16.99],
                        ['.literature', 13.99]
                    ];
                    break;
            }
            
            // Create extensions for this domain
            foreach ($extensions as $extensionData) {
                DomainExtension::create([
                    'domain_id' => $domain->id,
                    'extension' => $extensionData[0],
                    'price' => $extensionData[1]
                ]);
            }
        }
    }
}
