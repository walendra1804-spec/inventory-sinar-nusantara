@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-info">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <table class="table table-sm">
                            <tr><th width="200">No Transaksi</th><td>{{ $transaksi->no_transaksi }}</td></tr>
                            <tr><th>Jenis</th><td>{{ ucfirst($transaksi->jenis) }}</td></tr>
                            <tr><th>Tanggal</th><td>{{ $transaksi->tanggal }}</td></tr>
                            <tr><th>{{ $transaksi->jenis == 'transfer' ? 'Gudang Asal' : 'Gudang' }}</th><td>{{ $transaksi->gudang->nama_gudang }}</td></tr>
                            @if ($transaksi->jenis == 'transfer')
                                <tr><th>Gudang Tujuan</th><td>{{ $transaksi->gudangTujuan->nama_gudang }}</td></tr>
                            @endif
                            @if ($transaksi->jenis == 'penjualan')
                                <tr><th>Pelanggan</th><td>{{ $transaksi->pelanggan->nama_pelanggan }}</td></tr>
                            @endif
                            <tr><th>Diinput Oleh</th><td>{{ $transaksi->user->name }} ({{ $transaksi->created_at }})</td></tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($transaksi->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Batal</span>
                                    @endif
                                </td>
                            </tr>
                            <tr><th>Keterangan</th><td>{{ $transaksi->keterangan ?? '-' }}</td></tr>
                        </table>

                        <h5 class="mt-4">Daftar Barang</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi->detail as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->barang->sku }} - {{ $d->barang->nama_barang }}</td>
                                    <td>{{ $d->jumlah }} {{ $d->barang->satuan }}</td>
                                    <td>Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($d->jumlah * $d->harga, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <th colspan="4" class="text-right">Total</th>
                                    <th>Rp {{ number_format($transaksi->total(), 0, ',', '.') }}</th>
                                </tr>
                            </tbody>
                        </table>

                        <h5 class="mt-4">Kartu Stok dari Transaksi Ini</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Barang</th>
                                    <th>Gudang</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mutasi as $m)
                                <tr>
                                    <td>{{ $m->created_at }}</td>
                                    <td>{{ $m->barang->nama_barang }}</td>
                                    <td>{{ $m->gudang->nama_gudang }}</td>
                                    <td>{{ $m->jumlah > 0 ? '+' : '' }}{{ $m->jumlah }}</td>
                                    <td>{{ $m->keterangan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <a class="btn btn-warning" href="{{ route('transaksi.index') }}">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
