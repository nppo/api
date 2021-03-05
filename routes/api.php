<?php

declare(strict_types=1);

use App\Http\Controllers\EntityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ThemeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'as' => 'api.',
], function (): void {
    Route::get('search', [SearchController::class, 'search'])->name('search');
    Route::get('statistics/entities', [StatisticsController::class, 'entities'])->name('statistics.entities');

    Route::resource('themes', ThemeController::class)->only(['index']);
    Route::resource('types', EntityController::class)->only(['index']);
    Route::resource('products', ProductController::class)->only(['index', 'show']);
    Route::resource('projects', ProjectController::class)->only(['show', 'update']);
    Route::resource('people', PersonController::class)->only(['show']);
    Route::resource('parties', PartyController::class)->only(['show']);

    Route::get('discover', [HomeController::class, 'discover'])->name('discover');
});
