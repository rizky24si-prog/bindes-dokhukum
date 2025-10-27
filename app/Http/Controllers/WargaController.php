<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dataWarga'] = Warga::all();
		return view('admin.warga.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.warga.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'no_ktp' => 'required|string|max:20|unique:warga,no_ktp',
        'nama' => 'required|string|max:100',
        'jenis_kelamin' => 'required|in:L,P',
        'agama' => 'required|string|max:50',
        'pekerjaan' => 'required|string|max:100',
        'telp' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:100',
    ]);

    Warga::create($validated);

    return redirect()->route('warga.index')
        ->with('success', 'Data warga berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['dataWarga'] = Warga::findOrFail($id);
        return view('admin.warga.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validated = $request->validate([
        'no_ktp' => 'required|string|max:20|unique:warga,no_ktp,' . $id . ',warga_id',
        'nama' => 'required|string|max:100',
        'jenis_kelamin' => 'required|in:L,P',
        'agama' => 'nullable|string|max:50',
        'pekerjaan' => 'nullable|string|max:100',
        'telp' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:100',
    ]);

    $warga = Warga::findOrFail($id);

    $warga->update($validated);

    return redirect()->route('warga.index')
        ->with('success', 'Data warga berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $data = Warga::findOrFail($id);
    $data->delete();

    return redirect()->route('warga.index')
        ->with('success', 'Data warga berhasil dihapus!');
}

}
