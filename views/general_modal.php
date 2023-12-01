<!-- CONFIRM ACCEPT -->
<div class="modal fade" id="confirm" role="dialog">
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
                <a id="url" href=""><button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Accept</button></a>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End confirm modal modal -->

<!-- CONFIRM ACCEPT -->
<div class="modal fade" id="confirm_gr" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-body">
                <h4 class="modal_detail" style="text-align: center"></h4>
            </div>
            <div class="modal-footer" style="text-align: center">
            <span id="preloader-delete"></span>
                <a id="url_confirm" class="btn btn-sm btn-success" href=""> <i class="fa fa-check"></i> Confirm </a>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End confirm modal modal -->

<!-- REJECT ACCEPT -->
<div class="modal fade" id="reject_old" role="dialog">
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
                <a id="url_old" href=""><button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Reject</button></a>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End confirm modal modal -->

<!-- REJECT ACCEPT -->
<div class="modal fade" id="reject" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" style="text-align: center">Confirm Delete?</h3>
            </div> -->

            <div class="modal-body form">

                <h4 class="modal_detail" style="text-align: center"></h4>

                <form action="<?php echo site_url('General/reject') ?>" method="POST" id="url2" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="refno" value=''> 
                            <input type="hidden" name="customer_guid" value=''> 
                            <input type="hidden" name="table" value=''> 
                            <input type="hidden" name="col_guid" value=''> 
                            <input type="hidden" name="loc" value=''> 

                        <label class="control-label col-md-3">Reason <span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <select name="reason" class="form-control" required>
                                <?php
                                foreach($set_code->result() as $row)
                                {
                                  ?>
                                    <option data-default value="<?php echo $row->code ?>"><?php echo $row->reason?>
                                    </option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                         
                    </div>
                  </div>
            <div class="modal-footer" style="text-align: center">
            <span id="preloader-delete"></span>

                 <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Reject</button> 
              </form>   
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End confirm modal modal -->

<!-- HIDE SUPPLIER ACCEPT -->
<div class="modal fade" id="otherstatus" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body form">

                <h4 class="modal_detail" style="text-align: center"></h4>

                <form action="<?php echo site_url('general/otherstatus') ?>" method="POST" id="url23" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="refno" value=''> 
                            <input type="hidden" name="loc" value=''> 

                        <label class="control-label col-md-3">Reason <span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <select name="reason" class="form-control">
                                <?php
                                foreach($set_admin_code->result() as $row)
                                {
                                  ?>
                                    <option data-default value="<?php echo $row->code ?>"><?php echo $row->reason?>
                                    </option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                         
                    </div>
                  </div>
            <div class="modal-footer" style="text-align: center">
            <span id="preloader-delete"></span>

                 <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-paper-plane"></i> Submit</button> 
              </form>   
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End confirm modal modal -->

<!-- CHECK VIEW IN OTHERS-->
<div class="modal fade" id="viewothers" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form">
                <h4 class="modal_detail" style="text-align: center"></h4>
                      <div class="form-body">
                        <div class="form-group">
                        <label class="control-label col-md-3">Reason <span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <input type="hidden" name="loc">
                                <select name="reason" id="reason" class="form-control" required>
                                <?php
                                foreach($set_admin_code->result() as $row)
                                {
                                  ?>
                                    <option data-default value="<?php echo $row->code ?>"><?php echo $row->reason?>
                                    </option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                         
                    </div>
                  </div>
            <div class="modal-footer" style="text-align: center">
            <span id="preloader-delete"></span>
             <button type="submit" id="javascript_para" class="btn btn-primary" style="margin-top: 5px;"  
               onclick="ahsheng()"
                >Search </button>  
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- REJECT ACCEPT -->
<div class="modal fade" id="reject_rc" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" style="text-align: center">Confirm Delete?</h3>
            </div> -->

            <div class="modal-body form">

                <h4 class="modal_detail" style="text-align: center"></h4>

                <form action="<?php echo site_url('General/reject') ?>" method="POST" id="url2" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="refno" value=''> 
                            <input type="hidden" name="customer_guid" value=''> 
                            <input type="hidden" name="table" value=''> 
                            <input type="hidden" name="col_guid" value=''> 
                            <input type="hidden" name="loc" value=''> 

                        <label class="control-label col-md-3">Reason <span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <select name="reason" class="form-control" required>
                                <?php
                                foreach($set_code->result() as $row)
                                {
                                  ?>
                                    <option data-default value="<?php echo $row->reason ?>"><?php echo $row->reason?>
                                    </option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                         
                    </div>
                  </div>
            <div class="modal-footer" style="text-align: center">
            <span id="preloader-delete"></span>

                 <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Reject</button> 
              </form>   
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End confirm modal modal -->

<!-- End confirm modal modal -->