@extends('master')

@section('konten_utama')
<div class="card auth-card p-5">
    <h2 class="fw-bold">Menu Larita Bakery</h2>
    <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong>!</p>
    <div class="bg-warning p-2 rounded mb-3 text-dark">
        Poin Kamu: <strong>{{ Auth::user()->points }}</strong>
    </div>
    <hr>
    <p>Daftar roti akan muncul di sini.</p>
    
    <form action="/logout" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-danger">Logout</button>
    </form>
</div>
@endsection