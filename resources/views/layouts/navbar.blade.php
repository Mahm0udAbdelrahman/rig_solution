<!-- BEGIN: Main Menu-->
    <div class="no-print main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
              <li class=" nav-item"><a href="/dashboard"><i class="mbri-desktop"></i><span class="menu-title" data-i18n="Dashboard">Dashboard</span></a></li>
              <!---------------------------------------------------------------------------------->
              @if(auth()->user()->canany(['create', 'viewAny'], \App\Models\WorkFlow\JobRequest::class) ||
                  auth()->user()->canany('viewAny', App\Models\WorkFlow\Qutation::class) ||
                  auth()->user()->canany('viewAny', App\Models\WorkFlow\PackingSlip::class) ||
                  auth()->user()->canany('viewAny', App\Models\WorkFlow\ServiceTicket::class) ||
                  auth()->user()->canany('viewAny', App\Models\WorkFlow\Invoice::class) ||
                  auth()->user()->canany('viewAny', App\Models\WorkFlow\FileManager::class) ||
                  auth()->user()->canany('viewAny', App\Models\WorkFlow\Inquiry::class))
              <li class=" nav-item {{Request::segment(2) === 'work-flow' && !request()->routeIs('mailCenter.*') && !request()->routeIs('payment.*') && !request()->routeIs('inventory.*') && !request()->routeIs('accountant.*') && !request()->routeIs('bank.*') && !request()->routeIs('expense.*') ? 'active' : ''}}"><a href=""><i class="la la-cogs"></i><span class="menu-title" data-i18n="Dashboard">Work Flow</span></a>
                <ul class="menu-content">
                  @canany(['create', 'viewAny'], \App\Models\WorkFlow\JobRequest::class)
                  <li><a class="menu-item {{request()->routeIs('jobRequest.*') ? 'active' : ''}}" href="#"><i class="mbri-edit"></i><span data-i18n="All Items">Job Control Form (JCF)</span></a>
                    <ul class="menu-content">
                        @can('viewAny', App\Models\WorkFlow\JobRequest::class)
                        <li><a class="menu-item {{request()->routeIs('jobRequest.index') ? 'active' : ''}}" href="{{route('jobRequest.index')}}"><i></i><span data-i18n="All Items">All Jobs Control Form</span></a></li>
                        @endcan
                        @can('create', App\Models\WorkFlow\JobRequest::class)
                        <li><a class="menu-item {{request()->routeIs('jobRequest.create') ? 'active' : ''}}" href="{{route('jobRequest.create')}}"><i></i><span data-i18n="All Items">New Job Control Form</span></a></li>
                        @endcan
                    </ul>
                  </li>
                  @endcan
                  @can('viewAny', App\Models\WorkFlow\Qutation::class)
                  <li><a class="menu-item" href="#"><i class="la la-file-text"></i><span data-i18n="All Items">Quotation / Contract</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('qutation.index')}}"><i></i><span data-i18n="All Items">All Quotations / Contracts</span></a></li>
                    </ul>
                  </li>
                  @endcan
                  @can('viewAny', App\Models\WorkFlow\PackingSlip::class)
                  <li><a class="menu-item" href="#"><i class="icon-list"></i><span data-i18n="All Items">Packing Slip</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('packingSlip.index')}}"><i></i><span data-i18n="All Items">All Packing Slips</span></a></li>
                    </ul>
                  </li>
                  @endcan
                  @can('viewAny', App\Models\WorkFlow\ServiceTicket::class)
                  <li><a class="menu-item" href="#"><i class="icon-list"></i><span data-i18n="All Items">Service Ticket</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('serviceTicket.index')}}"><i></i><span data-i18n="All Items">All Service Tickets</span></a></li>
                    </ul>
                  </li>
                  @endcan
                  @can('viewAny', App\Models\WorkFlow\Invoice::class)
                  <li><a class="menu-item" href="#"><i class="la la-money"></i><span data-i18n="All Items">Invoices</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('invoice.index', ['type' => \App\Models\WorkFlow\Invoice::$INVOICE_RSE_TYPE])}}"><i></i><span data-i18n="All Items">All RSE Invoices</span></a></li>
                        <li><a class="menu-item" href="{{route('invoice.index', ['type' => \App\Models\WorkFlow\Invoice::$INVOICE_LTD_TYPE])}}"><i></i><span data-i18n="All Items">All LTD Invoices</span></a></li>
                    </ul>
                  </li>
                  @endcan
                  @can('viewAny', App\Models\WorkFlow\FileManager::class)
                  <li><a class="menu-item" href="#"><i class="la la-folder-open"></i><span data-i18n="All Items">File Manager</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('fileManager.index')}}"><i></i><span data-i18n="All Items">All Managed Files</span></a></li>
                    </ul>
                  </li>
                  @endcan
                  @can('viewAny', App\Models\Inquiry::class)
                  <li><a class="menu-item" href="#"><i class="la la-money"></i><span data-i18n="All Items">Inquiry</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('inquiry.index')}}"><i></i><span data-i18n="All Items">All inquiries</span></a></li>
                    </ul>
                  </li>
                  @endcan
                </ul>
              </li>
              @endif
              <!---------------------------------------------------------------------------------->
              @php
                  $canAccessFinancialTab = auth()->user()->hasPermission('financial', 'show') || auth()->user()->hasPermission('financial', 'all');
              @endphp
              @if(
                $canAccessFinancialTab && (
                  auth()->user()->can('viewAny', App\Models\WorkFlow\Payment::class) ||
                  auth()->user()->can('viewAny', App\Models\WorkFlow\Inventory::class) ||
                  auth()->user()->can('viewAny', App\Models\WorkFlow\Accountant::class) ||
                  auth()->user()->can('viewAny', App\Models\WorkFlow\Bank::class) ||
                  auth()->user()->can('viewAny', App\Models\WorkFlow\Expense::class)
                )
              )
              <li class=" nav-item {{ request()->routeIs('payment.*') || request()->routeIs('inventory.*') || request()->routeIs('accountant.*') || request()->routeIs('bank.*') || request()->routeIs('expense.*') ? 'active' : '' }}">
                <a href=""><i class="la la-line-chart"></i><span class="menu-title" data-i18n="Dashboard">Financial</span></a>
                <ul class="menu-content">
                    @can('viewAny', App\Models\WorkFlow\Bank::class)
                    <li><a class="menu-item {{request()->routeIs('bank.dashboard') ? 'active' : ''}}" href="{{route('bank.dashboard')}}"><i class="la la-area-chart"></i><span data-i18n="All Items">Dashboard</span></a></li>
                    <li><a class="menu-item {{request()->routeIs('bank.index') || request()->routeIs('bank.show') || request()->routeIs('bank.create') || request()->routeIs('bank.edit') || request()->routeIs('bank.transactions.*') ? 'active' : ''}}" href="{{route('bank.index')}}"><i class="la la-university"></i><span data-i18n="All Items">Banks</span></a></li>
                    @endcan
                    @can('viewAny', App\Models\WorkFlow\Payment::class)
                    <li><a class="menu-item {{request()->routeIs('payment.*') ? 'active' : ''}}" href="#"><i class="la la-credit-card"></i><span data-i18n="All Items">Payments</span></a>
                      <ul class="menu-content">
                          <li><a class="menu-item {{request()->routeIs('payment.index') ? 'active' : ''}}" href="{{route('payment.index')}}"><i></i><span data-i18n="All Items">All Payments</span></a></li>
                          @can('create', App\Models\WorkFlow\Payment::class)
                          <li><a class="menu-item {{request()->routeIs('payment.create') ? 'active' : ''}}" href="{{route('payment.create')}}"><i></i><span data-i18n="All Items">New Payment</span></a></li>
                          @endcan
                      </ul>
                    </li>
                    @endcan
                    @can('viewAny', App\Models\WorkFlow\Expense::class)
                    <li><a class="menu-item {{request()->routeIs('expense.*') ? 'active' : ''}}" href="#"><i class="la la-money"></i><span data-i18n="All Items">Expenses</span></a>
                      <ul class="menu-content">
                          <li><a class="menu-item {{request()->routeIs('expense.index') ? 'active' : ''}}" href="{{route('expense.index')}}"><i></i><span data-i18n="All Items">All Expenses</span></a></li>
                          @can('create', App\Models\WorkFlow\Expense::class)
                          <li><a class="menu-item {{request()->routeIs('expense.create') ? 'active' : ''}}" href="{{route('expense.create')}}"><i></i><span data-i18n="All Items">New Expense</span></a></li>
                          @endcan
                      </ul>
                    </li>
                    @endcan
                    @can('viewAny', App\Models\WorkFlow\Inventory::class)
                    <li><a class="menu-item {{request()->routeIs('inventory.*') ? 'active' : ''}}" href="{{route('inventory.index')}}"><i class="la la-archive"></i><span data-i18n="All Items">Inventory</span></a></li>
                    @endcan
                    @can('viewAny', App\Models\WorkFlow\Accountant::class)
                    <li><a class="menu-item {{request()->routeIs('accountant.*') ? 'active' : ''}}" href="#"><i class="la la-calculator"></i><span data-i18n="All Items">Accountant</span></a>
                      <ul class="menu-content">
                          <li><a class="menu-item {{request()->routeIs('accountant.index') ? 'active' : ''}}" href="{{route('accountant.index')}}"><i></i><span data-i18n="All Items">All Accountant Entries</span></a></li>
                          @can('create', App\Models\WorkFlow\Accountant::class)
                          <li><a class="menu-item {{request()->routeIs('accountant.create') ? 'active' : ''}}" href="{{route('accountant.create')}}"><i></i><span data-i18n="All Items">New Accountant Entry</span></a></li>
                          @endcan
                      </ul>
                    </li>
                    @endcan
                </ul>
              </li>
              @endif
              <!---------------------------------------------------------------------------------->
              @php
                  $canInspectionLifting = auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Lifting\Crane::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Lifting\OverheadCrane::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Lifting\Forklift::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Lifting\ThroughExamination::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Lifting\Defect::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Lifting\Lregister::class);
                  $canInspectionNdt = auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\Mpipt::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\Visual::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\Ultrasonic::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\Summary::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\Attached::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\High3Pressure::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\HighPressure::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\High2Pressure::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\WitnessHydro::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\TreatingIron::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\DrawingInspection::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Ndt\Nregister::class);
                  $canInspectionTubular = auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Tubular\PipesSummaryReport::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Tubular\DrillPipe::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Tubular\HeavyWeightPipe::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Tubular\DrillCollar::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Tubular\SubsDimensional::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Tubular\TubingString::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Tubular\StabilizerInspection::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Tubular\ReamerInspection::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Tubular\LinkInspection::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Tubular\Pbl::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Tubular\TubingCasing::class);
                  $canInspectionDropObject = auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\DropObject\DropObject::class);
                  $canInspectionCalibration = auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Calibration\CalibrationPressureGauge::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Calibration\CalibrationTorque::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Calibration\CalibrationPressureTest::class)
                      || auth()->user()->canany(['create', 'viewAny'], \App\Models\Inspection\Calibration\CalibrationYoke::class);
                   $canInspectionTab = $canInspectionLifting || $canInspectionNdt || $canInspectionTubular || $canInspectionDropObject;
               @endphp
               @if($canInspectionTab)
               <li class=" nav-item {{ Request::segment(2) === 'inspection' && Request::segment(3) !== 'calibration' ? 'active' : '' }}"><a href=""><i class="la la-certificate"></i><span class="menu-title" data-i18n="Dashboard">Inspections</span></a>
                 <ul class="menu-content">
                     <li><a class="menu-item {{ request()->routeIs('inspection.all') ? 'active' : '' }}" href="{{ route('inspection.all') }}"><i class="la la-list"></i><span data-i18n="All Items">All Inspections</span></a></li>
                     @if($canInspectionLifting)
                     <li><a class="menu-item {{ Request::segment(3) === 'lifting' ? 'active' : '' }}" href="{{ route('inspection.catalog.lifting') }}"><i class="la la-level-up"></i><span data-i18n="All Items">Lifting</span></a></li>
                     @endif
                     @if($canInspectionNdt)
                     <li><a class="menu-item {{ Request::segment(3) === 'ndt' ? 'active' : '' }}" href="{{ route('inspection.catalog.ndt') }}"><i class="la la-search"></i><span data-i18n="All Items">NDT</span></a></li>
                     @endif
                     @if($canInspectionTubular)
                     <li><a class="menu-item {{ Request::segment(3) === 'tubular' ? 'active' : '' }}" href="{{ route('inspection.catalog.tubular') }}"><i class="la la-link"></i><span data-i18n="All Items">Tubular</span></a></li>
                     @endif
                     @if($canInspectionDropObject)
                     <li><a class="menu-item {{ Request::segment(3) === 'drop-object' || Request::segment(3) === 'dropObject' ? 'active' : '' }}" href="{{ route('inspection.catalog.dropObject') }}"><i class="la la-cube"></i><span data-i18n="All Items">Drop Object</span></a></li>
                     @endif
                 </ul>
               </li>
               @endif
               @if($canInspectionCalibration)
               <li class=" nav-item {{ Request::segment(3) === 'calibration' ? 'active' : '' }}"><a href=""><i class="la la-sliders"></i><span class="menu-title" data-i18n="Dashboard">Calibration</span></a>
                 <ul class="menu-content">
                     <li><a class="menu-item {{ request()->routeIs('inspection.catalog.calibration') ? 'active' : '' }}" href="{{ route('inspection.catalog.calibration') }}"><i class="la la-list"></i><span data-i18n="All Items">All Calibrations</span></a></li>
                     @can('viewAny', 'App\Models\Inspection\Calibration\CalibrationPressureGauge')
                     <li><a class="menu-item {{ Request::segment(4) === 'calibrationPressureGauge' ? 'active' : '' }}" href="{{ route('calibrationPressureGauge.index') }}"><i class="la la-dashboard"></i><span data-i18n="All Items">Pressure Gauge</span></a></li>
                     @endcan
                     @can('viewAny', 'App\Models\Inspection\Calibration\CalibrationTorque')
                     <li><a class="menu-item {{ Request::segment(4) === 'calibrationTorque' ? 'active' : '' }}" href="{{ route('calibrationTorque.index') }}"><i class="la la-rotate-right"></i><span data-i18n="All Items">Torque</span></a></li>
                     @endcan
                     @can('viewAny', 'App\Models\Inspection\Calibration\CalibrationPressureTest')
                     <li><a class="menu-item {{ Request::segment(4) === 'calibrationPressureTest' ? 'active' : '' }}" href="{{ route('calibrationPressureTest.index') }}"><i class="la la-tachometer"></i><span data-i18n="All Items">Pressure Test</span></a></li>
                     @endcan
                     @can('viewAny', 'App\Models\Inspection\Calibration\CalibrationYoke')
                     <li><a class="menu-item {{ Request::segment(4) === 'calibrationYoke' ? 'active' : '' }}" href="{{ route('calibrationYoke.index') }}"><i class="la la-magnet"></i><span data-i18n="All Items">Yoke</span></a></li>
                     @endcan
                 </ul>
               </li>
               @endif
              <!---------------------------------------------------------------------------------->
              @if(auth()->user()->canany(['create', 'viewAny'], \App\Models\Persons\Supplier::class) ||
                  auth()->user()->canany(['create', 'viewAny'], \App\Models\Persons\Client::class) ||
                  auth()->user()->canany('viewAny', App\Models\WorkFlow\MailCenter::class) )
              <li class=" nav-item {{Request::segment(2) === 'persons' || request()->routeIs('mailCenter.*') ? 'active' : ''}}"><a href=""><i class="mbri-user"></i><span class="menu-title" data-i18n="Dashboard">Clients / Supplier</span></a>
                <ul class="menu-content">
                    @can('viewAny', App\Models\WorkFlow\MailCenter::class)
                    <li>
                      <a class="menu-item {{request()->routeIs('mailCenter.*') ? 'active' : ''}}" href="#"><i class="la la-envelope"></i><span data-i18n="All Items">Rig MailCenter</span></a>
                      <ul class="menu-content">
                          <li><a class="menu-item {{request()->routeIs('mailCenter.index') ? 'active' : ''}}" href="{{route('mailCenter.index')}}"><i></i><span data-i18n="All Items">All MailCenter Items</span></a></li>
                      </ul>
                    </li>
                    @endcan
                    @canany(['create', 'viewAny'], \App\Models\Persons\Supplier::class)
                    <li>
                      <a class="menu-item {{request()->routeIs('supplier.*') ? 'active' : ''}}" href="#"><i class="la la-sign-in"></i><span data-i18n="All Items">Suppliers</span></a>
                      <ul class="menu-content">
                          @can('viewAny', App\Models\Persons\Supplier::class)
                          <li><a class="menu-item {{request()->routeIs('supplier.index') ? 'active' : ''}}" href="{{route('supplier.index')}}"><i></i><span data-i18n="All Items">All Suppliers</span></a></li>
                          @endcan
                          @can('create', App\Models\Persons\Supplier::class)
                          <li><a class="menu-item {{request()->routeIs('supplier.create') ? 'active' : ''}}" href="{{route('supplier.create')}}"><i></i><span data-i18n="All Items">New Supplier</span></a></li>
                          @endcan
                      </ul>
                    </li>
                    @endcan
                    @canany(['create', 'viewAny'], \App\Models\Persons\Client::class)
                    <li><a class="menu-item {{request()->routeIs('client.*') ? 'active' : ''}}" href="#"><i class="ft-users"></i><span data-i18n="All Items">Clients</span></a>
                      <ul class="menu-content">
                          @can('viewAny', App\Models\Persons\Client::class)
                          <li><a class="menu-item {{request()->routeIs('client.index') ? 'active' : ''}}" href="{{route('client.index')}}"><i></i><span data-i18n="All Items">All Clients</span></a></li>
                          @endcan
                          @can('create', App\Models\Persons\Client::class)
                          <li><a class="menu-item {{request()->routeIs('client.create') ? 'active' : ''}}" href="{{route('client.create')}}"><i></i><span data-i18n="All Items">New Client</span></a></li>
                          @endcan
                      </ul>
                    </li>
                    @endcan
                </ul>
              </li>
              @endif
              <!---------------------------------------------------------------------------------->
              @if(auth()->user()->canany(['create', 'viewAny'], \App\Models\Organization\Department::class) ||
                  auth()->user()->canany(['create', 'viewAny'], \App\Models\Organization\Employee::class) ||
                  auth()->user()->canany(['create', 'viewAny'], \App\Models\User::class) ||
                  auth()->user()->canany(['create', 'viewAny'], \App\Models\Organization\Role::class) )
              <li class=" nav-item {{Request::segment(2) === 'organization' ? 'active' : ''}}"><a href=""><i class="la la-sitemap"></i><span class="menu-title" data-i18n="Dashboard">Organization</span></a>
                <ul class="menu-content">
                  @canany(['create', 'viewAny'], \App\Models\Organization\Department::class)
                  <li><a class="menu-item {{request()->routeIs('department.index') || request()->routeIs('department.create') ? 'active' : ''}}" href="#"><i class="la la-tasks"></i><span data-i18n="All Items">Departments</span></a>
                    <ul class="menu-content">
                      @can('viewAny', App\Models\Organization\Department::class)
                      <li><a class="menu-item {{request()->routeIs('department.index') ? 'active' : ''}}" href="{{route('department.index')}}"><i></i><span data-i18n="All Items">All Departments</span></a></li>
                      @endcan
                      @can('create', App\Models\Organization\Department::class)
                      <li><a class="menu-item {{request()->routeIs('department.create') ? 'active' : ''}}" href="{{route('department.create')}}"><i></i><span data-i18n="All Items">New Department</span></a></li>
                      @endcan
                    </ul>
                  </li>
                  @endcan
                  @canany(['create', 'viewAny'], \App\Models\Organization\Employee::class)
                  <li><a class="menu-item {{request()->routeIs('employee.*') ? 'active' : ''}}" href="#"><i class="icon-bag"></i><span data-i18n="All Items">Employees</span></a>
                    <ul class="menu-content">
                        @can('viewAny', App\Models\Organization\Employee::class)
                        <li><a class="menu-item {{request()->routeIs('employee.index') ? 'active' : ''}}" href="{{route('employee.index')}}"><i></i><span data-i18n="All Items">All Employees</span></a></li>
                        @endcan
                        @can('create', App\Models\Organization\Employee::class)
                        <li><a class="menu-item  {{request()->routeIs('employee.create') ? 'active' : ''}}" href="{{route('employee.create')}}"><i></i><span data-i18n="All Items">New Employee</span></a></li>
                        @endcan
                    </ul>
                  </li>
                  @endcan
                  @if((bool) auth()->user()->isSuperAdmin())
                  <li><a class="menu-item {{request()->routeIs('organization.staffPerformance') ? 'active' : ''}}" href="{{route('organization.staffPerformance')}}"><i class="la la-line-chart"></i><span data-i18n="All Items">Staff Performance</span></a></li>
                  @endif
                  <li>
                    <a class="menu-item {{request()->routeIs('user.*') || request()->routeIs('role.*') ? 'active' : ''}}" href="{{route('user.index')}}"><i class="icon-users"></i><span data-i18n="All Items">System Admins</span></a>
                    @canany(['create', 'viewAny'], [\App\Models\User::class])
                      <ul class="menu-content">
                        <li><a class="menu-item {{request()->routeIs('user.*') ? 'active' : ''}}" href="{{route('user.index')}}"><i class="icon-users"></i><span data-i18n="All Items">Admins Accounts</span></a>
                          <ul class="menu-content">
                            @can('viewAny', App\Models\User::class)
                            <li><a class="menu-item {{request()->routeIs('user.index') ? 'active' : ''}}" href="{{route('user.index')}}"><i></i><span data-i18n="All Items">All Accounts</span></a></li>
                            @endcan
                            @can('create', App\Models\User::class)
                            <li><a class="menu-item {{request()->routeIs('user.create') ? 'active' : ''}}" href="{{route('user.create')}}"><i></i><span data-i18n="All Items">New Account</span></a></li>
                            @endcan
                          </ul>
                        </li>
                      </ul>
                    @endcan
                    @canany(['create', 'viewAny'], [\App\Models\Organization\Role::class])
                        <ul class="menu-content">
                        <li><a class="menu-item {{request()->routeIs('role.*') ? 'active' : ''}}" href="{{route('user.index')}}"><i class="icon-users"></i><span data-i18n="All Items">Admins Roles</span></a>
                          <ul class="menu-content">
                            @can('viewAny', App\Models\Organization\Role::class)
                            <li><a class="menu-item {{request()->routeIs('role.index') ? 'active' : ''}}" href="{{route('role.index')}}"><i></i><span data-i18n="All Items">All Roles</span></a></li>
                            @endcan
                            @can('create', App\Models\Organization\Role::class)
                            <li><a class="menu-item {{request()->routeIs('role.create') ? 'active' : ''}}" href="{{route('role.create')}}"><i></i><span data-i18n="All Items">New User Roles</span></a></li>
                            @endcan
                          </ul>
                        </li>
                      </ul>
                    @endcan
                  </li>
                </ul>
              </li>
              @endif
              <!---------------------------------------------------------------------------------->
              @if(auth()->user()->canany(['create', 'viewAny'], \App\Models\GeneralInfo\item::class) ||
                  auth()->user()->canany(['create', 'viewAny'], \App\Models\GeneralInfo\Tool::class) ||
                  auth()->user()->canany(['create', 'viewAny'], \App\Models\GeneralInfo\Specification::class) )
              <li class=" nav-item {{Request::segment(2) === 'general-info' ? 'active' : ''}}"><a href=""><i class="la la-info-circle"></i><span class="menu-title" data-i18n="Dashboard">General Info</span></a>
                <ul class="menu-content">
                  @canany(['create', 'viewAny'], \App\Models\GeneralInfo\item::class)
                  <li><a class="menu-item {{request()->routeIs('item.*') ? 'active' : ''}}" href="#"><i class="la la-cube"></i><span data-i18n="All Items">Items</span></a>
                    <ul class="menu-content">
                        @can('viewAny', App\Models\GeneralInfo\item::class)
                        <li><a class="menu-item {{request()->routeIs('item.index') ? 'active' : ''}}" href="{{route('item.index')}}"><i></i><span data-i18n="All Items">All Items</span></a></li>
                        @endcan
                        @can('create', App\Models\GeneralInfo\item::class)
                        <li><a class="menu-item {{request()->routeIs('item.create') ? 'active' : ''}}" href="{{route('item.create')}}"><i></i><span data-i18n="All Items">New Item</span></a></li>
                        @endcan
                    </ul>
                  </li>
                  @endcan
                  @canany(['create', 'viewAny'], \App\Models\GeneralInfo\Tool::class)
                  <li><a class="menu-item {{request()->routeIs('tool.*') ? 'active' : ''}}" href="#"><i class="la la-legal"></i><span data-i18n="All Items">Tools</span></a>
                    <ul class="menu-content">
                      @can('viewAny', App\Models\GeneralInfo\Tool::class)
                      <li><a class="menu-item {{request()->routeIs('tool.index') ? 'active' : ''}}" href="{{route('tool.index')}}"><i></i><span data-i18n="All Items">All Tools</span></a></li>
                      @endcan
                      @can('create', App\Models\GeneralInfo\Tool::class)
                      <li><a class="menu-item {{request()->routeIs('tool.create') ? 'active' : ''}}" href="{{route('tool.create')}}"><i></i><span data-i18n="All Items">New Tool</span></a></li>
                      @endcan
                    </ul>
                  </li>
                  @endcan
                  @canany(['create', 'viewAny'], \App\Models\GeneralInfo\Specification::class)
                  <li><a class="menu-item {{request()->routeIs('specification.*') ? 'active' : ''}}" href="#"><i class="la la-odnoklassniki-square"></i><span data-i18n="All Items">Specifications</span></a>
                    <ul class="menu-content">
                      @can('viewAny', App\Models\GeneralInfo\Specification::class)
                      <li><a class="menu-item {{request()->routeIs('specification.index') ? 'active' : ''}}" href="{{route('specification.index')}}"><i></i><span data-i18n="All Items">All Specifications</span></a></li>
                      @endcan
                      @can('create', App\Models\GeneralInfo\Specification::class)
                      <li><a class="menu-item {{request()->routeIs('specification.create') ? 'active' : ''}}" href="{{route('specification.create')}}"><i></i><span data-i18n="All Items">New Specification</span></a></li>
                      @endcan
                    </ul>
                  </li>
                  @endcan
                    @canany(['create', 'viewAny'], \App\Models\GeneralInfo\Specification::class)
                    <li>
                        <a class="menu-item {{request()->routeIs('footer-values.index') ? 'active' : ''}}"
                           href="{{route('footer-values.index')}}">
                            <i class="la la-comment-o"></i>
                            <span data-i18n="All Items">Footer Values</span>
                        </a>
                    </li>
                    @endcan
                    @canany(['create', 'viewAny'], \App\Models\GeneralInfo\Specification::class)
                    <li>
                        <a class="menu-item {{request()->routeIs('inspectionLogo.index') ? 'active' : ''}}"
                           href="{{route('inspectionLogo.index')}}">
                            <i class="la la-comment-o"></i>
                            <span data-i18n="All Items">Header Logos</span>
                        </a>
                    </li>
                    @endcan
                    @canany(['create', 'viewAny'], \App\Models\GeneralInfo\Specification::class)
                    <li>
                        <a class="menu-item {{request()->routeIs('footer-address.*') ? 'active' : ''}}"
                           href="{{route('footer-address.edit')}}">
                            <i class="la la-map-marker"></i>
                            <span data-i18n="All Items">Footer Address</span>
                        </a>
                    </li>
                    @endcan
                </ul>
              </li>
              @endif
            </ul>
        </div>
    </div>
<!-- END: Main Menu-->
