<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComunicadoController;
use App\Http\Controllers\ProfessorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Nexus FATEC - Web Routes
|--------------------------------------------------------------------------
*/

// Profile selector & Authentication
Route::get('/', [AuthController::class, 'showSelector'])->name('home');
Route::get('/login/{persona}', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login/{persona}', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Consultation & Student feed (Alunos, Professores, Admins)
Route::middleware('role:aluno,professor,administrador')->group(function () {
    Route::get('/comunicados', [ComunicadoController::class, 'index'])->name('comunicados.index');
    Route::get('/comunicados/{comunicado}', [ComunicadoController::class, 'show'])->name('comunicados.show');
    Route::get('/eventos', [ComunicadoController::class, 'eventos'])->name('eventos.index');
    Route::get('/busca', [ComunicadoController::class, 'busca'])->name('comunicados.busca');
});

// Admin area
Route::middleware('role:administrador')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Turmas management
    Route::post('/turmas', [AdminController::class, 'storeTurma'])->name('turmas.store');
    Route::put('/turmas/{turma}', [AdminController::class, 'updateTurma'])->name('turmas.update');
    Route::delete('/turmas/{turma}', [AdminController::class, 'destroyTurma'])->name('turmas.destroy');
    Route::post('/turmas/{id}/restore', [AdminController::class, 'restoreTurma'])->name('turmas.restore');
    Route::delete('/turmas/{id}/force', [AdminController::class, 'forceDeleteTurma'])->name('turmas.forceDelete');

    // Professores management
    Route::post('/professores', [AdminController::class, 'storeProfessor'])->name('professores.store');
    Route::put('/professores/{user}', [AdminController::class, 'updateProfessor'])->name('professores.update');
    Route::delete('/professores/{user}', [AdminController::class, 'destroyProfessor'])->name('professores.destroy');

    // Alunos management
    Route::post('/alunos', [AdminController::class, 'storeAluno'])->name('alunos.store');
    Route::put('/alunos/{user}', [AdminController::class, 'updateAluno'])->name('alunos.update');
    Route::delete('/alunos/{user}', [AdminController::class, 'destroyAluno'])->name('alunos.destroy');

    // Usuarios lixeira (professores e alunos compartilham)
    Route::post('/usuarios/{id}/restore', [AdminController::class, 'restoreUsuario'])->name('usuarios.restore');
    Route::delete('/usuarios/{id}/force', [AdminController::class, 'forceDeleteUsuario'])->name('usuarios.forceDelete');

    // Comunicados moderation/management
    Route::post('/comunicados', [AdminController::class, 'storeComunicado'])->name('comunicados.store');
    Route::put('/comunicados/{comunicado}', [AdminController::class, 'updateComunicado'])->name('comunicados.update');
    Route::delete('/comunicados/{comunicado}', [AdminController::class, 'destroyComunicado'])->name('comunicados.destroy');
    Route::post('/comunicados/{id}/restore', [AdminController::class, 'restoreComunicado'])->name('comunicados.restore');
    Route::delete('/comunicados/{id}/force', [AdminController::class, 'forceDeleteComunicado'])->name('comunicados.forceDelete');

    // Eventos
    Route::post('/eventos', [AdminController::class, 'storeEvento'])->name('eventos.store');
    Route::delete('/eventos/{evento}', [AdminController::class, 'destroyEvento'])->name('eventos.destroy');
    Route::post('/eventos/{id}/restore', [AdminController::class, 'restoreEvento'])->name('eventos.restore');
    Route::delete('/eventos/{id}/force', [AdminController::class, 'forceDeleteEvento'])->name('eventos.forceDelete');
});

// Professor area
Route::middleware('role:professor')->prefix('professor')->name('professor.')->group(function () {
    Route::get('/', [ProfessorController::class, 'dashboard'])->name('dashboard');

    // Comunicados
    Route::post('/comunicados', [ProfessorController::class, 'storeComunicado'])->name('comunicados.store');
    Route::put('/comunicados/{comunicado}', [ProfessorController::class, 'updateComunicado'])->name('comunicados.update');
    Route::delete('/comunicados/{comunicado}', [ProfessorController::class, 'destroyComunicado'])->name('comunicados.destroy');

    // Eventos
    Route::post('/eventos', [ProfessorController::class, 'storeEvento'])->name('eventos.store');
    Route::put('/eventos/{evento}', [ProfessorController::class, 'updateEvento'])->name('eventos.update');
    Route::delete('/eventos/{evento}', [ProfessorController::class, 'destroyEvento'])->name('eventos.destroy');
});
