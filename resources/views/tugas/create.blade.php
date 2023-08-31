@extends('layouts.appadmin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Tugas</div>
                <div class="card-body">
                    {{-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}
                    <form action="{{ route('tugas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="role" value="{{ Auth::user()->role }}">
                        @if (Auth::user()->role === 'siswa')
                        <div class="form-group">
                            <label for="file_siswa">File Siswa</label>
                            <input type="file" class="form-control" name="file_siswa" required>
                        </div>
                        @endif
                        @if (Auth::user()->role === 'guru')
                            <div class="form-group">
                                <label for="file_guru">File Guru</label>
                                <input type="file" class="form-control" name="file_guru" required>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" name="keterangan" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="id_kelas">Kelas</label>
                            <select class="form-control js-example-tags" data-style="btn-success" name="id_kelas[]" id="id_kelas" multiple="multiple" @readonly(true)>
                                @forelse($kelas as $pengajares)
                                    <option value="{{ $pengajares->id }}" selected>{{ $pengajares->name_kelas }}</option>
                                @empty
                                    <option value="">No Data Kelas</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_category">Category</label>
                            <select class="form-control" name="id_category" required>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a class="btn btn-primary {{ Request::is('home')? "active":"" }}" aria-current="page" href="{{ url('/tugas') }}">
                            {{ config('Home', 'back') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
