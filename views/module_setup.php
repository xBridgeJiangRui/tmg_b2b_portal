<!-- Content Wrapper. Contains page content -->
<style type="text/css">
  #acc_master_setup_details{
    height: 250px;
    overflow-y: scroll;

  }
  #user_setup_details{
    height: 250px;
    overflow-y: scroll;

  }
</style>

<div class="content-wrapper" >
<div class="container-fluid"><!-- <?php echo var_dump($_SESSION)?>  -->
<br>
 <?php
  if($this->session->userdata('message'))
  {
     echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; 
  }
  ?>
  <div class="row">
      <div class="col-md-8">
           <button title="Update Outlet" onclick="confirm_update('<?php echo site_url('module_setup/UpdateAllOutlet') ?>?module_group_guid=<?php echo $_SESSION['module_group_guid'] ?>&customer_guid=<?php echo $_SESSION['customer_guid'] ?>')" 
              type="button" 
              class="btn btn-xs btn-warning" 
              data-toggle="modal" 
              data-target="#UpdateAllOutlet" 
              data-name="<?php echo 'module_group_guid'; ?>" ><i class="glyphicon glyphicon-map-marker"></i>Update Location for All User</button>

              <button title="Update SUPERUSER" onclick="confirm_update('<?php echo site_url('module_setup/update_all_cus_all_outlet') ?>?module_group_guid=<?php echo $_SESSION['module_group_guid']?>')" 
              type="button" 
              class="btn btn-xs btn-danger" 
              data-toggle="modal" 
              data-target="#UpdateAllOutlet" 
              data-name="<?php echo 'module_group_guid'; ?>" ><i class="fa fa-retweet" aria-hidden="true"></i>Sync Outlet to Superuser</button>  

              <a href="<?php echo site_url('module_setup/duplicate_user') ?>?from=<?php echo $_SESSION['customer_guid'] ?>&to=&filter=false" 
                class="btn btn-xs btn-info" 
                ><i class="fa fa-files-o" aria-hidden="true"></i>Duplicate User</a>

              <?php
              if(in_array('DBU',$_SESSION['module_code'])&& $call_user->num_rows() > 0)
              {
              ?>
                <a href="<?php echo site_url('module_setup/duplicate_by_user') ?>?from=<?php echo $_SESSION['customer_guid'] ?>&to=&filter=false" 
                class="btn btn-xs btn-success" 
                ><i class="fa fa-files-o" aria-hidden="true"></i>Duplicate By User</a> 

              <?php
              }
              ?>

              
      </div>
  </div>
  
  <div class="row">
    <div class="col-md-8">
      <?php  // echo var_dump($_SESSION)?>  
      <!-- User@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">User</h3>

          <div class="box-tools pull-right">

          <?php
          if(in_array('U1MS',$_SESSION['module_code'])&& $call_user->num_rows() > 0)
          {
            ?>
            <button class="btn btn-xs btn-success" onclick="$('#formUser').submit()"><i class="glyphicon glyphicon-floppy-saved"></i> Save</button>
            <?php
          }
          ?>

          <?php
          if(in_array('C1MS',$_SESSION['module_code']))
          {
            ?>
            <button class="btn btn-xs btn-primary" onclick="add_user()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
           <!--  <button class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-map-marker"></i> Update Location for All User</button> -->
           
            
            <?php
          }
          ?>
          
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        
        <div class="box-body" id="user_setup_details">
        <div id="userCheck">
          <form id="formUser" method="post" action="<?php echo site_url('module_setup/check')?>?table=set_user&col_guid=user_guid&col_check=isactive&edit=2">
          <table id="example11" class="table table-bordered table-hover" >
                <thead>
                <tr>
                  <th>Action</th>
                  <th>Branch</th>
                  <th>Active</th>
                  <th>User ID</th> 
                  <th>User Name</th>
                  <th >User Group</th>
                  <th>Module Group</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($call_user->result() as $row)
                {
                  ?>
                   <tr>
                      <td>
                        <?php
                        if(isset($_SESSION['view_details']) == 1)
                        {
                          ?>  
                          <button  title="View" onclick="view()" type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#view" 
                          data-name ="<?php echo $row->user_name?>"
                          data-created_at ="<?php echo $row->created_at?>"
                          data-created_by="<?php echo $row->created_by?>" 
                          data-updated_at="<?php echo $row->updated_at?>"
                          data-updated_by="<?php echo $row->updated_by?>">
                          <i class="glyphicon glyphicon-eye-open"></i></button>
                          <?php
                        }
                        ?>

                        <?php
                        if(in_array('U1MS',$_SESSION['module_code']))
                        {
                          ?>
                          <button title="Edit" onclick="edit_user()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#user"
                          data-query="SELECT * FROM set_user where user_guid = '<?php echo $row->user_guid?>'" 
                          data-guid="<?php echo $row->user_guid?>"
                          data-name="<?php echo $row->user_name?>" 
                          data-id="<?php echo $row->user_id?>"
                          data-password="<?php echo $row->user_password?>"
                          data-isactive="<?php echo $row->isactive?>"
                          data-user_group="<?php echo $row->user_group_name?>"
                          data-limited_location="<?php echo $row->limited_location?>"
                          data-user_group_guid="<?php echo $row->user_group_guid?>">

                          <i class="glyphicon glyphicon-pencil"></i></button>
                        
                          <?php
                        }
                        ?>

                        <?php
                        if(in_array('D1MS',$_SESSION['module_code']))
                        {
                          ?>  
                          <!-- <button title="Delete" onclick="confirm_modal('<?php echo site_url('module_setup/delete'); ?>?guid=<?php echo $row->user_guid ?>&table=set_user&col_guid=user_guid&delete=2')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->user_name?>" ><i class="glyphicon glyphicon-trash"></i></button> -->
                          <?php
                        }
                        ?>
                        
                      </td>
                      <td>
                        <button title="Assign" onclick="assign_branch()" type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#branch"
                        data-guid="<?php echo $row->user_guid?>"
                        data-acc_guid="<?php echo $row->acc_guid?>"
                        data-module_group_guid="<?php echo $row->module_group_guid?>"
                        data-user_group_guid="<?php echo $row->user_group_guid?>"
                        data-isactive="<?php echo $row->isactive?>"
                        data-user_id="<?php echo $row->user_id?>"
                        data-user_name="<?php echo $row->user_name?>"
                        data-user_password="<?php echo $row->user_password?>"
                        data-created_at="<?php echo $row->created_at?>"
                        data-created_by="<?php echo $row->created_by?>"
                        data-user_group="<?php echo $row->user_group_name?>"
                        data-limited_location="<?php echo $row->limited_location?>"
                        data-module_group="<?php echo $row->module_group_name ?>" ><i class="glyphicon glyphicon-edit"></i></button>

                        <button data-toggle="modal" data-target="#view-modal" 
                        data-id="<?php echo $row->user_guid; ?>" 
                        data-customer_guid ="<?php echo $_SESSION['customer_guid'] ?>" 
                        id="viewbranch" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-eye-open"></i></button>
                      </td>

                      <td>
                      <input type="hidden" name="guid[]" value="<?php echo $row->user_guid?>">
                        <!-- <div ng-controller="userController">    
                          <input type="checkbox" ng-model="checkedvalue" 
                          <?php
                          if($row->isactive == '1')
                          {
                            ?>
                            ng-init="checkedvalue = true"/>
                            <?php
                          }
                          ?> 

                          <?php
                          if($row->isactive == '0')
                          {
                            ?>
                            <input type="checkbox" ng-model="checkedvalue" >
                            <?php
                          }
                          ?>
                                                
                          <input style="display: none;" value="{getNumber()}" name="active[]">
                        </div> -->
                        <input type="checkbox" disabled name="cb_active" <?php if($row->isactive == '1') { echo "checked"; } ?> >
                      </td>

                      <td><?php echo $row->user_id?></td>
                       
                      <td><?php echo $row->user_name?></td>
                      <td
                      <?php 
                      if($_SESSION['user_group_guid'] == $row->user_group_guid)
                      {
                        ?>
                        id="highlight2" 
                        <?php
                      }
                      ?>
                      >
                      <?php echo $row->user_group_name?>
                      </td>
                      <td id="highlight"><?php echo $row->module_group_name?></td>
                      </tr>
                  <?php
                }
                ?>
                </tbody>
              </table>
            </form>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. User@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    </div>

    <div class="col-md-4">

      <!-- Module  Group@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Module Group</h3>
          <div class="box-tools pull-right">
          <?php
          if(in_array('C1MS',$_SESSION['module_code']))
          {
            ?>
            <button class="btn btn-xs btn-primary" onclick="add_module_group()"><i class="glyphicon glyphicon-plus"></i> Add New</button>     
            <?php
          }
          ?>
          
            
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_master_setup_details">

              <table id="example111" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Action</th>
                  <th>Sequence</th>
                  <th>Module Group Name</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($module_group->result() as $row)
                {
                  ?>
                   <tr>
                      <td>
                      <?php
                      if(in_array('V1MS',$_SESSION['module_code']))
                      {
                        ?>  
                        <button  title="View" onclick="view()" type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#view" 
                        data-name ="<?php echo $row->module_group_name?>"
                        data-created_at ="<?php echo $row->created_at?>"
                        data-created_by="<?php echo $row->created_by?>" 
                        data-updated_at="<?php echo $row->updated_at?>"
                        data-updated_by="<?php echo $row->updated_by?>">
                        <i class="glyphicon glyphicon-eye-open"></i></button>
                        <?php
                      }
                      ?>

                      <?php
                      if(in_array('U1MS',$_SESSION['module_code']))
                      {
                        ?>
                        <!-- <button title="Edit" onclick="edit_module_group()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modulegroup" 
                        data-guid="<?php echo $row->module_group_guid?>"
                        data-name="<?php echo $row->module_group_name?>" 
                        data-seq="<?php echo $row->module_group_seq?>"
                        data-group_name="<?php echo $row->module_group_guid?><?php echo '->'?><?php echo $row->module_group_name?>">
                        <i class="glyphicon glyphicon-pencil"></i></button> -->
                        <?php
                      }
                      ?>
                        
                      <?php
                      if(in_array('D1MS',$_SESSION['module_code']))
                      {
                        ?>  
                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('module_setup/delete'); ?>?guid=<?php echo $row->module_group_guid ?>&table=set_module_group&col_guid=module_group_guid&delete=4')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->module_group_name?>" ><i class="glyphicon glyphicon-trash"></i></button>
                        <?php
                      }
                      ?>
                      </td>
                      <td><?php echo $row->module_group_seq?></a></td>
                      <td>
                      <span
                      <?php 
                      if($_SESSION['module_group_guid'] == $row->module_group_guid)
                      {
                        ?>
                        id="highlight" 
                        <?php
                      }
                      ?>
                      class="label label-default" style="font-size: 14px"><a href="<?php echo site_url('Module_setup')?>?module_group_guid=<?php echo $row->module_group_guid?>"><?php echo $row->module_group_name?></a></span>
                      
                      </td>
                    </tr>
                  <?php
                }
                ?>
                </tbody>
              </table>
         
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /Module Group@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    </div>
  </div>

  <div class="row" >
    <div class="col-md-8">

      <!--   @@@@@@@@@@@@@@@@@@@@@@@ User Module @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"> User Module</h3>

          <div class="box-tools pull-right">

          <?php
          if(in_array('U1MS',$_SESSION['module_code']) && $call_user_module->num_rows() > 0)
          {
            ?>
            <button class="btn btn-xs btn-success" onclick="$('#formUM').submit()"><i class="glyphicon glyphicon-floppy-saved"></i> Save</button>
            <?php
          }
          ?>

          <?php
          if(in_array('C1MS',$_SESSION['module_code']))
          {
            ?>
            <button class="btn btn-xs btn-primary" onclick="add_user_module()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
            <?php
          }
          ?>
          
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="user_setup_details">
           <div id="usermoduleCheck">
            <form id="formUM" method="post" action="<?php echo site_url('module_setup/check')?>?table=set_user_module&col_guid=user_module_guid&col_check=isenable&edit=1">
                <div class="tab-pane active" id="view1">
                 <table id="user_module1" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Action</th>
                    <th>Enable</th>
                    <th>Module Code</th>
                    <th>Module Description</th>
                    <th>User Group</th>
                    <th>Module Group</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  foreach($call_user_module->result() as $row)
                  {
                    ?>
                     <tr>
                        <td>
                        <?php
                        if(isset($_SESSION['view_details']) == 1)
                        {
                          ?>  
                          <button  title="View" onclick="view()" type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#view" 
                          data-name ="<?php echo $row->user_group_name?> <?php echo $row->module_name?>"
                          data-created_at ="<?php echo $row->created_at?>"
                          data-created_by="<?php echo $row->created_by?>" 
                          data-updated_at="<?php echo $row->updated_at?>"
                          data-updated_by="<?php echo $row->updated_by?>">
                          <i class="glyphicon glyphicon-eye-open"></i></button>
                          <?php
                        }
                        ?>

                        <?php
                        if(in_array('U1MS',$_SESSION['module_code']))
                        {
                          ?>
                          <!-- <button title="Edit" onclick="edit_user_module()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#usermodule" 
                          data-guid="<?php echo $row->user_module_guid?>"
                          data-user_group_name="<?php echo $row->user_group_name?>"
                          data-user_group_guid="<?php echo $row->user_group_guid?>"
                          data-module_name="<?php echo $row->module_name?>" 
                          data-module_guid="<?php echo $row->module_guid?>" 
                          data-enable="<?php echo $row->isenable?>">
                          <i class="glyphicon glyphicon-pencil"></i></button> -->
                          <?php
                        }
                        ?>
                        
                        <?php
                        if(in_array('D1MS',$_SESSION['module_code']))
                        {
                          ?>  
                          <button title="Delete" onclick="confirm_modal('<?php echo site_url('module_setup/delete'); ?>?guid=<?php echo $row->user_module_guid ?>&table=set_user_module&col_guid=user_module_guid&delete=1')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="User Module" ><i class="glyphicon glyphicon-trash"></i></button>
                          <?php
                        }
                        ?>
                        
                      </td>
                        <td>
                        <input type="hidden" name="guid[]" value="<?php echo $row->user_module_guid?>">
                        <div ng-controller="usermoduleController">    
                          <input type="checkbox" ng-model="checkedvalue" 
                          <?php
                          if($row->isenable == '1')
                          {
                            ?>
                            ng-init="checkedvalue = true"/>
                            <?php
                          }
                          ?> 

                          <?php
                          if($row->isenable == '0')
                          {
                            ?>
                            <input type="checkbox" ng-model="checkedvalue" >
                            <?php
                          }
                          ?>
                                                
                          <input  style="display: none" value="{{getNumber()}}" name="active[]">
                        </div>
                        </td>
                        <td><?php echo $row->module_code?></td>
                        <td><?php echo $row->module_name?></td>
                        <td 
                        <?php 
                        if($_SESSION['user_group_guid'] == $row->user_group_guid)
                        {
                          ?>
                          id="highlight2" 
                          <?php
                        }
                        ?>
                        ><?php echo $row->user_group_name?></td>
                        <td id="highlight"><?php echo $row->module_group_name?></td>
                        </tr>
                    <?php
                  }
                  ?>
                  </tbody>
                </table>
              </div>
                <!-- /.tab-pane -->
            </form>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. @@@@@@@@@@@@@@@@@@@@@@@@@ User Module @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    </div>

    <div class="col-md-4">

      <!-- @@@@@@@@@@@@@@@@@@@ User Group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div id="demo" class="box box-default" >
        <div class="box-header with-border">
          <h3 class="box-title" >User Group</h3>

          <div class="box-tools pull-right">

          <?php
          if(in_array('U1MS',$_SESSION['module_code']) && $user_group->num_rows() > 0)
          {
            ?>
            <button class="btn btn-xs btn-success" onclick="$('#formUG').submit()"><i class="glyphicon glyphicon-floppy-saved"></i> Save</button>
            <?php
          }
          ?>
          
          <?php
          if(in_array('C1MS',$_SESSION['module_code']))
          {
            ?>
            <button class="btn btn-xs btn-primary" onclick="add_user_group()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
            <?php
          }
          ?>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="user_setup_details" >
        <div id="usergroupCheck">
        <form id="formUG" method="post" action="<?php echo site_url('module_setup/check')?>?table=set_user_group&col_guid=user_group_guid&col_check=isactive&edit=3">
          <table id="acc12" class="table table-bordered table-hover" >
                <thead>
                <tr>
                  <th>Action</th>
                  <th>Active</th>
                  <th>User Group Name</th>
                </tr>
                </thead>
                <tbody>
                
                <?php
                foreach($user_group->result() as $row)
                {
                  ?>
                   <tr>
                      <td>
                        <?php
                        if(isset($_SESSION['view_details']) == 1)
                        {
                          ?>  
                          <button  title="View" onclick="view()" type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#view" 
                          data-name ="<?php echo $row->user_group_name?>"
                          data-created_at ="<?php echo $row->created_at?>"
                          data-created_by="<?php echo $row->created_by?>" 
                          data-updated_at="<?php echo $row->updated_at?>"
                          data-updated_by="<?php echo $row->updated_by?>">
                          <i class="glyphicon glyphicon-eye-open"></i></button>
                          <?php
                        }
                        ?>

                        <?php
                        if(in_array('U1MS',$_SESSION['module_code']))
                        {
                          ?>
                          <button title="Edit" onclick="edit_user_group()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#usergroup" 
                          data-guid="<?php echo $row->user_group_guid?>"
                          data-name="<?php echo $row->user_group_name?>" 
                          data-active="<?php echo $row->isactive?>">
                          <i class="glyphicon glyphicon-pencil"></i></button>
                          <?php
                        }
                        ?>
                        
                        <?php
                        if(in_array('D1MS',$_SESSION['module_code']))
                        {
                          ?>  
                          <button title="Delete" onclick="confirm_modal('<?php echo site_url('module_setup/delete'); ?>?guid=<?php echo $row->user_group_guid ?>&table=set_user_group&col_guid=user_group_guid&delete=5')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->user_group_name?>" ><i class="glyphicon glyphicon-trash"></i></button>
                          <?php
                        }
                        ?>
                        
                      </td> 
                      <td>
                      
                        <input type="hidden" name="guid[]" value="<?php echo $row->user_group_guid?>">
                          <div ng-controller="usergroupController ">
                          <input type="checkbox" ng-model="checkedvalue" 
                          <?php
                          if($row->isactive == '1')
                          {
                            ?>
                            ng-init="checkedvalue = true"/>
                            <?php
                          }
                          ?> 

                          <?php
                          if($row->isactive == '0')
                          {
                            ?>
                            <input type="checkbox" ng-model="checkedvalue" >
                            <?php
                          }
                          ?>
                                                  
                          <input  style="display: none" value="{{getNumber()}}" name="active[]">
                          </div>
  
                      
                        <!-- <?php echo $row->isactive?> -->
                      </td>
                      <td>
                      <span
                      <?php 
                      if($_SESSION['user_group_guid'] == $row->user_group_guid)
                      {
                        ?>
                        id="highlight2" 
                        <?php
                      }
                      ?>
                      class="label label-default" style="font-size: 14px"><a href="<?php echo site_url('Module_setup')?>?module_group_guid=<?php echo $_SESSION['module_group_guid']?>&user_group_guid=<?php echo $row->user_group_guid?>"><?php echo $row->user_group_name?></a></span>
                      </td>
                      </tr>
                  <?php
                }
                ?>

                </tbody>
              </table><!-- <input type="submit" name="" value="submit"> -->
              </form>
              
            </div>        
            
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. end @@@@@@@@@@@@@@@@@ User Group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    </div>
  </div>

  <div class="row">
    <div class="col-md-8" >
            <!-- Module  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Module </h3>
          <div class="box-tools pull-right">
          <?php
          if(in_array('C1MS',$_SESSION['module_code']))
          {
            ?>
            <button class="btn btn-xs btn-primary" onclick="add_module()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
            <?php
          }
          ?>
          
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
          
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_master_setup_details">

              <table id="example10" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Action</th>
                  <th>Sequence</th>
                  <th>Module Code</th>
                  <th>Module Description</th>
                  <th>Module Group Name</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($call_module->result() as $row)
                {
                  ?>
                  <form>
                   <tr>
                      <td>
                      <?php
                      /* if(isset($_SESSION['view_details']) == 1)
                        { */
                          ?>  
                          <button  title="View" onclick="view()" type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#view" 
                          data-name ="<?php echo $row->module_name?>"
                          data-created_at ="<?php echo $row->created_at?>"
                          data-created_by="<?php echo $row->created_by?>" 
                          data-updated_at="<?php echo $row->updated_at?>"
                          data-updated_by="<?php echo $row->updated_by?>">
                          <i class="glyphicon glyphicon-eye-open"></i></button>
                          <?php
                      //  }
                        ?> 
                      <?php
                      if(in_array('U1MS',$_SESSION['module_code']))
                      {
                        ?>
                        <!-- <button title="Edit" onclick="edit_module()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#module" 
                        data-guid="<?php echo $row->module_guid?>"
                        data-name="<?php echo $row->module_name?>" 
                        data-seq="<?php echo $row->module_seq?>">
                        <i class="glyphicon glyphicon-pencil"></i></button> -->
                        <?php
                      }
                      ?>
                        
                      <?php
                      if(in_array('D1MS',$_SESSION['module_code']))
                      {
                        ?>  
                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('module_setup/delete'); ?>?guid=<?php echo $row->module_guid ?>&table=set_module&col_guid=module_guid&delete=3')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->module_name?>" ><i class="glyphicon glyphicon-trash"></i></button>
                        <?php
                      }
                      ?>

                      </td>
                      <td><?php echo $row->module_seq?></td>
                      <td><?php echo $row->module_code?></td>
                      <td><?php echo $row->module_name?></td>
                      <td id="highlight"><?php echo $row->module_group_name?></td>
                      </tr>
                    </form>
                  <?php
                }
                ?>
                </tbody>
              </table>
         
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /Module@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
    </div>
  </div>
  
