<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ubicacion;   // ✅ Aquí importas el modelo correctamente


class UbicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $ubicaciones = Ubicacion::orderBy('nombre')->get();
        $ubicaciones = Ubicacion::withCount('beneficios')->orderBy('id')->get();
        return view('admin.ubicaciones.index', compact('ubicaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ubicaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:ubicaciones,nombre',
            'activo' => 'boolean',
        ]);

        $data['activo'] = $request->has('activo') ? 1 : 0;

        Ubicacion::create($data);

        return redirect()->route('admin.ubicaciones.index')
            ->with('success', 'Ubicación creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ubicacion $ubicacion)
    {
        return view('admin.ubicaciones.edit', compact('ubicacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ubicacion $ubicacion)
    {
        $data = $request->validate([
            'nombre' => "required|string|max:255|unique:ubicaciones,nombre,{$ubicacion->id}",
            'activo' => 'boolean',
        ]);

        $data['activo'] = $request->has('activo') ? 1 : 0;

        $ubicacion->update($data);

        return redirect()->route('admin.ubicaciones.index')
            ->with('success', 'Ubicación actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ubicacion $ubicacion)
    {
        // Opcional: evitar borrar si tiene beneficios asociados
        if ($ubicacion->beneficios()->count() > 0) {
            return back()->with('error', 'No se puede eliminar esta ubicación porque tiene beneficios asociados');
        }

        $ubicacion->delete();

        return redirect()->route('admin.ubicaciones.index')
            ->with('success', 'Ubicación eliminada correctamente');
    }
}
