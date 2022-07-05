<?php

use App\Http\Controllers\ArticleController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TicketController;
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
Route::post('events/{event}/payment/email', [EventController::class, 'setupEmail'])->name('events.setup-email');
Route::post('events/{event}/payment/price', [EventController::class, 'preparePrice'])->name('events.prepare-price');
Route::post('events/{event}/payment/verify', [EventController::class, 'verifyPayment'])->name('events.verify-payment');
Route::post('events/{event}/payment/retry', [EventController::class, 'retryPayment'])->name('events.retry-payment');

Route::get('test', function () {
    return view('tickets.view-pdf', ['ticket' => Ticket::all()->first()]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/user/tickets', [TicketController::class, 'index'])->name('user.tickets');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
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

/**
 * Articles
 */
Route::group(['prefix' => 'articles'], function () {
    Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('{article}', [ArticleController::class, 'show'])->name('articles.show');
});

/**
 * Shop - Products
 */
Route::group(['prefix' => 'shop'], function () {
    Route::get('/', [ShopController::class, 'index'])->name('shop.index');
    Route::get('{product}', [ShopController::class, 'show'])->name('shop.show');
    Route::post('{product}/payment/setup', [ShopController::class, 'preparePayment'])->name('shop.prepare-payment');
    Route::post('{product}/payment/verify', [ShopController::class, 'verifyPayment'])->name('shop.verify-payment');
});

/**
 * Tickets
 */
Route::group(['prefix' => 'tickets'], function () {
    Route::post('refund', [TicketController::class, 'refundOrder'])->name('tickets.refund');
});
