<!doctype html>
<html lang="en">

<head>
    <title>:: Iconic :: Home</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Iconic Bootstrap 4.5.0 Admin Template" />
    <meta name="author" content="WrapTheme, design by: ThemeMakker.com" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/charts-c3/plugin.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert/sweetalert.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/dropify/css/dropify.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/dropify/css/dropify.min.css') }}" />

    <!-- SELECT2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css">

    <!-- DATATABLES CSS (STABLE VERSION 1.13.6) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap + Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- jQuery UI (optional) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- BUNDLE JS (jika diperlukan untuk komponen vendor) -->
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>

    <script src="{{ asset('assets/vendor/dropify/js/dropify.min.js') }}"></script>

    <!-- CUSTOM STYLE -->
    <style>
        #main-content {
            margin-right: 30px !important;
        }

        .parsley-errors-list {
            color: red;
            font-size: 0.875em;
            list-style: none;
            padding-left: 0;
            margin-top: 0.25rem;
        }

        .radio-form-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .radio-form-group .form-control,
        .radio-form-group .custom-select {
            max-width: 100px;
        }

        .radio-form-group .separator {
            font-weight: 500;
            color: #555;
        }

        .flex-form-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .flex-form-group label {
            width: 100px;
            margin-bottom: 0;
        }

        select.custom-select {
            width: 450px;
        }

        .flex-form-group .form-inputs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .flex-form-group select {
            max-width: 120px;
        }

        td.details-control {
            background: url('<?= asset('assets/images/details_open.png') ?>') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url('<?= asset('assets/images/details_close.png') ?>') no-repeat center center;
        }

        .select2-selection__choice {
            background-color: #f40000 !important;
            border: none !important;
            color: #fff !important;
            border-radius: 0.375rem !important;
            padding: 0.25rem 0.5rem 0.25rem 0rem !important;
            font-size: 0.875rem !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.4rem !important;
        }

        .select2-container--bootstrap4 .select2-results__option--highlighted,
        .select2-container--bootstrap4 .select2-results__option--highlighted.select2-results__option[aria-selected="true"] {
            color: #fff;
            background-color: #f40000 !important;
        }
        .select2-selection__choice__remove {
            color: #fff !important;
        }

        .btn-secondary {
            color: #fff !important;
            background-color: #6c757d !important;
            border-color: #6c757d !important;
        }

        .form-label,
        .form-check-label,
        .form-control,
        .form-select,
        .form-check-input,
        .btn {
            font-size: 15px;
            /* Perbesar font */
        }

        .form-section {
            text-align: left;
            padding: 1rem 1.5rem;
        }

        .form-section .form-check {
            margin-bottom: 0.5rem;
        }

        .form-section .row>div {
            margin-bottom: 0.75rem;
        }

        #filterForm label {
            font-weight: 500;
        }

        #filterForm .btn {
            font-weight: bold;
            font-size: 15px;
            padding: 8px 20px;
        }

        @media (min-width: 768px) {

            #filterForm .row .col-md-6,
            #filterForm .row .col-md-4 {
                padding-right: 1rem;
            }
        }
    </style>
</head>

<body data-theme="light" class="font-nunito">
    <div id="wrapper" class="theme-purple">