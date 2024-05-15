@extends('layouts.adminBar')



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

    <h2>Tambah Nahkoda</h2><br><br>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="card-title mb-0">Tambah Nahkoda</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('nahkoda.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama:</label>
                                <input type="text" class="form-control" name="nama" id="nama" required>
                            </div>
    
                            <div class="mb-3">
                                <label for="nomor_hp" class="form-label">Nomor HP:</label>
                                <input type="text" class="form-control" name="nomor_hp" id="nomor_hp" required>
                            </div>
    
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin:</label>
                                <input type="text" class="form-control" name="jenis_kelamin" id="jenis_kelamin" required>
                            </div>
    
                            <!-- <input type="hidden" name="pemilik_kapal_id" id="" value="{{session('user_id')}}"> -->
    
                            <button class="btn btn-primary w-100" type="submit">Tambah Nahkoda</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
