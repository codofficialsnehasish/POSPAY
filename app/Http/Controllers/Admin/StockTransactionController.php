<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockTransaction;

class StockTransactionController extends Controller
{
    public function index(Request $request)
    {
        $stockTransactions = StockTransaction::with(['product', 'variationOption'])
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('admin.stock_transactions.index', compact('stockTransactions'));
    }
}
