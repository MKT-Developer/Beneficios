<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beneficio;
use App\Models\Pilar;
use App\Models\Ubicacion;
use Illuminate\Http\Request;

class BeneficioController extends Controller
{
    /**
     * Display a listing of the resource.
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

        // IMPORTANTE
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.beneficios.partials.table', compact('beneficios'))->render(),
                'count' => $beneficios->count()
            ]);
        }

        return view('admin.beneficios.index', [
            'beneficios' => $beneficios,
            'paises' => \App\Models\Pais::all(),
            'pilares' => \App\Models\Pilar::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $beneficio = new Beneficio();

        $pilares = Pilar::with('pais')->orderBy('nombre')->get();
        $ubicaciones = Ubicacion::orderBy('nombre')->get();

        return view('admin.beneficios.create', compact('beneficio', 'pilares', 'ubicaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');

            $filename = \Str::slug($request->nombre) . '-' . time() . '.' . $file->getClientOriginalExtension();

            // Guardar en storage/app/public/beneficios
            $file->storeAs('beneficios', $filename, 'public');

            // Solo el nombre en la BD
            $data['logo'] = $filename;
        }

        try {
            $beneficio = Beneficio::create($data);
            $beneficio->ubicaciones()->sync($data['ubicaciones'] ?? []);

            return redirect()->route('admin.beneficios.index')
                ->with('success', 'Beneficio creado correctamente');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Ocurrió un error al crear el beneficio');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Beneficio $beneficio)
    {
        $beneficio->load('ubicaciones');

        $pilares = Pilar::with('pais')->orderBy('nombre')->get();
        $ubicaciones = Ubicacion::orderBy('nombre')->get();

        return view('admin.beneficios.edit', compact('beneficio', 'pilares', 'ubicaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Beneficio $beneficio)
    {
        $data = $this->validateData($request, $beneficio);

        if ($request->hasFile('logo')) {

            // borrar logo anterior
            if ($beneficio->logo && \Storage::disk('public')->exists('beneficios/' . $beneficio->logo)) {
                \Storage::disk('public')->delete('beneficios/' . $beneficio->logo);
            }

            $file = $request->file('logo');

            $filename = \Str::slug($request->nombre) . '-' . time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('beneficios', $filename, 'public');

            $data['logo'] = $filename;
        }

        try {
            $beneficio->update($data);
            $beneficio->ubicaciones()->sync($data['ubicaciones'] ?? []);

            return redirect()->route('admin.beneficios.index')
                ->with('success', 'Beneficio actualizado correctamente');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Ocurrió un error al actualizar el beneficio');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beneficio $beneficio)
    {
        try {
            $beneficio->delete();

            return redirect()->route('admin.beneficios.index')
                ->with('success', 'Beneficio eliminado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al eliminar el beneficio');
        }
    }

    /**
     * Validar datos comunes de store/update.
     */
    protected function validateData(Request $request, Beneficio $beneficio = null)
    {
        $beneficioId = $beneficio->id ?? 'NULL';

        $data = $request->validate([
            'pilar_id'      => 'required|exists:pilares,id',
            'nombre'        => "required|string|max:255|unique:beneficios,nombre,$beneficioId,id",
            'descripcion'   => 'required|string',
            'beneficios'   => 'nullable|string',
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

        // Garantizar que 'activo' siempre tenga valor 0 o 1
        $data['activo'] = $request->input('activo', 0);

        // Orden mínimo 0
        $data['orden'] = $data['orden'] ?? 0;

        return $data;
    }

    /**
     * Mostrar los datos en el modal.
     */
    public function show(Beneficio $beneficio)
    {
        $beneficio->load(['pilar.pais', 'ubicaciones']);

        return response()->json([
            'id' => $beneficio->id,
            'nombre' => $beneficio->nombre,
            'logo' => $beneficio->logo,

            // GENERAL
            'pilar' => $beneficio->pilar?->nombre,
            'pais' => $beneficio->pilar?->pais?->nombre,

            // DETALLE
            'descripcion' => $beneficio->descripcion,
            'beneficios' => $beneficio->beneficios,
            'condiciones' => $beneficio->condiciones,

            // CONTACTO
            'correo' => $beneficio->correo,
            'telefono' => $beneficio->telefono,
            'sitio' => $beneficio->sitio,
            'redsocial' => $beneficio->redsocial,

            // OPERATIVO
            'ubicaciones' => $beneficio->ubicaciones->pluck('nombre'),
            // 'ubicaciones' => $beneficio->ubicaciones
            //     ->pluck('nombre')
            //     ->take(5)
            //     ->implode(', ') ?: 'Sin ubicaciones',

            'activo' => $beneficio->activo,
            'orden' => $beneficio->orden,
        ]);
    }
}
