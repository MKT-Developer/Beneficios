<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pais;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PaisController extends Controller
{
    public function index()
    {
        $paises = Pais::orderBy('nombre')->get();
        return view('admin.pais.index', compact('paises'));
    }

    public function create()
    {
        return view('admin.pais.create');
    }

    public function store(Request $request)
    {
        try {

            $data = $this->validateData($request);

            $data['flag'] = $this->handleFlagUpload($request, null, $data['nombre']);

            Pais::create($data);

            return redirect()
                ->route('admin.pais.index')
                ->with('success', 'País creado correctamente');
        } catch (Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo crear el país. Intenta nuevamente.');
        }
    }

    public function edit(Pais $pais)
    {
        return view('admin.pais.edit', compact('pais'));
    }

    public function update(Request $request, Pais $pais)
    {
        try {

            $data = $this->validateData($request);

            $data['flag'] = $this->handleFlagUpload($request, $pais->flag, $data['nombre']);

            $pais->update($data);

            return redirect()
                ->route('admin.pais.index')
                ->with('success', 'País actualizado correctamente');
        } catch (Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el país.');
        }
    }

    public function destroy(Pais $pais)
    {
        try {

            if ($pais->flag) {
                Storage::disk('public')->delete('flags/' . $pais->flag);
            }

            $pais->delete();

            return redirect()
                ->route('admin.pais.index')
                ->with('success', 'País eliminado correctamente');
        } catch (Throwable $e) {

            return redirect()
                ->back()
                ->with('error', 'No se pudo eliminar el país.');
        }
    }

    /**
     * VALIDACIÓN + NORMALIZACIÓN
     */
    private function validateData(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10',
            'flag'   => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'activo' => 'nullable|boolean',
        ]);

        $validated['activo'] = $request->has('activo');

        return $validated;
    }

    /**
     * UPLOAD DE BANDERA (CREAR / UPDATE)
     */
    private function handleFlagUpload(Request $request, ?string $oldFile = null, ?string $nombre = null)
    {
        if (!$request->hasFile('flag')) {
            return $oldFile;
        }

        $file = $request->file('flag');

        $filename = Str::slug($nombre ?? 'pais')
            . '-' . time()
            . '.' . $file->getClientOriginalExtension();

        $file->storeAs('flags', $filename, 'public');

        // borrar anterior si existe
        if ($oldFile) {
            Storage::disk('public')->delete('flags/' . $oldFile);
        }

        return $filename;
    }

    /**
     * TOGGLE ACTIVO (AJAX)
     */
    public function toggleActivo(Pais $pais)
    {
        try {
            $pais->activo = !$pais->activo;
            $pais->save();

            return response()->json([
                'success' => true,
                'activo' => $pais->activo,
                'message' => 'Estado actualizado correctamente'
            ]);
        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estado'
            ], 500);
        }
    }
}
