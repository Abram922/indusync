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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('inventory.store') }}" method="POST">
              @csrf
              <!-- Form fields -->
              <!-- Add your form fields here -->
            </form>
          </div>
        </div>
      </div>
    </div>

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
