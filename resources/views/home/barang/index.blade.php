@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Barang</li>
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
                        <h3>Tabel Barang
                            <a class="btn btn-primary" href="{{ route('barang.create') }}">Tambah Data Barang</a>
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SKU</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Harga Pokok</th>
                                    <th>Harga Jual</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barang as $b)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $b->sku }}</td>
                                    <td>{{ $b->nama_barang }}</td>
                                    <td>{{ $b->kategori }}</td>
                                    <td>{{ $b->satuan }}</td>
                                    <td>Rp {{ number_format($b->harga_pokok, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($b->status_aktif)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-warning" href="{{ route('barang.edit', $b->id) }}">Edit</a>
                                        <a class="btn btn-danger"
                                            onclick="return confirm('Yakin mau hapus data ini ?')"
                                            href="{{ route('barang.destroy', $b->id) }}">Hapus</a>
                                    </td>
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