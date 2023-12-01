<style type="text/css">
.content-wrapper{
  min-height: 900px !important; 
}

.css_1 {
  float: left;
  background: transparent;
  margin-top: 0;
  margin-bottom: 0;
  /**padding: 7px 5px;**/
  right: 10px;
  border-radius: 2px;
  width: fit-content;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
  /* width: fit-content; */
}

.blinker_error {
  animation: blink-animation 5s steps(10, start) infinite;
  -webkit-animation: blink-animation 2s steps(10, start) infinite;
  background-color: red;
  font-weight: bold;
  font-size:14px;
  color:white;
}

@keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
@-webkit-keyframes blink-animation {
  to {
    visibility: hidden;
  }
}

.highlight_selected {
  background-color: #9df9a6;
}

.pill_button {
  font-size: 13px;
}

.table tbody > tr:hover {
  background-color: #acc9fa; /* Change this to your desired hover color */
  font-weight:bold;
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
  <div class="row">
    <div class="col-md-12" >
      <div class="css_1">
      </div>

      <a class="btn btn-app" id="create_btn" style="background-color:#aedffb;float:left;font-weight: bold;" >
        <i class="fa fa-user-plus" ></i> 
        <span class="badge bg-red" style="font-size: 16px">
          <?php echo $get_pending_creation ?> 
        </span>
        <span style="font-size: 12px;color:black;"> Create User </span>
      </a>

      <!-- <a class="btn btn-app" id="duplicate_from_retailer" style="background-color:#fbbc9e;font-weight: bold;" >
        <i class="glyphicon glyphicon-transfer" ></i> 
        <span style="font-size: 12px;color:black;"> Duplicate User </span>
      </a> -->
    </div>

    <div class="col-md-6">
      <div class="box">
        <div class="box-header with-border">
        <h3 class="box-title">Profile Information</h3>
        <div class="box-tools pull-right">
        </div> 
        <!--end pull right button -->
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-4"><b>Retailer Name </b></div>
            <div class="col-md-8">
              <span> <?php echo $acc_name; ?> </span>
            </div>
            <div class="clearfix"></div><br>

            <div class="col-md-4"><b>Supplier Name </b></div>
            <div class="col-md-8">
            <select class="form-control select2" name="select_supplier" id="select_supplier">
              <?php foreach($get_supplier as $row)
              {
                ?>
                <option value="<?php echo $row->supplier_guid?>" name_val="<?php echo $row->supplier_name?>" ><?php echo $row->supplier_name?></option>
                <?php
              }
              ?>
            </select>
            <!-- <span id="append_supplier"> </span> -->
            </div>
            <div class="clearfix"></div><br>

            <div class="col-md-4"><b>User ID</b></div>
            <div class="col-md-8">
            <span> <?php echo $user_id; ?> </span>
            </div>
            <div class="clearfix"></div><br>

            <div class="col-md-4"><b>User Name </b></div>
            <div class="col-md-8">
            <span> <?php echo $user_name; ?> </span>
            </div>
            <div class="clearfix"></div><br>

            <div class="col-md-4"><b>User Group </b></div>
            <div class="col-md-8">
            <span> <?php echo $_SESSION['user_group_name']; ?> </span>
            </div>
            <div class="clearfix"></div><br>
            
          </div> 
        </div>
        </div>  
      </div>
    </div>

    <div class="col-md-6">
      <div class="box">
        <div class="box-header with-border">
        <h3 class="box-title">Registered User</h3>
        <span id="count_registered"></span>
        <div class="box-tools pull-right">
        <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button> -->
        </div> 
        <!--end pull right button -->
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
            <table id="account_registered" class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead style="white-space: nowrap;">
                  <tr>
                    <th>Retailer Name</th>
                    <th>Active User Count</th>
                  </tr>
                </thead>
            </table>
        </div>  
      </div>
    </div>

    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
        <h3 class="box-title">Dashboard User Info
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
                    <!-- <th>Retailer Name</th> -->
                    <!-- <th>Supplier Name</th> -->
                    <th>User ID</th>
                    <th>User Name</th>
                    <!-- <th>Password</th> -->
                    <th>User Group</th>
                    <!-- <th>Vendor Code</th> -->
                    <!-- <th>Outlet</th> -->
                    <th>Status</th>
                    <th>Updated At</th>
                    <th>Updated By</th>
                    <th>Action</th>
                    <!-- <th>Sorting User</th>
                    <th>Sorting Retailer</th> -->
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
var supplier_guid = '';
var session_customer_guid = '<?php echo $_SESSION['customer_guid'];?>';
var selected_customer_guid = '';
$(document).ready(function() {
  var supplier_guid = $('#select_supplier').val();
  var supplier_name = $('#select_supplier option:selected').attr('name_val');
  $('.for_supp_name').addClass('pill_button');
  $('.for_supp_name').html(supplier_name);
  // $('#append_supplier').html(supplier_name);
  //console.log(supplier_name); 

  $(document).on('change','#select_supplier',function(){
    supplier_guid = $('#select_supplier').val();
    var supplier_name = $('#select_supplier option:selected').attr('name_val');
    // $('#append_supplier').html(supplier_name);

    $('.for_supp_name').addClass('pill_button');
    $('.for_supp_name').html(supplier_name);
    
    main_table(supplier_guid,selected_customer_guid);
    registered_tb(supplier_guid);
  });

  // $(document).on('click','#registered_selected_remove',function(){
  //   supplier_guid = $('#select_supplier').val();
  //   var selected_customer_guid = $(this).attr('reg_val_1');
  //   var selected_customer_name = $(this).attr('reg_val_2');
  //   $('.option_selection').css('background-color','');

  //   if(selected_customer_guid == session_customer_guid)
  //   {
  //     $(this).css('background-color','#7feded');
  //   }
  //   else
  //   {
  //     $(this).css('background-color','#9df9a6');
  //   }
      
  //   $(this).css('background-color','#7feded');

  //   $('.for_acc_name').addClass('pill_button');
  //   $('.for_acc_name').html(selected_customer_name + ' <i class="fa fa-close" style="color:red;"></i>');
  //   main_table(supplier_guid,selected_customer_guid);
  // });

  // $(document).on('click','.pill_button',function(){
  //   supplier_guid = $('#select_supplier').val();
  //   $('.option_selection').css('background-color','');
  //   $('.highlight_selected').css('background-color','#9df9a6');

  //   $('.for_acc_name').removeClass('pill_button');
  //   $('.for_acc_name').html('');
  //   main_table(supplier_guid,selected_customer_guid);
  // });
  
  registered_tb = function(supplier_guid) {
    $.ajax({
      url : "<?php echo site_url('User_account_setting/user_registered_tb');?>",
      method: "POST",
      data:{supplier_guid:supplier_guid},
      beforeSend : function() {
          $('.btn').button('loading');
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success : function(data)
      {  
        json = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#account_registered')) {
            $('#account_registered').DataTable().destroy();
        }

        $('#account_registered').DataTable({
        "columnDefs": [],
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [ [1, 'desc'] ],
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true, 
        "bFilter": true, 
        "sScrollY": "10vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
          data: json['data'],
          columns: [
            { "data" : "acc_name",render: function ( data, type, row ) {
              var append_class = '';
              var element = '';
              var element1 = row['acc_guid'];

              if(element1 == session_customer_guid)
              {
                append_class ='highlight_selected';
              }

              element += '<span id="registered_selected" class="label label-default option_selection '+append_class+'" style="font-size: 14px;" reg_val_1="'+row['acc_guid']+'" reg_val_2="'+row['acc_name']+'">'+data+'</span>';

              return element;

            }},
            { "data" : "count_data",render:function( data, type, row , meta ){
              var element = '';

              element += '<div class="col-md-6">';

              element += '<span style="float:left;font-size: 16px;"> Total :'+data+' <i class="fa fa-users" ></i></span>';

              element += '</div>';

              element += '<div class="col-md-6">';

              element += '<span style="float:left;font-size: 16px;"> Active : '+row['active_user']+' <i class="fa fa-users" ></i></span>';

              element += '</div>';

              element += '<div class="col-md-6">';

              element += '<span style="float:left;font-size: 16px;"> Deactive : '+row['deactive_user']+' <i class="fa fa-users" ></i></span>';

              element += '</div>';

              element += '<div class="col-md-6">';

              element += '<span style="float:left;font-size: 16px;"> Incomplete : '+row['incomplete_user']+' <i class="fa fa-users" ></i></span>';

              element += '</div>';

              return element;
            }},
          ],
          dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",  
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
          var value_data = aData['count_data'];
          $('#count_registered').html('<input type="hidden" id="count_value_register" name="count_value_register" value='+value_data+' readonly> ');
          // $(nRow).closest('tr').css({"cursor": "pointer"});
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
  registered_tb(supplier_guid);

  main_table = function(supplier_guid,selected_customer_guid) {
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
        { "orderable": false, "targets": [6]},
        <?php if(in_array('IAVA',$this->session->userdata('module_code')))
          {
            ?>
            { visible: true, targets: [5]}
            <?php
          }
          else
          {
            ?>
            { visible: false, targets: [5]}
            <?php
          }
          ?>
        // { visible: false, targets: [9,10]},
      ],
      "ajax": {
        "url": "<?php echo site_url('User_account_setting/user_info_tb') ?>",
        "type": "POST",
        "data": function(data) {
          data.supplier_guid = supplier_guid
          data.selected_customer_guid = selected_customer_guid
        },
      },
      columns: [
          // { "data" : "acc_name"},
          // { "data" : "supplier_name"},
          { "data" : "user_id",render:function( data, type, row ){
            var element = '';
            var element1 = row['admin_active'];

            if(element1 == '2')
            {
              element += '<span style="float:right;"><i class="glyphicon glyphicon-king"></i></span>' + data;
            }
            else
            {
              element += data;
            }

            return element;
          }},
          { "data" : "user_name"},
          // { "data" : "user_password",render:function( data, type, row , meta ){
          //   var element = '';

          //   element = '';

          //   return element;
          // }},
          { "data" : "user_group_name"},
          { "data" : "status_naming",render:function( data, type, row ){
            var element = '';

            if(data == 'Deactive')
            {
              element += '<span style="float:right;"><i class="fa fa-close"></i></span>' + data;
            }
            else if(data == 'Incomplete')
            {
              element += '<span style="float:right;"><i class="fa fa-hourglass-half"></i></span>' + data;
            }
            else
            {
              element += data;
            }

            return element;
          }},
          { "data" : "updated_at"},
          { "data" : "updated_by"},
          { "data" : "user_guid" ,render:function( data, type, row ){

            var element = '';
            var element1 = row['status_naming'];

            // element += '<button id="view_code" style="margin-left:5px;" title="Code" class="btn btn-xs btn-warning" vc_data_1="'+row['user_guid']+'" vc_data_2="'+row['acc_guid']+'" vc_data_3="'+row['relation_supplier_guid']+'" vc_data_4="'+row['user_name']+'" vc_data_5="'+row['acc_name']+'" vc_data_6="'+row['supplier_name']+'"><i class="fa fa-eye"></i> Vendor Code</button>';

            // element += '<button id="view_outlet" style="margin-left:5px;" title="Mapping Information" class="btn btn-xs btn-warning" vo_data_1="'+row['user_guid']+'" vo_data_2="'+row['acc_guid']+'" vo_data_3="'+row['relation_supplier_guid']+'" vo_data_4="'+row['user_name']+'" vo_data_5="'+row['acc_name']+'" vo_data_6="'+row['supplier_name']+'"><i class="fa fa-eye"></i> Mapping Info. </button>';

            if(element1 == 'Active' || element1 == 'Deactive')
            {
              element += '<button id="edit_btn" style="margin-left:5px;" title="Edit" class="btn btn-xs btn-primary" ed_uo_data_1="'+row['user_guid']+'" ed_uo_data_2="'+row['acc_guid']+'" ed_uo_data_3="'+row['relation_supplier_guid']+'" action="redirect_user"><i class="fa fa-pencil"></i></button>';

              element += '<button id="redirect_view_outlet" style="margin-left:5px;" title="Mapping Information" class="btn btn-xs btn-primary" vo_data_1="'+row['user_guid']+'" vo_data_2="'+row['acc_guid']+'" vo_data_3="'+row['relation_supplier_guid']+'" action="redirect_vo" ><i class="fa fa-sitemap"></i></button>';

              if(element1 == 'Active')
              {
                element += '<button id="send_user_info" style="margin-left:5px;" title="Send User Information" class="btn btn-xs btn-primary" se_data_1="'+row['user_guid']+'" se_data_2="'+row['acc_guid']+'" se_data_3="'+row['relation_supplier_guid']+'" ><i class="fa fa-send"></i></button>';
              }

              // transfer user from retailer or supplier 
              <?php
              if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
              {
                ?>
                element += '<button id="transfer_btn" style="margin-left:5px;" title="Transfer User" class="btn btn-xs btn-primary" dp_uo_data_1="'+row['user_guid']+'" dp_uo_data_2="'+row['acc_guid']+'" dp_uo_data_3="'+row['relation_supplier_guid']+'"><i class="glyphicon glyphicon-share-alt"></i></button>';
                <?php
              }
              ?>
              
            }

            return element;
          }},
          // { "data" : "sorting_user"},
          // { "data" : "sorting_retailer"},
      ],
      //dom: 'lBfrtip',
      dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
      // buttons: [
      //   'copy', 'csv', 'excel', 'pdf', 'print'
      // ]
      "fnCreatedRow": function( nRow, aData, iDataIndex ) 
      {
        $(nRow).closest('tr').css({"cursor": "pointer"});
        if(aData['status_naming'] == 'Deactive' )
        {
          $(nRow).find('td:eq(0)').css({"background-color":"#f55872","color":"black"});
          $(nRow).find('td:eq(1)').css({"background-color":"#f55872","color":"black"});
          $(nRow).find('td:eq(2)').css({"background-color":"#f55872","color":"black"});
          $(nRow).find('td:eq(3)').css({"background-color":"#f55872","color":"black"});
          $(nRow).find('td:eq(4)').css({"background-color":"#f55872","color":"black"});
          $(nRow).find('td:eq(5)').css({"background-color":"#f55872","color":"black"});
          $(nRow).find('td:eq(6)').css({"background-color":"#f55872","color":"black"});
          $(nRow).find('td:eq(7)').css({"background-color":"#f55872","color":"black"});
          $(nRow).find('td:eq(8)').css({"background-color":"#f55872","color":"black"});
          $(nRow).find('td:eq(9)').css({"background-color":"#f55872","color":"black"});
        }
        else if(aData['status_naming'] == 'Incomplete')
        {
          $(nRow).find('td:eq(0)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(1)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(2)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(3)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(4)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(5)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(6)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(7)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(8)').css({"background-color":"#f0f725","color":"black"});
          $(nRow).find('td:eq(9)').css({"background-color":"#f0f725","color":"black"});

        }
      }
    });
  }
  main_table(supplier_guid,selected_customer_guid);

  // can remove
  $(document).on('click','#view_code',function(){
    var user_guid = $(this).attr('vc_data_1');
    var customer_guid = $(this).attr('vc_data_2');
    var supplier_guid = $(this).attr('vc_data_3');
    var user_name = $(this).attr('vc_data_4');
    var acc_name = $(this).attr('vc_data_5');
    var supplier_name = $(this).attr('vc_data_6');

    $.ajax({
      url:"<?php echo site_url('User_account_setting/view_vc_tb') ?>",
      method:"POST",
      data:{user_guid:user_guid,supplier_guid:supplier_guid,customer_guid:customer_guid},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        var modal = $("#large-modal").modal();

        modal.find('.modal-title').html('Mapping Code Details');

        methodd = '';

        // methodd += '<div class=""><label>Retailer Name :</label> '+acc_name+' </div> <div class=""><label>Supplier Name :</label> '+supplier_name+' </div> <div class=""><label>User Name :</label> '+user_name+' </div>';

        methodd +='<div class="row"> <div class="col-md-12"> <div class="box "> <div class="box-body"> <table id="vc_table" class="table table-bordered table-hover " width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr> <th>Name</th> <th>Code</th>  </tr> </thead> </table> </div> </div> </div> </div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="close_btn" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
      
        setTimeout(function(){
          if ($.fn.DataTable.isDataTable('#vc_table')) {
              $('#vc_table').DataTable().destroy();
          }

          $('#vc_table').DataTable({
            "columnDefs": [
            ],
            'processing'  : true,
            'paging'      : true,
            'lengthChange': true,
            'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
            'searching'   : true,
            'ordering'    : true,
            'order'       : [ [1 , 'asc'] ],
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
                { "data": "supcus_name"},
                { "data": "supplier_group_name"},
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
        
        $('.btn').button('reset');
      }//close success
    });//close ajax 
  });

  // can remove
  $(document).on('click','#view_outlet',function(){
    var user_guid = $(this).attr('vo_data_1');
    var customer_guid = $(this).attr('vo_data_2');
    var supplier_guid = $(this).attr('vo_data_3');
    var user_name = $(this).attr('vo_data_4');
    var acc_name = $(this).attr('vo_data_5');
    var supplier_name = $(this).attr('vo_data_6');

    $.ajax({
      url:"<?php echo site_url('User_account_setting/view_vo_tb') ?>",
      method:"POST",
      data:{user_guid:user_guid,supplier_guid:supplier_guid,customer_guid:customer_guid},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);

        if(json.query_total != json.branch_total)
        {
          var blinker_css = 'blinker_error';
        }
        else
        {
          var blinker_css = '';
        }
        
        var modal = $("#large-modal").modal();

        modal.find('.modal-title').html('Mapping Information ');

        methodd = '';

        methodd +='<div class="row"> <div class="col-md-12"> <div class="box box-primary"> <h4 class="box-title"> Supplier / Vendor Code </h4> <div class="box-body">  <table id="vc_table" class="table table-bordered table-hover " width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr> <th>Name</th> <th>Code</th>  </tr> </thead> </table> </div> </div> </div> </div>';

        methodd += '<span class="pill_button '+blinker_css+'" style="float:right;">Outlet : '+json.query_total+' / '+json.branch_total+' </span>';

        methodd +='<div class="row"> <div class="col-md-12"> <div class="box box-primary"> <h4 class="box-title">Outlet / Branch </h4> <div class="box-body">  <table id="vo_table" class="table table-bordered table-hover " width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr> <th>Descirption</th> <th>Outlet Code</th> </tr> </thead> </table> </div> </div> </div> </div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="close_btn" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
      
        setTimeout(function(){

          if ($.fn.DataTable.isDataTable('#vc_table')) {
              $('#vc_table').DataTable().destroy();
          }

          if ($.fn.DataTable.isDataTable('#vo_table')) {
              $('#vo_table').DataTable().destroy();
          }

          $('#vc_table').DataTable({
            "columnDefs": [
            ],
            'processing'  : true,
            'paging'      : true,
            'lengthChange': true,
            'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
            'searching'   : true,
            'ordering'    : true,
            'order'       : [ [1 , 'asc'] ],
            'info'        : true,
            'autoWidth'   : true,
            "bPaginate": true, 
            "bFilter": true, 
            "sScrollY": "50vh", 
            "sScrollX": "100%", 
            "sScrollXInner": "100%", 
            "bScrollCollapse": true,
              data: json['data_vc'],
              columns: [
                { "data": "supcus_name"},
                { "data": "supplier_group_name"},
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

          $('#vo_table').DataTable({
            "columnDefs": [
            ],
            'processing'  : true,
            'paging'      : true,
            'lengthChange': true,
            'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
            'searching'   : true,
            'ordering'    : true,
            'order'       : [ [1 , 'asc'] ],
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
                { "data": "branch_desc"},
                { "data": "branch_code"},
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
        
        $('.btn').button('reset');
      }//close success
    });//close ajax 
  });

  $(document).on('click','#create_btn',function(){

    if(supplier_guid == '')
    {
      alert('Invalid Process.');
      return;
    }

    $.ajax({
      url:"<?php echo site_url('User_account_setting/user_pending_creation') ?>",
      method:"POST",
      data:{supplier_guid:supplier_guid},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);

        var modal = $("#large-modal").modal();

        modal.find('.modal-title').html('User Creation');

        methodd = '';

        methodd +='<div class="row"> <div class="col-md-12"> <div class="box-body"> <table id="pending_user_tb" class="table table-bordered table-hover " width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr>  <th>User ID</th> <th>User Name</th> <th>Created At</th> <th>Created By</th> <th>Status</th> <th>Action</th> </tr> </thead> </table> </div> </div> </div> ';

        methodd_footer = '<p class="full-width"><span class="pull-right"> <input type="button" id="create_new_user" class="btn btn-success" value="Create"> <input name="close_btn" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
      
        setTimeout(function(){
          if ($.fn.DataTable.isDataTable('#pending_user_tb')) {
              $('#pending_user_tb').DataTable().destroy();
          }

          $('#pending_user_tb').DataTable({
            "columnDefs": [
              { "orderable": false, "targets": [5]},
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
            "sScrollY": "50vh", 
            "sScrollX": "100%", 
            "sScrollXInner": "100%", 
            "bScrollCollapse": true,
              data: json['data'],
              columns: [
                { "data": "user_id"},
                { "data": "user_name"},
                { "data": "created_at"},
                { "data": "created_by"},
                { "data": "action_status"},
                { "data": "empty",render:function( data, type, row , meta ){
                  var element = '';

                  element += '<button id="continue_process_btn" style="margin-left:5px;" title="Edit" class="btn btn-xs btn-info" process_val_data1="'+row['guid']+'" ><i class="fa fa-pencil"></i> Continue</button>';

                  return element;
                }},
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
        
        $('.btn').button('reset');
      }//close success
    });//close ajax 
  });

  $(document).on('click','#continue_process_btn',function(){
    var link_one = $(this).attr('process_val_data1');
    //window.location = "<?= site_url('User_account_setting/information');?>";
    window.open("<?= site_url('User_account_setting/information?link_one=');?>"+link_one, "_blank");
  });

  $(document).on('click','#create_new_user',function(){

    var count_value_register = $('#count_value_register').attr('value');

    if(supplier_guid == '')
    {
      alert('Invalid Process.');
      return;
    }

    if(count_value_register % 5 == 0)
    {
      alert('Notes : Additional fees will be charge up to 5 persons.\nCurrent User Account : '+count_value_register);
    }

    window.location = "<?= site_url('User_account_setting/information?link=');?>"+supplier_guid;
  });

  $(document).on('click','#edit_btn',function(){
    var process_user_guid = $(this).attr('ed_uo_data_1');
    var process_customer_guid = $(this).attr('ed_uo_data_2');
    var process_supplier_guid = $(this).attr('ed_uo_data_3');
    var redirect_action = $(this).attr('action');

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

    if((process_supplier_guid == '') || (process_supplier_guid == null) || (process_supplier_guid == 'null'))
    {
      alert('Invalid Process.');
      return;
    }

    if((redirect_action == '') || (redirect_action == null) || (redirect_action == 'null'))
    {
      alert('Invalid Process.');
      return;
    }

    if(redirect_action != 'redirect_user')
    {
      alert('Invalid Process.');
      return;
    }

    $.ajax({
      url:"<?php echo site_url('User_account_setting/edit_process_list') ?>",
      method:"POST",
      data:{process_user_guid:process_user_guid,process_customer_guid:process_customer_guid,process_supplier_guid:process_supplier_guid,redirect_action:redirect_action},
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
          location.reload();
        }
        else
        {
          $('.btn').button('reset');
          window.open("<?= site_url('User_account_setting/information?link_one=');?>"+json.get_link, "_blank");

        }
      }//close success
    });//close ajax 
  });

  $(document).on('click','#redirect_view_outlet',function(){
    var process_user_guid = $(this).attr('vo_data_1');
    var process_customer_guid = $(this).attr('vo_data_2');
    var process_supplier_guid = $(this).attr('vo_data_3');
    var redirect_action = $(this).attr('action');

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

    if((process_supplier_guid == '') || (process_supplier_guid == null) || (process_supplier_guid == 'null'))
    {
      alert('Invalid Process.');
      return;
    }

    if((redirect_action == '') || (redirect_action == null) || (redirect_action == 'null'))
    {
      alert('Invalid Process.');
      return;
    }

    if(redirect_action != 'redirect_vo')
    {
      alert('Invalid Process.');
      return;
    }

    $.ajax({
      url:"<?php echo site_url('User_account_setting/edit_process_list') ?>",
      method:"POST",
      data:{process_user_guid:process_user_guid,process_customer_guid:process_customer_guid,process_supplier_guid:process_supplier_guid,redirect_action:redirect_action},
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
          location.reload();
        }
        else
        {
          $('.btn').button('reset');
          window.open("<?= site_url('User_account_setting/mapping_information?link_one=');?>"+json.get_link, "_blank");

        }
      }//close success
    });//close ajax 
  });

  $(document).on('click','#transfer_btn',function(){
    duplicate_user_guid = $(this).attr('dp_uo_data_1');
    duplicate_customer_guid = $(this).attr('dp_uo_data_2');
    duplicate_supplier_guid = $(this).attr('dp_uo_data_3');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Transfer User');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<input type="hidden" id="process_input_type" pit_value_1 = '+duplicate_user_guid+' pit_value2 = '+duplicate_customer_guid+' pit_value3 = '+duplicate_supplier_guid+' readonly/>';

    //<option value="retailer"> Retailer Name </option>
    methodd += '<div class="form-group"><label>Transfer By </label> <select class="form-control select2" name="duplicate_type" id="duplicate_type" > <option value=""> -SELECT DATA- </option> <option value="supplier"> Supplier Name </option> </select> </div> ';

    methodd += '<span id="duplicate_selection_append"></span>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="duplicate_process_btn" class="btn btn-success" value="Duplicate"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('#duplicate_type').on('change', function() {
        var duplicate_type = $('#duplicate_type').val();
        
        if(duplicate_type != '')
        {
          $.ajax({
            url : "<?php echo site_url('User_account_setting/fetch_duplicate_data'); ?>",
            method:"POST",
            data:{duplicate_type:duplicate_type,duplicate_customer_guid:duplicate_customer_guid,duplicate_supplier_guid:duplicate_supplier_guid},
            success:function(result)
            {
              json = JSON.parse(result); 

              append = '';

              selection = '';

              append = '<div class="form-group"><label> '+duplicate_type.charAt(0).toUpperCase() + duplicate_type.slice(1)+' Name </label> <select class="form-control select2" name="select_duplicate_value" id="select_duplicate_value" > <option value=""> -SELECT DATA- </option> ';

              Object.keys(json['data']).forEach(function(key) {
                selection += '<option value="'+json['data'][key]['value_guid']+'">'+json['data'][key]['value_name']+'</option>';
              });

              append += '</select> </div>';
              
              $('#duplicate_selection_append').html(append);

              $('#select_duplicate_value').select2().html(selection);

            }
          });
        }
        else
        {
          $('#duplicate_selection_append').html('');
        }
        
      });
    },300);
  });

  $(document).on('click','#duplicate_process_btn',function(){
    var duplicate_type = $('#duplicate_type').val();
    var select_duplicate_value = $('#select_duplicate_value').val();
    var duplicate_user_guid = $('#process_input_type').attr('pit_value_1');
    var duplicate_customer_guid = $('#process_input_type').attr('pit_value2');
    var duplicate_supplier_guid = $('#process_input_type').attr('pit_value3');

    // alert(duplicate_customer_guid); die;
    
    if((duplicate_user_guid == '') || (duplicate_user_guid == null) || (duplicate_user_guid == 'null'))
    {
      alert('Invalid Process. ERROR CODE 01');
      return;
    }

    if((duplicate_customer_guid == '') || (duplicate_customer_guid == null) || (duplicate_customer_guid == 'null'))
    {
      alert('Invalid Process. ERROR CODE 02');
      return;
    }

    if((duplicate_supplier_guid == '') || (duplicate_supplier_guid == null) || (duplicate_supplier_guid == 'null'))
    {
      alert('Invalid Process. ERROR CODE 03');
      return;
    }

    if((duplicate_type == '') || (duplicate_type == null) || (duplicate_type == 'null'))
    {
      alert('Please Select Duplicate By.');
      return;
    }

    if((select_duplicate_value == '') || (select_duplicate_value == null) || (select_duplicate_value == 'null'))
    {
      alert('Please Select ' + duplicate_type.charAt(0).toUpperCase() + duplicate_type.slice(1) + ' Name.');
      return;
    }

    confirmation_modal('Are you sure want to Duplicate User.');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('User_account_setting/duplicate_process') ?>",
        method:"POST",
        data:{duplicate_user_guid:duplicate_user_guid,duplicate_customer_guid:duplicate_customer_guid,duplicate_supplier_guid:duplicate_supplier_guid,select_duplicate_value:select_duplicate_value,duplicate_type:duplicate_type},
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
              onClose: close_process
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

  $(document).on('click','#duplicate_from_retailer',function(){
    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Duplicate User From Other Retailer');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="form-group"><label>Supplier Name</label><select class="form-control select2" name="duplicate_selection_supplier" id="duplicate_selection_supplier" > <option value=""> -SELECT DATA- </option> <?php foreach($get_supplier as $row) { ?> <option value="<?php echo $row->supplier_guid?>"><?php echo addslashes($row->supplier_name)?> </option> <?php } ?> </select></div>';

    methodd += '<span id="selection_supplier_user_append"></span>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="duplicate_from_retailer_process_btn" class="btn btn-success" value="Duplicate"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('#duplicate_selection_supplier').select2()

      $('#duplicate_selection_supplier').on('change', function() {
        var duplicate_type = 'other_retailer';
        var from_retailer_duplicate_supplier = $('#duplicate_selection_supplier').val();
        
        if(from_retailer_duplicate_supplier != '')
        {
          $.ajax({
            url : "<?php echo site_url('User_account_setting/fetch_duplicate_data'); ?>",
            method:"POST",
            data:{duplicate_type:duplicate_type,duplicate_supplier_guid:from_retailer_duplicate_supplier},
            success:function(result)
            {
              json = JSON.parse(result); 

              append = '';

              selection = '';

              append = '<div class="form-group"><label> Duplicate User </label> <select class="form-control select2" name="add_duplicate_user" id="add_duplicate_user" > <option value=""> -SELECT DATA- </option> ';

              Object.keys(json['data']).forEach(function(key) {
                selection += '<option value="'+json['data'][key]['user_guid']+'">'+json['data'][key]['user_id']+' - '+json['data'][key]['user_name']+ ' </option>';
              });

              append += '</select> </div>';
              
              $('#selection_supplier_user_append').html(append);

              $('#add_duplicate_user').select2().html(selection);

            }
          });
        }
        else
        {
          $('#duplicate_selection_append').html('');
        }
        
      });
    },300);
  });

  $(document).on('click','#duplicate_from_retailer_process_btn',function(){
    var duplicate_selection_supplier = $('#duplicate_selection_supplier').val();
    var add_duplicate_user = $('#add_duplicate_user').val();

    if((session_customer_guid == '') || (session_customer_guid == null) || (session_customer_guid == 'null'))
    {
      alert('Invalid Process. ERROR CODE DPR001');
      return;
    }
    
    if((duplicate_selection_supplier == '') || (duplicate_selection_supplier == null) || (duplicate_selection_supplier == 'null'))
    {
      alert('Please Select Supplier Name');
      return;
    }

    if((add_duplicate_user == '') || (add_duplicate_user == null) || (add_duplicate_user == 'null'))
    {
      alert('Invalid Process. Please Select Duplicate User');
      return;
    }

    confirmation_modal('Are you sure want to Duplicate User.');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('User_account_setting/duplicate_process_from_retailer') ?>",
        method:"POST",
        data:{duplicate_selection_supplier:duplicate_selection_supplier,add_duplicate_user:add_duplicate_user,session_customer_guid:session_customer_guid},
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
              onClose: close_process
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

  function close_process() {
    setTimeout(function() {
      $('.sidebar-collapse').css('padding-right','0px');
    }, 300); 
    window.location = "<?= site_url('User_account_setting');?>"
  }

  $(document).on('click','#send_user_info',function(){
    send_user_guid = $(this).attr('se_data_1');
    send_customer_guid = $(this).attr('se_data_2');
    send_supplier_guid = $(this).attr('se_data_3');

    // alert(send_user_guid); die;

    if(send_user_guid == '' || send_user_guid == 'null' || send_user_guid == null)
    {
      alert('Invalid Process Send. Please Contact Admin Support');
      return;
    }

    if(send_customer_guid == '' || send_customer_guid == 'null' || send_customer_guid == null)
    {
      alert('Invalid Process Send. Please Contact Admin Support');
      return;
    }

    if(send_supplier_guid == '' || send_supplier_guid == 'null' || send_supplier_guid == null)
    {
      alert('Invalid Process Send. Please Contact Admin Support');
      return;
    }

    confirmation_modal('Are you sure want to Send Information?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
          url:"<?php echo site_url('User_account_setting/send_user_information');?>",
          method:"POST",
          data:{send_user_guid:send_user_guid,send_customer_guid:send_customer_guid,send_supplier_guid:send_supplier_guid},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == 'false') {
              $("#medium-modal").modal('hide');
              $('.btn').button('reset');
              alert(json.msg);
              location.reload();
            }else{
              $("#medium-modal").modal('hide');
              $('.btn').button('reset');
              alert(json.msg); 
              location.reload();
            }//close else
          }//close success
      });//close ajax
    });//close ajax
  });//close add 
  

});
</script>

