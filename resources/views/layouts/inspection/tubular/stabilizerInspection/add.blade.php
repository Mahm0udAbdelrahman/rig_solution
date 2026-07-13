@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    <section id="validation">
        <div class="row">
            <div class="col-12">
{{--                <form action="{{route('summaryPipes.store')}}" method="post" class="steps-validation wizard-circle">--}}
                    {{--                    {{ Form::model(null, ['route' => 'my-route', 'method' => 'patch', 'class' => 'steps-validation wizard-circle']) }}--}}
                {!! Form::open(['url'=>route('stabilizerInspection.store'),'class' => 'steps-validation wizard-circle']) !!}
                {{ csrf_field() }}
                @include('layouts.inspection.tubular.stabilizerInspection.form')
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection

@extends('layouts.scripts.forms')

@prepend('child-scripts')

<script>
  $(".steps-validation").steps({
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
      if (currentIndex === 2 && newIndex === 3) {
        var $wizard = $(this);
        var $condition = $wizard.find('input[name="condition"]:checked').first();
        var $material = $wizard.find('input[name="material_description"]').first();
        var $toolNumber = $wizard.find('input[name="tool_number"]').first();
        var $inspectionApplied = $wizard.find('input[name="inspection_applied"]').first();

        var hasCondition = $condition.length > 0;
        var materialValue = $.trim(($material.val() || '').toString());
        var toolNumberValue = $.trim(($toolNumber.val() || '').toString());
        var inspectionAppliedValue = $.trim(($inspectionApplied.val() || '').toString());

        if (hasCondition && materialValue !== '' && toolNumberValue !== '' && inspectionAppliedValue !== '') {
          return true;
        }

        var $firstMissing = !hasCondition ? $wizard.find('input[name="condition"]').first()
          : (materialValue === '' ? $material : (toolNumberValue === '' ? $toolNumber : $inspectionApplied));
        if (window.showWizardValidationMessage) {
          window.showWizardValidationMessage($wizard, $firstMissing);
        }
        return false;
      }
      if (currentIndex === 3 && newIndex === 4) {
        var $wizard = $(this);
        var missingField = null;

        var getFirst = function (name) {
          return $wizard.find('[name="' + name + '"]').first();
        };

        var isTextFilled = function (name) {
          var $field = getFirst(name);
          if (!$field.length) {
            return false;
          }
          return $.trim(($field.val() || '').toString()) !== '';
        };

        if (!isTextFilled('pipe_type')) {
          missingField = getFirst('pipe_type');
        }

        if (!missingField && !$wizard.find('input[name="dimensions_data[unit]"]:checked').length) {
          missingField = $wizard.find('input[name="dimensions_data[unit]"]').first();
        }

        var stepFourTextNames = [
          'dimensions_data[front]', 'dimensions_data[middle]', 'dimensions_data[back]',
          'dimensions_data[input_a]', 'dimensions_data[input_b]', 'dimensions_data[input_c]',
          'dimensions_data[input_d]', 'dimensions_data[input_e]'
        ];

        for (var i = 1; !missingField && i <= 20; i++) {
          stepFourTextNames.push('inspection_data[input_' + i + ']');
        }

        for (var idx = 0; !missingField && idx < stepFourTextNames.length; idx++) {
          if (!isTextFilled(stepFourTextNames[idx])) {
            missingField = getFirst(stepFourTextNames[idx]);
          }
        }

        if (!missingField) {
          return true;
        }

        if (window.showWizardValidationMessage) {
          window.showWizardValidationMessage($wizard, missingField);
        }
        return false;
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
    <script src="{{asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('app-assets/js/scripts/forms/form-repeater.js')}}"></script>
    <script>

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


      /**************************************************************/
      $('.repeater').each(function () {
        var $repeater = $(this);
        if ($repeater.data('inspectionTubularRepeaterBound')) {
          return;
        }

        $repeater.data('inspectionTubularRepeaterBound', true);
        $repeater.repeater({
          show: function () {
            $(this).slideDown();
          },
          hide: function (remove) {
            var $item = $(this);

            if (window.Swal && typeof window.Swal.fire === 'function') {
              window.Swal.fire({
                title: 'Delete row?',
                text: 'This row will be removed from the report.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#f44336'
              }).then(function (result) {
                if (result.isConfirmed) {
                  $item.slideUp(150, function () {
                    remove.call(this);
                  });
                }
              });
              return;
            }

            if (confirm('Delete this row?')) {
              $item.slideUp(150, function () {
                remove.call(this);
              });
            }
          }
        });
      });
</script>


@endpush

