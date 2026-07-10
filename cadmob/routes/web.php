<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\ImovelController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AverbacaoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\AuditoriaController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('Index',[HelloWorldController::class,'index'])->name('helloworld');

Route::middleware(['auth', 'verified'])->group(function () {

    // Pessoas — todos os perfis
    Route::resource('pessoas', PessoaController::class);

    // Imóveis — todos os perfis
    Route::get('/imoveis', [ImovelController::class, 'index'])->name('imoveis.index');
    Route::get('/imoveis/create', [ImovelController::class, 'create'])->name('imoveis.create');
    Route::post('/imoveis', [ImovelController::class, 'store'])->name('imoveis.store');
    Route::get('/imoveis/{imovel}', [ImovelController::class, 'show'])->name('imoveis.show');
    Route::get('/imoveis/{imovel}/edit', [ImovelController::class, 'edit'])->name('imoveis.edit');
    Route::put('/imoveis/{imovel}', [ImovelController::class, 'update'])->name('imoveis.update');
    Route::delete('/imoveis/{imovel}', [ImovelController::class, 'destroy'])->name('imoveis.destroy');

    // Documentos — todos os perfis
    Route::post('/imoveis/{imovel}/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/{documento}/download', [DocumentoController::class, 'download'])->name('documentos.download');
    Route::delete('/documentos/{documento}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');

    // Averbações — todos os perfis
    Route::post('/imoveis/{imovel}/averbacoes', [AverbacaoController::class, 'store'])->name('averbacoes.store');

    // Relatórios — todos os perfis
    Route::get('/relatorios/sintetico', [RelatorioController::class, 'sintetico'])->name('relatorios.sintetico');
    Route::get('/relatorios/imovel/{imovel}', [RelatorioController::class, 'analitico'])->name('relatorios.analitico');

    // Auditoria — apenas T
    Route::middleware(['perfil:T'])->group(function () {
        Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
        Route::get('/auditoria/{audit}', [AuditoriaController::class, 'show'])->name('auditoria.show');
    });

    // Usuários — apenas T e S
    Route::middleware(['perfil:T,S'])->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
