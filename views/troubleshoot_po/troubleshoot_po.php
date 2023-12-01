<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
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

  <!-- filter by -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <!-- head -->
        <div class="box-header with-border">
          <h3 class="box-title">Filter By</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- head -->
        <!-- body -->
        <div class="box-body">
          <div class="col-md-12">
            <div class="row">


              <div class="col-md-2"><b>Customer</b></div>
              <div class="col-md-4">
                 <select name="customer_guid" id="customer_guid" class="form-control">
                  <?php
                  foreach($acc->result() as $row)
                  {
                  ?>

                    <option value="<?=$row->acc_guid;?>"><?=$row->acc_name;?></option>

                  <?php
                  }
                  ?>
                </select>
              </div>

              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Vendor Code</b></div>
              <div class="col-md-4">
                 <select name="vendor_guid" id="vendor_guid" class="form-control select2">
                  <option value="">None</option>
                </select>
              </div>

              <div class="clearfix"></div><br>


              <div class="col-md-2"><b>PO Ref No</b></div>
              <div class="col-md-4">
                 <input id="po_num" name="po_num" type="text" autocomplete="off" class="form-control pull-right" spellcheck="false">
              </div>

              <div class="clearfix"></div><br>


              <div class="col-md-2"><b>PO Status</b></div>
              <div class="col-md-4">
                <select name="po_status" id="po_status" class="form-control">
                  <?php
                  foreach($po_status->result() as $row)
                  {
                  ?>

                    <option value="<?=$row->code;?>"><?=$row->reason;?></option>

                  <?php
                  }
                  ?>
                </select>                

              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>PO Date Range<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-4">
                <input required id="daterange" name="daterange" type="datetime" class="form-control pull-right" id="reservationtime" readonly>
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger"  onclick="date_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Expired Date From<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="expiry_from" name="expiry_from" type="datetime" value="" readonly class="form-control pull-right">
              </div>
              <div class="col-md-2"><b>Expired Date To<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="expiry_to" name="expiry_to" type="datetime" class="form-control pull-right" readonly value="" onchange="CompareDate()">
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
              <div class="col-md-4">
                <select name="period_code" id="period_code" class="form-control">
                  <option value="">None</option>

                  <?php
                  foreach($period_code->result() as $row)
                  {
                  ?>

                    <option value="<?= $row->period_code;?>"><?= $row->period_code;?></option>
                  <?php
                  }
                  ?>


                </select> 
              </div>
              
              <div class="clearfix"></div><br>

              <div class="col-md-12">

                
                <button type="button" id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                <a href="<?php echo site_url('Troubleshoot_po');?>" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a>
                
              </div>

            </div>
          </div>
        </div>
        <!-- body -->

      </div>
    </div>
    
  </div>
  <!-- filter by -->

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Purchase Order</b></h3> &nbsp;

          <span id="parameter_span">
          </span>

<!-- 
          <span class="pill_button">
            test
</span>
 -->

          <br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
      <div class="box-body">
      <div class="col-md-12">
        <br>
        <div>
            <div class="row">
                <div class="col-md-12"  style="overflow-x:auto"> 
                    <table id="po_new_table" class="table table-bordered table-hover" >
                      <!-- <form id="formPO" method="post" action="<?php echo site_url('general/prints')?>"> -->
                        <thead>
                            <tr>
                            <?php // var_dump($_SESSION); ?>
                                <!--Begin=Column Header-->
                                <th>PO Refno</th>
                                <th>GRN Refno</th>
                                <th>Outlet</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Po Date</th>
                                <th>Delivery Date</th>
                                <th>Expiry Date</th>
                                <th>Amount</th>
                                <th>Tax</th>
                                <th>Total Incl Tax</th>
                                <th>Status</th>
                                <th>Reject Remark</th>
                                <th>Action</th>
                                <th><input type="checkbox" id="check-all"></th>
                                <!--End=Column Header-->
                            </tr>
                        </thead>
                      <!-- </form> -->
            </table>
             
        </div>
    </div>

