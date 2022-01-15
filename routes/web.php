<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\GiftEventsController;
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

Route::get('/', function () {
    return Inertia::render('Welcome');
});


Route::get('edit', function () {
    return Inertia::render('GiftEvent/Edit');
})->name('giftEvents.edit');


Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');
Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');


Route::get('gift-events', [GiftEventsController::class, 'index'])
    ->middleware('auth')
    ->name('home');
Route::post('gift-events', [GiftEventsController::class, 'store'])
    ->middleware('auth')
    ->name('gift_events.store');
Route::get('gift-events/{giftEvent:slug}', [GiftEventsController::class, 'show'])
    ->middleware('auth')
    ->name('gift_events.show');
Route::get('gift-events/{giftEvent:slug}/edit', [GiftEventsController::class, 'edit'])
    ->middleware('auth')
    ->name('gift_events.edit');
Route::patch('gift-events/{giftEvent:slug}/edit', [GiftEventsController::class, 'update'])
    ->middleware('auth')
    ->name('gift_events.update');
Route::delete('gift-events/{giftEvent:slug}/edit', [GiftEventsController::class, 'destroy'])
    ->middleware('auth')
    ->name('gift_events.destroy');
