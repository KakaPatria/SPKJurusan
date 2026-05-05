<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    /**
     * Display alumni data list
     */
    public function index()
    {
        $alumni = Alumni::orderBy('tahun_masuk', 'desc')->paginate(20);
        
        return view('admin.alumni.index', compact('alumni'));
    }

    /**
     * Show form to input/create new alumni
     */
    public function create()
    {
        return view('admin.alumni.create');
    }

    /**
     * Store new alumni data
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alumni' => 'required|string|max:255',
            'nis' => 'nullable|string|max:20',
            'kelompok_asal' => 'required|in:IPA,IPS',
            
            // Nilai
            'mtk' => 'nullable|numeric|min:0|max:100',
            'fisika' => 'nullable|numeric|min:0|max:100',
            'kimia' => 'nullable|numeric|min:0|max:100',
            'biologi' => 'nullable|numeric|min:0|max:100',
            'ekonomi' => 'nullable|numeric|min:0|max:100',
            'geografi' => 'nullable|numeric|min:0|max:100',
            'sosiologi' => 'nullable|numeric|min:0|max:100',
            'sejarah' => 'nullable|numeric|min:0|max:100',
            
            // Non-akademik
            'minat' => 'nullable|string|max:255',
            'cita_cita' => 'nullable|string|max:255',
            'preferensi_studi' => 'nullable|in:Sains & Teknologi,Pertanian & Lingkungan,Kesehatan & Ilmu Hayat,Bisnis & Manajemen,Sosial & Humaniora',
            'prestasi' => 'nullable|string|max:255',
            
            // Major
            'major_masuk' => 'required|string|max:255',
            'tahun_lulus_polije' => 'nullable|integer|min:2020|max:' . date('Y'),
            'catatan' => 'nullable|string|max:500',
        ]);

        Alumni::create($validated);

        return redirect()->route('admin.alumni.index')->with('success', 'Alumni berhasil ditambahkan');
    }

    /**
     * Show alumni detail
     */
    public function show(Alumni $alumnus)
    {
        return view('admin.alumni.show', compact('alumnus'));
    }

    /**
     * Show form to edit alumni
     */
    public function edit(Alumni $alumni)
    {
        return view('admin.alumni.edit', compact('alumni'));
    }

    /**
     * Update alumni data
     */
    public function update(Request $request, Alumni $alumni)
    {
        $validated = $request->validate([
            'nama_alumni' => 'required|string|max:255',
            'nis' => 'nullable|string|max:20',
            'kelompok_asal' => 'required|in:IPA,IPS',
            
            'mtk' => 'nullable|numeric|min:0|max:100',
            'fisika' => 'nullable|numeric|min:0|max:100',
            'kimia' => 'nullable|numeric|min:0|max:100',
            'biologi' => 'nullable|numeric|min:0|max:100',
            'ekonomi' => 'nullable|numeric|min:0|max:100',
            'geografi' => 'nullable|numeric|min:0|max:100',
            'sosiologi' => 'nullable|numeric|min:0|max:100',
            'sejarah' => 'nullable|numeric|min:0|max:100',
            
            'minat' => 'nullable|string|max:255',
            'cita_cita' => 'nullable|string|max:255',
            'preferensi_studi' => 'nullable|in:Sains & Teknologi,Pertanian & Lingkungan,Kesehatan & Ilmu Hayat,Bisnis & Manajemen,Sosial & Humaniora',
            'prestasi' => 'nullable|string|max:255',
            
            'major_masuk' => 'required|string|max:255',
            'tahun_lulus_polije' => 'nullable|integer|min:2020|max:' . date('Y'),
            'catatan' => 'nullable|string|max:500',
        ]);

        $alumni->update($validated);

        return redirect()->route('admin.alumni.index')->with('success', 'Alumni berhasil diupdate');
    }

    /**
     * Delete alumni
     */
    public function destroy(Alumni $alumni)
    {
        $alumni->delete();
        return redirect()->route('admin.alumni.index')->with('success', 'Alumni berhasil dihapus');
    }

    /**
     * Get summary analytics untuk alumni
     */
    private function getAlumniSummary()
    {
        $totalAlumni = Alumni::count();
        
        $byMajor = Alumni::selectRaw('major_masuk, COUNT(*) as count')
            ->groupBy('major_masuk')
            ->get();
        
        // Statistics by kelompok asal (IPA/IPS)
        $byKelompok = Alumni::selectRaw('kelompok_asal, COUNT(*) as count')
            ->groupBy('kelompok_asal')
            ->get();

        return [
            'total' => $totalAlumni,
            'by_major' => $byMajor,
            'by_kelompok' => $byKelompok,
        ];
    }
}
