@extends('layouts.appAdmin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Tugas</div>
                <div class="card-body">
                    <form action="{{ route('tugas.update', $tugas->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @if (Auth::user()->role === 'siswa')
                            <div class="form-group">
                                <label for="file_siswa">File Siswa</label>
                                <input type="file" class="form-control" name="file_siswa">
                            </div>
                        @endif
                        @if (Auth::user()->role === 'guru')
                            <div class="form-group">
                                <label for="file_guru">File Guru</label>
                                <input type="file" class="form-control" name="file_guru">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" name="keterangan" required>{{ $tugas->keterangan }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="id_kelas">Kelas</label>
                            <select class="form-control js-example-tags" data-style="btn-success" name="id_kelas[]" id="id_kelas" multiple="multiple" @readonly(true)>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}" {{ $tugas->id_kelas == $item->id ? 'selected' : '' }}>{{ $item->name_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_category">Category</label>
                            <select class="form-control" name="id_category" required>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" {{ $tugas->id_category == $item->id ? 'selected' : '' }}>{{ $item->name_category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
