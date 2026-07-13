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

            <div class="col-lg-5 col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4 class="card-title">Safe Browser Actions</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <p class="text-muted">
                                Use this page only on trusted admin sessions. It is intended for cPanel deployments where SSH is not available.
                            </p>
                            <div class="d-flex flex-column">
                                <form method="POST" action="{{ route('system.maintenance.run') }}" class="mb-75">
                                    @csrf
                                    <input type="hidden" name="command" value="all-safe">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="material-icons align-middle font-small-2 mr-25">play_circle_filled</i>
                                        Run Full Safe Deploy Steps
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('system.maintenance.run') }}" class="mb-75">
                                    @csrf
                                    <input type="hidden" name="command" value="migrate">
                                    <button type="submit" class="btn btn-outline-primary btn-block">Run Migrations</button>
                                </form>
                                <form method="POST" action="{{ route('system.maintenance.run') }}" class="mb-75">
                                    @csrf
                                    <input type="hidden" name="command" value="storage-link">
                                    <button type="submit" class="btn btn-outline-primary btn-block">Create Storage Link</button>
                                </form>
                                <form method="POST" action="{{ route('system.maintenance.run') }}" class="mb-75">
                                    @csrf
                                    <input type="hidden" name="command" value="optimize-clear">
                                    <button type="submit" class="btn btn-outline-primary btn-block">Clear All Caches</button>
                                </form>
                                <div class="row">
                                    <div class="col-sm-6 col-12 mb-75 mb-sm-0">
                                        <form method="POST" action="{{ route('system.maintenance.run') }}">
                                            @csrf
                                            <input type="hidden" name="command" value="view-clear">
                                            <button type="submit" class="btn btn-outline-secondary btn-block">View Clear</button>
                                        </form>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <form method="POST" action="{{ route('system.maintenance.run') }}">
                                            @csrf
                                            <input type="hidden" name="command" value="view-cache">
                                            <button type="submit" class="btn btn-outline-secondary btn-block">View Cache</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4 class="card-title">Last Command Output</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @if(empty($results))
                                <p class="text-muted mb-0">No command has been executed in this session yet.</p>
                            @else
                                @foreach($results as $result)
                                    <div class="mb-1">
                                        <h6 class="mb-50">{{ $result['label'] }}</h6>
                                        <pre class="bg-light border rounded p-1 mb-0" style="white-space: pre-wrap;">{{ $result['output'] ?: 'Command completed with no output.' }}</pre>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
