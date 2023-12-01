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

<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
    <div class="container-fluid">
        <br>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filter By</h3><br>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="row">
                                <!-- <div class="col-md-2"><b>Slip Status</b></div>
                                  <div class="col-md-4">
                                      <select id="select_status_data" class="form-control select2">
                                          <option value="" selected="">-STATUS-</option>
                                              <option value="Uploaded">Uploaded - Emailed </option>
                                      </select>
                                </div>

                                <div class="clearfix"></div><br> -->

                                <div class="col-md-2"><b>Supplier Name</b></div>
                                  <div class="col-md-4">
                                      <select id="select_supplier_guid" class="form-control select2">
                                          <option value="" selected="">-SUPPLIER NAME-</option>
                                          <?php foreach ($get_supplier_name_list->result() as $row) { ?>
                                              <option value="<?php echo $row->supplier_guid ?>">
                                                <?php echo $row->supplier_name; ?> </option>
                                          <?php } ?>
                                      </select>
                                  </div>

                                  <div class="clearfix"></div><br>

                                  <div class="col-md-2"><b>Invoice Date (Period Code)</b></div>
                                  <div class="col-md-4">
                                      <select id="select_period_code" class="form-control select2">
                                          <option value="" selected="">-PERIOD CODE-</option>
                                          <?php foreach ($get_period_code->result() as $row) { ?>
                                              <option value="<?php echo $row->period_code ?>">
                                                <?php echo $row->period_code; ?> </option>
                                          <?php } ?>
                                      </select>
                                  </div>

                                  <div class="clearfix"></div><br>

                                <div class="col-md-12">
                                    <button id="search_data" class="btn btn-primary" ><i class="fa fa-search"></i> Search</button>
                                    <button id="reset_data" class="btn btn-secondy"><i class="fa fa-repeat"></i> Reset</button>
                                </div>
                                <!-- </form> -->
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
                        <h3 class="box-title"><b>Billing Invoices</b></h3> &nbsp;
                        <div class="box-tools pull-right">
                            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="box-body">
                        <table class="table table-bordered table-hover" id="ttable"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                          <thead style="white-space: nowrap;">
                            <tr>
                            <th >Name</th> 
                            <th >Invoice Number</th>
                            <th >Invoice Date</th>
                            <th >CN Invoice Number</th>
                            <!-- <th >Invoice Status</th> -->
                            <th >Total Amount</th>
                            <th >Created At</th>
                            <!-- <th >Variance</th>
                            <th >Slip Status</th>
                            <th >Action</th> -->
                            <!-- <th >Sorting</th> -->
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
</div>

