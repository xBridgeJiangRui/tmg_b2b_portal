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
  <div class="col-md-12">
    <a class="btn btn-app" href="<?php echo site_url('Consignment') ?>">
      <i class="fa fa-undo"></i> Back
    </a>
  </div>
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

              <div class="col-md-2"><b>Retailer</b></div>
              <div class="col-md-8">
                <select id="retailer" name="retailer[]" class="form-control select2" required>
                  <option value="">Please Select Retailer</option> 
                  <?php foreach($retailer->result() as $row){ ?>
                    <option value="<?php echo $row->acc_guid ?>"> 
                    <?php echo $row->acc_name; ?></option>
                 <?php } ?>
                </select>
              </div>

              <div class="col-md-2">

              </div>

              <div class="clearfix"></div><br>

            </div>       

            <div class="row">

              <div class="col-md-2"><b>Effective Date</b></div>
              <div class="col-md-3">
                <select id="effective_date" name="effective_date[]" class="form-control select2" required>
                  <option value="">Please Select Effective Date</option> 
                  <?php foreach($effective_date->result() as $row){ ?>
                    <option value="<?php echo $row->effective_date ?>"> 
                    <?php echo $row->effective_date; ?></option>
                 <?php } ?>
                </select>
              </div>

              <div class="col-md-2"><b>Statement Date</b></div>
              <div class="col-md-3">
                <select id="statement_date" name="statement_date[]" class="form-control select2" required>
                  <option value="">Please Select Statement Date</option> 
                  <?php foreach($statement_date->result() as $row){ ?>
                    <option value="<?php echo $row->statement_date ?>"> 
                    <?php echo $row->statement_date; ?></option>
                 <?php } ?>
                </select>
              </div>
              <div class="clearfix"></div><br>

                <!-- </form> -->
                    
            </div>   
            
            <div class="row">

              <div class="col-md-2"><b>Email</b></div>
              <div class="col-md-3">
                <input type="text" id="email" name="email" class="form-control pull-right">
              </div>

              <div class="col-md-2"><b>Status</b></div>
              <div class="col-md-3">
                <select id="status" name="status[]" class="form-control" required>
                  <option value="">Please Select Status</option> 
                  <option value="success"> Success</option>
                  <option value="fail"> Failed</option>
                  <option value="pending"> Pending</option>
                </select>
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
          <h3 class="box-title">Consign Statement Email <span class="add_branch_list"></span></h3>
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
              <th>Retailer</th>
              <th>Email</th>
              <th>Effective Date</th>
              <th>Statement Date</th>
              <th>Status</th>
              <th>Response Message</th>
              <th>Send At</th>
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

    var retailer = $('#retailer').val();
    var effective_date = $('#effective_date').val();
    var statement_date = $('#statement_date').val();
    var email = $('#email').val();
    var status = $('#status').val();

    datatable(retailer,effective_date,statement_date,email,status);

  });//close search button

  datatable = function(retailer,effective_date,statement_date,email,status)
  { 
    $.ajax({
      url : "<?php echo site_url('Consignment/check_email_list');?>",
      method: "POST",
      data:{retailer:retailer,effective_date:effective_date,statement_date:statement_date,email:email,status:status},
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
        { className: "alignright", targets: [6] },
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
                    {"data" : "acc_name" },
                    {"data" : "email_add" },
                    {"data" : "effective_date" },
                    {"data" : "statement_date" },
                    {"data": "status" ,render:function( data, type, row ){

                      var element = '';

                      if(row['status'] == '3'){
                        element += '<button type="button" class="btn btn-xs btn-success"> Success</button>';
                      }else if(row['status'] == '99'){
                        element += '<button type="button" class="btn btn-xs btn-danger"> Failed</button>';
                      }else{
                        element += '<button type="button" class="btn btn-xs btn-warning"> Pending</button>';
                      }

                      return element;

                    }},
                    {"data" : "remark" },
                    {"data" : "sent_at" },
                   ],
          dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>Brtip",  
          buttons: [
                {
                    extend: 'csv',
                    title: 'Consign Check Email'
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

  datatable('','','','','');

});
</script>
