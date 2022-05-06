<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::middleware('auth')->get('/dashboard', [Controller::class, 'Dashboard']);

Route::middleware('auth')->get('/upload', [Controller::class, 'UploadFile']);
Route::middleware('auth')->post('/upload', [Controller::class, 'UploadFilePost']);

Route::middleware('auth')->get('/editor/{id}', [Controller::class, 'Editor']);
Route::middleware('auth')->post('/editor/{id}', [Controller::class, 'EditorSave']);

Route::middleware('auth')->get('/download/{id}', [Controller::class, 'DownloadFile']);

require __DIR__.'/auth.php';
