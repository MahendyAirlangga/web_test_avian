<?php

namespace App\Http\Controllers;

use App\Models\Nominal;
use App\Models\KodeToko;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class NominalController extends Controller
{
    public function index()
    {
        $data = Nominal::orderBy('kode_toko')->orderBy('nominal_transaksi')->get();
        $kode_toko_list = KodeToko::select('kode_toko_baru', 'kode_toko_lama')->get();
        return view('fiture.nominal.index', compact('data', 'kode_toko_list'));
    }

    public function createNominal(Request $request)
    {
        $request->validate([
            'kode_toko' => 'required',
            'nominal_transaksi' => 'required|numeric',
        ], [
            'kode_toko.required' => 'Kode toko harus diisi.',
            'nominal_transaksi.required' => 'Nominal transaksi harus diisi.',
            'nominal_transaksi.numeric' => 'Nominal transaksi harus berupa angka.',
        ]);

        $kodeToko = trim($request->kode_toko);
        $nominalTransaksi = trim($request->nominal_transaksi);

        $exists = Nominal::where('kode_toko', $kodeToko)
            ->where('nominal_transaksi', $nominalTransaksi)
            ->exists();

        if ($exists) {
            return redirect()->route('view.nominal')->with(
                'error',
                'Gagal! Kombinasi Kode Toko "' . $kodeToko . '" dengan Nominal "Rp ' . number_format($nominalTransaksi, 0, ',', '.') . '" sudah terdaftar.'
            );
        }

        Nominal::insert([
            'kode_toko' => $kodeToko,
            'nominal_transaksi' => $nominalTransaksi,
        ]);

        return redirect()->route('view.nominal')->with('success', 'Data nominal berhasil ditambahkan');
    }

    public function updateNominal(Request $request, $id)
    {
        $request->validate([
            'nominal_transaksi_lama' => 'required',
            'nominal_transaksi' => 'required|numeric',
        ], [
            'nominal_transaksi_lama.required' => 'Data nominal tidak valid.',
            'nominal_transaksi.required' => 'Nominal transaksi harus diisi.',
            'nominal_transaksi.numeric' => 'Nominal transaksi harus berupa angka.',
        ]);

        $kodeToko = $id;
        $nominalLama = trim($request->nominal_transaksi_lama);
        $newNominal = trim($request->nominal_transaksi);

        $exists = Nominal::where('kode_toko', $kodeToko)
            ->where('nominal_transaksi', $nominalLama)
            ->exists();

        if (!$exists) {
            return redirect()->route('view.nominal')->with('error', 'Data nominal tidak ditemukan');
        }

        if ($nominalLama !== $newNominal) {
            $conflict = Nominal::where('kode_toko', $kodeToko)
                ->where('nominal_transaksi', $newNominal)
                ->exists();
            if ($conflict) {
                return redirect()->route('view.nominal')->with(
                    'error',
                    'Gagal! Kombinasi Kode Toko "' . $kodeToko . '" dengan Nominal "Rp ' . number_format($newNominal, 0, ',', '.') . '" sudah terdaftar.'
                );
            }
        }

        Nominal::where('kode_toko', $kodeToko)
            ->where('nominal_transaksi', $nominalLama)
            ->update(['nominal_transaksi' => $newNominal]);

        return redirect()->route('view.nominal')->with('success', 'Data nominal berhasil diupdate');
    }

    public function destroyNominal(Request $request, $id)
    {
        $request->validate([
            'nominal_transaksi' => 'required',
        ], [
            'nominal_transaksi.required' => 'Data nominal tidak valid.',
        ]);

        $kodeToko = $id;
        $nominalTransaksi = trim($request->nominal_transaksi);

        $deleted = Nominal::where('kode_toko', $kodeToko)
            ->where('nominal_transaksi', $nominalTransaksi)
            ->delete();

        if ($deleted) {
            return redirect()->route('view.nominal')->with('success', 'Data nominal berhasil dihapus');
        }

        return redirect()->route('view.nominal')->with('error', 'Data nominal tidak ditemukan');
    }

    public function importNominal(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:4096'
        ], [
            'excel_file.required' => 'File harus diunggah.',
            'excel_file.mimes' => 'Format file harus .xlsx, .xls, atau .csv.',
            'excel_file.max' => 'Ukuran file tidak boleh lebih dari 4 MB.'
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (count($rows) <= 1) {
                return redirect()->route('view.nominal')->with('error', 'File excel tidak memiliki baris data.');
            }

            $header = array_shift($rows);
            $kodeTokoIndex = -1;
            $nominalIndex = -1;

            foreach ($header as $index => $colName) {
                if ($colName) {
                    $cleanedName = strtolower(trim($colName));
                    if ($cleanedName === 'kode_toko') {
                        $kodeTokoIndex = $index;
                    } elseif ($cleanedName === 'nominal_transaksi' || $cleanedName === 'nominal') {
                        $nominalIndex = $index;
                    }
                }
            }

            // Fallback defaults
            if ($kodeTokoIndex === -1) $kodeTokoIndex = 0;
            if ($nominalIndex === -1) $nominalIndex = 1;

            $importedCount = 0;
            $duplicateRows = [];
            foreach ($rows as $row) {
                $kodeToko = isset($row[$kodeTokoIndex]) ? trim($row[$kodeTokoIndex]) : null;
                $nominal = isset($row[$nominalIndex]) ? trim($row[$nominalIndex]) : null;

                if (empty($kodeToko) || empty($nominal)) {
                    continue;
                }

                // Clean nominal formatting if any (e.g. currency symbols or commas)
                $nominal = preg_replace('/[^0-9.]/', '', $nominal);

                $exists = Nominal::where('kode_toko', $kodeToko)
                    ->where('nominal_transaksi', $nominal)
                    ->exists();
                if ($exists) {
                    $duplicateRows[] = 'Kode Toko: ' . $kodeToko . ' + Nominal: Rp ' . number_format((float)$nominal, 0, ',', '.');
                    continue;
                }

                Nominal::insert([
                    'kode_toko' => $kodeToko,
                    'nominal_transaksi' => $nominal
                ]);

                $importedCount++;
            }

            $response = redirect()->route('view.nominal');
            if (count($duplicateRows) > 0) {
                $response = $response->with('duplicates', $duplicateRows);
            }

            if ($importedCount === 0) {
                return $response->with('error', 'Tidak ada data baru yang berhasil diimpor.');
            }

            return $response->with('success', "$importedCount data nominal berhasil diimpor.");
        } catch (\Exception $e) {
            return redirect()->route('view.nominal')->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }
}