<!-- Modal -->
<div id="postatusmodal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center">Check PO Status</h4>
      </div>
      <div class="modal-body">
        <p><input type="text" id="po_refno"  name="po_refno" class="form-control">
        <center><span style="font-weight: bolder;" id="webindex_result"></span></center>  </p>
  <center><span style="font-weight: bolder;" id="po_check_grn_refno_result"></span></center>  </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload()">Close</button>
      </div>
    </div>

  </div>
</div>

<script>  
 $(document).ready(function(){  


po_new_table = function()
{
  if ( $.fn.DataTable.isDataTable('#po_new_table') ) {
    $('#po_new_table').DataTable().destroy();
  }
  
  var customer_guid = $('#customer_guid').val();
  var po_num = $('#po_num').val();
  var po_status = $('#po_status').val();
  var daterange = $('#daterange').val();
  var expiry_from = $('#expiry_from').val();
  var expiry_to = $('#expiry_to').val();
  var period_code = $('#period_code').val();
  var vendor_guid = $('#vendor_guid').val();

  span_button_para = '';

  if(customer_guid != '' && customer_guid != null)
  { 
    var display_customer = $('#customer_guid option:selected').text();

    span_button_para += '<span class="pill_button"> '+display_customer+' </span>';
  }

  if(vendor_guid != '' && vendor_guid != null)
  { 
    var display_vendor = $('#vendor_guid option:selected').text()
    span_button_para += '<span class="pill_button">Vendor Code :'+display_vendor+'</span>';
  }

  if(po_status != '' && po_status != null)
  {
    span_button_para += '<span class="pill_button">'+po_status+'</span>';
  }

  if(po_num != '' && po_num != null)
  {
    span_button_para += '<span class="pill_button"> '+po_num+' </span>';
  }

  
  if(daterange != '' && daterange != null)
  {
    span_button_para += '<span class="pill_button">PO Date Range :'+daterange+'</span>';
  }

  if((expiry_from != '' && expiry_from != null) && (expiry_to != '' && expiry_to != null))
  {
    span_button_para += '<span class="pill_button">Expiry Date :'+expiry_from+' - '+expiry_to+'</span>';
  }

  if(period_code != '' && period_code != null)
  {
    span_button_para += '<span class="pill_button">Period Code :'+period_code+'</span>';
  }

  $('#parameter_span').html('');

  $('#parameter_span').html(span_button_para);
  


  var table;

  table = $('#po_new_table').DataTable({
    "columnDefs": [ {"targets": [13,14] ,"orderable": false}],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [2 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    // "sScrollY": "30vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    // "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Troubleshoot_po/po_new_table');?>",
        "type": "POST",
        data : {po_num:po_num,po_status:po_status,daterange:daterange,expiry_from:expiry_from,expiry_to:expiry_to,period_code:period_code,vendor_guid:vendor_guid,customer_guid:customer_guid},
        complete:function()
        {
        },
    },
    //'fixedHeader' : false,
    columns: [
                // {"data":"RefNo"},
                {"data":"RefNo" ,render: function ( data, type, row ) {
                  
                  var element = '';

                  element += '<span style="display:flex;">'+data+'<i data-toggle="tooltip" data-placement="top" title="Click to preview item details" class="fa fa-info-circle" style="padding-top:5px;padding-left:10px;cursor: pointer;"  id="preview_po_item_line" refno='+data+'></i></span>';

                  return element;

                }},
                {"data":"gr_refno"},
                {"data":"loc_group"},
                {"data":"SCode"},
                {"data":"SName"},
                {"data":"PODate"},
                {"data":"DeliverDate"},
                {"data":"expiry_date"},
                {"data":"Total"},
                {"data":"gst_tax_sum"},
                {"data":"total_include_tax"},
                {"data":"status", render:function( data, type, row ){
                  var element = data;

                  <?php
                  if(in_array('EPS',$_SESSION['module_code']))
                  {
                  ?>
                    element += '<button type="button" id="edit_po_status" class="btn btn-xs btn-primary" style="margin-left:10px;" RefNo="'+row['RefNo']+'" status="'+data+'"><i class="fa fa-edit"></i></button>';
                  <?php
                  }
                  ?>
                  
                  return element;

                }},
                {"data":"portal_description"},
                {"data":"filename" ,render: function ( data, type, row ) {
                
                  element = '<span style="display:flex;">';

                  element += '<button style="float:left" id="open_modal_troubleshoot_po" filename="'+data+'" class="btn btn-sm btn-info" role="button"><i class="glyphicon glyphicon-eye-open"></i></button>';

                  element += '<button style="float:left;margin-left:5px;" id="open_modal_troubleshoot_po_useraction" RefNo="'+row['RefNo']+'"" class="btn btn-sm btn-warning" role="button"><i class="fa fa-bars"></i></button>';

                  element == '</div>';

                  return element;

                }},
                {"data":"RefNo" ,render: function ( data, type, row ) {
                if (data == 1) { ischecked = 'checked' } else { ischecked = '' }
                return '<input type="checkbox" class="form-checkbox" '+ischecked+' />';
                }},
             ],
    // dom: "<'row'<'col-sm-2 'l > <'col-sm-4' > <'col-sm-6' f>  " + "<'col-sm-1' <'toolbar_list'>>>" +'rt<"row" <".col-md-4 remove_padding" i>  <".col-md-8" p> >',

    "buttons": [
    {
        extend: 'excelHtml5',
        exportOptions: { orthogonal: 'export' }
    },

    ],

    dom:'<"row"<"col-sm-2" l><"col-sm-4" B><"col-sm-6" f>>rtip',

    // "pagingType": "simple_numbers",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {

      // $(nRow).closest('tr').css({"cursor": "pointer"});

      $(nRow).attr('RefNo', aData['RefNo']);
      $(nRow).attr('gr_refno', aData['gr_refno']);
      $(nRow).attr('loc_group', aData['loc_group']);
      $(nRow).attr('scode', aData['scode']);
      $(nRow).attr('sname', aData['sname']);
      $(nRow).attr('podate', aData['podate']);
      $(nRow).attr('delivery_date', aData['delivery_date']);
      $(nRow).attr('expiry_date', aData['expiry_date']);
      $(nRow).attr('total', aData['total']);
      $(nRow).attr('gst_tax_sum', aData['gst_tax_sum']);
      $(nRow).attr('total_include_tax', aData['total_include_tax']);
      $(nRow).attr('status', aData['status']);
      $(nRow).attr('rejected_remark', aData['rejected_remark']);
      $(nRow).attr('refno', aData['refno']);
      $(nRow).attr('refno', aData['refno']);

    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
        $('.btn').button('reset');
      },300);
    }
  });//close datatable

}//close po_new_table


