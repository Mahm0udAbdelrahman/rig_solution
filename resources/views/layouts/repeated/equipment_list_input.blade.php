<!-- equipments_list_input.blade.php -->

<div class="card-content collapse show">
    <div class="card-body" data-repeater-list="equipment_no">
        <div class="row">
            <div class="col-3">
                <div class="form-group mb-0">
                    <label>Select Equipment</label>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group mb-0">
                    <label>Equipment No.</label>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group mb-0">
                    <label>Equipment Used</label>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group mb-0">
                    <label>Other Equipment</label>
                </div>
            </div>
            <div class="col-1"></div>
        </div>

        @php
            $items = [];

            if (isset($model) && $model->equipment_no != null) {
                $items = $model->equipment_no;
            } elseif (isset($model) && $model->equipments_data != null ) {
                $items = $model->equipments_data;
            } else {
                $items = [['equipment_no_value' => '', 'equipment_used' => '', 'other_equipment' => '']];
            }
        @endphp
        @foreach($items as $item)
            @php
                $item = is_array($item) ? $item : (array) $item;
                $equipmentUsedValue = (string) (
                    $item['equipment_used']
                    ?? $item['equipment']
                    ?? $item['used_equipment']
                    ?? ''
                );
                $otherEquipmentValue = (string) (
                    $item['other_equipment']
                    ?? $item['equipment_other']
                    ?? $item['other']
                    ?? ''
                );
                $equipmentNumberValue = (string) (
                    $item['equipment_no_value']
                    ?? $item['equipment_no']
                    ?? $item['equipment_number']
                    ?? $item['number']
                    ?? ''
                );
            @endphp
            <div class="row" data-repeater-item>
                <div class="col-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                            {{-- No name: the picker only fills the row, it is never submitted --}}
                            @include('layouts.repeated.equipment_picker', ['fill' => ['equipment_no' => '.equipment-no-value'], 'scope' => '[data-repeater-item]', 'class' => 'equipment-list-picker', 'placeholder' => 'Search serial no. or name'])
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <input type="text" id="equipment_no_value" name="equipment_no_value"
                                   class="form-control equipment-no-value" placeholder="Equipment No."
                                   value="{{$equipmentNumberValue}}"/>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <select id="equipment_used" name="equipment_used" class="form-control equipment-used">
                                <option value="">Select Equipment</option>
                                @foreach($equipments as $equipment)
                                    <option value="{{ $equipment }}" {{ $equipmentUsedValue == $equipment ? 'selected' : '' }}>{{ $equipment }}</option>
                                @endforeach
                                <option value="Other" {{$equipmentUsedValue == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
                <div class="col-3 other-equipment-wrapper" style="{{ $equipmentUsedValue == 'Other' ? '' : 'display: none;' }}">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <input type="text" id="other_equipment" name="other_equipment"
                                   class="form-control other-equipment" placeholder="Enter other equipment"
                                   value="{{ $otherEquipmentValue }}"/>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-danger" data-repeater-delete><i class="ft-x"></i></button>
                </div>
            </div>
        @endforeach
    </div>
    <div class="form-group overflow-hidden">
        <div class="col-12">
            <button type="button" data-repeater-create class="btn btn-primary"><i class="ft-plus"></i> Add </button>
        </div>
    </div>
</div>

<script>
  // jQuery is loaded at the bottom of the page, so wire everything up once the page has loaded
  window.addEventListener('load', function () {
    var $ = window.jQuery;
    if (!$) return;

    function toggleOtherEquipment($select) {
      var $row = $select.closest('[data-repeater-item]');
      $row.find('.other-equipment-wrapper').toggle($select.val() === 'Other');
    }

    // Toggle other equipment input based on selected option
    $(document).on('change', '.equipment-used', function () {
      toggleOtherEquipment($(this));
    });

    // Picking equipment from the register also sets "Equipment Used" (or "Other" + its description)
    $(document).on('change', 'select.equipment-list-picker', function () {
      var $row = $(this).closest('[data-repeater-item]');
      var $used = $row.find('.equipment-used');
      var $other = $row.find('.other-equipment');
      var data = $(this).find('option:selected').data('equipment') || {};
      var description = $.trim(data.equipment_description == null ? '' : String(data.equipment_description));
      var used = '';
      var other = '';

      if ($(this).val()) {
        $used.find('option').each(function () {
          if (this.value !== '' && this.value !== 'Other' && $.trim(this.value).toLowerCase() === description.toLowerCase()) {
            used = this.value;
            return false;
          }
        });
        if (!used) {
          used = 'Other';
          other = description;
        }
      }

      $other.val(other);
      $used.val(used).trigger('change');
    });

    // Rows added by the repeater are cloned from the first row: give them an empty, working picker
    function initNewEquipmentRows() {
      $('[data-repeater-list="equipment_no"] [data-repeater-item]').each(function () {
        var $row = $(this);
        var $picker = $row.find('select.equipment-list-picker');
        if (!$picker.length || $picker.data('select2')) return;
        // Cloned options still carry the source row's select2 cache ids
        $picker.find('option').removeAttr('data-select2-id');
        $picker.val('');
        $row.find('.equipment-no-value').val('');
        toggleOtherEquipment($row.find('.equipment-used'));
        if (typeof window.initEquipmentPickers === 'function') {
          window.initEquipmentPickers($row);
        }
      });
    }

    // The repeater appends the new row in its own click handler, which runs before this delegated one
    $(document).on('click', '[data-repeater-create]', initNewEquipmentRows);

    // Initialize visibility based on initial values
    $('.equipment-used').each(function () {
      toggleOtherEquipment($(this));
    });
  });
</script>
