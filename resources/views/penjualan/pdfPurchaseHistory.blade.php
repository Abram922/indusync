<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan History Sales</title>
  <style>
    /* Styling yang telah disesuaikan sebelumnya */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
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
    <h3>LAPORAN RIWAYAT PURCHASE</h3>
    <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }} &emsp;&emsp; <strong>Halaman:</strong> 1</p>
    <p><strong>Dicetak Oleh:</strong> Gudang</p>

    <table id="reportTable">
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
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{ $purchase->inventory->id ?? 'Tidak ada' }}</td>
          <td>{{ $purchase->inventory->name ?? 'Tidak ada' }}</td>
          <td>{{ $purchase->supllier }}</td>
          <td>{{ $purchase->quantity }}</td>
          <td>{{ $purchase->tanggalPembelian }}</td>
          <td>{{ $purchase->tanggalPengiriman }}</td>
          <td>{{ $purchase->status->status ?? 'Tidak ada' }}</td>
          <td>{{ $purchase->resi }}</td>
        </tr>
      </tbody>
    </table>
  </div>

</body>
</html>
