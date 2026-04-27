<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PolijeMajor;
use App\Models\Recommendation;
use App\Models\ChatHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // ============================================
    // 1. DASHBOARD
    // ============================================
    public function dashboard()
    {
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalRekomendasi = Recommendation::count();
        $totalChatHistory = ChatHistory::count();
        $totalJurusan = PolijeMajor::count();

        $recentStudents = User::where('role', 'siswa')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentRecommendations = Recommendation::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $kelompokStats = User::where('role', 'siswa')
            ->selectRaw('kelompok_asal, COUNT(*) as count')
            ->groupBy('kelompok_asal')
            ->get();

        $topMajors = Recommendation::selectRaw("
            JSON_EXTRACT(hasil_rekomendasi, '$[0].jurusan') as major_name,
            COUNT(*) as count
        ")
            ->groupByRaw("JSON_EXTRACT(hasil_rekomendasi, '$[0].jurusan')")
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // Data untuk chart - semua jurusan
        $allMajorsChart = Recommendation::selectRaw("
            JSON_EXTRACT(hasil_rekomendasi, '$[0].jurusan') as major_name,
            COUNT(*) as count
        ")
            ->groupByRaw("JSON_EXTRACT(hasil_rekomendasi, '$[0].jurusan')")
            ->orderBy('count', 'desc')
            ->get();

        // Persiapkan data untuk Chart.js
        $chartMajorNames = $allMajorsChart->pluck('major_name')->map(function($name) {
            return trim($name, '"');
        })->toArray();
        $chartMajorCounts = $allMajorsChart->pluck('count')->toArray();

        $chartKelompokNames = $kelompokStats->pluck('kelompok_asal')->toArray();
        $chartKelompokCounts = $kelompokStats->pluck('count')->toArray();

        // Top majors untuk horizontal bar chart
        $topMajorsChart = $topMajors->pluck('major_name')->map(function($name) {
            return trim($name, '"');
        })->toArray();
        $topMajorsCounts = $topMajors->pluck('count')->toArray();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalRekomendasi',
            'totalChatHistory',
            'totalJurusan',
            'recentStudents',
            'recentRecommendations',
            'kelompokStats',
            'topMajors',
            'chartMajorNames',
            'chartMajorCounts',
            'chartKelompokNames',
            'chartKelompokCounts',
            'topMajorsChart',
            'topMajorsCounts'
        ));
    }

    // ============================================
    // 2. MANAJEMEN DATA SISWA
    // ============================================
    public function students(Request $request)
    {
        $query = User::where('role', 'siswa')
            ->withCount('recommendations', 'chatHistories');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kelompok')) {
            $query->where('kelompok_asal', $request->kelompok);
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.students.index', compact('students'));
    }

    public function studentDetail($id)
    {
        $student = User::where('role', 'siswa')->findOrFail($id);
        $recommendations = Recommendation::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        $chatHistories = ChatHistory::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.students.detail', compact('student', 'recommendations', 'chatHistories'));
    }

    public function chatHistory($id)
    {
        $user = User::findOrFail($id);
        $chatHistories = ChatHistory::where('user_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.chat-history', compact('user', 'chatHistories'));
    }

    // ============================================
    // 3. MANAJEMEN JURUSAN (CRUD dari database)
    // ============================================
    public function jurusan()
    {
        $jurusanList = PolijeMajor::orderBy('nama_jurusan')->get();
        return view('admin.jurusan.index', compact('jurusanList'));
    }

    public function jurusanCreate()
    {
        return view('admin.jurusan.create');
    }

    public function jurusanStore(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255|unique:jurusan_polije,nama_jurusan',
            'deskripsi' => 'nullable|string|max:1000',
            'keywords' => 'nullable|string',
            'preferensi_studi' => 'nullable|string',
            'prospek_kerja' => 'nullable|string|max:1000',
            'bobot_mtk' => 'nullable|numeric|min:0|max:1',
            'bobot_fisika' => 'nullable|numeric|min:0|max:1',
            'bobot_kimia' => 'nullable|numeric|min:0|max:1',
            'bobot_biologi' => 'nullable|numeric|min:0|max:1',
            'bobot_ekonomi' => 'nullable|numeric|min:0|max:1',
            'bobot_geografi' => 'nullable|numeric|min:0|max:1',
            'bobot_sosiologi' => 'nullable|numeric|min:0|max:1',
            'bobot_sejarah' => 'nullable|numeric|min:0|max:1',
        ]);

        PolijeMajor::create([
            'nama_jurusan' => $request->nama_jurusan,
            'deskripsi' => $request->deskripsi,
            'keywords' => $this->parseTagInput($request->keywords),
            'preferensi_studi' => $this->parseTagInput($request->preferensi_studi),
            'prospek_kerja' => $request->prospek_kerja,
            'bobot_mapel' => $this->parseBobotMapel($request),
        ]);

        return redirect()->route('admin.jurusan')->with('success', 'Jurusan berhasil ditambahkan!');
    }

    public function jurusanEdit($id)
    {
        $jurusan = PolijeMajor::findOrFail($id);
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    public function jurusanUpdate(Request $request, $id)
    {
        $jurusan = PolijeMajor::findOrFail($id);

        $request->validate([
            'nama_jurusan' => ['required', 'string', 'max:255', Rule::unique('jurusan_polije', 'nama_jurusan')->ignore($jurusan->id)],
            'deskripsi' => 'nullable|string|max:1000',
            'keywords' => 'nullable|string',
            'preferensi_studi' => 'nullable|string',
            'prospek_kerja' => 'nullable|string|max:1000',
            'bobot_mtk' => 'nullable|numeric|min:0|max:1',
            'bobot_fisika' => 'nullable|numeric|min:0|max:1',
            'bobot_kimia' => 'nullable|numeric|min:0|max:1',
            'bobot_biologi' => 'nullable|numeric|min:0|max:1',
            'bobot_ekonomi' => 'nullable|numeric|min:0|max:1',
            'bobot_geografi' => 'nullable|numeric|min:0|max:1',
            'bobot_sosiologi' => 'nullable|numeric|min:0|max:1',
            'bobot_sejarah' => 'nullable|numeric|min:0|max:1',
        ]);

        $jurusan->update([
            'nama_jurusan' => $request->nama_jurusan,
            'deskripsi' => $request->deskripsi,
            'keywords' => $this->parseTagInput($request->keywords),
            'preferensi_studi' => $this->parseTagInput($request->preferensi_studi),
            'prospek_kerja' => $request->prospek_kerja,
            'bobot_mapel' => $this->parseBobotMapel($request),
        ]);

        return redirect()->route('admin.jurusan')->with('success', 'Jurusan berhasil diperbarui!');
    }

    public function jurusanDestroy($id)
    {
        $jurusan = PolijeMajor::findOrFail($id);
        $jurusan->delete();

        return redirect()->route('admin.jurusan')->with('success', 'Jurusan berhasil dihapus!');
    }

    /**
     * Parse comma-separated tag input into array
     */
    private function parseTagInput(?string $input): array
    {
        if (empty($input)) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $input))));
    }

    /**
     * Parse bobot mapel from request into structured array
     */
    private function parseBobotMapel(Request $request): array
    {
        $mapelList = ['mtk', 'fisika', 'kimia', 'biologi', 'ekonomi', 'geografi', 'sosiologi', 'sejarah'];
        $bobot = [];

        foreach ($mapelList as $mapel) {
            $value = $request->input("bobot_{$mapel}");
            if (!is_null($value) && $value !== '') {
                $bobot[$mapel] = floatval($value);
            }
        }

        return $bobot;
    }

    // ============================================
    // 4. MANAJEMEN AKUN GURU BK
    // ============================================
    public function guruBK()
    {
        $guruBK = User::where('role', 'bk')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.guru-bk.index', compact('guruBK'));
    }

    public function guruBKCreate()
    {
        return view('admin.guru-bk.create');
    }

    public function guruBKStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'bk',
        ]);

        return redirect()->route('admin.guru-bk')->with('success', 'Akun Guru BK berhasil dibuat!');
    }

    public function guruBKEdit($id)
    {
        $guruBK = User::where('role', 'bk')->findOrFail($id);
        return view('admin.guru-bk.edit', compact('guruBK'));
    }

    public function guruBKUpdate(Request $request, $id)
    {
        $guruBK = User::where('role', 'bk')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($guruBK->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $guruBK->name = $request->name;
        $guruBK->email = $request->email;

        if ($request->filled('password')) {
            $guruBK->password = Hash::make($request->password);
        }

        $guruBK->save();

        return redirect()->route('admin.guru-bk')->with('success', 'Akun Guru BK berhasil diperbarui!');
    }

    public function guruBKDestroy($id)
    {
        $guruBK = User::where('role', 'bk')->findOrFail($id);
        $guruBK->delete();

        return redirect()->route('admin.guru-bk')->with('success', 'Akun Guru BK berhasil dihapus!');
    }

    // ============================================
    // 5. RIWAYAT REKOMENDASI SISWA
    // ============================================
    public function riwayatRekomendasi(Request $request)
    {
        $query = Recommendation::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $recommendations = $query->orderBy('created_at', 'desc')->paginate(20);

        $uniqueStudents = Recommendation::distinct('user_id')->count('user_id');

        // Find top major
        $topMajorRow = Recommendation::selectRaw("
            JSON_EXTRACT(hasil_rekomendasi, '$[0].jurusan') as major_name,
            COUNT(*) as count
        ")
            ->groupByRaw("JSON_EXTRACT(hasil_rekomendasi, '$[0].jurusan')")
            ->orderBy('count', 'desc')
            ->first();

        $topMajor = $topMajorRow ? trim($topMajorRow->major_name, '"') : null;

        return view('admin.riwayat-rekomendasi.index', compact('recommendations', 'uniqueStudents', 'topMajor'));
    }

    // ============================================
    // 6. RIWAYAT KONSULTASI CHATBOT
    // ============================================
    public function riwayatChatbot(Request $request)
    {
        $query = ChatHistory::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pertanyaan', 'like', "%{$search}%")
                  ->orWhere('jawaban', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $chatHistories = $query->orderBy('created_at', 'desc')->paginate(20);

        $uniqueStudents = ChatHistory::distinct('user_id')->count('user_id');
        $todayCount = ChatHistory::whereDate('created_at', today())->count();

        return view('admin.riwayat-chatbot.index', compact('chatHistories', 'uniqueStudents', 'todayCount'));
    }

    // ============================================
    // 7. PROFIL ADMIN
    // ============================================
    public function profil()
    {
        $admin = Auth::user();
        return view('admin.profil.index', compact('admin'));
    }

    public function updateProfil(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($admin->id)],
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->save();

        return redirect()->route('admin.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Auth::user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('admin.profil')->with('success', 'Password berhasil diubah!');
    }

}
