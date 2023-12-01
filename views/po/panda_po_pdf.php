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

      <?php if(isset($_SESSION['from_other'])== '0')  { ?>

        <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=&status=<?php echo $_SESSION['check_status']; ?>">
          <i class="fa fa-search"></i> Browse
        </a>
      <?php } else { ?>
        <a class="btn btn-app" href="<?php echo site_url('general/view_status')?>?status=<?php echo $_SESSION['check_status']; ?>&loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n= ">
          <i class="fa fa-search"></i> Browse
        </a>
      <?php } ?>

        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>
        <?php if($_SESSION['frommodule'] == 'panda_po_2' ) { ?>
<?php if($show_action_button == '1') { ?>
        <button title="Accept"  onclick="confirm_modal('<?php echo site_url('general/accept'); ?>?refno=<?php echo $_REQUEST['trans'] ?>&customer_guid=<?php echo $_SESSION['customer_guid'] ?>&table=pomain&col_guid=refno&loc=<?php echo $_REQUEST['loc'] ?>')" 
          type="button" class="btn btn-app" style="color:#008D4C"  data-toggle="modal" data-target="#confirm" data-name="<?php echo $_REQUEST['trans'] ?>" >
                        <i class="fa fa-check"></i>Accept
        </button>

        <!-- <button title="reject" onclick="reject_modal('<?php echo site_url('general/reject'); ?>?refno=<?php echo $_REQUEST['trans'] ?>&customer_guid=<?php echo $_SESSION['customer_guid'] ?>&table=pomain&col_guid=refno&loc=<?php echo $_REQUEST['loc'] ?>')" 
          type="button" class="btn btn-app" style="color:#D73925"  data-toggle="modal" data-target="#reject"  data-name="<?php echo $_REQUEST['trans'] ?>" >
                        <i class="fa fa-times"></i>Reject</button> -->
        <?php } ?>

<?php if($show_action_button2 == '1') { ?>

             <button title="reject" onclick="reject_modal()" type="button" class="btn btn-app"  data-toggle="modal" data-target="#reject" style="color:#D73925"
                          data-refno="<?php echo $_REQUEST['trans'] ?>"
                          data-customer_guid="<?php echo $_SESSION['customer_guid'] ?>"
                          data-table="<?php echo 'pomain' ?>"
                          data-col_guid="<?php echo 'refno' ?>"
                          data-loc="<?php echo $_REQUEST['loc'] ?>"
                          data-name="<?php echo $_REQUEST['trans']?>"
                           > <i class="fa fa-times"></i>Reject</button>


        <?php } } ?> 
        <?php echo $hide_url ?>   


          <a class="btn btn-app" href="<?php echo site_url('panda_po_2/export_excel')?>?refno=<?php echo $_REQUEST['trans'] ?>&loc=<?php echo $_REQUEST['loc'] ?>">
          <i class="fa fa-file-excel-o"></i> CSV
          </a> 
           
        <?php if(in_array('IAVA',$this->session->userdata('module_code')))
        {
          ?>    
          <?php
        }
        ?>  

  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          
          <h3 class="box-title"><?php echo $title; echo ' <br>Ref No : '; echo $check_status->row('refno'); echo ' - '; echo $check_status->row('status');  
              if($check_status->row('rejected_remark') != '') 
                { 
                 echo ' '; echo $check_status->row('rejected_remark'); 
                }; ?></h3><br>

            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <?php // echo $file_path; ?>
      <div class="box-body">
        <div class="col-md-12">
                
              <?php if(in_array('IAVAa',$this->session->userdata('module_code')))
              {
                ?>
                <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 
                  <div id="accconceptCheck">
                    <embed id="embed" height="750px" width="100%" src="<?= $request_link; ?>"></embed>
                  </div>
                </div>
                <?php
              }
              else
              {
                ?>
                <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 

                  <?php 

                  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);


                  if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) { // && stripos($ua,'mobile') !== false) { ?>

                  <embed src="https://docs.google.com/gview?embedded=true&url=<?php echo $filename; ?>&amp;embedded=true" width="100%" style="border: none;height:20em"/> 

                  <?php  } else { ?>

                  <?php if($file_headers[0] != 'HTTP/1.1 404 Not Found') { ?>
                        <embed src="<?php echo $filename.'?time='.date("Ymdhs"); ?>" width="100%" height="500px" style="border: none;"/> If browser does not support PDFs. Please download the PDF to view it: <a href="<?php echo $filename; ?>" download>Download PDF</a> 
                    <?php } else 
                        {  
                          echo 'pdf not found'; 
                        }
                    ?>


                  <?php } ?>

                  If browser does not support PDFs. Please download the PDF to view it: <a href="<?php echo $filename; ?>" download>Download PDF</a> 
                    
                  </div>
                <?php
              }
              ?>
                

                
                
                  
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