</div>
</div>



<script>
  $(document).ready(function(){

    $(document).on('click', '#viewbranch', function(e){
       e.preventDefault();
      var uid = $(this).data('id');   // it will get id of clicked row
      
      // $('#dynamic-content').html(''); // leave it blank before ajax call
      $('#modal-loader').show();      // load ajax loader
     
      // $.ajax({
      //   url: '<?php echo base_url('module_setup/viewbranch')?>',
      //   // url: 'getuser.php',
      //   type: 'POST',
      //   data: {id: uid,system_admin: system_admin},
      //   dataType: 'html'
      // }) 
      // alert();
      $.ajax({
            url:"<?php echo base_url(); ?>index.php/module_setup/viewbranch",
            method:"POST",
            data:{user_guid: uid},
            success:function(data)
            { 

              json = JSON.parse(data);

              // alert(data);
              $('#modal-loader').hide(); 
              $('#dynamic-content').html(json['table']);
              $('#map_total').css({"margin-top":"0"}).html(json['map_total']+' of '+json['total']);

              // $('#user_branch_table').modal('toggle');
              // $('#user_branch_table').modal('show');
              // $('#user_branch_table').modal('hide');
              // alert('sdfsdfs');
                 // fetchUser();
                 // location.href="<?php echo base_url(); ?>index.php/main/adminenter";
            }
       })
    }); 

    $(document).on('click', '#xdelete_branch', function(e){
      var branch_guid = $(this).attr('branch_guid');   // it will get id of clicked row
      var user_guid = $(this).attr('user_guid');   // it will get id of clicked row
      var acc_guid = $(this).attr('acc_guid');   // it will get id of clicked row
      // alert(branch_guid);
      $('#mbranch_guid').val(branch_guid);
      $('#muser_guid').val(user_guid);
      $('#macc_guid').val(acc_guid);
    }); 

  });
