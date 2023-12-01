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

.cell_breakWord{
  word-break: break-all;
  max-width: 1px;
}

.loader_submit {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url("<?php echo base_url('assets/loading2.gif') ?>") center no-repeat #fff;
    /*background:   #fff;*/
}

.blinker {
  animation: blink-animation 5s steps(10, start) infinite;
  -webkit-animation: blink-animation 2s steps(10, start) infinite;
}

@keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
@-webkit-keyframes blink-animation {
  to {
    visibility: hidden;
  }
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
  <span class="pill_button"> Latest Sync On: 
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
<div class="loader_submit"></div>
  <div class="row">
    <div class="col-md-12">
      
    </div>
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B Reminder</h3> <br>
          <div class="box-tools pull-right">

           <?php
            if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>
              <a href="<?= site_url('Query_outstanding/reminder_duration');?>" target="_blank"><button id="btn" type="button" class="btn btn-xs btn-info"><i class="fa fa-gears" aria-hidden="true" ></i> Duration Settings</button></a>
              <button id="settings_data" type="button" class="btn btn-xs btn-danger"><i class="fa fa-gears" aria-hidden="true" ></i> Reminder Settings</button>
              <button id="resync_data" type="button" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-refresh" aria-hidden="true" ></i> Re-Sync Data</button>
            <?php
            }
            ?>
            <!-- <button id="import_excel" type="button" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Import Excel</button> -->
          </div>
        </div>
          <div class="box-body">
              
            <table class="table table-bordered table-striped dataTable" id="reminder_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;word-break: break-word !important">
                <tr>
                  <th>Code</th>
                  <th>Debtor Code</th>
                  <th>Supplier Name</th>
                  <th>Reg No</th>
                  
                  <th>Registration Invoice Date</th>
                  <th>Overdue Registration Fee</th> 
                  <th>Overdue Subscription</th>
                  <th>Total Overdue</th>

                  <th>Overdue Invoices Count</th>
                  <th>Overdue Invoice Date From</th>
                  <th>Overdue Invoice Date To</th>
                  <th>Overdue Invoice Due Date</th>

                  <th>Last Subscriptions Invoice Count</th>
                  <th>Last Invoice Date</th> 
                  <th>Last Invoice Due Date</th>
                  <th>Last Invoice Amt</th>

                  <th>Created At</th>
                  <th>Created By</th>
                  <th>Updated At</th>
                  <th>Updated By</th>
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
  </div>

    <div class="row">
    <div class="col-md-12">
    </div>
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B Reminder By Retailer</h3><br>
          <div class="box-tools pull-right">
            <!-- <button id="import_excel" type="button" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Import Excel</button> -->
          </div>
        </div>
          <div class="box-body">
              
            <table class="table table-bordered table-striped dataTable" id="reminder_retailer_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;word-break: break-word !important">
                <tr>
                  <th>Debtor Code</th>
                  <th>Customer Name</th>
                  <th>Supplier Name</th>
                  <th>Reg No</th>
                  
                  <th>Registration Invoice Date</th>
                  <th>One Off Invoice Date</th>
                  <th>Registration & Add ON Amt</th>
                  <th>Subscription One OFF Amt</th>
                  <th>Training Amt</th>
                  <th>AdHoc Service Amt</th>
                  <th>Overdue Registration Fee</th> 
                  <th>Overdue Subscription</th>
                  <th>Total Overdue</th>

                  <th>Overdue Invoices Count</th>
                  <th>Overdue Invoice Date From</th>
                  <th>Overdue Invoice Date To</th>
                  <th>Overdue Invoice Due Date</th>

                  <th>Last Subscriptions Invoice Count</th>
                  <th>Last Invoice Date</th> 
                  <th>Last Invoice Due Date</th>
                  <th>Last Invoice Amt</th>

                  <th>Created At</th>
                  <th>Created By</th>
                  <th>Updated At</th>
                  <th>Updated By</th>
                  <th>Invoice Number</th>
                  <th>Extended</th>
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
  </div>

