<script src="{{ asset('app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js') }}"></script>
<script>
// Set a date-input field from a Y-m-d value (hidden input) and show it as dd-mm-yyyy
function setDateField(name, ymd) {
    $('#' + name).val(ymd || '');
    $('#' + name + '_display').val(ymd ? ymd.split('-').reverse().join('-') : '');
}

$(document).ready(function() {
    $('.date-display').each(function() {
        var $display = $(this);
        var $value = $($display.data('target'));
        $display.datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy',
            altField: $value,
            altFormat: 'yy-mm-dd',
            beforeShow: function(input, inst) {
                if (input.readOnly) return false;
                inst.dpDiv.addClass('modern-datepicker-popup');
            },
            onSelect: function() { $value.trigger('change'); }
        });
        // Typed or cleared by hand
        $display.on('change', function() {
            if (!this.value) $value.val('');
            $value.trigger('change');
        });
    });
});
</script>
