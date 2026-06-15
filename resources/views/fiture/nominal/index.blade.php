@extends('layouts.app')

@section('title', 'Daftar Nominal Transaksi')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3>Nominal Transaksi</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Nominal</li>
                </ol>
            </div>

            <div class="col-sm-6 text-end">
                <button type="button"
                        class="btn btn-success px-4 me-2"
                        data-bs-toggle="modal"
                        data-bs-target="#importExcelModal">
                    <i class="fa fa-file-excel me-2"></i>Import Excel
                </button>
                <button type="button"
                        class="btn btn-primary px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#tambahDataModal">
                    <i class="fa fa-plus me-2"></i>Tambah Data
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    @if(session('duplicates'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-warning border-2 shadow-sm p-4 mb-0" role="alert">
                <div class="d-flex align-items-center mb-3">
                    <i class="fa fa-exclamation-triangle fa-2x text-warning me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1 fw-bold text-dark">Data Duplikat Ditemukan!</h5>
                        <p class="mb-0 text-muted small">Berikut adalah data dari file Excel yang sudah terdaftar di database. Data-data ini telah dilewati (tidak dimasukkan kembali):</p>
                    </div>
                </div>
                <div class="bg-white rounded p-3 border" style="max-height: 150px; overflow-y: auto;">
                    <ul class="list-group list-group-flush small">
                        @foreach(session('duplicates') as $dup)
                        <li class="list-group-item px-0 py-1 text-danger fw-semibold"><i class="fa fa-times-circle me-2"></i>{{ $dup }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3" style="border-bottom: 2px solid #f1f3f5;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fa fa-wallet me-2 text-primary"></i>Daftar Nominal Transaksi
                        </h5>
                        <span class="badge bg-primary rounded-pill">{{ count($data) }} Record</span>
                    </div>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive">

                        <table id="table-nominal" class="table table-hover align-middle w-100">
                            <thead>
                                <tr>
                                    <th class="text-center" width="50">#</th>
                                    <th>Kode Toko</th>
                                    <th>Nominal Transaksi</th>
                                    <th class="text-center" width="180">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $item)
                                <tr>
                                    <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $item->kode_toko }}</td>
                                    <td>Rp {{ number_format($item->nominal_transaksi, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button"
                                                    class="btn btn-sm btn-danger px-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $index }}">
                                                <i class="fa fa-trash me-1"></i> Hapus
                                            </button>

                                            <button type="button"
                                                    class="btn btn-sm btn-info px-3 text-white"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateModal{{ $index }}">
                                                <i class="fa fa-edit me-1"></i> Update
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- MODAL TAMBAH DATA -->
<div class="modal fade" id="tambahDataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fa fa-plus me-2"></i> Tambah Nominal Transaksi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('create.nominal') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="kode_toko" class="form-label fw-bold">Kode Toko</label>
                        <select id="kode_toko"
                                name="kode_toko"
                                class="form-select form-select-lg border-2"
                                required>
                            <option value="" disabled selected>-- Pilih Kode Toko --</option>
                            @foreach ($kode_toko_list as $kt)
                            <option value="{{ $kt->kode_toko_baru }}">{{ $kt->kode_toko_baru }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nominal_transaksi" class="form-label fw-bold">Nominal Transaksi (Rp)</label>
                        <input type="number"
                               id="nominal_transaksi"
                               name="nominal_transaksi"
                               class="form-control form-control-lg border-2"
                               placeholder="Masukkan Nominal..."
                               required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL IMPORT EXCEL -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-file-excel me-2"></i> Import Excel Nominal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('import.nominal') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="excel_file" class="form-label fw-bold">Pilih File Excel / CSV</label>
                        <input type="file"
                               id="excel_file"
                               name="excel_file"
                               class="form-control form-control-lg border-2"
                               accept=".xlsx, .xls, .csv"
                               required>
                        <div class="form-text mt-2">
                            <i class="fa fa-info-circle me-1"></i> Format file harus berupa <strong>.xlsx</strong>, <strong>.xls</strong>, atau <strong>.csv</strong>.
                        </div>
                    </div>
                    <div class="bg-light p-3 rounded border border-dashed text-center">
                        <span class="small text-muted d-block mb-1">Struktur Kolom Excel:</span>
                        <div class="d-flex gap-2 justify-content-center">
                            <span class="badge bg-secondary px-3 py-2 text-wrap">kode_toko</span>
                            <span class="badge bg-secondary px-3 py-2 text-wrap">nominal_transaksi</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4"><i class="fa fa-upload me-1"></i>Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL UPDATE DATA & DELETE DATA -->
@foreach ($data as $index => $item)
<div class="modal fade" id="updateModal{{ $index }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fa fa-edit me-2"></i>Update Nominal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('update.nominal', $item->kode_toko) }}" method="POST">
                @csrf
                <input type="hidden" name="nominal_transaksi_lama" value="{{ $item->nominal_transaksi }}">
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kode Toko</label>
                        <input type="text"
                               class="form-control form-control-lg border-2 bg-light"
                               value="{{ $item->kode_toko }}"
                               readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nominal_transaksi" class="form-label fw-bold">Nominal Transaksi (Rp)</label>
                        <input type="number"
                               id="nominal_transaksi"
                               name="nominal_transaksi"
                               class="form-control form-control-lg border-2"
                               value="{{ $item->nominal_transaksi }}"
                               required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info px-4 text-white">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal{{ $index }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('destroy.nominal', $item->kode_toko) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="nominal_transaksi" value="{{ $item->nominal_transaksi }}">
                <div class="modal-body py-4 text-center">
                    <i class="fa fa-trash-alt text-danger fa-3x mb-3"></i>
                    <p class="mb-0 fs-6">Apakah Anda yakin ingin menghapus data nominal <strong>Rp {{ number_format($item->nominal_transaksi, 0, ',', '.') }}</strong> untuk toko <strong>{{ $item->kode_toko }}</strong>?</p>
                    <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">Ya, Hapus!</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#table-nominal').DataTable({
        pageLength: 10,
        dom: '<"row mb-3"<"col-sm-6 d-flex align-items-center gap-3"lB><"col-sm-6 d-flex justify-content-end"f>>rt<"row mt-3"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel me-1"></i> Excel',
                className: 'btn btn-success btn-sm',
                title: 'Data Nominal Transaksi',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-danger btn-sm',
                title: 'Data Nominal Transaksi',
                exportOptions: { columns: ':not(:last-child)' },
                orientation: 'landscape',
                pageSize: 'A4'
            }
        ],
        drawCallback: function() {
            $('.dataTables_length select').addClass('form-select form-select-sm px-2 mx-1').css({
                'display': 'inline-block',
                'width': '60px',
                'height': '35px',
                'padding': '5px 10px',
                'border': '1px solid #dee2e6',
                'border-radius': '6px',
                'appearance': 'auto',
                '-webkit-appearance': 'auto',
                'background-image': 'none'
            });
        },
        language: {
            zeroRecords: '<div class="text-center py-4 text-muted"><i class="fa fa-inbox fa-2x mb-2 d-block"></i>Tidak ada data ditemukan</div>',
            info: "Menampilkan <b>_START_</b> sampai <b>_END_</b> dari <b>_TOTAL_</b> data",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(disaring dari <b>_MAX_</b> total data)",
            search: "",
            searchPlaceholder: " Cari nominal...",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: {
                first: "«",
                last: "»",
                next: "›",
                previous: "‹"
            }
        }
    });
});
</script>
@endpush
