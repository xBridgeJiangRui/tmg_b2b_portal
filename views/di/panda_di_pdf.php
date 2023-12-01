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
       <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?> ">
          <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php  echo $title; ?> -- <b><?php echo $document_no;?></b></h3><br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
      <div class="box-body">
        <div class="col-md-12">
<!--                 <div class="col-md-12"  style="overflow-x:auto"> 
                    <object width="100%" height="500px" data="<?php echo $filename; ?>">
                    </object>
                </div> -->
             <!-- <p><a href="Panda_home/logout">Logout</a></p> -->
                <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 

                  <?php 

                  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);


                  if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) { // && stripos($ua,'mobile') !== false) { ?>

                  <embed src="https://docs.google.com/gview?embedded=true&url=<?php echo $filename; ?>&amp;embedded=true" width="100%" style="border: none;height:20em"/> 

                  <?php  } else { ?>

                  <?php if($file_headers[0] != 'HTTP/1.1 404 Not Found') { ?>
                        <embed src="<?php echo $filename; ?>" width="100%" height="500px" style="border: none;"/> If browser does not support PDFs. Please download the PDF to view it: <a href="<?php echo $filename; ?>" download>Download PDF</a> 
                    <?php } else 
                        {  
                          echo 'pdf not found'; 
                        }
                    ?>


                  <?php } ?>


                    
                </div>             

        </div>
      </div>
    </div>
</div>
</div>
<?php  // echo var_dump($_SESSION); ?>
</div>
</div>

