@extends('layouts.appAdmin')
@section('title', 'Edit Ekstrakulikuler')
@section('guruAdmin')

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">{{ __("Dashboard") }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ekskul.index') }}">{{ __("Ekstrakulikuler") }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __("Edit Ekstrakulikuler") }}</li>
            </ol>
        </nav>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="font-weight-bold text-primary">Edit Ekstrakulikuler</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('ekskul.update', $ekskul->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama">Nama Ekstrakulikuler</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $ekskul->nama }}" required>
                        </div>
                        <div class="form-group">
                            <label for="predikat">Predikat</label>
                            <input type="text" class="form-control" id="predikat" name="predikat" value="{{ $ekskul->predikat }}" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan">{{ $ekskul->keterangan }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="user_id">Pilih Pengguna</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $ekskul->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('ekskul.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
