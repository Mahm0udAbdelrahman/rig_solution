@extends('layouts.app')

@section('content')
    <section class="system-maintenance-page">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $errors->first() }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>

            <div class="col-lg-6 col-12 mx-auto">
                <div class="card text-center p-2">
                    <div class="card-header pb-0 justify-content-center">
                        <h4 class="card-title font-weight-bold">Database Backup / النسخ الاحتياطي</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <p class="text-muted mb-2">
                                إضغط على الزر أدناه لتنزيل نسخة احتياطية كاملة ومباشرة من قاعدة البيانات بصيغة SQL.
                            </p>
                            <a href="{{ route('system.maintenance.downloadDatabase') }}" class="btn btn-success btn-lg btn-block py-1">
                                <i class="material-icons align-middle font-medium-3 mr-50">cloud_download</i>
                                DOWNLOAD DATABASE / تنزيل قاعدة البيانات
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

