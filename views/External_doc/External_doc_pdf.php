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

        <a class="btn btn-app" href="<?php echo $home_url;?>">
          <i class="fa fa-bank"></i> Document List
        </a>    

  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          
          <h3 class="box-title"><?php echo $refno ?></h3><br>

            <!-- <?php echo $refno ?> -->
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
                  if($page > 1)
                  {
                    $em = 28;
                  }
                  else
                  {
                    $em = 50;
                  }
                  foreach($file_paths as $row)
                  {
                  ?>
                  <embed src="<?php echo $row.'?time='.date("Ymdhs"); ?>" width="100%" style="border: none;height:<?php echo $em;?>em"/> 
                  If browser does not support PDFs. Please download the PDF to view it: <a href="<?php echo $row; ?>" download>Download PDF</a> 

                  <?php
                  }
                  ?>

                    
                </div>
        </div>
      </div>
    </div>
</div>
</div>
<?php  // echo var_dump($_SESSION); ?>
</div>
</div>

<script type="text/javascript">
  function confirm_modal(delete_url)
  {
    $('#confirm').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm Accept PO ' + button.data('name') + '?')
    document.getElementById('url').setAttribute("href" , delete_url );
    });
  }
</script>

<script type="text/javascript">
  function reject_modal()
  {
    $('#reject').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal_detail').text('Confirm Reject PO ' + button.data('name') + '?')
      modal.find('[name="refno"]').val(button.data('refno'))
      modal.find('[name="customer_guid"]').val(button.data('customer_guid'))
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="col_guid"]').val(button.data('col_guid'))
      modal.find('[name="loc"]').val(button.data('loc'))
      modal.find('[name="name"]').val(button.data('name'))
  
    });
  }
  
</script>

