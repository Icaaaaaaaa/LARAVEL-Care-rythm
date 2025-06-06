@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Rhythm - Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom, rgb(255, 255, 255), rgba(138, 43, 226, 0.5));
        }
        .header {
            background-color: #7a3cff;
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1rem 1rem;
        }
        .jadwal-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .jadwal-item {
            background-color: white;
            border-radius: 0.5rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: relative;
        }
        .jadwal-item h4 {
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .jadwal-item .waktu {
            color: #666;
            margin-bottom: 0.5rem;
        }
        .edit-btn {
            position: absolute;
            right: 1.25rem;
            bottom: 1.25rem;
            color: #7a3cff;
            font-weight: bold;
            text-decoration: none;
        }
        .delete-form {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }
        .filter-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            align-items: center;
        }
        .tambah-btn {
            background-color: #7a3cff;
            border: none;
            border-radius: 0.25rem;
            padding: 0.5rem 1rem;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .tambah-btn:hover {
            background-color: #6a2cee;
            color: white;
        }
        .filter-group {
            display: flex;
            gap: 0.5rem;
        }
        .hari-label {
            background-color: #e0d0ff;
            color: #7a3cff;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="jadwal-container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="filter-container">
            <a href="{{ route('jadwal.create') }}" class="tambah-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus me-1" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                Tambah Jadwal
            </a>
            
            <div class="filter-group">
                <div class="input-group">
                    <form method="GET" action="{{ route('jadwal.index') }}">
                        <select class="form-select form-select-sm" name="hari" style="width: 120px;" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ request('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                            <option value="Minggu" {{ request('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                        </select>
                    </form>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Harian
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Harian</a></li>
                        <li><a class="dropdown-item" href="#">Mingguan</a></li>
                    </ul>
                </div>
            </div>
        </div>

        @php
            // Tidak perlu filter lagi di view, karena controller sudah mengirimkan jadwal user yang login
            $jadwalsUser = $jadwals;
        @endphp

        @forelse($jadwalsUser as $jadwal)
            <div class="jadwal-item">
                <span class="hari-label">{{ $jadwal->hari }}</span>
                <h4>{{ $jadwal->nama_jadwal }}</h4>
                <div class="waktu">
                    {{ $jadwal->waktu_mulai ?? $jadwal->jam }}
                    @if(!empty($jadwal->waktu_selesai))
                        - {{ $jadwal->waktu_selesai }}
                    @endif
                </div>
                @if(!empty($jadwal->kategori))
                    <div><strong>Kategori:</strong> {{ $jadwal->kategori }}</div>
                @endif
                @if(!empty($jadwal->catatan))
                    <div><strong>Catatan:</strong> {{ $jadwal->catatan }}</div>
                @endif

                <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="edit-btn">Edit</a>

                <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</button>
                </form>
            </div>
        @empty
            <div class="text-center py-4">
                <p class="text-muted">Belum ada jadwal</p>
                <a href="{{ route('jadwal.create') }}" class="tambah-btn mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus me-1" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Tambah Jadwal Pertama
                </a>
            </div>
        @endforelse
    </div>

    <script>
        // Filter hari
        document.querySelector('select[name="hari"]').addEventListener('change', function() {
            window.location.href = "{{ route('jadwal.index') }}?hari=" + this.value;
        });

        // Set nilai filter hari jika ada di URL
        const urlParams = new URLSearchParams(window.location.search);
        const selectedHari = urlParams.get('hari');
        if (selectedHari) {
            document.querySelector('select[name="hari"]').value = selectedHari;
        }
    </script>
@endsection
