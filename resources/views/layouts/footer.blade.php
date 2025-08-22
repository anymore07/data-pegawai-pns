</div>

<!-- Footer JS -->
<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/vendor/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/forms/dropify.js') }}"></script>
<script src="{{ asset('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>
<script src="https://parsleyjs.org/dist/parsley.min.js"></script>

<!-- Javascript Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Vendor bundles (local assets) -->

<!-- Page-specific JS -->
<script src="{{ asset('assets/bundles/c3.bundle.js') }}"></script>
<script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/index.js') }}"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>


<script src="{{ asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>

{{-- Select 2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- page js file -->
<script src="{{ asset('assets/js/pages/tables/jquery-datatable.js') }}"></script>
<script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.print.min.js') }}"></script>

<script src="{{ asset('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>

<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>


<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<script src="{{ asset('assets/vendor/editable-table/mindmup-editabletable.js') }}"></script> <!-- Editable Table Plugin Js -->
<script src="{{ asset('assets/js/pages/tables/editable-table.js') }}"></script>

<script>
    // Multiple Pipeline
    $.fn.dataTable.pipeline = function(opts) {
        var conf = $.extend({
            pages: 5,
            method: 'GET'
        }, opts);

        var cache = {
            lower: -1,
            upper: null,
            lastRequest: null,
            lastJson: null
        };

        return function(request, drawCallback, settings) {
            var requestStart = request.start;
            var requestLength = request.length;
            var requestEnd = requestStart + requestLength;
            var ajax = false;

            // Clear cache condition
            if (settings.clearCache) {
                ajax = true;
                settings.clearCache = false;
            } else if (cache.lower < 0 || requestStart < cache.lower || requestEnd > cache.upper) {
                ajax = true;
            } else if (
                !cache.lastRequest ||
                request.order.toString() !== cache.lastRequest.order.toString() ||
                request.columns.toString() !== cache.lastRequest.columns.toString() ||
                request.search.value !== cache.lastRequest.search.value
            ) {
                ajax = true;
            }

            cache.lastRequest = $.extend({}, request); // shallow copy for performance

            if (ajax) {
                // Adjust start for prefetching
                var start = requestStart - requestLength * (conf.pages - 1);
                start = Math.max(start, 0);

                cache.lower = start;
                cache.upper = start + requestLength * conf.pages;

                request.start = start;
                request.length = requestLength * conf.pages;

                // Merge extra data
                if (typeof conf.data === 'function') {
                    var extraData = conf.data(request);
                    if (extraData) $.extend(request, extraData);
                } else if ($.isPlainObject(conf.data)) {
                    $.extend(request, conf.data);
                }

                return $.ajax({
                    type: conf.method,
                    url: conf.url,
                    data: request,
                    dataType: 'json',
                    cache: false,
                    success: function(json) {
                        cache.lastJson = json;

                        var data = json.data.slice(requestStart - cache.lower, requestStart - cache.lower + requestLength);
                        drawCallback($.extend({}, json, {
                            data: data
                        }));
                    }
                });
            } else {
                var json = cache.lastJson;
                var data = json.data.slice(requestStart - cache.lower, requestStart - cache.lower + requestLength);

                drawCallback($.extend({}, json, {
                    draw: request.draw,
                    data: data
                }));
            }
        };
    };

    function processingDataTable(element, totPagesLoad, dataUrl, dataBody, dataColumn, method = 'POST') {
        if ($.fn.DataTable.isDataTable(element)) {
            element.DataTable().clear().destroy();
        }

        return element.DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ordering: false,
            initComplete: function() {
                const api = this.api();
                const $input = $(api.table().container()).find('.dataTables_filter input');

                $input.off('keyup.DT input.DT')
                    .on('keypress', function(e) {
                        if (e.which === 13) {
                            api.search(this.value).draw();
                        }
                    });

                $input.attr('title', 'Press Enter to search')
                    .tooltip({
                        placement: 'bottom',
                        trigger: 'manual'
                    });

                $input.on('focus', function() {
                    $(this).tooltip('show');
                }).on('blur', function() {
                    $(this).tooltip('hide');
                });
            },
            language: {
                processing: `
                    <div class="d-flex align-items-center justify-content-center" style="gap: .5rem;">
                        <i class="fa fa-spinner fa-spin" style="font-size: 35px;"></i><span>Loading...</span>
                    </div>
                `,
                loadingRecords: " ",
                emptyTable: "<h4 class='mb-0 py-3'> Data kosong atau tidak ditemukan </h4>"
            },
            ajax: $.fn.dataTable.pipeline({
                pages: totPagesLoad,
                url: dataUrl,
                crossDomain: true,
                data: dataBody,
                method: method,
            }),
            infoCallback: function(settings, start, end, max, total, pre) {
                return (!isNaN(total) && total > 0) ?
                    `Showing ${start} to ${end} of ${total} entries${(total !== max ? ` (filtered from ${max} total entries)` : '')}` :
                    "No Data to Show";
            },
            columns: dataColumn,
        });
    }

    function showLoadingSwal(title = 'Memproses...', html = 'Mohon tunggu sebentar.') {
        Swal.fire({
            title: title,
            html: html,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    function showErrorSwal(title = 'Terjadi Kesalahan', text = 'Silakan coba lagi.') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: text,
            confirmButtonText: 'OK'
        });
    }

    function showSuccessSwal(title = 'Berhasil!', text = 'Data berhasil diproses.') {
        Swal.fire({
            icon: 'success',
            title: title,
            text: text,
            confirmButtonText: 'OK'
        });
    }
</script>

</body>

</html>
