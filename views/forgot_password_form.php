<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Panda | B2B</title>
  <link rel="icon" type="image/png" href="<?php echo base_url('asset/dist/img_sunshine/icon-512.png'); ?>" >
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url('asset/bootstrap/css/bootstrap.min.css')?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.css');?>"  type="text/css" />
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url('asset/ionicons.min.css')?>" type="text/css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/AdminLTE.min.css')?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/skins/_all-skins.min.css')?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/iCheck/flat/all.css')?>">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/morris/morris.css')?>">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/jvectormap/jquery-jvectormap-1.2.2.css')?>">

  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')?>">

  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/datatables/dataTables.bootstrap.css')?>">

  <!--DATE & TIME-->
  <script type="text/javascript" src="<?php echo base_url('asset/date_time.js');?>"></script>

  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/select2/select2.min.css')?>">
<!-- ANGULAR JS -->
  <script src="<?php echo base_url('asset/angularjs/angular.min.js')?>"></script> 



  <script  type="text/javascript" src="<?php echo base_url('asset/modernizr-custom.js')?>"></script>
  <script  type="text/javascript" src="<?php echo base_url('asset/polyfiller.js')?>"></script>
  

  <!-- Multi Select -->
 
<link rel="stylesheet" href="<?php echo base_url('asset/bootstrap-multiselect.css')?>"   type="text/css">
<script type="text/javascript" src="<?php echo base_url('asset/bootstrap-multiselect.js')?>" ></script>

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
 
  <style type="text/css">
 
.no-js #loader { display: none;  }
.js #loader { display: block; position: absolute; left: 100px; top: 0; }
.se-pre-con {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url("<?php echo base_url('assets/loading2.gif') ?>") center no-repeat #fff;
    /*background:   #fff;*/
}
</style>
<style type="text/css">
  #highlight {
    background-color: #f8f9c7;
  }

  #highlight2 {
    background-color: #9df9a6;
  }
 
</style>
<!-- // asda -->
<script src="<?php echo base_url('asset/modernizr.js')?>"></script>
<script src="<?php echo base_url('asset/jquery.min.js')?>"></script>
<script type="text/javascript">
    $(window).load(function() {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");
    });
</script>
</head>
<body class="hold-transition skin-yellow layout-top-nav" onload='myBizdate()'>
<div class="wrapper">

  <header class="main-header">
     <nav class="navbar navbar-static-top">
    </nav>

  </header>
  

<div class="se-pre-con"></div>

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


  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"> Forgot Password</h3><br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
      <div class="box-body" >
      <div class="col-md-3"></div>
      <div class="col-md-6">
      <form action="<?php echo site_url('Email_controller/reset_password');?>" method='post'>

        <div class="form-group">
        Please Enter Password : <input class="form-control" autocomplete="off" required id="first_password" name="first_password" value="" type="password" />
        </div>

        <div class="form-group">
        Confirm Password : <input class="form-control" autocomplete="off" required id="second_password" name="second_password" value="" type="password" />
        </div>

        <input type="hidden" id="rep_id" name="rep_id" value="<?php echo $_REQUEST['rep_id']; ?>">
        <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST['id'] ; ?>">
        <input type="hidden" id="token" name="token" value="<?php echo $_REQUEST['token']; ?>">
     
         <br>
         <br>
         <br>
         <br>
          
         <p><button class="btn btn-lg btn-primary btn-block" onclick="this.disabled=true;this.form.submit();" type="submit">Submit</button></p>

        </form>

             <!-- <p><a href="Panda_home/logout">Logout</a></p> -->
        </div>
        <div class="col-md-3"></div>
        </div>
    </div>
</div>
</div>
<?php // echo var_dump($_SESSION); ?>
</div>
</div>
<footer class="main-footer" style="background: transparent linear-gradient(180deg, #FD8B29 0%, #F96327 100%) 0% 0% no-repeat padding-box;color:white;">
    <!-- <div class="pull-right hidden-xs">
      Policy:&nbsp;<a href="https://b2b.xbridge.my/admin_files/Privacy%20Policy%20(ENGLISH).pdf" target="_blank">(EN)</a> <a href="https://b2b.xbridge.my/admin_files/Privacy%20Policy%20(BM).pdf" target="_blank">(BM)</a>&nbsp;<span data-toggle="modal" data-target="#contactus"><b style="cursor:pointer">Contact Us</b></span>  &nbsp<img src="<?php echo base_url('asset/dist/img_sunshine/icon-512.png');?>" class="img-circle" alt="User Image" style="height: 32px">
    </div> -->
    &copy; Copyright <?php echo date('Y'); ?> by Sunshine Wholesale Mart Sdn. Bhd. (148802-X). All rights
    reserved.
</footer>
