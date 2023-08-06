@extends('layouts.appAdmin')
@section('title', 'Tugas')
@section('siswaAdmin')

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between ">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="/home">{{ __("Dashboard") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("Tugas") }}</li>
            </ol>
          </nav>
    </div>
</div>

<div class="row">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="font-weight-bold text-primary">DataTable </h6>
        </div>
        <div class="m-3">
            @if(Auth::user()->role == 'guru')
            <a href="{{ route('tugas.create') }}" class="btn btn-primary m-1 p-3 shadow">
                <i class="bi bi-folder-plus fa-1x"></i>
                Tambah Tugas
            </a>
        @endif
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>File Pengerjaan Tugas Siswa</th>
                                <th>File Tugas Guru</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($tugas as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->file_siswa }}</td>
                                    <td>{{ $item->file_guru }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td>
                                        @if (Auth::user()->role === 'siswa')
                                        <a class="btn btn-info btn-sm" href="{{ url('/data_file/'.$item->file_guru) }}">Download Tugas</a>
                                        <a href="{{ route('tugas.edit', $item->id) }}" class="btn btn-info btn-sm">Kirim Tugas</a>
                                        @endif
                                        @if (Auth::user()->role === 'guru')
                                        <a class="btn btn-info btn-sm" href="{{ url('/data_file/'.$item->file_siswa) }}">Download Tugas Siswa</a>
                                        <a href="{{ route('tugas.edit', $item->id) }}" class="btn btn-info btn-sm">Edit</a>
                                        <form action="{{ route('tugas.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">Delete</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
