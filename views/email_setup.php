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

                             <a href="<?php echo site_url('Logout')?>" style="float:right">
                            <i class="fa fa-sign-out" style="color:#4380B8"></i></a>

                            <a href="<?php echo site_url('dashboard')?>" style="float:right" >
                            <i class="fa fa-arrow-left" style="color:#4380B8;margin-right:20px"></i></a>
                            
                                <font>Email Setup<br></font>
                            
                        </h1>
                            
                    </div>
                </div> 
                
                <span style="font-size: 18px;" class="label label-primary"><?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span>
                <br><br>
                <div class="container-fluid">
                  <div class="col-md-6 form-horizontal">
                    <form class="" role="form" action="<?php echo site_url('Email_controller/update')?>" method="post">
                    <input type="hidden" name="guid" value="<?php echo $guid?>">
                    <div class="form-group">
                       <label for="varchar" class="col-sm-2 control-label">SMTP Server </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="smtp_server" value="<?php echo $smtp_server?>" placeholder="smtp.server.com"/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="varchar" class="col-sm-2 control-label">SMTP Port </label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="smtp_port" placeholder="Port" value="<?php echo $smtp_port?>" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="varchar" class="col-sm-2 control-label">SMTP Secure</label>
                        <div class="col-sm-10">
                            <select size="1" name="smtp_security" class="form-control" >
                                <option selected data-default>TLS</option>
                                <option >SSL</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group"> 
                        <label for="varchar" class="col-sm-2 control-label">Username </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="username" placeholder="SMTP Server Username" value="<?php echo $username?>" />
                        </div>
                    </div>

                    <div class="form-group"> 
                        <label for="varchar" class="col-sm-2 control-label">Password </label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password" placeholder="SMTP Server Password" value="<?php echo $password?>" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                <input type="hidden" name="active" value="0" />
                                    <input type="checkbox" name="active" value="1" 
                                     <?php
                                     if($active == 1)
                                     {
                                        ?>
                                        checked
                                        <?php
                                     }
                                     ?>><font style="font-size: 18px">Active</font>
                                </label>
                            </div>
                        </div>
                    </div> 
                
                </div>
                <div class="col-md-6 form-horizontal">
                    
                        <div class="form-group"> 
                            <label for="inputEmail3" class="col-sm-2 control-label">Sender Name </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="sender_name" placeholder="Your Name" value="<?php echo $sender_name?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Sender Email </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="sender_email" placeholder="Your.Email@example.com" value="<?php echo $sender_email?>" />
                            </div>
                        </div>

                        <div class="form-group"> 
                            <label for="inputEmail3" class="col-sm-2 control-label">Recipient Name </label>
                            <div class="col-sm-10">

                                <input type="text" class="form-control" name="recipient_name" placeholder="Recipient's Name" value="<?php echo $recipient_name?>" />
                            </div>
                        </div>

                        <div class="form-group"> 
                            <label for="inputPassword3" class="col-sm-2 control-label">Recipient Email </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="recipient_email" placeholder="Recipients.Email@example.com" value="<?php echo $recipient_email?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Subject </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="subject" placeholder="Email Subject" value="<?php echo $subject?>" />
                            </div>
                        </div>

                        <div class="box-footer text-right">
                        <button type="submit" name="save" value="save" class="btn btn-success">Save</button>
                        <button type="submit" name="test" value="test" style=""  class="btn btn-primary">Test Send</button>
                    
                        </div>
                    </div>    
                </form>
                </div>
        </div>
    </body>
</div>
</div>

