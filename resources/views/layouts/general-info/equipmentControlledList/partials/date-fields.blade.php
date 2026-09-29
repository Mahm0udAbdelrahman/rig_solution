<script>
// Empty .date-field inputs show as blank text (some browsers, e.g. Safari, paint today's date into an
// empty type="date"), then become a date input whose calendar opens as soon as the user taps it.
function syncDateField(el) {
    el.type = el.value ? 'date' : 'text';
    if (el.type === 'text') el.placeholder = 'dd/mm/yyyy';
}

$(document).ready(function() {
    $('.date-field').each(function() { syncDateField(this); });

    function openCalendar(el) {
        if (el.readOnly) return;
        el.type = 'date';
        try { el.showPicker(); } catch (e) {}
    }

    // Focus from a tap/click (switching the type here swallows the click itself)
    $(document).on('focus', '.date-field', function() { openCalendar(this); });
    // Tapping a field that is already focused
    $(document).on('click', '.date-field', function() { openCalendar(this); });

    $(document).on('blur', '.date-field', function() { syncDateField(this); });
});
</script>
