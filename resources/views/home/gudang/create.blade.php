@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tambah Data Gudang</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active">Gudang</li>
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
                            <h3>Tambah Data Gudang</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('gudang.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama Gudang</label>
                                    <input type="text" class="form-control" name="nama_gudang"
                                        value="{{ old('nama_gudang') }}" />
                                </div>
                                @error('nama_gudang')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat">{{ old('alamat') }}</textarea>
                                </div>

                                <button class="btn btn-primary" type="submit">Simpan</button>
                                <a class="btn btn-warning" href="{{ route('gudang.index') }}">Kembali ke Halaman Gudang</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection