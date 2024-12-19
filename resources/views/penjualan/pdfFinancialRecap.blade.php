<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>

    <div class="container mt-5">
      <h1 class="text-center mb-4">Grafik Akumulasi Harga * Quantity</h1>


      <!-- Tampilkan grafik -->
      <div id="inventoryChart" class="mb-5" style="width:100%; height:400px;"></div>

      <!-- Tabel Horizontal -->
      <div class="mt-4 overflow-x-auto">
        <table class="table table-bordered">
          <thead>
            <tr class="table-light">
              <th class="text-center">Bulan</th>
              @foreach($months as $month)
                <th class="text-center">{{ $month }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            <tr>
              @foreach($totalValues as $total)
                <td class="text-center">{{ number_format($total, 2) }}</td>
              @endforeach
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.4.0/dist/jspdf.umd.min.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah data sudah dikirim dengan benar
        console.log('Total Values:', @json($totalValues));
        console.log('Months:', @json($months));

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
      });
    </script>

  </body>
</html>
