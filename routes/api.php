<?php

declare(strict_types=1);

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLikeController;
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

Route::group([
    'as'         => 'api.',
    'middleware' => 'auth:api',
], function (): void {
    Route::get('user', [UserController::class, 'current']);
});

Route::group([
    'as'         => 'api.',
    'middleware' => ['identify:api'],
], function (): void {
    Route::get('search', [SearchController::class, 'search'])->name('search');
    Route::get('statistics/entities', [StatisticsController::class, 'entities'])->name('statistics.entities');
    Route::get('discover', [HomeController::class, 'discover'])->name('discover');

    Route::resource('roles', RoleController::class)->only(['index']);

    Route::resource('product-types', ProductTypeController::class)->only(['index']);
    Route::resource('themes', ThemeController::class)->only(['index', 'store', 'update', 'show']);
    Route::resource('types', EntityController::class)->only(['index']);
    Route::resource('products', ProductController::class)->only(['index', 'show', 'update', 'store', 'destroy']);
    Route::resource('projects', ProjectController::class)->only(['show', 'store', 'update', 'create', 'destroy']);
    Route::resource('people', PersonController::class)->only(['show', 'store', 'update', 'index']);
    Route::resource('parties', PartyController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::resource('skills', SkillController::class)->only(['index']);
    Route::resource('keywords', KeywordController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::resource('articles', ArticleController::class)->only(['show']);
    Route::resource('users', UserController::class)->only(['index', 'update', 'show', 'destroy', 'store']);
    Route::resource('users.likes', UserLikeController::class)->only(['index', 'store']);

    Route::get('products/{id}/download', [ProductController::class, 'download'])
        ->name('products.download');
});
