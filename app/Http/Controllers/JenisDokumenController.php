<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisDokumen;

class JenisDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dataJenisDokumen'] = JenisDokumen::all();
		return view('admin.jenisdokumen.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jenisdokumen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'nama_jenis' => 'required|string|max:255|unique:jenis_dokumen,nama_jenis',
        'deskripsi' => 'required|string',
    ]);

    JenisDokumen::create($validated);

    return redirect()->route('jenis-dokumen.index')
        ->with('success', 'Jenis dokumen berhasil ditambahkan!');
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
        $data['dataJenisDokumen'] = JenisDokumen::findOrFail($id);
        return view('admin.jenisdokumen.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validated = $request->validate([
        'nama_jenis' => 'required|string|max:255|unique:jenis_dokumen,nama_jenis,' . $id . ',jenis_id',
        'deskripsi' => 'required|string',
    ]);

    $jenisDokumen = JenisDokumen::findOrFail($id);

    $jenisDokumen->update($validated);

    return redirect()->route('jenis-dokumen.index')
        ->with('success', 'Jenis dokumen berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $data = JenisDokumen::findOrFail($id);
    $data->delete();

    return redirect()->route('jenis-dokumen.index')
        ->with('success', 'Jenis dokumen berhasil dihapus!');
}

}
