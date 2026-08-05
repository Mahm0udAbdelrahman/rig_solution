@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    <section id="footer-address-edit">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><i class="la la-map-marker"></i> Edit Footer Address</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible mb-2" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Success!</strong> {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('footer-address.update') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address_line_1"><strong>Address Line 1:</strong></label>
                                            <input type="text" id="address_line_1" name="address_line_1" class="form-control" value="{{ old('address_line_1', $model->address_line_1) }}" placeholder="e.g. Head Office: Block# 3053|Hamdy Ramadan street">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address_line_2"><strong>Address Line 2:</strong></label>
                                            <input type="text" id="address_line_2" name="address_line_2" class="form-control" value="{{ old('address_line_2', $model->address_line_2) }}" placeholder="e.g. 2nd Floor #2 |El-Mearag City|Maadi|Cairo|Egypt">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone"><strong>Phone:</strong></label>
                                            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $model->phone) }}" placeholder="e.g. +20 2 24477058">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile"><strong>Mobile:</strong></label>
                                            <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile', $model->mobile) }}" placeholder="e.g. +20 1032703368">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email"><strong>Email:</strong></label>
                                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $model->email) }}" placeholder="e.g. rse@rigsolutionz.com">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="website"><strong>Website:</strong></label>
                                            <input type="text" id="website" name="website" class="form-control" value="{{ old('website', $model->website) }}" placeholder="e.g. www.rigsolutionz.com">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions right">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="la la-check-square-o"></i> Save Changes
                                    </button>
                                </div>
                            </form>

                            <hr class="mt-3 mb-2" />

                            <h5 class="text-bold-600 mb-1">Live Preview on Reports:</h5>
                            <div class="p-2 border rounded" style="background: #f8f9fa; font-size: 11px; font-weight: 700; color: #000;">
                                <div>
                                    {!! $model->getPartsHtml() !!}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
