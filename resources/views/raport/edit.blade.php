@extends('layouts.appAdmin')
@section('title', 'Edit Siswa')
@section('siswaAdmin')

<div class="container-fluid">
    <!-- Page Heading -->
    <!-- Add breadcrumbs and page heading as needed -->

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    Edit Kelas Siswa
                </div>
                <div class="card-body">
                    <form action="{{ route('raport.update', $siswa->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name_kelas">Kelas</label>
                            <input type="text" name="name_kelas" class="form-control" value="{{ $siswa->kelas->name_kelas ?? '' }}" readonly>
                        </div>

                        @foreach ($siswa->ujianSekolah as $ujian)
                            <div class="form-group">
                                <label for="deskripsi_{{ $ujian->id }}">{{ $ujian->category_pelajaran->name_category }}</label>
                                {{-- <textarea name="deskripsi_{{ $ujian->id }}" class="form-control">{{ $siswa->raportByUjian($ujian->id)->deskripsi }}</textarea> --}}
                                <textarea name="deskripsi_{{ $ujian->id }}" class="form-control">{{ $siswa->raportByUjian($ujian->id)->deskripsi }}</textarea>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
