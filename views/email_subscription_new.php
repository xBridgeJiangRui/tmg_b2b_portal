<style type="text/css">
  
.select2-container {
    width: 100%!important;
}

</style>

<style type="text/css">
  #acc_branch{
    height: auto;
    overflow-y: scroll;

  }
  #acc_branch_group,#acc_concept{
    height: auto;
    overflow-y: scroll;

  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
 <?php
  if($this->session->userdata('message'))
  {
    ?>
    <div class="alert alert-success text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>

  <?php
  if($this->session->userdata('warning'))
  {
    ?>
    <div class="alert alert-danger text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('warning') <> '' ? $this->session->userdata('warning') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>
<?php // echo var_dump($_SESSION); ?>
<div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Email Schedule</h3>
          <div class="box-tools pull-right">
          <button title="Subscription" onclick="subscription_schedule()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#subscription_schedule"  
            data-table="<?php echo 'email_list' ?>"
            data-mode="<?php echo 'create' ?>"
            data-customer_guid = "<?php echo $_SESSION['customer_guid'] ?>"            
            ><i class="glyphicon glyphicon-plus"></i>Create</button>

            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
          <form id="formACCconcept" method="post" action="<?php echo site_url('supplier_setup/check')?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                  <table id="email_subscription_email_schedule_table" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Report Name</th>
                        <th>Schedule</th>
                        <th>Day</th>
                    </tr>
                    </thead>
                    <tbody>

                    
                    </tbody>
                  </table>
                </form>
              </div>  
        </div>

      </div>
    </div>
  </div>
  <!-- diff level here -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Email Subscription</h3>
          <div class="box-tools pull-right">

            <div class="btn-group">
                  <button type="button" class="btn btn-primary btn-xs">Action</button>
                  <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li id="button-subscribe"><a href="#">Subscribe</a></li>
                  </ul>
                </div>

          <!-- <a href="<?php echo site_url('supplier_setup/create')?>"><button class="btn btn-xs btn-primary" ><i class="glyphicon glyphicon-plus"></i> Create</button></a> -->
<!-- 
          <button title="Create" onclick="reg_email()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#regemail"  
            data-table="<?php echo 'email_list' ?>"
            data-mode="<?php echo 'create' ?>"
            ><i class="glyphicon glyphicon-plus"></i>Create</button> -->

            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->

        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
          <form id="formACCconcept" method="post" action="<?php echo site_url('email_controller_new/batch_subscribe')?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                  <table id="email_subscription_email_subscription_table" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="user_guidall" onclick="checkall_subscription()"></th>
                        <!-- <th>Action</th> -->
                        <th>User ID</th>
                        <th>User Group</th>
                        <th>Email Subscription</th>
                        <th>Email Group</th>
                    </tr>
                    </thead>
                    <tbody>
               
                    
                    </tbody>
                  </table>

                  <!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@ End Register Supplier modal @@@@@@@@@@@@@@@@@@@@@@@@@ -->
                   <div class="modal fade" id="batch_subscribe" role="dialog">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <!-- <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h3 class="modal-title" style="text-align: center">Confirm Delete?</h3>
                              </div> -->
                              <div class="modal-body">
                                  
                              </div>
                              <div class="modal-footer" style="text-align: center">
                              <span id="preloader-delete"></span>
                                  <a id="url" href=""><button type="submit" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-send"></i> Submit</button></a>
                                  <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                              </div>
                          </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                  </div><!-- /.modal -->


                </form>
              </div>  
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /info div -->
    </div>
  </div>
<!-- nothing ends after -->
</div>
</div>

 <script type="text/javascript">

  function confirm_modal(delete_url)
  {
    $('#delete').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm subscribe ' + button.data('user_id') + ' to email function?')
    document.getElementById('url').setAttribute("href" , delete_url );
    });
  }
</script>
<script type="text/javascript">

  function delete_modal(delete_url)
  {
    $('#delete').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm Delete <<' + button.data('report') + '>> for this user?')
    modal.find('#url').attr("href" , delete_url );
    });
  }
</script>
<script>
     function reg_email()
  {
    $('#subscription_schedule_edit').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
      modal.find('[name="schedule_guid"]').val(button.data('schedule_guid'))
      modal.find('[name="email_user"]').val(button.data('email_user'))
      modal.find('[name="day_name"]').val(button.data('day_name'))
      modal.find('[name="report_guid"]').val(button.data('report_guid'))      
      modal.find('[name="report_type"]').val(button.data('report_guid'))
      modal.find('[name="schedule_type"]').val(button.data('schedule_type'))
      modal.find('[name="customer_guid"]').val(button.data('customer_guid'))
      modal.find('[name="report_type"] option[data-report_guid="' + button.data('report_guid') + '"]').attr('selected', 'selected');
    });
  }


   function subscription_schedule()
  {
    $('#subscription_schedule').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Add New')
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
      modal.find('[name="customer_guid"]').val(button.data('customer_guid'))
    });
  }


     function reg_supplier_group()
  {
    $('#regsuppliergroup').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Add New')
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
    });
  }

    function reg_supplier_edit()
  {
    $('#regsupplier').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
      modal.find('[name="supplier_name"]').val(button.data('supplier_name'))
      modal.find('[name="reg_no"]').val(button.data('reg_no'))
      modal.find('[name="gst_no"]').val(button.data('gst_no'))
    });
  }

  function reg_supplier_group_edit()
  {
    $('#regsuppliergroup').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
      modal.find('[name="supplier_group_name"]').val(button.data('supplier_group_name'))
      modal.find('[name="supplier_guid"]').val(button.data('supplier_guid'))
    });
  }

    /*function edit_user()
  {
    $('#user').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))

    });
  }
*/

