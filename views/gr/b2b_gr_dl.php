<!-- Content Wrapper. Contains page content -->
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

    <div class="col-md-12">
        <a class="btn btn-app" href="<?php echo site_url('b2b_gr/gr_list') ?> ">
        <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
        <i class="fa fa-bank"></i> Outlet
        </a>
        <!-- <a class="btn btn-app " style="color:#008D4C" onclick="filter_status(1)">
            <i class="fa fa-check-square"></i> View Confirmed GR
        </a>  -->
        <a class="btn btn-app pull-right"  style="color:#000000"  onclick="bulk_print()" >
            <i class="fa fa-print"></i> Print
        </a> 
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
                <div class="col-md-2"><b>GR Ref No</b></div>
                <div class="col-md-4">
                  <input id="gr_num" name="gr_num" type="text" autocomplete="off" class="form-control pull-right">
                </div>
                <div class="clearfix"></div><br>

                <div class="col-md-2"><b>GR Status</b></div>
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

                <div class="col-md-2"><b>GR Date Range<br>(YYYY-MM-DD)</b></div>
                <div class="col-md-4">
                  <input required id="daterange" name="daterange" type="datetime" class="form-control pull-right" id="reservationtime" readonly>
                </div>
                <div class="col-md-2">
                  <a class="btn btn-danger" onclick="date_clear()">Clear</a>
                </div>
                <div class="clearfix"></div><br>

                <!-- <div class="col-md-2"><b>Doc Date From<br>(YYYY-MM-DD)</b></div>
                <div class="col-md-2">
                  <input id="expiry_from" name="expiry_from" type="datetime" value="" readonly class="form-control pull-right">
                </div>
                <div class="col-md-2"><b>Doc Date To<br>(YYYY-MM-DD)</b></div>
                <div class="col-md-2">
                  <input id="expiry_to" name="expiry_to" type="datetime" class="form-control pull-right" readonly value="" onchange="CompareDate()">
                </div>
                <div class="col-md-2">
                  <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
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
          <h3 class="box-title"><b>Goods Received Download</b></h3> &nbsp;

          <span class="pill_button" id="status_tag">
              <?php 
  
                if ($_REQUEST['status'] == '') {
                  $status = 'new';
                } else if ($_REQUEST['status'] == 'geinv') {
                  $status = 'New - Viewed - Printed';
                } else {
                  $status = $_REQUEST['status'];
                }

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
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
      <div class="box-body">
      <div class="col-md-12">
        <br>
        <div>
            <div class="row">
                <div class="col-md-12"  style="overflow-x:auto"> 
                    <table id="table_list" class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th>GRN Refno</th>
                                <th>GRDA Status</th>
                                <th>PO Refno</th>
                                <th>Outlet</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>GRN Date</th>
                                <th>Supplier Inv/DO Date</th>
                                <th>Supplier <br>Inv No</th>
                                <th>E-Inv No</th>
                                <th>E-Inv Date</th>
                                <th>DO No</th>
                                <th>GRN Supplier Copy</th>
                                <th>Inv Amt</th>
                                <th>Tax</th>
                                <th>Total Inc Tax</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th><input type="checkbox" id="check-all"></th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
          </div>
             <!-- <p><a href="Panda_home/logout">Logout</a></p> -->
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
      element1.setAttribute("type", "hidden");
      element2.setAttribute("type", "hidden");
      
      element1.value=list_id;
      element1.name="id";
      form.appendChild(element1);  

      element2.value="GRN";
      element2.name="type";
      form.appendChild(element2);

      document.body.appendChild(form);
      $('#bulk_print_form').submit();
    } else {
      alert('No data selected');
    }
  }
</script>
<script type="text/javascript">
  //no use
  function bulk_accept()
  {
    var list_id = [];
    $(".data-check:checked").each(function() {
            list_id.push(this.value);
    });
    if(list_id.length > 0)
    {
        if(confirm('Are you sure bulk accept this '+list_id.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {id:list_id},
                url: "<?php echo site_url('general/ajax_bulk_accept?loc='.$_REQUEST['loc'])?>",
                dataType: "JSON",
               
               /* success: function(data)
                { 
                     alert('done.');
                   
                    
                },*/
                error: function (jqXHR, textStatus, errorThrown)
                {
                   // alert('Error Opening data');
                    alert('done');
                    window.location.reload();
                }

            });
        }
    }
    else
    {
        alert('no data selected');
    }
  }
