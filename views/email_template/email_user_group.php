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

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    display: flex;
    white-space: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar {
  width: 10px;
  height: 5px;
  background-color: #F5F5F5;           /* width of the entire scrollbar */
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
  border-radius: 10px;
  background-color: #F5F5F5;       /* color of the tracking area */
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
  background-color: #B7BABF; /* color of the scrolling */
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: black;
}
</style>
<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Email User Group</h3><br>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>
            
            <button id="create_btn" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Create Group</button>

            <?php
            }
            ?>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
          </div>
        </div>
          <div class="box-body">
            <table class="table table-bordered table-striped dataTable" id="list_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;">
                <tr>
                  <th>Action</th>
                  <th>Retailer Name</th>
                  <th>Type</th> 
                  <th>Mail Group</th>
                  <th>Description</th>
                  <th>Is Active</th>
                  <th>Created At</th>
                  <th>Created By</th>
                  <th>Updated At</th>
                  <th>Updated By</th>
                  
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
          <h3 class="box-title">Email Details</h3><br>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>
                <span id="append_btn"></span>
            <?php
            }
            ?>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
          </div>
        </div>
          <div class="box-body">
            <table class="table table-bordered table-striped dataTable" id="list_table_child"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;">
                <tr>
                  <th><input type="checkbox" class="form-checkbox" id="checkall_input_table" table_id="list_table_child"/></th>
                  <!-- <th>Action</th> -->
                  <th>Retailer Name</th>
                  <th>Supplier Name</th>
                  <!-- <th>User Name</th> -->
                  <th>Email Address</th>
                  <!-- <th>Category</th>  -->
                  <th>Is Active</th>
                  <th>Created At</th>
                  <th>Created By</th>
                  <th>Updated At</th>
                  <th>Updated By</th>
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

  $('#list_table_child').DataTable({
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
                "zeroRecords": "<?php echo '<b>No Record Found. Please Select Main Table to view data.</b>'; ?>",
      },
      "pagingType": "simple_numbers",
  });
  $('.remove_padding_right').css({'text-align':'left'});
  $("div.remove_padding").css({"text-align":"left"});

  setTimeout(function(){
    $('#medium-modal').attr({"data-backdrop":"static","data-keyboard":"false"}); //to remove clicking outside modal for closing
  },300);

  $('#list_table').DataTable({
    "columnDefs": [
    {"targets": 0 ,"orderable": false},
    ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : true,
    'ordering'    : true, 
    'order'       : [  [6 , 'asc'] ],
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
        "url": "<?php echo site_url('Blast_email_user/list_tb');?>",
        "type": "POST",
    },
    columns: [
            { "data" : "guid" ,render:function( data, type, row ){

              var element = '';

              <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
              {
                ?>
                  
                element += '<button id="edit_btn" type="button"  title="EDIT" class="btn btn-sm btn-info" guid="'+row['guid']+'" doc_type="'+row['type']+'" description="'+row['description']+'" email_group_name="'+row['email_group_name']+'" customer_guid="'+row['customer_guid']+'" activate="'+row['activate']+'"><i class="fa fa-edit"></i></button>';

                element += '<button id="delete_btn" style="margin-left:5px;" title="DELETE" class="btn btn-sm btn-danger" guid="'+row['guid']+'" description="'+row['description']+'" ><i class="fa fa-trash"></i></button>';

                <?php
              }
              ?>

              return element;
            }},
            { "data" : "acc_name" },
            { "data" : "type" },
            { "data" : "email_group_name" },
            { "data" : "description" },
            { "data" : "active" },
            { "data" : "created_at" },
            { "data" : "created_by" },
            { "data" : "updated_at" },
            { "data" : "updated_by" },

          ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      $(nRow).closest('tr').css({"cursor": "pointer"});
      $(nRow).attr('guid', aData['guid']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $(document).on('click', '#list_table tbody tr', function(event){
    
    var xstatus = $('#list_table').DataTable().rows().data().any();
    var guid = $(this).attr('guid');

    if((xstatus == false) || (xstatus != true)){
      return;
    }

    if(event.target.tagName == "I" || event.target.tagName == "BUTTON" || event.target.tagName == "INPUT") {
      return;
    }

    if((guid == '') || (guid == null) || (guid == 'null'))
    {
      alert('Invalid DATA');
      return;
    }

    //print_r($guid); die;
    child_table(guid);
    $('#append_btn').html('<button id="upload_excel" main_guid="'+guid+'" type="button" class="btn btn-xs btn-warning"><i class="fa fa-upload" aria-hidden="true" ></i> Upload Excel </button> <button id="create_email" main_guid="'+guid+'" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Create Email </button> <button id="create_email_module" main_guid="'+guid+'" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Create Email By Module </button> <button id="create_btn_child" main_guid="'+guid+'" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Create Email With Mapped </button> <button id="active_email" main_guid="'+guid+'" isactive_val="1" type="button" class="btn btn-xs btn-danger edit_activate"><i class="glyphicon glyphicon-ok" aria-hidden="true" ></i> Active Email </button>  <button id="deactive_email" main_guid="'+guid+'" isactive_val="0" type="button" class="btn btn-xs btn-danger edit_activate"><i class="glyphicon glyphicon-remove" aria-hidden="true" ></i> Deactive Email </button> <button id="delete_email_multiple" main_guid="'+guid+'" type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash" aria-hidden="true" ></i> Delete Email </button>');

    //$('input[aria-controls="reminder_retailer_table"]').val(debtor_code).keyup();

    var id = $(this).closest('table').attr('id');

    var table = $('#'+id).DataTable();

    table.rows('.active').nodes().to$().removeClass("active");

    $(this).closest('table').find('tr').removeClass("active");
    $(this).addClass('active');

  });//close mouse click

  child_table = function(guid)
  { 
    $.ajax({
        url : "<?php echo site_url('Blast_email_user/list_tb_child');?>",
        method: "POST",
        data:{guid:guid},
        beforeSend : function() {
          //$('.btn').button('loading');
          swal.fire({
            allowOutsideClick: false,
            title: 'Processing...',
            showCancelButton: false,
            showConfirmButton: false,
            onOpen: function () {
            swal.showLoading()
            }
          });
        },
        complete: function() {
          $('.btn').button('reset');
          setTimeout(function() {
            Swal.close();
          }, 600);
        },
        success : function(data)
        {  
          json = JSON.parse(data);

          if ($.fn.DataTable.isDataTable('#list_table_child')) {
              $('#list_table_child').DataTable().destroy();
          }

          $('#list_table_child').DataTable({
            "columnDefs": [
            {"targets": [0] ,"orderable": false},
            ],
            'processing'  : true,
            'paging'      : true,
            'lengthChange': true,
            'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
            'searching'   : true,
            'ordering'    : true,
            'order'       : [   ],
            'info'        : true,
            'autoWidth'   : false,
            "bPaginate": true, 
            "bFilter": true, 
            "sScrollY": "50vh", 
            "sScrollX": "100%", 
            "sScrollXInner": "100%", 
            "bScrollCollapse": true,
            data: json['data'],
            columns: [
                    { "data" : "guid" ,render:function( data, type, row ){

                    var element = '';

                    <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
                    {
                        ?>
                        
                        element += '<input type="checkbox" id="checkbox_id" class="form-checkbox" guid="'+row['guid']+'" />';   

                        <?php
                    }
                    ?>

                    return element;
                    }},
                    // { "data" : "guid" ,render:function( data, type, row ){

                    // var element = '';
                    // var element1 = row['cc_email'];

                    // <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
                    // {
                    //     ?>
                        
                    //     element += '<button id="delete_btn_child" style="margin-left:5px;" title="DELETE" class="btn btn-xs btn-danger" main_guid="'+row['email_group_guid']+'" guid="'+row['guid']+'" ><i class="fa fa-trash"></i></button>';

                    //     if(element1 != '' && element1 != null && element1 != 'null')
                    //     {
                    //       element += '<button id="view_cc" style="margin-top:5px;" title="CCEMAIL" class="btn btn-xs btn-success" cc_email="'+row['cc_email']+'" guid="'+row['guid']+'" ><i class="fa fa-eye"></i></button> <br>';
                    //     }

                    //     <?php
                    // }
                    // ?>

                    // return element;
                    // }},
                    { "data" : "acc_name" },
                    { "data" : "supplier_name" },
                    // { "data" : "email_name" },
                    { "data" : "user_email" },
                    // { "data" : "category" },
                    { "data" : "active" },
                    { "data" : "created_at" },
                    { "data" : "created_by" },
                    { "data" : "updated_at" },
                    { "data" : "updated_by" },
                     ],
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
            "language": {
                "lengthMenu": "Show _MENU_",
                "infoEmpty": "No records available",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "zeroRecords": "<span><?php echo '<b>No Record Found.</b>'; ?></span>",
            }, 
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                // $(nRow).attr('status', aData['status']);
                if(aData['active'] == 'Yes')
                {
                  $(nRow).find('td:eq(2)').css({"background-color":"#29ff69","color":"black"});
                  $(nRow).find('td:eq(3)').css({"background-color":"#29ff69","color":"black"});
                  $(nRow).find('td:eq(4)').css({"background-color":"#29ff69","color":"black"});
                  $(nRow).find('td:eq(5)').css({"background-color":"#29ff69","color":"black"});
                  $(nRow).find('td:eq(6)').css({"background-color":"#29ff69","color":"black"});
                  $(nRow).find('td:eq(7)').css({"background-color":"#29ff69","color":"black"});
                  $(nRow).find('td:eq(8)').css({"background-color":"#29ff69","color":"black"});
                  $(nRow).find('td:eq(9)').css({"background-color":"#29ff69","color":"black"});
                  $(nRow).find('td:eq(1)').css({"background-color":"#29ff69","color":"black"});
                }

            },
            "initComplete": function( settings, json ) {
                interval();
            },
          });//close datatable
        }//close success
    });//close ajax
  }//close child table

  $(document).on('click','#create_btn',function(){

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('New Email User Group');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><label>Retailer Name</label> <select class="form-control select2 get_code" name="add_retailer" id="add_retailer"> <option value="" disabled selected>-Select Retailer Name-</option>  <?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Type</label> <input type="text" class="form-control input-xm" id="add_type" "/> </div>';

    methodd += '<div class="col-md-12"><label>Mail Group</label> <input type="text" class="form-control input-xm" id="add_group_name" /> </div>';

    methodd += '<div class="col-md-12"><label>Description</label> <input type="text" class="form-control input-xm" id="add_description" /> </div>';

    methodd += '<div class="col-md-12"><label>Active</label><select class="form-control" name="add_active" id="add_active"> <option value="" >-Select Activate-</option> <option value="1"> YES </option>  <option value="0"> NO </option>  </select> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="submit_button" class="btn btn-primary"> Create </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('.select2').select2();
    },300);

  });//close modal create

  $(document).on('click','#submit_button',function(){
    var add_retailer = $('#add_retailer').val();
    var add_type = $('#add_type').val();
    var add_group_name = $('#add_group_name').val();
    var add_description = $('#add_description').val();
    var add_active = $('#add_active').val();

    if((add_retailer == '') || (add_retailer == null) || (add_retailer == 'null'))
    {
      alert('Please Select Retailer Name.');
      return;
    }

    if((add_type == '') || (add_type == null) || (add_type == 'null'))
    {
      alert('Please Insert Type.');
      return;
    }

    if((add_group_name == '') || (add_group_name == null) || (add_group_name == 'null'))
    {
      alert('Please Insert Mail Group.');
      return;
    }

    if((add_description == '') || (add_description == null) || (add_description == 'null'))
    {
      alert('Please Insert Description.');
      return;
    }

    if((add_active == '') || (add_active == null) || (add_active == 'null'))
    {
      alert('Please Select Active or Deactive.');
      return;
    }

    confirmation_modal('Are you sure to proceed Create New Email Group?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_user/add_email_group') ?>",
        method:"POST",
        data:{add_retailer:add_retailer,add_type:add_type,add_group_name:add_group_name,add_description:add_description,add_active:add_active},
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

  });//close submit process

  function isEmail(myVar){
    var regEmail = new RegExp('^[0-9a-z._-]+@{1}[0-9a-z.-]{2,}[.]{1}[a-z]{2,5}$','i');
    return regEmail.test(myVar);
  }

  //create email by individual
  $(document).on('click','#create_email',function(){
    var main_guid = $(this).attr('main_guid');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Set Email Address');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control input-xm" id="s_guid" value="'+main_guid+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><label>Retailer Name</label> <select class="form-control select2 create_get_user" name="s_retailer" id="s_retailer"> <option value="" disabled selected>-Select Retailer Name-</option>  <?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label> <select class="form-control select2 create_get_user" name="s_supplier" id="s_supplier"> <option value="" disabled selected>-Select Retailer Name-</option>  <?php foreach($get_supplier as $row) { ?> <option value="<?php echo $row->supplier_guid?>"><?php echo addslashes($row->supplier_name) ?></option> <?php } ?></select> </div>';

    //methodd += '<div class="col-md-12"><label>Email Address</label> <select class="form-control select2" name="add_user_child" id="add_user_child"> <option value="" disabled selected>-Select Retailer Name-</option>  </select> </div>';

    methodd += '<div class="col-md-12"><label>New Email Address (To)</label> <select class="form-control select2" name="s_email" id="s_email" multiple="multiple" > </select>  </div>';

    // methodd += '<div class="col-md-12"><label>Category</label><select class="form-control" name="add_category_child" id="add_category_child"> <option value="" disabled selected>-Select Category-</option> <option value="to"> to </option>  <option value="cc"> cc </option>  </select> </div>';

    methodd += '<div class="col-md-12"><label>Active</label><select class="form-control" name="s_active" id="s_active"> <option value="" disabled selected>-Select Activate-</option> <option value="1"> YES </option>  <option value="0"> NO </option>  </select> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="submit_email_btn" class="btn btn-primary"> Create </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
        $('.select2').select2();

        $("#s_email").select2({
        //data: json.cc_email,
        //maximumSelectionLength: 1,
        tags: true,
        tokenSeparators: [',', ''],
        createTag :function (params) 
          {
            if(!isEmail(params.term)){
                return {
                    text: params.term,
                };
            }
            return {
              id: params.term,
              text: params.term,
            };
          }
      });

    },300);

  });//close modal 
  
  //submit create email by individual
  $(document).on('click','#submit_email_btn',function(){
    var s_guid = $('#s_guid').val();
    var s_retailer = $('#s_retailer').val();
    var s_supplier = $('#s_supplier').val();
    var s_email = $('#s_email').val();
    var s_active = $('#s_active').val();

    if((s_guid == '') || (s_guid == null) || (s_guid == 'null'))
    {
      alert('Invalid Guid.');
      return;
    }

    if((s_retailer == '') || (s_retailer == null) || (s_retailer == 'null'))
    {
      alert('Please Select Retailer Name.');
      return;
    }

    if((s_supplier == '') || (s_supplier == null) || (s_supplier == 'null'))
    {
      alert('Please Select Supplier Name.');
      return;
    }

    if((s_email == '') || (s_email == null) || (s_email == 'null'))
    {
      alert('Please Insert Email.');
      return;
    }

    if((s_active == '') || (s_active == null) || (s_active == 'null'))
    {
      alert('Please Select Active or Deactive.');
      return;
    }

    confirmation_modal('Are you sure to proceed Create New Email Detail?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_user/add_email_multiple') ?>",
        method:"POST",
        data:{main_guid:s_guid,add_retailer_child:s_retailer,add_supplier_child:s_supplier,s_email:s_email,add_active_child:s_active},
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
            $('#medium-modal').modal('hide');
            $('#alertmodal').modal('hide');
            alert(json.msg);
            setTimeout(function(){
                child_table(s_guid);
                $('.sidebar-collapse').css('padding-right','0');
            },300);
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation

  });//close submit process

  //create email by mapping
  $(document).on('click','#create_btn_child',function(){
    var main_guid = $(this).attr('main_guid');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Create Email With Mapped');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control input-xm" id="main_guid" value="'+main_guid+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><label>Retailer Name</label> <select class="form-control select2 create_get_user" name="add_retailer_child" id="add_retailer_child"> <option value="" disabled selected>-Select Retailer Name-</option>  <?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label> <select class="form-control select2 create_get_user" name="add_supplier_child" id="add_supplier_child"> <option value="" disabled selected>-Select Retailer Name-</option>  <?php foreach($get_supplier as $row) { ?> <option value="<?php echo $row->supplier_guid?>"><?php echo addslashes($row->supplier_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Email Address</label><span id="mapped_all_btn"></span><select class="form-control select2" name="add_user_mapped" id="add_user_mapped" multiple="multiple"> </select> </div>';

    // methodd += '<div class="col-md-12"><label>CC Email Address</label><span id="all_btn"></span> <select class="form-control select2" name="add_cc_user" id="add_cc_user" multiple="multiple"> </select> </div>';

    // methodd += '<div class="col-md-12"><label>Category</label><select class="form-control" name="add_category_child" id="add_category_child"> <option value="" disabled selected>-Select Category-</option> <option value="to"> to </option>  <option value="cc"> cc </option>  </select> </div>';

    methodd += '<div class="col-md-12"><label>Active</label><select class="form-control" name="add_active_child" id="add_active_child"> <option value="" disabled selected>-Select Activate-</option> <option value="1"> YES </option>  <option value="0"> NO </option>  </select> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="submit_button_child" class="btn btn-primary"> Create </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
        $('.select2').select2();

        $('.create_get_user').change(function(){
            var customer_guid = $('#add_retailer_child').val();
            var supplier_guid = $('#add_supplier_child').val();

            if(customer_guid != '' || supplier_guid != '')
            {
                $.ajax({
                url : "<?php echo site_url('Blast_email_user/fetch_user'); ?>",
                method:"POST",
                data:{customer_guid:customer_guid,supplier_guid:supplier_guid},
                success:function(result)
                {

                json = JSON.parse(result); 

                  vendor = '';

                  Object.keys(json['content']).forEach(function(key) {

                    vendor += '<option value="'+json['content'][key]['user_guid']+'">'+json['content'][key]['user_id']+' - '+json['content'][key]['user_name']+'</option>';

                  });

                  $('#mapped_all_btn').html('<button id="mapped_location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-bottom:5px;margin-top:5px;" >X</button> <button id="mapped_location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-bottom:5px;margin-top:5px;">ALL</button>'); 
                  $('#add_user_mapped').select2().html(vendor);

                }
                });
            }
            else
            {
                $('#add_user_mapped').select2().html('<option value="" disabled>Please select the supplier and retailer</option>');
            }
        });//close selection
    },300);

  });//close modal create
  
  //submit email by mapping
  $(document).on('click','#submit_button_child',function(){
    var main_guid = $('#main_guid').val();
    var add_retailer_child = $('#add_retailer_child').val();
    var add_supplier_child = $('#add_supplier_child').val();
    var add_user_mapped = $('#add_user_mapped').val();
    //var add_cc_user = $('#add_cc_user').val();
    //var add_category_child = $('#add_category_child').val();
    var add_active_child = $('#add_active_child').val();

    if((add_retailer_child == '') || (add_retailer_child == null) || (add_retailer_child == 'null'))
    {
      alert('Please Select Retailer Name.');
      return;
    }

    if((add_supplier_child == '') || (add_supplier_child == null) || (add_supplier_child == 'null'))
    {
      alert('Please Select Supplier.');
      return;
    }

    if((add_user_mapped == '') || (add_user_mapped == null) || (add_user_mapped == 'null'))
    {
      alert('Please Select Email.');
      return;
    }

    // if((add_category_child == '') || (add_category_child == null) || (add_category_child == 'null'))
    // {
    //   alert('Please Insert Description.');
    //   return;
    // }

    if((add_active_child == '') || (add_active_child == null) || (add_active_child == 'null'))
    {
      alert('Please Select Active or Deactive.');
      return;
    }

    confirmation_modal('Are you sure to proceed Create New Email Detail?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_user/add_email_multiple') ?>",
        method:"POST",
        data:{main_guid:main_guid,add_retailer_child:add_retailer_child,add_supplier_child:add_supplier_child,add_user_child:add_user_mapped,add_active_child:add_active_child},
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
            $('#medium-modal').modal('hide');
            $('#alertmodal').modal('hide');
            alert(json.msg);
            setTimeout(function(){
                child_table(main_guid);
                $('.sidebar-collapse').css('padding-right','0');
            },300);
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation

  });//close submit process

  //create email by module
  $(document).on('click','#create_email_module',function(){
    var main_guid = $(this).attr('main_guid');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Create Email By Module');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control input-xm" id="main_guid" value="'+main_guid+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><label>Module Group Name</label> <select class="form-control select2 create_get_user" name="add_module_guid" id="add_module_guid"> <option value="" disabled selected>-Select Module Name-</option>  <?php foreach($get_module as $row) { ?> <option value="<?php echo $row->user_group_guid?>"><?php echo addslashes($row->user_group_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Retailer Name</label> <select class="form-control select2 create_get_user" name="add_retailer_child" id="add_retailer_child"> <option value="" disabled selected>-Select Retailer Name-</option>  <?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name) ?></option> <?php } ?></select> </div>';

    // methodd += '<div class="col-md-12"><label>Supplier Name</label> <select class="form-control select2 create_get_user" name="add_supplier_child" id="add_supplier_child"> <option value="" disabled selected>-Select Retailer Name-</option>  <?php foreach($get_supplier as $row) { ?> <option value="<?php echo $row->supplier_guid?>"><?php echo addslashes($row->supplier_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Email Address</label><span id="all_btn"></span><select class="form-control select2" name="add_user_child" id="add_user_child" multiple="multiple"> </select> </div>';

    // methodd += '<div class="col-md-12"><label>CC Email Address</label><span id="all_btn"></span> <select class="form-control select2" name="add_cc_user" id="add_cc_user" multiple="multiple"> </select> </div>';

    methodd += '<div class="col-md-12"><label>Active</label><select class="form-control" name="add_active_child" id="add_active_child"> <option value="" disabled selected>-Select Activate-</option> <option value="1"> YES </option>  <option value="0"> NO </option>  </select> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="submit_module_email" class="btn btn-primary"> Create </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
        $('.select2').select2();

        $('.create_get_user').change(function(){
            var customer_guid = $('#add_retailer_child').val();
            //var supplier_guid = $('#add_supplier_child').val();
            var module_guid = $('#add_module_guid').val();

            if(customer_guid != '' || module_guid != '')
            {
                $.ajax({
                url : "<?php echo site_url('Blast_email_user/fetch_user_by_module'); ?>",
                method:"POST",
                data:{customer_guid:customer_guid,module_guid:module_guid},
                success:function(result)
                {

                json = JSON.parse(result); 

                  vendor = '';

                  Object.keys(json['content']).forEach(function(key) {

                    vendor += '<option value="'+json['content'][key]['user_guid']+'">'+json['content'][key]['user_id']+' - '+json['content'][key]['user_name']+'</option>';

                  });

                  $('#all_btn').html('<button id="location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-bottom:5px;margin-top:5px;" >X</button> <button id="location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-bottom:5px;margin-top:5px;">ALL</button>'); 
                  $('#add_user_child').select2().html(vendor);

                }
                });
            }
            else
            {
                $('#add_user_child').select2().html('<option value="" disabled>Please select the supplier and retailer</option>');
            }
        });//close selection
    },300);

  });//close modal create

  //submit email by moduel
  $(document).on('click','#submit_module_email',function(){
    var main_guid = $('#main_guid').val();
    var add_module_guid = $('#add_module_guid').val();
    var add_retailer_child = $('#add_retailer_child').val();
    // var add_supplier_child = $('#add_supplier_child').val();
    var add_user_child = $('#add_user_child').val();
    //var add_category_child = $('#add_category_child').val();
    var add_active_child = $('#add_active_child').val();

    if((add_module_guid == '') || (add_module_guid == null) || (add_module_guid == 'null'))
    {
      alert('Please Select Module.');
      return;
    }

    if((add_retailer_child == '') || (add_retailer_child == null) || (add_retailer_child == 'null'))
    {
      alert('Please Select Retailer.');
      return;
    }

    if((add_user_child == '') || (add_user_child == null) || (add_user_child == 'null'))
    {
      alert('Please Select Email.');
      return;
    }

    if((add_active_child == '') || (add_active_child == null) || (add_active_child == 'null'))
    {
      alert('Please Select Active or Deactive.');
      return;
    }

    confirmation_modal('Are you sure to proceed Create New Email Detail?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_user/add_email_multiple') ?>",
        method:"POST",
        data:{main_guid:main_guid,add_retailer_child:add_retailer_child,add_module_guid:add_module_guid,add_user_child:add_user_child,add_active_child:add_active_child},
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
            $('#medium-modal').modal('hide');
            $('#alertmodal').modal('hide');
            alert(json.msg);
            setTimeout(function(){
                child_table(main_guid);
                $('.sidebar-collapse').css('padding-right','0');
            },300);
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation

  });//close submit process

  $(document).on('click','#delete_btn',function(){
    var guid = $(this).attr('guid');
    var description = $(this).attr('description');

    if((guid == '') || (guid == null) || (guid == 'null'))
    {
      alert('Invalid DATA.');
      return;
    }
    confirmation_modal('Are you sure to proceed Delete Email User Group and Email Details? <br> <b> Email Name : ' +description+'</b>');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_user/delete_email_user_group') ?>",
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

  });//close submit process

  $(document).on('click','#delete_btn_child',function(){
    var main_guid = $(this).attr('main_guid');
    var c_guid = $(this).attr('guid');

    if((main_guid == '') || (main_guid == null) || (main_guid == 'null'))
    {
      alert('Invalid Main DATA.');
      return;
    }

    if((c_guid == '') || (c_guid == null) || (c_guid == 'null'))
    {
      alert('Invalid DATA.');
      return;
    }
    confirmation_modal('Are you sure to proceed Delete Email Detail?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_user/delete_email_details') ?>",
        method:"POST",
        data:{main_guid:main_guid,c_guid:c_guid},
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
            setTimeout(function(){
                child_table(main_guid);
            },300);
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation
  });//close submit process

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

  $(document).on('click','.edit_activate',function(){
    var main_guid = $(this).attr('main_guid');
    var isactive_val = $(this).attr('isactive_val');
    var details = [];
    var table = $('#list_table_child').DataTable();

    table.rows().nodes().to$().each(function(){
        
      if($(this).find('td').find('input[type="checkbox"]').is(':checked'))
      {
        var c_guid = $(this).find('td').find('input[type="checkbox"]').attr('guid');

        if((c_guid == '') || (c_guid == null) || (c_guid == 'null'))
        {
          alert('Invalid DATA.');
          return;
        }
        details.push(c_guid);
      }
      
    });//close small loop

    if((main_guid == '') || (main_guid == null) || (main_guid == 'null'))
    {
      alert('Invalid Main DATA.');
      return;
    }

    if((details == '') || (details == null) || (details == 'null'))
    {
      alert('Please select checkbox.');
      return;
    }

    confirmation_modal('Are you sure to proceed Active/Deactive Email(s)?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_user/edit_email_activate') ?>",
        method:"POST",
        data:{main_guid:main_guid,details:details,isactive_val:isactive_val},
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
            setTimeout(function(){
                child_table(main_guid);
                $('#checkall_input_table').prop('checked',false);
            },300);
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation
  });//close submit process

  $(document).on('click', '#mapped_location_all', function(){
    // alert();
    $("#add_user_mapped option").prop('selected',true);
    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('click', '#mapped_location_all_dis', function(){
    // alert();
    $("#add_user_mapped option").prop('selected',false);
    $(".select2").select2();
  });//CLOSE ONCLICK

  $(document).on('click', '#location_all', function(){
    // alert();
    $("#add_user_child option").prop('selected',true);
    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('click', '#location_all_dis', function(){
    // alert();
    $("#add_user_child option").prop('selected',false);
    $(".select2").select2();
  });//CLOSE ONCLICK
  
  $(document).on('click','#upload_excel',function(){
    //alert('123'); die;
    var upload_main_guid = $(this).attr('main_guid');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Upload Email Details');

    methodd = '';

    methodd +='<div class="col-md-12">';
    // accept=".pdf"
    methodd += '<div class="col-md-12"><input type="hidden" class="form-control input-xm" id="upload_main_guid" value="'+upload_main_guid+'" readonly/> </div>';

    methodd += '<div class="col-md-12"><label>File</label></div><div class="col-md-10"><input id="edit_upload_file" type="file" class="form-control" ></div>';

    methodd += '<div class="col-md-2"><button type="button" class="btn btn-danger" id="edit_reset_input" >Reset</button></div>';

    methodd += '</div>';

    methodd_footer = '';

    methodd_footer += '<p class="full-width"><span class="pull-right"><span id="edit_button_file_form"></span>';

    methodd_footer += '<input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

  });

  $(document).on('change','#edit_upload_file',function(e){
    
    var edit_fileName = e.target.files[0].name;
    var upload_main_guid = $('#upload_main_guid').val();

    //alert(term_sheet); die;
    if((upload_main_guid == '') || (upload_main_guid == null) || (upload_main_guid == 'null'))
    {
      alert('Invalid Email User Group Guid.');
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
    var email_group_guid = $('#upload_main_guid').val();

    if((email_group_guid == '') || (email_group_guid == null) || (email_group_guid == 'null'))
    {
      alert('Invalid Email User Group Guid.');
      return;
    }
    
    confirmation_modal('Are you sure want to Upload?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#edit_upload_file')[0].files[0]);
      formData.append('file_name', edit_file_name);
      formData.append('email_group_guid', email_group_guid);
      //console.log(formData); die;
      $.ajax({
          url:"<?= site_url('Blast_email_user/file_upload');?>",
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
              $('#medium-modal').modal('hide');
              $('#edit_submit_button').hide();
              alert(json.msg);
              setTimeout(function(){
                child_table(email_group_guid);
            },300);

          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });//close submit_button

  $(document).on('click','#delete_email_multiple',function(){
    var main_guid = $(this).attr('main_guid');
    var details = [];
    var table = $('#list_table_child').DataTable();

    table.rows().nodes().to$().each(function(){
        
      if($(this).find('td').find('input[type="checkbox"]').is(':checked'))
      {
        var c_guid = $(this).find('td').find('input[type="checkbox"]').attr('guid');

        if((c_guid == '') || (c_guid == null) || (c_guid == 'null'))
        {
          alert('Invalid DATA.');
          return;
        }
        details.push(c_guid);
      }
      
    });//close small loop

    if((main_guid == '') || (main_guid == null) || (main_guid == 'null'))
    {
      alert('Invalid Main DATA.');
      return;
    }

    if((details == '') || (details == null) || (details == 'null'))
    {
      alert('Please select checkbox.');
      return;
    }

    confirmation_modal('Are you sure to proceed DELETE Email(s)?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_user/delete_email_multiple') ?>",
        method:"POST",
        data:{main_guid:main_guid,details:details},
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
            setTimeout(function(){
                child_table(main_guid);
                $('#checkall_input_table').prop('checked',false);
            },300);
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation
  });//close submit process

});
</script>

