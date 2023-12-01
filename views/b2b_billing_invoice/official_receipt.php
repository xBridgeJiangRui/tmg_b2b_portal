<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B Official Receipt Supplier Selection</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
          <div class="box-body">
          <div class="col-md-12">
              <div class="col-md-1">
                <label>Supplier</label>
              </div>
              <div class="col-md-6">
                <?php echo $supplier_drop_down;?>
              </div>
          </div>

          </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B Official Receipt</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
          <div class="box-body">

            <table class="table table-bordered table-striped dataTable" id="receipt_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;word-break: break-word !important">
                      <tr>
                          <th>Name</th>
                          <th>Receipt Number</th>
                          <th>Receipt Date</th>
                          <th>Receipt Total</th>
                          <th>Invoice Number</th>
                          <!-- <th>Invoice Total Apply Amount</th>  -->
                          <!-- <th>Invoice Total Amount</th> -->
                          <th>Invoice Count</th>
                          <th>Unapplied Amount</th>                                    
                          <!-- <th>Knock Off Amount</th>                           -->
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

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B Invoice</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
          <div class="box-body">

            <table class="table table-bordered table-striped dataTable" id="invoice_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;word-break: break-word !important">
                      <tr>
                          <th>Name</th>
                          <th>Invoice Number</th>
                          <th>Invoice Date</th>
                          <th>Invoice Status</th>
                          <th>Invoice Balance</th>
                          <th>Invoice Amount</th>
                          <th>Paid Amount</th>                                    
                          <th>Receipt Number</th>
                          <!-- <th>Receipt Total Apply Amount</th>  -->
                          <!-- <th>Receipt Count</th> -->
                          <th>CN Number</th>
                          <th>CN Total Amount</th>
                          <th>ARAP Contra Number</th>
                          <th>ARAP Contra Total Amount</th> 
                          <!-- <th>CN Count</th> -->
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
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B CN</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
          <div class="box-body">

            <table class="table table-bordered table-striped dataTable" id="cn_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;word-break: break-word !important">
                      <tr>
                          <th>Name</th>
                          <th>CN Number</th>
                          <th>CN Date</th>
                          <th>CN Amount</th>
                          <!-- <th>CN Balance</th>                                     -->
                          <th>Invoice Number</th>
                          <!-- <th>Invoice Total CN Amount</th>  -->
                          <!-- <th>Invoice Count</th> -->
                          <!-- <th>Invoice Total Amount</th> -->
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
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B Refund</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
          <div class="box-body">

            <table class="table table-bordered table-striped dataTable" id="refund_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;word-break: break-word !important">
                      <tr>
                          <th>Name</th>
                          <th>Refund Number</th>
                          <th>Refund Date</th>
                          <th>Refund Amount</th>
                          <!-- <th>CN Balance</th>                                     -->
                          <th>Ref Number</th>
                          <!-- <th>Invoice Total CN Amount</th>  -->
                          <!-- <th>Invoice Count</th> -->
                          <!-- <th>Invoice Total Amount</th> -->
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
<?php if(isset($_REQUEST['supp_guid']))
{
?>
// alert(1);
receipt_supplier_guid = "<?php echo $_REQUEST['supp_guid'];?>";
$('#receipt_supplier').val("<?php echo $_REQUEST['supp_guid'];?>");
<?php
}
else
{
?>
// alert(2);
receipt_supplier_guid = '';
<?php
}
?>


