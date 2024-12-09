<x-app-layout>
    <div>
        <h1>STOCK DATA</h1>
    </div>

    <div class="py-8 md:py-12 lg:py-16">
        <!-- Pemberitahuan jika stok barang menipis -->
        @if($stockData->contains(function($data) { return $data->total_quantity < 5; }))
            <div class="alert alert-warning d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 me-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                </svg>
                <span>TERDAPAT STOK BARANG YANG MENIPIS</span>
            </div>
        @endif

        <div>
            @if($stockData->isEmpty())
                <p>No data available.</p> <!-- Jika tidak ada data -->
            @else
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Kode Barang</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Tanggal Masuk</th>
                            <th scope="col">Tanggal Input</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockData as $dataStocks)
                            <tr class="{{ $dataStocks->total_quantity == 0 ? 'table-danger' : ($dataStocks->total_quantity < 5 ? 'table-warning' : '') }}">
                                <td>{{ $dataStocks->id }}</td>
                                <td>{{ $dataStocks->name ?? 'Nama tidak ditemukan' }}</td>
                                <td>{{ $dataStocks->total_quantity }}</td>
                                <td>{{ \Carbon\Carbon::parse($dataStocks->tanggal_masuk)->format('d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($dataStocks->tanggal_input)->format('d F Y') }}</td>
                                <td>{{ $dataStocks->jenis }}</td>
                                <td>{{ $dataStocks->description ?? 'description tidak ditemukan' }}</td>  
                                <td>
                                    @if($dataStocks->total_quantity == 0)
                                        <span class="text-danger d-inline-flex align-items-center">
                                            Tidak Tersedia
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 ms-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                            </svg>
                                        </span>
                                    @elseif($dataStocks->total_quantity < 5)
                                        <span class="text-warning">Tersedia <i class="bi bi-exclamation-circle"></i></span>
                                    @else
                                        <span class="text-success">Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="py-8 md:py-12 lg:py-16">
        <h2>Grafik Incoming dan Outcoming per Bulan</h2>
        <div id="inventoryChart" style="width:100%; height:400px;"></div>
    
        <!-- Tabel Horizontal -->
        <div class="mt-8 overflow-x-auto">
            <table class="min-w-max table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">Kategori</th>
                        @foreach($months as $month)
                            <th class="px-4 py-2 border">{{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border">Incoming Quantity</td>
                        @foreach($incomingQuantities as $quantity)
                            <td class="px-4 py-2 border">{{ $quantity }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border">Outcoming Quantity</td>
                        @foreach($outcomingQuantities as $quantity)
                            <td class="px-4 py-2 border">{{ $quantity }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    
        <script src="https://code.highcharts.com/highcharts.js"></script>
    
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Cek apakah data sudah dikirim dengan benar
                console.log('Incoming Quantities:', @json($incomingQuantities));
                console.log('Outcoming Quantities:', @json($outcomingQuantities));
    
                // Membuat grafik menggunakan Highcharts
                Highcharts.chart('inventoryChart', {
                    title: {
                        text: 'Grafik Incoming dan Outcoming per Bulan'
                    },
                    xAxis: {
                        categories: @json($months),  // Pastikan bulan dikirim dengan benar
                        title: {
                            text: 'Bulan'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Quantity'
                        }
                    },
                    series: [{
                        name: 'Incoming Inventory',
                        data: @json($incomingQuantities) 
                    }, {
                        name: 'Outcoming Inventory',
                        data: @json($outcomingQuantities),
                        color: '#FFA500' 
                    }]
                });
            });
        </script>
    </div>
    
    

</x-app-layout>
