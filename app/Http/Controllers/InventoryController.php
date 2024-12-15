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
        // Debug request untuk memastikan semua data diterima
        \Log::info('Request data:', $request->all());

        // Validasi data
        $data = $request->validate([
            'inventory_id' => 'required|integer',
            'namabarang' => 'nullable|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
            'supplier' => 'required|string',
            'tanggal_masuk' => 'required|date',
        ]);

        // Log hasil validasi
        \Log::info('Data setelah validasi:', $data);

        // Proses jika inventory_id adalah 0 (barang baru)
        if ($data['inventory_id'] == 0) {
            // Periksa apakah nama barang sudah ada
            $existingInventory = Inventory::where('name', $data['namabarang'])->first();
            
            if ($existingInventory) {
                return redirect()->back()
                    ->withErrors(['namabarang' => 'Nama barang sudah ada.'])
                    ->withInput();
            }

            // Buat ID baru untuk inventory
            $lastInventory = Inventory::orderBy('id', 'desc')->first();
            $newInventoryId = $lastInventory ? $lastInventory->id + 1 : 1;

            $inventory = Inventory::create([
                'id' => $newInventoryId,
                'name' => $data['namabarang'],
                'description' => $data['description'],
            ]);

            // Set inventory_id ke barang yang baru dibuat
            $data['inventory_id'] = $inventory->id;

            \Log::info('Inventory baru dibuat:', $inventory->toArray());
        }

        // Simpan Incoming Inventory
        try {
            $incomingInventory = IncomingInventory::create([
                'inventory_id' => $data['inventory_id'],
                'quantity' => $data['quantity'],
                'supplier' => $data['supplier'],
                'received_date' => $data['tanggal_masuk'], // Format sudah sesuai
            ]);

            \Log::info('Incoming Inventory berhasil disimpan:', $incomingInventory->toArray());
        } catch (\Exception $e) {
            \Log::error('Gagal menyimpan Incoming Inventory: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menyimpan data. Silakan coba lagi.'])
                ->withInput();
        }

        // Jika sukses, beri pesan sukses
        return redirect()->route('inventory.index')->with('success', 'Data berhasil ditambahkan');
    }



    public function destroy($id)
    {
        // Cari incoming inventory berdasarkan ID
        $incomingInventory = IncomingInventory::findOrFail($id);
    
        // Hapus incoming inventory
        $incomingInventory->delete();
    
        // incominmbali ke halaman sebelumnya dengan pesan sukses
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
    

    
    public function printByMonth($month, $year)
    {
        // Validasi parameter bulan dan tahun
        if (!is_numeric($month) || $month < 1 || $month > 12) {
            abort(400, 'Bulan tidak valid');
        }
    
        if (!is_numeric($year) || $year < 2000 || $year > now()->year) {
            abort(400, 'Tahun tidak valid');
        }
    
        // Mengambil data berdasarkan bulan dan tahun
        $incomingInventories = IncomingInventory::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();
    
        // Jika tidak ada data, tampilkan pesan error
        if ($incomingInventories->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk bulan dan tahun yang dipilih.');
        }
    
        // Menyiapkan PDF
        $pdf = Pdf::loadView('inventory.pdfMonthIncoming', compact('incomingInventories', 'month', 'year'));
    
        // Mengunduh PDF
        return $pdf->download('IncomingData_' . $month . '_' . $year . '.pdf');
    }
    

    
    
    
    
}
