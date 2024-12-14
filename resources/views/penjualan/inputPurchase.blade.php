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
            <h1>Input Purchase</h1>
        </div>

        <div>
            <button type="button" class="btn btn-success mt-2 mb-2"
                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
                data-bs-toggle="modal" data-bs-target="#exampleModal">
                Tambah Data 
            </button>       
        </div>

<!-- Modal untuk Input Data -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('inputPurchase.store') }}" method="POST" id="inventoryForm">
                    @csrf

                    <!-- Dropdown untuk Pilih Barang -->
                    <div class="mb-3">
                        <label for="inventory_id" class="form-label">Pilih Barang</label>
                        <select class="form-control" id="inventory_id" name="inventory_id" onchange="toggleNewItemFields(this.value)">
                            <option value="">Pilih Barang</option>
                            <option value="0">Barang Baru</option>
                            @foreach($inventories as $inventory)
                                <option value="{{ $inventory->id }}">{{ $inventory->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input Barang Baru -->
                    <div id="newItemFields" style="display: none;">
                        <div class="mb-3">
                            <label for="namabarang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="namabarang" name="namabarang">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                    </div>

                    <!-- Input Lainnya -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggalPembelian" class="form-label">Tanggal Pembelian</label>
                        <input type="date" class="form-control" id="tanggalPembelian" name="tanggalPembelian" required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggalPengiriman" class="form-label">Tanggal Pengiriman</label>
                        <input type="date" class="form-control" id="tanggalPengiriman" name="tanggalPengiriman" required>
                    </div>

                    <div class="mb-3">
                        <label for="supllier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supllier" name="supllier">
                    </div>

                    <!-- Button Simpan -->
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle input untuk barang baru
    function toggleNewItemFields(value) {
        const newItemFields = document.getElementById('newItemFields');
        const namabarang = document.getElementById('namabarang');
        
        if (value === '0') {
            newItemFields.style.display = 'block';
            namabarang.required = true;
        } else {
            newItemFields.style.display = 'none';
            namabarang.required = false;
        }
    }

    // Validasi form untuk mengaktifkan tombol Simpan
    function validateForm() {
        const inventoryId = document.getElementById('inventory_id').value;
        const namabarang = document.getElementById('namabarang').value;
        const submitButton = document.getElementById('submitBtn');

        if ((inventoryId !== '' && inventoryId !== '0') || (inventoryId === '0' && namabarang !== '')) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    document.getElementById('inventoryForm').addEventListener('input', validateForm);
</script>


        <div>
            @if($purchase->isEmpty())
                <p>No data available.</p> <!-- Jika tidak ada data -->
            @else
                <table class="table table-striped table-hover">
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
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase as $purchases)
                            <tr>
                                <td>{{ $purchases->id }}</td>
                                <td>{{ $purchases->inventory->name ?? 'Tidak ada' }}</td>
                                <td>{{ $purchases->supllier }}</td>
                                <td>{{ $purchases->quantity }}</td>
                                <td>{{ $purchases->tanggalPembelian }}</td>
                                <td>{{ $purchases->tanggalPengiriman }}</td>
                                <td>{{ $purchases->status->status }}</td>
                                <td>{{ $purchases->resi }}</td>
                                <td>
                                    @if($purchases->status_id == 1)
                                        <!-- Tombol untuk memicu modal -->
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $purchases->id }}">
                                            Edit
                                        </button>
                                        
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $purchases->id }}">
                                            Update Status
                                        </button>
                                
                                        <!-- Modal Konfirmasi Update Status -->
                                        <div class="modal fade" id="updateStatusModal{{ $purchases->id }}" tabindex="-1" aria-labelledby="updateStatusModalLabel{{ $purchases->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="updateStatusModalLabel{{ $purchases->id }}">Konfirmasi Update Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin memperbarui status untuk item ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('purchase.updateStatus', $purchases->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">Ya, Update</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                        <!-- Tombol untuk memicu modal Hapus -->
    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $purchases->id }}">
        Hapus
    </button>

    <!-- Modal Edit -->
<div class="modal fade" id="editModal{{ $purchases->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $purchases->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $purchases->id }}">Edit Data Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Edit -->
                <form action="{{ route('purchase.update', $purchases->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Input Nama Barang -->
                    <div class="mb-3">
                        <label for="namabarang{{ $purchases->id }}" class="form-label">Nama Barang</label>
                        <select class="form-control" id="inventory_id_edit_{{ $purchases->id }}" name="inventory_id" onchange="toggleNewItemFieldsEdit(this.value, '{{ $purchases->id }}')">
                            <option value="">Pilih Barang</option>
                            @foreach($inventories as $inventory)
                                <option value="{{ $inventory->id }}" {{ $purchases->inventory_id == $inventory->id ? 'selected' : '' }}>
                                    {{ $inventory->name }}
                                </option>
                            @endforeach
                        </select>                    </div>

                    <!-- Input Supplier -->
                    <div class="mb-3">
                        <label for="supplier{{ $purchases->id }}" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier{{ $purchases->id }}" name="supllier" value="{{ $purchases->supllier }}" required>
                    </div>

                    <!-- Input Quantity -->
                    <div class="mb-3">
                        <label for="quantity{{ $purchases->id }}" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity{{ $purchases->id }}" name="quantity" value="{{ $purchases->quantity }}" required>
                    </div>

                    <!-- Input Tanggal Pembelian -->
                    <div class="mb-3">
                        <label for="tanggalPembelian{{ $purchases->id }}" class="form-label">Tanggal Pembelian</label>
                        <input type="date" class="form-control" id="tanggalPembelian{{ $purchases->id }}" name="tanggalPembelian" value="{{ $purchases->tanggalPembelian }}" required>
                    </div>

                    <!-- Input Tanggal Pengiriman -->
                    <div class="mb-3">
                        <label for="tanggalPengiriman{{ $purchases->id }}" class="form-label">Tanggal Pengiriman</label>
                        <input type="date" class="form-control" id="tanggalPengiriman{{ $purchases->id }}" name="tanggalPengiriman" value="{{ $purchases->tanggalPengiriman }}" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal{{ $purchases->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $purchases->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel{{ $purchases->id }}">Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus item ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <!-- Form Hapus -->
                    <form action="{{ route('purchase.destroy', $purchases->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script>
        // Fungsi untuk mengatur URL pada form hapus
        function setDeleteUrl(url) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = url; 
        }


    </script>

</x-app-layout>
