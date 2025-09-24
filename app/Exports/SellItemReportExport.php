<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SellItemReportExport implements FromCollection, WithHeadings
{
    protected $items;

    /**
     * Create a new export instance.
     *
     * @param  \Illuminate\Support\Collection|array  $items
     */
    public function __construct($items)
    {
        $this->items = collect($items);
    }

    /**
     * Return the collection of data to export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->items;
    }

    /**
     * Return the headings for the sheet.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Order #',
            'Product',
            'Variation',
            'Quantity',
            'Price',
            'Subtotal',
        ];
    }
}
