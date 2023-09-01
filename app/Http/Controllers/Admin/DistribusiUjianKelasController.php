<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CategoryUjian;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DistribusiUjianKelas;
use Illuminate\Support\Facades\Auth;

class DistribusiUjianKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $DisujianKelases = DistribusiUjianKelas::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('kelas')->with('category')->with('categoryUjian')->get();
        // $DisujianKelases = DistribusiUjianKelas::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('kelas')->with('category')->with('categoryUjian')->get();
        // $kelas = Kelas::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_kelas', 'id')->all();
        // $sekolahs = Sekolah::pluck('name_sekolah', 'id')->all();
        // $categori = Category::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_category', 'id')->all();
        // $categoryUjians = CategoryUjian::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_category_ujian', 'id')->all();
        // $DisujianKelasCount = DistribusiUjianKelas::where('id_sekolah_asal', Auth::user()->sekolah_asal)->count();
        // dd($DisujianKelases);
        // return view('admin.distribusiUjianKelas.index', compact('DisujianKelases','kelas','sekolahs','categori','categoryUjians','DisujianKelasCount'));
        $user = Auth::user();

        // Periksa apakah pengguna adalah admin
        if ($user->role === 'admin') {
            // Jika pengguna adalah admin, ambil semua data distribusi ujian
            $DisujianKelases = DistribusiUjianKelas::with('kelas')
                ->with('category')
                ->with('categoryUjian')
                ->get();
        } else {
            // Jika pengguna bukan admin, lanjutkan dengan filter berdasarkan kelas dan kategori
            $userCategory = $user->category;
            $userKelas = $user->kelas;
            $userKelas = DB::table('user_classes')
            ->join('kelas', 'kelas.id', '=', 'user_classes.class_id')
            ->where('user_classes.user_id', $user->id)->get();
            // dd($userCategory);

            // Pastikan category dan kelas pengguna telah di-set sebelumnya
            if (!$userCategory || !$userKelas) {
                return response()->json(['message' => 'Anda belum memiliki kelas atau category yang ditetapkan.']);
            }

            // Ambil data berdasarkan category dan kelas pengguna yang saat ini diautentikasi
            foreach ($userKelas as $key => $value) {
                $DisujianKelases = DistribusiUjianKelas::where('id_category', $userCategory->id)
                    ->where('id_kelas', $value->class_id)
                    ->with('kelas')
                    ->with('category')
                    ->with('categoryUjian')
                    ->get();
            }
          
            // dd($DisujianKelases);
        }

        // Ambil data tambahan berdasarkan category dan kelas pengguna yang saat ini diautentikasi atau sesuai dengan peran admin
        $kelas = Kelas::pluck('name_kelas', 'id')->all();
        $categori = Category::pluck('name_category', 'id')->all();
        $categoryUjians = CategoryUjian::pluck('name_category_ujian', 'id')->all();
        $DisujianKelasCount = DistribusiUjianKelas::count();

        return view('admin.distribusiUjianKelas.index', compact('DisujianKelases', 'kelas', 'categori', 'categoryUjians', 'DisujianKelasCount'));
    }

    public function indexDistribusiUjianKelas()
    {
        
        $DisujianKelases = DistribusiUjianKelas::where('id_sekolah_asal', Auth::user()->sekolah_asal)->where('id_kelas', Auth::user()->id_kelas)->with('category')->with('categoryUjian')->get();
        // dd($DisujianKelases);    
        $categori = Category::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_category', 'id')->all();
        $categoryUjians = CategoryUjian::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_category_ujian', 'id')->all();
        $sekolahs = Sekolah::pluck('name_sekolah', 'id')->all();
        return view('ujianSekolah.index', compact('DisujianKelases','categori','sekolahs','categoryUjians'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $DisujianKelas = DistribusiUjianKelas::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('kelas')->get();
        // dd($DisujianKelas);
        $kelas = Kelas::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_kelas', 'id')->all();
        return view('admin.distribusiUjianKelas.create', compact('DisujianKelas','kelas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, DistribusiUjianKelas $DisujianKelas)
    {
        $this->validate($request, [
            'id_kelas' => 'required',
            'id_sekolah_asal' => 'required',
            'id_category' => 'required',
            'id_category_ujian' => 'required',
            'status' => 'required',
        ]);

        $DisujianKelas = DB::table('distribusi_ujian_kelas')->insert([
            'id_kelas' => $request->id_kelas,
            'id_sekolah_asal' => $request->id_sekolah_asal,
            'id_category' => $request->id_category,
            'id_category_ujian' => $request->id_category_ujian,
            'status' => $request->status,
            'created_at' => now(),
        ]);

        if($DisujianKelas){
            return redirect()->route('distribusiUjianKelas.index')->with('success', 'Data berhasil ditambahkan');
        }else{
            return redirect()->route('distribusiUjianKelas.index')->with('error', 'Data gagal ditambahkan');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DistribusiUjianKelas  $distribusiUjianKelas
     * @return \Illuminate\Http\Response
     */
    public function show(DistribusiUjianKelas $distribusiUjianKelas, $id)
    {
        $DisujianKelas = DistribusiUjianKelas::with('kelas')->with('category')->with('categoryUjian')->find($id);
        $DisujianKelasCount = DistribusiUjianKelas::count();
        return view('admin.distribusiUjianKelas.show', compact('DisujianKelas','DisujianKelasCount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DistribusiUjianKelas  $distribusiUjianKelas
     * @return \Illuminate\Http\Response
     */
    public function edit(DistribusiUjianKelas $distribusiUjianKelas, $id)
    {
        $DisujianKelas = DistribusiUjianKelas::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('kelas')->with('category')->with('categoryUjian')->find($id);
        $kelas = Kelas::pluck('name_kelas', 'id')->all();
        $categori = Category::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_category', 'id')->all();
        $categoryUjians = CategoryUjian::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_category_ujian', 'id')->all();
        return view('admin.distribusiUjianKelas.edit', compact('DisujianKelas','kelas','categori','categoryUjians'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DistribusiUjianKelas  $distribusiUjianKelas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DistribusiUjianKelas $distribusiUjianKelas)
    {
        $this->validate($request, [
            'id_kelas' => 'required',
            'id_sekolah_asal' => 'required',
            'id_category' => 'required',
            'id_category_ujian' => 'required',
            'status' => 'required',
        ]);

        $DisujianKelas = DB::table('distribusi_ujian_kelas')->where('id', $request->id)->update([
            'id_kelas' => $request->id_kelas,
            'id_sekolah_asal' => $request->id_sekolah_asal,
            'id_category' => $request->id_category,
            'id_category_ujian' => $request->id_category_ujian,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        if($DisujianKelas){
            return redirect()->route('distribusiUjianKelas.index')->with('success', 'Data berhasil diubah');
        }else{
            return redirect()->route('distribusiUjianKelas.index')->with('error', 'Data gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DistribusiUjianKelas  $distribusiUjianKelas
     * @return \Illuminate\Http\Response
     */

     public function status($id)
     {
         $data = DistribusiUjianKelas::where('id', $id)->first();

         $status_sekarang = $data->status;

         if($status_sekarang == 1)
         {
            DistribusiUjianKelas::where('id', $id)->update([
                'status' => 0,
            ]);
         }else {
            DistribusiUjianKelas::where('id', $id)->update([
                'status' => 1,
            ]);
         }

         if($status_sekarang == 1)
         {
            alert()->toast('Status berhasil diubah menjadi '. 'Tidak Aktif', 'success');

         } else{
            alert()->toast('Status berhasil diubah menjadi '. 'Aktif', 'success',);
         }

            return redirect()->route('distribusiUjianKelas.index');
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DistribusiUjianKelas  $distribusiUjianKelas
     * @return \Illuminate\Http\Response
     */
    public function destroy(DistribusiUjianKelas $distribusiUjianKelas, $id)
    {
        DB::table('distribusi_ujian_kelas')->where('id', $id)->delete();
        return redirect()->route('distribusiUjianKelas.index')->with('success', 'Data berhasil dihapus');
    }


}
