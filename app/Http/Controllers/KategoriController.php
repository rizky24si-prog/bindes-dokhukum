<?php

namespace App\Http\Controllers;

use App\Models\KategoriDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = KategoriDokumen::withCount('dokumenHukum');
    
    // Search
    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nama', 'like', '%' . $search . '%')
              ->orWhere('deskripsi', 'like', '%' . $search . '%');
        });
    }
    
    // Sort
    $sort = $request->get('sort', 'nama');
    $order = $request->get('order', 'asc');
    
    // Handle sorting by dokumen count
    if ($sort == 'dokumen_count') {
        $query->orderBy('dokumen_hukum_count', $order);
    } else {
        $query->orderBy($sort, $order);
    }
    
    $dataKategori = $query->paginate(10)->withQueryString();
    
    return view('pages.admin.kategori.index', compact('dataKategori'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kategori,nama',
            'deskripsi' => 'nullable|string'
        ]);

        try {
            KategoriDokumen::create([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi
            ]);

            return redirect()->route('kategori.index')
                ->with('success', 'Kategori berhasil ditambahkan');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kategori = KategoriDokumen::findOrFail($id);
        
        return view('pages.admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kategori,nama,' . $id . ',kategori_id',
            'deskripsi' => 'nullable|string'
        ]);

        try {
            $kategori = KategoriDokumen::findOrFail($id);
            $kategori->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi
            ]);

            return redirect()->route('kategori.index')
                ->with('success', 'Kategori berhasil diperbarui');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $kategori = KategoriDokumen::findOrFail($id);
            
            // Cek apakah kategori digunakan di dokumen
            if ($kategori->dokumenHukum()->count() > 0) {
                return redirect()->route('kategori.index')
                    ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh dokumen');
            }
            
            $kategori->delete();
            
            DB::commit();
            
            return redirect()->route('kategori.index')
                ->with('success', 'Kategori berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('kategori.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}