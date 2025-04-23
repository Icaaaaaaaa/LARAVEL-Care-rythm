
<div class="container">
<a href="{{ route('kegiatan.create') }}">+ Tambah Kegiatan</a>
</div>

<style>
body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color: #000;
            background: linear-gradient(to bottom, rgb(255, 255, 255), rgba(138, 43, 226,0.5));
            background-size: cover;
           
            height: 100%; /* Tinggi elemen sesuai dengan tinggi viewport */
            width: 100%;
            height: 100vh;
        
        }

        .card {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            margin: 20px;
        }
.container {

}


</style>
@foreach ($kegiatan as $item)
    <div class="card">
        <h3>{{ $item->nama_kegiatan }}</h3>
        <p>{{ $item->deskripsi }}</p>
        <p>Hari: {{ implode(', ', $item->hari) }}</p>
        <p>Waktu: {{ $item->waktu }}</p>
        <p>Kategori: {{ $item->kategori }}</p>

        <a href="{{ route('kegiatan.edit', $item->id) }}">Edit</a>
        <form action="{{ route('kegiatan.destroy', $item->id) }}" method="POST" style="display:inline">
            @csrf @method('DELETE')
            <button type="submit">Hapus</button>
        </form>
    </div>
@endforeach
