<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class BaoCaoKyLuatCaNhanExport implements FromView, ShouldAutoSize
{
    protected $kyLuatCaNhan;

    public function __construct($kyLuatCaNhan)
    {
        $this->kyLuatCaNhan = $kyLuatCaNhan;
    }

    public function view(): View
    {
        return view('bao-cao.ky-luat.export', [
            'kyLuatCaNhan' => $this->kyLuatCaNhan
        ]);
    }
}