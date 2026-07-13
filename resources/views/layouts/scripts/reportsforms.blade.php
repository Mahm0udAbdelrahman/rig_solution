@push('child-scripts')
    <script>
      function getInspectionJcfSelectScope(scope) {
        return scope ? $(scope) : $('.steps-validation');
      }

      function getInspectionJcfSelects(scope) {
        return getInspectionJcfSelectScope(scope).find('select#lcr_1, select[name="lcr_1"], select[name="job_request_id"]');
      }

      function prepareInspectionJcfSearchableSelects(scope) {
        var $selects = getInspectionJcfSelects(scope).filter(function () {
          return !$(this).prop('disabled');
        });

        $selects.each(function () {
          var $select = $(this);
          $select.addClass('searchable-select inspection-jcf-select');
          if (!$select.attr('data-placeholder')) {
            $select.attr('data-placeholder', 'Search JCF Number');
          }
        });
      }

      prepareInspectionJcfSearchableSelects($('.steps-validation'));

      function get_report_data(id, text) {
        url = "{{route('jobRequest.showForAdd', ':id')}}";
        url = url.replace(':id', id);
        $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          type: 'GET',
          url: url,
          dataType: "JSON",
          success: function (data) {
            console.log('>>>', data);
            const preparDeploc = data.clientDepartment ? data.clientDepartment.name+' / '+ data.deploc
              : data.deploc;
            $('#precode').val(text + ' / ');
            $('#code').val(data.lastcode);
            $('#cliname').val(data.client_name);
            $('#cliloc').val(data.client_location);
            $('#deploc').val(preparDeploc);
            // $('#clientDepartment').val(data?.clientDepartment?.name);
						const purchaseOrderVal = data.purchase_order? data.purchase_order : '-';
            $('#purchaseOrder').val(purchaseOrderVal);
          },
        });
      }

      function get_report_data_for_update(id, text) {
        url = "{{route('jobRequest.showForAdd', ':id')}}";
        url = url.replace(':id', id);
        $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          type: 'GET',
          url: url,
          dataType: "JSON",
          success: function (data) {
            console.log('>>>>',data, data.purchase_order);
            const preparDeploc = data.clientDepartment ? data.clientDepartment.name+' / '+ data.deploc
              : data.deploc;
            $('#cliname').val(data.client_name);
            $('#cliloc').val(data.client_location);
            $('#deploc').val(preparDeploc);
            // $('#clientDepartment').val(data?.clientDepartment?.name);
						const purchaseOrderVal = data.purchase_order? data.purchase_order : '-';
            $('#purchaseOrder').val(purchaseOrderVal);

          },
        });
      }

      $('.steps-validation').on("change", '#lcr_1, select[name="lcr_1"], select[name="job_request_id"]', function (e) {
        var id = $(this).val();
        var text = $(this).find('option:selected').text();
        get_report_data(id, text);
      });

      var pop = 6;
      $('.steps-validation').on("change", '#suporcli', function (e) {
        $('#lcr_6, #lcr_7').val('');
        if (this.checked == true) {
          pop = 6;
        }
        else {
          pop = 12;
        }
        $('#months').text(pop);
      });

      $(".datepicker-default").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        constrainInput: false,
      }).on("change", function () {
        var date = new Date($(this).datepicker("getDate").toISOString());
        date.setMonth(date.getMonth() + pop);
        date.setDate(date.getDate() - 1);
        $('#lcr_7').val(('0' + date.getDate()).slice(-2) + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + date.getFullYear());
      });

    </script>
@endpush
