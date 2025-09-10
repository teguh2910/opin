<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\OpinController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::get('/opin/target', [OpinController::class, 'target'])->name('opin.target');
    Route::resource('opin', OpinController::class);
    Route::resource('bom', BomController::class);
    Route::resource('component', ComponentController::class);
    Route::get('component/upload/excel', [ComponentController::class, 'showUploadForm'])->name('component.upload.form');
    Route::post('component/upload/excel', [ComponentController::class, 'uploadExcel'])->name('component.upload.excel');
    Route::get('component/download/template', [ComponentController::class, 'downloadTemplate'])->name('component.download.template');
});
