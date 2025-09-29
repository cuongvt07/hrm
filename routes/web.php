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
Route::post('nhan-vien/bulk-delete', [NhanVienController::class, 'bulkDelete'])->name('nhan-vien.bulk-delete');
Route::get('nhan-vien-export', [NhanVienController::class, 'export'])->name('nhan-vien.export');

// AJAX routes for family members and documents
Route::post('nhan-vien/{nhanVien}/add-family-member', [NhanVienController::class, 'addFamilyMember'])->name('nhan-vien.addFamilyMember');
Route::put('nhan-vien/{nhanVien}/family-member/{familyMember}', [NhanVienController::class, 'updateFamilyMember'])->name('nhan-vien.updateFamilyMember');
Route::delete('nhan-vien/{nhanVien}/family-member/{familyMember}', [NhanVienController::class, 'deleteFamilyMember'])->name('nhan-vien.deleteFamilyMember');
Route::post('nhan-vien/{nhanVien}/add-document', [NhanVienController::class, 'addDocument'])->name('nhan-vien.addDocument');
Route::delete('nhan-vien/{nhanVien}/document/{document}', [NhanVienController::class, 'deleteDocument'])->name('nhan-vien.deleteDocument');

// Quản lý giấy tờ tùy thân
Route::prefix('nhan-vien/{nhanVien}')->group(function () {
    Route::post('giay-to-tuy-than', [\App\Http\Controllers\GiayToTuyThanController::class, 'store'])->name('giay-to-tuy-than.store');
    Route::put('giay-to-tuy-than/{giayToTuyThan}', [\App\Http\Controllers\GiayToTuyThanController::class, 'update'])->name('giay-to-tuy-than.update');
    Route::delete('giay-to-tuy-than/{giayToTuyThan}', [\App\Http\Controllers\GiayToTuyThanController::class, 'destroy'])->name('giay-to-tuy-than.destroy');
});

// Quản lý hợp đồng
Route::resource('hop-dong', HopDongController::class);
Route::get('hop-dong/{id}/view', [HopDongController::class, 'view'])->name('hop-dong.view');
Route::get('hop-dong-sap-het-han', [HopDongController::class, 'sapHetHan'])->name('hop-dong.saphethan');
Route::get('hop-dong/{id}/gia-han', [HopDongController::class, 'giaHanForm'])->name('hop-dong.giahan.form');
Route::post('hop-dong/gia-han', [HopDongController::class, 'giaHanStore'])->name('hop-dong.giahan.store');
// Bulk update trạng thái hợp đồng
Route::post('hop-dong/bulk-update-status', [HopDongController::class, 'bulkUpdateStatus'])->name('hop-dong.bulk-update-status');

// Cài đặt hệ thống
Route::prefix('cai-dat')->name('cai-dat.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CaiDatHeThongController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\CaiDatHeThongController::class, 'store'])->name('store');
    Route::put('/{item}', [\App\Http\Controllers\CaiDatHeThongController::class, 'update'])->name('update');
    Route::delete('/{item}', [\App\Http\Controllers\CaiDatHeThongController::class, 'destroy'])->name('destroy');
});

// Quản lý chế độ
Route::prefix('che-do')->name('che-do.')->group(function () {
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
    
    // Export báo cáo
    Route::get('/nhan-su/export', [BaoCaoController::class, 'exportNhanSu'])->name('nhan-su.export');
    Route::get('/hop-dong/export', [BaoCaoController::class, 'exportHopDong'])->name('hop-dong.export');
});

// Quá trình công tác
Route::post('nhan-vien/{nhanvien}/qua-trinh-cong-tac', [QuaTrinhCongTacController::class, 'store'])->name('qua-trinh-cong-tac.store');
Route::delete('qua-trinh-cong-tac/{id}', [QuaTrinhCongTacController::class, 'destroy'])->name('qua-trinh-cong-tac.destroy');