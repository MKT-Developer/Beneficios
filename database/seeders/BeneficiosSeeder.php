<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Beneficio;
use App\Models\Pilar;
use App\Models\Ubicacion;

class BeneficiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // Obtener el pilar al que se asociará el beneficio
        $pilar = Pilar::where('slug', 'bienestar-integral')->first();

        if (!$pilar) {
            $this->command->error('No se encontró el pilar "bienestar-integral"');
            return;
        }

        // Crear o actualizar el beneficio
        $beneficio = Beneficio::updateOrCreate(
            [
                'nombre' => 'DEVLYN',
                'pilar_id' => $pilar->id,
            ],
            [
                'descripcion' => '20% sobre precio de lista en vigor de productos. 10% sobre precio de lista en consultas y/o cirugías.',
                'condiciones' => 'Descuentos no aplicables en centros comerciales.',
                'redsocial' => 'DevlynMX',
                'sitio' => 'https://devlyn.com.mx',
                'telefono' => '55 5104 5528',
                'correo' => 'atencion@devlyn.com.mx',
                'logo' => 'devlyn.png',
                'orden' => 1,
                'activo' => true,
            ]
        );

        // Asociar con ubicaciones existentes usando la tabla pivote
        $ubicaciones = Ubicacion::whereIn('nombre', [
            'CDMX',
            'Guadalajara',
            'Monterrey',
            'Puebla',
            'Querétaro'
        ])->pluck('id');

        $beneficio->ubicaciones()->sync($ubicaciones);

        $this->command->info("Beneficio 'DEVLYN' sembrado correctamente con sus ubicaciones.");
    }
}
