<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    private $subcategory_id;

    function __construct($subcategory_id)
    {
        $this->subcategory_id = $subcategory_id;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'title' => $row['title'],
            'details' => $row['details'],
            'quantity' => $row['quantity'],
            'price' => $row['price'],
            'vendor_id' => Auth::user()->id,
            'subcategory_id' => $this->subcategory_id
        ]);
    }
    public function rules(): array
    {
        return [
            '*.title' => ['unique:products,title'],
            '*.price' => ['numeric', 'min:0'],
            '*.quantity' => ['numeric', 'min:0']
        ];
    }
}
