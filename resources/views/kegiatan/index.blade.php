@extends('layouts.app')

@section('content')
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color: #000;
            background: linear-gradient(to bottom, rgb(255, 255, 255), rgba(138, 43, 226, 0.5));
            background-size: cover;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            margin: 20px 0;
        }

        a {
            text-decoration: none;
            color: blue;
            margin-right: 10px;
        }

        button {
            background-color: red;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-link {
            display: inline-block;
            margin-bottom: 20px;
            font-weight: bold;
            color: green;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

    </style>

    <a class="add-link" href="{{ route('kegiatan.create') }}">+ Tambah Kegiatan</a>
    <div class="row">
        
        @foreach ($kegiatan as $item)
            <div class="card">
                <h3>{{ $item['nama_kegiatan'] }}</h3>
                <p>{{ $item['deskripsi'] }}</p>
                <p>Hari: 
                    @if(is_array($item['hari']))
                        {{ implode(', ', $item['hari']) }}
                    @else
                        {{ $item['hari'] }}
                    @endif
                </p>
                <p>Waktu: {{ $item['waktu'] }}</p>
                <p>Kategori: {{ $item['kategori'] }}</p>
                <a href="{{ route('kegiatan.edit', $loop->index) }}">Edit</a>
                <form action="{{ route('kegiatan.destroy', $loop->index) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin ingin menghapus kegiatan ini?')">Hapus</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
