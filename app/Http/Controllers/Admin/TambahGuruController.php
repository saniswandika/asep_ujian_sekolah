<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\Kelas;
use App\Models\Category;
use App\Imports\PostImport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\guru_kela;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class TambahGuruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $guruAdmins = User::all();
        $guruAdmins = User::with('kelas')->where('sekolah_asal', Auth::user()->sekolah_asal)->where('role', 'guru')->orderBy('id', 'desc')->get(); // Menampilkan data terbaru
        
        $sekolahs = Sekolah::pluck('name_sekolah', 'id')->all();
        $kelas = Kelas::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_kelas', 'id')->all();
        $categori = Category::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_category', 'id')->all();
        $guruAdminCount = User::where('sekolah_asal', Auth::user()->sekolah_asal)->where('role', 'guru')->count();
        $guruMengajarKelas = DB::table('users')
        ->join('user_classes', 'user_classes.user_id', '=', 'users.id')
        ->join('kelas', 'kelas.id', '=', 'user_classes.class_id')
        ->where('users.role', 'guru') // Hanya guru
        ->orderBy('users.name')
        ->select('users.id', 'users.name as guru_name', 'kelas.name_kelas')
        ->get();
    
        // Mengelompokkan kelas berdasarkan guru
        $kelasByGuru = [];
        foreach ($guruMengajarKelas as $kelasData) {
            $guruName = $kelasData->guru_name;
            if (!isset($kelasByGuru[$guruName])) {
                $kelasByGuru[$guruName] = [];
            }
            $kelasByGuru[$guruName][] = $kelasData->name_kelas;
        }
        return view('admin.tambahguru.index', compact('kelasByGuru','guruAdmins','sekolahs','guruAdminCount', 'kelas','categori'));

    }

    public function listGuru()
    {
        $guruPersons = DB::table('users')
        ->select('users.*') // Ganti dengan kolom yang sesuai
        ->where('role', 'guru') // Filter berdasarkan role "guru"
        ->get();
    
        $kelas = Kelas::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_kelas', 'id')->all();
        $categori = Category::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_category', 'id')->all();
    
        return view('admin.tambahguru.print', compact('guruPersons', 'kelas', 'categori'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $guruAdmin = User::all();
        $categori = Category::pluck('name_category', 'id')->all();
        return view('admin.guru.create', compact('guruAdmin'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $guruAdmin)
    {
        // dd($request->get('kelas_id'));
        $this->validate($request, [
            'id_kelas' => 'required',
            'id_category' => 'required',
            'role' => 'required',
            'no_induk' => 'required',
            'nisn' => 'required',
            'jk' => 'required',
            // 'gambar' => 'nullable',
            'sekolah_asal' => 'required',
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

    

        $guruAdmin = new User();
        $guruAdmin->id_kelas = $request->get('id_kelas');
        $guruAdmin->id_category = $request->get('id_category');
        $guruAdmin->role = $request->get('role');
        $guruAdmin->no_induk = $request->get('no_induk');
        $guruAdmin->nisn = $request->get('nisn');
        $guruAdmin->jk = $request->get('jk');
        $guruAdmin->sekolah_asal = $request->get('sekolah_asal');
        $guruAdmin->name = $request->get('name');
        $guruAdmin->username = $request->get('username');
        $guruAdmin->password = Hash::make($request->password);
        // $guruAdmin->created_by = Auth::user()->id;
        $guruAdmin->save();
        $pengajar = $request->get('kelas_id');
        // dd($pengajar);
        foreach($pengajar as $p){
            // dd($guruAdmin);
            $guruKelas = guru_kela::insert([
                'class_id' => $p,
                'user_id' =>  $guruAdmin->id,
            ]);
        }
        if(!$guruAdmin){
            return redirect()->route('guru.index')->with('error', 'Failed to create Guru Admin!');
        } else {
            return redirect()->route('guru.index')->with('success', 'Created Guru Admin successfully!');
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
        $guruAdmin = User::findOrFail($id);
        $sekolahs = Sekolah::pluck('name_sekolah', 'id')->all();
        $categoriesCount = Category::count();
        $guruAdmins = User::with('kelas')->get();
        $guruAdminCount = User::where('role', 'guru')->count();
        return view('admin.tambahguru.show', compact('guruAdmin','guruAdminCount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $guruAdmin = User::with('kelas')->where('sekolah_asal', Auth::user()->sekolah_asal)->findOrFail($id);
        // $guruAdmin = User::with('kelas')->findOrFail($id);
        $sekolahs = Sekolah::pluck('name_sekolah', 'id')->all();
        $categori = Category::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_category', 'id')->all();
        $kelas = Kelas::where('id_sekolah_asal', Auth::user()->sekolah_asal)->pluck('name_kelas', 'id')->all();
        // $pengajar = Kelas::where('name_kelas', 'id')->all();
        $pengajar = DB::table('user_classes')
        ->join('kelas', 'kelas.id', '=', 'user_classes.class_id')
        ->where('user_classes.user_id', $id)->get();
        // dd($pengajar);
        return view('admin.tambahguru.edit', compact('guruAdmin','sekolahs','kelas','categori','pengajar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $guruAdmin)
    {
        // dd($request->get('kelas_id'));
        DB::table('users')->where('id', $request->id)->update([
            'id_kelas' => $request->id_kelas,
            'id_category' => $request->id_category,
            'no_induk' => $request->no_induk = $request->username,
            'nisn' => $request->nisn,
            'jk' => $request->jk,
            'sekolah_asal' => $request->sekolah_asal,
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);
        DB::table('user_classes')
            ->where('user_id',$request->id)
            ->where('class_id', '<>', $request->get('kelas_id')) // Hapus entri kelas yang hilang
            ->delete();
        $pengajar = $request->get('kelas_id');
        // dd($pengajar);
        foreach($pengajar as $p){
            // dd($guruAdmin);
            DB::table('user_classes')->updateOrInsert(
                ['user_id' =>$request->id, 'class_id' => $p], // Update atau masukkan entri baru
                // ['user_id' =>$request->id, 'class_id' => $request->get('kelas_id')]
            );
        }
       
        // DB::table('user_classes')
        //     ->where('user_id', $request->id)
        //     ->delete();
        // DB::table('user_classes')->where('id', $request->id)->update([
        //     // 'user_id' => $request->user_id,
        //     'class_id' => $request->class_id,
        // ]);

        return redirect()->route('guru.index')->with('success', 'Updated Guru successfully!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->route('guru.index')->with('success', 'Deleted Guru successfully!');
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        User::whereIn('id',explode(',',$ids))->delete();
        $messages = ['success', 'Delete Post successfully!'];
        return response()->json([
            'success' => $messages,
        ]);
    }

    public function importGuru(Request $request){
        //melakukan import file
        Excel::import(new PostImport, request()->file('file'));
        //jika berhasil kembali ke halaman sebelumnya
        return back()->with('success', 'Import Guru successfully!');
    }

}
