@extends('layouts.adminBar')


@section('content')

@if(isset($error))
<div class="alert alert-danger">
    {{$error}}
</div>
@endif


@if(isset($nahkodas))
{{-- @foreach($kapals as $k)
    <h1>{{$k['deskripsi']}}</h1>
@endforeach --}}
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session('success.edit'))
<div class="alert alert-success">{{ session('success.edit') }}</div>
@endif
<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nama</th>
        <th scope="col">Nomor Hp</th>
        <th scope="col">Jenis Kelamin</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
        @foreach($nahkodas as $n)
      <tr>
        <th scope="row">{{$n['id']}}</th>
        <td>{{$n['nama']}}</td>
        <td>{{$n['nomor_hp']}}</td>
        <td>{{$n['jenis_kelamin']}}</td>
        <th scope="col"><a class="btn btn-warning" href="{{route('nahkoda.edit', $n['id'])}}">Edit</a></th>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif

@endsection
