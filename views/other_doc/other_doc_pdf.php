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
        <a class="btn btn-app" href="<?php echo site_url('panda_other_doc').'?code='.$doctype?>">
          <i class="fa fa-search"></i> Browse
        </a>
<?php if($show_excel_button == '1')
{
?>
        <a class="btn btn-app pull-right" id="download_excel" style="color:#000000">
              <i class="fa fa-file-excel-o"></i> Download Excel
        </a>
<?php
}
?>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          
          <h3 class="box-title"><?php echo $title.' - '.$refno.' - '.$status;?></h3><br>

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


                  if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) { // && stripos($ua,'mobile') !== false) { ?>

                  <!-- <embed src="https://docs.google.com/gview?embedded=true&url=<?php echo $filename; ?>&amp;embedded=true" width="100%" style="border: none;height:20em"/>  -->
                    <embed src="<?php echo site_url('Panda_other_doc/readfile').'?refno='.urlencode($refno).'&code='.$doctype.'&supcode='.$supcode; ?>" width="100%" height="500px" style="border: none;"/>

                  <?php  } else { ?>


                        <embed src="<?php echo site_url('Panda_other_doc/readfile').'?refno='.urlencode($refno).'&code='.$doctype.'&supcode='.$supcode; ?>" width="100%" height="500px" style="border: none;"/>
<!--                          If browser does not support PDFs. Please download the PDF to view it: <a href="<?php echo site_url('Panda_other_doc/readfile').'?refno='.$refno.'&code='.$doctype; ?>" download>Download PDF</a>  -->

                         If browser does not support PDFs. Please download the PDF to view it: <a href="<?php echo site_url('Panda_other_doc/read_64').'?refno='.$refno.'&code='.$doctype; ?>" download>Download PDF</a>                          


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
<script type="text/javascript">

$(document).ready(function(){   
    $(document).on('click', '#download_excel', function(e) {
        // return;
        url= "<?php echo $excel_file_path?>";
        // alert(url);
        // return;
        window.location.href = url;
    });
});
</script>
