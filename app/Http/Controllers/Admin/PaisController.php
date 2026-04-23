<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Validation\Rule;

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

            $data['flag'] = $this->handleFlagUpload(
                $request,
                null,
                $data['nombre']
            );

            Pais::create($data);

            return redirect()
                ->route('admin.pais.index')
                ->with('success', 'País creado correctamente');
        } catch (Throwable $e) {

            return back()
                ->withInput()
                ->with('error', 'No se pudo crear el país.');
        }
    }

    public function edit(Pais $pais)
    {
        return view('admin.pais.edit', compact('pais'));
    }

    public function update(Request $request, Pais $pais)
    {
        try {

            $data = $this->validateData($request, $pais);

            $data['flag'] = $this->handleFlagUpload(
                $request,
                $pais->flag,
                $data['nombre']
            );

            $pais->update($data);

            return redirect()
                ->route('admin.pais.index')
                ->with('success', 'País actualizado correctamente');
        } catch (Throwable $e) {

            return back()
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

            return back()
                ->with('error', 'No se pudo eliminar el país.');
        }
    }

    /**
     * VALIDACIÓN UNIFICADA
     */
    private function validateData(Request $request, Pais $pais = null)
    {
        $paisId = $pais->id ?? 'NULL';

        return $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[\pL\s\-]+$/u'
            ],

            'codigo' => [
                'required',
                'string',
                'min:2',
                'max:5',
                'regex:/^[a-z]{2,5}$/',
                Rule::unique('paises', 'codigo')->ignore($paisId)
            ],

            'flag' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            'activo' => [
                'nullable',
                'boolean'
            ],
        ]);
    }

    /**
     * UPLOAD ESTANDARIZADO
     */
    private function handleFlagUpload(Request $request, ?string $oldFile = null, ?string $nombre = null)
    {
        if (!$request->hasFile('flag')) {
            return $oldFile;
        }

        $file = $request->file('flag');

        $filename = Str::slug($nombre ?? 'pais')
            . '-' . time()
            . '.'
            . $file->getClientOriginalExtension();

        $file->storeAs('flags', $filename, 'public');

        if ($oldFile) {
            Storage::disk('public')->delete('flags/' . $oldFile);
        }

        return $filename;
    }
}
