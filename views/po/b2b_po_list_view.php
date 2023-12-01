<style type="text/css">

.blinker {
  animation: blink-animation 5s steps(10, start) infinite;
  -webkit-animation: blink-animation 2s steps(10, start) infinite;
  background-color: yellow;
  font-weight: bold;
  font-size:24px;
  color:black;
}
@keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
@-webkit-keyframes blink-animation {
  to {
    visibility: hidden;
  }
}

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="container-fluid">
    <br>
    <?php
    if ($this->session->userdata('message')) {
    ?>
      <div class="alert alert-success text-center" style="font-size: 18px">
        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
        <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
      </div>
    <?php
    }
    ?>

    <?php
    if ($this->session->userdata('warning')) {
    ?>
      <div class="alert alert-danger text-center" style="font-size: 18px">
        <?php echo $this->session->userdata('warning') <> '' ? $this->session->userdata('warning') : ''; ?>
        <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
      </div>
    <?php
    }
    ?>

    <div class="col-md-12">
      <a class="btn btn-app" href="<?php echo site_url('b2b_po/po_list') ?>">
        <i class="fa fa-search"></i> Browse
      </a>
      <a class="btn btn-app" href="<?php echo site_url('login_c/location') ?>">
        <i class="fa fa-bank"></i> Outlet 
      </a>
      <a class="btn btn-app" style="color:#fa7f0c" onclick="filter_status(4)" title="New" >
        <i class="fa fa-file-o"></i> Unread 
        <!-- <small> (New) </small>  <small> (Viewed & Printed) </small> -->
      </a>
      <a class="btn btn-app" style="color:#5693f5" onclick="filter_status(3)" title="Viewed & Printed">
        <i class="fa fa-file-word-o"></i> Read 
      </a>
      <a class="btn btn-app " style="color:#008D4C" onclick="filter_status(1)" title="Accepted">
        <i class="fa fa-check-square"></i> Accepted 
      </a>
      <a class="btn btn-app" style="color:#D73925" onclick="filter_status(2)" title="Rejected">
        <i class="fa fa-window-close"></i> Rejected 
      </a>
      <a class="btn btn-app" style="color:#a320f5" onclick="filter_status(5)" title="All">
        <i class="fa fa-list"></i> All 
      </a>

      <?php if ($_SESSION['customer_guid'] != '8D5B38E931FA11E79E7E33210BD612D3') {
        // echo $hide_url;
      } ?>
  

      <!-- <a class="btn btn-app" style="color:#367FA9" href="<?php echo $other ?>">
            <i class="fa fa-external-link-square"></i> View Others
          </a> -->
      <a class="btn btn-app pull-right" style="color:#000000" onclick="bulk_print()">
        <i class="fa fa-print"></i>  Print
      </a>
      
      <?php
      if (in_array('BAPO', $_SESSION['module_code'])) {
        if ($_REQUEST['status'] == '' || $_REQUEST['status'] == null) {
      ?>
          <a class="btn btn-app pull-right" id="po_bulk_accept" style="color:#000000">
            <i class="fa fa-check-circle"></i> Bulk Accept
          </a>
      <?php
        }
      }
      ?>
      <!--old process shoot to backend check document.. -->
      <?php if (in_array('CPOS', $_SESSION['module_code'])) { ?>
        <!-- <button class="btn btn-app" data-toggle="modal" data-target="#postatusmodal"><i class="fa fa-file-text-o"></i>Check PO Status</button> -->
      <?php } ?>

      <div style="margin-top: 5px; margin-bottom: 10px;">
        <span class="blinker">Once the PO is accepted, supplier shall proceed to deliver the stock based on the stipulated quantity, pricing and other delivery terms and conditions. </span> 
      </div>

    </div>
    <!-- filter by -->

    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
          <!-- head -->
          <div class="box-header with-border">
            <h3 class="box-title">Filter By</h3><br>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <!-- head -->
          <!-- body -->
          <div class="box-body">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-2"><b>PO Ref No</b></div>
                <div class="col-md-4">
                  <input id="po_num" name="po_num" type="text" autocomplete="off" class="form-control pull-right">
                </div>
                <div class="clearfix"></div><br>

                <div class="col-md-2"><b>PO Status</b></div>
                <div class="col-md-4">
                  <select id="po_status" name="po_status" class="form-control">
                    <?php foreach ($po_status->result() as $row) { ?>
                      <option value="<?php echo $row->code ?>" <?php if (strtolower($_REQUEST['status']) == strtolower($row->code)) {
                                                                  echo 'selected';
                                                                }
                                                                ?>>
                        <?php echo $row->reason; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="clearfix"></div><br>

                <div class="col-md-2"><b>PO Date Range<br>(YYYY-MM-DD)</b></div>
                <div class="col-md-4">
                  <input required id="daterange" name="daterange" type="datetime" class="form-control pull-right" id="reservationtime" readonly>
                </div>
                <div class="col-md-2">
                  <a class="btn btn-danger" onclick="date_clear()">Clear</a>
                </div>
                <div class="clearfix"></div><br>

                <div class="col-md-2"><b>Expired Date From<br>(YYYY-MM-DD)</b></div>
                <div class="col-md-2">
                  <input id="expiry_from" name="expiry_from" type="datetime" value="" readonly class="form-control pull-right">
                </div>
                <div class="col-md-2"><b>Expired Date To<br>(YYYY-MM-DD)</b></div>
                <div class="col-md-2">
                  <input id="expiry_to" name="expiry_to" type="datetime" class="form-control pull-right" readonly value="" onchange="CompareDate()">
                </div>
                <div class="col-md-2">
                  <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
                </div>
                <div class="clearfix"></div><br>

                <!-- <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
                <div class="col-md-4">
                  <select id="period_code" name="period_code" class="form-control">
                    <option value="">None</option>
                    <?php foreach ($period_code->result() as $row) { ?>
                      <option value="<?php echo $row->period_code ?>" <?php if (isset($_SESSION['filter_period_code'])) {
                                                                        if ($_SESSION['filter_period_code'] == $row->period_code) {
                                                                          echo 'selected';
                                                                        }
                                                                      }
                                                                      ?>>
                        <?php echo $row->period_code; ?></option>
                    <?php } ?>
                  </select> 
                </div>

                <div class="clearfix"></div><br> -->

                <div class="col-md-12">
                  <button id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                  <button id="reset" class="btn btn-default"><i class="fa fa-repeat"></i> Reset</button>
                </div>
                <!--Bulk print form here -->
                <form target="_blank" action="<?php echo site_url('general/merge_jasper_pdf') ?>" id="bulk_print_form" method="post">
                </form>
              </div>
            </div>
          </div>
          <!-- body -->

        </div>
      </div>

    </div>
    <!-- filter by -->

    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title"><b>Purchase Order</b></h3> &nbsp;

            <span class="pill_button" id="status_tag">
              <?php

              $status = 'new';

              echo ucfirst($status) ?></span>

            <span class="pill_button" id="outlet_tag">
              <?php

              if (in_array($check_loc, $hq_branch_code_array)) {
                echo 'All Outlet';
              } else {

                echo $location_description->row('BRANCH_CODE') . ' - ' . $location_description->row('branch_desc');
              } ?>

            </span>

            <span class="pill_button hidden" id="po_date_tag">

            </span>

            <span class="pill_button hidden" id="exp_date_tag">

            </span>

            <span class="pill_button hidden" id="period_code_tag">

            </span>

            <span class="pill_button hidden" id="ref_no_tag">

            </span>


            <br>
            <!-- <?php echo $title_accno ?> -->
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <div class="box-body">
            <div class="col-md-12">
              <br>
              <div>
                <div class="row">
                  <div class="col-md-12" style="overflow-x:auto">
                    <table id="table_list" class="table table-bordered table-hover">
                      <form id="formPO" method="post" action="<?php echo site_url('general/prints') ?>">
                        <thead>
                          <tr>
                            <?php // var_dump($_SESSION); 
                            ?>
                            <!--Begin=Column Header-->
                            <th>PO Refno</th>
                            <th>GRN Refno</th>
                            <th>Outlet</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Po Date</th>
                            <th>Delivery Date</th>
                            <th>Expiry Date</th>
                            <th>Amount</th>
                            <th>Tax</th>
                            <th>Total Incl Tax</th>
                            <th>Status</th>
                            <th>Reject Remark</th>
                            <th>Action</th>
                            <th><input type="checkbox" id="check-all"></th>
                            <!--End=Column Header-->
                          </tr>
                        </thead>
                      </form>
                    </table>

                  </div>
                </div>

                <!-- Modal -->
                <div id="postatusmodal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                  <div class="modal-dialog modal-sm">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title text-center">Check PO Status</h4>
                      </div>
                      <div class="modal-body">
                        <p><input type="text" id="po_refno" name="po_refno" class="form-control">
                          <center><span style="font-weight: bolder;" id="webindex_result"></span></center>
                        </p>
                        <center><span style="font-weight: bolder;" id="po_check_grn_refno_result"></span></center>
                        </p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload()">Close</button>
                      </div>
                    </div>

                  </div>
                </div>

                <script>
                  $(document).ready(function() {

                    $(document).on('click', '#po_bulk_accept', function(e) {
                      var list_id = [];
                      $(".data-check:checked").each(function() {
                        list_id.push(this.value);
                      });

                      // alert(list_id.length);


                      $.ajax({
                        url: "<?php echo site_url('B2b_po/bulk_accept'); ?>",
                        method: "POST",
                        data: {
                          list_id: list_id
                        },
                        success: function(data) {
                          if (data == 1) {
                            alert('PO Accepted');
                            location.reload();
                          } else {
                            alert('Error Occur');
                            location.reload();

                          }

                        }
                      });
                    });


                    $(document).on('paste', '#po_refno', function(e) {
                      e.preventDefault();
                      var withoutSpaces = e.originalEvent.clipboardData.getData('Text');
                      withoutSpaces = withoutSpaces.replace(/\s+/g, '');
                      $(this).val(withoutSpaces);
                    });

                    $(document).on('keypress', '#po_refno', function(e) {
                      if (e.which == 32) {
                        event.preventDefault();
                        return false;
                      } //close function for click space
                    }); //close keypress funhction

                    $('#po_refno').keyup(function() {
                      var po_refno = $('#po_refno').val();
                      if (po_refno != '') {
                        $.ajax({
                          url: "<?php echo site_url('general/check_po_status'); ?>",
                          method: "POST",
                          data: {
                            po_refno: po_refno
                          },
                          success: function(data) {
                            if (data.substring(1, 2) == 1) {
                              $('#po_refno').css('border', '2px green solid');
                              $('#webindex_result').html(data.substring(2));
                            } else if (data == ' ') {
                              $('#po_refno').css('border', '2px blue solid');
                              $('#webindex_result').html('Please Wait......');
                            } else {
                              $('#po_refno').css('border', '2px red solid');
                              $('#webindex_result').html(data.substring(2));
                            }

                          }
                        });

                        $.ajax({

                          url: "<?php echo site_url('general/check_grn_no'); ?>",

                          method: "POST",

                          dataType: "json",

                          data: {
                            po_check_grn_refno: po_refno
                          },

                          success: function(data)

                          {

                            if (data.count == 0)

                            {

                              $('#po_check_grn_refno').css('border', '2px green solid');

                              $('#po_check_grn_refno_result').html(data.xmessage);

                            } else if (data.count == 1)

                            {

                              $('#po_check_grn_refno').css('border', '2px green solid');

                              $('#po_check_grn_refno_result').html(data.xmessage);

                            } else

                            {

                              $('#po_check_grn_refno').css('border', '2px red solid');

                              $('#po_check_grn_refno_result').html(data.xmessage);

                            }

                          }

                        });

                      }
                    });
                  });
                </script>
                <script>
                  $(function() {
                    $('input[name="daterange"]').daterangepicker({
                      locale: {
                        format: 'YYYY-MM-DD'
                      },
                    });
                    //$('#daterange').data('daterangepicker').setStartDate('<?php echo date('Y-m-d', strtotime('-7 days')) ?>');
                    //$('#daterange').data('daterangepicker').setEndDate('<?php echo date('Y-m-d') ?>');
                    $(this).find('[name="daterange"]').val("");
                  });
                </script>


                <script type="text/javascript">
                  $(function() {
                    $('input[name="expiry_from"]').daterangepicker({
                      locale: {
                        format: 'YYYY-MM-DD'
                      },
                      singleDatePicker: true,
                      showDropdowns: true,
                      autoUpdateInput: true,
                    });
                    $(this).find('[name="expiry_from"]').val("");
                  });
                </script>

                <script type="text/javascript">
                  $(function() {
                    $('input[name="expiry_to"]').daterangepicker({
                      locale: {
                        format: 'YYYY-MM-DD'
                      },
                      singleDatePicker: true,
                      showDropdowns: true,
                      autoUpdateInput: true,
                    });
                    $(this).find('[name="expiry_to"]').val("");
                  });
                </script>

                <script type="text/javascript">
                  function date_clear() {
                    $(function() {
                      $(this).find('[name="daterange"]').val("");
                    });
                  }

                  function expiry_clear() {
                    $(function() {
                      $(this).find('[name="expiry_from"]').val("");
                      $(this).find('[name="expiry_to"]').val("");
                      $('#search').removeAttr('disabled');
                    });
                  }
                </script>

                <script type="text/javascript">
                  function CompareDate() {
                    var dateOne = $('input[name="expiry_from"]').val(); //Year, Month, Date
                    var dateTwo = $('input[name="expiry_to"]').val(); //Year, Month, Date
                    if (dateOne > dateTwo) {
                      alert("Expiry To : " + dateTwo + " Cannot Be a date before " + dateOne + ".");
                      $('#search').attr('disabled', 'disabled');
                    } else {
                      $('#search').removeAttr('disabled');
                    }

                  }
                </script>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function bulk_print() {
    var list_id = [];
    $(".data-check:checked").each(function() {
      list_id.push(this.value);
    });

    $.ajax({
      url: "<?php echo site_url('B2b_po/bulk_print_check_status'); ?>",
      method: "POST",
      data: {
        list_id: list_id
      },
      success: function(data) {
        if (data == 1) {
          if (list_id.length > 0) {
            var form = document.getElementById("bulk_print_form");
            var element1 = document.createElement("input"); 
            var element2 = document.createElement("input");  
            element1.setAttribute("type", "hidden");
            element2.setAttribute("type", "hidden");
            
            element1.value=list_id;
            element1.name="id";
            form.appendChild(element1);  

            element2.value="PO";
            element2.name="type";
            form.appendChild(element2);

            document.body.appendChild(form);
            $('#bulk_print_form').submit();
          } else {
            alert('No data selected');
          }
        }else{
          alert('One of the selected PO is still not accepted');
        }
      }
    });
  }
</script>

</script>
<script type="text/javascript">
  function viewothers() {
    $('#viewothers').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)
      modal.find('.modal_detail').text('Confirm Hide ' + button.data('name') + '?')
      modal.find('[name="refno"]').val(button.data('refno'))
      modal.find('[name="customer_guid"]').val(button.data('customer_guid'))
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="col_guid"]').val(button.data('col_guid'))
      modal.find('[name="loc"]').val(button.data('loc'))
      modal.find('[name="name"]').val(button.data('name'))

    });
  }
