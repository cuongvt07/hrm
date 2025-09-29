# Hệ thống Quản lý Nhân sự (HRM System)

## Tổng quan dự án
Hệ thống quản lý nhân sự được xây dựng bằng Laravel với giao diện hiện đại, ưu tiên thao tác ít chuyển màn.

## Công nghệ sử dụng
- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates + JavaScript + CSS
- **Database**: MySQL
- **UI Framework**: Bootstrap 5 + Custom CSS
- **JavaScript**: Vanilla JS + AJAX

## Kiến trúc hệ thống
- **MVC Pattern**: Model-View-Controller
- **Single Page Application**: Sử dụng AJAX để giảm thiểu chuyển trang
- **Component-based**: Sử dụng Blade components để tái sử dụng UI
- **Đơn giản hóa**: Không phân quyền phức tạp, tập trung vào quản lý thuần túy

## Flow chính của hệ thống
- Mong muốn có 5 module chính : 
    - Dashboard Tổng quan 
    - Quản lý Nhân viên 
    - Quản lý Hợp đồng 
    - Quản lý chế độ 
    - Quản lý báo cáo

### 1. Authentication & Authorization
```
Login → Dashboard → Module Selection
```

### 2. Dashboard Tổng quan
- Thống kê nhân viên
- Biểu đồ chấm công
- Thông báo nghỉ phép
- Quick actions

### 3. Quản lý Nhân viên (Single Page)
```
Danh sách nhân viên → Chi tiết nhân viên → Chỉnh sửa → Lưu
```
- **Tabs**: Thông tin cơ bản, Liên hệ, Gia đình, Hợp đồng, Tài liệu
- **Modal**: Thêm/sửa thông tin
- **AJAX**: Load dữ liệu không reload trang

### 4. Quản lý Hợp đồng (Single Page)
```
Danh sách hợp đồng → Chi tiết → Tạo mới/Sửa → Lưu
```
- **Contract Status**: Hoạt động, Hết hạn, Chấm dứt
- **Auto Alert**: Cảnh báo hợp đồng sắp hết hạn

### 5. Quản lý chế độ (Single Page)
Phụ cấp & Phúc Lợi 
khen thưởng - Kỉ luật 

### 6. Quản lý báo cáo (Single Page)
Báo cáo danh sách nhân viên  // dạng list table -> xuất file
Báo cáo thống kê số lượng nhân sự  → biểu đồ + ds ở dưới  + xuất
Báo cáo số lượng nhân viên từng phòng ban theo thời gian // dạng list table -> xuất file

## Cấu trúc Database
-- ==========================================
-- Cơ sở dữ liệu HRM (Đã cập nhật và Việt hóa)
-- ==========================================

-- 1. Quản lý nhân viên (cốt lõi)
CREATE TABLE nhanvien (
    id INT AUTO_INCREMENT PRIMARY KEY,                           -- Mã định danh
    ma_nhanvien VARCHAR(50) UNIQUE NOT NULL,                    -- Mã nhân viên
    ten VARCHAR(100) NOT NULL,                                  -- Tên
    ho VARCHAR(100) NOT NULL,                                   -- Họ
    ngay_sinh DATE,                                             -- Ngày sinh
    gioi_tinh ENUM('nam','nu','khac'),                         -- Giới tính
    tinh_trang_hon_nhan ENUM('doc_than','da_ket_hon','ly_hon') NULL, -- Tình trạng hôn nhân
    quoc_tich VARCHAR(100) DEFAULT 'Việt Nam',                  -- Quốc tịch
    dan_toc VARCHAR(100),                                       -- Dân tộc
    ton_giao VARCHAR(100),                                      -- Tôn giáo
    so_dien_thoai VARCHAR(20),                                  -- Số điện thoại
    email VARCHAR(100) UNIQUE,                                  -- Email
    dia_chi TEXT,                                               -- Địa chỉ
    phong_ban_id INT,                                          -- Mã phòng ban
    chuc_vu_id INT,                                            -- Mã chức vụ
    ngay_vao_lam DATE,                                          -- Ngày vào làm
    trang_thai ENUM('nhan_vien_chinh_thuc','thu_viec','thai_san','nghi_viec','khac') DEFAULT 'nhan_vien_chinh_thuc', -- Trạng thái
    anh_dai_dien VARCHAR(255) NULL COMMENT 'Ảnh đại diện nhân viên', -- Ảnh đại diện
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,               -- Ngày tạo
    ngay_cap_nhat TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Ngày cập nhật
    FOREIGN KEY (phong_ban_id) REFERENCES phong_ban(id) ON DELETE SET NULL,
    FOREIGN KEY (chuc_vu_id) REFERENCES chuc_vu(id) ON DELETE SET NULL
);

