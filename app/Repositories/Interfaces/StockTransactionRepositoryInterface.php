<?php

namespace App\Repositories\Interfaces;

interface StockTransactionRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getByType($type);
    public function approve($id, string $status, ?string $notes = null);
}
