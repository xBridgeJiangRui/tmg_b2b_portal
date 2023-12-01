<!-- Content Wrapper. Contains page content -->
<style type="text/css">

  .content-wrapper{
    min-height: 1000px !important; 
  }

  #acc_master_setup_details{
    height: 250px;
    overflow-y: scroll;

  }
  #user_setup_details{
    height: 250px;
    overflow-y: scroll;

  }

  #exampleeee2_wrapper {
  overflow-x: hidden;
  }

  #exampleeeee2_wrapper {
  overflow-x: hidden;
  }
</style>

<div class="content-wrapper" >
<div class="container-fluid">
<br>
 <?php
  if($this->session->userdata('message'))
  {
     echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; 
  }
  ?>
<?php // echo var_dump($_SESSION) ?>
  <div class="row" >
    
    <div class="col-md-6">

      <!-- Module  Group@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Account Module Group</h3>
          <div class="box-tools pull-right">
          
            <button class="btn btn-xs btn-primary" onclick="add_module_group()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
           
           <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>  
            
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_master_setup_details">

              <table id="exampleeee1" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Action</th>
                  <th>Sequence</th>
                  <th>Module Group Name</th>
                  <!-- <th>Created at</th>
                  <th>Created by</th>
                  <th>Updated at</th>
                  <th>Updated by</th> -->
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($account_module_group->result() as $row)
                {
                  ?>
                   <tr>
                      <td>
                        <button title="Edit" onclick="edit_module_group()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modulegroup" 
                        data-guid="<?php echo $row->acc_module_group_guid?>"
                        data-name="<?php echo $row->acc_module_group_name?>" 
                        data-seq="<?php echo $row->acc_module_group_seq?>">
                        <i class="glyphicon glyphicon-pencil"></i></button>

                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_master_setup/delete'); ?>?guid=<?php echo $row->acc_module_group_guid ?>&table=acc_module_group&col_guid=acc_module_group_guid')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->acc_module_group_name?>" ><i class="glyphicon glyphicon-trash"></i></button>
                      </td>
                      <td><?php echo $row->acc_module_group_seq?></td>
                      <td>
                      <span
                      <?php 
                      if($_SESSION['acc_module_group_guid'] == $row->acc_module_group_guid)
                      {
                        ?>
                        id="highlight" 
                        <?php
                      }
                      ?>
                      class="label label-default" style="font-size: 14px"><a href="<?php echo site_url('acc_master_setup')?>?acc_module_group_guid=<?php echo $row->acc_module_group_guid?>"><?php echo $row->acc_module_group_name?></a></span>
                      </td>
                      <!-- <td><?php echo $row->created_at?></td>
                      <td><?php echo $row->created_by?></td>
                      <td><?php echo $row->updated_at?></td>
                      <td><?php echo $row->updated_by?></td> -->
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
    <div class="col-md-6">
            <!-- Module  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Account Module </h3>
          <div class="box-tools pull-right">
          <button class="btn btn-xs btn-success" onclick="$('#formAM').submit()"><i class="glyphicon glyphicon-floppy-saved"></i> Save</button>
          <button class="btn btn-xs btn-primary" onclick="add_module()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_master_setup_details">
          <div id="accmoduleCheck">
          <form id="formAM" method="post" action="<?php echo site_url('acc_master_setup/check')?>?table=acc_module&col_guid=acc_module_guid&col_check=isenable">
                <table id="exampleeee2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Action</th>
                    <th>Enable</th>
                    <th>Sequence</th>
                    <th>Module Code</th>
                    <th>Module Description</th>
                    <th>Module Group Name</th>
                    <!-- <th>Created at</th>
                    <th>Created by</th>
                    <th>Updated at</th>
                    <th>Updated by</th> -->
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  foreach($account_module->result() as $row)
                  {
                    ?>
                     <tr>
                        <td>
                        <button title="Edit" onclick="edit_module()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#module" 
                        data-guid="<?php echo $row->acc_module_guid?>"
                        data-group_guid="<?php echo $row->acc_module_group_guid?>"
                        data-group_name="<?php echo $row->acc_module_group_name?>"
                        data-name="<?php echo $row->acc_module_name?>" 
                        data-seq="<?php echo $row->acc_module_seq?>"
                        data-code="<?php echo $row->acc_module_code?>">
                        <i class="glyphicon glyphicon-pencil"></i></button>

                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_master_setup/delete'); ?>?guid=<?php echo $row->acc_module_guid ?>&table=acc_module&col_guid=acc_module_guid')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->acc_module_name?>" ><i class="glyphicon glyphicon-trash"></i></button>
                      </td>
                        <td>
                        <input type="hidden" name="guid[]" value="<?php echo $row->acc_module_guid?>">
                          <div ng-controller="accmoduleController ">
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
                        <td><?php echo $row->acc_module_seq?></td>
                        <td><?php echo $row->acc_module_code?></td>
                        <td><?php echo $row->acc_module_name?></td>
                        <td><?php echo $row->acc_module_group_name?></td>
                        <!-- <td><?php echo $row->created_at?></td>
                        <td><?php echo $row->created_by?></td>
                        <td><?php echo $row->updated_at?></td>
                        <td><?php echo $row->updated_by?></td> -->
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
      <!-- /Module@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    </div>
  </div>

