<?php

namespace App\Repositories;

use App\Models\ProductAttribute;
use App\Repositories\Interfaces\ProductAttributeRepositoryInterface;

class ProductAttributeRepository implements ProductAttributeRepositoryInterface
{
    public function getAll()
    {
        return ProductAttribute::with('product:id,name')->get();
    }

    public function findById($id)
    {
        return ProductAttribute::find($id);
    }

    public function create(array $data)
    {
        return ProductAttribute::create($data);
    }

    public function update($id, array $data)
    {
        $attribute = $this->findById($id);
        if ($attribute) {
            $attribute->update($data);
            return $attribute;
        }
        return null;
    }

    public function delete($id)
    {
        $attribute = $this->findById($id);
        if ($attribute) {
            return $attribute->delete();
        }
        return false;
    }
}