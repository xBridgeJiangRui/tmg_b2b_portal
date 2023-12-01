<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
  <?php
  if($this->session->userdata('message'))
  {
    ?>
    <div class="alert alert-success text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>

  <?php
  if($this->session->userdata('warning'))
  {
    ?>
    <div class="alert alert-danger text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('warning') <> '' ? $this->session->userdata('warning') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>
  

  <div class="row">
    <div class="col-md-12">

      <!-- User@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">User</h3>

          <div class="box-tools pull-right">
          <button class="btn btn-xs btn-primary" onclick="add_user()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table id="example11" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Action</th>
                  <th>User ID</th>
                  <th>User Password</th>
                  <th>User Name</th>
                  <th>User Group</th>
                  <th>Created at</th>
                  <th>Created by</th>
                  <th>Updated at</th>
                  <th>Updated by</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($user->result() as $row)
                {
                  ?>
                   <tr>
                      <td>
                        <button title="Edit" onclick="edit_user()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#user" 
                        data-guid="<?php echo $row->user_guid?>"
                        data-name="<?php echo $row->user_name?>" 
                        data-id="<?php echo $row->user_id?>"
                        data-password="<?php echo $row->user_password?>">
                        <i class="glyphicon glyphicon-pencil"></i></button>

                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_master_setup/delete'); ?>?guid=<?php echo $row->user_guid ?>&table=set_user&col_guid=user_guid')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->user_name?>" ><i class="glyphicon glyphicon-trash"></i></button>
                      </td>
                      <td><?php echo $row->user_id?></td>
                      <td><?php echo $row->user_password?></td>
                      <td><?php echo $row->user_name?></td>
                      <td><?php echo $row->created_at?></td>
                      <td><?php echo $row->created_by?></td>
                      <td><?php echo $row->updated_at?></td>
                      <td><?php echo $row->updated_by?></td>
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
    <div class="col-md-12">

      <!--  User Module @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">User Module</h3>

          <div class="box-tools pull-right">
          <button class="btn btn-xs btn-primary" onclick="add_user_module()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

                <div class="tab-pane active" id="view1">
                 <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Action</th>
                    <th>Enable</th>
                    <th>Module Name</th>
                    <th>User Group</th>
                    <th>Created at</th>
                    <th>Created by</th>
                    <th>Updated at</th>
                    <th>Updated by</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  foreach($user_module->result() as $row)
                  {
                    ?>
                     <tr>
                        <td>
                        <button title="Edit" onclick="edit_user_module()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#usermodule" 
                        data-guid="<?php echo $row->user_module_guid?>"
                        data-user_group_name="<?php echo $row->user_group_name?>"
                        data-user_group_guid="<?php echo $row->user_group_guid?>"
                        data-module_name="<?php echo $row->module_name?>" 
                        data-module_guid="<?php echo $row->module_guid?>" 
                        data-enable="<?php echo $row->isenable?>">
                        <i class="glyphicon glyphicon-pencil"></i></button>

                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_master_setup/delete'); ?>?guid=<?php echo $row->user_module_guid ?>&table=set_user_module&col_guid=user_module_guid')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="User Module" ><i class="glyphicon glyphicon-trash"></i></button>
                      </td>
                        <td><?php echo $row->isenable?></td>
                        <td><?php echo $row->module_name?></td>
                        <td><?php echo $row->user_group_name?></td>
                        <td><?php echo $row->created_at?></td>
                        <td><?php echo $row->created_by?></td>
                        <td><?php echo $row->updated_at?></td>
                        <td><?php echo $row->updated_by?></td>
                        </tr>
                    <?php
                  }
                  ?>
                  </tbody>
                </table>
              </div>
                <!-- /.tab-pane -->
         
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. User Module @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    </div>
  </div>


  <div class="row">
    <div class="col-md-12">

      <!-- User Group@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">User Group</h3>

          <div class="box-tools pull-right">
          <button class="btn btn-xs btn-primary" onclick="add_user_group()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Action</th>
                  <th>Active</th>
                  <th>User Group Name</th>
                  <th>Created at</th>
                  <th>Created by</th>
                  <th>Updated at</th>
                  <th>Updated by</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($user_group->result() as $row)
                {
                  ?>
                   <tr>
                      <td>
                        <button title="Edit" onclick="edit_user_group()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#usergroup" 
                        data-guid="<?php echo $row->user_group_guid?>"
                        data-name="<?php echo $row->user_group_name?>" 
                        data-active="<?php echo $row->isactive?>">
                        <i class="glyphicon glyphicon-pencil"></i></button>

                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_master_setup/delete'); ?>?guid=<?php echo $row->user_group_guid ?>&table=acc_user_group&col_guid=acc_user_group_guid')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->user_group_name?>" ><i class="glyphicon glyphicon-trash"></i></button>
                      </td>
                      <td><?php echo $row->isactive?></td>
                      <td><?php echo $row->user_group_name?></td>
                      <td><?php echo $row->created_at?></td>
                      <td><?php echo $row->created_by?></td>
                      <td><?php echo $row->updated_at?></td>
                      <td><?php echo $row->updated_by?></td>
                      </tr>
                  <?php
                }
                ?>
                </tbody>
              </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. User Group@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    </div>
  </div>


</div>
</div>



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
    document.getElementById('url').setAttribute("href" , delete_url );
    });
  }


    
</script>