</div>
</div>

<script>
$(document).ready(function () {    
$(".loader_submit").hide(); 
  $('#reminder_table').DataTable({
    "columnDefs": [{ "orderable": false, "targets": 21 },
    { className: "alignright", targets: [5,6,7,15] },
    { className: "alignleft", targets: '_all' },
    { visible: false, targets: [20,21]}],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100, 9999999], [10, 25, 50, 100, 'ALL'] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [13 , 'desc'],[2 , 'asc']],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "50vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Query_outstanding/b2b_reminder_tb');?>",
        "type": "POST",
    },
    columns: [
      { data: "Code"},
      { data: "DebtorCode"},
      { data: "supplier_name"},
      { data: "reg_no"},
      { data: "Registration_Invoice_Date", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Overdue_Registration_Fees", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //5
      { data: "Overdue_Subscriptions_Invoice_Amt", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //6
      { data: "Total_Overdue", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //7
      { data: "Overdue_Invoices_Count"},
      { data: "Overdue_Invoice_Date_From", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Overdue_Invoice_Date_To", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Overdue_Invoice_Due_Date", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Last_Subscriptions_Invoice_Count"},  
      { data: "Last_Invoice_Date", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Last_Due_Date", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Last_Invoice_Amt", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //15
      { data: "created_at"},
      { data: "created_by"},
      { data: "updated_at", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00 00:00:00') || (data == 'null') || (data == null))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "updated_by"},
      { data: "reminder_type"},
      { data: "action", render: function(data, type, row){ 
        var element = '';

        <?php
        if(in_array('IAVA',$this->session->userdata('module_code')))
        {
        ?>
          element += '<button id="edit_reminder_supplier" type="button"  title="EDIT" class="btn btn-xs btn-info edit_reminder" supplier_guid="'+row['supplier_guid']+'" supplier_name="'+row['supplier_name']+'" reg_no="'+row['reg_no']+'" DebtorCode="'+row['DebtorCode']+'" Overdue_Registration_Fees="'+row['Overdue_Registration_Fees']+'" Overdue_Subscriptions_Invoice_Amt="'+row['Overdue_Subscriptions_Invoice_Amt']+'" Total_Overdue="'+row['Total_Overdue']+'" Registration_Invoice_Date="'+row['Registration_Invoice_Date']+'" Last_Invoice_Amt="'+row['Last_Invoice_Amt']+'" Variance="'+row['Variance']+'" table_name="query_outstanding_new"><i class="fa fa-edit"></i></button>';

          element += '<button id="delete_reminder" type="button" style="margin-left:5px;" title="REMOVE" class="btn btn-xs btn-danger"  supplier_guid="'+row['supplier_guid']+'" supplier_name="'+row['supplier_name']+'" DebtorCode="'+row['DebtorCode']+'"><i class="fa fa-trash"></i></button>';

        <?php
        }
        ?>

        return element;
      }},
    ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'Brtip',
        buttons: [

       { extend: 'excelHtml5',
         exportOptions: {columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15 ]} /*, footer: true */},

       { extend: 'csvHtml5',  
         exportOptions: {columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15 ]} /*, footer: true*/ },
              ],
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      $(nRow).closest('tr').css({"cursor": "pointer"});
      $(nRow).attr('dockey', aData['dockey']);
      $(nRow).attr('DebtorCode', aData['DebtorCode']);
      //$(nRow).closest('tr').find('td:eq(0)').css('white-space', 'nowrap');
      //$(nRow).closest('tr').find('td:eq(4)').css('word-break', 'break-all');
    },
    "initComplete": function( settings, json ) {
      interval();
    }
  });//close datatable

  $(document).on('click', '.edit_reminder', function(e) {
    var supplier_guid = $(this).attr('supplier_guid');
    var DebtorCode = $(this).attr('DebtorCode');
    var supplier_name = $(this).attr('supplier_name');
    var reg_no = $(this).attr('reg_no');
    var Overdue_Registration_Fees = $(this).attr('Overdue_Registration_Fees');
    var Overdue_Subscriptions_Invoice_Amt = $(this).attr('Overdue_Subscriptions_Invoice_Amt');
    var Total_Overdue = $(this).attr('Total_Overdue');
    var Registration_Invoice_Date = $(this).attr('Registration_Invoice_Date');
    var Variance = $(this).attr('Variance');
    var Last_Invoice_Amt = $(this).attr('Last_Invoice_Amt');
    var table_name = $(this).attr('table_name');
    var customer_guid = $(this).attr('customer_guid');
    var type_list_dropdown = "<?php echo $type_list_dropdown;?>";

    if((Registration_Invoice_Date == '0000-00-00') || (Registration_Invoice_Date == '1001-01-01') || (Registration_Invoice_Date == null) || (Registration_Invoice_Date == 'null'))
    {
      disabled = 'disabled';
      datepicker = '';
      Registration_Invoice_Date = '';
    }
    else
    {
      disabled = '';
      datepicker = 'datepicker';
    }

    var modal = $("#large-modal").modal();

    modal.find('.modal-title').html('Edit Reminder');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<input type="hidden" class="form-control input-sm" id="supplier_guid" value="'+supplier_guid+'" readonly/>';

    methodd += '<input type="hidden" class="form-control input-sm" id="customer_guid" value="'+customer_guid+'" readonly/>';

    methodd += '<input type="hidden" class="form-control input-sm" id="table_name" value="'+table_name+'" readonly/>';

    methodd += '<input type="hidden" class="form-control input-sm" id="DebtorCode" value="'+DebtorCode+'" readonly/>';

    methodd += '<div class="col-md-6"><label>Supplier Name</label><input type="text" class="form-control input-sm" id="e_supplier_name" autocomplete="off" required="true" value="'+supplier_name+'" readonly/></div>';

    methodd += '<div class="col-md-6"><label>Reg No</label><input type="text" class="form-control input-sm" id="e_reg_no" autocomplete="off" required="true" value="'+reg_no+'" readonly/></div>';

    methodd += '<div class="col-md-6"><label>Overdue Registration Fee</label><input type="text" class="form-control input-sm" id="e_Overdue_Registration_Fees" autocomplete="off" required="true" value="'+Overdue_Registration_Fees+'" readonly/></div>';

    methodd += '<div class="col-md-6"><label>Overdue Subscription</label><input type="text" class="form-control " id="e_Overdue_Subscriptions_Invoice_Amt" autocomplete="off" required="true" value="'+Overdue_Subscriptions_Invoice_Amt+'" readonly/></div>';

    methodd += '<div class="col-md-6"><label>Total Overdue</label><input type="text" class="form-control " id="e_Total_Overdue" autocomplete="off" required="true" value="'+Total_Overdue+'" readonly/></div>';

    methodd += '<div class="col-md-6"><label>Last Invoice Amount</label><input type="text" class="form-control " id="e_Last_Invoice_Amt" autocomplete="off" required="true" value="'+Last_Invoice_Amt+'" readonly/></div>';

    methodd += '<div class="col-sm-6"> <label>Registration Date</label> <div class="input-group"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input name="e_Registration_Invoice_Date" id="e_Registration_Invoice_Date" type="text" class="form-control input-sm '+datepicker+'" '+disabled+' value="<?php echo date('Y-m-d');?>" autocomplete="off" readonly > </div> </div>';

    methodd += '<div class="col-md-12"><label>Reminder Type</label>'+type_list_dropdown+'</div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="edit_reminder_btn" class="btn btn-success" value="Edit"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('#e_Registration_Invoice_Date').val(Registration_Invoice_Date);
      $('#reminder_type').val(Variance);
      $('.select2').select2();
      // $('.datepicker').datepicker({
      //   forceParse: false,
      //   autoclose: true,
      //   format: 'yyyy-mm-dd'
      // });   
    },300);
  });

  $(document).on('click', '#edit_reminder_btn', function(e) {
    var supplier_guid = $('#supplier_guid').val();
    var DebtorCode = $('#DebtorCode').val();
    var Overdue_Registration_Fees = $('#e_Overdue_Registration_Fees').val();
    var Overdue_Subscriptions_Invoice_Amt = $('#e_Overdue_Subscriptions_Invoice_Amt').val();
    var Total_Overdue = $('#e_Total_Overdue').val();
    var Registration_Invoice_Date = $('#e_Registration_Invoice_Date').val();
    var Variance = $('#reminder_type').val();
    var Last_Invoice_Amt = $('#e_Last_Invoice_Amt').val();
    var table_name = $('#table_name').val();
    var sum_overdue = parseFloat(Overdue_Registration_Fees) + parseFloat(Overdue_Subscriptions_Invoice_Amt);

    if((table_name == '') || (table_name == null) || (table_name == 'null'))
    {
      alert("Invalid Process. Please Refresh the Page.");
      return;
    }

    if(table_name == 'query_outstanding_retailer')
    {
      var customer_guid = $('#customer_guid').val();

      if((customer_guid == '') || (customer_guid == null) || (customer_guid == 'null'))
      {
        alert("Invalid GUID. Please Refresh the Page.");
        return;
      }
    }

    if((supplier_guid == '') || (supplier_guid == null) || (supplier_guid == 'null'))
    {
      alert("Invalid GUID. Please Refresh the Page.");
      return;
    }

    if((DebtorCode == '') || (DebtorCode == null) || (DebtorCode == 'null'))
    {
      alert("Invalid Debtor Code. Please Refresh the Page.");
      return;
    }

    if((Overdue_Registration_Fees == '') || (Overdue_Registration_Fees == null) || (Overdue_Registration_Fees == 'null'))
    {
      alert("Please make sure Overdue Registration Fees have value or 0.00.");
      return;
    }

    if((Overdue_Subscriptions_Invoice_Amt == '') || (Overdue_Subscriptions_Invoice_Amt == null) || (Overdue_Subscriptions_Invoice_Amt == 'null'))
    {
      alert("Please make sure Overdue Subscriptions have value or 0.00.");
      return;
    }

    if((Total_Overdue == '') || (Total_Overdue == null) || (Total_Overdue == 'null'))
    {
      alert("Please make sure Total Overdue have value or 0.00.");
      return;
    }
    
    // if(Total_Overdue != sum_overdue)
    // {
    //   alert("Incorrect Sum out with Overdue Registration Fees and Overdue Subscriptions.");
    //   return;
    // }

    if((Last_Invoice_Amt == '') || (Last_Invoice_Amt == null) || (Last_Invoice_Amt == 'null'))
    {
      alert("Please make sure Last Invoice Amt have value or 0.00.");
      return;
    }
    
    $.ajax({
          url:"<?php echo site_url('Query_outstanding/b2b_reminder_update');?>",
          method:"POST",
          data:{supplier_guid:supplier_guid,DebtorCode:DebtorCode,Overdue_Registration_Fees:Overdue_Registration_Fees,Overdue_Subscriptions_Invoice_Amt:Overdue_Subscriptions_Invoice_Amt,Total_Overdue:Total_Overdue,Registration_Invoice_Date:Registration_Invoice_Date,Variance:Variance,Last_Invoice_Amt:Last_Invoice_Amt,table_name:table_name,customer_guid:customer_guid},
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
  });

  $(document).on('click','#delete_reminder',function(){
    var supplier_guid = $(this).attr('supplier_guid');
    var supplier_name = $(this).attr('supplier_name');
    var DebtorCode = $(this).attr('DebtorCode');

    confirmation_modal('Are you sure want to Remove?<br><b>'+supplier_name+'</b>');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Query_outstanding/b2b_reminder_delete');?>",
        method:"POST",
        data:{supplier_guid:supplier_guid,supplier_name:supplier_name,DebtorCode:DebtorCode},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == '1') {
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
            location.reload();
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });

  $(document).on('click', '#reminder_table tbody tr', function(event){
    
    var xstatus = $('#reminder_table').DataTable().rows().data().any();
    var debtor_code = $(this).attr('DebtorCode');

    if((xstatus == false) || (xstatus != true)){
      return;
    }

    if(event.target.tagName == "I" || event.target.tagName == "BUTTON" || event.target.tagName == "INPUT") {
      return;
    }

    if((debtor_code == '') || (debtor_code == null) || (debtor_code == 'null'))
    {
      alert('Invalid Debtor Code');
      return;
    }

    //child_table(debtor_code);

    $('input[aria-controls="reminder_retailer_table"]').val(debtor_code).keyup();

    var id = $(this).closest('table').attr('id');

    var table = $('#'+id).DataTable();

    table.rows('.active').nodes().to$().removeClass("active");

    $(this).closest('table').find('tr').removeClass("active");
    $(this).addClass('active');

  });//close mouse click

  $('#reminder_retailer_table').DataTable({
    "columnDefs": [{ "orderable": false, "targets": 28 },
    { className: "alignright", targets: [6,7,8,9,10,11,12,20] },
    { className: "alignleft", targets: '_all' }],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100, 9999999], [10, 25, 50, 100, 'ALL'] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [14 , 'desc'],[2 , 'asc']],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "50vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Query_outstanding/b2b_reminder_retailer_tb');?>",
        "type": "POST",
    },
    columns: [
      { data: "DebtorCode"},
      { data: "acc_name"},
      { data: "supplier_name"},
      { data: "reg_no"},
      { data: "Registration_Invoice_Date", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "One_Off_Invoice_Date", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Registration_AddON_Invoice_Amt", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //5
      { data: "Subscription_OneOFF_Invoice_Amt", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //6
      { data: "Training_Invoice_Amt", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //7
      { data: "Ad_Hoc_Service_Invoice_Amt", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //8
      { data: "Overdue_Registration_Fees", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //9
      { data: "Overdue_Subscriptions_Invoice_Amt", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //10
      { data: "Total_Overdue", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //11
      { data: "Overdue_Invoices_Count"},
      { data: "Overdue_Invoice_Date_From", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Overdue_Invoice_Date_To", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Overdue_Invoice_Due_Date", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Last_Subscriptions_Invoice_Count"},  
      { data: "Last_Invoice_Date", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Last_Due_Date", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00') || (data == '1001-01-01'))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "Last_Invoice_Amt", render: $.fn.dataTable.render.number( ',', '.', 2,)}, //15
      { data: "created_at"},
      { data: "created_by"},
      { data: "updated_at", render: function(data, type, row){ 
        var element = '';

        if((data == '0000-00-00 00:00:00') || (data == 'null') || (data == null))
        {
          element += '';
        }
        else
        {
          element += data;
        }

        return element;
      }},
      { data: "updated_by"},
      { data: "invoice_number", render: function(data, type, row){ 
         var element = '';

         element += '<span class="cell_breakWord">'+data+'</span>';

         element += '<button id="view_last_gr" type="button" style="float:right;" title="REMOVE" class="btn btn-xs btn-warning"  supplier_guid="'+row['supplier_guid']+'" customer_guid="'+row['customer_guid']+'"><i class="fa fa-file"></i></button>';

         return element;
      }},
      { data: "extend_status"},
      { data: "reminder_type", render: function(data, type, row){ 
        var element = '';
        var element1 = row['variance_day'];
        var element2 = row['until_date'];

        if(data == 'PROCESSING'  || data == 'Extended')
        {
          if(element1 != 0)
          {
            element += data+' '+element1+' DAY(S) until <br/>'+ element2;
          }
          else
          {
            element += data;
          }

        }
        else
        {
          element += data;
        }


        return element;
      }},
      { data: "action", render: function(data, type, row){ 
        var element = '';

        <?php
        if(in_array('IAVA',$this->session->userdata('module_code')))
        {
        ?>
          element += '<button id="edit_reminder_retailer" type="button"  title="EDIT" class="btn btn-xs btn-info edit_reminder" supplier_guid="'+row['supplier_guid']+'" supplier_name="'+row['supplier_name']+'" reg_no="'+row['reg_no']+'" DebtorCode="'+row['DebtorCode']+'" Overdue_Registration_Fees="'+row['Overdue_Registration_Fees']+'" Overdue_Subscriptions_Invoice_Amt="'+row['Overdue_Subscriptions_Invoice_Amt']+'" Total_Overdue="'+row['Total_Overdue']+'" Registration_Invoice_Date="'+row['Registration_Invoice_Date']+'" Last_Invoice_Amt="'+row['Last_Invoice_Amt']+'" Variance="'+row['Variance']+'" customer_guid="'+row['customer_guid']+'" table_name="query_outstanding_retailer"><i class="fa fa-edit"></i></button>';

          element += '<button id="delete_reminder" type="button" style="margin-left:5px;" title="REMOVE" class="btn btn-xs btn-danger"  supplier_guid="'+row['supplier_guid']+'" supplier_name="'+row['supplier_name']+'" DebtorCode="'+row['DebtorCode']+'"><i class="fa fa-trash"></i></button>';
        <?php
        }
        ?>

        return element;
      }},
    ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'Brtip',
        buttons: [

       { extend: 'excelHtml5',
         exportOptions: {columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,19,20,21,22,23,24,25,26,27 ]} /*, footer: true */},

       { extend: 'csvHtml5',  
         exportOptions: {columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,19,20,21,22,23,24,25,26,27 ]} /*, footer: true*/ },
              ],
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).closest('tr').css({"cursor": "pointer"});
      $(nRow).attr('dockey', aData['dockey']);
      $(nRow).attr('DebtorCode', aData['DebtorCode']);
      //$(nRow).closest('tr').find('td:eq(0)').css('white-space', 'nowrap');
      //$(nRow).closest('tr').find('td:eq(4)').css('word-break', 'break-all');
    },
    "initComplete": function( settings, json ) {
      interval();
    }
  });//close datatable

  $(document).on('click','#resync_data',function(){
    confirmation_modal('Are you sure want to Re Sync? Current Data will be remove. Please do a excel backup.');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Query_outstanding/b2b_resync_data');?>",
        method:"POST",
        beforeSend:function(){
          $('.btn').button('loading');
          $(".loader_submit").show(); 
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == '1') {
            $('#alertmodal').modal('hide');
            $(".loader_submit").hide();
            alert(json.msg.replace(/\\n/g,"\n"));
            $('.btn').button('reset');
          }else{
            $('#alertmodal').modal('hide');
            $(".loader_submit").hide();
            alert(json.msg.replace(/\\n/g,"\n"));
            $('.btn').button('reset');
            location.reload();
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });//close resync_data

  $(document).on('click', '#settings_data', function(e){

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Reminder Settings');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<?php foreach($settings as $row){?> <div class="col-md-12"><label> <?php echo $row->type?> </label> <input style="background-color:#e8f073;" type="text" class="form-control input-sm" id="d_<?php echo $row->type; ?>" value="<?php echo $row->description; ?>" placeholder ="Your Description"/> <input style="margin-top:5px;" type="text" class="form-control input-sm" id="e_<?php echo $row->type; ?>" value="<?php echo $row->value; ?>" placeholder="Your Value" /></div> <?php } ?>';


    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="edit_reminder_settings" class="btn btn-success" value="Edit"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);
  });

  $(document).on('click', '#edit_reminder_settings', function(e) {
    var e_registration_date = $('#e_registration_date').val();
    var e_outstanding_date = $('#e_outstanding_date').val();
    var e_overdue_date = $('#e_overdue_date').val();
    var e_overdue_count_block = $('#e_overdue_count_block').val();
    var e_overdue_count_warning = $('#e_overdue_count_warning').val();
    var e_overdue_count_gentle = $('#e_overdue_count_gentle').val();

    if((e_registration_date == '') || (e_registration_date == null) || (e_registration_date == 'null'))
    {
      alert("Invalid Registration Date Count Value . Please Insert Value.");
      return;
    }

    if((e_outstanding_date == '') || (e_outstanding_date == null) || (e_outstanding_date == 'null'))
    {
      alert("Invalid Invoice Date Count Value . Please Insert Value.");
      return;
    }

    if((e_overdue_date == '') || (e_overdue_date == null) || (e_overdue_date == 'null'))
    {
      alert("Invalid Overdue Date Count Value . Please Insert Value.");
      return;
    }

    if((e_overdue_count_block == '') || (e_overdue_count_block == null) || (e_overdue_count_block == 'null'))
    {
      alert("Invalid Overdue Count Block Value. Please Insert Value.");
      return;
    }

    if((e_overdue_count_warning == '') || (e_overdue_count_warning == null) || (e_overdue_count_warning == 'null'))
    {
      alert("Invalid Overdue Count Warning Value. Please Insert Value.");
      return;
    }

    if((e_overdue_count_gentle == '') || (e_overdue_count_gentle == null) || (e_overdue_count_gentle == 'null'))
    {
      alert("Invalid Overdue Count Gentle Value. Please Insert Value.");
      return;
    }

    $.ajax({
          url:"<?php echo site_url('Query_outstanding/b2b_reminder_settings');?>",
          method:"POST",
          data:{e_registration_date:e_registration_date,e_outstanding_date:e_outstanding_date,e_overdue_date:e_overdue_date,e_overdue_count_block:e_overdue_count_block,e_overdue_count_warning:e_overdue_count_warning,e_overdue_count_gentle:e_overdue_count_gentle},
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
  });

  $(document).on('click', '#view_last_gr', function() {
    //alert('OPPSSS!!'); die;
    var supplier_guid = $(this).attr('supplier_guid');
    var customer_guid = $(this).attr('customer_guid');

    $.ajax({
      url:"<?php echo site_url('Query_outstanding/view_last_gr_date') ?>",
      method:"POST",
      data:{supplier_guid:supplier_guid,customer_guid:customer_guid},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        var modal = $("#medium-modal").modal();

        modal.find('.modal-title').html('View GRN');

        methodd = '';
        //methodd +='<div class="row"> <div class="col-md-12"> <div class="box box-info"> <div class="box-body">  </div> </div> </div> </div>';

        methodd +='<div class="row"> <div class="col-md-12"> <div class="box box-info"> <div class="box-body">'; 
        
        methodd += '<table class="table table-bordered table-striped dataTable" id="view_gr_tb"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;"> <thead style="white-space: nowrap;"> <tr> <th>GR RefNo</th> <th>Status</th> <th>GR Date</th> <th>PostDateTime</th> </tr> </thead> <tbody> </tbody> </table>';

        methodd += '</div> </div> </div> </div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
        
        setTimeout(function(){
          if ($.fn.DataTable.isDataTable('#view_gr_tb')) {
              $('#view_gr_tb').DataTable().destroy();
          }

          $('#view_gr_tb').DataTable({
          "columnDefs": [
          // { className: "alignleft", targets: [0] },
          // { className: "alignright", targets: '_all' }
          ],
          "fixedColumns" : {
            leftColumns: 1
            },
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [3 , 'desc'] ],
          'info'        : true,
          'autoWidth'   : false,
          "bPaginate": false, 
          "bFilter": true, 
          "sScrollY": "60vh", 
          "sScrollX": "100%", 
          "sScrollXInner": "100%", 
          "bScrollCollapse": true,
            data: json['query'],
            columns: [
              { "data": "refno"},
              {"data" : "status"},
              {"data" : "grdate"},
              {"data" : "postdatetime"},
              ],
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip", 
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              //$(nRow).closest('tr').css({"cursor": "pointer"});
              // $(nRow).attr('status', aData['status']);
            },
            "initComplete": function( settings, json ) {
              interval();
            },
          });//close datatable
        },300);

        $('.btn').button('reset');
      }//close success
    });//close ajax 
  });
})
</script>
