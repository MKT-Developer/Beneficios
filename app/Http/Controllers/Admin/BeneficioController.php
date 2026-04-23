<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beneficio;
use App\Models\Pilar;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BeneficioController extends Controller
{
    /**
     * LISTADO
     */
    public function index(Request $request)
    {
        $query = Beneficio::with(['pilar.pais', 'ubicaciones']);

        if ($request->search) {
            $query->where('nombre', 'like', "%{$request->search}%");
        }

        if ($request->pais) {
            $query->whereHas('pilar.pais', function ($q) use ($request) {
                $q->where('id', $request->pais);
            });
        }

        if ($request->pilar) {
            $query->where('pilar_id', $request->pilar);
        }

        $beneficios = $query->orderBy('orden')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.beneficios.partials.table', compact('beneficios'))->render(),
                'count' => $beneficios->count()
            ]);
        }

        return view('admin.beneficios.index', [
            'beneficios' => $beneficios,
            'paises' => \App\Models\Pais::all(),
            'pilares' => Pilar::all()
        ]);
    }

    /**
     * CREATE
     */
    public function create()
    {
        $pilares = Pilar::with('pais')->orderBy('nombre')->get();
        $ubicaciones = Ubicacion::orderBy('nombre')->get();

        return view('admin.beneficios.create', compact('pilares', 'ubicaciones'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        try {

            $data = $this->validateData($request);

            $data['logo'] = $this->handleLogoUpload(
                $request,
                null,
                $data['nombre']
            );

            Beneficio::create($data)
                ->ubicaciones()
                ->sync($data['ubicaciones'] ?? []);

            return redirect()
                ->route('admin.beneficios.index')
                ->with('success', 'Beneficio creado correctamente');
        } catch (Throwable $e) {

            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear el beneficio');
        }
    }

    /**
     * EDIT
     */
    public function edit(Beneficio $beneficio)
    {
        $beneficio->load('ubicaciones');

        $pilares = Pilar::with('pais')->orderBy('nombre')->get();
        $ubicaciones = Ubicacion::orderBy('nombre')->get();

        return view('admin.beneficios.edit', compact('beneficio', 'pilares', 'ubicaciones'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, Beneficio $beneficio)
    {
        try {

            $data = $this->validateData($request, $beneficio);

            $data['logo'] = $this->handleLogoUpload(
                $request,
                $beneficio->logo,
                $data['nombre']
            );

            $beneficio->update($data);
            $beneficio->ubicaciones()->sync($data['ubicaciones'] ?? []);

            return redirect()
                ->route('admin.beneficios.index')
                ->with('success', 'Beneficio actualizado correctamente');
        } catch (Throwable $e) {

            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el beneficio');
        }
    }

    /**
     * DELETE
     */
    public function destroy(Beneficio $beneficio)
    {
        try {

            if ($beneficio->logo) {
                Storage::disk('public')->delete('beneficios/' . $beneficio->logo);
            }

            $beneficio->delete();

            return redirect()
                ->route('admin.beneficios.index')
                ->with('success', 'Beneficio eliminado correctamente');
        } catch (Throwable $e) {

            return back()
                ->with('error', 'Ocurrió un error al eliminar el beneficio');
        }
    }

    /**
     * SHOW (modal)
     */
    public function show(Beneficio $beneficio)
    {
        $beneficio->load(['pilar.pais', 'ubicaciones']);

        return response()->json([
            'id' => $beneficio->id,
            'nombre' => $beneficio->nombre,
            'logo' => $beneficio->logo,

            'pilar' => $beneficio->pilar?->nombre,
            'pais' => $beneficio->pilar?->pais?->nombre,

            'descripcion' => $beneficio->descripcion,
            'beneficios' => $beneficio->beneficios,
            'condiciones' => $beneficio->condiciones,

            'correo' => $beneficio->correo,
            'telefono' => $beneficio->telefono,
            'sitio' => $beneficio->sitio,
            'redsocial' => $beneficio->redsocial,

            'ubicaciones' => $beneficio->ubicaciones->pluck('nombre'),
            'activo' => $beneficio->activo,
            'orden' => $beneficio->orden,
        ]);
    }

    /**
     * VALIDACIÓN CENTRALIZADA
     */
    private function validateData(Request $request, Beneficio $beneficio = null)
    {
        $beneficioId = $beneficio->id ?? 'NULL';

        $data = $request->validate([
            'pilar_id'      => 'required|exists:pilares,id',
            'nombre'        => "required|string|max:255|unique:beneficios,nombre,$beneficioId,id",
            'descripcion'   => 'required|string',
            'beneficios'    => 'nullable|string',
            'condiciones'   => 'nullable|string',
            'redsocial'     => 'nullable|string|max:255',
            'sitio'         => 'nullable|url|max:255',
            'telefono'      => 'nullable|string|max:50',
            'correo'        => 'nullable|email|max:255',
            'logo'          => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'orden'         => 'nullable|integer|min:0',
            'activo'        => 'boolean',
            'ubicaciones'   => 'nullable|array',
            'ubicaciones.*' => 'exists:ubicaciones,id',
        ]);

        $data['activo'] = $request->input('activo', 0);
        $data['orden'] = $data['orden'] ?? 0;

        return $data;
    }

    /**
     * UPLOAD ESTANDARIZADO (igual que pilares)
     */
    private function handleLogoUpload(Request $request, ?string $oldFile = null, ?string $nombre = null)
    {
        if (!$request->hasFile('logo')) {
            return $oldFile;
        }

        $file = $request->file('logo');

        $filename = Str::slug($nombre ?? 'beneficio')
            . '-' . time()
            . '.'
            . $file->getClientOriginalExtension();

        $file->storeAs('beneficios', $filename, 'public');

        if ($oldFile) {
            Storage::disk('public')->delete('beneficios/' . $oldFile);
        }

        return $filename;
    }
}
