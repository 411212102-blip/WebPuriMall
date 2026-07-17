<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminCustomerController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KatalogHadiahController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManagementDashboardController;
use App\Http\Controllers\ManagerReportController;
use App\Http\Controllers\MyRewardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedeemHistoryController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\VoucherClaimController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::middleware(['auth:pelanggan', 'role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/upload-struk', [StrukController::class, 'showForm'])->name('upload-struk');
    Route::post('/upload-struk', [StrukController::class, 'upload'])->name('upload-struk.store');
    Route::get('/upload-struk/ulang/{transaksi}', [StrukController::class, 'showReuploadForm'])->name('upload-struk.reupload');
    Route::post('/upload-struk/ulang/{transaksi}', [StrukController::class, 'reupload'])->name('upload-struk.reupload.store');
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');
    Route::get('/my-rewards', [MyRewardController::class, 'index'])->name('my-rewards.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth:pelanggan', 'role:pelanggan'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('pelanggan.dashboard'))->name('dashboard');
    Route::get('/history', fn () => redirect()->route('pelanggan.dashboard'))->name('history');
    Route::get('/upload-struk', fn () => redirect()->route('pelanggan.upload-struk'))->name('upload-struk');
    Route::post('/upload-struk', [StrukController::class, 'upload'])->name('upload-struk.store');
});

Route::middleware(['auth:pegawai', 'role:superadmin,admin,kasir'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth:pegawai', 'role:manager'])->prefix('mgmt')->name('mgmt.')->group(function () {
    Route::get('/dashboard', [ManagementDashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports/tenants.csv', [ManagementDashboardController::class, 'exportTenants'])->name('reports.tenants');
    Route::get('/reports/members.csv', [ManagementDashboardController::class, 'exportMembers'])->name('reports.members');
    Route::get('/reports/tenants.xlsx', [ManagerReportController::class, 'exportTenantExcel'])->name('reports.tenants.xlsx');
    Route::get('/reports/tenants.pdf', [ManagerReportController::class, 'exportTenantPdf'])->name('reports.tenants.pdf');
    Route::get('/reports/members.xlsx', [ManagerReportController::class, 'exportMemberExcel'])->name('reports.members.xlsx');
    Route::get('/reports/members.pdf', [ManagerReportController::class, 'exportMemberPdf'])->name('reports.members.pdf');
});

Route::middleware(['auth:pegawai', 'role:superadmin,admin,kasir'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/verifikasi', [AdminDashboardController::class, 'index'])->name('verifikasi.index');
        Route::post('/transaksi/{id}/approve', [AdminDashboardController::class, 'approve'])->name('transaksi.approve');
        Route::post('/transaksi/{id}/reject', [AdminDashboardController::class, 'reject'])->name('transaksi.reject');
        Route::get('/voucher-claims', [VoucherClaimController::class, 'index'])->name('voucher-claims.index');
        Route::post('/voucher-claims', [VoucherClaimController::class, 'claim'])->name('voucher-claims.claim');
    });

Route::middleware(['auth:pegawai', 'role:superadmin,admin,manager'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/pelanggan', [AdminCustomerController::class, 'index'])->name('pelanggan.index');
        Route::get('/redeem-history', [RedeemHistoryController::class, 'index'])->name('redeem-history.index');
    });

Route::middleware(['auth:pegawai', 'role:superadmin,admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('tenants', TenantController::class)->except(['show']);
        Route::resource('events', EventController::class)->except(['show']);
        Route::resource('fasilitas', FasilitasController::class)->except(['show'])->parameters(['fasilitas' => 'fasilitas']);
        Route::resource('hadiah', KatalogHadiahController::class)->except(['show']);
    });
