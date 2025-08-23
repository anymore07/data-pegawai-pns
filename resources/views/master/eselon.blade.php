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
                    <h2>Data Eselon</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html"><i class="fa fa-dashboard"></i></a></li>
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item active">Eselon</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="eselon_page">
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
                                    <h2>Master Data Eselon</h2>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-primary float-right" onclick="openModal()">
                                        Tambah Eselon&nbsp;<i class="fa fa-plus"></i>
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
                                            <th>Eselon</th>
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
                <h4 class="title" id="modalFormLabel">Form Eselon</h4>
            </div>
            <form action="<?= url('eselon/submit') ?>" id="form_eselon" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_eselon">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="nama_eselon" class="form-label">Nama Eselon <small
                                        class="text-danger">*</small></label>
                                <input type="text" id="nama_eselon" name="nama_eselon" class="form-control"
                                    placeholder="Masukkan eselon" required>
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

        $('.select2-eselon').select2({
            theme: 'bootstrap4',
            allowClear: true
        })
        DropdownOptionTipe($('.select2-eselon'))

        $('.select2-main-eselon').select2({
            theme: 'bootstrap4',
            allowClear: true,
            placeholder: '-- Pilih Eselon --'
        })
        DropdownOptionTipe($('.select2-main-eselon'))
    });

    function reset() {
        $('input[name="id_eselon"]').val('')
        $('input[name="nama_eselon"]').val('')
        $('.select2-eselon').val('').trigger('change')
    }
</script>

<script>
    function filterData() {
        var element = $('#basic-datatable')
        var totPagesLoad = 2
        var dataUrl = "<?= url('eselon/all-data') ?>"
        var dataBody = {
            '_token': $('meta[name="csrf-token"]').attr('content')
        }
        var dataColumn = [
        {
            data: 'NAMA_ESELON'
        },
        {
            data: 'ACTION_BUTTON'
        }
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
                window.location.href = "eselon/delete" + '/' + id;
            }
        });
    }

    function openModal(rawData) {
        reset();
        $('#modalForm').modal('show');

        if (rawData) {
            let data = JSON.parse(rawData);
            $('input[name="id_eselon"]').val(data.ID_ESELON);
            $('input[name="nama_eselon"]').val(data.NAMA_ESELON);
        }
    }


    function submitForm() {
        var isValid = true;
        var form = $('#form_eselon')[0];
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
            $('#form_eselon').submit()
        }
    }
</script>
