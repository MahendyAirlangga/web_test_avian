@extends('layouts.app')

@section('title', 'Daftar Kode Toko')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3>Toko</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Toko</li>
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
                        <li class="list-group-item px-0 py-1 text-danger fw-semibold"><i class="fa fa-times-circle me-2"></i>Kode Toko Lama: {{ $dup }}</li>
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
                            <i class="fa fa-store me-2 text-primary"></i>Daftar Kode Toko
                        </h5>
                        <span class="badge bg-primary rounded-pill">{{ count($data) }} Toko</span>
                    </div>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive">

                        <table id="table-kode-toko" class="table table-hover align-middle w-100">
                            <thead>
                                <tr>
                                    <th class="text-center" width="50">#</th>
                                    <th>Kode Toko Baru</th>
                                    <th>Kode Toko Lama</th>
                                    <th class="text-center" width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $item)
                                <tr>
                                    <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $item->kode_toko_baru }}</td>
                                    <td>{{ $item->kode_toko_lama ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">

                                            <button type="button"
                                                    class="btn btn-sm btn-danger px-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $item->kode_toko_baru }}">
                                                <i class="fa fa-trash me-1"></i>
                                                Hapus
                                            </button>

                                            <button type="button"
                                                    class="btn btn-sm btn-info px-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateModal{{ $item->kode_toko_baru }}">
                                                <i class="fa fa-edit me-1"></i>
                                                Update
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
                <h5 class="modal-title"><i class="fa fa-plus me-2"></i> Tambah Data Toko</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('create.toko') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="kode_toko_lama" class="form-label fw-bold">Kode Toko Lama</label>
                        <input type="text"
                               id="kode_toko_lama"
                               name="kode_toko_lama"
                               class="form-control form-control-lg border-2"
                               placeholder="Masukkan Kode Toko Lama..."
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
                <h5 class="modal-title"><i class="fa fa-file-excel me-2"></i> Import Excel Toko</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('import.toko') }}" method="POST" enctype="multipart/form-data">
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
                        <span class="badge bg-secondary px-3 py-2 text-wrap">kode_toko_lama</span>
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

<!-- MODAL DELETE DATA -->
@foreach ($data as $item)
<div class="modal fade" id="deleteModal{{ $item->kode_toko_baru }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('destroy.toko', $item->kode_toko_baru) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body py-4 text-center">
                    <i class="fa fa-trash-alt text-danger fa-3x mb-3"></i>
                    <p class="mb-0 fs-6">Apakah Anda yakin ingin menghapus data toko dengan kode <strong>{{ $item->kode_toko_baru }}</strong>?</p>
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

<!-- MODAL UPDATE DATA -->

<div class="modal fade" id="updateModal{{ $item->kode_toko_baru }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fa fa-edit me-2"></i>Update Data Toko</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('update.toko', $item->kode_toko_baru) }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="kode_toko_baru" class="form-label fw-bold">Kode Toko Baru</label>
                        <input type="text"
                               id="kode_toko_baru"
                               name="kode_toko_baru"
                               class="form-control form-control-lg border-2"
                               value="{{ $item->kode_toko_baru }}"
                               readonly
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="kode_toko_lama" class="form-label fw-bold">Kode Toko Lama</label>
                        <input type="text"
                               id="kode_toko_lama"
                               name="kode_toko_lama"
                               class="form-control form-control-lg border-2"
                               value="{{ $item->kode_toko_lama }}"
                               required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info px-4">Update</button>
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
    var table = $('#table-kode-toko').DataTable({
        pageLength: 10,
        dom: '<"row mb-3"<"col-sm-6 d-flex align-items-center gap-3"lB><"col-sm-6 d-flex justify-content-end"f>>rt<"row mt-3"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel me-1"></i> Excel',
                className: 'btn btn-success btn-sm',
                title: 'Data Kode Toko',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-danger btn-sm',
                title: 'Data Kode Toko',
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
            searchPlaceholder: " Cari toko...",
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