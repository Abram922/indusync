<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use App\Models\IncomingInventory;
use App\Models\OutcomingInventory;



use Illuminate\Http\Request;

class OutGoingInventroyController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel inventories
        $inventories = Inventory::all();
    
        // Mengambil data incoming_inventories yang stoknya lebih dari 0
        $incomingInventories = IncomingInventory::with('inventory')
            ->where('quantity', '>', 0)  // Filter stok lebih dari 0
            ->get();
    
        // Mengambil semua data dari tabel outgoing_inventories
        $outgoingInventories = OutcomingInventory::with('inventory')->get();
    
        // Menghitung stok untuk setiap inventory
        $inventoriesWithStock = $inventories->map(function ($inventory) use ($incomingInventories, $outgoingInventories) {
            // Menghitung total incoming untuk masing-masing inventory
            $totalIncoming = $incomingInventories->where('inventory_id', $inventory->id)->sum('quantity');
    
            // Menghitung total outgoing untuk masing-masing inventory
            $totalOutgoing = $outgoingInventories->where('inventory_id', $inventory->id)->sum('quantity');
    
            // Menghitung total stok = incoming - outgoing
            $inventory->total_stock = $totalIncoming - $totalOutgoing;
    
            return $inventory;
        });
    
        // Mengirim data ke view
        return view('inventory.outgoingItemData', compact('inventoriesWithStock', 'incomingInventories', 'outgoingInventories'));
    }
    

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'inventory_id' => 'integer',
            'quantity' => 'integer',
            'receiver' => 'integer',
            'issued_date' => 'date',
        ]);

        // Ambil inventory_id dan quantity dari request
        $inventoryId = $request->input('inventory_id');
        $requestedQuantity = $request->input('quantity');

        // Hitung stok yang tersedia dengan menjumlahkan incoming quantity dan mengurangi outgoing quantity
        $totalIncoming = IncomingInventory::where('inventory_id', $inventoryId)
            ->sum('quantity');  // Jumlahkan semua incoming quantity untuk inventory_id ini

        $totalOutgoing = OutcomingInventory::where('inventory_id', $inventoryId)
            ->sum('quantity');  // Jumlahkan semua outgoing quantity untuk inventory_id ini

        $availableStock = $totalIncoming - $totalOutgoing;  // Stok yang tersedia

        // Debugging - lihat nilai total incoming, outgoing dan stok yang tersedia
        // dd('Available Stock:', $availableStock, 'Requested Quantity:', $requestedQuantity);

        // Pengecekan apakah jumlah quantity yang diminta melebihi stok yang tersedia
        if ($requestedQuantity > $availableStock) {
            // Jika quantity yang diminta lebih besar dari stok yang tersedia
            return redirect()->back()->with('error', 'Jumlah yang diminta melebihi stok yang tersedia.');
        }

        // Jika tidak, lanjutkan dengan penyimpanan data
        DB::beginTransaction();

        try {
            // Simpan data ke tabel `outcoming_inventories`
            $outcomingInventory = [
                'inventory_id' => $inventoryId,
                'quantity' => $requestedQuantity,
                'receiver' => $request->input('receiver'),
                'issued_date' => $request->input('issued_date'),
            ];

            // Simpan data ke database
            OutcomingInventory::create($outcomingInventory);

            DB::commit();

            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {

        $outgoingInventory = OutcomingInventory::find($id);
    
        if (!$outgoingInventory) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    
        try {
            $outgoingInventory->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function print($id)
{
    // Cari data berdasarkan ID
    $outgoingInventory = OutcomingInventory::findOrFail($id);

    // Kirim data ke view untuk dicetak
    return view('inventory.pdfOutGoingData', compact('outgoingInventory'));
}
    
    
    

    
}
