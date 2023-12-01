<!-- module group modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="module" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('panda_gr/check_rejected')?>" method="POST" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="guid"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Reason</label>
                            <div class="col-md-9">
                               <select name="rejected" class="form-control">
                        <?php foreach($child_reject->result() as $cr) { ?>
                          <option><?php echo $cr->reason;?> </option>
                          <?php } ?>
                               </select>

                            </div>
                        </div>
                       
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-sm btn-primary">Confirm</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
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