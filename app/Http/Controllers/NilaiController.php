<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    /**
     * Endpoint /nilaiRT
     *
     * Mengambil nilai Tes Minat (materi_uji_id = 7)
     * tanpa mengikutkan Pelajaran Khusus (pelajaran_id = 43).
     * Perhitungan menggunakan SQL, collection hanya untuk grouping akhir.
     */
    public function nilaiRT(): JsonResponse
    {
        // Query SQL: ambil data nilai untuk materi_uji_id = 7, kecuali Pelajaran Khusus
        $hasilNilai = DB::select("
            SELECT
                nama,
                nisn,
                LOWER(nama_pelajaran) AS nama_pelajaran,
                skor
            FROM nilai
            WHERE materi_uji_id = 7
              AND pelajaran_id != 43
            ORDER BY nama ASC, pelajaran_id ASC
        ");

        // Collection hanya untuk grouping terakhir (sesuai aturan)
        $hasilPerSiswa = collect($hasilNilai)
            ->groupBy('nisn')
            ->map(function ($nilaiSiswa) {
                $siswa = $nilaiSiswa->first();

                // Bangun object nilaiRt dari setiap pelajaran
                $nilaiRt = [];
                foreach ($nilaiSiswa as $item) {
                    $nilaiRt[$item->nama_pelajaran] = $item->skor;
                }

                return [
                    'nama' => $siswa->nama,
                    'nilaiRt' => $nilaiRt,
                    'nisn' => $siswa->nisn,
                ];
            })
            ->values();

        return response()->json($hasilPerSiswa);
    }

    /**
     * Endpoint /nilaiST
     *
     * Mengambil nilai Tes Skolastik (materi_uji_id = 4).
     * Setiap pelajaran dikalikan bobot tertentu:
     *   - pelajaran_id 44 (Verbal)       × 41.67
     *   - pelajaran_id 45 (Kuantitatif)  × 29.67
     *   - pelajaran_id 46 (Penalaran)    × 100
     *   - pelajaran_id 47 (Figural)      × 23.81
     * Hasil diurutkan dari total nilai terbesar.
     */
    public function nilaiST(): JsonResponse
    {
        // Query SQL: hitung nilai berbobot langsung di SQL menggunakan CASE WHEN
        $hasilNilai = DB::select("
            SELECT
                nama,
                nisn,
                LOWER(nama_pelajaran) AS nama_pelajaran,
                ROUND(
                    skor * CASE pelajaran_id
                        WHEN 44 THEN 41.67
                        WHEN 45 THEN 29.67
                        WHEN 46 THEN 100
                        WHEN 47 THEN 23.81
                        ELSE 0
                    END,
                2) AS nilai_berbobot
            FROM nilai
            WHERE materi_uji_id = 4
            ORDER BY nama ASC, pelajaran_id ASC
        ");

        // Collection hanya untuk grouping terakhir (sesuai aturan)
        $hasilPerSiswa = collect($hasilNilai)
            ->groupBy('nisn')
            ->map(function ($nilaiSiswa) {
                $siswa = $nilaiSiswa->first();

                // Bangun object listNilai dan hitung total
                $listNilai = [];
                $totalNilai = 0;

                foreach ($nilaiSiswa as $item) {
                    $listNilai[$item->nama_pelajaran] = $item->nilai_berbobot;
                    $totalNilai += $item->nilai_berbobot;
                }

                return [
                    'nama' => $siswa->nama,
                    'nisn' => $siswa->nisn,
                    'total' => round($totalNilai, 2),
                    'listNilai' => $listNilai,
                ];
            })
            ->sortByDesc('total')
            ->values();

        return response()->json($hasilPerSiswa);
    }
}