</script>

<script>
    
    var usermoduleApp = angular.module('usermoduleApp', []);
    usermoduleApp.controller('usermoduleController', function($scope) {
       $scope.getText = function() { return $scope.checkedvalue ? "Yes" : "No"; };
        $scope.getNumber = function() { return $scope.checkedvalue ? 1 : 0; };
    });

    var usergroupApp = angular.module('usergroupApp', []);
    usergroupApp.controller('usergroupController', function($scope) {
      $scope.getText = function() { return $scope.checkedvalue ? "Yes" : "No"; };
        $scope.getNumber = function() { return $scope.checkedvalue ? 1 : 0; };
    });

    var userApp = angular.module('userApp', []);
    userApp.controller('userController', function($scope) {
      $scope.getText = function() { return $scope.checkedvalue ? "Yes" : "No"; };
        $scope.getNumber = function() { return $scope.checkedvalue ? 1 : 0; };
    });
    
    var usermodule = document.getElementById('usermoduleCheck');
    var usergroup = document.getElementById('usergroupCheck');
    var user = document.getElementById('userCheck');
    
    angular.element(document).ready(function() {
      angular.bootstrap(usermodule, ['usermoduleApp']);
      angular.bootstrap(usergroup, ['usergroupApp']);
      angular.bootstrap(user, ['userApp']);
   });

  </script>

