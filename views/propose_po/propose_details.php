<style>

@keyframes blink-button {
  0% { opacity: 1; }
  50% { opacity: 0; }
  100% { opacity: 1; }
}

.blinking-button {
  animation: blink-button 1s infinite;
}

#floatingButton {
  position: fixed;
  top: 60px; /* Adjust the top distance as needed */
  left: 50%;
  transform: translateX(-50%);
  z-index: 9999; /* Ensure the button appears above other elements if necessary */
}

.highlighted-row {
    background-color: #c1e7c1; /* Green color for highlighting */
}

.content-wrapper{
  min-height: 850px !important; 
}

.alignright {
  text-align: right;
}

.aligncenter{
  text-align: center;
}

.alignleft
{
  text-align: left;
}

.to_update_cost {
  text-align: right;
}

.modal-lg
{
  width: 80%;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice
{
  background: #3c8dbc;
}  

.selected_location
{
  background: #3c8dbc;
  border: 1px solid #aaa;
  border-radius: 4px;
  cursor: default;
  float: left;
  margin-right: 5px;
  margin-top: 5px;
  padding: 0 5px;
  color: white;
}  

#table1 th {
  text-align: left;
}

#table2 th {
  text-align: left;
}

#table3 th {
  text-align: left;
}

#edit_table th {
  text-align: left;
}

/* .sorting::after,
.sorting_asc::after,
.sorting_desc::after {
  display: none !important;
  content: none;
} */

.dataTables_wrapper .sorting,
.dataTables_wrapper .sorting_asc,
.dataTables_wrapper .sorting_desc {
  background-image: none !important;
  background-repeat: no-repeat !important;
  padding-right: 3px !important;
}