-- 2. Phòng ban & Chức vụ
CREATE TABLE phong_ban (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_phong_ban VARCHAR(200) NOT NULL,                       -- Tên phòng ban
    phong_ban_cha_id INT NULL,                                 -- Mã phòng ban cha
    FOREIGN KEY (phong_ban_cha_id) REFERENCES phong_ban(id) ON DELETE SET NULL
);

CREATE TABLE chuc_vu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_chuc_vu VARCHAR(200) NOT NULL                          -- Tên chức vụ
);

-- 3. Tài khoản đăng nhập
CREATE TABLE tai_khoan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nhan_vien_id INT UNIQUE,                                   -- Mã nhân viên
    ten_dang_nhap VARCHAR(100) UNIQUE NOT NULL,                -- Tên đăng nhập
    mat_khau VARCHAR(255) NOT NULL,                            -- Mật khẩu mã hóa
    email VARCHAR(100) UNIQUE,                                 -- Email
    vai_tro ENUM('quan_tri','nhan_su','quan_ly','nhan_vien') DEFAULT 'nhan_vien', -- Vai trò
    trang_thai ENUM('hoat_dong','khong_hoat_dong') DEFAULT 'hoat_dong', -- Trạng thái
    lan_dang_nhap_cuoi TIMESTAMP NULL,                         -- Lần đăng nhập cuối
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,              -- Ngày tạo
    ngay_cap_nhat TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Ngày cập nhật
    FOREIGN KEY (nhan_vien_id) REFERENCES nhanvien(id) ON DELETE SET NULL
);

-- 4. Thông tin giấy tờ tùy thân
CREATE TABLE giay_to_tuy_than (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nhan_vien_id INT NOT NULL,                                 -- Mã nhân viên
    loai_giay_to ENUM('CMND','CCCD','Ho_chieu','Ma_so_thue','Khac') DEFAULT 'CCCD', -- Loại giấy tờ
    so_giay_to VARCHAR(50) NOT NULL,                           -- Số giấy tờ
    ngay_cap DATE,                                             -- Ngày cấp
    noi_cap VARCHAR(255),                                      -- Nơi cấp
    ngay_het_han DATE,                                         -- Ngày hết hạn
    ghi_chu TEXT,                                              -- Ghi chú
    FOREIGN KEY (nhan_vien_id) REFERENCES nhanvien(id) ON DELETE CASCADE
);

-- 5. Thông tin liên hệ
CREATE TABLE thong_tin_lien_he (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nhan_vien_id INT NOT NULL,                                 -- Mã nhân viên
    dien_thoai_di_dong VARCHAR(20) NOT NULL,                   -- Điện thoại di động
    dien_thoai_co_quan VARCHAR(20),                            -- Điện thoại cơ quan
    dien_thoai_nha_rieng VARCHAR(20),                          -- Điện thoại nhà riêng
    dien_thoai_khac VARCHAR(20),                               -- Điện thoại khác
    email_co_quan VARCHAR(100),                                -- Email cơ quan
    email_ca_nhan VARCHAR(100),                                -- Email cá nhân
    dia_chi_thuong_tru VARCHAR(255),                           -- Địa chỉ thường trú
    dia_chi_hien_tai VARCHAR(255),                             -- Địa chỉ hiện tại
    lien_he_khan_cap_ten VARCHAR(200),                         -- Tên người liên hệ khẩn cấp
    lien_he_khan_cap_quan_he VARCHAR(100),                     -- Quan hệ người liên hệ khẩn cấp
    lien_he_khan_cap_dien_thoai VARCHAR(20),                   -- Số điện thoại người liên hệ khẩn cấp
    FOREIGN KEY (nhan_vien_id) REFERENCES nhanvien(id) ON DELETE CASCADE
);

