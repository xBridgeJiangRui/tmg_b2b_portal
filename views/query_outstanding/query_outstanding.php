<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>
<?php
if($this->session->userdata('message'))
{
  ?>
  <div class="alert alert-success text-center" style="font-size: 18px">
  <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
</div>
  <?php
}
?>

<?php
if($this->session->userdata('warning'))
{
  ?>
  <div class="alert alert-danger text-center" style="font-size: 18px">
  <?php echo $this->session->userdata('warning') <> '' ? $this->session->userdata('warning') : ''; ?>
<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
</div>
  <?php
}
?>

  <div class="row">
    <div class="col-md-12">
    </div>
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B Reminder</h3><br>
          <div class="box-tools pull-right">
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
            <button id="import_excel" type="button" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Import Excel</button>
          </div>
        </div>
          <div class="box-body">
              
            <table class="table table-bordered table-striped dataTable" id="reminder_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;word-break: break-word !important">
                      <tr>
                          <th>Retailer</th>
                          <th>Supplier Name</th>
                          <th>Reg No</th>
                          <th>Overdue Registration Fee</th> 
                          <th>Overdue Subscription</th>
                          <th>Total Overdue</th>

                          <th>Last Subscriptions Invoice Count</th>
                          <th>Overdue Invoice Date From</th>
                          <th>Overdue Invoice Date To</th>
                          <th>Overdue Invoice Due Date</th>
                          <th>Last Invoice Date</th> 
                          <th>Last Invoice Amt</th>
                          <th>Last Invoice Due Date</th>

                          <th>Registration Date</th>
                          <th>Created At</th>
                          <th>Created By</th>
                          <th>Updated At</th>
                          <th>Updated By</th>
                          <th>Reminder Type</th>                          
                          <th>Action</th>
                          <!-- <th>Action</th> -->
                      </tr>
              </thead>
              <tbody> 
              </tbody>
            </table>

          </div>
      </div>
    </div>
  </div>

<!--   <div class="row">
    <div class="col-md-12">
    </div>
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B Reminder One Off</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
          <div class="box-body">
              
            <table class="table table-bordered table-striped dataTable" id="reminder_one_off_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;word-break: break-word !important">
                      <tr>
                          <th>Retailer</th>
                          <th>Supplier Name</th>
                          <th>Reg No</th>
                          <th>Overdue Registration Fee</th> 
                          <th>Overdue One Off</th>
                          <th>Total Overdue</th>
                          <th>Registration Date</th>
                          <th>Reminder Type</th>                          
                          <th>Action</th>
                      </tr>
              </thead>
              <tbody> 
              </tbody>
            </table>

          </div>
      </div>
    </div>
  </div>   -->

</div>
</div>

