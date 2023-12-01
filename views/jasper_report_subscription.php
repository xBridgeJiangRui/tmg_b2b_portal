<style type="text/css">
  #acc_branch{
    height: auto;
    overflow-y: scroll;

  }
  #acc_branch_group,#acc_concept{
    height: auto;
    overflow-y: scroll;

  }

  .select2-container--default .select2-selection--multiple .select2-selection__choice
  {
    background: #3c8dbc;
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
          <h3 class="box-title">Report Subscribe</h3>
          <div class="box-tools pull-right">
                        <button title="Subscription" id="multiple_subscribe" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#multiple_subscribe_modal"><i class="glyphicon glyphicon-plus"></i>Multiple</button>
<!--           <button title="Subscription" onclick="subscription_schedule()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#subscription_schedule"  
            data-table="<?php echo 'email_list' ?>"
            data-mode="<?php echo 'create' ?>"
            data-customer_guid = "<?php echo $_SESSION['customer_guid'] ?>"            
            ><i class="glyphicon glyphicon-plus"></i>Create</button> -->

            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
          <form id="formACCconcept" method="post" action="">
                  <table id="email_subscription2" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>User Name</th>
                        <th>Subscribed Report</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Company</th>
                        <th>Supplier</th>
<!--                         <th>Schedule</th>
                        <th>Day</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($user_list->result() as $row)
                    {
                      ?>
                      <tr>
                      <td>
<!--                         <button title="Add" onclick="subscribe_report()" type="button" class="btn btn-xs btn-success" data-toggle="modal" 
                        data-target="#subscription_schedule_add"  
                        data-user_guid="<?php echo $row->user_guid;?>"
                        data-supplier_name="<?php echo $row->supplier_name;?>"
                        data-user_name="<?php echo $row->user_name;?>"
                        data-acc_name="<?php echo $row->acc_name;?>"

                        ><i class=" glyphicon glyphicon-plus"></i></button> -->

                        <button title="Edit" onclick="edit_subscribe_report()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" 
                        data-target="#subscription_schedule_edit"  
                        data-user_guid="<?php echo $row->user_guid;?>"
                        data-supplier_name="<?php echo $row->supplier_name;?>"
                        data-user_name="<?php echo $row->user_name;?>"
                        data-acc_name="<?php echo $row->acc_name;?>"
			data-acc_guid="<?php echo $row->acc_guid;?>"

                        ><i class=" glyphicon glyphicon-pencil"></i></button>
                      </td>
                      <td><?php echo $row->user_name ?></td>
                      <?php 
                      if($row->description != '' || $row->description != null)
                        {
                        ?>
                        <td style="color: #0099ff;">
                          <?php echo $row->description;?>
                        </td>
                        <?php
                        }
                      else
                        {
                        ?>
                        <td style="color: red";>
                          <?php echo "No Report Subscribed Yet";?>
                        </td>
                        <?php
                        } 
                        ?>
                      <td><?php echo $row->user_id ?></td>
                      <td><input type="password" value="<?php echo $row->user_password ?>" disabled/></td>
                      <td><?php echo $row->acc_name ?></td>
                      <td><?php echo $row->supplier_name ?></td>
                      <!-- <td><?php echo $row->day_name ?></td> -->
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
<!-- nothing ends after -->
</div>
</div>

<script>
$(document).ready(function() { 
      $(document).on('click', '#btn_remove', function(){
        // alert();
        $("#xreport_guid option").prop('selected',false);
        $(".select2").select2();
      });//CLOSE ONCLICK

        $(document).on('click', '#selectall', function(){
        // alert();
        $("#xreport_guid option").prop('selected',true);
        $(".select2").select2();
      });//CLOSE ONCLICK


      $(document).on('click', '#multiple_subscribe', function(){
        // alert(1);
        $.ajax({
            url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/fetch_multiple_subscribe",
            method:"POST",
            dataType: "JSON",
            // data:{user_guid:user_guid},
            success:function(data)
            {
              // alert(data.customer_name);
              $("#jasper_report_customer").val(data.customer_name);
              $("#jasper_report_user").html(data.select);
              $("#jasper_report_list").html(data.report);
              $("#jasper_user_customer_guid").val(data.customer_guid);
              $(".select2").select2();
            }
       });
      });//CLOSE ONCLICK

      $(document).on('click','#jasper_user_all',function(){
        $('#jasper_subscribe_user_guid option').prop('selected',true);

        $(".select2").select2();
      });

      $(document).on('click','#jasper_user_diselect_all',function(){
        $('#jasper_subscribe_user_guid option').prop('selected',false);

        $(".select2").select2();
      });

});//CLOSE READY




  function subscribe_report()
  {
    $('#subscription_schedule_add').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      // alert(button.data('user_guid'));
      var modal = $(this)
      // modal.find('.modal-title').text('Edit')
      modal.find('[name="user_guid"]').val(button.data('user_guid'))
      modal.find('[name="supplier_name"]').val(button.data('supplier_name'))
      modal.find('[name="user_name"]').val(button.data('user_name'))
      modal.find('[name="acc_name"]').val(button.data('acc_name'));
    });
  }

  function edit_subscribe_report()
  {
    $('#subscription_schedule_edit').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 

      // alert(button.data('user_guid'));
      var modal = $(this);
      // modal.find('.modal-title').text('Edit')
      modal.find('[name="user_guid"]').val(button.data('user_guid'));
      modal.find('[name="supplier_name"]').val(button.data('supplier_name'));
      modal.find('[name="user_name"]').val(button.data('user_name'));
      modal.find('[name="acc_name"]').val(button.data('acc_name'));
      modal.find('#acc_guid').val(button.data('acc_guid'));

      var user_guid = button.data('user_guid');
      $.ajax({
                url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/fetch_dropdown",
                method:"POST",
                data:{user_guid:user_guid},
                success:function(data)
                {
                  // alert(data);
                  $("#xreport_guid").html(data);
                     // fetchUser();
                     // location.href="<?php echo base_url(); ?>index.php/main/adminenter";
                }
           });


    });
  }
  </script>
