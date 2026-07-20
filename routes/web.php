<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Perbaikan rute dashboard menggunakan Facade Auth resmi agar Intelephense tidak error
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();
    $role = $user->role;

    if ($role === 'super_admin') {
        return redirect()->route('dashboard.super');
    }

    return redirect()->route('dashboard.petugas');
})->middleware('auth')->name('dashboard');


Route::get('/dashboard/super-admin', function () {
    return view('dashboard_super_admin');
})->middleware(['auth', 'role:super_admin'])->name('dashboard.super');

// Diubah menjadi role:petugas agar sinkron dengan database MySQL
Route::get('/dashboard/petugas-gudang', function () {
    return view('dashboard_petugas_gudang');
})->middleware(['auth', 'role:petugas'])->name('dashboard.petugas');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Grup Rute KHUSUS Super Admin
Route::middleware(['auth','role:super_admin'])->group(function () {
    Route::resource('items', ItemController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/filter', [ReportController::class, 'filter'])->name('reports.filter');
});

// Grup Rute KHUSUS Petugas (Sudah disinkronkan ke role:petugas)
Route::middleware(['auth','role:petugas'])->group(function () {
    // Stok (read-only) - pakai index items
    Route::get('/stock',[ItemController::class, 'index'])->name('stock.index');

    // CRUD transaksi masuk
    Route::get('/transactions/ins', [TransactionController::class, 'insIndex'])->name('transactions.ins.index');
    Route::get('/transactions/ins/create', [TransactionController::class, 'insCreate'])->name('transactions.ins.create');
    Route::post('/transactions/ins', [TransactionController::class, 'insStore'])->name('transactions.ins.store');
    Route::get('/transactions/ins/{itemIn}/edit', [TransactionController::class, 'insEdit'])->name('transactions.ins.edit');
    Route::put('/transactions/ins/{itemIn}', [TransactionController::class, 'insUpdate'])->name('transactions.ins.update');
    Route::delete('/transactions/ins/{itemIn}', [TransactionController::class, 'insDestroy'])->name('transactions.ins.destroy');

    // CRUD transaksi keluar
    Route::get('/transactions/outs', [TransactionController::class, 'outsIndex'])->name('transactions.outs.index');
    Route::get('/transactions/outs/create', [TransactionController::class, 'outsCreate'])->name('transactions.outs.create');
    Route::post('/transactions/outs', [TransactionController::class, 'outsStore'])->name('transactions.outs.store');
    Route::get('/transactions/outs/{itemOut}/edit', [TransactionController::class, 'outsEdit'])->name('transactions.outs.edit');
    Route::put('/transactions/outs/{itemOut}', [TransactionController::class, 'outsUpdate'])->name('transactions.outs.update');
    Route::delete('/transactions/outs/{itemOut}', [TransactionController::class, 'outsDestroy'])->name('transactions.outs.destroy');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

require __DIR__.'/auth.php';