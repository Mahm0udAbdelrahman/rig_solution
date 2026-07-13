@push('child-scripts')
		<script>
        var modules;
        var roles = [];
        var permissions = [];

        $('.custom-control-input').prop( "disabled", true );

        var elems = document.querySelectorAll('input.switchery');

        for (var i = 0; i < elems.length; i++)
        {
            var changeCheckbox = document.querySelector('#'+elems[i].id);
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
