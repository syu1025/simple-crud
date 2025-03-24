<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalorieRecordController;
use App\Models\CalorieRecord;

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

Route::get('/', [CalorieRecordController::class, "index"])
    ->name('records.index');

Route::get('/calorie_records/{date}', [CalorieRecordController::class, "show"])
    ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
    ->name('records.show');

Route::get('/calorie_records/create' , [CalorieRecordController::class,'create'])
    ->name('records.create');

Route::post('/calorie_records', [CalorieRecordController::class, 'store'])
    ->name('records.store');

Route::get('/calorie_records/{id}/edit', [CalorieRecordController::class, 'edit'])
    ->name('records.edit');

Route::put('/calorie_records/{id}', [CalorieRecordController::class, 'update'])
    ->name('records.update');

Route::delete('/calorie_records/{id}', [CalorieRecordController::class, "destroy"])
    ->name('records.destroy');
