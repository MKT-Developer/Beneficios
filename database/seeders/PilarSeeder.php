<?php

namespace Database\Seeders;

use App\Models\Pilar;
use Illuminate\Database\Seeder;

class PilarSeeder extends Seeder
{
    public function run(): void
    {
        $pilares = [
            [
                'pais_id' => 1,
                'nombre' => 'Bienestar Integral',
                'slug' => 'bienestar-integral',
                'icono' => 'bienestar-integral.svg',
                'orden' => 1,
                'activo' => true,
            ],
            [
                'pais_id' => 1,
                'nombre' => 'Desarrollo & Educación',
                'slug' => 'desarrollo-educacion',
                'icono' => 'desarrollo-educacion.svg',
                'orden' => 2,
                'activo' => true,
            ],
            [
                'pais_id' => 1,
                'nombre' => 'Estilo de Vida',
                'slug' => 'estilo-de-vida',
                'icono' => 'estilo-de-vida.svg',
                'orden' => 3,
                'activo' => true,
            ],
            [
                'pais_id' => 1,
                'nombre' => 'Convenios Financieros',
                'slug' => 'convenios-financieros',
                'icono' => 'convenios-financieros.svg',
                'orden' => 4,
                'activo' => true,
            ],
        ];

        foreach ($pilares as $pilar) {
            Pilar::updateOrCreate(
                ['slug' => $pilar['slug']], // condición UNIQUE
                $pilar                    // datos a actualizar/crear
            );
        }
    }
}
