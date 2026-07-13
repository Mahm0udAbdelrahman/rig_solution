@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    <section id="validation">
        <div class="row">
            <div class="col-12">
                {!! Form::model($model, ['route' => ['subsDimensional.update', $model->id], 'method' => 'PATCH', 'class'=> 'steps-validation wizard-circle']) !!}
                {{ csrf_field() }}
                @include('layouts.inspection.tubular.subsDimensional.form');
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection

@extends('layouts.scripts.forms')

@prepend('child-scripts')
<script>
  $(".steps-validation").steps({
    enableAllSteps: true,
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
      finish: 'Submit'
    },
    onStepChanging: function (event, currentIndex, newIndex) {
      // Allways allow previous action even if the current form is not valid!
      if (currentIndex > newIndex) {
        return true;
      }
// Needed in some cases if the user went back (clean up)
      if (currentIndex < newIndex) {
        // To remove error styles
        form.find(".body:eq(" + newIndex + ") label.error").remove();
        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
      }
      if (currentIndex === 1 && newIndex === 2) {
        return true;
      }
      return window.validateWizardCurrentStep($(this));
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

@push('bottom-child-scripts')
    <script>
      /*** bind jop request data on edit ***/
      $('#lcr_1').prop('disabled', true);
      var id = $('#lcr_1').val();
      var text = $('#lcr_1 option:selected').text();
      console.log('>>>>', id, text);
      get_report_data_for_update(id, text);
      /*************************************/

      $('.steps-validation').on('ifChecked', '.specification', function () {
        var id =  this.id; //S-008 is the code for the other spec entity
        if (id === 'S-008') {
          $('#other_specification').prop("disabled", false);
          $('#other_specification_section').show();
        }
      });
      $('.steps-validation').on('ifUnchecked', '.specification', function (event) {
        var id =  this.id;
        if (id === 'S-008') {
          $('#other_specification').prop("disabled", true).val('');
          $('#other_specification_section').hide();
        }
      });
      //___________________________________________________________________________

      $('.steps-validation').on('ifChecked', '.inspection_method', function () {
        var id =  $(this).data('id');
        if (id === 'other') {
          $('#other_inspection_method').prop("disabled", false);
          $('#other_inspection_method_section').show();
        }
      });
      $('.steps-validation').on('ifUnchecked', '.inspection_method', function (event) {
        var id =  $(this).data('id');
        if (id === 'other') {
          $('#other_inspection_method').prop("disabled", true).val('');
          $('#other_inspection_method_section').hide();
        }
      });
      //_____________________________________________________________________________

      $('.steps-validation').on('ifChecked', '.equipment_used', function () {
        var id =  $(this).data('id');
        if (id === 'other') {
          $('#other_equipment').prop("disabled", false);
          $('#other_equipment_section').show();
        }
      });
      $('.steps-validation').on('ifUnchecked', '.equipment_used', function (event) {
        var id =  $(this).data('id');
        if (id === 'other') {
          $('#other_equipment').prop("disabled", true).val('');
          $('#other_equipment_section').hide();
        }
      });
    </script>


@endpush

