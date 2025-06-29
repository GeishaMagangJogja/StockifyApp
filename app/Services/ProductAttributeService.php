<?php

namespace App\Services;

use App\Models\ProductAttribute;
use App\Repositories\Interfaces\ProductAttributeRepositoryInterface;

class ProductAttributeService
{
    protected ProductAttributeRepositoryInterface $attributeRepo;

    public function __construct(ProductAttributeRepositoryInterface $attributeRepo)
    {
        $this->attributeRepo = $attributeRepo;
    }

    public function getAll()
    {
        return $this->attributeRepo->getAll();
    }

    public function findById($id)
    {
        return $this->attributeRepo->findById($id);
    }

    public function create(array $data)
    {
        return $this->attributeRepo->create($data);
    }

    public function update($id, array $data)
    {
        return $this->attributeRepo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->attributeRepo->delete($id);
    }
}