<div class="row">
    <div class="col-md-12">

      <!-- User@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Account User</h3>

          <div class="box-tools pull-right">
          <button class="btn btn-xs btn-primary" onclick="add_user()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="user_setup_details">
          <table id="exampleeee11" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Action</th>
                  <th>User ID</th>
                  <th>User Password</th>
                  <th>User Name</th>
                  <th>User Group</th>
                  <!-- <th>Created at</th>
                  <th>Created by</th>
                  <th>Updated at</th>
                  <th>Updated by</th> -->
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($account_user->result() as $row)
                {
                  ?>
                   <tr>
                      <td>
                        <button title="Edit" onclick="edit_user()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#user" 
                        data-guid="<?php echo $row->acc_user_guid?>"
                        data-name="<?php echo $row->acc_user_name?>" 
                        data-id="<?php echo $row->acc_user_id?>"
                        data-password="<?php echo $row->acc_user_password?>"
                        data-user_group="<?php echo $row->user_group_name?>"
                        data-user_group_guid="<?php echo $row->acc_user_group_guid?>" >
                        <i class="glyphicon glyphicon-pencil"></i></button>

                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_master_setup/delete'); ?>?guid=<?php echo $row->acc_user_guid ?>&table=acc_user&col_guid=acc_user_guid')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->acc_user_name?>" ><i class="glyphicon glyphicon-trash"></i></button>
                      </td>
                      <td><?php echo $row->acc_user_id?></td>
                      <td><input readonly type="password" value="<?php echo $row->acc_user_password?>"></td>
                      <td><?php echo $row->acc_user_name?></td>
                      <td><?php echo $row->user_group_name?></td>
                      <!-- <td><?php echo $row->created_at?></td>
                      <td><?php echo $row->created_by?></td>
                      <td><?php echo $row->updated_at?></td>
                      <td><?php echo $row->updated_by?></td> -->
                      </tr>
                  <?php
                }
                ?>
                </tbody>
              </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. User@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    </div>
  </div>

<div class="row">
    <div class="col-md-6">

      <!--   @@@@@@@@@@@@@@@@@@@@@@@ User Module @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Account User Module</h3>

          <div class="box-tools pull-right">

          <button class="btn btn-xs btn-success" onclick="$('#formUM').submit()"><i class="glyphicon glyphicon-floppy-saved"></i> Save</button>
          
          <button class="btn btn-xs btn-primary" onclick="add_user_module()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="user_setup_details">
        <div id="usermoduleCheck">
          <form id="formUM" method="post" action="<?php echo site_url('acc_master_setup/check')?>?table=acc_user_module&col_guid=acc_user_module_guid&col_check=isenable">
                <div class="tab-pane active" id="view1">
                 <table id="exampleeeee2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Action</th>
                    <th>Enable</th>
                    <th>Module Name</th>
                    <th>User Group</th>
                    <!-- <th>Created at</th>
                    <th>Created by</th>
                    <th>Updated at</th>
                    <th>Updated by</th> -->
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  foreach($account_user_module->result() as $row)
                  {
                    ?>
                     <tr>
                        <td>
                        <button title="Edit" onclick="edit_user_module()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#usermodule" 
                        data-guid="<?php echo $row->acc_user_module_guid?>"
                        data-user_group_name="<?php echo $row->user_group_name?>"
                        data-user_group_guid="<?php echo $row->acc_user_group_guid?>"
                        data-module_name="<?php echo $row->acc_module_name?>" 
                        data-module_guid="<?php echo $row->acc_module_guid?>" 
                        data-enable="<?php echo $row->isenable?>">
                        <i class="glyphicon glyphicon-pencil"></i></button>

                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_master_setup/delete'); ?>?guid=<?php echo $row->acc_user_module_guid ?>&table=acc_user_module&col_guid=acc_user_module_guid')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="User Module" ><i class="glyphicon glyphicon-trash"></i></button>
                      </td>
                        <td>
                        <input type="hidden" name="guid[]" value="<?php echo $row->acc_user_module_guid?>">
                          <div ng-controller="usermoduleController ">
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
                        <td><?php echo $row->acc_module_name?></td>
                        <td><?php echo $row->user_group_name?></td>
                        <!-- <td><?php echo $row->created_at?></td>
                        <td><?php echo $row->created_by?></td>
                        <td><?php echo $row->updated_at?></td>
                        <td><?php echo $row->updated_by?></td> -->
                        </tr>
                    <?php
                  }
                  ?>
                  </tbody>
                </table>
              </div>
            </form>
                <!-- /.tab-pane -->
         </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. @@@@@@@@@@@@@@@@@@@@@@@@@ User Module @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    </div>
  
    <div class="col-md-6">

      <!-- @@@@@@@@@@@@@@@@@@@ User Group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Account User Group</h3>

          <div class="box-tools pull-right">

          <button class="btn btn-xs btn-success" onclick="$('#formUG').submit()"><i class="glyphicon glyphicon-floppy-saved"></i> Save</button>

          <button class="btn btn-xs btn-primary" onclick="add_user_group()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="user_setup_details">
        <div id="usergroupCheck">
        <form id="formUG" method="post" action="<?php echo site_url('acc_master_setup/check')?>?table=acc_user_group&col_guid=acc_user_group_guid&col_check=isactive">
          <table id="exampleeee2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Action</th>
                  <th>Active</th>
                  <th>User Group Name</th>
                  <!-- <th>Created at</th>
                  <th>Created by</th>
                  <th>Updated at</th>
                  <th>Updated by</th> -->
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($account_user_group->result() as $row)
                {
                  ?>
                   <tr>
                      <td>
                        <button title="Edit" onclick="edit_user_group()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#usergroup" 
                        data-guid="<?php echo $row->acc_user_group_guid?>"
                        data-name="<?php echo $row->user_group_name?>" 
                        data-active="<?php echo $row->isactive?>">
                        <i class="glyphicon glyphicon-pencil"></i></button>

                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_master_setup/delete'); ?>?guid=<?php echo $row->acc_user_group_guid ?>&table=acc_user_group&col_guid=acc_user_group_guid')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" 
                        data-name="<?php echo $row->user_group_name?>"
                        data-alert="This action will delete all the data belong this user group !" ><i class="glyphicon glyphicon-trash"></i></button>
                      </td>
                      <td>

                      <input type="hidden" name="guid[]" value="<?php echo $row->acc_user_group_guid?>">
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
                        
                      </td>
                      <td><?php echo $row->user_group_name?></td>
                      <!-- <td><?php echo $row->created_at?></td>
                      <td><?php echo $row->created_by?></td>
                      <td><?php echo $row->updated_at?></td>
                      <td><?php echo $row->updated_by?></td> -->
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
      <!-- /. end @@@@@@@@@@@@@@@@@ User Group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    </div>
  </div>
                                        