<script>
  $(document).ready(function () {    
$(document).on('click', '#edit_reminder', function(e) {

    var supplier_guid = $(this).attr('e_supplier_guid');
    var customer_guid = $(this).attr('e_customer_guid');
    var Retailer = $(this).attr('e_Retailer');
    var Supplier = $(this).attr('e_Supplier');
    var Reg_NO = $(this).attr('e_Reg_NO');
    var Overdue_Registration_Fees = $(this).attr('e_Overdue_Registration_Fees');
    var Overdue_Subscriptions = $(this).attr('e_Overdue_Subscriptions');
    var Total_Overdue = $(this).attr('e_Total_Overdue');
    var Registration_date = $(this).attr('e_Registration_date');
    var type = $(this).attr('e_type');
    var table_type = $(this).attr('e_table_type');
    var type_list_dropdown = "<?php echo $type_list_dropdown;?>";

    // alert(type);

    // alert(supplier_guid+'****'+customer_guid+'****'+Retailer+'****'+Supplier+'****'+Reg_NO+'****'+Overdue_Registration_Fees+'****'+Overdue_Subscriptions+'****'+Total_Overdue+'****'+Registration_date+'****'+type);
    // return;
    $('.modal').attr('data-backdrop','static')
    $('.modal').attr('data-keyboard','false')
    var xform_url = "<?php echo site_url('/Query_outstanding/reminder_update');?>"; 
    var modal = $("#large-modal").modal();

    modal.find('.modal-title').html('Reminder Update');

    methodd = '';

    // methodd +='<div id="myDropZone" class="dropzone" style="height:100px;"><label class="" id="output" for="upload_file">Upload Remittance(**If have)</label></div>';

    methodd += '<form id="excel_file_form" method="POST" action="'+xform_url+'">';
    methodd += '<div class="row" id="append_rimittance_row">';

    methodd += '<input class="form-control" type="hidden" name="reminder_customer_guid" id="reminder_customer_guid" readonly value="'+customer_guid+'"/>';
    methodd += '<input class="form-control" type="hidden" name="reminder_supplier_guid" id="reminder_supplier_guid" readonly value="'+supplier_guid+'"/>';
    methodd += '<input class="form-control" type="hidden" name="reminder_table_type" id="reminder_table_type" readonly value="'+table_type+'"/>';    

    methodd += '<div class="col-md-12">';
    methodd += '<div class="col-md-3">';
    methodd += '<label>Retailer</label>';
    methodd += '</div>'; 
    methodd += '<div class="col-md-9">';
    methodd += '<input class="form-control" type="text" name="reminder_retailer_name" id="reminder_retailer_name" readonly value="'+Retailer+'"/>';
    methodd += '</div>';        
    methodd += '</div>';
    methodd += '<br><br>';

    methodd += '<div class="col-md-12">';
    methodd += '<div class="col-md-3">';
    methodd += '<label>Supplier Name</label>';
    methodd += '</div>'; 
    methodd += '<div class="col-md-9">';
    methodd += '<input class="form-control" type="text" name="reminder_supplier_name" id="reminder_supplier_name" readonly value="'+Supplier+'"/>';
    methodd += '</div>';        
    methodd += '</div>';
    methodd += '<br><br>'; 

    methodd += '<div class="col-md-12">';
    methodd += '<div class="col-md-3">';
    methodd += '<label>Reg No</label>';
    methodd += '</div>'; 
    methodd += '<div class="col-md-9">';
    methodd += '<input class="form-control" type="text" name="reminder_reg_no" id="reminder_reg_no" readonly value="'+Reg_NO+'"/>';
    methodd += '</div>';        
    methodd += '</div>';
    methodd += '<br><br>'; 

    methodd += '<div class="col-md-12">';
    methodd += '<div class="col-md-3">';
    methodd += '<label>Overdue Registration Fee</label>';
    methodd += '</div>'; 
    methodd += '<div class="col-md-9">';
    methodd += '<input class="form-control" type="text" name="reminder_overdue_registration_fees" id="reminder_overdue_registration_fees" readonly value="'+Overdue_Registration_Fees+'"/>';
    methodd += '</div>';        
    methodd += '</div>';
    methodd += '<br><br>'; 

    methodd += '<div class="col-md-12">';
    methodd += '<div class="col-md-3">';
    methodd += '<label>Overdue Subscription</label>';
    methodd += '</div>'; 
    methodd += '<div class="col-md-9">';
    methodd += '<input class="form-control" type="text" name="reminder_subscription_fees" id="reminder_subscription_fees" readonly value="'+Overdue_Subscriptions+'"/>';
    methodd += '</div>';        
    methodd += '</div>';
    methodd += '<br><br>'; 

    methodd += '<div class="col-md-12">';
    methodd += '<div class="col-md-3">';
    methodd += '<label>Total Overdue</label>';
    methodd += '</div>'; 
    methodd += '<div class="col-md-9">';
    methodd += '<input class="form-control" type="text" name="reminder_total_overdue" id="reminder_total_overdue" readonly value="'+Total_Overdue+'"/>';
    methodd += '</div>';        
    methodd += '</div>';
    methodd += '<br><br>'; 

    methodd += '<div class="col-md-12">';
    methodd += '<div class="col-md-3">';
    methodd += '<label>Registration Date</label>';
    methodd += '</div>'; 
    methodd += '<div class="col-md-9">';
    methodd += '<input class="form-control" type="text" name="reminder_reg_date" id="reminder_reg_date" readonly value="'+Registration_date+'"/>';
    methodd += '</div>';        
    methodd += '</div>';
    methodd += '<br><br>'; 

    methodd += '<div class="col-md-12">';
    methodd += '<div class="col-md-3">';
    methodd += '<label>Type</label>';
    methodd += '</div>'; 
    methodd += '<div class="col-md-9">';
    methodd += type_list_dropdown;
    methodd += '</div>';        
    methodd += '</div>';
    methodd += '<br><br>';     


    methodd += '</div>';
    methodd += '<input style="display:none;" id="save_remittance_save_button" type="submit">';
    methodd += '</form>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input name="savesumbit" type="button" class="btn btn-success" id="click_save_remittance" value="Save"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span></p>';


    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);    
    $('#reminder_reg_date').datepicker({
            forceParse: false,
            autoclose: true,
            format: 'yyyy-mm-dd'
    });    
    $('#reminder_type').val(type);
    $('.select').select2();
});

$(document).on('click', '#click_save_remittance', function(e) {
  // alert('save');
  var check_file_payment_voucher = $('#upload_remittance_payment_voucher').val();
  if(check_file_payment_voucher != null && check_file_payment_voucher != '')
  {
    $('#remittance_payment_voucher_no').attr('required',true);
  }
  else
  {
    $('#remittance_payment_voucher_no').attr('required',false);
  }
  // alert(check_file_payment_voucher);
  // return;
  $('#save_remittance_save_button').click();
});


$(document).on('click', '#delete_reminder', function(e) {

    var supplier_guid = $(this).attr('e_supplier_guid');
    var customer_guid = $(this).attr('e_customer_guid');
    var type = $(this).attr('e_type');
    var table_type = $(this).attr('e_table_type');

    $('.modal').attr('data-backdrop','static')
    $('.modal').attr('data-keyboard','false')
    var xform_url = "<?php echo site_url('/Query_outstanding/reminder_delete');?>"; 
    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Delete Reminder');

    methodd = '';

    // methodd +='<div id="myDropZone" class="dropzone" style="height:100px;"><label class="" id="output" for="upload_file">Upload Remittance(**If have)</label></div>';

    methodd += '<form id="excel_file_form" method="POST" action="'+xform_url+'">';
    methodd += '<div class="row" id="append_rimittance_row">';

    methodd += '<input class="form-control" type="hidden" name="reminder_customer_guid" id="reminder_customer_guid" readonly value="'+customer_guid+'"/>';
    methodd += '<input class="form-control" type="hidden" name="reminder_supplier_guid" id="reminder_supplier_guid" readonly value="'+supplier_guid+'"/>';
    methodd += '<input class="form-control" type="hidden" name="reminder_table_type" id="reminder_table_type" readonly value="'+table_type+'"/>';
    methodd += '<input class="form-control" type="hidden" name="reminder_type" id="reminder_type" readonly value="'+type+'"/>';        

    methodd += '<div class="col-md-12">';
    methodd += '<div class="col-md-12">';
    methodd += '<label>Confirm to delete Reminder?</label>';
    methodd += '</div>'; 
    methodd += '<div class="col-md-9">';
    methodd += '</div>';        
    methodd += '</div>';
    methodd += '<br><br>';

    methodd += '</div>';
    methodd += '<input style="display:none;" id="delete_reminder_button" type="submit">';
    methodd += '</form>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input name="savesumbit" type="button" class="btn btn-danger" id="click_delete_reminder" value="Delete"><input name="sendsumbit" type="button" class="btn btn-primary" data-dismiss="modal" value="Cancel"></span></p>';


    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);       

});

