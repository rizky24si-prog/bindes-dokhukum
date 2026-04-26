<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumenHukum;
use App\Models\JenisDokumen; // Ganti dari JenisDokumen
use App\Models\Kategori; // Ganti dari KategoriDokumen
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DokumenHukumController extends Controller
{
    public function index(Request $request)
    {
        // HAPUS ->with(['jenis', 'kategori', 'media']) menjadi:
        $query = DokumenHukum::with(['JenisDokumen', 'kategori']); // HAPUS 'media'
        
        // Search - HAPUS bagian media dari search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor', 'like', '%' . $search . '%')
                  ->orWhere('judul', 'like', '%' . $search . '%')
                  ->orWhere('ringkasan', 'like', '%' . $search . '%')
                  ->orWhereHas('jenis', function($q) use ($search) {
                      $q->where('nama_jenis', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('kategori', function($q) use ($search) {
                      $q->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Filter jenis
        if ($request->has('jenis_id') && !empty($request->jenis_id)) {
            $query->where('jenis_id', $request->jenis_id);
        }
        
        // Filter kategori
        if ($request->has('kategori_id') && !empty($request->kategori_id)) {
            $query->where('kategori_id', $request->kategori_id);
        }
        
        // Filter status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Filter tanggal
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }
        
        // Sort
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        
        // Handle special sorting cases
        if ($sort == 'jenis') {
            $query->join('JenisDokumen', 'dokumen.jenis_id', '=', 'jenis.jenis_id')
                  ->orderBy('jenis.nama_jenis', $order)
                  ->select('dokumen.*');
        } elseif ($sort == 'kategori') {
            $query->join('kategori', 'dokumen.kategori_id', '=', 'kategori.kategori_id')
                  ->orderBy('kategori.nama', $order)
                  ->select('dokumen.*');
        } else {
            $query->orderBy($sort, $order);
        }
        
        $dataDokumenHukum = $query->paginate(10)->withQueryString();
        
        $jenisDokumen = JenisDokumen::orderBy('nama_jenis')->get(); // Ganti dari JenisDokumen
        $kategoriDokumen = Kategori::orderBy('nama')->get(); // Ganti dari KategoriDokumen
        $statusOptions = [
            'draft' => 'Draft',
            'review' => 'Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'archived' => 'Archived'
        ];
        
        return view('pages.admin.dokumenhukum.index', compact(
            'dataDokumenHukum', 
            'jenisDokumen',
            'kategoriDokumen',
            'statusOptions'
        ));
    }

    public function create()
    {
        $jenisDokumen = Jenis::all(); // Ganti dari JenisDokumen
        $kategoriDokumen = Kategori::all(); // Ganti dari KategoriDokumen
        
        return view('pages.admin.dokumenhukum.create', compact('jenisDokumen', 'kategoriDokumen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_id' => 'required|exists:jenis,jenis_id',
            'kategori_id' => 'required|exists:kategori,kategori_id',
            'nomor' => 'required|string|max:100|unique:dokumen,nomor', // Ganti 'dokumen_hukum' menjadi 'dokumen'
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'ringkasan' => 'nullable|string',
            'status' => 'required|in:draft,review,approved,rejected,archived',
            'berkas' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240' // Optional jika belum ada upload
        ]);

        DB::beginTransaction();

        try {
            // 1. Create dokumen
            $dokumen = DokumenHukum::create([
                'jenis_id' => $request->jenis_id,
                'kategori_id' => $request->kategori_id,
                'nomor' => $request->nomor,
                'judul' => $request->judul,
                'tanggal' => $request->tanggal,
                'ringkasan' => $request->ringkasan,
                'status' => $request->status
            ]);

            // 2. Upload file jika ada (SIMPAN DI LOCAL FOLDER DULU)
            if ($request->hasFile('berkas')) {
                $file = $request->file('berkas');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('dokumen', $fileName, 'public');
                
                // Simpan path file di database (tambahkan kolom jika perlu)
                // $dokumen->update(['file_path' => $filePath]);
            }

            DB::commit();

            return redirect()->route('dokumen.index') // Pastikan route name benar
                ->with('success', 'Dokumen hukum berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Gagal menambahkan dokumen: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        // HAPUS 'media' dari with()
        $dokumen = DokumenHukum::with(['jenis', 'kategori'])->findOrFail($id); // HAPUS 'media'
        $jenisDokumen = Jenis::all(); // Ganti dari JenisDokumen
        $kategoriDokumen = Kategori::all(); // Ganti dari KategoriDokumen
        
        return view('pages.admin.dokumenhukum.edit', compact('dokumen', 'jenisDokumen', 'kategoriDokumen'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_id' => 'required|exists:jenis,jenis_id',
            'kategori_id' => 'required|exists:kategori,kategori_id',
            'nomor' => 'required|string|max:100',
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'ringkasan' => 'nullable|string',
            'status' => 'required|in:draft,review,approved,rejected,archived',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240'
        ]);

        $dokumen = DokumenHukum::findOrFail($id);

        // Handle file upload jika ada (SIMPAN DI LOCAL FOLDER)
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('dokumen', $fileName, 'public');
            
            // Simpan path di database (tambahkan kolom jika perlu)
            // $dokumen->file_path = $filePath;
        }

        $dokumen->update($request->only([
            'jenis_id', 'kategori_id', 
            'judul', 'tanggal', 'ringkasan', 'status'
        ]));

        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui');
    }

    public function destroy($id)
    {
        $dokumen = DokumenHukum::findOrFail($id);
        
        // Hapus file jika ada
        if ($dokumen->file_path && Storage::exists('public/' . $dokumen->file_path)) {
            Storage::delete('public/' . $dokumen->file_path);
        }

        $dokumen->delete();

        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }
}