<?php 
'session_start()' 
?>
<style>

@media screen and (max-width: 768px) {
  p,input,div,span,h4 {
    font-size: 7px;
  }
  h1,h3{
    font-size: 20px;  
  }
  h4 {
    font-size: 18px;  
  } 
  h6,td.big {
    font-size: 12px;
  }
  font{
    font-size: 16px;
  }
  h1.page-head-line{
    font-size: 25px;
  }
}

</style>

<script type="text/javascript">

$(document).ready(function() 
    { 
        $("#myTable").tablesorter(); 
    } 
);

function check()
{
    var answer=confirm("Confirm want to delete item ?");
    return answer;
}

</script>
<!--onload Init-->
<body>
    <div id="wrapper">
        
        <div id="page-inner">
        <div class="fixed">
            <div class="row">
                <div class="col-md-12">

                    <h1 class="page-head-line">
                        <a href="<?php echo site_url('logout_c/logout')?>" style="float:right">
                        <i class="fa fa-sign-out" style="color:#4380B8"></i></a>

                        <a href="<?php echo site_url('main_controller/home')?>" style="float:right">
                        <i class="fa fa-home" style="color:#4380B8;margin-right:20px"></i></a>
                        
                        <a href="<?php echo $back_button?>" style="float:right">
                        <i class="fa fa-arrow-left" style="color:#4380B8;margin-right:20px"></i></a>
                        
                        <font>scan log<br><small><b><?php echo $type?></b></small></font>

                    </h1>
                        <!--<h1 class="page-subhead-line"></h1>-->
                </div>
            </div>
        </div>

                <!-- ROW  -->
            <div class="row" >

                    <!--REVIEWS &  SLIDESHOW  -->
                <div class="col-md-8">

                    <div class="row">
                        <div class="col-md-12">

                          <h5><b>Ref No :</b> <?php echo $refno?></h5>
                            
                            <div style="overflow-x:auto;">
                                <table id="myTable" class="tablesorter table table-striped table-bordered table-hovers">
                                  <thead style="cursor:s-resize">
                                    <tr>
                                      <th>Line</th>
                                      <th style="text-align:center;">Rec.Qty</th>
                                      <th style="text-align:center;">Description</th>
                                      <th style="text-align:center;">Itemcode</th>
                                      <th style="text-align:center;">Created At</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                        foreach ($result->result() as $row)
                                        {
                                            ?>
                                            <tr>
                                              <td class="big"><?php echo $row->lineno; ?></td>
                                              <td class="big" style="text-align:center;"><?php echo $row->scan_qty; ?></td>
                                              <td class="big" style="text-align:center;"><?php echo $row->scan_description; ?></td>
                                              <td class="big" style="text-align:center;"><?php echo $row->scan_itemcode; ?></td>
                                              <td class="big" style="text-align:center;"><?php echo $row->created_at; ?>
                                                <a style="float:right;" href="<?php echo site_url('Main_controller/scan_log')?>?delete_scan&delete_batch_scan=<?php echo $row->scan_qty?>&item_guid=<?php echo $row->item_guid?>&scan_guid=<?php echo $row->scan_guid?>&type=<?php echo $type ?>&uniq_guid=<?php echo $uniq_guid?>" class="btn btn-xs btn-danger" onclick="return check()">
                                            <span class="glyphicon glyphicon-trash"></span> </a>
                                              </td>
                                            </tr>
                                            <?php
                                        }
                                    ?> 
                                  </tbody>
                                </table>
                            </div>
                            
                        </div>

                    </div>
                        <!-- /. ROW  -->
                </div>

            </div>
        </div>
            <!-- /. PAGE INNER  -->
        <!--</div>-->
        <!-- /. PAGE WRAPPER  -->
    </div>