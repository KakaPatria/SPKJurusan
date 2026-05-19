<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    private const IPA_SUBJECTS = ['mtk', 'fisika', 'kimia', 'biologi'];
    private const IPS_SUBJECTS = ['ekonomi', 'geografi', 'sosiologi', 'sejarah'];

    private const ALL_SUBJECTS = ['mtk', 'fisika', 'kimia', 'biologi', 'ekonomi', 'geografi', 'sosiologi', 'sejarah'];

    private function normalizeScoreFields(array $validated, string $kelompokAsal): array
    {
        $activeSubjects = $kelompokAsal === 'IPA' ? self::IPA_SUBJECTS : self::IPS_SUBJECTS;

        foreach (self::ALL_SUBJECTS as $subject) {
            if (!in_array($subject, $activeSubjects, true)) {
                $validated[$subject] = null;
            }
        }

        return $validated;
    }

    private function validateScoreByKelompok(Request $request): void
    {
        $requiredSubjects = $request->input('kelompok_asal') === 'IPA'
            ? self::IPA_SUBJECTS
            : self::IPS_SUBJECTS;

        foreach ($requiredSubjects as $subject) {
            $request->validate([
                $subject => 'required|numeric|min:0|max:100',
            ]);
        }
    }

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
            'nama_alumni' => 'required|string|min:3|max:255',
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
            'preferensi_studi' => 'nullable|in:Praktik Langsung,Praktik_Langsung,DuDi,Project Based,Project_Based,Blended Learning,Blended',
            'prestasi' => 'nullable|string|max:255',
            
            // Major
            'major_masuk' => 'required|string|min:3|max:255',
            'tahun_lulus_polije' => 'nullable|integer|min:2020|max:' . date('Y'),
            'catatan' => 'nullable|string|max:500',
        ]);

        $this->validateScoreByKelompok($request);
        $validated = $this->normalizeScoreFields($validated, $validated['kelompok_asal']);

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
            'nama_alumni' => 'required|string|min:3|max:255',
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
            'preferensi_studi' => 'nullable|in:Praktik Langsung,Praktik_Langsung,DuDi,Project Based,Project_Based,Blended Learning,Blended',
            'prestasi' => 'nullable|string|max:255',
            
            'major_masuk' => 'required|string|min:3|max:255',
            'tahun_lulus_polije' => 'nullable|integer|min:2020|max:' . date('Y'),
            'catatan' => 'nullable|string|max:500',
        ]);

        $this->validateScoreByKelompok($request);
        $validated = $this->normalizeScoreFields($validated, $validated['kelompok_asal']);

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
