<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\IncomingInventory;
use App\Models\OutcomingInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\DB;

 class InventoryController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel inventories
        $inventories = Inventory::all(); 

        $incomingInventories = IncomingInventory::with('inventory')->get(); // Dengan relasi ke inventory

        // Mengirim data ke view
        return view('inventory.incomingItemData', compact('inventories', 'incomingInventories'));
    }

    public function stockData()
    {
        // Ambil data untuk satu tahun
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
    
        // Ambil data incoming dan outcoming per bulan dalam satu tahun
        $incomingData = IncomingInventory::whereBetween('received_date', [$startOfYear, $endOfYear])
            ->selectRaw('MONTH(received_date) as month, SUM(quantity) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    
        $outcomingData = OutcomingInventory::whereBetween('issued_date', [$startOfYear, $endOfYear])
            ->selectRaw('MONTH(issued_date) as month, SUM(quantity) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    
        // Format data untuk dikirim ke view
        $months = [];
        $incomingQuantities = [];
        $outcomingQuantities = [];
    
        // Isi data bulan dan quantity untuk incoming dan outcoming
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->format('F');
            $incomingQuantities[] = $incomingData->firstWhere('month', $i)->total ?? 0;
            $outcomingQuantities[] = $outcomingData->firstWhere('month', $i)->total ?? 0;
        }
    
        // Konversi incomingQuantities dan outcomingQuantities menjadi angka
        $incomingQuantities = array_map('intval', $incomingQuantities);
        $outcomingQuantities = array_map('intval', $outcomingQuantities);
    
        // Ambil data stock dari Inventory
        $stockData = Inventory::with(['incomingInventories', 'outcomingInventories'])
            ->get()
            ->map(function ($inventory) {
                $incoming = $inventory->incomingInventories->sum('quantity');
                $outcoming = $inventory->outcomingInventories->sum('quantity');
                
                // Total quantity berdasarkan incoming dan outcoming
                $inventory->total_quantity = $incoming - $outcoming;
    
                return $inventory;
            });
    
        //dd(compact('stockData', 'months', 'incomingQuantities', 'outcomingQuantities'));
    
        return view('inventory.stockData', compact('stockData', 'months', 'incomingQuantities', 'outcomingQuantities'));
    }

    public function update(Request $request, IncomingInventory $inventory)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'inventory_id' => 'integer|exists:incoming_inventories,id',
            'namabarang' => 'string|max:255',
            'description' => 'nullable|string|max:500',
            'quantity' => 'integer|min:1',
            'supplier' => 'string|max:255',
            'tanggal_masuk' => 'date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Pengelolaan error saat proses update
        try {
            $inventory->update($validatedData);
            return redirect()->route('inventory.incomingItemData')->with('success', 'Item berhasil diperbarui.');
        } catch (\Exception $e) {
            // Jika terjadi error, alihkan kembali dengan pesan error
            return back()->withErrors(['error' => 'Gagal memperbarui item: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'inventory_id' => 'integer',
            'namabarang' => 'string',
            'description' => 'nullable|string',
            'quantity' => 'integer',
            'supplier' => 'string',
            'tanggal_masuk' => 'date',
            'keterangan' => 'nullable|string',
        ]);
    
        if ($data['inventory_id'] == '0') {
            // Periksa apakah nama barang sudah ada
            $existingInventory = Inventory::where('name', $data['namabarang'])->first();
            
            if ($existingInventory) {
                return redirect()->back()->withErrors(['namabarang' => 'Nama barang sudah ada.'])->withInput();
            }
    
            // Logika penyimpanan inventory baru
            $lastInventory = Inventory::orderBy('id', 'desc')->first();
            $newInventoryId = $lastInventory ? $lastInventory->id + 1 : 1;
    
            $inventory = Inventory::create([
                'id' => $newInventoryId,
                'name' => $data['namabarang'],
                'description' => $data['description'],
            ]);
            
            $data['inventory_id'] = $inventory->id;
        }
    
        // Simpan Incoming Inventory
        IncomingInventory::create([
            'inventory_id' => $data['inventory_id'],
            'quantity' => $data['quantity'],
            'supplier' => $data['supplier'],
            'received_date' => $data['tanggal_masuk'],
        ]);
    
        // Jika sukses, beri pesan sukses
        return redirect()->route('inventory.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function destroy($id)
    {
        // Cari incoming inventory berdasarkan ID
        $incomingInventory = IncomingInventory::findOrFail($id);
    
        // Hapus incoming inventory
        $incomingInventory->delete();
    
        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('inventory.index')->with('success', 'Data berhasil dihapus');
    }




    public function print($id)
    {
        // Mengambil data pembelian dengan relasi 'inventory' dan 'status'
        $incomingInventory = IncomingInventory::findOrFail($id);

        //dd($purchase);
        
        // Menyiapkan data untuk PDF
        $pdf = Pdf::loadView('inventory.pdfIncomingData', compact('incomingInventory'));
        
        // Mengunduh PDF
        return $pdf->download('OutGoingData'.$incomingInventory->id.'.pdf');
    }
    

    
    
    
    
    
    
}
