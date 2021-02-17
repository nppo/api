<?php

declare(strict_types=1);

use App\Enumerators\Entities;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ThemeController;
use App\Http\Resources\EntityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
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

    Route::resource('themes', ThemeController::class)->only(['index']);
    Route::get('types', function (): AnonymousResourceCollection {
        return EntityResource::collection(Arr::flatten(Entities::asArray()));
    });
});
