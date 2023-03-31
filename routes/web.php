<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\ContentTemplateController;
use App\Http\Controllers\PromptController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Content Templates
    Route::get('/content-templates', [ContentTemplateController::class, 'index'])->name('contentTemplates.index');
    Route::get('/content-templates/{id}', [ContentTemplateController::class, 'show'])->name('contentTemplates.show');
    Route::post('/content-templates', [ContentTemplateController::class, 'store'])->name('contentTemplates.store');
    Route::get('/content/{id}', [ContentController::class, 'show'])->name('content');

    // Prompts
    Route::get('/prompts/{id}', [PromptController::class, 'show'])->name('prompts.show');
    Route::get('/prompts', [PromptController::class, 'index'])->name('prompts.index');
    Route::post('/prompts', [PromptController::class, 'store'])->name('prompts.store');
});
