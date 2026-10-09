@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Transaksi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        @if (session('success'))
                            <div class="alert alert-info">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <a class="btn btn-primary" href="{{ route('transaksi.create', 'masuk') }}">+ Barang Masuk</a>
                        <a class="btn btn-success" href="{{ route('transaksi.create', 'penjualan') }}">+ Penjualan</a>
                        <a class="btn btn-info" href="{{ route('transaksi.create', 'transfer') }}">+ Transfer</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Gudang</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Diinput Oleh</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $t)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $t->no_transaksi }}</td>
                                    <td>{{ $t->tanggal }}</td>
                                    <td>{{ ucfirst($t->jenis) }}</td>
                                    <td>
                                        {{ $t->gudang->nama_gudang }}
                                        @if ($t->jenis == 'transfer')
                                            &rarr; {{ $t->gudangTujuan->nama_gudang }}
                                        @endif
                                    </td>
                                    <td>{{ $t->pelanggan->nama_pelanggan ?? '-' }}</td>
                                    <td>Rp {{ number_format($t->total(), 0, ',', '.') }}</td>
                                    <td>{{ $t->user->name }}</td>
                                    <td>
                                        @if ($t->status == 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Batal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('transaksi.show', $t->id) }}">Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
