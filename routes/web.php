<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SupporterController;

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
})->name('welcome');

Route::middleware(['auth'])->prefix("admin")->group(function() {
    Route::get("/", function () {
        return redirect()->route('dashboard');
    })->name('home');

    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put("users/{user}/activate", [UserController::class, 'activate'])->name('users.activate');

    Route::middleware("role:admin")->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('sites', SiteController::class);
    });


    Route::middleware("site")->prefix("sites")->group(function () {
        Route::prefix("{site}")->group(function() {
            Route::put('supporters/{supporter}/activate', [SupporterController::class, 'activate'])->name('supporters.activate');
            Route::put('supporters/{supporter}/deactivate', [SupporterController::class, 'deactivate'])->name('supporters.deactivate');
            Route::get('supporters/export', [SupporterController::class, "export"])->name('supporters.export');
            Route::resource('supporters', SupporterController::class);
        });
    });
});

Route::prefix("wp")->group(function() {
    Route::post("new-site", [SiteController::class, 'instantiate'])->name('new-site');
    Route::post("user", [UserController::class, 'WPUser']);
});


require __DIR__.'/auth.php';
