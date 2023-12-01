<!--  @@@@@@@@@@@@@@@@@@@@@@ Start Register Supplier modal @@@@@@@@@@@@@@@@ -->
<div class="modal fade" id="queries" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Annoucement Content</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('CusAdmin_controller/update') ?>?mode=query" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="announcement_guid" id="announcement_guid"/> 
                            <div class="col-md-9">
                            <textarea name="content" rows="10" cols="30" class="form-control"></textarea>
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
<!-- End confirm modal modal -->
<!--  @@@@@@@@@@@@@@@@@@@@@@ Start Register Supplier modal @@@@@@@@@@@@@@@@ -->
<div class="modal fade" id="publish_detail" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Publish At</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('CusAdmin_controller/update') ?>?mode=publish" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="announcement_guid" id="announcement_guid"/> 
                        </div>
                             
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3">Publish At</label>
                              <div class="col-md-9">
                                <input name="published_date" class="form-control" type="text" required autocomplete="off" >
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
<div class="modal fade" id="sup_checklist_action" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Supplier Check List Action</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('CusAdmin_controller/action_button') ?>" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="customer_guid" id="customer_guid"/> 
                            <input type="hidden" name="supcus_guid" id="supcus_guid"/> 
                            <input type="hidden" name="code" id="code"/>   
                        </div>
                             
                        </div>

                        <div class="form-group">
                          <label class="control-label col-md-3">Supplier Name</label>
                              <div class="col-md-9">
                                <input name="sup_name" class="form-control" type="text"  autocomplete="off" > 
                              </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-md-3">Email</label>
                              <div class="col-md-9">
                                <input name="PIC" class="form-control" type="text"  autocomplete="off" > 
                              </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3">Tel</label>
                              <div class="col-md-9">
                                <input name="tel" class="form-control" type="text"  autocomplete="off" > 
                              </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3">Invoice Number</label>
                              <div class="col-md-9">
                                <input name="invoice_no" class="form-control" type="text"  autocomplete="off" > 
                              </div>
                        </div>
                         <div class="form-group">
                          <label class="control-label col-md-3">Payment Amount</label>
                              <div class="col-md-9">
                                <select id="custom_amount_select" name="PAYMENT" class="form-control" <?php if($_SESSION['user_group_name'] != 'SUPER_ADMIN') { echo "readonly"; } ?>  >
                                  <?php foreach($payment_amount->result() as $row) { ?>
                                    <option value="<?php echo $row->code ?>"><?php echo $row->reason ?></option>
                                  <?php } ?>
                                  <option value="custom_amount">Custom Amount</option>
                                </select>
                              </div>
                        </div>

                         <div class="form-group">
                          <label class="control-label col-md-3">Active</label>
                              <div class="col-md-9"> 
                                <select name="IsActive" class="form-control" <?php if($_SESSION['user_group_name'] != 'SUPER_ADMIN') { echo "readonly"; } ?>  >
                                  <option value="1">Active</option>
                                  <option value="0">Inactive</option>
                                  <option value="2">NA</option>
                                </select>
                              </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-md-3">Acceptance Form</label>
                              <div class="col-md-9"> 
                                <select name="form_a" class="form-control" <?php if($_SESSION['user_group_name'] != 'SUPER_ADMIN') { echo "readonly"; } ?>  >
                                  <option value=""></option>
                                  <option value="RECEIVED">RECEIVED</option>
                                  <option value="PENDING">PENDING</option> 
                                </select>
                              </div>
                        </div>  

                        <div class="form-group">
                          <label class="control-label col-md-3">Registration Form</label>
                              <div class="col-md-9"> 
                                <select name="REG_FORM" class="form-control" <?php if($_SESSION['user_group_name'] != 'SUPER_ADMIN') { echo "readonly"; } ?>  >
                                  <option value=""></option>
                                  <option value="RECEIVED">RECEIVED</option>
                                  <option value="PENDING">PENDING</option> 
                                </select>
                              </div>
                        </div> 

                        <div class="form-group">
                          <label class="control-label col-md-3">Training (pax)</label>
                             <div class="col-md-9">
                                <input name="training_pax" class="form-control" type="number"  autocomplete="off" > 
                              </div>
                        </div> 

                         <div class="form-group">
                          <label class="control-label col-md-3">Status</label>
                              <div class="col-md-9">
                                <select name="STATUS" class="form-control" <?php if($_SESSION['user_group_name'] != 'SUPER_ADMIN') { echo "readonly"; } ?>  >
                                  <?php foreach($payment_status->result() as $row) { ?>
                                    <option value="<?php echo $row->reason ?>"><?php echo $row->reason ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-md-3">Remark</label>
                              <div class="col-md-9">
                                <textarea id="remarkss" class="form-control" rows="4"  name="remark" readonly></textarea>
                              </div>
                        </div>
                        <!-- <div class="form-group"> -->
                          <!-- <label class="control-label col-md-3">Remark</label> -->
                              <!-- <div class="col-md-9"> -->
                                <textarea id="remark_new_line" class="form-control" rows="4"  name="remark_new_line" style="display:none"></textarea>
                              <!-- </div> -->
                        <!-- </div> -->
                        <div class="form-group">
                          <label class="control-label col-md-3">Remark</label>
                              <div class="col-md-9">
                                <textarea id="remarksss" class="form-control" rows="4"  name="remarksss" ></textarea>
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
<div class="modal fade" id="sup_hide" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Supplier Check List Action</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('CusAdmin_controller/set_hide') ?>" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="customer_guid" id="customer_guid"/> 
                            <input type="hidden" name="supcus_guid" id="supcus_guid"/> 
                            <input type="hidden" name="code" id="code"/> 
                        </div>
                             
                        </div> 
                         <div class="form-group">
                          <label class="control-label col-md-3">Active</label>
                              <div class="col-md-9">
                                <select name="IsActive" class="form-control" <?php if($_SESSION['user_group_name'] != 'SUPER_ADMIN') { echo "readonly"; } ?>  >
                                  <option value="1">Active</option>
                                  <option value="0">Inactive</option>
                                  <option value="2">NA</option>
                                </select>
                              </div>
                        </div> 

                         <div class="form-group">
                          <label class="control-label col-md-3">Acceptance Form</label>
                              <div class="col-md-9"> 
                                <select name="form_a" class="form-control" <?php if($_SESSION['user_group_name'] != 'SUPER_ADMIN') { echo "readonly"; } ?>  >
                                  <option value=""></option>
                                  <option value="RECEIVED">RECEIVED</option>
                                  <option value="PENDING">PENDING</option> 
                                </select>
                              </div>
                        </div>  

                         <div class="form-group">
                          <label class="control-label col-md-3">Registration Form</label>
                              <div class="col-md-9"> 
                                <select name="REG_FORM" class="form-control" <?php if($_SESSION['user_group_name'] != 'SUPER_ADMIN') { echo "readonly"; } ?>  >
                                  <option value=""></option>
                                  <option value="RECEIVED">RECEIVED</option>
                                  <option value="PENDING">PENDING</option> 
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
<script>
$(document).ready(function() {
      $(document).on("change","#custom_amount_select",function() {
        var value = $(this).attr('ori_value');

        details = [];

        $(this).find('option').each(function(){

          if($(this).val() != 'custom_amount')
          {
            details.push($(this).val());
          }

        });
        
        var exist = details.includes($(this).val());
        
        if(exist == false || $(this).val() == 'custom_amount')
        { 
          $(this).val('custom_amount');
          $(this).after('<input type="text" id="custom_amount_value" name="custom_amount_value_insert" class="form-control" value="'+value+'" style="margin-top:10px;"/>');
        }
        else
        {
          $('#custom_amount_value').remove();
        }

    });


});
</script>