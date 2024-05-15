@extends('layouts.pkBar')


@section('content')

@if(isset($error))
<div class="alert alert-danger">
    {{$error}}
</div>
@endif

@if(session('failed.edit'))
<div class="alert alert-danger">{{ session('failed.edit') }}</div>
@endif

@if(isset($data))
{{-- @foreach($kapals as $k)
    <h1>{{$k['deskripsi']}}</h1>
@endforeach --}}
 {{-- @php
    var_dump($data)
 @endphp --}}
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session('failed.edit'))
<div class="alert alert-danger">{{ session('failed.edit') }}</div>
@endif

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title mb-0">Form Edit Kapal</h2>
                </div>
                <div class="card-body"> 
                    <form action="{{route('kapal.update')}}" class="form" method="POST">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="id" id="" value="{{$data['id']}}">
                        {{-- <h1>{{$data['id']}}</h1> --}}
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama:</label>
                            <input type="text" class="form-control" name="nama" id="nama" value="{{$data['nama']}}">
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi :</label>
                            <input type="text" class="form-control" name="deskripsi" id="deskripsi" value="{{$data['deskripsi']}}">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@endsection
