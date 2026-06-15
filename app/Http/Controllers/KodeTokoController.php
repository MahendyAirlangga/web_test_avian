<?php

namespace App\Http\Controllers;

use App\Models\KodeToko;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KodeTokoController extends Controller
{
    public function index()
    {
        $data = KodeToko::all();
        return view('fiture.kode_toko.index', compact('data'));
    }

    public function createToko(Request $request){
        $request->validate([
            'kode_toko_lama' => 'required',
        ], [
            'kode_toko_lama.required' => 'Kode toko lama harus diisi.',
        ]);

        // Check if Kode Toko Lama already exists
        $exists = KodeToko::where('kode_toko_lama', trim($request->kode_toko_lama))->exists();
        if ($exists) {
            return redirect()->route('view.toko')->with('error', 'Gagal! Kode Toko Lama "' . $request->kode_toko_lama . '" sudah ada di database.');
        }

        $lastKodeTokoBaru = KodeToko::orderBy('kode_toko_baru', 'desc')->first();
        $newKodeTokoBaru = $lastKodeTokoBaru
            ? (int)$lastKodeTokoBaru->kode_toko_baru + 1
            : 1;

        KodeToko::create([
            'kode_toko_baru' => $newKodeTokoBaru,
            'kode_toko_lama' => trim($request->kode_toko_lama),
        ]);
        return redirect()->route('view.toko')->with('success', 'Data berhasil ditambahkan');
    }

    public function destroyToko($id){
        $toko = KodeToko::find($id);
        $toko->delete();
        return redirect()->route('view.toko')->with('success', 'Data berhasil dihapus');
    }

    public function updateToko(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'kode_toko_baru' => 'required',
            'kode_toko_lama' => 'required',
        ]);

        // Cari data toko berdasarkan kode_toko_baru
        $toko = KodeToko::where('kode_toko_baru', $id)->first();

        if (!$toko) {
            return redirect()->route('view.toko')->with('error', 'Data toko tidak ditemukan');
        }

        // Update data
        $toko->update([
            'kode_toko_baru' => $request->kode_toko_baru,
            'kode_toko_lama' => $request->kode_toko_lama,
        ]);

        return redirect()->route('view.toko')->with('success', 'Data toko berhasil diupdate');
    }

    public function importToko(Request $request)
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
                return redirect()->route('view.toko')->with('error', 'File excel tidak memiliki baris data.');
            }

            $header = array_shift($rows);
            $kodeTokoLamaIndex = -1;

            foreach ($header as $index => $colName) {
                if ($colName && strtolower(trim($colName)) === 'kode_toko_lama') {
                    $kodeTokoLamaIndex = $index;
                    break;
                }
            }

            // Fallback to first column if header not matched
            if ($kodeTokoLamaIndex === -1) {
                $kodeTokoLamaIndex = 0;
            }

            $importedCount = 0;
            $duplicateRows = [];

            foreach ($rows as $row) {
                $kodeTokoLama = isset($row[$kodeTokoLamaIndex]) ? trim($row[$kodeTokoLamaIndex]) : null;

                if (empty($kodeTokoLama)) {
                    continue;
                }

                // Check if Kode Toko Lama already exists to avoid duplicates
                $exists = KodeToko::where('kode_toko_lama', $kodeTokoLama)->exists();
                if ($exists) {
                    $duplicateRows[] = $kodeTokoLama;
                    continue;
                }

                // Generate new Kode Toko Baru
                $lastKodeTokoBaru = KodeToko::orderBy('kode_toko_baru', 'desc')->first();
                $newKodeTokoBaru = $lastKodeTokoBaru
                    ? (int)$lastKodeTokoBaru->kode_toko_baru + 1
                    : 1;

                KodeToko::create([
                    'kode_toko_baru' => $newKodeTokoBaru,
                    'kode_toko_lama' => $kodeTokoLama
                ]);

                $importedCount++;
            }

            $response = redirect()->route('view.toko');
            if (count($duplicateRows) > 0) {
                $response->with('duplicates', $duplicateRows);
            }

            if ($importedCount === 0) {
                return $response->with('error', 'Tidak ada data baru yang berhasil ditambahkan.');
            }

            return $response->with('success', "$importedCount data toko berhasil diimpor.");

        } catch (\Exception $e) {
            return redirect()->route('view.toko')->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }
}
