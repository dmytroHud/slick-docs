<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('spaces')->group(function () {
    Route::get('/', [SpaceController::class, 'index'])->name('space.index');
    Route::get('/create', [SpaceController::class, 'create'])->name('space.create');
    Route::get('/{slug}', [SpaceController::class, 'single'])->name('space.single');
    Route::get('/{slug}/edit', [SpaceController::class, 'edit'])->name('space.edit');
    Route::patch('/{slug}', [SpaceController::class, 'update'])->name('space.update');
    Route::post('/store', [SpaceController::class, 'store'])->name('space.store');
})->name('space');

require __DIR__.'/auth.php';
