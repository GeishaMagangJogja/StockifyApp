<?php

namespace App\Services;

use App\Repositories\Interfaces\StockTransactionInterface;

class StockTransactionService
{
    protected $repository;

    public function __construct(StockTransactionInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTransactions()
    {
        return $this->repository->getAll();
    }

    public function createTransaction($data, $userId)
    {
        $data['user_id'] = $userId;
        return $this->repository->create($data);
    }

    public function getTransactionById($id)
    {
        return $this->repository->find($id);
    }

    public function updateTransaction($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteTransaction($id)
    {
        return $this->repository->delete($id);
    }

    public function confirmTransaction($id)
    {
        return $this->repository->updateStatus($id, 'confirmed');
    }

    public function getReport($from, $to)
    {
        return $this->repository->getByDateRange($from, $to);
    }

    public function getDashboardSummary()
    {
        return $this->repository->getSummary();
    }
}