-- 6. Thông tin gia đình
CREATE TABLE thong_tin_gia_dinh (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nhan_vien_id INT NOT NULL,                                 -- Mã nhân viên
    quan_he VARCHAR(100) NOT NULL,                             -- Quan hệ
    ho_ten VARCHAR(200) NOT NULL,                              -- Họ tên
    ngay_sinh DATE,                                            -- Ngày sinh
    nghe_nghiep VARCHAR(100),                                  -- Nghề nghiệp
    dia_chi_lien_he VARCHAR(255),                              -- Địa chỉ liên hệ
    dien_thoai VARCHAR(20),                                    -- Số điện thoại
    la_nguoi_phu_thuoc BOOLEAN DEFAULT FALSE,                  -- Là người phụ thuộc thuế
    ghi_chu TEXT,                                              -- Ghi chú
    FOREIGN KEY (nhan_vien_id) REFERENCES nhanvien(id) ON DELETE CASCADE
);

-- 7. Hồ sơ & File upload
CREATE TABLE tep_tin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nhan_vien_id INT NOT NULL,                                 -- Mã nhân viên
    module_id INT NULL,                                        -- Mã module (VD: mã hợp đồng, khen thưởng)
    loai_tep ENUM('giay_to_tuy_than','hop_dong','chung_chi','khen_thuong','ky_luat','bao_hiem','khac') DEFAULT 'khac', -- Loại tệp
    ten_tep VARCHAR(255),                                      -- Tên tệp
    duong_dan_tep VARCHAR(255) NOT NULL,                       -- Đường dẫn tệp
    kieu_mime VARCHAR(100),                                    -- Kiểu MIME
    ngay_tai_len TIMESTAMP DEFAULT CURRENT_TIMESTAMP,          -- Ngày tải lên
    nguoi_tai_len INT,                                         -- Người tải lên
    FOREIGN KEY (nhan_vien_id) REFERENCES nhanvien(id) ON DELETE CASCADE,
    FOREIGN KEY (nguoi_tai_len) REFERENCES tai_khoan(id) ON DELETE SET NULL
);

-- 8. Hợp đồng lao động
CREATE TABLE hop_dong_lao_dong (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nhan_vien_id INT NOT NULL,                                 -- Mã nhân viên
    so_hop_dong VARCHAR(100) UNIQUE,                           -- Số hợp đồng
    loai_hop_dong VARCHAR(100),                                -- Loại hợp đồng
    ngay_bat_dau DATE,                                         -- Ngày bắt đầu
    ngay_ket_thuc DATE,                                        -- Ngày kết thúc
    trang_thai ENUM('hoat_dong','het_han','cham_dut') DEFAULT 'hoat_dong', -- Trạng thái
    ngay_ky DATE,                                              -- Ngày ký
    luong_co_ban DECIMAL(15,2),                                -- Lương cơ bản
    luong_bao_hiem DECIMAL(15,2),                              -- Lương đóng bảo hiểm
    ghi_chu TEXT,                                              -- Ghi chú
    FOREIGN KEY (nhan_vien_id) REFERENCES nhanvien(id) ON DELETE CASCADE
);

-- 9. Cài đặt hệ thống (phúc lợi, meta)
CREATE TABLE cai_dat_he_thong (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_cai_dat VARCHAR(100) UNIQUE NOT NULL,                  -- Tên cài đặt
    gia_tri_cai_dat TEXT NOT NULL,                             -- Giá trị cài đặt
    mo_ta VARCHAR(255)                                         -- Mô tả
);

