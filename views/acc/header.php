<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sunshine | B2B</title>
  <link rel="icon" type="image/png" href="<?php echo base_url('asset/dist/img_sunshine/icon-512.png');?>" >
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!--   <script src="<?php echo base_url('assets/plugins/jquery.min.js')?>"></script>
 --><!--   <script src="<?php echo base_url('assets/plugins/moment.min.js')?>"></script>
 -->  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/daterangepicker/daterangepicker.css')?>">
<!--   <script src="<?php echo base_url('assets/plugins/daterangepicker/daterangepicker.js')?>"></script> -->

 
  <link rel="stylesheet" href="<?php echo base_url('asset/bootstrap/css/bootstrap.min.css')?>">
 
  <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.css');?>"  type="text/css" />
  
  <link rel="stylesheet" href="<?php echo base_url('asset/ionicons.min.css')?>" type="text/css">
 
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/AdminLTE.css')?>">
 
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/skins/_all-skins.min.css')?>">

  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/morris/morris.css')?>">

  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/jvectormap/jquery-jvectormap-1.2.2.css')?>">

  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')?>">

  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/datatables/dataTables.bootstrap.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/datatables/dataTables.checkboxes.css')?>">  

 <!--  <script src="<?php echo base_url('asset/plugins/datatables/dataTables.checkboxes.min.js')?>"></script> -->
<!--   <script src="<?php echo base_url('asset/plugins/datatables/datatables.min.js')?>"></script> 
 --> 
<!--   <script type="text/javascript" src="<?php echo base_url('asset/date_time.js');?>"></script>
 --> 
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/select2/select2.min.css')?>">
 
<!--   <script src="<?php echo base_url('asset/angularjs/angular.min.js')?>"></script> 
 -->
<!--   <script  type="text/javascript" src="<?php echo base_url('asset/modernizr-custom.js')?>"></script> -->
<!--   <script  type="text/javascript" src="<?php echo base_url('asset/polyfiller.js')?>"></script>
 -->
<link rel="stylesheet" href="<?php echo base_url('asset/bootstrap-multiselect.css')?>"   type="text/css">
<!-- <script type="text/javascript" src="<?php echo base_url('asset/bootstrap-multiselect.js')?>" ></script> -->

  

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

  #highlight3 {
    background-color: #DD4B39;
  }

 /*.main-sidebar {
    width: 250px;
  }

  .main-header .logo
  {
    width:250px;
  }

  .main-header .navbar
  {
    margin-left:250px;
  }

  .content-wrapper, .right-side, .main-footer
  {
    margin-left:250px;
  }*/
@media print {
   a[href]:after {
      display: none;
      visibility: hidden;
   }
}
</style>
<!-- // asda -->
<script src="<?php echo base_url('asset/modernizr.js')?>"></script>
<script src="<?php echo base_url('asset/jquery.min.js')?>"></script>
<script type="text/javascript">
    $(window).load(function() {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");;
    });

</script>

