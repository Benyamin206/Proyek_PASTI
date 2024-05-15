@extends('layouts.adminBar')


@section('content')

@if(isset($error))
<div class="alert alert-danger">
    {{$error}}
</div>
@endif


@if(isset($rutes))
{{-- @foreach($kapals as $k)
    <h1>{{$k['deskripsi']}}</h1>
@endforeach --}}
@if(session('success.edit'))
<div class="alert alert-success">{{ session('success.edit') }}</div>
@endif

<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Lokasi Berangkat</th>
        <th scope="col">Lokasi Tujuan</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
        @foreach($rutes as $r)
      <tr>
        <th scope="row">{{$r['id']}}</th>
        <td>{{$r['lokasi_berangkat']}}</td>
        <td>{{$r['lokasi_tujuan']}}</td>
        <th scope="col"><a class="btn btn-warning" href="{{route('rute.edit', $r['id'])}}">Edit</a></th>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif

@endsection
