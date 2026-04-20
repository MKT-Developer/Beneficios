<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilar extends Model
{
    use HasFactory;

    protected $table = 'pilares';

    protected $fillable = [
        'pais_id',
        'nombre',
        'slug',
        'icono',
        'descripcion',
        'orden',
        'activo',
    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }

    public function beneficios()
    {
        return $this->hasMany(Beneficio::class);
    }
}
