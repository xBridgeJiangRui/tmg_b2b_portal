<style type="text/css">
  #acc_branch{
    height: 250px;
    overflow-y: scroll;

  }
  #acc_branch_group,#acc_concept{
    height: 250px;
    overflow-y: scroll;

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
<?php // echo var_dump($_SESSION); ?>
  <div class="row">
    <div class="col-md-12">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Registered Supplier</h3>
          <div class="box-tools pull-right">
          <!-- <a href="<?php echo site_url('supplier_setup/create')?>"><button class="btn btn-xs btn-primary" ><i class="glyphicon glyphicon-plus"></i> Create</button></a> -->

          <button title="Create" onclick="reg_supplier()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#regsupplier"  
            data-table="<?php echo 'set_supplier' ?>"
            data-mode="<?php echo 'create' ?>"
            ><i class="glyphicon glyphicon-plus"></i>Create</button>

            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->

        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
          <form id="formACCconcept" method="post" action="<?php echo site_url('supplier_setup/check')?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                  <table id="reg_supplier" class="table table-bordered table-hover" width="100%" cellspacing="0">
                 
                    <thead>
                    <tr>
                        <th>Action</th>
                        <!-- <th>Isactive</th> -->
                        <th>User ID</th>
                        <th>Supplier Name</th>
                        <th>Customer</th>
                        <th>Customer Supplier Code</th>
                        <!-- <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($view_rec->result() as $row)
                    {
                      ?>
                      <tr>
                      <td>
                      <!--   <button title="Edit" onclick="remove_assigned()" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#regsupplier"
                          data-table="<?php echo 'set_supplier_user_relationship' ?>"
                          data-mode="<?php echo 'delete' ?>"
                          data-acc_guid="<?php echo $row->acc_guid?>"
                          data-supplier_guid="<?php echo $row->supplier_guid?>"
                          data-supplier_group_guid="<?php echo $row->supplier_group_guid?>"
                          data-user_guid="<?php echo $row->user_guid?>"
                           ><i class="glyphicon glyphicon-trash"></i></button> -->

                           <button title="Delete" onclick="delete_modal('<?php echo site_url('supplier_setup/delete_guid'); ?>?acc_guid=<?php echo $row->acc_guid?>&supplier_guid=<?php  echo $row->supplier_guid?>&supplier_group_guid=<?php echo $row->supplier_group_guid ?>&user_guid=<?php echo $row->user_guid ?>')" type="button" class="btn btn-xs btn-danger" 
                          data-toggle="modal"  
                          data-target="#delete" 
                          data-report="<?php echo $row->user_guid; ?>" ><i class=" glyphicon glyphicon-trash"></i></button>
                      </td>
                      <td>
                        <?php echo $row->user_id; ?>                        
                      </td>
                      <td>
                        <?php echo $row->supplier_name; ?>                        
                      </td>
                      <td>
                        <?php echo $row->acc_name; ?>                        
                      </td>
                     <td>
                        <?php echo $row->supplier_group_name; ?>                        
                      </td>
                       
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
</div>
</div>


<script>
    
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

     function reg_supplier()
  {
    $('#regsupplier').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Add New')
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
    });
  }

     function reg_supplier_group()
  {
    $('#regsuppliergroup').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Add New')
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
    });
  }

    function reg_supplier_edit()
  {
    $('#regsupplier').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
      modal.find('[name="supplier_name"]').val(button.data('supplier_name'))
      modal.find('[name="reg_no"]').val(button.data('reg_no'))
      modal.find('[name="gst_no"]').val(button.data('gst_no'))
    });
  }

  function reg_supplier_group_edit()
  {
    $('#regsuppliergroup').on('show.bs.modal', function (event) {
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

function edit_user()
  {
    $('#user').on('show.bs.modal', function (event) {
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


 function delete_modal(delete_url)
  {
    $('#delete').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm Delete <<' + button.data('report') + '>>?')
    document.getElementById('url').setAttribute("href" , delete_url );
    });
  }
   


  </script>