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
          <h3 class="box-title">Select Customer</h3><br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
      <div class="box-body" >
      <div class="col-md-3"></div>
      <div class="col-md-6">
      <form action="<?php echo site_url('login_c/customer_setsession');?>" method='post'>

        <div class="form-group">
        Please Choose a Customer
            <select id="customer" name="customer" class="form-control">
              <?php foreach($customer->result() as $row)
              { ?>
                <option value="<?php echo $row->acc_guid ?>" ><?php echo $row->acc_name; ?></option>
             <?php }
             ?>
            </select>
          
          
        </div>
     
         <br>
         <br>
         <br>
         <br>
          
         <p><button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button></p>

        </form>

             <!-- <p><a href="Panda_home/logout">Logout</a></p> -->
        </div>
        <div class="col-md-3"></div>
        </div>
    </div>
</div>
</div>
<?php //  echo var_dump($_SESSION); ?>
</div>
</div>

