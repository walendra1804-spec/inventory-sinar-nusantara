@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Data Pelanggan</h1>
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
                            <h3>Edit Data Pelanggan</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama Pelanggan</label>
                                    <input type="text" class="form-control" name="nama_pelanggan"
                                        value="{{ $pelanggan->nama_pelanggan }}" />
                                </div>
                                @error('nama_pelanggan')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label class="form-label">No HP</label>
                                    <input type="text" class="form-control" name="no_hp" value="{{ $pelanggan->no_hp }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat">{{ $pelanggan->alamat }}</textarea>
                                </div>
                                @error('alamat')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror

                                <button class="btn btn-primary" type="submit">Simpan</button>
                                <a class="btn btn-warning" href="{{ route('pelanggan.index') }}">Kembali ke Halaman Pelanggan</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection