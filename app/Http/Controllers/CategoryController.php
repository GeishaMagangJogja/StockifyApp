<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $category = $this->service->create($validated);
        return response()->json($category, 201);
    }

    public function show($id)
    {
        return response()->json($this->service->findById($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
        ]);

        $category = $this->service->update($id, $validated);
        return response()->json($category);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}
