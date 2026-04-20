<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Pais;

class PaisesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paises = [
            [
                'codigo' => 'mx',
                'nombre' => 'México',
                'activo' => true,
            ],
            [
                'codigo' => 'int',
                'nombre' => 'Internacional',
                'activo' => true,
            ],
        ];

        foreach ($paises as $pais) {
            Pais::updateOrCreate(
                ['codigo' => $pais['codigo']], // condición
                $pais                        // datos
            );
        }
    }
}
