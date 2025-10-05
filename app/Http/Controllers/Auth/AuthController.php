<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = '12345678';

    public function showLoginForm()
    {
        try {
            return view('auth.login');
        } catch (\Throwable $e) {
            Log::error('Lỗi khi vào showLoginForm', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $tenDangNhap = $request->input('ten_dang_nhap');
            $matKhau = $request->input('mat_khau');

            // Tìm tài khoản theo tên đăng nhập
            $taiKhoan = TaiKhoan::where('ten_dang_nhap', $tenDangNhap)->first();

            // Kiểm tra tài khoản
            if ($taiKhoan && Hash::check($matKhau, $taiKhoan->mat_khau)) {
                // Kiểm tra vai trò
                if ($taiKhoan->vai_tro !== 'quan_tri') {
                    return back()
                        ->withInput()
                        ->withErrors(['ten_dang_nhap' => 'Bạn không có quyền đăng nhập vào hệ thống.']);
                }

                if ($taiKhoan->trang_thai !== 'hoat_dong') {
                    return back()
                        ->withInput()
                        ->withErrors(['ten_dang_nhap' => 'Tài khoản đã bị khóa.']);
                }

                Auth::login($taiKhoan);
                $taiKhoan->update(['lan_dang_nhap_cuoi' => now()]);

                return redirect()->intended(route('dashboard'));
            }

            // Nếu sai thông tin
            return back()
                ->withInput()
                ->withErrors(['ten_dang_nhap' => 'Thông tin đăng nhập không chính xác.']);
        } catch (\Throwable $e) {
            // Ghi log chi tiết
            Log::error('Lỗi khi đăng nhập', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->only('ten_dang_nhap'),
            ]);

            return back()
                ->withInput()
                ->withErrors(['ten_dang_nhap' => 'Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau.']);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->forget('admin_account');
            return redirect()->route('login');
        } catch (\Throwable $e) {
            Log::error('Lỗi khi logout', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->route('login')->withErrors(['logout' => 'Có lỗi khi đăng xuất.']);
        }
    }
}
