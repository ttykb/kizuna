<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ConfigController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AttendanceController::class, 'index']);
Route::post('/', [AttendanceController::class, 'index']);
Route::post('/edit', [AttendanceController::class, 'edit']);
Route::get('/config', [ConfigController::class, 'index']);
Route::post('/config', [ConfigController::class, 'show']);
Route::get('/config/edit', function() {return redirect('/config');});
Route::post('/config/edit', [ConfigController::class, 'edit']);
