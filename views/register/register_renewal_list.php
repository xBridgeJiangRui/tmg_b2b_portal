<style>
.content-wrapper{
  min-height: 950px !important; 
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
              <div class="col-md-2"><b>Retailer Name</b></div>
              <div class="col-md-4">
                <select id="select_retailer_guid" class="form-control select2">
                  <option value="" selected="">-Select Retailer-</option>
                  <?php foreach ($get_customer->result() as $row) { ?>
                      <option value="<?php echo $row->acc_guid ?>">
                        <?php echo $row->acc_name; ?> </option>
                  <?php } ?>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Supplier Name</b></div>
              <div class="col-md-4">
                <select id="select_supplier_guid" class="form-control select2">
                  <option value="" selected="">-Select Supplier-</option>
                  <?php foreach ($get_supplier->result() as $row) { ?>
                      <option value="<?php echo $row->supplier_guid ?>">
                        <?php echo $row->supplier_name; ?> </option>
                  <?php } ?>
                </select>
              </div>
              <div class="clearfix"></div><br>
              
              <div class="col-md-2"><b>Status</b></div>
              <div class="col-md-4">
                <select name="status_type" id="status_type" class="form-control">
                  <option value=""  selected>-Select Status-</option>
                  <option value="0">Pending</option> 
                  <option value="1">Agree</option> 
                  <!-- <option value="2">Reject</option>  -->
                  <option value="3">Uploaded</option> 
                  <option value="4">PDF Approved</option> 
                </select>
              </div>
              <div class="clearfix"></div><br>
          
              <div class="col-md-12">

                <button type="button" id="search_data" class="btn btn-primary" ><i class=""></i> Search </button>
                
              </div>

            </div>
          </div>
        </div>
        <!-- body -->
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Renewal List <span class="add_branch_list"></span></h3>
          <div class="box-tools pull-right">
            <!-- <button id="upload_btn" style="margin-left:5px;" title="Upload" class="btn btn-xs btn-primary" ><i class="fa fa-cloud-upload"></i> Upload</button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >

          <table id="renewal_tb" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead style="white-space: nowrap;" > <!--style="white-space: nowrap;"-->
            <tr>
                <th>Action</th>
                <th>Retailer Name</th>
                <th>Supplier Name</th>
                <th>Old Template Name</th>
                <th>Old Start Date</th>
                <th>Old End Date</th>
                <th>Template Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Recevied By</th>
                <th>CC Email</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Created By</th>
                <th>Updated At</th>
                <th>Updated By</th>
                <th>Send At</th>

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
$(document).ready(function() {
  $('#renewal_tb').DataTable({
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
                "zeroRecords": "<?php echo '<b>No Record Found. Please Select Dcoument Type to view data.</b>'; ?>",
      },
      "pagingType": "simple_numbers",
  });
  $('.remove_padding_right').css({'text-align':'left'});
  $("div.remove_padding").css({"text-align":"left"});

  $(document).on('click','#search_data',function(){

    select_supplier_guid = $('#select_supplier_guid').val();
    select_retailer_guid = $('#select_retailer_guid').val();
    status_type = $('#status_type').val();

    renewal_table(select_supplier_guid,select_retailer_guid,status_type);

  });//close search button

  renewal_table = function(select_supplier_guid,select_retailer_guid,status_type)
  { 
    $.ajax({
      url : "<?php echo site_url('Registration_renewal/renewal_table');?>",
      method: "POST",
      data:{select_supplier_guid:select_supplier_guid,select_retailer_guid:select_retailer_guid,status_type:status_type},
      beforeSend : function() {
          $('.btn').button('loading');
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success : function(data)
      {  
        json = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#renewal_tb')) {
            $('#renewal_tb').DataTable().destroy();
        }

        $('#renewal_tb').DataTable({
        "columnDefs": [
          {"targets": [0] ,"orderable": false},],
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [ [12 , 'DESC'] ],
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true, 
        "bFilter": true, 
        "sScrollY": "60vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
          data: json['query_data'],
          columns: [
              { "data": "guid" ,render:function( data, type, row ){

                var element = '';
                var css_style = '';
                <?php

                if(in_array('IAVA',$this->session->userdata('module_code')))
                {
                ?>
                  if(row['is_confirm'] != '4')
                  {
                    element += '<button id="approve_btn" style="margin-left:5px;margin-bottom:5px;" title="Edit" class="btn btn-xs btn-info btn_form" guid="'+row['guid']+'" is_confirm="'+row['is_confirm']+'" ><i class="fa fa-edit"></i></button>';
                  }

                  element += '<button id="preview_btn" style="margin-left:5px;margin-bottom:5px;" title="Preview" class="btn btn-xs bg-purple" guid="'+row['guid']+'" supplier_name="'+row['supplier_name']+'" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" cross_guid="'+row['cross_guid']+'" file_name="'+row['file_name']+'" ><i class="fa fa-file"></i></button>';

                  if(row['url_path'] != '' && row['url_path'] != 'null' && row['url_path'] != null)
                  {
                    if(row['is_confirm'] != '1' && row['is_confirm'] != '3' && row['is_confirm'] != '4')
                    {
                      css_style = 'margin-top:5px;';
                    }
                    
                    element += '<button id="pdf_btn" style="margin-left:5px;margin-bottom:5px;" title="View" class="btn btn-xs btn-warning" guid="'+row['guid']+'" rejected="'+row['rejected']+'" url_path="'+row['url_path']+'" is_confirm="'+row['is_confirm']+'" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" ><i class="glyphicon glyphicon-open-file"></i></button>';
                  }

                  if(row['is_confirm'] == '1')
                  {
                    element += '<button id="upload_btn" style="margin-left:5px;margin-bottom:5px;" title="Upload" class="btn btn-xs btn-success" guid="'+row['guid']+'" rejected="'+row['rejected']+'" is_confirm="'+row['is_confirm']+'" customer_guid ="'+row['customer_guid']+'" supplier_guid ="'+row['supplier_guid']+'" ><i class="glyphicon glyphicon-open"></i></button>';
                  }

                  if(row['is_confirm'] != '1' && row['is_confirm'] != '2' && row['is_confirm'] != '3' && row['is_confirm'] != '4')
                  {
                    element += '<button id="remove_btn" style="margin-left:5px;margin-bottom:5px;" title="Delete" class="btn btn-xs btn-danger" guid="'+row['guid']+'" rejected="'+row['rejected']+'" is_confirm="'+row['is_confirm']+'" ><i class="fa fa-remove"></i></button>';
                  }

                <?php
                }
                ?>
                return element;

              }},
              { "data": "acc_name" },
              { "data": "supplier_name" },
              { "data": "old_template_name"},
              { "data": "renewal_old_start_at" },
              { "data": "renewal_old_end_at" },
              { "data": "template_name" },
              { "data": "renewal_start_at" },
              { "data": "renewal_end_at" },
              { "data": "email_add" },
              { "data": "cc_email_add" ,render: function (data, type , row){

                var element = '';
                var element1 = data.split(",").join("<br/>");

                element += '<span>'+element1+'</span>';

                return element;

              }},
              { "data": "status" ,render:function( data, type, row ){

                var element = '';
                var element1 = row['rejected'];

                if(element1 == '1')
                {
                element = data + ' PDF File Rejected';
                }
                else
                {
                element = data;
                }

                return element;

              }},
              { "data": "created_at" },
              { "data": "created_by" },
              { "data": "updated_at" },
              { "data": "updated_by" },
              { "data": "send_at" },
          ],
          dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>Brtip",  
          buttons: [
            'excel'
          ],
          "language": {
            "lengthMenu": "Show _MENU_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "zeroRecords": "<?php echo '<b>No Record Found.</b>'; ?>",
          },
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

  $(document).on('click', '.btn_form', function(event){

    var renewal_guid = $(this).attr('guid');
    var is_confirm = $(this).attr('is_confirm');

    if(is_confirm == '1')
    {
        var value1 = 'disabled';
    }
    else if(is_confirm == '2')
    {
        var value2 = 'disabled';
    }
    else if(is_confirm == '0')
    {
        var value3 = 'disabled';
    }

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Edit Renewal Status');

    methodd = '';

    methodd += '<input type="hidden" class="form-control input-sm" id="renewal_guid" value="'+renewal_guid+'" readonly/>';

    methodd += '<div class="col-md-12"><label>Status</label> <select class="form-control select2" name="modal_flag_status" id="modal_flag_status"> <option value="" disabled selected>-SELECTION-</option> <option value="0" '+value3+'>Pending</option> <option value="1" '+value1+'>Agree</option> </select> <br/></div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="flag_btn_submit" class="btn btn-primary"> Update </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
        $('.select2').select2();
    },300);

  });//close update button

  $(document).on('click', '#flag_btn_submit', function(event){

    var renewal_guid = $('#renewal_guid').val();
    var status = $('#modal_flag_status').val();

    if(renewal_guid == '' || renewal_guid == null || renewal_guid == 'null')
    {
        alert('Invalid GUID.Please Contact Handsome Developer.');
        return;
    }

    if(status == '1')
    {
        var modal = 'Agree';
    }
    else if(status == '2')
    {
        var modal = 'Reject';
    }
    else if(status == '0')
    {
        var modal = 'Reset';
    }
    else
    {
        alert('Invalid Process. Please select the status.');
        return;
    }

    confirmation_modal("Are you sure want to "+modal+" ?");

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Registration_renewal/update_status') ?>",
            method:"POST",
            data:{renewal_guid:renewal_guid,status:status,modal:modal},
            beforeSend:function(){
            $('.btn').button('loading');
            },
            success:function(data)
            {
            json = JSON.parse(data);
            if (json.para1 == 'false') {
                $('#alertmodal').modal('hide');
                $('.btn').button('reset');
                alert(json.msg);
                renewal_table(select_supplier_guid,select_retailer_guid,status_type);
            }else{
                $('.btn').button('reset');
                $('#alertmodal').modal('hide');
                alert(json.msg);
                renewal_table(select_supplier_guid,select_retailer_guid,status_type);
                //renewal_table(select_supplier_guid,select_retailer_guid,status_type);
            }//close else
            }//close success
        });//close ajax 
    });//close document yes click
  });//close update button

  $(document).on('click', '#remove_btn', function(event){

    var renewal_guid = $(this).attr('guid');

    if(renewal_guid == '' || renewal_guid == null || renewal_guid == 'null')
    {
        alert('Invalid GUID.Please Contact Handsome Developer.');
        return;
    }

    confirmation_modal("Are you sure want to Delete?");

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Registration_renewal/remove_renewal_data') ?>",
            method:"POST",
            data:{renewal_guid:renewal_guid},
            beforeSend:function(){
            $('.btn').button('loading');
            },
            success:function(data)
            {
            json = JSON.parse(data);
            if (json.para1 == 'false') {
                $('#alertmodal').modal('hide');
                $('.btn').button('reset');
                alert(json.msg);
                renewal_table(select_supplier_guid,select_retailer_guid,status_type);
            }else{
                $('.btn').button('reset');
                $('#alertmodal').modal('hide');
                alert(json.msg);
                renewal_table(select_supplier_guid,select_retailer_guid,status_type);
            }//close else
            }//close success
        });//close ajax 
    });//close document yes click
  });//close update button
  
  $(document).on('click','#pdf_btn',function(e){
    var url_data = $(this).attr('url_path');
    var guid = $(this).attr('guid');
    var rejected = $(this).attr('rejected');
    var is_confirm = $(this).attr('is_confirm');
    var virtual_path_sign = "<?php echo $virtual_path_sign; ?>";
    var customer_guid = $(this).attr('customer_guid');
    var supplier_guid = $(this).attr('supplier_guid');
    var file_name = $(this).attr('file_name');
    var file_url = virtual_path_sign+customer_guid+'/'+supplier_guid+'/'+guid+'/'+url_data ;
    var status = '';

    if(rejected == '1')
    {
      status = '(Rejected)';
    }

    //alert(url_data); die;
    if((url_data == null) || (url_data == 'null') || (url_data == ''))
    {
      alert('Still not yet upload Document');
      return;
    }

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Sign Renewal PDF '+status);

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<input type="hidden" class="form-control input-sm" id="pdf_renewal_guid" value="'+guid+'" readonly/>';

    methodd += '<embed src="'+file_url+'" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';

    methodd += '</div>';

    if(rejected == '1' )
    {
      methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> <button type="button" id="okay_pdf" class="btn btn-success"> Correct </button> </span> </p>';
    }
    else if(is_confirm == '4' )
    {
      methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> <button type="button" id="reject_pdf" class="btn btn-danger"> Reject </button> </span> </p>';
    }
    else
    {
      methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> <button type="button" id="reject_pdf" class="btn btn-danger"> Reject </button> <button type="button" id="okay_pdf" class="btn btn-success"> Correct </button>  </span> </p>';
    }

    modal.find('.modal-body').html(methodd);
    modal.find('.modal-footer').html(methodd_footer);
  });//close upload file

  $(document).on('click', '#reject_pdf', function(event){

    var pdf_renewal_guid = $('#pdf_renewal_guid').val();

    if(pdf_renewal_guid == '' || pdf_renewal_guid == null || pdf_renewal_guid == 'null')
    {
        alert('Invalid GUID.Please Contact Handsome Developer.');
        return;
    }

    confirmation_modal("Are you sure want to Reject PDF?");

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Registration_renewal/reject_pdf_data') ?>",
            method:"POST",
            data:{pdf_renewal_guid:pdf_renewal_guid},
            beforeSend:function(){
            $('.btn').button('loading');
            },
            success:function(data)
            {
            json = JSON.parse(data);
            if (json.para1 == 'false') {
                $('#alertmodal').modal('hide');
                $('.btn').button('reset');
                alert(json.msg);
                renewal_table(select_supplier_guid,select_retailer_guid,status_type);
            }else{
                $('.btn').button('reset');
                $('#alertmodal').modal('hide');
                alert(json.msg);
                renewal_table(select_supplier_guid,select_retailer_guid,status_type);
            }//close else
            }//close success
        });//close ajax 
    });//close document yes click
  });//close update button

  $(document).on('click', '#okay_pdf', function(event){

    var pdf_renewal_guid = $('#pdf_renewal_guid').val();

    if(pdf_renewal_guid == '' || pdf_renewal_guid == null || pdf_renewal_guid == 'null')
    {
        alert('Invalid GUID.Please Contact Handsome Developer.');
        return;
    }

    confirmation_modal("Are you sure want to Accept PDF?");

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Registration_renewal/okay_pdf_data') ?>",
            method:"POST",
            data:{pdf_renewal_guid:pdf_renewal_guid},
            beforeSend:function(){
            $('.btn').button('loading');
            },
            success:function(data)
            {
            json = JSON.parse(data);
            if (json.para1 == 'false') {
                $('#alertmodal').modal('hide');
                $('.btn').button('reset');
                alert(json.msg);
                renewal_table(select_supplier_guid,select_retailer_guid,status_type);
            }else{
                $('.btn').button('reset');
                $('#alertmodal').modal('hide');
                alert(json.msg);
                renewal_table(select_supplier_guid,select_retailer_guid,status_type);
            }//close else
            }//close success
        });//close ajax 
    });//close document yes click
  });//close update button

  $(document).on('click', '#upload_btn', function(event){

    var renewal_guid = $(this).attr('guid');
    var customer_guid = $(this).attr('customer_guid');
    var supplier_guid = $(this).attr('supplier_guid');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Upload Renewal PDF');

    methodd = '';

    methodd += '<input type="hidden" class="form-control input-sm" id="renewal_guid" value="'+renewal_guid+'" readonly/>';

    methodd += '<input type="hidden" class="form-control input-sm" id="customer_guid" value="'+customer_guid+'" readonly/>';

    methodd += '<input type="hidden" class="form-control input-sm" id="supplier_guid" value="'+supplier_guid+'" readonly/>';

    methodd += '</div><div class="col-md-8"><input id="edit_upload_renewal" type="file" class="form-control" accept=".pdf"></div>';

    methodd += '<div class="col-md-4"><span id="edit_button_renewal"></span><button type="button" class="btn btn-danger" id="edit_reset_renewal" >Reset</button></div><div class="clearfix"></div><br>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
        $('.select2').select2();
    },300);

  });//close update button

  $(document).on('change','#edit_upload_renewal',function(e){
    
    var edit_fileName = e.target.files[0].name;
    if(edit_fileName != '')
    { 
      $('#edit_button_renewal').html('<button type="button" id="edit_submit_renewal_button" class="btn btn-primary" edit_fileName="'+edit_fileName+'" style="margin-right:5px;" > Upload</button>');
    }
    else
    { 
      //$('#button_file_form').remove();
      $('#edit_submit_renewal_button').remove();
    }
  });//close upload file

  $(document).on('click','#edit_reset_renewal',function(){

    $('#edit_upload_renewal').val('');

    var edit_file = $('#edit_upload_renewal')[0].files[0];

    if(edit_file === undefined)
    {
      $('#edit_submit_renewal_button').remove();
    }
    else
    { 
      $('#edit_button_renewal').html('<button type="button" id="edit_submit_renewal_button" class="btn btn-primary" style="margin-top: 25px;" edit_fileName="'+edit_file+'" term_type="normal_term"> Upload</button>');
    }
  });//close reset_input

  $(document).on('click','#edit_submit_renewal_button',function(){

    var edit_file_name = $('#edit_submit_renewal_button').attr('edit_fileName');
    var renewal_guid = $('#renewal_guid').val();
    var customer_guid = $('#customer_guid').val();
    var supplier_guid = $('#supplier_guid').val();
    //alert('Function Not Ready Yo'); die;
    if((renewal_guid == '') || (renewal_guid == null) || (renewal_guid == 'null'))
    {
      alert('Invalid Data GUID. Please Contact Support.');
      return;
    }

    if((customer_guid == '') || (customer_guid == null) || (customer_guid == 'null'))
    {
      alert('Invalid Data Retailer. Please Contact Support.');
      return;
    }

    if((supplier_guid == '') || (supplier_guid == null) || (supplier_guid == 'null'))
    {
      alert('Invalid Data Supplier. Please Contact Support.');
      return;
    }

    confirmation_modal('Are you sure want to Upload?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#edit_upload_renewal')[0].files[0]);
      formData.append('file_name', edit_file_name);
      formData.append('renewal_guid', renewal_guid);
      formData.append('customer_guid', customer_guid);
      formData.append('supplier_guid', supplier_guid);
      //console.log(formData); die;
      $.ajax({
          url:"<?= site_url('Registration_renewal/sign_appendix_upload_process');?>",
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
            
            if (json.para1 == 'false') {
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
              setTimeout(function() { 
                renewal_table(select_supplier_guid,select_retailer_guid,status_type);
                //registration_modal();
              }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });//close submit_button

  $(document).on('click', '#preview_btn', function(event){

    var supplier_name = $(this).attr('supplier_name');
    var link = $(this).attr('guid');
    var virtual_path = "<?php echo $virtual_path; ?>";
    var customer_guid = $(this).attr('customer_guid');
    var supplier_guid = $(this).attr('supplier_guid');
    var cross_guid = $(this).attr('cross_guid'); // renewal guid
    var file_name = $(this).attr('file_name');
    // alert(link); die;

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Preview Form');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<label>Renewal Form</label>';

    methodd += '<embed src="<?php echo site_url('Invoice/view_renewal_report?link=');?>'+link+'&supplier_name='+supplier_name+'" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';

    if(file_name != '' && file_name != 'null' )
    {
      var file_url = virtual_path+customer_guid+'/'+supplier_guid+'/'+cross_guid+'/'+file_name ;

      methodd += '<label>Appendix Form</label>';

      methodd += '<embed src="'+file_url+'" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';
    }

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> </span> </p>';

    modal.find('.modal-body').html(methodd);
    modal.find('.modal-footer').html(methodd_footer);

    // setTimeout(function () { 
    //     alert('Please Download / Sign and send to us.');
    // }, 300);

  });//close update button

});
</script>
