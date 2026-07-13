@extends('layouts.app')

@include('layouts.styles.datatables')

@extends('layouts.styles.paper')

@push('page_content')

<div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

@endpush

@section('footer')
    <script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.fixedHeader.min.js')}}"></script>

    <script>
        var table = $('#users-list').DataTable({
            ordering: false,
            // stateSave: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('getDataForDataTable.getForShow', $lregister->id) }}",
            columns: [
                    {data: 'id'},
            ],
            // search: {
            //  "regex": true
            // },
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.header()).empty())
                    .on('change', function () {
                        column.search($(this).val(), false, false, true).draw();
                    });
                });
            },
            stateLoadParams: function(settings, data) {
                for (i = 0; i < data.columns["length"]; i++) {
                    var col_search_val = data.columns[i].search.search;
                    if (col_search_val != "") {
                        $("input").val(col_search_val);
                        console.log(col_search_val);
                    }
                }
            },
        });
      </script>
@endsection 
