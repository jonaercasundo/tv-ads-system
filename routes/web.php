<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;

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