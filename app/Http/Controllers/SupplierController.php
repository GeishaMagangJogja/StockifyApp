<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query()
            ->withCount('products')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->paginate(10);

        return view('pages.admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.suppliers.create', [
            'title' => 'Tambah Supplier Baru',
            'header' => 'Form Tambah Supplier'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:suppliers',
            'address' => 'nullable|string',
        ]);

        try {
            Supplier::create($validated);

            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Supplier berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan supplier: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load(['products' => function($query) {
            $query->with(['category'])
                 ->select('id', 'name', 'sku', 'category_id', 'supplier_id')
                 ->withCount('stockTransactions');
        }]);

        // Calculate current stock for each product
        $supplier->products->each(function($product) {
            $product->current_stock = $product->stockTransactions()
                ->selectRaw('SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END) as stock')
                ->value('stock') ?? 0;
        });

        return view('pages.admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('pages.admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,'.$supplier->id,
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:suppliers,email,'.$supplier->id,
            'address' => 'nullable|string',
        ]);

        try {
            $supplier->update($validated);

            return redirect()->route('admin.suppliers.show', $supplier->id)
                ->with('success', 'Data supplier berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui supplier: '.$e->getMessage());
        }
    }

    /**
     * Show the confirmation page for deleting the specified resource.
     */
    public function confirmDelete(Supplier $supplier)
    {
        $supplier->loadCount('products');
        return view('pages.admin.suppliers.delete', compact('supplier'));
    }

    /**
     * Remove the specified resource from storage.
     */
  public function destroy(Supplier $supplier)
{
    DB::beginTransaction();

    try {
        // Putuskan relasi dengan produk
        $supplier->products()->update(['supplier_id' => null]);

        // Hapus permanen
        $supplier->forceDelete();

        DB::commit();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil dihapus permanen');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Gagal menghapus supplier: ' . $e->getMessage());
    }
}
}

