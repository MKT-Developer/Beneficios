<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Admin\PaisController;
use App\Http\Controllers\Admin\PilarController;
use App\Http\Controllers\Admin\BeneficioController;
use App\Http\Controllers\Admin\UbicacionController;

use App\Http\Controllers\Admin\ToggleController;

use App\Http\Controllers\WidgetController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH (GUEST)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    /*
    |--------------------------------------------------------------------------
    | PASSWORD RESET
    |--------------------------------------------------------------------------
    */

    Route::get('forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('forgot-password', function (Request $request) {

        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    })->name('password.email');

    Route::get('reset-password/{token}', function ($token, Request $request) {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    })->name('password.reset');

    Route::post('reset-password', function (Request $request) {

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Contraseña actualizada correctamente')
            : back()->withErrors(['email' => __($status)]);
    })->name('password.store');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED AREA
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| ADMIN MODULE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('pais', PaisController::class)
            ->parameters(['pais' => 'pais']);

        Route::resource('pilares', PilarController::class)
            ->parameters(['pilares' => 'pilar']);

        Route::resource('beneficios', BeneficioController::class)
            ->parameters(['beneficios' => 'beneficio']);

        Route::resource('ubicaciones', UbicacionController::class)
            ->parameters(['ubicaciones' => 'ubicacion']);

        Route::patch('/toggle/{model}/{id}', ToggleController::class)
            ->name('admin.toggle');

        Route::get('widget', function () {
            return view('admin.widget.index');
        })->name('widget.index');
    });

/*
|--------------------------------------------------------------------------
| WIDGET
|--------------------------------------------------------------------------
*/


Route::get('/widget', [WidgetController::class, 'index'])
    ->middleware('allow.iframe');
// require __DIR__ . '/auth.php';


// use Illuminate\Support\Facades\Mail;

// Route::get('/test-mail', function () {
//     Mail::raw('Correo de prueba desde Laravel 🚀', function ($message) {
//         $message->to('jtcastro98@gmail.com')
//             ->subject('Test Mail Laravel');
//     });

//     return 'Correo enviado';
// });
