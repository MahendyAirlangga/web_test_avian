<?php

namespace App\Http\Controllers;

use App\Models\NamaSales;
use App\Models\AreaSales;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SalesController extends Controller
{
    public function index()
    {
        $data = NamaSales::all();

        // Get all existing kode_sales to exclude already-used combinations from dropdown
        $usedKodeSales = NamaSales::pluck('kode_sales')->toArray();

        // Build area_sales list and filter out already-used combinations
        $area_sales_list = AreaSales::select('kode_toko', 'area_sales')
            ->get()
            ->filter(function ($as) use ($usedKodeSales) {
                $combinedCode = $as->area_sales . $as->kode_toko;
                return !in_array($combinedCode, $usedKodeSales);
            })
            ->values();

        return view('fiture.sales.index', compact('data', 'area_sales_list'));
    }

    public function createSales(Request $request)
    {
        $request->validate([
            'kode_sales' => 'required',
            'nama_sales' => 'required',
        ], [
            'kode_sales.required' => 'Kode sales harus diisi.',
            'nama_sales.required' => 'Nama sales harus diisi.',
        ]);

        // Check if Sales already exists
        $exists = NamaSales::where('kode_sales', trim($request->kode_sales))->exists();
        if ($exists) {
            return redirect()->route('view.sales')->with('error', 'Gagal! Kode Sales "' . $request->kode_sales . '" sudah terdaftar.');
        }

        NamaSales::create([
            'kode_sales' => trim($request->kode_sales),
            'nama_sales' => trim($request->nama_sales),
        ]);

        return redirect()->route('view.sales')->with('success', 'Data sales berhasil ditambahkan');
    }

    public function updateSales(Request $request, $id)
    {
        $request->validate([
            'nama_sales' => 'required',
        ], [
            'nama_sales.required' => 'Nama sales harus diisi.',
        ]);

        $sales = NamaSales::where('kode_sales', $id)->first();

        if (!$sales) {
            return redirect()->route('view.sales')->with('error', 'Data sales tidak ditemukan');
        }

        $sales->update([
            'nama_sales' => $request->nama_sales,
        ]);

        return redirect()->route('view.sales')->with('success', 'Data sales berhasil diupdate');
    }

    public function destroySales($id)
    {
        $sales = NamaSales::where('kode_sales', $id)->first();
        if ($sales) {
            $sales->delete();
            return redirect()->route('view.sales')->with('success', 'Data sales berhasil dihapus');
        }
        return redirect()->route('view.sales')->with('error', 'Data sales tidak ditemukan');
    }

    public function importSales(Request $request)
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
                return redirect()->route('view.sales')->with('error', 'File excel tidak memiliki baris data.');
            }

            $header = array_shift($rows);
            $kodeSalesIndex = -1;
            $namaSalesIndex = -1;

            foreach ($header as $index => $colName) {
                if ($colName) {
                    $cleanedName = strtolower(trim($colName));
                    if ($cleanedName === 'kode_sales') {
                        $kodeSalesIndex = $index;
                    } elseif ($cleanedName === 'nama_sales' || $cleanedName === 'sales_name' || $cleanedName === 'sales') {
                        $namaSalesIndex = $index;
                    }
                }
            }

            // Fallback defaults
            if ($kodeSalesIndex === -1) $kodeSalesIndex = 0;
            if ($namaSalesIndex === -1) $namaSalesIndex = 1;

            $importedCount = 0;
            $duplicateRows = [];

            foreach ($rows as $row) {
                $kodeSales = isset($row[$kodeSalesIndex]) ? trim($row[$kodeSalesIndex]) : null;
                $namaSales = isset($row[$namaSalesIndex]) ? trim($row[$namaSalesIndex]) : null;

                if (empty($kodeSales) || empty($namaSales)) {
                    continue;
                }

                // Check if Kode Sales already exists to prevent duplicate entries
                $exists = NamaSales::where('kode_sales', $kodeSales)->exists();
                if ($exists) {
                    $duplicateRows[] = "Kode Sales: " . $kodeSales . " (Nama: " . $namaSales . ")";
                    continue;
                }

                NamaSales::create([
                    'kode_sales' => $kodeSales,
                    'nama_sales' => $namaSales
                ]);

                $importedCount++;
            }

            $response = redirect()->route('view.sales');
            if (count($duplicateRows) > 0) {
                $response = $response->with('duplicates', $duplicateRows);
            }

            if ($importedCount === 0) {
                return $response->with('error', 'Tidak ada data baru yang berhasil ditambahkan.');
            }

            return $response->with('success', "$importedCount data sales berhasil diimpor.");

        } catch (\Exception $e) {
            return redirect()->route('view.sales')->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }
}
