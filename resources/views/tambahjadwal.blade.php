<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal - Care Rhythm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #f0e6ff, #d9c6ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
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
        .btn-outline-secondary {
            padding: 0.5rem 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Tambah Jadwal Baru</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('jadwal.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="nama_jadwal" class="form-label">Nama Jadwal</label>
                    <input type="text" class="form-control" id="nama_jadwal" name="nama_jadwal" required placeholder="Contoh: Kelas Bahasa Inggris" value="{{ old('nama_jadwal') }}">
                </div>
                
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Pelajaran" {{ old('kategori') == 'Pelajaran' ? 'selected' : '' }}>Pelajaran</option>
                        <option value="Olahraga" {{ old('kategori') == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="Hiburan" {{ old('kategori') == 'Hiburan' ? 'selected' : '' }}>Hiburan</option>
                        <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                        <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" required value="{{ old('waktu_mulai') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                        <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai" required value="{{ old('waktu_selesai') }}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Hari</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="hari" value="{{ $hari }}" id="hari_{{ $loop->index }}" {{ old('hari') == $hari ? 'checked' : '' }}>
                                <label class="form-check-label" for="hari_{{ $loop->index }}">{{ $hari }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                    <textarea class="form-control" id="catatan" name="catatan" rows="2" placeholder="Tambahkan catatan jika perlu">{{ old('catatan') }}</textarea>
                </div>
                
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
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
                    waktuSelesai.value = '';
                }
            });
        });
    </script>
</body>
</html>
