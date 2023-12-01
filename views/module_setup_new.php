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
  .select2-container--default .select2-selection--multiple .select2-selection__rendered {
    overflow-x: auto;
    overflow-y: scroll;
    height: 250px;
    width: 100%;
    cursor: pointer;
  }

  .select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar {
    width: 10px;
    height: 5px;
    background-color: #F5F5F5;           /* width of the entire scrollbar */
  }

  .select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    border-radius: 10px;
    background-color: #F5F5F5;       /* color of the tracking area */
  }

  .select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #B7BABF; /* color of the scrolling */
  }

  .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: black;
  }

  /* Remove horizontal scrolling from the DataTable */
  #module_setup_user_module_table_wrapper {
    overflow-x: hidden;
  }
    
  #module_setup_user_table_wrapper {
    overflow-x: hidden;
  }
    
  #example10_wrapper {
    overflow-x: hidden;
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
           <button title="Update Outlet" onclick="confirm_update('<?php echo site_url('module_setup_new/UpdateAllOutlet') ?>?module_group_guid=<?php echo $_SESSION['module_group_guid'] ?>&customer_guid=<?php echo $_SESSION['customer_guid'] ?>')" 
              type="button" 
              class="btn btn-xs btn-warning" 
              data-toggle="modal" 
              data-target="#UpdateAllOutlet" 
              data-name="<?php echo 'module_group_guid'; ?>" ><i class="glyphicon glyphicon-map-marker"></i>Update Location for All User</button>

              <button title="Update SUPERUSER" onclick="confirm_update('<?php echo site_url('module_setup_new/update_all_cus_all_outlet') ?>?module_group_guid=<?php echo $_SESSION['module_group_guid']?>')" 
              type="button" 
              class="btn btn-xs btn-danger" 
              data-toggle="modal" 
              data-target="#UpdateAllOutlet" 
              data-name="<?php echo 'module_group_guid'; ?>" ><i class="fa fa-retweet" aria-hidden="true"></i>Sync Outlet to Superuser</button>  

              <a href="<?php echo site_url('module_setup_new/duplicate_user') ?>?from=<?php echo $_SESSION['customer_guid'] ?>&to=&filter=false" 
                class="btn btn-xs btn-info" 
                ><i class="fa fa-files-o" aria-hidden="true"></i>Duplicate User</a>

              <?php
              if(in_array('DBU',$_SESSION['module_code'])&& $call_user->num_rows() > 0)
              {
              ?>
                <a href="<?php echo site_url('module_setup_new/duplicate_by_user') ?>?from=<?php echo $_SESSION['customer_guid'] ?>&to=&filter=false" 
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
          <form id="formUser" method="post" action="<?php echo site_url('module_setup_new/check')?>?table=set_user&col_guid=user_guid&col_check=isactive&edit=2">
          <table id="module_setup_user_table" class="table table-bordered table-hover" >
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

              <table id="module_setup_module_group_table" class="table table-bordered table-hover">
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
                      class="label label-default" style="font-size: 14px"><a href="<?php echo site_url('Module_setup_new')?>?module_group_guid=<?php echo $row->module_group_guid?>"><?php echo $row->module_group_name?></a></span>
                      
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
            <form id="formUM" method="post" action="<?php echo site_url('module_setup_new/check')?>?table=set_user_module&col_guid=user_module_guid&col_check=isenable&edit=1">
                <div class="tab-pane active" id="view1">
                 <table id="module_setup_user_module_table" class="table table-bordered table-hover">
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
          if(in_array('IAVA',$_SESSION['module_code']))
          {
            ?>
            <button class="btn btn-xs btn-warning" id="user_group_dp"><i class="glyphicon glyphicon-copy"></i> Duplicate</button>
            <?php
          }
          ?>

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
        <form id="formUG" method="post" action="<?php echo site_url('module_setup_new/check')?>?table=set_user_group&col_guid=user_group_guid&col_check=isactive&edit=3">
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
                          data-active="<?php echo $row->isactive?>"
                          data-admin_active="<?php echo $row->admin_active?>"
                          data-group_info_status="<?php echo $row->group_info_status?>">
                          <i class="glyphicon glyphicon-pencil"></i></button>
                          <?php
                        }
                        ?>
                        
                        <?php
                        if(in_array('D1MS',$_SESSION['module_code']))
                        {
                          ?>  
                          <button title="Delete" onclick="confirm_modal('<?php echo site_url('module_setup_new/delete'); ?>?guid=<?php echo $row->user_group_guid ?>&table=set_user_group&col_guid=user_group_guid&delete=5')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->user_group_name?>" ><i class="glyphicon glyphicon-trash"></i></button>
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
                      class="label label-default" style="font-size: 14px"><a href="<?php echo site_url('Module_setup_new')?>?module_group_guid=<?php echo $_SESSION['module_group_guid']?>&user_group_guid=<?php echo $row->user_group_guid?>"><?php echo $row->user_group_name?></a></span>
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
                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('module_setup_new/delete'); ?>?guid=<?php echo $row->module_guid ?>&table=set_module&col_guid=module_guid&delete=3')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->module_name?>" ><i class="glyphicon glyphicon-trash"></i></button>
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
      //   url: '<?php echo base_url('module_setup_new/viewbranch')?>',
      //   // url: 'getuser.php',
      //   type: 'POST',
      //   data: {id: uid,system_admin: system_admin},
      //   dataType: 'html'
      // }) 
      // alert();
      $.ajax({
            url:"<?php echo base_url(); ?>index.php/module_setup_new/viewbranch",
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

    $(document).on('click', '#user_group_dp', function(e){
      var modal = $("#medium-modal").modal();

      modal.find('.modal-title').html('Edit Document Status');

      methodd = '';

      methodd += '<div class="col-md-12"><label>User Group Name (Duplicate From) </label> <select class="form-control select2" name="dp_group_from" id="dp_group_from"> <option value="" disabled selected>-SELECTION-</option> <?php foreach($user_group->result() as $row) { ?> <option value="<?php echo $row->user_group_guid?>"><?php echo $row->user_group_name?></option> <?php } ?> </select> </div>';

      methodd += '<div class="col-md-12"><label>User Group Name (Duplicate To) </label> <select class="form-control select2" name="dp_group_to" id="dp_group_to"> <option value="" disabled selected>-SELECTION-</option> <?php foreach($user_group->result() as $row) { ?> <option value="<?php echo $row->user_group_guid?>"><?php echo $row->user_group_name?></option> <?php } ?> </select> </div>';

      methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="user_group_dp_btn" class="btn btn-primary"> Submit </button></span></p>';

      modal.find('.modal-footer').html(methodd_footer);
      modal.find('.modal-body').html(methodd);

      setTimeout(function(){
        $('.select2').select2();
      },300);
    }); 
    
    $(document).on('click','#user_group_dp_btn',function(){
      var dp_group_from = $('#dp_group_from').val();
      var dp_group_to = $('#dp_group_to').val();
      
      if((dp_group_from == '') || (dp_group_from == null) || (dp_group_from == 'null'))
      {
        alert('Please Select data.');
        return
      }

      if((dp_group_to == '') || (dp_group_to == null) || (dp_group_to == 'null'))
      {
        alert('Please Select data.');
        return
      }

      if(dp_group_from == dp_group_to)
      {
        alert('User group cannot same.');
        return
      }

      confirmation_modal('Are you sure want to Duplicate User Group Settings?');
      $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
              url:"<?php echo site_url('Module_setup_new/user_group_duplicate');?>",
              method:"POST",
              data:{dp_group_from:dp_group_from,dp_group_to:dp_group_to},
              beforeSend:function(){
                $('.btn').button('loading');
              },
              success:function(data)
              {
                json = JSON.parse(data);
                if (json.para1 == 'false') {
                  alert(json.msg.replace(/\\n/g,"\n"));
                  $('.btn').button('reset');
                }else{
                  alert(json.msg.replace(/\\n/g,"\n"));
                  // setTimeout(function() {
                  $('.btn').button('reset');
                  location.reload();
                  // }, 300);
                }//close else
              }//close success
        });//close ajax
      });//close document yes click
    });//close edit

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
      modal.find('[name="admin_active"]').val(button.data('admin_active'))
      modal.find('[name="group_info_status"]').val(button.data('group_info_status'))

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
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="userid"]').val(button.data('id'))
      modal.find('[name="name"]').val(button.data('name'))
      modal.find('[name="password"]').val(button.data('password'))
      // modal.find('[name="user_group_name"]').text(button.data('user_group'))
      // modal.find('[name="user_group_name"]').val(button.data('user_group_guid')) 
      modal.find('[name="user_group"]').val(button.data('user_group_guid')).trigger('change');
      modal.find('[name="active"]').val(button.data('isactive'))
      modal.find('[name="limited_location"]').val(button.data('limited_location'))
      modal.find('[name="hide_admin"]').val(button.data('hide_admin'))
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
          url : "<?php echo site_url('module_setup_new/ajax_edit/')?>/" + id,
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
 
 

