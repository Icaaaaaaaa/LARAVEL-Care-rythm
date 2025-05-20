@extends('layouts.app')

@section('content')
<style>
    body { font-family: system-ui; background: linear-gradient(to bottom, white, rgba(138,43,226,0.5)); height: 100vh; padding: 20px; }
    .card { background: rgba(255,255,255,0.2); padding: 20px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); margin-bottom: 15px; }
    .actions { margin-top: 10px; }
</style>

{{-- Tampilkan pesan sukses --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<a href="{{ route('kegiatan.create') }}" class="btn btn-success mb-3">+ Tambah Kegiatan</a>

@foreach ($kegiatan as $item)
    <div class="card">
        <h4>{{ $item->kegiatan }}</h4>
        <p>Catatan: {{ $item->catatan }}</p>
        <p>Tanggal: {{ $item->tanggal }}</p>
        <p>Waktu: {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}</p>
        <p>Tempat: {{ $item->tempat }}</p>
        <div class="actions">
            <a href="{{ route('kegiatan.edit', $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
            <form action="{{ route('kegiatan.destroy', $item->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </div>
    </div>
@endforeach
@endsection