</script>
<script>
  var po_ref_no = '';
  var ref_no = '';
  var status = '';
  var datefrom = '';
  var dateto = '';
  var exp_datefrom = '';
  var exp_dateto = '';
  var period_code = '';
  var loc = '';

  $(document).ready(function() {
    main_table = function(po_ref_no, ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc) {

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
            "targets": [17, 18], //first column
            "orderable": false, //set not orderable
          }
        ],
        "ajax": {
          "url": "<?php echo site_url('B2b_gr_download/gr_datatable') ?>",
          "type": "POST",
          "data": function(data) {
            data.po_ref_no = po_ref_no
            data.ref_no = ref_no
            data.status = status
            data.datefrom = datefrom
            data.dateto = dateto
            data.exp_datefrom = exp_datefrom
            data.exp_dateto = exp_dateto
            data.period_code = period_code
            data.loc = loc
            data.type = 'gr'
          },
        },
        "columns": [
            { "data": "refno" },
            { "data": "grda_status" },
            { "data": "porefno" },
            { "data": "loc_group" },
            { "data": "supplier_code" },
            { "data": "supplier_name" },
            { "data": "grdate" },
            { "data": "docdate" },
            { "data": "dono" },
            { "data": "einvno" },
            { "data": "einvdate" },
            { "data": "invno" },
            { "data": "cross_ref" },
            { "data": "total" , render:function( data, type, row ){
              var element = '';
              <?php
              if(in_array('HBTN',$_SESSION['module_code']))
              {
                ?>
                  element += '';
                <?php
              }
              else
              {
                ?>
                element += data;
                <?php
              }
              ?>
              return element;

              }},
            { "data": "gst_tax_sum" , render:function( data, type, row ){

              var element = '';
              <?php
              if(in_array('HBTN',$_SESSION['module_code']))
              {
                ?>
                  element += '';
                <?php
              }
              else
              {
                ?>
                element += data;
                <?php
              }
              ?>
              return element;

              }},
            { "data": "total_include_tax" , render:function( data, type, row ){

              var element = '';
              <?php
              if(in_array('HBTN',$_SESSION['module_code']))
              {
                ?>
                  element += '';
                <?php
              }
              else
              {
                ?>
                element += data;
                <?php
              }
              ?>
              return element;

              }},
            { "data": "status" },
            { "data": "button" , render:function( data, type, row ){

              var element = '';
              <?php
              if(in_array('HBTN',$_SESSION['module_code']))
              {
                ?>
                  element += '';
                <?php
              }
              else
              {
                ?>
                element += data;
                <?php
              }
              ?>
              return element;

              }},
            { "data": "box" , render:function( data, type, row ){

              var element = '';
              <?php
              if(in_array('HBTN',$_SESSION['module_code']))
              {
                ?>
                  element += '';
                <?php
              }
              else
              {
                ?>
                element += data;
                <?php
              }
              ?>
              return element;

            }},
        ],
        //dom: 'lBfrtip',
        dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
        buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
        ]

      });
    }

    main_table(po_ref_no, ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);

  });

  $('#search').click(function() {

    po_ref_no = $('#po_num').val();
    ref_no = $('#gr_num').val();
    status = $('#po_status').val();
    daterange = $('#daterange').val();
    period_code = $('#period_code').val();
    daterange = daterange.split(" - ");
    datefrom = daterange[0];
    dateto = daterange[1];
    exp_datefrom = $('#expiry_from').val();
    exp_dateto = $('#expiry_to').val();
    loc = "<?php echo $_SESSION['gr_loc']; ?>";

    if (po_ref_no != '') {
      $('#po_ref_no_tag').removeClass("hidden").html(po_ref_no);
    } else {
      $('#po_ref_no_tag').addClass("hidden").html('');
    }

    if (ref_no != '') {
      $('#ref_no_tag').removeClass("hidden").html(ref_no);
    } else {
      $('#ref_no_tag').addClass("hidden").html('');
    }

    if (status != '') {
      if(status == 'gr_completed'){
        status = 'GRN Completed';
        $('#status_tag').removeClass("hidden").html(status[0].toUpperCase() + status.substring(1));
      } else {
        $('#status_tag').removeClass("hidden").html(status[0].toUpperCase() + status.substring(1));
      }
    }
    else{
      $('#status_tag').removeClass("hidden").html('NEW');
    }

    if (daterange != '') {
      $('#po_date_tag').removeClass("hidden").html('GR Date Range : ' + datefrom + ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' + dateto);
    } else {
      $('#po_date_tag').addClass("hidden").html('');
    }

    // if (exp_datefrom != '' && exp_dateto != '') {
    //   $('#exp_date_tag').removeClass("hidden").html('Expired Date Range : ' + exp_datefrom + ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' + exp_dateto);
    // } else {
    //   $('#exp_date_tag').addClass("hidden").html('');
    // }

    // if (period_code != '') {
    //   $('#period_code_tag').removeClass("hidden").html(period_code);
    // } else {
    //   $('#period_code_tag').addClass("hidden").html('');
    // }

    main_table(po_ref_no, ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);

  })

  $('#reset').click(function() {

    po_ref_no = '';
    ref_no = '';
    status = '';
    datefrom = '';
    dateto = '';
    exp_datefrom = '';
    exp_dateto = '';
    period_code = '';
    loc = '';

    $('#po_num').val('');
    $('#gr_num').val('');
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
    $('#po_ref_no_tag').addClass("hidden").html('');


    main_table(po_ref_no, ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);

  })

  function filter_status(data){
    if(data == '1'){
      new_status = 'accepted';
      $('#po_status').val('accepted');
    }else if(data == '2'){
      new_status = 'rejected';
      $('#po_status').val('rejected');
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

    main_table(po_ref_no, ref_no, new_status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);
  }
</script>
