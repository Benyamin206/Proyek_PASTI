@extends('layouts.pkBar')


@section('content')

@if(isset($error))
<div class="alert alert-danger">
    {{$error}}
</div>
@endif



@if(isset($kapals))
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
        <th scope="col">Nama</th>
        <th scope="col">Deskripsi</th>
        <th scope="col">Action</th>

        {{-- <th scope="col">Pemilik Kapal</th> --}}
      </tr>
    </thead>
    <tbody>
        @foreach($kapals as $k)
      <tr>
        <th scope="row">{{$k['id']}}</th>
        <td>{{$k['nama']}}</td>
        <td>{{$k['deskripsi']}}</td>
        <th scope="col"><a class="btn btn-warning" href="{{route('kapal.edit', $k['id'])}}">Edit</a></th>
        {{-- <td>{{$k['pemilik_kapal_id']}}</td> --}}
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif

@endsection
