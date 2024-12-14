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
            <h1>Sales History</h1>
        </div>



        <div>
            @if($sales->isEmpty())
                <p>No data available.</p> <!-- Jika tidak ada data -->
            @else
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Kode Barang</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Nama Customer</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Tanggal Penjualan</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $inputsales)
                            <tr>
                                <td>{{ $inputsales->id }}</td>
                                <td>{{ $inputsales->inventory->name ?? 'Tidak ada' }}</td>
                                <td>{{ $inputsales->namaCustomer }}</td>
                                <td>{{ $inputsales->quantity }}</td>
                                <td>{{ $inputsales->tanggalPenjualan }}</td>
                                <td>{{ $inputsales->harga }}</td>
                                <td>Rp.{{ $inputsales->quantity * $inputsales->harga }}</td>
                                <td>{{ $inputsales->inventory->description }}</td>
                                <td>
                                    <a href="{{ route('penjualan.print', $inputsales->id) }}" class="btn btn-info">
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