$(document).on('click', '#preview_po_item_line', function(e) {
      var customer_guid = $('#customer_guid').val();      
      var refno = $(this).attr('refno');

      var modal = $("#medium-modal").modal();

      modal.find('.modal-title').html('PO Preview Item Line');

      methodd = '';

      methodd +='<table class="table table-bordered table-striped" id="preview_po_item_line_table" width="100%"><thead><th>Line</th><th>Itemcode</th><th>Description</th></thead></table>';

      methodd +='</div>';


      methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

      modal.find('.modal-footer').html(methodd_footer);
      modal.find('.modal-body').html(methodd);

      $('#preview_po_item_line_table').DataTable({
        'processing'  : true,
      });

      $('#preview_po_item_line_table_processing').css({'z-index':'1040'}).show();


      setTimeout(function(){

       $.ajax({
            url:"<?php echo site_url('Troubleshoot_po/preview_po_item_line'); ?>",
            method:"POST",
            data: {customer_guid:customer_guid,refno:refno},
            success:function(data)
            { 
              json = JSON.parse(data);
              // alert(json);return;
              if ( $.fn.DataTable.isDataTable('#preview_po_item_line_table') ) {
                $('#preview_po_item_line_table').DataTable().destroy();
              }

              $('#preview_po_item_line_table').DataTable({
                // "columnDefs": [ {"targets": 1 ,"visible": false}],
                'processing'  : true,
                "sScrollY": "40vh", 
                "sScrollX": "100%", 
                "sScrollXInner": "100%", 
                'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
                "bScrollCollapse": true,
                // "pagingType": "simple",
                'order'       : [ [0 , 'asc'] ],
                data: json['po_item_line'],
                columns: [  
                          {data: "Line"},
                          {data: "Itemcode"},
                          {data: "Description"}
                         ],   
                dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                  // $(nRow).attr('id', aData['RefNo']);
                },
                "initComplete": function( settings, json ) {
                  setTimeout(function(){
                    interval();
                  },300);
                }
              });//close datatatable

            }//close succcess
      });//close ajax
    },300);          

});//close onclick preview item line

