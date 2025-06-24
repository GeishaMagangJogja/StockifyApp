<?php

namespace App\Repositories\Interfaces;

interface StockTransactionInterface
{
    public function getAll();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function updateStatus($id, $status);
    public function getByDateRange($from, $to);
    public function getSummary();
}