tablelist = function(ticket_status_value='')
{ 

  if ( $.fn.DataTable.isDataTable('#receipt_table') ) {
    $('#receipt_table').DataTable().destroy();
  }
  var table_branch;
  // alert();

  table_branch = $('#receipt_table').DataTable({  
    // "order": [[2,'desc']], 
    // "columnDefs": [{ "visible": false, "targets": [1]  },{ "orderable": false, "targets": 1 }],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [2 , 'desc'],[0 , 'asc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "30vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url" : "<?php echo site_url('B2b_billing_invoice_controller/official_receipt_table'); ?>",
        "type": "POST",
        "data": {supplier_guid:receipt_supplier_guid},
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
              { data: "supp_name"},
              { data: "receipt_no"},
              { data: "receipt_date"},
              { data: "receipt_total"},
              // { data: "ticket_status",render: function ( data, type, row ) {
              //   if (data == 'New') { word = '<b style="color:red; ">'+data+'</b>' } else { word = data }
              //   return word;
              // }},
              { data: "t_inv_no"},
              // { data: "inv_apply_amount_total"},
              // { data: "inv_amount_total"},
              { data: "inv_count"},
              { data: "receipt_unapply"},
              // { data: "knock_off_amount"},              
              // { data: "action"},
             ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'Brtip',
    "pagingType": "simple_numbers",
    "fnCreatedRow" : function( nRow, aData, iDataIndex) {
      $(nRow).closest('tr').find('td:eq(0)').css('white-space', 'nowrap');
      $(nRow).closest('tr').find('td:eq(4)').css('word-break', 'break-all');
      $(nRow).closest('tr').find('td:eq(3)').css('text-align', 'right');
      $(nRow).closest('tr').find('td:eq(6)').css('text-align', 'right');
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

  if ( $.fn.DataTable.isDataTable('#invoice_table') ) {
    $('#invoice_table').DataTable().destroy();
  }
  var table_branch;
  // alert();

  table_branch = $('#invoice_table').DataTable({  
    // "order": [[2,'desc']], 
    // "columnDefs": [{ "visible": false, "targets": [1]  },{ "orderable": false, "targets": 1 }],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [4 , 'desc'],[2 , 'asc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "30vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url" : "<?php echo site_url('B2b_billing_invoice_controller/official_invoice_table'); ?>",
        "type": "POST",
        "data": {supplier_guid:receipt_supplier_guid},
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
              { data: "CompanyName"},
              { data: "DocNo"},
              { data: "DocDate"},
              { data: "inv_payment_status_a_cn"},
              { data: "inv_balance"},
              { data: "inv_amount"},
              { data: "inv_applied_amount"},
              { data: "t_receipt_no"},
              // { data: "receipt_apply_total"},
              // { data: "inv_payment_status"},
              // { data: "receipt_count"},
              { data: "cn_no"},
              { data: "total_cn_amount"},
              { data: "t_contra_no"},
              { data: "contra_apply_total"},
              // { data: "overdue_status"},
              // { data: "cn_apply_count"},
              // { data: "action"},
             ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'Brtip',
    "pagingType": "simple_numbers",
    "fnCreatedRow" : function( nRow, aData, iDataIndex) {

      $(nRow).closest('tr').find('td:eq(1)').find('a').css({'text-decoration':'underline'});
      $(nRow).closest('tr').find('td:eq(7)').find('a').css({'text-decoration':'underline'});

      $(nRow).closest('tr').find('td:eq(0)').css('white-space', 'nowrap');
      $(nRow).closest('tr').find('td:eq(4)').css('word-break', 'break-all');
      if(aData['overdue_status'] == 1 && aData['inv_balance'] != 0.00 && aData['inv_applied_amount'] == 0.00)
      {
        $(nRow).closest('tr').css('background-color', 'crimson');
        $(nRow).closest('tr').find('td:eq(1)').find('a').css({'color':'white','text-decoration':'underline'});
        $(nRow).closest('tr').find('td:eq(7)').find('a').css({'color':'white','text-decoration':'underline'});
        // $(nRow).closest('tr').find('td:eq(1)').css('background-color', 'white');
      };
      if(aData['inv_payment_status_a_cn'] == 'Partial Paid')
      {
        $(nRow).closest('tr').css('background-color', 'palegoldenrod');
      };
      $(nRow).closest('tr').find('td:eq(4)').css('text-align', 'right');
      $(nRow).closest('tr').find('td:eq(5)').css('text-align', 'right');
      $(nRow).closest('tr').find('td:eq(6)').css('text-align', 'right');
      $(nRow).closest('tr').find('td:eq(9)').css('text-align', 'right');
      $(nRow).closest('tr').find('td:eq(11)').css('text-align', 'right');
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

tablelist3 = function(ticket_status_value='')
{ 

  if ( $.fn.DataTable.isDataTable('#cn_table') ) {
    $('#cn_table').DataTable().destroy();
  }
  var table_branch;
  // alert();

  table_branch = $('#cn_table').DataTable({  
    // "order": [[2,'desc']], 
    // "columnDefs": [{ "visible": false, "targets": [1]  },{ "orderable": false, "targets": 1 }],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [2 , 'desc'],[0 , 'asc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "30vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url" : "<?php echo site_url('B2b_billing_invoice_controller/official_cn_table'); ?>",
        "type": "POST",
        "data": {supplier_guid:receipt_supplier_guid},
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
                { data: "supplier_name"},
                { data: "CNDocNo"},
                { data: "CNDocDate"},
                { data: "total_cn_amount"},
                // { data: "cn_balance_amount"},                    
                { data: "invoice_number"},
                // { data: "apply_cn_amount"},
                // { data: "inv_count"},           
                // { data: "total_invoice_number"},
              // { data: "action"},
             ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'Brtip',
    "pagingType": "simple_numbers",
    "fnCreatedRow" : function( nRow, aData, iDataIndex) {
      $(nRow).closest('tr').find('td:eq(0)').css('white-space', 'nowrap');
      $(nRow).closest('tr').find('td:eq(4)').css('word-break', 'break-all');
      $(nRow).closest('tr').find('td:eq(3)').css('text-align', 'right');
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

tablelist3();

tablelist4 = function(ticket_status_value='')
{ 
  // alert(receipt_supplier_guid);
  if ( $.fn.DataTable.isDataTable('#refund_table') ) {
    $('#refund_table').DataTable().destroy();
  }
  var table_branch;
  // alert();

  table_branch = $('#refund_table').DataTable({  
    // "order": [[2,'desc']], 
    // "columnDefs": [{ "visible": false, "targets": [1]  },{ "orderable": false, "targets": 1 }],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [2 , 'desc'],[0 , 'asc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "30vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url" : "<?php echo site_url('B2b_billing_invoice_controller/official_refund_table'); ?>",
        "type": "POST",
        "data": {supplier_guid:receipt_supplier_guid},
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
                { data: "supplier_name"},
                { data: "DocNo"},
                { data: "DocDate"},
                { data: "apply_refund_amount"},
                // { data: "cn_balance_amount"},                    
                { data: "invoice_number"},
                // { data: "apply_cn_amount"},
                // { data: "inv_count"},           
                // { data: "total_invoice_number"},
              // { data: "action"},
             ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'Brtip',
    "pagingType": "simple_numbers",
    "fnCreatedRow" : function( nRow, aData, iDataIndex) {
      $(nRow).closest('tr').find('td:eq(0)').css('white-space', 'nowrap');
      $(nRow).closest('tr').find('td:eq(4)').css('word-break', 'break-all');
      $(nRow).closest('tr').find('td:eq(3)').css('text-align', 'right');
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

tablelist4();
$(document).on('change','#receipt_supplier',function(){
  receipt_supplier_guid = $(this).val();
  // alert(receipt_supplier_guid);
  tablelist();
  tablelist2();
  tablelist3();
  tablelist4();
  history.pushState("", document.title, 'official_receipt?supp_guid='+receipt_supplier_guid);
});

  $(document).on('click','#statement',function(){
    //alert('Opps.');
    var redirect = $(this).attr('direct_view');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Choose Customer');

    methodd = '';

    methodd = '<form action="<?php echo site_url('login_c/outside_view_statement');?>" method="post">';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-12"><label>Customer Name</label><select class="form-control" name="acc_guid" id="acc_guid"> <option value="">-Select-</option> <?php foreach ($customer->result() as $key) { ?> <option value="<?php echo $key->acc_guid ?>"><?php echo $key->acc_name?></option> <?php } ?> </select></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="choose_acc" class="btn btn-success" value="Submit" redirect_data='+redirect+'> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p></form>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);
    
  });

  $(document).on('click','#choose_acc',function(){
    //alert('Opps.');
    var customer_guid = $('#acc_guid').val();
    var redirect_data = $(this).attr('redirect_data');
    var location = '';

    if(customer_guid == '')
    {
      alert('Please Select Customer to Proceed View Statement');
      return;
    }

    if((redirect_data == '') || (redirect_data == 'null') || (redirect_data == null))
    {
      alert('Invalid redirect. Please Contact Support.');
      return;
    }

    if(redirect_data == 'view_statement')
    {
      location = "<?= site_url('b2b_billing_invoice_controller/statement'); ?>";
    }

    if(redirect_data == 'view_receipt')
    {
      location  = "<?= site_url('b2b_billing_invoice_controller/official_receipt');?>";
    }

    $.ajax({
          url:"<?= site_url('Login_c/outside_view_statement');?>",
          method:"POST",
          data:{customer_guid:customer_guid},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              $('#medium-modal').modal('hide');
              $('.btn').button('reset');
              window.location = location;
              //redirect(site_url('b2b_billing_invoice_controller/statement'));
            }
          }//close success
        });//close ajax
  });
  
  })
</script>

