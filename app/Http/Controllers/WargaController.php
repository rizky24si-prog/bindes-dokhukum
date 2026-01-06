<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Warga::query();
    
    // Search
    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nama', 'like', '%' . $search . '%')
              ->orWhere('no_ktp', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('telp', 'like', '%' . $search . '%');
        });
    }
    
    // Filter jenis kelamin
    if ($request->has('jenis_kelamin') && $request->jenis_kelamin) {
        $query->where('jenis_kelamin', $request->jenis_kelamin);
    }
    
    // Filter agama
    if ($request->has('agama') && $request->agama) {
        $query->where('agama', $request->agama);
    }
    
    // Filter pekerjaan
    if ($request->has('pekerjaan') && $request->pekerjaan) {
        $query->where('pekerjaan', 'like', '%' . $request->pekerjaan . '%');
    }
    
    // Sort
    $sort = $request->get('sort', 'nama');
    $order = $request->get('order', 'asc');
    $query->orderBy($sort, $order);
    
    $dataWarga = $query->paginate(10)->withQueryString();
    
    // Get unique values for filters
    $agamaList = Warga::select('agama')->whereNotNull('agama')->distinct()->pluck('agama');
    $pekerjaanList = Warga::select('pekerjaan')->whereNotNull('pekerjaan')->distinct()->pluck('pekerjaan');
    
    return view('pages.admin.warga.index', compact('dataWarga', 'agamaList', 'pekerjaanList'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.warga.create');
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
        return view('pages.admin.warga.edit', $data);
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
