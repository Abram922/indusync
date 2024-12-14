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
      <h1>INCOMING ITEM DATA</h1>
    </div>

    <div>
      <button type="button" class="btn btn-success mt-2 mb-2"
        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
        data-bs-toggle="modal" data-bs-target="#exampleModal">
        Tambah Data 
      </button>       
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('inventory.store') }}" method="POST" id="inventoryForm">
              @csrf

              <!-- Dropdown untuk memilih barang yang sudah ada -->
              <div class="mb-3">
                <label for="inventory_id" class="form-label">Pilih Barang</label>
                <select class="form-control" id="inventory_id" name="inventory_id" onchange="toggleNewItemFields(this.value)">
                  <option value="">Pilih Barang</option>
                  <option value="0">Barang Baru</option> <!-- Opsi untuk barang baru -->
                  @foreach($inventories as $inventory)
                    <option value="{{ $inventory->id }}">{{ $inventory->name }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Input untuk data barang baru, ditampilkan jika opsi "Barang Baru" dipilih -->
              <div id="newItemFields" style="display: none;">
                <div class="mb-3">
                  <label for="namabarang" class="form-label">Nama Barang</label>
                  <input type="text" class="form-control" id="namabarang" name="namabarang" required>
                </div>
                <div class="mb-3">
                  <label for="description" class="form-label">Deskripsi</label>
                  <textarea class="form-control" id="description" name="description"></textarea>
                </div>
              </div>

              <!-- Input untuk quantity, supplier, tanggal masuk, dan keterangan -->
              <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
              </div>

              <div class="mb-3">
                <label for="supplier" class="form-label">Supplier</label>
                <input type="text" class="form-control" id="supplier" name="supplier" required>
              </div>

              <div class="mb-3">
                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
              </div>

              <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      function toggleNewItemFields(value) {
        const newItemFields = document.getElementById('newItemFields');
        const namabarang = document.getElementById('namabarang');
        
        if (value === '0') {
            newItemFields.style.display = 'block'; // Menampilkan input baru
            namabarang.required = true; // Nama barang harus diisi
        } else {
            newItemFields.style.display = 'none'; // Menyembunyikan input baru
            namabarang.required = false; // Nama barang tidak wajib diisi
        }
        validateForm(); // Memanggil fungsi validasi saat dropdown berubah
      }

      // Fungsi untuk mengaktifkan tombol simpan berdasarkan input
      function validateForm() {
        const submitButton = document.getElementById('submitBtn');
        const inventoryId = document.getElementById('inventory_id').value;
        const namabarang = document.getElementById('namabarang').value;

        if ((inventoryId !== '0' && inventoryId !== '') || (inventoryId === '0' && namabarang.trim() !== '')) {
          submitButton.disabled = false; // Aktifkan tombol simpan jika data valid
        } else {
          submitButton.disabled = true; // Nonaktifkan tombol simpan jika data tidak valid
        }
      }

      // Event listener untuk form pada modal
      document.getElementById('inventoryForm').addEventListener('input', validateForm);
    </script>

    <div>
      @if($incomingInventories->isEmpty())
        <p>No data available.</p> <!-- Jika tidak ada data -->
      @else
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Nama Barang</th>
              <th scope="col">Quantity</th>
              <th scope="col">Sumber</th>
              <th scope="col">Tanggal Masuk</th>
              <th scope="col">Keterangan</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($incomingInventories as $incoming)
              <tr>
                <td>{{ $incoming->id }}</td>
                <td>{{ $incoming->inventory->name ?? 'Tidak ada' }}</td>
                <td>{{ $incoming->quantity }}</td>
                <td>{{ $incoming->supplier }}</td>
                <td>{{ \Carbon\Carbon::parse($incoming->received_at)->format('d F Y') }}</td>
                <td>{{ $incoming->inventory->description }}</td>
                <td>
                  <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $incoming->id }}">Edit</a>

                  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteUrl('{{ route('inventory.destroy', $incoming->id) }}')">
                    Hapus
                  </button>

                  <!-- Modal Konfirmasi Hapus -->
                  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <p>Apakah Anda yakin ingin menghapus item ini?</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <!-- Form untuk hapus data -->
                          <form id="deleteForm" action="" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <a href="{{ route('inventory.print', $incoming->id) }}" class="btn btn-info">
                    Print 
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif

      <!-- Modal Edit -->
      @foreach($incomingInventories as $incoming)
        <div class="modal fade" id="editModal{{ $incoming->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $incoming->id }}" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $incoming->id }}">Edit Incoming Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ route('incominginventory.update', $incoming->id) }}" method="POST" id="inventoryForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                  <!-- Dropdown untuk memilih barang yang sudah ada -->
                  <div class="mb-3">
                    <label for="inventory_id_{{ $incoming->id }}" class="form-label">Pilih Barang</label>
                    <select class="form-control" id="inventory_id_{{ $incoming->id }}" name="inventory_id" onchange="toggleNewItemFields({{ $incoming->id }}, this.value)">
                      <option value="">Pilih Barang</option>
                      @foreach($inventories as $inventory)
                        <option value="{{ $inventory->id }}" {{ $incoming->inventory_id == $inventory->id ? 'selected' : '' }}>
                          {{ $inventory->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <!-- Input untuk quantity, supplier, tanggal masuk, dan keterangan -->
                  <div class="mb-3">
                    <label for="quantity_{{ $incoming->id }}" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity_{{ $incoming->id }}" name="quantity" value="{{ $incoming->quantity }}" required>
                  </div>

                  <div class="mb-3">
                    <label for="supplier_{{ $incoming->id }}" class="form-label">Supplier</label>
                    <input type="text" class="form-control" id="supplier_{{ $incoming->id }}" name="supplier" value="{{ $incoming->supplier }}" required>
                  </div>

                  <div class="mb-3">
                    <label for="received_date_{{ $incoming->id }}" class="form-label">Tanggal Masuk</label>
                    <input type="date" class="form-control" id="received_date_{{ $incoming->id }}" name="received_date" value="{{ $incoming->received_date }}" required>
                  </div>

                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="submitBtn_{{ $incoming->id }}" >Simpan</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  
  <script>
    function toggleNewItemFields(id, value) {
  const newItemFields = document.getElementById('newItemFields_' + id);
  const namabarang = document.getElementById('namabarang_' + id);
  
  if (value === '0') {
    newItemFields.style.display = 'block'; // Menampilkan input baru
    namabarang.required = true; // Nama barang harus diisi
  } else {
    newItemFields.style.display = 'none'; // Menyembunyikan input baru
    namabarang.required = false; // Nama barang tidak wajib diisi
  }
  
  validateForm(id); // Memanggil fungsi validasi saat dropdown berubah
}

function validateForm(id) {
  const submitButton = document.getElementById('submitBtn_' + id); // Dynamic id
  const inventoryId = document.getElementById('inventory_id_' + id).value;
  const namabarang = document.getElementById('namabarang_' + id).value.trim();
  const quantity = document.getElementById('quantity_' + id).value;  
  const supplier = document.getElementById('supplier_' + id).value.trim();
  const tanggalMasuk = document.getElementById('tanggal_masuk_' + id).value;

  if ((inventoryId !== '0' && inventoryId !== '') || (inventoryId === '0' && namabarang !== '') && quantity && supplier && tanggalMasuk) {
    submitButton.disabled = false; // Aktifkan tombol simpan jika data valid
  } else {
    submitButton.disabled = true; // Nonaktifkan tombol simpan jika data tidak valid
  }
}

  </script>
</x-app-layout>
