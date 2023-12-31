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
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Buat Materi Baru</h2>
        </div>
    </div>
</div>

<form action="{{ route('materis.store') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="form-group">
        <b>File Materi</b><br/>
        <input type="file" name="file">
    </div>

    <div class="form-group">
        <b>Keterangan</b>
        <textarea class="form-control" name="keterangan"></textarea>
    </div>

    <div class="form-group">
        <label for="id_kelas">Kelas:</label>
        {{-- @foreach ($pengajar as $item) --}}
        <select class="form-control js-example-tags" data-style="btn-success" name="id_kelas[]" id="id_kelas" multiple="multiple" @readonly(true)>
            @forelse($kelas as $pengajares)
                <option value="{{ $pengajares->id }}" selected>{{ $pengajares->name_kelas }}</option>
            @empty
                <option value="">No Data Kelas</option>
            @endforelse
        </select>
            {{-- <input type="text" class="form-control" value="
            @foreach ($pengajar as $item)
                {{ $item->name_kelas }},{{ $item->name_kelas }}
            @endforeach" 
            readonly>
            <input type="hidden" name="id_kelas" value="
            @foreach ($pengajar as $item)
                {{ $item->name_kelas }},{{ $item->name_kelas }}
            @endforeach"> --}}
    </div>

    <div class="form-group">
        <label for="id_category">Kategori:</label>
        <select class="form-control" id="id_category" name="id_category">
            @foreach($categories as $name)
                <option value="{{ $name->id }}">{{ $name->name_category }}</option>
            @endforeach
        </select>
    </div>

    <input type="submit" value="Upload" class="btn btn-primary">
    <a class="btn btn-primary" href="{{ route('materis.index') }}"> Back</a>
</form>


@endsection

