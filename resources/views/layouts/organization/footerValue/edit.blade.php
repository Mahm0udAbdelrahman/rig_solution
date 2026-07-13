@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    <section id="validation">
        <div class="row">
            <h1>{{$model->name}}</h1>
            <div class="col-12">
                {!! Form::model($model, ['route' => ['footer-values.update', $model->id], 'method' => 'PATCH', 'class'=> 'steps-validation wizard-circle']) !!}
                {{ csrf_field() }}
                <div class="form-group">
                    <p>Related Inspection Model: {{$model->related_inspection}}</p>
                </div>

                <div class="form-group">
                    {!! Form::label('form_no', 'Form No') !!}
                    {!! Form::text('form_no', null, ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('issue_no', 'Issue No') !!}
                    {!! Form::text('issue_no', null, ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('issue_date', 'Issue Date') !!}
                    {!! Form::text('issue_date', null, ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('revision_no', 'Revision No') !!}
                    {!! Form::text('revision_no', null, ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('revision_date', 'Revision Date') !!}
                    {!! Form::text('revision_date', null, ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection

@extends('layouts.scripts.forms')

@prepend('child-scripts')
<script src="{{asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
<script src="{{asset('app-assets/js/scripts/forms/form-repeater.js')}}"></script>
<script>
  $(".steps-validation").steps({
    enableAllSteps: fales,
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
      finish: 'Submit'
    },
    onStepChanging: function (event, currentIndex, newIndex) {
      form.validate().settings.ignore = ":disabled,:hidden";
      return form.valid();
    },
    onFinishing: function (event, currentIndex) {
      form.validate().settings.ignore = ":disabled";
      return form.valid();
    },
    onFinished: function (event, currentIndex) {
      var form = $(this);
      form.submit();
    }
  });
</script>
@endprepend

@extends('layouts.scripts.reportsforms')

