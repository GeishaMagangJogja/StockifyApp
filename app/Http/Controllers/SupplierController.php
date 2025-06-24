<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $supplierService)
    {
    }

    public function index()
    {
        try {
            $suppliers = $this->supplierService->getAll();

            return response()->json([
                'success' => true,
                'message' => 'Data supplier berhasil diambil',
                'data' => [
                    'suppliers' => $suppliers,
                    'total_suppliers' => $suppliers->count(),
                    'timestamp' => now()->toDateTimeString()
                ],
                'meta' => [
                    'endpoint' => '/api/suppliers',
                    'method' => 'GET',
                    'description' => 'Menampilkan semua data supplier'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data supplier',
                'error' => 'Terjadi kesalahan pada server',
                'code' => 'FETCH_SUPPLIERS_ERROR'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $supplier = $this->supplierService->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail supplier berhasil diambil',
                'data' => [
                    'supplier' => $supplier,
                    'retrieved_at' => now()->toDateTimeString()
                ],
                'meta' => [
                    'endpoint' => "/api/suppliers/{$id}",
                    'method' => 'GET',
                    'description' => 'Menampilkan detail supplier berdasarkan ID'
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan',
                'error' => "Supplier dengan ID {$id} tidak ada dalam database",
                'code' => 'SUPPLIER_NOT_FOUND'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail supplier',
                'error' => 'Terjadi kesalahan pada server',
                'code' => 'FETCH_SUPPLIER_ERROR'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|unique:suppliers,email'
            ]);

            $supplier = $this->supplierService->create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil ditambahkan!',
                'data' => [
                    'supplier' => $supplier
                ],
                'meta' => [
                    'endpoint' => '/api/suppliers',
                    'method' => 'POST',
                    'description' => 'Menambahkan supplier baru ke database'
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
                'message' => 'Gagal menambahkan supplier',
                'error' => 'Terjadi kesalahan pada server saat menyimpan data',
                'code' => 'CREATE_SUPPLIER_ERROR'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi email harus unik, tapi abaikan email dari supplier yang sedang di-update
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'address' => 'sometimes|nullable|string',
                'phone' => 'sometimes|nullable|string|max:20',
                'email' => 'sometimes|required|email|unique:suppliers,email,' . $id
            ]);
            
            if (empty($validated)) {
                 return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data untuk diupdate',
                    'code' => 'NO_DATA_TO_UPDATE'
                ], 400);
            }

            $supplier = $this->supplierService->update($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil diperbarui!',
                'data' => [
                    'supplier' => $supplier,
                    'updated_fields' => array_keys($validated)
                ],
                'meta' => [
                    'endpoint' => "/api/suppliers/{$id}",
                    'method' => 'PUT/PATCH',
                    'description' => 'Memperbarui data supplier berdasarkan ID'
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid',
                'errors' => $e->errors(),
                'code' => 'VALIDATION_ERROR'
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan',
                'error' => "Supplier dengan ID {$id} tidak ada untuk diupdate",
                'code' => 'SUPPLIER_NOT_FOUND'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui supplier',
                'error' => 'Terjadi kesalahan pada server',
                'code' => 'UPDATE_SUPPLIER_ERROR'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Ambil data dulu untuk response, findById akan throw error jika tidak ada
            $supplier = $this->supplierService->findById($id);

            $this->supplierService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil dihapus!',
                'data' => [
                    'deleted_supplier_info' => [
                        'id' => $supplier->id,
                        'name' => $supplier->name,
                    ],
                    'deleted_at' => now()->toDateTimeString()
                ],
                'meta' => [
                    'endpoint' => "/api/suppliers/{$id}",
                    'method' => 'DELETE',
                    'description' => 'Menghapus supplier berdasarkan ID'
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan',
                'error' => "Supplier dengan ID {$id} tidak ada untuk dihapus",
                'code' => 'SUPPLIER_NOT_FOUND'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus supplier',
                'error' => 'Terjadi kesalahan pada server',
                'code' => 'DELETE_SUPPLIER_ERROR'
            ], 500);
        }
    }
}