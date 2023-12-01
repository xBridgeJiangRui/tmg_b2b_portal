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
        <a class="btn btn-app" href="<?php echo site_url('panda_gr')?>?loc=<?php echo $grmain->row('location'); ?>">
          <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>
        <a class="btn btn-app" href="<?php echo site_url('panda_gr/confirm')?>?trans=<?php echo $grmain->row('refno'); ?>">
          <i class="fa fa-upload"></i> Confirm GR
        </a>
  </div>
 
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Goods Received Detail</h3><br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>

      <!-- This is for the header -->
          <div class="box-body">
            <div class="col-md-12">
            <br>
              <div>
                <div class="row">
                  <div class="col-md-12"  style="overflow-x:auto"> 
                     <?php
                        foreach ($grmain->result() as $row)
                        {
                      ?>        
                  <div class="row">
                       <div class="col-xs-6 col-md-2">
                       <label>Receiving Location :</label><br>
                       <?php echo $row->location; ?>
                       </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-xs-6 col-md-2">
                    <label>Refno :</label><br>
                    <?php echo $row->refno; ?>
                    </div>
                    <input value="<?php echo $row->refno?>" name="refno" type="hidden">

                    <div class="col-xs-6 col-md-2">
                    <label>GR Date :</label><br>
                    <?php echo $row->grdate; ?>
                    </div>

                    <div class="col-xs-6 col-md-2">
                    <label>Doc Date :</label><br>
                    <?php echo $row->docdate; ?>
                    </div>
      
                    <div class="col-xs-6 col-md-2">
                    <label>Doc No :</label><br>
                    <?php echo $row->dono; ?>
                    </div>
      
                    <div class="col-xs-6 col-md-2">
                    </div>
                  </div>
                <br>
                <div class="row">
                      <div class="col-xs-6 col-md-2">
                      <label>Total Before Tax :</label><br>
                      <?php echo number_format($row->total,2); ?>
                      </div>
        
                      <div class="col-xs-6 col-md-2">
                      <label>Tax Amount :</label><br>
                      <?php echo number_format($row->gst_tax_sum,2); ?>
                      </div>
        
                      <div class="col-xs-6 col-md-2">
                      <label>Total After Tax :</label><br>
                      <?php echo number_format($row->total_include_tax,2); ?>
                      </div>
        
                      <div class="col-xs-6 col-md-2">
                      <label>Purchase tax Code :</label><br>
                      <?php echo $row->tax_code_purchase; ?>
                      </div>
        
                      <div class="col-xs-6 col-md-2">
                      <label>Status :</label>
                      <p style="font-size:20px"><span class="label label-primary"><?php echo $row->status; ?></span></p>
                      </div>
                </div>

                      <?php 
                          } 
                      ?>
                  </div>
               </div>
              </div>
            </div>
          </div>

          <!-- This is END for the header -->
          <!-- This is for the child -->
          <div class="box-body">
            <div class="col-md-12">
            <br>
              <div>
                <div class="row">
                  <div class="col-md-12"  style="overflow-x:auto">
                    <div class="panel-body">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                              <tr>
                                  <!--Begin=Column Header-->
                                  <th>No.</th>
                                  <th>Price Type</th>
                                  <th>Itemcode</th>
                                  <th>Barcode</th>
                                  <th>Description</th>
                                  <th>Qty</th>
                                  <th>Unit Price before Disc</th>
                                  <th>Item Disc Amount</th>
                                 <!--  <th>Bill Discount Prorated</th>
                                  <th>Unit Price After Disc</th> -->
                                  <th>Total Amount</th>
                                  <th>GST Tax Amount</th>
                                  <th>Total Include Tax</th>
                                  
                                  <!-- <th>Action</th> -->
                                  <!--End=Column Header-->
                              </tr>
                          </thead>
                          <tbody>
                            <?php
                                foreach ($grchild->result() as $row)
                                {
                            ?>     
                                <tr>
                                  <td ><?php echo $row->Line; ?></td>
                                  <input value="<?php  echo $row->Line?>" name="line[]" type="hidden">
                                  <input value="<?php echo $row->Itemcode?>" name="itemcode" type="hidden">
                                  
                                  <td ><?php echo $row->PriceType; ?></td>
                                  <td ><?php echo $row->Itemcode; ?></td>
                                  <td ><?php echo $row->barcode; ?></td>
                                  <td ><?php echo $row->Description; ?></td>
                                  <td style="text-align:right"><?php echo number_format($row->Qty,2); ?></td>
                                  <td style="text-align:right"><?php echo number_format($row->UnitPrice,2); ?></td>
                                  <td style="text-align:right"><?php echo number_format($row->DiscAmt,2); ?></td>
                                  <td style="text-align:right"><?php echo number_format($row->TotalPrice,2); ?></td>
                                  <td style="text-align:right"><?php echo number_format($row->gst_tax_amount,2); ?></td>
                                  <td style="text-align:right"><?php echo number_format($row->TotalPrice + $row->gst_tax_amount,2) ; ?></td>
                                  
                                </tr>
                            <?php } ?>
                          </tbody>
                      </table>
                    </div>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <!-- This is END for the child section -->
      </div>
    </div>
  </div>
<?php  // echo var_dump($_SESSION); ?>
</div>
</div>