.sorting::after,
.sorting_asc::after,
.sorting_desc::after {
  display: none !important;
  content: none;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
    
/* Disable the default styling */
input[type="number"] {
  -moz-appearance: textfield;
}

.custom-tabs {
  display: flex;
  border-bottom: 1px solid #ccc;
  padding: 0px;
}

.custom-tab {
  padding: 8px 15px;
  cursor: pointer;
  background-color: #f0f0f0;
  border: 1px solid #ccc;
  border-bottom: none;
  border-radius: 5px 5px 0 0;
  margin-right: 5px;
}

.custom-tab.active {
  background-color: white;
  border-bottom: none;
  margin-bottom: -1px;
}

.my-swal-header {
  font-size: 12px;
}

.my-swal-text {
  font-size: 18px;
  color: #333;
}

.custom-swal-modal .swal2-popup {
  width: 40vw;
}

.column-no-wrap {
  white-space: nowrap;
}

.column-wrap-2-line {
  max-height: 2.4em;
  /* overflow: hidden; */
  /* position: relative; */
}

.input-error {
  animation: blink 1s infinite; /* Adjust animation duration as needed */
  border: 2px solid transparent; /* Start with no border */
}

@keyframes blink {
  0% {
    border: 2px solid transparent;
  }
  50% {
    border: 2px solid red; /* Adjust the border style as needed */
  }
  100% {
    border: 2px solid transparent;
  }
}

</style>

<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>
  <div class="col-md-12">

    <a class="btn btn-app no_hide" href="<?php echo site_url('Propose_po/propose_record') ?>" style="color:grey" title="All">
      <i class="fa fa-list"></i> View Proposed Doc 
    </a>

    <a class="btn btn-app pull-right hidden" id="propose_btn_header" style="background-color: #00a65a; color:white">
      <i class="fa fa-check"></i> Submit 
    </a>

    <a class="btn btn-app pull-right no_hide" id="btn_create_new" style="color:#000000">
      <i class="fa fa-plus-circle"></i> Create New
    </a>

    <!-- <?php if($_SESSION['user_group_name'] == 'SUPER_ADMIN'){ ?>
      <a class="btn btn-app pull-right" data-toggle="modal" data-target="#modal_setting" style="color:#000000">
        <i class="fa fa-gear"></i> Setting
      </a>
    <?php } ?> -->

  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <!-- head -->
        <div class="box-header with-border">
          <h3 class="box-title">Proposed Purchase Order</h3><?php echo ($doc_status == 'CANCELLED') ? '<a class="btn btn-sm btn-danger no_hide blinking-button" style="margin-left: 1vw"> <b>This Document has been Cancelled</b></a>' : '' ?><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- head -->
        <!-- body -->
        <div class="box-body">
        <div class="col-md-12">

            <div class="row">

              <div class="col-md-1"><b>Retailer</b></div>
              <div class="col-md-5">
                <input type="text" value="<?php echo $retailer->row('acc_name'); ?>" readonly class="form-control pull-right">
                <input  id="retailer" name="retailer" type="text" value="<?php echo $retailer->row('acc_guid'); ?>" hidden>
              </div>

              <div class="col-md-1"><b>Ref No</b></div>
              <div class="col-md-2">
                <input type="text" id="doc_refno" name="doc_refno" value="<?php echo isset($_GET['id']) ? $po_refno : '' ?>" readonly class="form-control pull-right" placeholder="NEW[]">
              </div>

              <div class="col-md-1"><b>Created By</b></div>
              <div class="col-md-2">
                <input type="text" id="created_by" name="created_by" value="<?php echo isset($_GET['id']) ? $created_by : '' ?>" readonly class="form-control pull-right">
              </div>

              <div class="clearfix"></div><br>

            </div> 

            <div class="row">

              <div class="col-md-1"><b>Supplier</b></div>

              <div class="col-md-5">
                <select id="outright_code" name="outright_code[]" class="form-control select2" required <?php echo isset($_GET['id']) ? 'disabled' : ''; ?>>
                  <option value="">Please Select One Code</option> 
                  <?php foreach($code as $row){ ?>
                    <option value="<?php echo $row->code ?>||<?php echo $row->name ?>" <?php echo (isset($_GET['id']) && $row->code == $sup_code && $row->name == $sup_name) ? 'selected' : '' ; ?>> 
                      <?php echo $row->code.' - '.$row->name; ?>
                    </option>
                <?php } ?>
                </select>
              </div>
              
              <div class="col-md-1"><b>Delivery Date</b></div>
              <div class="col-md-2">

                <?php if(isset($_GET['id'])){ ?>
                  <input type="datetime" id="delivery_date" name="delivery_date" autocomplete="off" placeholder="yyyy-mm-dd" value="<?php echo date_format($delivery_date, 'Y-m-d D') ?>" class="form-control pull-right">
                <?php }else{ ?>
                  <input type="datetime" id="delivery_date" name="delivery_date" autocomplete="off" placeholder="yyyy-mm-dd" class="form-control pull-right" disabled>
                <?php } ?>
              </div>

              <div class="col-md-1"><b>Created At</b></div>
              <div class="col-md-2">
                <input type="text" id="created_at" name="created_at" value="<?php echo isset($_GET['id']) ? $created_at : '' ?>" readonly class="form-control pull-right">
              </div>

              <div class="clearfix"></div><br>

            </div>  

            <div class="row">

              <div class="col-md-1"><b>Outlet</b></div>
              <div class="col-md-5">
                <select id="outright_location" name="outright_location[]" class="form-control select2" required disabled>
                  <option value="">Please Select One Outlet</option> 
                  <?php foreach($location as $row){ ?>
                    <option value="<?php echo addslashes($row->BRANCH_CODE) ?>" <?php echo (isset($_GET['id']) && strpos($row->branch_description, $location_list[0]) !== false) ? 'selected' : '' ; ?>>
                      <?php echo $row->BRANCH_CODE ?> | <?php echo $row->branch_description ?>
                    </option>
                  <?php } ?>
                </select>
              </div>

              <div class="col-md-1"><b>Remark</b></div>
              <div class="col-md-5">
                <textarea name="remark_h" style="height: 10vh; width: 36vw;"><?php echo isset($_GET['id']) ? $remark_h : '' ?></textarea>
              </div>

              <div class="clearfix"></div><br>

            </div> 
            
            <!-- on 05/09/2023, Ms Loo asked to only live with one outlet selection first, to avoid confusion. -->
            <?php if(1 == 0){ ?>
              <div class="row location_select_option1 <?php echo isset($_GET['id']) ? 'hidden' : '' ?>">

                <div class="col-md-1"><b>Outlet</b></div>
                <div class="col-md-4">
                  <select id="outright_location" name="outright_location[]" class="form-control select2" multiple>
                    <?php foreach($location as $row){ ?>
                      <option value="<?php echo addslashes($row->BRANCH_CODE) ?>"><?php echo $row->BRANCH_CODE ?> | <?php echo $row->branch_description ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-1">
                  <button id="outright_location_all" class="btn btn-primary" >ALL</button>
                  <button id="outright_location_all_dis" class="btn btn-danger" >X</button>
                </div>

                <div class="col-md-1"><b>Remark</b></div>
                <div class="col-md-5">
                  <textarea name="remark_h" style="height: 10vh; width: 36vw;"><?php echo isset($_GET['id']) ? $remark_h : '' ?></textarea>
                </div>

                <div class="clearfix"></div><br>
                      
              </div> 
              
              <?php if(isset($_GET['id'])){ ?> 
                <div class="row location_select_option2">

                  <div class="col-md-1"><b>Outlet</b></div>
                  <div class="col-md-5">

                    <select class="form-control select2 select2-container--default select2-container--disabled" multiple disabled>
                      <?php foreach($location_list as $row){ ?>
                        <?php
                          $detail = explode(" - ",$row);
                          $outlet_code = $detail[0];
                          $outlet_name = $detail[1];
                        ?>
                        <option selected value="<?php echo addslashes($outlet_code) ?>"><?php echo $outlet_code ?> | <?php echo $row ?></option>
                      <?php } ?>
                    </select>
                    
                  </div>

                  <div class="col-md-1"><b>Remark</b></div>
                  <div class="col-md-5">
                    <textarea name="remark_h" style="height: 10vh; width: 36vw;"><?php echo isset($_GET['id']) ? $remark_h : '' ?></textarea>
                  </div>

                  <div class="clearfix"></div><br>
                        
                </div> 
              <?php } ?>
            <?php } ?>
            <!-- on 05/09/2023, Ms Loo asked to only live with one outlet selection first, to avoid confusion. -->

            <div class="row">
              <div class="col-md-2">
                <a class="btn btn-success" id="search_data">Create</a>
              </div>
            </div>      

          </div>
        </div>
        <!-- body -->
      </div>
    </div>
  </div>

  <div class="row table_list_by_item">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <!-- <h3 class="box-title"> -->
            <!-- Item Details<span class="add_branch_list"></span>
            <a class="btn btn-sm btn-secondary hidden" id="btn_list_by_outlet">
              <i class="fa fa-th-list"></i> List By Outlet 
            </a> -->

            <!-- <ul class="nav nav-tabs">
              <li class="nav-item nav_list_by_item">
                <a class="nav-link btn_list_by_item" data-toggle="tab" href="#tab1">Item Details</a>
              </li>
              <li class="nav-item nav_list_by_outlet">
                <a class="nav-link btn_list_by_outlet" data-toggle="tab" href="#tab2">Outlet Details</a>
              </li>
            </ul> -->

            <div class="custom-tabs">
              <div class="custom-tab btn_list_by_item active">Item Details</div>
              <div class="custom-tab btn_list_by_outlet">Outlet Details</div>
            </div>
            </br>
          <!-- </h3> -->
          <a class="btn btn-sm btn-warning pull-right hidden" id="btn_view_cost_summary">
            <i class="fa fa-list"></i> View Cost Summary
          </a>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="height: 650px; overflow-y: auto;" >

          <table id="table1" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead style="white-space: nowrap;"> <!--style="white-space: nowrap;"-->
            <tr>
              <th>Action</th>
              <th>Item Code </br>Item Link</th>
              <th>Barcode </br>Article No</th>
              <th>Description</th>
              <th>PS </br>UM</th>
              <th>Order Lot </br>Ctn Qty</th>
              <th>Order Qty </br>FOC Qty</th> 
              <th>Total Qty</th>
              <th>Total Amount</th>
              <th>Net Cost</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="row table_list_by_outlet">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <!-- <h3 class="box-title">Outlet Details<span class="add_branch_list"></span></h3>
          <a class="btn btn-sm btn-secondary hidden" id="btn_list_by_item">
            <i class="fa fa-th-list"></i> List By Item 
          </a> -->

          <!-- <ul class="nav nav-tabs">
            <li class="nav-item nav_list_by_item">
              <a class="nav-link btn_list_by_item" data-toggle="tab" href="#tab1">Item Details</a>
            </li>
            <li class="nav-item nav_list_by_outlet">
              <a class="nav-link btn_list_by_outlet" data-toggle="tab" href="#tab2">Outlet Details</a>
            </li>
          </ul> -->

          <div class="custom-tabs">
            <div class="custom-tab btn_list_by_item">Item Details</div>
            <div class="custom-tab btn_list_by_outlet">Outlet Details</div>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="height: 650px; overflow-y: auto;" >

          <table id="table2" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead style="white-space: nowrap;"> <!--style="white-space: nowrap;"-->
            <tr>
              <th>Action</th>
              <th>Outlet</th>
              <th>Total Proposed Qty</th>
              <th>Total Proposed Amt</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="row table_single_outlet">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Item Details<span class="add_branch_list"></span></h3>

          <a class="btn btn-sm btn-warning pull-right hidden" id="btn_view_cost_summary_single_outlet">
            <i class="fa fa-list"></i> View Cost Summary 
          </a>

          <a class="btn btn-sm btn-primary pull-right hidden" id="btn_print_document" style="margin-right: 1vw;">
            <i class="fa fa-print"></i> Print
          </a>

          <!-- <a class="btn btn-sm btn-primary pull-right" id="btn_print_detail_single_outlet" style="margin-right: 10px;">
            <i class="fa fa-print"></i> Print 
          </a> -->
        </div>
        <div id="previous_propose_indication" style="position: absolute; right: 35vw;" class="hidden"><span style="color: red; font-weight: bold; font-style: italic;">** Indicates previous propose quantity</span></div>
        <!-- /.box-header -->
        <div class="box-body" style="height: 650px; overflow-y: auto;" >
          <table id="table3" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead style="white-space: nowrap;"> <!--style="white-space: nowrap;"-->
            <tr>
              <th rowspan="2" colspan="1">Item Code </br>Item Link</th>
              <th rowspan="2" colspan="1">Barcode </br>Article No</th>
              <th rowspan="2" colspan="1">Description</th>
              <th rowspan="2" colspan="1" title="Pack Size 
              Unit of Measurement">PS </br>UM</th>
              <th rowspan="2" colspan="1" title="Order Lot Size 
              Carton Quantity">Order Lot </br>Ctn Qty</th>
              <!-- <th rowspan="1" colspan="2">Previous Propose</th> -->
              <th style="border: 1px solid #A9A9A9;" rowspan="1" colspan="6"><center>Supplier Propose</center></th>
              <th rowspan="2" colspan="1" title="Quantity On Hand">QOH</th>
              <th rowspan="2" colspan="1" title="Days On Hand">DOH</th>
              <th rowspan="2" colspan="1" title="To Be Return">TBR</th>
              
              <?php if($show_info){ ?>
                
                <th rowspan="2" colspan="1" title="Average Daily Sold">ADS</th>
                <th rowspan="2" colspan="1" title="Average Weekly Sold">AWS</th>
                <th rowspan="2" colspan="1" title="Average Monthly Sold">AMS</th>
                <!-- <th rowspan="2" colspan="1">OPN</th>
                <th rowspan="2" colspan="1">GRN</th>
                <th rowspan="2" colspan="1">SOLD</th>
                <th rowspan="2" colspan="1">OTHERS</th> -->
              <?php } ?>

            </tr>
            <tr>
              <!-- <th title="Quantity">Qty</th>
              <th title="Free of Charge">FOC</th> -->
              <th style="border: 1px solid #A9A9A9;">Bulk</th>
              <th style="border: 1px solid #A9A9A9;" title="Quantity">Qty</th>
              <th style="border: 1px solid #A9A9A9;" title="Free of Charge">FOC</th>
              <th style="border: 1px solid #A9A9A9;">Cost</th>
              <th style="border: 1px solid #A9A9A9">Amount</th>
              <th style="border: 1px solid #A9A9A9;" title="Total Quantity">Total Qty</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          

        </div>
      </div>
    </div>
  </div>

</div>
</div>

<!-- <div class="modal fade" id="modal_setting" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?php echo site_url('CusAdmin_controller/manual_guide_mapping_to_retailer')?>" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
            <h4>Display Additional Info</h4>        
        </div>
        <div class="modal-body" style="display: inline-block;">
          <div class="col-md-12">
            <label>Supplier <span class="pull-right" style="position: absolute; padding-left: 2vw; top: -2px;">Apply to supplier <input type="checkbox" id="apply_to_all_supplier"></label>
            <select name="selected_supplier" id="selected_supplier" class="form-control">
              <option value="">Please Select One Supplier</option> 
              <?php foreach($code as $row){ ?>
                <option value="<?php echo $row->supcus_guid ?>"> 
                  <?php echo $row->code.' - '.$row->name; ?>
                </option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-12">
            <span id="selected-column"></span>
          </div>
        </div>
        <div class="modal-footer">
          <p class="full-width">
            <span class="buttons pull-left">
              <input type="button" value="No, Cancel" data-dismiss="modal">
            </span>
            <span class="buttons pull-right">
              <input type="submit" value="Yes, Do it!" class="" name="submit">
            </span>
          </p>
        </div>
      </form>
    </div>
  </div>
</div> -->

<div id="modalViewSummary" class="modal" role="dialog">
  <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">          
          <h3 class="modal-title">Cost Summary<button type="button" id="btn_close_modal1" class="close" data-dismiss="modal">×</button></h3>
        </div>
        <div class="modal-body">
          <div class="box-body">
            <div class="row" style="overflow:auto;">
              <div id="tableViewSummary"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="button" id="btn_close_modal2" class="btn btn-default" data-dismiss="modal" value="Close">
        </div>
      </div>
  </div>
</div>

<div class="modal" id="propose_medium_by_outlet-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                  <b>Item Details</b>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </h3>
                
            </div>
            <div class="modal-body">
                <table id="edit_table_by_outlet" class="table table-bordered table-striped"></table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div id="modalPrintDocument" class="modal" role="dialog" data-keyboard="false" data-backdrop ="static">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" id="close_modalPrintDocument1" name="close_modalPrintDocument1" class="close">×</button>    
          <h3 class="modal-title">Document</h3>
        </div>
        <div class="modal-body">
          <div class="box-body">
            <div class="col-md-12">
              <div class="col-md-12"  style="max-height: 70vh;overflow-x:auto;overflow-y:auto"> 
                <div id="accconceptCheck">
                  <div id="embed_loader" class="loader"></div>
                  <embed id="embed_document" height="1000px" width="100%"></embed>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <p class="full-width">
            <span class="pull-right">
              <input type="button" id="close_modalPrintDocument2" name="close_modalPrintDocument2" class="btn btn-default" value="Close"> 
            </span>
          </p>
        </div>
      </div>
  </div>
</div>

<button id="floatingButton" class="btn btn-xs hidden"><i class="fa fa-check-circle"></i></button>
<div id="loader_div" class="se-pre-con hidden"></div>

<script>
$(document).ready(function() {

  // $('#selected_retailer').change(function() {

  //   var supplier = $("#selected_supplier").val();

  //   $.ajax({
  //     url : "<?php echo site_url('Propose_po/setting_column_list');?>",
  //     dataType: 'html',
  //     method: "POST",
  //     data:{retailer:retailer},
  //     success: function(html) {         
  //       $('#selected-column').html(html);
  //     },
  //     error: function(xhr, ajaxOptions, thrownError) {
  //       alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
  //     }
  //   });

  // });

  var load_table = "<?php echo isset($_GET['id']) ? 1 : 0; ?>";
  var outright_location = $('#outright_location').val();
  var show_info = "<?php echo $show_info; ?>";
  var block_cost = "<?php echo $block_cost; ?>";
  var outlet_count = "<?php echo isset($_SESSION['outlet_count']) ? $_SESSION['outlet_count'] : 1; ?>";

  // on 05/09/2023, Ms Loo asked to only live with one outlet selection first, to avoid confusion.
  $('.table_list_by_item').addClass('hidden');
  $('.table_list_by_outlet').addClass('hidden');
  $('.table_single_outlet').removeClass('hidden');

  // if(outright_location == '' || outright_location == null){

  //   if(outlet_count == 1){
  //     $('.table_list_by_item').addClass('hidden');
  //     $('.table_list_by_outlet').addClass('hidden');
  //     $('.table_single_outlet').removeClass('hidden');
  //   }else{
  //     $('.table_list_by_item').removeClass('hidden');
  //     $('.table_list_by_outlet').addClass('hidden');
  //     $('.table_single_outlet').addClass('hidden');
  //   }

  // }else{
  //   if(outright_location.length > 1){
  //     $('.table_list_by_item').removeClass('hidden');
  //     $('.table_list_by_outlet').addClass('hidden');
  //     $('.table_single_outlet').addClass('hidden');
  //   }else if(outright_location.length == 0){
  //     $('.table_list_by_item').removeClass('hidden');
  //     $('.table_list_by_outlet').addClass('hidden');
  //     $('.table_single_outlet').addClass('hidden');
  //   }else{
  //     $('.table_list_by_item').addClass('hidden');
  //     $('.table_list_by_outlet').addClass('hidden');
  //     $('.table_single_outlet').removeClass('hidden');
  //   }
  // }

  $('#table1').DataTable({
    "columnDefs": [{"targets": '_all' ,"orderable": false}],
    "order": [],
    "sScrollY": "30vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    dom: "<'row'<'col-sm-2 remove_padding_right 'l > <'col-sm-10' f>  " + "<'col-sm-1' <'toolbar_list'>>>" +'rt<"row" <".col-md-4 remove_padding" i>  <".col-md-8" p> >',
    "language": {
      "lengthMenu": "Display _MENU_",
      "infoEmpty": "No records available",
      "infoFiltered": "(filtered from _MAX_ total records)",
      "info":           "Show _START_ - _END_ of _TOTAL_ entry",
      "zeroRecords": "<?php echo '<b>No Record Found. Please Select filtering to view data.</b>'; ?>",
    },
    "pagingType": "simple_numbers",
  });

  $('#table2').DataTable({
    "columnDefs": [{"targets": '_all' ,"orderable": false}],
    "order": [],
    "sScrollY": "30vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    dom: "<'row'<'col-sm-2 remove_padding_right 'l > <'col-sm-10' f>  " + "<'col-sm-1' <'toolbar_list'>>>" +'rt<"row" <".col-md-4 remove_padding" i>  <".col-md-8" p> >',
    "language": {
      "lengthMenu": "Display _MENU_",
      "infoEmpty": "No records available",
      "infoFiltered": "(filtered from _MAX_ total records)",
      "info":           "Show _START_ - _END_ of _TOTAL_ entry",
      "zeroRecords": "<?php echo '<b>No Record Found. Please Select filtering to view data.</b>'; ?>",
    },
    "pagingType": "simple_numbers",
  });

  $('#table3').DataTable({
    "columnDefs": [{"targets": '_all' ,"orderable": false}],
    "order": [],
    "sScrollY": "30vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    dom: "<'row'<'col-sm-2 remove_padding_right 'l > <'col-sm-10' f>  " + "<'col-sm-1' <'toolbar_list'>>>" +'rt<"row" <".col-md-4 remove_padding" i>  <".col-md-8" p> >',
    "language": {
      "lengthMenu": "Display _MENU_",
      "infoEmpty": "No records available",
      "infoFiltered": "(filtered from _MAX_ total records)",
      "info":           "Show _START_ - _END_ of _TOTAL_ entry",
      "zeroRecords": "<?php echo '<b>No Record Found. Please Select filtering to view data.</b>'; ?>",
    },
    "pagingType": "simple_numbers",
  });

  $('.remove_padding_right').css({'text-align':'left'});
  $("div.remove_padding").css({"text-align":"left"});

  $("#outright_code").change(function() {

    var selectedSupplier = $(this).val();

    $.ajax({
      type: "POST",
      url: "<?php echo site_url('Propose_po/get_active_outlet');?>",
      data: { supplier: selectedSupplier },
      dataType: "json",
      beforeSend : function() {
        $('.btn').button('loading');

        $("#outright_location").empty();

        $("#outright_location").append($("<option>", {
          value: "",
          text: "Loading..."
        }));
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success: function(response) {

        if(response.status == true){

          $("#outright_location").empty();
          $("#outright_location").prop("disabled", false);

          $("#outright_location").append($("<option>", {
            value: "",
            text: "Please Select One Outlet"
          }));

          $.each(response['results'], function(index, option) {
            $("#outright_location").append($("<option>", {
              value: option.BRANCH_CODE,
              text: option.BRANCH_CODE + " | " + option.branch_description
            }));
          });

        }
      },
      error: function() {
        console.log("Error fetching active outlets");
      }
    });

    $.ajax({
      type: "POST",
      url: "<?php echo site_url('Propose_po/get_delivery_date');?>",
      data: { supplier: selectedSupplier },
      dataType: "json",
      beforeSend : function() {
        $('.btn').button('loading');
        $("#delivery_date").val("Loading...");
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success: function(response) {
        
        if(response.status == true){
          $("#delivery_date").prop("disabled", false);
          $("#delivery_date").val(response.delivery_date);

          $('input[name="delivery_date"]').daterangepicker({
            startDate: response.delivery_date,
            locale: {
              format: 'YYYY-MM-DD  ddd'
            },
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: true,
          });
        }

      },
      error: function() {
        console.log("Error fetching delivery terms");
      }
    });
  });

  $(document).on('click','#btn_create_new',function(){

    $('.btn').button('loading');
    var urlWithoutParameters = window.location.pathname;
    window.location.href = urlWithoutParameters;

    // $('#propose_btn_header').addClass('hidden');
    // $('#btn_view_cost_summary').addClass('hidden');
    // // $('.btn_list_by_outlet').addClass('hidden');
    // // $('.btn_list_by_item').addClass('hidden');
    // $('.location_select_option2').addClass('hidden');
    
    // $('#search_data').removeClass('hidden');
    // $('#outright_location_all').removeClass('hidden');
    // $('#outright_location_all_dis').removeClass('hidden');
    // $('.location_select_option1').removeClass('hidden');

    // $('#outright_code').prop('readonly', false);
    // $('#outright_location').prop('readonly', false);

    // $('#outright_code').prop('disabled', false);
    // $('#outright_location').prop('disabled', false);
    // $('#delivery_date').prop('disabled', false);

    // $('#doc_refno').val('');
    // $('#created_by').val('');
    // $('#created_at').val('');
    // $('#delivery_date').val('');
    // $('#outright_code').val('').trigger('change');
    // $('#outright_location_all_dis').trigger('click');

    // var url = window.location.href;
    // var updatedUrl = url.split('?')[0];
    // window.history.replaceState(null, null, updatedUrl); 

    // var dataTable1 = $('#table1').DataTable();
    // dataTable1.clear().draw();

    // var dataTable2 = $('#table2').DataTable();
    // dataTable2.clear().draw();

    // var dataTable3 = $('#table3').DataTable();
    // dataTable3.clear().draw();

    // $('.table_list_by_item').removeClass("hidden");
    // $('.table_list_by_outlet').addClass("hidden"); 
    // $('.table_single_outlet').addClass("hidden"); 

  });

  $(document).on('click','#search_data',function(){

    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var month = ('0' + (currentDate.getMonth() + 1)).slice(-2); // Months are zero-based
    var day = ('0' + currentDate.getDate()).slice(-2);
    var formattedDate = year + '-' + month + '-' + day;

    var retailer = $('#retailer').val();
    var outright_code = $('#outright_code').val();
    var outright_location = $('#outright_location').val();
    var delivery_date = $('#delivery_date').val().split('  ')[0].trim();
    var remark = $('textarea[name="remark_h"]').val();

    if(retailer == '' || retailer == null)
    {

      Swal.fire(
        'Please Choose Retailer',
        '',
        'error'
      );

      return;
    } 

    if(outright_code == '' || outright_code == null)
    {

      Swal.fire(
        'Please Choose Supplier Code',
        '',
        'error'
      );

      return;
    }   

    if(outright_location == '' || outright_location == null)
    {

      Swal.fire(
        'Please Choose Location',
        '',
        'error'
      );

      return;
    }
    
    if(delivery_date == '' || delivery_date == null)
    {

      Swal.fire(
        'Please Choose Delivery Date',
        '',
        'error'
      );

      return;
    }

    if(delivery_date < formattedDate)
    {

      Swal.fire(
        'Please select a delivery date greater than or equal to today.',
        '',
        'error'
      );

      return;
    }

    $.ajax({
      url:"<?php echo site_url('Propose_po/create_header') ?>",
      method:"POST",
      data:{retailer:retailer,outright_code:outright_code,outright_location:outright_location,delivery_date:delivery_date,remark:remark},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);

        if (json.status == 1) {
          
          // $('#btn_create_header').addClass('hidden');
          // $('#doc_refno').val(json.refno);
          // $('#created_by').val(json.created_by);
          // $('#created_at').val(json.created_at);
          // $('#outright_code').prop('disabled', true);
          // $('.add_item_details').removeClass('hidden');

          var currentUrl = window.location.href;
          var parameterName = "id";
          var parameterValue = json.transmain_guid;
          if (currentUrl.indexOf('?') !== -1) {
            currentUrl += '&' + parameterName + '=' + parameterValue;
          } else {
            currentUrl += '?' + parameterName + '=' + parameterValue;
          }

          // window.history.pushState({ path: currentUrl }, '', currentUrl);
          window.location.href = currentUrl;

        }else{
            
          Swal.fire(
            'Failed to create, please try again',
            '',
            'error'
          );

        }

        $('.btn').button('reset');
      }
    });

    // datatable1(retailer,outright_code,outright_location,delivery_date);
    // datatable2();
    // datatable3(retailer,outright_code,outright_location,delivery_date,0);

    $('#propose_btn_header').attr('retailer', retailer);
    $('#propose_btn_header').attr('sup_code', outright_code);

    // on 05/09/2023, Ms Loo asked to only live with one outlet selection first, to avoid confusion.
    $('.table_list_by_item').addClass('hidden');
    $('.table_list_by_outlet').addClass('hidden');
    $('.table_single_outlet').removeClass('hidden');
    

    // if(outright_location == '' || outright_location == null){

    //   $('.table_list_by_item').removeClass('hidden');
    //   $('.table_list_by_outlet').addClass('hidden');
    //   $('.table_single_outlet').addClass('hidden');

    // }else{

    //   if(outright_location.length > 1){
    //     $('.table_list_by_item').removeClass('hidden');
    //     $('.table_list_by_outlet').addClass('hidden');
    //     $('.table_single_outlet').addClass('hidden');
    //   }else if(outright_location.length == 0){
    //     $('.table_list_by_item').removeClass('hidden');
    //     $('.table_list_by_outlet').addClass('hidden');
    //     $('.table_single_outlet').addClass('hidden');
    //   }else{
    //     $('.table_list_by_item').addClass('hidden');
    //     $('.table_list_by_outlet').addClass('hidden');
    //     $('.table_single_outlet').removeClass('hidden');
    //   }
    // }

    // $.ajax({
    //   url : "<?php echo site_url('Propose_po/get_transmain_guid');?>",
    //   method: 'GET',                                      
    //   success: function(data) {          
    //     json = JSON.parse(data);
        
    //     if(json.status == true && json.transmain_guid != ''){
    //       var currentURL = window.location.href;
    //       var newURL = currentURL + (currentURL.includes('?') ? '&' : '?') + 'id=' + json.transmain_guid;
    //       window.location.href = newURL;
    //     }
    
    //   },
    //   error: function(xhr, status, error) {
    //     console.error('Error:', error);
    //   }
    // });

  });//close search button

  datatable1 = function(retailer,outright_code,outright_location,delivery_date,refresh = 0)
  { 
    $.ajax({
      url : "<?php echo site_url('Propose_po/propose_details_main');?>",
      method: "POST",
      data:{retailer:retailer,outright_code:outright_code,outright_location:outright_location,delivery_date:delivery_date,refresh:refresh},
      beforeSend : function() {
          $('.btn').button('loading');
          // $('#loader_div').removeClass('hidden');
          // $('#loader_div').fadeIn();
      },
      complete: function() {
          // $('.btn').button('reset');
      },
      success : function(data)
      {  
        json = JSON.parse(data);

        if (json.status == 0) {

          Swal.fire(
            'Error',
            json.message,
            'error'
          );

        }else{

          $.ajax({
            url:"<?php echo site_url('Propose_po/view_header') ?>",
            method:"POST",
              data:{retailer:retailer},
              success:function(data)
              {
                json = JSON.parse(data);

                if (json.count == 1) {
                  
                  results = json.results;

                  var check_delivery_date = $('#delivery_date').val();

                  $('#doc_refno').val(results[0].refno);

                  if (check_delivery_date == '') {
                    $('#delivery_date').val(moment(results[0].delivery_date).format("YYYY-MM-DD  ddd"));
                  }
                  // $('#delivery_date').val(moment(results[0].delivery_date).format("YYYY-MM-DD  ddd"));
                  $('#created_by').val(results[0].created_by);
                  $('#created_at').val(results[0].created_at);

                  $('#search_data').addClass('hidden');
                  $('#outright_location_all').addClass('hidden');
                  $('#outright_location_all_dis').addClass('hidden');

                  $('#btn_view_cost_summary').removeClass('hidden');

                  $('#outright_code').prop('disabled', true);
                  $('#outright_location').prop('disabled', true);

                }else{
                  
                  Swal.fire(
                    'Error',
                    'Unable to retrieve header info',
                    'error'
                  );

                  $('.btn').button('reset');

                  return false;

                }
              }
          });

          if ($.fn.DataTable.isDataTable('#table1')) {
              $('#table1').DataTable().destroy();
          }

          $('#table1').DataTable({
            "columnDefs": [
            { className: "aligncenter", targets: [0] },
            { className: "alignleft", targets: [1,2,3] },
            { className: "alignright", targets: '_all' },
            { width: '5%', targets: [0,4,5,6,8,9] },
            { width: '7%', targets: [1,7] },
            { width: '10%', targets: [2] },
            ],
            'processing'  : true,
            'paging'      : true,
            'lengthChange': true,
            'lengthMenu'  : [ [10, 25, 50, 100, 9999999999999999], [10, 25, 50, 100, "ALL"] ],
            'searching'   : true,
            'ordering'    : true,
            'order'       : [ [1 , 'asc'] ],
            'info'        : true,
            'autoWidth'   : false,
            "bPaginate": true, 
            "bFilter": true, 
            // "sScrollY": "40vh", 
            // "sScrollX": "100%", 
            "sScrollXInner": "100%", 
            "bScrollCollapse": true,
              data: json['results'],
              columns: [
                {"data" : "action", render:function( data, type, row ){
                  var element = '';

                  // element += '<button id="propose_edit_btn" style="margin-left:5px;" title="EDIT" class="btn btn-sm btn-info" retailer="'+retailer+'" outright_code="'+outright_code+'" outright_location="'+outright_location+'" itemcode="'+row['Itemcode']+'" description="'+row['description']+'" order_lot="'+row['order_lot']+'"><i class="fa fa-edit"></i></button>';

                  element += '<button id="propose_edit_btn" style="margin-left:5px;" title="EDIT" class="btn btn-sm btn-info" retailer="'+retailer+'" itemcode="'+row['itemcode']+'" itemlink="'+row['itemlink']+'" barcode="'+row['barcode']+'" article="'+row['article']+'" packsize="'+row['packsize']+'" um="'+row['um']+'" ctn_qty="'+row['ctn_qty']+'" description="'+row['description']+'" transchild_guid="'+row['transchild_guid']+'" order_lot="'+parseInt(row['orderlotsize'])+'" propose_amt="'+row['amount']+'" propose_qty="'+row['qty']+'"><i class="fa fa-edit"></i></button>';
          
                  return element;

                }},
                {"data" : "", render:function( data, type, row ){
                  var element = '';

                  var itemcode_c = row['itemcode'];
                  var itemlink_c = row['itemlink'];

                  if(itemcode_c != itemlink_c){
                    element += "<span id='itemcode_c'>" + itemcode_c + "</span></br>" + itemlink_c;
                  }else{
                    element += itemcode_c;
                  }
          
                  return element;

                }},
                {"data" : "", render:function( data, type, row ){
                  var element = '';

                  var barcode_c = row['barcode'];
                  var article_c = row['article'];

                  element += barcode_c + "</br>" + article_c;
          
                  return element;

                }},
                {"data" : "description", render:function( data, type, row ){

                  var description_c = row['description'];

                  return "<span id='description_c'>" + description_c + "</span>";

                }},
                {"data" : "", render:function( data, type, row ){
                  var element = '';

                  var packsize_c = row['packsize'];
                  var um_c = row['um'];

                  element += packsize_c + "</br><span style='float: left'>" + um_c + "</span>";
          
                  return element;

                }},
                {"data" : "", render:function( data, type, row ){
                  var element = '';

                  var ctn_qty_c = row['ctn_qty'];
                  var order_lot_c = parseInt(row['orderlotsize']);

                  element += order_lot_c + "</br>" + ctn_qty_c;
          
                  return element;

                }},
                {"data" : "", render:function( data, type, row ){
                  var element = '';

                  var order_qty_c = row['order_qty'];
                  var foc_qty_c = row['foc_qty'];

                  if(order_qty_c == '' || order_qty_c == null){
                    var order_qty_c = 0;
                  }

                  if(foc_qty_c == '' || foc_qty_c == null){
                    var foc_qty_c = 0;
                  }

                  if(foc_qty_c != 0){
                    element += '<span id="order_qty_c">' + parseFloat(order_qty_c) + '</span></br><span id="foc_qty_c">' + parseFloat(foc_qty_c) + '</span>';
                  }else{
                    element += '<span id="order_qty_c">' + parseFloat(order_qty_c) + '</span></br><span id="foc_qty_c"></span>';
                  }
          
                  return element;

                }},
                {"data" : "", render:function( data, type, row ){
          
                  var element = '';

                  var order_qty_c = row['qty'];
                  var qty_in_ctn_c = row['qty'] / row['ctn_qty'];
                  var umbulk_c = row['umbulk'];
                  qty_in_ctn_c = parseInt(qty_in_ctn_c);

                  var calc_qty = qty_in_ctn_c + " " + umbulk_c;

                  element += parseFloat(order_qty_c) + "</br><span style='float: left'>[" + calc_qty + "]</span>";
          
                  return element;

                }},
                {"data": "amount",
                  render: function(data, type, row) {

                    if(data == '' || data == null){
                      var proposed_amount = 0;
                    }else{
                      var proposed_amount = data;
                    }

                    return formatNumberWithCommas(proposed_amount);
                  }
                },
                {"data" : "", render:function( data, type, row ){
                  var net_cost_c = row['net_cost'];
          
                  return net_cost_c;

                }},
              ],
              dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",  
              buttons: [
                    {
                        extend: 'csv'
                    }
              ],
              "language": {
                "lengthMenu": "Show _MENU_",
                "infoEmpty": "No records available",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "zeroRecords": "<?php echo '<b>No Record Found.</b>'; ?>",
              },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).closest('tr').css({"cursor": "pointer"});
                // $(nRow).attr('refno_val', aData['refno_val']);
              // $(nRow).attr('status', aData['status']);
            },
            "initComplete": function( settings, json ) {
              interval();
            }
          });//close datatable

          $('#propose_btn_header').removeClass("hidden");
          $('.btn_list_by_outlet').removeClass('hidden');
          $('.btn_list_by_item').removeClass('hidden'); 
          
        }

        $('.btn').button('reset');

        // $('#loader_div').fadeOut();
        // $('#loader_div').addClass('hidden');
        
      }
    });
  }

  datatable2 = function()
  { 

    $.ajax({
      url:"<?php echo site_url('Propose_po/view_cost_summary') ?>",
      method:"POST",
      data:{return_json:1},
      success:function(data)
      {
        json = JSON.parse(data);

        if ($.fn.DataTable.isDataTable('#table2')) {
          $('#table2').DataTable().destroy();
        }

        $('#table2').DataTable({
          "columnDefs": [
          // { className: "aligncenter", targets: [0] },
          { className: "alignleft", targets: [0,1] },
          { className: "alignright", targets: '_all' },
          // { width: '5%', targets: [0,4,5,6,8,9] },
          // { width: '7%', targets: [1,7] },
          // { width: '10%', targets: [2] },
          ],
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 100, 9999999999999999], [10, 25, 50, 100, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [1 , 'asc'] ],
          'info'        : true,
          'autoWidth'   : false,
          "bPaginate": true, 
          "bFilter": true, 
          // "sScrollY": "40vh", 
          // "sScrollX": "100%", 
          "sScrollXInner": "100%", 
          "bScrollCollapse": true,
            data: json['results'],
            columns: [
              {"data" : "action", render:function( data, type, row ){
                var element = '';
                var outlet_code = row['location'];
                var outlet_desc = row['branch_desc'];

                element += '<button id="outlet_propose_edit_btn" style="margin-left:5px;" title="EDIT" class="btn btn-sm btn-info" outlet_code="'+outlet_code+'" outlet_desc="'+outlet_desc+'"><i class="fa fa-edit"></i></button>';
              
                return element;

              }},
              {"data" : "location", render:function( data, type, row ){

                var code = row['location'];
                var desc = row['branch_desc'];

                return code + " - " + desc;

              }},
              {"data": "quantity_total"},
              {"data": "outlet_total",
                render: function(data, type, row) {

                  if(data == '' || data == null){
                    var outlet_total = 0;
                  }else{
                    var outlet_total = data;
                  }

                  return formatNumberWithCommas(outlet_total.toFixed(2));
                }
              },
            ],
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",  
            buttons: [
              {
                extend: 'csv'
              }
            ],
            "language": {
              "lengthMenu": "Show _MENU_",
              "infoEmpty": "No records available",
              "infoFiltered": "(filtered from _MAX_ total records)",
              "zeroRecords": "<?php echo '<b>No Record Found.</b>'; ?>",
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              $(nRow).closest('tr').css({"cursor": "pointer"});
            },
            "initComplete": function( settings, json ) {
              interval();
            }
        });//close datatable

        $('#propose_btn_header').removeClass("hidden");
        $('.btn_list_by_outlet').removeClass('hidden');
        $('.btn_list_by_item').removeClass('hidden');     
        $('.btn').button('reset');
      }
    });

  }

  datatable3 = function(retailer,outright_code,outright_location,delivery_date,refresh = 1)
  { 
    $.ajax({
            url:"<?php echo site_url('Propose_po/view_header') ?>",
            method:"POST",
              data:{retailer:retailer},
              success:function(data)
              {
                json = JSON.parse(data);

                if (json.count == 1) {
                  
                  results = json.results;

                  var check_delivery_date = $('#delivery_date').val();

                  $('#doc_refno').val(results[0].refno);

                  if (check_delivery_date == '') {
                    $('#delivery_date').val(moment(results[0].delivery_date).format("YYYY-MM-DD  ddd"));
                  }
                  // $('#delivery_date').val(moment(results[0].delivery_date).format("YYYY-MM-DD  ddd"));
                  $('#created_by').val(results[0].created_by);
                  $('#created_at').val(results[0].created_at);

                  $('#search_data').addClass('hidden');
                  $('#outright_location_all').addClass('hidden');
                  $('#outright_location_all_dis').addClass('hidden');

                  $('#btn_view_cost_summary_single_outlet').removeClass('hidden');

                  $('#outright_code').prop('disabled', true);
                  $('#outright_location').prop('disabled', true);

                }else{
                  
                  Swal.fire(
                    'Error',
                    'Unable to retrieve header info',
                    'error'
                  );

                  $('.btn').button('reset');

                  return false;

                }
              }
          });

          if ($.fn.DataTable.isDataTable('#table3')) {
              $('#table3').DataTable().destroy();
          }

          $('#table3').DataTable({
            "columnDefs": [
              { className: "column-no-wrap", targets: [7] },
              { className: "aligncenter", targets: [0] },
              { className: "alignleft", targets: [1,2,3] },
              { className: "noalign", targets: [5] },
              { className: "alignright", targets: '_all' },
              { width: '9%', targets: [1,5,9,10] },
              // { width: '12%', targets: [5] },
              { width: '25%', targets: [2] },
              { width: '1%', targets: '_all' },
            ],
            'processing'  : true,
            'serverSide'  : true,
            'paging'      : true,
            'lengthChange': true,
            'lengthMenu'  : [ [10, 25, 50, 100, 9999999999999999], [10, 25, 50, 100, "ALL"] ],
            'searching'   : true,
            'ordering'    : true,
            'order'       : [],
            'info'        : true,
            'autoWidth'   : false,
            "bPaginate": true, 
            "bFilter": true, 
            // "sScrollY": "40vh", 
            // "sScrollX": "100%", 
            "sScrollXInner": "100%", 
            "bScrollCollapse": true,
            "ajax": {
              url : "<?php echo site_url('Propose_po/propose_details_main');?>",
              method: "POST",
              data:{retailer:retailer,outright_code:outright_code,outright_location:outright_location,delivery_date:delivery_date,refresh:refresh},
            },
              columns: [
                {"data" : "itemcode/itemlink", render:function( data, type, row ){
                  var element = '';

                  var itemcode_c = row['itemcode'];
                  var itemlink_c = row['itemlink'];

                  if(itemcode_c != itemlink_c){
                    element += "<span id='itemcode_c'>" + itemcode_c + "</span></br>" + itemlink_c;
                  }else{
                    element += "<span id='itemcode_c'>" + itemcode_c + "</span>";
                  }
          
                  return element;

                }},
                {"data" : "barcode/article", render:function( data, type, row ){

                  var barcode_c = row['barcode'];
                  var article_c = row['article'];

                  return barcode_c + "</br>" + article_c;

                }},
                {"data" : "description", render:function( data, type, row ){

                  var element = '';
                  var description_c = row['description'];
                  var backendcheck_status = row['pochild2_transchild_guid'][0]['check']['status'];
                  var backendcheck_message = row['pochild2_transchild_guid'][0]['check']['message'];

                  if(backendcheck_status == false){
                    element = "<span id='description_c'>" + description_c + "</span></br><span style='color:red'>(" + backendcheck_message + ")</span>";
                  }else{
                    element = "<span id='description_c'>" + description_c + "</span>";
                  }

                  return element;

                }},
                {"data" : "ps/um", render:function( data, type, row ){

                  var packsize_c = row['packsize'];
                  var um_c = row['um'];

                  return "<span class='packsize_c' id='packsize_c'>" + packsize_c + "</span></br><span style='float: left'>" + um_c + "</span>";

                }},
                {"data" : "order_lot/ctn_qty", render:function( data, type, row ){

                  var ctn_qty_c = row['ctn_qty'];
                  var order_lot_c = parseInt(row['orderlotsize']);

                  return "<span class='order_lot_c' id='order_lot_c'>" + order_lot_c + "</span></br><span class='ctn_qty_c' id='ctn_qty_c'>" + ctn_qty_c + "</span>";

                }},
                // {"data" : "previous_qty", render:function( data, type, row ){

                //   var previous_qty = row['pochild2_transchild_guid'][0]['sum_qty'];
                //   return previous_qty;

                // }},
                {"data" : "proposed_bulk_qty", render:function( data, type, row ){

                  var element = '';

                  umbulk = row['umbulk'];
                  um = row['um'];
                  ctn_qty_c = row['ctn_qty'];
                  packsize_c = row['packsize'];
                  store_qty = row['pochild2_transchild_guid'][0]['qty'];
                  outlet = row['pochild2_transchild_guid'][0]['location'];
                  guid_child2 = row['pochild2_transchild_guid'][0]['transchild2_guid'];
                  order_lot = row['orderlotsize'];
                  itemcode = row['itemcode'];
                  previous_qty = row['pochild2_transchild_guid'][0]['sum_qty'];
                  backendcheck_status = row['pochild2_transchild_guid'][0]['check']['status'];
                  backendcheck_message = row['pochild2_transchild_guid'][0]['check']['message'];

                  if(!isNaN(ctn_qty_c) && ctn_qty_c > 0){
                    ctn_qty = Math.floor(store_qty / ctn_qty_c);
                    total_ctn_qty = ctn_qty * ctn_qty_c;
                    reminder_qty = store_qty - total_ctn_qty;

                    prev_ctn_qty = Math.floor(previous_qty / ctn_qty_c);
                    total_prev_ctn_qty = prev_ctn_qty * ctn_qty_c;
                    prev_reminder_qty = previous_qty - total_prev_ctn_qty;

                    if(um == umbulk){
                      um = 'UNIT';
                    }

                    if(parseInt(total_prev_ctn_qty) == 0){
                      previous_propose = previous_qty + " " + um;
                    }else{
                      previous_propose = prev_ctn_qty + " " + umbulk + " " + prev_reminder_qty + " " + um;
                    }

                  }else{
                    ctn_qty = 0;
                    reminder_qty = store_qty;
                  }

                  if(backendcheck_status == false){
                    var input_behaviour = "disabled";
                  }else{
                    var input_behaviour = "";
                  }

                  element += '  <input ' + input_behaviour + ' type="number" data-column="5" title=" x ' + ctn_qty_c + '" class="to_update_cost edit_ctn_qty_actual" id="edit_ctn_qty_actual" name="edit_ctn_qty_actual" min="0" style="width:10vh;" value='+ctn_qty+' step="'+order_lot+'"> X ' + ctn_qty_c + ' (' + umbulk + ')';

                  if(parseInt(previous_qty) != 0 && !(isNaN(previous_qty) || previous_qty == 'undefined' || previous_qty == null)){
                    $('#previous_propose_indication').removeClass('hidden');

                    element += '<br>';
                    element += '<span style="text-align: left; font-weight: bold; font-style: italic;">** ' + previous_propose + '</span>';
                  }

                  return element;

                }},
                {"data" : "proposed_qty", render:function( data, type, row ){

                  var element = '';

                  umbulk = row['umbulk'];
                  um = row['um'];
                  ctn_qty_c = row['ctn_qty'];
                  packsize_c = row['packsize'];
                  store_qty = row['pochild2_transchild_guid'][0]['qty'];
                  outlet = row['pochild2_transchild_guid'][0]['location'];
                  guid_child2 = row['pochild2_transchild_guid'][0]['transchild2_guid'];
                  order_lot = row['orderlotsize'];
                  itemcode = row['itemcode'];
                  previous_qty = row['pochild2_transchild_guid'][0]['sum_qty'];
                  max_packsize = row['max_packsize'];
                  backendcheck_status = row['pochild2_transchild_guid'][0]['check']['status'];
                  backendcheck_message = row['pochild2_transchild_guid'][0]['check']['message'];

                  if(!isNaN(ctn_qty_c) && ctn_qty_c > 0){
                    ctn_qty = Math.floor(store_qty / ctn_qty_c);
                    total_ctn_qty = ctn_qty * ctn_qty_c;
                    reminder_qty = store_qty - total_ctn_qty;

                    prev_ctn_qty = Math.floor(previous_qty / ctn_qty_c);
                    total_prev_ctn_qty = prev_ctn_qty * ctn_qty_c;
                    prev_reminder_qty = previous_qty - total_prev_ctn_qty;

                    if(um == umbulk){
                      um = 'UNIT';
                    }

                    if(parseInt(total_prev_ctn_qty) == 0){
                      previous_propose = previous_qty + " " + um;
                    }else{
                      previous_propose = prev_ctn_qty + " " + umbulk + " " + prev_reminder_qty + " " + um;
                      previous_propose = prev_reminder_qty + " " + um;
                    }

                  }else{
                    ctn_qty = 0;
                    reminder_qty = store_qty;
                  }

                  if(max_packsize == true){
                    var input_behaviour = "disabled";
                  }else{
                    var input_behaviour = "";
                  }

                  element += '<span id="show_text_qty" style="text-align: right;" branch="'+outlet+'" detail_guid="'+guid_child2+'" itemcode="'+itemcode+'">';

                  element += '  <input ' + input_behaviour + ' type="number" data-column="5" class="to_update_cost edit_qty_actual" id="edit_qty_actual" name="edit_qty_actual" min="0" style="width:10vh;" value='+reminder_qty+' step="'+order_lot+'">';

                  element += '</span>';

                  // if(parseInt(previous_qty) != 0){
                  //   $('#previous_propose_indication').removeClass('hidden');

                  //   element += '<br>';
                  //   element += '<span style="text-align: left; font-weight: bold; font-style: italic;">** ' + previous_propose + '</span>';
                  // }

                  return element;

                }},
                {"data" : "proposed_foc", render:function( data, type, row ){

                  var element = '';

                  store_foc_qty = row['pochild2_transchild_guid'][0]['qty_foc'];
                  outlet = row['pochild2_transchild_guid'][0]['location'];
                  guid_child2 = row['pochild2_transchild_guid'][0]['transchild2_guid'];
                  order_lot = row['orderlotsize'];
                  itemcode = row['itemcode'];
                  previous_foc = row['pochild2_transchild_guid'][0]['sum_qty_foc'];
                  backendcheck_status = row['pochild2_transchild_guid'][0]['check']['status'];
                  backendcheck_message = row['pochild2_transchild_guid'][0]['check']['message'];

                  if(backendcheck_status == false){
                    var input_behaviour = "disabled";
                  }else{
                    var input_behaviour = "";
                  }

                  element += '<span id="show_foc_qty" branch="'+outlet+'" detail_guid="'+guid_child2+'" itemcode="'+itemcode+'"><input ' + input_behaviour + ' type="number" data-column="6" class="to_update_cost edit_foc_actual" id="edit_foc_actual" name="edit_foc_actual" min="0" style="width:10vh;" value='+store_foc_qty+'></span>';

                  if(parseInt(previous_foc) != 0 && !(isNaN(previous_foc) || previous_foc == 'undefined' || previous_foc == null)){
                    $('#previous_propose_indication').removeClass('hidden');

                    element += '<br>';
                    element += '<span style="text-align: left; font-weight: bold; font-style: italic;">** ' + previous_foc + '</span>';
                  }

                  return element;

                }},
                {"data" : "proposed_cost", render:function( data, type, row ){

                  var element = '';

                  store_price = row['pochild2_transchild_guid'][0]['cost'];
                  outlet = row['pochild2_transchild_guid'][0]['location'];
                  guid_child2 = row['pochild2_transchild_guid'][0]['transchild2_guid'];
                  order_lot = row['orderlotsize'];
                  itemcode = row['itemcode'];
                  backendcheck_status = row['pochild2_transchild_guid'][0]['check']['status'];
                  backendcheck_message = row['pochild2_transchild_guid'][0]['check']['message'];

                  store_price = parseFloat(store_price);
                  store_price = store_price.toFixed(4);

                  if(backendcheck_status == false){
                    var input_behaviour = "disabled";
                  }else{
                    var input_behaviour = "";
                  }

                  element += '<span id="show_text_price" branch="'+outlet+'" detail_guid="'+guid_child2+'"><input ' + input_behaviour + ' type="number" data-column="7" class="to_update_cost edit_price_actual" id="edit_price_actual" name="edit_price_actual" min="0" style="width:15vh;text-align: right;" value='+store_price+'></span>';

                  return element;

                }},
                {"data" : "proposed_amount", render:function( data, type, row ){

                  if(row['amount'] == '' || row['amount'] == null){
                    var proposed_amount = 0;
                  }else{
                    var proposed_amount = row['amount'];
                  }

                  var element = '<span data-column="8" class="append_supplier_cost" id="append_supplier_cost">'+formatNumberWithCommas(proposed_amount)+'</span>';

                  return element;

                }},

                {"data" : "total_qty", render:function( data, type, row ){

                  var element = '';

                  packsize_c = row['packsize'];
                  umbulk = row['umbulk'];
                  um = row['um'];
                  ctn_qty_c = row['ctn_qty'];
                  store_qty = row['pochild2_transchild_guid'][0]['qty'] + row['pochild2_transchild_guid'][0]['qty_foc'];

                  if(!isNaN(ctn_qty_c) && ctn_qty_c > 0){
                    ctn_qty = Math.floor(store_qty / ctn_qty_c);
                    total_ctn_qty = ctn_qty * ctn_qty_c;
                    reminder_qty = store_qty - total_ctn_qty;
                  }else{
                    ctn_qty = 0;
                    reminder_qty = store_qty;
                  }

                  if(um == umbulk){
                    um = 'UNIT';
                  }

                  element += '<span data-column="9" style="font-weight: bold;" class="append_supplier_qty" id="append_supplier_qty">'+row['qty']+'</span>';

                  if(!isNaN(store_qty) && store_qty != '' && store_qty > 0){
                    element += "</br><span style='float: left; font-weight: bold;' class='append_supplier_qty_desc' id='append_supplier_qty_desc'>[" + ctn_qty + " " + umbulk + " " + reminder_qty*packsize_c + " " + um + "]</span>";
                  }else{
                    element += "</br><span style='float: left; font-weight: bold;' class='append_supplier_qty_desc' id='append_supplier_qty_desc'></span>";
                  }

                  return element;


                }},
                {"data" : "qty_bal", render:function( data, type, row ){

                  var qty_bal = row['pochild2_transchild_guid'][0]['qty_bal'];
                  return parseFloat(qty_bal).toFixed(1);

                }},
                {"data" : "days", render:function( data, type, row ){
                
                  var days = row['pochild2_transchild_guid'][0]['days'];
                  return Number(days).toFixed(0);

                }},
                {"data" : "qty_tbr", render:function( data, type, row ){

                  var qty_tbr = row['pochild2_transchild_guid'][0]['qty_tbr'];
                  return parseFloat(qty_tbr).toFixed(1);

                }},
                {"data" : "ads", render:function( data, type, row ){

                  var ads = row['pochild2_transchild_guid'][0]['ads'];
                  return Number(ads).toFixed(0);
                   // parseFloat

                }},
                {"data" : "aws", render:function( data, type, row ){

                  var aws = row['pochild2_transchild_guid'][0]['aws'];
                  return parseFloat(aws).toFixed(1);

                }},
                {"data" : "ams", render:function( data, type, row ){

                  var ams = row['pochild2_transchild_guid'][0]['ams'];
                  return Number(ams).toFixed(0);

                }},
                // {"data" : "qty_opn", render:function( data, type, row ){

                //   var qty_opn = row['pochild2_transchild_guid'][0]['qty_opn'];
                //   return parseFloat(qty_opn).toFixed(1);

                // }},
                // {"data" : "qty_rec", render:function( data, type, row ){

                //   var qty_rec = row['pochild2_transchild_guid'][0]['qty_rec'];
                //   return parseFloat(qty_rec).toFixed(1);

                // }},
                // {"data" : "qty_sold", render:function( data, type, row ){ 

                //   var qty_sold = row['pochild2_transchild_guid'][0]['qty_sold'];
                //   return parseFloat(qty_sold).toFixed(1);

                // }},
                // {"data" : "qty_other", render:function( data, type, row ){ 

                //   var qty_other = row['pochild2_transchild_guid'][0]['qty_other'];
                //   return parseFloat(qty_other).toFixed(1);

                // }},
              ],
              dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",  
              buttons: [
                    {
                        extend: 'csv'
                    }
              ],
              "language": {
                "lengthMenu": "Show _MENU_",
                "infoEmpty": "No records available",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "zeroRecords": "<?php echo '<b>No Record Found.</b>'; ?>",
              },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).closest('tr').css({"cursor": "pointer"});
                $(nRow).attr('detail_guid', aData['pochild2_transchild_guid'][0]['transchild2_guid']);
                $(nRow).attr('ori_cost', aData['pochild2_transchild_guid'][0]['ori_cost']);
                $(nRow).attr('umbulk', aData['umbulk']);
                $(nRow).attr('um', aData['um']);

                if(aData['pochild2_transchild_guid'][0]['qty'] > 0 || aData['pochild2_transchild_guid'][0]['qty_foc'] > 0){
                  $(nRow).addClass("highlighted-row");
                }
                
              // $(nRow).attr('status', aData['status']);
            },
            "initComplete": function( settings, json ) {

              var maxCtnQtyWidth = 0;
              var maxQtyWidth = 0;
              var maxFOCWidth = 0;
              var maxCostWidth = 0;
                  
                $('input[name="edit_ctn_qty_actual"]').each(function() {
                  var value = $(this).val();
                  var width = value.length * 5 + 25;
                    
                  // Update the maximum width if necessary
                  if (width > maxCtnQtyWidth) {
                    maxCtnQtyWidth = width;
                  }
                });

                $('input[name="edit_qty_actual"]').each(function() {
                  var value = $(this).val();
                  var width = value.length * 5 + 25;
                    
                  // Update the maximum width if necessary
                  if (width > maxQtyWidth) {
                    maxQtyWidth = width;
                  }
                });

                $('input[name="edit_foc_actual"]').each(function() {
                  var value = $(this).val();
                  var width = value.length * 5 + 25;
                    
                  // Update the maximum width if necessary
                  if (width > maxFOCWidth) {
                    maxFOCWidth = width;
                  }
                });

                $('input[name="edit_price_actual"]').each(function() {
                  var value = $(this).val();
                  var width = value.length * 5 + 25;
                    
                  // Update the maximum width if necessary
                  if (width > maxCostWidth) {
                    maxCostWidth = width;
                  }
                });
                  
                // Apply the maximum width to all input fields
                $('input[name="edit_ctn_qty_actual"]').width(maxCtnQtyWidth);
                $('input[name="edit_qty_actual"]').width(maxQtyWidth);
                $('input[name="edit_foc_actual"]').width(maxFOCWidth);
                $('input[name="edit_price_actual"]').width(maxCostWidth);

              interval();
            }
          });//close datatable

          // $('#table3').DataTable().column(5).nodes().to$().css("border", "1px solid #A9A9A9");
          // $('#table3').DataTable().column(6).nodes().to$().css("border", "1px solid #A9A9A9");
          // $('#table3').DataTable().column(7).nodes().to$().css("border", "1px solid #A9A9A9");
          // $('#table3').DataTable().column(8).nodes().to$().css("border", "1px solid #A9A9A9");
          // $('#table3').DataTable().column(9).nodes().to$().css("border", "1px solid #A9A9A9");
          // $('#table3').DataTable().column(10).nodes().to$().css("border", "1px solid #A9A9A9");

          if(show_info == 0){
            // $('#table3').DataTable().column(-2).nodes().to$().addClass('hidden');
            // $('#table3').DataTable().column(-3).nodes().to$().addClass('hidden'); 
            // $('#table3').DataTable().column(-4).nodes().to$().addClass('hidden'); 

            $('#table3').DataTable().column(-1).visible(false);
            $('#table3').DataTable().column(-2).visible(false);
            $('#table3').DataTable().column(-3).visible(false);

            $('#table3').DataTable().column(-1).nodes().to$().css('display', 'none');
            $('#table3').DataTable().column(-2).nodes().to$().css('display', 'none');
            $('#table3').DataTable().column(-3).nodes().to$().css('display', 'none');
          }

          if(block_cost == 1){
            $(".edit_price_actual").prop("disabled", true);
          }

          $('#propose_btn_header').removeClass("hidden"); 


        $('.btn').button('reset');

        // $('#loader_div').fadeOut();
        // $('#loader_div').addClass('hidden');

          $('#table3').on('click', 'input[type="number"]', function() {
            $(this).select();
            $(this).removeClass('input-error');
          });

          $('#table3').on('input', '.to_update_cost', function() {

            var maxCtnQtyWidth = 0;
            var maxQtyWidth = 0;
            var maxFOCWidth = 0;
            var maxCostWidth = 0;
            
           
            $('input[name="edit_ctn_qty_actual"]').each(function() {
              var value = $(this).val();
              var width = value.length * 5 + 25;
                
              // Update the maximum width if necessary
              if (width > maxCtnQtyWidth) {
                maxCtnQtyWidth = width;
              }
            });

            $('input[name="edit_qty_actual"]').each(function() {
              var value = $(this).val();
              var width = value.length * 5 + 25;
              
              // Update the maximum width if necessary
              if (width > maxQtyWidth) {
                maxQtyWidth = width;
              }
            });

            $('input[name="edit_foc_actual"]').each(function() {
              var value = $(this).val();
              var width = value.length * 5 + 25;
              
              // Update the maximum width if necessary
              if (width > maxFOCWidth) {
                maxFOCWidth = width;
              }
            });

            $('input[name="edit_price_actual"]').each(function() {
              var value = $(this).val();
              var width = value.length * 5 + 25;
              
              // Update the maximum width if necessary
              if (width > maxCostWidth) {
                maxCostWidth = width;
              }
            });
            
            // Apply the maximum width to all input fields
            $('input[name="edit_ctn_qty_actual"]').width(maxCtnQtyWidth);
            $('input[name="edit_qty_actual"]').width(maxQtyWidth);
            $('input[name="edit_foc_actual"]').width(maxFOCWidth);
            $('input[name="edit_price_actual"]').width(maxCostWidth);

            var currentRow = $(this).closest('tr');
            var columnNumber = $(this).data('column');
            var value = $(this).val();

            var packsize_c = currentRow.find('#packsize_c').text();
            var ctn_qty_c = currentRow.find('#ctn_qty_c').text();
            var ctn_qty_value = currentRow.find('#edit_ctn_qty_actual').val();
            var qty_value = currentRow.find('#edit_qty_actual').val();
            var foc_value = currentRow.find('#edit_foc_actual').val();
            var price_value = currentRow.find('#edit_price_actual').val();
            var umbulk = currentRow.attr('umbulk');
            var um = currentRow.attr('um');

            if (isNaN(ctn_qty_value) || ctn_qty_value == '') {
              ctn_qty_value = '0';
            }

            if (isNaN(qty_value) || qty_value == '') {
              qty_value = '0';
            }

            if (isNaN(foc_value) || foc_value == '') {
              foc_value = '0';
            }

            if(um == umbulk){
              um = 'UNIT';
            }
            
            if(!isNaN(ctn_qty_c) && ctn_qty_c != 0){
              var formattedQuantity = Number(parseFloat(ctn_qty_value*ctn_qty_c)+parseFloat(qty_value)+parseFloat(foc_value)).toFixed(2);
              var ctn_qty = Math.floor((formattedQuantity*packsize_c) / (ctn_qty_c*packsize_c));
              var total_ctn_value = Number(parseFloat(ctn_qty_c*ctn_qty)).toFixed(2);         
              var reminder_qty = Number(parseFloat((formattedQuantity*packsize_c) - (ctn_qty*ctn_qty_c*packsize_c)));

              $(".append_supplier_qty_desc", currentRow).text("[" + ctn_qty + " " + umbulk + " " + reminder_qty + " " + um + "]");

              if(parseInt(ctn_qty_value) == 0 && parseInt(qty_value) == 0 && parseInt(foc_value) == 0){
                $(".append_supplier_qty_desc", currentRow).text("");
              }
            }else{
              var formattedQuantity = Number(parseFloat(qty_value)+parseFloat(foc_value)).toFixed(2);
            }

            var formattedAmount = Number(((formattedQuantity - foc_value) * price_value)).toFixed(2);

            $(".append_supplier_cost", currentRow).text(formatNumberWithCommas(formattedAmount));
            $(".append_supplier_qty", currentRow).text(formattedQuantity);

          });
  }


  if (load_table == 1) {
    
    var retailer = $('#retailer').val();
    var outright_code = $('#outright_code').val();

    $('#search_data').addClass('hidden');
    $('#outright_location_all').addClass('hidden');
    $('#outright_location_all_dis').addClass('hidden');
    $('.location_select_option1').addClass('hidden');

    $('#btn_print_document').removeClass('hidden');
    $('#btn_view_cost_summary').removeClass('hidden');
    $('.location_select_option2').removeClass('hidden');

    $('#propose_btn_header').attr('retailer', retailer);
    $('#propose_btn_header').attr('sup_code', outright_code);

    datatable1(retailer,'','','',1);
    datatable2();
    datatable3(retailer,'','','',1);
  }

  $(document).on('click', '#propose_edit_btn', function(event){

    var retailer = $(this).attr('retailer');
    var transchild_guid = $(this).attr('transchild_guid');
    // var outright_code = $(this).attr('outright_code');
    // var outright_location = $(this).attr('outright_location');
    var itemcode = $(this).attr('itemcode');
    var itemlink = $(this).attr('itemlink');
    var barcode = $(this).attr('barcode');
    var article = $(this).attr('article');
    var description = $(this).attr('description');
    var packsize = $(this).attr('packsize');
    var um = $(this).attr('um');
    var ctn_qty = $(this).attr('ctn_qty');
    var order_lot = $(this).attr('order_lot');
    var propose_amt = $(this).attr('propose_amt');

    if(propose_amt == '' || propose_amt == null){
      propose_amt = 0;
    }

    var propose_qty = $(this).attr('propose_qty');
    propose_qty = (Number.isInteger(parseFloat(propose_qty))) ? Number(propose_qty).toFixed(0) : Number(propose_qty).toFixed(2);

    $.ajax({
      url:"<?php echo site_url('Propose_po/propose_action_editor') ?>",
      method:"POST",
      data:{retailer:retailer,transchild_guid:transchild_guid},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        
        var modal = $("#propose_medium-modal").modal();

        modal.find('.modal-title').html('Edit Propose PO');

        methodd = '';

        methodd +='<div class="row">';
        methodd +=' <div class="col-md-12">';
        methodd +='   <div class="box box-info">';
        methodd +='     <div class="box-body">';
        methodd +='       <div class="row">'; 
        methodd +='         <div class="col-md-9">';
        methodd +='           <table style="width: 70%;">';
        methodd +='             <tr>';
        methodd +='               <th>Description </th>';
        methodd +='               <td> : '+description+'</td>';
        methodd +='             </tr>';
        methodd +='             <tr>';
        methodd +='               <th>Item Code </br> Item Link </th>';
        methodd +='               <td> : '+itemcode+'</br> : '+itemlink+'</td>';
        methodd +='               <th>Pack Size </br> UM </th>';
        methodd +='               <td> : '+packsize+'</br> : '+um+'</td>';
        methodd +='             </tr>';
        methodd +='             <tr>';
        methodd +='               <th>Barcode</br> Article No </th>';
        methodd +='               <td> : '+barcode+'</br> : '+article+'</td>';
        methodd +='               <th>Order Lot</br> Ctn Qty </th>';
        methodd +='               <td> : '+order_lot+'</br> : '+ctn_qty+'</td>';
        methodd +='             </tr>';
        methodd +='           </table>';
        methodd +='         </div>';
        methodd +='         <div class="col-md-3">';
        methodd +='           <table class="pull-right" style="width: 100%; border: 3px solid black;">';
        methodd +='             <tr>';
        methodd +='               <th>Total Proposed Amt </th>';
        methodd +='               <td> : <b><span id="total_propose_amount"> '+formatNumberWithCommas(propose_amt)+'</span></b></td>';
        methodd +='             </tr>';
        methodd +='             <tr>';
        methodd +='               <th>Total Proposed Qty</th>';
        methodd +='               <td> : <b><span id="total_propose_quantity"> '+propose_qty+'</span></b></td>';
        methodd +='             </tr>';
        methodd +='           </table>'; 
        methodd +='         </div>';
        methodd +='       </div>';
        methodd +='       </br>';
        methodd += '      <table id="edit_table" class="table table-bordered table-striped" width="100%" cellspacing="0">';
        methodd += '        <thead style="white-space: nowrap;">';
        methodd += '          <tr>';
        methodd += '            <th rowspan="2" colspan="1">Outlet</th>';
        methodd += '            <th rowspan="1" colspan="2">Previous Propose</th>';
        methodd += '            <th rowspan="1" colspan="4">Supplier Propose <span class="pull-right">Apply to all outlet <input type="checkbox" id="duplicate_propose"></span></th>';
        methodd += '            <th rowspan="2" colspan="1">DOH</th>';
        methodd += '            <th rowspan="2" colspan="1">QOH</th>';

        // if(show_info == 0){
          methodd += '            <th rowspan="2" colspan="1">ADS</th>';
          methodd += '            <th rowspan="2" colspan="1">AWS</th>';
          methodd += '            <th rowspan="2" colspan="1">AMS</th>';
          methodd += '            <th rowspan="2" colspan="1">TBR</th>';
          methodd += '            <th rowspan="2" colspan="1">OPN</th>';
          methodd += '            <th rowspan="2" colspan="1">GRN</th>';
          methodd += '            <th rowspan="2" colspan="1">SOLD</th>';
          methodd += '            <th rowspan="2" colspan="1">OTHERS</th>';
        // }

        methodd += '          </tr>';
        methodd += '          <tr>';
        methodd += '            <th>Qty</th>';
        methodd += '            <th>FOC</th>';
        methodd += '            <th>Qty</th>';
        methodd += '            <th>FOC</th>';
        methodd += '            <th>Cost</th>';
        methodd += '            <th style="border:1px solid #f4f4f4">Amount</th>';
        methodd += '          </tr>'; 
        methodd += '        </thead>'; 
        methodd += '        <tbody>'; 
        methodd += '        </tbody>';
        methodd += '      </table>'; 
        methodd += '    </div>'; 
        methodd += '  </div>'; 
        methodd += '</div>'; 
        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="update_propose_btn" class="btn btn-primary" value="Update"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
      
        setTimeout(function(){
          if ($.fn.DataTable.isDataTable('#edit_table')) {
              $('#edit_table').DataTable().destroy();
          }

          $('#edit_table').DataTable({
          "columnDefs": [
          { className: "alignleft", targets: [0] },
          { className: "alignright", targets: '_all' },
          { width: '7%', targets: [1,2,3] },
          { width: '8%', targets: [4] },
          { width: '6%', targets: [5] },
          ],
          "fixedColumns" : {
            leftColumns: 1
            },
          'processing'  : true,
          'paging'      : false,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : false,
          'ordering'    : true,
          'order'       : [ [0 , 'asc'] ],
          'info'        : true,
          'autoWidth'   : false,
          "bPaginate": false, 
          "bFilter": true, 
          // "sScrollY": "60vh", 
          // "sScrollX": "100%", 
          "sScrollXInner": "100%", 
          "bScrollCollapse": true,
            data: json['results'],
            columns: [
              {"data" : "location", render:function( data, type, row ){

                var location = row['location'];
                var branch_desc = row['branch_desc'];

                var element = location + ' - ' + branch_desc;

                return element;

              }},
              {"data" : "sum_qty", render:function( data, type, row ){

                return row['sum_qty'];

              }},
              {"data" : "sum_qty_foc", render:function( data, type, row ){

                return row['sum_qty_foc'];

              }},
              {"data" : "qty", render:function( data, type, row ){
                var element = '';
                var store_qty = '';

                if((data == '') || (data == 'null') || (data == null))
                {
                  store_qty = row['qty'];
                }
                else
                {
                  store_qty = data;
                }

                element += '<span id="show_text_qty" branch="'+row['location']+'" detail_guid="'+row['transchild2_guid']+'" itemcode="'+itemcode+'"><input type="number" data-column="1" class="to_update_cost edit_qty_actual" id="edit_qty_actual" name="edit_qty_actual" min="0" style="width:10vh;" value='+store_qty+' step="'+order_lot+'"></span>';

                return element;
                  
                }},
              {"data" : "qty_foc", render:function( data, type, row ){
                var element = '';
                var store_foc_qty = '';

                if((data == '') || (data == 'null') || (data == null))
                {
                  store_foc_qty = row['qty_foc'];
                }
                else
                {
                  store_foc_qty = data;
                }

                element += '<span id="show_foc_qty" branch="'+row['location']+'" detail_guid="'+row['transchild2_guid']+'" itemcode="'+itemcode+'"><input type="number" data-column="2" class="to_update_cost edit_foc_actual" id="edit_foc_actual" name="edit_foc_actual" min="0" style="width:10vh;" value='+store_foc_qty+'></span>';

                return element;
                  
              }},
              {"data" : "cost", render:function( data, type, row ){
                var element = '';
                var store_price = '';

                if((data == '') || (data == 'null') || (data == null) || (data == '0'))
                {
                  store_price = row['cost'];
                }
                else
                {
                  store_price = data;
                }

                store_price = parseFloat(store_price);
                store_price = store_price.toFixed(4);

                element += '<span id="show_text_price" branch="'+row['location']+'" detail_guid="'+row['transchild2_guid']+'"><input type="number" data-column="3" class="to_update_cost edit_price_actual" id="edit_price_actual" name="edit_price_actual" min="0" style="width:15vh;text-align: right;" value='+store_price+'></span>';

                return element;

                }},
              {"data" : "proposed_amount", render:function( data, type, row ){

                var element = '<span data-column="4" class="append_supplier_cost" id="append_supplier_cost">'+formatNumberWithCommas(row['proposed_amount'])+'</span>';

                return element;

              }},
              {"data" : "days", render:function( data, type, row ){
                
                var days = row['days'];
                return Number(days).toFixed(0);

              }},
              {"data" : "qty_bal", render:function( data, type, row ){

                var qty_bal = row['qty_bal'];
                return parseFloat(qty_bal).toFixed(1);

              }},

              // if(show_info == 0){
                {"data" : "ads", render:function( data, type, row ){

                  var ads = row['ads'];
                  return Number(ads).toFixed(0);
                  // parseFloat

                }},
                {"data" : "aws", render:function( data, type, row ){

                  var aws = row['aws'];
                  return parseFloat(aws).toFixed(1);

                }},
                {"data" : "ams", render:function( data, type, row ){

                  var ams = row['ams'];
                  return Number(ams).toFixed(0);

                }},
                {"data" : "qty_tbr", render:function( data, type, row ){

                  var qty_tbr = row['qty_tbr'];
                  return parseFloat(qty_tbr).toFixed(1);

                }},
                {"data" : "qty_opn", render:function( data, type, row ){

                  var qty_opn = row['qty_opn'];
                  return parseFloat(qty_opn).toFixed(1);

                }},
                {"data" : "qty_rec", render:function( data, type, row ){

                  var qty_rec = row['qty_rec'];
                  return parseFloat(qty_rec).toFixed(1);

                }},
                {"data" : "qty_sold", render:function( data, type, row ){ 

                  var qty_sold = row['qty_sold'];
                  return parseFloat(qty_sold).toFixed(1);

                }},
                {"data" : "qty_other", render:function( data, type, row ){ 

                  var qty_other = row['qty_other'];
                  return parseFloat(qty_other).toFixed(1);

                }},
              // }

              //{ "data": "order_lot"},  <th rowspan="2" colspan="1">Order Lot</th>
              
              ],
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip", 
           "language": {
                            "lengthMenu": "Show _MENU_",
                            "infoEmpty": "No records available",
                            "infoFiltered": "(filtered from _MAX_ total records)",
                            "zeroRecords": "<span style='margin-left:13vh;'><?php echo '<b>No Record Found.</b>'; ?></span>",
                    }, 
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              $(nRow).closest('tr').css({"cursor": "pointer"});
              $(nRow).attr('itemcode', itemcode);
              $(nRow).attr('detail_guid', aData['transchild2_guid']);
              $(nRow).attr('location', aData['location']);
              $(nRow).attr('supplier_qty_actual', aData['qty']);
              $(nRow).attr('supplier_foc_actual', aData['qty_foc']);
              $(nRow).attr('supplier_price_actual', aData['cost']);
              $(nRow).attr('ads', aData['ads']);
              $(nRow).attr('aws', aData['aws']);
              $(nRow).attr('ams', aData['ams']);
              $(nRow).attr('doh', aData['days']);
              $(nRow).attr('qty_bal', aData['qty_bal']);
              $(nRow).attr('order_lot', order_lot);

              // if(show_info == 0){
                $(nRow).find('td:eq(7)').css({"background-color":"#ffb84d","color":"black"});
                $(nRow).find('td:eq(8)').css({"background-color":"#ffb84d","color":"black"});
                $(nRow).find('td:eq(9)').css({"background-color":"#ffb84d","color":"black"});
              // }

              $(nRow).find('td:eq(6)').css({"background-color":"#4dff4d","color":"black"});

            // $(nRow).attr('status', aData['status']);
          },
          "initComplete": function( settings, json ) {
            interval();

          },
          "footerCallback": function ( row, data, start, end, display ) {
              var api = this.api(), data;

              var data = $.fn.dataTable.render.number( '\,', '.', 2, 'RM' ).display;

              // Remove the formatting to get integer data for summation
              var intVal = function ( i ) {
                  return typeof i === 'string' ?
                      i.replace(/[\$,]/g, '')*1 :
                      typeof i === 'number' ?
                          i : 0;
              };
              // Total over all pages
              total = api
                  .column( 3 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
                  
              // Total over this page
              pageTotal = api
                  .column( 4, { page: 'current'} )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );

              // total_sys = api
              //     .column( 9 )
              //     .data()
              //     .reduce( function (a, b) {
              //         return intVal(a) + intVal(b);
              //     }, 0 );
                  
              // Total over this page
              pageTotal = api
                  .column( 9, { page: 'current'} )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
               
              // Update footer
              $( '.edit_total_sum' ).html(
                  /*''+(pageTotal).toFixed(2) +' <hr>'+ (total).toFixed(2)+''*/
                  (total).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+''
              );

              // Update footer
              // $( '.edit_total_sum_sys' ).html(
              //     /*''+(pageTotal).toFixed(2) +' <hr>'+ (total).toFixed(2)+''*/
              //     (total_sys).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+''
              // );
              }
          });//close datatable

          var maxCtnQtyWidth = 0;
          var maxQtyWidth = 0;
          var maxFOCWidth = 0;
          var maxCostWidth = 0;

          $('input[name="edit_ctn_qty_actual"]').each(function() {
            var value = $(this).val();
            var width = value.length * 5 + 25;
              
            // Update the maximum width if necessary
            if (width > maxCtnQtyWidth) {
              maxCtnQtyWidth = width;
            }
          });
            
          $('input[name="edit_qty_actual"]').each(function() {
            var value = $(this).val();
            var width = value.length * 5 + 25;
              
            // Update the maximum width if necessary
            if (width > maxQtyWidth) {
              maxQtyWidth = width;
            }
          });

          $('input[name="edit_foc_actual"]').each(function() {
            var value = $(this).val();
            var width = value.length * 5 + 25;
              
            // Update the maximum width if necessary
            if (width > maxFOCWidth) {
              maxFOCWidth = width;
            }
          });

          $('input[name="edit_price_actual"]').each(function() {
            var value = $(this).val();
            var width = value.length * 5 + 25;
              
            // Update the maximum width if necessary
            if (width > maxCostWidth) {
              maxCostWidth = width;
            }
          });
            
          // Apply the maximum width to all input fields
          $('input[name="edit_ctn_qty_actual"]').width(maxCtnQtyWidth);
          $('input[name="edit_qty_actual"]').width(maxQtyWidth);
          $('input[name="edit_foc_actual"]').width(maxFOCWidth);
          $('input[name="edit_price_actual"]').width(maxCostWidth);

          $('#edit_table').on('click', 'input[type="number"]', function() {
            $(this).select();
            $(this).removeClass('input-error');
          });

          $('.to_update_cost').on('input', function() {

            var maxCtnQtyWidth = 0;
            var maxQtyWidth = 0;
            var maxFOCWidth = 0;
            var maxCostWidth = 0;
            
            $('input[name="edit_ctn_qty_actual"]').each(function() {
              var value = $(this).val();
              var width = value.length * 5 + 25;
              
              // Update the maximum width if necessary
              if (width > maxCtnQtyWidth) {
                maxCtnQtyWidth = width;
              }
            });

            $('input[name="edit_qty_actual"]').each(function() {
              var value = $(this).val();
              var width = value.length * 5 + 25;
              
              // Update the maximum width if necessary
              if (width > maxQtyWidth) {
                maxQtyWidth = width;
              }
            });

            $('input[name="edit_foc_actual"]').each(function() {
              var value = $(this).val();
              var width = value.length * 5 + 25;
              
              // Update the maximum width if necessary
              if (width > maxFOCWidth) {
                maxFOCWidth = width;
              }
            });

            $('input[name="edit_price_actual"]').each(function() {
              var value = $(this).val();
              var width = value.length * 5 + 25;
              
              // Update the maximum width if necessary
              if (width > maxCostWidth) {
                maxCostWidth = width;
              }
            });
            
            // Apply the maximum width to all input fields
            $('input[name="edit_ctn_qty_actual"]').width(maxCtnQtyWidth);
            $('input[name="edit_qty_actual"]').width(maxQtyWidth);
            $('input[name="edit_foc_actual"]').width(maxFOCWidth);
            $('input[name="edit_price_actual"]').width(maxCostWidth);

            var currentRow = $(this).closest('tr');
            var columnNumber = $(this).data('column');
            var value = $(this).val();

            var ctn_qty_value = currentRow.find('#edit_ctn_qty_actual').val();
            var qty_value = currentRow.find('#edit_qty_actual').val();
            var foc_value = currentRow.find('#edit_foc_actual').val();
            var price_value = currentRow.find('#edit_price_actual').val();

            var formattedAmount = Number(qty_value*price_value).toFixed(2);

            $(".append_supplier_cost", currentRow).text(formatNumberWithCommas(formattedAmount));

            var isChecked = $('#duplicate_propose').prop('checked');

            if(isChecked){

              var current_qty_value = currentRow.find('td:eq(3) input').val();
              var current_foc_value = currentRow.find('td:eq(4) input').val();
              var current_price_value = currentRow.find('td:eq(5) input').val();
                  
              $('.to_update_cost[data-column="' + columnNumber + '"]').val(value);

              $(".edit_qty_actual").val(current_qty_value);
              $(".edit_foc_actual").val(current_foc_value);
              $(".edit_price_actual").val(current_price_value);

              $(".append_supplier_cost").text(formatNumberWithCommas(formattedAmount));

            }

            var sum = 0;
            $('#edit_table tr').each(function() {
              var cellValue = $(this).find('td').eq(4).text();
              var numericValue = parseFloat(cellValue.replace(/,/g, ""));
              if (!isNaN(numericValue)) {
                sum += numericValue;
              }
            });

            var total_sum = Number(sum).toFixed(2);

            $('#total_propose_amount').text(formatNumberWithCommas(total_sum));

            var sum_qty = 0;
            $('#edit_table tr').each(function() {
              var qty = parseFloat($(this).find('#edit_qty_actual').val());
              var foc = parseFloat($(this).find('#edit_foc_actual').val());
              if (!isNaN(qty) && !isNaN(foc)) {
                sum_qty += qty + foc;
              }
            });

            var total_sum_qty = (Number.isInteger(sum_qty)) ? Number(sum_qty).toFixed(0) : Number(sum_qty).toFixed(2);

            $('#total_propose_quantity').text(total_sum_qty);

          });
        },300);
        $('.btn').button('reset');
      }//close success
    });//close ajax 
  });

  $(document).on('click', '#outlet_propose_edit_btn', function(event){

    var outlet_code = $(this).attr('outlet_code');
    var outlet_desc = $(this).attr('outlet_desc');

    $.ajax({
      url:"<?php echo site_url('Propose_po/propose_action_editor_by_outlet') ?>",
      method:"POST",
      data:{outlet_code:outlet_code},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        
        var modal = $("#propose_medium_by_outlet-modal").modal();

        methodd = '';
        methodd +='<div class="row">';
        methodd +=' <div class="col-md-12">';
        methodd +='   <div class="box box-info">';
        methodd +='     <div class="box-body">';
        methodd +='       <div class="row">'; 
        methodd +='         <div class="col-md-9">';
        methodd +='           <table style="width: 70%;">';
        methodd +='             <tr>';
        methodd +='               <th>Outlet </th>';
        methodd +='               <td> : '+outlet_code+' - '+outlet_desc+'</td>';
        methodd +='             </tr>';
        methodd +='           </table>';
        methodd +='         </div>';
        methodd +='       </div>';
        methodd +='       </br>';
        methodd += '      <table id="edit_table_by_outlet" class="table table-bordered table-striped" width="100%" cellspacing="0">';
        methodd += '        <thead style="white-space: nowrap;">';
        methodd += '          <tr>';
        methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">Item Code </br>Item Link</th>';
        methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">Barcode </br>Article No</th>';
        methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">Description</th>';
        methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">PS </br>UM</th>';
        methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">Order Lot </br>Ctn Qty</th>';
        methodd += '            <th rowspan="1" colspan="2" style="text-align: left;">Previous Propose</th>';
        methodd += '            <th rowspan="1" colspan="5" style="text-align: left;">Supplier Propose</th>';
        methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">DOH</th>';
        methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">QOH</th>';

        // if(show_info == 0){
          methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">ADS</th>';
          methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">AWS</th>';
          methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">AMS</th>';
          methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">TBR</th>';
          methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">OPN</th>';
          methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">GRN</th>';
          methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">SOLD</th>';
          methodd += '            <th rowspan="2" colspan="1" style="text-align: left;">OTHERS</th>';
        // }

        methodd += '          </tr>';
        methodd += '          <tr>';
        methodd += '            <th style="text-align: left;">Qty</th>';
        methodd += '            <th style="text-align: left;">FOC</th>';
        methodd += '            <th style="text-align: left;">Qty</th>';
        methodd += '            <th style="text-align: left;">FOC</th>';
        methodd += '            <th style="text-align: left;">Cost</th>';
        methodd += '            <th style="border:1px solid #f4f4f4; text-align: left;">Amount</th>';
        methodd += '            <th style="text-align: left;">Total Qty</th>';
        methodd += '          </tr>'; 
        methodd += '        </thead>'; 
        methodd += '        <tbody>'; 
        methodd += '        </tbody>';
        methodd += '      </table>'; 
        methodd += '    </div>'; 
        methodd += '  </div>'; 
        methodd += '</div>'; 
        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
        modal.modal('show');
              
          if ($.fn.DataTable.isDataTable('#edit_table_by_outlet')) {
              $('#edit_table_by_outlet').DataTable().destroy();
          }

          $('#edit_table_by_outlet').DataTable({
          "columnDefs": [
          // { className: "alignleft", targets: [0] },
          { className: "alignright", targets: '_all' },
          { width: '7%', targets: [1,2,3] },
          { width: '8%', targets: [4] },
          { width: '6%', targets: '_all_' },
          ],
          "fixedColumns" : {
            leftColumns: 1
            },
          'processing'  : true,
          'paging'      : false,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : false,
          'ordering'    : true,
          'order'       : [ [0 , 'asc'] ],
          'info'        : true,
          'autoWidth'   : false,
          "bPaginate": false, 
          "bFilter": true, 
          // "sScrollY": "60vh", 
          // "sScrollX": "100%", 
          "sScrollXInner": "100%", 
          "bScrollCollapse": true,
            data: json['results'],
            columns: [
                {"data" : "itemcode/itemlink", render:function( data, type, row ){
                  var element = '';

                  var itemcode_c = row['itemcode'];
                  var itemlink_c = row['itemlink'];

                  if(itemcode_c != itemlink_c){
                    element += itemcode_c + "</br>" + itemlink_c;
                  }else{
                    element += itemcode_c;
                  }
          
                  return element;

                }},
                {"data" : "barcode/article", render:function( data, type, row ){

                  var barcode_c = row['barcode'];
                  var article_c = row['article'];

                  return barcode_c + "</br>" + article_c;

                }},
                {"data": "description"},
                {"data" : "packsize", render:function( data, type, row ){

                  var packsize_c = row['packsize'];
                  var um_c = row['um'];

                  return packsize_c + "</br><span style='float: left'>" + um_c + "</span>";

                }},
                {"data" : "orderlotsize", render:function( data, type, row ){

                  var ctn_qty_c = row['ctn_qty'];
                  var order_lot_c = parseInt(row['orderlotsize']);

                  return order_lot_c + "</br>" + ctn_qty_c;

                }},
                {"data" : "previous_qty", render:function( data, type, row ){

                  return row['pochild2_transchild_guid']['sum_qty'];

                }},
                {"data" : "previous_foc", render:function( data, type, row ){

                  return row['pochild2_transchild_guid']['sum_qty_foc'];

                }},
                {"data" : "proposed_qty", render:function( data, type, row ){

                  var element = '';

                  store_qty = row['pochild2_transchild_guid']['qty'];
                  outlet = row['pochild2_transchild_guid']['location'];
                  guid_child2 = row['pochild2_transchild_guid']['transchild2_guid'];
                  order_lot = row['orderlotsize'];
                  itemcode = row['itemcode'];

                  element += '<span id="show_text_qty" branch="'+outlet+'" detail_guid="'+guid_child2+'" itemcode="'+itemcode+'"><input type="number" data-column="5" class="to_update_cost edit_qty_actual" id="edit_qty_actual" name="edit_qty_actual" min="0" style="width:10vh;" value='+store_qty+' step="'+order_lot+'"></span>';

                  return element;

                }},
                {"data" : "proposed_foc", render:function( data, type, row ){

                  var element = '';

                  store_foc_qty = row['pochild2_transchild_guid']['qty_foc'];
                  outlet = row['pochild2_transchild_guid']['location'];
                  guid_child2 = row['pochild2_transchild_guid']['transchild2_guid'];
                  order_lot = row['orderlotsize'];
                  itemcode = row['itemcode'];

                  element += '<span id="show_foc_qty" branch="'+outlet+'" detail_guid="'+guid_child2+'" itemcode="'+itemcode+'"><input type="number" data-column="6" class="to_update_cost edit_foc_actual" id="edit_foc_actual" name="edit_foc_actual" min="0" style="width:10vh;" value='+store_foc_qty+'></span>';

                  return element;

                }},
                {"data" : "orderlotsize", render:function( data, type, row ){

                  var element = '';

                  store_price = row['pochild2_transchild_guid']['cost'];
                  outlet = row['pochild2_transchild_guid']['location'];
                  guid_child2 = row['pochild2_transchild_guid']['transchild2_guid'];
                  order_lot = row['orderlotsize'];
                  itemcode = row['itemcode'];

                  store_price = parseFloat(store_price);
                  store_price = store_price.toFixed(4);

                  element += '<span id="show_text_price" branch="'+outlet+'" detail_guid="'+guid_child2+'"><input type="number" data-column="7" class="to_update_cost edit_price_actual" id="edit_price_actual" name="edit_price_actual" min="0" style="width:15vh;text-align: right;" value='+store_price+'></span>';

                  return element;

                }},
                {"data" : "proposed_amount", render:function( data, type, row ){

                  if(row['amount'] == '' || row['amount'] == null){
                    var proposed_amount = 0;
                  }else{
                    var proposed_amount = row['amount'];
                  }

                  var element = '<span data-column="8" class="append_supplier_cost" id="append_supplier_cost">'+formatNumberWithCommas(proposed_amount)+'</span>';

                  return element;

                }},

                {"data" : "qty", render:function( data, type, row ){

                  var element = '<span data-column="9" class="append_supplier_qty" id="append_supplier_qty">'+row['qty']+'</span>';

                  return element;


                }},
                {"data" : "days", render:function( data, type, row ){
                
                  var days = row['pochild2_transchild_guid']['days'];
                  return Number(days).toFixed(0);

                }},
                {"data" : "qty_bal", render:function( data, type, row ){

                  var qty_bal = row['pochild2_transchild_guid']['qty_bal'];
                  return parseFloat(qty_bal).toFixed(1);

                }},

                // if(show_info == 0){
                  {"data" : "ads", render:function( data, type, row ){

                    var ads = row['pochild2_transchild_guid']['ads'];
                    return Number(ads).toFixed(0);
                    // parseFloat

                  }},
                  {"data" : "aws", render:function( data, type, row ){

                    var aws = row['pochild2_transchild_guid']['aws'];
                    return parseFloat(aws).toFixed(1);

                  }},
                  {"data" : "ams", render:function( data, type, row ){

                    var ams = row['pochild2_transchild_guid']['ams'];
                    return Number(ams).toFixed(0);

                  }},
                  {"data" : "qty_tbr", render:function( data, type, row ){

                    var qty_tbr = row['pochild2_transchild_guid']['qty_tbr'];
                    return parseFloat(qty_tbr).toFixed(1);

                  }},
                  {"data" : "qty_opn", render:function( data, type, row ){

                    var qty_opn = row['pochild2_transchild_guid']['qty_opn'];
                    return parseFloat(qty_opn).toFixed(1);

                  }},
                  {"data" : "qty_rec", render:function( data, type, row ){

                    var qty_rec = row['pochild2_transchild_guid']['qty_rec'];
                    return parseFloat(qty_rec).toFixed(1);

                  }},
                  {"data" : "qty_sold", render:function( data, type, row ){ 

                    var qty_sold = row['pochild2_transchild_guid']['qty_sold'];
                    return parseFloat(qty_sold).toFixed(1);

                  }},
                  {"data" : "qty_other", render:function( data, type, row ){ 

                    var qty_other = row['pochild2_transchild_guid']['qty_other'];
                    return parseFloat(qty_other).toFixed(1);

                  }},
                // }
              ],
              dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip", 
          "language": {
                            "lengthMenu": "Show _MENU_",
                            "infoEmpty": "No records available",
                            "infoFiltered": "(filtered from _MAX_ total records)",
                            "zeroRecords": "<span style='margin-left:13vh;'><?php echo '<b>No Record Found.</b>'; ?></span>",
                    }, 
          "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              $(nRow).closest('tr').css({"cursor": "pointer"});
              $(nRow).attr('outlet_code', outlet_code);
              $(nRow).attr('outlet_desc', outlet_desc);
              $(nRow).attr('detail_guid', aData['pochild2_transchild_guid']['transchild2_guid']);

              $(nRow).find('td:eq(13)').css({"background-color":"#ffb84d","color":"black"});
              $(nRow).find('td:eq(14)').css({"background-color":"#ffb84d","color":"black"});
              $(nRow).find('td:eq(15)').css({"background-color":"#ffb84d","color":"black"});

              $(nRow).find('td:eq(12)').css({"background-color":"#4dff4d","color":"black"});

            // $(nRow).attr('status', aData['status']);
          },
          "initComplete": function( settings, json ) {
            interval();

          },
          "footerCallback": function ( row, data, start, end, display ) {
              var api = this.api(), data;

              var data = $.fn.dataTable.render.number( '\,', '.', 2, 'RM' ).display;

              // Remove the formatting to get integer data for summation
              var intVal = function ( i ) {
                  return typeof i === 'string' ?
                      i.replace(/[\$,]/g, '')*1 :
                      typeof i === 'number' ?
                          i : 0;
              };
              // Total over all pages
              total = api
                  .column( 3 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
                  
              // Total over this page
              pageTotal = api
                  .column( 4, { page: 'current'} )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );

              // total_sys = api
              //     .column( 9 )
              //     .data()
              //     .reduce( function (a, b) {
              //         return intVal(a) + intVal(b);
              //     }, 0 );
                  
              // Total over this page
              pageTotal = api
                  .column( 9, { page: 'current'} )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              
              // Update footer
              $( '.edit_total_sum' ).html(
                  /*''+(pageTotal).toFixed(2) +' <hr>'+ (total).toFixed(2)+''*/
                  (total).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+''
              );

              // Update footer
              // $( '.edit_total_sum_sys' ).html(
              //     /*''+(pageTotal).toFixed(2) +' <hr>'+ (total).toFixed(2)+''*/
              //     (total_sys).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+''
              // );
              }
          });//close datatable

          var maxQtyWidth = 0;
          var maxFOCWidth = 0;
          var maxCostWidth = 0;
            
          // Loop over each input field with class "to_update_cost"
          $('input[name="edit_qty_actual"]').each(function() {
            var value = $(this).val();
            var width = value.length * 5 + 25;
              
            // Update the maximum width if necessary
            if (width > maxQtyWidth) {
              maxQtyWidth = width;
            }
          });

          $('input[name="edit_foc_actual"]').each(function() {
            var value = $(this).val();
            var width = value.length * 5 + 25;
              
            // Update the maximum width if necessary
            if (width > maxFOCWidth) {
              maxFOCWidth = width;
            }
          });

          $('input[name="edit_price_actual"]').each(function() {
            var value = $(this).val();
            var width = value.length * 5 + 25;
              
            // Update the maximum width if necessary
            if (width > maxCostWidth) {
              maxCostWidth = width;
            }
          });
            
          // Apply the maximum width to all input fields
          $('input[name="edit_qty_actual"]').width(maxQtyWidth);
          $('input[name="edit_foc_actual"]').width(maxFOCWidth);
          $('input[name="edit_price_actual"]').width(maxCostWidth);

          $('#edit_table_by_outlet').on('click', 'input[type="number"]', function() {
            $(this).select();
            $(this).removeClass('input-error');
          });

          $('.to_update_cost').on('input', function() {

            var maxQtyWidth = 0;
            var maxFOCWidth = 0;
            var maxCostWidth = 0;
            
            // Loop over each input field with class "to_update_cost"
            $('input[name="edit_qty_actual"]').each(function() {
              var value = $(this).val();
              var width = value.length * 5 + 25;
              
              // Update the maximum width if necessary
              if (width > maxQtyWidth) {
                maxQtyWidth = width;
              }
            });

            $('input[name="edit_foc_actual"]').each(function() {
              var value = $(this).val();
              var width = value.length * 5 + 25;
              
              // Update the maximum width if necessary
              if (width > maxFOCWidth) {
                maxFOCWidth = width;
              }
            });

            $('input[name="edit_price_actual"]').each(function() {
              var value = $(this).val();
              var width = value.length * 5 + 25;
              
              // Update the maximum width if necessary
              if (width > maxCostWidth) {
                maxCostWidth = width;
              }
            });
            
            // Apply the maximum width to all input fields
            $('input[name="edit_qty_actual"]').width(maxQtyWidth);
            $('input[name="edit_foc_actual"]').width(maxFOCWidth);
            $('input[name="edit_price_actual"]').width(maxCostWidth);

            var currentRow = $(this).closest('tr');
            var columnNumber = $(this).data('column');
            var value = $(this).val();

            var qty_value = currentRow.find('#edit_qty_actual').val();
            var foc_value = currentRow.find('#edit_foc_actual').val();
            var price_value = currentRow.find('#edit_price_actual').val();

            var formattedAmount = Number(qty_value*price_value).toFixed(2);
            var formattedQuantity = Number(parseFloat(qty_value)+parseFloat(foc_value)).toFixed(2);

            if(formattedQuantity == '' || formattedQuantity == null || formattedQuantity == 'NaN' || formattedQuantity == 'null'){
              formattedQuantity = 0;
            }

            $(".append_supplier_cost", currentRow).text(formatNumberWithCommas(formattedAmount));
            $(".append_supplier_qty", currentRow).text(formattedQuantity);

            var sum = 0;
            $('#edit_table_by_outlet tr').each(function() {
              var cellValue = $(this).find('td').eq(4).text();
              var numericValue = parseFloat(cellValue.replace(/,/g, ""));
              if (!isNaN(numericValue)) {
                sum += numericValue;
              }
            });

            var total_sum = Number(sum).toFixed(2);

            $('#total_propose_amount').text(formatNumberWithCommas(total_sum));

            var sum_qty = 0;
            $('#edit_table_by_outlet tr').each(function() {
              var qty = parseFloat($(this).find('#edit_qty_actual').val());
              var foc = parseFloat($(this).find('#edit_foc_actual').val());
              if (!isNaN(qty) && !isNaN(foc)) {
                sum_qty += qty + foc;
              }
            });

            var total_sum_qty = (Number.isInteger(sum_qty)) ? Number(sum_qty).toFixed(0) : Number(sum_qty).toFixed(2);

            $('#total_propose_quantity').text(total_sum_qty);

          });

        $('.btn').button('reset');
      }//close success
    });//close ajax 
  });

  $('#propose_medium_by_outlet-modal').on('hidden.bs.modal', function() {
    datatable1(retailer,'','','',1);
    datatable2();
    // datatable3(retailer,'','','',1);
  });

  // setTimeout(function() {
  //  datatable1(retailer,outright_code,outright_location,delivery_date,1);
  //  $('.btn').button('reset');
  // }, 300);

  $(document).on('click', '.btn_list_by_outlet', function(event){
    $('.table_list_by_item').addClass('hidden');
    $('.table_list_by_outlet').removeClass('hidden');
    $('.btn_list_by_outlet').addClass('active');
    $('.btn_list_by_item').removeClass('active');
  });

  $(document).on('click', '.btn_list_by_item', function(event){
    $('.table_list_by_item').removeClass('hidden');
    $('.table_list_by_outlet').addClass('hidden');
    $('.btn_list_by_item').addClass('active');
    $('.btn_list_by_outlet').removeClass('active');
  });

  $(document).on('click', '#btn_view_cost_summary', function(event){
   
   $.ajax({
     url : "<?php echo site_url('Propose_po/view_cost_summary');?>",
     method: "POST",
     // data:{guid:guid, details:details},
     dataType: 'html',   
     success: function(html) {
         $('#tableViewSummary').html(html);
         $('#modalViewSummary').show();
     },
     error: function(xhr, ajaxOptions, thrownError) {
       alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
     }

   }); 

 });

  $(document).on('click', '#btn_view_cost_summary_single_outlet', function(event){
   
    $.ajax({
      url : "<?php echo site_url('Propose_po/view_cost_summary');?>",
      method: "POST",
      // data:{guid:guid, details:details},
      dataType: 'html',   
      success: function(html) {
          $('#tableViewSummary').html(html);
          $('#modalViewSummary').show();
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }

    }); 

  });

  $('#btn_close_modal1, #btn_close_modal2').click(function() {
    $("#modalViewSummary").hide();
  });

  $(document).on('click', '#btn_print_document', function(event){

    $('#embed_loader').show();

    var doc_refno = $('#doc_refno').val();
    var report_url = "<?php echo site_url('Propose_po/print_document')?>";
    var param_refno = '?refno='+doc_refno;
    var src = report_url+param_refno;

    $('#embed_document').attr("src", src);
    $('#modalPrintDocument').fadeIn();
  });

  $('#close_modalPrintDocument1, #close_modalPrintDocument2').click(function() {
    $("#modalPrintDocument").hide();
  });

  $(document).on('click','#update_propose_btn',function(){
    var details = [];
    shoot_link = 0;

    var retailer = $('#retailer').val();
    var outright_code = $('#outright_code').val();
    var outright_location = $('#outright_location').val();
    var delivery_date = $('#delivery_date').val().split('  ')[0].trim();

    $('#edit_table tbody tr ').each(function(){
      $('#edit_qty_actual').prop('disabled', false);
      $('#edit_foc_actual').prop('disabled', false);
      $('#edit_price_actual').prop('disabled', false);
      $('#edit_supplier_doh').prop('readonly', false);
      var detail_guid = $(this).attr('detail_guid');
      var order_lot = $(this).attr('order_lot');
      var itemcode = $(this).attr('itemcode');
      // var qty_actual = $(this).attr('supplier_qty_actual');
      // var price_actual = $(this).attr('supplier_price_actual');
      // var foc_actual = $(this).attr('supplier_foc_actual');
      
      // var qty_actual = $('#edit_table').DataTable().rows().nodes().to$().find('#edit_qty_actual').val();
      var qty_actual = $(this).find('#edit_qty_actual').val();
      var price_actual = $(this).find('#edit_price_actual').val();
      var foc_actual = $(this).find('#edit_foc_actual').val();

      // if(parseInt(qty_actual) < 0)
      // {

      //   Swal.fire(
      //     'Error : Quantity',
      //     'Quantity cannot be less than 0',
      //     'error'
      //   );

      //   shoot_link = shoot_link+1;
      //   return false;
      // }

      if(parseInt(foc_actual) < 0)
      {

        Swal.fire(
          'Error : FOC',
          'FOC cannot be less than 0',
          'error'
        );

        shoot_link = shoot_link+1;
        return false;
      }
      
      if(parseFloat(price_actual) == 0 && qty_actual != 0)
      {

        Swal.fire(
          'Error : Cost',
          'Cost must have amount value.',
          'error'
        );

        shoot_link = shoot_link+1;
        return false;
      }

      if(parseFloat(price_actual) < 0)
      {

        Swal.fire(
          'Error : Cost',
          'Cost cannot be less than 0',
          'error'
        );

        shoot_link = shoot_link+1;
        return false;
      }

      if(qty_actual != 0)
      {
        if(order_lot != 1 && order_lot != 0)
        {
          if(qty_actual % order_lot !== 0)
          {

            Swal.fire(
              'Item Code: '+itemcode+'\n'+'Order Lot: '+order_lot+'\n',
              'Please make sure your quantity increase order by Order Lot.',
              'error'
            );
            
            shoot_link = shoot_link+1;
            return false;
          }
        }
      }

      details.push({'detail_guid':detail_guid,'qty_actual':qty_actual,'price_actual':price_actual,'foc_actual':foc_actual});

    });

    if(shoot_link == 0)
    {
      $.ajax({
        url:"<?php echo site_url('Propose_po/save_propose_child_new') ?>",
        method:"POST",
        data:{details:details,retailer:retailer},
        beforeSend:function(){
          $('.btn').button('loading');
          $('#loader_div').removeClass('hidden');
          $('#loader_div').fadeIn();
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.status == 1) {
            
            Swal.fire(
              json.message,
              '',
              'success'
            );

            $("#propose_medium-modal").modal('toggle');
            // $("#propose_medium-modal").hide();

            setTimeout(function() {
              datatable1(retailer,outright_code,outright_location,delivery_date,1);
              $('.btn').button('reset');
            }, 300);
          }else{
            
            Swal.fire(
              'Error : ' +json.error,
              json.message,
              'error'
            );

          }//close else
          
          $('#loader_div').addClass('hidden');
          $('#loader_div').fadeOut();

          $('.btn').button('reset');
        }//close success
      });//close ajax
    }

    $("body").css("padding-right", "");
 
  });

  $(document).on('click','#propose_btn_header',function(){

    var text_message = "After submit you cannot edit this document again!";
    var itemcount_foc = 0;
    var itemcount_cost = 0;
    var itemcount_0_qty = 0;
    var itemcount_qty_w_0_cost = 0;
    var message = "";
    var cost_message = "";
    var outlet_count = $('#outright_location option:selected').length;

    if(outlet_count == 1){
      var table = '#table3';
    }else{
      var table = '#table1';
    }
    
    var rowCount = $(table + ' tbody tr').length;

    if(rowCount == 0){

      Swal.fire(
        'No item listed, cannot proceed to submit the proposed document.',
        '',
        'error'
      );

      return;
    }

    $(table + " tbody tr").each(function() {
      var itemcode = $(this).find("#itemcode_c").text();
      var description = $(this).find("#description_c").text();
      var ctn_order_qty = parseFloat($(this).find("#edit_ctn_qty_actual").val());
      var order_qty = parseFloat($(this).find("#edit_qty_actual").val());
      var foc_qty = parseFloat($(this).find('#edit_foc_actual').val());
      var propose_cost = parseFloat($(this).find('#edit_price_actual').val());
      var ori_cost = $(this).attr('ori_cost');

      if (isNaN(ctn_order_qty)) {
        ctn_order_qty = 0;
      }

      if (isNaN(order_qty)) {
        order_qty = 0;
      }

      if (isNaN(foc_qty)) {
        foc_qty = 0;
      }

      if (isNaN(propose_cost)) {
        propose_cost = 0;
      }

      if(ctn_order_qty == 0 && order_qty == 0 && foc_qty == 0){
        itemcount_0_qty++;
      }

      if(propose_cost == 0 && (ctn_order_qty > 0 || order_qty > 0)){
        $(this).find('#edit_price_actual').addClass('input-error');
        itemcount_qty_w_0_cost++;
      }

      if(ctn_order_qty == 0 && order_qty == 0 && foc_qty > 0){
        $(this).find('#edit_foc_actual').addClass('input-error');
        message += "<li style='text-align: left;'>" + itemcode + " - " + description + "</li>";
        itemcount_foc++;
      }

      if(parseFloat(propose_cost) != parseFloat(ori_cost)){
        $(this).find('#edit_price_actual').addClass('input-error');
        cost_message += "<li style='text-align: left;'>" + itemcode + " - " + description + " <span style='font-weight: bold;'>";
        cost_message += "</br>[Original Cost : " + Number(parseFloat(ori_cost)).toFixed(2) + "] &#8594; [Proposed Cost : " + Number(parseFloat(propose_cost)).toFixed(2) + "]</span></li>";
        itemcount_cost++;
      }

    });

    if(rowCount == itemcount_0_qty){
      Swal.fire(
        'No item is being proposed, cannot proceed to submit this proposed document.',
        '',
        'error'
      );

      return false;
    }

    if(itemcount_qty_w_0_cost != 0){

      Swal.fire(
        'Cannot proceed to submit, Non-FOC item with Cost=0 Exist!',
        '',
        'error'
      );

      return false;
    }

    if(itemcount_foc != 0){
      text_message = '</br>';
      text_message += "<p style='text-align: left; font-weight: bold; padding-left: 2vw;'>There are FOC item with 0 quantity: </p>";
      text_message += '<ul>';
      text_message += message;
      text_message += "</ul>";
    }

    if(itemcount_cost != 0){
        
      if(itemcount_foc != 0){
        text_message += '</br>';
        text_message += "<p style='text-align: left; font-weight: bold; padding-left: 2vw;'>New cost were proposed for these items: </p>";
      }else{
        text_message = "<p style='text-align: left; font-weight: bold; padding-left: 2vw;'>New cost were proposed for these items: </p>";
      }
        
      text_message += '<ul>';
      text_message += cost_message;
      text_message += "</ul>";
    }

    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var month = ('0' + (currentDate.getMonth() + 1)).slice(-2); // Months are zero-based
    var day = ('0' + currentDate.getDate()).slice(-2);
    var formattedDate = year + '-' + month + '-' + day;

    var retailer = $(this).attr('retailer');
    var sup_code = $(this).attr('sup_code');
    var transmain_guid = '<?php echo $_SESSION['transmain_guid'] ?>';
    var delivery_date = $('#delivery_date').val().split('  ')[0].trim();

    var customClass = {
      header: 'my-swal-header',
      content: 'my-swal-text',
    };

    if (itemcount_foc != 0 || itemcount_cost != 0) {
      customClass.container = 'custom-swal-modal';
    }

    Swal.fire({
      title: 'Submit this document for approval?',
      html: text_message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, confirm!',
      cancelButtonText: 'No, cancel',
      customClass: customClass
    }).then((result) => {

      if (result.value) {

        if(delivery_date == '' || delivery_date == null)
        {

          Swal.fire(
            'Please Choose Delivery Date',
            '',
            'error'
          );

          return;
        }

        if(delivery_date < formattedDate)
        {

          Swal.fire(
            'Please select a delivery date greater than or equal to today.',
            '',
            'error'
          );

          return;
        }

        $.ajax({
          url:"<?php echo site_url('Propose_po/confirm_propose') ?>",
          method:"POST",
          data:{supplier_detail:sup_code,retailer:retailer,transmain_guid:transmain_guid,delivery_date:delivery_date},
          beforeSend:function(){
            $('.btn').button('loading');  
            $('#loader_div').removeClass('hidden');
            $('#loader_div').fadeIn();
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.status == 1) {
                
              Swal.fire(
                json.message,
                '',
                'success'
              );

              setTimeout(function(){
                window.location.href = '<?php echo site_url("Propose_po/propose_record"); ?>';
              },500);

            }else{

              Swal.fire(
                json.message,
                '',
                'error'
              );

              $('.btn').button('reset');

              if(json.force_refresh !== undefined){
                if(json.force_refresh == true){
                  setTimeout(function(){
                    window.location.href = '<?php echo site_url("Propose_po/propose_record"); ?>';
                  },500);
                }
              }

            }

            $('#loader_div').addClass('hidden');
            $('#loader_div').fadeOut();
          }
        });

      } else if (result.dismiss === Swal.DismissReason.cancel) {
        // User clicked the "No, cancel" button or pressed Esc
        // Swal.fire(
        //   'Cancelled',
        //   'You may re-submit again',
        //   'error'
        // )

        return;
      }
    });
  });

  $(document).on('change', '#doc_type', function(){
    $('#insert_refno').val('');
  });//CLOSE ONCLICK  

  $(document).on('click', '#outright_location_all', function(){
    // alert();
    $("#outright_location option").prop('selected',true);
    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('click', '#outright_location_all_dis', function(){
    // alert();
    $("#outright_location option").prop('selected',false);
    $(".select2").select2();
  });//CLOSE ONCLICK  

  function resizeSelect() {
    var select = document.getElementById("outright_location");
    var selectedOptions = select.selectedOptions;
    var numSelectedOptions = selectedOptions.length;
    select.style.height = (numSelectedOptions * 25) + "px";
  }

  var timeoutId;

  $(document).on('input', '#table3 tbody tr', function(event){

    clearTimeout(timeoutId);
    $('#floatingButton').addClass('hidden');

    var details = [];
    var guid = '<?php echo $_GET["id"] ?>';
    var retailer = $('#retailer').val();
      
    var table = $('#table3').DataTable();
    var currentRow = $(this).closest('tr');

    var detail_guid = $(this).attr('detail_guid');
    var order_lot_c = $(this).find('#order_lot_c').text();
    var ctn_qty_c = $(this).find('#ctn_qty_c').text();
    var packsize_c = $(this).find('#packsize_c').text();
    var ctn_qty_value = $(this).find('#edit_ctn_qty_actual').val();
    var qty_value = $(this).find('#edit_qty_actual').val();
    var foc_value = $(this).find('#edit_foc_actual').val();
    var price_value = $(this).find('#edit_price_actual').val();

    if(parseInt(ctn_qty_value) < 0)
    {
      alert('Bulk Quantity cannot be less than 0');
      return false;
    }

    if(parseInt(qty_value) < 0)
    {
      alert('Quantity cannot be less than 0');
      return false;
    }

    if(parseInt(foc_value) < 0)
    {
      alert('FOC cannot be less than 0');
      return false;
    }
      
    if(parseFloat(price_value) < 0 && qty_value != 0)
    {
      alert('Cost must have amount value.');
      return false;
    }

    if(parseInt(price_value) < 0)
    {
      alert('Cost cannot be less than 0');
      return false;
    }

    if(parseInt(order_lot_c) != 0 && parseInt(order_lot_c) != 1)
    {
      var total_quantity = (ctn_qty_value*ctn_qty_c*packsize_c) + (qty_value*packsize_c) + (foc_value*packsize_c);
      console.log(total_quantity);
      console.log(parseInt(order_lot_c));
      console.log(total_quantity % parseInt(order_lot_c));
      if(total_quantity % parseInt(order_lot_c) != 0){

        $('#floatingButton').text('Failed, total quantity must be aligns with the order lot size');
        $('#floatingButton').removeClass('btn-success');
        $('#floatingButton').addClass('btn-danger');
        $('#floatingButton').removeClass('hidden');
        $('#floatingButton').fadeIn().delay(1000).fadeOut();

        return false;
      }
    }

    if(parseInt(ctn_qty_value) > 0 || parseInt(qty_value) > 0 || parseInt(foc_value) > 0){
      currentRow.addClass("highlighted-row");
    }else{
      currentRow.removeClass("highlighted-row");
    }

    if(!isNaN(ctn_qty_c) && parseInt(ctn_qty_value) > 0){
      qty_value = Number(parseFloat(ctn_qty_value*ctn_qty_c)+parseFloat(qty_value)).toFixed(2);
    }

    var formattedAmount = Number(qty_value*price_value).toFixed(2);

    details.push({'detail_guid':detail_guid,'qty_actual':qty_value,'price_actual':price_value,'foc_actual':foc_value});

    timeoutId = setTimeout(function() {
      $.ajax({
        url:"<?php echo site_url('Propose_po/save_propose_child_new') ?>",
        method:"POST",
        data:{details:details,retailer:retailer},
        success:function(data)
        {
          json = JSON.parse(data);

          if(json.status == 1){
            $('#floatingButton').text('Saved');
            $('#floatingButton').removeClass('btn-danger');
            $('#floatingButton').addClass('btn-success');
          }else{
            $('#floatingButton').text(json.message);
            $('#floatingButton').removeClass('btn-success');
            $('#floatingButton').addClass('btn-danger');
          }

          $('#floatingButton').removeClass('hidden');
          $('#floatingButton').fadeIn().delay(500).fadeOut();
        }
      });
    }, 500);

  });

  var timeoutIdByOutlet;

  $(document).on('input', '#edit_table_by_outlet tbody tr', function(event){

    clearTimeout(timeoutIdByOutlet);

    var details = [];
    var guid = '<?php echo $_GET["id"] ?>';
    var retailer = $('#retailer').val();
      
    var table = $('#edit_table_by_outlet').DataTable();
    var currentRow = $(this).closest('tr');

    var detail_guid = $(this).attr('detail_guid');
    var qty_value = $(this).find('#edit_qty_actual').val();
    var foc_value = $(this).find('#edit_foc_actual').val();
    var price_value = $(this).find('#edit_price_actual').val();

    if(parseInt(qty_value) < 0)
    {
      alert('Quantity cannot be less than 0');
      return false;
    }

    if(parseInt(foc_value) < 0)
    {
      alert('FOC cannot be less than 0');
      return false;
    }
      
    if(parseFloat(price_value) < 0 && qty_value != 0)
    {
      alert('Cost must have amount value.');
      return false;
    }

    if(parseInt(price_value) < 0)
    {
      alert('Cost cannot be less than 0');
      return false;
    }

    var formattedAmount = Number(qty_value*price_value).toFixed(2);

    details.push({'detail_guid':detail_guid,'qty_actual':qty_value,'price_actual':price_value,'foc_actual':foc_value});

    timeoutIdByOutlet = setTimeout(function() {
      $.ajax({
        url:"<?php echo site_url('Propose_po/save_propose_child_new') ?>",
        method:"POST",
        data:{details:details,retailer:retailer},
        success:function(data)
        {
          json = JSON.parse(data);

          if(json.status == 1){
            $('#floatingButton').text('Saved');
            $('#floatingButton').removeClass('btn-danger');
            $('#floatingButton').addClass('btn-success');
          }else{
            $('#floatingButton').text(json.message);
            $('#floatingButton').removeClass('btn-success');
            $('#floatingButton').addClass('btn-danger');
          }

          $('#floatingButton').removeClass('hidden');
          $('#floatingButton').fadeIn().delay(500).fadeOut();
        }
      });
    }, 500);

  });

  var timeoutIdremark;

  $(document).on('input', 'textarea[name="remark_h"]', function(event){

    var have_id = '<?php echo isset($_GET['id']) ? 1 : 0 ?>';
    var guid = '<?php echo $_GET["id"] ?>';
    var retailer = $('#retailer').val();

    clearTimeout(timeoutIdremark);

    var remark_value = $('textarea[name="remark_h"]').val();

    timeoutIdremark = setTimeout(function() {
      $.ajax({
        url:"<?php echo site_url('Propose_po/update_header_info') ?>",
        method:"POST",
        data:{retailer:retailer,guid:guid,remark_h:remark_value},
        success:function(data)
        {

          json = JSON.parse(data);

          if(json.status == 1){
            $('#floatingButton').text('Saved');
            $('#floatingButton').removeClass('btn-danger');
            $('#floatingButton').addClass('btn-success');
          }else{
            $('#floatingButton').text(json.message);
            $('#floatingButton').removeClass('btn-success');
            $('#floatingButton').addClass('btn-danger');
          }

          $('#floatingButton').removeClass('hidden');
          $('#floatingButton').fadeIn().delay(500).fadeOut();
        }
      });
    }, 1500);

  });

  var timeoutIddeliverydate;

  $(document).on('change', '#delivery_date', function(event){

    var guid = '<?php echo $_GET["id"] ?>';
    var retailer = $('#retailer').val();
    var delivery_date = $('#delivery_date').val().split('  ')[0].trim();

    clearTimeout(timeoutIddeliverydate);

    timeoutIddeliverydate = setTimeout(function() {
      $.ajax({
        url:"<?php echo site_url('Propose_po/update_header_info') ?>",
        method:"POST",
        data:{retailer:retailer,guid:guid,delivery_date:delivery_date},
        success:function(data)
        {

          json = JSON.parse(data);

          if(json.status == 1){
            $('#floatingButton').text('Saved');
            $('#floatingButton').removeClass('btn-danger');
            $('#floatingButton').addClass('btn-success');
          }else{
            $('#floatingButton').text(json.message);
            $('#floatingButton').removeClass('btn-success');
            $('#floatingButton').addClass('btn-danger');
          }

          $('#floatingButton').removeClass('hidden');
          $('#floatingButton').fadeIn().delay(500).fadeOut();
        }
      });
    }, 500);

  });

});
</script>

