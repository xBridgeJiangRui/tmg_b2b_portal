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


  .highlight_green {
    background-color: #9df9a6;
  }

  .content-wrapper{
    min-height: 800px !important; 
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
              <!-- <a href="<?php echo site_url('supplier_setup_new/create') ?>"><button class="btn btn-xs btn-primary" ><i class="glyphicon glyphicon-plus"></i> Create</button></a> -->

              <button id="import_attribute" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Import Create Supplier</button>

              <!-- <button title="Create" onclick="reg_supplier()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#regsupplier" data-table="<?php echo 'set_supplier' ?>" data-mode="<?php echo 'create' ?>"><i class="glyphicon glyphicon-plus"></i>Create</button> -->

              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
            </div>
          </div>
          <!-- /.box-header -->

          <div class="box-body" id="acc_concept">
            <div id="accconceptCheck">
              <form id="formACCconcept" method="post" action="<?php echo site_url('supplier_setup_new/check') ?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                <table id="reg_supplier" class="table table-bordered table-hover" width="100%" cellspacing="0">

                  <thead>
                    <tr>
                      <th>Action</th>
                      <th>Isactive</th>
                      <th>Old Company Name</th>
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
              <form id="formACCconcept" method="post" action="<?php echo site_url('supplier_setup_new/check') ?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
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

              <a href="<?php echo site_url('supplier_setup_new') ?>"><button class="btn btn-xs btn-info"><i class="glyphicon glyphicon-th-list"></i> Show All</button></a>

              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

            </div>

          </div>
          <!-- /.box-header -->
          <div class="box-body" id="acc_branch">
            <div id="accbranchCheck">
              <form id="formaccbranch" method="post" action="<?php echo site_url('supplier_setup_new/check') ?>?table=set_user&col_guid=user_guid&col_check=isactive">
                <table id="acc1" class="table table-bordered table-hover" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Action</th>
                      <!-- <th>Active</th> -->
                      <th>Comp Name </th>
                      <th>Comp Group</th>
                      <th>Username</th>
                      <th>Email</th>
                      <th>User Group</th>
                      <!--  <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
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

      setTimeout(function() {
        $('#select2_erp_group').select2();
        $('#select2_supplier_name').select2();
      }, 300);
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
      modal.find('[name="old_supplier_name"]').val(button.data('supplier_name'))
      modal.find('[name="old_supplier_name_display"]').val(button.data('old_supplier_name'))
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
    location.href = '<?php echo site_url('supplier_setup_new/change_cus_guid') ?>?customer_guid=' + $('#filter_customer').val();
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



  $(document).ready(function() {



    reg_supplier_table = function() {
      if ($.fn.DataTable.isDataTable('#reg_supplier')) {
        $('#reg_supplier').DataTable().destroy();
      }

      var table;

      table = $('#reg_supplier').DataTable({
        "columnDefs": [
          // {"targets": 3 ,"visible": false},
          {
            "targets": [0, 1],
            "orderable": false
          }
        ],
        "serverSide": true,
        'processing': true,
        'paging': true,
        'lengthChange': true,
        'lengthMenu': [
          [10, 25, 50, 9999999999999999],
          [10, 25, 50, "ALL"]
        ],
        'searching': true,
        'ordering': true,
        'order': [
          [3, 'asc']
        ],
        'info': true,
        'autoWidth': false,
        "bPaginate": true,
        "bFilter": true,
        stateSave: true,
        // "sScrollY": "15vh", 
        // "sScrollX": "100%", 
        // "sScrollXInner": "100%", 
        "bScrollCollapse": true,
        "ajax": {
          "url": "<?php echo site_url('supplier_setup_new/reg_supplier_table'); ?>",
          "type": "POST",
          complete: function() {},
        },
        //'fixedHeader' : false,
        columns: [
          // {"data":"RefNo"},
          // {"data":"filename" ,render: function ( data, type, row ) {

          // return '<button style="float:left" id="open_modal_troubleshoot_po" filename="'+data+'" class="btn btn-sm btn-info" role="button"><i class="glyphicon glyphicon-eye-open"></i></button>';

          // }},
          // {"data":"RefNo" ,render: function ( data, type, row ) {
          // if (data == 1) { ischecked = 'checked' } else { ischecked = '' }
          // return '<input type="checkbox" class="form-checkbox" '+ischecked+' />';
          // }},

          {
            "data": "supplier_guid",
            render: function(data, type, row) {

              element = '';

              element += '<button title="Edit" onclick="reg_supplier_edit()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#regsupplier" data-table="set_supplier" data-mode="update" data-guid="' + row['supplier_guid'] + '" data-old_supplier_name="' + row['old_supplier_name'] + '" data-supplier_name="' + row['supplier_name'] + '" data-gst_no="' + row['gst_no'] + '" data-reg_no="' + row['reg_no'] + '" data-isactive="' + row['isactive'] + '" ><i class="glyphicon glyphicon-pencil"></i></button>';

              if (row['suspended'] == '1') {
                suspend_var = 'unsuspend';
              } else {
                suspend_var = 'suspend';
              }

              element += '<button title="Suspend" onclick="confirm_modal_suspend(\'<?php echo site_url('supplier_setup_new/suspend'); ?>?guid=' + row['supplier_guid'] + '\')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#suspend" data-name="' + row['name_reg'] + '"  data-suspend=' + suspend_var + ' ><i class="glyphicon glyphicon-time"></i></button>';

              return element;

            }
          },
          {
            "data": "isactive",
            render: function(data, type, row) {
              if (data == 1) {
                ischecked = 'checked'
              } else {
                ischecked = ''
              }
              return '<input type="checkbox" disabled class="form-checkbox" ' + ischecked + ' />';
            }
          },
          {
            "data": "old_supplier_name"
          },
          {
            "data": "supplier_name",
            render: function(data, type, row) {
              var id = '';
              <?php
              if (isset($_REQUEST['supplier_guid'])) {
              ?>

                var supplier_guid_session = '<?= $_REQUEST['supplier_guid']; ?>';

                if (row['supplier_guid'] == supplier_guid_session) {
                  id = 'id="highlight2"';
                }

              <?php
              }
              ?>

              element = '';

              element += '<span ' + id + ' class="label label-default" supplier_guid="' + row['supplier_guid'] + '" style="font-size: 14px;cursor:pointer;"><a href="<?php echo site_url('supplier_setup_new') ?>?supplier_guid=' + row['supplier_guid'] + '">' + row['supplier_name'] + '</a></span>';

              return element;

            }
          },
          {
            "data": "reg_no"
          },
          {
            "data": "created_at"
          },
          {
            "data": "acc_code"
          },

        ],


        dom: '<"row"<"col-sm-4" l><"col-sm-8" f>>Brtip',


        // "pagingType": "simple_numbers",
        "fnCreatedRow": function(nRow, aData, iDataIndex) {

          if (aData['suspended'] == '1') {
            $(nRow).closest('tr').attr("id", "highlight3");
          }

          // $(nRow).attr('RefNo', aData['RefNo']);

        },
        "initComplete": function(settings, json) {
          setTimeout(function() {
            interval();
          }, 300);
        }
      }); //close datatable

    } //close reg_supplier



    group_supplier_table = function(variable, supplier_guid) {
      if ($.fn.DataTable.isDataTable('#group_supplier')) {
        $('#group_supplier').DataTable().destroy();
      }

      var table;

      table = $('#group_supplier').DataTable({
        "columnDefs": [
          // {"targets": 3 ,"visible": false},
          {
            "targets": 0,
            "orderable": false
          }
        ],
        "serverSide": true,
        'processing': true,
        'paging': true,
        'lengthChange': true,
        'lengthMenu': [
          [10, 25, 50, 9999999999999999],
          [10, 25, 50, "ALL"]
        ],
        'searching': true,
        'ordering': true,
        'order': [
          [2, 'asc']
        ],
        'info': true,
        'autoWidth': false,
        "bPaginate": true,
        "bFilter": true,
        stateSave: true,
        // "sScrollY": "15vh", 
        // "sScrollX": "100%", 
        // "sScrollXInner": "100%", 
        "bScrollCollapse": true,
        "ajax": {
          "url": "<?php echo site_url('supplier_setup_new/group_supplier_table'); ?>",
          "type": "POST",
          data: {
            variable: variable,
            supplier_guid: supplier_guid
          },
          complete: function() {},
        },
        //'fixedHeader' : false,
        columns: [
          // {"data":"RefNo"},
          // {"data":"filename" ,render: function ( data, type, row ) {

          // return '<button style="float:left" id="open_modal_troubleshoot_po" filename="'+data+'" class="btn btn-sm btn-info" role="button"><i class="glyphicon glyphicon-eye-open"></i></button>';

          // }},
          // {"data":"RefNo" ,render: function ( data, type, row ) {
          // if (data == 1) { ischecked = 'checked' } else { ischecked = '' }
          // return '<input type="checkbox" class="form-checkbox" '+ischecked+' />';
          // }},
          {
            "data": "supplier_guid",
            render: function(data, type, row) {

              element = '';

              element += '<button title="Edit" onclick="reg_supplier_group_edit()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#regsuppliergroup" data-table="set_supplier_group" data-mode="update" data-guid="' + row['supplier_group_guid'] + '" data-supplier_guid="' + row['supplier_guid'] + '" data-supplier_group_name="' + row['supplier_group_name'] + '" ><i class="glyphicon glyphicon-pencil"></i></button>';

              element += '<button title="Delete" onclick="confirm_modal(\'<?php echo site_url("supplier_setup/delete_group"); ?>?supplier_guid=' + row['supplier_guid'] + '&supplier_group_guid=' + row['supplier_group_guid'] + '&customer_guid=<?php echo $_SESSION["customer_guid"] ?> \')"  type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="' + row['supplier_name'] + '" ><i class="glyphicon glyphicon-trash"></i></button> ';

              return element;
            }
          },
          {
            "data": "supplier_group_name",
            render: function(data, type, row) {
              var id = '';
              <?php
              if (isset($_REQUEST['supplier_guid'])) {
              ?>

                var supplier_guid_session = '<?= $_REQUEST['supplier_guid']; ?>';

                if (row['supplier_guid'] == supplier_guid_session) {
                  id = 'id="highlight2"';
                }

              <?php
              }
              ?>

              element = '';

              element += '<span ' + id + ' class="label label-default" supplier_guid="' + row['supplier_guid'] + '" style="font-size: 14px;cursor:pointer;"><a href="<?php echo site_url('supplier_setup_new') ?>?supplier_guid=' + row['supplier_guid'] + '">' + row['supplier_group_name'] + '</a></span>';

              return element;

            }
          },
          {
            "data": "supplier_name"
          }
        ],


        dom: '<"row"<"col-sm-4" l><"col-sm-8" f>>rtip',


        // "pagingType": "simple_numbers",
        "fnCreatedRow": function(nRow, aData, iDataIndex) {

          // $(nRow).attr('RefNo', aData['RefNo']);

        },
        "initComplete": function(settings, json) {
          setTimeout(function() {
            interval();
          }, 300);
        }
      }); //close datatable

    } //close reg_supplier








    acc1_table = function(variable, supplier_guid) {

      if ($.fn.DataTable.isDataTable('#acc1')) {
        $('#acc1').DataTable().destroy();
      }

      var table;

      table = $('#acc1').DataTable({
        "columnDefs": [
          // {"targets": 3 ,"visible": false},
          {
            "targets": 0,
            "orderable": false
          }
        ],
        "serverSide": true,
        'processing': true,
        'paging': true,
        'lengthChange': true,
        'lengthMenu': [
          [10, 25, 50, 9999999999999999],
          [10, 25, 50, "ALL"]
        ],
        'searching': true,
        'ordering': true,
        'order': [
          [2, 'asc']
        ],
        'info': true,
        'autoWidth': false,
        "bPaginate": true,
        "bFilter": true,
        stateSave: true,
        // "sScrollY": "15vh", 
        // "sScrollX": "100%", 
        // "sScrollXInner": "100%", 
        "bScrollCollapse": true,
        "ajax": {
          "url": "<?php echo site_url('supplier_setup_new/acc1_table'); ?>",
          "type": "POST",
          data: {
            variable: variable,
            supplier_guid: supplier_guid
          },
          complete: function() {},
        },
        //'fixedHeader' : false,
        columns: [
          // {"data":"RefNo"},
          // {"data":"filename" ,render: function ( data, type, row ) {

          // return '<button style="float:left" id="open_modal_troubleshoot_po" filename="'+data+'" class="btn btn-sm btn-info" role="button"><i class="glyphicon glyphicon-eye-open"></i></button>';

          // }},
          // {"data":"RefNo" ,render: function ( data, type, row ) {
          // if (data == 1) { ischecked = 'checked' } else { ischecked = '' }
          // return '<input type="checkbox" class="form-checkbox" '+ischecked+' />';
          // }},

          {
            "data": "acc_guid",
            render: function(data, type, row) {

              element = '';

              element += '<button title="Edit" onclick="edit_user()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#user" data-query="SELECT * FROM set_supplier" data-guid="' + row['user_guid'] + '" > <i class="glyphicon glyphicon-pencil"></i></button>';

              element += '<a  title="View Record" class="btn btn-xs btn-warning" href="<?php echo site_url('supplier_setup_new/view_records'); ?>?user_guid=' + row['user_guid'] + '" > <i class="glyphicon glyphicon-eye-open"></i></a> ';

              return element;
            }
          },
          {
            "data": "name_reg"
          },
          {
            "data": "all_sup_assigned"
          },
          {
            "data": "user_name"
          },
          {
            "data": "user_id"
          },
          {"data":"user_group_name"},

        ],


        dom: '<"row"<"col-sm-4" l><"col-sm-8" f>>rtip',


        // "pagingType": "simple_numbers",
        "fnCreatedRow": function(nRow, aData, iDataIndex) {

          var id = '';

          <?php
          if (isset($_REQUEST['supplier_guid'])) {
          ?>

            var supplier_guid_request = '<?= $_REQUEST['supplier_guid']; ?>';

            if (supplier_guid_request == aData['supplier_guid']) {
              $(nRow).closest('tr').find('td:eq(1)').attr("id", "highlight2");
              $(nRow).closest('tr').find('td:eq(2)').attr("id", "highlight2");
            }

          <?php
          }
          ?>

        },
        "initComplete": function(settings, json) {
          setTimeout(function() {
            interval();
          }, 300);
        }
      }); //close datatable

    } //close reg_supplier









    reg_supplier_table();

    <?php
    if (isset($_REQUEST['supplier_guid'])) //if using highlight supplier mode
    {
      $variable = 'supplier_guid';
    } else {
      if (isset($_REQUEST['customer_guid'])) // if using customer filter mode
      {
        $variable = 'customer_guid';
      } else {
        $variable = '';
      } //clsoe else
    }
    ?>



    <?php
    if (isset($_REQUEST['supplier_guid'])) //if using highlight supplier mode
    {
    ?>

      var variable = '<?php echo $variable; ?>';

      group_supplier_table(variable, '<?= $_REQUEST['supplier_guid']; ?>');

      acc1_table(variable, '<?= $_REQUEST['supplier_guid']; ?>');

      <?php
    } else {
      if (isset($_REQUEST['customer_guid'])) // if using customer filter mode
      {
      ?>

        var variable = '<?php echo $variable; ?>';

        group_supplier_table(variable, '<?= $_REQUEST['customer_guid']; ?>');

        acc1_table(variable, '<?= $_REQUEST['customer_guid']; ?>');

      <?php
      } else {
      ?>

        var variable = '<?php echo $variable; ?>';

        group_supplier_table(variable, '');

        acc1_table(variable, '');

    <?php
      } //clsoe else
    }
    ?>

    $(document).on('click','#import_attribute',function(){
      var modal = $("#medium-modal").modal();

      modal.find('.modal-title').html('Import File');

      methodd = '';

      methodd +='<div id="myDropZone" class="dropzone" style="height:20px;"><center><label class="vertical-center" id="output" for="upload_file">Select a file to continue</label></center> </div>';

      methodd += '<div class="row" style="padding-top:10px;">';
      methodd += '<form id="excel_file_form">';
      methodd += '<div class="col-md-6">';
      methodd += '<label for="upload_file" class="btn btn-block btn-primary">Select File</label>';
      methodd += '</div>';
      methodd += '<div class="col-md-6" style="margin-bottom:10px;">';
      methodd += '<button type="button" class="btn btn-block btn-danger" id="reset_input">Reset</button>';
      methodd += '</div>';
      methodd += '<div class="col-md-6">';
      methodd += '<input type="file" name="photo" id="upload_file" accept=".xls,.xlsx,.csv" style="margin-right:50px;"/>';
      methodd += '</div>';
      methodd += '</form>';
      methodd += '</div>';

      methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

      modal.find('.modal-footer').html(methodd_footer);
      modal.find('.modal-body').html(methodd);
    });//close import

    $(document).on('change','#upload_file',function(e){

      var fileName = e.target.files[0].name;

      if(fileName != '')
      { 
        $('#submit_button').remove();

        $('#excel_file_form').append('<div class="col-md-12" ><button type="button" id="submit_button" class="btn btn-block btn-success" style="margin-top:10px;">Submit</button></div>');

        $('#output').html(fileName);

      }
      else
      { 
        $('#output').html('No files selected');
        $('#submit_button').remove();
      }
    });//close upload file

    $(document).on('click','#reset_input',function(){

      $('#upload_file').val('');

      var file = $('#upload_file')[0].files[0];

      if(file === undefined)
      {
        $('#output').html('No files selected');
          $('#submit_button').remove();
      }
      else
      { 
        var fileName = file.name;

        $('#submit_button').remove();

          $('#excel_file_form').append('<button type="button" class="btn btn-block btn-success" id="submit_button" style="margin-top:10px;">Submit</button>');

          $('#output').html(fileName);
      }
    });//close reset_input

    $(document).on('click','#submit_button',function(){

      confirmation_modal('Are you sure want to Submit?');

      $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

        var formData = new FormData();
        formData.append('file', $('#upload_file')[0].files[0]);

        $.ajax({
            url:"<?= site_url('Supplier_setup_new/file_upload');?>",
            method:"POST",
            data: formData,
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            beforeSend : function()
            { 
              $('.btn').button('loading');
            },
            complete : function()
            { 
              $('.btn').button('reset');
            },
            success:function(data)
            {
              json = JSON.parse(data);
              $('#alertmodal').modal('hide');
              if (json.para1 == 'false') {
                alert(json.msg);
                //alert(json.msg.replace(/\\n/g,"\n"));
                $('.btn').button('reset');
                $('#upload_file').val('');
                $('#output').html('No files selected');
                $('#submit_button').remove();

              }else{

                $('#medium-modal').modal('hide');
                $('#alertmodal').modal('hide');
                alert(json.msg.replace(/\\n/g,"\n"));
                location.reload();
                // setTimeout(function() {
                // $('#upload_file').val('');
                // $('#output').html('No files selected');
                // $('#submit_button').remove();
                // }, 300);
            }//close else
          }//close success
        });//close ajax
      });//close document yes click
    });//close submit_button


  }); //close document ready
</script>