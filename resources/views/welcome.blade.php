@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Care Rythm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            min-height: 100vh;
            color: #fff;
        }
        .main-content {
            background: #fff;
            border-radius: 2rem;
            box-shadow: 0 4px 32px rgba(130, 90, 200, 0.08);
            padding: 2rem;
            margin-top: 2rem;
        }
        .card-custom {
            background: #ececec; /* sedikit lebih silver */
            border-radius: 1.2rem;
            color: #4b006e;
            box-shadow: 0 2px 16px rgba(130,90,200,0.10);
            max-height: 500px; /* tinggi maksimum card jadwal */
            overflow-y: auto;  /* scroll hanya di dalam card */
        }
        .rounded-btn {
            border-radius: 2rem;
        }
        .bg-purple {
            background: #a259e6 !important;
            color: #fff !important;
        }
        .text-purple {
            color: #a259e6 !important;
        }
        .sidebar .nav-link, .sidebar .nav-link.active {
            color: #fff;
        }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            border-radius: 1rem;
        }
        .circle {
            width: 40px; height: 40px; border-radius: 50%; background: #a259e6; display: inline-block;
        }
        .friend-active {
            background: #a259e6;
            color: #fff;
            border-radius: 1rem;
        }
        .friend {
            background: #f3e6ff;
            color: #a259e6;
            border-radius: 1rem;
        }
        .jadwal-time-col {
            width: 60px;
            color: #444;
            font-size: 0.95rem;
            text-align: right;
            padding-right: 10px;
            user-select: none;
        }
        .jadwal-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 8px;
        }
        .jadwal-item {
            flex: 1;
            background: #b6e2c7;
            border-radius: 0.8rem;
            padding: 0.6rem 1rem;
            margin-left: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 44px;
        }
        .jadwal-empty {
            flex: 1;
            min-height: 44px;
            margin-left: 10px;
        }
        .jadwal-label {
            font-weight: 500;
        }
        .jadwal-list-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
    </style>
</head>
<body style="padding:0;margin:0;">
<div class="container-fluid px-0 d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="row gx-0 w-100 justify-content-center">
        <!-- Main Content -->
            <div class="row mb-4">
                <div class="col-8">
                    <div class="card card-custom p-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Jadwal</span>
                            <span class="fw-bold">Rutinitas</span>
                        </div>
                        <div class="mt-3">
                            @php
                                $sortedJadwals = $jadwals->sortBy('waktu_mulai');
                            @endphp
                            @forelse($sortedJadwals as $jadwal)
                                <div class="mb-4 p-3" style="background: #fff; border-radius: 1rem; box-shadow: 0 2px 8px #b6b6b61a; position:relative;">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            @if($jadwal->hari)
                                                @foreach(explode(',', $jadwal->hari) as $hari)
                                                    <span class="badge" style="background:#ede3ff;color:#7a3cff;font-weight:500;">{{ $hari }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="fw-bold" style="font-size:1.4rem;">{{ $jadwal->nama_jadwal }}</div>
                                        <div class="text-muted mb-2" style="font-size:1.1rem;">
                                            {{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai }}
                                        </div>
                                        <div><b>Kategori:</b> {{ $jadwal->kategori }}</div>
                                        <div><b>Catatan:</b> {{ $jadwal->catatan }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted">Belum ada jadwal</div>
                            @endforelse
                        </div>
                        <div class="mt-3">
                            {{-- Hapus badge kategori di pojok kiri bawah --}}
                            {{-- @if(isset($jadwals[0]->kategori))
                                <span class="badge bg-purple">{{ $jadwals[0]->kategori }}</span>
                            @endif --}}
                        </div>
                        <button class="btn btn-danger rounded-btn mt-3">Jadwal Darurat</button>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card card-custom p-3">
                        <h6 class="fw-bold mb-3">Rencana</h6>
                        <ul class="list-unstyled mb-3">
                            <li><i class="bi bi-plus-circle"></i> Menambah rutinitas</li>
                            <li><i class="bi bi-plus-circle"></i> Tugas Matematika</li>
                            <li><i class="bi bi-plus-circle"></i> Jalan-jalan bersama</li>
                        </ul>
                        <button class="btn btn-purple btn-sm rounded-btn w-100 mb-2">+</button>
                        <h6 class="fw-bold mt-4 mb-2">Teman</h6>
                        <div class="friend-active p-2 mb-2">Liam <span class="small text-white">Jawa Barat</span></div>
                        <div class="friend p-2 mb-2">Budiono Siregar</div>
                        <button class="btn btn-purple btn-sm rounded-btn w-100">+</button>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-8">
                    <div class="card card-custom p-3">
                        <h6 class="fw-bold mb-3">Catatan kegiatan</h6>
                        <div class="d-flex gap-3 mb-3">
                            <div class="text-center flex-fill">
                                <div class="display-6 fw-bold">10</div>
                                <div class="small">Olahraga</div>
                            </div>
                            <div class="text-center flex-fill">
                                <div class="display-6 fw-bold">12</div>
                                <div class="small">Belajar</div>
                            </div>
                            <div class="text-center flex-fill">
                                <div class="display-6 fw-bold">4</div>
                                <div class="small">Membaca</div>
                            </div>
                        </div>
                        <button class="btn btn-outline-purple rounded-btn">Ubah target</button>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card card-custom p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold">Target Kamu</span>
                            <span class="badge bg-purple">Mingguan</span>
                        </div>
                        <ul class="list-unstyled">
                            <li>10/30</li>
                            <li>12/25</li>
                            <li>4/10</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
@endsection
