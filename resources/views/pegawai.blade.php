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
                    <h2>Data Pegawai</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html"><i class="fa fa-dashboard"></i></a></li>
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item active">Pegawai</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="pegawai_page">
                    <div class="tab-content">
                        <div class="header">
                            @if (session('resp_msg'))
                                <div class="alert alert-success">{{ session('resp_msg') }}</div>
                            @endif
                            @if (session('err_msg'))
                                <div class="alert alert-danger">{{ session('err_msg') }}</div>
                            @endif
                            <div class="row">
                                <div class="col-9">
                                    <h2>Master Data Pegawai</h2>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-primary float-right" onclick="openModal()">
                                        Tambah Pegawai&nbsp;<i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Jenis Kelamin</label>
                                        <select id="filter_jk" class="form-control select2-jk">
                                            <option value="">Semua</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Golongan</label>
                                        <select id="filter_golongan" class="form-control select2-golongan">
                                            <option value="">Semua Golongan</option>
                                            @foreach($golongan as $gol)
                                                <option value="{{ $gol->ID_GOLONGAN }}">{{ $gol->NAMA_GOLONGAN }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Eselon</label>
                                        <select id="filter_eselon" class="form-control select2-eselon">
                                            <option value="">Semua Eselon</option>
                                            @foreach($eselon as $es)
                                                <option value="{{ $es->ID_ESELON }}">{{ $es->NAMA_ESELON }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Jabatan</label>
                                        <select id="filter_jabatan" class="form-control select2-jabatan">
                                            <option value="">Semua Jabatan</option>
                                            @foreach($jabatan as $jab)
                                                <option value="{{ $jab->ID_JABATAN }}">{{ $jab->NAMA_JABATAN }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Tempat Tugas</label>
                                        <select id="filter_tempat_tugas" class="form-control select2-kota">
                                            <option value="">Semua Kota</option>
                                            @foreach($kota as $k)
                                                <option value="{{ $k->ID_KOTA }}">{{ $k->NAMA_KOTA }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 d-flex align-items-end justify-content-end"
                                        style="gap:.5rem;">
                                        <button type="button" class="btn btn-success" onclick="exportExcel()">
                                            <i class="fa fa-file-excel-o"></i> Export to Excel
                                        </button>
                                        <button type="button" id="btnFilter" class="btn btn-info">
                                            <i class="fa fa-filter"></i> Filter Table
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover js-basic-example table-custom"
                                    id="basic-datatable" role="grid" style="width: 100% !important;">
                                    <thead>
                                        <tr role="row">
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Tempat Lahir</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Golongan</th>
                                            <th>Eselon</th>
                                            <th>Jabatan</th>
                                            <th>Unit Kerja</th>
                                            <th>Tempat Tugas</th>
                                            <th>Agama</th>
                                            <th>Alamat</th>
                                            <th>No Telepon</th>
                                            <th>NPWP</th>
                                            <th>Foto</th>
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

<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="modalFormLabel">Form Pegawai</h4>
            </div>
            <form action="<?= url('pegawai/submit') ?>" id="form_pegawai" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="nip">
                <div class="modal-body">
                    <input type="hidden" name="old_nip">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nip" class="form-label">NIP <small class="text-danger">*</small></label>
                            <input type="text" id="nip" name="nip" class="form-control" placeholder="Masukkan NIP"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="nama_pegawai" class="form-label">Nama Pegawai <small
                                    class="text-danger">*</small></label>
                            <input type="text" id="nama_pegawai" name="nama_pegawai" class="form-control"
                                placeholder="Masukkan Nama" required>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                                placeholder="Masukkan Tempat Lahir">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="form-label">Jenis Kelamin <small class="text-danger">*</small></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l"
                                        value="L" required>
                                    <label class="form-check-label" for="jk_l">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p"
                                        value="P" required>
                                    <label class="form-check-label" for="jk_p">Perempuan</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <label class="form-label">Golongan</label>
                            <select name="id_golongan" class="form-control select2-golongan">
                                <option value="">-- Pilih Golongan --</option>
                                @foreach($golongan as $gol)
                                    <option value="{{ $gol->ID_GOLONGAN }}">{{ $gol->NAMA_GOLONGAN }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="form-label">Eselon</label>
                            <select name="id_eselon" class="form-control select2-eselon">
                                <option value="">-- Pilih Eselon --</option>
                                @foreach($eselon as $es)
                                    <option value="{{ $es->ID_ESELON }}">{{ $es->NAMA_ESELON }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="form-label">Jabatan</label>
                            <select name="id_jabatan" class="form-control select2-jabatan">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($jabatan as $jab)
                                    <option value="{{ $jab->ID_JABATAN }}">{{ $jab->NAMA_JABATAN }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="form-label">Unit Kerja</label>
                            <select name="id_unit_kerja" class="form-control select2-unit">
                                <option value="">-- Pilih Unit Kerja --</option>
                                @foreach($unit_kerja as $unit)
                                    <option value="{{ $unit->ID_UNIT_KERJA }}">{{ $unit->NAMA_UNIT }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="form-label">Tempat Tugas</label>
                            <select name="tempat_tugas" class="form-control select2-kota">
                                <option value="">-- Pilih Kota --</option>
                                @foreach($kota as $k)
                                    <option value="{{ $k->ID_KOTA }}">{{ $k->NAMA_KOTA }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="agama" class="form-label">Agama</label>
                            <select name="agama" id="agama" class="form-control select2-agama">
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="no_telepon" class="form-label">No Telepon</label>
                            <input type="text" id="no_telepon" name="no_telepon" class="form-control"
                                placeholder="Masukkan No Telepon">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="npwp" class="form-label">NPWP</label>
                            <input type="text" id="npwp" name="npwp" class="form-control" placeholder="Masukkan NPWP">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" id="foto" name="foto" class="form-control">
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
        $('.select2-jk, .select2-golongan, .select2-eselon, .select2-jabatan, .select2-unit, .select2-kota, .select2-agama').select2({
            theme: 'bootstrap4',
            allowClear: true,
            width: '100%'
        });
    });

</script>

<script>
    let dtPegawai = null;

    $(function () {
        $('.select2-jk, .select2-golongan, .select2-eselon, .select2-jabatan, .select2-unit, .select2-kota').select2({
            theme: 'bootstrap4',
            allowClear: true,
            width: '100%'
        });

        dtPegawai = $('#basic-datatable').DataTable({
            serverSide: true,
            processing: true,
            autoWidth: false,
            scrollX: true,
            responsive: false,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[0, 'desc']],
            ajax: {
                url: "{{ url('pegawai/all-data') }}",
                type: "POST",
                data: function (d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.filter_jk = $('#filter_jk').val();
                    d.filter_golongan = $('#filter_golongan').val();
                    d.filter_eselon = $('#filter_eselon').val();
                    d.filter_jabatan = $('#filter_jabatan').val();
                    d.filter_tempat_tugas = $('#filter_tempat_tugas').val();
                }
            },
            columns: [
                { data: 'NIP' },
                { data: 'NAMA_PEGAWAI' },
                { data: 'TEMPAT_LAHIR' },
                { data: 'TGL_LAHIR' },
                { data: 'JENIS_KELAMIN', render: d => d === 'L' ? 'Laki-laki' : (d === 'P' ? 'Perempuan' : '') },
                { data: 'GOLONGAN' },
                { data: 'ESELON' },
                { data: 'JABATAN' },
                { data: 'UNIT_KERJA' },
                { data: 'TEMPAT_TUGAS' },
                { data: 'AGAMA' },
                { data: 'ALAMAT' },
                { data: 'NO_TELEPON' },
                { data: 'NPWP' },
                { data: 'FOTO', orderable: false, render: d => d ? `<img src="data:image/png;base64,${d}" style="height:50px">` : '' },
                { data: 'ACTION_BUTTON', orderable: false, searchable: false }
            ],
            drawCallback: function () { this.api().columns.adjust(); },
            initComplete: function () { this.api().columns.adjust(); }
        });

     
        $('#btnFilter').on('click', function () {
            dtPegawai.ajax.reload(null, true);
        });

        $('#modalForm').on('shown.bs.modal hidden.bs.modal', function () {
            if (dtPegawai) dtPegawai.columns.adjust();
        });
    });

    function exportExcel() {
        const params = {
            filter_jk: $('#filter_jk').val(),
            filter_golongan: $('#filter_golongan').val(),
            filter_eselon: $('#filter_eselon').val(),
            filter_jabatan: $('#filter_jabatan').val(),
            filter_tempat_tugas: $('#filter_tempat_tugas').val(),
        };
        window.location.href = "{{ url('pegawai/export-excel') }}?" + $.param(params);
    }
</script>
<script>

    function reset() {
        const $form = $('#form_pegawai');
        $form[0].reset();

        $('input[name="old_nip"]').val('');
        $('.select2-jk, .select2-golongan, .select2-eselon, .select2-jabatan, .select2-unit, .select2-kota, .select2-agama')
            .val(null).trigger('change');
        $('input[name="jenis_kelamin"]').prop('checked', false);
        $('#foto').val('');
        $('#modalFormLabel').text('Form Pegawai - Tambah');
    }

    function deleteModal(nip) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Anda tidak akan dapat mengembalikan data Anda!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Deleting...",
                    html: "Please wait, the system is deleting...",
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                window.location.href = "pegawai/delete/" + nip;
            }
        });
    }

    function openModal(rawData) {
        reset();
        $('#modalForm').modal('show');
        if (rawData) {
            let data = JSON.parse(rawData);
            $('input[name="old_nip"]').val(data.NIP);
            $('input[name="nip"]').val(data.NIP);
            $('input[name="nama_pegawai"]').val(data.NAMA_PEGAWAI);
            $('input[name="tempat_lahir"]').val(data.TEMPAT_LAHIR);
            $('input[name="tgl_lahir"]').val(data.TGL_LAHIR);
            $('input[name="no_telepon"]').val(data.NO_TELEPON);
            $('input[name="npwp"]').val(data.NPWP);
            $('.select2-agama').val(data.AGAMA).trigger('change');
            if (data.JENIS_KELAMIN) {
                $(`input[name="jenis_kelamin"][value="${data.JENIS_KELAMIN}"]`).prop('checked', true);
            }
            $('.select2-golongan').val(data.ID_GOLONGAN).trigger('change');
            $('.select2-eselon').val(data.ID_ESELON).trigger('change');
            $('.select2-jabatan').val(data.ID_JABATAN).trigger('change');
            $('.select2-unit').val(data.ID_UNIT_KERJA).trigger('change');
            $('.select2-kota').val(data.ID_KOTA).trigger('change');
        }
    }


    function submitForm() {
        var form = $('#form_pegawai')[0];
        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }
        Swal.fire({
            title: "Saving...",
            html: "Please wait, the system is saving...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        $('#form_pegawai').submit();
    }

    function exportExcel() {
        let params = {
            filter_jk: $('#filter_jk').val(),
            filter_golongan: $('#filter_golongan').val(),
            filter_eselon: $('#filter_eselon').val(),
            filter_jabatan: $('#filter_jabatan').val(),
            filter_tempat_tugas: $('#filter_tempat_tugas').val(),
        };
        let query = $.param(params);
        window.location.href = "{{ url('pegawai/export-excel') }}?" + query;
    }
</script>