
<div class="modal fade" id="subscription_schedule_add" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Report Subscription</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('report_jasper_controller/subscription_report') ?>" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">

                        <input type="hidden" name="user_guid"/> 
                        <input type="hidden" name="table"/> 
                        <input type="hidden" name="mode"/> 
                        <input type="hidden" name="report_guid" id="report_guid_edit"/> 
                        <input type="hidden" name="schedule_guid" id="schedule_guid"/> 

                        <div class="form-group">
                        <label class="control-label col-md-3">Acc<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <input type="text" name="acc_name" value="" class="form-control" readOnly/>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-md-3">Supplier<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <input type="text" name="supplier_name" value="" class="form-control" readOnly/>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-md-3">User Name<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <input type="text" name="user_name" value="" class="form-control" readOnly/>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-md-3">Report<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                            <!-- <input type="text" name="supplier_name" class="form-control" required>  -->
                            <select name="report_guid[]" style="width:100%" class="select2 form-control" multiple>
                                <?php foreach($report->result() as $row) { ?>
                                    <option value=<?php echo $row->childID; ?>><?php echo $row->Description ?></option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" id="sendButton" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="subscription_schedule_edit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Edit Report Subscription</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('report_jasper_controller/edit_subscription_report') ?>" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">

                        <input type="hidden" name="user_guid"/> 
                        <input type="hidden" name="table"/> 
                        <input type="hidden" name="mode"/> 
                        <input type="hidden" name="report_guid" id="report_guid_edit"/> 
                        <input type="hidden" name="schedule_guid" id="schedule_guid"/> 
			<input type="hidden" name="acc_guid" id="acc_guid"/>

                        <div class="form-group">
                        <label class="control-label col-md-3">Acc<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <input type="text" name="acc_name" value="" class="form-control" readOnly/>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-md-3">Supplier<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <input type="text" name="supplier_name" value="" class="form-control" readOnly/>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-md-3">User Name<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <input type="text" name="user_name" value="" class="form-control" readOnly/>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-md-3">Report<span style="color:red">*</span> </label>
                            <div class="col-md-7" >
                            <!-- <input type="text" name="supplier_name" class="form-control" required>  -->
                            <select name="report_guid[]" style="width:100%"  id="xreport_guid" class="select2 form-control" multiple>
                                <?php foreach($report->result() as $row) { ?>
                                    <option value=<?php echo $row->childID; ?>><?php echo $row->Description ?></option>
                                <?php } ?>
                            </select>
                            </div>
                            <div class="col-md-2" >
                              <button type="button" name="selectall" id="selectall" class="btn btn-sm btn-primary btn_remove">All</button>
                              <button type="button" name="remove" id="btn_remove" class="btn btn-sm btn-danger btn_remove">X</button>
                            </select>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" id="sendButton" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="multiple_subscribe_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                    <h3 class="modal-title">Edit Report Subscription</h3>
            </div>
            <div class="modal-body form">
                    <form action="<?php echo site_url('report_jasper_controller/add_multiple') ?>" method="POST" id="form" class="form-horizontal">
                        <div class="form-group">
                        <label class="control-label col-md-3">Customer </label>
                            <div class="col-md-9">
                                <input type="text" id="jasper_report_customer" name="jasper_user_customer" value="" class="form-control" readonly/>
                                <input type="hidden" id="jasper_user_customer_guid" name="jasper_user_customer_guid" value=""/>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-md-3">Report<span style="color:red">*</span> </label>
                            <div class="col-md-9" id="jasper_report_list"></div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-md-3">User<span style="color:red">*</span> </label>
                            <div class="col-md-9" id="jasper_report_user"></div>
                        </div>

                        <div class="modal-footer">
                              <button type="submit" id="sendButton" class="btn btn-sm btn-primary">Save</button>
                              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                        </div>

                    </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>