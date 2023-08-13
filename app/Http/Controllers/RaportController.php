<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Raport;
use App\Models\User; // Import model User
use App\Models\DataUjian;
use App\Models\Ekskul;
use Illuminate\Support\Facades\Auth;

class RaportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $user = Auth::user();

    // Check if the user has a kelas and then get the name_kelas
    $name_kelas = $user->kelas->name_kelas ?? '';

    // Fetch the data based on the name_kelas
    $siswaUsers = User::where('role', 'siswa')
                      ->whereHas('kelas', function ($query) use ($name_kelas) {
                          $query->where('name_kelas', $name_kelas);
                      })
                      ->with('kelas')
                      ->get();

            return view('raport.index', compact('siswaUsers'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        // $siswa = User::findOrFail($id);
        // $siswa = User::with('kelas', 'sekolah', 'dataUjian')->findOrFail($id);
        // return view('raport.show', compact('siswa'));
        $siswa = User::with('kelas', 'sekolah', 'dataUjian')->findOrFail($id);
        $nilaiUjian = DataUjian::where('id_user', $id)->get();
        // dd($nilaiUjian);
        // return view('raport.show', compact('siswa', 'nilaiUjian'));
        $dataAnggotaEkskul = Ekskul::where('id_user', $id)->get();
        // dd($dataAnggotaEkskul);

    return view('raport.show', compact('siswa', 'nilaiUjian', 'dataAnggotaEkskul'));
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
        //
    }
}
