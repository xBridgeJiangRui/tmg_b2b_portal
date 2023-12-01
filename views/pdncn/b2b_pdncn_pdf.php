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
       <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?>&status=<?php echo $_SESSION['check_status']; ?>">
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
          <h3 class="box-title"><?php  echo $title; ?></h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
      <div class="box-body">
        <div class="col-md-12">
            <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 
                <div id="accconceptCheck">
                    <embed id="embed" height="750px" width="100%" src="<?= $request_link_pdncn; ?>"></embed>
                </div>
            </div>
        </div>
      </div>

    </div>
</div>
</div>

</div>
</div>

<script type="text/javascript">


   function selectall_activate(source) {  
    activate = document.getElementsByName('supcheck[]');

    if(source.checked)
    {
      var valieber = '1';

    }else
    {
       var valieber = '0';
    }
    for(var i=0, n=activate.length;i<n;i++) {
      activate[i].checked = source.checked;
        $('.hiddencheckbox').eq(i).val(valieber); 
    }
  }
</script>
<script type="text/javascript">
  $(document).ready(function(){
     $('input[type=checkbox]').attr('checked',false);
    $( ".ahshengcheckbox" ).click(function() {
    var indes = $(".ahshengcheckbox").index(this);
    if($(this).is(':checked'))
    {
       $('.hiddencheckbox').eq(indes).val('1');
    }
    else
    {
       $('.hiddencheckbox').eq(indes).val(0);
    }
  
    });  
  })
  setTimeout(function(){
   window.location.reload(1);
}, 300000);
</script>
<script type="text/javascript">
$(function() {
  $('input[name="ext_docdate[]"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
 /* $(this).find('[name="ext_docdate[]"]').val("");*/
});
</script>
<script type="text/javascript">
$(function() {
  $('input[name="ext_date1[]"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });/*
  $(this).find('[name="ext_date1[]"]').val("");*/
});
</script>
