@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Transaksi {{ ucfirst($jenis) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
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
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('transaksi.store', $jenis) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{ $jenis == 'transfer' ? 'Gudang Asal' : 'Gudang' }}</label>
                                    <select class="form-control" name="gudang_id">
                                        <option value="">-- pilih gudang --</option>
                                        @foreach ($gudang as $g)
                                            <option value="{{ $g->id }}" {{ old('gudang_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($jenis == 'transfer')
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Gudang Tujuan</label>
                                    <select class="form-control" name="gudang_tujuan_id">
                                        <option value="">-- pilih gudang tujuan --</option>
                                        @foreach ($gudang as $g)
                                            <option value="{{ $g->id }}" {{ old('gudang_tujuan_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                @if ($jenis == 'penjualan')
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pelanggan</label>
                                    <select class="form-control" name="pelanggan_id">
                                        <option value="">-- pilih pelanggan --</option>
                                        @foreach ($pelanggan as $p)
                                            <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_pelanggan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <input type="text" class="form-control" name="keterangan" value="{{ old('keterangan') }}">
                            </div>

                            <h5>Daftar Barang</h5>
                            <table class="table table-bordered" id="tabel-item">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th width="150">Jumlah</th>
                                        <th width="100">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (old('barang_id', ['']) as $i => $barang_lama)
                                    <tr>
                                        <td>
                                            <select class="form-control" name="barang_id[]">
                                                <option value="">-- pilih barang --</option>
                                                @foreach ($barang as $b)
                                                    <option value="{{ $b->id }}" {{ $barang_lama == $b->id ? 'selected' : '' }}>
                                                        {{ $b->sku }} - {{ $b->nama_barang }} ({{ $b->satuan }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="jumlah[]" min="1" value="{{ old('jumlah')[$i] ?? 1 }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger hapus-baris">Hapus</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-secondary mb-3" id="tambah-baris">+ Tambah Baris</button>
                            <br>

                            <button class="btn btn-primary" type="submit">Simpan</button>
                            <a class="btn btn-warning" href="{{ route('transaksi.index') }}">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script>
    //tombol tambah baris: copy baris pertama, kosongkan isinya, tempel di bawah
    $('#tambah-baris').click(function () {
        var baris = $('#tabel-item tbody tr:first').clone();
        baris.find('select').val('');
        baris.find('input').val(1);
        $('#tabel-item tbody').append(baris);
    });

    //tombol hapus baris (minimal sisa 1 baris)
    $(document).on('click', '.hapus-baris', function () {
        if ($('#tabel-item tbody tr').length > 1) {
            $(this).closest('tr').remove();
        }
    });
</script>
@endsection
