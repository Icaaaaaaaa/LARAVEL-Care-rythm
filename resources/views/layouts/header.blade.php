<!-- resources/views/layouts/header.blade.php -->
<div class="top-bar px-5">
    <div class="d-flex align-items-end justify-content-between">
        <!-- logo -->
        <div class="logo py-3">
            <a href="{{ url('/') }}">
                <img src="{{ asset('image/logo.jpg') }}" alt="Logo" style="height: 75px; width: auto;">
            </a>
        </div>
        <!-- navbar -->
        <nav class="navbar navbar-expand-lg navbar-light d-none d-lg-block py-0">
            <ul class="navbar-nav d-flex gap-3 px-0 my-0">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Halaman Utama</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Jadwal</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/kegiatann') }}">Kegiatan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/pencapaian') }}">Pencapaian</a></li>
            </ul>
        </nav>
        <!-- profil -->
        <div class="profil d-flex align-self-center py-3">
            <div class="nama-profil d-none d-md-block pe-3">
                <h3>Contoh Nama</h3>deskripsi
            </div>
            <div class="dropdown-center">
                <a data-bs-toggle="dropdown">
                    <div class="rounded-circle overflow-hidden" style="width: 75px; height: 75px;">
                        <img src="{{ asset('image/TesProfil.png') }}" alt="foto">
                    </div>
                </a>
                <ul class="dropdown-menu custom-position">
                    <li><a class="dropdown-item" href="{{ url('/profil') }}">Profil</a></li>
                    <li><a class="dropdown-item" href="{{ url('/pencapaian') }}">Pencapaian</a></li>
                    <li class="d-block d-md-none"><a class="dropdown-item" href="#">Teman</a></li>
                    <li class="d-block d-lg-none"><a class="dropdown-item" href="#">Jadwal</a></li>
                    <li class="d-block d-lg-none"><a class="dropdown-item" href="#">Kegiatan</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<hr>
