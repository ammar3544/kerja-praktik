<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BuzzerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| AREA PUBLIK (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/

// Halaman Public
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');



/*
|--------------------------------------------------------------------------
| AREA PRIVAT (Hanya untuk User yang sudah Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard & Home
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Monitoring & Management Tasks
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::delete('/tasks/bulk-delete', [TaskController::class, 'bulkDestroy'])->name('tasks.bulkDestroy');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');     
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/analysis', [TaskController::class, 'analysis'])->name('tasks.analysis');


    // Scraper Engine
    Route::get('/scraper', [ScraperController::class, 'index'])->name('scraper.page');
    Route::get('/scraper/result/{task_id}', [ScraperController::class, 'result'])->name('scraper.result');
    Route::post('/scraper/run', [ScraperController::class, 'scrape'])->name('scraper.run');

    // Analysis & Buzzer Engine
    Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis');
    Route::get('/analysis/data', [AnalysisController::class, 'data']);
    Route::get('/buzzer/{task}', [BuzzerController::class, 'analyze'])->name('buzzer.analyze');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::delete('/reports/delete/{task}', [ReportController::class, 'delete'])->name('reports.delete');
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::delete('/{task_id}', [ReportController::class, 'destroy'])->name('reports.delete');
    Route::delete('/bulk/delete', [ReportController::class, 'bulkDelete'])->name('reports.bulkDelete');

    // Auth Action
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});