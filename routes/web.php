<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BaoCao\BaoCaoKhenThuongController;
use App\Http\Controllers\BaoCao\BaoCaoKyLuatController;
use App\Http\Controllers\BaoCao\NhanSuController;
use App\Http\Controllers\CheDoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HopDongController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\TaiKhoanController;
use App\Http\Controllers\QuaTrinhCongTacController;
use App\Http\Controllers\GiayToTuyThanController;
use Illuminate\Support\Facades\Route;


// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    // Logout route
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tài khoản routes - chỉ admin và nhân sự mới có quyền
    Route::resource('tai-khoan', TaiKhoanController::class);
    Route::post('tai-khoan/{id}/update-status', [TaiKhoanController::class, 'updateStatus']);
    // Quản lý nhân viên
    Route::resource('nhan-vien', NhanVienController::class);
    Route::post('nhan-vien/bulk-delete', [NhanVienController::class, 'bulkDelete'])->name('nhan-vien.bulk-delete');
    Route::get('nhan-vien-export', [NhanVienController::class, 'export'])->name('nhan-vien.export');

    // AJAX routes for family members and documents
    Route::prefix('nhan-vien')->group(function () {
        Route::post('{nhanVien}/add-family-member', [NhanVienController::class, 'addFamilyMember'])->name('nhan-vien.addFamilyMember');
        Route::put('{nhanVien}/family-member/{familyMember}', [NhanVienController::class, 'updateFamilyMember'])->name('nhan-vien.updateFamilyMember');
        Route::delete('{nhanVien}/family-member/{familyMember}', [NhanVienController::class, 'deleteFamilyMember'])->name('nhan-vien.deleteFamilyMember');
        Route::post('{nhanVien}/add-document', [NhanVienController::class, 'addDocument'])->name('nhan-vien.addDocument');
        Route::delete('{nhanVien}/document/{document}', [NhanVienController::class, 'deleteDocument'])->name('nhan-vien.deleteDocument');

        // Quá trình công tác
        Route::post('{nhanvien}/qua-trinh-cong-tac', [QuaTrinhCongTacController::class, 'store'])->name('qua-trinh-cong-tac.store');
        Route::delete('qua-trinh-cong-tac/{id}', [QuaTrinhCongTacController::class, 'destroy'])->name('qua-trinh-cong-tac.destroy');
    });

    // Quản lý giấy tờ tùy thân
    Route::prefix('nhan-vien/{nhanVien}/giay-to-tuy-than')->name('giay-to-tuy-than.')->group(function () {
        Route::post('/', [GiayToTuyThanController::class, 'store'])->name('store');
        Route::put('/{giayToTuyThan}', [GiayToTuyThanController::class, 'update'])->name('update');
        Route::delete('/{giayToTuyThan}', [GiayToTuyThanController::class, 'destroy'])->name('destroy');
    });

    // Quản lý hợp đồng
    Route::resource('hop-dong', HopDongController::class);
    Route::get('hop-dong/{id}/view', [HopDongController::class, 'view'])->name('hop-dong.view');
    Route::get('hop-dong-sap-het-han', [HopDongController::class, 'sapHetHan'])->name('hop-dong.saphethan');
    Route::get('hop-dong/{id}/gia-han', [HopDongController::class, 'giaHanForm'])->name('hop-dong.giahan.form');
    Route::post('hop-dong/gia-han', [HopDongController::class, 'giaHanStore'])->name('hop-dong.giahan.store');
    // Bulk update trạng thái hợp đồng
    Route::post('hop-dong/bulk-update-status', [HopDongController::class, 'bulkUpdateStatus'])->name('hop-dong.bulk-update-status');

    // Báo cáo
    Route::prefix('bao-cao')->name('bao-cao.')->group(function () {
        // Báo cáo khen thưởng
        Route::prefix('khen-thuong')->group(function () {
            Route::get('/', [BaoCaoKhenThuongController::class, 'index'])->name('khen-thuong.index');
            Route::get('/export-ca-nhan', [BaoCaoKhenThuongController::class, 'exportCaNhan'])->name('khen-thuong.export-ca-nhan');
            Route::get('/export-phong-ban', [BaoCaoKhenThuongController::class, 'exportPhongBan'])->name('khen-thuong.export-phong-ban');
        });

        // Báo cáo kỷ luật
        Route::prefix('ky-luat')->group(function () {
            Route::get('/', [BaoCaoKyLuatController::class, 'index'])->name('ky-luat.index');
            Route::get('/export-ca-nhan', [BaoCaoKyLuatController::class, 'exportCaNhan'])->name('ky-luat.export-ca-nhan');
            Route::get('/export-phong-ban', [BaoCaoKyLuatController::class, 'exportPhongBan'])->name('ky-luat.export-phong-ban');
        });

        // Báo cáo nhân sự
        Route::prefix('nhan-su')->name('nhan-su.')->group(function () {
            Route::get('/', [NhanSuController::class, 'index'])->name('index');
            Route::get('/chart-data', [NhanSuController::class, 'getChartData'])->name('chart-data');
            Route::get('/table-data', [NhanSuController::class, 'getTableData'])->name('table-data');
            Route::get('/export', [NhanSuController::class, 'export'])->name('export');
        });

        // Báo cáo hợp đồng nhân sự
        Route::prefix('hop-dong')->name('hop-dong.')->group(function () {
            Route::get('/', [\App\Http\Controllers\BaoCao\HopDongNhanSuController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\BaoCao\HopDongNhanSuController::class, 'export'])->name('export');
        });
    });

    // Cài đặt hệ thống
    Route::prefix('cai-dat')->name('cai-dat.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CaiDatHeThongController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\CaiDatHeThongController::class, 'store'])->name('store');
        Route::put('/{item}', [\App\Http\Controllers\CaiDatHeThongController::class, 'update'])->name('update');
        Route::delete('/{item}', [\App\Http\Controllers\CaiDatHeThongController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('cai-dat-item')->name('cai-dat-item.')->group(function () {
        Route::post('/{danh_muc_id}', [\App\Http\Controllers\CaiDatItemController::class, 'store'])->name('store');
        Route::put('/{item}', [\App\Http\Controllers\CaiDatItemController::class, 'update'])->name('update');
        Route::delete('/{item}', [\App\Http\Controllers\CaiDatItemController::class, 'destroy'])->name('destroy');
    });

    // Quản lý chế độ
    Route::prefix('che-do')->name('che-do.')->group(function () {
        // Khen thưởng
        Route::get('/khen-thuong', [CheDoController::class, 'khenThuongIndex'])->name('khen-thuong.index');
        // Kỷ luật
        Route::get('/ky-luat', [CheDoController::class, 'kyLuatIndex'])->name('ky-luat.index');
        // Các route chung
        Route::get('/khen-thuong-ky-luat/create', [CheDoController::class, 'khenThuongKyLuatCreate'])->name('khen-thuong-ky-luat.create');
        Route::get('/khen-thuong-ky-luat/{id}', [CheDoController::class, 'khenThuongKyLuatShow'])->name('khen-thuong-ky-luat.show');
        Route::post('/khen-thuong-ky-luat', [CheDoController::class, 'khenThuongKyLuatStore'])->name('khen-thuong-ky-luat.store');
        Route::put('/khen-thuong-ky-luat/{id}', [CheDoController::class, 'khenThuongKyLuatUpdate'])->name('khen-thuong-ky-luat.update');
    });
});