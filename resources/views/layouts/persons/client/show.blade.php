@extends('layouts.app')

@section('content')
    @php
        $formatMoney = function ($value) {
            return number_format((float) $value, 2, '.', ',');
        };
        $typeLabel = function ($report) {
            return trim(preg_replace('/(?<!^)[A-Z]/', ' $0', class_basename((string) $report->reportable_type)));
        };
    @endphp
    <style>
        .client-overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: .75rem;
            margin-bottom: 1rem;
        }
        .client-overview-card {
            border: 1px solid #dfe3eb;
            border-radius: 1rem;
            padding: .8rem .95rem;
            background: linear-gradient(180deg, #ffffff 0%, #f6f8fc 100%);
            box-shadow: 0 12px 28px rgba(31, 45, 61, 0.08);
        }
        .client-overview-card-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #74839b;
            margin-bottom: 0.45rem;
        }
        .client-overview-card-value {
            font-size: 1.5rem;
            line-height: 1;
            font-weight: 700;
            color: #23344d;
            margin-bottom: 0.35rem;
        }
        .client-overview-card-note {
            color: #697a92;
            font-size: 0.84rem;
            line-height: 1.45;
        }
        .client-overview-panel {
            border: 1px solid #dfe3eb;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 14px 32px rgba(31, 45, 61, 0.08);
            margin-bottom: 1rem;
        }
        .client-overview-panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: .8rem 1rem;
            border-bottom: 1px solid #edf1f7;
        }
        .client-overview-panel-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #23344d;
            margin: 0;
        }
        .client-overview-panel-subtitle {
            color: #7a889d;
            font-size: 0.88rem;
        }
        .client-overview-panel-body {
            padding: .8rem 1rem .95rem;
        }
        .client-overview-inline-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.9rem;
        }
        .client-inspection-chip {
            border-radius: 0.9rem;
            padding: 0.75rem 0.85rem;
            background: #f7f9fd;
            border: 1px solid #e2e8f2;
        }
        .client-inspection-chip h5 {
            margin: 0 0 0.3rem;
            font-size: 1rem;
            color: #23344d;
        }
        .client-inspection-chip .count {
            font-size: 1.45rem;
            font-weight: 700;
            color: #3246d3;
        }
        .client-badge-yes,
        .client-badge-no {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 78px;
            border-radius: 999px;
            padding: 0.28rem 0.7rem;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .client-badge-yes {
            background: #e6f7ef;
            color: #117a47;
        }
        .client-badge-no {
            background: #fff2ec;
            color: #c15b2c;
        }
        .client-table-compact td,
        .client-table-compact th {
            vertical-align: middle;
            white-space: nowrap;
            padding-top: .75rem;
            padding-bottom: .75rem;
        }
        .client-cert-actions {
            display: inline-flex;
            gap: .45rem;
            flex-wrap: wrap;
        }
        .client-cert-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .38rem .72rem;
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            border: 1px solid transparent;
        }
        .client-cert-btn.is-open {
            background: #eef2ff;
            color: #3146d3;
            border-color: #ccd5ff;
        }
        .client-cert-btn.is-download {
            background: #e8f7ef;
            color: #147c4d;
            border-color: #c8ebd9;
        }
        .client-overview-panel.is-primary {
            border-color: #cfd7ff;
            box-shadow: 0 18px 40px rgba(56, 76, 214, 0.12);
        }
        .client-overview-panel.is-primary .client-overview-panel-head {
            background: linear-gradient(180deg, #fbfcff 0%, #f4f7ff 100%);
        }
        .client-link {
            color: #3246d3;
            font-weight: 700;
        }
        .client-link:hover {
            color: #2233a7;
            text-decoration: underline;
        }
        .client-overview-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
            margin: 0 0 1rem;
            padding: 0;
            list-style: none;
        }
        .client-overview-tabs .nav-link {
            border: 1px solid #d7ddec;
            border-radius: 999px;
            padding: .58rem .95rem;
            background: #fff;
            color: #5b6d87;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(31, 45, 61, 0.06);
        }
        .client-overview-tabs .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, #3146d3 0%, #5a6cf0 100%);
            border-color: #3146d3;
            box-shadow: 0 12px 26px rgba(49, 70, 211, 0.22);
        }
        .client-tab-pane {
            animation: clientFadeIn .18s ease-in;
        }
        @keyframes clientFadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .client-tab-stack {
            display: grid;
            gap: 1rem;
        }
    </style>
    <section class="users-list-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media-list media-bordered">
                                <div class="media">
                                    <h4 class="media-head text-uppercase">{{$client->name}}</h4>
                                </div>
                                <div class="media">
                                    <div class="media-left">
                                        <img class="media-object" src="{{Storage::url('persons/'.$route.'s/')}}{{$client->logo}}" alt="client Logo" width="120">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-heading text-bold-600 text-uppercase">Code : <span>{{$client->code}}</span></div>
                                        <p>{{$client->desc}}</p>
                                        <span class="badge bg-teal float-right"  style="font-size:100%;"><i class="la la-map-marker"></i> {{$client->location}}</span>
                                    </div>
                                </div>
                                <div class="media">
                                    <a href="mailto: {{$client->email}}" class="btn mr-1 mb-1 btn-primary"><i class="la la-envelope-o"></i> Send E-mail</a>
                                    @if($client->url)
                                        <a href="{{$client->url}}" target="_blank" class="btn mr-1 mb-1 btn-secondary"><i class="la la-eye"></i> WebSite URL</a>
                                    @endif
                                    <span class="dropdown">
                                        <button id="btnGroupVerticalDrop6" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn btn-info dropdown-toggle dropdown-menu-right"><i class="la la-phone-square"></i> Make A Call</button>
                                        <span aria-labelledby="btnSearchDrop25" class="dropdown-menu mt-1 dropdown-menu-right">
                                            <a class="dropdown-item" href="tel: {{$client->tel}}"><i class="la la-arrow-right"></i> {{$client->tel}}</a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="client-overview-grid">
            @foreach($overview_cards as $card)
                <div class="client-overview-card">
                    <div class="client-overview-card-label">{{ $card['label'] }}</div>
                    <div class="client-overview-card-value">{{ $card['value'] }}</div>
                    <div class="client-overview-card-note">{{ $card['note'] }}</div>
                </div>
            @endforeach
        </div>
        <ul class="nav nav-pills client-overview-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="client-published-tab" data-toggle="tab" href="#client-published-pane" role="tab" aria-controls="client-published-pane" aria-selected="true">Published Certificates</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="client-jcfs-tab" data-toggle="tab" href="#client-jcfs-pane" role="tab" aria-controls="client-jcfs-pane" aria-selected="false">JCFs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="client-departments-tab" data-toggle="tab" href="#client-departments-pane" role="tab" aria-controls="client-departments-pane" aria-selected="false">Departments & Access</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="client-contacts-tab" data-toggle="tab" href="#client-contacts-pane" role="tab" aria-controls="client-contacts-pane" aria-selected="false">Contact Persons</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active client-tab-pane" id="client-published-pane" role="tabpanel" aria-labelledby="client-published-tab">
                <div class="row">
                    <div class="col-xl-8 col-lg-12">
                        <div class="client-overview-panel is-primary">
                            <div class="client-overview-panel-head">
                                <div>
                                    <h4 class="client-overview-panel-title">Recent Published Certificates</h4>
                                    <div class="client-overview-panel-subtitle">Latest approved and published inspection outputs for this client. You can open or download each certificate directly.</div>
                                </div>
                            </div>
                            <div class="client-overview-panel-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped client-table-compact mb-0">
                                        <thead>
                                        <tr>
                                            <th>Certificate</th>
                                            <th>Type</th>
                                            <th>Section</th>
                                            <th>Published</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($recent_published_reports as $report)
                                            <tr>
                                                <td>
                                                    @if(!empty($report['open_url']))
                                                        <a href="{{ $report['open_url'] }}" target="_blank" class="client-link">{{ $report['certificate_code'] }}</a>
                                                    @else
                                                        {{ $report['certificate_code'] }}
                                                    @endif
                                                </td>
                                                <td>{{ $report['type_label'] }}</td>
                                                <td>{{ $report['section_label'] }}</td>
                                                <td><span class="client-badge-yes">{{ $report['published_label'] }}</span></td>
                                                <td>
                                                    <div class="client-cert-actions">
                                                        @if(!empty($report['open_url']))
                                                            <a href="{{ $report['open_url'] }}" target="_blank" class="client-cert-btn is-open">Open</a>
                                                        @endif
                                                        @if(!empty($report['download_url']))
                                                            <a href="{{ $report['download_url'] }}" download class="client-cert-btn is-download">PDF</a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center text-muted">No published inspection reports found.</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12">
                        <div class="client-overview-panel">
                            <div class="client-overview-panel-head">
                                <div>
                                    <h4 class="client-overview-panel-title">Inspection Snapshot</h4>
                                    <div class="client-overview-panel-subtitle">Published inspection distribution across all sections for this client.</div>
                                </div>
                            </div>
                            <div class="client-overview-panel-body">
                                <div class="client-overview-inline-grid">
                                    @forelse($inspection_section_cards as $section)
                                        <div class="client-inspection-chip">
                                            <h5>{{ $section['label'] }}</h5>
                                            <div class="count">{{ $section['count'] }}</div>
                                            <div class="text-muted small mt-25">Latest: {{ $section['latest_code'] ?: '-' }}</div>
                                        </div>
                                    @empty
                                        <div class="text-muted">No published inspection certificates are linked to this client yet.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade client-tab-pane" id="client-jcfs-pane" role="tabpanel" aria-labelledby="client-jcfs-tab">
                <div class="client-overview-panel">
                    <div class="client-overview-panel-head">
                        <div>
                            <h4 class="client-overview-panel-title">Workflow By JCF</h4>
                            <div class="client-overview-panel-subtitle">Operational readiness across quotation, logistics, billing, and inspection delivery.</div>
                        </div>
                    </div>
                    <div class="client-overview-panel-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped client-table-compact mb-0">
                                <thead>
                                <tr>
                                    <th>JCF</th>
                                    <th>Department</th>
                                    <th>Location</th>
                                    <th>Quotation</th>
                                    <th>Packing Slip</th>
                                    <th>Service Ticket</th>
                                    <th>Published</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($workflow_rows as $row)
                                    <tr>
                                        <td>
                                            @if(!empty($row['job_request_url']))
                                                <a href="{{ $row['job_request_url'] }}" class="client-link">{{ $row['job_request_code'] }}</a>
                                            @else
                                                {{ $row['job_request_code'] }}
                                            @endif
                                        </td>
                                        <td>{{ $row['department'] }}</td>
                                        <td>{{ $row['location'] }}</td>
                                        <td><span class="{{ $row['quotation'] ? 'client-badge-yes' : 'client-badge-no' }}">{{ $row['quotation'] ? 'Ready' : 'Missing' }}</span></td>
                                        <td><span class="{{ $row['packing_slip'] ? 'client-badge-yes' : 'client-badge-no' }}">{{ $row['packing_slip'] ? 'Ready' : 'Missing' }}</span></td>
                                        <td><span class="{{ $row['service_ticket'] ? 'client-badge-yes' : 'client-badge-no' }}">{{ $row['service_ticket'] ? 'Ready' : 'Missing' }}</span></td>
                                        <td>{{ $row['published_inspections'] }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted">No job requests linked to this client.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade client-tab-pane" id="client-departments-pane" role="tabpanel" aria-labelledby="client-departments-tab">
                <div class="row">
                    <div class="col-md-8 col-lg-9">
                        <div class="client-overview-panel">
                            <div class="client-overview-panel-head">
                                <div>
                                    <h4 class="client-overview-panel-title">{{$client->name}} Departments</h4>
                                    <div class="client-overview-panel-subtitle">Manage department records and keep account access separated from inline edits.</div>
                                </div>
                            </div>
                            <div class="client-overview-panel-body p-0">
                                <div class="table-responsive">
                                    <table id="departmentsTable" class="dataTable table table-striped table-bordered dataex-fixh-responsive mb-0">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>E-mail</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($client->departments as $responsible)
                                            <tr id="{{route('clientDepartment.update', $responsible->id)}}">
                                                <td>{{$responsible->name}}</td>
                                                <td>{{$responsible->email}}</td>
                                                <td>{{$responsible->description}}</td>
                                                <td>
                                                    <div class="form-group text-left" style="margin-bottom: auto;">
                                                        <button
                                                                type="button"
                                                                class="btn btn-icon btn-warning mr-1 js-fill-department-password-form"
                                                                data-department-id="{{$responsible->id}}"
                                                        >
                                                            <i class="la la-key"></i>
                                                        </button>
                                                        <button
                                                                type="button"
                                                                onclick="handleDelete('{{route('clientDepartment.destroy', $responsible->id)}}');"
                                                                data-url="{{ route('clientDepartment.destroy', $responsible->id) }}"
                                                                class="btn btn-icon btn-danger delete"
                                                        >
                                                            <i class="la la-trash"></i>
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
                    <div class="col-md-4 col-lg-3">
                        <div class="client-tab-stack">
                            <div class="card">
                                <div class="card-content collpase show">
                                    <div class="card-body">
                                        <form id="create-department" class="create-form form-horizontal" data-url="{{route($route.'.departmentStore', $client->id)}}" novalidate>
                                            <input type="hidden" name="clientid" value="{{$client->id}}" />
                                            <div class="form-body">
                                                <h4 class="form-section"><i class="ft-user"></i> Add Department</h4>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <input type="text" class="form-control" placeholder="Name" name="name" required data-validation-required-message="This name field is required">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <input type="email" class="form-control" placeholder="E-mail" name="email" >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <input type="password" class="form-control" placeholder="password" name="password" >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <input type="text" class="form-control" placeholder="Description" name="description" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary mr-1">
                                                    <i class="la la-check-square-o"></i> Save Changes
                                                </button>
                                                <button type="button" class="btn btn-light">
                                                    <i class="ft-x"></i> Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-content collpase show">
                                    <div class="card-body">
                                        <form id="reset-department-password-form" class="form-horizontal" data-url-template="{{ route('clientDepartment.password.update', ['clientDepartment' => '__ID__']) }}" novalidate>
                                            <div class="form-body">
                                                <h4 class="form-section"><i class="la la-key"></i> Reset Department Password</h4>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <select class="form-control" name="department_id" required>
                                                            <option value="">Select Department</option>
                                                            @foreach($client->departments as $department)
                                                                <option value="{{$department->id}}">{{$department->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <input type="password" class="form-control" placeholder="New Password" name="password" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
                                                    </div>
                                                </div>
                                                <div class="small text-muted mb-1">Password handling is isolated from the department table.</div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-warning mr-1">
                                                    <i class="la la-key"></i> Update Password
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade client-tab-pane" id="client-contacts-pane" role="tabpanel" aria-labelledby="client-contacts-tab">
                <div class="row">
                    <div class="col-md-8 col-lg-9">
                        <div class="client-overview-panel">
                            <div class="client-overview-panel-head">
                                <div>
                                    <h4 class="client-overview-panel-title">{{$client->name}} Contact Persons</h4>
                                    <div class="client-overview-panel-subtitle">Keep client-side decision makers and communication channels up to date.</div>
                                </div>
                            </div>
                            <div class="client-overview-panel-body p-0">
                                <div class="table-responsive">
                                    <table id="contactTable" class="dataTable table table-striped table-bordered dataex-fixh-responsive mb-0">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Job Title</th>
                                            <th>E-mail</th>
                                            <th>Phone</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($client->conatctPerson as $responsible)
                                            <tr id="{{route('contactPerson.update', $responsible->id)}}">
                                                <td>{{$responsible->name}}</td>
                                                <td>{{$responsible->postion}}</td>
                                                <td>{{$responsible->email}}</td>
                                                <td>{{$responsible->tel}}</td>
                                                <td>
                                                    <div class="form-group text-left" style="margin-bottom: auto;">
                                                        <button
                                                                type="button"
                                                                onclick="handleDelete('{{ route('contactPerson.destroy', $responsible->id) }}');"
                                                                data-url="{{ route('contactPerson.destroy', $responsible->id) }}"
                                                                class="btn btn-icon btn-danger delete"
                                                        >
                                                            <i class="la la-trash"></i>
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
                    <div class="col-md-4 col-lg-3">
                        <div class="card">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <form id="create" class="form-horizontal create-form" data-url="{{route($route.'.contactPersonStore', $client->id)}}" novalidate>
                                        <input type="hidden" name="clientid" value="{{$client->id}}" />
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i> Add Contact Person</h4>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <input type="text" id="sname" class="form-control" placeholder="Name" name="sname" required data-validation-required-message="This name field is required">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <input type="text" id="stitle" class="form-control" placeholder="Job Title" name="stitle" >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <input type="email" id="semail" class="form-control" placeholder="E-mail" name="semail" >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <input type="tel" placeholder="Telephone" name="stel" class="form-control" id="stel" required data-validation-required-message="This telephone field is required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button id="submit" type="submit" class="btn btn-primary mr-1">
                                                <i class="la la-check-square-o"></i> Save Changes
                                            </button>
                                            <button type="button" class="btn btn-light">
                                                <i class="ft-x"></i> Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    <script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
@endsection

@section('ajax')
<script>
/**********************************/
var contactTable;
var departmentTable;
const createdCell = function (cell) {
  var original;
  cell.addEventListener("click", function (e) {
    original = e.target.textContent
    console.log(">>>>", cell, e.target);
    cell.setAttribute('contenteditable', true)
  });
  cell.addEventListener("focusout", function (e) {
    if (original !== e.target.textContent) {
      cell.setAttribute('contenteditable', true);
      const url =  e.target.parentElement.id;
      var supmitData = {};
      if (url.includes('contactPerson')) {
        const row = contactTable.row(e.target.parentElement);
        row.invalidate();
        supmitData = {
          name: row.data()[0],
          email: row.data()[2],
          tel: row.data()[3],
          postion: row.data()[1]
        };
      } else {
        const row = departmentTable.row(e.target.parentElement);
        row.invalidate();
        supmitData = {
          name: row.data()[0],
          email: row.data()[1],
          description: row.data()[2]
        };
      }
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'PUT',
        url: url,
        data: supmitData,
        dataType: "JSON",
        success: function (data) {
          toastr.info('Good Job !', data.success, {
            positionClass: 'toast-bottom-left',
            "showMethod": "slideDown",
            "hideMethod": "slideUp",
            "progressBar": true,
            timeOut: 1000,
            fadeOut: 1000,
            onHidden: function () {
              window.location.reload();
            }
          });
        }
      });
    }
  })
};
contactTable = $('#contactTable').DataTable({
  columnDefs: [
    {
      targets: [0,1,2,3],
      createdCell: createdCell,
    }
  ]
});
departmentTable = $('#departmentsTable').DataTable({
  columnDefs: [
    {
      targets: [0,1,2],
      createdCell: createdCell,
    }
  ]
});
$('.client-overview-tabs a[data-toggle="tab"]').on('shown.bs.tab', function () {
  $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});
/**********************************/
$(".create-form").submit(function(stay){
  stay.preventDefault();
  var form = $(this)[0];
  var formdata = new FormData(form);
  var url = $(this).data('url');
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'POST',
    url: url,
    processData: false,
    contentType: false,
    cache: false,
    data: formdata,
    dataType: "JSON",
    beforeSend:function(){
      $(this).find('i').addClass('la la-refresh spinner');
    },
    success: function (data){
      toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { window.location.reload(); }  });

    },
  });
});

