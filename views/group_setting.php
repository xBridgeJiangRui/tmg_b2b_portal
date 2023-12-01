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
  p {
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
                        
                        <a href="<?php echo site_url('main_controller/home')?>" style="float:right">
                        <i class="fa fa-arrow-left" style="color:#4380B8;margin-right:20px"></i></a>
                        
                        <font>User Group Setting</font>
                    </h1>
                        <!--<h1 class="page-subhead-line"></h1>-->
                </div>
            </div>      
                <!--1-->
            <div class="row">
                    <!--1.1-->
                <div class="col-md-4">
                    <form class="form-inline" role="form" method="POST" id="myForm" action="<?php echo site_url('main_controller/add_trans'); ?>">
                        <div class="form-group">
                          <table>
                            <thead>
                              <tr>
                                <td><b>User Group</td>
                                <td><b>Show Cost</td>
                              </tr>
                            </thead>
                            <tbody>
                            <?php foreach($user_group->result() as $row)
                            {
                              ?>
                              <tr>
                                <td><?php echo $row->group_name; ?></td>

                                
                                <td style="text-align: center">
                                <input type="hidden" name="show_cost[]" 
                                <?php if($row->show_cost == 0)
                                {
                                echo 'value="0"';
                                }
                                else
                                {
                                  echo 'value="1"';
                                }
                                ?>
                                ><input type="checkbox"
                                <?php if($row->show_cost == 0)
                                {
                                  echo " ";
                                }
                                else
                                {
                                  echo "checked";
                                } ?> 
                                  onchange="this.previousSibling.value=1-this.previousSibling.value" 
                                  /></td>
                                 <input type='hidden' name="user_group_guid[]" value = "<?php echo  $row->user_group_guid; ?>"/>

                              <?php }
                                ?>
                             
                              </tr>
                      
                              </tbody>
                              </table>
                            <button value="save" name="save" type="submit" class="btn btn-success btn-xs" style=""><b>SAVE</b></button>
                        </div>
                    </form><br>
                </div>
            </div>

                
   
        </div>
            <!-- /. PAGE INNER  -->
        <!--</div>-->
        <!-- /. PAGE WRAPPER  -->
    </div>