</head>
<body class="hold-transition skin-blue sidebar-mini  sidebar-collapse"  >
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <?php if(isset($_SESSION['module_code'])) { ?>
    <a href=" <?php  if(in_array('DASH',$_SESSION['module_code']))
                    {
                        echo site_url('dashboard');
                    }
                    else
                    {
                        echo site_url('panda_home');
                    } 
      ?>" class="logo">
     
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">B2B</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-xs"><b style="font-size: 12px">B2B</b></span>

    </a> 
    <?php } ?>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <?php // echo var_dump($_SESSION) ?>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li>
            <a data-toggle="modal" data-target="#contactus"><i class="fa fa-phone"> </i> Contact Us</a>
          </li>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo base_url('asset/dist/img_sunshine/icon-512.png');?>" class="user-image" alt="User Image">
              <span class="hidden-xs">Profile</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <?php if(isset($_SESSION['customer_guid'])) 
                  { 
              ?>
               
               <?php $name = $this->db->query("SELECT acc_name from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('acc_name')  ; ?>
               
               <?php 
                    } 
                ?>
              <li class="user-header">
                <img src="<?php echo base_url('asset/dist/img_sunshine/icon-512.png');?>" class="img-circle" alt="User Image">

                <p> Username : <?php echo $_SESSION['userid'] ?>
                 <!--  Customer name or simple desc
                  <small>addresss</small> -->
                  <br>
                  <a href="<?php echo site_url('login_c/customer')?>" class="btn btn-default btn-flat"><i class="fa fa-location-arrow"></i> Customer : 

                  <?php if(isset($name) == '1') 
                    {
                      echo $name;
                    }
                    else
                    {
                      echo '';
                    } 
                  ?>

                </a>
                </p>
                <br>
                
              </li>

              <!-- Menu Footer-->
             
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo site_url('login_c/password')?>" class="btn btn-default btn-flat"><i class="fa fa-key"></i>Change Password </a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo site_url('login_c/logout')?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li> 
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>

  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
    <ul class="sidebar-menu" >

    <?php if(isset($_SESSION['show_side_menu']) == '1') { ?>
      <?php 
        if(in_array('PPANEL',$_SESSION['module_code']))
        {
      ?>
         <li <?php if (preg_match("/general/i", $this->uri->segment(1)) ||  preg_match("/panda_grda/i",$this->uri->segment(1)) || preg_match("/panda_prdncn/i", $this->uri->segment(1)) || preg_match("/panda_pdncn/i", $this->uri->segment(1)) || preg_match("/panda_pci/i", $this->uri->segment(1)) ||  preg_match("/panda_di/i",$this->uri->segment(1))    )   
        echo 'class="treeview active"'; ?>>
          <a href="#">
            <i class="fa fa-file-text"></i>
            <span>Transactions</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           <?php 
            if(in_array('VPO',$_SESSION['module_code']))
              {
            ?>
            <li ><a href="<?php echo site_url('panda_po_2')?>" ><i class="fa fa-circle-o"></i>Purchase Order (PO)</a></li>
            <?php } ?>
            
            <?php 
            if(in_array('VGR',$_SESSION['module_code']))
              {
            ?>
            <li ><a href="<?php echo site_url('panda_gr') ?>"><i class="fa fa-circle-o">
             </i>Goods Received Note (GRN)</a></li>
             <?php } ?>

            <?php 
            if(in_array('VRB',$_SESSION['module_code']))
              {
            ?>
            <li ><a href="<?php echo site_url('panda_return_collection')?>"><i class="fa fa-circle-o"></i>Stock Return Batch Document(RB)</a></li> 
            <?php } ?>

            <?php 
            if(in_array('VPRDN',$_SESSION['module_code']))
              {
            ?>
            <li ><a href="<?php echo site_url('panda_prdncn')?>"><i class="fa fa-circle-o"></i>Purchase Return DN/CN (PRDN/CN)</a></li>
            <?php } ?>
            <!-- dbnotemain type = S  union cnnotemain type = 'S' -->

          </ul>
        </li>
        <li <?php if (  preg_match("/panda_pdncn/i", $this->uri->segment(1)) || preg_match("/panda_pci/i", $this->uri->segment(1)) ||  preg_match("/panda_di/i",$this->uri->segment(1))    )   
        echo 'class="treeview active"'; ?>>
          <a href="#">
            <i class="fa fa-download"></i>
            <span>To Download</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">   

            <?php 

            if(!in_array('VGR',$_SESSION['module_code']))
              {
            ?>
            <li ><a href="<?php echo site_url('panda_gr_download')?>"><i class="fa fa-circle-o"></i>Goods Received Note (GRN)</a></li>
            <?php } ?>

            <?php 

            if(in_array('VGRDA',$_SESSION['module_code']))
              {
            ?>
            <li ><a href="<?php echo site_url('panda_grda')?>"><i class="fa fa-circle-o"></i>Goods Received Diff Advice (GRDA)</a></li>
            <?php } ?>

            <?php 

            if(in_array('VPDNCN',$_SESSION['module_code']))
              {
            ?>
            <li ><a href="<?php echo site_url('panda_pdncn')?>"><i class="fa fa-circle-o"></i>Purchase DN/CN (PDN/CN)</a></li>
             <?php } ?>

            <?php 

            if(in_array('VPCI',$_SESSION['module_code']))
              {
            ?>
            <li ><a href="<?php echo site_url('panda_pci')?>"><i class="fa fa-circle-o"></i>Promotion Claim Tax Invoice (PCI)</a></li>
            <!-- promo_taxinv -->
            <?php } ?>

            <?php 

            if(in_array('VDI',$_SESSION['module_code']))
              {
            ?>
            <li ><a href="<?php echo site_url('panda_di')?>"><i class="fa fa-circle-o"></i>Display Incentive Tax Invoice (DI)</a></li>
            <!-- `discheme_taxinv` -->
            <?php } ?>
          </ul>
        </li>
      <?php } ?>

     <li <?php if (preg_match("/Profile_setup/i", $this->uri->segment(1)) ||  preg_match("/Acc_master_setup/i",$this->uri->segment(1)) || preg_match("/Module_setup/i", $this->uri->segment(1)) || preg_match("/Supplier_setup/i", $this->uri->segment(1)) || preg_match("/User_log/i", $this->uri->segment(1)))   
        echo 'class="treeview active"'; ?> >
          <a href="#">
            <i class="fa fa-user"></i>
            <span>User Profile</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           
            <?php 
        if(in_array('VPS',$_SESSION['module_code']))
        {
          ?>
          <li class="treeview">
            <a href="<?php echo site_url('Profile_setup')?>">
              <i class="fa fa-newspaper-o"></i>
              <span>Profile Setup</span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
          </li>
          <?php
        }
        ?>


        <?php 
        if(in_array('VPS',$_SESSION['module_code']))
        /*Please change VPS to make sure is system admin*/
        {
          ?>
          <li class="treeview">
            <a href="<?php echo site_url('Acc_master_setup')?>">
              <i class="fa fa-university"></i>
              <span>Account Master Setup</span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
          </li>
          <?php
        }
        ?>
        
        <?php 
        if(in_array('VMS',$_SESSION['module_code']))
        {
          ?>
          <li class="treeview">
            <a href="<?php echo site_url('Module_setup')?>">
              <i class="fa fa-users"></i>
              <span>Module Setup</span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
          </li>
          <?php
        }
        ?>

        <?php 
        if(in_array('VSUP',$_SESSION['module_code']))
        {
          ?>
          <li class="treeview">
            <a href="<?php echo site_url('Supplier_setup')?>?customer_guid=<?php echo $_SESSION['customer_guid'] ?>">
              <i class="fa fa-truck"></i>
              <span>Supplier Setup</span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
          </li>
          <?php
        }
        ?>

        <?php 
        if(in_array('VUL',$_SESSION['module_code']))
        {
          ?>
          <li class="treeview">
            <a href="<?php echo site_url('User_log')?>">
              <i class="fa fa-user-secret"></i>
              <span>User Log</span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
          </li>
          <?php
        }
        ?>
      </ul>
    </li>


         <?php 
        if(in_array('VREP',$_SESSION['module_code']))
        {
          ?>  

          <li <?php if (preg_match("/Report_controller/i", $this->uri->segment(1))  ||  preg_match("/Report_controller/i",$this->uri->segment(1))  )   
        echo 'class="treeview active"'; ?>>
          <a href="#">
            <i class="fa fa-file"></i>
            <span>Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

<?php $find_title = $this->db->query("SELECT * FROM lite_b2b.`set_report_query` WHERE report_type = 'excel' AND active = '1' ORDER BY seq"); ?>
<?php  foreach ($find_title->result() as $row) {  ?>
          <li class="treeview">
            <a href="<?php echo site_url('Report_controller/gen_rep?report_id='.$row->report_id);  ?>">
              <i class="fa fa-file-excel-o"></i>
              <span><?php echo $row->report_name ?></span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
          </li>
<?php } ?>
<!-- nothing below for this section -->
        </ul>
      </li>

 <?php } ?>  

              <?php  if(in_array('CUSADMIN',$_SESSION['module_code']))
        { ?>

          <li <?php if (preg_match("/CusAdmin_controller/i", $this->uri->segment(2))   )   
          echo 'class="treeview active"'; ?>>
          <a href="#">
            <i class="fa fa-feed"></i>
            <span>Admin Panel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="treeview">
              <a href="<?php echo site_url('CusAdmin_controller/annoucement')?>">
                <i class="fa fa-bullhorn"></i>
                <span>Annoucement</span>
                <span class="pull-right-container">
                  <!-- <i class="fa fa-angle-left pull-right"></i> -->
                </span>
              </a>
            </li>
            <li class="treeview">
              <a href="<?php echo site_url('CusAdmin_controller/supplier_checklist')?>">
                <i class="fa fa-list-ol"></i>
                <span>Supplier Checklist</span>
                <span class="pull-right-container">
                  <!-- <i class="fa fa-angle-left pull-right"></i> -->
                </span>
              </a>
            </li>
            <li class="treeview">
              <a href="<?php echo site_url('CusAdmin_controller/cusadmin_settings')?>">
                <i class="fa fa-cogs"></i>
                <span>Retailer General Settings</span>
                <span class="pull-right-container">
                  <!-- <i class="fa fa-angle-left pull-right"></i> -->
                </span>
              </a>
            </li>
            <li class="treeview">
              <a href="<?php echo site_url('CusAdmin_controller/manual_guide_setup')?>">
                <i class="fa fa-book"></i>
                <span>Manual Guide Setup</span>
                <span class="pull-right-container">
                  <!-- <i class="fa fa-angle-left pull-right"></i> -->
                </span>
              </a>
            </li>
          </ul>
        </li>

      <?php } ?>

        <?php 
        if(in_array('TBEMAIL',$_SESSION['module_code']))
        {
          ?>

          <li <?php if (preg_match("/Email_controller/i", $this->uri->segment(1)) && preg_match("/Export_controller/i", $this->uri->segment(2)) ||  preg_match("/Export_controller/i",$this->uri->segment(1)) || preg_match("/Email_controller/i", $this->uri->segment(1)) || preg_match("/Email_controller/i", $this->uri->segment(1)) || preg_match("/Email_controller/i", $this->uri->segment(1))|| preg_match("/Report_controller/i", $this->uri->segment(1)) )   
        echo 'class="treeview active"'; ?>>
          <a href="#">
            <i class="fa fa-cog"></i>
            <span>Troubleshooter</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <li class="treeview">
            <a href="<?php echo site_url('Restreport')?>">
              <i class="fa fa-cogs"></i>
              <span>Troubleshoot Document</span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
          </li>
           
          <li class="treeview">
            <a href="<?php echo site_url('Email_controller/subscription')?>">
              <i class="fa fa-cogs"></i>
              <span>Email Subscription</span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
          </li>

           <li class="treeview">
            <a href="<?php echo site_url('Report_jasper_controller/subscription')?>">   
              <i class="fa fa-newspaper-o"></i>   
              <span>Report Subscription</span>    
              <span class="pull-right-container">   
                <!-- <i class="fa fa-angle-left pull-right"></i> -->    
              </span>   
            </a>    
          </li>   
          

          <li class="treeview">
            <a href="<?php echo site_url('Report_controller/main')?>">
              <i class="fa fa-line-chart"></i>
              <span>Report Setup</span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
          </li>
          
          <li class="treeview">
            <a href="<?php echo site_url('Email_controller/setup')?>">
              <i class="fa fa-newspaper-o"></i>
              <span>Troubleshoot Email</span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
          </li>

          <li class="treeview">
            <a href="<?php echo site_url('Export_controller/main')?>">
              <i class="fa fa-file-excel-o"></i>
              <span>Troubleshoot Excel</span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
          </li>

          <li class="treeview">            
            <a href="<?php echo site_url('fax/setup')?>">   
              <i class="fa fa-newspaper-o"></i>   
              <span>Troubleshoot Fax</span>   
              <span class="pull-right-container">   
                <!-- <i class="fa fa-angle-left pull-right"></i> -->    
              </span>   
            </a>    
          </li>
      </li>
    </ul>

      

      <?php } ?>
      <!-- invoice details -->
        <li class="treeview">
          <a href="#">
            <i class="fa fa-inbox"></i>
            <span>B2B Monthly Billing Invoices</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="treeview">
              <a href="<?php echo site_url('b2b_billing_invoice_controller/invoices')?>">
                <i class="fa fa-circle-o"></i>
                <span>Invoices</span>
                <span class="pull-right-container">
                  <!-- <i class="fa fa-angle-left pull-right"></i> -->
                </span>
              </a>
            </li>
            <li class="treeview">
              <a href="<?php echo site_url('b2b_billing_invoice_controller/invoices_detail?type=invoices_detail')?>">
                <i class="fa fa-circle-o"></i>
                <span>Invoice Detail</span>
                <span class="pull-right-container">
                  <!-- <i class="fa fa-angle-left pull-right"></i> -->
                </span>
              </a>
            </li>
          </ul>
        </li>

        <!--<li class="treeview">
          <a href="#">
            <i class="fa fa-question-circle"></i>
            <span>Manual Guide</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="treeview">
              <a href="<?php echo site_url('manual_guide')?>">
                <i class="fa fa-circle-o"></i>
                <span>Manual Guide Info</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>
          </ul>
        </li> -->
 
      </ul>

      

      
         


      <?php } ?>

      

    </section>
    <!-- /.sidebar -->
  </aside>
<div class="modal fade" id="contactus" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Information</h4>
        </div>
        <div class="modal-body">
          <p><b>Office Hour Support (excluding Public Holiday)</b><br>
          Monday to Friday 9:30am to 6:00pm<br>
          Saturday 9:30am to 1:00pm<br>
          </p>

          <p><b>Contact</b><br>
          Call : +6017-745-1185/+6017-715-9340<br>
          Whatsapp : +6017-745-1185<br>
          Email : support@xbridge.my
          </p>

        </div>
        
      </div>
      
    </div>
  </div>


<!-- <script type="text/javascript">
    $(document).ready(function() {
        $('#example-post').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true
        });
    });
</script> -->
  
<div class="se-pre-con"></div>
   