<script type="text/javascript">

  function mouseOver() {
    document.getElementById("demo").style.color = "red";
}


  function edit_module_group()
  {
    $('#modulegroup').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)

      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="seq"]').val(button.data('seq'))
      modal.find('[name="module_group_name"]').text(button.data('name'))
      modal.find('[name="module_group_name"]').val(button.data('group_name'))
    });
  }
  
  function add_module_group()
  {
    save_method = 'add';
    $('#modulegroup').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New'); // Set Title to Bootstrap modal title
  }


  function edit_user_group()
  {
    $('#usergroup').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="active"]').checked = false
      modal.find('[name="group_name"]').val(button.data('name'))

    });
  }


  function add_user_group()
  {
    save_method = 'add';
    $('#usergroup').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New'); // Set Title to Bootstrap modal title
  }

  function edit_user_module()
  {
    $('#usermodule').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="user_group_name"]').text(button.data('user_group_name'))
      modal.find('[name="user_group_name"]').val(button.data('user_group_guid'))
      modal.find('[name="module_name"]').text(button.data('module_name'))
      modal.find('[name="module_name"]').val(button.data('module_guid'))

    });
  }
  
  function add_user_module()
  {
    save_method = 'add';
    $('#usermodule').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New'); // Set Title to Bootstrap modal title
  }


  function edit_user()
  {
    $('#user').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)  
      var modal = $(this)
      modal.find('.modal-title').text('Edit2')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="userid"]').val(button.data('id'))
      modal.find('[name="name"]').val(button.data('name'))
      modal.find('[name="password"]').val(button.data('password'))
      modal.find('[name="user_group_name"]').text(button.data('user_group'))
      modal.find('[name="user_group_name"]').val(button.data('user_group_guid')) 
      modal.find('[name="active"]').val(button.data('isactive'))
      modal.find('[name="limited_location"]').val(button.data('limited_location'))
    });
  }
  
  function add_user()
  {
    save_method = 'add';
    $('#user').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New'); // Set Title to Bootstrap modal title
  }

  function edit_module()
  {
    $('#module').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="seq"]').val(button.data('seq'))
      modal.find('[name="name"]').val(button.data('name'))
      modal.find('[name="module_name"]').text(button.data('name'))
      modal.find('[name="module_name"]').val(button.data('name'))

    });
  }
  
  function add_module()
  {
    save_method = 'add';
    $('#module').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New'); // Set Title to Bootstrap modal title
  }


  function view()
  {
    $('#view').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text(button.data('name'))
      modal.find('[id="created_at"]').text(button.data('created_at'))
      modal.find('[id="created_by"]').text(button.data('created_by'))
      modal.find('[id="updated_at"]').text(button.data('updated_at'))
      modal.find('[id="updated_by"]').text(button.data('updated_by'))

    });
  }

  function assign_branch()
  {
    $('#branch').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('[name="guid"]').val(button.data('guid'))

      modal.find('[name="acc_guid"]').val(button.data('acc_guid'))
      modal.find('[name="module_group_guid"]').val(button.data('module_group_guid'))
      modal.find('[name="user_group_guid"]').val(button.data('user_group_guid'))
      modal.find('[name="isactive"]').val(button.data('isactive'))
      modal.find('[name="user_id"]').val(button.data('user_id'))
      modal.find('[name="user_name"]').val(button.data('user_name'))
      modal.find('[name="user_password"]').val(button.data('user_password'))
      modal.find('[name="created_at"]').val(button.data('created_at'))
      modal.find('[name="created_by"]').val(button.data('created_by'))

      modal.find('[id="user_id"]').text(button.data('user_id'))
      modal.find('[id="user_group"]').text(button.data('user_group'))
      modal.find('[id="module_group"]').text(button.data('module_group'))

      modal.find('[name="limited_location"]').val(button.data('limited_location'))
      
      
    });

  }

  function assign_branch2(id)
  {
      //Ajax Load data from ajax
      $.ajax({
          url : "<?php echo site_url('module_setup/ajax_edit/')?>/" + id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
              $('[name="id"]').val(data.id);
              $('[name="firstName"]').val(data.firstName);
              $('[name="lastName"]').val(data.lastName);
              $('[name="gender"]').val(data.gender);
              $('[name="address"]').val(data.address);
              $('[name="dob"]').datepicker('update',data.dob);
              $('#please').modal('show'); // show bootstrap modal when complete loaded
              $('.modal-title').text('Edit Person'); // Set title to Bootstrap modal title

          },
          error: function (jqXHR, textStatus, errorThrown)
          {
              alert('Error get data from ajax');
          }
      });
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


   function confirm_update(update_url)
  {
    $('#UpdateAllOutlet').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm Update ALL user with Latest Outlet Created ?')
    document.getElementById('url2').setAttribute("href" , update_url );
    });
  }
 
 
    
</script>

