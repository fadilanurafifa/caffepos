@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard Kasir</h1>
    <p>Selamat datang, {{ auth()->user()->name }}!</p>

    <a href="{{ route('penjualan.create') }}" class="btn btn-primary">Buat Transaksi Baru</a>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>    
</div>
@endsection
