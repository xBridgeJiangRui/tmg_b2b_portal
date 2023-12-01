<?php 
'session_start()' 
?>


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
                            
                                <font>Email Report</font>
                            
                        </h1>
                            
                    </div>
                </div>    

                <div class="container-fluid">
                  <div class="row">
                    <div class="col-md-12">

                    <!-- <div class="alert alert-success text-center">
                    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
                      <span><?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span>
                    </div> -->
                    <!-- <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-search"></i> Advance Search</button> -->
                    <a href="<?php echo $export_url?>">
                    <button type="submit" class="btn btn-success btn-sm" style="float:right"><b><i class="fa">&#xf1c3;</i> EXPORT</b></button></a> 
                    <br><br>
                    <div style="overflow-x:auto;">
                    <table id="smstable" class="tablesorter table table-striped table-bordered table-hover"> 
                        <thead>
                        <tr>
                          <th>Status</th>
                          <th>Recipient</th>
                          <th>Sender</th>
                          <th>Subject</th>
                          <th>SMTP Server</th>
                          <th>SMTP Port</th>
                          <th>SMTP Security</th>
                          <th>Respond Message</th>
                          <th>Created At</th>
                          <th>Created By</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($transaction->result() as $row)
                        {
                          ?>
                           <tr>
                              <td><?php echo $row->status?></td>
                              <td><?php echo $row->recipient?></td>
                              <td><?php echo $row->sender?></td>
                              <td><?php echo $row->subject?></td>
                              <td><?php echo $row->smtp_server?></td>
                              <td><?php echo $row->smtp_port?></td>
                              <td><?php echo $row->smtp_security?></td>
                              <td><?php echo $row->respond_message?></td>
                              <td><?php echo $row->created_at?></td>
                              <td><?php echo $row->created_by?></td>
                              </tr>
                          <?php
                        }
                        ?>
                        </tbody>
                      </table>
                      </div> 
                    </div>
                  </div>
                </div>
        </div>
    </body>


<!-- Trigger the modal with a button -->


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Advance Search</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo site_url('Sms_controller/search_report')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/> 
                      <div class="form-body">
                        <div class="form-group">
                        <label class="control-label col-md-3">Date From</label>
                            <div class="col-md-9">
                                <input name="datefrom" class="form-control" type="date" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Date To</label>
                            <div class="col-md-9">
                                <input name="dateto" class="form-control" type="date" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Merchant ID</label>
                            <div class="col-md-9">
                                <input name="merchant_id" class="form-control" type="text" required>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-sm btn-primary">Search</button>
                      <a href="<?php echo site_url('Sms_controller/report')?>"><button type="button" class="btn btn-default" >Close</button></a>
                  </div>
                </form>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
    function search()
    {
        save_method = 'add';
        $('#search').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add New'); // Set Title to Bootstrap modal title
    }
</script>
