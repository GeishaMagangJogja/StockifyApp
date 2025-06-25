<?php

namespace App\Services;

use App\Repositories\Interfaces\StockTransactionRepositoryInterface;

class StockTransactionService
{
    protected $repo;

    public function __construct(StockTransactionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    public function getByType($type)
    {
        return $this->repo->getByType($type);
    }

    public function approve($id, string $status, ?string $notes = null)
    {
        return $this->repo->approve($id, $status, $notes);
    }
}
