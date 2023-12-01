<?php 
'session_start()' 
?>
<style>

#poDetails, #promoDetails {
  display: none;
}


b .font {
    font-size: 90px;
}

@media screen and (max-width: 768px) {
  
  p,input,div,span,h4 {
    font-size: 90%;
  }
  h1 {
    font-size: 20px;  
  }
  h4 {
    font-size: 18px;  
  }
  h3 {
    font-size: 20px;  
  }
  input {
    font-size: 16px;
  }
   font{
    font-size: 16px;
  }
  h1.page-head-line{
    font-size: 25px;
  }
  p {
    font-size: 12px;
  }
}

</style>

<script type="text/javascript">
$(document).ready( function() {
  $('#id').click( function( event_details ) {
    $(this).select();
  });
});

</script>
<!--onload Init-->
<body>
    <div id="wrapper">
        
        <div id="page-inner">

            <div class="row">
                <div class="col-md-12">

                    <h1 class="page-head-line">
                        <a href="<?php echo site_url('logout_c/logout')?>" style="float:right">
                        <i class="fa fa-sign-out" style="color:#4380B8"></i></a>

                        <a href="<?php echo site_url('main_controller/home')?>" style="float:right">
                        <i class="fa fa-home" style="color:#4380B8;margin-right:20px"></i></a>
                        
                        <a href="<?php echo site_url('greceive_controller/batch_entry')?>?batch_guid=<?php echo $_SESSION['batch_guid']?>" style="float:right">
                        <i class="fa fa-arrow-left" style="color:#4380B8;margin-right:20px"></i></a>

                         <font>GRN BY PO<br>
                         <small><b><?php echo $heading?></b></small></font> 
                    </h1>
                        <!--<h1 class="page-subhead-line"></h1>-->
                </div>
            </div>      


                <!--1-->
            <div class="row">
                    <!--1.1-->
                <div class="col-md-4">
                    <form class="form-inline" role="form" method="POST" id="myForm" action="<?php echo site_url('greceive_controller/item_entry_update')?>?item_guid=<?php echo $_REQUEST['item_guid']?>&batch_guid=<?php echo $_REQUEST['batch_guid']?>&posum_guid=<?php echo $_REQUEST['posum_guid']?>">
                        <div class="form-group">
                            <h5>Order: <b><?php echo $order_qty?></b>&nbsp&nbsp
                           Balance: <b><?php echo $balance_qty?></b>&nbsp&nbsp
                           FOC: <b><?php echo $foc_qty?></h5>
                              
                                <h4>Description: <b><?php echo $description?>&nbsp&nbsp&nbsp&nbsp<?php echo $line_no?></b></h4>

                                <div class="row">
                                  <div class="col-md-5 col-xs-3 form-group">
                                    <h5 ><b>DO Qty</b></h5>
                                    <input
                                     value="<?php echo $do_qty?>" style="text-align:center;width:80px;background-color:#ffff99" name="do_qty" type="number" step="any"/>
                                     <input type="hidden" name="balance_qty" value="<?php echo $balance_qty?>">
                                  </div>

                                  <div class="col-md-6 col-xs-6 form-group">
                                    <h5><b>Received Qty</b></h5>
                                    <input value="<?php echo $received_qty?>" name="rec_qty" type="number" step="any" style="text-align:center;width:80px;background-color:#80ff80" autofocus onfocus="this.select()"/>
                                  </div>
                                </div>
                                
                                <div class="row">
                                  <div class="col-md-5 col-xs-3 form-group">
                                  <h5><b>Weight(kg)</b></h5>
                                    <input autofocus value="<?php echo $scan_weight?>" type="number" step="any" name="weight" style="text-align:center;width:80px;background-color:#e6ccff" onfocus="this.select()"/>
                                  </div>
                                  <div class="col-md-6 col-xs-6 form-group">
                                    <?php
                                    if($check_trace_qty == '1')
                                    {
                                      ?>
                                      <h5><b>Trace Qty</b></h5>
                                      <input  value="<?php echo $trace_qty?>" type="number" step="any" name="trace_qty" style="text-align:center;width:80px;background-color:#f4b042"/>
                                      <?php
                                    }
                                    else
                                    {
                                      ?>
                                       <input type="hidden" value="<?php echo $WeightTraceQtyCount?>" name="trace_qty" type="hidden"> 
                                      <?php
                                    }
                                    ?>
                                  </div>
                                </div>
                            
                                <br>
                                
                                <button value="submit" name="submit" type="submit" class="btn btn-success btn-xs" style="background-color:#00b359;"><b>SAVE</b></button>

                                <h5><b>Expired Date</b></h5>
                                <input type="date" class="form-control" value="<?php echo $expiry_date?>" style="width: 220px" name="expiry_date">

                                <h5><b>Reason</b></h5>
                                <select name="reason" class="form-control" style="width: 220px;background-color:#ccf5ff"  >
                                <!-- <option selected data-default disabled style="display: none;">Select Reason:</option> -->
                                <?php
                                foreach($set_master_code->result() as $row)
                                {
                                    ?>
                                <option><?php echo $row->CODE_DESC;?></option>
                                    <?php
                                }
                                ?>
                                
                                </select>
                                <input value="<?php echo $balance_qty?>" name="balance_qty" type="hidden">
                                
                                <input value="<?php echo $WeightTraceQty?>" name="WeightTraceQty" type="hidden">
                                <input value="<?php echo $WeightTraceQtyUOM?>" name="WeightTraceQtyUOM" type="hidden">
                                
                                <input value="<?php echo $PurTolerance_Std_plus?>" name="PurTolerance_Std_plus" type="hidden">
                                <input value="<?php echo $PurTolerance_Std_Minus?>" name="PurTolerance_Std_Minus" type="hidden">
                                   
                        </div><br><br><br><br>
                                  
                    </form>

                </div>
            </div>

            <div class="row" >

                    <!--REVIEWS &  SLIDESHOW-->
                    <div class="col-md-8">

                        <div class="row">
                          <div class="col-md-12">
                                         
                              
                                
                            </div>
                          </div>
                    
                    </div>

            </div>
                        

                
   
        </div>
            <!-- /. PAGE INNER  -->
        <!--</div>-->
        <!-- /. PAGE WRAPPER  -->
    </div>