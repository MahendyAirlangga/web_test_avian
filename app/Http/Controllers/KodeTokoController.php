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

        try {
            KodeToko::create([
                'kode_toko_baru' => $newKodeTokoBaru,
                'kode_toko_lama' => trim($request->kode_toko_lama),
            ]);
            return redirect()->route('view.toko')->with('success', 'Data berhasil ditambahkan');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode == 1062 || $errorCode == 19) {
                return redirect()->route('view.toko')->with('error', 'Gagal menyimpan data! Kode Toko sudah terdaftar di database.');
            }
            return redirect()->route('view.toko')->with('error', 'Gagal menyimpan data! Terjadi kesalahan pada database.');
        } catch (\Exception $e) {
            return redirect()->route('view.toko')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyToko($id){
        try {
            $toko = KodeToko::find($id);
            if (!$toko) {
                return redirect()->route('view.toko')->with('error', 'Data toko tidak ditemukan');
            }
            $toko->delete();
            return redirect()->route('view.toko')->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('view.toko')->with('error', 'Gagal menghapus data! Kode toko ini masih digunakan di tabel nominal atau area sales.');
        } catch (\Exception $e) {
            return redirect()->route('view.toko')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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

        try {
            // Update data
            $toko->update([
                'kode_toko_baru' => $request->kode_toko_baru,
                'kode_toko_lama' => $request->kode_toko_lama,
            ]);
            return redirect()->route('view.toko')->with('success', 'Data toko berhasil diupdate');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode == 1062 || $errorCode == 19) {
                return redirect()->route('view.toko')->with('error', 'Gagal mengupdate data! Kode Toko Baru atau Kode Toko Lama sudah terdaftar di database.');
            }
            return redirect()->route('view.toko')->with('error', 'Gagal mengupdate data! Terjadi kesalahan relasi atau integritas database.');
        } catch (\Exception $e) {
            return redirect()->route('view.toko')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('view.toko')->with('error', 'Gagal memproses file! Terjadi kesalahan database. Silakan periksa kembali kecocokan data file.');
        } catch (\Exception $e) {
            return redirect()->route('view.toko')->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }
}
