<!-- Content Wrapper. Contains page content -->
<style type="text/css">
  .red {
  background-color: #DD4B39 !important;
}
</style>
<div class="content-wrapper" >
<div class="container-fluid">
<br>
 <?php
  if($this->session->userdata('message'))
  {
    ?>
    <div class="alert alert-success text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>

  <?php
  if($this->session->userdata('warning'))
  {
    ?>
    <div class="alert alert-danger text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('warning') <> '' ? $this->session->userdata('warning') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>
<?php //  echo var_dump($_SESSION); ?>
 <div class="row">
         
     <button id="asn_save_btn" class="btn btn-app"  onclick="$('#insert').submit()" >
          <i class="fa fa-save"></i> Save
        </button>
  </div>

  <!-- GRN -->
  <div class="row">
    <div class="col-md-12">
      <!-- Module  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
      <form id="insert" role="form" method="POST"  action="<?php echo site_url('CusAdmin_controller/insert_config')?>">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">GRN Configuration</h3>
           <div class="box-tools pull-right"> 
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
             
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>

        <!-- /.box-header --> 
        <div class="box-body">
              <div class="tab-pane active" id="view1"> 
                  <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Portal Live Date</label>
                      <div class="col-md-3">
                          <input class="form-control" name="portal_live_date"  type="date" value="<?php echo $get_current_settings->row('portal_live_date');?>" autocomplete="off"  >   
                      </div> 
                  </div> 
             </div> 
        </div>  
        
        <!-- /.box-header --> 
        <div class="box-body">
          <div class="tab-pane active" id="view1"> 

              <div class="form-group col-md-12">
                  <label class="control-label col-md-3">GRN e-Invoice Start Date</label>
                  <div class="col-md-3">
                      <input class="form-control" name="grn_e_invoice_start_date"  type="date" value="<?php echo $get_current_settings->row('einv_grab_date');?>" autocomplete="off"  >
                  </div> 
                  
                  <label class="control-label col-md-3">GRN e-Invoice Reminder Date</label>
                  <div class="col-md-3">
                      <input class="form-control" name="grn_reminder_start_date"  type="date" value="<?php echo $get_current_settings->row('reminder_grn_start_date');?>" autocomplete="off"  >
                  </div>
              </div> 

              <div class="form-group col-md-12">
                <label class="control-label col-md-3">GRN auto e-Invoice Status</label>
                <div class="col-md-3">
                  <select name="grn_e_invoice_status" class="form-control">
                    <option <?php if($get_current_settings->row('force_einvoice') == 1){echo 'selected ';} ?>value="1">Active</option>
                    <option <?php if($get_current_settings->row('force_einvoice') == 0){echo 'selected ';} ?>value="0">Inactive<?php $get_current_settings->row('force_einvoice');?></option>
                  </select>
                </div> 

                <label class="control-label col-md-3">GRN Auto E-Invoice (days)</label>
                <div class="col-md-3">
                  <input class="form-control" name="GRN_auto_einv_days"  type="number" value="<?php echo $get_current_settings->row('GRN_auto_einv_days') ?>" autocomplete="off"  >
                </div> 

              </div> 

              <div class="form-group col-md-12">
                <label class="control-label col-md-3">GRN e-Invoice Notification (days)</label>
                <div class="col-md-3">
                  <input class="form-control" name="GRN_einv_notification_1"  type="number" value="<?php echo $get_current_settings->row('GRN_einv_notification_1') ?>" autocomplete="off"  >
                </div> 

                <label class="control-label col-md-3">GRN e-Invoice Last Notification (days)</label>
                <div class="col-md-3">
                  <input class="form-control" name="GRN_einv_notification_2"  type="number" value="<?php echo $get_current_settings->row('GRN_einv_notification_2') ?>" autocomplete="off"  > 
                </div> 
              </div>

          </div> 
        </div>  
      </div>
    </div>
  </div> 
  
  <!-- STRB -->
  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Stock Return Batch Configuration (STRB)</h3>
        <div class="box-tools pull-right"> 
         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
      </div>
    </div>
    <!-- /.box-header --> 
    <div class="box-body">
      <div class="tab-pane active" id="view1"> 
        <div class="form-group col-md-12">
          <label class="control-label col-md-3">Stock Return Batch Start Date</label>
          <div class="col-md-3">
              <input class="form-control" name="strb_start_date"  type="date" value="<?php echo $get_current_settings->row('strb_start_date');?>" autocomplete="off"  >
          </div> 

          <label class="control-label col-md-3">STRB Auto Accept Status</label>
          <div class="col-md-3">
            <select name="strb_auto_status" class="form-control">
              <option <?php if($get_current_settings->row('force_strb') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('force_strb') == 0){echo 'selected ';} ?>value="0">Inactive<?php $get_current_settings->row('force_strb');?></option>
            </select>
          </div> 
          
        </div> 

        <div class="form-group col-md-12"  >
         <label class="control-label col-md-3">Stock Return Batch first notification (days) <br><small> 1st Reminder</small></label>
            <div class="col-md-3">
               <input class="form-control" name="RB_email_notification_1"  type="number" value="<?php echo $get_current_settings->row('RB_email_notification_1') ?>" autocomplete="off" >
            </div>

            <label class="control-label col-md-3">Stock Return Batch second notification (days) <br><small> 2nd Reminder</small></label>
            <div class="col-md-3">
               <input class="form-control" name="RB_email_notification_2"  type="number" value="<?php echo $get_current_settings->row('RB_email_notification_2') ?>" autocomplete="off" >
            </div>    
        </div>

        <div class="form-group col-md-12" >
        <label class="control-label col-md-3">Stock Return Batch auto generate DN (days) <br><small> 3rd Reminder (After next day AUTO ACCEPT)</small></label>
            <div class="col-md-3">
               <input class="form-control" name="RB_auto_gen_dn_days"  type="number" value="<?php echo $get_current_settings->row('RB_auto_gen_DN_days') ?>" autocomplete="off" >
            </div>  
        </div> 
      </div> 
    </div>  
  </div>  

  <!-- PRDN -->
  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Purchase Return Configuration (PRDN)</h3>
        
        <div class="box-tools pull-right"> 
         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
      </div>
    </div>
    <!-- /.box-header --> 
    <div class="box-body">
      <div class="tab-pane active" id="view1"> 
        <div class="form-group col-md-12">
          <label class="control-label col-md-3">Return Collection Expiry Days</label>
          <div class="col-md-3">
            <input class="form-control" name="PRDN_total_days_collect"  type="number" value="<?php echo $get_current_settings->row('PRDN_total_days_collect') ?>" autocomplete="off" >
          </div> 

          <label class="control-label col-md-3">PRDN Upload CN Status</label>
          <div class="col-md-3">
            <select name="upload_cn_setting" class="form-control">
              <option <?php if($get_current_settings->row('upload_cn_setting') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('upload_cn_setting') == 0){echo 'selected ';} ?>value="0">Inactive<?php $get_current_settings->row('upload_cn_setting');?></option>
            </select>
          </div> 
        </div> 
      </div> 
    </div>  
  </div>  
  <!-- nothing ends after -->

  <!-- CONSIGNMENT -->
  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Consignment Sales Configuration</h3>
        
        <div class="box-tools pull-right"> 
         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
      </div>
    </div>
    <!-- /.box-header --> 
    <div class="box-body">
      <div class="tab-pane active" id="view1"> 

        <div class="form-group col-md-12">
          <label class="control-label col-md-3">Consignment Sales Start Date</label>
          <div class="col-md-3">
            <input class="form-control" name="consignment_start_date"  type="date" value="<?php echo $get_current_settings->row('consignment_start_date');?>" autocomplete="off"  >
          </div> 

          <label class="control-label col-md-3">Consignment Billing Charges</label>
          <div class="col-md-3">
            <select name="consignment_billing_status" class="form-control">
              <option <?php if($get_current_settings->row('consignment_charges_billing') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('consignment_charges_billing') == 0){echo 'selected ';} ?>value="0">Inactive<?php $get_current_settings->row('consignment_charges_billing');?></option>
            </select>
          </div> 
        </div>

        <div class="form-group col-md-12">
          <label class="control-label col-md-3">Consignment Sales Flow to Backend HQ </label>
          <div class="col-md-3">
            <select name="consign_statement_flow_hq" class="form-control">
              <option <?php if($get_current_settings->row('consign_statement_flow_back') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('consign_statement_flow_back') == 0){echo 'selected ';} ?>value="0">Inactive<?php $get_current_settings->row('consign_statement_flow_back');?></option>
            </select>
          </div> 
          
          <label class="control-label col-md-3">Consignment Upload Invoice Status</label>
          <div class="col-md-3">
            <select name="upload_consign_invoice" class="form-control">
              <option <?php if($get_current_settings->row('upload_consign_invoice') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('upload_consign_invoice') == 0){echo 'selected ';} ?>value="0">Inactive<?php $get_current_settings->row('upload_consign_invoice');?></option>
            </select>
          </div> 
        </div> 

        <div class="form-group col-md-12">
          <label class="control-label col-md-3">Consignment Report B2B Status</label>
          <div class="col-md-3">
            <select name="consignment_report_b2b" class="form-control">
              <option <?php if($get_current_settings->row('consignment_report_redirect') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('consignment_report_redirect') == 0){echo 'selected ';} ?>value="0">Inactive<?php $get_current_settings->row('consignment_report_redirect');?></option>
            </select>
          </div> 
        </div> 
        
      </div> 
    </div>  
  </div>  
  <!-- nothing ends after -->

  <!-- Propose Document -->
  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Propose Document Configuration</h3>
        
        <div class="box-tools pull-right"> 
         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
      </div>
    </div>
    <!-- /.box-header --> 
    <!-- <div class="box-body">
      <div class="tab-pane active" id="view1"> 
        <div class="form-group col-md-12">
          <label class="control-label col-md-3">Propose PO</label>
          <div class="col-md-3">
            <select name="propose_po_status" class="form-control">
              <option <?php if($get_current_settings->row('propose_po') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('propose_po') == 0){echo 'selected ';} ?>value="0">Inactive</option>
            </select>
          </div> 
        </div> 
      </div> 
    </div>  -->
    
    <div class="box-body">
      <div class="tab-pane active" id="view1"> 
        <div class="form-group col-md-12">
          <label class="control-label col-md-3">Propose Doc API URL</label>
          <div class="col-md-3">
            <input class="form-control" name="propose_doc_api"  type="text" value="<?php echo $get_current_settings->row('propose_doc_api') ?>" autocomplete="off" >
          </div>  

          <label class="control-label col-md-3">Show Additional Info (Purchase Order)</label>
          <div class="col-md-3">
            <select name="show_additional_info" class="form-control">
              <option <?php if($get_current_settings->row('show_additional_info') == 0){echo 'selected ';} ?>value="0">No</option>
              <option <?php if($get_current_settings->row('show_additional_info') == 1){echo 'selected ';} ?>value="1">Yes</option>
            </select>
          </div> 
        </div>

        <div class="form-group col-md-12">
          <label class="control-label col-md-3">Block Edit Listed Cost (Purchase Order)</label>
            <div class="col-md-3">
              <select name="block_edit_cost" class="form-control">
                <option <?php if($get_current_settings->row('block_edit_cost') == 0){echo 'selected ';} ?>value="0">No</option>
                <option <?php if($get_current_settings->row('block_edit_cost') == 1){echo 'selected ';} ?>value="1">Yes</option>
              </select>
            </div> 
        </div> 
      </div> 
    </div>
  </div>  
  <!-- nothing ends after -->

  <!-- Azure Blob Storage -->
  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">E-Document Copy Configuration (Azure Blob Storage)</h3>
        
        <div class="box-tools pull-right"> 
         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
      </div>
    </div>
    <!-- /.box-header --> 
    <div class="box-body">
      <div class="tab-pane active" id="view1"> 
        <div class="form-group col-md-12">
          <label class="control-label col-md-3">E-Invoice & E-CN Invoice Status</label>
          <div class="col-md-3">
            <select name="e_document_status" class="form-control">
              <option <?php if($get_current_settings->row('e_document_copy') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('e_document_copy') == 0){echo 'selected ';} ?>value="0">Inactive</option>
            </select>
          </div> 

          <label class="control-label col-md-3">Consignment Invoice Status</label>
          <div class="col-md-3">
            <select name="consignment_document_status" class="form-control">
              <option <?php if($get_current_settings->row('consignment_document_copy') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('consignment_document_copy') == 0){echo 'selected ';} ?>value="0">Inactive</option>
            </select>
          </div> 
        </div> 
      </div> 
    </div>  
  </div>  
  <!-- nothing ends after -->

  <!-- Purchase Order -->
  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Purchase Order Configuration</h3>
        <div class="box-tools pull-right"> 
         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
      </div>
    </div>
    <!-- /.box-header --> 
    <div class="box-body">
      <div class="tab-pane active" id="view1"> 
        <div class="form-group col-md-12">
          <label class="control-label col-md-3">Accept Mandatory Status</label>
          <div class="col-md-3">
            <select name="supplier_mandatory_to_accept_po" class="form-control">
              <option <?php if($get_current_settings->row('supplier_mandatory_to_accept_po') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('supplier_mandatory_to_accept_po') == 0){echo 'selected ';} ?>value="0">Inactive</option>
            </select>
          </div> 

          <label class="control-label col-md-3">Watermark Info</label>
          <div class="col-md-3">
            <input class="form-control" name="po_report_watermark_info"  type="text" value="<?php echo $get_current_settings->row('po_report_watermark_info');?>" autocomplete="off" >
          </div>  
        </div> 
      </div> 
    </div>  
  </div>  
  <!-- nothing ends after -->

  <!-- Auto Mapping & Flow to HQ status -->
  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Mapping & Registration Flag Configuration</h3>
        <div class="box-tools pull-right"> 
         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
      </div>
    </div>
    <!-- /.box-header --> 
    <div class="box-body">
      <div class="tab-pane active" id="view1"> 
        <div class="form-group col-md-12">
          <label class="control-label col-md-3">Auto Mapping Status</label>
          <div class="col-md-3">
            <select name="auto_mapping_status" class="form-control">
              <option <?php if($get_current_settings->row('auto_mapping_status') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('auto_mapping_status') == 0){echo 'selected ';} ?>value="0">Inactive</option>
            </select>
          </div> 

          <label class="control-label col-md-3">Disabled Backend B2B Registration Flag</label>
          <div class="col-md-3">
            <select name="disabled_b2b_flag" class="form-control">
              <option <?php if($get_current_settings->row('disabled_b2b_flag') == 1){echo 'selected ';} ?>value="1">Active</option>
              <option <?php if($get_current_settings->row('disabled_b2b_flag') == 0){echo 'selected ';} ?>value="0">Inactive</option>
            </select>
          </div>   
        </div> 
      </div> 
    </div>  
  </div>  
  <!-- nothing ends after -->
  </form>

</div>
</div> 

<script> 
 $(function() {
    $('input[name="docdate"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
         locale: {
            format: 'YYYY-MM-DD'
        },
         
    }, 
  );
});

  $(function() {
    $('input[name="published_date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: true, 
        timePickerIncrement: 30,
        ampm: true,
         locale: {
            format: 'YYYY-MM-DD HH:mm:ss'
        },
         
    }, 
  );
});  
</script> 
 