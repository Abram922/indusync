<?php

namespace App\Http\Controllers;

use App\Models\OutcomingInventory;
use App\Models\Inventory;
use App\Models\IncomingInventory;

use Illuminate\Http\Request;

class OutcomingInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data dari tabel inventories
        $inventories = Inventory::all();

        // Mengambil semua data dari tabel incoming_inventories
        $incomingInventories = IncomingInventory::with('inventory')->get();

        // Mengambil semua data dari tabel outgoing_inventories
        $outgoingInventories = OutcomingInventory::with('inventory')->get();

        // Mengirim data ke view
        return view('inventory.outgoingItemData', compact('inventories', 'incomingInventories', 'outgoingInventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OutcomingInventory $outcomingInventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OutcomingInventory $outcomingInventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OutcomingInventory $outcomingInventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutcomingInventory $outcomingInventory)
    {
        //
    }
}
