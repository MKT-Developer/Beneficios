<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';

    protected $fillable = [
        'nombre', 
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    protected $attributes = [
        'activo' => true
    ];

    public function beneficios()
    {
        return $this->belongsToMany(Beneficio::class, 'beneficio_ubicacion');
    }
}
