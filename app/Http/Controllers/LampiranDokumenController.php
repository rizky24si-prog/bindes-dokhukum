<?php

namespace App\Http\Controllers;

use App\Models\LampiranDokumen;
use App\Models\DokumenHukum;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LampiranDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LampiranDokumen::with(['dokumen', 'media'])
            ->latestFirst();
        
        // Filter berdasarkan dokumen jika ada parameter
        if ($request->has('dokumen_id') && $request->dokumen_id) {
            $query->where('dokumen_id', $request->dokumen_id);
            $dokumen = DokumenHukum::find($request->dokumen_id);
        } else {
            $dokumen = null;
        }
        
        $dataLampiran = $query->paginate(10);
        $allDokumen = DokumenHukum::orderBy('judul')->get();
        
        return view('pages.admin.lampirandokumen.index', compact('dataLampiran', 'allDokumen', 'dokumen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $dokumenList = DokumenHukum::orderBy('judul')->get();
        $selectedDokumen = $request->get('dokumen_id');
        
        return view('pages.admin.lampirandokumen.create', compact('dokumenList', 'selectedDokumen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dokumen_id' => 'required|exists:dokumen_hukum,dokumen_id',
            'keterangan' => 'nullable|string|max:255',
            'file_lampiran' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,txt,zip,rar'
        ]);

        DB::beginTransaction();
        
        try {
            // Upload file
            $file = $request->file('file_lampiran');
            $path = $file->store('lampiran-dokumen', 'public');
            
            // Simpan ke media
            $media = Media::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'disk' => 'public'
            ]);

            // Simpan lampiran
            LampiranDokumen::create([
                'dokumen_id' => $request->dokumen_id,
                'keterangan' => $request->keterangan,
                'media_id' => $media->media_id,
                'nama_file' => $file->getClientOriginalName(),
                'ukuran_file' => $file->getSize(),
                'tipe_file' => $file->getMimeType()
            ]);

            DB::commit();
            
            return redirect()->route('lampiran-dokumen.index')
                ->with('success', 'Lampiran berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
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
        $lampiran = LampiranDokumen::with(['dokumen', 'media'])->findOrFail($id);
        
        return view('pages.admin.lampirandokumen.show', compact('lampiran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $lampiran = LampiranDokumen::with('dokumen')->findOrFail($id);
        $dokumenList = DokumenHukum::orderBy('judul')->get();
        
        return view('pages.admin.lampirandokumen.edit', compact('lampiran', 'dokumenList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'dokumen_id' => 'required|exists:dokumen_hukum,dokumen_id',
            'keterangan' => 'nullable|string|max:255',
            'file_lampiran' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,txt,zip,rar'
        ]);

        DB::beginTransaction();
        
        try {
            $lampiran = LampiranDokumen::findOrFail($id);
            
            $data = [
                'dokumen_id' => $request->dokumen_id,
                'keterangan' => $request->keterangan,
            ];
            
            // Jika ada file baru
            if ($request->hasFile('file_lampiran')) {
                // Hapus file lama
                if ($lampiran->media_id) {
                    $oldMedia = Media::find($lampiran->media_id);
                    if ($oldMedia) {
                        Storage::disk($oldMedia->disk)->delete($oldMedia->file_path);
                        $oldMedia->delete();
                    }
                }
                
                // Upload file baru
                $file = $request->file('file_lampiran');
                $path = $file->store('lampiran-dokumen', 'public');
                
                // Simpan ke media
                $media = Media::create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'disk' => 'public'
                ]);
                
                $data['media_id'] = $media->media_id;
                $data['nama_file'] = $file->getClientOriginalName();
                $data['ukuran_file'] = $file->getSize();
                $data['tipe_file'] = $file->getMimeType();
            }
            
            $lampiran->update($data);

            DB::commit();
            
            return redirect()->route('lampiran-dokumen.index')
                ->with('success', 'Lampiran berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
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
            $lampiran = LampiranDokumen::findOrFail($id);
            
            // Hapus file media jika ada
            if ($lampiran->media_id) {
                $media = Media::find($lampiran->media_id);
                if ($media) {
                    Storage::disk($media->disk)->delete($media->file_path);
                    $media->delete();
                }
            }
            
            $lampiran->delete();
            
            DB::commit();
            
            return redirect()->route('lampiran-dokumen.index')
                ->with('success', 'Lampiran berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('lampiran-dokumen.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download file lampiran
     */
    public function download($id)
    {
        $lampiran = LampiranDokumen::findOrFail($id);
        
        if (!$lampiran->media || !$lampiran->media->file_path) {
            return redirect()->back()
                ->with('error', 'File tidak ditemukan');
        }
        
        $path = $lampiran->media->file_path;
        $fileName = $lampiran->nama_file_display;
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path, $fileName);
        }
        
        return redirect()->back()
            ->with('error', 'File tidak ditemukan di server');
    }

    /**
     * Preview file lampiran
     */
    public function preview($id)
    {
        $lampiran = LampiranDokumen::findOrFail($id);
        
        if (!$lampiran->media || !$lampiran->media->full_url) {
            abort(404);
        }
        
        return redirect($lampiran->media->full_url);
    }
}