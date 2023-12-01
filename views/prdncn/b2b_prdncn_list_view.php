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
         <a class="btn btn-app" href="<?php echo site_url('b2b_pci/pci_list') ?> ">
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

        <!-- <a class="btn btn-app pull-right"  style="color:#000000"  onclick="md5md5()" >
          <i class="fa fa-print"></i> HEllo
        </a> -->
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
                <div class="col-md-2"><b>PRDN/CN Ref No</b></div>
                <div class="col-md-4">
                  <input id="po_num" name="po_num" type="text" autocomplete="off" class="form-control pull-right">
                </div>
                <div class="clearfix"></div><br>

                <div class="col-md-2"><b>Transaction Type</b></div>
                <div class="col-md-4">
                  <select id="po_status" name="po_status" class="form-control">
                    <?php foreach ($filter_status->result() as $row) { ?>
                      <option value="<?php echo $row->code ?>" <?php if (strtolower($_REQUEST['status']) == strtolower($row->code)) {
                                                                  echo 'selected';
                                                                }
                                                                ?>>
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
          <h3 class="box-title"><b>PRDN/CN</b></h3>
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
            <!-- <?php echo $title_accno ?> -->
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
                                <!--Begin=Column Header-->
                                <th>PRDN/CN Refno</th>
                                <!-- <th>STRB Refno</th> -->
                                <th>Outlet</th>
                                <th>Type</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Doc Date</th>
                                <!-- <th>Doc No</th> edit-->
                                <th>Amount</th>
                                <th>Tax</th>
                                <th>Total Incl Tax</th>
                                <th>Stock Collected</th>
                                <th>Stock Collected By</th>
                                <th>Date Collected</th>                                
                                <th>Status</th>
                                <th>Action</th>
                                <th><input id="check-all" type="checkbox" value=""/></th>
                                <!--End=Column Header-->
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
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
    if (list_id.length > 0) {
      var form = document.getElementById("bulk_print_form");
      var element1 = document.createElement("input"); 
      var element2 = document.createElement("input");  
      element1.setAttribute("type", "hidden");
      element2.setAttribute("type", "hidden");
      
      element1.value=list_id;
      element1.name="id";
      form.appendChild(element1);  

      element2.value="PRDNCN"; // need change document type to print out
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
    var new_status = '';
    var period_code = '';
    var loc = '';

    if(data == '1'){
      new_status = 'accepted';
      $('#po_status').val('accepted');
    }else if(data == '2'){
      new_status = 'rejected';
      $('#po_status').val('rejected');
    }else if(data == '3'){
      new_status = 'READ';
      //$('#po_status').val('READ');
      $('#status_tag').removeClass("hidden").html('Read');
    }else if(data == '4'){
      new_status = 'UNREAD';
      //$('#po_status').val('UNREAD');
      $('#status_tag').removeClass("hidden").html('Unread');
    }else if(data == '5'){
      new_status = 'ALL';
      $('#po_status').val('ALL');
      $('#status_tag').removeClass("hidden").html('All');
    }else {
      new_status = '';
      $('#po_status').val('');
    }

    main_table(ref_no, new_status, period_code, loc);
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
    var status = '';
    var period_code = '';
    var loc = '';
    var datefrom = '';
    var dateto = '';

    main_table = function(ref_no, status, period_code, loc, datefrom, dateto) {

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
            "targets": [6, 7, 8],
            "className": "alignright",
          },
          {
            "targets": [ 13,14 ], //first column
            "orderable": false, //set not orderable
          },
        ],
        "ajax": {
          "url": "<?php echo site_url('B2b_prdncn/prdncn_datatable') ?>",
          "type": "POST",
          "data": function(data) {
            data.type = 'prdncn'
            data.ref_no = ref_no
            data.status = status
            data.datefrom = datefrom
            data.dateto = dateto
            data.period_code = period_code
            data.loc = loc
          },
        },
        "columns": [
            { "data": "refno" },
            // { "data": "batch_no" },
            { "data": "loc_group" },
            { "data": "type" },
            { "data": "supplier_code" },
            { "data": "supplier_name" },
            { "data": "docdate" },
            //{ "data": "docno" }, edit
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
            { "data": "total_incl_tax" , render:function( data, type, row ){

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
            { "data": "stock_collected" },
            { "data": "stock_collected_by" },
            { "data": "date_collected" },              
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

      ref_no = $('#po_num').val();
      status = $('#po_status').val();
      period_code = ''; //$('#period_code').val()
      daterange = $('#daterange').val();
      daterange = daterange.split(" - ");
      datefrom = daterange[0];
      dateto = daterange[1];
      loc = "<?php echo $_SESSION['prdncn_loc']; ?>";

      if (ref_no != '') {
        $('#ref_no_tag').removeClass("hidden").html(ref_no);
      } else {
        $('#ref_no_tag').addClass("hidden").html('');
      }

      if (status != '') {
        $('#status_tag').removeClass("hidden").html(status[0].toUpperCase() + status.substring(1));
      }

      if (daterange != '') {
        $('#po_date_tag').removeClass("hidden").html('Doc Date Range : ' + datefrom + ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' + dateto);
      } else {
        $('#po_date_tag').addClass("hidden").html('');
      }

      // if (period_code != '') {
      //   $('#period_code_tag').removeClass("hidden").html(period_code);
      // } else {
      //   $('#period_code_tag').addClass("hidden").html('');
      // }

      main_table(ref_no, status, period_code, loc, datefrom, dateto);

    })

    $('#reset').click(function() {

      ref_no = '';
      status = '';
      datefrom = '';
      dateto = '';
      period_code = '';
      loc = '';

      $('#po_num').val('');
      $('#po_status').val('ALL');
      $('#daterange').val('');
      $('#period_code').val('');

      $('#status_tag').html('ALL');
      $('#po_date_tag').addClass("hidden").html('');
      $('#period_code_tag').addClass("hidden").html('');
      $('#ref_no_tag').addClass("hidden").html('');


      main_table(ref_no, status, period_code, loc, datefrom, dateto);

    })

    main_table(ref_no, status, period_code, loc, datefrom, dateto);

  $(document).on('click','#btn_image',function(){
    var refno = $(this).attr('refno');
    var outlet = $(this).attr('outlet');
    var period_code = $(this).attr('period_code');
    var image_type = $(this).attr('image_type');

	  //alert(image_type); die;
    if((refno == '') || (refno == null) || (refno == 'null'))
    {
      alert('Invalid Get STRB RefNo.');
      return;
    }

    if((period_code == '') || (period_code == null) || (period_code == 'null'))
    {
      alert('Invalid Get Period Code.');
      return;
    }

    if((image_type == '') || (image_type == null) || (image_type == 'null'))
    {
      alert('Invalid Get Image Details.');
      return;
    }

    $.ajax({
        url:"<?php echo site_url('Panda_return_collection/strb_view_image') ?>",
        method:"POST",
        data:{refno:refno,period_code:period_code,image_type:image_type,outlet:outlet},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            var url = json.file_path_list;
            var name = url.toString().substring(url.lastIndexOf('/') + 1);
            
            var modal = $("#large-modal").modal();

            modal.find('.modal-title').html( 'STRB RefNo : <b>' + refno + '</b>');

            methodd = '';

            //methodd +='<div class="col-md-12">';

            //methodd += '<input type="hidden" class="form-control input-sm" id="supplier_guid" value="'+supplier_guid+'" readonly/>';

            Object.keys(url).forEach(function(key) {

              var before_name = url[key].toString().split('?')[0];
              var after_name = before_name.toString().split("/").slice(-1)[0];
              
              methodd += '<div class="col-md-12"><label>'+after_name+'<span id="alert'+key+'"></span></label>';

              methodd += '<input style="float:right;" type="button" id="show'+key+'" class="btn btn-primary view_image_btn" value="Show" path_url="'+url[key]+'" key="'+key+'" image_name="'+after_name+'">';

              methodd += '<input style="float:right;margin-right:5px;" type="button" id="show'+key+'" class="btn btn-warning dl_image_btn" value="Download" path_url="'+url[key]+'" key="'+key+'">';

              methodd += '</div>';

              methodd += '<div class="clearfix"></div><br>';

              methodd += '<div class="col-md-12"><span id="image'+key+'"></span></div>';

            });

            //methodd += '</div>';

            methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);

            setTimeout(function(){

              $(document).off('click', '.view_image_btn').on('click', '.view_image_btn', function(){
                var path_url = $(this).attr('path_url');
                var key = $(this).attr('key');
                var image_name = $(this).attr('image_name');
                var value = $(this).val();
                
                if(value == 'Show') 
                {
                  $.ajax({
                    url:"<?php echo site_url('General/strb_show_image_logs');?>",
                    method:"POST",
                    data:{refno:refno,image_name:image_name,},
                    beforeSend:function(){
                      $('.btn').button('loading');
                    },
                    success:function(data)
                    {
                      json = JSON.parse(data);
                      if (json.para1 == 'false') {
                        $('.btn').button('reset');
                        $('#alert'+key+'').html(' - '+json.msg);
                      }else{
                        $('.btn').button('reset');
                        //$(this).attr('value','Hide');
                        //$(this).attr('class','btn btn-danger view_image_btn');
                        $('#image'+key+'').html('<embed src="'+path_url+'" width="100%" height="800px" style="border: none;" toolbar="0" id="image_view'+key+'"/>');
                      }//close else
                    }//close success
                  });//close ajax
                }
                
                // if(value == 'Hide') 
                // {
                //   $(this).attr('value','Show');
                //   $(this).attr('class','btn btn-primary view_image_btn');
                //   $('#image'+key+'').html('');
                //   //alert('show image'); die;
                // }
              });//close modal create

              $(document).off('click', '.dl_image_btn').on('click', '.dl_image_btn', function(){
                var path_url = $(this).attr('path_url');
                var value = $(this).val();
                
                if(value == 'Download') 
                {
                  var form = document.createElement('a');
                  form.href = path_url;
                  form.download = path_url;
                  document.body.appendChild(form);
                  form.click();
                  alert('Download Successful'); 
                  $(this).attr('value','Download Complete');
                  $(this).prop('disabled', true);
                } 
              });//close modal create
            },300);
          }//close else
        }//close success
      });//close ajax 
  });//close image process

});
</script>