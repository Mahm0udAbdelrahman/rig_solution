/***************************************************************************/
/*** This Function To Get Client / Supplier Data ***/
function getclinetOrSupplier(url){
    $('#client').html('<option value="">Select Value</option>');
    $('#contact').html('<option value="">Select Value</option>');
    $('#code').val('');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: url,
        dataType: "JSON",
        success: function (data) {
              $.each(data, function( index, value ) {
                  $('#client').append("<option value='"+value.id+"'> "+value.name+"</option>");
              });
        },
    });
}
getclinetOrSupplier("{{route('client.forJcf')}}");
/***************************************************************************/
/*** This Function To Get Client / Supplier Code & Contact Persons Data ***/
function getcodeAndContactPersons(url){
    $('#contact').html('<option value="">Select Value</option>');
    $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          type: 'GET',
          url: url,
          dataType: "JSON",
          success: function (data) {
              $('#code').val(data.code);
              $.each(data.contactperson, function( index, value ) {
                  $('#contact').append("<option value='"+value.id+"'> "+value.name+"</option>");
              });
          },
    });
}
/***************************************************************************/
/*** This Function To Get Managers & Employees ***/
function getManagersAndEmployees(){
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: "{{route('department.showManagers')}}",
        cache: false,
        data: {
            "department":department
        },
        success: function(data){
            $('#managers, #employees, #assistants').html('');
            $.each(data.managers, function( key1, value1 ){
                $('#managers').append('<div class="col-md-6 col-sm-12"><fieldset><input type="checkbox" class="manager" name="manager" data-id="'+value1+'" id="manager'+key1+'" /><label for="manager'+key1+'">'+value1+'</label></fieldset></div>');
            });
            $.each(data.employees, function( key, value ) {
                $('#employees').append('<div class="col-md-3 col-sm-12"><fieldset><input type="radio" class="eng" name="eng" data-id="'+value+'" id="employee'+key+'" /><label for="employee'+key+'">'+value+'</label></fieldset></div>');
            });
            $.each(data.assistants, function( key, value ) {
                $('#assistants').append('<div class="col-md-3 col-sm-12"><fieldset><input type="checkbox" class="assistant" name="assistant" data-id="'+value+'" id="assistant'+key+'" /><label for="assistant'+key+'">'+value+'</label></fieldset></div>');
            });
            $('.manager').iCheck({
                checkboxClass: 'icheckbox_square-green',
            });
            $('.eng').iCheck({
                radioClass: 'iradio_square-green',
            });
            $('.assistant').iCheck({
                checkboxClass: 'icheckbox_square-green',
            });
        },
    });
}
/***************************************************************************/
var url;
var contactway = [];
/***************************************************************************/
$('.contactway').on('ifChecked', function(event){
    var id = this.id;
    contactway.push(id);
});
$('.contactway').on('ifUnchecked', function(event){
    var id = this.id;
    index = contactway.indexOf(id);
    contactway.splice(index, 1);
});
/***************************************************************************/
var worklocation = [];
/***************************************************************************/
$('.worklocation').on('ifChecked', function(event){
    var id = this.id;
    worklocation.push(id);
});
$('.worklocation').on('ifUnchecked', function(event){
    var id = this.id;
    index1 = worklocation.indexOf(id);
    worklocation.splice(index1, 1);
});
/***************************************************************************/
var department = [];
/***************************************************************************/
$('.department').on('ifChecked', function(event){
    var id = this.id;
    department.push(id);
    console.log(department);
    getManagersAndEmployees();
});
$('.department').on('ifUnchecked', function(event){
    var id = this.id;
    index2 = department.indexOf(id);
    department.splice(index2, 1);
    getManagersAndEmployees();
});
/***************************************************************************/
var manager = [];
/***************************************************************************/
$('.steps-validation').on("ifChecked", '.manager',function (e){
    var id = $(this).data('id');
    manager.push(id);
});
$('.steps-validation').on("ifUnchecked", '.manager',function (e){
    var id = $(this).data('id');
    index = manager.indexOf(id);
    manager.splice(index, 1);
});
/***************************************************************************/
var eng = [];
/***************************************************************************/
$('.steps-validation').on("ifChecked", '.eng',function (e){
    var id = $(this).data('id');
    eng.push(id);
});
$('.steps-validation').on("ifUnchecked", '.eng',function (e){
    var id = $(this).data('id');
    index = eng.indexOf(id);
    eng.splice(index, 1);
});
/***************************************************************************/
var tool = [];
/***************************************************************************/
$('.tool').on('ifChecked', function(event){
  var id = this.id;
  tool.push(id);
});
$('.tool').on('ifUnchecked', function(event){
  var id = this.id;
  index = tool.indexOf(id);
  tool.splice(index, 1);
});
/***************************************************************************/
var specification = [];
/***************************************************************************/
$('.specification').on('ifChecked', function(event){
  var id = this.id;
  specification.push(id);
});
$('.specification').on('ifUnchecked', function(event){
  var id = this.id;
  index = specification.indexOf(id);
  specification.splice(index, 1);
});
/***************************************************************************/
url = "{{route('client.contactPersonShow', ':id')}}";
/***************************************************************************/
$('.steps-validation').on("change", '#suporcli',function (e){
    if(this.checked == true)
    {
        $('#csname').text('Client Name');
        getclinetOrSupplier("{{route('client.forJcf')}}");
        url = "{{route('client.contactPersonShow', ':id')}}";
        $('#persontype').val('1');
    }
    else
    {
        $('#csname').text('Supplier Name');
        getclinetOrSupplier("{{route('supplier.forJcf')}}");
        url = "{{route('supplier.contactPersonShow', ':id')}}";
        $('#persontype').val('2');
    }
});
/***************************************************************************/
$('.steps-validation').on("change", '#client',function (e){
    var id = $(this).val();
    url = url.replace(':id', id).replace(/\d+/g, id);
    getcodeAndContactPersons(url);
});
/***************************************************************************/
