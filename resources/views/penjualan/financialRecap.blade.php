<x-app-layout>
    <div class="py-8 md:py-12 lg:py-16">
        <h2>Grafik Akumulasi Harga * Quantity per Produk per Bulan</h2>
        <div id="inventoryChart" style="width:100%; height:400px;"></div>
    
        <!-- Tabel Horizontal -->
        <div class="mt-8 overflow-x-auto">
            <table class="min-w-max table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        @foreach($months as $month)
                            <th class="px-4 py-2 border">{{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($totalValues as $total)
                            <td class="px-4 py-2 border">{{ number_format($total, 2) }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    
        <script src="https://code.highcharts.com/highcharts.js"></script>
    
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Cek apakah data sudah dikirim dengan benar
                console.log('Total Values:', @json($totalValues));
    
                // Membuat grafik menggunakan Highcharts
                Highcharts.chart('inventoryChart', {
                    title: {
                        text: 'Grafik Akumulasi Harga * Quantity per Produk per Bulan'
                    },
                    xAxis: {
                        categories: @json($months),  // Pastikan bulan dikirim dengan benar
                        title: {
                            text: 'Bulan'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Total Harga * Quantity'
                        }
                    },
                    series: [{
                        name: 'Total Akumulasi',
                        data: @json($totalValues),
                        color: '#007BFF'  // Warna biru untuk visualisasi
                    }]
                });
            });
        </script>
    </div>
</x-app-layout>
