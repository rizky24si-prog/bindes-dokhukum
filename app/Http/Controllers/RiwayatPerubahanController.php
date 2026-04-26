<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPerubahan;
use App\Models\DokumenHukum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatPerubahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = RiwayatPerubahan::with('dokumen');
    
    // Search
    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('uraian_perubahan', 'like', '%' . $search . '%')
              ->orWhere('versi', 'like', '%' . $search . '%')
              ->orWhere('pembuat', 'like', '%' . $search . '%');
        });
    }
    
    // Filter berdasarkan dokumen
    if ($request->has('dokumen_id') && $request->dokumen_id) {
        $query->where('dokumen_id', $request->dokumen_id);
        $dokumen = DokumenHukum::find($request->dokumen_id);
    } else {
        $dokumen = null;
    }
    
    // Filter berdasarkan tipe perubahan
    if ($request->has('tipe_perubahan') && $request->tipe_perubahan) {
        $query->where('tipe_perubahan', $request->tipe_perubahan);
    }
    
    // Filter tanggal
    if ($request->has('start_date') && $request->start_date) {
        $query->whereDate('tanggal', '>=', $request->start_date);
    }
    
    if ($request->has('end_date') && $request->end_date) {
        $query->whereDate('tanggal', '<=', $request->end_date);
    }
    
    // Sort
    $sort = $request->get('sort', 'tanggal');
    $order = $request->get('order', 'desc');
    $query->orderBy($sort, $order);
    
    $dataRiwayat = $query->paginate(10)->withQueryString();
    $allDokumen = DokumenHukum::orderBy('judul')->get();
    
    $tipePerubahanOptions = [
        'revisi' => 'Revisi',
        'penambahan' => 'Penambahan',
        'pengurangan' => 'Pengurangan',
        'koreksi' => 'Koreksi',
        'pembaruan' => 'Pembaruan'
    ];
    
    return view('pages.admin.riwayatperubahan.index', compact(
        'dataRiwayat', 
        'allDokumen', 
        'dokumen', 
        'tipePerubahanOptions'
    ));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $dokumenList = DokumenHukum::orderBy('judul')->get();
        $selectedDokumen = $request->get('dokumen_id');
        $nextVersion = $selectedDokumen ? RiwayatPerubahan::generateNextVersion($selectedDokumen) : '1.0';
        
        $tipePerubahanOptions = [
            'revisi' => 'Revisi',
            'penambahan' => 'Penambahan',
            'pengurangan' => 'Pengurangan',
            'koreksi' => 'Koreksi',
            'pembaruan' => 'Pembaruan'
        ];
        
        return view('pages.admin.riwayatperubahan.create', compact('dokumenList', 'selectedDokumen', 'nextVersion', 'tipePerubahanOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dokumen_id' => 'required|exists:dokumen_hukum,dokumen_id',
            'tanggal' => 'required|date',
            'uraian_perubahan' => 'required|string|min:10',
            'versi' => 'required|string|max:20',
            'pembuat' => 'nullable|string|max:100',
            'tipe_perubahan' => 'nullable|string|max:50'
        ]);

        DB::beginTransaction();
        
        try {
            RiwayatPerubahan::create([
                'dokumen_id' => $request->dokumen_id,
                'tanggal' => $request->tanggal,
                'uraian_perubahan' => $request->uraian_perubahan,
                'versi' => $request->versi,
                'pembuat' => $request->pembuat,
                'tipe_perubahan' => $request->tipe_perubahan
            ]);

            DB::commit();
            
            return redirect()->route('riwayat-perubahan.index')
                ->with('success', 'Riwayat perubahan berhasil ditambahkan');
                
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
        $riwayat = RiwayatPerubahan::with('dokumen')->findOrFail($id);
        
        return view('pages.admin.riwayatperubahan.show', compact('riwayat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $riwayat = RiwayatPerubahan::findOrFail($id);
        $dokumenList = DokumenHukum::orderBy('judul')->get();
        
        $tipePerubahanOptions = [
            'revisi' => 'Revisi',
            'penambahan' => 'Penambahan',
            'pengurangan' => 'Pengurangan',
            'koreksi' => 'Koreksi',
            'pembaruan' => 'Pembaruan'
        ];
        
        return view('pages.admin.riwayatperubahan.edit', compact('riwayat', 'dokumenList', 'tipePerubahanOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'dokumen_id' => 'required|exists:dokumen_hukum,dokumen_id',
            'tanggal' => 'required|date',
            'uraian_perubahan' => 'required|string|min:10',
            'versi' => 'required|string|max:20',
            'pembuat' => 'nullable|string|max:100',
            'tipe_perubahan' => 'nullable|string|max:50'
        ]);

        DB::beginTransaction();
        
        try {
            $riwayat = RiwayatPerubahan::findOrFail($id);
            
            $riwayat->update([
                'dokumen_id' => $request->dokumen_id,
                'tanggal' => $request->tanggal,
                'uraian_perubahan' => $request->uraian_perubahan,
                'versi' => $request->versi,
                'pembuat' => $request->pembuat,
                'tipe_perubahan' => $request->tipe_perubahan
            ]);

            DB::commit();
            
            return redirect()->route('riwayat-perubahan.index')
                ->with('success', 'Riwayat perubahan berhasil diperbarui');
                
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
            $riwayat = RiwayatPerubahan::findOrFail($id);
            $riwayat->delete();
            
            DB::commit();
            
            return redirect()->route('riwayat-perubahan.index')
                ->with('success', 'Riwayat perubahan berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('riwayat-perubahan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}