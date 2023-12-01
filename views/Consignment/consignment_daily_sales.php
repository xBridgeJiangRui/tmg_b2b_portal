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

            <div class="row">

              <div class="col-md-2"><b>Code</b></div>
              <div class="col-md-8">
                <select id="consign_code" name="consign_code[]" class="form-control select2" required>
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
                <select id="consign_location" name="consign_location[]" class="form-control select2" multiple required>
                  <?php foreach($location->result() as $row){ ?>
                    <option value="<?php echo $row->branch_code ?>"> 
                    <?php echo $row->branch_code.' - '.$row->branch_desc; ?></option>
                 <?php } ?>
                </select>
              </div>
              <div class="col-md-2">
                    <button id="consign_location_all" class="btn btn-primary" >ALL</button>
                    <button id="consign_location_all_dis" class="btn btn-danger" >X</button>
              </div>
              <div class="clearfix"></div><br>

                <!-- </form> -->
                    
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
          <h3 class="box-title">Daily Sales By Consignment<span class="add_branch_list"></span></h3>
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
            <tr>
              <!-- <th>Action</th> -->
              <th>Supplier Name</th>
              <th>Supplier Code</th>
              <th>Biz Date</th>
              <th>Location</th>
              <th>Item Code</th>
              <th>Barcode</th>
              <th>Description</th>
              <th>UOM</th>
              <th>Quantity</th>
              <th>Total NetSales</th>
              <th>Cost</th>
              <th>Profit</th>
              <th>Gross Profit</th>
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
    mend = '<?php echo date('Y-m-t', strtotime(date('Y-m-d') . " - 1 month"));?>';
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
    var consign_code = $('#consign_code').val();
    var consign_location = $('#consign_location').val();

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

    if(consign_code == '' || consign_code == null)
    {
      alert('Please Choose Code');
      return;
    }   

    if(consign_location == '' || consign_location == null)
    {
      alert('Please Choose Location');
      return;
    }    

    datatable(date_start,date_end,consign_code,consign_location);
    // $('.add_branch_list').addClass('pill_button');
    // $('.add_branch_list').html(value);

  });//close search button

  datatable = function(date_start,date_end,consign_code,consign_location)
  { 
    $.ajax({
      url : "<?php echo site_url('Consignment_b2b_report/consign_sum_daily_table');?>",
      method: "POST",
      data:{date_start:date_start,date_end:date_end,consign_code:consign_code,consign_location:consign_location},
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
          { className: "alignright", targets: [9,10,11,12] },
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
                    // {"data" : "empty" , render: function(data, type, row){
                    //   var element = '';

                    //   element += '<button id="details_btn" style="margin-left:5px;" title="EDIT" class="btn btn-sm btn-info" itemcode="'+row['itemcode']+'" bizdate="'+row['bizdate']+'" loc_group="'+row['loc_group']+'" supplier_code="'+row['supplier_code']+'" supplier_name="'+row['supplier_name']+'" description ="'+row['description']+'" ><i class="fa fa-edit"></i></button>';
                      
                    //   return element;
                    // }},
                    {"data" : "supplier_name" },
                    {"data" : "supplier_code" },
                    {"data" : "bizdate" },
                    {"data" : "loc_group" , render: function(data, type, row){
                      var element = '';

                      element = data + ' - ' + row['location_name'];
                      
                      return element;

                    }},
                    {"data" : "itemcode" },
                    {"data" : "barcode" },
                    {"data" : "description" },
                    {"data" : "uom" },
                    {"data" : "sum_qty" },
                    {"data" : "total_amount" , render: $.fn.dataTable.render.number(',', '.', 2, '')},
                    {"data" : "total_cost" , render: $.fn.dataTable.render.number(',', '.', 2, '')},
                    {"data" : "total_profit" , render: $.fn.dataTable.render.number(',', '.', 2, '')},
                    {"data" : "gross_profit" , render: function(data, type, row){
                      var element = '';
                      var gross_profit = $.fn.dataTable.render.number(',', '.', 0, '').display(data);
  
                      element = gross_profit + '%';
  
                      return element;
                    }},
                    
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

  $(document).on('click', '#details_btn', function(event){
    var supplier_name = $(this).attr('supplier_name');
    var itemcode = $(this).attr('itemcode');
    var bizdate = $(this).attr('bizdate');
    var loc_group = $(this).attr('loc_group');
    var supplier_code = $(this).attr('supplier_code');
    var description = $(this).attr('description');

    if(itemcode == '' || itemcode == 'null' || itemcode == null)
    {
      alert('Invalid Itemcode.');
      return;
    }
    
    if(bizdate == '' || bizdate == 'null' || bizdate == null)
    {
      alert('Invalid Biz Date.');
      return;
    }

    if(loc_group == '' || loc_group == 'null' || loc_group == null)
    {
      alert('Invalid Location.');
      return;
    }

    if(supplier_code == '' || supplier_code == 'null' || supplier_code == null)
    {
      alert('Invalid Supplier Code.');
      return;
    }

    $.ajax({
      url:"<?php echo site_url('Consignment_b2b_report/consign_sum_daily_list_table') ?>",
      method:"POST",
      data:{itemcode:itemcode,bizdate:bizdate,loc_group:loc_group,supplier_code:supplier_code},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        var modal = $("#propose_medium-modal").modal();

        modal.find('.modal-title').html('Break Down List');

        methodd = '';

        methodd +='<div class="row"> <div class="col-md-12"> <div class="box box-info"> <div class="box-body"> <div class="col-md-6"><label>Supplier Name :</label> '+supplier_name+' </div> <div class="col-md-6"><label>Supplier Code :</label> '+supplier_code+' </div> <div class="col-md-6"><label>Location :</label> '+loc_group+' </div> <div class="col-md-6"><label>Biz Date :</label> '+bizdate+' </div> <div class="col-md-12"><label>Description :</label> '+description+' </div> <table id="list_table" class="table table-bordered table-striped" width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr> <th>Barcode</th> <th>Description</th> <th>UOM</th> <th>Quantity</th> <th>Amount</th> <th>Cost</th> <th>Profit</th> <th>Gross Profit</th> </tr> </thead> <tbody> </tbody> </table> <table id="footer" class="table" width="100%"> <tr> <th style="width:200px;">Total Amount </th> <th class="total_amount alignright"></th> </tr> <tr> <th >Total Cost</th> <th class="total_cost alignright"></th> </tr> <tr> <th >Total Profit</th> <th class="total_profit alignright"></th> </tr> </table>  </div> </div> </div> </div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
      
        setTimeout(function(){
          if ($.fn.DataTable.isDataTable('#list_table')) {
              $('#list_table').DataTable().destroy();
          }

          $('#list_table').DataTable({
            "columnDefs": [
            { className: "aligncenter", targets: [3] },
            { className: "alignright", targets: [4,5,6,7] },
            { className: "alignleft", targets: '_all' }
            ],
            "fixedColumns" : {
              leftColumns: 1
              },
            'processing'  : true,
            'paging'      : false,
            'lengthChange': true,
            'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
            'searching'   : true,
            'ordering'    : true,
            'order'       : [ [2 , 'desc'] ],
            'info'        : true,
            'autoWidth'   : false,
            "bPaginate": false, 
            "bFilter": true, 
            "sScrollY": "60vh", 
            "sScrollX": "100%", 
            "sScrollXInner": "100%", 
            "bScrollCollapse": true,
              data: json['query_data_list'],
              columns: [
                      {"data" : "barcode" },
                      {"data" : "description" },
                      {"data" : "uom" },
                      {"data" : "qty" },
                      {"data" : "amount" , render: $.fn.dataTable.render.number(',', '.', 2, '')},
                      {"data" : "cost" , render: $.fn.dataTable.render.number(',', '.', 2, '')},
                      {"data" : "profit" , render: $.fn.dataTable.render.number(',', '.', 2, '')},
                      {"data" : "gross_profit" , render: function(data, type, row){
                        var element = '';
                        var gross_profit = $.fn.dataTable.render.number(',', '.', 0, '').display(data);
    
                        element = gross_profit + '%';
    
                        return element;
                      }},
                ],
              dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip", 
            "language": {
                              "lengthMenu": "Show _MENU_",
                              "infoEmpty": "No records available",
                              "infoFiltered": "(filtered from _MAX_ total records)",
                              "zeroRecords": "<span><?php echo '<b>No Record Found.</b>'; ?></span>",
                      }, 
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).closest('tr').css({"cursor": "pointer"});

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
                total_amount = api
                    .column( 4 )
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

                total_cost = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    
                // Total over this page
                pageTotal = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                total_profit = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    
                // Total over this page
                pageTotal = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                // Update footer
                $( '.total_amount' ).html(
                    /*''+(pageTotal).toFixed(2) +' <hr>'+ (total).toFixed(2)+''*/
                    (total_amount).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+''
                );

                // Update footer
                $( '.total_cost' ).html(
                    /*''+(pageTotal).toFixed(2) +' <hr>'+ (total).toFixed(2)+''*/
                    (total_cost).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+''
                );

                // Update footer
                $( '.total_profit' ).html(
                    /*''+(pageTotal).toFixed(2) +' <hr>'+ (total).toFixed(2)+''*/
                    (total_profit).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+''
                );
            },
          });//close datatable

        },300);

        $('.btn').button('reset');
      }//close success

    });//close ajax 

  });

  $(document).on('change', '#doc_type', function(){
    $('#insert_refno').val('');
  });//CLOSE ONCLICK  

  $(document).on('click', '#consign_location_all', function(){
    // alert();
    $("#consign_location option").prop('selected',true);
    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('click', '#consign_location_all_dis', function(){
    // alert();
    $("#consign_location option").prop('selected',false);
    $(".select2").select2();
  });//CLOSE ONCLICK  

});
</script>
