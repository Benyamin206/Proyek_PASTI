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

    

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="card-title mb-0">Tambah Rute</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('rute.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="lokasi_berangkat" class="form-label">Lokasi Berangkat:</label>
                                <input type="text" class="form-control" name="lokasi_berangkat" id="lokasi_berangkat" required>
                            </div>
    
                            <div class="mb-3">
                                <label for="lokasi_tujuan" class="form-label">Lokasi Tujuan:</label>
                                <input type="text" class="form-control" name="lokasi_tujuan" id="lokasi_tujuan" required>
                            </div>
    
                            <!-- <input type="hidden" name="pemilik_kapal_id" id="" value="{{session('user_id')}}"> -->
    
                            <button class="btn btn-primary w-100" type="submit">Tambah Rute</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
