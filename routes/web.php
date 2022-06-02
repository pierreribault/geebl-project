<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\Views\LandingViewController;
use App\Models\Ticket;

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

Route::get('/', [LandingViewController::class, 'home'])->name('home');

/**
 * Events
 */
Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
Route::post('events/{event}/payment/setup', [EventController::class, 'preparePayment'])->name('events.prepare-payment');
Route::post('events/{event}/payment/email', [EventController::class, 'setupEmail'])->name('events.prepare-payment');
Route::post('events/{event}/payment/price', [EventController::class, 'preparePrice'])->name('events.prepare-price');

Route::get('test', function () {
    return view('tickets.view-pdf', ['ticket' => Ticket::all()->first()]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

/**
 * Organizers
 */
Route::group(['prefix' => 'organizers'], function () {
    Route::get('validator', [OrganizerController::class, 'validator']);
    Route::post('validator', [OrganizerController::class, 'capture']);
});

/**
 * Artists
 */
Route::group(['prefix' => 'artists'], function () {
    Route::get('{artist}', [ArtistController::class, 'show']);
});