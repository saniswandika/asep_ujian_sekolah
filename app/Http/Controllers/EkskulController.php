<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ekskul;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EkskulController extends Controller
{
    public function index()
    {
        $ekskuls = Ekskul::all();

        return view('ekskul.index', compact('ekskuls'));
    }

    public function create()
    {
        $users = User::all(); // Ambil semua data user
    return view('ekskul.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'predikat' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        Ekskul::create($validatedData);

        return redirect()->route('ekskul.index')->with('success', 'Ekskul berhasil ditambahkan.');
    }

    public function edit(Ekskul $ekskul)
    {
        return view('ekskul.edit', compact('ekskul'));
    }

    public function update(Request $request, Ekskul $ekskul)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'predikat' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $ekskul->update($validatedData);

        return redirect()->route('ekskul.index')->with('success', 'Ekskul berhasil diperbarui.');
    }

    public function destroy(Ekskul $ekskul)
    {
        DB::table("ekskuls")->where('id', $id)->delete();

        return redirect()->route('ekskul.index')->with('success', 'Ekskul berhasil dihapus.');
    }


}
