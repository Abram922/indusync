<?php

namespace App\Http\Controllers;
use App\Models\Inventory;
use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
   
    public function index()
    {
        $inventories = Inventory::all();
        $purchases = Purchase::with('inventory', 'status')->orderBy('created_at','desc')->paginate(10) ;
        return view('penjualan.inputPurchase', compact('inventories', 'purchases'));
    }
    
    
    public function historyPurchase()
    {
        // Mengambil semua data dari tabel inventories
        $inventories = Inventory::all(); 
    
        // Mengambil data dengan paginasi untuk tabel purchase
        $purchase = purchase::with('inventory')->paginate(10); // Tambahkan paginate(10)
    
        // Mengirim data ke view
        return view('penjualan.purchaseHistory', compact('inventories', 'purchase'));
    }
    

    public function update(Request $request, $id)
{
    $request->validate([
        'inventory_id' => 'required|exists:inventories,id',
        'quantity' => 'required|integer|min:1',
        'tanggalPembelian' => 'required|date',
        'tanggalPengiriman' => 'required|date',
        'supllier' => 'required|string',
    ]);

    try {
        \DB::beginTransaction();

        // Temukan data purchase berdasarkan ID
        $purchase = Purchase::findOrFail($id);

        // Perbarui data purchase
        $purchase->update([
            'inventory_id' => $request->input('inventory_id'),
            'quantity' => $request->input('quantity'),
            'supllier' => $request->input('supllier'),
            'tanggalPembelian' => $request->input('tanggalPembelian'),
            'tanggalPengiriman' => $request->input('tanggalPengiriman'),
        ]);

        \DB::commit();
        return redirect()->back()->with('success', 'Data purchase berhasil diperbarui!');
    } catch (\Exception $e) {
        \DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    public function print($id)
    {
        // Mengambil data pembelian dengan relasi 'inventory' dan 'status'
        $purchase = Purchase::findOrFail($id);

        //dd($purchase);
        
        // Menyiapkan data untuk PDF
        $pdf = Pdf::loadView('penjualan.pdfPurchaseHistory', compact('purchase'));
        
        // Mengunduh PDF
        return $pdf->download('purchase_'.$purchase->id.'.pdf');
    }
    

    public function printByMonth($month, $year)
{
    // Validasi parameter bulan dan tahun
    if (!is_numeric($month) || $month < 1 || $month > 12) {
        abort(400, 'Bulan tidak valid');
    }

    if (!is_numeric($year) || $year < 2000 || $year > now()->year) {
        abort(400, 'Tahun tidak valid');
    }

    // Mengambil data pembelian berdasarkan bulan dan tahun
    $purchases = Purchase::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->get();

    // Jika tidak ada data, tampilkan pesan error
    if ($purchases->isEmpty()) {
        return back()->with('error', 'Tidak ada data pembelian untuk bulan dan tahun yang dipilih.');
    }

    // Menyiapkan PDF
    $pdf = Pdf::loadView('penjualan.pdfPurchaseHistoryMonth', compact('purchases', 'month', 'year'));

    // Mengunduh PDF
    return $pdf->download('purchaseHistory_'.$month.'_'.$year.'.pdf');
}



        // app/Http/Controllers/PurchaseController.php
    public function updateStatus($id)
    {
        // Cari data purchase berdasarkan ID
        $purchase = Purchase::find($id);

        if ($purchase && $purchase->status_id == 1) {   
            // Update status_id menjadi 2
            $purchase->status_id = 2;
            $purchase->save();

            // Mengembalikan response sukses
            return redirect()->route('penjualan.inputPurchase')->with('success', 'Status berhasil diperbarui!');
        }

        // Jika tidak ditemukan atau status bukan 1
        return redirect()->route('penjualan.inputPurchase')->with('error', 'Gagal memperbarui status.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'tanggalPembelian' => 'required|date',
            'tanggalPengiriman' => 'required|date',
        ]);
    
        try {
            \DB::beginTransaction();
    
            // Ambil atau buat barang baru
            $inventoryId = $request->input('inventory_id');
            if ($inventoryId == 0) {
                $inventory = Inventory::create([
                    'name' => $request->input('namabarang'),
                    'description' => $request->input('description'),
                ]);
                $inventoryId = $inventory->id;
            }
    
            // Generate nomor resi
            $resi = now()->format('YmdHis') . mt_rand(10, 99);
    
            // Simpan data purchase
            Purchase::create([
                'inventory_id' => $inventoryId,
                'quantity' => $request->input('quantity'),
                'supllier' => $request->input('supllier'),
                'tanggalPembelian' => $request->input('tanggalPembelian'),
                'tanggalPengiriman' => $request->input('tanggalPengiriman'),
                'status_id' => 1, // Default status ID
                'resi' => $resi,
            ]);
    
            \DB::commit();
            return redirect()->back()->with('success', 'Data purchase berhasil disimpan!');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();

        return redirect()->route('penjualan.inputPurchase')->with('success', 'Data berhasil dihapus.');
    }

    
}
