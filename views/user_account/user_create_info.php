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

.edi_header {
    margin: 0px 0 10px 0;
    font-size: 22px;
    border-bottom: 1px solid #eee;
}

.li_hover:hover{
  font-weight: bold;
}

.icon_tick_match {
  background-image: url('https://file.xbridge.my/b2b-pdf/asset/image_logo/password_match.png'); /* Replace with your tick icon */
  background-repeat: no-repeat;
  background-position: right center;
}

.icon_tick_no_match {
  background-image: url('https://file.xbridge.my/b2b-pdf/asset/image_logo/password_not_match.png'); /* Replace with your tick icon */
  background-repeat: no-repeat;
  background-position: right center;
}

.selection_info {
  min-height: fit-content;
  border: 2px solid #aabff9;
  border-radius: 4px;
  padding: 9px;
  /* margin-bottom: 20px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  box-shadow: inset 0 1px 1px rgba(0,0,0,.05); */
}

.title_info_pill_button {
  background-color: #222d32;
  border: none;
  color: white;
  font-weight: bold;
  padding: 2px 10px;
  text-align: center;
  text-decoration: none;
  /* display: inline-block; */
  margin: 4px 2px;
  border-radius: 16px;
  /* font-family: sans-serif; */
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

.select2-container--default .select2-selection--multiple .select2-selection__choice
{
  background: #3c8dbc;
} 

</style>
<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>
  <div class="row">
    <div class="col-md-12">
      <!-- Custom Tabs -->
      <!-- <h2 class="edi_header">Account Creation</h2> -->
      <div class="step_info"> Account Setup - Step 1 of 3
      </div>
      <div class="progress progress-xs">
        <div class="progress-bar progress-bar-primary" style="width: 30%"></div>
      </div>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <?php if($flag_show_tab == 'show_tab')
          {
            ?>
            <li class="active"><a class="css_tab">Information</a></li>
            <li class="li_hover"><a style="color:black" >Mapping Information</a></li>
            <li class="li_hover"><a style="color:black">Review</a></li>
            <?php
          }
          else
          {
            ?>
            <li class="active"><a class="css_tab" href="#tab_1" data-toggle="tab" aria-expanded="true">Information</a></li>
            <?php
          }
          ?>
        </ul>
        <div class="tab-content" >
          <div class="tab-pane active" id="tab_1">

            <div class=""> <!-- box-body -->
             <div class="row">
              <div class="col-md-12">
                <!--Body Content box box-primary-->
                <div class="">
                  <div class="box-header" style="padding:0px;">
                    <div class="box-tools pull-right">
                      <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button> -->
                    </div>
                  </div>
                  <div class="box-body">
                    <div class="tab-content tab_body"></div>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
              </div>
            </div>
          </div>
          <br/>
          <div class="box-footer">
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
  var process_customer_guid = '<?php echo $process_customer_guid ?>';
  var process_supplier_guid = '<?php echo $process_supplier_guid ?>';
  var process_action_status = '<?php echo $process_action_status;?>';
  var get_registered_count = '<?php echo $get_registered_count; ?>';
  var admin_guid = '<?php echo $admin_guid; ?>';

  // console.log(admin_guid); 
  $.ajax({
    url:"<?php echo site_url('User_account_setting/list_info');?>",
    method:"POST",
    data:{link_one:link_one,process_supplier_guid:process_supplier_guid},
    beforeSend:function()
    {
      $(".tab_body").empty();
    },
    success:function(data)
    {
      json = JSON.parse(data); 

      if(json.user_id == null) 
      {
        json.user_id = '';
      }

      if(json.user_name == null) 
      {
        json.user_name = '';
      }

      if(json.user_group_guid == null) 
      {
        json.user_group_guid = '';
      }

      if(json.limited_location == null) 
      {
        json.limited_location = '';
      }

      if(json.notification == null) 
      {
        json.notification = '';
      }

      if(json.isactive == '1')
      {
        notification_disabled = '';
        show_active_html = 'show';
      }
      else if(json.isactive == '0')
      {
        notification_disabled = 'disabled';
        show_active_html = 'show';
      }
      else if(json.isactive == '9')
      {
        notification_disabled = '';
        show_active_html = 'reupdate';
      }
      else
      {
        notification_disabled = '';
        show_active_html = '';
      }

      if(process_action_status == 'DUPLICATE')
      {
        readonly = 'readonly';
        disabled = 'disabled';
      }
      else
      {
        readonly = '';
        disabled = '';
      }

      if(json.auto_vendor_code == null)
      {
        json.auto_vendor_code = '';
      }

      methodd = '';

      methodd += '<div class="row">';

      methodd += '<div class="col-md-6" >';

      if(get_registered_count % 5 == 0 && process_action_status != 'EDIT')
      {
        
        methodd += '<mark style="background-color:yellow;font-weight:bold;font-size:16px;">Current User Account : <?php echo $get_registered_count; ?> <br> Additional fees will be charge in your next billing month. </mark>';

        methodd += '<div class="clearfix"></div><br/>';

      }

      methodd += '<table width="100%" >';

      methodd += '<th style="border: none;">Retailer Name : <span style="padding-left: 8px; text-decoration: underline"><?php echo addslashes($acc_name); ?> </span> </th>';

      methodd += '<th style="border: none;">Supplier Name : <span style="padding-left: 8px; text-decoration: underline"><?php echo addslashes($supplier_name); ?> </span> </th>';

      methodd += '</table>';

      // methodd += '<div class="form-group"><label>Retailer Name</label><div><span style="font-weight:bold;"> <?php echo $acc_name; ?> </span></div></div>';

      // methodd += '<div class="form-group"><label>Supplier Name</label><div><span style="font-weight:bold;"> <?php echo $supplier_name; ?> </span></div></div>';
      
      methodd += '<div class="clearfix"></div><br/>';

      if(link_one == '')
      {
        methodd += '<div class="form-group"><label>User List</label><select class="form-control select2" name="add_duplicate_user" id="add_duplicate_user" '+disabled+'> <option value="SELECTION"> -SELECT DATA- </option> <option value="NEW"> CREATE NEW </option> <?php foreach($get_supplier_user as $row) { ?> <option value="<?php echo $row->user_guid?>"><?php echo $row->user_id?> - <?php echo $row->user_name?> </option> <?php } ?> </select></div>';

        methodd += '<div class="clearfix"></div>';
      }

      methodd += '<div class="form-group"><label>User ID</label><input type="text" class="form-control" id="add_user_id" name="add_user_id" autocomplete="off" value="'+json.user_id+'" '+readonly+'/></div>';

      methodd += '<div class="clearfix"></div>';

      methodd += '<div class="form-group"><label>User Name</label><input type="text" class="form-control" id="add_user_name" name="add_user_name" autocomplete="off" value="'+json.user_name+'" '+readonly+'/></div>';

      methodd += '<div class="clearfix"></div>';

      if(link_one == '')
      {
        methodd += '<label>Password</label><div class="input-group"><input type="password" class="form-control" id="add_password" name="add_password" autocomplete="off" '+readonly+' /> <span class="input-group-addon" id="view_pass" style="cursor:pointer;"><i class="glyphicon glyphicon-eye-open" ></i></span> </div>';
      
        methodd += '<div class="clearfix"></div><br>';

        methodd += '<label>Confirm Password</label><div class="input-group"><input type="password" class="form-control" id="confirm_add_password" name="confirm_add_password" autocomplete="off" '+readonly+'/> <span class="input-group-addon" id="confirm_view_pass" style="cursor:pointer;"><i class="glyphicon glyphicon-eye-open" ></i></span> </div>';

        methodd += '<div class="clearfix"></div><br>';
      }
      
      methodd += '<div class="form-group"><label>User Group</label><select class="form-control select2" name="add_user_group" id="add_user_group" '+disabled+'> <option value=""> -SELECT DATA- </option> <?php foreach($get_user_group as $row) { ?> <option value="<?php echo $row->user_group_guid?>"><?php echo $row->user_group_name?></option> <?php } ?> </select> <span id="append_user_group_alert"></span> </div>';

      methodd += '<div class="clearfix"></div>';

      methodd += '<div class="form-group"><label>Auto Mapping (Supplier/Vendor Code)</label><select class="form-control" name="add_mapping_vc" id="add_mapping_vc" '+disabled+'> <option value=""> -SELECT DATA- </option> <option value="1"> YES </option> <option value="0" > NO </option> </select></div>';

      methodd += '<div class="clearfix"></div>';

      methodd += '<div class="form-group"><label>Auto Mapping (Branch/Outlet)</label><select class="form-control" name="add_limited_location" id="add_limited_location" '+disabled+'> <option value=""> -SELECT DATA- </option> <option value="1"> YES </option> <option value="0" > NO </option> </select></div>';

      // methodd += '<div class="form-group"><label>Daily Notification</label><select class="form-control" name="add_notification" id="add_notification" > <option value=""> -SELECT DATA- </option> <option value="1"> YES </option> <option value="0" > NO </option> </select></div>';

      methodd += '<div class="form-group"><label>Daily Notification <i class="fa fa-question-circle" id="notification_info"></i></label> <button id="notification_list_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="notification_list_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button> <select class="form-control select2" name="add_notification" id="add_notification" multiple="multiple" '+notification_disabled+'> <?php foreach($get_notification_report as $row) { ?> <option value="<?php echo $row->rep_option_guid?>"><?php echo $row->option_description?></option> <?php } ?> </select></div>';

      if(show_active_html == 'show')
      {
        methodd += '<div class="form-group"><label>User Status</label><select class="form-control" name="add_active_status" id="add_active_status" '+disabled+'> <option value=""> -SELECT DATA- </option> <option value="1"> Active </option> <option value="0"> Deactive </option> </select></div>';

        methodd += '<div class="clearfix"></div>';
      }

      methodd +='</div>';

      methodd += '<div class="col-md-6" >';

      methodd += '<span id="user_group_info_append"> </span>';

      methodd += '<span id="notification_info_append"> </span>';
      
      methodd +='</div>';

      methodd += '</div>';
      
      methodd += '<div class="clearfix"></div><br>';

      $('.tab_body').html(methodd);
      //$('.tab_footer').html(methodd_footer);
      $('.select2').select2();
      setTimeout(function(){

        $('#add_limited_location').val(json.limited_location);
        $('#add_mapping_vc').val(json.auto_vendor_code);
        
        if(json.user_group_guid != "")
        {
          $('#add_user_group').val(json.user_group_guid).trigger('change');
          //alert(json.export_format); die;
        }

        if(json.notification != "")
        {
          $('#add_notification').val(json.notification).trigger('change');
        }

        if(show_active_html == 'show')
        {
          $('#add_active_status').val(json.isactive);
          //alert(json.export_format); die;
        }

        if(json.isactive == '0')
        {
          $('#notification_list_all_dis').hide();
          $('#notification_list_all').hide();
        }

        // datepicker
        $('.datepicker').datepicker({
          forceParse: false,
          autoclose: true,
          format: 'yyyy-mm-dd'
        });
        
        $('#view_pass').click(function() {
          var x = document.getElementById("add_password");
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
        });

        $('#confirm_view_pass').click(function() {
          var x = document.getElementById("confirm_add_password");
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
        });

        $(document).on('change','#confirm_add_password',function(){
          var check_pass = $('#add_password').val();
          var confim_check_pass = $('#confirm_add_password').val();
          $('#confirm_add_password').removeClass('icon_tick_match');
          $('#confirm_add_password').removeClass('icon_tick_no_match');

          if(check_pass == confim_check_pass)
          {
            $('#confirm_add_password').addClass('icon_tick_match');
          }
          else
          {
            $('#confirm_add_password').addClass('icon_tick_no_match');
          }

        });

        $(document).on('change','#add_user_group',function(){

          $('.selection_info ').addClass('collapsed-box');
          $('.collapse_info').removeClass('fa fa-minus');
          $('.collapse_info').addClass('fa fa-plus');

          var info_user_group_guid = $('#add_user_group').val();

          if(info_user_group_guid != '')
          {
            $.ajax({
            url : "<?php echo site_url('User_account_setting/fetch_module_description'); ?>",
            method:"POST",
            data:{info_user_group_guid:info_user_group_guid},
            success:function(result)
            {
              json = JSON.parse(result); 

              if(json.title_group_name != null)
              {
                title = "<span class='title_info_pill_button'>"+json.title_group_name+"</span>";
              }
              else
              {
                title = '';
              }

              append = '';

              append += '<div class="box selection_info"> <div class="box-header"> <h3 class="box-title"> User Group Information </h3> '+title+' <div class="box-tools pull-right">  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus collapse_info"></i> <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> </button> </div> </div> <div class="box-body" > <table id="user_group_info_tb" class="table table-bordered table-hover" width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr> <th>Group Module Description</th> </tr> </thead> </table> </div> </div>';

              $('#user_group_info_append').html(append);

              setTimeout(function(){
                if ($.fn.DataTable.isDataTable('#user_group_info_tb')) {
                    $('#user_group_info_tb').DataTable().destroy();
                }

                $('#user_group_info_tb').DataTable({
                  "columnDefs": [
                  ],
                  'processing'  : true,
                  'paging'      : true,
                  'lengthChange': true,
                  'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
                  'searching'   : true,
                  'ordering'    : true,
                  'order'       : [ [0 , 'asc'] ],
                  'info'        : true,
                  'autoWidth'   : true,
                  "bPaginate": true, 
                  "bFilter": true, 
                  "sScrollY": "30vh", 
                  "sScrollX": "100%", 
                  "sScrollXInner": "100%", 
                  "bScrollCollapse": true,
                    data: json['data'],
                    columns: [
                      // { "data": "user_group_name"},
                      { "data": "module_name"},
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
                  },
                  "initComplete": function( settings, json ) {
                    setTimeout(function(){
                      interval();
                    },300);
                  }
                });//close datatable
              },300);
              
            }
            });
          }
          else
          {
            $('#user_group_info_append').html('');
          }

          if(admin_guid.includes(info_user_group_guid))
          {
            $('#append_user_group_alert').html('You are setting user <b>' + $('#add_user_id').val() + '</b> to <b> Admin </b>. System will be <b><mark style="color:red;"> REMOVE </mark></b> the access right and set it to default.');
          }
          else
          {
            $('#append_user_group_alert').html('');
          }

        });

        $(document).on('change','#add_duplicate_user',function(){
          var add_duplicate_user = $('#add_duplicate_user').val();

          if(process_supplier_guid == '' || process_supplier_guid == null || process_supplier_guid == 'null' )
          {
            alert('Supplier Data Not Found.');
            return;
          }

          if((add_duplicate_user != 'NEW') && (add_duplicate_user != 'SELECTION'))
          {
            $.ajax({
              url : "<?php echo site_url('User_account_setting/fetch_user_details'); ?>",
              method:"POST",
              data:{add_duplicate_user:add_duplicate_user,add_duplicate_supplier:process_supplier_guid},
              success:function(result)
              {
                json = JSON.parse(result); 

                console.log(json);
                console.log(Object.keys(json['data']).length);

                if(Object.keys(json['data']).length == 0)
                {
                  alert('Data Not Found. Please Contact Admin.');
                  return;
                }
                else if(Object.keys(json['data']).length > '1')
                {
                  alert('Data Found More Than One. Please Contact Admin.');
                  return;
                }
                else
                {
                  $('#add_user_id').val(json['data'][0]['user_id']);
                  $('#add_user_id').prop('readonly',true);
                  $('#add_user_name').val(json['data'][0]['user_name']);
                  $('#add_user_name').prop('readonly',true);
                  $('#add_user_group').val(json['data'][0]['user_group_guid']).trigger('change');
                  $('#add_limited_location').val(json['data'][0]['limited_location']).trigger('change');
                  $('#add_password').prop('disabled',true);
                  $('#confirm_add_password').prop('disabled',true);
                }

              }
            });
          }
          else
          {
            $('#add_user_id').val('');
            $('#add_user_id').prop('readonly',false);
            $('#add_user_name').val('');
            $('#add_user_name').prop('readonly',false);
            $('#add_user_group').val('').trigger('change');
            $('#add_limited_location').val('').trigger('change');
            $('#add_password').prop('disabled',false);
            $('#confirm_add_password').prop('disabled',false);
          }

        });

        $(document).on('change','#add_active_status',function(){
          var verify_active_status = $('#add_active_status').val();

          if(verify_active_status == '0')
          {
            $("#add_notification option").prop('selected',false);
            $('#add_notification').prop('disabled', true);
            $('#notification_list_all_dis').hide();
            $('#notification_list_all').hide();
            var verify_selectedOptions = $('#add_notification').val();
            $(".select2").select2();
          }
          else
          {
            $('#add_notification').prop('disabled', false);
            $('#notification_list_all_dis').show();
            $('#notification_list_all').show();
            var verify_selectedOptions = $('#add_notification').val();
            $(".select2").select2();
          }
        });

        $(document).on('click','#notification_info',function(){
          $('.selection_info ').addClass('collapsed-box');
          $('.collapse_info').removeClass('fa fa-minus');
          $('.collapse_info').addClass('fa fa-plus');

          $.ajax({
            url : "<?php echo site_url('User_account_setting/fetch_notification_report'); ?>",
            method:"POST",
            data:{},
            success:function(result)
            {
              json = JSON.parse(result); 

              append = '';

              append += '<div class="box selection_info"> <div class="box-header"> <h3 class="box-title"> Notification Information </h3> <div class="box-tools pull-right">  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus collapse_info"></i> <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> </button> </div> </div> <div class="box-body" > <table id="notification_info_tb" class="table table-bordered table-hover" width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr> <th>Notification Name</th> <th>Description</th> </tr> </thead> </table> </div> </div>';

              $('#notification_info_append').html(append);

              setTimeout(function(){
                if ($.fn.DataTable.isDataTable('#notification_info_tb')) {
                    $('#notification_info_tb').DataTable().destroy();
                }

                $('#notification_info_tb').DataTable({
                  "columnDefs": [
                  ],
                  'processing'  : true,
                  'paging'      : true,
                  'lengthChange': true,
                  'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
                  'searching'   : true,
                  'ordering'    : true,
                  'order'       : [ [0 , 'asc'] ],
                  'info'        : true,
                  'autoWidth'   : true,
                  "bPaginate": true, 
                  "bFilter": true, 
                  "sScrollY": "30vh", 
                  "sScrollX": "100%", 
                  "sScrollXInner": "100%", 
                  "bScrollCollapse": true,
                    data: json['data'],
                    columns: [
                      { "data": "log_table"},
                      { "data": "option_description"},
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
                  },
                  "initComplete": function( settings, json ) {
                    setTimeout(function(){
                      interval();
                    },300);
                  }
                });//close datatable
              },300);
              
            }
          });
        });//close search button

        $(document).on('change','#add_user_id',function(){
          $('#add_user_id').removeClass('icon_tick_match');
          $('#add_user_id').removeClass('icon_tick_no_match');
          var user_id_input = $('#add_user_id').val();
          var duplicate_input = $('#add_duplicate_user').val();
          var trigger_ajax = '0';

          if((add_duplicate_user != 'NEW') && (add_duplicate_user != 'SELECTION'))
          {
            trigger_ajax == '1';
          }

          if((add_user_id == '') || (add_user_id == null) || (add_user_id == 'null'))
          {
            trigger_ajax == '1';
          }
          else if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(user_id_input))
          {
            trigger_ajax == '1';
            alert('Invalid User ID (Email Address)');
            $('#add_user_id').addClass('icon_tick_no_match');
            return;
          }

          if(trigger_ajax == '0')
          {
            $.ajax({
              url:"<?php echo site_url('User_account_setting/verify_user_info') ?>",
              method:"POST",
              data:{user_id_input:user_id_input},
              beforeSend:function(){
                $('.btn').button('loading');
                swal.fire({
                  allowOutsideClick: false,
                  title: 'Verifying...',
                  showCancelButton: false,
                  showConfirmButton: false,
                  onOpen: function () {
                  swal.showLoading()
                  }
                });
              },
              complete: function() {
                  setTimeout(function() {
                      Swal.close();
                  }, 300);
              },
              success:function(data)
              {
                json = JSON.parse(data);
                if(json.para1 == 'false')
                {
                  $('.btn').button('reset');
                  // $('#alertmodal').modal('hide');
                  alert(json.msg);
                  $('#add_user_id').addClass('icon_tick_no_match');
                }
                else
                {
                  $('.btn').button('reset');
                  // $('#alertmodal').modal('hide');
                  // $("#medium-modal").modal('hide');
                  alert(json.msg);
                  $('#add_user_id').addClass('icon_tick_match');
                }
              }//close success
            });//close ajax 
          }

        });//close change user


      },300);
    }//close success
  });//close ajax

  $(document).on('click','#next_process',function(){

    var add_customer_guid = process_customer_guid;
    var add_supplier_guid = process_supplier_guid;
    var add_user_id = $('#add_user_id').val();
    var add_user_name = $('#add_user_name').val();
    var add_password = $('#add_password').val();
    var confirm_add_password = $('#confirm_add_password').val();
    var add_user_group = $('#add_user_group').val();
    var add_mapping_vc = $('#add_mapping_vc').val();
    var add_limited_location = $('#add_limited_location').val();
    var add_active_status = $('#add_active_status').val();
    var checkbox_pass = $('#checkbox_pass').val();
    var action_type = 'process';
    var add_duplicate_user = $('#add_duplicate_user').val();
    var array_notification = $('#add_notification').val();
    var array_notification_not_selected = $('#add_notification option:not(:selected)').map(function() {
      return $(this).val();
    }).get();
    var additional_message = '';

    var add_notification = [];
    var notselected_notification = [];

    $.each(array_notification, function(index, value) {
      add_notification.push({'rep_option_guid':value,'action_type':'insert'})
    });

    $.each(array_notification_not_selected, function(index, value) {
      notselected_notification.push({'rep_option_guid':value,'action_type':'delete'})
    });

    if((add_customer_guid == '') || (add_customer_guid == null) || (add_customer_guid == 'null'))
    {
      alert('Invalid Process. ERROR NEXT PROCESS 001');
      return;
    }

    if((add_supplier_guid == '') || (add_supplier_guid == null) || (add_supplier_guid == 'null'))
    {
      alert('Invalid Process. ERROR NEXT PROCESS 002');
      return;
    }

    if((add_user_id == '') || (add_user_id == null) || (add_user_id == 'null'))
    {
      alert('Please Insert User ID.');
      return;
    }
    else
    {
      if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(add_user_id))
      {
        alert('Invalid User ID (Email Address)');
        return;
      }
    }

    if((add_user_name == '') || (add_user_name == null) || (add_user_name == 'null'))
    {
      alert('Please Insert User Name.');
      return;
    }

    if(link_one == '')
    {
      if(add_duplicate_user == 'NEW' || add_duplicate_user == 'SELECTION')
      {
        if((add_password == '') || (add_password == null) || (add_password == 'null'))
        {
          alert('Please Insert Password.');
          return;
        }
        
        if((confirm_add_password == '') || (confirm_add_password == null) || (confirm_add_password == 'null'))
        {
          alert('Please Insert Confirm Password.');
          return;
        }

        if(add_password != confirm_add_password)
        {
          alert('Incorrect Confirm Password.');
          return;
        }
      }
    }
    else
    {
      action_type = 'update'
    }

    if((add_user_group == '') || (add_user_group == null) || (add_user_group == 'null'))
    {
      alert('Please Select User Group.');
      return;
    }

    if(admin_guid.includes(add_user_group))
    {
      additional_message = 'You are setting user <b>' + add_user_id + '</b> to <b> Admin </b>. System will <b><mark style="color:red;"> REMOVE </mark></b> your access right and set it to default.';
    }

    if((add_mapping_vc == '') || (add_mapping_vc == null) || (add_mapping_vc == 'null'))
    {
      alert('Please Select Auto Mapping Supplier/Vendor Code.');
      return;
    }

    if((add_limited_location == '') || (add_limited_location == null) || (add_limited_location == 'null'))
    {
      alert('Please Select Auto Mapping Branch/Outlet Code.');
      return;
    }

    // if((add_notification == '') || (add_notification == null) || (add_notification == 'null'))
    // {
    //   alert('Please Select Daily Notification.');
    //   return;
    // }

    if(show_active_html == 'show')
    {
      if((add_active_status == '') || (add_active_status == null) || (add_active_status == 'null'))
      {
        alert('Please Select User Status.');
        return;
      }
      else if(add_active_status == '0')
      {
        if(admin_guid.includes(add_user_group))
        {
          alert('Deactive User cannot select ADMIN Group.');
          return;
        }

        additional_message = 'You are setting user <b>' + add_user_id + '</b> to <b> Deactive </b>. System will skip the remaining steps.';
 
      }
    }

    if(add_duplicate_user != 'NEW' && add_duplicate_user != 'SELECTION' && add_duplicate_user != undefined && add_duplicate_user != 'undefined')
    {
      action_type = 'duplicate';
    }

    confirmation_modal('Are you sure want to proceed? <br>' + additional_message );
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('User_account_setting/process_info') ?>",
        method:"POST",
        data:{link_one:link_one,add_customer_guid:add_customer_guid,add_supplier_guid:add_supplier_guid,add_user_id:add_user_id,add_user_name:add_user_name,add_password:add_password,add_user_group:add_user_group,add_mapping_vc:add_mapping_vc,add_limited_location:add_limited_location,action_type:action_type,add_active_status:add_active_status,add_duplicate_user:add_duplicate_user,add_notification:add_notification,notselected_notification:notselected_notification},
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

          get_link = json.get_link;

          if(json.para1 == 'false')
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            // alert(json.msg);
            if(action_type == 'update')
            {
              Swal.fire({
                title: json.msg, 
                text: '', 
                type: "error",
                allowOutsideClick: false,
                showConfirmButton: true,
                onClose: error_close_process_update
              })
            }
            else
            {
              Swal.fire({
                title: json.msg, 
                text: '', 
                type: "error",
                allowOutsideClick: false,
                showConfirmButton: true,
                onClose: error_close_process_insert
              })
            }

          }
          else if(json.para1 == 'true' && json.valid_process == 'REMOVE')
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
              onClose: redirect_main
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
              onClose: close_process
            })
          }
         
        }//close success
        
      });//close ajax 
    });//close document yes click
  });//close redirect

  $(document).on('click', '#notification_list_all', function(){

    var isDisabled = $('#add_notification').prop('disabled');

    if(isDisabled)
    {
      alert('User Status active only can subscribe');
      return;
    } 
    else 
    {
      $("#add_notification option").prop('selected',true);
      var selectedOptions = $('#add_notification').val();
      $(".select2").select2();
    }

  });//CLOSE ONCLICK  

  $(document).on('click', '#notification_list_all_dis', function(){
    $("#add_notification option").prop('selected',false);
    var selectedOptions = $('#add_notification').val();
    $(".select2").select2();
  });//CLOSE ONCLICK 

  function error_close_process_update() {
    setTimeout(function() {
      $('.sidebar-collapse').css('padding-right','0px');
    }, 300); 
    window.location = "<?= site_url('User_account_setting/information?link_one=');?>"+link_one;
  }

  function error_close_process_insert() {
    setTimeout(function() {
      $('.sidebar-collapse').css('padding-right','0px');
    }, 300); 
    window.location = "<?= site_url('User_account_setting/information?link=');?>"+process_supplier_guid;
  }

  function close_process() {
    setTimeout(function() {
      $('.sidebar-collapse').css('padding-right','0px');
    }, 300); 
    window.location = "<?= site_url('User_account_setting/mapping_information?link_one=');?>"+get_link;
  }

  function redirect_main() {
    setTimeout(function() {
      $('.sidebar-collapse').css('padding-right','0px');
    }, 300); 
    window.location = "<?= site_url('User_account_setting');?>";
  }

  $(document).on('click','#skip_process',function(){
    window.location = "<?= site_url('User_account_setting/mapping_information?link_one=');?>"+link_one;
  });//close redirect

});
</script>

