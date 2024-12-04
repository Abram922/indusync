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
  
      <!-- OUTGOING ITEM DATA -->
      <div>
      </div>
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
                <td>{{ $outgoing->inventory->namabarang ?? 'Tidak ada' }}</td>
                <td>{{ $outgoing->quantity }}</td>
                <td>{{ $outgoing->receiver }}</td>
                <td>{{ \Carbon\Carbon::parse($outgoing->issued_date)->format('d F Y') }}</td>
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
  </x-app-layout>
  