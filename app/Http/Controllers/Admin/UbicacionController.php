<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Throwable;

class UbicacionController extends Controller
{
    /**
     * LISTADO
     */
    public function index()
    {
        $ubicaciones = Ubicacion::withCount('beneficios')
            ->orderBy('id')
            ->get();

        return view('admin.ubicaciones.index', compact('ubicaciones'));
    }

    /**
     * CREATE
     */
    public function create()
    {
        return view('admin.ubicaciones.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        try {

            $data = $this->validateData($request);

            Ubicacion::create($data);

            return redirect()
                ->route('admin.ubicaciones.index')
                ->with('success', 'Ubicación creada correctamente');
        } catch (Throwable $e) {

            return back()
                ->withInput()
                ->with('error', 'No se pudo crear la ubicación');
        }
    }

    /**
     * EDIT
     */
    public function edit(Ubicacion $ubicacion)
    {
        return view('admin.ubicaciones.edit', compact('ubicacion'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, Ubicacion $ubicacion)
    {
        try {

            $data = $this->validateData($request, $ubicacion);

            $ubicacion->update($data);

            return redirect()
                ->route('admin.ubicaciones.index')
                ->with('success', 'Ubicación actualizada correctamente');
        } catch (Throwable $e) {

            return back()
                ->withInput()
                ->with('error', 'No se pudo actualizar la ubicación');
        }
    }

    /**
     * DELETE
     */
    public function destroy(Ubicacion $ubicacion)
    {
        try {

            /** @var \App\Models\User $authUser */
            $authUser = auth()->user();
            if (!$authUser->canDelete()) {
                abort(403);
            }

            if ($ubicacion->beneficios()->count() > 0) {
                return back()->with('error', 'No se puede eliminar esta ubicación porque tiene beneficios asociados');
            }

            $ubicacion->delete();

            return redirect()
                ->route('admin.ubicaciones.index')
                ->with('success', 'Ubicación eliminada correctamente');
        } catch (Throwable $e) {

            return back()->with('error', 'No se pudo eliminar la ubicación');
        }
    }

    /**
     * VALIDACIÓN CENTRALIZADA
     */
    private function validateData(Request $request, Ubicacion $ubicacion = null)
    {
        $ubicacionId = $ubicacion->id ?? 'NULL';

        return $request->validate([
            'nombre' => "required|string|max:255|unique:ubicaciones,nombre,$ubicacionId,id",
            'activo' => 'boolean',
        ]);
    }
}
