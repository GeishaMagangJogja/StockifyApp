<?php

namespace App\Repositories;

use App\Models\StockTransaction;
use App\Models\Product;
use App\Repositories\Interfaces\StockTransactionInterface;
use Illuminate\Support\Facades\DB;

class StockTransactionRepository implements StockTransactionInterface
{
    public function getAll()
    {
        return StockTransaction::with(['product', 'user'])->latest()->get();
    }

    public function find($id)
    {
        return StockTransaction::with(['product', 'user'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return StockTransaction::create($data);
    }

    public function update($id, array $data)
    {
        $transaction = StockTransaction::findOrFail($id);
        $transaction->update($data);
        return $transaction;
    }

    public function delete($id)
    {
        return StockTransaction::findOrFail($id)->delete();
    }

    public function updateStatus($id, $status)
    {
        $transaction = StockTransaction::findOrFail($id);
        $transaction->status = $status;
        $transaction->save();
        return $transaction;
    }

    public function getByDateRange($from, $to)
    {
        return StockTransaction::with(['product', 'user'])
            ->whereBetween('date', [$from, $to])
            ->orderBy('date', 'asc')
            ->get();
    }

    public function getSummary()
    {
        return [
            'total_products' => Product::count(),
            'total_in' => StockTransaction::where('type', 'in')->sum('quantity'),
            'total_out' => StockTransaction::where('type', 'out')->sum('quantity'),
            'recent_users' => DB::table('users')->latest()->limit(5)->get(),
        ];
    }
}
