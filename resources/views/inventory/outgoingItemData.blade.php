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

                                <a href="{{ route('outgoing.print', $outgoing->id) }}" class="btn btn-info">
                                    Print
                                </a>

                                </td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>
          @endif
      </div>

  </div>

  


</x-app-layout>
