{{-- Atur layout navbar --}}
@php
    // $extend = (Auth::user()->role_id == 2) ?  : ;
    if(session('role') == "admin"){     // admin
        $extend = 'layouts.adminBar';
    }else if(session('role') == "ship_owner"){ // ship owner
        $extend = 'layouts.pkBar';
    }else if(session('role') == "passenger"){ // passenger
        $extend = 'layouts.pasBar';
    }
@endphp

@extends($extend)


@section('content')

<center>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if(session('berhasil'))
<div class="alert alert-success">   
    {{ session('berhasil') }}
</div>
@endif
@if(session('user_id'))
<h1>Welcome {{session('username')}}</h1>
<h2></h2>
    {{-- <p>User ID: {{ session('user_id') }}</p> --}}
    <p>Role : {{ session('role') }}</p>


    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-danger" type="submit">Logout</button>
    </form>
@else
    <p>User ID not found in session</p>
@endif
</center>
<br><br><br>



@endsection
