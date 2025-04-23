<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Frontend\PackageSubscribeController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified','rolemanager:admin'])->name('admin.dashboard');
Route::get('user/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified','rolemanager:user'])->name('user.dashboard');
//checklist routes
Route::middleware(['auth', 'verified','rolemanager:admin'])->group(function () {
Route::get('/checklist', [ChecklistController::class, 'index'])->name('checklist.index');
Route::get('/checklist/list', [ChecklistController::class, 'list'])->name('checklist.list');
Route::post('/checklist/store', [ChecklistController::class, 'store'])->name('checklist.store');
Route::get('/checklist/edit/{id}', [ChecklistController::class, 'edit'])->name('checklist.edit');
Route::post('/checklist/update/{id}', [ChecklistController::class, 'update'])->name('checklist.update');
Route::delete('/checklist/delete/{id}', [ChecklistController::class, 'destroy'])->name('checklist.delete');
});
// packages routes



Route::middleware(['auth', 'verified','rolemanager:admin'])->group(function () {

Route::prefix('packages')->group(function () {
    Route::get('/', [PackageController::class, 'index'])->name('package.index');
    Route::get('/list', [PackageController::class, 'list'])->name('package.list');
    Route::post('/store', [PackageController::class, 'store'])->name('package.store');
    Route::get('/edit/{id}', [PackageController::class, 'edit'])->name('package.edit');
    Route::post('/update/{id}', [PackageController::class, 'update'])->name('package.update');
    Route::delete('/delete/{id}', [PackageController::class, 'destroy'])->name('package.delete');
});
});
// subscribers routes
Route::post('/subscribe', [SubscriptionController::class, 'store'])->name('subscribe.store');

//to show the packages on the frontend

Route::get('/subscribe/packages', [PackageSubscribeController::class, 'showPackages'])->name('frontend.packages');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//subscriber routes
Route::middleware(['auth', 'verified','rolemanager:admin'])->group(function () {
Route::get('/subscribers', [SubscriptionController::class, 'index'])->name('subscribers.index');
Route::delete('/subscribers/{id}', [SubscriptionController::class, 'destroy'])->name('subscribers.destroy');
Route::get('/subscribers/{id}/edit', [SubscriptionController::class, 'edit'])->name('subscribers.edit');
Route::put('/subscribers/{id}', [SubscriptionController::class, 'update'])->name('subscribers.update');
});

require __DIR__.'/auth.php';