</script>
<!-- <script>
  function ahsheng() {
    location.href = '<?php echo site_url('general/view_status') ?>?status=' + $('#reason').val() + '&loc=HQ';
  }
</script> -->

<script>
  $(document).ready(function() {

    status = $('#po_status').val();
    
    if(status != ''){
      $('#po_bulk_accept').addClass("hidden");
    }

  });
</script>

<script>
  $(document).ready(function() {
    $(document).on('click', '#preview_po_item_line', function(e) {
      var refno = $(this).attr('refno');

      var modal = $("#medium-modal").modal();

      modal.find('.modal-title').html('PO Preview Item Line');

      methodd = '';

      methodd += '<table class="table table-bordered table-striped" id="preview_po_item_line_table" width="100%"><thead><th>Line</th><th>Itemcode</th><th>Qty</th><th>Price</th><th>Description</th></thead></table>';

      methodd += '</div>';


      methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

      modal.find('.modal-footer').html(methodd_footer);
      modal.find('.modal-body').html(methodd);

      $('#preview_po_item_line_table').DataTable({
        'processing': true,
      });

      $('#preview_po_item_line_table_processing').css({
        'z-index': '1040'
      }).show();


      setTimeout(function() {

        $.ajax({
          url: "<?php echo site_url('B2b_po/preview_po_item_line'); ?>",
          method: "POST",
          data: {
            refno: refno
          },
          success: function(data) {
            json = JSON.parse(data);
            // alert(json);return;
            if ($.fn.DataTable.isDataTable('#preview_po_item_line_table')) {
              $('#preview_po_item_line_table').DataTable().destroy();
            }

            $('#preview_po_item_line_table').DataTable({
              // "columnDefs": [ {"targets": 1 ,"visible": false}],
              'processing': true,
              "sScrollY": "40vh",
              "sScrollX": "100%",
              "sScrollXInner": "100%",
              lengthMenu: [
                [10, 25, 50, 99999999],
                ['10', '25', '50', 'Show all']
              ],
              "bScrollCollapse": true,
              // "pagingType": "simple",
              'order': [
                [0, 'asc']
              ],
              data: json['po_item_line'],
              columns: [{
                  data: "Line"
                },
                {
                  data: "Itemcode"
                },
                {
                  data: "Qty",
                  render: function(data, type, row) {
                    var element = '';
                    <?php
                    if (in_array('HBTN', $_SESSION['module_code'])) {
                    ?>
                      element += '';
                    <?php
                    } else {
                    ?>
                      element += data;
                    <?php
                    }
                    ?>
                    return element;

                  }
                },
                {
                  data: "TotalPrice",
                  render: function(data, type, row) {
                    var element = ''
                    <?php
                    if (in_array('HBTN', $_SESSION['module_code'])) {
                    ?>
                      element += '';
                    <?php
                    } else {
                    ?>
                      element += parseFloat(data).toFixed(2);
                    <?php
                    }
                    ?>
                    //element = parseFloat(data).toFixed(2);
                    return element;
                  }
                },
                {
                  data: "Description"
                }
              ],
              dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" + 'rtip',
              "fnCreatedRow": function(nRow, aData, iDataIndex) {

                // $(nRow).attr('id', aData['RefNo']);
              },
              "initComplete": function(settings, json) {
                setTimeout(function() {
                  interval();
                }, 300);
              }
            }); //close datatatable

          } //close succcess
        }); //close ajax
      }, 300);

    });
  });
