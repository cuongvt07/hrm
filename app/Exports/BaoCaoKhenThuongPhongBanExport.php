<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class BaoCaoKhenThuongPhongBanExport implements FromView, ShouldAutoSize
{
    protected $khenThuongPhongBan;

    public function __construct($khenThuongPhongBan)
    {
        $this->khenThuongPhongBan = $khenThuongPhongBan;
    }

    public function view(): View
    {
        return view('bao-cao.khen-thuong.export', [
            'khenThuongPhongBan' => $this->khenThuongPhongBan
        ]);
    }
}