-- 10. Khen thưởng & Kỷ luật
CREATE TABLE khen_thuong_ky_luat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    loai ENUM('khen_thuong','ky_luat') NOT NULL,               -- Loại
    so_quyet_dinh VARCHAR(100),                                -- Số quyết định
    ngay_quyet_dinh DATE NOT NULL,                             -- Ngày quyết định
    tieu_de VARCHAR(200) NOT NULL,                             -- Tiêu đề
    mo_ta TEXT,                                                -- Mô tả
    gia_tri DECIMAL(15,2),                                     -- Giá trị (tiền/hiện vật)
    nguoi_quyet_dinh VARCHAR(200),                             -- Người quyết định
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP               -- Ngày tạo
);

CREATE TABLE khen_thuong_ky_luat_doi_tuong (
    id INT AUTO_INCREMENT PRIMARY KEY,
    khen_thuong_ky_luat_id INT NOT NULL,                       -- Mã khen thưởng/kỷ luật
    loai_doi_tuong ENUM('nhan_vien','phong_ban') NOT NULL,     -- Loại đối tượng
    doi_tuong_id INT NOT NULL,                                 -- Mã đối tượng
    FOREIGN KEY (khen_thuong_ky_luat_id) REFERENCES khen_thuong_ky_luat(id) ON DELETE CASCADE
);

## Tính năng chính

### 1. Dashboard Tổng quan
- **Thống kê real-time**: Số lượng nhân viên, tỷ lệ chấm công, đơn nghỉ chờ duyệt
- **Biểu đồ trực quan**: Chart.js hiển thị xu hướng chấm công, nghỉ phép theo tháng
- **Quick Actions**: Truy cập nhanh các chức năng thường dùng
- **Thông báo**: Alert hợp đồng sắp hết hạn, đơn nghỉ chờ duyệt

### 2. Quản lý Nhân viên (Single Page)
- **Danh sách nhân viên**: Tìm kiếm, lọc theo phòng ban/chức vụ
- **Chi tiết nhân viên**: Giao diện tab (Thông tin cơ bản, Liên hệ, Gia đình, Hợp đồng, Tài liệu)
- **Thêm/Sửa nhân viên**: Modal popup với validation real-time
- **Upload ảnh đại diện**: Drag & drop, crop ảnh
- **Quản lý tài liệu**: Upload, xem, tải xuống các loại giấy tờ

### 3. Quản lý Hợp đồng (Single Page)
- **Danh sách hợp đồng**: Lọc theo trạng thái, thời hạn
- **Tạo/Sửa hợp đồng**: Form chi tiết với template
- **Cảnh báo hết hạn**: Alert tự động 30/60/90 ngày trước hết hạn
- **Lịch sử hợp đồng**: Theo dõi các lần gia hạn, sửa đổi
- **Upload tài liệu**: Lưu trữ file hợp đồng

### 4. Quản lý Khen thưởng & Kỷ luật
- **Danh sách khen thưởng/kỷ luật**: Phân loại theo loại, thời gian
- **Tạo quyết định**: Form tạo quyết định khen thưởng/kỷ luật
- **Áp dụng cho nhân viên**: Chọn đối tượng áp dụng
- **Báo cáo**: Thống kê khen thưởng/kỷ luật theo phòng ban

### 5. Quản lý Phòng ban & Chức vụ
- **Cây phòng ban**: Hiển thị cấu trúc tổ chức
- **Quản lý chức vụ**: CRUD chức vụ trong hệ thống
- **Gán nhân viên**: Phân công nhân viên vào phòng ban/chức vụ

### 6. Báo cáo & Thống kê
- **Dashboard báo cáo**: Biểu đồ tổng quan
- **Báo cáo chấm công**: Theo tháng/quý/năm
- **Báo cáo nhân sự**: Thống kê nhân viên, tuyển dụng
- **Xuất báo cáo**: Excel, PDF, CSV