</div>
</div>

<script>
    
    $(document).ready(function() {
        $('#exampleeee2').DataTable({
          columnDefs: [
            // Disable sorting for the "Action" column
            {"targets": [0], "orderable": false},
          ],
          searching: true,
          paging: true,
          order: [], // Disable initial sorting for all columns
          dom:'<"row"<"col-sm-4" l><"col-sm-8" f>>Brtip'
        });

        $('#exampleeeee2').DataTable({
          columnDefs: [
            // Disable sorting for the "Action" column
            {"targets": [0], "orderable": false},
            // Disable sorting for the "Enable" column
            {"targets": [1], "orderable": false}
          ],
          searching: true,
          paging: true,
          order: [] // Disable initial sorting for all columns
        });

    });

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

    var accmoduleApp = angular.module('accmoduleApp', []);
    accmoduleApp.controller('accmoduleController', function($scope) {
      $scope.getText = function() { return $scope.checkedvalue ? "Yes" : "No"; };
        $scope.getNumber = function() { return $scope.checkedvalue ? 1 : 0; };
    });
    
    var usermodule = document.getElementById('usermoduleCheck');
    var usergroup = document.getElementById('usergroupCheck');
    var accmodule = document.getElementById('accmoduleCheck');
    
    angular.element(document).ready(function() {
      angular.bootstrap(usermodule, ['usermoduleApp']);
      angular.bootstrap(usergroup, ['usergroupApp']);
      angular.bootstrap(accmodule, ['accmoduleApp']);
   });

  </script>

<script type="text/javascript">

  function edit_module_group()
  {
    $('#modulegroup').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="seq"]').val(button.data('seq'))
      modal.find('[name="name"]').val(button.data('name'))

    });
  }
  
  function add_module_group()
  {
    save_method = 'add';
    $('#modulegroup').modal('show'); // show bootstrap modal
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
      modal.find('[name="code"]').val(button.data('code'))
      modal.find('[name="name"]').val(button.data('name'))
      modal.find('[name="module_group_name"]').text(button.data('group_name'))
      modal.find('[name="module_group_name"]').val(button.data('group_guid'))

    });
  }
  
  function add_module()
  {
    save_method = 'add';
    $('#module').modal('show'); // show bootstrap modal
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
      modal.find('[name="user_group_name"]').text(button.data('user_group'))
      modal.find('[name="user_group_name"]').val(button.data('user_group_guid'))

    });
  }
  
  function add_user()
  {
    save_method = 'add';
    $('#user').modal('show'); // show bootstrap modal
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



  function confirm_modal(delete_url)
  {
    $('#delete').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm delete ' + button.data('name') + '?')
    modal.find('.modal_alert').text(button.data('alert'))
    document.getElementById('url').setAttribute("href" , delete_url );
    });
  }


    
</script>

