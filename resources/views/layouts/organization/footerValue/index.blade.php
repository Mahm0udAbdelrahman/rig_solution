@extends('layouts.app')

@include('layouts.styles.datatables')

@section('content')
    <section class="users-list-wrapper">
        <div class="users-list">
            @if (!$dataList->count())
                @include('layouts.repeated.nodata', ['route' => false])
            @else

                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
                                    <thead>
                                        <tr>
                                            <th>Inspection Name</th>
                                            <th>Form No</th>
                                            <th>Issue No</th>
                                            <th>Issue Date</th>
                                            <th>Revision No</th>
                                            <th>Revision Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($dataList as $row)
                                        <tr>
                                            <td>{{ $row['name'] }}</td>
                                            <td>{{ $row['form_no']}}</td>
                                            <td>{{ $row['issue_no']}}</td>
                                            <td>{{ $row['issue_date']}}</td>
                                            <td>{{ $row['revision_no']}}</td>
                                            <td>{{ $row['revision_date']}}</td>
                                            <td>
                                                <a href="{{route('footer-values.edit', $row->id)}}" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
@section('footer')
    <script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.fixedHeader.min.js')}}"></script>

    <script>
      const table = $('#users-list').DataTable({
        ordering: true,
        // stateSave: true,
        processing: true
      });
    </script>
@endSection