<?php

namespace App\Exports;

use App\Models\StockTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class IncomingReportExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // Query ini harus cocok dengan query untuk menampilkan data di halaman
        $query = StockTransaction::query()
            ->with(['product', 'supplier', 'user']);

        // ===================================================================
        // PERBAIKAN UTAMA: Mengganti 'in' menjadi 'masuk' agar sama dengan Controller
        // ===================================================================
        $query->where('type', 'masuk');

        // Terapkan filter dari request (jika user mengisi filter di UI)
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($subq) use ($search) {
                    $subq->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                })->orWhereHas('supplier', function ($subq) use ($search) {
                    $subq->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('date_start')) {
            $query->whereDate('date', '>=', $this->request->date_start);
        }

        if ($this->request->filled('date_end')) {
            $query->whereDate('date', '<=', $this->request->date_end);
        }
        
        // Mengurutkan berdasarkan tanggal terbaru, sama seperti di Controller
        return $query->latest('date');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Sesuaikan dengan kolom di UI Laporan Riwayat Barang Masuk
        return [
            'Tanggal',
            'Nama Produk',
            'SKU Produk',
            'Jumlah',
            'Supplier',
            'Status',
            'Diproses Oleh',
        ];
    }

    /**
     * @param StockTransaction $transaction
     * @return array
     */
    public function map($transaction): array
    {
        // Mapping data agar sesuai dengan headings
        return [
            Carbon::parse($transaction->date)->format('d M Y H:i'),
            optional($transaction->product)->name ?? 'N/A',
            optional($transaction->product)->sku ?? 'N/A',
            $transaction->quantity,
            optional($transaction->supplier)->name ?? 'N/A',
            ucfirst($transaction->status),
            optional($transaction->user)->name ?? 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk baris heading
            1 => ['font' => ['bold' => true]],
        ];
    }
}