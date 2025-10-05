<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class BaoCaoKyLuatPhongBanExport implements FromView, ShouldAutoSize
{
    protected $kyLuatPhongBan;

    public function __construct($kyLuatPhongBan)
    {
        $this->kyLuatPhongBan = $kyLuatPhongBan;
    }

    public function view(): View
    {
        return view('bao-cao.ky-luat.export', [
            'kyLuatPhongBan' => $this->kyLuatPhongBan
        ]);
    }
}