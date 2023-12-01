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
<?php
function readtime($time,$toTz,$fromTz)
{   
    // timezone by php friendly values
    $date = new DateTime($time, new DateTimeZone($fromTz));
    $date->setTimezone(new DateTimeZone($toTz));
    $time= $date->format('Y-m-d H:i:s');
    return $time;
}
?>
                <div class="row">
                    <div class="col-md-12">
                            
                        
                        <h1 class="page-head-line">

                            <a href="<?php echo site_url('Logout')?>" style="float:right">
                            <i class="fa fa-sign-out" style="color:#4380B8"></i></a>

                            <a href="<?php echo site_url('dashboard')?>" style="float:right" >
                            <i class="fa fa-arrow-left" style="color:#4380B8;margin-right:20px"></i></a>
                            
                                <font>Fax Setup<br></font>
                        </h1>
                         <h4 class="page-head-line">

                            <a href="<?php echo site_url('Logout')?>" style="float:right">
                            <i class="fa fa-sign-out" style="color:#4380B8"></i></a>

                            <a href="<?php echo site_url('dashboard')?>" style="float:right" >
                            <i class="fa fa-arrow-left" style="color:#4380B8;margin-right:20px"></i></a>
                            
                                <font>Balance : RM<?php echo $fax_balance;?><br></font>
                        </h4>
                            
                            
                    </div>
                </div> 
                
                <span style="font-size: 18px;" class="label label-primary"><?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span>
                <br><br>
                <div class="container-fluid">
                  <div class="col-md-6 form-horizontal">
                    <form class="" role="form" action="<?php echo site_url('fax/update')?>" method="post">
                    <!-- <input type="hidden" name="guid" value="<?php echo $guid?>"> -->
                    <div class="form-group">
                       <label for="varchar" class="col-sm-2 control-label">Fax User </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="fax_user" value="<?php echo $fax_user?>" placeholder="fax user"/>
                        </div>
                    </div>
                
                </div>
                <div class="col-md-6 form-horizontal">
                        <div class="form-group"> 
                        <label for="varchar" class="col-sm-2 control-label">Password </label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password" placeholder="password" value="<?php echo $fax_password?>" />
                        </div>
                        </div>

                        <div class="text-right">
                        <button type="submit" name="save" value="save" class="btn btn-success">Save</button>
                        <button type="submit" name="test" value="test" style=""  class="btn btn-primary">Test Send</button>
                        <a href="<?php echo site_url('fax/insert_fax_record')?>"><button type="button" name="save_local" value="test" style=""  class="btn btn-info">Store Record Local</button></a>

                    </div>
                    </div>  
                </form>
                </div>
<br>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title">Fax List</h3>
                          <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                          </div>
                        </div>
                        <div class="box-body" >
                         <div class="box-body" style="overflow-y: hidden ">
                            <table id="fax_list" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Destination</th>
                            <th>Page Send</th>
                            <th>Send Time</th>
                            <th>End Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            foreach($faxlist as $row)
                            {
                              ?>                  
                               <tr>
                                <td><?php echo $row->id;?></td>
                                <td><?php echo $row->userId;?></td>
                                <td>
                                  <?php 
                                  if($row->status == 0)
                                  {
                                    $status = $this->db->query("SELECT error_msg FROM lite_b2b.fax_error WHERE code = '$row->status'")->row('error_msg');
                                  }
                                  else
                                  {
                                    $status = $this->db->query("SELECT error_msg FROM lite_b2b.fax_error WHERE code = '$row->status'")->row('error_msg');
                                  }
                                  echo $status;
                                  ?>                        
                                </td>
                                <td><?php echo $row->destinationFax;?></td>
                                <td><?php echo $row->pagesSent;?></td>
                                <td><?php 
                                $userTime=$row->submitTime; 
                                $userTimezone="Asia/Kuala_Lumpur";
                                $userConvertedTime = readtime($userTime,$userTimezone,'UTC');

                                echo $userConvertedTime
                                // echo $row->submitTime
                                ;?></td>
                                <td><?php 
                                $userTime=$row->completionTime; 
                                $userTimezone="Asia/Kuala_Lumpur";
                                $userendConvertedTime = readtime($userTime,$userTimezone,'UTC');
                                echo $userendConvertedTime;
                                ?></td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>  
                            </tbody>
                            </table>
                          </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </body>
</div>
</div>

