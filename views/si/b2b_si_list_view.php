<style type="text/css">
  .alignright {
    text-align: right;
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
      <a class="btn btn-app" href="<?php echo site_url('b2b_si/si_list') ?>">
        <i class="fa fa-search"></i> Browse
      </a>
      <a class="btn btn-app" href="<?php echo site_url('login_c/location') ?>">
        <i class="fa fa-bank"></i> Outlet
      </a>
      <!-- <a class="btn btn-app" style="color:#367FA9" href="<?php echo $other ?>">
            <i class="fa fa-external-link-square"></i> View Others
          </a> -->
      
      <a class="btn btn-app pull-right" style="color:#000000" onclick="bulk_print()">
        <i class="fa fa-print"></i> Print
      </a>
      <form target="_blank" action="<?php echo site_url('general/merge_jasper_pdf') ?>" id="bulk_print_form" method="post">
      </form>

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
                <div class="col-md-2"><b>SI Ref No</b></div>
                <div class="col-md-4">
                  <input id="si_ref_no" type="text" autocomplete="off" class="form-control pull-right">
                </div>
                <div class="clearfix"></div><br>

                <div class="col-md-2"><b>SI Status</b></div>
                <div class="col-md-4">
                  <select id="status" class="form-control">
                    <?php foreach ($status->result() as $row) { ?>
                      <option value="<?php echo $row->code ?>" <?php if (strtolower($_REQUEST['status']) == strtolower($row->code)) {
                                                                  echo 'selected';
                                                                } ?>>
                        <?php echo $row->reason; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="clearfix"></div><br>

                <div class="col-md-2"><b>Invoice Date Range<br>(YYYY-MM-DD)</b></div>
                <div class="col-md-4">
                  <input id="daterange" name="daterange" type="datetime" class="form-control pull-right" readonly>
                </div>
                <div class="col-md-2">
                  <a class="btn btn-danger" onclick="date_clear()">Clear</a>
                </div>
                <div class="clearfix"></div><br>
                <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
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

                <div class="clearfix"></div><br>

                <div class="col-md-12">

                  <button id="search" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                  <button id="reset" class="btn btn-default"><i class="fa fa-repeat"></i> Reset</button>

                </div>
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
            <h3 class="box-title"><b>Sales Invoice</b></h3> &nbsp;

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

            <span class="pill_button hidden" id="invoice_date_tag">

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
                    <table id="si_table" class="table table-bordered table-hover">
                      <form id="formPO" method="post" action="<?php echo site_url('general/prints') ?>">
                        <thead>
                          <tr>
                            <?php // var_dump($_SESSION); 
                            ?>
                            <!--Begin=Column Header-->
                            <th>SI Refno</th>
                            <th>Loc Group</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Invoice Date</th>
                            <!-- <th>Delivery Date</th> -->
                            <th>Doc No</th>
                            <th>Amount</th>
                            <th>Tax</th>
                            <th>Total Incl Tax</th>
                            <th>Status</th>
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
    });
  }
</script>
<script type="text/javascript">
  function bulk_print() {
    var list_id = [];
    $(".data-check:checked").each(function() {
      list_id.push(this.value);
    });
    if (list_id.length > 0) {
      var form = document.getElementById("bulk_print_form");
      var element1 = document.createElement("input"); 
      var element2 = document.createElement("input");  

      element1.value=list_id;
      element1.name="id";
      form.appendChild(element1);  

      element2.value="SI";
      element2.name="type";
      form.appendChild(element2);

      document.body.appendChild(form);
      $('#bulk_print_form').submit();
    } else {
      alert('No data selected');
    }
  }
</script>
<script>
  var si_ref_no = '';
  var status = '';
  var datefrom = '';
  var dateto = '';
  var period_code = '';
  var loc = '';

  $(document).ready(function() {
    main_table = function(si_ref_no, status, datefrom, dateto, period_code, loc) {

      if ($.fn.DataTable.isDataTable('#si_table')) {
        $('#si_table').DataTable().destroy();
      }

      var table;

      table = $('#si_table').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        lengthMenu: [
          [10, 25, 50, 99999999],
          ['10', '25', '50', 'Show all']
        ],
        "order": [
          [0, "desc"]
        ],
        "columnDefs": [{
            "targets": [6, 7, 8],
            "className": "alignright",
          },
          {
            "targets": [10, 11], //first column
            "orderable": false, //set not orderable
          }
        ],
        "ajax": {
          "url": "<?php echo site_url('B2b_si/si_datatable') ?>",
          "type": "POST",
          "data": function(data) {
            data.si_ref_no = si_ref_no
            data.status = status
            data.datefrom = datefrom
            data.dateto = dateto
            data.period_code = period_code
            data.loc = loc
            data.type = 'si'
          },
        },
        "columns": [{
            "name": "si_refno",
            "data": "si_refno"
          },
          {
            "name": "loc_group",
            "data": "loc_group"
          },
          {
            "name": "code",
            "data": "code"
          },
          {
            "name": "name",
            "data": "name"
          },
          {
            "name": "invoice_date",
            "data": "invoice_date"
          },
          // {
          //   "name": "delivery_date",
          //   "data": "delivery_date"
          // },
          {
            "name": "doc_no",
            "data": "doc_no"
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
            "data": "action"
          },
          {
            "data": "chkb"
          },
        ],
        dom: 'lBfrtip',

        buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
        ]

      });
    }

    main_table(si_ref_no, status, datefrom, dateto, period_code, loc);

  });

  $('#search').click(function() {

    si_ref_no = $('#si_ref_no').val();
    status = $('#status').val();
    daterange = $('#daterange').val();
    period_code = $('#period_code').val();
    daterange = daterange.split(" - ");
    datefrom = daterange[0];
    dateto = daterange[1];
    loc = "<?php echo $_SESSION['si_loc']; ?>";

    if (si_ref_no != '') {
      $('#ref_no_tag').removeClass("hidden").html(si_ref_no);
    } else {
      $('#ref_no_tag').addClass("hidden").html('');
    }

    if (status != '') {
      $('#status_tag').removeClass("hidden").html(status[0].toUpperCase() + status.substring(1));
    }

    if (daterange != '') {
      $('#invoice_date_tag').removeClass("hidden").html('Invoice Date Range : ' + datefrom + ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' + dateto);
    } else {
      $('#invoice_date_tag').addClass("hidden").html('');
    }

    if (period_code != '') {
      $('#period_code_tag').removeClass("hidden").html(period_code);
    } else {
      $('#period_code_tag').addClass("hidden").html('');
    }

    main_table(si_ref_no, status, datefrom, dateto, period_code, loc);

  })

  $('#reset').click(function() {

    si_ref_no = '';
    status = '';
    datefrom = '';
    dateto = '';
    period_code = '';
    loc = '';

    $('#si_ref_no').val('');
    $('#status').val('');
    $('#daterange').val('');
    $('#period_code').val('');

    $('#status_tag').html('New');
    $('#invoice_date_tag').addClass("hidden").html('');
    $('#period_code_tag').addClass("hidden").html('');
    $('#ref_no_tag').addClass("hidden").html('');


    main_table(si_ref_no, status, datefrom, dateto, period_code, loc);

  })
</script>