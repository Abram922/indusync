<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\IncomingInventory;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel inventories
        $inventories = Inventory::all(); 

        $sales = Sales::with('inventory')->get(); // Dengan relasi ke inventory

        // Mengirim data ke view
        return view('penjualan.inputSales', compact('inventories', 'sales'));
    }

    public function salesHistory()
    {
        // Mengambil semua data dari tabel inventories
        $inventories = Inventory::all(); 

        $sales = Sales::with('inventory')->get(); // Dengan relasi ke inventory

        // Mengirim data ke view
        return view('penjualan.salesHistory', compact('inventories', 'sales'));
    }





    public function store(Request $request)
    {
        $data = $request->validate([
            'inventory_id' => 'required|integer',
            'namabarang' => 'nullable|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
            'harga' => 'required|numeric',
            'namaCustomer' => 'required|string',
            'tanggalPenjualan' => 'required|date',
            'Keterangan' => 'nullable|string',
        ]);
    
    
        if ($data['inventory_id'] == 0) {
            $existingInventory = Inventory::where('name', $data['namabarang'])->first();
            if ($existingInventory) {
                return redirect()->back()->withErrors(['namabarang' => 'Nama barang sudah ada.'])->withInput();
            }
    
            $inventory = Inventory::create([
                'name' => $data['namabarang'],
                'description' => $data['description'],
            ]);
            $data['inventory_id'] = $inventory->id;
        }
    
        // Debug sebelum simpan
    
        Sales::create([
            'inventory_id' => $data['inventory_id'],
            'quantity' => $data['quantity'],
            'harga' => $data['harga'],
            'namaCustomer' => $data['namaCustomer'],
            'tanggalPenjualan' => $data['tanggalPenjualan'],
            'Keterangan' => $data['Keterangan'],
        ]);
    
        return redirect()->route('penjualan.inputSales')->with('success', 'Data berhasil ditambahkan');
    }

    public function destroy($id)
    {

        dd($id);
        // Cari IncomingInventory berdasarkan ID
        $incomingInventory = IncomingInventory::find($id);
    
        // Cek apakah data ditemukan
        if (!$incomingInventory) {
            return redirect()->route('penjualan.inputSales')->with('error', 'Data tidak ditemukan');
        }
    
        try {
            // Hapus data IncomingInventory
            $incomingInventory->delete();
    
            // Jika berhasil, beri pesan sukses
            return redirect()->route('penjualan.inputSales')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            // Jika terjadi error, beri pesan error
            return redirect()->route('penjualan.inputSales')->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }



    public function print($id)
    {
        $sales = Sales::findOrFail($id);
        $pdf = PDF::loadView('penjualan.pdfInputPurchase', compact('sales'));
    
        return $pdf->download('sales_'.$sales->id.'.pdf');
    }
    
    

    
}
