<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\HopDongController;
use App\Http\Controllers\CheDoController;
use App\Http\Controllers\BaoCaoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Quản lý nhân viên
Route::resource('nhan-vien', NhanVienController::class);
Route::get('nhan-vien-export', [NhanVienController::class, 'export'])->name('nhan-vien.export');

// Quản lý hợp đồng
Route::resource('hop-dong', HopDongController::class);

// Quản lý chế độ
Route::prefix('che-do')->name('che-do.')->group(function () {
    // Nghỉ phép
    Route::get('/nghi-phep', [CheDoController::class, 'nghiPhepIndex'])->name('nghi-phep.index');
    Route::get('/nghi-phep/{nghiPhep}', [CheDoController::class, 'nghiPhepShow'])->name('nghi-phep.show');
    Route::patch('/nghi-phep/{nghiPhep}/approve', [CheDoController::class, 'nghiPhepApprove'])->name('nghi-phep.approve');
    Route::patch('/nghi-phep/{nghiPhep}/reject', [CheDoController::class, 'nghiPhepReject'])->name('nghi-phep.reject');
    
    // Khen thưởng kỷ luật
    Route::get('/khen-thuong-ky-luat', [CheDoController::class, 'khenThuongKyLuatIndex'])->name('khen-thuong-ky-luat.index');
    Route::get('/khen-thuong-ky-luat/create', [CheDoController::class, 'khenThuongKyLuatCreate'])->name('khen-thuong-ky-luat.create');
    Route::post('/khen-thuong-ky-luat', [CheDoController::class, 'khenThuongKyLuatStore'])->name('khen-thuong-ky-luat.store');
});

// Báo cáo
Route::prefix('bao-cao')->name('bao-cao.')->group(function () {
    Route::get('/', [BaoCaoController::class, 'index'])->name('index');
    Route::get('/nhan-su', [BaoCaoController::class, 'nhanSu'])->name('nhan-su');
    Route::get('/hop-dong', [BaoCaoController::class, 'hopDong'])->name('hop-dong');
    Route::get('/nghi-phep', [BaoCaoController::class, 'nghiPhep'])->name('nghi-phep');
    
    // Export báo cáo
    Route::get('/nhan-su/export', [BaoCaoController::class, 'exportNhanSu'])->name('nhan-su.export');
    Route::get('/hop-dong/export', [BaoCaoController::class, 'exportHopDong'])->name('hop-dong.export');
    Route::get('/nghi-phep/export', [BaoCaoController::class, 'exportNghiPhep'])->name('nghi-phep.export');
});