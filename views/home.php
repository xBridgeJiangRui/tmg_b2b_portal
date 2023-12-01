<style>
.logout-header{
    float:right;
}
</style>

<body>
    <div id="wrapper">
        
        <!-- /. NAV TOP  -->
         
        <!-- /. NAV SIDE  -->
        <!--<div id="page-wrapper">-->
            <div id="page-inner">                

                <!-- ROW  -->
                <!--<div class="row">-->

                    <!--REVIEWS &  SLIDESHOW  -->
                <ul class="nav" id="main-menu">
                    <li>
                        <div class="user-img-div">

                            <img src="<?php echo base_url('assets/img/panda.png')?>" class="img-rounded" />
                            
                            <div class="logout-header">

                  <!--           <a href="<?php echo site_url('logout_c/clearSession')?>"  title="clearSession" >
                            <span class="glyphicon glyphicon-refresh" style="color:black;font-size:20px;margin-right: 26px"> -->
                            <?php foreach($user_group->result() as $ug){
                                if($ug->user_group_guid == 'currently_hiding_this_setting_until_further_notice') {
                                    ?>
                            <a href="<?php echo site_url('main_controller/group_setting')?>"><span class="glyphicon glyphicon-cog" style="color:black;font-size:20px;margin-right: 20px"></span></a> 
                                <?php }
                                }  ?>
                                
                            <?php if($show_dropbox == '1') { ?>
                            <a href="<?php echo site_url('main_controller/reload_from_dropbox')?>"  title="Reload" ><i class="fa fa-dropbox" style="color:black;font-size:20px;margin-right:10px""></i></a>
                            <?php } ?>
                            
                             <a href="<?php echo site_url('logout_c/logout')?>"  title="Logout" >
                            <span class="glyphicon glyphicon-log-out" style="color:black;font-size:20px;margin-right:10px"></span></a>

                            
                           

                            
                            </div>
                            
                            <div class="inner-text">
                             <?php // echo var_dump($_SESSION) ; ?>
                                <strong>Login as: </strong><?php echo $_SESSION["username"] ?>
                                <strong>Loc: </strong><?php echo $_SESSION["location"] ?>
                                <br />
                                
                                <small><span id="date_time"></span>
                                <script type="text/javascript">window.onload = date_time('date_time');</script>
                                </small>
                            </div>
                            
                        </div>
                    </li>
                    
                    <?php
                        $parent_name = $this->db->query("SELECT parent_name FROM backend_warehouse.`module_menu`  where hide_menu = '0' GROUP BY parent_name ORDER BY parent_sequence ASC;");
                        
                        foreach($parent_name->result() as $row)
                        {
                            ?>
                        
                        <li class="menu">
                            <a href="#" style="color:black"><i class="fa fa-dot-circle-o" style="color:#00b359"></i>&nbsp<b><?php echo $row->parent_name?></b></a>
                            <ul class="nav nav-second-level">
                            <?php
                            $i = 0;
                                $module_name = $this->db->query("SELECT a.module_name, a.module_link 
                                FROM backend_warehouse.`module_menu` AS a
                                INNER JOIN backend_warehouse.set_user_group_webmodule AS b 
                                ON a.`module_name`=b.module_name 
                                INNER JOIN backend_warehouse.set_user_group AS c 
                                ON b.user_group_guid=c.user_group_guid 
                                INNER JOIN backend_warehouse.set_user AS d 
                                ON d.user_group_guid=c.user_group_guid  
                                WHERE parent_name = '".$row->parent_name."' 
                                AND user_name='".$_SESSION['username']."'
                                AND hide_menu <> 1 ORDER BY sequence ASC ");
                                foreach($module_name->result() as $row2)
                                {
                            ?>
                                <li>
                                    <a href="<?php echo site_url($row2->module_link)?>" style="color:black"><?php echo ++$i ?>.&nbsp<?php echo $row2->module_name?></a>
                                </li>

                            <?php
                                }
                            ?>
                            </ul>
                        </li>
                    
                            <?php
                        }
                    ?>

                </ul>
                    <!-- /.REVIEWS &  SLIDESHOW  -->
                    
                    <!--4-->
                    
                <!--</div>-->
                <!-- /. ROW  -->

                <!--5-->
                
                <!--5-->

                <!--/.Row-->
                <hr />
            </div>
            <!-- /. PAGE INNER  -->
        <!--</div>-->
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->

    <!--<div id="footer-sec">
        &copy; Panda Software House Sdn. Bhd.
    </div>-->
    <!-- /. FOOTER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->