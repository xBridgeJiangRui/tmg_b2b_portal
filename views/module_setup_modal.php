<!--@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ user modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="user" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('module_setup/user_form')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/> 
                   
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
                                <select name="user_group" class="form-control" required>
                                <option required name="user_group_name" selected data-default style="display: none;" >Select Group</option>
                                <?php
                                foreach($select_user_group->result() as $row)
                                {
                                  ?>
                                    <option required value="<?php echo $row->user_group_guid?>"><?php echo $row->user_group_name?></option>
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
                                foreach($select_module_group->result() as $row)
                                {
                                  ?>
                                    <option required selected data-default value="<?php echo $row->module_group_guid?>"><?php echo $row->module_group_name?></option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="control-label col-md-3">Active </label>
                            <div class="col-md-9">
                                <select name="active" class="form-control" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option> 
                                </select>
                                
                            </div>
                        </div>

                        <div class="form-group" >
                            <label class="control-label col-md-3">Limited Location </label>
                            <div class="col-md-9">
                                <select name="limited_location" class="form-control" required>
                                    <option value="1">TRUE</option>
                                    <option value="0">FALSE</option> 
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
                <form action="<?php echo site_url('module_setup/module_group_form')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/> 
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
                                foreach($account_module_group->result() as $row)
                                {
                                  ?>
                                    <option value="<?php echo $row->acc_module_group_guid?>"><?php echo $row->acc_module_group_name?></option>
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
                <form action="<?php echo site_url('module_setup/user_module_form')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">User Group</label>
                            <div class="col-md-9">
                                <select name="user_group" class="form-control">
                                <!-- <option name="user_group_name" selected data-default style="display: none;"  >Select User Group</option> -->
                                <?php
                                foreach($user_group->result() as $row)
                                {
                                  ?>
                                    <option value="<?php echo $row->user_group_guid?>"><?php echo $row->user_group_name?></option>
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
                                foreach($select_module_group->result() as $row)
                                {
                                  ?>
                                    <option required selected data-default value="<?php echo $row->module_group_guid?>"><?php echo $row->module_group_name?></option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module </label>
                            <div class="col-md-9">
                                <select required name="module[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                <!-- <option name="module_name" selected data-default style="display: none;"  >Select Module</option> -->
                                <?php
                                foreach($call_module->result() as $row)
                                {
                                  ?>
                                    <option  selected value="<?php echo $row->module_guid?>"><?php echo $row->module_name?></option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Enable </label>
                            <div class="col-md-9" >
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
                <form action="<?php echo site_url('module_setup/user_group_form')?>" method="POST" id="form" class="form-horizontal">
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
                            <label class="control-label col-md-3">Module Group</label>
                            <div class="col-md-9">
                                <select name="module_group_guid" class="form-control" required>
                                <?php
                                foreach($select_module_group->result() as $row)
                                {
                                  ?>
                                    <option required selected data-default value="<?php echo $row->module_group_guid?>"><?php echo $row->module_group_name?></option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Active </label>
                            <div class="col-md-9" >
                                <select name="active" class="form-control" required>
                                
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                                
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
                <form action="<?php echo site_url('module_setup/module_form')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/> 
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
                                foreach($select_module_group->result() as $row)
                                {
                                  ?>
                                    <option required selected data-default value="<?php echo $row->module_group_guid?>"><?php echo $row->module_group_name?></option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Module </label>
                            <div class="col-md-9">
                                <select required name="module[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                <!-- <option name="module_name" selected data-default style="display: none;"  >Select Module</option> -->
                                <?php
                                foreach($account_module->result() as $row)
                                {
                                  ?>
                                    <option selected style="color: red" value="<?php echo $row->acc_module_guid?>"><?php echo $row->acc_module_name?></option>
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
            <div class="modal-body" > 
                    <table style="margin-left: auto;margin-right: auto;">
                        <tr>
                            <th><h4>Created At :</h4></th>
                            <td><h4 id="created_at"><b> sfsdf</b></h4></td>
                        </tr>
                        <tr>
                            <th><h4>Created By :</h4></th>
                            <td><h4 id="created_by"><b> sfsdf</b></h4></td>
                        </tr>
                        <tr>
                            <th><h4>Updated At :</h4></th>
                            <td><h4 id="updated_at"><b> sfsdf</b></h4></td>
                        </tr>
                        <tr>
                            <th><h4>Updated By :</h4></th>
                            <td><h4 id="updated_by"><b> sfsdf</b></h4></td>
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
                <form action="<?php echo site_url('module_setup/branch_form')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/>
                    <input type="hidden" value="" name="acc_guid"/>
                    <input type="hidden" value="" name="module_group_guid"/>
                    <input type="hidden" value="" name="user_group_guid"/>
                    <input type="hidden" value="" name="isactive"/>
                    <input type="hidden" value="" name="user_id"/>
                    <input type="hidden" value="" name="user_name"/>
                    <input type="hidden" value="" name="user_password"/>
                    <input type="hidden" value="" name="created_at"/>
                    <input type="hidden" value="" name="created_by"/> 
                    <input type="hidden" value="" name="limited_location"/> 

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
                              <input type="radio" name="branch_mode" value="Branch" required>
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
                                        foreach($branch->result() as $row)
                                        {
                                          ?>
                                            <option required selected data-default value="<?php echo $row->branch_guid?>"><?php echo $row->branch_name?></option>
                                          <?php
                                        }
                                        ?>
                                    </select>
                                    </div> 
                                </div>
                            </div> -->

                            <div id="methodBranch" class="desc" style="display: none;">
                                <div class="form-group"  id="div1" >
                                <label class="control-label col-md-3">Branch</label> 
                                    <div class="col-md-9">
                                    <select name="branch[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                    <?php
                                        foreach($branch->result() as $row)
                                        {
                                          ?>
                                            <option required data-default value="<?php echo $row->branch_guid?>"><?php echo $row->branch_name.' - '.$row->branch_code.' - '.$row->branch_description?></option>
                                          <?php
                                        }
                                        ?>
                                    </select>
                                    </div> 
                                </div>
                            </div>

                            <div id="methodBranchGroup" class="desc" style="display: none;">
                                <div class="form-group"  id="div1">
                                <label class="control-label col-md-3">Branch Group</label> 
                                    <div class="col-md-9">
                                    <select name="branch_group[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                    <?php
                                        foreach($branch_group->result() as $row)
                                        {
                                          ?>
                                            <option required data-default value="<?php echo $row->branch_group_guid?>"><?php echo $row->group_name?></option>
                                          <?php
                                        }
                                        ?>
                                    </select>
                                    </div> 
                                </div>
                            </div>

                            <div id="methodConcept" class="desc" style="display: none;">
                                <div class="form-group"  id="div1" >
                                    <label class="control-label col-md-3">Concept</label> 
                                    <div class="col-md-9">
                                    <select name="concept[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                    <?php
                                        foreach($acc_concept->result() as $row)
                                        {
                                          ?>
                                            <option required data-default value="<?php echo $row->concept_guid?>"><?php echo $row->concept_name?></option>
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
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
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
                            <img src="<?php echo base_url('assets/img/ajax-loader.gif')?>">
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
                <a href="<?php echo site_url('module_setup/delete'); ?>?guid=&table=set_user_group&col_guid=user_group_guid"><button type="submit" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button></a>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" >Cancel</button>
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
            <form action="<?php echo site_url('module_setup/delete_branch')?>" method="POST" id="form" class="form-horizontal">
                <span id="preloader-delete"></span>
                <input type="text" name="branch_guid" id="mbranch_guid">
                <input type="text" name="user_guid" id="muser_guid">
                <input type="text" name="acc_guid" id="macc_guid">

                <button type="submit" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" >Cancel</button>
            </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ transfer_supplier_user_based_on_company  modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<script>
$(document).ready(function()
{
    $(document).on('change','#userid',function(){
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


    $(document).on('keypress','#userid', function(e) {
        if (e.which == 32)
            return false;
    });

});
    

</script>