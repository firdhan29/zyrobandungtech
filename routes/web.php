<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{project}/progress', [ProjectController::class, 'updateProgress'])->name('projects.updateProgress');
    Route::post('projects/{project}/assign-team', [ProjectController::class, 'assignTeam'])->name('projects.assignTeam');
    Route::delete('projects/{project}/remove-team/{team}', [ProjectController::class, 'removeTeam'])->name('projects.removeTeam');
    
    Route::resource('teams', TeamController::class);
    Route::resource('payments', PaymentController::class)->only(['store', 'destroy']);
    
    Route::resource('clients', ClientController::class);
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
});


Auth::routes();
// require __DIR__.'/auth.php';