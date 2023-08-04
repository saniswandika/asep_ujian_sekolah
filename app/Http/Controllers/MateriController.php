<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materis;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{

    public function proses_upload(Request $request)
{
    $this->validate($request, [
        'file' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048',
        'keterangan' => 'required',
        'id_kelas' => 'required', // Make sure id_kelas and id_category are required.
            'id_category' => 'required',
    ]);

    // menyimpan data file yang diupload ke variabel $file
    $file = $request->file('file');

    $nama_file = time() . "_" . $file->getClientOriginalName();

    // isi dengan nama folder tempat kemana file diupload
    $tujuan_upload = 'data_file';
    $file->move($tujuan_upload, $nama_file);

    Materis::create([
        'file' => $nama_file,
        'keterangan' => $request->keterangan,
    ]);

    // Redirect to the create view instead of index view
    return redirect()->route('materi.create')->with('success', 'Materi created successfully');
}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_kelas = Auth::user()->id_kelas;

    // Fetch materials based on the user's id_kelas and id_category
    $materi = Materis::where('id_kelas', $id_kelas)
    ->with('kelas') // Eager load the 'kelas' relationship
    ->get();
                    // dd($materi);
    return view('materi.index', ['materi' => $materi]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
{
    // Mengambil data kelas yang terkait dengan user
    $kelas = Auth::user()->kelas;
    // dd($kelas);
 // Mengambil data category yang terkait dengan user
 $categories = Auth::user()->categories->pluck('name_category', 'pivot_id_category');
//  dd($categories);
    // Mengambil data category yang terkait dengan user
    // $categories = Auth::user()->categories;
    // dd($categories);
    return view('materi.create', compact('kelas', 'categories'));
}


    public function upload()
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048',
            'keterangan' => 'required',
            'id_kelas' => 'required', // Make sure id_kelas and id_category are required.
            'id_category' => 'required',
        ]);

        // menyimpan data file yang diupload ke variabel $file
        $file = $request->file('file');

        $nama_file = time() . "_" . $file->getClientOriginalName();

        // isi dengan nama folder tempat kemana file diupload
        $tujuan_upload = 'data_file';
        $file->move($tujuan_upload, $nama_file);

        Materis::create([
            'file' => $nama_file,
            'keterangan' => $request->keterangan,
        ]);

        // Redirect to the create view instead of index view
        return redirect()->route('materi.create')->with('success', 'Materi created successfully');
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
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048',
            'keterangan' => 'required',
        ]);

        $file = $request->file('file');
        $nama_file = time() . "_" . $file->getClientOriginalName();
        $tujuan_upload = 'data_file';
        $file->move($tujuan_upload, $nama_file);

        $id_user = Auth::id();

        Materis::create([
            'file' => $nama_file,
            'keterangan' => $request->keterangan,
            'id_kelas' => $request->input('id_kelas'), // Gunakan $request->input('nama_field') untuk mengambil nilai dari input field.
        'id_category' => $request->input('id_category'),
            'id_user' => $id_user, // Assign ID user yang sedang login
        ]);

        return redirect()->route('materis.index')->with('success', 'Materi created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("materis")->where('id', $id)->delete();
        return redirect()->route('materis.index')->with('success', 'Materi deleted successfully');
    }
}
