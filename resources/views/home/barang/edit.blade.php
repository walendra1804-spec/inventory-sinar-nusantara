@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Data Barang</h1>
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
                            <h3>Edit Data Barang</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('barang.update', $barang->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">SKU</label>
                                    <input type="text" class="form-control" name="sku" value="{{ $barang->sku }}" />
                                </div>
                                @error('sku')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" name="nama_barang"
                                        value="{{ $barang->nama_barang }}" />
                                </div>
                                @error('nama_barang')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <input type="text" class="form-control" name="kategori"
                                        value="{{ $barang->kategori }}" />
                                </div>
                                @error('kategori')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <input type="text" class="form-control" name="satuan" value="{{ $barang->satuan }}"
                                        placeholder="pcs / kg / box" />
                                </div>
                                @error('satuan')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label class="form-label">Harga Pokok</label>
                                    <input type="number" class="form-control" name="harga_pokok"
                                        value="{{ $barang->harga_pokok }}" />
                                </div>
                                @error('harga_pokok')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label class="form-label">Harga Jual</label>
                                    <input type="number" class="form-control" name="harga_jual"
                                        value="{{ $barang->harga_jual }}" />
                                </div>
                                @error('harga_jual')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status_aktif">
                                        <option value="1" {{ $barang->status_aktif == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ $barang->status_aktif == 0 ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                </div>

                                <button class="btn btn-primary" type="submit">Simpan</button>
                                <a class="btn btn-warning" href="{{ route('barang.index') }}">Kembali ke Halaman Barang</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection