@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Tambah Counter</h3>
        <p><strong>{{ $kegiatan['nama'] }}</strong> - {{ $kegiatan['kategori'] }}</p>

        <form action="/pencapaian/tambah-counter/{{ $index }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="/pencapaian" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
