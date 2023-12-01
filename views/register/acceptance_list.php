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
          <a class="btn btn-app btn_pending" style="background-color:#99ff99">
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
            <span style="font-size: 12px;color:black;"> Follow Up </span>
          </a> 

          <?php
        }
        ?>
      </div>
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Acceptance Form List <span class="add_branch_list"></span></h3>
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
    'order'       : [ [4 , 'DESC'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    // "sScrollY": "30vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Registration_acceptance/acceptance_table');?>",
        "type": "POST",
    },
    columns: [

             { "data": "acceptance_guid" ,render:function( data, type, row ){

                var element = '';

                element += '<button id="view_content_btn" style="margin-left:5px;" title="Content" class="btn btn-sm btn-info" acceptance_guid="'+row['acceptance_guid']+'" url_data="'+row['url']+'"><i class="fa fa-eye"></i></button>';
                          <?php

                if(in_array('IAVA',$this->session->userdata('module_code')))
                {
                ?>
                  if(row['status'] != 'Accepted')
                  {
                    element += '<button id="approve_btn" style="margin-left:5px;" title="Approve" class="btn btn-sm btn-success" acceptance_guid="'+row['acceptance_guid']+'" ><i class="glyphicon glyphicon-ok"></i></button>';
                  }

                  if(row['status'] != 'Rejected')
                  {
                    element += '<button id="reject_btn" style="margin-left:5px;" title="Reject" class="btn btn-sm btn-danger" acceptance_guid="'+row['acceptance_guid']+'" ><i class="glyphicon glyphicon-remove"></i></button>';
                  }


                <?php
                }
                ?>
                return element;
       
              }},
             { "data": "acc_name" },
             { "data": "supplier_name" },
             { "data": "status" },
             { "data": "created_at" },
             { "data": "created_by" },

             ],
    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('guid', aData['guid']);
      
    },
    "initComplete": function( settings, json ) {
      interval();
    }
  });//close datatable

  $(document).on('click','#view_content_btn',function(){
    var acceptance_guid = $(this).attr('acceptance_guid');
    var url_data = $(this).attr('url_data');
    //alert(url_data); die;

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Preview Acceptance Form');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<embed src="'+url_data+'" width="100%" height="500px" style="border: none;" id="pdf_view"/>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> </span> </p>';

    modal.find('.modal-body').html(methodd);
    modal.find('.modal-footer').html(methodd_footer);

  });

  $(document).on('click','#approve_btn',function(){
    var acceptance_guid = $(this).attr('acceptance_guid');
    //alert(acceptance_guid); die;
    confirmation_modal('Are you sure want to Approve?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Registration_acceptance/term_approval');?>",
        method:"POST",
        data:{acceptance_guid:acceptance_guid},
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
    var acceptance_guid = $(this).attr('acceptance_guid');
    //alert(acceptance_guid); die;
    confirmation_modal('Are you sure want to Reject?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Registration_acceptance/term_rejection');?>",
        method:"POST",
        data:{acceptance_guid:acceptance_guid},
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

    methodd += '<div class="col-md-12"><label>Retailer Name</label><select class="form-control" id="acc_guid" name="acc_guid" required="true" ><option value="" disabled selected>Please Select</option> <?php foreach($acc as $row){?> <option value="<?php echo $row->acc_guid ?>"> <?php echo $row->acc_name ?> </option> <?php } ?></select></div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label><select class="form-control" id="supplier_guid" name="supplier_guid" required="true" ><option value="" disabled selected>Please Select Customer</option></select></div>';

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
    if(type_val != '')
    {
      $.ajax({
      url : "<?php echo site_url('Registration_acceptance/fetch_data'); ?>",
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
      }
      });
    }
    else
    {
       $('#supplier_guid').select2().html('<option value="" disabled>Please select the customer</option>');
    }
  });

  $(document).on('change','#edit_upload_file',function(e){
    
    var edit_fileName = e.target.files[0].name;
    var acc_name = $('#acc_guid').val();
    var supplier_guid = $('#supplier_guid').val();
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
    var supplier_guid = $('#supplier_guid').val();
    var customer_guid = $('#acc_guid').val();

    if((supplier_guid == '') || (supplier_guid == null) || (supplier_guid == 'null'))
    {
      alert('Invalid Data. Please Contact Handsome Developer');
      return;
    }

    if((customer_guid == '') || (customer_guid == null) || (customer_guid == 'null'))
    {
      alert('Invalid Data. Please Contact Handsome Developer');
      return;
    }

    confirmation_modal('Are you sure want to Upload?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#edit_upload_file')[0].files[0]);
      formData.append('file_name', edit_file_name);
      formData.append('supplier_guid_data', supplier_guid);
      formData.append('customer_guid_data', customer_guid);
      //console.log(formData); die;
      $.ajax({
          url:"<?= site_url('Registration_new/upload_acceptance_form');?>",
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
      url:"<?php echo site_url('Registration_acceptance/normal_term_tb') ?>",
      method:"POST",
      //data:{sup_code:sup_code},
      beforeSend:function(){
        //$('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);

        var modal = $("#propose_medium-modal").modal();

        modal.find('.modal-title').html('Acceptance Form');

        methodd = '';

        methodd +='<div class="row"> <div class="col-md-12"> <div class="box box-warning"> <div class="box-body"> <table id="normal_table" class="table table-hover" width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr> <th>Retailer Name</th> <th>Supplier Name</th> <th>Contact</th> <th>Second Contact</th> <th>Email Address</th> <th>Updated At</th>  </tr> </thead> <tbody> </tbody> </table> </div> </div> </div> </div>';

        methodd_footer = '<input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
      
        setTimeout(function(){
          //$('#edit_propose_btn').hide();
          if ($.fn.DataTable.isDataTable('#normal_table')) {
              $('#normal_table').DataTable().destroy();
          }

          $('#normal_table').DataTable({
          "columnDefs": [],
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [5 , 'asc'] ],
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
              { "data": "comp_name"},
              { "data": "comp_contact"},
              { "data": "second_comp_contact"},
              { "data": "comp_email"},
              { "data": "update_at"},
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

  
});
</script>