// po_new_table();

$('#po_new_table').DataTable();

$(document).on('click','#open_modal_troubleshoot_po',function()
{

  var filename = $(this).attr('filename');


  var modal = $("#large-modal").modal();

  modal.find('.modal-title').html('PO');

  methodd = '';

  methodd +='<div class="col-md-12">';

  methodd += '<embed src="'+filename+'" width="100%" height="500px" style="border: none;">';


  methodd += '</div>';

  methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

  modal.find('.modal-footer').html(methodd_footer);
  modal.find('.modal-body').html(methodd);


});//close click button modal




$(document).on('click','#open_modal_troubleshoot_po_useraction',function()
{

  var modal = $("#medium-modal").modal();

  modal.find('.modal-title').html('Details');

  methodd = '';

  methodd +='<div class="col-md-12">';

  methodd += '<table id="user_movements_table_details" class="table table-bordered table-hover"><thead><tr><th>User ID</th><th>Value</th><th>Action</th><th>Created At</th><th>Type</th></tr></thead></table>';


  methodd += '</div>';

  methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

  modal.find('.modal-footer').html(methodd_footer);
  modal.find('.modal-body').html(methodd);

  var customer_guid = $('#customer_guid').val();
  var RefNo = $(this).attr('RefNo');

  $.ajax({
        url : "<?php echo site_url('Troubleshoot_po/user_movements_table'); ?>",
        type: "POST",
        data:{customer_guid:customer_guid,RefNo:RefNo},
        beforeSend : function() {
            $('.btn').button('loading');
        },
        complete: function() {
            $('.btn').button('reset');
        },
        success : function(data){
          
        json = JSON.parse(data);

        if ($.fn.DataTable.isDataTable('#user_movements_table_details')) {
            $('#user_movements_table_details').DataTable().destroy();
        }

         $('#user_movements_table_details').DataTable({
            // "columnDefs": [{ "orderable": false, "targets": 0 }],
            'processing'  : true,
            'paging'      : true,
            'lengthChange': true,
            'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
            'searching'   : true,
            'ordering'    : true,
            'order'       : [ [3 , 'desc'] ],
            'info'        : true,
            'autoWidth'   : false,
            "bPaginate": true, 
            "bFilter": false, 
            "sScrollY": "30vh", 
            "sScrollX": "100%", 
            "sScrollXInner": "100%", 
            "bScrollCollapse": true,
            "language": {
                "zeroRecords": "No movements.",
                "infoEmpty": "",
            },
            data: json['movements'],
            columns: [
                      // {"data":"set_disable",render: function ( data, type, row ) {
                      //   if (data == 1) { ischecked = 'checked' } else { ischecked = '' }
                      //   return '<input '+ischecked+' type="checkbox" class="form-checkbox" disabled>';
                      // }},     
                      {"data": "user_id"},
                      {"data": "value",render: function ( data, type, row ) {
                       
                        return '<span class="label label-default" style="font-size:14px;">'+data+'</span>';
                      }},
                      {"data": "action"},
                      {"data": "c_date"},
                      {"data": "type"}
                     ],                       
             dom: '<"row" <"col-sm-6"l><"col-sm-6" f> >rt  <"row" <"col-sm-6"i><"col-sm-6" p> >',
             "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                // $(nRow).attr('outlet_guid', aData['outlet_guid']);


             },
             "initComplete": function( settings, json ) {
                setTimeout(function() {
                  interval();
                }, 300);
              }
          });//close datatable


        }//close success
      });//close ajax

});//close click button modal


