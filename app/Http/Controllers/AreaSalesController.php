<?php

namespace App\Http\Controllers;

use App\Models\AreaSales;
use App\Models\KodeToko;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AreaSalesController extends Controller
{
    public function index()
    {
        $data = AreaSales::orderBy('kode_toko')->orderBy('area_sales')->get();
        $kode_toko_list = KodeToko::select('kode_toko_baru', 'kode_toko_lama')->get();
        return view('fiture.area_sales.index', compact('data', 'kode_toko_list'));
    }

    public function createAreaSales(Request $request)
    {
        $request->validate([
            'kode_toko'  => 'required',
            'area_sales' => 'required',
        ], [
            'kode_toko.required'  => 'Kode toko harus diisi.',
            'area_sales.required' => 'Area sales harus diisi.',
        ]);

        $kodeToko  = trim($request->kode_toko);
        $areaSales = trim($request->area_sales);

        $exists = AreaSales::where('kode_toko', $kodeToko)
            ->where('area_sales', $areaSales)
            ->exists();
        if ($exists) {
            return redirect()->route('view.area_sales')->with(
                'error',
                'Gagal! Kombinasi Kode Toko "' . $kodeToko . '" dengan Area "' . $areaSales . '" sudah terdaftar.'
            );
        }

        try {
            AreaSales::insert([
                'kode_toko'  => $kodeToko,
                'area_sales' => $areaSales,
            ]);
            return redirect()->route('view.area_sales')->with('success', 'Data area sales berhasil ditambahkan');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode == 1062 || $errorCode == 19) {
                return redirect()->route('view.area_sales')->with('error', 'Gagal menyimpan data! Kombinasi Kode Toko dengan Area sudah terdaftar di database.');
            }
            return redirect()->route('view.area_sales')->with('error', 'Gagal menyimpan data! Pastikan relasi kode toko sudah benar.');
        } catch (\Exception $e) {
            return redirect()->route('view.area_sales')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateAreaSales(Request $request, $id)
    {
        $request->validate([
            'area_sales_lama' => 'required',
            'area_sales'      => 'required',
        ], [
            'area_sales_lama.required' => 'Data area sales tidak valid.',
            'area_sales.required'      => 'Area sales harus diisi.',
        ]);

        $kodeToko      = $id;
        $areaSalesLama = trim($request->area_sales_lama);
        $newArea       = trim($request->area_sales);

        $exists = AreaSales::where('kode_toko', $kodeToko)
            ->where('area_sales', $areaSalesLama)
            ->exists();

        if (!$exists) {
            return redirect()->route('view.area_sales')->with('error', 'Data area sales tidak ditemukan');
        }

        if ($areaSalesLama !== $newArea) {
            $conflict = AreaSales::where('kode_toko', $kodeToko)
                ->where('area_sales', $newArea)
                ->exists();
            if ($conflict) {
                return redirect()->route('view.area_sales')->with(
                    'error',
                    'Gagal! Kombinasi Kode Toko "' . $kodeToko . '" dengan Area "' . $newArea . '" sudah terdaftar.'
                );
            }
        }

        try {
            AreaSales::where('kode_toko', $kodeToko)
                ->where('area_sales', $areaSalesLama)
                ->update(['area_sales' => $newArea]);
            return redirect()->route('view.area_sales')->with('success', 'Data area sales berhasil diupdate');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode == 1062 || $errorCode == 19) {
                return redirect()->route('view.area_sales')->with('error', 'Gagal mengupdate data! Kombinasi Kode Toko dengan Area sudah terdaftar di database.');
            }
            return redirect()->route('view.area_sales')->with('error', 'Gagal mengupdate data! Pastikan input dan relasi database benar.');
        } catch (\Exception $e) {
            return redirect()->route('view.area_sales')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyAreaSales(Request $request, $id)
    {
        $request->validate([
            'area_sales' => 'required',
        ], [
            'area_sales.required' => 'Data area sales tidak valid.',
        ]);

        $kodeToko  = $id;
        $areaSales = trim($request->area_sales);

        try {
            $deleted = AreaSales::where('kode_toko', $kodeToko)
                ->where('area_sales', $areaSales)
                ->delete();

            if ($deleted) {
                return redirect()->route('view.area_sales')->with('success', 'Data area sales berhasil dihapus');
            }
            return redirect()->route('view.area_sales')->with('error', 'Data area sales tidak ditemukan');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('view.area_sales')->with('error', 'Gagal menghapus data! Data ini memiliki relasi aktif di database.');
        } catch (\Exception $e) {
            return redirect()->route('view.area_sales')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function importAreaSales(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:4096'
        ], [
            'excel_file.required' => 'File harus diunggah.',
            'excel_file.mimes'   => 'Format file harus .xlsx, .xls, atau .csv.',
            'excel_file.max'     => 'Ukuran file tidak boleh lebih dari 4 MB.'
        ]);

        try {
            $file        = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray();

            if (count($rows) <= 1) {
                return redirect()->route('view.area_sales')->with('error', 'File excel tidak memiliki baris data.');
            }

            $header         = array_shift($rows);
            $kodeTokoIndex  = -1;
            $areaSalesIndex = -1;

            foreach ($header as $index => $colName) {
                if ($colName) {
                    $cleanedName = strtolower(trim($colName));
                    if ($cleanedName === 'kode_toko') {
                        $kodeTokoIndex = $index;
                    } elseif ($cleanedName === 'area_sales' || $cleanedName === 'area') {
                        $areaSalesIndex = $index;
                    }
                }
            }

            if ($kodeTokoIndex === -1)  $kodeTokoIndex  = 0;
            if ($areaSalesIndex === -1) $areaSalesIndex = 1;

            $importedCount = 0;
            $duplicateRows = [];

            foreach ($rows as $row) {
                $kodeToko  = isset($row[$kodeTokoIndex])  ? trim($row[$kodeTokoIndex])  : null;
                $areaSales = isset($row[$areaSalesIndex]) ? trim($row[$areaSalesIndex]) : null;

                if (empty($kodeToko) || empty($areaSales)) {
                    continue;
                }

                $exists = AreaSales::where('kode_toko', $kodeToko)
                    ->where('area_sales', $areaSales)
                    ->exists();
                if ($exists) {
                    $duplicateRows[] = 'Kode Toko: ' . $kodeToko . ' + Area: ' . $areaSales;
                    continue;
                }

                AreaSales::insert([
                    'kode_toko'  => $kodeToko,
                    'area_sales' => $areaSales,
                ]);

                $importedCount++;
            }

            $response = redirect()->route('view.area_sales');
            if (count($duplicateRows) > 0) {
                $response = $response->with('duplicates', $duplicateRows);
            }

            if ($importedCount === 0) {
                return $response->with('error', 'Tidak ada data baru yang berhasil ditambahkan.');
            }

            return $response->with('success', "$importedCount data area sales berhasil diimpor.");
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('view.area_sales')->with('error', 'Gagal memproses file! Terjadi kesalahan database. Pastikan relasi kode toko sudah terdaftar di data toko.');
        } catch (\Exception $e) {
            return redirect()->route('view.area_sales')->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }
}
