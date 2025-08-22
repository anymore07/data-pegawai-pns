@include('layouts.sidebar')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2>Data Unit Kerja</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html"><i class="fa fa-dashboard"></i></a></li>
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item active">Unit Kerja</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="unit_kerja_page">
                    <div class="tab-content">
                        <div class="header">
                            @if (session('resp_msg'))
                                <div class="alert alert-success">
                                    {{ session('resp_msg') }}
                                </div>
                            @endif
                            @if (session('err_msg'))
                                <div class="alert alert-danger">
                                    {{ session('err_msg') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-9">
                                    <h2>Master Data Unit Kerja</h2>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-primary float-right" onclick="openModal()">
                                        Tambah Unit Kerja&nbsp;<i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover js-basic-example table-custom"
                                    id="basic-datatable" role="grid" style="width: 100% !important;">
                                    <thead>
                                        <tr role="row">
                                            <th>Nama Unit</th>
                                            <th>Lokasi</th>
                                            <th>Parent</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="modalFormLabel">Form Unit Kerja</h4>
            </div>
            <form action="<?= url('unit-kerja/submit') ?>" id="form_unit_kerja" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_unit_kerja">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="nama_unit" class="form-label">Nama Unit <small class="text-danger">*</small></label>
                                <input type="text" id="nama_unit" name="nama_unit" class="form-control"
                                    placeholder="Masukkan nama unit kerja" required>
                            </div>
                            <div class="mb-3">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" id="lokasi" name="lokasi" class="form-control"
                                    placeholder="Masukkan lokasi">
                            </div>
                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Parent Unit</label>
                                <select id="parent_id" name="parent_id" class="form-control select2-unit-kerja">
                                    <option value="">-- Tanpa Parent (Root) --</option>
                                    @foreach ($unit_kerja as $uk)
                                        <option value="{{ $uk->ID_UNIT_KERJA }}">{{ $uk->NAMA_UNIT }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="submitForm()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        filterData()

        $('.select2-unit-kerja').select2({
            theme: 'bootstrap4',
            allowClear: true,
            placeholder: '-- Pilih Parent --'
        })
    });

    function reset() {
        $('input[name="id_unit_kerja"]').val('')
        $('input[name="nama_unit"]').val('')
        $('input[name="lokasi"]').val('')
        $('#parent_id').val('').trigger('change')
    }
</script>

<script>
    function filterData() {
        var element = $('#basic-datatable')
        var totPagesLoad = 2
        var dataUrl = "<?= url('unit-kerja/all-data') ?>"
        var dataBody = {
            '_token': $('meta[name="csrf-token"]').attr('content')
        }
        var dataColumn = [
            { data: 'NAMA_UNIT' },
            { data: 'LOKASI' },
            { data: 'PARENT' },
            { data: 'ACTION_BUTTON' }
        ]

        processingDataTable(element, totPagesLoad, dataUrl, dataBody, dataColumn)
    }

    function deleteModal(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Anda tidak akan dapat mengembalikan data Anda!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    title: "Deleting...",
                    html: "Please wait, the system is still deleting...",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                window.location.href = "unit-kerja/delete" + '/' + id;
            }
        });
    }

    function openModal(rawData) {
        reset();
        $('#modalForm').modal('show');

        if (rawData) {
            let data = JSON.parse(rawData);
            $('input[name="id_unit_kerja"]').val(data.ID_UNIT_KERJA);
            $('input[name="nama_unit"]').val(data.NAMA_UNIT);
            $('input[name="lokasi"]').val(data.LOKASI);
            $('#parent_id').val(data.PARENT_ID).trigger('change')
        }
    }

    function submitForm() {
        var isValid = true;
        var form = $('#form_unit_kerja')[0];
        if (!form.checkValidity()) {
            form.reportValidity();
            isValid = false;
            return false;
        }

        if (isValid) {
            Swal.fire({
                title: "Saving...",
                html: "Please wait, the system is still saving...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $('#form_unit_kerja').submit()
        }
    }
</script>
