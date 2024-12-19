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
                            <div id="jika barang baru " style="display: none;">
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
                            <button type="submit" class="btn btn-primary" id="submitBtn" >Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div>
            @if($purchases->isEmpty())
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
                            <th scope="col">Tanggal Pengiriman</th>
                            <th scope="col">Status</th>
                            <th scope="col">No.Resi</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->id }}</td>
                                <td>{{ $purchase->inventory->name ?? 'Tidak ada' }}</td>
                                <td>{{ $purchase->supllier }}</td>
                                <td>{{ $purchase->quantity }}</td>
                                <td>{{ $purchase->tanggalPembelian }}</td>
                                <td>{{ $purchase->tanggalPengiriman }}</td>
                                <td>{{ $purchase->status->status }}</td>
                                <td>{{ $purchase->resi }}</td>
                                <td>
                                    @if($purchase->status_id == 1)
                                        <!-- Tombol untuk memicu modal -->
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $purchase->id }}">
                                            Edit
                                        </button>
                                        
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $purchase->id }}">
                                            Update Status
                                        </button>
                                      
                                        <!-- Modal Konfirmasi Update Status -->
                                        <div class="modal fade" id="updateStatusModal{{ $purchase->id }}" tabindex="-1" aria-labelledby="updateStatusModalLabel{{ $purchase->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="updateStatusModalLabel{{ $purchase->id }}">Konfirmasi Update Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin memperbarui status untuk item ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('purchase.updateStatus', $purchase->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">Ya, Update</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Tombol untuk memicu modal Hapus -->
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $purchase->id }}">
                                        Hapus
                                    </button>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal{{ $purchase->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $purchase->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $purchase->id }}">Edit Data Barang</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form Edit -->
                                                    <form action="{{ route('purchase.update', $purchase->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- Input Nama Barang -->
                                                        <div class="mb-3">
                                                            <label for="namabarang{{ $purchase->id }}" class="form-label">Nama Barang</label>
                                                            <select class="form-control" id="inventory_id_edit_{{ $purchase->id }}" name="inventory_id" onchange="toggleNewItemFieldsEdit(this.value, '{{ $purchase->id }}')">
                                                                <option value="">Pilih Barang</option>
                                                                <option value="0">Barang Baru</option>
                                                                @foreach($inventories as $inventory)
                                                                    <option value="{{ $inventory->id }}" {{ $purchase->inventory_id == $inventory->id ? 'selected' : '' }}>{{ $inventory->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Field Barang Baru -->
                                                        <div id="newItemFieldsEdit{{ $purchase->id }}" style="display: none;">
                                                            <div class="mb-3">
                                                                <label for="namabarang_edit_{{ $purchase->id }}" class="form-label">Nama Barang</label>
                                                                <input type="text" class="form-control" id="namabarang_edit_{{ $purchase->id }}" name="namabarang">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="description_edit_{{ $purchase->id }}" class="form-label">Deskripsi</label>
                                                                <textarea class="form-control" id="description_edit_{{ $purchase->id }}" name="description"></textarea>
                                                            </div>
                                                        </div>

                                                        <!-- Input Quantity, Harga, dan lainnya -->
                                                        <div class="mb-3">
                                                            <label for="quantity_edit_{{ $purchase->id }}" class="form-label">Quantity</label>
                                                            <input type="number" class="form-control" id="quantity_edit_{{ $purchase->id }}" name="quantity" value="{{ $purchase->quantity }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="harga_edit_{{ $purchase->id }}" class="form-label">Harga</label>
                                                            <input type="number" class="form-control" id="harga_edit_{{ $purchase->id }}" name="harga" value="{{ $purchase->harga }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tanggalPembelian_edit_{{ $purchase->id }}" class="form-label">Tanggal Pembelian</label>
                                                            <input type="date" class="form-control" id="tanggalPembelian_edit_{{ $purchase->id }}" name="tanggalPembelian" value="{{ $purchase->tanggalPembelian }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tanggalPengiriman_edit_{{ $purchase->id }}" class="form-label">Tanggal Pengiriman</label>
                                                            <input type="date" class="form-control" id="tanggalPengiriman_edit_{{ $purchase->id }}" name="tanggalPengiriman" value="{{ $purchase->tanggalPengiriman }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="supllier_edit_{{ $purchase->id }}" class="form-label">Supplier</label>
                                                            <input type="text" class="form-control" id="supllier_edit_{{ $purchase->id }}" name="supllier" value="{{ $purchase->supllier }}">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="resi_edit_{{ $purchase->id }}" class="form-label">No.Resi</label>
                                                            <input type="text" class="form-control" id="resi_edit_{{ $purchase->id }}" name="resi" value="{{ $purchase->resi }}">
                                                        </div>

                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $purchase->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $purchase->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $purchase->id }}">Konfirmasi Hapus Data</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus item ini?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST">
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

            <!-- Pagination -->
            {{ $purchases->links() }}
        </div>
    </div>
</x-app-layout>
