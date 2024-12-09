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
    <h3>LAPORAN PENGELUARAN BARANG</h3>
    <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }} &emsp;&emsp; <strong>Halaman:</strong> 1</p>
    <p><strong>Dicetak Oleh:</strong> Gudang</p>

    <table>
        <thead>
            <tr>
                <th>Kode Supplier</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Tanggal Keluar</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $outgoingInventory->id }}</td>
                <td>{{ $outgoingInventory->inventory->name }}</td>
                <td>{{ $outgoingInventory->quantity }}</td>
                <td>{{ $outgoingInventory->receiver }}</td>
                <td>{{ \Carbon\Carbon::parse($outgoingInventory->issued_date)->format('d F Y') }}</td>
            </tr>
            <!-- Tambahkan baris lainnya di sini -->
        </tbody>
    </table>
  </div>

  <button onclick="downloadPDF()">Print PDF</button>

  <script>
    function downloadPDF() {
      const { jsPDF } = window.jspdf;
      
      // Membuat instance jsPDF dengan ukuran halaman A4 (210mm x 297mm)
      const doc = new jsPDF({
        orientation: 'portrait',
        unit: 'mm',
        format: 'a4'
      });
      
      // Menambahkan kop surat ke PDF
      doc.setFontSize(18);
      doc.text("CV. GANESH CUSTOM SHOP", 105, 20, null, null, 'center');
      doc.setFontSize(14);
      doc.text("Clothing Shop", 105, 30, null, null, 'center');
      doc.setFontSize(12);
      doc.text("Jalan Ngurah Rai no 48 Kediri Tabanan Bali\nBali - Indonesia", 105, 40, null, null, 'center');
      doc.text("Telp. (0361)-8759357", 105, 50, null, null, 'center');
      doc.line(10, 55, 200, 55);  // Menambahkan garis horizontal
      
      // Menambahkan judul laporan
      doc.setFontSize(16);
      doc.text("LAPORAN PENERIMAAN BARANG", 105, 60, null, null, 'center');
      
      // Menambahkan tanggal dan halaman
      doc.setFontSize(12);
      doc.text(`Tanggal Cetak: ${new Date().toLocaleDateString()}`, 10, 70);
      doc.text(`Halaman: 1`, 190, 70, null, null, 'right');
      
      // Menambahkan tabel ke PDF
      const table = document.getElementById('reportTable');
      const rows = table.querySelectorAll('tr');
      
      let y = 80; // Mulai menulis konten dari posisi 80mm dari atas
      
      // Menambahkan header tabel
      let x = 10;
      rows[0].querySelectorAll('th').forEach((th, index) => {
        doc.setFontSize(10);
        doc.text(th.textContent, x, y);
        x += 50;  // Jarak antar kolom
      });
      
      y += 10;  // Jarak antar baris
      
      // Menambahkan data tabel
      rows.forEach((row, rowIndex) => {
        if (rowIndex > 0) {  // Skip header row
          const cells = row.querySelectorAll('td');
          x = 10; // Reset x untuk setiap baris
          cells.forEach((td, index) => {
            doc.text(td.textContent, x, y);
            x += 50;  // Jarak antar kolom
          });
          y += 10; // Jarak antar baris
        }
      });
      
      // Menyimpan dan mendownload PDF
      doc.save('laporan_penerimaan_barang.pdf');
    }
  </script>
  
</body>
</html>
