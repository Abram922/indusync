<x-app-layout>
    <div class="py-8 md:py-12 lg:py-16">
        <h2 class="text-xl font-semibold mb-6">Grafik Akumulasi Harga * Quantity per Produk per Bulan</h2>
        
        <!-- Tombol Cetak PDF -->
        <button id="btnPrint" class="btn btn-info px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">
            Print
        </button>

        <!-- Grafik -->
        <div id="inventoryChart" style="width:100%; height:400px; margin-top: 20px;"></div>
    
        <!-- Tabel Horizontal -->
        <div class="mt-8 overflow-x-auto">
            <table class="min-w-max table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        @foreach($months as $month)
                            <th class="px-4 py-2 border text-center">{{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($totalValues as $total)
                            <td class="px-4 py-2 border text-right">{{ number_format($total, 2) }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    
        <!-- Script -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jspdf@2.4.0/dist/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Membuat grafik menggunakan Highcharts
                Highcharts.chart('inventoryChart', {
                    title: {
                        text: 'Grafik Akumulasi Harga * Quantity per Produk per Bulan'
                    },
                    xAxis: {
                        categories: @json($months),
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
                        color: '#007BFF'
                    }]
                });

                // Fungsi Cetak PDF
                document.getElementById('btnPrint').addEventListener('click', async function() {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF();

                    // Tambahkan judul ke PDF
                    doc.setFontSize(16);
                    doc.text('Grafik Akumulasi Harga * Quantity per Produk per Bulan', 10, 10);

                    // Ambil elemen grafik sebagai gambar
                    const chartElement = document.getElementById('inventoryChart');
                    const canvas = await html2canvas(chartElement);
                    const imgData = canvas.toDataURL('image/png');

                    // Tambahkan gambar grafik ke PDF
                    doc.addImage(imgData, 'PNG', 10, 20, 180, 100);

                    // Tambahkan tabel ke PDF
                    const tableColumns = @json($months).map(month => ({ header: month, dataKey: month }));
                    const tableRows = [@json($totalValues)];

                    const formattedRows = tableRows.map(row => {
                        return tableColumns.reduce((acc, col, idx) => {
                            acc[col.dataKey] = row[idx].toFixed(2);
                            return acc;
                        }, {});
                    });

                    doc.autoTable({
                        columns: tableColumns,
                        body: formattedRows,
                        startY: 130,
                        theme: 'grid'
                    });

                    doc.save('grafik-akumulasi-harga-quantity.pdf');
                });
            });
        </script>
    </div>
</x-app-layout>