$(document).ready(function(){

module_setup_user_table = function()
{
  if ( $.fn.DataTable.isDataTable('#module_setup_user_table') ) {
    $('#module_setup_user_table').DataTable().destroy();
  }
  
  var table;

  table = $('#module_setup_user_table').DataTable({
    "columnDefs": [ 
                  // {"targets": 3 ,"visible": false},
                  {"targets": [0,1,2] ,"orderable": false}
                  ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [2 , 'asc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true,
    stateSave: true,
    // "sScrollY": "15vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('module_setup_new/module_setup_user_table');?>",
        "type": "POST",
        complete:function()
        {
        },
    },
    //'fixedHeader' : false,
    columns: [
              {"data":"user_guid", render:function( data, type, row ){
                var element = '';

                <?php
                if(isset($_SESSION['view_details']) == 1)
                {
                ?>

                  element += '<button  title="View" onclick="view()" type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#view" data-name ="'+row['user_name']+'" data-created_at ="'+row['created_at']+'" data-created_by="'+row['created_by']+'" data-updated_at="'+row['updated_at']+'" data-updated_by="'+row['updated_by']+'"> <i class="glyphicon glyphicon-eye-open"></i></button>';

                <?php
                }
                ?>


                <?php
                if(in_array('U1MS',$_SESSION['module_code']))
                {
                ?>
                
                  element += '<button title="Edit" onclick="edit_user()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#user" data-query="SELECT * FROM set_user where user_guid = \''+row['user_guid']+'\' " data-guid="'+row['user_guid']+'" data-name="'+row['user_name']+'" data-id="'+row['user_id']+'" data-password="'+row['user_password']+'" data-isactive="'+row['isactive']+'" data-user_group="'+row['user_group_name']+'" data-limited_location="'+row['limited_location']+'" data-user_group_guid="'+row['user_group_guid']+'" data-hide_admin="'+row['hide_admin']+'"> <i class="glyphicon glyphicon-pencil"></i></button>';

                  element += '<button type="button" id="email_reset" title="Email" class="btn btn-xs btn-warning" user_guid="'+row['user_guid']+'" supplier_guid="'+row['supplier_guid']+'" user_password="'+row['user_password']+'" user_id="'+row['user_id']+'" acc_guid="'+row['acc_guid']+'" user_name="'+row['user_name']+'"><i class="fa fa-send"></i></button>';

                <?php
                }
                ?>

                <?php
                if(in_array('D1MS',$_SESSION['module_code']))
                {
                ?>

                  // element += '<button title="Delete" onclick="confirm_modal(\'<?php echo site_url('module_setup/delete'); ?>?guid='+row['user_guid']+'&table=set_user&col_guid=user_guid&delete=2\')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="'+row['user_name']+'" ><i class="glyphicon glyphicon-trash"></i></button>';
                  element += '';

                <?php
                }
                ?>
                

                return element;

              }},
              {"data":"user_guid", render: function( data, type, row ){
                var element = '';

                element += '<button title="Assign" onclick="assign_branch()" type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#branch" data-guid="'+row['user_guid']+'" data-acc_guid="'+row['acc_guid']+'" data-module_group_guid="'+row['module_group_guid']+'" data-user_group_guid="'+row['user_group_guid']+'" data-isactive="'+row['isactive']+'" data-user_id="'+row['user_id']+'" data-user_name="'+row['user_name']+'" data-user_password="'+row['user_password']+'" data-created_at="'+row['created_at']+'" data-created_by="'+row['created_by']+'" data-user_group="'+row['user_group_name']+'" data-limited_location="'+row['limited_location']+'" data-module_group="'+row['module_group_name']+'" ><i class="glyphicon glyphicon-edit"></i></button>';

                element += '<button data-toggle="modal" data-target="#view-modal" data-id="'+row['user_guid']+'" data-customer_guid ="<?php echo $_SESSION['customer_guid'] ?>" id="viewbranch" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-eye-open"></i></button>';

                return element;

              }},
              {"data":"user_guid", render:function( data, type, row ){
                var element = '';

                element += '<input type="hidden" name="guid[]" value="'+row['user_guid']+'">';

                if(row['isactive'] == '1')
                {
                  ischecked = 'checked';
                }
                else
                {
                  ischecked = '';
                }

                element += '<input type="checkbox" disabled name="cb_active" '+ischecked+' >';
                

                return element;
              }},
              {"data":"user_id"},
              {"data":"user_name"},
              {"data":"user_group_name"},
              {"data":"module_group_name"},
             ],


    dom:'<"row"<"col-sm-4" l><"col-sm-8" f>>Brtip',


    // "pagingType": "simple_numbers",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {

      // if(aData['suspended'] == '1')
      // {   
      //     $(nRow).closest('tr').attr("id","highlight3");  
      // }

      // $(nRow).attr('RefNo', aData['RefNo']);

    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

}//close module_setup_user_table

module_setup_user_table();

$('#module_setup_user_module_table').DataTable({
  columnDefs: [
    // Disable sorting for the "Action" column
    {"targets": [0], "orderable": false},
    // Disable sorting for the "Enable" column
    {"targets": [1], "orderable": false}
  ],
  searching: true,
  paging: true,
  order: [], // Disable initial sorting for all columns
});

$('#acc12').DataTable({
  columnDefs: [
    // Disable sorting for the "Action" column
    {"targets": [0], "orderable": false},
    // Disable sorting for the "Active" column
    {"targets": [1], "orderable": false}
  ],
  searching: true,
  paging: true,
  order: [], // Disable initial sorting for all columns
  dom:'<"row"<"col-sm-4" l><"col-sm-8" f>>rtip',
});

$('#example10').DataTable({
  columnDefs: [
    // Disable sorting for the "Action" column
    {"targets": [0], "orderable": false},
  ],
  searching: true,
  paging: true,
  order: [] // Disable initial sorting for all columns
});

$(document).on('click','#email_reset',function(){
  
  var user_guid = $(this).attr('user_guid');
  var user_password = $(this).attr('user_password');
  var user_id = $(this).attr('user_id');
  var acc_guid = $(this).attr('acc_guid');
  var user_name = $(this).attr('user_name');

  var modal = $("#medium-modal").modal();

  modal.find('.modal-title').html('Edit Reset Status');

  methodd = '';

  methodd +='<div class="col-md-12">';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="user_guid" value="'+user_guid+'"/></div>';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="acc_guid" value="'+acc_guid+'"/></div>';

  methodd += '<div class="col-md-6"><label>User ID</label><input type="input" class="form-control input-sm" id="user_id" readonly value="'+user_id+'"/></div>';

  methodd += '<div class="col-md-6"><label>User Name</label><input type="input" class="form-control input-sm" id="user_name" readonly value="'+user_name+'"/></div>';

  methodd += '<div class="col-md-6"><label>User password</label><input type="password" class="form-control input-sm" id="password" autocomplete="off"/></div>';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="user_password" value="'+user_password+'"/></div>';

  methodd += '<div class="col-md-6"><label>Remark</label><select id="reset_remark" name="reset_remark" class="form-control" ><option value="new">New</option> <option value="duplicate">Duplicate</option></select></div>';

  methodd += '<div class="col-md-6"><label>Supplier Name</label> <select class="select2 form-control" id="choose_supplier" name="choose_supplier"></select> </div>';

  methodd += '</div>';

  methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="check_pass" class="btn btn-success" value="Check"> <input type="button" id="send_status" class="btn btn-warning" disabled value="Send"> <input name="sendsubmit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

  modal.find('.modal-footer').html(methodd_footer);
  modal.find('.modal-body').html(methodd);

  setTimeout(function(){
    $('#user_guid').val(user_guid);
    $('#supplier_guid').val(supplier_guid);
    $('#user_password').val(user_password);
    $('#user_id').val(user_id);
    $('#user_name').val(user_name);
    $('#acc_guid').val(acc_guid);

    $(document).on('change','#reset_remark',function(){

      var remark = $('#reset_remark').val();
      if(remark == 'duplicate')
      {
        $('#send_status').removeAttr('disabled');
        $('#check_pass').prop('disabled',true);
      }
      else
      {
        $('#send_status').prop('disabled',true);
        $('#check_pass').removeAttr('disabled');
      }
      
    });//close reset_input

    if(user_guid != '')
    {
      $.ajax({
          url : "<?php echo site_url('Module_setup_new/fetch_supplier_data'); ?>",
          method:"POST",
          data:{user_guid:user_guid},
          success:function(result)
          {
            json = JSON.parse(result); 
              vendor = '';
              vendor += '<option value="" selected>-SUPPLIER NAME-</option>';
              Object.keys(json['vendor']).forEach(function(key) {

                vendor += '<option value="'+json['vendor'][key]['supplier_guid']+'" >'+json['vendor'][key]['supplier_name']+'</option>';
              });

            $('#choose_supplier').select2().html(vendor);
           
          }
      });
    }
    else
    {
      $('#edit_acc_no').select2().html('<option value="" disabled>Please select the supplier</option>');
    }
          
  },300);

});//close add  

$(document).on('click','#check_pass',function(){

  var user_password = $('#user_password').val();
  var password = $('#password').val();

  $.ajax({
      url:"<?php echo site_url('module_setup_new/check_md5');?>",
      method:"POST",
      data:{user_password:user_password,password:password},
      beforeSend:function(){
        // $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        if (json.para1 == '1') {
          alert(json.msg);
          // $('.btn').button('reset');
        }else{
          alert(json.msg);
          $('#send_status').removeAttr('disabled');
        }//close else
      }//close success
  });//close ajax
});//close add 

$(document).on('click','#send_status',function(){

  var user_guid = $('#user_guid').val();
  var user_id = $('#user_id').val();
  var acc_guid = $('#acc_guid').val();
  var reset_remark = $('#reset_remark').val();
  var choose_supplier = $('#choose_supplier').val();
  //alert(choose_supplier); die;
  $.ajax({
      url:"<?php echo site_url('module_setup_new/resend_status');?>",
      method:"POST",
      data:{user_guid:user_guid,user_id:user_id,acc_guid:acc_guid,reset_remark:reset_remark,choose_supplier:choose_supplier},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        if (json.para1 == '1') {
          alert(json.msg);
          $('.btn').button('reset');
        }else{
          $("#medium-modal").modal('hide');
          alert(json.msg);
          $('.btn').button('reset');
          //location.reload();
        }//close else
      }//close success
    });//close ajax
});//close add 

module_setup_user_module_table

});//close document ready
</script>

