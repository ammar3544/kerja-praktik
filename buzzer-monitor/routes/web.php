<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BuzzerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| MAIN DASHBOARD (FIX: semua lewat controller)
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| BUZZER (FIX: hapus duplicate route)
|--------------------------------------------------------------------------
*/
Route::get('/buzzer/{task}', [BuzzerController::class, 'analyze']);

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| SCRAPER
|--------------------------------------------------------------------------
*/
Route::get('/scraper', [ScraperController::class,'index'])->name('scraper.page');
Route::post('/scraper/run', [ScraperController::class,'scrape'])->name('scraper.run');
Route::get('/scraper/result', [ScraperController::class,'result'])->name('scraper.result');

/*
|--------------------------------------------------------------------------
| ANALYSIS
|--------------------------------------------------------------------------
*/
Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis');
Route::get('/analysis/data', [AnalysisController::class, 'data']);

/*
|--------------------------------------------------------------------------
| REPORT
|--------------------------------------------------------------------------
*/
Route::get('/reports', [ReportController::class, 'index'])->name('reports');
Route::delete('/reports/delete/{task}', [ReportController::class, 'delete'])
    ->name('reports.delete');