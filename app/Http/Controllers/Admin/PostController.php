<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Sekolah;
use App\Models\Category;
use App\Imports\PostImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CategoryUjian;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        // $posts = Post::where('id_sekolah_asal', $user->sekolah_asal)
        //     ->where('id_user', $user->id)
        //     ->with('category')
        //     ->with('category_ujian')
        //     ->get();
        // foreach ($posts as $key => $post) {
        //     $kelas = kelas::where('id',$post->id_kelas)->get();
        // }
        // dd($kelas);
        
        $posts = DB::table('posts')
            ->join('categories', 'categories.id', '=', 'posts.id_category')
            ->join('category_ujians', 'category_ujians.id', '=', 'posts.id_category_ujian')
            ->join('kelas', 'kelas.id', '=', 'posts.id_kelas')
            ->where('posts.id_sekolah_asal', Auth::user()->sekolah_asal)
            ->where('posts.id_user', $user->id)
            // ->where('categories.id', $user->id)
            ->select('posts.*',
                    'kelas.name_kelas',
                    'categories.id as id_category',
                    'categories.name_category as name_category',
                    'category_ujians.id as id_category_ujian',
                    'category_ujians.name_category_ujian')->get();
        // dd($posts);
        $user = Auth::user();
        $CategoryUjian = CategoryUjian::all();
       
        $categories = $user->categories()->pluck('name_category', 'categories.id')->all();
        $sekolahs = Sekolah::pluck('name_sekolah', 'id')->all();
        $postCount = $user->posts()->count();
        $kelas = DB::table('user_classes')
        ->join('kelas', 'kelas.id', '=', 'user_classes.class_id')
        ->where('user_classes.user_id', $user->id)->get();
        return view('admin.posts.index', compact('posts', 'categories', 'sekolahs', 'postCount','kelas','CategoryUjian'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post = Post::all();
        $user = Auth::user();
       
    // $categories = $user->categories()->pluck('name_category', 'id')->all();
        $category = $user->category;
    
        return view('admin.posts.create', compact('category','post','kelas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        // dd($request->all());
        $this->validate($request, [
            'id_sekolah_asal' => 'required',
            'id_category' => 'required',
            'soal_ujian' => 'required',
            'pilihan_a' => 'required',
            'pilihan_b' => 'required',
            'pilihan_c' => 'required',
            'pilihan_d' => 'required',
            'jawaban' => 'required',
            'id_category_ujian' => 'required',
            'id_kelas' => 'required'
        ]);
        
        $user = Auth::user();
        $id_kelas_array = $request->input('id_kelas'); // Array ID kelas
        foreach ($id_kelas_array as $id_kelas) {
            $post = Post::create([
                'id_user' => $user->id,
                'id_sekolah_asal' => $request->id_sekolah_asal,
                'id_category' => $request->id_category,
                'soal_ujian' => $request->soal_ujian,
                'pilihan_a' => $request->pilihan_a,
                'pilihan_b' => $request->pilihan_b,
                'pilihan_c' => $request->pilihan_c,
                'pilihan_d' => $request->pilihan_d,
                'jawaban' => $request->jawaban,
                'id_category_ujian' => $request->id_category_ujian,
                'id_kelas' => $id_kelas,
            ]);
        }     
        $user = Auth::user();


        $post->save();

        if($post){
            return redirect()->route('posts.index')->with('success', 'Created Post successfully!');
        } else {
            return redirect()->route('posts.index')->with('error', 'Failed to create Post!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        $categoriesCount = Category::count();
        $postCount = Post::count();
        return view('admin.posts.show', compact('post','categoriesCount','postCount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $post = Post::where('id_sekolah_asal', Auth::user()->sekolah_asal)->with('category')->find($id);
        $post = Post::where('id_sekolah_asal', Auth::user()->sekolah_asal)
        // ->where('id_user', $user->id)
        ->with('category')
        ->with('category_ujian')
        ->find($id);
        // dd($post);
        $categori = Category::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_category', 'id')->all();
        return view('admin.posts.edit', compact('post', 'categori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->validate($request, [
            'id_sekolah_asal' => 'required',
            'id_category' => 'required',
            'soal_ujian' => 'required',
            'pilihan_a' => 'required',
            'pilihan_b' => 'required',
            'pilihan_c' => 'required',
            'pilihan_d' => 'required',
            'jawaban' => 'required',
            'id_category_ujian' => 'required'
        ]);

        $post = Post::find($request->id);
        $post = $post->update([
            'id_sekolah_asal' => $request->id_sekolah_asal,
            'id_category' => $request->id_category,
            'soal_ujian' => $request->soal_ujian,
            'pilihan_a' => $request->pilihan_a,
            'pilihan_b' => $request->pilihan_b,
            'pilihan_c' => $request->pilihan_c,
            'pilihan_d' => $request->pilihan_d,
            'jawaban' => $request->jawaban,
            'id_category_ujian' => $request->id_category_ujian
        ]);

        if($post){
            return redirect()->route('posts.index')->with('success', 'Updated Post successfully!');
        }else{
            return redirect()->route('posts.index')->with('error', 'Error during the update!');
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
        DB::table('posts')->where('id', $id)->delete();
        return redirect()->route('posts.index')->with('success', 'Delete Post successfully!');
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        Post::whereIn('id',explode(',',$ids))->delete();
        $messages = ['success', 'Delete Post successfully!'];
        return response()->json([
            'success' => $messages,
        ]);
    }

    public function importPosts(Request $request){
        //melakukan import file
        Excel::import(new PostImport, request()->file('file'));
        //jika berhasil kembali ke halaman sebelumnya
        return back()->with('success', 'Import Post successfully!');
    }
}