<script>
$(document).ready(function () {    

  $('#ttable').DataTable({
    "columnDefs": [{"targets": '_all' ,"orderable": false}],
      'order': [],
      "sScrollY": "30vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
       dom: "<'row'<'col-sm-2 remove_padding_right 'l > <'col-sm-10' f>  " + "<'col-sm-1' <'toolbar_list'>>>" +'rt<"row" <".col-md-4 remove_padding" i>  <".col-md-8" p> >',
        "language": {
                "lengthMenu": "Display _MENU_",
                "infoEmpty": "No records available",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "info":           "Show _START_ - _END_ of _TOTAL_ entry",
                "zeroRecords": "<?php echo '<b>No Record Found. Please Select Filter view data.</b>'; ?>",
      },
      "pagingType": "simple_numbers",
  });
  $('.remove_padding_right').css({'text-align':'left'});
  $("div.remove_padding").css({"text-align":"left"});

  $(document).on('click','#search_data',function(){

    filter_status_data = $('#select_status_data').val();
    filter_supplier_guid = $('#select_supplier_guid').val();
    filter_period_code = $('#select_period_code').val();

    billing_tb(filter_status_data,filter_supplier_guid,filter_period_code);
    // $('.add_branch_list').addClass('pill_button');
    // $('.add_branch_list').html(value);

  });//close search button

  $(document).on('click','#reset_data',function(){

    filter_status_data = $('#select_status_data').val('').trigger('change');

    filter_supplier_guid = $('#select_supplier_guid').val('').trigger('change');
    filter_period_code = $('#select_period_code').val('').trigger('change');

  });//close search button

  billing_tb = function(filter_status_data,filter_supplier_guid,filter_period_code)
  { 
    $.ajax({
      url : "<?php echo site_url('B2b_billing_invoice_controller/invoice_tb_admin');?>",
      method: "POST",
      data:{filter_status_data:filter_status_data,filter_supplier_guid:filter_supplier_guid,filter_period_code:filter_period_code},
      beforeSend : function() {
          $('.btn').button('loading');
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success : function(data)
      {  
        json = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#ttable')) {
            $('#ttable').DataTable().destroy();
        }

        $('#ttable').DataTable({
          "columnDefs": [
          <?php if ($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE') { ?>
            // { visible: false, targets: [5]},
          <?php } else { ?>
            // { visible: false, targets: [5]},
          <?php } ?>
          //{"targets": 7 ,"orderable": false},
          { className: "alignright", targets: [4] },
          { className: "alignleft", targets: '_all' },
          ],
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          "lengthMenu": [
            [10, 25, 50, 100, 200, 500, 1000 , 999999], 
            [10, 25, 50, 100, 200, 500, 1000, 'All'] 
          ],
          'searching'   : true,
          'ordering'    : true,
          <?php if ($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE') { ?>
          'order'       : [ ],
          <?php } else { ?>
          'order'       : [],
          <?php } ?>
          'info'        : true,
          'autoWidth'   : false,
          "bPaginate": true, 
          "bFilter": true, 
          "sScrollY": "40vh", 
          "sScrollX": "100%", 
          "sScrollXInner": "100%", 
          "bScrollCollapse": true,
          data: json['query_data'],
          columns: [
            { "data": "name" },
            { "data" : "invoice_number" ,render:function( data, type, row ){

              var element = '';
              var element1 = row['final_amount'];
              var element2 = row['invoice_type'];

              if(element2 == 'Subscription')
              {
                element += '<a target="framename" href="<?php echo site_url('Invoice/view_report_inv?inv_guid=');?>'+row['inv_guid']+'&inv_number='+row['invoice_number']+'"> '+data+'';
              }
              
              if(element2 == 'Registration')
              {
                element += '<a target="framename" href="<?php echo site_url('Invoice/view_report_reg?inv_guid=');?>'+row['inv_guid']+'&inv_number='+row['invoice_number']+'"> '+data+'';
              }

              return element;
       
            }},
            { "data" : "invoice_date" },
            { "data" : "cn_inv_no" ,render:function( data, type, row ){

              var element = '';

              if(data != '' && data != null && data != 'null')
              {
                element += '<a target="framename" href="<?php echo site_url('Invoice/view_report_cn?inv_guid=');?>'+row['cn_inv_guid']+'&inv_number='+row['cn_inv_no']+'"> '+data+'';
              }
              
              return element;

            }},
            // { "data" : "inv_status" },
            { "data" : "total_include_tax" ,render:function( data, type, row ){

              var element = '';
              var element1 = row['final_amount'];

              if((data == '') || (data == 'null') || (data == null) || (data == '0.00'))
              {
                element = '<b>'+element1+'</b>';
              }
              else
              {
                element = '<b>'+data+'</b>';
              }

              return element;
       
            }},
            { "data" : "created_at" },
            // { "data" : "variance_status" },
            // { "data" : "file_status" },
            // { "data" : "sorting_two" ,render:function( data, type, row ){

            //   var element = '';
            //   var element1 = row['final_amount'];
            //   var element2 = row['file_status'];
            //   var element3 = row['inv_status'];

            //   if((element3 != 'Paid') && (element3 != 'cn' || element3 != 'CN'))
            //   {
            //     if((element2 == 'Uploaded') || (element2 == 'Processed'))
            //     {
            //       element += '<button id="view_btn" type="button" style="margin-right:5px;" title="VIEW" class="btn btn-xs btn-info view" inv_guid="'+row['inv_guid']+'" invoice_number="'+row['invoice_number']+'" supplier_guid="'+row['biller_guid']+'" file_status="'+row['file_status']+'" slip_created_at="'+row['slip_created_at']+'" slip_created_by="'+row['slip_created_by']+'"><i class="fa fa-eye"></i></button>';
            //     }

            //     if(element2 != 'Processed')
            //     {
            //       element += '<button id="upload_btn" type="button" style="margin-right:5px;" title="UPLOAD" class="btn btn-xs btn-warning" inv_guid="'+row['inv_guid']+'" invoice_number="'+row['invoice_number']+'" supplier_guid="'+row['biller_guid']+'" ><i class="fa fa-upload"></i></button>';

            //       if(element2 == 'Uploaded')
            //       {
            //         element += '<button id="delete_btn" type="button"  title="REMOVE" class="btn btn-xs btn-danger" inv_guid="'+row['inv_guid']+'" invoice_number="'+row['invoice_number']+'" supplier_guid="'+row['biller_guid']+'" ><i class="fa fa-trash"></i></button>';
            //       }
            //     }

            //     <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
            //     {
            //       ?>
            //       if(element2 == 'Processed')
            //       {
            //         element += '<button id="delete_btn" type="button"  title="REMOVE" class="btn btn-xs btn-danger" inv_guid="'+row['inv_guid']+'" invoice_number="'+row['invoice_number']+'" supplier_guid="'+row['biller_guid']+'" ><i class="fa fa-trash"></i></button><button id="view_btn" type="button" style="margin-right:5px;" title="VIEW" class="btn btn-xs btn-info view" inv_guid="'+row['inv_guid']+'" invoice_number="'+row['invoice_number']+'" supplier_guid="'+row['biller_guid']+'" file_status="'+row['file_status']+'" slip_created_at="'+row['slip_created_at']+'" slip_created_by="'+row['slip_created_by']+'"><i class="fa fa-eye"></i></button>';
            //       }

            //       <?php
            //     }
            //     ?>
            //   }
            //   else
            //   {
            //     <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
            //     {
            //       ?>
            //       if(element2 == 'Processed')
            //       {
            //       element += '<button id="view_btn" type="button" style="margin-right:5px;" title="VIEW" class="btn btn-xs btn-info view" inv_guid="'+row['inv_guid']+'" invoice_number="'+row['invoice_number']+'" supplier_guid="'+row['biller_guid']+'" file_status="'+row['file_status']+'" slip_created_at="'+row['slip_created_at']+'" slip_created_by="'+row['slip_created_by']+'"><i class="fa fa-eye"></i></button>';
            //       }

            //       <?php
            //     }
            //     ?>
            //   }

            //   return element;
       
            // }},
            // { "data" : "sorting" },
          ],
          //dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",  
          dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'Brtip',
          buttons: [
          { extend: 'excelHtml5',
            exportOptions: {columns: [0,1,2,3,4]} /*, footer: true */},

          { extend: 'csvHtml5',  
            exportOptions: {columns: [0,1,2,3,4]} /*, footer: true*/ },
            ],
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            // $(nRow).closest('tr').css({"cursor": "pointer"});
            // $(nRow).attr('refno_val', aData['refno_val']);
          // $(nRow).attr('status', aData['status']);
        },
        "initComplete": function( settings, json ) {
          interval();
        }
        });//close datatable
      }//close success
    });//close ajax
  }//close proposed batch table

  $(document).on('click','#view_btn',function(){

    var invoice_number = $(this).attr('invoice_number');
    var supplier_guid = $(this).attr('supplier_guid');
    var file_status = $(this).attr('file_status');
    var slip_created_at = $(this).attr('slip_created_at');
    var slip_created_by = $(this).attr('slip_created_by');

    if((invoice_number == '') || (invoice_number == 'null') || (invoice_number == null))
    {
      alert('Invalid Invoice Number');
      return
    }

    if((supplier_guid == '') || (supplier_guid == 'null') || (supplier_guid == null))
    {
      alert('Invalid Supplier GUID. Please Refresh Page.');
      return;
    }

    $.ajax({
      url:"<?php echo site_url('B2b_billing_invoice_controller/invoice_view_process') ?>",
      method:"POST",
      data:{invoice_number:invoice_number,supplier_guid:supplier_guid},
      beforeSend:function(){
        $('.view').button('loading');
      },
      success:function(data)
      {
        $('.view').button('reset');
        json = JSON.parse(data);
        //alert(json); die;
        if(json.remark == null)
        {
          remark = '';
        }
        else
        {
          remark = json.remark ;
        }

        if(json.para == '0')
        {
          var modal = $("#medium-modal").modal();

          modal.find('.modal-title').html('View Bank Remmittance');

          methodd = '';

          methodd +='<div class="col-md-12">';

          methodd += '<input type="hidden" class="form-control input-sm" id="view_invoice_number" value="'+invoice_number+'" readonly/>';

          methodd += '<input type="hidden" class="form-control input-sm" id="view_supplier_guid" value="'+supplier_guid+'" readonly/>';

          methodd += '<p>Remark : <b>'+remark+'</b> </p>';

          methodd += '<p>Uploaded At : <b>'+slip_created_at+'</b></p>';

          methodd += '<p>Uploaded By : <b>'+slip_created_by+'</b></p>';

          methodd += '<embed src="'+json.file_path+'" width="100%" height="400px" style="border: none;" id="pdf_view"/>';

          methodd += '</div>';

          <?php if ($_SESSION['user_group_name'] == "SUPER_ADMIN") { ?>
            if(file_status != 'Processed')
            {
              methodd_footer = '<p class="full-width"><span class="pull-left"><a href="'+json.file_path+'" target="_blank"><button type="button" class="btn btn-info">View & Download</button></a></span><span class="pull-right"><input type="button" id="process_btn" class="btn btn-success" value="Confirm"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';
            }
            else
            {
              methodd_footer = '<p class="full-width"><span class="pull-left"><a href="'+json.file_path+'" target="_blank" ><button type="button" class="btn btn-info">View & Download</button></a></span><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';
            }
          <?php } else { ?>
            methodd_footer = '<p class="full-width"><span class="pull-left"><a href="'+json.file_path+'" target="_blank" ><button type="button" class="btn btn-info">View & Download</button></a></span> <span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';
          <?php } ?>

          modal.find('.modal-footer').html(methodd_footer);
          modal.find('.modal-body').html(methodd);
        }
        else
        {
          alert(json.msg);
        }
       
      }//close success
    });//close ajax 

  });//close edit

  $(document).on('click','#process_btn',function(){

    var invoice_number = $('#view_invoice_number').val();
    var supplier_guid = $('#view_supplier_guid').val();

    if((invoice_number == '') || (invoice_number == 'null') || (invoice_number == null))
    {
      alert('Invalid Invoice Number');
      return
    }

    if((supplier_guid == '') || (supplier_guid == 'null') || (supplier_guid == null))
    {
      alert('Invalid Supplier GUID. Please Refresh Page.');
      return;
    }

    confirmation_modal('Are you sure want to Confirm Bank Remmittance?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('B2b_billing_invoice_controller/invoice_update_process') ?>",
        method:"POST",
        data:{invoice_number:invoice_number,supplier_guid:supplier_guid},
        beforeSend:function(){
          //$('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);

            if (json.para1 == '1') {
              $('#alertmodal').modal('hide');
              alert(json.msg);
              $('.btn').button('reset');
              $('#submit_button').remove();

            }else{
              $('.btn').button('reset');
              $('#alertmodal').modal('hide');
              alert(json.msg);
              location.reload();
          }//close else
        }//close success
      });//close ajax 
    });
  });

  $(document).on('click','#upload_btn',function(){

    var invoice_number = $(this).attr('invoice_number');
    var supplier_guid = $(this).attr('supplier_guid');
    
    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Upload Bank Remmittance');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<input type="hidden" class="form-control input-sm" id="invoice_number" value="'+invoice_number+'" readonly/>';

    methodd += '<input type="hidden" class="form-control input-sm" id="supplier_guid" value="'+supplier_guid+'" readonly/>';

    methodd += '<div class="col-md-12"><label>File</label></div><div class="col-md-10"><input id="edit_upload_file" type="file" class="form-control" get_supp_guid ="'+supplier_guid+'"></div><div class="col-md-2"><button type="button" class="btn btn-danger" id="edit_reset_input" >Reset</button></div>';

    methodd += '<div class="col-md-12"><label>Remark</label></div><div class="col-md-12"><textarea id="remark_slip" name="remark_slip" class="form-control" rows="3" cols="50" placeholder="Describe your remark here..."></textarea></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><span id="edit_button_file_form"></span><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);
  });//close edit

  $(document).on('change','#edit_upload_file',function(e){
    
    var edit_fileName = e.target.files[0].name;
    var invoice_number = $('#invoice_number').val();
    var supplier_guid = $('#supplier_guid').val();

    if((invoice_number == '') || (invoice_number == null) || (invoice_number == 'null'))
    {
      alert('Invalid Invoice Number. Please Refresh Page.');
      return;
    }

    if((supplier_guid == '') || (supplier_guid == null) || (supplier_guid == 'null'))
    {
      alert('Invalid Supplier GUID. Please Refresh Page.');
      return;
    }

    if(edit_fileName != '')
    { 
      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" edit_fileName="'+edit_fileName+'" style="margin-right:5px;"> Upload</button>');
    }
    else
    { 
      $('#edit_submit_button').remove();
    }
  });//close upload file

  $(document).on('click','#edit_reset_input',function(){

    $('#edit_upload_file').val('');

    var edit_file = $('#edit_upload_file')[0].files[0];

    if(edit_file === undefined)
    {
      $('#edit_submit_button').remove();
    }
    else
    { 

      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" style="margin-top: 25px;" edit_fileName="'+edit_file+'"> Upload</button>');

    }
  });//close reset_input

  $(document).on('click','#edit_submit_button',function(){

    var edit_file_name = $('#edit_submit_button').attr('edit_fileName');
    var invoice_number = $('#invoice_number').val();
    var supplier_guid = $('#supplier_guid').val();
    var remark_slip = $('#remark_slip').val();

    if((invoice_number == '') || (invoice_number == null) || (invoice_number == 'null'))
    {
      alert('Invalid Invoice Number. Please Refresh Page.');
      return;
    }

    if((supplier_guid == '') || (supplier_guid == null) || (supplier_guid == 'null'))
    {
      alert('Invalid Supplier GUID. Please Refresh Page.');
      return;
    }

    if((edit_file_name == '') || (edit_file_name == null) || (edit_file_name == 'null'))
    {
      alert('Invalid File Select.');
      return;
    }

    confirmation_modal('Are you sure want to Upload Bank Remmittance?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      var formData = new FormData();
      formData.append('file', $('#edit_upload_file')[0].files[0]);
      formData.append('file_name', edit_file_name);
      formData.append('invoice_number', invoice_number);
      formData.append('supplier_guid', supplier_guid);
      formData.append('remark_slip', remark_slip);
      //console.log(formData); die;
      $.ajax({
          url:"<?= site_url('B2b_billing_invoice_controller/invoice_upload_process');?>",
          method:"POST",
          data: formData,
          processData: false, // Don't process the files
          contentType: false, // Set content type to false as jQuery will tell the server its a query string request
          beforeSend : function()
          { 
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            
            if (json.para1 == '1') {
              $('#alertmodal').modal('hide');
              alert(json.msg);
              $('.btn').button('reset');
              $('#upload_file').val('');
              $('#submit_button').remove();

            }else{
              $('.btn').button('reset');
              $('#alertmodal').modal('hide');
              $('#edit_submit_button').hide();
              alert(json.msg);
              location.reload();

          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });//close submit_button

  $(document).on('click','#delete_btn',function(){

    var invoice_number = $(this).attr('invoice_number');
    var supplier_guid = $(this).attr('supplier_guid');

    if((invoice_number == '') || (invoice_number == 'null') || (invoice_number == null))
    {
      alert('Invalid Invoice Number');
      return
    }

    if((supplier_guid == '') || (supplier_guid == 'null') || (supplier_guid == null))
    {
      alert('Invalid Supplier GUID. Please Refresh Page.');
      return;
    }

    confirmation_modal('Are you sure want to Remove Bank Remmittance?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('B2b_billing_invoice_controller/invoice_delete_process') ?>",
        method:"POST",
        data:{invoice_number:invoice_number,supplier_guid:supplier_guid},
        beforeSend:function(){
          //$('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == '1') {
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
            $('#submit_button').remove();

          }else{
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            alert(json.msg);
            location.reload();
          }//close else
        }//close success
      });//close ajax 
    });
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

    // if(redirect_data == 'view_receipt')
    // {
    //   location  = "<?= site_url('b2b_billing_invoice_controller/official_receipt');?>";
    // }
    
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
});
</script>

