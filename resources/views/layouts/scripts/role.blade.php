@push('child-scripts')
		<script>
        var modules;
        var roles = [];
        var permissions = [];

        $('.custom-control-input').prop( "disabled", true );

        var elems = document.querySelectorAll('input.switchery');

        for (var i = 0; i < elems.length; i++)
        {
            var changeCheckbox = elems[i];
            changeCheckbox.onchange = function() {
                var parent_id = $(this).closest('.top');
                if (this.checked == true)
                {
                    modules = this.id;
                    permissions.push({
                    modules : modules,
                    roles : [],
                    });
                    parent_id.find('.custom-control-input').prop( "disabled", false ).iCheck( "check");
                    parent_id.find('.icheckbox_square-green').removeClass('disabled');
                }
                else
                {
                    var v = this.id;
                    index = permissions.findIndex(x => x.modules === v);
                    permissions.splice(index, 1);
                    parent_id.find('.custom-control-input').prop( "disabled", true ).iCheck( "uncheck");
                }
            };
        }

        $('.custom-control-input').on('ifChecked', function(event){
            var parent_id = $(this).closest('.top').find('.switchery').attr('id');
            roles = $(this).attr('id').replace(parent_id+"-", "");
            $.each(permissions, function (i, value) {
                if (value.modules === parent_id)
                {
                    value.roles.push(roles);
                }
            });
        });

        // Bulk on / off: flips each module switch (which checks or clears its boxes) through the normal handlers above.
        function setModuleSwitch(input, on)
        {
            if (input.checked !== on)
            {
                $(input).siblings('span.switchery')[0].click();
            }
            else if (on)
            {
                $(input).closest('.top').find('.custom-control-input').prop( "disabled", false ).iCheck( "check");
            }
        }

        function bulkButtons(scope)
        {
            return $('<div class="mb-1"></div>')
                .append($('<button type="button" class="btn btn-sm btn-success mr-1"><i class="la la-check"></i> Turn all on</button>').on('click', function () {
                    $(scope()).find('input.switchery').each(function () { setModuleSwitch(this, true); });
                }))
                .append($('<button type="button" class="btn btn-sm btn-outline-danger"><i class="la la-close"></i> Turn all off</button>').on('click', function () {
                    $(scope()).find('input.switchery').each(function () { setModuleSwitch(this, false); });
                }));
        }

        $('.card').has('input.switchery').each(function () {
            var card = this;
            $(card).find('.card-body').first().prepend(bulkButtons(function () { return card; }));
        });

        $('.card').has('input.switchery').first().before(
            bulkButtons(function () { return $('.card').has('input.switchery'); }).addClass('ml-1').prepend('<span class="mr-1 text-bold-600">Every section:</span>')
        );

        $('.custom-control-input').on('ifUnchecked', function(event){
            var parent_id = $(this).closest('.top').find('.switchery').attr('id');
            var v = $(this).attr('id').replace(parent_id+"-", "");
            $.each(permissions, function (i, value) {
                if (value.modules === parent_id)
                {
                    $.each(value.roles, function (ii, value1) {
                        if (value1===v)
                        {
                            value.roles.splice(ii, 1);
                        }
                    });
                }
            });
        });
    </script>
@endpush
