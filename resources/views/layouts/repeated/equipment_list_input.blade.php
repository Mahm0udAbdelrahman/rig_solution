<!-- equipments_list_input.blade.php -->

<div class="card-content collapse show">
    <div class="card-body" data-repeater-list="equipment_no">
        <div class="row">
            <div class="col-3">
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
                            <input type="text" id="equipment_no_value" name="equipment_no_value"
                                   class="form-control" placeholder="Equipment No."
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
                                   class="form-control" placeholder="Enter other equipment"
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
  $(document).ready(function() {
    // Initialize repeater
    // $('[data-repeater-list="equipment_no"]').repeater({
    //   show: function () {
    //     $(this).slideDown();
    //   },
    //   hide: function (deleteElement) {
    //     if (confirm('Are you sure you want to delete this element?')) {
    //       $(this).slideUp(deleteElement);
    //     }
    //   },
    //   isFirstItemUndeletable: true // Ensures the first item cannot be deleted
    // });

    // Toggle other equipment input based on selected option
    $(document).on('change', '.equipment-used', function() {
      var $row = $(this).closest('.row');
      var selectedValue = $(this).val();
      if (selectedValue === 'Other') {
        $row.find('.other-equipment-wrapper').show();
      } else {
        $row.find('.other-equipment-wrapper').hide();
      }
    });

    // Ensure only one row is added at a time
    // $(document).on('click', '[data-repeater-create]', function() {
    //   var $list = $(this).closest('.card-body').find('[data-repeater-list="equipment_no"]');
    //   var $firstItem = $list.find('[data-repeater-item]:first');
    //
    //   // Clone the first item and append
    //   var $newItem = $firstItem.clone();
    //   $list.append($newItem);
    //
    //   // Initialize select2 or other plugins if needed for the new item
    //   $newItem.find('.other-equipment-wrapper').hide(); // Hide other equipment input initially for the new item
    // });

    // Initialize visibility based on initial values
    $('.equipment-used').each(function() {
      var $row = $(this).closest('.row');
      var selectedValue = $(this).val();
      if (selectedValue === 'Other') {
        $row.find('.other-equipment-wrapper').show();
      } else {
        $row.find('.other-equipment-wrapper').hide();
      }
    });
  });
</script>
