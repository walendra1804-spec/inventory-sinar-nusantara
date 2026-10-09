@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Pelanggan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Pelanggan</li>
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
                        <h3>Tabel Pelanggan
                            <a class="btn btn-primary" href="{{ route('pelanggan.create') }}">Tambah Data Pelanggan</a>
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Alamat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelanggan as $p)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->nama_pelanggan }}</td>
                                    <td>{{ $p->no_hp }}</td>
                                    <td>{{ $p->alamat }}</td>
                                    <td>
                                        <a class="btn btn-warning" href="{{ route('pelanggan.edit', $p->id) }}">Edit</a>
                                        <a class="btn btn-danger"
                                            onclick="return confirm('Yakin mau hapus data ini ?')"
                                            href="{{ route('pelanggan.destroy', $p->id) }}">Hapus</a>
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