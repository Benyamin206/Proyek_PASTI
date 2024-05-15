@extends('layouts.pasBar')

@section('content')

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(isset($error))
<div class="alert alert-danger">
    {{$error}}
</div>
@endif

@if(isset($jadwals))
<div class="row">
    @foreach($jadwals as $j)
    <div class="col-sm-6 mb-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Keberangkatan : {{$j['waktu_berangkat']}}</h5>
          <h6 class="card-title">Rute : {{$j['rute_id']}}</h6>
          <p class="card-text">Kapal : {{$j['kapal_id']}}</p>
          <p class="card-text">Nahkoda : {{$j['nahkoda_id']}}</p>
          <p class="card-text">Sisa stok : {{$j['stok']}}</p>
          <form action="{{ route('order.store') }}" method="POST">
            @csrf
            <input required type="number" name="qty" min="1" placeholder="mau pesan berapa tiket?">
            <input type="hidden" name="user_id" value="{{ session('user_id') }}">
            <input type="hidden" name="jadwal_id" value="{{ $j['id'] }}">
            <button type="submit" class="btn btn-primary">Pesan</button>
          </form>
        </div>
      </div>
    </div>
    @endforeach
</div>
@endif

@endsection
