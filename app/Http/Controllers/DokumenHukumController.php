<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumenHukum;
use App\Models\JenisDokumen;
use App\Models\KategoriDokumen;
use App\Models\Media;




class DokumenHukumController extends Controller
{
    
    public function index(Request $request)
    {
        $query = DokumenHukum::with(['jenis', 'kategori', 'media']);
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor', 'like', '%' . $search . '%')
                  ->orWhere('judul', 'like', '%' . $search . '%')
                  ->orWhere('ringkasan', 'like', '%' . $search . '%');
            });
        }
        
        if ($request->has('jenis_id') && !empty($request->jenis_id)) {
            $query->where('jenis_id', $request->jenis_id);
        }
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        $query->orderBy('created_at', 'desc');
        
        $dataDokumenHukum = $query->paginate(10);
        
        $jenisDokumen = JenisDokumen::orderBy('nama_jenis')->get();
        
        return view('pages.admin.dokumenhukum.index', compact('dataDokumenHukum', 'jenisDokumen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $jenisDokumen = JenisDokumen::all();
    $kategoriDokumen = KategoriDokumen::all();
    
    return view('pages.admin.dokumenhukum.create', compact('jenisDokumen', 'kategoriDokumen'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_id' => 'required|exists:jenis_dokumen,jenis_id',
            'kategori_id' => 'required|exists:kategori,kategori_id',
            'nomor' => 'required|string|max:100|unique:dokumen_hukum,nomor',
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'ringkasan' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'berkas' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240'
        ]);

        DB::beginTransaction();

        try {
            // 1. Create dokumen first (without media reference)
            $dokumen = DokumenHukum::create([
                'jenis_id' => $request->jenis_id,
                'kategori_id' => $request->kategori_id,
                'nomor' => $request->nomor,
                'judul' => $request->judul,
                'tanggal' => $request->tanggal,
                'ringkasan' => $request->ringkasan,
                'status' => $request->status
            ]);

            // 2. Upload file
            if ($request->hasFile('berkas')) {
                $dokumen->uploadBerkas(
                    $request->file('berkas'),
                    'Berkas: ' . $dokumen->judul
                );
            }

            DB::commit();

            return redirect()->route('dokumen-hukum.index')
                ->with('success', 'Dokumen hukum berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Gagal menambahkan dokumen: ' . $e->getMessage())
                ->withInput();
        }
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
   public function edit($id)
    {
        $dokumen = DokumenHukum::with(['jenis', 'kategori', 'media'])->findOrFail($id);
        $jenisDokumen = JenisDokumen::all();
        $kategoriDokumen = KategoriDokumen::all();
        
        return view('pages.admin.dokumenhukum.edit', compact('dokumen', 'jenisDokumen', 'kategoriDokumen'));
    }

    /**
     * Update the specified resource in storage.
     */
       public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_id' => 'required|exists:jenis_dokumen,jenis_id',
            'kategori_id' => 'required|exists:kategori,kategori_id',
            'nomor' => 'required|string|max:100',
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'ringkasan' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240'
        ]);

        $dokumen = DokumenHukum::findOrFail($id);

        // Handle file upload jika ada
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($dokumen->media_id) {
                $oldMedia = Media::find($dokumen->media_id);
                if ($oldMedia) {
                    Storage::delete($oldMedia->file_path);
                    $oldMedia->delete();
                }
            }

            // Upload file baru
            $file = $request->file('file');
            $path = $file->store('dokumen-hukum', 'public');
            
            $media = Media::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'disk' => 'public'
            ]);

            $request->merge(['media_id' => $media->media_id]);
        }

        $dokumen->update($request->only([
            'jenis_id', 'kategori_id', 'media_id', 
            'judul', 'tanggal', 'ringkasan', 'status'
        ]));

        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
    {
        $dokumen = DokumenHukum::findOrFail($id);
        
        // Hapus file media jika ada
        if ($dokumen->media_id) {
            $media = Media::find($dokumen->media_id);
            if ($media) {
                Storage::disk($media->disk)->delete($media->file_path);
                $media->delete();
            }
        }

        $dokumen->delete();

        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }
}
