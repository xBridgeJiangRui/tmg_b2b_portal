<style>
.content-wrapper{
  min-height: 700px !important; 
}

.alignright {
  text-align: right;
}

.alignleft
{
  text-align: left;
}

.highlight-supplier {
  background-color: yellow;
}

.supplier_propose {
  background-color: #80ff80;
}

.retailer_propose {
  background-color: #34ebe8;
}

.item_value {
  background-color: #ffb84d;
}

.no-select {
  -webkit-user-select: none; /* Safari */
  -moz-user-select: none; /* Firefox */
  -ms-user-select: none; /* Internet Explorer/Edge */
  user-select: none;
}

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

#view_complete_list {
  margin: 0;
  padding: 0;
}

#view_complete_list td, #view_complete_list th {
  padding: 5px; /* Adjust the padding as needed */
}

</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
  <div class="container-fluid">
  <br>
    <div class="col-md-12">
      <a class="btn btn-app active" style="color:grey" id="btn_view_pending">
        <i class="fa fa-spinner"></i> Pending 
      </a>
      <a class="btn btn-app " style="color:grey" id="btn_view_complete">
        <i class="fa fa-check-square"></i> Completed 
      </a>
      <a class="btn btn-app " style="color:grey" id="btn_view_cancel">
        <i class="fa fa-remove"></i> Cancelled 
      </a>

      <!-- <a class="btn btn-app" style="color:#D73925" onclick="filter_status(2)" title="Rejected">
        <i class="fa fa-window-close"></i> Rejected 
      </a>
      <a class="btn btn-app" style="color:#a320f5" onclick="filter_status(5)" title="All">
        <i class="fa fa-list"></i> All 
      </a> -->

      <a class="btn btn-app pull-right" id="propose_btn_header" style="color:#000000" href="<?php echo site_url('Propose_po/propose_details');?>">
        <i class="fa fa-plus-circle"></i> Create New
      </a>

    </div>

    <div class="row class_pending_list">
      <div class="col-md-12 col-xs-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> Pending Proposed PO List <span class="pill_button"><?php echo $retailer; ?></span></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body" >

            <table id="view_pending_list" class="table table-bordered table-hover" width="100%" cellspacing="0">
              <thead style="white-space: nowrap;">
              <tr>
                <th>No</th>
                <th>Ref No</th>
                <th>Supplier</th>
                <th>Delivery Date</th>
                <th>Created By</th>
                <th>Updated By</th> 
                <th>Action</th> 
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row class_complete_list">
      <div class="col-md-12 col-xs-12">
        <div class="box" style="overflow-x: auto; white-space: nowrap;">
          <div class="box-header with-border">
            <h3 class="box-title"> Completed Proposed PO List <span class="pill_button"><?php echo $retailer; ?></span></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body" >

            <table id="view_complete_list" class="table table-bordered table-hover" width="100%" cellspacing="0">
              <thead style="white-space: nowrap;">
              <tr>
                <th>No</th>
                <th>Ref No</th>
                <th>Outlet</th>
                <th>Supplier</th>
                <th>Doc Date</th>
                <th>Delivery Date</th>
                <!-- <th>Created By</th> -->
                <th>Posted By</th>
                <th>PO No</th> 
                <th>Status</th> 
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row class_cancel_list">
      <div class="col-md-12 col-xs-12">
        <div class="box" style="overflow-x: auto; white-space: nowrap;">
          <div class="box-header with-border">
            <h3 class="box-title"> Cancelled Proposed PO List <span class="pill_button"><?php echo $retailer; ?></span></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body" >

            <table id="view_cancel_list" class="table table-bordered table-hover" width="100%" cellspacing="0">
              <thead style="white-space: nowrap;">
              <tr>
                <th>No</th>
                <th>Ref No</th>
                <th>Supplier</th>
                <th>Delivery Date</th>
                <th>Created By</th>
                <th>Cancelled By</th> 
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row class_complete_list_header hidden">
      <div class="col-md-12 col-xs-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> Proposed Purchase Order (PO)<span class="add_branch_list"></span></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body" >

            <table id="view_complete_list_header" class="table table-bordered table-hover" width="100%" cellspacing="0">
              <thead style="white-space: nowrap;">
              <tr>
                <!-- <th>No</th> -->
                <th>Ref No</th>
                <th>Outlet</th>
                <th>Supplier</th>
                <th>Doc Date</th>
                <th>Doc Expiry Date</th>
                <th>Delivery Date</th>
                <!-- <th>Created By</th> -->
                <th>Posted By</th>
                <th>PO No</th>
                <?php if($header_list[0]['doc_status'] == 'REJECTED'){ ?>
                  <th>Reject Reason</th>
                <?php } ?> 
                <th>Status</th> 
              </tr>
              </thead>
              <tbody>
                <?php $count = 1; ?>
                <?php foreach ($header_list as $row){ ?>
                  <tr poex_guid="<?php echo $row['poex_guid'];?>">
                    <!-- <td><?php echo $count; ?></td> -->
                    <td><?php echo $row['refno'];?></td>
                    <td><?php echo $row['branch'];?> - <?php echo $row['branch_desc'];?></td>
                    <td><?php echo $row['sup_code'];?> - <?php echo $row['sup_name'];?></td>
                    <td><?php echo $row['docdate'];?></br><?php echo date('D', strtotime($row['docdate'])); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row['delivery_date'] . " +". $supcus_info[0]['pur_expiry_days'] ."days"));?></br><?php echo date('D', strtotime($row['delivery_date'] . " +". $supcus_info[0]['pur_expiry_days'] ."days"));?></td>
                    <td><?php echo $row['delivery_date'];?></br><?php echo date('D', strtotime($row['delivery_date'])); ?></td>
                    <td><?php echo $row['posted_by'];?></br><?php echo $row['posted_at'];?></td>
                    <td><?php echo $row['po_refno'];?></td>
                    <?php if($header_list[0]['doc_status'] == 'REJECTED'){ ?>
                      <td><a class="btn btn-xs btn-warning" id="view_reason_btn" data-cancel-reason="<?php echo $row['cancel_reason'];?>"><i class="fa fa-file"></i> View</a></td>
                    <?php } ?> 
                    <td>
                      <?php if($row['doc_status'] == 'APPROVED' || $row['doc_status'] == 'GENERATED'){ ?>
                        <button type="button" class="btn btn-xs btn-success"> <?php echo ucfirst($row['doc_status']) ?></button>
                      <?php }else if($row['doc_status'] == 'REJECTED'){ ?>
                        <button type="button" class="btn btn-xs btn-danger"> <?php echo ucfirst($row['doc_status']) ?></button>
                      <?php }else{ ?>
                        <button type="button" class="btn btn-xs btn-warning"> <?php echo ucfirst($row['doc_status']) ?></button>
                      <?php } ?>
                    </td>
                  </tr>
                  <?php $count++; ?>
                <?php } ?>
              </tbody>
            </table>
            
            <?php if($header_list[0]['remark_h'] != null){ ?>
            </br>
            <table width="50%" cellspacing="0">
              <tbody>
                <td style="width:10%; vertical-align: top;"><h5><b>Remark :</b></h5></td>
                <td><?php echo nl2br($header_list[0]['remark_h']); ?></td>
              </tbody>
            </table>
            <?php } ?>

          </div>

        </div>
      </div>
    </div>

    <div class="row class_complete_list_child hidden">
      <div class="col-md-12 col-xs-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> Proposed Details<span class="add_branch_list"></span></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body" >

            <table id="view_complete_list_child" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead style="white-space: nowrap;">
                <tr>
                    <th rowspan="2" colspan="1" style="text-align: left;" title="Sequence">Seq</th> 
                    <th rowspan="2" colspan="1" style="text-align: left;">Item Code </br>Item Link</th>
                    <th rowspan="2" colspan="1" style="text-align: left;">Barcode </br>Article No</th>
                    <th rowspan="2" colspan="1" style="text-align: left;">Description</th>
                    <th rowspan="2" colspan="1" style="text-align: left;" title="Pack Size
                    Unit of Measurement">PS </br>UM</th>
                    <th rowspan="2" colspan="1" style="text-align: left;" title="Order Lot Size
                    Carton Quantity">Ord Lot </br>Ctn Qty</th>
                    <th rowspan="1" colspan="3" style="text-align: left;">Supplier Propose</th>
                    <th rowspan="2" colspan="1" style="text-align: left;" title="Quantity On Hand">QOH</th>
                    <th rowspan="2" colspan="1" style="text-align: left;" title="Days On Hand">DOH</th>
                    <th rowspan="2" colspan="1" style="text-align: left;" title="To Be Return">TBR</th>

                    <?php if($show_info){ ?>
                      <th rowspan="2" colspan="1" style="text-align: left;" title="Average Daily Sold">ADS</th>
                      <th rowspan="2" colspan="1" style="text-align: left;" title="Average Weekly Sold">AWS</th>
                      <th rowspan="2" colspan="1" style="text-align: left;" title="Average Monthly Sold">AMS</th>
                      <!-- <th rowspan="2" colspan="1" style="text-align: left;">OPN</th>
                      <th rowspan="2" colspan="1" style="text-align: left;">GRN</th>
                      <th rowspan="2" colspan="1" style="text-align: left;">SOLD</th>
                      <th rowspan="2" colspan="1" style="text-align: left;">OTHERS</th> -->
                    <?php } ?>
                  </tr>
                  <tr>
                    <th style="text-align: left;" title="Quantity
                    Free of Charge">Qty </br> FOC</th>
                    <!-- <th>Qty FOC</th> -->
                    <th style="text-align: left;">Cost</th>
                    <th style="text-align: left;">Amount</th>
                </tr>
                </thead>
                <tbody>
                  <?php $count = 1; ?>
                  <?php foreach ($child_list as $row){ ?>
                    <tr guid="<?php echo $row['detail_guid'];?>">
                      <td><?php echo $row['seq'];?></td>
                      <?php if($row['itemcode'] == $row['itemlink']){ ?>
                        <td><?php echo $row['itemcode'];?></td>
                      <?php }else{ ?>
                        <td><?php echo $row['itemcode'];?></br><?php echo $row['itemlink'];?></td>
                      <?php } ?>
                      <td><?php echo $row['barcode'];?></br><?php echo $row['articleno'];?></td>
                      <td><?php echo $row['description'];?></td>
                      <td><?php echo $row['packsize'];?></br><?php echo $row['um'];?></td>
                      <td><?php echo $row['order_lot'];?></br><?php echo $row['bulkqty'];?></td>
                      <td><?php echo $row['qty_propose'];?></br><?php echo ($row['qty_foc_propose'] > 0) ? $row['qty_foc_propose'] : '' ;?></td>
                      <!-- <td><?php echo $row['qty_foc_propose'];?></td> -->
                      <td><?php echo number_format($row['price_propose'], 4, ".", ",");?></td>
                      <td><?php echo number_format($row['amount_propose'], 2, ".", ",");?></td>
                      <td class="supplier_propose"><?php echo number_format($row['qty_bal'],1, '.', '');?></td>
                      <?php if($row['doh'] < $row['stockday_min']){ ?>
                        <td style="background-color: red;"><?php echo number_format($row['doh'], 0, '.', '');?></td>
                      <?php }else{ ?>
                        <td><?php echo number_format($row['doh'], 0, '.', '');?></td>
                      <?php } ?>
                      <?php if($row['qty_tbr'] > 0){ ?>
                        <td style="background-color: red;"><?php echo number_format($row['qty_tbr'],1, '.', '');?></td>
                      <?php }else{ ?>
                        <td><?php echo number_format($row['qty_tbr'],1, '.', '');?></td>
                      <?php } ?>

                      <?php if($show_info){ ?>
                        <td class="item_value"><?php echo number_format($row['ads'], 0, '.', '');?></td>
                        <td class="item_value"><?php echo number_format($row['aws'],1, '.', '');?></td>
                        <td class="item_value"><?php echo number_format($row['ams'], 0, '.', '');?></td>
                        
                        <!-- <td><?php echo number_format($row['qty_opn'],1, '.', '');?></td>
                        <td><?php echo number_format($row['qty_rec'],1, '.', '');?></td>
                        <td><?php echo number_format($row['qty_sold'],1, '.', '');?></td>
                        <td><?php echo number_format($row['qty_other'],1, '.', '');?></td> -->
                      <?php } ?>

                    </tr>
                  <?php $count++; ?>
                  <?php } ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- <div id="reasonModal" class="modal hidden">
  <div class="modal-content">
    <span class="close">&times;</span>
    <p id="reasonDesc"></p>
  </div>
