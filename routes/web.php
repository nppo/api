<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (App::environment('local')) {
    Route::get('login', function () {
        Auth::login(User::first());

        return redirect()->intended();
    })->name('login');
} else {
    Route::get('login', [AuthController::class, 'login'])
        ->name('login');

    Route::get('surfconext', [AuthController::class, 'surfconext']);
}

Route::get('logout', [AuthController::class, 'logout'])
    ->name('logout');
