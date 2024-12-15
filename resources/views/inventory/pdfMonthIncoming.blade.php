<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Penerimaan Barang</title>
  <style>
    /* Styling yang telah disesuaikan sebelumnya */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: white;
    }
    .header {
      text-align: center;
      padding: 20px;
    }
    .header h1 {
      margin: 0;
      font-size: 24px;
    }
    .header h2 {
      margin: 5px 0;
      font-size: 18px;
    }
    .header p {
      margin: 5px 0;
      font-size: 14px;
    }
    .content {
      margin: 20px;
      padding: 10px;
      background-color: white;
      border-radius: 8px;
    }
    .content h3 {
      font-size: 22px;
      margin-bottom: 20px;
    }
    .content table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .content table th, .content table td {
      border: 1px solid #000;
      padding: 8px;
      text-align: left;
      font-size: 14px;
    }
    .content table th {
      background-color: #f2f2f2;
    }
  </style>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>

  <div class="header">
    <h1>CV. GANESH CUSTOM SHOP</h1>
    <h2>Clothing Shop</h2>
    <p>Jalan Ngurah Rai no 48 Kediri Tabanan Bali<br>Bali - Indonesia</p>
    <p>Telp. (0361)-8759357</p>
    <hr>
  </div>

  <div class="content">
    <h3>LAPORAN PENERIMAAN BARANG</h3>
    <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }} &emsp;&emsp; <strong>Halaman:</strong> 1</p>
    <p><strong>Dicetak Oleh:</strong> Gudang</p>

    <table id="reportTable">
      <thead>
        <tr>
          <th>Kode Supplier</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th>Tanggal Masuk</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($incomingInventories as $inventory)
          <tr>
            <td>{{ $inventory->supplier }}</td>
            <td>{{ $inventory->inventory->id }}</td>
            <td>{{ $inventory->inventory->name }}</td>
            <td>{{ \Carbon\Carbon::parse($inventory->received_at)->format('d F Y') }}</td>
          </tr>
        @endforeach
      </tbody>
      
    </table>
  </div>

  
</body>
</html>
