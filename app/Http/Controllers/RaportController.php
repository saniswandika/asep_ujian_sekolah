<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Raport;
use App\Models\User; // Import model User
use App\Models\DataUjian;

class RaportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siswaUsers = User::where('role', 'siswa')->with('kelas')->get(); // Mengambil data siswa berdasarkan role_id
        return view('raport.index', compact('siswaUsers'));
        // dd($siswaUsers);
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
        return view('raport.show', compact('siswa', 'nilaiUjian'));
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