<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const IPA_SUBJECTS = ['mtk', 'fisika', 'kimia', 'biologi'];
    private const IPS_SUBJECTS = ['ekonomi', 'geografi', 'sosiologi', 'sejarah'];

    public function up(): void
    {
        $majors = DB::table('jurusan_polije')->select('id', 'bobot_mapel')->get();

        foreach ($majors as $major) {
            $bobotMapel = $this->decodeBobotMapel($major->bobot_mapel);
            $nested = $this->toNestedStructure($bobotMapel);

            DB::table('jurusan_polije')
                ->where('id', $major->id)
                ->update([
                    'bobot_mapel' => json_encode($nested),
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        $majors = DB::table('jurusan_polije')->select('id', 'bobot_mapel')->get();

        foreach ($majors as $major) {
            $bobotMapel = $this->decodeBobotMapel($major->bobot_mapel);
            $flat = $this->toFlatStructure($bobotMapel);

            DB::table('jurusan_polije')
                ->where('id', $major->id)
                ->update([
                    'bobot_mapel' => json_encode($flat),
                    'updated_at' => now(),
                ]);
        }
    }

    private function decodeBobotMapel(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    private function toNestedStructure(array $bobotMapel): array
    {
        if (isset($bobotMapel['ipa']) || isset($bobotMapel['ips'])) {
            return [
                'ipa' => $this->normalizeGroup($bobotMapel['ipa'] ?? [], self::IPA_SUBJECTS),
                'ips' => $this->normalizeGroup($bobotMapel['ips'] ?? [], self::IPS_SUBJECTS),
            ];
        }

        return [
            'ipa' => $this->normalizeGroup($bobotMapel, self::IPA_SUBJECTS),
            'ips' => $this->normalizeGroup($bobotMapel, self::IPS_SUBJECTS),
        ];
    }

    private function toFlatStructure(array $bobotMapel): array
    {
        if (!isset($bobotMapel['ipa']) && !isset($bobotMapel['ips'])) {
            return $this->normalizeFlatGroup($bobotMapel, array_merge(self::IPA_SUBJECTS, self::IPS_SUBJECTS));
        }

        return array_merge(
            $this->normalizeGroup($bobotMapel['ipa'] ?? [], self::IPA_SUBJECTS),
            $this->normalizeGroup($bobotMapel['ips'] ?? [], self::IPS_SUBJECTS)
        );
    }

    private function normalizeGroup(array $values, array $subjects): array
    {
        $normalized = [];

        foreach ($subjects as $subject) {
            $value = $values[$subject] ?? null;
            $normalized[$subject] = is_numeric($value) ? (float) $value : 0.0;
        }

        return $normalized;
    }

    private function normalizeFlatGroup(array $values, array $subjects): array
    {
        $normalized = [];

        foreach ($subjects as $subject) {
            $value = $values[$subject] ?? null;
            if (is_numeric($value)) {
                $normalized[$subject] = (float) $value;
            }
        }

        return $normalized;
    }
};
