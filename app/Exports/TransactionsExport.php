<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    protected $transactions;

    /**
     * Create a new export instance.
     *
     * @param  \Illuminate\Support\Collection|array  $transactions
     */
    public function __construct($transactions)
    {
        $this->transactions = collect($transactions);
    }

    /**
     * Return the collection of data to export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transactions;
    }

    /**
     * Return the headings for the sheet.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date & Time',
            'Bill No',
            'Payment Mode',
            'Amount'
        ];
    }
}
