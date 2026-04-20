<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ubicacion;

class UbicacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ubicaciones = [
            'CDMX',
            'Guadalajara',
            'Monterrey',
            'Puebla',
            'Querétaro',
            'Cancún',
            'Mérida',
            'Tijuana',
        ];

        foreach ($ubicaciones as $nombre) {
            Ubicacion::firstOrCreate([
                'nombre' => $nombre
            ]);
        }
    }
}
