<?php 
'session_start()' 
?>
<div class="content-wrapper" >
<div class="container-fluid">

<style>

#none{
    display: none;
}

#poDetails, #promoDetails {
  display: none;
}

#head{
    font-size: 12px;
  }


b .font {
    font-size: 90px;
}

@media screen and (max-width: 768px) {
  p,input,div,span,h4 {
    font-size: 90%;
  }
  h1 {
    font-size: 2px;  
  }
  h4 {
    font-size: 18px;  
  }
  h3 {
    font-size: 20px;  
  }
  h1 #head{
    font-size: 12px;
  }
  td,th{
    font-size: 10px;
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
    var answer=confirm("Confirm want to delete record ?");
    return answer;
}


</script>
<!--onload Init-->

                <div class="row">
                    <div class="col-md-12">
                            
                        
                        <h1 class="page-head-line">

                            <a href="<?php echo site_url('logout_c/logout')?>" style="float:right">
                            <i class="fa fa-sign-out" style="color:#4380B8"></i></a>

                            <a href="<?php echo site_url('Main_controller/system_admin_menu')?>" style="float:right" >
                            <i class="fa fa-arrow-left" style="color:#4380B8;margin-right:20px"></i></a>
                            
                                <font>Excel Setup<br></font>
                            
                        </h1>
                            
                    </div>
                </div> 
                
                <span style="font-size: 18px;" class="label label-primary"><?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span>
                <br><br>
                <div class="container-fluid">
                  <div class="col-md-6 form-horizontal">
                    <form class="" role="form" action="<?php echo site_url('Export_controller/update')?>" method="post">
                    <!-- <input type="hidden" name="guid" value="<?php echo $guid?>"> -->
                    <div class="form-group">
                       <label for="varchar" class="col-sm-2 control-label">Date From </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="datefrom" id="datefrom" value="<?php echo $datefrom?>" />
                            <input type="hidden" class="form-control" name="customer_guid" id="customer_guid" value="<?php echo $_SESSION['customer_guid']?>" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="varchar" class="col-sm-2 control-label">Date To </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="dateto" id="dateto" value="<?php echo $dateto?>" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="varchar" class="col-sm-2 control-label">Report Type</label>
                        <div class="col-sm-10">
                            <select size="1" name="report_id" id="report_id" class="form-control" >
                                <option value='testing'>Testing</option>
                                
                                 
                            </select>
                        </div>
                    </div>
 
                        <div class="box-footer text-right">
                        <button type="submit" name="save" value="save" class="btn btn-success">Save</button>
                        <button type="submit" name="test" value="save" class="btn btn-primary">Test</button>
                        </div>
                    </div>    
                </form>

               <button type="submit" id="javascript_para" class="btn btn-primary" style="margin-top: 5px;"  
               onclick="ahsheng()"
                >Test excel and email</button><br>
                        <script>
                            function ahsheng() 
                                {
                                 location.href = '<?php echo $form_submit ?>?datefrom='+$('#datefrom').val()+'&dateto='+$('#dateto').val()+'&report_id='+$('#report_id').val()+'&customer_guid='+$('#customer_guid').val();
                                }
                        </script> 
                </div>
        </div>
    </body>
</div>
</div>

