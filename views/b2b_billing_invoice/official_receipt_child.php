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

<div class="col-md-12">
<?php if($this->session->flashdata('noconnection')): ?>
<strong><center><?php echo $this->session->flashdata('noconnection'); ?></center></strong>
<?php endif; ?>
</div>


<div class="col-md-12">
</div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          
          <h3 class="box-title"></h3><br>

            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <?php // echo $file_path; ?>
      <div class="box-body">
        <div class="col-md-12">
                <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 

                  <?php 
                  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
                  ?>

                  <embed src="<?php echo site_url('B2b_billing_invoice_controller/official_receipt_doc').'?time='.date("Ymdhs").'&refno='.$_REQUEST['refno'].'&doctype='.$_REQUEST['doctype'].'&supcode='.$_REQUEST['supcode'].'&doctime='.$_REQUEST['doctime']; ?>&amp;embedded=true" width="100%" style="border: none;height:40em"/> 

                  If browser does not support PDFs. Please download the PDF to view it: <a href="<?php echo site_url('B2b_billing_invoice_controller/official_receipt_doc').'?time='.date("Ymdhs").'&refno='.$_REQUEST['refno'].'&doctype='.$_REQUEST['doctype'].'&supcode='.$_REQUEST['supcode'].'&doctime='.$_REQUEST['doctime']; ?>" download>Download PDF</a> 
                    
                </div>
        </div>
      </div>
    </div>
</div>
</div>
<?php  // echo var_dump($_SESSION); ?>
</div>
</div>
<script>
$(document).ready(function() {
  $(document).on('click','#statement',function(){
    //alert('Opps.');
    var redirect = $(this).attr('direct_view');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Choose Customer');

    methodd = '';

    methodd = '<form action="<?php echo site_url('login_c/outside_view_statement');?>" method="post">';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-12"><label>Customer Name</label><select class="form-control" name="acc_guid" id="acc_guid"> <option value="">-Select-</option> <?php foreach ($customer->result() as $key) { ?> <option value="<?php echo $key->acc_guid ?>"><?php echo $key->acc_name?></option> <?php } ?> </select></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="choose_acc" class="btn btn-success" value="Submit" redirect_data='+redirect+'> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p></form>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);
    
  });

  $(document).on('click','#choose_acc',function(){
    //alert('Opps.');
    var customer_guid = $('#acc_guid').val();
    var redirect_data = $(this).attr('redirect_data');
    var location = '';

    if(customer_guid == '')
    {
      alert('Please Select Customer to Proceed View Statement');
      return;
    }

    if((redirect_data == '') || (redirect_data == 'null') || (redirect_data == null))
    {
      alert('Invalid redirect. Please Contact Support.');
      return;
    }

    if(redirect_data == 'view_statement')
    {
      location = "<?= site_url('b2b_billing_invoice_controller/statement'); ?>";
    }

    if(redirect_data == 'view_receipt')
    {
      location  = "<?= site_url('b2b_billing_invoice_controller/official_receipt');?>";
    }

    $.ajax({
          url:"<?= site_url('Login_c/outside_view_statement');?>",
          method:"POST",
          data:{customer_guid:customer_guid},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              $('#medium-modal').modal('hide');
              $('.btn').button('reset');
              window.location = location;
              //redirect(site_url('b2b_billing_invoice_controller/statement'));
            }
          }//close success
        });//close ajax
  });
});
</script>
