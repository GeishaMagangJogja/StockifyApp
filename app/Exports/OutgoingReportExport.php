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

class OutgoingReportExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        // Query ini disesuaikan untuk mengambil data barang KELUAR
        $query = StockTransaction::query()
            ->with(['product', 'user']) // Relasi supplier tidak ada di laporan keluar
            ->where('type', 'keluar'); // PERUBAHAN UTAMA: Mengambil tipe 'keluar'

        // Terapkan filter dari request (jika user mengisi filter di UI)
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($subq) use ($search) {
                    $subq->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                })->orWhere('notes', 'like', "%{$search}%"); // Mencari di kolom catatan/tujuan
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

        // Mengurutkan berdasarkan tanggal terbaru
        return $query->latest('date');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Disesuaikan dengan kolom pada tabel UI Laporan Barang Keluar
        return [
            'Tanggal',
            'Nama Produk',
            'SKU Produk',
            'Jumlah',
            'Catatan/Tujuan', // PERUBAHAN: dari Supplier menjadi Catatan/Tujuan
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
        // Mapping data disesuaikan dengan kolom pada Laporan Barang Keluar
        return [
            Carbon::parse($transaction->date)->format('d M Y H:i'),
            optional($transaction->product)->name ?? 'N/A',
            optional($transaction->product)->sku ?? 'N/A',
            $transaction->quantity,
            $transaction->notes ?? '-', // PERUBAHAN: Mengambil dari kolom 'notes'
            ucfirst($transaction->status),
            optional($transaction->user)->name ?? 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Membuat baris heading (baris pertama) menjadi tebal (bold)
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}