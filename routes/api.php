<?php

use App\Http\Controllers\Api\CategoryController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'category',
    'as' => 'category.',
//    'middleware' => ['auth:sanctum']
], function () {
    Route::get('/', [CategoryController::class,'data']);
    Route::post('/', [CategoryController::class,'store'])->name('store');
    Route::delete('/', [CategoryController::class,'delete'])->name('delete');
});