</div> -->

<div id="reasonModal" class="modal hidden" role="dialog">
  <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">          
          <h3 class="modal-title">Reject Reason<button type="button" id="btn_close_modal1" class="close" data-dismiss="modal">×</button></h3>
        </div>
        <div class="modal-body">
          <div class="box-body">
            <div class="row" style="overflow:auto;">
              <div><p id="reasonDesc"></p></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <!-- <input type="button" class="btn btn-default close" data-dismiss="modal" value="Close"> -->
        </div>
      </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Get the modal element
    var modal = $("#reasonModal");

    // When a supplier code is clicked, show the modal with the supplier name
    $("#view_reason_btn").on("click", function() {
      var cancelReason = $(this).data("cancel-reason");
      cancelReason = cancelReason.replace(/\n/g, '<br>');
      $("#reasonDesc").html(cancelReason);
      modal.removeClass('hidden');
      modal.show();
    });

    // When the user clicks on the close button, close the modal
    $(".close").on("click", function() {
      modal.hide();
    });

    // When the user clicks anywhere outside of the modal, close it
    $(window).on("click", function(event) {
      if (event.target == modal[0]) {
        modal.hide();
      }
    });
  });
</script>

<script type="text/javascript">

  $(document).ready(function() {

    var check_id = "<?php echo isset($_GET['id']) ? 1 : 0 ; ?>";

    // $('#view_pending_list thead tr').clone(true).addClass('filters').appendTo('#view_pending_list thead');

    var retailer = "<?php echo $_SESSION['customer_guid']; ?>";

    var table = $('#view_pending_list').DataTable({
        columnDefs: [
          { className: "aligncenter", targets: [0,6] },
          { className: "alignright", targets: [] },
          { className: "alignleft", targets: '_all' },
          // { width: '1%', targets: [0,3,6] },
          // { width: '12%', targets: [1,4,5] },
        ],
        filter      : true,
        // pageLength  : 10,
        processing  : true,
        serverSide  : true,
        paging      : true,
        lengthChange: true,
        lengthMenu  : [ [10, 25, 50, 100, 99999999], ['10', '25', '50', '100', 'ALL'] ],
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : false,
        bPaginate: true, 
        bFilter: true, 
        // sScrollY: "80vh", 
        // sScrollX: "100%", 
        sScrollXInner: "100%", 
        bScrollCollapse: true,
        orderCellsTop: true,
        fixedHeader: true,
        ajax: {
          url : "<?php echo site_url('Propose_po/view_header');?>",
          method: "POST",
          data:{datatable_load:1,retailer:retailer},
        },
        columns: [
          {"data": null, render: function (data, type, row, meta) {
            // Calculate and return the row index based on the visible rows
            var settings = meta.settings;
            var currentPage = settings._iDisplayStart;
            return currentPage + meta.row + 1;
          }},
          { "data": "refno" },
          {"data" : "", render:function( data, type, row ){

            var supplier_code = row['supplier_code'];
            var supplier_name = row['supplier_name'];

            return supplier_code + " - " + supplier_name;

          }},
          {"data" : "", render:function( data, type, row ){

            var delivery_date = row['delivery_date'];
            var date = new Date(delivery_date);
            var daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            var dayAbbreviation = daysOfWeek[date.getDay()];

            return delivery_date + "</br>" + dayAbbreviation;

          }},
          {"data" : "", render:function( data, type, row ){

            var created_by = row['created_by'];
            var created_at = row['created_at'];

            return created_by + "</br>" + created_at;

          }},
          {"data" : "", render:function( data, type, row ){

            var updated_by = row['updated_by'];
            var updated_at = row['updated_at'];

            return updated_by + "</br>" + updated_at;

          }},
          {"data" : "", render:function( data, type, row ){

            var refno = row['refno'];
            var transmain_guid = row['transmain_guid'];
            var url = "<?php echo site_url('Propose_po/propose_details?id=');?>" + transmain_guid;

            return '<a class="btn btn-xs btn-primary" id="edit_btn" href="'+url+'"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-xs btn-danger" id="cancel_btn" doc_refno="'+refno+'" transmain_guid="'+transmain_guid+'"><i class="fa fa-remove"></i> Cancel</a>';

          }},
        ],
        initComplete: function () {
          var api = this.api();
          api.columns().eq(0).each(function (colIdx) {

            var cell = $('.filters th').eq(
                $(api.column(colIdx).header()).index()
              );

            var title = $(cell).text();
            var dataTable = $('#view_pending_list').DataTable();
            var columnCells = dataTable.column(colIdx).nodes();
            var columnSize = $(columnCells[0]).width();

            if (colIdx == 0 || colIdx == 6) { 
              $(cell).html('');
            }else{
              $(cell).html('<input type="text" class="form-control" style="width:'+ columnSize +'px;" placeholder="' + title + '" />');
            }

             // On every keypress in this input
             $('input',$('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change').on('change', function (e) {
                              
              // Get the search value
              $(this).attr('title', $(this).val());
              var regexr = '({search})';
              var cursorPosition = this.selectionStart;
              
              // Search the column for that value
              api.column(colIdx).search(this.value != '' ? regexr.replace('{search}', '(((' + this.value + ')))') : '', this.value != '', this.value == '').draw();
            }).on('keyup', function (e) {
              
              e.stopPropagation();
              $(this).trigger('change');
              $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
            });
          });
        },
    });

    if(check_id == 1){
      $('#btn_view_pending').removeClass('active');
      $('.class_complete_list_header').removeClass('hidden');
      $('.class_complete_list_child').removeClass('hidden');
      $('.class_pending_list').addClass('hidden');
      $('.class_cancel_list').addClass('hidden');
    }

    var table = $('#view_cancel_list').DataTable({
        columnDefs: [
          { className: "aligncenter", targets: [0] },
          { className: "alignright", targets: [] },
          { className: "alignleft", targets: '_all' },
          // { width: '1%', targets: [0,3,6] },
          // { width: '12%', targets: [1,4,5] },
        ],
        filter      : true,
        // pageLength  : 10,
        processing  : true,
        serverSide  : true,
        paging      : true,
        lengthChange: true,
        lengthMenu  : [ [10, 25, 50, 100, 99999999], ['10', '25', '50', '100', 'ALL'] ],
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : false,
        bPaginate: true, 
        bFilter: true, 
        // sScrollY: "80vh", 
        // sScrollX: "100%", 
        sScrollXInner: "100%", 
        bScrollCollapse: true,
        orderCellsTop: true,
        fixedHeader: true,
        ajax: {
          url : "<?php echo site_url('Propose_po/view_header');?>",
          method: "POST",
          data:{datatable_load:1,retailer:retailer,doc_status:'CANCELLED'},
        },
        columns: [
          {"data": null, render: function (data, type, row, meta) {
            // Calculate and return the row index based on the visible rows
            var settings = meta.settings;
            var currentPage = settings._iDisplayStart;
            return currentPage + meta.row + 1;
          }},
          {"data" : "", render:function( data, type, row ){

            var element = '';
            var transmain_guid = row['transmain_guid'];
            var refno = row['refno'];
            var url = "<?php echo site_url('Propose_po/propose_details?id=');?>" + transmain_guid;

            element += '<a href="' + url + '">'+refno+'</a>';

            return element;

          }},
          {"data" : "", render:function( data, type, row ){

            var supplier_code = row['supplier_code'];
            var supplier_name = row['supplier_name'];

            return supplier_code + " - " + supplier_name;

          }},
          {"data" : "", render:function( data, type, row ){

            var delivery_date = row['delivery_date'];
            var date = new Date(delivery_date);
            var daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            var dayAbbreviation = daysOfWeek[date.getDay()];

            return delivery_date + "</br>" + dayAbbreviation;

          }},
          {"data" : "", render:function( data, type, row ){

            var created_by = row['created_by'];
            var created_at = row['created_at'];

            return created_by + "</br>" + created_at;

          }},
          {"data" : "", render:function( data, type, row ){

            var updated_by = row['updated_by'];
            var updated_at = row['updated_at'];

            return updated_by + "</br>" + updated_at;

          }},
        ],
        initComplete: function () {
          var api = this.api();
          api.columns().eq(0).each(function (colIdx) {

            var cell = $('.filters th').eq(
                $(api.column(colIdx).header()).index()
              );

            var title = $(cell).text();
            var dataTable = $('#view_pending_list').DataTable();
            var columnCells = dataTable.column(colIdx).nodes();
            var columnSize = $(columnCells[0]).width();

            if (colIdx == 0 || colIdx == 6) { 
              $(cell).html('');
            }else{
              $(cell).html('<input type="text" class="form-control" style="width:'+ columnSize +'px;" placeholder="' + title + '" />');
            }

             // On every keypress in this input
             $('input',$('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change').on('change', function (e) {
                              
              // Get the search value
              $(this).attr('title', $(this).val());
              var regexr = '({search})';
              var cursorPosition = this.selectionStart;
              
              // Search the column for that value
              api.column(colIdx).search(this.value != '' ? regexr.replace('{search}', '(((' + this.value + ')))') : '', this.value != '', this.value == '').draw();
            }).on('keyup', function (e) {
              
              e.stopPropagation();
              $(this).trigger('change');
              $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
            });
          });
        },
    });

    $('#view_complete_list thead tr').clone(true).addClass('filters-complete').appendTo('#view_complete_list thead');

    var table = $('#view_complete_list').DataTable({
        columnDefs: [
          { className: "aligncenter", targets: [0,6] },
          { className: "alignright", targets: [] },
          { className: "alignleft", targets: '_all' },
          { width: '1%', targets: '_all' },
          // { width: '1%', targets: [1,6,7,8] },
          // { width: '7%', targets: 3 },
        ],
        filter      : true,
        pageLength  : 10,
        processing  : true,
        serverSide  : true,
        paging      : true,
        lengthChange: true,
        lengthMenu  : [ [10, 25, 50, 99999999], ['10', '25', '50', 'ALL'] ],
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : false,
        bPaginate: true, 
        bFilter: true, 
        // sScrollY: "80vh", 
        // sScrollX: "100%", 
        sScrollXInner: "100%", 
        bScrollCollapse: true,
        orderCellsTop: true,
        fixedHeader: true,
        // scrollX: true,
        ajax: {
          url : "<?php echo site_url('Propose_po/view_posted_header');?>",
          method: "POST",
        },
        columns: [
          {"data": null, render: function (data, type, row, meta) {
            // Calculate and return the row index based on the visible rows
            var settings = meta.settings;
            var currentPage = settings._iDisplayStart;
            return currentPage + meta.row + 1;
          }},
          {"data" : "", render:function( data, type, row ){

            var element = '';
            var retailer_guid = row['retailer_guid'];
            var poex_guid = row['poex_guid'];
            var refno = row['refno'];
            var exported = row['exported'];
            var url = "<?php echo site_url('Propose_po/propose_record?id=');?>" + poex_guid;

            element += '<a href="' + url + '">'+refno+'</a>'; 
            
            if(exported == 99){
              element += '  <a class="btn btn-xs btn-danger" id="retry_btn" retailer_guid="' + retailer_guid + '" poex_guid="' + poex_guid + '" refno="' + refno + '"><i class="fa fa-repeat"></i> Retry</a>';
            }

            return element;

          }},
          {"data" : "", render:function( data, type, row ){

            var branch = row['branch'];
            var branch_desc = row['branch_desc'];

            return branch + " - " + branch_desc;

          }},
          {"data" : "", render:function( data, type, row ){

            var sup_code = row['sup_code'];
            var sup_name = row['sup_name'];

            return sup_code + " - " + sup_name;

          }},
          {"data" : "", render:function( data, type, row ){

            var docdate = row['docdate'];
            var date = new Date(docdate);
            var daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            var dayAbbreviation = daysOfWeek[date.getDay()];

            return docdate + "</br>" + dayAbbreviation;

          }},
          {"data" : "", render:function( data, type, row ){

            var delivery_date = row['delivery_date'];
            var date = new Date(delivery_date);
            var daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            var dayAbbreviation = daysOfWeek[date.getDay()];

            return delivery_date + "</br>" + dayAbbreviation;

          }},
          {"data" : "", render:function( data, type, row ){

            var posted_by = row['posted_by'];
            var posted_at = row['posted_at'];

            return posted_by + "</br>" + posted_at;

          }},
          {"data": "po_refno"},
          {"data" : "", render:function( data, type, row ){

            var element = '';
            var doc_status = row['doc_status'];

            if(doc_status == 'APPROVED' || doc_status == 'GENERATED'){
              element = '<button type="button" class="btn btn-xs btn-success">' + doc_status + '</button>';
            }else if(doc_status == 'REJECTED'){
              element = '<button type="button" class="btn btn-xs btn-danger">' + doc_status + '</button>';
            }else if(doc_status == 'POSTED' || doc_status == 'PROCESSING'){
              element = '<button type="button" class="btn btn-xs btn-warning"> AWAITING APPROVAL</button>';
            }else{
              element = '<button type="button" class="btn btn-xs btn-warning">' + doc_status + '</button>';
            }

            return element;

          }},
        ],
        initComplete: function () {
          var api = this.api();
          api.columns().eq(0).each(function (colIdx) {

            var cell = $('.filters-complete th').eq(
                $(api.column(colIdx).header()).index()
              );

            var title = $(cell).text();
            var dataTable = $('#view_complete_list').DataTable();
            var columnCells = dataTable.column(colIdx).nodes();
            var columnSize = $(columnCells[0]).width();
            columnSize = columnSize + 140;

            if (colIdx == 8) { 
              $(cell).html('<select id="filter_status_option" class="form-control"><option value=""> --Choose Value-- </option><option value="AWAITING APPROVAL">Awaiting Approval</option><option value="APPROVED">Approved</option><option value="GENERATED">Generated</option><option value="REJECTED">Rejected</option></select>');
            }else{
              $(cell).html('');
            }

            // if (colIdx == 0) { 
            //   $(cell).html('');
            // }else if(colIdx == 8){
            //   $(cell).html('<select id="filter_status_option" class="form-control"><option value=""> --Choose Value-- </option><option value="AWAITING APPROVAL">Awaiting Approval</option><option value="APPROVED">Approved</option><option value="GENERATED">Generated</option><option value="REJECTED">Rejected</option></select>');
            // }else{
            //   $(cell).html('<input type="text" class="form-control" style="width:'+ columnSize +'px;" placeholder="' + title + '" />');
            // }

            // On every keypress in this input
            $('input',$('.filters-complete th').eq($(api.column(colIdx).header()).index())).off('keyup change').on('change', function (e) {
                              
              // Get the search value
              $(this).attr('title', $(this).val());
              var regexr = '({search})';
              var cursorPosition = this.selectionStart;
              
              // Search the column for that value
              api.column(colIdx).search(this.value != '' ? regexr.replace('{search}', '(((' + this.value + ')))') : '', this.value != '', this.value == '').draw();
            }).on('keyup', function (e) {

              e.stopPropagation();
              $(this).trigger('change');
              $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);

            });

            $('#filter_status_option').on('change', function() {
              var selectedValue = $(this).val();
              var regexr = '({search})';
              
              // Search the column for that value
              api.column(8).search(this.value != '' ? regexr.replace('{search}', '(((' + selectedValue + ')))') : '', selectedValue != '', selectedValue == '').draw();
            });

          });
        },
    });

    $('#view_complete_list_header').DataTable(
      {
        "columnDefs": [
          { className: "aligncenter", targets: [-6] },
          { className: "alignright", targets: [] },
          { className: "alignleft", targets: '_all' },
        ],
        'filter'      : true,
        'pageLength'  : 10,
        'processing'  : true,
        'paging'      : false,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 99999999], ['10', '25', '50', 'ALL'] ],
        'searching'   : false,
        'ordering'    : true,
        'info'        : false,
        'autoWidth'   : false,
        "bPaginate": true, 
        "bFilter": true, 
        "sScrollY": "80vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
      }
    );

    $('#view_complete_list_child').DataTable(
      {
        "columnDefs": [
          { className: "aligncenter", targets: 0 },
          { className: "alignleft", targets: [1,2,3] },
          { className: "alignright", targets: '_all' },
        ],
        'filter'      : true,
        'pageLength'  : 10,
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 99999999], ['10', '25', '50', 'ALL'] ],
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true, 
        "bFilter": true, 
        // "sScrollY": "80vh", 
        // "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
      }
    );
    
    $('.class_complete_list').addClass('hidden');
    $('.class_cancel_list').addClass('hidden');

    $(document).on('click','#btn_view_pending',function(){

      $('#btn_view_pending').addClass('active');
      $('#btn_view_complete').removeClass('active');
      $('#btn_view_cancel').removeClass('active');

      $('.class_complete_list').addClass('hidden');
      $('.class_cancel_list').addClass('hidden');
      $('.class_pending_list').removeClass('hidden');

      $('.class_complete_list_header').addClass('hidden');
      $('.class_complete_list_child').addClass('hidden');

      var url = window.location.href;
      var updatedUrl = url.split('?')[0];
      window.history.replaceState(null, null, updatedUrl);

    });

    $(document).on('click','#btn_view_complete',function(){

      $('#btn_view_complete').addClass('active');
      $('#btn_view_pending').removeClass('active');
      $('#btn_view_cancel').removeClass('active');

      $('.class_pending_list').addClass('hidden');
      $('.class_cancel_list').addClass('hidden');
      $('.class_complete_list').removeClass('hidden');

      $('.class_complete_list_header').addClass('hidden');
      $('.class_complete_list_child').addClass('hidden');

      var url = window.location.href;
      var updatedUrl = url.split('?')[0];
      window.history.replaceState(null, null, updatedUrl);

    });

    $(document).on('click','#btn_view_cancel',function(){

      $('#btn_view_cancel').addClass('active');
      $('#btn_view_complete').removeClass('active');
      $('#btn_view_pending').removeClass('active');

      $('.class_pending_list').addClass('hidden');
      $('.class_complete_list').addClass('hidden');
      $('.class_cancel_list').removeClass('hidden');

      $('.class_complete_list_header').addClass('hidden');
      $('.class_complete_list_child').addClass('hidden');

      var url = window.location.href;
      var updatedUrl = url.split('?')[0];
      window.history.replaceState(null, null, updatedUrl);

    });

    $(document).on('click','#cancel_btn',function(){

      var guid = $(this).attr('transmain_guid');
      var doc_refno = $(this).attr('doc_refno');

      Swal.fire({
        title: 'Confirm cancel this proposed document?',
        html: '<span style="font-size:12px"><b>'+doc_refno+'</b></span>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, confirm!',
        cancelButtonText: 'No, cancel',
      }).then((result) => {

        if (result.value) {

          $.ajax({
            url:"<?php echo site_url('Propose_po/update_header_info') ?>",
            method:"POST",
            data:{guid:guid,doc_status:'CANCELLED'},
            beforeSend:function(){
              $('.btn').button('loading');
            },
            success:function(data)
            {
              Swal.fire(
                'Successfully cancel the document',
                '',
                'success'
              );
              
              $('.btn').button('reset');
              location.reload(true);
            }
          });
        }
      });

    });

    $(document).on('click','#retry_btn',function(){

      var retailer_guid = $(this).attr('retailer_guid');
      var poex_guid = $(this).attr('poex_guid');
      var refno = $(this).attr('refno');
      var retrigger_url = "<?php echo $retrigger_url; ?>";

      $.ajax({
        url: retrigger_url,
        type: "POST",
        data: {
          retailer_guid:retailer_guid,
          refno:refno,
          poex_guid:poex_guid,
        },
        success: function (data) {

          json = JSON.parse(data);

          console.log(json.status);

          Swal.fire(
            'Successfully posted the document',
            '',
            'success'
          );

          location.reload();

        },
        // error: function (xhr, status, error) {

        //   Swal.fire(
        //     'Fail to post the document, please retry again',
        //     '',
        //     'error'
        //   );
        // },
      });

    });

  });

</script>

<script text="text/javascript">

  function formatNumberWithCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

</script>
