@extends('layouts.appAdmin')
@section('title', 'Siswa')
@section('siswaAdmin')

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between ">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="/home">{{ __("Dashboard") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("Raport Siswa") }}</li>
            </ol>
          </nav>
    </div>

    </div>

        <!-- DataTales Example -->
        <div class="container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NISN</th>
                        <th>Kelas</th>
                        <th>Sekolah</th>
                        <th class="text-center w-25">Action</th>
                        <!-- Tambahkan kolom lain sesuai kebutuhan -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswaUsers as $siswa)
                        <tr>
                            <td>{{ $siswa->name }}</td>
                            <td>{{ $siswa->nisn }}</td>
                            <td>{{ $siswa->kelas->name_kelas ?? '' }}</td>
                            <td>{{ $siswa->sekolah->name_sekolah ?? '' }}</td>
                            <td class="text-center">
                                <a href="{{ route('raport.show', $siswa->id) }}" class="btn btn-info text-white p-2 shadow-sm m-2 show-confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="Show dan print">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


{{-- Import Siswa --}}
    {{-- @include('admin.tambahsiswa.importsiswa') --}}

{{-- Create Siswa --}}
    {{-- @include('admin.tambahsiswa.create') --}}


@endsection
