<style>
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

.select2-container--default .select2-selection--multiple .select2-selection__choice
{
    background: #3c8dbc;
}  
</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>
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
          <?php if($_GET['report_type'] == 'sum_daily_list'){ ?>
            <div class="row">
                <div class="col-md-2"><b>Date From<br>(YYYY-MM-DD)</b></div>
                <div class="col-md-2">
                  <input  id="date_from" name="date_from" type="datetime" value="" readonly class="form-control pull-right">
                </div>
                <div class="col-md-2"><b>Date To<br>(YYYY-MM-DD)</b></div>
                <div class="col-md-2">
                  <input  id="date_to" name="date_to" type="datetime" class="form-control pull-right" readonly value="">
                </div>
                <div class="col-md-1">
                  <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
                </div>
              <div class="clearfix"></div><br>
                <!-- </form> -->
            </div>
            <?php } ?>

            <div class="row">

              <div class="col-md-2"><b>Code</b></div>
              <div class="col-md-8">
                <select id="outright_code" name="outright_code[]" class="form-control select2" required>
                  <option value="">Please Select One Code</option> 
                  <?php foreach($code->result() as $row){ ?>
                    <option value="<?php echo $row->Code ?>"> 
                    <?php echo $row->Code.' - '.$row->Name; ?></option>
                 <?php } ?>
                </select>
              </div>

              <div class="col-md-2">

              </div>

              <div class="clearfix"></div><br>

            </div>       

            <div class="row">

              <div class="col-md-2"><b>Location</b></div>
              <div class="col-md-8">
                <select id="outright_location" name="outright_location[]" class="form-control select2" multiple required>
                  <?php foreach($location->result() as $row){ ?>
                    <option value="<?php echo $row->branch_code ?>"> 
                    <?php echo $row->branch_code.' - '.$row->branch_desc; ?></option>
                 <?php } ?>
                </select>
              </div>
              <div class="col-md-2">
                    <button id="outright_location_all" class="btn btn-primary" >ALL</button>
                    <button id="outright_location_all_dis" class="btn btn-danger" >X</button>
              </div>
              <div class="clearfix"></div><br>

                <!-- </form> -->
                    
            </div>     

            <div class="row">

              <div class="col-md-2"><b>Category</b></div>
              <div class="col-md-8">
                <select id="category" name="category[]" class="form-control select2" multiple required>
                  <?php foreach($category->result() as $row){ ?>
                    <option value="<?php echo $row->category ?>"> 
                    <?php echo $row->categorydesc; ?></option>
                 <?php } ?>
                </select>
              </div>
              <div class="col-md-2">
                    <button id="category_all" class="btn btn-primary" >ALL</button>
                    <button id="category_all_dis" class="btn btn-danger" >X</button>
              </div>
              <div class="clearfix"></div><br>

                <!-- </form> -->
                    
            </div>  

            <div class="row">

              <div class="col-md-2"><b>Article No</b></div>
              <div class="col-md-2">
                <input type="text" id="article_no" name="article_no" class="form-control pull-right">
              </div>

              <div class="col-md-2"><b>Article Description</b></div>
              <div class="col-md-4">
                <input type="text" id="article_desc" name="article_desc" class="form-control pull-right">
              </div>
              <div class="clearfix"></div><br>
                    
            </div> 

            <div class="row">
              <div class="col-md-2">
                <a class="btn btn-success" id="search_data">Submit</a>
              </div>
            </div>      

          </div>
        </div>
        <!-- body -->
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $report_title; ?> <span class="add_branch_list"></span></h3>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$_SESSION['module_code']))
            {
            ?>
              <!-- <a class="btn btn-xs btn-warning" target="_blank" href="<?php echo site_url('Amend_doc/amend_sites');?>">
                <i class="fa fa-file"></i> Hide/Reset Doc
              </a> -->
            <?php
            }
            ?> 
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >

          <table id="table1" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead style="white-space: nowrap;"> <!--style="white-space: nowrap;"-->
              <?php echo $table_column; ?>
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

