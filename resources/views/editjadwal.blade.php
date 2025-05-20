<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal - Care Rhythm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(to bottom, rgb(255, 255, 255), rgba(138, 43, 226, 0.5));
        }
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin: 2rem auto;
        }
        .form-title {
            color: #7a3cff;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background-color: #7a3cff;
            border: none;
            padding: 0.5rem 1.5rem;
        }
        .btn-primary:hover {
            background-color: #6a2cee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Edit Jadwal</h2>
            
            <form action="{{ route('jadwal.update', $id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="nama_jadwal" class="form-label">Nama Jadwal</label>
                    <input type="text" class="form-control" id="nama_jadwal" name="nama_jadwal" required 
                           value="{{ old('nama_jadwal', $jadwal['nama_jadwal'] ?? '') }}">
                </div>
                
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori" name="kategori" required>
                        <option value="Pelajaran" {{ ($jadwal['kategori'] ?? '') == 'Pelajaran' ? 'selected' : '' }}>Pelajaran</option>
                        <option value="Olahraga" {{ ($jadwal['kategori'] ?? '') == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="Hiburan" {{ ($jadwal['kategori'] ?? '') == 'Hiburan' ? 'selected' : '' }}>Hiburan</option>
                        <option value="Lainnya" {{ ($jadwal['kategori'] ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                        <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" 
                               value="{{ old('waktu_mulai', $jadwal['waktu_mulai'] ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                        <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai" 
                               value="{{ old('waktu_selesai', $jadwal['waktu_selesai'] ?? '') }}" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Hari</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="hari" 
                                       value="{{ $hari }}" id="hari_{{ $loop->index }}"
                                       {{ (old('hari', $jadwal['hari'] ?? '') == $hari) ? 'checked' : '' }}>
                                <label class="form-check-label" for="hari_{{ $loop->index }}">{{ $hari }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                    <textarea class="form-control" id="catatan" name="catatan" rows="2">{{ old('catatan', $jadwal['catatan'] ?? '') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="jam" class="form-label">Jam</label>
                    <input type="time" class="form-control" id="jam" name="jam" required value="{{ old('jam', $jadwal['jam'] ?? '') }}">
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validasi client-side untuk waktu selesai harus setelah waktu mulai
        document.addEventListener('DOMContentLoaded', function() {
            const waktuMulai = document.getElementById('waktu_mulai');
            const waktuSelesai = document.getElementById('waktu_selesai');
            
            waktuMulai.addEventListener('change', function() {
                waktuSelesai.min = this.value;
                if (waktuSelesai.value && waktuSelesai.value < this.value) {
                    waktuSelesai.value = this.value;
                }
            });
        });
    </script>
</body>
</html>