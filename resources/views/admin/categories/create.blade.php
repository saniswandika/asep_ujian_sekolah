<!-- Create Category -->
<div class="modal fade" id="createCategories" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title btn btn-primary" id="staticBackdropLabel">
                <i class="bi bi-folder-plus fa-1x"></i>
               Create Category Pelajaran
              </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="/categories-pelajaran/store" method="post">
                @csrf
                <div class="form-group m-3" >
                    <label for="id_sekolah_asal" class="pb-2  fs-5"><i class="bi bi-building"></i> Sekolah</label>
                    <select class="form-select form-select-lg  py-2" name="id_sekolah_asal" id="id_sekolah_asal">
                        @if(Auth::user()->role == 'admin' && Auth::user()->sekolah->name_sekolah)
                            <option class="fw-bold" value="{{ Auth::user()->sekolah->id }}">{{ Auth::user()->sekolah->name_sekolah }}</option>
                        @endif
                    </select>
                </div>
            <div class="m-3">
                <label for="name_category" class="pb-2 fw-bold"><i class="bi bi-bookmarks-fill"></i> {{ __('Category Pelajaran') }}</label>
                <input type="text" class="form-control" placeholder="Name Category Pelajaran" name="name_category" value="{{ old('name_category') }}" required>
            </div>
            <div class="m-3">
                <label for="kkm" class="pb-2 fw-bold"><i class="bi bi-bookmarks"></i> KKM</label>
                <input type="number" class="form-control" placeholder="KKM" name="kkm" value="{{ old('kkm') }}">
            </div>
            <div class="m-3">
                <label for="status" class="pb-2 fw-bold"><i class="bi bi-check-circle"></i> {{ __('Status') }}</label>
                <select class="form-select form-select-lg py-2" name="status" id="status" required>
                    <option value="wajib">Wajib</option>
                    <option value="pilihan">Pilihan</option>
                    <option value="muatan lokal">Muatan Lokal</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary fs-5 shadow"><i class="bi bi-check-circle"></i> SIMPAN</button>
            <button type="reset" class="btn btn-warning fs-5 shadow fst-italic fw-bold"><i class="bi bi-info-circle-fill"></i> RESET</button>
        </div>
        </form>
    </div>
    </div>
</div>
