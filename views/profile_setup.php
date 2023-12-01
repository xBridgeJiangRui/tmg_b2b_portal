<style type="text/css">
  #acc_branch{
    height: 250px;
    overflow-y: scroll;

  }
  #acc_branch_group,#acc_concept{
    height: 250px;
    overflow-y: scroll;

  }

  #acc_concept1_wrapper {
    overflow-x: hidden;
  }

  #acc_branch_group1_wrapper {
    overflow-x: hidden;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
  <?php
  if($this->session->userdata('message'))
  {
     echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; 
  }
  ?>
  

    <div class="row">
    <div class="col-md-12">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Account Branch</h3>
              <div class="box-tools pull-right">

              <a href="<?php echo site_url('Profile_setup')?>"><button class="btn btn-xs btn-info" onclick="add_user()"><i class="glyphicon glyphicon-th-list"></i> Show All</button></a>

              <a href="<?php echo site_url('acc_branch/create')?>"><button class="btn btn-xs btn-primary" onclick="add_user()"><i class="glyphicon glyphicon-plus"></i> Create</button></a>

                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
              </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_branch">
          <div id="accbranchCheck">
          <form id="formaccbranch" method="post" action="<?php echo site_url('profile_setup/check')?>?table=acc_branch&col_guid=branch_guid&col_check=isactive">
            <table id="acc1" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr >
                        <th>Action</th>
                        <th>Active</th>
                        <th>Concept </th>
                        <th>Branch Group </th>
                        <th>Branch Code</th>
                        <th>Branch Name</th>
                        <th>Regno</th>
                        <th>GSTno</th>
                        <th>Faxno</th>
                        <th>Address 1</th>
                        <th>Address 2</th>
                        <th>Address 3</th>
                        <th>Address 4</th>
                        <th>Postcode</th>
                        <th>State</th>
                        <th>Country</th>
                       <!--  <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                    foreach($account_branch->result() as $row)
                    {
                      ?>

                      <tr>
                      <td>
                        <a title="Edit" href="<?php echo site_url('acc_branch/update')?>?guid=<?php echo $row->branch_guid?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-pencil "></i></a>
                        <!-- <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_branch/delete'); ?>?guid=<?php echo $row->branch_guid?>')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->branch_name?>" ><i class="glyphicon glyphicon-trash"></i></button> -->
                      </td>
                      <td>

                      <input type="hidden" name="guid[]" value="<?php echo $row->branch_guid?>">
                          <div ng-controller="accbranchCheckController ">
                          <input type="checkbox" ng-model="checkedvalue" onchange="$('#formaccbranch').submit();"
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
                            <input type="checkbox" ng-model="checkedvalue" onchange="$('#formaccbranch').submit();">
                            <?php
                          }
                          ?>
                                                  
                          <input  style="display: none" value="{{getNumber()}}" name="active[]">
                          </div>
                        
                      </td>
                      <td
                      <?php 
                      if(isset($_REQUEST['concept_guid']) == $row->concept_guid)
                      {
                        ?>
                        id="highlight2" 
                        <?php
                      }
                      ?>>
                      <?php echo $row->concept_name?>
                      </td>
                      <td
                      <?php 
                      if(isset($_REQUEST['branch_group_guid']) == $row->branch_group_guid)
                      {
                        ?>
                        id="highlight" 
                        <?php
                      }

                      ?>><?php echo $row->group_name?></td>
                      <td><?php echo $row->branch_code?></td>
                      <td><?php echo $row->branch_name?></td>
                      <td><?php echo $row->branch_regno?></td>
                      <td><?php echo $row->branch_gstno?></td>
                      <td><?php echo $row->branch_fax?></td>
                      <td><?php echo $row->branch_add1?></td>
                      <td><?php echo $row->branch_add2?></td>
                      <td><?php echo $row->branch_add3?></td>
                      <td><?php echo $row->branch_add4?></td>
                      <td><?php echo $row->branch_postcode?></td>
                      <td><?php echo $row->branch_state?></td>
                      <td><?php echo $row->branch_country?></td>
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
      <!-- /.box -->

    </div>
  </div>

  <div class="row">
    <div class="col-md-6">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Account concept</h3>
          <div class="box-tools pull-right">
          <a href="<?php echo site_url('acc_concept/create')?>"><button class="btn btn-xs btn-primary" ><i class="glyphicon glyphicon-plus"></i> Create</button></a>

            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
          <form id="formACCconcept" method="post" action="<?php echo site_url('profile_setup/check')?>?table=acc_concept&col_guid=concept_guid&col_check=isactive">
                  <table id="acc_concept1" class="table table-bordered table-hover" width="100%" cellspacing="0">
                 
                    <thead>
                    <tr >
                        <th>Action</th>
                        <th>Isactive</th>
                        <th>Account</th>
                        <th>Concept Name</th>
                        <!-- <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($account_concept->result() as $row)
                    {
                      ?>
                      <tr>
                      <td>
                        <a title="Edit" href="<?php echo site_url('acc_concept/update')?>?guid=<?php echo $row->concept_guid?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-pencil "></i></a>
                        
                        <!-- <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_concept/delete'); ?>?guid=<?php echo $row->concept_guid?>')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->concept_name?>" ><i class="glyphicon glyphicon-trash"></i></button> -->
                        
                      </td>
                      <td>

                      <input type="hidden" name="guid[]" value="<?php echo $row->concept_guid?>">
                          <div ng-controller="accconceptCheckController ">
                          <input type="checkbox" ng-model="checkedvalue" onchange="$('#formACCconcept').submit();"
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
                            <input type="checkbox" ng-model="checkedvalue" onchange="$('#formACCconcept').submit();">
                            <?php
                          }
                          ?>
                                                  
                          <input  style="display: none" value="{{getNumber()}}" name="active[]">
                          </div>
                      
                        
                      </td>
                      <td><?php echo $row->acc_name?></td>
                      <td>
                      <span 
                      <?php 
                      if(isset($_REQUEST['concept_guid']) && $_REQUEST['concept_guid'] == $row->concept_guid)
                      {
                        ?>
                        id="highlight2" 
                        <?php
                      }
                      ?>
                      class="label label-default" style="font-size: 14px"><a href="<?php echo site_url('Profile_setup')?>?concept_guid=<?php echo $row->concept_guid?>"><?php echo $row->concept_name?></a></span>
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
                </form>
              </div>  
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /info div -->

    </div>

        <div class="col-md-6">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Account Branch Group</h3>
              <div class="box-tools pull-right">

              <a href="<?php echo site_url('acc_branch_group/create')?>"><button class="btn btn-xs btn-primary" onclick="add_user()"><i class="glyphicon glyphicon-plus"></i> Create</button></a>

                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
              </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_branch_group">
          <div id="accbranchgroupCheck">
          <form id="formaccbranchgroup" method="post" action="<?php echo site_url('profile_setup/check')?>?table=acc_branch_group&col_guid=branch_group_guid&col_check=isactive">
            <table id="acc_branch_group1" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr >
                        <th>Action</th>
                        <th>Isactive</th>
                        <th>Concept</th>
                        <th>Group Name</th>
                        <!-- <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                    foreach($account_branch_group->result() as $row)
                    {
                      ?>

                      <tr>
                      <td>
                        <a title="Edit" href="<?php echo site_url('acc_branch_group/update')?>?guid=<?php echo $row->branch_group_guid?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-pencil "></i></a>

                        <!-- <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_branch_group/delete'); ?>?guid=<?php echo $row->branch_group_guid?>')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->group_name?>" ><i class="glyphicon glyphicon-trash"></i></button> -->
                      </td>
                      <td>
                       <input type="hidden" name="guid[]" value="<?php echo $row->branch_group_guid?>">
                          <div ng-controller="accbranchgroupCheckController ">
                          <input type="checkbox" ng-model="checkedvalue" onchange="$('#formaccbranchgroup').submit();"
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
                            <input type="checkbox" ng-model="checkedvalue" onchange="$('#formaccbranchgroup').submit();">
                            <?php
                          }
                          ?>
                                                  
                          <input  style="display: none" value="{{getNumber()}}" name="active[]">
                          </div>

                      </td>
                      <td><?php echo $row->concept_name?></td>
                      <td>
                      <span 
                      <?php 
                      if(isset($_REQUEST['branch_group_guid']) && $_REQUEST['branch_group_guid'] == $row->branch_group_guid)
                      {
                        ?>
                        id="highlight" 
                        <?php
                      }
                      ?>
                      class="label label-default" style="font-size: 14px"><a href="<?php echo site_url('Profile_setup')?>?branch_group_guid=<?php echo $row->branch_group_guid?>"><?php echo $row->group_name?></a></span>
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
          </form>
        </div>
         
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </div>

  </div>

    <div class="row">
    <div class="col-md-12">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Account</h3>
              <div class="box-tools pull-right">

               <a href="<?php echo site_url('acc/create')?>"><button class="btn btn-xs btn-primary" onclick="add_user()"><i class="glyphicon glyphicon-plus"></i> Create</button></a> 
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
              </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div id="accCheck">
          <form id="formACC" method="post" action="<?php echo site_url('profile_setup/check')?>?table=acc&col_guid=acc_guid&col_check=isactive">
            <table id="acc2" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr >
                        <th>Action</th>
                        <!-- <th>Active</th> -->
                        <th>Name</th>
                        <th>Regno</th>
                        <th>Tax No</th>
                        <th>Taxcode</th>
                        <th>Address 1</th>
                        <th>Address 2</th>
                        <th>Address 3</th>
                        <th>Address 4</th>
                        <th>Postcode</th>
                        <th>State</th>
                        <th>Country</th>
                        <!-- <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                    foreach($account->result() as $row)
                    {
                        $guid = $row->acc_guid;
                      ?>

                      <tr>
                      <td>
                        <a title="Edit" href="<?php echo site_url('acc/update')?>?guid=<?php echo $row->acc_guid?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-pencil "></i></a>
                        <!-- <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc/delete'); ?>?guid=<?php echo $row->acc_guid ?>')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->acc_name?>" ><i class="glyphicon glyphicon-trash"></i></button> -->
                      </td>
                     <!--  <td>
                      <input type="hidden" name="guid[]" value="<?php echo $row->acc_guid?>">
                          <div ng-controller="accCheckController ">
                          <input type="checkbox" ng-model="checkedvalue" onchange="$('#formACC').submit();"
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
                            <input type="checkbox" ng-model="checkedvalue" onchange="$('#formACC').submit();">
                            <?php
                          }
                          ?>
                                                  
                          <input  style="display: none" value="{{getNumber()}}" name="active[]">
                          </div>
                        
                      </td> -->
                      <td><?php echo $row->acc_name?></td>
                      <td><?php echo $row->acc_regno?></td>
                      <td><?php echo $row->acc_gstno?></td>
                      <td><?php echo $row->acc_taxcode?></td>
                      <td><?php echo $row->acc_add1?></td>
                      <td><?php echo $row->acc_add2?></td>
                      <td><?php echo $row->acc_add3?></td>
                      <td><?php echo $row->acc_add4?></td>
                      <td><?php echo $row->acc_postcode?></td>
                      <td><?php echo $row->acc_state?></td>
                      <td><?php echo $row->acc_country?></td>
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
      <!-- /.box -->

    </div>
  </div>