<script>
$(document).ready(function() {

  $('#table1').DataTable({
    "columnDefs": [{"targets": '_all' ,"orderable": false}],
      'order': [],
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

  var today = '<?php echo date('Y-m-d', strtotime(date('Y-m-01') . " - 1 month"));?>';

  $(function() {
    $('input[name="date_from"]').daterangepicker({
        locale: {
        format: 'YYYY-MM-DD'
        },
        startDate: today,
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: true,
        
    },function(start) {

        // alert(moment(start, 'DD-MM-YYYY').add(31, 'days'));
        qenddate = moment(start, 'DD-MM-YYYY').add(30, 'days');
        enddate = moment(start, 'DD-MM-YYYY').endOf('month');
        // var maxDate = start.addDays(5);
        // alert(maxDate);

            $('input[name="date_to"]').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            "minDate": start,
            "maxDate": qenddate,
            startDate: enddate,
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: true,
            });
        });
    $(this).find('[name="date_from"]').val(today);
  });

  $(function() {
    mend = '<?php echo date('Y-m-t', strtotime(date('Y-m-d') . " - 30 days"));?>';
    $('input[name="date_to"]').daterangepicker({
        locale: {
        format: 'YYYY-MM-DD'
        },
        "minDate": today,
        "maxDate": mend,
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: true,
    });
    $(this).find('[name="date_to"]').val(mend);
  });

  function expiry_clear()
  {
    $(function() {
        $(this).find('[name="date_from"]').val("");
        $(this).find('[name="date_to"]').val("");
    });
  }

  $(document).on('click','#search_data',function(){

    var date_start = $('#date_from').val();
    var date_end = $('#date_to').val();
    var outright_code = $('#outright_code').val();
    var outright_location = $('#outright_location').val();
    var article_no = $('#article_no').val();
    var article_desc = $('#article_desc').val();

    if(date_start == '' || date_start == null)
    {
      alert('Please Choose Start Date');
      return;
    }

    if(date_end == '' || date_end == null)
    {
      alert('Please Choose End Date');
      return;
    }

    if(date_end < date_start)
    {
      alert('Date End cannot smaller than date start.');
      return;
    }

    if(outright_code == '' || outright_code == null)
    {
      alert('Please Choose Code');
      return;
    }   

    if(outright_location == '' || outright_location == null)
    {
      alert('Please Choose Location');
      return;
    }    

    <?php if($_GET['report_type'] == 'sum_daily_list'){ ?>
      datatable_sum_daily_list(date_start,date_end,outright_code,outright_location);
    <?php }else if($_GET['report_type'] == 'supplier_daily_inventory'){ ?>
      datatable_supplier_daily_inventory(date_start,date_end,outright_code,outright_location);
    <?php }else if($_GET['report_type'] == 'supplier_article_information_query'){ ?>
      datatable_supplier_article_information_query(date_start,date_end,outright_code,outright_location,article_no,article_desc);
    <?php } ?>

    // $('.add_branch_list').addClass('pill_button');
    // $('.add_branch_list').html(value);

  });//close search button

  datatable_sum_daily_list = function(date_start,date_end,outright_code,outright_location)
  { 
    $.ajax({
      url : "<?php echo site_url('Article_report/sum_daily_table');?>",
      method: "POST",
      data:{date_start:date_start,date_end:date_end,outright_code:outright_code,outright_location:outright_location},
      beforeSend : function() {
          $('.btn').button('loading');
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success : function(data)
      {  
        json = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#table1')) {
            $('#table1').DataTable().destroy();
        }

        $('#table1').DataTable({
        "columnDefs": [
        { className: "aligncenter", targets: [7,8] },
        { className: "alignright", targets: [9] },
        { className: "alignleft", targets: '_all' },
        ],
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [ [2 , 'desc'] ],
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true, 
        "bFilter": true, 
        "sScrollY": "40vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
          data: json['query_data'],
          columns: [

                    {"data" : "supplier_name" },
                    {"data" : "supplier_code" },
                    {"data" : "bizdate" },
                    {"data" : "location" , render: function(data, type, row){
                      var element = '';

                      element = data + ' - ' + row['location_name'];
                      
                      return element;

                    }},
                    {"data" : "itemcode"},
                    {"data" : "description" },
                    {"data" : "barcode" },
                    {"data" : "total_qty" },
                    {"data" : "uom" },
                    {"data" : "total_netsales" },
                   ],
          dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>Brtip",  
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
      }//close success
    });//close ajax
  }//close proposed batch table

  datatable_supplier_daily_inventory = function(date_start,date_end,outright_code,outright_location)
  { 
    $.ajax({
      url : "<?php echo site_url('Article_report/supplier_daily_inventory_table');?>",
      method: "POST",
      data:{date_start:date_start,date_end:date_end,outright_code:outright_code,outright_location:outright_location},
      beforeSend : function() {
          $('.btn').button('loading');
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success : function(data)
      {  
        json = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#table1')) {
            $('#table1').DataTable().destroy();
        }

        $('#table1').DataTable({
        "columnDefs": [
        { className: "aligncenter", targets: [7,8] },
        { className: "alignright", targets: [9] },
        { className: "alignleft", targets: '_all' },
        ],
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [ [2 , 'desc'] ],
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true, 
        "bFilter": true, 
        "sScrollY": "40vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
          data: json['query_data'],
          columns: [

                    {"data" : "branch_code" },
                    {"data" : "branch_name" },
                    {"data" : "Code" },
                    {"data" : "Name" },
                    {"data" : "articleNo"},
                    {"data" : "barcode" },
                    {"data" : "description" },
                    {"data" : "um" },
                    {"data" : "averagecost" },
                    {"data" : "lastcost" },
                    {"data" : "netsales" },
                    {"data" : "QOH" },
                    {"data" : "inv_amt" },
                   ],
          dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>Brtip",  
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
      }//close success
    });//close ajax
  }//close proposed batch table

  datatable_supplier_article_information_query = function(date_start,date_end,outright_code,outright_location,article_no,article_desc)
  { 
    $.ajax({
      url : "<?php echo site_url('Article_report/supplier_article_information_query_table');?>",
      method: "POST",
      data:{date_start:date_start,date_end:date_end,outright_code:outright_code,outright_location:outright_location,article_no:article_no,article_desc:article_desc},
      beforeSend : function() {
          $('.btn').button('loading');
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success : function(data)
      {  
        json = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#table1')) {
            $('#table1').DataTable().destroy();
        }

        $('#table1').DataTable({
        "columnDefs": [
        { className: "aligncenter", targets: [7,8] },
        { className: "alignright", targets: [9] },
        { className: "alignleft", targets: '_all' },
        ],
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [ [2 , 'desc'] ],
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true, 
        "bFilter": true, 
        "sScrollY": "40vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
          data: json['query_data'],
           columns: [
                    {"data" : "Code" },
                    {"data" : "Name" },
                    {"data" : "dept_desc" },
                    {"data" : "cat_desc" },
                    {"data" : "articleNo"},
                    {"data" : "barcode" },
                    {"data" : "description" },
                    {"data" : "um" },
                    {"data" : "SupBulkQty" },
                    {"data" : "averagecost" },
                    {"data" : "netsales" },
                   ],
          dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>Brtip",  
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
      }//close success
    });//close ajax
  }//close proposed batch table

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

  $(document).on('click', '#category_all', function(){
    // alert();
    $("#category option").prop('selected',true);
    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('click', '#category_all_dis', function(){
    // alert();
    $("#category option").prop('selected',false);
    $(".select2").select2();
  });//CLOSE ONCLICK 
  
  <?php if($_GET['report_type'] == 'sum_daily_list'){ ?>
    datatable_sum_daily_list(date_start,date_end,outright_code,outright_location);
  <?php }else if($_GET['report_type'] == 'supplier_daily_inventory'){ ?>
    datatable_supplier_daily_inventory(date_start,date_end,outright_code,outright_location);
  <?php }else if($_GET['report_type'] == 'supplier_article_information_query'){ ?>
    datatable_supplier_article_information_query('','','','');
  <?php } ?>

});
</script>
