@extends('layouts.app')

@section('content')
<style>
    body { font-family: system-ui; background: linear-gradient(to bottom, white, rgba(138,43,226,0.5)); height: 100vh; }
    .card { background: rgba(255,255,255,0.2); padding: 20px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
</style>

<div class="card">
    <h1 class="text-center">Edit Kegiatan</h1>

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama Kegiatan</label>
            <input type="text" name="kegiatan" value="{{ old('kegiatan', $kegiatan->kegiatan) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Catatan</label>
            <textarea name="catatan" rows="3" class="form-control">{{ old('catatan', $kegiatan->catatan) }}</textarea>
        </div>

        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="{{ old('tanggal', $kegiatan->tanggal) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Waktu Mulai</label>
            <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', $kegiatan->waktu_mulai) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Waktu Selesai</label>
            <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $kegiatan->waktu_selesai) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Tempat</label>
            <input type="text" name="tempat" value="{{ old('tempat', $kegiatan->tempat) }}" class="form-control">
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
