<?php

namespace App\Repositories;

use App\Repositories\Interfaces\StockTransactionRepositoryInterface;
use App\Models\StockTransaction;

class StockTransactionRepository implements StockTransactionRepositoryInterface
{
    public function getAll()
    {
        return StockTransaction::with(['product', 'user'])->latest()->get();
    }

    public function findById($id)
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
        return StockTransaction::destroy($id);
    }

    public function getByType($type)
    {
        return StockTransaction::where('type', $type)->with(['product', 'user'])->get();
    }

    public function approve($id, string $status, ?string $notes = null)
    {
        $transaction = StockTransaction::findOrFail($id);
        $transaction->status = $status;
        $transaction->notes = $notes;
        $transaction->save();
        return $transaction;
    }
}
