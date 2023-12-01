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
         <a class="btn btn-app" href="<?php echo site_url('b2b_pdncn/pdncn_list') ?> ">
          <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>

        <a class="btn btn-app" style="color:#fa7f0c" onclick="filter_status(4)" title="New" >
          <i class="fa fa-file-o"></i> Unread 
          <!-- <small> (New) </small>  <small> (Viewed & Printed) </small> -->
        </a>
        <a class="btn btn-app" style="color:#5693f5" onclick="filter_status(3)" title="Viewed & Printed">
          <i class="fa fa-file-word-o"></i> Read 
        </a>
        <a class="btn btn-app" style="color:#a320f5" onclick="filter_status(5)" title="All">
          <i class="fa fa-list"></i> All
        </a>

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
              <div class="col-md-2"><b>PDN/CN Ref No</b></div>
              <div class="col-md-4">
                 <input id="refno" name="refno" type="text" autocomplete="off" class="form-control pull-right">
              </div>
              <div class="clearfix"></div><br>
              <div class="col-md-2"><b>Transaction Type<br></b></div>
              <div class="col-md-4">
                <select id="txn_type" name="txn_type" class="form-control">
                  <?php foreach($filter_status->result() as $row){ ?>
                    <option value="<?php echo $row->code ?>" 
                      <?php if(strtolower($_REQUEST['status']) == strtolower($row->code))
                      {
                        echo 'selected';
                      } 
                      ?>
                    > 
                    <?php echo $row->reason; ?></option>
                 <?php } ?>
                </select> 
              </div>
              
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Doc Date Range<br>(YYYY-MM-DD)</b></div>
                  <div class="col-md-4">
                    <input required id="daterange" name="daterange" type="datetime" class="form-control pull-right" id="reservationtime" readonly>
                  </div>
                  <div class="col-md-2">
                    <a class="btn btn-danger" onclick="date_clear()">Clear</a>
                  </div>
                <div class="clearfix"></div><br>


              <!-- <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
              <div class="col-md-4">
                <select id="period_code" name="period_code" class="form-control">
                  <option value="">None</option>
                  <?php foreach($period_code->result() as $row){ ?>
                    <option value="<?php echo $row->period_code ?>" 
                       <?php if(isset($_SESSION['filter_period_code'])){
                      if($_SESSION['filter_period_code'] == $row->period_code)
                      {
                        echo 'selected';
                      } }
                      ?>
                    > 
                    <?php echo $row->period_code; ?></option>
                 <?php } ?>
                </select> 
              </div>
              <div class="clearfix"></div><br> -->

              <div class="col-md-12">
                  <button id="search" class="btn btn-primary" ><i class="fa fa-search"></i> Search</button>
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
          <h3 class="box-title"><b>PDN/CN</b></h3>
          <span class="pill_button" id="status_tag">
              <?php

              $status = 'all';

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

            <span class="pill_button hidden" id="po_date_tag">

            </span>

            <br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
            <!--Bulk print form here -->
            <form target="_blank" action="<?php echo site_url('general/merge_jasper_pdf') ?>" id="bulk_print_form" method="post">
            </form>
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
                            <?php // var_dump($_SESSION); ?>
                                <!--Begin=Column Header-->
                                <th>PDN/CN Refno</th>
                                <th>Outlet</th>
                                <th>Code</th>
                                <th>Name</th>                                
                                <th>Type</th>
                                <th>Doc No</th>
                                <th>Doc Date</th>
                                <th>Amount</th>
                                <th>Tax</th>
                                <th>Total Incl Tax</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th><input type="checkbox" id="check-all"/></th>
                                <!--End=Column Header-->
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
 
