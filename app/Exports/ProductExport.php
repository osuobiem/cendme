<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\BeforeExport;

class ProductExport implements FromView
{
    public function view(): View
    {
        return view('vendor.product.productlist');
    }

    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            BeforeExport::class => function (BeforeExport $event) {
                $sheet = $event->sheet->freezeFirstRow();
            }
        ];
    }
}