$(document).on('click', '#click_delete_reminder', function(e) {
  // alert('delete');
  $('#delete_reminder_button').click();
});

tablelist = function(ticket_status_value='')
{ 

  if ( $.fn.DataTable.isDataTable('#reminder_table') ) {
    $('#list').DataTable().destroy();
  }
  var table_branch;
  // alert();
  // alert();

  table_branch = $('#reminder_table').DataTable({  
    // "order": [[2,'desc']], 
    // "columnDefs": [{ "visible": false, "targets": [1]  },{ "orderable": false, "targets": 1 }],
    "columnDefs": [{ "orderable": false, "targets": 19 }],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100, 9999999], [10, 25, 50, 100, 'ALL'] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [10 , 'desc'],[0 , 'asc'],[1 , 'asc']],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "60vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url" : "<?php echo site_url('Query_outstanding/reminder_table'); ?>",
        "type": "POST",
        // "data": {ticket_status_value:''},
        beforeSend:function(){
          // alert('beforeSend');
        },
        complete:function()
        { 
          // alert('complete');
        },
    },
    //'fixedHeader' : false,
    columns: [
              { data: "Retailer"},
              { data: "Supplier"},
              { data: "Reg_NO"},
              { data: "Overdue_Registration_Fees"},
              // { data: "ticket_status",render: function ( data, type, row ) {
              //   if (data == 'New') { word = '<b style="color:red; ">'+data+'</b>' } else { word = data }
              //   return word;
              // }},
              { data: "Overdue_Subscriptions", render: $.fn.dataTable.render.number( ',', '.', 2,)},
              { data: "Total_Overdue", render: $.fn.dataTable.render.number( ',', '.', 2,)},
              { data: "Last_Subscriptions_Invoice_Count"},
              { data: "Overdue_Invoice_Date_From"},
              { data: "Overdue_Invoice_Date_To"},
              { data: "Overdue_Invoice_Due_Date"},
              { data: "Last_Invoice_Date"},
              { data: "Last_Invoice_Amt", render: $.fn.dataTable.render.number( ',', '.', 2,)},
              { data: "Last_Due_Date"},  
              { data: "Registration_date"},
              { data: "created_at"},
              { data: "created_by"},
              { data: "updated_at"},
              { data: "updated_by"},
              { data: "type_name"},


              // { data: "inv_count"},
              { data: "action"},
             ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'Brtip',
        buttons: [
       { extend: 'copyHtml5',
         exportOptions: {columns: [  0,1,2,3,4,5,6,7,8,9,10,11,12,13,18 ]} /*, footer: true */},

       { extend: 'excelHtml5',
         exportOptions: {columns: [  0,1,2,3,4,5,6,7,8,9,10,11,12,13,18 ]} /*, footer: true */},

       { extend: 'csvHtml5',  
         exportOptions: {columns: [  0,1,2,3,4,5,6,7,8,9,10,11,12,13,18 ]} /*, footer: true*/ },

       { extend: 'print',
         exportOptions: {columns: [  0,1,2,3,4,5,6,7,8,9,10,11,12,13,18 ]} /*, footer: true*/ },
              ],
    "pagingType": "simple_numbers",
    "fnCreatedRow" : function( nRow, aData, iDataIndex) {
      $(nRow).closest('tr').find('td:eq(0)').css('white-space', 'nowrap');
      $(nRow).closest('tr').find('td:eq(4)').css('word-break', 'break-all');
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        // interval();
      },300);
      $('.btn').button('reset');
    }
  });//close datatable

  $('#list_filter').find('input').off('keyup.DT input.DT');
  $("div.remove_padding").css({"text-align":"left"});

  var searchDelay = null;
     
  $('#list_filter').find('input').on('keyup', function(e) {
      var search = $(this).val();
      if (e.keyCode == 13) {
          table_branch.search(search).draw();
          reset = 1;
      }//close keycode
  });//close keyup function

}//close recreate_child_table

