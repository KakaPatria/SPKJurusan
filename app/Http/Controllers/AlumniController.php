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
        $summary = $this->getAlumniSummary();
        
        return view('alumni.index', compact('alumni', 'summary'));
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
            'preferensi_studi' => 'nullable|in:Praktik_Langsung,DuDi,Project_Based,Blended,Praktik Langsung,Project Based',
            'prestasi' => 'nullable|string|max:255',
            
            // Major & Outcome
            'major_masuk' => 'required|string|max:255',
            'ranking_saat_rekomendasi' => 'nullable|integer|min:1|max:9',
            'success_status' => 'nullable|in:sangat_sukses,sukses,cukup,kurang_sukses',
            'catatan' => 'nullable|string|max:500',
        ]);

        if (!empty($validated['preferensi_studi'])) {
            $validated['preferensi_studi'] = str_replace(['Praktik Langsung', 'Project Based'], ['Praktik_Langsung', 'Project_Based'], $validated['preferensi_studi']);
        }

        Alumni::create($validated);

        return redirect()->route('admin.alumni.index')->with('success', 'Alumni berhasil ditambahkan');
    }

    /**
     * Show alumni detail
     */
    public function show(Alumni $alumnus)
    {
        return view('alumni.show', compact('alumnus'));
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
            'preferensi_studi' => 'nullable|in:Praktik_Langsung,DuDi,Project_Based,Blended,Praktik Langsung,Project Based',
            'prestasi' => 'nullable|string|max:255',
            
            'major_masuk' => 'required|string|max:255',
            'ranking_saat_rekomendasi' => 'nullable|integer|min:1|max:9',
            'success_status' => 'nullable|in:sangat_sukses,sukses,cukup,kurang_sukses',
            'catatan' => 'nullable|string|max:500',
        ]);

        if (!empty($validated['preferensi_studi'])) {
            $validated['preferensi_studi'] = str_replace(['Praktik Langsung', 'Project Based'], ['Praktik_Langsung', 'Project_Based'], $validated['preferensi_studi']);
        }

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
        
        $bySuccess = Alumni::selectRaw('success_status, COUNT(*) as count')
            ->groupBy('success_status')
            ->get();
        
        $prediction_accuracy = $this->calculatePredictionAccuracy();

        return [
            'total' => $totalAlumni,
            'by_major' => $byMajor,
            'by_success' => $bySuccess,
            'prediction_accuracy' => $prediction_accuracy,
        ];
    }

    /**
     * Calculate how accurate was our algorithm prediction
     * vs actual major the alumni entered
     */
    private function calculatePredictionAccuracy()
    {
        $alumni = Alumni::whereNotNull('ranking_saat_rekomendasi')->get();
        
        if ($alumni->isEmpty()) {
            return null;
        }

        $correctTop1 = 0;
        $correctTop3 = 0;
        $correctTop5 = 0;

        foreach ($alumni as $a) {
            if ($a->ranking_saat_rekomendasi == 1) {
                $correctTop1++;
            }
            if ($a->ranking_saat_rekomendasi <= 3) {
                $correctTop3++;
            }
            if ($a->ranking_saat_rekomendasi <= 5) {
                $correctTop5++;
            }
        }

        return [
            'top_1' => round(($correctTop1 / count($alumni)) * 100, 2),
            'top_3' => round(($correctTop3 / count($alumni)) * 100, 2),
            'top_5' => round(($correctTop5 / count($alumni)) * 100, 2),
            'total_alumni_analyzed' => count($alumni),
        ];
    }
}
