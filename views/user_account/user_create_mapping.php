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

.disabled{
    pointer-events:none;
    opacity:0.7;
}

.css_tab{
  background-color: #abe4f5 !important;
  font-weight: bold;
}

.li_hover:hover{
  font-weight: bold;
}

.header_name {
  margin: 0px 0 10px 0;
  font-size: 22px;
  border-bottom: 1px solid #eee;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice
{
  background: #3c8dbc;
} 

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
  display: inline-grid;
  white-space: nowrap;
  overflow-x: hidden;
  overflow-y: scroll;
  max-height: 250px;
}

.notes_css {
  min-height: 20px;
  background-color: #7af740;
  border: 1px solid #e3e3e3;
  border-radius: 4px;
  padding: 9px;
  /* margin-bottom: 20px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  box-shadow: inset 0 1px 1px rgba(0,0,0,.05); */
}

.step_info {
  font-size : 20px;
  font-weight : bold;
  text-align : left;
  border: none;
  font-weight: bold;
  /* padding: 5px 10px; */
  text-decoration: none;
  /* margin: 4px 2px; */
  border-radius: 16px;
  font-family: sans-serif;
  animation: blink-animation 5s ;
  -webkit-animation: blink-animation 2s ;
}

@keyframes blink-animation {
  30% { opacity: 1; background-color: #86dcff; color:black;}
  50% { opacity: 0; background-color: #86dcff; color:black;}
  100% { opacity: 1; background-color: transparent; color:black;}
}

@-webkit-keyframes blink-animation {
  30% { opacity: 1; background-color: #86dcff; color:black;}
  50% { opacity: 0; background-color: #86dcff; color:black;}
  100% { opacity: 1; background-color: transparent; color:black;}
}

.table_design {
  min-height: fit-content;
  border: 1px solid white;
  border-radius: 4px;
  padding: 9px;
  background-color: ghostwhite;
  /* margin-bottom: 20px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  box-shadow: inset 0 1px 1px rgba(0,0,0,.05); */
}

.table_design:hover {
  border: 1px solid #51c4f5;
}

.table tbody > tr:hover {
  background-color: #5c98ff; /* Change this to your desired hover color */
  font-weight:bold;
}

</style>
<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>
  <div class="row">
    <div class="col-md-12">
      <!-- Custom Tabs -->
      <!-- <h2 class="header_name">Account Creation</h2> -->
      <div class="step_info"> Account Setup - Step 2 of 3 </div>
      <div class="progress progress-xs">
        <div class="progress-bar progress-bar-primary" style="width: 65%"></div>
      </div>
      <span id="count_code_tb"></span>
      <span id="count_outlet_tb"></span>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <?php if($flag_show_tab == 'show_tab')
          {
            ?>
            <li class="li_hover"><a style="color:black">Information</a></li>
            <li class="active"><a class="css_tab">Mapping Information</a></li>
            <li class="li_hover"><a style="color:black">Review</a></li> 
            <?php
          }
          else
          {
            ?>
            <li class="disabled"><a href="#tab_1" data-toggle="tab" aria-expanded="false" >Information</a></li>
            <li class="active"><a class="css_tab" href="#tab_2" data-toggle="tab" aria-expanded="true" >Mapping Information</a></li>
            <?php
          }
          ?>
        </ul>
        <div class="tab-content" >
          
          <?php
          if($get_registered_count % 5 == 0  && $process_action_status != 'EDIT')
          {
            ?>
            <mark style="background-color:yellow;font-weight:bold;font-size:16px;">Current User Account : <?php echo $get_registered_count; ?> <br> Additional fees will be charge in your next billing month. </mark>
            <?php
          }
          ?>

          <div class="tab-pane active" id="tab_2">

            <div class="box-body table_design">
            <h4 class="box-title">Map Vendor Code
              <div class="pull-right">
                <button id="vendor_mapping_btn" class="btn btn-xs btn-primary">
                  <i class="fa fa-edit"></i> Map Vendor Code
                </button>

                <button id="vendor_delete_btn" class="btn btn-xs btn-danger">
                  <i class="fa fa-trash"></i> Remove Vendor Code
                </button>
                
              </div>
            </h4>
            <div class="">
            <table class="table table-bordered table-striped dataTable" id="mapping_code_tb" style="width: 100%;">
              <thead style="white-space: nowrap;">
                <tr>
                  <th>Retailer Name</th> 
                  <th>Supplier Name</th>
                  <th>User ID</th>
                  <th>User Name</th>
                  <th>Name</th>
                  <th>Code</th>
                  <th>Created At</th>
                  <th>Created By</th>
                  <th>
                    <input type="checkbox" id="vendor_checkall_input_table" name="vendor_checkall_input_table" table_id="mapping_code_tb">
                  </th> 
                  <!-- <th>Action</th> -->
                </tr>
              </thead>
              <tbody> 
              </tbody>

            </table>
            </div>
            </div>

            <div class="box-body table_design">
            <h4 class="box-title">Map Outlet Code
              <div class="pull-right">
                <button id="outlet_mapping_btn" class="btn btn-xs btn-primary">
                  <i class="fa fa-edit"></i> Map Outlet Code
                </button>

                <button id="outlet_delete_btn" class="btn btn-xs btn-danger">
                  <i class="fa fa-trash"></i> Remove Outlet Code
                </button>
                
              </div>
              </h4>
            <div class="">
            <table class="table table-bordered table-striped dataTable" id="mapping_outlet_tb" style="width: 100%;">
              <thead style="white-space: nowrap;">
                <tr>
                  <th>Retailer Name</th> 
                  <th>User ID</th>
                  <th>User Name</th>
                  <th>Outlet Name</th>
                  <th>Outlet Code</th>
                  <th>Created At</th>
                  <th>Created By</th>
                  <th>
                    <input type="checkbox" id="checkall_input_table" name="checkall_input_table" table_id="mapping_outlet_tb">
                  </th> 
                  <!-- <th>Action</th> -->
                </tr>
              </thead>
              <tbody> 
              </tbody>
            </table>
            </div>
            </div>

          <br/>
          <div class="box-footer">
            <button id="back_process" type="button" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> Back</button>
            <button id="next_process" type="button" class="btn btn-primary"  style="float:right;"><i class="fa fa-arrow-circle-right"></i> Next</button>
       
            <?php
              if($flag_show_tab == 'show_tab')
              {
                ?>
                <button id="skip_process" type="button" class="btn btn-warning" style="float:right;margin-right:5px;"> Skip </button>
                <?php
              }
            ?>
          </div>
          
          </div>
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- nav-tabs-custom -->
    </div>
  </div>

</div>
</div>

<script>
$(document).ready(function () {    
  var link_one = '<?php echo $link_one ?>';
  var process_user_guid = '<?php echo $process_user_guid ?>';
  var process_customer_guid = '<?php echo $process_customer_guid ?>';
  var user_active_status = '<?php echo $user_active_status ?>';

  // START Outlet Mapping Function Here @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

  vendor_mapping_table = function(link_one) {
    $.ajax({
      url : "<?php echo site_url('User_account_setting/list_code_tb');?>",
      method: "POST",
      data:{link_one:link_one},
      beforeSend : function() {
          $('.btn').button('loading');
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success : function(data)
      {  
        json = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#mapping_code_tb')) {
            $('#mapping_code_tb').DataTable().destroy();
        }

        $('#mapping_code_tb').DataTable({
          "columnDefs": [{ "orderable": false, "targets": [8]},],
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [6, 'desc'] ],
          'info'        : true,
          'autoWidth'   : true,
          "bPaginate": true, 
          "bFilter": true, 
          "sScrollY": "50vh", 
          "sScrollX": "100%", 
          "sScrollXInner": "100%", 
          "bScrollCollapse": true,
            data: json['data'],
            columns: [
              { "data" : "acc_name"},
              { "data" : "supplier_name"},
              { "data" : "user_id"},
              { "data" : "user_name"},
              { "data" : "supcus_name"},
              { "data" : "supplier_group_name"},
              { "data" : "created_at"},
              { "data" : "created_by"},
              { "data" : "empty",render:function( data, type, row , meta ){
                  var element = '';

                  element += '<input type="checkbox" class="form-checkbox" name="flag_checkbox" id="flag_checkbox" mp_val_data_1="'+row['supplier_group_guid']+'" mp_val_data_2="'+row['user_guid']+'" mp_val_data_3="'+row['supplier_guid']+'" mp_val_data_4="'+row['acc_guid']+'" mp_val_data_5="'+row['supplier_group_name']+'"/>';

                  return element;
              }},
            ],
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip", 
          "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              $(nRow).closest('tr').css({"cursor": "pointer"});
            // $(nRow).attr('status', aData['status']);
          },
          "footerCallback": function ( row, data, start, end, display ,iDataIndex) {
            var value_data = $('#mapping_code_tb').DataTable().data().length;
            $('#count_code_tb').html('<input type="hidden" id="code_tb_count" name="code_tb_count" value='+value_data+' readonly> ');
          },
          "initComplete": function( settings, json ) {
            setTimeout(function(){
              interval();
            },300);
          }
        });//close datatable
      }//close success
    });//close ajax
  }
  vendor_mapping_table(link_one);

  $(document).on('click','#vendor_mapping_btn',function(){

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Map Vendor Code');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<p class="notes_css"><span style="font-size:16px;float:right;font-weight:bold;">Mapped Code : <span id="append_total_hint"></span> </span>';

    methodd += '<span style="font-size:16px;font-weight:bold;"> Selected Code : <span id="append_hint" ></span></span></p>';

    methodd += '<div class="clearfix"></div>';

    methodd += '<div class="form-group"><label>User ID </label><div><span><?php echo $user_id?></span></div></div>';

    methodd += '<div class="form-group"><label>User Name </label><div class=""><span><?php echo $user_name?></div></span></div>';

    methodd += '<div class="form-group"><label>Supplier Name </label> <select class="form-control select2" name="select_supplier" id="select_supplier" > <option value=""> -SELECT DATA- </option><?php foreach($get_supplier as $row) { ?> <option value="<?php echo $row->supplier_guid?>"><?php echo addslashes($row->supplier_name)?>  </option> <?php } ?></select> </div> ';

    methodd += '<span id="append_assign" ></span>';
    
    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="vendor_create_mapping" class="btn btn-success" value="Create"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);
 
    setTimeout(function(){
      $('#select_supplier').select2();

      $('#select_supplier').on('change', function() {
        var type_val = $('#select_supplier').val();
        
        if(type_val != '')
        {
          $.ajax({
            url : "<?php echo site_url('User_account_setting/fetch_assign_selection'); ?>",
            method:"POST",
            data:{type_val:type_val,link_one:link_one},
            success:function(result)
            {
              json = JSON.parse(result); 

              append = '';

              selection = '';

              append = '<div class="form-group"><label>Map Vendor Code</label> <button id="vendor_location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="vendor_location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button> <select class="form-control select2" name="vendor_assign_value" id="vendor_assign_value" multiple="multiple">';

              Object.keys(json['data']).forEach(function(key) {
                selection += '<option value="'+json['data'][key]['supplier_group_guid']+'">'+json['data'][key]['supplier_group_name']+' - '+json['data'][key]['supcus_name']+'</option>';
              });

              append += '</select> </div>';
              
              $('#append_assign').html(append);

              $('#vendor_assign_value').select2().html(selection);

              $('#append_total_hint').html( json.data_mapped_count +' / '+ json.data_count);

              $('#vendor_assign_value').on('change', function() {
                var selectedOptions = $(this).val();
                var selectedCount = selectedOptions ? selectedOptions.length : 0;
                $('#append_hint').html(selectedCount);
              });
            }
          });
        }
        else
        {
          $('#append_assign').html('');
          $('#append_total_hint').html('0');

        }
        
      });
    },300);

  });//close create part2 vendor
  
  $(document).on('click','#vendor_create_mapping',function(){

    var add_supplier_guid = $('#select_supplier').val();
    var vendor_assign_value = $('#vendor_assign_value').val();
    
    if((link_one == '') || (link_one == null) || (link_one == 'null'))
    {
      alert('Invalid Process.');
      return;
    }

    if((add_supplier_guid == '') || (add_supplier_guid == null) || (add_supplier_guid == 'null'))
    {
      alert('Invalid Process.');
      return;
    }

    if((vendor_assign_value == '') || (vendor_assign_value == null) || (vendor_assign_value == 'null'))
    {
      alert('Please Select Mapping Code.');
      return;
    }

    confirmation_modal('Are you sure want to Mapping User.');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('User_account_setting/process_code') ?>",
        method:"POST",
        data:{link_one:link_one,add_supplier_guid:add_supplier_guid,vendor_assign_value:vendor_assign_value},
        beforeSend:function(){
          $('.btn').button('loading');
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
        success:function(data)
        {
          json = JSON.parse(data);
          if(json.para1 == 'falsea')
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            // alert(json.msg);
            Swal.fire({
              title: json.msg, 
              text: '', 
              type: "error",
              allowOutsideClick: false,
              showConfirmButton: true,
              onClose: vendor_close_process
            })
          }
          else
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            $("#medium-modal").modal('hide');
            // alert(json.msg);
            Swal.fire({
              title: json.msg, 
              text: '', 
              type: "success",
              allowOutsideClick: false,
              showConfirmButton: true,
              onClose: vendor_close_process
            })
          }
         
        }//close success
        
      });//close ajax 
    });//close document yes click
  });//close redirect

  $(document).on('change','#vendor_checkall_input_table',function(){

    var id = $(this).attr('table_id');

    var table = $('#'+id).DataTable();

    if($(this).is(':checked'))
    {
      table.rows().nodes().to$().each(function(){

        $(this).find('td').find('#flag_checkbox').prop('checked',true)

      });//close small loop
    }
    else
    {
      table.rows().nodes().to$().each(function(){

        $(this).find('td').find('#flag_checkbox').prop('checked',false)

      });//close small loop
    }//close else

  });//close checkbox

  $(document).on('click', '#vendor_delete_btn', function(){

    var table = $('#mapping_code_tb').DataTable();
    var details = [];
    var code = [];

    table.rows().nodes().to$().each(function(){
      if($(this).find('td').find('#flag_checkbox').is(':checked'))
      {
        d_supplier_group_guid = $(this).find('td').find('#flag_checkbox').attr('mp_val_data_1');
        d_user_guid = $(this).find('td').find('#flag_checkbox').attr('mp_val_data_2');
        d_supplier_guid = $(this).find('td').find('#flag_checkbox').attr('mp_val_data_3');
        d_acc_guid = $(this).find('td').find('#flag_checkbox').attr('mp_val_data_4');
        d_code = $(this).find('td').find('#flag_checkbox').attr('mp_val_data_5');

        if(d_supplier_group_guid == '' || d_supplier_group_guid == 'null' || d_supplier_group_guid == null)
        {
          alert('Invalid Process Error 1.');
          return;
        }

        if(d_user_guid == '' || d_user_guid == 'null' || d_user_guid == null)
        {
          alert('Invalid Process Error 2.');
          return;
        }

        if(d_supplier_guid == '' || d_supplier_guid == 'null' || d_supplier_guid == null)
        {
          alert('Invalid Process Error 3.');
          return;
        }

        if(d_acc_guid == '' || d_acc_guid == 'null' || d_acc_guid == null)
        {
          alert('Invalid Process Error 4.');
          return;
        }
  
        details.push({'d_supplier_group_guid':d_supplier_group_guid,'d_user_guid':d_user_guid,'d_supplier_guid':d_supplier_guid,'d_acc_guid':d_acc_guid});

        // code.push({'d_code':d_code});
      }
    });//close small loop

    var count_selected = details.length;

    // var array_code = code.map(function(obj) {
    //   return obj.d_code;
    // }).join(',');

    if(details == '' || details == 'null' || details == null)
    {
      alert('Please Select Checkbox.');
      return;
    }

    confirmation_modal('Are you sure want to Remove Mapped Code?  <b> Count : '+count_selected+'</b>');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('User_account_setting/remove_process_code') ?>",
        method:"POST",
        data:{details:details},
        beforeSend:function(){
          $('.btn').button('loading');
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
        success:function(data)
        {
          json = JSON.parse(data);
          if(json.para1 == 'false')
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            // alert(json.msg);
            Swal.fire({
              title: json.msg, 
              text: '', 
              type: "error",
              allowOutsideClick: false,
              showConfirmButton: true,
              onClose: vendor_close_process
            })
          }
          else
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            $("#medium-modal").modal('hide');
            // alert(json.msg);
            Swal.fire({
              title: json.msg, 
              text: '', 
              type: "success",
              allowOutsideClick: false,
              showConfirmButton: true,
              onClose: vendor_close_process
            })
          }
         
        }//close success
        
      });//close ajax 
    });//close document yes click
  });//CLOSE ONCLICK  

  $(document).on('click', '#vendor_location_all', function(){
    $("#vendor_assign_value option").prop('selected',true);

    var selectedOptions = $('#vendor_assign_value').val();
    var selectedCount = selectedOptions ? selectedOptions.length : 0;
    $('#append_hint').html(selectedCount);

    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('click', '#vendor_location_all_dis', function(){
    $("#vendor_assign_value option").prop('selected',false);

    var selectedOptions = $('#vendor_assign_value').val();
    var selectedCount = selectedOptions ? selectedOptions.length : 0;
    $('#append_hint').html(selectedCount);

    $(".select2").select2();
  });//CLOSE ONCLICK 

  // END Vendor Mapping Function @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

  // START Outlet Mapping Function Here @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

  outlet_mapping_table = function(process_user_guid,process_customer_guid) {
    $.ajax({
      url : "<?php echo site_url('User_account_setting/view_vo_tb');?>",
      method: "POST",
      data:{user_guid:process_user_guid,customer_guid:process_customer_guid},
      beforeSend : function() {
          $('.btn').button('loading');
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success : function(data)
      {  
        json = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#mapping_outlet_tb')) {
            $('#mapping_outlet_tb').DataTable().destroy();
        }

        $('#mapping_outlet_tb').DataTable({
          "columnDefs": [{ "orderable": false, "targets": [7]},],
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [5, 'desc'] ],
          'info'        : true,
          'autoWidth'   : true,
          "bPaginate": true, 
          "bFilter": true, 
          "sScrollY": "50vh", 
          "sScrollX": "100%", 
          "sScrollXInner": "100%", 
          "bScrollCollapse": true,
            data: json['data'],
            columns: [
                { "data" : "acc_name"},
                { "data" : "user_id"},
                { "data" : "user_name"},
                { "data" : "branch_desc"},
                { "data" : "branch_code"},
                { "data" : "created_at"},
                { "data" : "created_by"},
                { "data" : "empty",render:function( data, type, row , meta ){
                    var element = '';

                    element += '<input type="checkbox" class="form-checkbox" name="flag_checkbox" id="flag_checkbox" mp_val_data_1="'+row['branch_guid']+'" mp_val_data_2="'+row['user_guid']+'" mp_val_data_3="'+row['acc_guid']+'"/>';

                    return element;
                }},
            ],
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip", 
          "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              $(nRow).closest('tr').css({"cursor": "pointer"});
            // $(nRow).attr('status', aData['status']);
          },
          "footerCallback": function ( row, data, start, end, display ,iDataIndex) {
            var value_data = $('#mapping_outlet_tb').DataTable().data().length;
            $('#count_outlet_tb').html('<input type="hidden" id="outlet_tb_count" name="outlet_tb_count" value='+value_data+' readonly> ');
          },
          "initComplete": function( settings, json ) {
            setTimeout(function(){
              interval();
            },300);
          }
        });//close datatable
      }//close success
    });//close ajax
  }
  outlet_mapping_table(process_user_guid,process_customer_guid);

  $(document).on('click','#outlet_mapping_btn',function(){

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Map Outlet Code');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<p class="notes_css"><span style="font-size:16px;float:right;font-weight:bold;">Mapped Outlet : <?php echo $total_mapped_outlet?> / <?php echo $total_mapping_outlet?> </span>';

    methodd += ' <span style="font-size:16px;font-weight:bold;"> Selected Code : <span id="append_hint" style="font-size:16px;font-weight:bold;"></span></span></p>';

    methodd += '<div class="clearfix"></div>';

    methodd += '<div class="form-group"><label>User ID </label><div><span><?php echo $user_id?></span></div></div>';

    methodd += '<div class="form-group"><label>User Name </label><div class=""><span><?php echo $user_name?></div></span></div>';

    methodd += '<div class="form-group"><label>Map Outlet Code </label> <button id="outlet_location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="outlet_location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button> <select class="form-control select2" name="outlet_assign_value" id="outlet_assign_value" multiple="multiple"> <?php foreach($get_mapping_outlet as $row) { ?> <option value="<?php echo $row->branch_guid?>"><?php echo $row->branch_code?> - <?php echo addslashes($row->branch_desc)?>  </option> <?php } ?></select> </div> ';

    methodd += '<span id="append_assign" ></span>';
    
    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="outlet_create_mapping" class="btn btn-success" value="Create"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);
 
    setTimeout(function(){
      $('#outlet_assign_value').select2();

      $('#outlet_assign_value').on('change', function() {
        var selectedOptions = $(this).val();
        var selectedCount = selectedOptions ? selectedOptions.length : 0;
        $('#append_hint').html(selectedCount);
      });
    },300);

  });//close create part2 vendor
  
  $(document).on('click','#outlet_create_mapping',function(){

    var outlet_assign_value = $('#outlet_assign_value').val();
    
    if((process_user_guid == '') || (process_user_guid == null) || (process_user_guid == 'null'))
    {
      alert('Invalid Process.');
      return;
    }

    if((process_customer_guid == '') || (process_customer_guid == null) || (process_customer_guid == 'null'))
    {
      alert('Invalid Process.');
      return;
    }

    if((outlet_assign_value == '') || (outlet_assign_value == null) || (outlet_assign_value == 'null'))
    {
      alert('Please Select Mapping Outlet.');
      return;
    }

    confirmation_modal('Are you sure want to Mapping Outlet.');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('User_account_setting/process_outlet') ?>",
        method:"POST",
        data:{process_user_guid:process_user_guid,process_customer_guid:process_customer_guid,outlet_assign_value:outlet_assign_value},
        beforeSend:function(){
          $('.btn').button('loading');
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
        success:function(data)
        {
          json = JSON.parse(data);
          if(json.para1 == 'false')
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            // alert(json.msg);
            Swal.fire({
              title: json.msg, 
              text: '', 
              type: "error",
              allowOutsideClick: false,
              showConfirmButton: true,
              onClose: outlet_close_process
            })
          }
          else
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            $("#medium-modal").modal('hide');
            // alert(json.msg);
            Swal.fire({
              title: json.msg, 
              text: '', 
              type: "success",
              allowOutsideClick: false,
              showConfirmButton: true,
              onClose: outlet_close_process
            })
          }
         
        }//close success
        
      });//close ajax 
    });//close document yes click
  });//close redirect

  $(document).on('change','#checkall_input_table',function(){

    var id = $(this).attr('table_id');

    var table = $('#'+id).DataTable();

    if($(this).is(':checked'))
    {
      table.rows().nodes().to$().each(function(){

        $(this).find('td').find('#flag_checkbox').prop('checked',true)

      });//close small loop
    }
    else
    {
      table.rows().nodes().to$().each(function(){

        $(this).find('td').find('#flag_checkbox').prop('checked',false)

      });//close small loop
    }//close else

  });//close checkbox

  $(document).on('click', '#outlet_delete_btn', function(){

    var table = $('#mapping_outlet_tb').DataTable();
    var details = [];
    var code = [];

    table.rows().nodes().to$().each(function(){
      if($(this).find('td').find('#flag_checkbox').is(':checked'))
      {
        d_branch_guid = $(this).find('td').find('#flag_checkbox').attr('mp_val_data_1');
        d_user_guid = $(this).find('td').find('#flag_checkbox').attr('mp_val_data_2');
        d_acc_guid = $(this).find('td').find('#flag_checkbox').attr('mp_val_data_3');
        d_code = $(this).find('td').find('#flag_checkbox').attr('mp_val_data_5');

        if(d_branch_guid == '' || d_branch_guid == 'null' || d_branch_guid == null)
        {
          alert('Invalid Process Error 1.');
          return;
        }

        if(d_user_guid == '' || d_user_guid == 'null' || d_user_guid == null)
        {
          alert('Invalid Process Error 2.');
          return;
        }

        if(d_acc_guid == '' || d_acc_guid == 'null' || d_acc_guid == null)
        {
          alert('Invalid Process Error 3.');
          return;
        }
  
        details.push({'d_branch_guid':d_branch_guid,'d_user_guid':d_user_guid,'d_acc_guid':d_acc_guid});

      }
    });//close small loop

    var count_selected = details.length;

    if(details == '' || details == 'null' || details == null)
    {
      alert('Please Select Checkbox.');
      return;
    }

    confirmation_modal('Are you sure want to Remove Mapped Outlet?  <b> Count : '+count_selected+'</b>');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('User_account_setting/remove_process_outlet') ?>",
        method:"POST",
        data:{details:details},
        beforeSend:function(){
          $('.btn').button('loading');
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
        success:function(data)
        {
          json = JSON.parse(data);
          if(json.para1 == 'false')
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            // alert(json.msg);
            Swal.fire({
              title: json.msg, 
              text: '', 
              type: "error",
              allowOutsideClick: false,
              showConfirmButton: true,
              onClose: outlet_close_process
            })
          }
          else
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            $("#medium-modal").modal('hide');
            // alert(json.msg);
            Swal.fire({
              title: json.msg, 
              text: '', 
              type: "success",
              allowOutsideClick: false,
              showConfirmButton: true,
              onClose: outlet_close_process
            })
          }
         
        }//close success
        
      });//close ajax 
    });//close document yes click
  });//CLOSE ONCLICK  

  $(document).on('click', '#outlet_location_all', function(){
    $("#outlet_assign_value option").prop('selected',true);

    var selectedOptions = $('#outlet_assign_value').val();
    var selectedCount = selectedOptions ? selectedOptions.length : 0;
    $('#append_hint').html(selectedCount);

    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('click', '#outlet_location_all_dis', function(){
    $("#outlet_assign_value option").prop('selected',false);

    var selectedOptions = $('#outlet_assign_value').val();
    var selectedCount = selectedOptions ? selectedOptions.length : 0;
    $('#append_hint').html(selectedCount);

    $(".select2").select2();
  });//CLOSE ONCLICK 

  // END Outlet Mapping Function @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

  function vendor_close_process() {
    setTimeout(function() {
      $('.sidebar-collapse').css('padding-right','0px');
    }, 300); 
    // vendor_mapping_table(link_one);
    location.reload();
    // window.location = "<?= site_url('User_account_setting/create_code?link_one=');?>"+link_one+"&link_two="+link_two;
  }

  function outlet_close_process() {
    setTimeout(function() {
      $('.sidebar-collapse').css('padding-right','0px');
    }, 300); 
    // outlet_mapping_table(process_user_guid,process_customer_guid);
    location.reload();
    // window.location = "<?= site_url('User_account_setting/create_code?link_one=');?>"+link_one+"&link_two="+link_two;
  }

  $(document).on('click','#back_process',function(){
    window.location = "<?= site_url('User_account_setting/information?link_one=');?>"+link_one;
  });//close redirect

  $(document).on('click', '#next_process', function(){

    var code_tb_count = $('#code_tb_count').val();
    var outlet_tb_count = $('#outlet_tb_count').val();

    if(user_active_status != 0)
    {
      if(code_tb_count == 0)
      {
        alert('Please Assign atleast one Supplier Code for user.');
        return;
      }

      if(outlet_tb_count == 0)
      {
        alert('Please Assign atleast one Outlet/Branch for user.');
        return;
      }
    }
    
    confirmation_modal('Are you sure proceed to next step?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      window.location = "<?= site_url('User_account_setting/final_summary?link_one=');?>"+link_one;
    });//close document yes click
  });//CLOSE ONCLICK

  $(document).on('click','#skip_process',function(){

    var code_tb_count = $('#code_tb_count').val();
    var outlet_tb_count = $('#outlet_tb_count').val();

    if(user_active_status != 0)
    {
      if(code_tb_count == 0)
      {
        alert('Please Assign atleast one Supplier Code for user.');
        return;
      }

      if(outlet_tb_count == 0)
      {
        alert('Please Assign atleast one Outlet/Branch for user.');
        return;
      }
    }

    window.location = "<?= site_url('User_account_setting/final_summary?link_one=');?>"+link_one;
  });//close redirect

});
</script>

