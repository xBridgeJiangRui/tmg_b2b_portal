<style type="text/css">
  #acc_branch {
    height: 250px;
    overflow-y: scroll;

  }

  #acc_branch_group,
  #acc_concept {
    height: 250px;
    overflow-y: scroll;

  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="container-fluid">
    <br>
    <?php
    if ($this->session->userdata('message')) {
      echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : '';
    }
    ?>
    <?php // echo var_dump($_SESSION); 
    ?>
    <?php echo 'Current Company :' . $acc_current; ?>

    <div class="row">
      <div class="col-md-6">

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Registered Supplier</h3>
            <div class="box-tools pull-right">
              <!-- <a href="<?php echo site_url('supplier_setup/create') ?>"><button class="btn btn-xs btn-primary" ><i class="glyphicon glyphicon-plus"></i> Create</button></a> -->

              <button title="Create" onclick="reg_supplier()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#regsupplier" data-table="<?php echo 'set_supplier' ?>" data-mode="<?php echo 'create' ?>"><i class="glyphicon glyphicon-plus"></i>Create</button>

              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
            </div>
          </div>
          <!-- /.box-header -->

          <div class="box-body" id="acc_concept">
            <div id="accconceptCheck">
              <form id="formACCconcept" method="post" action="<?php echo site_url('supplier_setup/check') ?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                <table id="reg_supplier" class="table table-bordered table-hover" width="100%" cellspacing="0">

                  <thead>
                    <tr>
                      <th>Action</th>
                      <th>Isactive</th>
                      <th>Company Name</th>
                      <th>Company Reg No</th>
                      <th>Created Time</th>
                      <th>Debtor Code</th>
                      <!-- <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($set_supplier->result() as $row) {
                    ?>
                      <tr <?php if ($row->suspended == '1') { ?> id="highlight3" <?php }; ?>>
                        <td>
                          <button title="Edit" onclick="reg_supplier_edit()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#regsupplier" data-table="<?php echo 'set_supplier' ?>" data-mode="<?php echo 'update' ?>" data-guid="<?php echo $row->supplier_guid ?>" data-supplier_name="<?php echo $row->supplier_name ?>" data-gst_no="<?php echo $row->gst_no ?>" data-reg_no="<?php echo $row->reg_no ?>" data-isactive="<?php echo $row->isactive ?>"><i class="glyphicon glyphicon-pencil"></i></button>



                          <button title="Suspend" onclick="confirm_modal_suspend('<?php echo site_url('supplier_setup/suspend'); ?>?guid=<?php echo $row->supplier_guid ?>')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#suspend" data-name="<?php echo $row->name_reg ?>" <?php if ($row->suspended == '1') { ?> data-suspend='unsuspend' <?php } else { ?> data-suspend='suspend' <?php } ?>><i class="glyphicon glyphicon-time"></i></button>

                        </td>
                        <td>
                          <input type="checkbox" disabled name="cb_box" value="<?php echo $row->isactive ?>" <?php if ($row->isactive == '1') {
                                                                                                                echo "checked";
                                                                                                              } ?>>
                        </td>
                        <!-- <td>

                      <input type="hidden" name="guid[]" value="<?php echo $row->supplier_guid ?>">
                          <div ng-controller="accconceptCheckController ">
                          <input type="checkbox" ng-model="checkedvalue" onchange="$('#formACCconcept').submit();"
                          <?php
                          if ($row->isactive == '1') {
                          ?>
                            ng-init="checkedvalue = true"/>
                            <?php
                          }
                            ?> 

                          <?php
                          if ($row->isactive == '0') {
                          ?>
                            <input type="checkbox" ng-model="checkedvalue" onchange="$('#formACCconcept').submit();">
                            <?php
                          }
                            ?>
                                                  
                          <input  style="display: none" value="{{getNumber()}}" name="active[]">
                          </div>
                      
                        
                      </td> -->
                        <td>
                          <span <?php
                                if (isset($_REQUEST['supplier_guid']) && $_REQUEST['supplier_guid'] == $row->supplier_guid) {
                                ?> id="highlight2" <?php
                                                  }
                                                    ?> class="label label-default" style="font-size: 14px"><a href="<?php echo site_url('supplier_setup') ?>?supplier_guid=<?php echo $row->supplier_guid ?>"><?php echo $row->name_reg ?></a></span>
                        </td>
                        <td><?php echo $row->reg_no ?></td>
                        <td><?php echo $row->created_at ?></td>
                        <td><?php echo $row->acc_code ?></td>
                        <!-- <td><?php echo $row->created_by ?></td>
                      <td><?php echo $row->updated_at ?></td>
                      <td><?php echo $row->updated_by ?></td> -->
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
            <h3 class="box-title">ERP Supplier Group</h3>
            <div class="box-tools pull-right">
              <button title="Create" onclick="reg_supplier_group()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#regsuppliergroup" data-table="<?php echo 'set_supplier_group' ?>" data-mode="<?php echo 'create' ?>"><i class="glyphicon glyphicon-plus"></i>Create</button>

              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body" id="acc_concept">
            <div id="accconceptCheck">
              <form id="formACCconcept" method="post" action="<?php echo site_url('supplier_setup/check') ?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                <table id="group_supplier" class="table table-bordered table-hover" width="100%" cellspacing="0">

                  <thead>
                    <tr>
                      <th>Action</th>
                      <!-- <th>Isactive</th> -->
                      <th>Group Name</th>
                      <th>Company Name</th>
                      <!-- <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($set_supplier_group->result() as $row) {
                    ?>
                      <tr>
                        <td>
                          <button title="Edit" onclick="reg_supplier_group_edit()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#regsuppliergroup" data-table="<?php echo 'set_supplier_group' ?>" data-mode="<?php echo 'update' ?>" data-guid="<?php echo $row->supplier_group_guid ?>" data-supplier_guid="<?php echo $row->supplier_guid ?>" data-supplier_group_name="<?php echo $row->supplier_group_name ?>"><i class="glyphicon glyphicon-pencil"></i></button>

                          <button title="Delete" onclick="confirm_modal('<?php echo site_url('supplier_setup/delete_group'); ?>?supplier_guid=<?php echo $row->supplier_guid ?>&supplier_group_guid=<?php echo $row->supplier_group_guid ?>&customer_guid=<?php echo $_SESSION['customer_guid'] ?> ')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->supplier_name ?>"><i class="glyphicon glyphicon-trash"></i></button>
                        </td>

                        <!-- <td>

                      <input type="hidden" name="guid[]" value="<?php echo $row->supplier_guid ?>">
                          <div ng-controller="accconceptCheckController ">
                          <input type="checkbox" ng-model="checkedvalue" onchange="$('#formACCconcept').submit();"
                          <?php
                          if ($row->isactive == '1') {
                          ?>
                            ng-init="checkedvalue = true"/>
                            <?php
                          }
                            ?> 

                          <?php
                          if ($row->isactive == '0') {
                          ?>
                            <input type="checkbox" ng-model="checkedvalue" onchange="$('#formACCconcept').submit();">
                            <?php
                          }
                            ?>
                                                  
                          <input  style="display: none" value="{{getNumber()}}" name="active[]">
                          </div>
                      
                        
                      </td> -->
                        <td>
                          <span <?php
                                if (isset($_REQUEST['supplier_guid']) && $_REQUEST['supplier_guid'] == $row->supplier_guid) {
                                ?> id="highlight2" <?php
                                                  }
                                                    ?> class="label label-default" style="font-size: 14px"><a href="<?php echo site_url('supplier_setup') ?>?supplier_guid=<?php echo $row->supplier_guid ?>"><?php echo $row->supplier_group_name ?></a></span>
                        </td>
                        <td>
                          <?php echo $row->supplier_name ?>
                        </td>
                        <!-- <td><?php echo $row->created_by ?></td>
                      <td><?php echo $row->updated_at ?></td>
                      <td><?php echo $row->updated_by ?></td> -->
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
    </div>


    <div class="row">
      <div class="col-md-12">

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Registered Supplier User</h3><input id="supplier_multiple_startup" type="hidden" value="2">
            <div class="box-tools pull-right">

              <select name="filter_customer" id="filter_customer" class="btn btn-xs btn-default" style="width: 50%;" onchange="ahsheng()">
                <?php
                foreach ($acc_filter->result() as $row) {
                ?>
                  <option required data-default value="<?php echo $row->acc_guid ?>" <?php if ($row->acc_guid == $_SESSION['customer_guid']) echo 'selected'; ?>><?php echo $row->acc_name ?></option>
                <?php
                }
                ?>
              </select>

              <a href="<?php echo site_url('supplier_setup') ?>"><button class="btn btn-xs btn-info" onclick="add_user()"><i class="glyphicon glyphicon-th-list"></i> Show All</button></a>

              <!--  <a href="<?php echo site_url('acc_branch/create') ?>"><button class="btn btn-xs btn-primary" onclick="add_user()"><i class="glyphicon glyphicon-plus"></i> Create</button></a> -->

              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body" id="acc_branch">
            <div id="accbranchCheck">
              <form id="formaccbranch" method="post" action="<?php echo site_url('supplier_setup/check') ?>?table=set_user&col_guid=user_guid&col_check=isactive">
                <table id="acc1" class="table table-bordered table-hover" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Action</th>
                      <!-- <th>Active</th> -->
                      <th>Comp Name </th>
                      <th>Comp Group</th>
                      <th>Username</th>
                      <th>Email</th>
                      <!--  <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    foreach ($set_user->result() as $row) {
                    ?>

                      <tr>
                        <td>
                          <!--   <a title="Assign Supplier" href="<?php echo site_url('supplier_setup/assign') ?>?guid=<?php echo $row->user_guid ?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-pencil "></i></a>
                      -->

                          <button title="Edit" onclick="edit_user()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#user" data-query="SELECT * FROM set_supplier" data-guid="<?php echo $row->user_guid ?>">
                            <i class="glyphicon glyphicon-pencil"></i></button>

                          <a title="View Record" class="btn btn-xs btn-warning" href="<?php echo site_url('supplier_setup/view_records'); ?>?user_guid=<?php echo $row->user_guid ?>">
                            <i class="glyphicon glyphicon-eye-open"></i></a>

                        </td>

                        <td <?php
                            if (isset($_REQUEST['supplier_guid']) == $row->supplier_guid) {
                            ?> id="highlight2" <?php
                                              }
                                                ?>>
                          <?php echo $row->name_reg ?>
                        </td>

                        <td <?php
                            if (isset($_REQUEST['supplier_guid']) == $row->supplier_guid) {
                            ?> id="highlight2" <?php
                                              }
                                                ?>>
                          <?php echo $row->all_sup_assigned ?>
                        </td>
                        <td><?php echo $row->user_name ?></td>
                        <td><?php echo $row->user_id ?></td>


                        <!-- <td><?php echo $row->created_at ?></td>
                      <td><?php echo $row->created_by ?></td>
                      <td><?php echo $row->updated_at ?></td>
                      <td><?php echo $row->updated_by ?></td> -->
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
    <div class="modal fade" id="view_record" role="dialog" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog  modal-md">

        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modal Headers</h4>
          </div>
          <div class="modal-body">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="">Close</button>
          </div>
        </div>


      </div>
    </div>

    <!-- confirm modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
    <div class="modal fade" id="delete" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" style="text-align: center">Confirm Delete?</h3>
            </div> -->
          <div class="modal-body">
            <h4 class="modal_detail" style="text-align: center"></h4>
          </div>
          <div class="modal-footer" style="text-align: center">
            <span id="preloader-delete"></span>
            <a id="url" href=""><button type="submit" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button></a>
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End confirm modal modal -->
    <!-- confirm modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
    <div class="modal fade" id="suspend" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" style="text-align: center">Confirm Delete?</h3>
            </div> -->
          <div class="modal-body">
            <h4 class="modal_detail" style="text-align: center"></h4>
          </div>
          <div class="modal-footer" style="text-align: center">
            <span id="preloader-delete"></span>
            <a id="url2" href=""><button type="submit" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-time"></i> Proceed</button></a>
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End confirm modal modal -->
    <!-- confirm modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
    <div class="modal fade" id="updatebackendb2bflag" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" style="text-align: center">Confirm Delete?</h3>
            </div> -->
          <div class="modal-body">
            <h4 class="modal_detail" style="text-align: center"></h4>
          </div>
          <div class="modal-footer" style="text-align: center">
            <span id="preloader-delete"></span>
            <a id="url2" href=""><button type="submit" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-time"></i> Proceed</button></a>
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End confirm modal modal -->




  </div>
</div>


<script>
  var accCheckApp = angular.module('accCheckApp', []);
  accCheckApp.controller('accCheckController', function($scope) {
    $scope.getText = function() {
      return $scope.checkedvalue ? "Yes" : "No";
    };
    $scope.getNumber = function() {
      return $scope.checkedvalue ? 1 : 0;
    };
  });

  var accconceptCheckpApp = angular.module('accconceptCheckpApp', []);
  accconceptCheckpApp.controller('accconceptCheckController', function($scope) {
    $scope.getText = function() {
      return $scope.checkedvalue ? "Yes" : "No";
    };
    $scope.getNumber = function() {
      return $scope.checkedvalue ? 1 : 0;
    };
  });

  var accbranchCheckpApp = angular.module('accbranchCheckpApp', []);
  accbranchCheckpApp.controller('accbranchCheckController', function($scope) {
    $scope.getText = function() {
      return $scope.checkedvalue ? "Yes" : "No";
    };
    $scope.getNumber = function() {
      return $scope.checkedvalue ? 1 : 0;
    };
  });

  var accbranchgroupCheckApp = angular.module('accbranchgroupCheckApp', []);
  accbranchgroupCheckApp.controller('accbranchgroupCheckController', function($scope) {
    $scope.getText = function() {
      return $scope.checkedvalue ? "Yes" : "No";
    };
    $scope.getNumber = function() {
      return $scope.checkedvalue ? 1 : 0;
    };
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

  function reg_supplier() {
    $('#regsupplier').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)
      modal.find('.modal-title').text('Add New')
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
    });
  }

  function reg_supplier_group() {
    $('#regsuppliergroup').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)
      modal.find('.modal-title').text('Add New')
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
    });
  }

  function reg_supplier_edit() {
    $('#regsupplier').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
      modal.find('[name="supplier_name"]').val(button.data('supplier_name'))
      modal.find('[name="reg_no"]').val(button.data('reg_no'))
      modal.find('[name="gst_no"]').val(button.data('gst_no'))
      modal.find('[name="isactive"]').val(button.data('isactive'))
    });
  }

  function reg_supplier_group_edit() {
    $('#regsuppliergroup').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
      modal.find('[name="supplier_group_name"]').val(button.data('supplier_group_name'))
      modal.find('[name="supplier_guid"]').val(button.data('supplier_guid'))
    });
  }

  /*function edit_user()
  {
    $('#user').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))

    });
  }
*/

  function edit_user() {
    $('#user').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)
      modal.find('[name="guid"]').val(button.data('guid'))

      /*modal.find('[name="acc_guid"]').val(button.data('acc_guid'))
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
      modal.find('[id="module_group"]').text(button.data('module_group'))*/


    });

  }


  function view_assigned() {
    $('#assigned_supplier').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="query"]').val(button.data('query'))

    });

  }
