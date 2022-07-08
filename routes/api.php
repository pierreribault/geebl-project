<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\TicketController;

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

/**
 * Events
 */
Route::get('events/search', [EventController::class, 'search']);

/**
 * Tickets
 */
Route::post('tickets/{ticket}/use', [TicketController::class, 'use']);
Route::post('tickets/{ticket}/refund', [TicketController::class, 'refund']);
Route::post('tickets/{ticket}/transfer', [TicketController::class, 'transfer'])->name('tickets.transfer');

/**
 * Carousels
 */
Route::get('carousels', [CarouselController::class, 'index']);
Route::get('carousels/{key}', [CarouselController::class, 'show']);