### 7. Hệ thống & Cài đặt
- **Quản lý tài khoản**: User management đơn giản
- **Cài đặt hệ thống**: Cấu hình các tham số cơ bản
- **Backup dữ liệu**: Xuất dữ liệu Excel/PDF

## Giao diện đặc điểm
- **Sidebar Navigation**: Menu dọc cố định
- **Top Bar**: Thông tin user và thông báo
- **Content Area**: Nội dung chính với tabs/modal
- **Dark/Light Mode**: Chuyển đổi theme

## Cài đặt và chạy dự án

### Bước 2: Cài đặt các thư viện cần thiết
```bash
# Cài đặt thư viện PHP
composer require laravel/ui
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf

# Cài đặt thư viện Frontend
npm install bootstrap@5.2.3
npm install @fortawesome/fontawesome-free
npm install chart.js
npm install flatpickr
npm install sweetalert2
```

### Bước 3: Cấu hình môi trường
```bash
# Copy file cấu hình
cp .env.example .env

# Tạo key ứng dụng
php artisan key:generate

# Cấu hình database trong file .env:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=hrm_system
# DB_USERNAME=root
# DB_PASSWORD=your_password
```

### Bước 4: Khởi tạo Database
```bash
# Tạo database MySQL
mysql -u root -p
CREATE DATABASE hrm_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit

# Chạy migration
php artisan migrate

# Tạo storage link
php artisan storage:link
```

### Bước 5: Cài đặt Authentication
```bash
# Tạo authentication scaffolding
php artisan ui bootstrap --auth

# Cài đặt frontend assets
npm install && npm run dev
```

### Bước 6: Seed dữ liệu mẫu
```bash
# Tạo seeder cho dữ liệu mẫu
php artisan make:seeder DatabaseSeeder
php artisan make:seeder PhongBanSeeder
php artisan make:seeder ChucVuSeeder
php artisan make:seeder NhanVienSeeder
php artisan make:seeder UserSeeder

# Chạy seeder
php artisan db:seed
```

### Bước 7: Chạy dự án
```bash
# Chạy Laravel server
php artisan serve

# Chạy Vite (terminal khác)
npm run dev

# Truy cập: http://localhost:8000
# Tài khoản mặc định: admin@hrm.com / password
```

### Bước 8: Cấu trúc thư mục dự án
```
hrm-system/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── NhanVienController.php
│   │   ├── HopDongController.php
│   │   ├── CheDoController.php
│   │   └── BaoCaoController.php
│   ├── Models/
│   │   ├── NhanVien.php
│   │   ├── PhongBan.php
│   │   ├── ChucVu.php
│   │   ├── HopDongLaoDong.php
│   │   └── ...
│   └── Services/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   └── sidebar.blade.php
│   │   ├── components/
│   │   │   ├── ui/
│   │   │   │   ├── button.blade.php
│   │   │   │   ├── input.blade.php
│   │   │   │   ├── select.blade.php
│   │   │   │   ├── modal.blade.php
│   │   │   │   ├── table.blade.php
│   │   │   │   └── card.blade.php
│   │   │   ├── forms/
│   │   │   │   ├── employee-form.blade.php
│   │   │   │   ├── contract-form.blade.php
│   │   │   │   └── filter-form.blade.php
│   │   │   └── charts/
│   │   │       ├── pie-chart.blade.php
│   │   │       └── bar-chart.blade.php
│   │   ├── dashboard/
│   │   ├── nhan-vien/
│   │   ├── hop-dong/
│   │   ├── che-do/
│   │   └── bao-cao/
│   ├── css/
│   └── js/
└── public/
    └── storage/
```

### Bước 9: Component Architecture

