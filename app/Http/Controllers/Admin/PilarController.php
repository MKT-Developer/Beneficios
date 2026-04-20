<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pilar;
use App\Models\Pais;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PilarController extends Controller
{
    /**
     * LISTADO
     */
    public function index()
    {
        $pilares = Pilar::with('pais')
            ->orderBy('orden')
            ->get();

        return view('admin.pilares.index', compact('pilares'));
    }

    /**
     * CREATE FORM
     */
    public function create()
    {
        $paises = Pais::where('activo', true)
            ->orderBy('nombre')
            ->get();

        $siguienteOrden = (Pilar::max('orden') ?? 0) + 1;

        return view('admin.pilares.create', compact('paises', 'siguienteOrden'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        try {

            $data = $this->validateData($request);

            $data['icono'] = $this->handleIconUpload($request, null, $data['nombre']);
            $data['slug'] = Str::slug($data['nombre']);

            Pilar::create($data);

            return redirect()
                ->route('admin.pilares.index')
                ->with('success', 'Pilar creado correctamente');
        } catch (Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo crear el pilar. Intenta nuevamente.');
        }
    }

    /**
     * EDIT
     */
    public function edit(Pilar $pilar)
    {
        $paises = Pais::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('admin.pilares.edit', compact('pilar', 'paises'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, Pilar $pilar)
    {
        try {

            $data = $this->validateData($request);

            $data['icono'] = $this->handleIconUpload($request, $pilar->icono, $data['nombre']);
            $data['slug'] = Str::slug($data['nombre']);

            $pilar->update($data);

            return redirect()
                ->route('admin.pilares.index')
                ->with('success', 'Pilar actualizado correctamente');
        } catch (Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el pilar.');
        }
    }

    /**
     * DELETE
     */
    public function destroy(Pilar $pilar)
    {
        try {

            if ($pilar->icono) {
                Storage::disk('public')->delete('pilares/' . $pilar->icono);
            }

            $pilar->delete();

            return redirect()
                ->route('admin.pilares.index')
                ->with('success', 'Pilar eliminado correctamente');
        } catch (Throwable $e) {

            return redirect()
                ->back()
                ->with('error', 'No se pudo eliminar el pilar.');
        }
    }

    /**
     * TOGGLE ACTIVO (AJAX como países)
     */
    public function toggleActivo(Pilar $pilar)
    {
        try {

            $pilar->activo = !$pilar->activo;
            $pilar->save();

            return response()->json([
                'success' => true,
                'activo' => $pilar->activo,
                'message' => 'Estado actualizado correctamente'
            ]);
        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estado'
            ], 500);
        }
    }

    /**
     * VALIDACIÓN CENTRALIZADA
     */
    private function validateData(Request $request)
    {
        $validated = $request->validate([
            'pais_id' => 'required|exists:paises,id',
            'nombre' => 'required|string|max:255',
            'icono' => 'nullable|file|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer',
            'activo' => 'nullable|boolean',
        ]);

        $validated['activo'] = $request->has('activo');

        return $validated;
    }

    /**
     * UPLOAD ICONO (CREAR / UPDATE)
     */
    private function handleIconUpload(Request $request, ?string $oldFile = null, ?string $nombre = null)
    {
        if (!$request->hasFile('icono')) {
            return $oldFile;
        }

        $file = $request->file('icono');

        $filename = Str::slug($nombre ?? 'pilar')
            . '-' . time()
            . '.'
            . $file->getClientOriginalExtension();

        $file->storeAs('pilares', $filename, 'public');

        if ($oldFile) {
            Storage::disk('public')->delete('pilares/' . $oldFile);
        }

        return $filename;
    }
}
