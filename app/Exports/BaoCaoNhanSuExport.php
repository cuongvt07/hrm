<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BaoCaoNhanSuExport implements FromView
{
    protected $tableData;
    protected $fromDate;
    protected $toDate;
    protected $totalRow;

    public function __construct($tableData, $fromDate, $toDate, $totalRow = null)
    {
        $this->tableData = $tableData;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->totalRow = $totalRow;
    }

    public function view(): View
    {
        return view('bao-cao.nhan-su.export', [
            'tableData' => $this->tableData,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'totalRow' => $this->totalRow,
        ]);
    }
}