</script>
<script type="text/javascript">
  function status_confirmation() {
    var frm_element = document.getElementById("inactive_supplier").value;
    if (frm_element == '0') {
      alert('Setting Inactive will deactive all user under this company. Are you sure you want to continue?');
    }

  }
</script>
<script>
  function ahsheng() {
    <?php // $_SESSION['customer_guid'] = $this.('#filter_customer').val();  
    ?>
    location.href = '<?php echo site_url('supplier_setup/change_cus_guid') ?>?customer_guid=' + $('#filter_customer').val();
  }
</script>

<script type="text/javascript">
  function confirm_modal(delete_url) {
    $('#delete').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)
      modal.find('.modal_detail').text('Confirm delete ' + button.data('name') + '?')
      document.getElementById('url').setAttribute("href", delete_url);
    });
  }

  function confirm_modal_suspend(delete_url) {
    $('#suspend').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)
      modal.find('.modal_detail').text('Confirm  ' + button.data('suspend') + ' ' + button.data('name') + '?')
      document.getElementById('url2').setAttribute("href", delete_url);
    });
  }

  function update_backend_b2b_flah_model(delete_url) {
    $('#updatebackendb2bflag').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)
      modal.find('.modal_detail').text('Confirm delete ' + button.data('name') + '?')
      document.getElementById('url').setAttribute("href", delete_url);
    });
  }
</script>