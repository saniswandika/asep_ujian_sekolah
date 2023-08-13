@extends('layouts.appAdmin')
@section('title', 'Materi')
@section('guruAdmin')

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between ">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="/home">{{ __("Dashboard") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("Ekstrakulikuler") }}</li>
            </ol>
          </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Buat Ekstrakulikuler Baru</h2>
        </div>
    </div>
</div>
            <form action="{{ route('ekskul.store') }}" method="post">
                @csrf
                <div class="m-3">
                    <label for="name" class="pb-2 fw-bold fs-5"><i class="bi bi-person"></i> {{ __('Nama Ekstrakulikuler') }}</label>
                    <input type="text" class="form-control text-capitalize @error('nama') is-invalid @enderror" placeholder="Nama Ekstrakulikuler" name="nama" value="{{ old('nama') }}">
                    @error('nama')
                        <span class="invalid-feedback" role="alert">
                            <strong>Isi Form Nama Ekstrakulikuler</strong>
                        </span>
                    @enderror
                </div>
                <div class="m-3">
                    <label for="predikat" class="pb-2 fw-bold fs-5"><i class="bi bi-card-text"></i> {{ __('Predikat') }}</label>
                    <input type="text" class="form-control" placeholder="Predikat" name="predikat" value="{{ old('predikat') }}">
                </div>
                <div class="m-3">
                    <label for="keterangan" class="pb-2 fw-bold fs-5"><i class="bi bi-card-text"></i> {{ __('Keterangan') }}</label>
                    <input type="text" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Keterangan" name="keterangan" value="{{ old('keterangan') }}">
                    @error('keterangan')
                        <span class="invalid-feedback" role="alert">
                            <strong>Isi Form Keterangan</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="user_id" class="form-label fw-bold fs-5">{{ __('Pilih User') }}</label>
                    <select class="form-select" name="user_id">
                        <option value="" disabled selected>Pilih User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

        </div>
        <div class="modal-footer ">
            <button type="submit" class="btn btn-primary fs-5 shadow"><i class="bi bi-check-circle"></i> SIMPAN</button>
            <button type="reset" class="btn btn-warning fs-5 fst-italic fw-bold shadow"><i class="bi bi-info-circle-fill"></i> Reset</button>
        </div>
        </form>
        @endsection
