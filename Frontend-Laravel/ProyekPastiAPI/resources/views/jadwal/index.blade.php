@extends('layouts.adminBar')


@section('content')

@if(isset($error))
<div class="alert alert-danger">
    {{$error}}
</div>
@endif


@if(isset($jadwals))
{{-- @foreach($kapals as $k)
    <h1>{{$k['deskripsi']}}</h1>
@endforeach --}}

<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Kapal</th>
        <th scope="col">Nahkoda</th>
        <th scope="col">Rute</th>
        <th scope="col">Waktu Berangkat</th>
        <th scope="col">Sisa Stok</th>
        <th scope="col">Harga per tiket</th>

      </tr>
    </thead>
    <tbody>
        @foreach($jadwals as $j)
      <tr>
        <th scope="row">{{$j['id']}}</th>
        <td>{{$j['kapal_id']}}</td>
        <td>{{$j['nahkoda_id']}}</td>
        <td>{{$j['rute_id']}}</td>
        <td>{{$j['waktu_berangkat']}}</td>
        <td>{{$j['stok']}}</td>
        <td>{{$j['harga']}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif

@endsection
