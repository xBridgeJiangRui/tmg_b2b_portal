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
    <div class="col-md-12" >
        <?php if(in_array('IAVA',$this->session->userdata('module_code')))
        {
          ?>
          <a class="btn btn-app btn_pending" style="background-color:#ffff33">
            <span class="badge bg-red" style="font-size: 16px">
              <?php echo $query_pending->num_rows() ?> 
            </span>
            <i class="fa fa-address-card-o" ></i> 
            <span style="font-size: 12px;color:black;"> PENDING </span>
          </a> 

          <a class="btn btn-app btn_normal_term" style="background-color:#ffcccc">
            <span class="badge bg-red" style="font-size: 16px">
              <?php echo $query_normal->num_rows() ?> 
            </span>
            <i class="fa fa-address-card-o" ></i> 
            <span style="font-size: 12px;color:black;"> NORMAL TERM SHEET </span>
          </a> 

          <a class="btn btn-app btn_special_term" style="background-color:#ffcccc">
            <span class="badge bg-red" style="font-size: 16px">
              <?php echo $query_special->num_rows() ?> 
            </span>
            <i class="fa fa-address-card-o" ></i> 
            <span style="font-size: 12px;color:black;"> SPECIAL TERM SHEET </span>
          </a> 
          <?php
        }
        ?>
      </div>
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Term Sheet List <span class="add_branch_list"></span></h3>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>
            <button id="upload_term" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Upload Docs</button>
            <?php
            }
            ?>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >

          <table id="term_tb" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead > <!--style="white-space: nowrap;"-->
            <tr>
                <th>Action</th>
                <th>Retailer Name</th>
                <th>Supplier Name</th>
                <th>User Name</th>
                <th>Term Type</th>
                <th>Status</th>
                <th>Created at</th>
                <th>Created by</th>
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
  $('#term_tb').DataTable({
    "columnDefs": [{"targets": 0 ,"orderable": false} ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [6 , 'DESC'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    // "sScrollY": "30vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Registration_upload_doc/term_list_table');?>",
        "type": "POST",
    },
    columns: [

             { "data": "upload_guid" ,render:function( data, type, row ){

                var element = '';
                var element1 = row['status'];

                element += '<button id="view_content_btn" style="margin-left:5px;" title="Content" class="btn btn-sm btn-info" upload_guid="'+row['upload_guid']+'" url_data="'+row['url']+'"><i class="fa fa-eye"></i></button>';
                          <?php

                if(in_array('IAVA',$this->session->userdata('module_code')))
                {
                ?>
                  if(element1 != 'Accepted')
                  {
                    element += '<button id="approve_btn" style="margin-left:5px;" title="Approve" class="btn btn-sm btn-success" upload_guid="'+row['upload_guid']+'" ><i class="glyphicon glyphicon-ok"></i></button>';
                  }
                  
                  element += '<button id="reject_btn" style="margin-left:5px;" title="Reject" class="btn btn-sm btn-danger" upload_guid="'+row['upload_guid']+'" ><i class="glyphicon glyphicon-remove"></i></button>';

                  //element += '<button id="remove_btn" style="margin-left:5px;" title="Remove" class="btn btn-sm btn-warning" upload_guid="'+row['upload_guid']+'" rejected="'+row['rejected']+'" ><i class="glyphicon glyphicon-trash"></i></button>';

                <?php
                }
                ?>
                return element;
       
              }},
             { "data": "acc_name" },
             { "data": "supplier_name" },
             { "data": "user_name" },
             { "data": "term_type" ,render:function( data, type, row ){

                var element = '';

                if(data == 'normal_term')
                {
                  element = 'Term Sheet';
                }
                else
                {
                  element = 'Special Term Sheet';
                }

                return element;
       
              }},
             { "data": "status" },
             { "data": "created_at" },
             { "data": "created_by" },

             ],
    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      $(nRow).attr('guid', aData['guid']);
      
      <?php if(in_array('IAVA',$this->session->userdata('module_code')))
      {
        ?>
        if(aData['status'] == 'Pending' )
        {
          //$(nRow).find('td:eq(0)').css({"background-color":"#ffff33","color":"black"});
          $(nRow).find('td:eq(1)').css({"background-color":"#ffff33","color":"black"});
          $(nRow).find('td:eq(2)').css({"background-color":"#ffff33","color":"black"});
          $(nRow).find('td:eq(3)').css({"background-color":"#ffff33","color":"black"});
          $(nRow).find('td:eq(4)').css({"background-color":"#ffff33","color":"black"});
          $(nRow).find('td:eq(5)').css({"background-color":"#ffff33","color":"black"});
          $(nRow).find('td:eq(6)').css({"background-color":"#ffff33","color":"black"});
          $(nRow).find('td:eq(7)').css({"background-color":"#ffff33","color":"black"});
        }
        else if(aData['status'] == 'Rejected')
        {
          //$(nRow).find('td:eq(0)').css({"background-color":"#ff6b6b","color":"black"});
          $(nRow).find('td:eq(1)').css({"background-color":"#ff6b6b","color":"black"});
          $(nRow).find('td:eq(2)').css({"background-color":"#ff6b6b","color":"black"});
          $(nRow).find('td:eq(3)').css({"background-color":"#ff6b6b","color":"black"});
          $(nRow).find('td:eq(4)').css({"background-color":"#ff6b6b","color":"black"});
          $(nRow).find('td:eq(5)').css({"background-color":"#ff6b6b","color":"black"});
          $(nRow).find('td:eq(6)').css({"background-color":"#ff6b6b","color":"black"});
          $(nRow).find('td:eq(7)').css({"background-color":"#ff6b6b","color":"black"});
        }
      <?php
      }
      ?>
    },
    "initComplete": function( settings, json ) {
      interval();
    }
  });//close datatable

 $(document).on('click','#view_content_btn',function(){
    var upload_guid = $(this).attr('upload_guid');
    var url_data = $(this).attr('url_data');
    //alert(url_data); die;

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Preview Term Sheet');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<embed src="'+url_data+'" width="100%" height="500px" style="border: none;" id="pdf_view"/>';

    methodd += '</div>';


    methodd_footer = '<p class="full-width"><span class="pull-left"> <a href="'+url_data+'" target="_blank" download><button id="dl_pdf" type="button" title="DOWNLOAD" class="btn btn-xm btn-warning" > View & Download </button></a> </span> <span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> </span> </p>';

    modal.find('.modal-body').html(methodd);
    modal.find('.modal-footer').html(methodd_footer);

  });

  $(document).on('click','#approve_btn',function(){
    var upload_guid = $(this).attr('upload_guid');
    //alert(upload_guid); die;
    confirmation_modal('Are you sure want to Approve?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Registration_upload_doc/term_approval');?>",
        method:"POST",
        data:{upload_guid:upload_guid},
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
            setTimeout(function() {
              location.reload();
            }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  }); 

  $(document).on('click','#reject_btn',function(){
    var upload_guid = $(this).attr('upload_guid');
    //alert(upload_guid); die;
    confirmation_modal('Are you sure want to Reject?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Registration_upload_doc/term_rejection');?>",
        method:"POST",
        data:{upload_guid:upload_guid},
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
            setTimeout(function() {
              location.reload();
            }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });

  $(document).on('click','#remove_btn',function(){
    var upload_guid = $(this).attr('upload_guid');
    var rejected = $(this).attr('rejected');

    if(rejected != '1')
    {
      alert('Opps, Please do Rejection first only can remove data.');
      return;
    }
    //alert(rejected); die;
    confirmation_modal('Are you sure want to Remove PDF and data?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Registration_upload_doc/term_remove_file');?>",
        method:"POST",
        data:{upload_guid:upload_guid},
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
            setTimeout(function() {
              location.reload();
            }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });

  $(document).on('click','#upload_term',function(){
    //alert('123'); die;
    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Upload Term Sheet');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<input type="hidden" id="term_user_guid" readonly/>';

    methodd += '<div class="col-md-12"><label>Retailer Name</label><select class="form-control" id="acc_guid" name="acc_guid" required="true" ><option value="" disabled selected>Please Select</option> <?php foreach($acc as $row){?> <option value="<?php echo $row->acc_guid ?>"> <?php echo $row->acc_name ?> </option> <?php } ?></select></div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label><select class="form-control" id="supplier_guid" name="supplier_guid" required="true" ><option value="" disabled selected>Please Select Customer</option></select></div>';

    methodd += '<div class="col-md-12"><label>Term Sheet</label><select class="form-control" id="term_type" name="term_type" required="true" ><option value="" disabled selected>Please Select Supplier</option></select></div>';

    methodd += '<div class="col-md-12"><label>File</label></div><div class="col-md-10"><input id="edit_upload_file" type="file" class="form-control" accept=".pdf"></div>';

    methodd += '<div class="col-md-2"><button type="button" class="btn btn-danger" id="edit_reset_input" >Reset</button></div>';

    methodd += '</div>';

    methodd_footer = '';

    methodd_footer += '<p class="full-width"><span class="pull-right"><span id="edit_button_file_form"></span>';

    methodd_footer += '<input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

  });

  $(document).on('change','#acc_guid',function(){
    var type_val = $('#acc_guid').val();
    $('#term_user_guid').val('');
    if(type_val != '')
    {
      $.ajax({
      url : "<?php echo site_url('Registration_upload_doc/fetch_data'); ?>",
      method:"POST",
      data:{type_val:type_val},
      success:function(result)
      {
        json = JSON.parse(result); 
        vendor = '';
        vendor += '<option value="" disabled selected>--Please Select Supplier--</option>';
        Object.keys(json['vendor']).forEach(function(key) {

          vendor += '<option value="'+json['vendor'][key]['supplier_guid']+'" >'+json['vendor'][key]['supplier_name']+' -('+json['vendor'][key]['reg_no']+')</option>';
        });

        $('#supplier_guid').select2().html(vendor);
        $('#term_type').html('<option value="" disabled selected>Please Select Supplier</option>');
      }
      });
    }
    else
    {
       $('#supplier_guid').select2().html('<option value="" disabled>Please select the customer</option>');
    }
  });

  $(document).on('change','#supplier_guid',function(){
    var type_val = $('#supplier_guid').val();
    var acc_guid = $('#acc_guid').val();

    if(type_val != '')
    {
      $.ajax({
      url : "<?php echo site_url('Registration_upload_doc/fetch_term'); ?>",
      method:"POST",
      data:{type_val:type_val,acc_guid:acc_guid},
      success:function(result)
      {
        json = JSON.parse(result); 
        vendor = '';
        vendor += '<option value="" disabled selected>--Please Select Term--</option>';
        vendor += json.select_option;

        $('#term_type').html(vendor);
        $('#term_user_guid').val(json.user_guid);
      }
      });
    }
    else
    {
      $('#term_type').html('<option value="" disabled>Please select the supplier</option>');
    }
  });

  $(document).on('change','#edit_upload_file',function(e){
    
    var edit_fileName = e.target.files[0].name;
    var acc_name = $('#acc_guid').val();
    var supplier_guid = $('#supplier_guid').val();
    var term_sheet = $('#term_type').val();
    //alert(term_sheet); die;
    if((acc_name == '') || (acc_name == null) || (acc_name == 'null'))
    {
      alert('Please Select Customer');
      return;
    }

    if((supplier_guid == '') || (supplier_guid == null) || (supplier_guid == 'null'))
    {
      alert('Please Select Supplier');
      return;
    }

    if((term_sheet == '') || (term_sheet == null) || (term_sheet == 'null'))
    {
      alert('Please Select Term Sheet Type');
      return;
    }

    if(term_sheet == 'all_uploaded')
    {
      alert('Document Already Uploaded.');
      return;
    }

    if(edit_fileName != '')
    { 
      if(term_sheet != 'all_uploaded')
      {
        $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" edit_fileName="'+edit_fileName+'" style="margin-right:5px;"> Upload</button>');
      }
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
    var term_user_guid = $('#term_user_guid').val();
    var supplier_guid = $('#supplier_guid').val();
    var customer_guid = $('#acc_guid').val();
    var term_type = $('#term_type').val();

    if((term_type == '') || (term_type == null) || (term_type == 'null'))
    {
      alert('Please Select Term Sheet');
      return;
    }

    confirmation_modal('Are you sure want to Upload?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#edit_upload_file')[0].files[0]);
      formData.append('file_name', edit_file_name);
      formData.append('term_user_guid', term_user_guid);
      formData.append('supplier_guid', supplier_guid);
      formData.append('customer_guid', customer_guid);
      formData.append('term_type', term_type);
      //console.log(formData); die;
      $.ajax({
          url:"<?= site_url('Dashboard/upload_term_docs');?>",
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

  $(document).on('click','.btn_normal_term',function(){
    $.ajax({
      url:"<?php echo site_url('Registration_upload_doc/normal_term_tb') ?>",
      method:"POST",
      //data:{sup_code:sup_code},
      beforeSend:function(){
        //$('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);

        var modal = $("#propose_medium-modal").modal();

        modal.find('.modal-title').html('Normal Term Sheet');

        methodd = '';

        methodd +='<div class="row"> <div class="col-md-12"> <div class="box box-warning"> <div class="box-body"> <table id="normal_table" class="table table-hover" width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr> <th>Retailer Name</th> <th>Supplier Name</th> <th>Memo Type</th> <th>Updated At</th> <th>Action</th> </tr> </thead> <tbody> </tbody> </table> </div> </div> </div> </div>';

        methodd_footer = '<input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
      
        setTimeout(function(){
          //$('#edit_propose_btn').hide();
          if ($.fn.DataTable.isDataTable('#normal_table')) {
              $('#normal_table').DataTable().destroy();
          }

          $('#normal_table').DataTable({
          "columnDefs": [ {targets: 4 ,orderable: false} ],
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [1 , 'asc'] ],
          'info'        : true,
          'autoWidth'   : true,
          "bPaginate": true, 
          "bFilter": true, 
          "sScrollY": "30vh", 
          "sScrollX": "100%", 
          "sScrollXInner": "100%", 
          "bScrollCollapse": true,
            data: json['query_normal'],
            columns: [
              { "data": "acc_name"},
              { "data": "supplier_name"},
              { "data": "memo_type"},
              { "data" : "update_at"},
              { "data" : "setting_guid",render:function( data, type, row ){

                var element = '';

                if((data == null) || (data == '') || (data == 'null'))
                {
                  element += '<button id="action_btn_normal" style="margin-left:5px;" title="BLOCK" class="btn btn-sm btn-primary setting_btn" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" supplier_name="'+row['supplier_name']+'" memo_type="'+row['memo_type']+'" setting_guid="'+row['setting_guid']+'" action_type="add" ><i class="glyphicon glyphicon-ok"></i></button>';
                }
                else
                {
                  element += '<button id="action_btn_normal" style="margin-left:5px;" title="NO BLOCK" class="btn btn-sm btn-danger setting_btn" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" supplier_name="'+row['supplier_name']+'" memo_type="'+row['memo_type']+'" setting_guid="'+row['setting_guid']+'" action_type="remove"><i class="fa fa-ban"></i></button>';
                }


                return element;
       
              }},
              ],
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",  
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              //$(nRow).closest('tr').css({"cursor": "pointer"});
            // $(nRow).attr('status', aData['status']);
          },
          "initComplete": function( settings, json ) {
            interval();
          }
          });//close datatable
        },300);

        //$('.btn').button('reset');
 
      }//close success
    });//close ajax
  });//close reset_input

  $(document).on('click','.btn_special_term',function(){
    $.ajax({
      url:"<?php echo site_url('Registration_upload_doc/special_term_tb') ?>",
      method:"POST",
      //data:{sup_code:sup_code},
      beforeSend:function(){
        //$('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);

        var modal = $("#propose_medium-modal").modal();

        modal.find('.modal-title').html('Special Term Sheet');

        methodd = '';

        methodd +='<div class="row"> <div class="col-md-12"> <div class="box box-warning"> <div class="box-body"> <table id="special_table" class="table table-hover" width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr> <th>Retailer Name</th> <th>Supplier Name</th> <th>Template Name</th> <th>Document Count</th> <th>Updated At</th> <th>Action</th> </tr> </thead> <tbody> </tbody> </table> </div> </div> </div> </div>';

        methodd_footer = '<input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
      
        setTimeout(function(){
          //$('#edit_propose_btn').hide();
          if ($.fn.DataTable.isDataTable('#special_table')) {
              $('#special_table').DataTable().destroy();
          }

          $('#special_table').DataTable({
          "columnDefs": [{targets: 5 ,orderable: false} ],
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [1 , 'asc'] ],
          'info'        : true,
          'autoWidth'   : true,
          "bPaginate": true, 
          "bFilter": true, 
          "sScrollY": "30vh", 
          "sScrollX": "100%", 
          "sScrollXInner": "100%", 
          "bScrollCollapse": true,
            data: json['query_special'],
            columns: [
              { "data": "acc_name"},
              { "data": "supplier_name"},
              { "data": "template_name"},
              { "data": "counting"},
              { "data" : "update_at"},
              { "data" : "setting_guid",render:function( data, type, row ){

                var element = '';

                if((data == null) || (data == '') || (data == 'null'))
                {
                  element += '<button id="action_btn_special" style="margin-left:5px;" title="BLOCK" class="btn btn-sm btn-primary setting_btn" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" supplier_name="'+row['supplier_name']+'" memo_type="'+row['memo_type']+'" setting_guid="'+row['setting_guid']+'" action_type="add"><i class="glyphicon glyphicon-ok"></i></button>';
                }
                else
                {
                  element += '<button id="action_btn_special" style="margin-left:5px;" title="NO BLOCK" class="btn btn-sm btn-danger setting_btn" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" supplier_name="'+row['supplier_name']+'" memo_type="'+row['memo_type']+'" setting_guid="'+row['setting_guid']+'" action_type="remove"><i class="fa fa-ban"></i></button>';
                }

                return element;
       
              }},
              ],
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",  
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              //$(nRow).closest('tr').css({"cursor": "pointer"});
            // $(nRow).attr('status', aData['status']);
          },
          "initComplete": function( settings, json ) {
            interval();
          }
          });//close datatable
        },300);
      }//close success
    });//close ajax
  });//close reset_input

  $(document).on('click','.setting_btn',function(){

    var setting_guid = $(this).attr('setting_guid');
    var supplier_name = $(this).attr('supplier_name');
    var customer_guid = $(this).attr('customer_guid');
    var supplier_guid = $(this).attr('supplier_guid');
    var memo_type = $(this).attr('memo_type');
    var action_type = $(this).attr('action_type');

    //alert(setting_guid); die;
    if((customer_guid == '') || (customer_guid == 'null') || (customer_guid == null))
    {
      alert('Invalid Retailer Guid. Please Refresh Page');
      return;
    }

    if((supplier_guid == '') || (supplier_guid == 'null') || (supplier_guid == null))
    {
      alert('Invalid Supplier Guid. Please Refresh Page');
      return;
    }

    if((memo_type == '') || (memo_type == 'null') || (memo_type == null))
    {
      alert('Invalid Memo Type Guid. Please Refresh Page');
      return;
    }

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Choose Status');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control " id="confirm_setting_guid" name="confirm_setting_guid" value="'+setting_guid+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control " id="confirm_supplier_name" name="confirm_supplier_name" value="'+supplier_name+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control " id="confirm_customer_guid" name="confirm_customer_guid" value="'+customer_guid+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control " id="confirm_supplier_guid" name="confirm_supplier_guid" value="'+supplier_guid+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control " id="confirm_memo_type" name="confirm_memo_type" value="'+memo_type+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control " id="confirm_action_type" name="confirm_action_type" value="'+action_type+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><label>Action Status</label><select id="confirm_action_status" name="confirm_action_status" class="form-control" ><option value="need_check">Need Validate - Wait for Approval Term</option> <option value="no_check">No Validate - No checking with the due date</option> <option value="remove">Remove Settings</option> </select></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="setting_btn_submit" class="btn btn-primary"> Update </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      //$('#edit_day').val(day_limit);
      //$('.select2').select2();
    },300);

  });//close modal edit

  $(document).on('click','#setting_btn_submit',function(){
    
    var setting_guid = $('#confirm_setting_guid').val();
    var supplier_name = $('#confirm_supplier_name').val();
    var customer_guid = $('#confirm_customer_guid').val();
    var supplier_guid = $('#confirm_supplier_guid').val();
    var memo_type = $('#confirm_memo_type').val();
    var confirm_value = 'Are you sure want to set back normal setting for <b>'+supplier_name+'</b>?';
    var action_type = $('#confirm_action_type').val();
    var action_status = $('#confirm_action_status').val();

    //alert(setting_guid); die;
    if((setting_guid == '') || (setting_guid == 'null') || (setting_guid == null))
    {
      setting_guid = 'nodata';
      confirm_value = 'Are you sure want to set no <b>Submission Due Date</b> for <b>'+supplier_name+'</b>?';
    }

    if((customer_guid == '') || (customer_guid == 'null') || (customer_guid == null))
    {
      alert('Invalid Retailer Guid. Please Refresh Page');
      return;
    }

    if((supplier_guid == '') || (supplier_guid == 'null') || (supplier_guid == null))
    {
      alert('Invalid Supplier Guid. Please Refresh Page');
      return;
    }

    if((memo_type == '') || (memo_type == 'null') || (memo_type == null))
    {
      alert('Invalid Memo Type Guid. Please Refresh Page');
      return;
    }

    if(action_status == 'need_check')
    {
      confirm_value = 'Are you sure want to set checking <b>Approval</b> for <b>'+supplier_name+'</b>?';
    }

    //alert(rejected); die;
    confirmation_modal(confirm_value);
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Registration_upload_doc/setting_status');?>",
        method:"POST",
        data:{setting_guid:setting_guid,customer_guid:customer_guid,supplier_guid:supplier_guid,memo_type:memo_type,action_type:action_type,action_status:action_status},
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
            setTimeout(function() {
              location.reload();
            }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });
  
});
</script>
