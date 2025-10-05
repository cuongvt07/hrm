<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BaoCaoHopDongNhanSuExport implements FromView
{
    protected $tableData;
    protected $fromDate;
    protected $toDate;
    protected $trangThai;
    protected $loaiHopDong;
    protected $totalRow;

    public function __construct($tableData, $fromDate, $toDate, $trangThai, $loaiHopDong, $totalRow)
    {
        $this->tableData = $tableData;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->trangThai = $trangThai;
        $this->loaiHopDong = $loaiHopDong;
        $this->totalRow = $totalRow;
    }

    public function view(): View
    {
        return view('bao-cao.hop-dong-nhan-su.export', [
            'tableData' => $this->tableData,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'trangThai' => $this->trangThai,
            'loaiHopDong' => $this->loaiHopDong,
            'totalRow' => $this->totalRow,
        ]);
    }
}
