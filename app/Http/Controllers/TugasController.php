<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\Kelas;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TugasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $id_user = $user->id;
        $role = $user->role;

        // Fetch tugas based on the current user's role and class
        if ($role === 'siswa') {
            $kelas = $user->kelas;
            $tugas = Tugas::where('id_kelas', $kelas->id)->get();
        } else if ($role === 'guru') {
            $kelas = $user->kelas;
            $kategori = $user->category;
            $tugas = Tugas::where('id_category', $kategori->id)->where('tugas.id_user',$user->id)->get();
        } else {
            // Handle other roles if needed
            $tugas = collect(); // Return an empty collection for other roles
        }

        return view('tugas.index', ['tugas' => $tugas]);
    }

    public function create()
    {
        // $kelas = Kelas::all();
        $id = Auth::user()->id;

        $categories = DB::table('users')
        ->join('categories', 'users.id_category', '=', 'categories.id')
        ->where('users.id', $id)
        ->get();
        $kelas = DB::table('user_classes')
        ->join('kelas', 'kelas.id', '=', 'user_classes.class_id')
        ->where('user_classes.user_id', $id)->get();
        return view('tugas.create', compact('kelas', 'categories'));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        // Tetapkan aturan validasi dengan menggunakan Rule untuk memeriksa peran
        $request->validate([
            'file_siswa' => ($request->role === 'siswa') ? 'required|file|mimes:pdf,doc,docx|max:2048' : 'nullable',
            'file_guru' => ($request->role === 'guru') ? 'required|file|mimes:pdf,doc,docx|max:2048' : 'nullable',
    //         'file_siswa' => 'required', // Coba ganti dengan 'required' untuk saat ini
    // 'file_guru' => 'required', // Coba ganti dengan 'required' untuk saat ini

            'keterangan' => 'required',
            'id_kelas' => 'required',
            'id_category' => 'required',
        ]);
       
        // Dapatkan peran pengguna saat ini
        $role = $request->role;
        // Sesuaikan nama field file berdasarkan peran pengguna
        if ($role === 'siswa' && $request->hasFile('file_siswa')) {
            $file = $request->file('file_siswa');
            $namaFile = time() . "_" . $file->getClientOriginalName();
            $tujuanUpload = 'data_file';
            $file->move($tujuanUpload, $namaFile);
        } elseif ($role === 'guru' && $request->hasFile('file_guru')) {
            $file = $request->file('file_guru');
            $namaFile = time() . "_" . $file->getClientOriginalName();
            $tujuanUpload = 'data_file';
            $file->move($tujuanUpload, $namaFile);
        } else {
            $namaFile = null; // Jika tidak ada file, set nilai null
        }

        // dd($namaFile);
        // Dapatkan id_user dari pengguna yang sedang login
        $id_user = Auth::id();

        // dd($request->all(), $namaFile, $role);
        $id_user = Auth::id();
        
        $id_kelas_array = $request->input('id_kelas'); // Array ID kelas
        foreach ($id_kelas_array as $id_kelas) { 
            Tugas::create([
                'file_siswa' => $role === 'siswa' ? $namaFile : null,
                'file_guru' => $role === 'guru' ? $namaFile : null,
                'keterangan' => $request->keterangan,
                'id_kelas' => $id_kelas,
                'id_category' => $request->id_category,
                'id_user' => $id_user, // Gunakan id_user yang didapatkan dari Auth
            ]);
        } 

        return redirect()->route('tugas.index')->with('success', 'Tugas created successfully');
    }


    public function edit($id)
    {
        $tugas = Tugas::findOrFail($id);
        // dd($tugas);
        $kelas = Kelas::all();
        $categories = Category::all();
        $user_id = auth::user()->id;
        // $kelas = DB::table('user_classes')
        // ->join('kelas', 'kelas.id', '=', 'user_classes.class_id')
        // ->where('user_classes.user_id', $user_id)->get();
        // dd($kelas);
        // // Mengambil data category yang terkait dengan user
        // // $categories = Auth::user()->categories->pluck('name_category', 'pivot_id_category');
        // $categories = DB::table('users')
        // ->join('categories', 'users.id_category', '=', 'categories.id')
        // ->where('users.id', $user_id)
        // ->get();

        return view('tugas.edit', compact('tugas', 'kelas', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'file_siswa' => 'nullable|file|mimes:pdf,doc,docx',
            'file_guru' => 'nullable|file|mimes:pdf,doc,docx',
            'keterangan' => 'required',
            'id_category' => 'required',
        ]);
    
        $tugas = Tugas::findOrFail($id);
    
        if ($request->hasFile('file_siswa')) {
            $fileSiswa = $request->file('file_siswa');
            $namaFileSiswa = time() . "_" . $fileSiswa->getClientOriginalName();
            $tujuanUpload = 'data_file';
            $fileSiswa->move($tujuanUpload, $namaFileSiswa);
            $tugas->file_siswa = $namaFileSiswa;
        }
    
        if ($request->hasFile('file_guru')) {
            $fileGuru = $request->file('file_guru');
            $namaFileGuru = time() . "_" . $fileGuru->getClientOriginalName();
            $tujuanUpload = 'data_file';
            $fileGuru->move($tujuanUpload, $namaFileGuru);
            $tugas->file_guru = $namaFileGuru;
        }
    
        $tugas->keterangan = $request->keterangan;
        $tugas->id_category = $request->id_category;
        $tugas->save();
    
        // Handle multiple id_kelas values
        $id_kelas_values = $request->id_kelas;
        foreach ($id_kelas_values as $id_kelas_value) {
            $tugas->id_kelas = $id_kelas_value;
            $tugas->save();
        }
    
        return redirect()->route('tugas.index')->with('success', 'Tugas updated successfully');
    }
    


    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();

        return redirect()->route('tugas.index')->with('success', 'Tugas deleted successfully');
    }
}

