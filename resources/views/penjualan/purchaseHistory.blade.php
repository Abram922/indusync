<x-app-layout>
    <div class="py-12">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div>
            <h1>Purchase History</h1>
        </div>

        <div>
            @if($purchase->isEmpty())
                <p>No data available.</p> <!-- Jika tidak ada data -->
            @else
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Kode Barang</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Supplier</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Tanggal Pembelian</th>
                            <th scope="col">Tanggal Penjualan</th>
                            <th scope="col">Status</th>
                            <th scope="col">No.Resi</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase as $purchases)
                            <tr>
                                <td>{{ $purchases->id }}</td>
                                <td>{{ $purchases->inventory->name ?? 'Tidak ada' }}</td>
                                <td>{{ $purchases->supllier }}</td>
                                <td>{{ $purchases->quantity }}</td>
                                <td>{{ $purchases->tanggalPembelian }}</td>
                                <td>{{ $purchases->tanggalPengiriman }}</td>
                                <td>{{ $purchases->status->status }}</td>
                                <td>{{ $purchases->resi }}</td>
                                <td>
                                    <a href="{{ route('penjualan.print', $purchases->id) }}" class="btn btn-info">
                                        Print
                                    </a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
