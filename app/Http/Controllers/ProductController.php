<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {

        try {
            $products = $this->productService->getAll();

            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diambil',
                'data' => [
                    'products' => $products,
                    'total_products' => count($products),
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ],
                'meta' => [
                    'endpoint' => '/api/products',
                    'method' => 'GET',
                    'description' => 'Menampilkan semua data produk'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk',
                'error' => 'Terjadi kesalahan pada server',
                'code' => 'FETCH_PRODUCTS_ERROR'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productService->findById($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan',
                    'error' => "Produk dengan ID {$id} tidak ada dalam database",
                    'code' => 'PRODUCT_NOT_FOUND'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail produk berhasil diambil',
                'data' => [
                    'product' => $product,
                    'retrieved_at' => now()->format('Y-m-d H:i:s')
                ],
                'meta' => [
                    'endpoint' => "/api/products/{$id}",
                    'method' => 'GET',
                    'description' => 'Menampilkan detail produk berdasarkan ID'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail produk',
                'error' => 'Terjadi kesalahan pada server',
                'code' => 'FETCH_PRODUCT_ERROR'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'name' => 'required|string',
                'sku' => 'required|string|unique:products',
                'description' => 'nullable|string',
                'purchase_price' => 'required|numeric',
                'selling_price' => 'required|numeric',
                'image' => 'nullable|string',
                'min_stock' => 'required|integer'
            ]);

            $product = $this->productService->create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke sistem!',
                'data' => [
                    'product' => $product,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'summary' => [
                        'product_name' => $product->name ?? $validated['name'],
                        'sku' => $product->sku ?? $validated['sku'],
                        'category_id' => $validated['category_id'],
                        'supplier_id' => $validated['supplier_id'],
                        'purchase_price' => number_format($validated['purchase_price'], 0, ',', '.'),
                        'selling_price' => number_format($validated['selling_price'], 0, ',', '.'),
                        'profit_margin' => number_format((($validated['selling_price'] - $validated['purchase_price']) / $validated['purchase_price']) * 100, 2) . '%'
                    ]
                ],
                'meta' => [
                    'endpoint' => '/api/products',
                    'method' => 'POST',
                    'description' => 'Menambahkan produk baru ke database'
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid',
                'errors' => $e->errors(),
                'code' => 'VALIDATION_ERROR'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk',
                'error' => 'Terjadi kesalahan pada server saat menyimpan data',
                'code' => 'CREATE_PRODUCT_ERROR'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'sometimes|exists:categories,id',
                'supplier_id' => 'sometimes|exists:suppliers,id',
                'name' => 'sometimes|string',
                'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
                'description' => 'nullable|string',
                'purchase_price' => 'sometimes|numeric',
                'selling_price' => 'sometimes|numeric',
                'image' => 'nullable|string',
                'min_stock' => 'sometimes|integer'
            ]);

            $product = $this->productService->update($id, $validated);

           if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan',
                    'error' => "Produk dengan ID {$id} tidak ada dalam database",
                    'code' => 'PRODUCT_NOT_FOUND'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui!',
                'data' => [
                    'product' => $product,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                    'updated_fields' => array_keys($validated),
                    'total_updated_fields' => count($validated)
                ],
                'meta' => [
                    'endpoint' => "/api/products/{$id}",
                    'method' => 'PUT/PATCH',
                    'description' => 'Memperbarui data produk berdasarkan ID'
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid',
                'errors' => $e->errors(),
                'code' => 'VALIDATION_ERROR'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk',
                'error' => 'Terjadi kesalahan pada server saat mengupdate data',
                'code' => 'UPDATE_PRODUCT_ERROR'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Coba ambil data produk dulu untuk konfirmasi
            $product = $this->productService->findById($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan',
                    'error' => "Produk dengan ID {$id} tidak ada dalam database",
                    'code' => 'PRODUCT_NOT_FOUND'
                ], 404);
            }

            $productName = $product->name ?? "ID: {$id}";
            $productSku = $product->sku ?? "Unknown SKU";

            $this->productService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari sistem!',
                'data' => [
                    'deleted_product' => [
                        'id' => $id,
                        'name' => $productName,
                        'sku' => $productSku
                    ],
                    'deleted_at' => now()->format('Y-m-d H:i:s')
                ],
                'meta' => [
                    'endpoint' => "/api/products/{$id}",
                    'method' => 'DELETE',
                    'description' => 'Menghapus produk berdasarkan ID',
                    'warning' => 'Data yang dihapus tidak dapat dikembalikan'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk',
                'error' => 'Terjadi kesalahan pada server saat menghapus data',
                'code' => 'DELETE_PRODUCT_ERROR'
            ], 500);
        }
    }
}
