@extends('layouts.app')

@section('content')
    <div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h2>Pencapaian</h2>
        <div class="row">
            <!-- Kiri -->
            <div class="col-md-8">
                <ul class="list-group">
                    @foreach ($data as $i => $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item['nama'] }}</strong> ({{ $item['kategori'] }})
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <form action="/pencapaian/kurang" method="POST">@csrf
                                    <input type="hidden" name="index" value="{{ $i }}">
                                    <button class="btn btn-warning btn-sm">-</button>
                                </form>
                                <span>{{ $item['counter'] }}</span>
                                <a href="/pencapaian/tambah-counter/{{ $i }}" class="btn btn-success btn-sm">+</a>
                                <form action="/pencapaian/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                                    @csrf
                                    <input type="hidden" name="index" value="{{ $i }}">
                                    <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <br>
                <form action="/pencapaian/reset" method="POST" class="mb-3">
                    @csrf
                    <button class="btn btn-danger">Reset Semua</button>
                </form>
                
                <h4>Tambah Kegiatan Baru</h4>
                <form action="/pencapaian/tambah-kegiatan" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="nama" class="form-control" placeholder="Nama kegiatan" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="kategori" class="form-control" placeholder="Kategori" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Kanan -->
            <div class="col-md-4">
                <h4>Jumlah Per Kategori</h4>
                @php
                    $kategoriTotal = [];
                    foreach ($data as $item) {
                        if (!isset($kategoriTotal[$item['kategori']])) {
                            $kategoriTotal[$item['kategori']] = 0;
                        }
                        $kategoriTotal[$item['kategori']] += $item['counter'];
                    }
                @endphp
                <ul class="list-group">
                    @forelse ($kategoriTotal as $kategori => $total)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $kategori }}</span>
                            <span><strong>{{ $total }}</strong></span>
                        </li>
                    @empty
                        <li class="list-group-item">Belum ada kegiatan.</li>
                    @endforelse
                </ul>
                <hr>

                <h4>Catatan Terbaru</h4>
                @php
                    $catatan = session('catatan', []);
                    $catatanTerbalik = array_reverse($catatan); // tampilkan yang terbaru dulu
                @endphp

                @if (count($catatanTerbalik) > 0)
                    <ul class="list-group mb-4">
                        @foreach ($catatanTerbalik as $c)
                            <li class="list-group-item">
                                <div class="fw-bold">{{ $c['nama'] }} ({{ $c['kategori'] }})</div>
                                <div>{{ $c['keterangan'] }}</div>
                                <small class="text-muted">{{ $c['waktu'] }}</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Belum ada catatan.</p>
                @endif

            </div>
        </div>
        <hr>
    </div>
@endsection
                    