$(document).on('change','#customer_guid',function(){

  var customer_guid = $('#customer_guid').val();

  $.ajax({
            url:"<?php echo site_url('Troubleshoot_po/vendor_code_dropdown');?>",
            method:"POST",
            data:{customer_guid:customer_guid},
            beforeSend:function(){
              $('.btn').button('loading');
              $('#vendor_guid').prepend($('<option></option>').html('LOADING...'));
            },
            complete:function(){
            },
            success:function(data)
            {
              json = JSON.parse(data);

              var set_supplier = '';

              // set_supplier += '<option value="NA">NA</option>';

              Object.keys(json['set_supplier']).forEach(function(key) {

                set_supplier += '<option value="'+json['set_supplier'][key]['supplier_guid']+'">';

                set_supplier += json['set_supplier'][key]['supplier_name'];

                set_supplier += '</option>';

              });

              $('#vendor_guid').html(set_supplier);
              $('.btn').button('reset');

            }//close success
          });//close ajax

});


$('#customer_guid').trigger('change');


$(document).on('click','#search',function(){

  var vendor_guid = $('#vendor_guid').val();
  var customer_guid = $('#customer_guid').val();

  if(customer_guid == '' || customer_guid == null)
  {
    alert('Please select a customer to proceed.');
    return;
  }

  if(vendor_guid == '' || vendor_guid == null)
  {
    alert('Vendor Code must have value.');
    return;
  }

  po_new_table();
});


  $(document).on('click', '#po_bulk_accept', function(e) {
      var list_id = [];
      $(".data-check:checked").each(function() {
            list_id.push(this.value);
      });

      // alert(list_id.length);
      

      $.ajax({  
       url:"<?php echo site_url('Panda_po_2/bulk_accept'); ?>",  
       method:"POST",  
       data:{list_id:list_id},  
       success:function(data)
       {                         
          if(data == 1)
          {
            alert('PO Accepted');
            location.reload();
          }
          else
          {
            alert('Error Occur');
            location.reload();

          }
                   
       }  
      });  
  });


