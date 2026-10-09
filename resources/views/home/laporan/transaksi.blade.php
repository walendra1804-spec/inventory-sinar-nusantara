@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Laporan {{ $jenis == 'masuk' ? 'Barang Masuk' : 'Penjualan' }}</h1>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{-- form filter, pakai GET agar filter muncul di URL --}}
                        <form action="{{ route('laporan.transaksi', $jenis) }}" method="GET" class="row">
                            <div class="col-md-2 mb-2">
                                <label>Dari Tanggal</label>
                                <input type="date" class="form-control" name="dari" value="{{ request('dari') }}">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Sampai Tanggal</label>
                                <input type="date" class="form-control" name="sampai" value="{{ request('sampai') }}">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Gudang</label>
                                <select class="form-control" name="gudang_id">
                                    <option value="">-- semua --</option>
                                    @foreach ($gudang as $g)
                                        <option value="{{ $g->id }}" {{ request('gudang_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Barang</label>
                                <select class="form-control" name="barang_id">
                                    <option value="">-- semua --</option>
                                    @foreach ($barang as $b)
                                        <option value="{{ $b->id }}" {{ request('barang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-1">Filter</button>
                                <a href="{{ route('laporan.transaksi', $jenis) }}" class="btn btn-secondary mr-1">Reset</a>
                                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-success">Export CSV</a>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Gudang</th>
                                    @if ($jenis == 'penjualan')
                                        <th>Pelanggan</th>
                                    @endif
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $grand_total = 0; @endphp
                                @foreach ($data as $d)
                                @php $grand_total += $d->jumlah * $d->harga; @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->transaksi->no_transaksi }}</td>
                                    <td>{{ $d->transaksi->tanggal }}</td>
                                    <td>{{ $d->transaksi->gudang->nama_gudang }}</td>
                                    @if ($jenis == 'penjualan')
                                        <td>{{ $d->transaksi->pelanggan->nama_pelanggan }}</td>
                                    @endif
                                    <td>{{ $d->barang->nama_barang }}</td>
                                    <td>{{ $d->jumlah }} {{ $d->barang->satuan }}</td>
                                    <td>Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($d->jumlah * $d->harga, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <th colspan="{{ $jenis == 'penjualan' ? 8 : 7 }}" class="text-right">Total</th>
                                    <th>Rp {{ number_format($grand_total, 0, ',', '.') }}</th>
                                </tr>
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
