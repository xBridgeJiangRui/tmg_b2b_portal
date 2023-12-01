<!-- Content Wrapper. Contains page content -->
<style type="text/css">
  .red {
  background-color: #DD4B39 !important;
}

.content-wrapper{
  min-height: 1000px !important; 
}
</style>
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
          <h3 class="box-title"><?php echo $title ?></h3>
          <div class="box-tools pull-right">
          <!-- <button title="Subscription" onclick="create_new()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#create_new"  
            data-table="<?php echo 'announcement' ?>"
            data-mode="<?php echo 'create' ?>"
            data-customer_guid = "<?php echo $_SESSION['customer_guid'] ?>"            
            ><i class="glyphicon glyphicon-plus"></i>Create
          </button> -->

          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
            <?php // echo var_dump($_SESSION) ?>
                  <table id="sup_checklist" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <?php if(in_array('UAP',$_SESSION['module_code']))
                        {
                        ?>
                        <th>Action</th>
                        <?php 
                        }
                        ?>
                        <th>Type</th>
                        <th>Account Code</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Supplier Name</th>
                        <th>Reg No</th>
                        <th>Block</th>
                        <th>Remark</th>
                        <th>Email</th>
                        <th>Invoice Number</th>
                        <!-- <th>Training Pax</th> -->
                        <th>Tel</th>
                        <!-- <th>Payment</th>
                        <th>Active</th>
                        <th>Acceptance Form</th>
                        <th>Registration Form</th>
                        <th>Status</th> -->
                        <th>Supply Type</th>
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
   
<!-- nothing ends after -->
</div>
</div> 
<script>
$(document).ready(function(){
    input_loop = 1;
    $(document).on("click","#remarksss",function() {
        _this = $(this);
        var text = $('#remarkss').val();
        var user_id = "<?php echo $this->session->userdata('userid');?>"
        // alert(text);
        // var time = "<?php date_default_timezone_set("Asia/Kuala_Lumpur");echo date('d-M-Y h:i:s');?>";

        $.ajax({
              type: "POST",
              url: "<?php echo site_url('CusAdmin_controller/get_time');?>",
              dataType: 'json',
              success: function(data){
                  // alert();return;
                  time = data.now;
                  // text = $(this).val();
                  // alert(text);
                  // alert(time+input_loop);
                  if(input_loop == 1)
                  {
                    if(text == '' || text == null)
                    {
                      _this.val('['+time+'] '+user_id+' : ');
                      input_loop++;
                    }
                    else
                    {
                      // alert(2);
                      $('#remark_new_line').val("\r\n")
                      _this.val('['+time+']'+user_id+' : ');
                      input_loop++;
                    }   
                  }
                  // return;
              }
        });    


    });
});
  function hide_modal()
  {
    input_loop = 1;
    var time = "<?php date_default_timezone_set("Asia/Kuala_Lumpur");echo date('d-M-Y h:i:s');?>";
    // alert(time);
    $('#sup_checklist_action').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 
    
      var modal = $(this)
      modal.find('.modal-title').text('Supplier Check List Action')
      modal.find('[name="customer_guid"]').val(button.data('customer_guid'))       
      modal.find('[name="code"]').val(button.data('code')) 
      modal.find('[name="supcus_guid"]').val(button.data('supcus_guid')) 
      modal.find('[name="PIC"]').val(button.data('pic')) 
      modal.find('[name="PAYMENT"]').val(button.data('payment')).attr('ori_value',button.data('payment')) 
      modal.find('[name="IsActive"]').val(button.data('isactive')) 
      modal.find('[name="invoice_no"]').val(button.data('invoice_no')) 
      modal.find('[name="sup_name"]').val(button.data('sup_name')) 
      modal.find('[name="tel"]').val(button.data('tel')) 
      modal.find('[name="form_a"]').val(button.data('accform')) 
      modal.find('[name="REG_FORM"]').val(button.data('regform')) 
      modal.find('[name="training_pax"]').val(button.data('training_pax'))
      modal.find('[name="STATUS"]').val(button.data('status')) 
      modal.find('[name="remark"]').val(button.data('remark')) 
      if(button.data('remark') == '' || button.data('remark') == null)
      {
          // modal.find('[name="remark"]').val(button.data('remark')+'['+time+']'+' : ') 
      }
      else
      {
          // modal.find('[name="remark"]').val(button.data('remark')+"\r\n"+'['+time+']'+' : ') 
      }

      $('#custom_amount_select').trigger('change');

    });
  }

  function hide_supplier()
  {
    $('#sup_hide').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Supplier Check List Action')
      modal.find('[name="customer_guid"]').val(button.data('customer_guid'))       
      modal.find('[name="code"]').val(button.data('code')) 
      modal.find('[name="supcus_guid"]').val(button.data('supcus_guid')) 
      modal.find('[name="IsActive"]').val(button.data('isactive')) 
      modal.find('[name="form_a"]').val(button.data('accform')) 
      modal.find('[name="REG_FORM"]').val(button.data('regform')) 
    });
  }

 $(function() {
    $('input[name="docdate"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
         locale: {
            format: 'YYYY-MM-DD'
        },
         
    }, 
  );
});

  $(function() {
    $('input[name="published_date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: true, 
        timePickerIncrement: 30,
        ampm: true,
         locale: {
            format: 'YYYY-MM-DD HH:mm:ss'
        },
         
    }, 
  );
});  
</script> 
 