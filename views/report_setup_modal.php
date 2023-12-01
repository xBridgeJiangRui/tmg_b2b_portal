<!--  @@@@@@@@@@@@@@@@@@@@@@ Start Register Supplier modal @@@@@@@@@@@@@@@@ -->
<div class="modal fade" id="create_new" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Create Detail</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('Report_controller/creat_new') ?>" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="report_guid" id="report_guid"/> 
                        </div>
                            <div class="form-group">
                              <label class="control-label col-md-3">Sequence</label>
                                <div class="col-md-9">
                                  <input name="seq" class="form-control" type="text" required maxlength="60">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3">Report Name</label>
                              <div class="col-md-9">
                                <input name="report_name" class="form-control" type="text" required maxlength="60">
                              </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3">Report Type</label>
                              <div class="col-md-9">
                                <select name="report_type" class="form-control" type="text">
                                  <option value="excel">Excel</option>
                                </select>
                              </div>
                        </div>
                         <div class="form-group">
                          <label class="control-label col-md-3">Queries</label>
                              <div class="col-md-9">
                               <textarea name="query" rows="10" cols="30" class="form-control"></textarea>
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
<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@ End Register Supplier modal @@@@@@@@@@@@@@@@@@@@@@@@@ -->

<!--  @@@@@@@@@@@@@@@@@@@@@@ Start Register Supplier modal @@@@@@@@@@@@@@@@ -->
<div class="modal fade" id="queries" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Queries</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('Report_controller/update') ?>?mode=query" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="report_guid" id="report_guid"/> 
                            <div class="col-md-9">
                            <textarea name="query" rows="10" cols="30" class="form-control"></textarea>
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
<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@ End Register Supplier modal @@@@@@@@@@@@@@@@@@@@@@@@@ -->
<!--  @@@@@@@@@@@@@@@@@@@@@@ Start Register Supplier modal @@@@@@@@@@@@@@@@ -->
<div class="modal fade" id="edit_detail" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Edit Detail</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('Report_controller/update') ?>?mode=detail" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="report_guid" id="report_guid"/> 
                        </div>
                            <div class="form-group">
                              <label class="control-label col-md-3">Sequence</label>
                                <div class="col-md-9">
                                  <input name="seq" class="form-control" type="text" required maxlength="60">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3">Report Name</label>
                              <div class="col-md-9">
                                <input name="report_name" class="form-control" type="text" required maxlength="60">
                              </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3">Report Type</label>
                              <div class="col-md-9">
                                <select name="report_type" class="form-control" type="text">
                                  <option value="excel">Excel</option>
                                </select>
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
<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@ End Register Supplier modal @@@@@@@@@@@@@@@@@@@@@@@@@ -->

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
                <a id="url" href=""><button type="submit" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-send"></i> Submit</button></a>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End confirm modal modal