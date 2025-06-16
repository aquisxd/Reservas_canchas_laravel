<?php
// database/seeders/CourtSeeder.php
namespace Database\Seeders;

use App\Models\Court;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        $courts = [
            [
                'name' => 'Cancha Central',
                'description' => 'Cancha principal con capacidad para 200 espectadores',
                'price_per_hour' => 50.00,
                'surface_type' => 'hard'
            ],
            [
                'name' => 'Cancha Norte',
                'description' => 'Cancha de entrenamiento',
                'price_per_hour' => 35.00,
                'surface_type' => 'clay'
            ],
            [
                'name' => 'Cancha Sur',
                'description' => 'Cancha recreativa',
                'price_per_hour' => 30.00,
                'surface_type' => 'synthetic'
            ]
        ];

        foreach ($courts as $court) {
            Court::create($court);
        }
    }
}
