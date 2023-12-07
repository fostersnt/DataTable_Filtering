@extends('layouts.admin')
@push('main')
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.7/b-2.4.2/b-html5-2.4.2/datatables.min.css" rel="stylesheet">
    {{-- <link href="https://cdn.datatables.net/v/bs5/datatables.min.css" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.bootstrap5.min.css"> --}}
    {{-- @include('datatable.css') --}}

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <h3>Reports</h3>
                    </div>
                    <div class="col-12 col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"> <i data-feather="home"></i></a></li>
                            <li class="breadcrumb-item">Reports</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="">Category</label>
                                    <select class="form-select" name="category" id="category">
                                        <option value="">-- select category --</option>
                                        <option value="Invalid Token">Invalid Token</option>
                                        <option value="Invalid Age Range">Invalid Age Range</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Start Date</label>
                                    <input class="form-control" type="date" name="start_date" id="start_date">
                                </div>
                                <div class="col-md-2">
                                    <label for="">End Date</label>
                                    <input class="form-control" type="date" name="end_date" id="end_date">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary" style="margin-top: 30px;" id="filter">Filter</button>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-warning" style="margin-top: 30px;"
                                        id="refresh">Refresh</button>
                                </div>
                            </div>
                            <div class="dt-ext column-visibility.dt table-responsive">
                                {{-- {{ $dataTable->table(['class' => 'display', 'id'=>'my_table'], true) }} --}}
                                <a href="#" class="btn btn-primary mb-3 mt-3" id="my_export_btn">Export All To
                                    Excel</a>
                            </div>
                            <!--SECOND TABLE-->
                            <div class="">
                                <table id="chale_errors_report" class="table" style="width:100%">
                                    <thead>
                                        <tr>
                                            {{-- <th>ID</th> --}}
                                            <th>Nominee</th>
                                            <th>Nominee Phone</th>
                                            <th>Nominator Phone</th>
                                            <th>Code</th>
                                            <th>Transaction ID</th>
                                            <th>Response</th>
                                            <th>Age Range</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endpush
{{-- @push('js')
    {{ $dataTable->scripts() }}
@endpush --}}

@push('js')
    {{-- {{ $dataTable->scripts() }} --}}
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script> --}}


    <!--BUTTONS-->
    {{-- <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        // $('#my_export_btn').click(function() {
        //     var category = $('#category').val();
        //     var start_date = $('#start_date').val();
        //     var end_date = $('#end_date').val();
        //     window.location.href = `/chale_errors_export?category=${category}&start_date=${start_date}&end_date=${end_date}`;
        // })
    </script>
    <script>
        $('#chale_errors_report')
        .on('preXhr.dt', function(e, settings, data) {
            data.start_date = $('#start_date').val();
            data.end_date = $('#end_date').val();
            data.category = $('#category').val();
            // data.search_value = $('#global_search_input_field').val();
            // data.email = $('#email').val();
            // data.stage = $('#stage').val();
            // data.status = $('#status').val();
        });

    $('#filter').on('click', function() {
        $('#chale_errors_report').DataTable().ajax.reload();
        return false;
    });
</script>
    <script>
        $(document).ready(function() {
            $('#chale_errors_report').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('chale.errors.report') }}",
                    type: 'GET'
                },
                columns: [
                    // { data: 'id', name: 'id' },
                    {
                        data: 'nominee',
                        name: 'nominee',
                        render: function(data, type, full, meta) {
                            if (data == '' || data == null) {
                                // Format the date using Moment.js
                                return 'N/A'; // Customize the format
                            }
                            return data; // Return the original data for sorting and other types
                        }
                    },
                    {
                        data: 'nominee_phone',
                        name: 'nominee_phone',
                        render: function(data, type, full, meta) {
                            if (data == '' || data == null) {
                                // Format the date using Moment.js
                                return 'N/A'; // Customize the format
                            }
                            return data; // Return the original data for sorting and other types
                        }
                    },
                    {
                        data: 'nominator_phone',
                        name: 'nominator_phone',
                        render: function(data, type, full, meta) {
                            if (data == '' || data == null) {
                                // Format the date using Moment.js
                                return 'N/A'; // Customize the format
                            }
                            return data; // Return the original data for sorting and other types
                        }
                    },
                    {
                        data: 'code',
                        name: 'code',
                        render: function(data, type, full, meta) {
                            if (data == '' || data == null) {
                                // Format the date using Moment.js
                                return 'N/A'; // Customize the format
                            }
                            return data; // Return the original data for sorting and other types
                        }
                    },
                    {
                        data: 'transaction_id',
                        name: 'transaction_id',
                        render: function(data, type, full, meta) {
                            if (data == '' || data == null) {
                                // Format the date using Moment.js
                                return 'N/A'; // Customize the format
                            }
                            return data; // Return the original data for sorting and other types
                        }
                    },
                    {
                        data: 'response',
                        name: 'response',
                        render: function(data, type, full, meta) {
                            if (data == '' || data == null) {
                                // Format the date using Moment.js
                                return 'N/A'; // Customize the format
                            }
                            return data; // Return the original data for sorting and other types
                        }
                    },
                    {
                        data: 'dob',
                        name: 'dob',
                        render: function(data, type, full, meta) {
                            if (data == '' || data == null) {
                                // Format the date using Moment.js
                                return 'N/A'; // Customize the format
                            }
                            return data; // Return the original data for sorting and other types
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, full, meta) {
                            if (data == '' || data == null) {
                                // Format the date using Moment.js
                                return 'N/A'; // Customize the format
                            }
                            return moment(data).format(
                                'YYYY-MM-DD HH:mm:ss'
                            ); // Return the original data for sorting and other types
                        }
                    }
                ],
                dom: 'Bfrtip',

                // buttons: [
                //     'copyHtml5', 'excelHtml5', 'pdfHtml5', 'csvHtml5'
                // ]
                buttons: [
                    // {
                    //     extend: 'csv',
                    //     exportOptions: {
                    //         modifier: {
                    //             page: 'all'
                    //         }
                    //     }
                    // },
                    // {
                    //     extend: 'excel',
                    //     exportOptions: {
                    //         modifier: {
                    //             page: 'all'
                    //         }
                    //     }
                    // },
                    // {
                    //     extend: 'pdf',
                    //     exportOptions: {
                    //         modifier: {
                    //             page: 'all'
                    //         }
                    //     }
                    // },
                    // {
                    //     extend: 'print',
                    //     exportOptions: {
                    //         modifier: {
                    //             page: 'all'
                    //         }
                    //     }
                    // }
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Initialize the DataTable
            var dataTable = $('#chale_errors_report').DataTable();

            //Handle export
            $('#my_export_btn').click(function() {
                var globalSearchValue = dataTable.search();
                $('#global_search_input_field').val(globalSearchValue)
                var category = $('#category').val();
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                window.location.href =
                    `/chale_errors_export?category=${category}&start_date=${start_date}&end_date=${end_date}&search_value=${globalSearchValue}`;
            })

            // Handle the "Refresh" button click
            $('#refresh').on('click', function() {
                // Clear any applied filters and reset the DataTable
                $('select[name="category"]').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                dataTable.search('').columns().search('').draw();
            });
        });
    </script>
@endpush

{{-- @include('include.alert') --}}