$('.js-fill-department-password-form').on('click', function () {
  $('#reset-department-password-form select[name="department_id"]').val($(this).data('department-id'));
  $('#reset-department-password-form input[name="password"]').focus();
});

$('#reset-department-password-form').submit(function(stay){
  stay.preventDefault();
  var form = $(this)[0];
  var formdata = new FormData(form);
  var departmentId = $(this).find('select[name="department_id"]').val();
  if (!departmentId) {
    toastr.error('Select a department first.');
    return;
  }

  formdata.append('_method', 'PATCH');
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'POST',
    url: $(this).data('url-template').replace('__ID__', departmentId),
    processData: false,
    contentType: false,
    cache: false,
    data: formdata,
    dataType: "JSON",
    success: function (data){
      toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { window.location.reload(); }  });
    },
    error: function (xhr) {
      var message = 'Unable to update department password.';
      if (xhr.responseJSON && xhr.responseJSON.errors) {
        var firstKey = Object.keys(xhr.responseJSON.errors)[0];
        if (firstKey && xhr.responseJSON.errors[firstKey][0]) {
          message = xhr.responseJSON.errors[firstKey][0];
        }
      }
      toastr.error(message);
    }
  });
});

// $('.delete').on('click',
function handleDelete(url){
  // console.log("CLOCJ>>>", $(this));
  //   const url = $(this).data('url');
    Swal.fire({
      title: 'Are You Sure ?',
      text: "This contact person will be permanently deleted!",
      type: 'error',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Delete It !',
      confirmButtonClass: 'btn btn-danger',
      cancelButtonClass: 'btn btn-dark ml-1',
      cancelButtonText: 'Cancel',
      buttonsStyling: false,
    }).then(function (result) {
      if (result.value) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: url,
            method: 'DELETE',
            success: function(data){
              toastr.error('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { window.location.reload(); } });
            }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'Your data is safe :)',
          type: 'error',
          confirmButtonClass: 'btn btn-success',
        })
      }
    })
};// );
</script>
@endsection
