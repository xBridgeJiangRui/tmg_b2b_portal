<style>
.content-wrapper{
  min-height: 850px !important; 
}

.alignright {
  text-align: right;
}

.alignleft
{
  text-align: left;
}
</style>
<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Reminder Duration Settings</h3><br>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>

            <button id="create_duration" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Create</button>

            <?php
            }
            ?>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
          </div>
        </div>
          <div class="box-body">
            <table class="table table-bordered table-hover" id="reminder_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;">
                <tr>
                  <th>Action</th>
                  <th>Retailer Name</th> 
                  <th>Supplier Name</th>
                  <th>Duration Day(s)</th>
                  <th>Created At</th>
                  <th>Created By</th>
                  <th>Updated At</th>
                  <th>Updated By</th>
                </tr>
              </thead>
              <tbody> 
              </tbody>

            </table>
          </div>
      </div>
    </div>
  </div>

</div>
</div>

<script>
$(document).ready(function () {    
  $('#reminder_table').DataTable({
    "columnDefs": [
    {"targets": 0 ,"orderable": false},
    ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : true,
    'ordering'    : true, 
    'order'       : [  [1 , 'asc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "fixedColumns": true,
    "sScrollY": "50vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Query_outstanding/duration_tb');?>",
        "type": "POST",
    },
    columns: [
            { "data" : "guid" ,render:function( data, type, row ){

              var element = '';

              <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
              {
                ?>
                  
                element += '<button id="edit_duration" type="button"  title="EDIT" class="btn btn-sm btn-info" guid="'+row['guid']+'" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" day_limit="'+row['day_limit']+'" ><i class="fa fa-edit"></i></button>';

                element += '<button id="delete_button" type="button" style="margin-left:5px;" title="DELETE" class="btn btn-sm btn-danger" guid="'+row['guid']+'" ><i class="fa fa-trash"></i></button>';

                <?php
              }
              ?>

              return element;
            }},
            { "data" : "acc_name" },
            { "data" : "supplier_name" },
            { "data" : "day_limit" },
            { "data" : "created_at" },
            { "data" : "created_by" },
            { "data" : "updated_at" },
            { "data" : "updated_by" },
          ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
    // buttons: [
    //   { extend: 'excelHtml5',
    //     exportOptions: {columns: [1,2,3,4,5,6]}},

    //   { extend: 'csvHtml5',  
    //     exportOptions: {columns: [1,2,3,4,5,6]}},
    //     ],
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('final_amount', aData['final_amount']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $(document).on('click','#create_duration',function(){

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Create New Setting');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><label>Retailer Name</label> <select class="form-control select2 get_code" name="new_retailer" id="new_retailer"> <option value="" disabled selected>-Select Retailer Name-</option>  <?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label> <select class="form-control select2 get_code" name="new_supplier" id="new_supplier"> <option value="" disabled selected>-Select Supplier Name-</option>  <?php foreach($get_supplier as $row) { ?> <option value="<?php echo $row->supplier_guid?>"><?php echo addslashes($row->supplier_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Duration Day(s)</label> <input type="number" class="form-control input-sm" id="add_day" name="add_day" autocomplete="off"/> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="submit_button" class="btn btn-primary"> Create </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('.select2').select2();
    },300);

  });//close modal create

  $(document).on('click','#submit_button',function(){
    var new_retailer = $('#new_retailer').val();
    var new_supplier = $('#new_supplier').val();
    var add_day = $('#add_day').val();

    if((new_retailer == '') || (new_retailer == null) || (new_retailer == 'null'))
    {
      alert('Invalid Retailer Name.');
      return;
    }

    if((new_supplier == '') || (new_supplier == null) || (new_supplier == 'null'))
    {
      alert('Invalid Supplier Name.');
      return;
    }

    if((add_day == '') || (add_day == null) || (add_day == 'null'))
    {
      alert('Invalid Day Duration.');
      return;
    }

    confirmation_modal('Are you sure to proceed Create?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Query_outstanding/add_duration') ?>",
        method:"POST",
        data:{new_retailer:new_retailer,new_supplier:new_supplier,add_day:add_day},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            alert(json.msg);
            location.reload();
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation

  });//close submit process

  $(document).on('click','#edit_duration',function(){
    var guid = $(this).attr('guid');
    var customer_guid = $(this).attr('customer_guid');
    var supplier_guid = $(this).attr('supplier_guid');
    var day_limit = $(this).attr('day_limit');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Edit Duration Setting');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control input-sm" id="guid" name="guid" value="'+guid+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><label>Retailer Name</label> <select class="form-control select2 get_code" name="edit_retailer" id="edit_retailer"> <option value="" disabled selected>-Select Retailer Name-</option>  <?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label> <select class="form-control select2 get_code" name="edit_supplier" id="edit_supplier"> <option value="" disabled selected>-Select Supplier Name-</option>  <?php foreach($get_supplier as $row) { ?> <option value="<?php echo $row->supplier_guid?>"><?php echo addslashes($row->supplier_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Duration Day(s)</label> <input type="number" class="form-control input-sm" id="edit_day" name="edit_day" autocomplete="off"/> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="update_button" class="btn btn-primary"> Update </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('#edit_retailer').val(customer_guid);
      $('#edit_supplier').val(supplier_guid);
      $('#edit_day').val(day_limit);
      $('.select2').select2();
    },300);

  });//close modal edit

  $(document).on('click','#update_button',function(){
    var guid = $('#guid').val();
    var edit_retailer = $('#edit_retailer').val();
    var edit_supplier = $('#edit_supplier').val();
    var edit_day = $('#edit_day').val();

    if((edit_retailer == '') || (edit_retailer == null) || (edit_retailer == 'null'))
    {
      alert('Invalid Retailer Name.');
      return;
    }

    if((edit_supplier == '') || (edit_supplier == null) || (edit_supplier == 'null'))
    {
      alert('Invalid Supplier Name.');
      return;
    }

    if((edit_day == '') || (edit_day == null) || (edit_day == 'null'))
    {
      alert('Invalid Day Duration.');
      return;
    }

    confirmation_modal('Are you sure to proceed Update?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Query_outstanding/update_duration') ?>",
        method:"POST",
        data:{guid:guid,edit_retailer:edit_retailer,edit_supplier:edit_supplier,edit_day:edit_day},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            alert(json.msg);
            location.reload();
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation
  });//close update process

  $(document).on('click','#delete_button',function(){
    var guid = $(this).attr('guid');

    if((guid == '') || (guid == null) || (guid == 'null'))
    {
      alert('Invalid GUID. Contact Handsome Developer');
      return;
    }

    confirmation_modal('Are you sure to proceed Delete?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Query_outstanding/delete_duration') ?>",
        method:"POST",
        data:{guid:guid},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            alert(json.msg);
            location.reload();
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation
  });//close update process

});
</script>