</script>
<script>
  var ref_no = '';
  var status = '';
  var datefrom = '';
  var dateto = '';
  var exp_datefrom = '';
  var exp_dateto = '';
  var period_code = '';
  var loc = '';

  $(document).ready(function() {
    main_table = function(ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc) {

      if ($.fn.DataTable.isDataTable('#table_list')) {
        $('#table_list').DataTable().destroy();
      }

      var table;

      table = $('#table_list').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "order": [
          [0, "desc"]
        ],
        "columnDefs": [{
            "targets": [9, 10, 11],
            "className": "alignright",
          },
          {
            "targets": [13, 14], //first column
            "orderable": false, //set not orderable
          }
        ],
        "ajax": {
          "url": "<?php echo site_url('B2b_po/po_datatable') ?>",
          "type": "POST",
          "data": function(data) {
            data.ref_no = ref_no
            data.status = status
            data.datefrom = datefrom
            data.dateto = dateto
            data.exp_datefrom = exp_datefrom
            data.exp_dateto = exp_dateto
            data.period_code = period_code
            data.loc = loc
            data.type = 'po'
          },
        },
        "columns": [{
            "name": "refno",
            "data": "refno"
          },
          {
            "name": "grn_refno",
            "data": "grn_refno"
          },
          {
            "name": "loc_group",
            "data": "loc_group"
          },
          {
            "name": "supplier_code",
            "data": "supplier_code"
          },
          {
            "name": "supplier_name",
            "data": "supplier_name"
          },
          {
            "name": "podate",
            "data": "podate"
          },
          {
            "name": "delivery_date",
            "data": "delivery_date"
          },
          {
            "name": "expiry_date",
            "data": "expiry_date"
          },
          {
            "name": "amount",
            "data": "amount"
          },
          {
            "name": "tax",
            "data": "tax"
          },
          {
            "name": "total_include_tax",
            "data": "total_include_tax"
          },
          {
            "name": "status",
            "data": "status"
          },
          {
            "name": "rejected_remark",
            "data": "rejected_remark"
          },
          {
            "data": "action"
          },
          {
            "data": "chkb"
          },
        ],
        //dom: 'lBfrtip',
        dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
        buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
        ]

      });
    }

    main_table(ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);

    $(document).on('click','#hide_doc_btn',function(){
      var refno = $(this).attr('refno');
      var loc = $(this).attr('loc');

      var modal = $("#medium-modal").modal();

      modal.find('.modal-title').html('Hide Document : '+refno);

      methodd = '';

      methodd +='<div class="col-md-12">';

      methodd += '<div class="col-md-12"><input type="hidden" class="form-control input-xm" id="refno" value="'+refno+'" readonly/> </div>';

      methodd += '<div class="col-md-12"><label>Reason<span style="colro:red;">*</span></label><select class="form-control select2 " id="reason_hide" name="reason_hide"> <option value="" selected disabled>-Select Reason-</option> <?php foreach ($set_admin_code->result() as $row) { ?> <option value="<?php echo $row->code ?>"> <?php echo $row->reason?> </option> <?php } ?> </select></div>'

      methodd += '</div>';

      methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="hide_button" class="btn btn-primary"> Update </button></span></p>';

      modal.find('.modal-footer').html(methodd_footer);
      modal.find('.modal-body').html(methodd);

      setTimeout(function() {
        $('.select2').select2();
        
      }, 300);
    });//close submit_button

    $(document).on('click','#hide_button',function(){
      var refno = $('#refno').val();
      var reason_hide = $('#reason_hide').val();

      if((refno == '') || (refno == null) || (refno == 'null'))
      {
        alert('Invalid Refno.');
        return;
      }

      if((reason_hide == '') || (reason_hide == null) || (reason_hide == 'null'))
      {
        alert('Please Select Reason for Hide.');
        return;
      }
      
      confirmation_modal('Are you sure to proceed Hide this RefNo?');

      $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
          url:"<?php echo site_url('B2b_po/update_hide_status') ?>",
          method:"POST",
          data:{refno:refno,reason_hide:reason_hide},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              $('#alertmodal').modal('hide');
              alert(json.msg);
              $('.btn').button('reset');
            }else{
              $('.btn').button('reset');
              $('#alertmodal').modal('hide');
              alert(json.msg);
              location.reload();
            }//close else
          }//close success
        });//close ajax 
      });//close confirmation

    });//close submit process

  });

  $('#search').click(function() {

    ref_no = $('#po_num').val();
    status = $('#po_status').val();
    daterange = $('#daterange').val();
    //period_code = $('#period_code').val();
    period_code = '';
    daterange = daterange.split(" - ");
    datefrom = daterange[0];
    dateto = daterange[1];
    exp_datefrom = $('#expiry_from').val();
    exp_dateto = $('#expiry_to').val();
    loc = "<?php echo $_SESSION['po_loc']; ?>";

    if (ref_no != '') {
      $('#ref_no_tag').removeClass("hidden").html(ref_no);
    } else {
      $('#ref_no_tag').addClass("hidden").html('');
    }

    if (status != '') {

      $('#po_bulk_accept').addClass("hidden");

      if(status == 'gr_completed'){
        status = 'GRN Completed';
        $('#status_tag').removeClass("hidden").html(status[0].toUpperCase() + status.substring(1));
      } else {
        $('#status_tag').removeClass("hidden").html(status[0].toUpperCase() + status.substring(1));
      }
    }else{
      $('#po_bulk_accept').removeClass("hidden");
    }

    if (daterange != '') {
      $('#po_date_tag').removeClass("hidden").html('PO Date Range : ' + datefrom + ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' + dateto);
    } else {
      $('#po_date_tag').addClass("hidden").html('');
    }

    if (exp_datefrom != '' && exp_dateto != '') {
      $('#exp_date_tag').removeClass("hidden").html('Expired Date Range : ' + exp_datefrom + ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' + exp_dateto);
    } else {
      $('#exp_date_tag').addClass("hidden").html('');
    }

    if (period_code != '') {
      $('#period_code_tag').removeClass("hidden").html(period_code);
    } else {
      $('#period_code_tag').addClass("hidden").html('');
    }

    main_table(ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);

  })

  $('#reset').click(function() {

    ref_no = '';
    status = '';
    datefrom = '';
    dateto = '';
    exp_datefrom = '';
    exp_dateto = '';
    period_code = '';
    loc = '';

    $('#po_num').val('');
    $('#po_status').val('');
    $('#daterange').val('');
    $('#period_code').val('');
    $('#expiry_from').val('');
    $('#expiry_to').val('');

    $('#status_tag').html('New');
    $('#po_date_tag').addClass("hidden").html('');
    $('#exp_date_tag').addClass("hidden").html('');
    $('#period_code_tag').addClass("hidden").html('');
    $('#ref_no_tag').addClass("hidden").html('');


    main_table(ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);

  })

  function filter_status(data){
    if(data == '1'){
      new_status = 'accepted';
      $('#po_status').val('accepted');
      $('#status_tag').removeClass("hidden").html('Accepted');
    }else if(data == '2'){
      new_status = 'rejected';
      $('#po_status').val('rejected');
      $('#status_tag').removeClass("hidden").html('Rejected');
    }else if(data == '3'){
      new_status = 'READ';
      $('#po_status').val('READ');
      $('#status_tag').removeClass("hidden").html('Read');
    }else if(data == '4'){
      new_status = '';
      $('#po_status').val('');
      $('#status_tag').removeClass("hidden").html('Unread');
    }else if(data == '5'){
      new_status = 'ALL';
      $('#po_status').val('ALL');
      $('#status_tag').removeClass("hidden").html('All');
    } else {
      new_status = '';
      $('#po_status').val('');
    }

    main_table(ref_no, new_status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);
  }
</script>