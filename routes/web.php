<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\SalaryController;
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

Route::get('/', [AttendanceController::class, 'index'])->name('attendance');
Route::post('/', [AttendanceController::class, 'index'])->name('attendance');
Route::post('/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');

Route::get('/summary/employee', [SummaryController::class, 'index'])->name('summary.employee');
Route::post('/summary/employee', [SummaryController::class, 'index'])->name('summary.employee');
Route::get('/summary/daily', [SummaryController::class, 'index'])->name('summary.daily');
Route::post('/summary/daily', [SummaryController::class, 'index'])->name('summary.daily');
Route::get('/summary', function() {return redirect('/summary/employee');})->name('summary');

Route::get('/salary', [SalaryController::class, 'index'])->name('salary');
Route::post('/salary', [SalaryController::class, 'index'])->name('salary');

Route::get('/config', [ConfigController::class, 'index'])->name('config');
Route::post('/config', [ConfigController::class, 'show'])->name('config');
Route::get('/config/edit', function() {return redirect('/config');})->name('config.edit');
Route::post('/config/edit', [ConfigController::class, 'edit'])->name('config.edit');
