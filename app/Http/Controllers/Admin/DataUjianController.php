<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\DataUjian;
use App\Models\PostEssay;
use App\Models\UjianSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DistribusiUjianKelas;
use App\Models\UjianSekolahEssay;
use Illuminate\Support\Facades\Auth;

class DataUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexDataUjian2()
    {
        $user_classes = DB::table('user_classes')->where('user_id', Auth::user()->id)->get();
        
        foreach ($user_classes as $user) {
            $classId = $user->class_id; // Access the class ID using the correct key
            // dd($classId);
            $dataUjian = DataUjian::whereHas('category_pelajaran', function ($query) use ($user) {
                    $query->where('name_category', '=', Auth::user()->category->name_category);
                })
                ->whereHas('kelas', function ($query) use ($classId) {
                    $query->where('id_kelas', $classId);
                })
                ->with('category_ujian')
                ->with('category_pelajaran')
                ->with('kelas')
                ->with('user')
                ->get();

            // Do something with $dataUjian for this user's class...
        }
        // dd($dataUjian);
        $dataUjianCount = $dataUjian->count();

        return view('guru.dataUjian.index', compact('dataUjian', 'dataUjianCount'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataUjian = DataUjian::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('category_ujian')
        ->with('category_pelajaran')
        // ->with('post')
        ->with('kelas')
        ->with('user')
        ->findOrFail($id);


        $ujianSekolah = UjianSekolah::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('category_ujian')
        ->with('category_pelajaran')
        ->with('post')
        ->with('kelas')
        ->with('user')
        ->get();

        $ujianSekolahEssay = UjianSekolahEssay::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('category_ujian')
        ->with('category_pelajaran')
        ->with('postEssay')
        ->with('kelas')
        ->with('user')
        ->get();

        // $ujianSekolahCount = UjianSekolah::where('id_user', Auth::user()->id)->count();
        // $ujianSekolahCount = UjianSekolah::count();
        $ujianSekolahCount = UjianSekolah::where('id_sekolah_asal', Auth::user()->sekolah_asal)->where('id_user', $id)->count();

        $dataUjianCount = DataUjian::where('id_sekolah_asal', Auth::user()->sekolah_asal)->count();
        return view('guru.dataUjian.show', compact('dataUjian','ujianSekolahEssay','ujianSekolah','dataUjianCount','ujianSekolahCount'));

    }
    /**
     * Create the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataUjian = DataUjian::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('category_ujian')
        ->with('category_pelajaran')
        // ->with('post')
        ->with('kelas')
        ->with('user')
        ->findOrFail($id);


        $ujianSekolah = UjianSekolah::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('category_ujian')
        ->with('category_pelajaran')
        ->with('post')
        ->with('kelas')
        ->with('user')
        ->get();

        $ujianSekolahEssay = UjianSekolahEssay::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('category_ujian')
        ->with('category_pelajaran')
        ->with('postEssay')
        ->with('kelas')
        ->with('user')
        ->get();


        return view('guru.dataUjian.edit-nilai', compact('dataUjian','ujianSekolahEssay','ujianSekolah'));
    }
    /**
     * Store the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $dataUjian =  DB::table('data_ujians')->where('id', $request->id)->update([
            'total_nilai' => $request->total_nilai,
            'deskripsi' => $request->deskripsi,
            'updated_at' => now(),
        ]);

        if($dataUjian){
        return redirect()->route('dataUjian.indexDataUjian')->with('success', 'Data berhasil ditambahkan');
        }else{
            return redirect()->route('dataUjian.indexDataUjian')->with('error', 'Data Nilai Gagal Diperbarui');
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $dataUjian = DataUjian::findOrFail($id);
        $dataUjian->delete();
        return redirect()->route('data-ujian.index')->with('success', 'Data Ujian berhasil dihapus');
    }
}
