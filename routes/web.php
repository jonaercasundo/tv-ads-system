<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AuthController;

Route::get('/api/videos', [VideoController::class, 'index']);
Route::post('/api/videos', [VideoController::class, 'store']);
Route::delete('/api/videos/{id}', [VideoController::class, 'destroy']);
Route::get('/admin', function () {
    return view('admin');
});
Route::delete('/api/videos/{id}', [VideoController::class, 'destroy']);
Route::post('/api/videos/upload-zip', [VideoController::class, 'uploadZip']);
Route::get('/', function () {
    return view('tv');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/admin', function () {

    if (!session('admin_logged_in')) {
        return redirect('/login');
    }

    return view('admin');
});