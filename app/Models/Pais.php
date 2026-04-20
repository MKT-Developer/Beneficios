<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;

    protected $table = 'paises';

    protected $fillable = [
        'codigo',
        'flag',
        'nombre',
        'activo',
    ];

    // Relaciones futuras
    public function pilares()
    {
        return $this->hasMany(Pilar::class);
    }
}
