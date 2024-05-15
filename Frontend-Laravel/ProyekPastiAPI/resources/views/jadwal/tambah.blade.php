@extends('layouts.adminBar')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">



@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <h2>Tambah Jadwal</h2><br><br>
    
    <form action="{{ route('jadwal.store') }}" method="POST">
        @csrf
        Kapal : 
        <select class = "form-control" name="kapal_id" id="" required>
            @if(isset($kapalError))
                <option disabled>Server sedang bermasalah</option>
            @else
            @foreach($kapals as $kapal)
                <option value="{{ $kapal['id'] }}" {{ isset($kapalError) ? 'disabled' : '' }}>
                    {{ $kapal['nama'] }}
                </option>
            @endforeach
            @endif
        </select>
        <br><br>
        Nahkoda : 
        <select class = "form-control" name="nahkoda_id" id="" required>

            @if(isset($nahkodaError))
                <option disabled>Server sedang bermasalah</option>
            @else
            @foreach($nahkodas as $nahkoda)
                <option value="{{ $nahkoda['id'] }}" {{ isset($nahkodaError) ? 'disabled' : '' }}>
                    {{ $nahkoda['nama'] }}
                </option>
            @endforeach
            @endif
        </select>
        <br><br>
        Rute : 
        <select class = "form-control" name="rute_id" id="" required>

            @if(isset($ruteError))
                <option disabled>Server sedang bermasalah</option>
                @else
                @foreach($rutes as $rute)
                <option value="{{ $rute['id'] }}" {{ isset($ruteError) ? 'disabled' : '' }}>
                    {{ $rute['lokasi_berangkat'] }} - {{ $rute['lokasi_tujuan'] }}
                </option>
            @endforeach
            @endif
        </select>
        <br><br>
        Waktu Berangkat : <input class="form-control" id="waktu_berangkat" type="text" class="" name="waktu_berangkat" required>
        <br><br>
        Stok: <input class="form-control" id="stok" type="number" name="stok" required>
        <br><br>
        Harga: <input class="form-control" id="harga" type="number" name="harga" required>
        <br><br>
        <button class="btn btn-primary" type="submit">Tambah jadwal</button>
    </form>
    
    
    


    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr('#waktu_berangkat', {
        enableTime: true,
        minDate: 'today', // Memastikan hanya waktu yang saat ini atau di masa depan yang dapat dipilih
        dateFormat: 'Y-m-d H:i', // Format tanggal dan waktu
        time_24hr: true, // Format waktu dalam 24 jam
    });
</script>

@endsection
