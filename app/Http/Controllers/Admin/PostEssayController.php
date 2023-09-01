<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sekolah;
use App\Models\Category;
use App\Models\PostEssay;
use Illuminate\Http\Request;
use App\Imports\PostEssayImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CategoryUjian;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PostEssayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Auth::user()->category);
        // $postsEssays = PostEssay::where('id_sekolah_asal', Auth::user()->sekolah_asal)->where('id_category', Auth::user()->category->id)->with('category_pelajaran')->get();
        $user = Auth::user();
        
        $postsEssays = DB::table('post_essays')
            ->join('categories', 'categories.id', '=', 'post_essays.id_category')
            ->join('category_ujians', 'category_ujians.id', '=', 'post_essays.id_category_ujian')
            ->join('kelas', 'kelas.id', '=', 'post_essays.id_kelas')
            // ->join('user_classes', 'user_classes.user_id', '=', 'post_essays.id_user')
            // ->where('user_classes.user_id', Auth::user()->id)
            ->where('post_essays.id_sekolah_asal', Auth::user()->sekolah_asal)
            ->where('post_essays.id_user', $user->id)
            // ->where('categories.id', $user->id)
            ->select('post_essays.*',
                    'kelas.name_kelas',
                    'categories.id as id_category',
                    'categories.name_category as name_category',
                    'category_ujians.id as id_category_ujian',
                    'category_ujians.name_category_ujian')->get();
        // dd($postsEssays);
        $categori = Category::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_category', 'id')->all();
        $sekolahs = Sekolah::pluck('name_sekolah', 'id')->all();
        $postEssayCount = PostEssay::where('id_sekolah_asal', Auth::user()->sekolah_asal)->count();
        $CategoryUjian = CategoryUjian::all();
        $kelas = DB::table('user_classes')
        ->join('kelas', 'kelas.id', '=', 'user_classes.class_id')
        ->where('user_classes.user_id', $user->id)->get();
        return view('admin.postsEssay.index', compact('postsEssays','sekolahs','categori','postEssayCount','CategoryUjian','kelas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_sekolah_asal' => 'required',
            'id_category' => 'required',
            'soal_ujian_essay' => 'required',
            'jawaban_essay' => 'required',
            'id_category_ujian' => 'required',
            'id_kelas' => 'required'

        ]);
        // dd($request->all());
        $user = Auth::user();
        $id_kelas_array = $request->input('id_kelas'); // Array ID kelas
        foreach ($id_kelas_array as $id_kelas) {
            $post = PostEssay::create([
                'id_user' => $user->id,
                'id_sekolah_asal' => $request->id_sekolah_asal,
                'id_category' => $request->id_category,
                'soal_ujian_essay' => $request->soal_ujian_essay,
                'jawaban_essay' => $request->jawaban_essay,
                'created_at' => now(),
                'id_category_ujian' => $request->id_category_ujian,
                'id_kelas' => $id_kelas
            ]);
        }     
        $post->save();

        return redirect()->route('post-essay.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $postsEssay = PostEssay::findOrFail($id);
        $categori = Category::pluck('name_category', 'id')->all();
        $postEssayCount = PostEssay::count();
        return view('admin.postsEssay.show', compact('postsEssay','categori','postEssayCount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $postsEssay = PostEssay::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('')->find($id);
        $postsEssay = DB::table('post_essays')
        ->join('categories', 'categories.id', '=', 'post_essays.id_category')
        ->join('category_ujians', 'category_ujians.id', '=', 'post_essays.id_category_ujian')
        ->join('user_classes', 'user_classes.user_id', '=', 'post_essays.id_user')
        ->where('post_essays.id_sekolah_asal', Auth::user()->sekolah_asal)
        ->where('user_classes.user_id', Auth::user()->id)
        // ->where('post_essays.id_sekolah_asal', Auth::user()->sekolah_asal)
        ->where('post_essays.id', $id)
        ->select('post_essays.*',
                'categories.id as id_category',
                'categories.name_category as name_category',
                'category_ujians.id as id_category_ujian',
                'category_ujians.name_category_ujian')->first();
        // dd($postsEssay);
        $categori = Category::where('id_sekolah_asal', Auth::user()->sekolah_asal)->get();
        // dd($categori);
        $CategoryUjian = CategoryUjian::all();
        // dd($categori);
        return view('admin.postsEssay.edit', compact('postsEssay','categori','CategoryUjian'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        PostEssay::where('id', $request->id)->update([
            'id_sekolah_asal' => $request->id_sekolah_asal,
            'id_category' => $request->id_category,
            'soal_ujian_essay' => $request->soal_ujian_essay,
            'jawaban_essay' => $request->jawaban_essay,
            'updated_at' => now(),
            'id_category_ujian' => $request->id_category_ujian

        ]);

        return redirect()->route('post-essay.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('post_essays')->where('id', $id)->delete();
        return redirect()->route('post-essay.index')->with('success', 'Data berhasil dihapus');
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        PostEssay::whereIn('id',explode(',',$ids))->delete();
        $messages = ['success', 'Delete Post successfully!'];
        return response()->json([
            'success' => $messages,
        ]);
    }

    public function importPostsEssay(Request $request){
        //melakukan import file
        Excel::import(new PostEssayImport, request()->file('file'));
        //jika berhasil kembali ke halaman sebelumnya
        return back()->with('success', 'Data berhasil diimport');
    }

}
