<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class BaoCaoKhenThuongCaNhanExport implements FromView, ShouldAutoSize
{
    protected $khenThuongCaNhan;

    public function __construct($khenThuongCaNhan)
    {
        $this->khenThuongCaNhan = $khenThuongCaNhan;
    }

    public function view(): View
    {
        return view('bao-cao.khen-thuong.export', [
            'khenThuongCaNhan' => $this->khenThuongCaNhan
        ]);
    }
}