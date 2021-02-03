<?php

declare(strict_types=1);

use App\Http\Controllers\ProductSearchController;
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
    'as'         => 'api.',
    'middleware' => ['auth:api'],
], function (): void {
    Route::get('products/search', ProductSearchController::class)->name('products.search');
});
