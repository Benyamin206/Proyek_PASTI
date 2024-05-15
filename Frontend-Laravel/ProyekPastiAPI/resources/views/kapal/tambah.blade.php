@extends('layouts.pkBar')



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

    <h2>Tambah Kapal</h2><br><br>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="card-title mb-0">Tambah Kapal</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kapal.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama:</label>
                                <input type="text" class="form-control" name="nama" id="nama" required>
                            </div>
    
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi:</label>
                                <input type="text" class="form-control" name="deskripsi" id="deskripsi" required>
                            </div>
    
                            <input type="hidden" name="pemilik_kapal_id" id="pemilik_kapal_id" value="{{ session('user_id') }}">
    
                            <button class="btn btn-primary w-100" type="submit">Tambah Kapal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
