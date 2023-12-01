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

              <div class="col-md-2"><b>Code</b></div>
              <div class="col-md-8">
                <select id="outright_code" name="outright_code[]" class="form-control select2" required>
                  <option value="">Please Select One Code</option> 
                  <?php foreach($code->result() as $row){ ?>
                    <option value="<?php echo $row->Code ?>"> 
                    <?php echo ($row->supp_type != '') ? $row->Code.' - '.$row->Name.' ('.$row->supp_type.')' : $row->Code.' - '.$row->Name; ?></option>
                 <?php } ?>
                </select>
              </div>

              <!-- <div class="col-md-2"><b>Item Type</b></div>
              <div class="col-md-2">
                <select class="form-control" name="itemtype" id="itemtype">
                  <option value="">All</option>
                  <option value="0">Outright</option>
                  <option value="1">Consign</option>
                </select>
              </div> -->

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
                  <option value="All_jing">ALL</option>
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

              <div class="col-md-2"><b>Item Code</b></div>
              <div class="col-md-2">
                <input type="text" id="itemcode" name="itemcode" class="form-control pull-right">
              </div>

              <!-- <div class="col-md-2"><b>Article No</b></div>
              <div class="col-md-2">
                <input type="text" id="article_no" name="article_no" class="form-control pull-right">
              </div> -->

              <div class="col-md-2"><b>Description</b></div>
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
          <h3 class="box-title">Supplier Information Query <span class="add_branch_list"></span></h3>
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
              <th>Supplier Name</th>
              <th>Supplier Code</th>
              <th>Dept No</th>
              <th>Category No</th>
              <th>Item Code</th>
              <!-- <th>Main Barcode</th> -->
              <th>Barcode</th>
              <th>Description</th>
              <th>Packing Unit</th>
              <th>No. Of Boxes</th>
              <th>Order Unit Price</th>
              <!-- <th>Entry Tax Rate (%)</th> -->
              <th>Selling Price</th>
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

    var outright_code = $('#outright_code').val();
    var outright_location = $('#outright_location').val();
    var category = $('#category').val();
    var itemcode = $('#itemcode').val();
    var article_no = $('#article_no').val();
    var article_desc = $('#article_desc').val();
    var itemtype = $('#itemtype').val();

    // if(outright_code == '' || outright_code == null)
    // {
    //   if (!confirm('Not filter Supplier Code may cause query to load longer, are you sure to proceed?')) {
    //     return;
    //   }
    // }   

    // if(outright_location == '' || outright_location == null)
    // {
    //   alert('Please Choose Location');
    //   return;
    // }

    // if(category == '' || category == null)
    // {
    //   alert('Please Choose Category');
    //   return;
    // }

    // if(article_no == '' || article_no == null)
    // {
    //   alert('Please Choose Article No');
    //   return;
    // }

    // if(article_desc == '' || article_desc == null)
    // {
    //   alert('Please Choose Article Description');
    //   return;
    // }

    datatable(outright_code,outright_location,category,article_no,article_desc,itemcode,itemtype);
    // $('.add_branch_list').addClass('pill_button');
    // $('.add_branch_list').html(value);

  });//close search button

  datatable = function(outright_code,outright_location,category,article_no,article_desc,itemcode,itemtype)
  { 
    $.ajax({
      url : "<?php echo site_url('Article_report/supplier_article_information_query_table');?>",
      method: "POST",
      data:{outright_code:outright_code,outright_location:outright_location,category:category,article_no:article_no,article_desc:article_desc,itemcode:itemcode,itemtype:itemtype},
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
        { className: "aligncenter", targets: [] },
        { className: "alignright", targets: [8,9,10] },
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
                    {"data" : "Name" },
                    {"data" : "Code" },
                    {"data" : "deptdesc" },
                    {"data" : "categorydesc" },
                    {"data" : "Itemcode"},
                    // {"data" : "main_barcode" },
                    {"data" : "Barcode" },
                    {"data" : "description" },
                    {"data" : "um" },
                    {"data" : "OrderLotSize" },
                    {"data" : "averagecost" },
                    // {"data" : "tax_rate" },
                    {"data" : "netsales" },
                   ],
          dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>Brtip",  
          buttons: [
                {
                    extend: 'csv',
                    title: 'Supplier Article Information'
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
    var category = $('#category').val('All_jing');
    // $("#category option").prop('selected',true);
    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('click', '#category_all_dis', function(){
    // alert();
    $("#category option").prop('selected',false);
    $(".select2").select2();
  });//CLOSE ONCLICK 

  // datatable('','','','','');

});
</script>
