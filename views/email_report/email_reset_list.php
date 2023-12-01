<style type="text/css">
.alignleft {
  text-align: left;
  white-space: nowrap;
}

</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>
    <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Email Reset List</h3>
          <div class="box-tools pull-right">
            <button id="create_email_list" class="btn btn-xs btn-default">
              <i class="glyphicon glyphicon-plus"></i> Create
            </button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
            <table id="email_reset_list_tb" class="table table-hover" >
              <thead style="white-space: nowrap;">
              <tr>
                <th>Action</th>
                <th>Customer Name</th>
                <th>Email Address</th>
                <th>Reset Status</th>
                <th>Reset At</th>
                <th>Viewed At</th>
                <th>IP Address</th>
                <th>Browser</th>
                <th>Delete Status</th>
                <th>Updated At</th>
                <th>Updated By</th>
                <th>Created At</th>
                <th>Created By</th>
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
<script src="<?php echo base_url('assets/plugins/timepicker/bootstrap-timepicker.min.js')?>"></script>
<script>
$(document).ready(function() {
  $('#email_reset_list_tb').DataTable({
    "columnDefs": [{"targets": 0 ,"orderable": false},{ className: "alignleft", targets: [0,1] }],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [9 , 'DESC'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "fixedColumns": true,
    // "sScrollY": "80vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Email_report/email_reset_list_tb');?>",
        "type": "POST",
    },
    columns: [

             { "data": "reset_guid" ,render:function( data, type, row ){

                var element = '';
                if(row['deleted'] != 1)
                {
                  element += '<button id="view_action_btn"  title="EDIT" class="btn btn-sm btn-info" reset_guid="'+row['reset_guid']+'" is_reset="'+row['is_reset']+'" created_at="'+row['created_at']+'"><i class="fa fa-edit"></i></button>';

                  if(row['is_reset'] == '0')
                  {
                    element += '<button id="resend_btn" style="margin-left:5px;" title="Send"  title="RESEND" class="btn btn-sm btn-warning" reset_guid="'+row['reset_guid']+'" user_guid="'+row['user_guid']+'" customer_guid="'+row['customer_guid']+'" email_id="'+row['email_id']+'" ><i class="fa fa-send"></i></button>';

                  }

                  element += '<button id="delete_email_reset" type="button" style="margin-left:5px;" title="DELETE" class="btn btn-sm btn-danger" reset_guid="'+row['reset_guid']+'" ><i class="fa fa-trash"></i></button>  ';
                }
                
                return element;
       
              }},
              { "data" : "acc_name" },
              { "data" : "email_id" },
              { "data" : "is_reset" ,render:function( data, type, row ){

                var element = '';

                if(data == 1 )
                {
                  element += 'Reset';
                }
                else
                {
                  element += ''
                }

                return element;
       
              }},
              { "data" : "reset_at" ,render:function( data, type, row ){

                var element = '';

                if((data == '1001-01-01 00:00:00') || (data == 'null' ) || (data == null ))
                {
                  element += '';
                }
                else
                {
                  element += data;
                }

                return element;
       
              }},
              { "data" : "viewed_at" },
              { "data" : "ip" },
              { "data" : "browser" },
              { "data" : "deleted" ,render:function( data, type, row ){

                var element = '';

                if((data == '0') || (data == 'null' ) || (data == null ))
                {
                  element += '';
                }
                else
                {
                  element += 'Deleted';
                }

                return element;
       
              }},
              { "data" : "updated_at" },
              { "data" : "updated_by" },
              { "data" : "created_at" },
              { "data" : "created_by" },
              
             ],
    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      $(nRow).attr('reset_guid', aData['reset_guid']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $(document).on('click','#view_action_btn',function(){

    var reset_guid = $(this).attr('reset_guid');
    var is_reset = $(this).attr('is_reset');
    var created_at = $(this).attr('created_at');
    var date = created_at.substring(0, 11);
    var time = created_at.substring(11, 19);
    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Edit Reset Status');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="reset_guid" value="'+reset_guid+'"/></div>';

    methodd += '<div class="col-md-12"><label>Reset Status</label><select id="reset_val" name="reset_val" class="form-control" ><option value="1">Reset</option> <option value="0">No Reset</option></select></div>';

    methodd += '<div class="col-sm-6"> <label>Date</label> <div class="input-group"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input name="created_at" id="created_at" type="text" class="datepicker form-control input-sm" value="<?php echo date('Y-m-d');?>" autocomplete="off" > </div> </div>';

    methodd += '<div class="col-sm-6"> <label>Time</label> <input name="time_at" id="time_at" type="time" step="1" class="form-control input-sm" autocomplete="off" > </div> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="save_status" class="btn btn-success" value="Save"> <input name="sendsubmit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('#reset_val').val(is_reset);
      $('#created_at').val(date);
      $('#time_at').val(time);
      $('.datepicker').datepicker({
        forceParse: false,
        autoclose: true,
        format: 'yyyy-mm-dd'
      });
    },300);

  });

  $(document).on('click','#save_status',function(){

    var reset_val = $('#reset_val').val();
    var reset_guid = $('#reset_guid').val();
    var created_at = $('#created_at').val();
    var time_at = $('#time_at').val();

    if((reset_val == '') || (reset_val == null))
    {
      alert("Please select reset status.")
      return;
    }

    if((created_at == '') || (created_at == null))
    {
      alert("Please select Date.")
      return;
    }

    if((time_at == '') || (time_at == null))
    {
      alert("Please select Time.")
      return;
    }


    $.ajax({
          url:"<?php echo site_url('Email_report/save_status_reset');?>",
          method:"POST",
          data:{reset_val:reset_val,reset_guid:reset_guid,created_at:created_at,time_at:time_at},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            }else{
              alert(json.msg);
              $('.btn').button('reset');
              location.reload();
            }//close else
          }//close success
        });//close ajax
  });//close add 

  $(document).on('click','#create_email_list',function(){

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Create Email Reset');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-12"><label>Customer</label><select class="form-control get_cust_value" id="acc_name" name="acc_name"> <option value="">-Select-</option> <?php foreach ($customer as $key) { ?> <option value="<?php echo $key->acc_guid ?>"> <?php echo addslashes($key->acc_name)?> </option> <?php } ?> </select></div>';

    methodd += '<div class="col-md-12"><label>Email</label><select class="select2 form-control" id="email_data" name="email_data"> <option value="" disabled>-Please select the Customer-</option> </select></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="create_email_btn" class="btn btn-success" value="Save"> <input name="sendsubmit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){

      $('.get_cust_value').change(function(){

       var type_val = $('.get_cust_value').val();

       if(type_val != '')
       {
          $.ajax({
          url : "<?php echo site_url('Email_report/fetch_email'); ?>",
          method:"POST",
          data:{type_val:type_val},
          success:function(result)
          {

           json = JSON.parse(result); 

              code = '';

              Object.keys(json['Code']).forEach(function(key) {

                code += '<option value="'+json['Code'][key]['user_id']+'">'+json['Code'][key]['user_id']+' </option>';

              });
           $('#email_data').select2().html(code);
          }
         });
       }
       else
       {
          $('#email_data').select2().html('<option value="" disabled>Please select the supplier</option>');
       }
          
      });//close selection
      
    },300);

  });

  $(document).on('click','#create_email_btn',function(){

    var acc_name = $('#acc_name').val();
    var email_data = $('#email_data').val();

    if((acc_name == '') || (acc_name == null))
    {
      alert("Please select Customer.")
      return;
    }

    if((email_data == '') || (email_data == null))
    {
      alert("Please select Email.")
      return;
    }

    $.ajax({
          url:"<?php echo site_url('Email_report/create_email_reset');?>",
          method:"POST",
          data:{acc_name:acc_name,email_data:email_data},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            }else{
              alert(json.msg);
              $('.btn').button('reset');
              location.reload();
            }//close else
          }//close success
        });//close ajax
  });//close add   

  $(document).on('click','#resend_btn',function(){

    var reset_guid = $(this).attr('reset_guid');
    var user_guid = $(this).attr('user_guid');
    var customer_guid = $(this).attr('customer_guid');
    var email_id = $(this).attr('email_id');

    $.ajax({
          url:"<?php echo site_url('Email_report/resend_link');?>",
          method:"POST",
          data:{reset_guid:reset_guid,user_guid:user_guid,customer_guid:customer_guid,email_id:email_id},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            }else{
              alert(json.msg);
              $('.btn').button('reset');
              location.reload();
            }//close else
          }//close success
        });//close ajax
  });//close add   

  $(document).on('click','#delete_email_reset',function(){

  var reset_guid = $(this).attr('reset_guid');

  confirmation_modal("<?php echo 'Are you sure want to delete this?'; ?>");

  $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

    $.ajax({
        url:"<?php echo site_url('Email_report/delete_email_reset_list');?>",
        method:"POST",
        data:{reset_guid:reset_guid},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {

          json = JSON.parse(data);

          if (json.para1 == '1') {
            alert(json.msg);
            $('.btn').button('reset');
            $("#alertmodal").modal('hide');
          }else{

            $("#alertmodal").modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
            setTimeout(function() {
              location.reload();
            }, 300);

          }//close else

        }//close success
      });//close ajax

  });//close document yes

  });//close delete reset 

});
</script>
