<!--@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ user modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="user" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('module_setup_new/user_form') ?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid" />

                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">User ID</label>
                            <div class="col-md-9">
                                <input name="userid" id="userid" class="form-control" type="text" required maxlength="60">
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
                                <select name="user_group" class="form-control select2" required style="width: 420px;">
                                    <option required name="user_group_name" selected data-default style="display: none;">Select Group</option>
                                    <?php
                                    foreach ($select_user_group->result() as $row) {
                                    ?>
                                        <option required value="<?php echo $row->user_group_guid ?>"><?php echo $row->user_group_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module Group</label>
                            <div class="col-md-9">
                                <select name="module_group_guid" class="form-control" required>
                                    <?php
                                    foreach ($select_module_group->result() as $row) {
                                    ?>
                                        <option required selected data-default value="<?php echo $row->module_group_guid ?>"><?php echo $row->module_group_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
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

                        <div class="form-group">
                            <label class="control-label col-md-3">Limited Location </label>
                            <div class="col-md-9">
                                <select name="limited_location" class="form-control" required>
                                    <option value="1">TRUE</option>
                                    <option value="0">FALSE</option>
                                </select>

                            </div>
                        </div>

                        <div class="form-group" >
                            <label class="control-label col-md-3">Hide Admin Maintenance </label>
                            <div class="col-md-9">
                                <select name="hide_admin" class="form-control" required>
                                    <option value="1">TRUE</option>
                                    <option value="0" selected>FALSE</option> 
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
<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ End user modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ module group modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="modulegroup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('module_setup_new/module_group_form') ?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid" />
                    <div class="form-body">
                        <!-- <div class="form-group">
                        <label class="control-label col-md-3">Sequence</label>
                            <div class="col-md-9">
                                <input name="seq" class="form-control" type="number" required>
                                <span class="help-block"></span>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="control-label col-md-3">Module Group Name</label>
                            <div class="col-md-9">
                                <select name="module_group[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                    <!-- <option name="module_group_name" selected data-default style="display: none;"  >Select Module Group</option> -->
                                    <?php
                                    foreach ($account_module_group->result() as $row) {
                                    ?>
                                        <option value="<?php echo $row->acc_module_group_guid ?>"><?php echo $row->acc_module_group_name ?></option>
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
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ End module group modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->


<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ user module modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="usermodule" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('module_setup_new/user_module_form') ?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">User Group</label>
                            <div class="col-md-9">
                                <select name="user_group" class="form-control">
                                    <!-- <option name="user_group_name" selected data-default style="display: none;"  >Select User Group</option> -->
                                    <?php
                                    foreach ($user_group->result() as $row) {
                                    ?>
                                        <option value="<?php echo $row->user_group_guid ?>"><?php echo $row->user_group_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module Group</label>
                            <div class="col-md-9">
                                <select name="module_group_guid" class="form-control" required>
                                    <?php
                                    foreach ($select_module_group->result() as $row) {
                                    ?>
                                        <option required selected data-default value="<?php echo $row->module_group_guid ?>"><?php echo $row->module_group_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module </label>
                            <div class="col-md-9">
                                <button id="user_module_remove" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;">X</button>
                                <button id="user_module_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button>
                                <select required id="user_module_option" name="module[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                    <!-- <option name="module_name" selected data-default style="display: none;"  >Select Module</option> -->
                                    <?php
                                    foreach ($call_module->result() as $row) {
                                    ?>
                                        <option selected value="<?php echo $row->module_guid ?>"><?php echo $row->module_name ?></option>
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
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ End  user module modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->


<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@ user group modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="usergroup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('module_setup_new/user_group_form') ?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">User Group</label>
                            <div class="col-md-9">
                                <input name="group_name" class="form-control" type="text" required style="" maxlength="60">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module Group</label>
                            <div class="col-md-9">
                                <select name="module_group_guid" class="form-control" required>
                                    <?php
                                    foreach ($select_module_group->result() as $row) {
                                    ?>
                                        <option required selected data-default value="<?php echo $row->module_group_guid ?>"><?php echo $row->module_group_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
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

                        <!-- setup user account for supplier to selection -->
                        <div class="form-group">
                            <label class="control-label col-md-3">Account Maintenance Selection Active </label>
                            <div class="col-md-9" >
                                <select name="admin_active" class="form-control" required>
                                
                                <option value="" selected>-SELECT DATA-</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                                
                                </select>
                            </div>
                        </div>

                        <!-- setup the user group under what group default -->
                        <div class="form-group">
                            <label class="control-label col-md-3">Set Default User Group </label>
                            <div class="col-md-9" >
                                <select name="group_info_status" class="form-control" required>
                                
                                <option value="" selected>-SELECT DATA-</option>
                                <option value="0">Normal</option>
                                <option value="1">Admin Group (Self Service)</option>
                                <option value="2">Outright & Consign Group</option>
                                <option value="3">Consign Group</option>
                                
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
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@ End user group modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

<!-- module modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="module" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('module_setup_new/module_form') ?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid" />
                    <div class="form-body">
                        <!-- <div class="form-group">
                        <label class="control-label col-md-3">Sequence</label>
                            <div class="col-md-9">
                                <input name="seq" class="form-control" type="number" required>
                                <span class="help-block"></span>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="control-label col-md-3">Module Group</label>
                            <div class="col-md-9">
                                <select name="module_group_guid" class="form-control" required>
                                    <?php
                                    foreach ($select_module_group->result() as $row) {
                                    ?>
                                        <option required selected data-default value="<?php echo $row->module_group_guid ?>"><?php echo $row->module_group_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module </label>
                            <div class="col-md-9">
                                <button id="module_id_remove" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;">X</button>
                                <button id="module_id_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button>
                                <select required id="module_id" name="module[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                    <!-- <option name="module_name" selected data-default style="display: none;"  >Select Module</option> -->
                                    <?php
                                    foreach ($account_module->result() as $row) {
                                    ?>
                                        <option selected style="color: red" value="<?php echo $row->acc_module_guid ?>"><?php echo $row->acc_module_name ?></option>
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
<!-- End module modal modal -->


<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ view modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="view" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">

                <h3 class="modal-title" style="text-align: center"></h3>
            </div>
            <div class="modal-body">
                <table style="margin-left: auto;margin-right: auto;">
                    <tr>
                        <th>
                            <h4>Created At :</h4>
                        </th>
                        <td>
                            <h4 id="created_at"><b> sfsdf</b></h4>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <h4>Created By :</h4>
                        </th>
                        <td>
                            <h4 id="created_by"><b> sfsdf</b></h4>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <h4>Updated At :</h4>
                        </th>
                        <td>
                            <h4 id="updated_at"><b> sfsdf</b></h4>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <h4>Updated By :</h4>
                        </th>
                        <td>
                            <h4 id="updated_by"><b> sfsdf</b></h4>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ End view modal modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->


<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ assign branch modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="branch" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h3 class="modal-title">Branch Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('module_setup_new/branch_form') ?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid" id="branch_form_guid" />
                    <input type="hidden" value="" name="acc_guid" id="branch_form_acc_guid" />
                    <input type="hidden" value="" name="module_group_guid" id="branch_form_module_group_guid" />
                    <input type="hidden" value="" name="user_group_guid" />
                    <input type="hidden" value="" name="isactive" />
                    <input type="hidden" value="" name="user_id" />
                    <input type="hidden" value="" name="user_name" />
                    <input type="hidden" value="" name="user_password" />
                    <input type="hidden" value="" name="created_at" />
                    <input type="hidden" value="" name="created_by" />
                    <input type="hidden" value="" name="limited_location" />

                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">User ID</label>
                            <div class="col-md-9">
                                <h5 id="user_id"></h5>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">User Group</label>
                            <div class="col-md-9">
                                <h5 id="user_group"></h5>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module Group</label>
                            <div class="col-md-9">
                                <h5 id="module_group"></h5>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="radios">Branch Mode</label>
                            <div class="col-md-9">
                                <label class="radio-inline" for="radios-0">
                                    <input type="radio" id="view_branch" name="branch_mode" value="Branch" required>
                                    Branch
                                </label>
                                <label class="radio-inline" for="radios-1">
                                    <input type="radio" name="branch_mode" value="BranchGroup" required>
                                    Branch Group
                                </label>
                                <label class="radio-inline" for="radios-2">
                                    <input type="radio" name="branch_mode" value="Concept" required>
                                    Concept
                                </label>
                            </div>
                        </div>

                        <!-- <div id="methodBranch" class="descDefault" >
                                <div class="form-group"  id="div1" >
                                <label class="control-label col-md-3">Branch</label> 
                                    <div class="col-md-9">
                                    <select name="branch" class="form-control">
                                    <option required name="branch_name" selected data-default style="display: none;" >Select Group</option>
                                    <?php
                                    foreach ($branch->result() as $row) {
                                    ?>
                                            <option required selected data-default value="<?php echo $row->branch_guid ?>"><?php echo $row->branch_name ?></option>
                                          <?php
                                        }
                                            ?>
                                    </select>
                                    </div> 
                                </div>
                            </div> -->

                        <div id="methodBranch" class="desc" style="display: none;">
                            <div class="form-group" id="div1">
                                <button id="location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-right:20px;">X</button>
                                <button id="location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-right:10px;">ALL</button>
                                <label class="control-label col-md-3">Branch</label>
                                <div class="col-md-9">
                                    <select name="branch[]" id="append_branch" class="form-control select2" multiple="multiple" style="width: 100%;">
                                        <!-- <select name="branch[]" class="form-control select2" multiple="multiple" style="width: 100%;"> -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="methodBranchGroup" class="desc" style="display: none;">
                            <div class="form-group" id="div1">
                                <label class="control-label col-md-3">Branch Group</label>
                                <div class="col-md-9">
                                    <select name="branch_group[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                        <?php
                                        foreach ($branch_group->result() as $row) {
                                        ?>
                                            <option required data-default value="<?php echo $row->branch_group_guid ?>"><?php echo $row->group_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="methodConcept" class="desc" style="display: none;">
                            <div class="form-group" id="div1">
                                <label class="control-label col-md-3">Concept</label>
                                <div class="col-md-9">
                                    <select name="concept[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                        <?php
                                        foreach ($acc_concept->result() as $row) {
                                        ?>
                                            <option required data-default value="<?php echo $row->concept_guid ?>"><?php echo $row->concept_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                <button type="button" class="btn btn-sm btn-default pull-left" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ End assign branch modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ view branch modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title">
                    Branch
                </h3>
            </div>
            <div class="modal-body">

                <div id="modal-loader" style="display: none; text-align: center;">
                    <img src="<?php echo base_url('assets/img/ajax-loader.gif') ?>">
                </div>
                <h4 id="map_total"></h4>
                <!-- content will be load here -->
                <div id="dynamic-content"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!-- onClick="window.location.reload();"   -->
            </div>

        </div>
    </div>
</div><!-- /.modal -->
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ End view branch modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->


<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ confirm modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
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
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End confirm modal modal -->

<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete branch modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade small" id="delete_branch" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="modal_detail" style="text-align: center"> Confirm Delete ?</h4>
            </div>
            <div class="modal-footer" style="text-align: center">
                <span id="preloader-delete"></span>
                <a href="<?php echo site_url('module_setup_new/delete'); ?>?guid=&table=set_user_group&col_guid=user_group_guid"><button type="submit" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button></a>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End  delete branch modal -->

<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ confirm modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="UpdateAllOutlet" role="dialog">
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
                <a id="url2" href=""><button type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Update</button></a>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End confirm modal modal -->

<div class="modal fade small" id="mdelete_branch" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="modal_detail" style="text-align: center"> Confirm Delete ?</h4>
            </div>
            <div class="modal-footer" style="text-align: center">
                <form action="<?php echo site_url('module_setup_new/delete_branch') ?>" method="POST" id="form" class="form-horizontal">
                    <span id="preloader-delete"></span>
                    <input type="text" name="branch_guid" id="mbranch_guid">
                    <input type="text" name="user_guid" id="muser_guid">
                    <input type="text" name="acc_guid" id="macc_guid">

                    <button type="submit" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ transfer_supplier_user_based_on_company  modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<script>
    $(document).ready(function() {
        $(document).on('change', '#userid', function() {
            var value = $(this).val();
            withoutSpaces = value.replace(/[\t\r\n]+/g, '');
            $(this).val(withoutSpaces);
        });


        $(document).on('paste', '#userid', function(e) {
            e.preventDefault();
            // prevent copying action
            // alert(e.originalEvent.clipboardData.getData('Text'));
            var withoutSpaces = e.originalEvent.clipboardData.getData('Text');
            withoutSpaces = withoutSpaces.replace(/\t+/g, '');
            $(this).val(withoutSpaces);
            // you need to use val() not text()
        });


        $(document).on('keypress', '#userid', function(e) {
            if (e.which == 32)
                return false;
        });

        $(document).on('click', '#user_module_all', function() {
            // alert();
            $("#user_module_option option").prop('selected', true);
            $(".select2").select2();
        }); //CLOSE ONCLICK  

        $(document).on('click', '#user_module_remove', function() {
            // alert();
            $("#user_module_option option").prop('selected', false);
            $(".select2").select2();
        }); //CLOSE ONCLICK  

        $(document).on('click', '#module_id_all', function() {
            // alert();
            $("#module_id option").prop('selected', true);
            $(".select2").select2();
        }); //CLOSE ONCLICK  

        $(document).on('click', '#module_id_remove', function() {
            // alert();
            $("#module_id option").prop('selected', false);
            $(".select2").select2();
        }); //CLOSE ONCLICK  

        setTimeout(function() {
            $('#branch').attr({
                "data-backdrop": "static",
                "data-keyboard": "false"
            }); //to remove clicking outside modal for closing
        }, 300);

        $(document).on('click', '#view_branch', function() {
            var user_guid = $('#branch_form_guid').val();
            var acc_guid = $('#branch_form_acc_guid').val();
            var module_group_guid = $('#branch_form_module_group_guid').val();
            //alert(user_guid); die;
            $.ajax({
                url: "<?php echo site_url('Module_setup_new/fetch_branch_form') ?>",
                method: "POST",
                data: {
                    user_guid: user_guid,
                    acc_guid: acc_guid,
                    module_group_guid: module_group_guid
                },
                beforeSend: function() {
                    //$('.btn').button('loading');
                },
                success: function(data) {
                    json = JSON.parse(data);

                    if (json.para1 == 'false') {
                        alert(json.msg);
                        //$('.btn').button('reset');
                    } else {
                        //$('.btn').button('reset');

                        vendor = '';

                        Object.keys(json['query']).forEach(function(key) {
                            if (json['query'][key]['selected'] == '1') {
                                var selected = 'selected';
                            } else {
                                var selected = '';
                            }
                            vendor += '<option value="' + json['query'][key]['branch_guid'] + '" ' + selected + '>' + json['query'][key]['description'] + ' </option>';
                        });

                        $('#append_branch').select2().html(vendor);
                        //$('#append_branch').select2().html(json.option);

                    } //close else
                } //close success
            }); //close ajax 
        }); //close batch process

        $(document).on('click', '#location_all', function() {
            // alert();
            $("#append_branch option").prop('selected', true);
            $(".select2").select2();
            die;
        }); //CLOSE ONCLICK  

        $(document).on('click', '#location_all_dis', function() {
            // alert();
            $("#append_branch option").prop('selected', false);
            $(".select2").select2();
            die;
        }); //CLOSE ONCLICK  
    });
</script>