$(document).on('click', '#edit_po_status', function(e) {
   
      var RefNo = $(this).attr('RefNo');
      var status = $(this).attr('status');

      var modal = $("#medium-modal").modal();

      modal.find('.modal-title').html('PO Preview Item Line');

      methodd = '<div class="col-md-12">';

      methodd += '<div class="col-md-4"><label>RefNo</label><input type="text" class="form-control" value="'+RefNo+'" readonly/> </div>';

      methodd += '<div class="col-md-8"><label>Status</label><select name="edit_po_status_dropdown" id="edit_po_status_dropdown" class="form-control">';

      <?php
      foreach($po_status->result() as $row)
      {
      ?>

        methodd += '<option value="<?=$row->code;?>"><?=$row->reason;?></option>';

      <?php
      }
      ?>

      methodd += '<option value="HFSP">Hide From Supplier</option>';

      methodd += '</select> </div>';

      methodd +='</div>';


      methodd_footer = '<p class="full-width"><span class="pull-right"><input id="confirm_edit_po_status" name="sendsumbit" type="button" class="btn btn-default" value="Save"/> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

      modal.find('.modal-footer').html(methodd_footer);
      modal.find('.modal-body').html(methodd);

      setTimeout(function(){

        $('#edit_po_status_dropdown').val(status);

      },300);          

      $(document).off('click', '#confirm_edit_po_status').on('click', '#confirm_edit_po_status', function(){

        confirmation_modal('Confirm To update PO status?');

        $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

          var status = $('#edit_po_status_dropdown').val();

          if((RefNo == '') || (RefNo == null))
          {
            alertmodal('RefNo must have value.');
            return;
          }//close checking for posted table_ss

          if(status == null)
          {
            alertmodal('Please select a status to update.');
            return;
          }//close checking for posted table_ss


          $.ajax({
              url:"<?php echo site_url('Troubleshoot_po/update_po_status');?>",
              method:"POST",
              data:{RefNo:RefNo,status:status},
              beforeSend:function(){
                $('.btn').button('loading');
              },
              success:function(data)
              {

                json = JSON.parse(data);

                if (json.para1 == '1') {
                  informationalertmodal(json.button,json.icons,json.msg,'Error');
                  $('.btn').button('reset');
                }else{

                  $('#medium-modal').modal('hide');
                  informationalertmodal(json.button,json.icons,json.msg,'Information');
                  setTimeout(function() {

                    po_new_table();

                  }, 300);

                }//close else

              }//close success
            });//close ajax

        });//close document yes
      });//close edit button


});//close onclick preview item line


  $(document).on('paste', '#po_refno', function(e) {
    e.preventDefault();
    var withoutSpaces = e.originalEvent.clipboardData.getData('Text');
    withoutSpaces = withoutSpaces.replace(/\s+/g, '');
    $(this).val(withoutSpaces);
  });

  $(document).on('keypress','#po_refno',function(e) {
      if(e.which == 32) {
        event.preventDefault();
        return false;
      }//close function for click space
  });//close keypress funhction

      $('#po_refno').keyup(function(){  
           var po_refno = $('#po_refno').val();  
           if(po_refno != '')  
           {  
              $.ajax({  
                     url:"<?php echo site_url('general/check_po_status'); ?>",  
                     method:"POST",  
                     data:{po_refno:po_refno},  
                     success:function(data)
                     {                         
                         if(data.substring(1,2)  == 1)
                          {
                            $('#po_refno').css('border', '2px green solid');
                            $('#webindex_result').html(data.substring(2));   
                          } 
                          else if(data == ' ')
                          {
                              $('#po_refno').css('border', '2px blue solid');
                              $('#webindex_result').html('Please Wait......');   
                          }                         
                          else
                      {
                              $('#po_refno').css('border', '2px red solid');
                              $('#webindex_result').html(data.substring(2));                             
                          }         
                             
                     }  
                });  

                           $.ajax({  

                           url:"<?php echo site_url('general/check_grn_no'); ?>",  

                           method:"POST",  

                           dataType:"json",

                           data:{po_check_grn_refno:po_refno},  

                           success:function(data)

                           {                  

                               if(data.count == 0)

                                {

                                  $('#po_check_grn_refno').css('border', '2px green solid');

                                  $('#po_check_grn_refno_result').html(data.xmessage);   

                                } 

                                else if(data.count == 1)

                                {

                                  $('#po_check_grn_refno').css('border', '2px green solid');

                                  $('#po_check_grn_refno_result').html(data.xmessage);    

                                }                         

                                else

                                {

                                  $('#po_check_grn_refno').css('border', '2px red solid');

                                  $('#po_check_grn_refno_result').html(data.xmessage);                              

                                }                                 

                           }  

                          });  

           }  
      }); 
 });  
 </script>  
<script>
$(function() {
  $('input[name="daterange"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
  });
  //$('#daterange').data('daterangepicker').setStartDate('<?php echo date('Y-m-d', strtotime('-7 days')) ?>');
  //$('#daterange').data('daterangepicker').setEndDate('<?php echo date('Y-m-d') ?>');
  $(this).find('[name="daterange"]').val("");
});
</script>
 

