<?php

namespace App\Http\Controllers;

use App\Services\ProductAttributeService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductAttributeController extends Controller
{
    protected ProductAttributeService $productAttributeService;

    public function __construct(ProductAttributeService $productAttributeService)
    {
        $this->productAttributeService = $productAttributeService;
    }

    /**
     * Menampilkan semua atribut produk.
     */
    public function index()
    {
        try {
            $attributes = $this->productAttributeService->getAll();

            return response()->json([
                'success' => true,
                'message' => 'Data atribut produk berhasil diambil',
                'data' => [
                    'product_attributes' => $attributes,
                    'total_attributes' => count($attributes),
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ],
                'meta' => [
                    'endpoint' => '/api/product-attributes',
                    'method' => 'GET',
                    'description' => 'Menampilkan semua data atribut produk'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data atribut produk',
                'error' => 'Terjadi kesalahan pada server',
                'code' => 'FETCH_ATTRIBUTES_ERROR'
            ], 500);
        }
    }

    /**
     * Menampilkan detail atribut produk berdasarkan ID.
     */
    public function show($id)
    {
        try {
            $attribute = $this->productAttributeService->findById($id);

            if (!$attribute) {
                return response()->json([
                    'success' => false,
                    'message' => 'Atribut produk tidak ditemukan',
                    'error' => "Atribut produk dengan ID {$id} tidak ada dalam database",
                    'code' => 'PRODUCT_ATTRIBUTE_NOT_FOUND'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail atribut produk berhasil diambil',
                'data' => [
                    'product_attribute' => $attribute,
                    'retrieved_at' => now()->format('Y-m-d H:i:s')
                ],
                'meta' => [
                    'endpoint' => "/api/product-attributes/{$id}",
                    'method' => 'GET',
                    'description' => 'Menampilkan detail atribut produk berdasarkan ID'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail atribut produk',
                'error' => 'Terjadi kesalahan pada server',
                'code' => 'FETCH_ATTRIBUTE_ERROR'
            ], 500);
        }
    }

    /**
     * Menyimpan atribut produk baru.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'name' => 'required|string|max:255',
                'value' => 'required|string|max:255'
            ]);

            $attribute = $this->productAttributeService->create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Atribut produk berhasil ditambahkan!',
                'data' => [
                    'product_attribute' => $attribute,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'summary' => [
                        'product_id' => $attribute->product_id,
                        'attribute_name' => $attribute->name,
                        'attribute_value' => $attribute->value,
                    ]
                ],
                'meta' => [
                    'endpoint' => '/api/product-attributes',
                    'method' => 'POST',
                    'description' => 'Menambahkan atribut produk baru ke database'
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid',
                'errors' => $e->errors(),
                'code' => 'VALIDATION_ERROR'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan atribut produk',
                'error' => 'Terjadi kesalahan pada server saat menyimpan data',
                'code' => 'CREATE_ATTRIBUTE_ERROR'
            ], 500);
        }
    }

    /**
     * Memperbarui atribut produk yang ada.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'sometimes|exists:products,id',
                'name' => 'sometimes|string|max:255',
                'value' => 'sometimes|string|max:255'
            ]);

            $attribute = $this->productAttributeService->update($id, $validated);

           if (!$attribute) {
                return response()->json([
                    'success' => false,
                    'message' => 'Atribut produk tidak ditemukan',
                    'error' => "Atribut produk dengan ID {$id} tidak ada dalam database",
                    'code' => 'PRODUCT_ATTRIBUTE_NOT_FOUND'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Atribut produk berhasil diperbarui!',
                'data' => [
                    'product_attribute' => $attribute,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                    'updated_fields' => array_keys($validated)
                ],
                'meta' => [
                    'endpoint' => "/api/product-attributes/{$id}",
                    'method' => 'PUT/PATCH',
                    'description' => 'Memperbarui data atribut produk berdasarkan ID'
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid',
                'errors' => $e->errors(),
                'code' => 'VALIDATION_ERROR'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui atribut produk',
                'error' => 'Terjadi kesalahan pada server saat mengupdate data',
                'code' => 'UPDATE_ATTRIBUTE_ERROR'
            ], 500);
        }
    }

    /**
     * Menghapus atribut produk.
     */
    public function destroy($id)
    {
        try {
            $attribute = $this->productAttributeService->findById($id);

            if (!$attribute) {
                return response()->json([
                    'success' => false,
                    'message' => 'Atribut produk tidak ditemukan',
                    'error' => "Atribut produk dengan ID {$id} tidak ada dalam database",
                    'code' => 'PRODUCT_ATTRIBUTE_NOT_FOUND'
                ], 404);
            }

            $this->productAttributeService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Atribut produk berhasil dihapus!',
                'data' => [
                    'deleted_attribute' => [
                        'id' => $id,
                        'name' => $attribute->name,
                        'value' => $attribute->value
                    ],
                    'deleted_at' => now()->format('Y-m-d H:i:s')
                ],
                'meta' => [
                    'endpoint' => "/api/product-attributes/{$id}",
                    'method' => 'DELETE',
                    'description' => 'Menghapus atribut produk berdasarkan ID',
                    'warning' => 'Data yang dihapus tidak dapat dikembalikan'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus atribut produk',
                'error' => 'Terjadi kesalahan pada server saat menghapus data',
                'code' => 'DELETE_ATTRIBUTE_ERROR'
            ], 500);
        }
    }
}