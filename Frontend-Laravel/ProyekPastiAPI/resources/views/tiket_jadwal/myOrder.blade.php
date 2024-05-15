@extends('layouts.pasBar')

@section('content')


    @if(empty($orders))
        <div class="alert alert-warning">
            No orders found.
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Order ID</th>
                        {{-- <th scope="col">User ID</th> --}}
                        <th scope="col">Jadwal ID</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total Amount</th>
                        {{-- <th scope="col">Status Pembayaran</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order['id'] }}</td>
                            {{-- <td>{{ $order['user_id'] }}</td> --}}
                            <td>{{ $order['jadwal_id'] }}</td>
                            <td>{{ $order['qty'] }}</td>
                            <td>{{ $order['total_amount'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
