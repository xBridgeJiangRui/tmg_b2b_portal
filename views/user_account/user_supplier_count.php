<style type="text/css">
.content-wrapper{
  min-height: 900px !important; 
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
        <h3 class="box-title">Dashboard User Count
        <span class="for_supp_name" ></span> 
        <!-- <span class="for_acc_name" wording="Click To Remove"></span>  -->
        </h3>
        <div class="box-tools pull-right">
          <!-- <button id="duplicate_from_retailer" class="btn btn-xs btn-danger">
            <i class="fa fa-copy"></i> Duplicate User From Other Retailer
          </button> -->
        </div> 
        <!--end pull right button -->
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
            <table id="account_tb1" class="table table-hover" width="100%" cellspacing="0">
                <thead style="white-space: nowrap;">
                  <tr>
                    <th>Action</th>
                    <th>Retailer Name</th>
                    <th>Supplier Name</th>
                    <th>Log User Count</th>
                    <th>Invoice Number</th>
                    <th>Created At</th>
                    <th>Created By</th>
                  </tr>
                </thead>
            </table>
        </div>  
      </div>
    </div>
  </div>
</div>
</div>
<script>
$(document).ready(function() {

  main_table = function() {
    if ($.fn.DataTable.isDataTable('#account_tb1')) {
      $('#account_tb1').DataTable().destroy();
    }

    var table;

    table = $('#account_tb1').DataTable({
      "scrollX": true,
      "processing": true,
      "serverSide": true,
      "lengthMenu": [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
      "sScrollY": "50vh",
      "sScrollX": "100%", 
      "sScrollXInner": "100%", 
      "bScrollCollapse": true,
      "order": [
        // [6, "desc"]
      ],
      "columnDefs": [
        { "orderable": false, "targets": [0]},
      ],
      "ajax": {
        "url": "<?php echo site_url('User_account_count/user_count_tb') ?>",
        "type": "POST",
        // "data": function(data) {
        //   data.supplier_guid = supplier_guid
        //   data.selected_customer_guid = selected_customer_guid
        // },
      },
      columns: [
          { "data" : "guid",render:function( data, type, row ){
            var element = '';

            element += '<button id="edit_btn" style="margin-left:5px;" title="Edit" class="btn btn-xs btn-primary" data_guid="'+row['guid']+'" data_customer_guid="'+row['customer_guid']+'" data_supplier_guid="'+row['supplier_guid']+'" data_acc_name="'+row['acc_name']+'" data_supplier_name="'+row['supplier_name']+'" data_count="'+row['user_count']+'"><i class="fa fa-pencil"></i></button>';

            return element;
          }},
          { "data" : "acc_name"},
          { "data" : "supplier_name",render:function( data, type, row ){
            var element = '';

            element += data + '<span style="float:right;""><i id="help_pop_up" class="fa fa-info-circle" data_customer_guid="'+row['customer_guid']+'" data_supplier_guid="'+row['supplier_guid']+'"></i></span>';

            return element;
          }},
          { "data" : "user_count"},
          { "data" : "invoice_number"},
          { "data" : "created_at"},
          { "data" : "created_by"},
      ],
      //dom: 'lBfrtip',
      dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
      // buttons: [
      //   'copy', 'csv', 'excel', 'pdf', 'print'
      // ]
      "fnCreatedRow": function( nRow, aData, iDataIndex ) 
      {
        $(nRow).closest('tr').css({"cursor": "pointer"});
        if(aData['invoice_number'] == '' )
        {
          $(nRow).find('td:eq(0)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(1)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(2)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(3)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(4)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(5)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(6)').css({"background-color":"#f0f725","color":"black"});
        }
      }
    });
  }
  main_table();

  $(document).on('click','#edit_btn',function(){

    var data_guid = $(this).attr('data_guid');
    var data_customer_guid = $(this).attr('data_customer_guid');
    var data_supplier_guid = $(this).attr('data_supplier_guid');
    var data_acc_name = $(this).attr('data_acc_name');
    var data_supplier_name = $(this).attr('data_supplier_name');
    var data_count = $(this).attr('data_count');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Edit Supplier User Count');

    methodd = '';

    methodd += '<div class="form-group"><input type="hidden" class="form-control" id="edit_guid" name="edit_guid" autocomplete="off" value="'+data_guid+'" /></div>';

    methodd += '<div class="form-group"><input type="hidden" class="form-control" id="edit_customer_guid" name="edit_customer_guid" autocomplete="off" value="'+data_customer_guid+'" /></div>';

    methodd += '<div class="form-group"><input type="hidden" class="form-control" id="edit_supplier_guid" name="edit_supplier_guid" autocomplete="off" value="'+data_supplier_guid+'" /></div>';

    methodd += '<div class=""><label>Retailer Name :</label> '+data_acc_name+' </div> <div class=""><label>Supplier Name :</label> '+data_supplier_name+' </div> <div class=""><label>Log User Count :</label> '+data_count+' </div>';

    methodd += '<div class="form-group"><label>Invoice Number</label><select class="form-control select2" name="edit_billing_info" id="edit_billing_info" > <option value=""> -SELECT DATA- </option> <?php foreach($get_billing as $row) { ?> <option value="<?php echo $row->invoice_number?>"><?php echo addslashes($row->name)?> - <?php echo addslashes($row->invoice_number)?> </option> <?php } ?> </select></div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="update_btn" class="btn btn-success" value="Update"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('#edit_billing_info').select2()
    },300);

  });

  $(document).on('click','#update_btn',function(){
    var edit_guid = $('#edit_guid').val();
    var edit_customer_guid = $('#edit_customer_guid').val();
    var edit_supplier_guid = $('#edit_supplier_guid').val();
    var edit_billing_info = $('#edit_billing_info').val();

    if((edit_guid == '') || (edit_guid == null) || (edit_guid == 'null'))
    {
      alert('Invalid GUID Process.');
      return;
    }
    
    if((edit_customer_guid == '') || (edit_customer_guid == null) || (edit_customer_guid == 'null'))
    {
      alert('Invalid Retailer GUID Process.');
      return;
    }

    if((edit_supplier_guid == '') || (edit_supplier_guid == null) || (edit_supplier_guid == 'null'))
    {
      alert('Invalid Supplier GUID Process.');
      return;
    }

    if((edit_billing_info == '') || (edit_billing_info == null) || (edit_billing_info == 'null'))
    {
      alert('Invalid Process. Please Select Invoice Number');
      return;
    }

    confirmation_modal('Are you sure want to Update?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('User_account_count/update_user_count') ?>",
        method:"POST",
        data:{edit_guid:edit_guid,edit_customer_guid:edit_customer_guid,edit_supplier_guid:edit_supplier_guid,edit_billing_info:edit_billing_info},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if(json.para1 == 'false')
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            alert(json.msg);
          }
          else
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            $("#medium-modal").modal('hide');
            alert(json.msg);
            location.reload();
          }
        }//close success
      });//close ajax 
    });//close document yes click
  });//close redirect

  $(document).on('click','#help_pop_up',function(){
    var info_customer_guid = $(this).attr('data_customer_guid');
    var info_supplier_guid = $(this).attr('data_supplier_guid');

    var modal = $("#large-modal").modal();

    modal.find('.modal-title').html('Current Supplier User Info');

    methodd = '';

    methodd +=' <div class="form-group"> <table id="supplier_info_tb" class="table table-bordered table-striped" width="100%" cellspacing="0"> <thead style="white-space: nowrap;"><tr> <th>Retailer Name</th> <th>Supplier Name</th> <th>User ID</th> <th>User Name</th> <th>User Group Name</th> </tr> </thead> <tbody> </tbody></table> </div> ';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
        $.ajax({
            url : "<?php echo site_url('User_account_count/fetch_user_tb'); ?>",
            method:"POST",
            data:{info_customer_guid:info_customer_guid,info_supplier_guid:info_supplier_guid},
            success:function(data)
            {
              json = JSON.parse(data); 

              if ($.fn.DataTable.isDataTable('#supplier_info_tb')) {
                $('#supplier_info_tb').DataTable().destroy();
              }

              $('#supplier_info_tb').DataTable({
                "columnDefs": [
                //    { "orderable": false, "targets": 0 },
                ],
                'processing'  : true,
                'paging'      : true,
                'lengthChange': true,
                'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
                'searching'   : true,
                'ordering'    : true,
                'order'       : [],
                'info'        : true,
                'autoWidth'   : true,
                "bPaginate": true, 
                "bFilter": true, 
                "sScrollY": "60vh", 
                "sScrollX": "100%", 
                "sScrollXInner": "100%", 
                "bScrollCollapse": true,
                  data: json['query_table_data'],
                  columns: [
                    { "data": "acc_name"},
                    { "data": "supplier_name"},
                    { "data": "user_id"},
                    { "data": "user_name"},
                    { "data": "user_group_name"},
                    // { "data": "company_name", render:function( data, type, row ){
                    //   var element = '';

                    //   element += row['company_name']+ '<br>'+ row['company_id'];

                    //   return element;
                    // }},
                  ],
                  dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip", 
                "language": {
                    "lengthMenu": "Show _MENU_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "zeroRecords": "<span ><?php echo '<b>No Record Found.</b>'; ?></span>",
                  }, 
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                    $(nRow).closest('tr').css({"cursor": "pointer"});
                },
                "initComplete": function( settings, json ) {
                  interval();
                },
              });//close datatable
            }
        });
    },300);
  });//close search button

});
</script>

