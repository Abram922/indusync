<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\IncomingInventory;
use App\Models\OutcomingInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Carbon\Carbon;

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
    
        // Kirim data ke view
        return view('inventory.stockData', compact('stockData', 'months', 'incomingQuantities', 'outcomingQuantities'));
    }
    
    
    

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'namabarang' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'jenis' => 'required|string|max:255',
        ]);
    
        try {
            // Menyiapkan data untuk disimpan
            $data = [
                'kode_barang' => uniqid(),
                'namabarang' => $request->namabarang,
                'quantity' => $request->quantity,
                'tanggal_masuk' => $request->tanggal_masuk,
                'tanggal_input' => now()->toDateTimeString(),
                'keterangan' => $request->keterangan,
                'jenis' => $request->jenis,
            ];
    
            // Dump data sebelum disimpan
            //dd($data);
    
            // Simpan data ke database
            Inventory::create($data);
    
            return redirect()->route('inventory.index')->with('success', 'Data berhasil ditambahkan');
            
        } catch (\Exception $e) {
            // Tangani error dan log exception
            Log::error('Error saat menyimpan data inventory: ' . $e->getMessage());
            
            return redirect()->route('inventory.index')->with('error', 'Terjadi kesalahan saat menambahkan data');
        }
    }
    
}
