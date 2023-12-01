<!--user modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="user" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('acc_master_setup/user_form')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/> 
                      <div class="form-body">
                        <div class="form-group">
                        <label class="control-label col-md-3">User ID</label>
                            <div class="col-md-9">
                                <input name="userid" class="form-control" type="text" required maxlength="60">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">User Name</label>
                            <div class="col-md-9">
                                <input name="name" class="form-control" type="text" required maxlength="60">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">User Password</label>
                            <div class="col-md-9">
                                <input name="password" class="form-control" type="password" required maxlength="15">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">User Group</label>
                            <div class="col-md-9">
                                <select name="user_group" class="form-control" required>
                                <option required name="user_group_name" selected data-default style="display: none;" >Select Group</option>
                                <?php
                                foreach($select_user_group->result() as $row)
                                {
                                  ?>
                                    <option required value="<?php echo $row->acc_user_group_guid?>"><?php echo $row->user_group_name?></option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End user form modal -->


<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@user group modal@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="usergroup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('acc_master_setup/user_group_form')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">User Group</label>
                            <div class="col-md-9">
                                <input name="group_name" class="form-control" type="text" required style="" maxlength="60">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Active </label>
                            <div class="col-md-9">
                                <select name="active" class="form-control" required>
                                
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                                
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox"> 
                                    <label>
                                    <input type="hidden" name="active" id="active" value="0" />
                                        <input type="checkbox" name="active" id="active" value="1" 
                                        </> Active
                                    </label>
                                </div>
                            </div>
                        </div> -->
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End user form modal -->


<!-- user module modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="usermodule" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('acc_master_setup/user_module_form')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">User Group</label>
                            <div class="col-md-9">
                                <select name="user_group" class="form-control">
                                <option name="user_group_name" selected data-default style="display: none;"  >Select User Group</option>
                                <?php
                                foreach($account_user_group->result() as $row)
                                {
                                  ?>
                                    <option value="<?php echo $row->acc_user_group_guid?>"><?php echo $row->user_group_name?></option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module </label>
                            <div class="col-md-9">
                                <select name="module" class="form-control">
                                <option name="module_name" selected data-default style="display: none;"  >Select Module</option>
                                <?php
                                foreach($account_module->result() as $row)
                                {
                                  ?>
                                    <option value="<?php echo $row->acc_module_guid?>"><?php echo $row->acc_module_name?></option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Enable </label>
                            <div class="col-md-9">
                                <select name="enable" class="form-control" required>
                                
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                                
                                </select>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End user module form modal -->

<!-- module modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="module" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('acc_master_setup/module_form')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/> 
                    <div class="form-body">
                        <div class="form-group">
                        <label class="control-label col-md-3">Sequence</label>
                            <div class="col-md-9">
                                <input name="seq" class="form-control" type="number" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module Code</label>
                            <div class="col-md-9">
                                <input name="code" class="form-control" type="text" required maxlength="30">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module Description</label>
                            <div class="col-md-9">
                                <input name="name" class="form-control" type="text" required maxlength="60">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module Group</label>
                            <div class="col-md-9">
                                <select name="module_group_guid" class="form-control" required>
                                <?php
                                foreach($select_module_group->result() as $row)
                                {
                                  ?>
                                    <option required selected data-default value="<?php echo $row->acc_module_group_guid?>"><?php echo $row->acc_module_group_name?></option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End user form modal -->

<!-- module group modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="modulegroup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('acc_master_setup/module_group_form')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/> 
                    <div class="form-body">
                        <div class="form-group">
                        <label class="control-label col-md-3">Sequence</label>
                            <div class="col-md-9">
                                <input name="seq" class="form-control" type="number" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module Group Name</label>
                            <div class="col-md-9">
                                <input name="name" class="form-control" type="text" required maxlength="60">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End module group modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->


<!-- confirm modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="delete" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" style="text-align: center">Confirm Delete?</h3>
            </div> -->
            <div class="modal-body">
                <h4 class="modal_alert" style="text-align: center;color: red"></h4>
                <h4 class="modal_detail" style="text-align: center"></h4>
            </div>
            <div class="modal-footer" style="text-align: center">
            <span id="preloader-delete"></span>
                <a id="url" href=""><button type="submit" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button></a>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End confirm modal modal