tablelist();


tablelist2 = function(ticket_status_value='')
{ 

  if ( $.fn.DataTable.isDataTable('#reminder_one_off_table') ) {
    $('#list').DataTable().destroy();
  }
  var table_branch;
  // alert();
  // alert();

  table_branch = $('#reminder_one_off_table').DataTable({  
    // "order": [[2,'desc']], 
    // "columnDefs": [{ "visible": false, "targets": [1]  },{ "orderable": false, "targets": 1 }],
    "columnDefs": [{ "orderable": false, "targets": 8 }],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [7 , 'desc'],[0 , 'asc'],[1 , 'asc']],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "30vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url" : "<?php echo site_url('Query_outstanding/reminder_one_off_table'); ?>",
        "type": "POST",
        // "data": {ticket_status_value:''},
        beforeSend:function(){
          // alert('beforeSend');
        },
        complete:function()
        { 
          // alert('complete');
        },
    },
    //'fixedHeader' : false,
    columns: [
              { data: "Retailer"},
              { data: "Supplier"},
              { data: "Reg_NO"},
              { data: "Overdue_Registration_Fees"},
              // { data: "ticket_status",render: function ( data, type, row ) {
              //   if (data == 'New') { word = '<b style="color:red; ">'+data+'</b>' } else { word = data }
              //   return word;
              // }},
              { data: "Overdue_Subscriptions"},
              { data: "Total_Overdue"},
              { data: "Registration_date"},
              { data: "type_name"},
              // { data: "inv_count"},
              { data: "action"},
             ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
    "pagingType": "simple_numbers",
    "fnCreatedRow" : function( nRow, aData, iDataIndex) {
      $(nRow).closest('tr').find('td:eq(0)').css('white-space', 'nowrap');
      $(nRow).closest('tr').find('td:eq(4)').css('word-break', 'break-all');
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        // interval();
      },300);
      $('.btn').button('reset');
    }
  });//close datatable

  $('#list_filter').find('input').off('keyup.DT input.DT');
  $("div.remove_padding").css({"text-align":"left"});

  var searchDelay = null;
     
  $('#list_filter').find('input').on('keyup', function(e) {
      var search = $(this).val();
      if (e.keyCode == 13) {
          table_branch.search(search).draw();
          reset = 1;
      }//close keycode
  });//close keyup function

}//close recreate_child_table

