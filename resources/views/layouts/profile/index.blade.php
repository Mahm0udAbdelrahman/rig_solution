@extends('layouts.app')

@section('content')
    <section class="users-edit">
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
                        <strong>Please review the highlighted fields.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(auth()->user()->can('update', $employee))
                    <div class="alert alert-info">
                        You can manage the same user/employee data from Organization Employees too.
                        <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary ml-1">Open Employee Unified Edit</a>
                    </div>
                @endif

                @php
                    $avatarUrl = $employee->avatar_url;
                    $esignUrl = $employee->esign_url;
                    $hasAvatarFile = !empty($avatarUrl);
                    $hasEsignFile = !empty($esignUrl);
                    $esignExtension = strtolower(pathinfo((string) $employee->esign, PATHINFO_EXTENSION));
                    $isEsignImage = in_array($esignExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'], true);
                @endphp
            </div>

            <div class="col-xl-4 col-lg-5 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body text-center">
                            <span class="avatar avatar-xl avatar-online mb-1 app-user-avatar-shell app-user-avatar-xl">
                                @if($hasAvatarFile)
                                    <img src="{{ $avatarUrl }}" alt="avatar">
                                @else
                                    <span class="app-user-avatar-fallback">{{ $employee->avatar_initials }}</span>
                                @endif
                            </span>
                            <h4 class="mb-25">{{ $employee->name }}</h4>
                            <p class="text-muted mb-50">{{ optional($user->role)->name ?: ($user->is_super_admin ? 'Super Admin' : 'User') }}</p>
                            <div class="d-flex justify-content-center flex-wrap mb-1">
                                <span class="badge badge-light-primary mr-50 mb-50">{{ $employee->code }}</span>
                                @if($employee->email)
                                    <span class="badge badge-light-secondary mb-50">{{ $employee->email }}</span>
                                @endif
                            </div>
                            @if($employee->departments->isNotEmpty())
                                <div class="mt-1">
                                    @foreach($employee->departments as $department)
                                        <span class="badge badge-info mr-50 mb-50">{{ $department->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header pb-0">
                        <h4 class="card-title">Current Signature</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @if($hasEsignFile && $isEsignImage)
                                <div class="text-center border rounded p-1 bg-light">
                                    <img src="{{ $esignUrl }}" alt="E-Signature" style="max-width: 100%; max-height: 180px;">
                                </div>
                            @elseif($hasEsignFile)
                                <a href="{{ $esignUrl }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="la la-file mr-25"></i> Open Signature File
                                </a>
                            @else
                                <p class="text-muted mb-0">No signature uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7 col-12">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title">Profile Details</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Employee Code</label>
                                            <input type="text" class="form-control" value="{{ $employee->code }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Role</label>
                                            <input type="text" class="form-control" value="{{ optional($user->role)->name ?: ($user->is_super_admin ? 'Super Admin' : 'User') }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="profile-name">Name</label>
                                            <input id="profile-name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $employee->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="profile-email">E-mail</label>
                                            <input id="profile-email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $employee->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="profile-tel">Telephone</label>
                                            <input id="profile-tel" type="text" name="tel" class="form-control @error('tel') is-invalid @enderror" value="{{ old('tel', $employee->tel) }}">
                                            @error('tel')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="profile-avatar">Avatar</label>
                                            <div class="custom-file">
                                                <input id="profile-avatar" type="file" name="avatar" class="custom-file-input @error('avatar') is-invalid @enderror">
                                                <label class="custom-file-label" for="profile-avatar">Choose file</label>
                                            </div>
                                            @error('avatar')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="profile-esign">Signature File</label>
                                            <div class="custom-file">
                                                <input id="profile-esign" type="file" name="esign" class="custom-file-input @error('esign') is-invalid @enderror">
                                                <label class="custom-file-label" for="profile-esign">Choose file</label>
                                            </div>
                                            @error('esign')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="profile-desc">Description</label>
                                            <textarea id="profile-desc" name="desc" rows="4" class="form-control @error('desc') is-invalid @enderror">{{ old('desc', $employee->desc) }}</textarea>
                                            @error('desc')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @if($hasAvatarFile)
                                        <div class="col-12">
                                            <fieldset class="checkbox mb-75">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input type="checkbox" name="remove_avatar" value="1" {{ old('remove_avatar') ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span>Remove current avatar image</span>
                                                </div>
                                            </fieldset>
                                        </div>
                                    @endif
                                    @if($hasEsignFile)
                                        <div class="col-12">
                                            <fieldset class="checkbox">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input type="checkbox" name="remove_esign" value="1" {{ old('remove_esign') ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span>Remove current signature file</span>
                                                </div>
                                            </fieldset>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title">Security</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="profile-password">New Password</label>
                                            <input id="profile-password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="profile-password-confirmation">Confirm Password</label>
                                            <input id="profile-password-confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="material-icons align-middle font-small-2 mr-25">save</i>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
