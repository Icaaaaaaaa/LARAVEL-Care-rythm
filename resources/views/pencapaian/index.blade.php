@extends('layouts.app')

@section('content')
    <div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2>Pencapaian</h2>
        <div class="row">
            <!-- Kiri -->
            <div class="col-md-8">
                <ul class="list-group">
                    @foreach ($pencapaians as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item->nama }}</strong>
                                @if(isset($item->kategori))
                                    ({{ $item->kategori }})
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                {{-- Tombol tambah jumlah --}}
                                <form action="{{ url('/pencapaian/tambah') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button type="submit" class="btn btn-success btn-sm" title="Tambah jumlah {{ $item->nama }}">+</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <br>
                <form action="{{ route('pencapaian.reset') }}" method="POST" class="mb-3">
                    @csrf
                    <button class="btn btn-danger">Reset Semua</button>
                </form>
                
                <h4>Tambah Pencapaian Baru</h4>
                <form action="{{ route('pencapaian.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="nama" class="form-control" placeholder="Nama pencapaian" required>
                        </div>
                        <div class="col-md-4">
                            <select name="kategori" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                <option value="pelajaran">Pelajaran</option>
                                <option value="istirahat">Istirahat</option>
                                <option value="olahraga">Olahraga</option>
                                <option value="hiburan">Hiburan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="target" class="form-control" placeholder="Target" required>
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Kanan -->
            <div class="col-md-4">
                <h4>Jumlah Per Kategori</h4>
                @php
                    $kategoriTotal = [];
                    foreach ($pencapaians as $item) {
                        $kategori = $item->kategori ?? '-';
                        if (!isset($kategoriTotal[$kategori])) {
                            $kategoriTotal[$kategori] = 0;
                        }
                        $kategoriTotal[$kategori] += $item->jumlah;
                    }
                @endphp
                <ul class="list-group">
                    @forelse ($kategoriTotal as $kategori => $total)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $kategori }}</span>
                            <strong>{{ $total }}</strong>
                        </li>
                    @empty
                        <li class="list-group-item">Belum ada pencapaian.</li>
                    @endforelse
                </ul>
                <hr>

                <h4>Pencapaian Terbaru</h4>
                <ul class="list-group mb-4">
                    @forelse ($pencapaians->sortByDesc('waktu_pencapaian')->take(5) as $item)
                        <li class="list-group-item">
                            <div class="fw-bold">{{ $item->nama }} @if(isset($item->kategori)) ({{ $item->kategori }}) @endif</div>
                            <div>Target: {{ $item->target }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($item->waktu_pencapaian)->format('d M Y H:i') }}</small>
                        </li>
                    @empty
                        <li class="list-group-item">Belum ada pencapaian.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <hr>
    </div>
@endsection