</div>
</div>

<script>
  $(document).ready(function() {
    $('#acc1').DataTable({
      columnDefs: [
        // Disable sorting for the "Action" column
        {"targets": [0], "orderable": false},
        // Disable sorting for the "Active" column
        {"targets": [1], "orderable": false}
      ],
      searching: true,
      paging: true,
      order: [],// Disable initial sorting for all columns
      dom: '<"row"<"col-sm-4" l><"col-sm-8" f>>Brtip'
    });

    $('#acc_concept1').DataTable({
      columnDefs:[
        // Disable sorting for the "Action" column
        {"targets":[0], "orderable": false},
        // Disable sorting for the "Isactive" column
        {"targets":[1], "orderable": false}
      ],
      searching: true,
      paging: true,
      order: [] // Disable initial sorting for all columns
    });

    $('#acc_branch_group1').DataTable({
      columnDefs:[
        // Disable sorting for the "Action" column
        {"targets": [0], "orderable": false},
        // Disable sorting for the "" Isactive column
        {"targets": [1], "orderable": false}
      ],
        searching: true,
        paging: true,
        order: [], //Disable initial sorting for all columns
        dom: '<"row"<"col-sm-4" l><"col-sm-8" f>>Brtip'
    });

    $('#acc2').DataTable({
      columnDefs:[
        // Disable sorting for the "Action" column
        {"targets": [0], "orderable": false},
      ],
      searching: true,
      paging: true,
      order:[] //Disable initial sorting for all columns
    });
  });

  var accCheckApp = angular.module('accCheckApp', []);
    accCheckApp.controller('accCheckController', function($scope) {
       $scope.getText = function() { return $scope.checkedvalue ? "Yes" : "No"; };
        $scope.getNumber = function() { return $scope.checkedvalue ? 1 : 0; };
    });

    var accconceptCheckpApp = angular.module('accconceptCheckpApp', []);
    accconceptCheckpApp.controller('accconceptCheckController', function($scope) {
      $scope.getText = function() { return $scope.checkedvalue ? "Yes" : "No"; };
        $scope.getNumber = function() { return $scope.checkedvalue ? 1 : 0; };
    });

    var accbranchCheckpApp = angular.module('accbranchCheckpApp', []);
    accbranchCheckpApp.controller('accbranchCheckController', function($scope) {
      $scope.getText = function() { return $scope.checkedvalue ? "Yes" : "No"; };
        $scope.getNumber = function() { return $scope.checkedvalue ? 1 : 0; };
    });

    var accbranchgroupCheckApp = angular.module('accbranchgroupCheckApp', []);
    accbranchgroupCheckApp.controller('accbranchgroupCheckController', function($scope) {
      $scope.getText = function() { return $scope.checkedvalue ? "Yes" : "No"; };
        $scope.getNumber = function() { return $scope.checkedvalue ? 1 : 0; };
    });
    
    var accCheck = document.getElementById('accCheck');
    var accconceptCheck = document.getElementById('accconceptCheck');
    var accbranchCheck = document.getElementById('accbranchCheck');
    var accbranchgroupCheck = document.getElementById('accbranchgroupCheck');
    
    angular.element(document).ready(function() {
      angular.bootstrap(accCheck, ['accCheckApp']);
      angular.bootstrap(accconceptCheck, ['accconceptCheckpApp']);
      angular.bootstrap(accbranchCheck, ['accbranchCheckpApp']);
      angular.bootstrap(accbranchgroupCheck, ['accbranchgroupCheckApp']);
   });

</script>