<?php

namespace App\Http\Controllers;

use App\Models\Inventory; // Gunakan namespace yang benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
class InventoryController extends Controller
{
    public function index()
    {
        // Mengambil semua data inventory menggunakan Eloquent
        $inventories = Inventory::all(); // Mengambil semua data dari tabel inventories

        // Mengirim data ke view
        return view('inventory.incomingItemData', compact('inventories')); // Mengirim variabel 'inventories' ke view
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
