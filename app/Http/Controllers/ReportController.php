<?php

namespace App\Http\Controllers;

use App\Models\ItemIn;
use App\Models\ItemOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return view('reports.index');
    }

    public function filter(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $start = $validated['start_date'];
        $end = $validated['end_date'];

        // Menggunakan satu parameter array pada where() agar Intelephense mendeteksi hanya ada 1 argumen
        $totalMasuk = ItemIn::query()
            ->where([
                ['tanggal_masuk', '>=', $start],
                ['tanggal_masuk', '<=', $end]
            ])
            ->sum('jumlah_masuk');

        $totalKeluar = ItemOut::query()
            ->where([
                ['tanggal_keluar', '>=', $start],
                ['tanggal_keluar', '<=', $end]
            ])
            ->sum('jumlah_keluar');


        $labels = collect();

        // Ambil list tanggal unik dari union tabel masuk/keluar.
        // Gunakan binding parameter agar aman & sesuai standar Laravel.
        $dates = DB::table(DB::raw("(
            SELECT DATE(tanggal_masuk) AS d FROM item_ins WHERE tanggal_masuk BETWEEN ? AND ?
            UNION
            SELECT DATE(tanggal_keluar) AS d FROM item_outs WHERE tanggal_keluar BETWEEN ? AND ?
        ) t"))
            ->setBindings([$start, $end, $start, $end])
            ->pluck('d')
            ->sort()
            ->values();

        foreach ($dates as $d) {
            // Jika d berupa string hasil query raw, kita bungkus dulu agar aman saat di-format
            $formattedDate = is_string($d) ? date('Y-m-d', strtotime($d)) : $d->format('Y-m-d');
            $labels->push($formattedDate);
        }

        // Membungkus argumen select ke dalam array [] agar Intelephense mendeteksi hanya 1 argumen
        $masukByDate = ItemIn::query()
            ->select([
                DB::raw('DATE(tanggal_masuk) AS d'), 
                DB::raw('SUM(jumlah_masuk) AS total')
            ])
            ->where([
                ['tanggal_masuk', '>=', $start],
                ['tanggal_masuk', '<=', $end]
            ])
            ->groupBy(DB::raw('DATE(tanggal_masuk)'))
            ->orderBy(DB::raw('DATE(tanggal_masuk)'))
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->d => $item->total];
            });

        // Membungkus argumen select ke dalam array [] agar Intelephense mendeteksi hanya 1 argumen
        $keluarByDate = ItemOut::query()
            ->select([
                DB::raw('DATE(tanggal_keluar) AS d'), 
                DB::raw('SUM(jumlah_keluar) AS total')
            ])
            ->where([
                ['tanggal_keluar', '>=', $start],
                ['tanggal_keluar', '<=', $end]
            ])
            ->groupBy(DB::raw('DATE(tanggal_keluar)'))
            ->orderBy(DB::raw('DATE(tanggal_keluar)'))
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->d => $item->total];
            });

        $labelsArr = $labels->toArray();
        $dataMasuk = array_map(function ($d) use ($masukByDate) {
            return (int) ($masukByDate[$d] ?? 0);
        }, $labelsArr);

        $dataKeluar = array_map(function ($d) use ($keluarByDate) {
            return (int) ($keluarByDate[$d] ?? 0);
        }, $labelsArr);

        $detailsIns = ItemIn::with('item')
            ->where([
                ['tanggal_masuk', '>=', $start],
                ['tanggal_masuk', '<=', $end]
            ])
            ->orderBy('tanggal_masuk')
            ->get();

        $detailsOuts = ItemOut::with('item')
            ->where([
                ['tanggal_keluar', '>=', $start],
                ['tanggal_keluar', '<=', $end]
            ])
            ->orderBy('tanggal_keluar')
            ->get();

        return view('reports.index', [
            'start' => $start,
            'end' => $end,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'labels' => $labelsArr,
            'dataMasuk' => $dataMasuk,
            'dataKeluar' => $dataKeluar,
            'detailsIns' => $detailsIns,
            'detailsOuts' => $detailsOuts,
        ]);
    }
}