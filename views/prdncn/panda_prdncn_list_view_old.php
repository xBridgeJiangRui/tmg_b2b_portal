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
          <h3 class="box-title">PRDN CN</h3><br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
      <div class="box-body">
      <div class="col-md-12">
        <br>
        <div>
            <div class="row">
                <div class="col-md-12"  style="overflow-x:auto"> 
                    <table id="paloi" class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                            <?php // var_dump($_SESSION); ?>
                                <!--Begin=Column Header-->
                                <th>Refno</th>
                                <th>Outlet</th>
                                <th>Doc Date</th>
                                <th>Doc No</th>
                                <th>Amount</th>
                                <th>GST</th>
                                <th>Total Incl Tax</th>
                                <th>Action</th>
                                <!--End=Column Header-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($result->result() as $row)
                                {
                             ?>
                        <tr>
                          <td><a href="<?php echo site_url('panda_prdncn/prdncn_child')?>?trans=<?php echo $row->refno?>&loc=<?php echo $_REQUEST['loc'] ?>"><?php echo $row->refno; ?></a></td>
                          <td ><?php echo $row->location; ?></td>
                          <td ><?php echo $row->docdate; ?></td>
                          <td ><?php echo $row->docno; ?></td>
                          <td style="text-align: right"><?php echo number_format($row->amount,2);?></td>
                          <td style="text-align: right"><?php echo number_format($row->gst_tax_sum,2); ?></td>
                          <td style="text-align: right"><?php echo number_format($row->amount+$row->gst_tax_sum,2) ?></td>
                             
                          <td><a href="<?php echo site_url('panda_prdncn/prdncn_child')?>?trans=<?php echo $row->refno?>&loc=<?php echo $_REQUEST['loc'] ?>" style="float:left" class="btn btn-info" role="button"><span class="glyphicon glyphicon-list"></span></a></td>
                        </tr>
                            <?php
                                }
                            ?> 
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
             <!-- <p><a href="Panda_home/logout">Logout</a></p> -->
        </div> 
      </div>
    </div>
</div>
</div>
 
<?php  // echo var_dump($_SESSION); ?>
</div>
</div>

