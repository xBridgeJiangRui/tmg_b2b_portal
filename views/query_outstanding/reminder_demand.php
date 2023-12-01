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
<span class="pill_button">Sync Status: 
  <?php if($sync_status == 'Completed')
    {
      ?>
      <span style="color:#52eb34"><?php echo $sync_status; ?>
      <?php
    }
    else
    {
      ?>
      <span class="blinker" style="color:red"><?php echo $sync_status; ?>
      <?php
    } ?>
    </span>
  </span>
  <span class="pill_button"> Last Sync On: 
  <?php if($sync_status == 'Completed')
    {
      ?>
      <span style="color:#52eb34"><?php echo $latest_sync_on; ?></span>
      <?php
    }
    else
    {
      ?>
      <span class="blinker" style="color:red"><?php echo $latest_sync_on; ?></span>
      <?php
    } ?>
    </span>
  </span>
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Reminder Demand Lists</h3><br>
          <div class="box-tools pull-right">
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>
            <button id="sync_btn" type="button" class="btn btn-xs btn-warning"><i class="fa fa-refresh" aria-hidden="true" ></i> Sync & Send</button>
            <button id="process_btn" type="button" class="btn btn-xs btn-primary"><i class="fa fa-edit" aria-hidden="true" ></i> Change Status</button>
            <?php
            }
            ?>
          </div>
        </div>
          <div class="box-body">
            <table class="table table-bordered table-hover" id="demand_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;">
                <tr>
                  <th><input type="checkbox" class="form-checkbox" id="checkall_input_table" table_id="demand_table"/></th>
                  <th>Action</th>
                  <th>Batch No</th> 
                  <th>Retailer Name</th> 
                  <th>Supplier Name</th>
                  <th>Email Address</th>
                  <th>Amount</th> 
                  <th>Letter Date</th>
                  <th>First Send At</th>
                  <th>Second Send At</th>
                  <th>Next Send Scheduler</th>
                  <th>Status</th>
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
  $('#demand_table').DataTable({
    "columnDefs": [
    {"targets": [0,1] ,"orderable": false},
    ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : true,
    'ordering'    : true, 
    'order'       : [  [2 , 'desc'] ],
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
        "url": "<?php echo site_url('Reminder_letter/demand_tb');?>",
        "type": "POST",
    },
    columns: [
            { "data" : "empty" ,render:function( data, type, row ){

              var element = '';

              if(row['status'] == '0')
              {
                element += '<input type="checkbox" id="checkbox_id" class="form-checkbox" guid="'+row['guid']+'" mail_type="'+row['mail_type']+'"/>';
              }

              if(row['status'] == '1')
              {
                if(row['cur_date'] >= row['next_send_date'] )
                {
                  element += '<input type="checkbox" id="checkbox_id" class="form-checkbox" guid="'+row['guid']+'" mail_type="'+row['mail_type']+'"/>';
                }
              }
                      
              return element;
              }},
            { "data" : "guid" ,render:function( data, type, row ){

              var element = '';

              <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
              {
                ?>
                  
                element += '<button id="edit_data_btn" type="button" style="margin-left:2px;margin-top:2px;" title="EDIT" class="btn btn-xs btn-info" guid="'+row['guid']+'" acc_name="'+row['acc_name']+'" supplier_name="'+row['supplier_name']+'" supplier_name="'+row['supplier_name']+'" email="'+row['email']+'" address="'+row['address']+'" amount="'+row['amount']+'" word_amount="'+row['word_amount']+'" created_at="'+row['created_at']+'" ><i class="fa fa-edit"></i></button>';

                element += '<button id="view_pdf" type="button" style="margin-left:2px;margin-top:2px;" title="PDF" class="btn btn-xs btn-success" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" mail_type="'+row['mail_type']+'" ><i class="fa fa-file"></i></button>';

                if(row['status'] == '0')
                {
                  element += '<button id="send_letter" type="button" style="margin-left:2px;margin-top:2px;" title="SEND" class="btn btn-xs btn-warning" guid="'+row['guid']+'" status="'+row['status']+'" ><i class="fa fa-send"></i></button>';
                }

                if(row['status'] == '1')
                {
                  if(row['cur_date'] >= row['next_send_date'] )
                  {
                    element += '<button id="send_letter" type="button" style="margin-left:2px;margin-top:2px;" title="SEND" class="btn btn-xs btn-warning" guid="'+row['guid']+'" status="'+row['status']+'" ><i class="fa fa-send"></i></button>';
                  }
                }

                <?php
              }
              ?>

              return element;
            }},
            { "data" : "batch_no" },
            { "data" : "acc_name" },
            { "data" : "supplier_name" },
            { "data" : "email" },
            { "data" : "amount" },
            { "data" : "letter_date" },
            { "data" : "first_send" },
            { "data" : "second_send" },
            { "data" : "next_send_date" ,render:function( data, type, row ){
              var element = '';

              if(row['status'] != '2')
              {
                element = data
              }
              else
              {
                element = '';
              }
                      
              return element;
            }},
            { "data" : "status_naming" },
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

  $(document).on('click','#edit_data_btn',function(){
    var guid = $(this).attr('guid');
    var acc_name = $(this).attr('acc_name');
    var supplier_name = $(this).attr('supplier_name');
    var email = $(this).attr('email');
    var address = $(this).attr('address');
    var amount = $(this).attr('amount');
    var word_amount = $(this).attr('word_amount');
    var created_at = $(this).attr('created_at');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Edit Demand Letter');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control " id="guid" name="guid" value="'+guid+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><label>Retailer Name</label> <input type="text" class="form-control " id="acc_name" name="acc_name" autocomplete="off" value="'+acc_name+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label> <input type="text" class="form-control " id="supplier_name" name="supplier_name" autocomplete="off" value="'+supplier_name+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><label>Address</label><textarea class="form-control " id="address" rows="5" cols="50">'+address+'</textarea></div>';

    methodd += '<div class="col-md-12"><label>Email</label> <input type="text" class="form-control " id="email" name="email" autocomplete="off" value="'+email+'"/> </div>';

    methodd += '<div class="col-md-12"><label>Amount</label> <input type="text" class="form-control " id="amount" name="amount" autocomplete="off" value="'+amount+'"/> </div>';

    methodd += '<div class="col-md-12"><label>Word Amount</label> <input type="text" class="form-control " id="word_amount" name="word_amount" autocomplete="off" value="'+word_amount+'"/> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="update_button" class="btn btn-primary"> Update </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      //$('#edit_day').val(day_limit);
      //$('.select2').select2();
    },300);

  });//close modal edit

  $(document).on('click','#update_button',function(){
    var guid = $('#guid').val();
    var address = $('#address').val();
    var email = $('#email').val();
    var amount = $('#amount').val();
    var word_amount = $('#word_amount').val();

    if((guid == '') || (guid == null) || (guid == 'null'))
    {
      alert('Invalid GUID.');
      return;
    }

    if((address == '') || (address == null) || (address == 'null'))
    {
      alert('Invalid Address.');
      return;
    }

    if((email == '') || (email == null) || (email == 'null'))
    {
      alert('Invalid Email Address.');
      return;
    }

    if((amount == '') || (amount == null) || (amount == 'null'))
    {
      alert('Invalid Amount.');
      return;
    }

    if((word_amount == '') || (word_amount == null) || (word_amount == 'null'))
    {
      alert('Invalid Word Amount.');
      return;
    }

    confirmation_modal('Are you sure to proceed Update?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Reminder_letter/update_letter') ?>",
        method:"POST",
        data:{guid:guid,address:address,email:email,amount:amount,word_amount:word_amount},
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

  $(document).on('click','#view_pdf',function(){
    var customer_guid = $(this).attr('customer_guid');
    var supplier_guid = $(this).attr('supplier_guid');
    var mail_type = $(this).attr('mail_type');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Preview Demand Letter');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<embed src="<?php echo site_url('Invoice/demand_letter_report?customer_guid=');?>'+customer_guid+'&supplier_guid='+supplier_guid+'&type='+mail_type+'" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';
    
    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> </span> </p>';

    modal.find('.modal-body').html(methodd);
    modal.find('.modal-footer').html(methodd_footer);
    // setTimeout(function () { 
    //     modal.find('.modal-footer').html(methodd_footer);
    // }, 1500);
  });

  $(document).on('click','#send_letter',function(){
    var guid = $(this).attr('guid');
    //var status = $(this).attr('status');

    confirmation_modal('Are you sure want to SEND?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Reminder_letter/update_send_letter') ?>",
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
  });

  $(document).on('change','#checkall_input_table',function(){

    var id = $(this).attr('table_id');

    var table = $('#'+id).DataTable();

    if($(this).is(':checked'))
    {
      table.rows().nodes().to$().each(function(){

        $(this).find('td').find('input[type="checkbox"]').prop('checked',true)

      });//close small loop
    }
    else
    {
      table.rows().nodes().to$().each(function(){

        $(this).find('td').find('input[type="checkbox"]').prop('checked',false)

      });//close small loop
    }//close else
  });//close checkbox all set_group_table

  $(document).on('click','#process_btn',function(){
    var table = $('#demand_table').DataTable();
    var details = [];

    table.rows().nodes().to$().each(function(){
      if($(this).find('td').find('#checkbox_id').is(':checked'))
      {
        guid = $(this).find('td').find('#checkbox_id').attr('guid');

        //mail_type = $(this).find('td').find('#checkbox_id').attr('mail_type'); ,'mail_type':mail_type
        
        details.push({'guid':guid});
      }
    });//close small loop

    //console.log(details); die;
    
    if(details == '' || details == 'null' || details == null)
    {
      alert('Please Select Checkbox.');
      return;
    }
    confirmation_modal('Are you sure want to send demand letter?');
    
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      
      $.ajax({
        url:"<?php echo site_url('Reminder_letter/update_send_letter_by_batch') ?>",
        method:"POST",
        data:{details:details},
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
            $("#medium-modal").modal('hide');
            alert(json.msg);
            location.reload();
          }//close else
        }//close success
      });//close ajax 
    });//close document yes click
  });

  $(document).on('click','#sync_btn',function(){
    
    confirmation_modal('Are you sure want to resync and send demand letter?');
    
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      
      $.ajax({
        url:"<?php echo site_url('Reminder_letter/update_demand_config') ?>",
        method:"POST",
        //data:{details:details},
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
            $("#medium-modal").modal('hide');
            alert(json.msg);
            location.reload();
          }//close else
        }//close success
      });//close ajax 
    });//close document yes click
  });// close

});
</script>

