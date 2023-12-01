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

.summary_info {
  min-height: fit-content;
  border: 1px solid white;
  border-radius: 4px;
  padding: 9px;
  background-color: ghostwhite;
  /* margin-bottom: 20px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  box-shadow: inset 0 1px 1px rgba(0,0,0,.05); */
}

.summary_info:hover {
  border: 1px solid #51c4f5;
}

.table tbody > tr:hover {
  background-color: #5c98ff; /* Change this to your desired hover color */
  font-weight:bold;
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

</style>
<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>
  <div class="row">
    <div class="col-md-12">
      <!-- Custom Tabs -->
      <!-- <h2 class="header_name">Account Creation</h2> -->
      <div class="step_info"> Account Setup - Step 3 of 3 </div>
      <div class="progress progress-xs">
        <div class="progress-bar progress-bar-primary" style="width: 90%"></div>
      </div>
      <span id="count_code_tb"></span>
      <span id="count_outlet_tb"></span>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <?php if($flag_show_tab == 'show_tab')
          {
            ?>
            <li class="li_hover"><a style="color:black">Information</a></li>
            <li class="li_hover"><a style="color:black">Mapping Information</a></li>
            <li class="active"><a class="css_tab">Review</a></li>
            <?php
          }
          else
          {
            ?>
            <li class="disabled"><a href="#tab_1" data-toggle="tab" aria-expanded="false" >Information</a></li>
            <li class="disabled"><a href="#tab_2" data-toggle="tab" aria-expanded="false" >Mapping Information</a></li>
            <li class="active"><a class="css_tab" href="#tab_4" data-toggle="tab" aria-expanded="true" >Review</a></li>
            <?php
          }
          ?>
        </ul>
        <div class="tab-content" >
          <?php
          if($get_registered_count % 5 == 0 && $process_action_status != 'EDIT')
          {
            ?>
            <mark style="background-color:yellow;font-weight:bold;font-size:16px;">Current User Account : <?php echo $get_registered_count; ?> <br> Additional fees will be charge in your next billing month. </mark>
            <?php
          }
          ?>

          <div class="tab-pane active" id="tab_2">

            <div class="row">
              <div class="col-md-12">
                <div class="box-body summary_info">
                  <h4 class="box-title">User Information
                    <div class="pull-right">

                    </div>
                  </h4>
                  <table id="info_tb" class="table table-hover" width="100%" cellspacing="0">
                    <thead style="white-space: nowrap;">
                      <tr>
                        <th>Retailer Name</th>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>User Group</th>
                        <th>User Status</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>

              <div class="clearfix"></div><br/>
            
              <!-- LEFT TABLE COLUMN -->
              <div class="col-md-6">
                <div class="box-body summary_info">
                  <h4 class="box-title">Mapped Vendor Code
                    <div class="pull-right">

                    </div>
                  </h4>
                  <table class="table table-hover" id="mapping_code_tb" style="width: 100%;">
                  <thead style="white-space: nowrap;">
                    <tr>
                      <th>Name</th>
                      <th>Code</th>
                    </tr>
                  </thead>
                  </table>
                </div>

                <div class="clearfix"></div><br/>

                <div class="box-body summary_info">
                  <h4 class="box-title">Daily Notification
                    <div class="pull-right">

                    </div>
                  </h4>
                  <table class="table table-hover" id="daily_notification_tb" style="width: 100%;">
                  <thead style="white-space: nowrap;">
                    <tr>
                      <th>Notification Name</th> 
                      <th>Description</th>
                    </tr>
                  </thead>
                  <tbody> 
                  </tbody>
                  </table>
                </div>
                 
                <!-- More Table Info -->
              </div>

              <!-- RIGHT TABLE COLUMN -->
              <div class="col-md-6">
                <div class="box-body summary_info">
                  <h4 class="box-title">Mapped Outlet Code
                    <div class="pull-right">

                    </div>
                  </h4>
                  <table class="table table-hover" id="mapping_outlet_tb" style="width: 100%;">
                  <thead style="white-space: nowrap;">
                    <tr>
                      <th>Outlet Name</th>
                      <th>Outlet Code</th>
                    </tr>
                  </thead>
                  <tbody> 
                  </tbody>
                  </table>
                </div>

                <div class="clearfix"></div><br/>

                <!-- More Table Info -->

              </div>

            </div>
        
            <br/>

            <div class="box-footer">
              <button id="back_process" type="button" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> Back</button>
              <button id="next_process" type="button" class="btn btn-primary"  style="float:right;"><i class="fa fa-arrow-circle-right"></i> Done</button>
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
  var process_supplier_guid = '<?php echo $process_supplier_guid ?>';
  var user_active_status = '<?php echo $user_active_status ?>';

  information_table = function(link_one,process_user_guid,process_customer_guid) {
    $.ajax({
      url : "<?php echo site_url('User_account_setting/summary_info_tb');?>",
      method: "POST",
      data:{link_one:link_one,process_user_guid:process_user_guid,process_customer_guid:process_customer_guid},
      beforeSend : function() {
          $('.btn').button('loading');
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success : function(data)
      {  
        json = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#info_tb')) {
            $('#info_tb').DataTable().destroy();
        }

        $('#info_tb').DataTable({
          "columnDefs": [],
          'processing'  : true,
          'paging'      : false,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : false,
          'ordering'    : false,
          'order'       : [ [0, 'asc'] ],
          'info'        : true,
          'autoWidth'   : true,
          "bPaginate": false, 
          "bFilter": false, 
          "sScrollY": "50vh", 
          "sScrollX": "100%", 
          "sScrollXInner": "100%", 
          "bScrollCollapse": true,
            data: json['data'],
            columns: [
                { "data" : "acc_name"},
                { "data" : "user_id"},
                { "data" : "user_name"},
                { "data" : "user_group_name"},
                { "data" : "isactive",render:function( data, type, row ){
                  var element = '';

                  if(data == '1')
                  {
                    element = 'Active';
                  }
                  else if(data == '0')
                  {
                    element = 'Deactive';
                  }
                  else
                  {
                    element = 'Process';
                  }

                  return element;
                }},
            ],
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtp", 
          "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              $(nRow).closest('tr').css({"cursor": "pointer"});

            // $(nRow).attr('status', aData['status']);
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
  information_table(link_one,process_user_guid,process_customer_guid);

  mapping_table_code = function(link_one,process_user_guid) {
    $.ajax({
      url : "<?php echo site_url('User_account_setting/list_code_tb');?>",
      method: "POST",
      data:{link_one:link_one,process_user_guid:process_user_guid},
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
          "columnDefs": [],
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [1, 'asc'] ],
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
              { "data" : "supcus_name"},
              { "data" : "supplier_group_name"},
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
  mapping_table_code(link_one,process_user_guid);

  mapping_table_outlet = function(process_user_guid,process_customer_guid) {
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
          "columnDefs": [],
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [1, 'asc'] ],
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
                { "data" : "branch_desc"},
                { "data" : "branch_code"},
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
  mapping_table_outlet(process_user_guid,process_customer_guid);

  daily_notification = function(process_user_guid,process_customer_guid) {
    $.ajax({
      url : "<?php echo site_url('User_account_setting/view_daily_notification_tb');?>",
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
        if ($.fn.DataTable.isDataTable('#daily_notification_tb')) {
            $('#daily_notification_tb').DataTable().destroy();
        }

        $('#daily_notification_tb').DataTable({
          "columnDefs": [],
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [1, 'asc'] ],
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
                { "data" : "log_table"},
                { "data" : "option_description"},
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
  daily_notification(process_user_guid,process_customer_guid);

  $(document).on('click', '#next_process', function(){

    var code_tb_count = $('#code_tb_count').val();
    var outlet_tb_count = $('#outlet_tb_count').val();
    var process_action_status = '<?php echo $process_action_status ?>';
    var process_supplier_guid = '<?php echo $process_supplier_guid ?>';

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

    if(process_action_status == '' || process_action_status == null || process_action_status == 'null')
    {
      alert('Invalid Process');
      return;
    }

    if(process_supplier_guid == '' || process_supplier_guid == null || process_supplier_guid == 'null')
    {
      alert('Invalid Process');
      return;
    }

    confirmation_modal('Are you sure all information all correct to proceed?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('User_account_setting/final_process') ?>",
        method:"POST",
        data:{link_one:link_one,process_user_guid:process_user_guid,process_customer_guid:process_customer_guid,process_action_status:process_action_status,process_supplier_guid:process_supplier_guid},
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
              onClose: error_close_process
            })
          }
          else
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
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
  });//CLOSE ONCLICK

  function close_process() {
    setTimeout(function() {
      $('.sidebar-collapse').css('padding-right','0px');
    }, 300); 
    window.location = "<?= site_url('User_account_setting');?>";
  }

  function error_close_process() {
    setTimeout(function() {
      $('.sidebar-collapse').css('padding-right','0px');
    }, 300); 
    //window.location = "<?= site_url('User_account_setting/final_summary?link_one=');?>"+link_one;
  }

  $(document).on('click','#back_process',function(){
    window.location = "<?= site_url('User_account_setting/mapping_information?link_one=');?>"+link_one;
  });//close redirect

});
</script>

