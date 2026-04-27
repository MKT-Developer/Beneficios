<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Throwable;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create', [
            'user' => new \App\Models\User()
        ]);
    }

    public function store(Request $request)
    {
        try {

            $data = $this->validateData($request);

            $data['password'] = Hash::make($data['password']);

            User::create($data);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Usuario creado correctamente');
        } catch (Throwable $e) {
            dd($e); // Para ver el error real
            // return back()
            //     ->withInput()
            //     ->with('error', 'No se pudo crear el usuario');
        }
    }

    public function edit(User $user)
    {
        if ($user->isLocked()) {
            return back()->with('error', 'Este usuario no puede ser editado');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {

            if ($user->isLocked()) {
                return back()->with('error', 'Este usuario no puede ser modificado');
            }

            $data = $this->validateData($request, $user);

            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Usuario actualizado correctamente');
        } catch (Throwable $e) {
            dd($e); // Para ver el error real
            // return back()
            //     ->withInput()
            //     ->with('error', 'No se pudo actualizar el usuario');
        }
    }

    public function destroy(User $user)
    {
        try {

            /** @var \App\Models\User $authUser */
            $authUser = auth()->user();

            if ($user->id === $authUser->id) {
                return back()->with('error', 'No puedes eliminar tu propio usuario');
            }

            if ($user->isLocked()) {
                return back()->with('error', 'Este usuario está protegido');
            }

            // if (!$authUser->canDeleteUsers()) {
            //     abort(403);
            // }

            if (!$authUser->canDelete()) {
                abort(403);
            }

            $user->delete();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Usuario eliminado correctamente');
        } catch (Throwable $e) {
            dd($e); // ara ver el error real
            // return back()->with('error', 'No se pudo eliminar el usuario');
        }
    }

    private function validateData(Request $request, User $user = null)
    {
        $userId = $user->id ?? null;

        $rules = [
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId)
            ],

            'role' => [
                'required',
                Rule::in([
                    User::ROLE_SUPERADMIN,
                    User::ROLE_ADMIN,
                    User::ROLE_EDITOR
                ])
            ],

            'password' => $user ? 'nullable|min:6' : 'required|min:6',
        ];

        $data = $request->validate($rules);

        // AHORA sí puedes usar $data
        if (!auth()->user()->isSuperAdmin() && $data['role'] === User::ROLE_SUPERADMIN) {
            abort(403);
        }

        return $data;
    }
}