<script text="text/javascript">
  $(document).ready(function() {

    // if(delivery_date == '' || delivery_date == null){}
    // Get the current date and time
    var today = new Date();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var day = String(today.getDate()).padStart(2, '0');
    var year = today.getFullYear();
    var hour = String(today.getHours()).padStart(2, '0');
    var minutes = String(today.getMinutes()).padStart(2, '0');

    // Format the date and time as YYYY-MM-DDThh:mm
    var formattedDate = year + '-' + month + '-' + day;
    var formattedTime = hour + ':' + minutes;
    var defaultDateTime = formattedDate + 'T' + formattedTime;

    // Set the default value of the datetime input field
    $('#delivery_date').val(defaultDateTime);
  });
</script>

<script text="text/javascript">

  function formatNumberWithCommas(number) {

    if(number == '' || number == null || number == 'NaN' || number == 'null'){
      number = 0;
    }

    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

</script>

<script type="text/javascript">

  $(document).ready(function() {
    var date = "<?php echo isset($_GET['id']) ? $delivery_date : "''" ?>";

    if(date != ''){
      var formattedDate = moment(date).format("YYYY-MM-DD  ddd");
      $("#delivery_date").val(formattedDate);
    }

    $('#delivery_date').on('keydown paste', function(e) {
      e.preventDefault();
    });
  });

  $(function() {

    var current_date = "<?php echo date('Y-m-d') ?>";
    var date = "<?php echo isset($_GET['id']) ? $delivery_date : date('Y-m-d') ?>";

    $('input[name="delivery_date"]').daterangepicker({
      startDate: date,
      minDate: current_date,
      locale: {
        format: 'YYYY-MM-DD  ddd'
      },
      singleDatePicker: true,
      showDropdowns: true,
      autoUpdateInput: true,
    });
    // $(this).find('[name="delivery_date"]').val("");
  });
</script>

<?php if($doc_status == 'CANCELLED'){ ?>
  <script text="text/javascript">

  $(document).ready(function() {
    $('.btn:not(.no_hide)').hide();
    $('input[type="text"]').prop('disabled', true);
    $('input[type="number"]').prop('disabled', true);
    $('input[type="datetime"]').prop('disabled', true);
    $('textarea').prop('disabled', true);

    $(document).on('init.dt', 'table', function () {
        $(this).find('select').prop('disabled', true);
        $(this).find('input[type="text"]').prop('disabled', true);
        $(this).find('input[type="number"]').prop('disabled', true);
        $(this).find('input[type="datetime"]').prop('disabled', true);
    });
  });

  </script>
<?php } ?>
