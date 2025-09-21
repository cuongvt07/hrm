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