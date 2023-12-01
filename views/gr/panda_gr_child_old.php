  <div class="container">
    <div class="col-md-12 main">

        <div class="row">
        <div class="col-md-2 text-left">
            <a href='<?php echo site_url('panda_gr/index'); ?>' data-toggle="tooltip" title="Back" class="btn btn-warning" role="button">
            <span class="glyphicon glyphicon-home"></span></a>
        </div>
        <?php
  if($this->session->userdata('message'))
  {
    ?>
        <div class="col-md-6 text-center">
        <span class="label label-warning" style="font-size: 14px"><?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span>
        </div>
  <?php
  }
  ?>

 
  
       </div>
       <br>


       <div class="panel panel-info">

          <div class="panel-heading">
              <h3 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span> GR Details</h3>
           </div>

       <div class="panel-body">
       <form class="form-inline" role="form" method="POST" id="myForm" action="<?php echo site_url('panda_gr/check_accept'); ?>">
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
       <?php } ?>
		</div>


    <div class="panel-footer text-right">
     <!--  <a href="<?php echo site_url('#')?>" class="btn btn-success" > <span class="glyphicon 
     glyphicon-ok-sign"  ></span> Accept PO</a> -->
       <button type="submit" class="btn btn-success" >
              <span class="glyphicon glyphicon-ok-sign"  ></span> Confirm GR </button>
              
     <!--  <button class="btn btn-danger" onclick="add_module()" data-toggle="modal" data-taget="#module" type="button"><i class="glyphicon glyphicon-ban-circle"></i> Reject</button>  -->
      <!-- <span onclick="add_module()" class="btn btn-danger" >Reject PO</span> -->
    </div>
		</div>
   
		<div class="panel panel-info">
          <div class="panel-heading">
              <h3 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span> Item Details</h3>
           </div>

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
                        <!-- <td ><?php echo $row->unit_disc_prorate; ?></td>
                        <td ><?php echo $row->unit_price_bfr_tax; ?></td> -->
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
</form>
    </div>
  </div> <!-- /container -->



  <?php // var_dump($_SESSION); ?>


<script type="text/javascript">


  function add_module()
  {
    save_method = 'add';
    $('#module').modal('show'); // show bootstrap modal
    $('.modal-title').text('Reject GR'); // Set Title to Bootstrap modal title
  }


  function confirm_modal(delete_url)
  {
    $('#delete').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm delete ' + button.data('name') + '?')
    document.getElementById('url').setAttribute("href" , delete_url );
    });
  }


    
</script>

