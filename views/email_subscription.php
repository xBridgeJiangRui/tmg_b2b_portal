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
                  <table id="email_subscription2" class="table table-bordered table-hover" width="100%" cellspacing="0">
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
                    <?php
                    foreach($email_schedule->result() as $row)
                    {
                      ?>
                      <tr>
                      <td>
                        <button title="Edit" onclick="reg_email()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" 
                        data-target="#subscription_schedule_edit"  
                        data-table="<?php echo 'email_list'; ?>"
                        data-mode="<?php echo 'update'; ?>"
                        data-schedule_guid="<?php echo $row->schedule_guid ?>"
                        data-customer_guid="<?php echo $_SESSION['customer_guid']; ?>"
                        data-email_user="<?php echo  $row->trans_guid ?>"
                        data-schedule_type="<?php echo  $row->schedule_type ?>"
                        data-day_name="<?php echo  $row->day_name ?>"
                        data-report_guid="<?php echo  $row->report_guid ?>"
                        ><i class=" glyphicon glyphicon-pencil"></i></button>
                        <button title="Delete" onclick="delete_modal('<?php echo site_url('email_controller/delete_schedule_guid'); ?>?schedule_guid=<?php echo $row->schedule_guid?>')" type="button" class="btn btn-xs btn-danger" 
                          data-toggle="modal"  
                          data-target="#delete" 
                          data-report="<?php echo $row->report_name; ?>" ><i class=" glyphicon glyphicon-trash"></i></button>
                      </td>
                      <td><?php echo $row->acc_name ?></td>
                      <td><?php echo $row->email ?></td>
                      <td><?php echo $row->first_name ?></td>
                      <td><?php echo $row->report_name ?></td>
                      <td><?php echo $row->schedule_type ?></td>
                      <td><?php echo $row->day_name ?></td>
                      </tr>
                      <?php
                    }
                    ?>
                    
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
          <form id="formACCconcept" method="post" action="<?php echo site_url('email_controller/batch_subscribe')?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                  <table id="email_subscription" class="table table-bordered table-hover" width="100%" cellspacing="0">
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
                    <?php
                    foreach($email_subscription->result() as $row)
                    {
                      ?>
                      <tr>
                        <td>
                        <?php if($row->email == 'No') { ?>
                        <input id="posted" type="checkbox" value="<?php echo $row->user_guid ?>" name="user_guid[]">
                        <?php } ?>
                        </td>
                      <!-- <td>
                        <?php if($row->email == 'No') { ?>
                        <button title="Assign" onclick="confirm_modal('<?php echo site_url('email_controller/subscription_detail'); ?>?user_guid=<?php echo $row->user_guid?>')" type="button" class="btn btn-xs btn-primary" data-toggle="modal" 
                        data-target="#delete"  
                        data-table="<?php echo 'email_list' ?>"
                        data-user_id="<?php echo $row->user_id ?>"
                        ><i class=" glyphicon glyphicon-bell"></i></button>

                        <?php } ?>
                      </td> -->
                      <td><?php echo $row->user_id ?></td>
                      <td><?php echo $row->user_group ?></td>
                      <td><?php if($row->email != 'No') { ?>
                        <?php echo $row->email ?>
                        <?php } else{

                          echo 'No Subscribe';
                        }?>
                      </td>
                      <td><?php echo $row->email_group ?></td>
                      </tr>
                      <?php
                    }
                    ?>
                    
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


  </script>