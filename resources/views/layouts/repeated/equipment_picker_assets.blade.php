{{-- Styles + script for equipment pickers; included once per page, and before any <template> holding a picker --}}
@once
<style>
    .equipment-picker-target[readonly] { background-color: #f4f5fa; cursor: not-allowed; }
    .table-responsive .equipment-picker + .select2-container { min-width: 240px; max-width: 340px; }
    .report-equipment-list { overflow-x: auto; }
    /* Keep the sideways scrollbar visible (macOS hides it until scrolling) */
    .table-responsive::-webkit-scrollbar, .report-equipment-list::-webkit-scrollbar { height: 10px; }
    .table-responsive::-webkit-scrollbar-track, .report-equipment-list::-webkit-scrollbar-track { background: #eef0f7; border-radius: 5px; }
    .table-responsive::-webkit-scrollbar-thumb, .report-equipment-list::-webkit-scrollbar-thumb { background: #b9bfd6; border-radius: 5px; }
    .report-equipment-list .report-equipment-row { min-width: 820px; flex-wrap: nowrap; }
</style>
<script>
// jQuery is loaded at the bottom of the page, so wire everything up once the page has loaded
// Form wizards move their markup with jQuery, which runs inline scripts again: bind only once
if (!window.__equipmentPickerScript) {
window.__equipmentPickerScript = true;
window.addEventListener('load', function () {
    var $ = window.jQuery;
    if (!$) return;

    function scopeOf($picker) {
        var scope = $picker.data('scope');
        return scope ? $picker.closest(scope) : $(document);
    }

    function eachTarget($picker, callback) {
        var $scope = scopeOf($picker);
        $.each($picker.data('fill') || {}, function (key, selector) {
            $scope.find(selector).each(function () { callback(key, $(this)); });
        });
    }

    // Tick checkboxes (plain or iCheck) so the report enables the section/column before values are filled
    window.checkEquipmentToggles = function (selectors) {
        $.each(selectors || [], function (i, selector) {
            var $box = $(selector);
            if (!$box.length || $box.is(':checked')) return;
            if (typeof $.fn.iCheck === 'function' && $box.parent('[class*="icheckbox"]').length) {
                $box.iCheck('check');
            } else {
                $box.prop('checked', true).trigger('change');
            }
        });
    };

    function isOther($picker) {
        return $picker.val() === 'other';
    }

    // Values that only mean "nothing here" (sections switched off fill NA)
    function isBlank(value) {
        return /^\s*(n\/?a|-)?\s*$/i.test(value || '');
    }

    // Register items fill read-only fields; "Other" lets the user type them
    function applyLock($picker) {
        var locked = !isOther($picker);
        eachTarget($picker, function (key, $target) {
            $target.prop('readonly', locked).toggleClass('equipment-picker-target', locked);
        });
    }

    function fillFrom($picker) {
        applyLock($picker);
        if (isOther($picker)) {
            // Start the manual entry empty, then put the cursor in the first field the user can see
            var $first = null;
            eachTarget($picker, function (key, $target) {
                if ($target.val() !== '') $target.val('').trigger('change');
                if (!$first && $target.is(':visible')) $first = $target;
            });
            if ($first) $first.trigger('focus');
            return;
        }
        var data = $picker.find('option:selected').data('equipment') || {};
        eachTarget($picker, function (key, $target) {
            var value = data[key] == null ? '' : String(data[key]);
            // Only fire change when the value really changes: reports collect these fields on "change"
            if ($target.val() !== value) {
                $target.val(value).trigger('change');
            }
        });
    }

    function preselect($picker) {
        if ($picker.val()) return; // already selected on the server
        var key = $picker.data('match');
        var current = null;
        eachTarget($picker, function (targetKey, $target) {
            if (targetKey === key && current === null) current = $target.val();
        });
        var found = false;
        if (!isBlank(current)) {
            $picker.find('option').each(function () {
                var data = $(this).data('equipment');
                if (data && String(data[key]) === current) {
                    $picker.val(this.value).trigger('change.select2');
                    found = true;
                    return false;
                }
            });
        }
        if (found || !$picker.find('option[value="other"]').length || $picker.prop('disabled')) return;
        // Saved values that are not in the register were typed by hand: keep them editable under "Other"
        var typed = false;
        eachTarget($picker, function (targetKey, $target) {
            if (!isBlank($target.val())) typed = true;
        });
        if (typed) $picker.val('other').trigger('change.select2');
    }

    function syncToggle($picker) {
        var clearWith = $picker.data('clear-with');
        if (clearWith && !$(clearWith).is(':checked') && $picker.val()) {
            $picker.val('').trigger('change.select2');
        }
        var toggle = $picker.data('toggle-with');
        if (toggle) {
            var enabled = $(toggle).is(':checked');
            $picker.prop('disabled', !enabled);
            if (!enabled && $picker.val()) {
                $picker.val('').trigger('change.select2');
            }
        }
        applyLock($picker);
    }

    // Initialise pickers inside scope (call again for rows added later, e.g. repeaters)
    window.initEquipmentPickers = function (scope) {
        $(scope || document).find('select.equipment-picker').each(function () {
            var $picker = $(this);
            // A cloned row carries a dead select2 container: drop it so select2 can start fresh
            if ($picker.hasClass('select2-hidden-accessible') && !$picker.data('select2')) {
                $picker.removeClass('select2-hidden-accessible').removeAttr('data-select2-id aria-hidden tabindex');
                $picker.find('option').removeAttr('data-select2-id');
                $picker.next('.select2-container').remove();
            }
            if (!$picker.data('select2')) {
                if (typeof window.initSearchableSelect === 'function') {
                    window.initSearchableSelect($picker);
                } else if (typeof $.fn.select2 === 'function') {
                    $picker.select2({ width: '100%', placeholder: $picker.data('placeholder'), allowClear: true });
                }
            }
            syncToggle($picker);
            preselect($picker);
            applyLock($picker);
        });
    };

    $(document).on('change', 'select.equipment-picker', function () {
        if ($(this).val()) window.checkEquipmentToggles($(this).data('checks'));
        fillFrom($(this));
    });

    var toggles = {};
    $('select.equipment-picker[data-toggle-with], select.equipment-picker[data-clear-with]').each(function () {
        if ($(this).data('toggle-with')) toggles[$(this).data('toggle-with')] = true;
        if ($(this).data('clear-with')) toggles[$(this).data('clear-with')] = true;
    });
    $.each(toggles, function (toggle) {
        $(document).on('change ifChanged', toggle, function () {
            // iCheck updates the checkbox state right after firing its events
            setTimeout(function () {
                $('select.equipment-picker').filter(function () {
                    return $(this).data('toggle-with') === toggle || $(this).data('clear-with') === toggle;
                }).each(function () { syncToggle($(this)); });
            }, 0);
        });
    });

    window.initEquipmentPickers(document);
});
}
</script>
@endonce
