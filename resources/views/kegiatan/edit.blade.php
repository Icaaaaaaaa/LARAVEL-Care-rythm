@extends('layouts.app') {{-- Ganti dengan layout yang sesuai --}}

@section('content')
<style>
body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color: #000;
            background: linear-gradient(to bottom, rgb(255, 255, 255), rgba(138, 43, 226,0.5));
            background-size: cover;
            background-position: top;
            height: 100%; /* Tinggi elemen sesuai dengan tinggi viewport */
            width: 100%;
            height: 100vh;
          
            justify-content: center;
            align-items: center;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
          
        }

        .card h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
    <div class="card">
        <h1 class="text-center">Edit Kegiatan</h1>

        <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama Kegiatan -->
            <div class="form-group">
                <label for="namaKegiatan">Nama Kegiatan</label>
                <input
                    type="text"
                    id="namaKegiatan"
                    name="nama_kegiatan"
                    value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}"
                    required
                    class="form-control"
                />
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label for="deskripsiKegiatan">Deskripsi</label>
                <textarea
                    id="deskripsiKegiatan"
                    name="deskripsi"
                    rows="3"
                    class="form-control"
                    required
                >{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
            </div>

            <!-- Hari -->
            <div class="form-group">
                <label for="hari">Hari</label><br>
                @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    $selectedDays = $kegiatan->hari ?? [];
                @endphp
                @foreach($days as $day)
                    <label>
                        <input type="checkbox" name="hari[]" value="{{ $day }}"
                            {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                        {{ $day }}
                    </label><br>
                @endforeach
            </div>

            <!-- Waktu -->
            <div class="form-group">
                <label for="waktu">Waktu</label>
                <input
                    type="time"
                    id="waktu"
                    name="waktu"
                    value="{{ old('waktu', $kegiatan->waktu) }}"
                    required
                    class="form-control"
                />
            </div>

            <!-- Kategori -->
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" required class="form-control">
                    <option value="Membaca" {{ $kegiatan->kategori == 'Membaca' ? 'selected' : '' }}>Membaca</option>
                    <option value="Olahraga" {{ $kegiatan->kategori == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                    <option value="Belajar" {{ $kegiatan->kategori == 'Belajar' ? 'selected' : '' }}>Belajar</option>
                </select>
            </div>

            <!-- Tombol Submit -->
            <div class="action">
                <button type="submit" class="btn btn-primary">Update Kegiatan</button>
                <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
