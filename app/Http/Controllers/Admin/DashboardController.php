<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pais;
use App\Models\Pilar;
use App\Models\Beneficio;
use App\Models\Ubicacion;
// use App\Models\User;


class DashboardController extends Controller
{
    public function index()
    {
        $pilaresActivos = Pilar::where('activo', 1)->count();
        $beneficiosTotales = Beneficio::count();
        $paisesTotales = Pais::where('activo', 1)->count();
        $ubicacionesTotales = Ubicacion::where('activo', 1)->count();
        // $usuariosActivos = User::where('activo', 1)->count();

        return view('admin.dashboard', compact(
            'pilaresActivos',
            'beneficiosTotales',
            'paisesTotales',
            'ubicacionesTotales'
        ));
        // ,'usuariosActivos'
    }
}
