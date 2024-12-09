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
          <h1>OUTGOING ITEM DATA</h1>
      </div>

      <div>
          <button type="button" class="btn btn-success mt-2 mb-2"
              style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
              data-bs-toggle="modal" data-bs-target="#exampleModal">
              Tambah Data 
          </button>       
      </div>

      <!-- Modal Form -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Tambah Data Outgoing Item</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('outgoing.store') }}" method="POST">
                      @csrf
                      <div class="modal-body">
                          <div class="mb-3">
                              <label for="inventory_id" class="form-label">Pilih Barang</label>
                              <select class="form-control" id="inventory_id" name="inventory_id">
                                  <option value="">Pilih Barang</option>
                                  @foreach($inventoriesWithStock as $inventory)
                                      @if($inventory->total_stock > 0)
                                          <option value="{{ $inventory->id }}" data-stock="{{ $inventory->total_stock }}">
                                              {{ $inventory->name }} (Stok: {{ $inventory->total_stock }})
                                          </option>
                                      @endif
                                  @endforeach
                              </select>
                          </div>
                          <div class="mb-3">
                              <label for="quantity" class="form-label">Quantity</label>
                              <input type="number" class="form-control" id="quantity" name="quantity" required>
                              <div id="quantity-error" class="text-danger" style="display: none;">Jumlah keluar tidak boleh melebihi stok tersedia.</div>
                          </div>
                          <div class="mb-3">
                              <label for="receiver" class="form-label">Tujuan</label>
                              <input type="text" class="form-control" id="receiver" name="receiver" required>
                          </div>
                          <div class="mb-3">
                              <label for="issued_date" class="form-label">Tanggal Keluar</label>
                              <input type="date" class="form-control" id="issued_date" name="issued_date" required>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success" id="submit-btn">Simpan</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>

      <!-- Table for Outgoing Data -->
      <div>
          @if($outgoingInventories->isEmpty())
              <p>No outgoing data available.</p>
          @else
              <table class="table table-striped table-hover">
                  <thead>
                      <tr>
                          <th scope="col">ID</th>
                          <th scope="col">Nama Barang</th>
                          <th scope="col">Quantity</th>
                          <th scope="col">Tujuan</th>
                          <th scope="col">Tanggal Keluar</th>
                          <th scope="col">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($outgoingInventories as $outgoing)
                          <tr>
                              <td>{{ $outgoing->id }}</td>
                              <td>{{ $outgoing->inventory->name ?? 'Tidak ada' }}</td>
                              <td>{{ $outgoing->quantity }}</td>
                              <td>{{ $outgoing->receiver }}</td>
                              <td>{{ \Carbon\Carbon::parse($outgoing->issued_date)->format('d F Y') }}</td>
                              <td>
                                  <a href="#" class="btn btn-primary">Edit</a>
                                  
                                  <!-- Button untuk Trigger Modal -->
                                  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $outgoing->id }}">
                                    Delete
                                  </button>

                                  <!-- Button Print -->
                                  <button type="button" class="btn btn-info" onclick="printOutgoingData({{ $outgoing->id }})">Print</button>
                              </td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>
          @endif
      </div>

  </div>

  <!-- Modal Confirm Delete -->
  @foreach($outgoingInventories as $outgoing)
    <div class="modal fade" id="deleteModal{{ $outgoing->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $outgoing->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel{{ $outgoing->id }}">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="{{ route('outgoing.destroy', $outgoing->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
  @endforeach

  <!-- JavaScript Print Function -->
  <script>
    function printOutgoingData(id) {
        // Membuat URL untuk halaman print
        var printUrl = "{{ route('outgoing.print', ':id') }}";
        printUrl = printUrl.replace(':id', id);

        // Membuka jendela baru untuk mencetak data
        var printWindow = window.open(printUrl, '_blank');
        printWindow.print();
    }
  </script>

</x-app-layout>
