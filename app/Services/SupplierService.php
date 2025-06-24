<?php

namespace App\Services;

use App\Repositories\Interfaces\SupplierRepositoryInterface;

class SupplierService
{
    // Gunakan constructor property promotion untuk kode yang lebih bersih
    public function __construct(protected SupplierRepositoryInterface $supplierRepository)
    {
    }

    public function getAll()
    {
        return $this->supplierRepository->getAll();
    }

    public function findById($id)
    {
        return $this->supplierRepository->findById($id);
    }

    public function create(array $data)
    {
        return $this->supplierRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->supplierRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->supplierRepository->delete($id);
    }
}