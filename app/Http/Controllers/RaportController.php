<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
// use App\Models\Raport;
use App\Models\User; // Import model User
use App\Models\DataUjian;
use App\Models\Ekskul;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    // $name_kelas = $user->kelas->name_kelas ?? '';
    $namaKls = DB::table('kelas')
        ->join('users', 'users.id', '=', 'kelas.id_wali')
        // ->where('post_essays.id_sekolah_asal', Auth::user()->sekolah_asal)
        ->where('users.id', $user->id)
        ->select('kelas.name_kelas')->first();
    // dd($name_kelas);
    // Fetch the data based on the name_kelas
    $siswaUsers = User::where('role', 'siswa')
                      ->whereHas('kelas', function ($query) use ($namaKls) {
                          $query->where('name_kelas', $namaKls->name_kelas)->where('id_wali', Auth::user()->id);
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
        $siswa = User::with('kelas', 'sekolah', 'dataUjian', 'semester')->findOrFail($id);
        $nilaiUjian = DataUjian::where('id_user', $id)->get();

        $totalNilai = 0;
        $jumlahUjian = count($nilaiUjian);

        foreach ($nilaiUjian as $nilai) {
            $totalNilai += $nilai->total_nilai;
        }

        $rataRataNilai = ($jumlahUjian > 0) ? $totalNilai / $jumlahUjian : 0;

        $nilaiPerPelajaran = $nilaiUjian->groupBy('id_category_pelajaran')->map(function ($items) {
            return $items->avg('nilai');
        });

        $nilaiAmbang = 90; // Atur ambang batas sesuai kebijakan Anda
        $permataPelajaran = $nilaiPerPelajaran->filter(function ($nilai) use ($nilaiAmbang) {
            return $nilai > $nilaiAmbang;
        });

        // Mengambil data mata pelajaran dan nilai rata-ratanya
        $dataMataPelajaran = [];
        foreach ($nilaiPerPelajaran as $idPelajaran => $rataNilai) {
            $mataPelajaran = Category::find($idPelajaran); // Ganti CategoryPelajaran dengan model yang sesuai
            $dataMataPelajaran[] = [
                'mata_pelajaran' => $mataPelajaran->name_category, // Ubah sesuai field yang sesuai
                'rata_nilai' => $rataRataNilai,
            ];
        }
        // dd($dataMataPelajaran);

        // $dataMataPelajaran sekarang berisi data mata pelajaran beserta nilai rata-ratanya


        $dataAnggotaEkskul = Ekskul::where('id_user', $id)->get();
        $jumlahAnggotaEkskul = count($dataAnggotaEkskul);
        // dd($permataPelajaran);
        return view('raport.show', compact('siswa', 'nilaiUjian', 'rataRataNilai', 'jumlahAnggotaEkskul', 'dataMataPelajaran'));
        
        // return view('raport.show', compact('siswa', 'nilaiUjian', 'dataAnggotaEkskul'));
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
