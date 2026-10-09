@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Laporan Stok</h1>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{-- form filter, pakai GET agar filter muncul di URL --}}
                        <form action="{{ route('laporan.stok') }}" method="GET" class="row">
                            <div class="col-md-3 mb-2">
                                <label>Stok per Tanggal</label>
                                <input type="date" class="form-control" name="sampai" value="{{ request('sampai') }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Gudang</label>
                                <select class="form-control" name="gudang_id">
                                    <option value="">-- semua gudang --</option>
                                    @foreach ($gudang as $g)
                                        <option value="{{ $g->id }}" {{ request('gudang_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Barang</label>
                                <select class="form-control" name="barang_id">
                                    <option value="">-- semua barang --</option>
                                    @foreach ($barang as $b)
                                        <option value="{{ $b->id }}" {{ request('barang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-1">Filter</button>
                                <a href="{{ route('laporan.stok') }}" class="btn btn-secondary mr-1">Reset</a>
                                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-success">Export CSV</a>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SKU</th>
                                    <th>Nama Barang</th>
                                    <th>Gudang</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->barang->sku }}</td>
                                    <td>{{ $d->barang->nama_barang }}</td>
                                    <td>{{ $d->gudang->nama_gudang }}</td>
                                    <td>{{ $d->stok }} {{ $d->barang->satuan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
