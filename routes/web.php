<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantAnalyzeController;

Route::get('/', [PlantAnalyzeController::class, 'index'])->name('home');
Route::post('/analyze', [PlantAnalyzeController::class, 'analyze'])->name('analyze');
