@include('layouts.sidebar')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Alamat Pegawai: {{ $pegawai->NAMA_PEGAWAI ?? '-' }}</h2>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='/pegawai'">
                Kembali
            </button>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="alamat_page">
                    <div class="tab-content">
                        <div class="header">
                            @if(session('resp_msg'))
                                <div class="alert alert-success">{{ session('resp_msg') }}</div>
                            @endif
                            @if(session('err_msg'))
                                <div class="alert alert-danger">{{ session('err_msg') }}</div>
                            @endif
                            <div class="row">
                                <div class="col-9">
                                    <h2>Daftar Alamat</h2>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-primary float-right" onclick="openModal()">
                                        Tambah Alamat&nbsp;<i class="fa fa-plus"></i>
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
                                            <th>Alamat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Form Alamat -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="modalFormLabel">Form Alamat</h4>
            </div>
            <form action="{{ url('alamat/save') }}" id="form_alamat" method="POST">
                @csrf
                <input type="hidden" name="id_alamat">
                <input type="hidden" name="nip" value="{{ $nip }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="alamat" class="form-label">Alamat <small class="text-danger">*</small></label>
                            <textarea id="alamat" name="alamat" class="form-control" rows="4"
                                placeholder="Masukkan alamat" required></textarea>
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
        filterData();
    });

    function reset() {
        $('input[name="id_alamat"]').val('');
        $('#alamat').val('');
    }

    function filterData() {
        var element = $('#basic-datatable');
        var totPagesLoad = 2;
        var dataUrl = "{{ url('alamat/all-data/' . $nip) }}";
        var dataBody = {
            '_token': $('meta[name="csrf-token"]').attr('content')
        };
        var dataColumn = [
            { data: 'ALAMAT' },
            { data: 'ACTION_BUTTON' }
        ];

        processingDataTable(element, totPagesLoad, dataUrl, dataBody, dataColumn);
    }

    function openModal(rawData) {
        reset();
        $('#modalForm').modal('show');

        if (rawData) {
            let data = JSON.parse(rawData);
            $('input[name="id_alamat"]').val(data.ID_ALAMAT);
            $('#alamat').val(data.ALAMAT);
        }
    }

    function submitForm() {
        var form = $('#form_alamat')[0];
        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }
        Swal.fire({
            title: "Saving...",
            html: "Please wait, the system is still saving...",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
        $('#form_alamat').submit();
    }

    function deleteModal(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Alamat akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Deleting...",
                    html: "Please wait, the system is still deleting...",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => Swal.showLoading()
                });
                window.location.href = "{{ url('alamat/delete') }}/" + id;
            }
        });
    }
</script>