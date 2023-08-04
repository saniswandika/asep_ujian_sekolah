@extends('layouts.appAdmin')
@section('title', 'Materi')
@section('siswaAdmin')

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between ">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="/home">{{ __("Dashboard") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("Materi") }}</li>
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
            <a href="{{ route('materi.create') }}" class="btn btn-primary m-1 p-3 shadow">
                <i class="bi bi-folder-plus fa-1x"></i>
                Create Materi
            </a>
        @endif
        {{-- <button class="btn btn-danger  m-1 p-3 shadow delete_all" data-url="{{ url('/guruDeleteAll') }}">
            <i class="bi bi-trash-fill"></i>
            Delete All Selected
        </button> --}}
    </div>
    <div class="card-body">
        <div class="table-responsive ">
            <table class="table table-bordered" id="example" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        {{-- <th width="5%" class="text-center">
                            <input type="checkbox" class="p-5" id="master" />
                        </th> --}}
                        <th width="1%">No</th>
                        <th width="1%">File</th>
                        <th>Keterangan</th>
                        <th>Kelas</th>
                        <th class="text-center" width="">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $no = 1;
                @endphp
                @foreach($materi as $g)
                <tr id="tr_{{ $g->id }}">
                    {{-- <td class="text-center">
                        <input type="checkbox" class="sub_chk" data-id="{{$g->id }}">
                    </td> --}}
                    <td class="fw-bold">{{ $no++ }}</td>
                    <td class="fw-bold bg-primary text-white text-capitalize">{{ $g->file }}</td>
                    <td class="text-capitalize">{{$g->keterangan}}</td>
                    <td class="text-capitalize">
                        @if ($g->kelas) <!-- Check if "kelas" relationship exists and is not null -->
                            {{ $g->kelas->name_kelas }}
                        @else
                            N/A <!-- Display default value if "kelas" relationship is null or does not exist -->
                        @endif
                    </td>
                    <td class="text-center">
                        {{-- <a href="/guru-show-{{ $guru->id }}" class="btn btn-info text-white p-2 shadow-sm m-2 show-confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="Show"> <i class="bi bi-eye-fill"></i></a>
                        <a href="/guru-edit-{{ $guru->id }}" class="btn btn-warning text-white p-2 shadow-sm m-2 edit-confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"> <i class="bi bi-pencil-square"></i></a>
                        <a href="/guru/delete/{{ $guru->id }}" class="btn btn-danger text-white p-2 shadow-sm m-2 delete-confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"> <i class="bi bi-trash-fill"></i></a> --}}
                        <a class="btn btn-info btn-sm" href="{{ url('/data_file/'.$g->file) }}">Download</a>
                        @if(Auth::user()->role == 'guru')
                            <form action="{{ route('materi.destroy', $g->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        @endif
                        </td>
                    </tr>
                    {{-- @endif --}}
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
