<body style="background-color: #E2E2E2;">
    <div class="container">
        <div class="row text-center " style="padding-top:50px;">
            <div class="col-md-12">
                <!--<img src="assets/img/panda.png" class="img-thumbnail" alt="Cinque Terre" width="184" height="100" />-->
                <img src="<?php echo base_url('assets/img/panda.png');?>" class="img-thumbnail" alt="Cinque Terre" width="100" height="60" />
                <br />
                <h4>PANDA RETAIL SYSTEM</h4>
               

            </div>
        </div>
        <div class="row ">
               
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                           
                <div class="panel-body">
                    <form role="form" method="post" action="<?php echo site_url('main_controller/login_form'); ?>">
                        <hr style="background-color:#00b359"/>            
                        <!--<br />-->

                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input name="username" type="text" class="form-control" placeholder="Username"
                            style="background-color:#e6fff2" autofocus/>     
                        </div>
                        <span class="help-block"><?php echo form_error('username') ?></span>

                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
                            <input name="userpass" type="password" class="form-control"  placeholder="Password" 
                            style="background-color:#e6fff2" />
                        </div>
                        <span class="help-block"><?php echo form_error('userpass') ?></span>
                         
                        <div class="form-group input-group" style="float:right">
                            
                            <select name="location" class="form-control" 
                            style="width:170px;background-color:#e6fff2" class="form-control" >
                            <!-- <option selected data-default disabled style="display: none;">Location</option> -->

                                <?php
                                foreach($location->result() as $row)
                                {
                                    ?>
                                <option selected ><?php echo $row->sublocation;?></option>
                                    <?php
                                }
                                ?>
                                
                            </select>
                        </div>

                        <span class="help-block"><?php echo form_error('location') ?></span>
                        
                        <div class="form-group">                
                            <!--<span class="pull-right">
                            <a href="" >Forget password ? </a> 
                            </span>-->
                            <span class="pull-right">
                            <!--<h6><small>&copy; Panda Software House Sdn. Bhd.</small></h6>-->
                            </span>
                        </div>
                                     
                        <button class="btn btn-primary" type="submit" name="login" value="Login" >Login</button>
                        <hr />
                        <h5><small><?php echo 'Current PHP version: ' . phpversion();?></small><br>
                        <small>
                            &copy; Panda Software House Sdn. Bhd.
                        </small></h5>
                        <?php $segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));   ?>
                        <a href="http://<?php echo  $_SERVER['SERVER_NAME']; ?>/<?php echo $segments[0] ?>/assets/com.android.chrome.apk" >

                        <h5>Dont Have Chrome? Download here </h5></a>
                       
                    </form>       
                </div>
                           
            </div>
                
        </div>
    </div>