<?php //   echo var_dump($_SESSION); ?>
</div>
</div>

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

      element2.value="PDNCN";
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
  function filter_status(data){

    var ref_no = '';
    var txn_type = '';
    var period_code = '';
    var loc = '';

    if(data == '1'){
      txn_type = 'accepted';
      $('#po_status').val('accepted');
    }else if(data == '2'){
      txn_type = 'rejected';
      $('#po_status').val('rejected');
    }else if(data == '3'){
      txn_type = 'READ';
      $('#po_status').val('READ');
      $('#status_tag').removeClass("hidden").html('Read');
    }else if(data == '4'){
      txn_type = 'UNREAD';
      $('#po_status').val('UNREAD');
      $('#status_tag').removeClass("hidden").html('Unread');
    }else if(data == '5'){
      new_status = 'ALL';
      $('#po_status').val('ALL');
      $('#status_tag').removeClass("hidden").html('All');
    } else {
      txn_type = '';
      $('#po_status').val('');
    }

    main_table(ref_no, txn_type, period_code, loc);
  }

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

  function date_clear() {
      $(function() {
        $(this).find('[name="daterange"]').val("");
      });
  }
</script>

<script>
  $(document).ready(function() {
    var ref_no = '';
    var txn_type = '';
    var period_code = '';
    var loc = '';
    var datefrom = '';
    var dateto = '';

    main_table = function(ref_no, txn_type, period_code, loc, datefrom, dateto) {

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
            "targets": [7, 8, 9],
            "className": "alignright",
          },
          {
            "targets": [ 11,12 ], //first column
            "orderable": false, //set not orderable
          },
        ],
        "ajax": {
          "url": "<?php echo site_url('B2b_pdncn/pdncn_datatable') ?>",
          "type": "POST",
          "data": function(data) {
            data.type = 'pdncn'
            data.ref_no = ref_no
            data.txn_type = txn_type
            data.period_code = period_code
            data.datefrom = datefrom
            data.dateto = dateto
            data.loc = loc
          },
        },
        "columns": [
            { "data": "refno" },
            { "data": "loc_group" },
            { "data": "supplier_code" },
            { "data": "supplier_name" },
            { "data": "trans_type" },
            { "data": "docno" },
            { "data": "docdate" },
            { "data": "amount" , render:function( data, type, row ){
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
            { "data": "amount_include_tax" , render:function( data, type, row ){

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

    $('#search').click(function() {

      ref_no = $('#refno').val();
      txn_type = $('#txn_type option:selected').text();
      period_code = ''; //$('#period_code option:selected').text();
      loc = "<?php echo $_SESSION['pdncn_loc']; ?>";
      daterange = $('#daterange').val();
      daterange = daterange.split(" - ");
      datefrom = daterange[0];
      dateto = daterange[1];

      if (ref_no != '') {
        $('#ref_no_tag').removeClass("hidden").html(ref_no);
      } else {
        $('#ref_no_tag').addClass("hidden").html('');
      }

      if (txn_type != '') {
        if(txn_type == 'PCNAMT'){
          txn_type = 'PCNAMT';
          $('#status_tag').removeClass("hidden").html(txn_type);
        }else if(txn_type == 'PDNAMT'){
          txn_type = 'PDNAMT';
          $('#status_tag').removeClass("hidden").html(txn_type);
        } else {
          $('#status_tag').removeClass("hidden").html(txn_type);
        }
      }
      else{
        $('#status_tag').removeClass("hidden").html('NEW');
      }

      if (period_code != '' && period_code != 'None') {
        $('#period_code_tag').removeClass("hidden").html(period_code);
      } else {
        $('#period_code_tag').addClass("hidden").html('');
      }

      if (daterange != '') {
        $('#po_date_tag').removeClass("hidden").html('Doc Date Range : ' + datefrom + ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' + dateto);
      } else {
        $('#po_date_tag').addClass("hidden").html('');
      }

      main_table(ref_no, txn_type, period_code, loc, datefrom, dateto);

    })

    $('#reset').click(function() {

      ref_no = '';
      txn_type = '';
      period_code = '';
      loc = '';
      datefrom = '';
      dateto = '';

      $('#refno').val('');
      $('#txn_type').val('ALL');
      $('#daterange').val('');
      $('#period_code').val('');

      $('#status_tag').html('ALL');
      $('#period_code_tag').addClass("hidden").html('');
      $('#ref_no_tag').addClass("hidden").html('');
      $('#po_date_tag').addClass("hidden").html('');


      main_table(ref_no, txn_type, period_code, loc, datefrom, dateto);

    })

    main_table(ref_no, txn_type, period_code, loc, datefrom, dateto);

  });

</script>

