<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use App\Models\IncomingInventory;
use App\Models\OutcomingInventory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;



use Illuminate\Http\Request;

class OutGoingInventroyController extends Controller
{
    public function financialRecap()
    {
        // Mendapatkan data OutcomingInventory
        $outcomingData = OutcomingInventory::selectRaw('MONTH(issued_date) as month, SUM(harga * quantity) as total_value')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    
        // Mengisi array bulan (1-12) untuk memastikan data konsisten
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create()->month($month)->format('F');
        });
    
        // Data total nilai untuk grafik (harga * quantity per bulan)
        $totalValues = $months->map(function ($month, $index) use ($outcomingData) {
            $data = $outcomingData->firstWhere('month', $index + 1);
            return $data ? (int) $data->total_value : 0;  // Ubah menjadi integer
        });
    
        // Data dummy untuk incoming quantities (jika diperlukan)
        // Anda bisa mengganti dengan data dari tabel lain, misalnya IncomingInventory
        $incomingQuantities = $months->map(function () {
            return rand(100, 500); // Data simulasi
        });
    
        return view('penjualan.financialRecap', [
            'months' => $months,
            'incomingQuantities' => $incomingQuantities,
            'totalValues' => $totalValues,  // Pastikan ini dikirim ke view
        ]);
    }
    
    public function index()
    {
        // Mengambil semua data dari tabel inventories
        $inventories = Inventory::all();
    
        // Mengambil data incoming_inventories yang stoknya lebih dari 0
        $incomingInventories = IncomingInventory::with('inventory')
            ->where('quantity', '>', 0)  // Filter stok lebih dari 0
            ->get();
    
        // Mengambil semua data dari tabel outgoing_inventories dengan pagination
        $outgoingInventories = OutcomingInventory::with('inventory')->orderBy('created_at','desc')->paginate(10); // paginate dengan jumlah per halaman
    
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
    

    public function salesHistory()
    {
        // Mengambil semua data dari tabel inventories
        $inventories = Inventory::all();
    
        // Mengambil data incoming_inventories yang stoknya lebih dari 0
        $incomingInventories = IncomingInventory::with('inventory')
            ->where('quantity', '>', 0)  // Filter stok lebih dari 0
            ->get();
    
        // Mengambil semua data dari tabel outgoing_inventories
        $outgoingInventories = OutcomingInventory::with('inventory')->orderBy('created_at','desc')->paginate(10); // Tambahkan paginate(10)
    
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
        return view('penjualan.salesHistory', compact('inventoriesWithStock', 'incomingInventories', 'outgoingInventories'));
    }
    
    public function inputsales(Request $request)
    {
        // Mengambil semua data dari tabel inventories
        $inventories = Inventory::all();
    
        // Mengambil data incoming_inventories yang stoknya lebih dari 0
        $incomingInventories = IncomingInventory::with('inventory')
            ->where('quantity', '>', 0)  // Filter stok lebih dari 0
            ->get();
    
        // Mengambil semua data dari tabel outgoing_inventories dengan pagination
        $outgoingInventories = OutcomingInventory::with('inventory')->orderBy('created_at','desc')
            ->paginate(10); // 10 data per halaman
        
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
        return view('penjualan.inputSales', compact('inventoriesWithStock', 'incomingInventories', 'outgoingInventories'));
    }
    

    public function update(Request $request, $id)
    {
        // Ambil data OutgoingInventory berdasarkan ID
        $outgoingInventory = OutcomingInventory::findOrFail($id);
    
        // Ambil inventory_id dan quantity dari request
        $inventoryId = $request->input('inventory_id');
        $requestedQuantity = $request->input('quantity');
    
        // Ambil semua incoming dan outgoing inventory
        $incomingInventories = IncomingInventory::with('inventory')
            ->where('quantity', '>', 0)  // Filter stok lebih dari 0
            ->get();
    
        $outgoingInventories = OutcomingInventory::with('inventory')->get();
    
        // Hitung stok untuk setiap inventory
        $inventoriesWithStock = IncomingInventory::with('inventory')
            ->get()
            ->merge(OutcomingInventory::with('inventory')->get())
            ->map(function ($inventory) use ($incomingInventories, $outgoingInventories, $inventoryId) {
                $totalIncoming = $incomingInventories->where('inventory_id', $inventoryId)->sum('quantity');
                $totalOutgoing = $outgoingInventories->where('inventory_id', $inventoryId)->sum('quantity');
    
                $inventory->total_stock = $totalIncoming - $totalOutgoing;
                return $inventory;
            });
    
        // Cari stok yang tersedia untuk inventory_id yang dipilih
        $selectedInventory = $inventoriesWithStock->firstWhere('id', $inventoryId);
        $availableStock = $selectedInventory->total_stock ?? 0;
    
        // Pengecekan apakah quantity yang diminta melebihi stok yang tersedia
        if ($requestedQuantity > $availableStock) {
            return redirect()->back()->with('error', 'Jumlah yang diminta melebihi stok yang tersedia.');
        }
    
        // Proses update data
        $outgoingInventory->inventory_id = $request->input('inventory_id');
        $outgoingInventory->quantity = $request->input('quantity');
        $outgoingInventory->harga = $request->input('harga');
        $outgoingInventory->customer_name = $request->input('customer_name');
        $outgoingInventory->receiver = $request->input('receiver');
        $outgoingInventory->keterangan = $request->input('keterangan');
        $outgoingInventory->issued_date = $request->input('issued_date');
        $outgoingInventory->save();
    
        return redirect()->route('penjualan.inputSales')->with('success', 'Data berhasil diperbarui.');
    }
    
    
    
    

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'inventory_id' => 'exists:inventories,id',
            'quantity' => 'integer|min:1',
            'harga' => 'numeric|min:0',
            'customer_name' => 'string|max:255',
            'receiver' => 'string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'issued_date' => 'date',
        ]);
    
        // Ambil inventory_id dan quantity dari request
        $inventoryId = $request->input('inventory_id');
        $requestedQuantity = $request->input('quantity');
    
        // Hitung stok yang tersedia
        $totalIncoming = IncomingInventory::where('inventory_id', $inventoryId)
            ->sum('quantity');  // Jumlahkan semua incoming quantity untuk inventory_id ini
    
        $totalOutgoing = OutcomingInventory::where('inventory_id', $inventoryId)
            ->sum('quantity');  // Jumlahkan semua outgoing quantity untuk inventory_id ini
    
        $availableStock = $totalIncoming - $totalOutgoing;  // Stok yang tersedia
    
        // Pengecekan apakah quantity yang diminta melebihi stok yang tersedia
        if ($requestedQuantity > $availableStock) {
            return redirect()->back()->with('error', 'Jumlah yang diminta melebihi stok yang tersedia.');
        }
    
        // Hitung total harga
        $totalPrice = $request->input('harga') * $requestedQuantity;
    
        DB::beginTransaction();
    
        try {
            // Simpan data ke tabel `outcoming_inventories`
            $outcomingInventory = OutcomingInventory::create([
                'inventory_id' => $inventoryId,
                'quantity' => $requestedQuantity,
                'harga' => $request->input('harga'),
                'total_price' => $totalPrice,
                'customer_name' => $request->input('customer_name'),
                'receiver' => $request->input('receiver'),
                'keterangan' => $request->input('keterangan'),
                'issued_date' => $request->input('issued_date'),
            ]);
    

    
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
        // Mengambil data pembelian dengan relasi 'inventory' dan 'status'
        $outgoingInventory = OutcomingInventory::findOrFail($id);

        //dd($purchase);
        
        // Menyiapkan data untuk PDF
        $pdf = Pdf::loadView('inventory.pdfOutGoingData', compact('outgoingInventory'));
        
        // Mengunduh PDF
        return $pdf->download('OutGoingData'.$outgoingInventory->id.'.pdf');
    }
    
    public function printSales($id)
    {
        // Mengambil data pembelian dengan relasi 'inventory' dan 'status'
        $outgoingInventory = OutcomingInventory::findOrFail($id);

        //dd($purchase);
        
        // Menyiapkan data untuk PDF
        $pdf = Pdf::loadView('penjualan.pdfInputPurchase', compact('outgoingInventory'));
        
        // Mengunduh PDF
        return $pdf->download('salesHistory'.$outgoingInventory->id.'.pdf');
    }

    public function printSalesByMonth($month, $year)
    {
        // Validasi parameter bulan dan tahun
        if (!is_numeric($month) || $month < 1 || $month > 12) {
            abort(400, 'Bulan tidak valid');
        }
    
        if (!is_numeric($year) || $year < 2000 || $year > now()->year) {
            abort(400, 'Tahun tidak valid');
        }
    
        // Mengambil data berdasarkan bulan dan tahun
        $outcomingInventories = OutcomingInventory::whereMonth('issued_date', $month)
            ->whereYear('issued_date', $year)
            ->get();
    
        // Jika tidak ada data, tampilkan pesan error
        if ($outcomingInventories->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk bulan dan tahun yang dipilih.');
        }
    
        // Menyiapkan PDF
        $pdf = Pdf::loadView('penjualan.pdfMonthSales', compact('outcomingInventories', 'month', 'year'));
    
        // Mengunduh PDF
        return $pdf->download('SalesData_' . $month . '_' . $year . '.pdf');
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
        $outcomingInventories = OutcomingInventory::whereMonth('issued_date', $month)
            ->whereYear('issued_date', $year)
            ->get();
    
        // Jika tidak ada data, tampilkan pesan error
        if ($outcomingInventories->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk bulan dan tahun yang dipilih.');
        }
    
        // Menyiapkan PDF
        $pdf = Pdf::loadView('inventory.pdfMonthOutcoming', compact('outcomingInventories', 'month', 'year'));
    
        // Mengunduh PDF
        return $pdf->download('OutcomingData_' . $month . '_' . $year . '.pdf');
    }

    
}
