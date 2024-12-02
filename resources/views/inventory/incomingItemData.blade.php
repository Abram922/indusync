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

    <!-- Button to trigger modal -->
    <div>
      <button type="button" class="btn btn-success mt-2 mb-2"
        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
        data-bs-toggle="modal" data-bs-target="#exampleModal">
        Tambah Data 
      </button>       
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <!-- Modal Body -->
          <div class="modal-body">
            <!-- Form for adding data -->
            <form action="{{ route('inventory.store') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="itemName" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="itemName" name="namabarang" placeholder="Masukkan nama barang" required>
              </div>
              <div class="mb-3">
                <label for="itemQuantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="itemQuantity" name="quantity" placeholder="Masukkan quantity" required>
              </div>
              <div class="mb-3">
                <label for="itemDate" class="form-label">Tanggal Masuk</label>
                <input type="date" class="form-control" id="itemDate" name="tanggal_masuk" required>
              </div>
              <div class="mb-3">
                <label for="itemDescription" class="form-label">Keterangan</label>
                <textarea class="form-control" id="itemDescription" name="keterangan" rows="3" placeholder="Masukkan keterangan"></textarea>
              </div>
              <div class="mb-3">
                <label for="itemJenis" class="form-label">Jenis</label>
                <input type="text" class="form-control" id="itemJenis" name="jenis" placeholder="Masukkan jenis barang" required>
              </div>
              <!-- Modal Footer -->
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div>
      @if($inventories->isEmpty())
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
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($inventories as $inventory)
              <tr>
                <td>{{ $inventory->kode_barang }}</td>
                <td>{{ $inventory->namabarang }}</td>
                <td>{{ $inventory->quantity }}</td>
                <td>{{ \Carbon\Carbon::parse($inventory->tanggal_masuk)->format('d F Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($inventory->tanggal_input)->format('d F Y') }}</td>                          
                <td>{{ $inventory->jenis }}</td>
                <td>{{ $inventory->keterangan }}</td>
                <td>
                  <a href="#" class="btn btn-primary">Edit</a>
                  <a href="#" class="btn btn-danger">Delete</a>
                  <button type="button" class="btn btn-info" onclick="window.print()">Print</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>
</x-app-layout>
