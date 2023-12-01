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
  

    <div class="row">
    <div class="col-md-12">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">User</h3>
              <div class="box-tools pull-right">

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
                        <th><b>Module Group Description</b></th>
                        <th><b>Field</b></th>
                        <th><b>Value From</b></th>
                        <th><b>Value To</b></th>
                        <th><b>Created At</b></th>
                        <th><b>Created By</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                          foreach ($user->result() as $row)
                          { ?> 

                        <tr>
                            <td><?php echo $row->module_group_description; ?></td>
                            <td><?php echo $row->field; ?></td>
                            <td><?php echo $row->value_from; ?></td>
                            <td><?php echo $row->value_to; ?></td>
                            <td><?php echo $row->created_at; ?></td>
                            <td><?php echo $row->created_by; ?></td>
                        </tr>
                        <?php } ?>
                    
                    </tbody>
            </table>
          </form>
        </div>         
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">User Module</h3>
              <div class="box-tools pull-right">

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
                        <th><b>Module Group Description</b></th>
                        <th><b>Field</b></th>
                        <th><b>Value From</b></th>
                        <th><b>Value To</b></th>
                        <th><b>Created At</b></th>
                        <th><b>Created By</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                          foreach ($user_module->result() as $row)
                          { ?> 

                        <tr>
                            <td><?php echo $row->module_group_description; ?></td>
                            <td><?php echo $row->field; ?></td>
                            <td><?php echo $row->value_from; ?></td>
                            <td><?php echo $row->value_to; ?></td>
                            <td><?php echo $row->created_at; ?></td>
                            <td><?php echo $row->created_by; ?></td>
                        </tr>
                        <?php } ?>
                    
                    </tbody>
            </table>
          </form>
        </div>
        </div>
      </div>
    </div>
      </div>
      <div class="row">
    <div class="col-md-12">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Module</h3>
              <div class="box-tools pull-right">

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
                        <th><b>Module Group Description</b></th>
                        <th><b>Field</b></th>
                        <th><b>Value From</b></th>
                        <th><b>Value To</b></th>
                        <th><b>Created At</b></th>
                        <th><b>Created By</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                          foreach ($module->result() as $row)
                          { ?> 

                        <tr>
                            <td><?php echo $row->module_group_description; ?></td>
                            <td><?php echo $row->field; ?></td>
                            <td><?php echo $row->value_from; ?></td>
                            <td><?php echo $row->value_to; ?></td>
                            <td><?php echo $row->created_at; ?></td>
                            <td><?php echo $row->created_by; ?></td>
                        </tr>
                        <?php } ?>
                    
                    </tbody>
            </table>
          </form>
        </div>
        </div>
      </div>
    </div>
      </div>
      <div class="row">
    <div class="col-md-12">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Module Group</h3>
              <div class="box-tools pull-right">

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
                        <th><b>Module Group Description</b></th>
                        <th><b>Field</b></th>
                        <th><b>Value From</b></th>
                        <th><b>Value To</b></th>
                        <th><b>Created At</b></th>
                        <th><b>Created By</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                          foreach ($module_group->result() as $row)
                          { ?> 

                        <tr>
                            <td><?php echo $row->module_group_description; ?></td>
                            <td><?php echo $row->field; ?></td>
                            <td><?php echo $row->value_from; ?></td>
                            <td><?php echo $row->value_to; ?></td>
                            <td><?php echo $row->created_at; ?></td>
                            <td><?php echo $row->created_by; ?></td>
                        </tr>
                        <?php } ?>
                    
                    </tbody>
            </table>
          </form>
        </div>
        </div>
      </div>
    </div>
      </div>
      <div class="row">
    <div class="col-md-12">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">User Group</h3>
              <div class="box-tools pull-right">

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
                        <th><b>Module Group Description</b></th>
                        <th><b>Field</b></th>
                        <th><b>Value From</b></th>
                        <th><b>Value To</b></th>
                        <th><b>Created At</b></th>
                        <th><b>Created By</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                          foreach ($user_group->result() as $row)
                          { ?> 

                        <tr>
                            <td><?php echo $row->module_group_description; ?></td>
                            <td><?php echo $row->field; ?></td>
                            <td><?php echo $row->value_from; ?></td>
                            <td><?php echo $row->value_to; ?></td>
                            <td><?php echo $row->created_at; ?></td>
                            <td><?php echo $row->created_by; ?></td>
                        </tr>
                        <?php } ?>
                    
                    </tbody>
            </table>
          </form>
        </div>
        </div>
      </div>
    </div>
      </div>
    
</div>
</div>