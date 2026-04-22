<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficio extends Model
{
    // use HasFactory;
    protected $table = 'beneficios';

    protected $fillable = [
        'pilar_id',
        'nombre',
        'descripcion',
        'beneficios',
        'condiciones',
        'redsocial',
        'sitio',
        'telefono',
        'correo',
        'logo',
        'orden',
        'activo',
    ];

    public function pilar()
    {
        return $this->belongsTo(Pilar::class);
    }

    public function ubicaciones()
    {
        return $this->belongsToMany(Ubicacion::class, 'beneficio_ubicacion');
    }
}
