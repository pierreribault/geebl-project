<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\Views\LandingViewController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', [LandingViewController::class, 'index'])->name('index');

/**
 * Events
 */
Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
Route::post('events/{event}/payment/setup', [EventController::class, 'preparePayment'])->name('events.prepare-payment');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
