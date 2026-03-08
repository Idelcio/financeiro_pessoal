<?php

use App\Http\Controllers\CartaoController;
use App\Http\Controllers\CartaoGastoController;
use App\Http\Controllers\CartaoParcelaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CombustivelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GastoAvulsoController;
use App\Http\Controllers\GastoFixoController;
use App\Http\Controllers\GastoFixoPagamentoController;
use App\Http\Controllers\ImpostoController;
use App\Http\Controllers\ImpostoParcelaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\ManutencaoController;
use App\Http\Controllers\DespesaVeiculoController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gastos Fixos
    Route::resource('gastos-fixos', GastoFixoController::class);
    Route::post('gastos-fixos/{gastoFixo}/pagar', [GastoFixoPagamentoController::class, 'store'])->name('gastos-fixos.pagar');
    Route::delete('gastos-fixos-pagamentos/{pagamento}', [GastoFixoPagamentoController::class, 'destroy'])->name('gastos-fixos.pagamento.destroy');

    // Combustivel
    Route::resource('combustivel', CombustivelController::class)->except(['show']);

    // Cartoes
    Route::resource('cartoes', CartaoController::class)->parameters(['cartoes' => 'cartao']);
    Route::get('cartoes/{cartao}/gastos/create', [CartaoGastoController::class, 'create'])->name('cartoes.gastos.create');
    Route::post('cartoes/{cartao}/gastos', [CartaoGastoController::class, 'store'])->name('cartoes.gastos.store');
    Route::delete('cartoes-gastos/{cartaoGasto}', [CartaoGastoController::class, 'destroy'])->name('cartoes.gastos.destroy');
    Route::post('cartoes-gastos/{cartaoGasto}/cancelar-recorrente', [CartaoGastoController::class, 'cancelarRecorrente'])->name('cartoes.gastos.cancelar-recorrente');
    Route::post('cartoes-parcelas/{parcela}/pagar', [CartaoParcelaController::class, 'pagar'])->name('cartoes.parcelas.pagar');
    Route::post('cartoes-parcelas/{parcela}/desfazer', [CartaoParcelaController::class, 'desfazer'])->name('cartoes.parcelas.desfazer');

    // Impostos (IPVA / IPTU)
    Route::resource('impostos', ImpostoController::class);
    Route::post('impostos-parcelas/{parcela}/pagar', [ImpostoParcelaController::class, 'pagar'])->name('impostos.parcelas.pagar');
    Route::post('impostos-parcelas/{parcela}/desfazer', [ImpostoParcelaController::class, 'desfazer'])->name('impostos.parcelas.desfazer');

    // Gastos Avulsos
    Route::resource('gastos-avulsos', GastoAvulsoController::class)->except(['show']);

    // Categorias
    Route::resource('categorias', CategoriaController::class)->except(['show', 'create', 'edit']);

    // Veículos
    Route::resource('veiculos', VeiculoController::class);

    // Manutenções (aninhadas ao veículo)
    Route::get('veiculos/{veiculo}/manutencoes/create', [ManutencaoController::class, 'create'])->name('veiculos.manutencoes.create');
    Route::post('veiculos/{veiculo}/manutencoes', [ManutencaoController::class, 'store'])->name('veiculos.manutencoes.store');
    Route::get('veiculos/{veiculo}/manutencoes/{manutencao}/edit', [ManutencaoController::class, 'edit'])->name('veiculos.manutencoes.edit');
    Route::put('veiculos/{veiculo}/manutencoes/{manutencao}', [ManutencaoController::class, 'update'])->name('veiculos.manutencoes.update');
    Route::delete('veiculos/{veiculo}/manutencoes/{manutencao}', [ManutencaoController::class, 'destroy'])->name('veiculos.manutencoes.destroy');

    // Despesas do veículo (aninhadas ao veículo)
    Route::get('veiculos/{veiculo}/despesas/create', [DespesaVeiculoController::class, 'create'])->name('veiculos.despesas.create');
    Route::post('veiculos/{veiculo}/despesas', [DespesaVeiculoController::class, 'store'])->name('veiculos.despesas.store');
    Route::get('veiculos/{veiculo}/despesas/{despesa}/edit', [DespesaVeiculoController::class, 'edit'])->name('veiculos.despesas.edit');
    Route::put('veiculos/{veiculo}/despesas/{despesa}', [DespesaVeiculoController::class, 'update'])->name('veiculos.despesas.update');
    Route::delete('veiculos/{veiculo}/despesas/{despesa}', [DespesaVeiculoController::class, 'destroy'])->name('veiculos.despesas.destroy');
});

// Admin
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::patch('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('toggle-admin');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('destroy');
});

require __DIR__ . '/auth.php';