tablelist2();

  $(document).on('click','#import_excel',function(){
    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Import File');

    methodd = '';

    //methodd +='<div id="myDropZone" class="dropzone" style="height:20px;"><center><label class="vertical-center" id="output" for="upload_file">Select a file to continue</label></center> </div>';

    methodd += '<div class="row">';
    methodd += '<form id="excel_file_form">';
    methodd += '<div class="col-md-12" style="padding-bottom:10px;"><label>Retailer <span class="text-danger">*</span></label>';
    methodd += '<select class="form-control" name="acc_name" id="acc_name" ><option value="">-Select Retailer-</option><?php foreach ($acc as $key) { ?> <option value="<?php echo $key->acc_guid ?>" retailer_name="<?php echo $key->acc_name?>"><?php echo $key->acc_name?></option> <?php } ?></select>';
    methodd += '</div>';
    methodd += '<div class="col-md-6">';
    methodd += '<label for="upload_file" class="btn btn-block btn-primary">Select File</label>';
    methodd += '</div>';
    methodd += '<div class="col-md-6" style="margin-bottom:10px;">';
    methodd += '<button type="button" class="btn btn-block btn-danger" id="reset_input">Reset</button>';
    methodd += '</div>';
    methodd += '<div class="col-md-6">';
    methodd += '<input type="file" name="photo" id="upload_file" accept=".csv" style="margin-right:50px;"/>';
    methodd += '</div>';
    methodd += '</form>';
    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);
  });//close import

  $(document).on('change','#upload_file',function(e){

    var fileName = e.target.files[0].name;
    var acc_name = $('#acc_name').val();

    if((fileName != '') && (acc_name != ''))
    { 
      $('#submit_button').remove();

      $('#excel_file_form').append('<div class="col-md-12" ><button type="button" id="submit_button" class="btn btn-block btn-success" style="margin-top:10px;">Submit</button></div>');

      $('#output').html(fileName);

    }
    else
    { 
      $('#output').html('No files selected');
      $('#submit_button').remove();
    }
  });//close upload file

  $(document).on('click','#reset_input',function(){

    $('#upload_file').val('');

    var file = $('#upload_file')[0].files[0];

    if(file === undefined)
    {
      $('#output').html('No files selected');
        $('#submit_button').remove();
    }
    else
    { 
      var fileName = file.name;

      $('#submit_button').remove();

        $('#excel_file_form').append('<button type="button" class="btn btn-block btn-success" id="submit_button" style="margin-top:10px;">Submit</button>');

        $('#output').html(fileName);
    }
  });//close reset_input

  $(document).on('click','#submit_button',function(){

    var acc_guid = $('#acc_name').val();

    var retailer = $('#acc_name').find('option:selected').attr('retailer_name');

    confirmation_modal('Are you sure want to Import Excel <br> Retailer Name : <b>'+retailer+'</b> ?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#upload_file')[0].files[0]);
      formData.append('acc_guid', acc_guid);

      $.ajax({
          url:"<?= site_url('Query_outstanding/file_upload');?>",
          method:"POST",
          data: formData,
          processData: false, // Don't process the files
          contentType: false, // Set content type to false as jQuery will tell the server its a query string request
          beforeSend : function()
          { 
            $('.btn').button('loading');
          },
          complete : function()
          { 
            $('.btn').button('reset');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            $('#alertmodal').modal('hide');
            if (json.para1 == '1') {
              alert(json.msg);
              //alert(json.msg.replace(/\\n/g,"\n"));
              $('.btn').button('reset');
              $('#upload_file').val('');
              $('#output').html('No files selected');
              $('#submit_button').remove();

            }else{

              $('#medium-modal').modal('hide');
              $('#alertmodal').modal('hide');
              alert(json.msg);
              location.reload();
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });//close submit_buttona

  })
</script>