<script type="text/javascript">
$(function() {
  $('input[name="expiry_from"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
  $(this).find('[name="expiry_from"]').val("");
});
</script>

<script type="text/javascript">
$(function() {
  $('input[name="expiry_to"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
  $(this).find('[name="expiry_to"]').val("");
});
</script>

<script type="text/javascript">
  function date_clear()
  {
    $(function() {
        $(this).find('[name="daterange"]').val("");
    });
  }

  function expiry_clear()
  {
    $(function() {
        $(this).find('[name="expiry_from"]').val("");
        $(this).find('[name="expiry_to"]').val("");
    });
  }
</script>

<script type="text/javascript">
   function CompareDate() {
       var dateOne = $('input[name="expiry_from"]').val(); //Year, Month, Date
       var dateTwo = $('input[name="expiry_to"]').val(); //Year, Month, Date
       if (dateOne > dateTwo) {
            alert("Expiry To : "+dateTwo+" Cannot Be a date before "+dateOne+".");
            $('#search').attr('disabled','disabled');
        }
        else 
        {
           $('#search').removeAttr('disabled');
        }

    }
</script>

<script type="text/javascript">
  function hide_modal()
  {
    $('#otherstatus').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal_detail').text('Confirm Hide ' + button.data('refno') + '?')
      modal.find('[name="refno"]').val(button.data('refno'))
      modal.find('[name="loc"]').val(button.data('loc'))
    });
  }
  
</script>
</div>
        </div>
        
        </div>
    </div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
  function bulk_print()
  {
    var list_id = [];
    $(".data-check:checked").each(function() {
            list_id.push(this.value);
    });
     if(list_id.length > 1)
    {
      // alert('use merge');
            $.ajax({
            type: "POST",
            data: {id:list_id},
            url: "<?php echo site_url('general/merge_pdf?loc='.$_REQUEST['loc'].'&po_type=PO')?>",
            dataType: "JSON",
            success: function(data)
            { 
                // alert(data.link_url);
                if(data.link_url)
                {
                   
                   var newwin = window.open(data.link_url); 
                    newwin.onload = function() {

                      setTimeout(function(){

                        var url_link = data.pdf_file;

                        $.ajax({
                                type: "POST",
                                data: {url_link:url_link},
                                url: "<?php echo site_url('general/unlink_file')?>",
                                dataType: "JSON",
                                success: function(data)
                                { 
                                  alert('delete success'+data);
                                }//close success
                              });//close ajax

                      },1000);
                    
                    };//close onload
                }
                else
                {
                    alert('Failed.');
                }
                
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error Opening data');
            }
        });
    }
    else if(list_id.length > 0)
    {
        if(confirm('Are you sure open this '+list_id.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {id:list_id},
                url: "<?php echo site_url('general/ajax_bulk_print?loc='.$_REQUEST['loc'])?>",
                dataType: "JSON",
                success: function(data)
                { 
                    //alert(data.link_url);
                    if(data.link_url)
                    {
                      data.link_url.forEach(function(element){
                        window.open(element); 
                      });
                       
                    }
                    else
                    {
                        alert('Failed.');
                    }
                    
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error Opening data');
                }
            });
        }
    }
    else
    {
        alert('no data selected');
    }
  }

</script>

</script>
<script type="text/javascript">
  function viewothers()
  {
    $('#viewothers').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal_detail').text('Confirm Hide ' + button.data('name') + '?')
      modal.find('[name="refno"]').val(button.data('refno'))
      modal.find('[name="customer_guid"]').val(button.data('customer_guid'))
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="col_guid"]').val(button.data('col_guid'))
      modal.find('[name="loc"]').val(button.data('loc'))
      modal.find('[name="name"]').val(button.data('name'))
  
    });
  }
  
</script>
<script>
    function ahsheng() 
    {
      location.href = '<?php echo site_url('general/view_status') ?>?status='+$('#reason').val()+'&loc=HQ';
    }
</script>