#### UI Components (Tái sử dụng cao)
```php
// Button Component
<x-ui.button type="primary" size="sm" icon="plus">Thêm mới</x-ui.button>

// Input Component  
<x-ui.input name="ten" label="Tên nhân viên" required />

// Select Component
<x-ui.select name="phong_ban_id" label="Phòng ban" :options="$phongBans" />

// Modal Component
<x-ui.modal id="employeeModal" title="Thông tin nhân viên">
    <x-forms.employee-form :employee="$employee" />
</x-ui.modal>

// Table Component
<x-ui.table :headers="['Tên', 'Phòng ban', 'Chức vụ', 'Thao tác']" :data="$nhanViens">
    <x-slot name="actions">
        <x-ui.button type="edit" size="sm">Sửa</x-ui.button>
        <x-ui.button type="delete" size="sm">Xóa</x-ui.button>
    </x-slot>
</x-ui.table>
```

#### Form Components (Chuyên biệt)
```php
// Employee Form
<x-forms.employee-form :employee="$employee" :phongBans="$phongBans" :chucVus="$chucVus" />

// Contract Form  
<x-forms.contract-form :contract="$contract" :nhanViens="$nhanViens" />

// Filter Form
<x-forms.filter-form :filters="['phong_ban', 'chuc_vu', 'trang_thai']" />
```

#### Chart Components
```php
// Pie Chart
<x-charts.pie-chart :data="$departmentStats" title="Thống kê theo phòng ban" />

// Bar Chart
<x-charts.bar-chart :data="$monthlyStats" title="Thống kê theo tháng" />
```

### Bước 10: Bắt đầu phát triển các module
1. **Tạo UI Components**: Button, Input, Select, Modal, Table
2. **Tạo Form Components**: Employee, Contract, Filter forms
3. **Dashboard Module**: Tạo controller và view tổng quan
4. **Nhân viên Module**: CRUD nhân viên với tabs
5. **Hợp đồng Module**: Quản lý hợp đồng lao động
6. **Chế độ Module**: Khen thưởng, kỷ luật, nghỉ phép
7. **Báo cáo Module**: Thống kê và xuất báo cáo

## Component Architecture Benefits

### Lợi ích của việc sử dụng Components
- **Tái sử dụng**: Một component có thể dùng ở nhiều nơi
- **Nhất quán**: Giao diện đồng bộ trên toàn hệ thống
- **Dễ bảo trì**: Sửa 1 lần, áp dụng mọi nơi
- **Tăng tốc phát triển**: Không cần viết lại code tương tự
- **Dễ test**: Test từng component riêng biệt

### Các loại Components chính
1. **UI Components**: Button, Input, Select, Modal, Table, Card
2. **Form Components**: Employee form, Contract form, Filter form
3. **Chart Components**: Pie chart, Bar chart, Line chart
4. **Layout Components**: Sidebar, Header, Footer, Breadcrumb

### Ví dụ sử dụng thực tế
```php
// Trang danh sách nhân viên
<x-ui.table :headers="['Tên', 'Phòng ban', 'Chức vụ', 'Thao tác']" :data="$nhanViens">
    <x-slot name="filters">
        <x-forms.filter-form :filters="['phong_ban', 'chuc_vu']" />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button type="primary" icon="plus" onclick="openModal('employeeModal')">
            Thêm nhân viên
        </x-ui.button>
    </x-slot>
</x-ui.table>

// Modal thêm/sửa nhân viên
<x-ui.modal id="employeeModal" title="Thông tin nhân viên" size="lg">
    <x-forms.employee-form :employee="$employee" :phongBans="$phongBans" :chucVus="$chucVus" />
</x-ui.modal>
```

## Mục tiêu UX/UI
- **Ít chuyển trang**: Sử dụng AJAX và modal
- **Giao diện trực quan**: Icons, colors, spacing hợp lý
- **Thao tác nhanh**: Shortcuts, bulk actions
- **Performance**: Load nhanh, ít request
- **Component-based**: UI nhất quán và dễ bảo trì


// xử lý module cài đặt hệ thống nữa