

<!--  @@@@@@@@@@@@@@@@@@@@@@ Start Register Supplier modal @@@@@@@@@@@@@@@@ -->
<div class="modal fade" id="subscription_schedule" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Email Subscription</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('email_controller/subscription_schedule') ?>" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="customer_guid"/> 
                            <input type="hidden" name="table"/> 
                            <input type="hidden" name="mode"/> 
                            <input type="hidden" name="report_guid" id="report_guid"/> 
                            <input type="hidden" name="schedule_guid" id="schedule_guid"/> 
                        <label class="control-label col-md-3">Email<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                            <!-- <input type="text" name="supplier_name" class="form-control" required>  -->
                            <select name="email_user[]" class="form-control select2" multiple>
                                <?php foreach($email_user->result() as $row) { ?>
                                    <option value=<?php echo $row->trans_guid; ?>><?php echo $row->email ?></option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3">Report Type<span style="color:red">*</span> </label>
                             <div class="col-md-9">
                             <select name="report_type" id="report_type" class="form-control" onclick="report_guid()" >
                                <option data-report_type="" data-report_guid="" selected disabled  ><?php echo 'Select a report'; ?></option>
                                <?php foreach($report_type->result() as $row) { ?>
                                    <option 
                                    data-report_type="<?php echo $row->report_type ?>" 
                                    data-report_guid="<?php echo $row->report_guid; ?>" >
                                        <?php echo $row->report_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3">Frequency</label>
                            <div class="col-md-9">
                                <select name="day_name" id="day_name" class="form-control" style="display: none">
                                   <option  selected disabled hidden  value='Monday'><?php echo 'Select a Day'; ?></option>
                                    <option value='Monday'>Monday</option>
                                    <option value='Tuesday'>Tuesday</option>
                                    <option value='Wednesday'>Wednesday</option>
                                    <option value='Thursday'>Thursday</option>
                                    <option value='Friday'>Friday</option>
                                    <option value='Saturday'>Saturday</option>
                                    <option value='Sunday'>Sunday</option>
                                </select>

                                <select name="day_name_ever" id="day_name_ever" class="form-control" style="display: none">
                                    <option value='Everyday'>Everyday</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-md-3">Type</label>
                            <div class="col-md-9">
                                <select name="schedule_type" id="schedule_type" class="form-control" >
                                    <option value='daily'>daily</option>  
                                    <option value='weekly'>weekly</option>                
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
<!-- email schedule EDIT BUTTON -->
<div class="modal fade" id="subscription_schedule_edit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Email Subscription</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('email_controller/subscription_schedule') ?>" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="customer_guid"/> 
                            <input type="hidden" name="table"/> 
                            <input type="hidden" name="mode"/> 
                            <input type="hidden" name="report_guid" id="report_guid_edit"/> 
                            <input type="hidden" name="schedule_guid" id="schedule_guid"/> 
                        <label class="control-label col-md-3">Email<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                            <!-- <input type="text" name="supplier_name" class="form-control" required>  -->
                            <select name="email_user" class="form-control">
                                <?php foreach($email_user->result() as $row) { ?>
                                    <option value=<?php echo $row->trans_guid; ?>><?php echo $row->email ?></option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3">Report Type<span style="color:red">*</span> </label>
                             <div class="col-md-9">
                             <select name="report_type" id="report_type_edit" class="form-control" >
                                
                                <?php foreach($report_type->result() as $row) { ?>
                                    <option 
                                    data-report_type="<?php echo $row->report_type ?>" 
                                    data-report_guid="<?php echo $row->report_guid; ?>"

                                     >
                                        <?php echo $row->report_name ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3">Frequency</label>
                            <div class="col-md-9">
                                <select name="day_name" id="day_name" class="form-control" >
                                    <option value='Monday'>Monday</option>
                                    <option value='Tuesday'>Tuesday</option>
                                    <option value='Wednesday'>Wednesday</option>
                                    <option value='Thursday'>Thursday</option>
                                    <option value='Friday'>Friday</option>
                                    <option value='Saturday'>Saturday</option>
                                    <option value='Sunday'>Sunday</option>
                                </select>

                                <select name="day_name_ever" id="day_name_ever" class="form-control" style="display: none">
                                    <option value='Everyday'>Everyday</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3">Type</label>
                            <div class="col-md-9">
                                <select name="schedule_type" id="schedule_type" class="form-control" >
                                    <option value='daily'>daily</option>  
                                    <option value='weekly'>weekly</option>                
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
<script>
$("#report_type").change(function () {
     if($(this).find(':selected').data('report_type') == 'each_trans')
     {
        document.getElementById('day_name').style.display = 'none';
        document.getElementById('day_name_ever').style.display = 'block';
     }
     else
     {
        document.getElementById('day_name').style.display = 'block';
        document.getElementById('day_name_ever').style.display = 'none';
     }
     document.getElementById("report_guid").value = $(this).find(':selected').data('report_guid');
});

$("#report_type_edit").change(function () {
     document.getElementById("report_guid_edit").value = $(this).find(':selected').data('report_guid');
});

$(document).ready(function(){  
      var checkField;
      //checking the length of the value of message and assigning to a variable(checkField) on load
      checkField = $("input#report_guid").val().length;  
      var enableDisableButton = function(){         
        if(checkField > 0){
          $('#sendButton').removeAttr("disabled");
        } 
        else {
          $('#sendButton').attr("disabled","disabled");
        }
      }        
      enableDisableButton();            

      $("select#report_type").change(function(){ 
        checkField = $("input#report_guid").val().length;
        enableDisableButton();
      });
    });
</script>

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