function edit_user()
  {
    $('#user').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('[name="guid"]').val(button.data('guid'))

      /*modal.find('[name="acc_guid"]').val(button.data('acc_guid'))
      modal.find('[name="module_group_guid"]').val(button.data('module_group_guid'))
      modal.find('[name="user_group_guid"]').val(button.data('user_group_guid'))
      modal.find('[name="isactive"]').val(button.data('isactive'))
      modal.find('[name="user_id"]').val(button.data('user_id'))
      modal.find('[name="user_name"]').val(button.data('user_name'))
      modal.find('[name="user_password"]').val(button.data('user_password'))
      modal.find('[name="created_at"]').val(button.data('created_at'))
      modal.find('[name="created_by"]').val(button.data('created_by'))

      modal.find('[id="user_id"]').text(button.data('user_id'))
      modal.find('[id="user_group"]').text(button.data('user_group'))
      modal.find('[id="module_group"]').text(button.data('module_group'))*/
      
      
    });

  }
   
var checked=false;
function checkall_subscription () {
    var aa =  document.getElementsByName("user_guid[]");
    checked = document.getElementById('user_guidall').checked;
     
    for (var i =0; i < aa.length; i++) 
    {
        aa[i].checked = checked;
    }
 }

$('#button-subscribe').click(function() {
      checked = $("input[id=posted]:checked").length;

      if(!checked) {
        alert("You must check at least one user.");
        return false;
      }

      modal = $('#batch_subscribe').modal();

      html = '';

      html += '<div class="modal_detail" style="text-align:center;">Do you want to batch insert ?</div>';


      modal.find('.modal-body').html(html)

});





$(document).ready(function(){

email_subscription_email_schedule_table = function()
{
  if ( $.fn.DataTable.isDataTable('#email_subscription_email_schedule_table') ) {
    $('#email_subscription_email_schedule_table').DataTable().destroy();
  }
  
  var table;

  table = $('#email_subscription_email_schedule_table').DataTable({
    "columnDefs": [ 
                  // {"targets": 3 ,"visible": false},
                  {"targets": [0] ,"orderable": false}
                  ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [2 , 'asc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true,
    stateSave: true,
    // "sScrollY": "15vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Email_controller_new/email_subscription_email_schedule_table');?>",
        "type": "POST",
        complete:function()
        {
        },
    },
    //'fixedHeader' : false,
    columns: [
              {"data":"customer_guid", render:function( data, type, row ){
                var element = '';

                element += '<button title="Edit" onclick="reg_email()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#subscription_schedule_edit" data-table="email_list" data-mode="update" data-schedule_guid="'+row['schedule_guid']+'" data-customer_guid="<?php echo $_SESSION['customer_guid']; ?>" data-email_user="'+row['trans_guid']+'" data-schedule_type="'+row['schedule_type']+'" data-day_name="'+row['day_name']+'" data-report_guid="'+row['report_guid']+'" ><i class=" glyphicon glyphicon-pencil"></i></button>';

                element += '<button title="Delete" onclick="delete_modal(\'<?php echo site_url('email_controller_new/delete_schedule_guid'); ?>?schedule_guid='+row['schedule_guid']+'\')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-report="'+row['report_name']+'?>" ><i class=" glyphicon glyphicon-trash"></i></button>';

                return element;
              }},
              {"data":"acc_name"},
              {"data":"email"},
              {"data":"first_name"},
              {"data":"report_name"},
              {"data":"schedule_type"},
              {"data":"day_name"},
             ],


    dom:'<"row"<"col-sm-4" l><"col-sm-8" f>>rtip',


    // "pagingType": "simple_numbers",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {

      // if(aData['suspended'] == '1')
      // {   
      //     $(nRow).closest('tr').attr("id","highlight3");  
      // }

      // $(nRow).attr('RefNo', aData['RefNo']);

    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

}//close email_subscription_email_schedule_table






email_subscription_email_subscription_table = function()
{
  if ( $.fn.DataTable.isDataTable('#email_subscription_email_subscription_table') ) {
    $('#email_subscription_email_subscription_table').DataTable().destroy();
  }
  
  var table;

  table = $('#email_subscription_email_subscription_table').DataTable({
    "columnDefs": [ 
                  // {"targets": 3 ,"visible": false},
                  {"targets": [0] ,"orderable": false}
                  ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [0 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true,
    // stateSave: true,
    // "sScrollY": "15vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Email_controller_new/email_subscription_email_subscription_table');?>",
        "type": "POST",
        complete:function()
        {
        },
    },
    //'fixedHeader' : false,
    columns: [
              {"data":"subscribe", render:function( data, type, row ){
                var element = '';

                if(row['email'] == 'No')
                {
                  element += '<input id="posted" type="checkbox" value="'+row['user_guid']+'" name="user_guid[]">';
                }

                return element;
              }},
              {"data":"user_id"},
              {"data":"user_group"},
              {"data":"email", render:function( data, type, row ){
                var element = '';

                if(row['email'] != 'No')
                {
                  element += row['email'];
                }
                else
                {
                  element += 'No Subscribe';
                }

                return element;
              }},
              {"data":"email_group"},
             ],


    dom:'<"row"<"col-sm-4" l><"col-sm-8" f>>rtip',


    // "pagingType": "simple_numbers",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {

      // if(aData['suspended'] == '1')
      // {   
      //     $(nRow).closest('tr').attr("id","highlight3");  
      // }

      // $(nRow).attr('RefNo', aData['RefNo']);

    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

}//close email_subscription_email_subscription_table

email_subscription_email_schedule_table();

email_subscription_email_subscription_table();






});//close document ready



  </script>