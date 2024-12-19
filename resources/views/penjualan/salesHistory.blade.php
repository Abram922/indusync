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
            <h1>Sales History</h1>
        </div>
  
        <!-- Tombol Print -->
        <button type="button" class="btn btn-success mt-2 mb-2" 
        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem; margin-right: 1rem;"
        data-bs-toggle="modal" data-bs-target="#printModal">
            Print
        </button>
  
        <!-- Modal Dialog -->
        <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="printModalLabel">Pilih Bulan dan Tahun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- Form di Modal -->
                    <form id="printForm" method="GET">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="month" class="form-label">Bulan</label>
                                <select class="form-select" id="month" name="month" required>
                                    <option value="" disabled selected>Pilih Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="year" class="form-label">Tahun</label>
                                <select class="form-select" id="year" name="year" required>
                                    <option value="" disabled selected>Pilih Tahun</option>
                                    @for ($year = now()->year; $year >= (now()->year - 5); $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" id="submitPrint">Print</button>
                        </div>
                    </form>
  
                    <script>
                        document.getElementById('submitPrint').addEventListener('click', function () {
                            const month = document.getElementById('month').value;
                            const year = document.getElementById('year').value;
                
                            if (month && year) {
                                // Redirect to route with parameters
                                const url = `{{ route('sales.printByMonth', ['month' => ':month', 'year' => ':year']) }}`
                                    .replace(':month', month)
                                    .replace(':year', year);
                
                                window.location.href = url;
                            } else {
                                alert('Harap pilih bulan dan tahun terlebih dahulu.');
                            }
                        });
                    </script>
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
                        <th scope="col">Harga</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Keterangan</th>
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
                            <td>{{ $outgoing->harga }}</td>
                            <td>{{ $outgoing->harga * $outgoing->quantity }}</td>
                            <td>{{ $outgoing->keterangan }}</td>
                            <td>{{ \Carbon\Carbon::parse($outgoing->issued_date)->format('d F Y') }}</td>
                            <td>
                                <a href="{{ route('inputSales.print', $outgoing->id) }}" class="btn btn-info">
                                    Print
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        
            <!-- Pagination -->
            <div class="mt-4">
                {{ $outgoingInventories->links() }}
            </div>
        @endif
        
        </div>
    </div>
  </x-app-layout>
  