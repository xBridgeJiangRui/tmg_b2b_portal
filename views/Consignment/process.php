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

.no-js #loader {
    display: none;
}

.js #loader {
    display: block;
    position: absolute;
    left: 100px;
    top: 0;
}

.se-pre-con {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url("<?php echo base_url('assets/loading2.gif') ?>") center no-repeat #fff;
    /*background:   #fff;*/
}

</style>

<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>

  <div id="no_variance_message" class="alert alert-success text-center hidden" style="font-size: 18px">
    <?php echo 'No Variance Found, Please Proceed to Release'; ?>
    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>

  <div id="release_message" class="alert alert-success text-center hidden" style="font-size: 18px">
    <?php echo 'Please Proceed to Release'; ?>
    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>

  <div id="no_consign" class="alert alert-warning text-center hidden" style="font-size: 18px">
    <?php echo 'No consign to be Release'; ?>
    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>

<?php if ($_POST['email_status'] && $_POST['email_message']) { ?>
  <div class="alert <?php echo ($_POST['email_status'] == 1) ? 'alert-success' : 'alert-danger'; ?> text-center" style="font-size: 18px">
    <?php echo $_POST['email_message']; ?>
    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
<?php } ?>

<?php if ($this->session->flashdata('message')) { ?>
  <div class="alert alert-success text-center" style="font-size: 18px">
    <?php echo $this->session->flashdata('message') <> '' ? $this->session->flashdata('message') : ''; ?>
    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
<?php } ?>

<?php if ($this->session->flashdata('warning')) { ?>
  <div class="alert alert-danger text-center" style="font-size: 18px">
    <?php echo $this->session->flashdata('warning') <> '' ? $this->session->flashdata('warning') : ''; ?>
    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
<?php } ?>


<div class="col-md-12">
  <a class="btn btn-app" href="<?php echo site_url('Consignment/view_log') ?>">
    <i class="fa fa-file-text"></i> View Log
  </a>
  <a class="btn btn-app" style="color:#B8860B" href="<?php echo site_url('Consignment/check_email') ?>">
    <i class="fa fa-envelope"></i> Check Email
  </a>
  <a class="btn btn-app hidden" style="color:#3C8DBC" id="release_button" title="Release Consignment">
    <i class="fa fa-paper-plane"></i> Release 
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

              <div class="col-md-2"><b>Retailer</b></div>
              <div class="col-md-8">

                <?php if(sizeof($retailer->result()) == 1){ ?>
                    <input type="text" value="<?php echo $retailer->row('acc_name'); ?>" readonly class="form-control pull-right">
                    <input  id="retailer" name="retailer" type="text" value="<?php echo $retailer->row('acc_guid'); ?>" hidden>
                <?php }else{ ?>
                  <select id="retailer" name="retailer" class="form-control select2" required>
                    <option value="">Please Select One Retailer</option> 
                    <?php foreach($retailer->result() as $row){ ?>
                      <option value="<?php echo $row->acc_guid ?>"> 
                      <?php echo $row->acc_name; ?></option>
                    <?php } ?>
                  </select>
                <?php } ?>
              </div>

              <div class="col-md-2">

              </div>

              <div class="clearfix"></div><br>

            </div>           

            <div class="row">
              <div class="col-md-2">
                <a class="btn btn-success" id="search_data">Submit</a>
                <!-- <a class="btn btn-info" id="release_button">Release</a> -->
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
          <h3 class="box-title"><i class="fa fa-folder-open-o"></i> HQ Variance <span class="add_branch_list"></span></h3>
          <div class="box-tools pull-right">
            <div class="box-tools pull-right">
              <button type="button" id="hq_variance_collapse" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
              <!-- <a class="btn btn-xs btn-warning" target="_blank" href="<?php echo site_url('Amend_doc/amend_sites');?>">
                <i class="fa fa-file"></i> Hide/Reset Doc
              </a> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >

          <table id="table1" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead style="white-space: nowrap;"> <!--style="white-space: nowrap;"-->
            <tr>
              <th style="width: 1px;" class="text-center"><input type="checkbox" checked="checked" onclick="$('input[name*=\'consign_checkbox\']').prop('checked', this.checked);" /></th>
              <th>Variance Amt</th>
              <th>Cost Amt</th>
              <th>Invoice Amt</th>
              <th>Period Code</th>
              <th>Code</th>
              <th>Supplier</th>
              <th>Missing B2B</th>
              <th>Total Row</th>
              <th>Total Posted</th>
              <th>Total Outlet</th>
              <th>Diff Outlet</th>
              <th>Trans Refno</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div id="email-recipient-layout"></div>

</div>
</div>

<div id="loader_div" class="se-pre-con hidden"></div>

<script>
$(document).ready(function() {

  $( ".target" ).change(function() {
  alert( "Handler for .change() called." );
});

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
        maxDate: new Date(),
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

  $(document).on('click','#release_button',function(){

    var count_checkbox = $('#consign_checkbox:checked').length;

    if(count_checkbox < 1){
      Swal.fire({
        title: 'Please select at least one(1) supplier', 
        text: '', 
        type: "error",
        allowOutsideClick: true,
        showConfirmButton: true,
      });

      return;
    }

    // Swal.fire({
    //   title: "Are you sure?",
    //   text: "You will release " + count_check + " numbers of invoices!",
    //   icon: "warning",
    //   buttons: [
    //     'No, cancel it!',
    //     'Yes, I am sure!'
    //   ],
    //   dangerMode: true,
    // }).then(function(isConfirm) {
    //   if (isConfirm) {
    //     swal({
    //       title: 'Success!',
    //       text: count_check + 'invoices has been released!',
    //       icon: 'success'
    //     }).then(function() {
    //       form.submit();
    //     });
    //   } else {
    //     swal("Cancelled", "You may submit again to proceed", "error");
    //     return;
    //   }
    // });

    Swal.fire({
      title: 'Are you sure?',
      text: "You will release " + count_checkbox + " numbers of invoices!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, release it!',
      cancelButtonText: 'No, cancel',
    }).then((result) => {

      if (result.value) {

        $("#no_variance_message").addClass('hidden');
        $("#release_message").addClass('hidden');
        $("#no_consign").addClass('hidden');

        var date_start = $('#date_from').val();
        var date_end = $('#date_to').val();
        var retailer = $('#retailer').val();
        var selectedCheckboxes = $('input[name="consign_checkbox[]"]:checked');
        var selectedCheckboxValues = [];
        
        selectedCheckboxes.each(function() {
          selectedCheckboxValues.push($(this).val());
        });

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

        if(retailer == '' || retailer == null)
        {
          alert('Please Choose Retailer');
          return;
        }  

        $.ajax({
          url : "<?php echo site_url('Consignment/check_hq_variance');?>",
          method: "POST",
          data:{date_start:date_start,date_end:date_end,retailer:retailer,selectedCheckboxes:selectedCheckboxValues,doublecheck:true},
          beforeSend : function() {
              $('.btn').button('loading');
          },
          complete: function() {
              $('.btn').button('reset');
          },
          success : function(data)
          {  
            json = JSON.parse(data);
            
            //nabil hardcode
            // if(retailer == ''){
            if(json['query_data'].length == 0){
              Swal.fire({
                title: 'Cannot proceed, no result from HQ. Kindly re-start again the process', 
                text: '', 
                type: "error",
                allowOutsideClick: true,
                showConfirmButton: true,
              });
            }else{

              $.ajax({
                url : "<?php echo site_url('Consignment/run_process');?>",
                method: "POST",
                data:{date_start:date_start,date_end:date_end,retailer:retailer,selectedCheckboxes:selectedCheckboxValues},
                beforeSend : function() {
                    $('.btn').button('loading');
                    $('#loader_div').removeClass('hidden');
                },
                complete: function() {
                    $('.btn').button('reset');
                },
                success : function(data)
                {  
                  $(".se-pre-con").fadeOut("slow");
                  // json = JSON.parse(data);
                  console.log(data);
                  
                  if(json['status'] == 0){
                    Swal.fire({
                      title: json['message'], 
                      text: '', 
                      type: "error",
                      allowOutsideClick: true,
                      showConfirmButton: true,
                    });
                  }else{
                    get_layout_email_recipient(date_start,date_end,retailer);

                    if (!$("#hq_variance_collapse").parents('.box').hasClass('collapsed-box')) {
                      $("#hq_variance_collapse").click();
                    }

                    Swal.fire(
                      'Release!',
                      count_checkbox + ' numbers of invoices has been released.',
                      'success'
                    );
                  }
                  
                }
              });

            }
          }
        });

      } else if (result.dismiss === Swal.DismissReason.cancel) {
        // User clicked the "No, cancel" button or pressed Esc
        Swal.fire(
          'Cancelled',
          'You may submit again to proceed',
          'error'
        )

        return;
      }
    });
  });

  $(document).on('click','#search_data',function(){

    $('#release_message').addClass('hidden');
    $('#no_consign').addClass('hidden');
    $('#email-recipient-layout').addClass('hidden');

    if ($("#hq_variance_collapse").parents('.box').hasClass('collapsed-box')) {
      $("#hq_variance_collapse").click();
    }

    // if ($(this).parents('.box').hasClass('collapsed-box')) {
    //   console.log('Box has been collapsed');
    // } else {
    //   console.log('Box has not been collapsed');
    // }

    var date_start = $('#date_from').val();
    var date_end = $('#date_to').val();
    var retailer = $('#retailer').val();

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

    if(retailer == '' || retailer == null)
    {
      alert('Please Choose Retailer');
      return;
    }   

    datatable(date_start,date_end,retailer);

  });//close search button

  datatable = function(date_start,date_end,retailer)
  { 
    $.ajax({
      url : "<?php echo site_url('Consignment/check_hq_variance');?>",
      method: "POST",
      data:{date_start:date_start,date_end:date_end,retailer:retailer},
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

        if(json['query_data'] != '' && json['query_data'] != '[]'){
          $('#release_button').removeClass('hidden');
          $('#release_message').removeClass('hidden');
        }else{
          $('#no_consign').removeClass('hidden');
        }

        $('#table1').DataTable({
        "columnDefs": [
        { className: "aligncenter", targets: [] },
        { className: "alignright", targets: [0,1,2,6,7] },
        { className: "alignleft", targets: '_all' },
        ],
        'pageLength'  : 9999999999999999,
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [9999999999999999], ["ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        // 'order'       : [ [2 , 'desc'] ],
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true, 
        "bFilter": true, 
        "sScrollY": "60vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
        "aoColumnDefs": [
        {
          "bSortable": false,
          "aTargets": [0]
        }
        ],
          data: json['query_data'],
          columns: [
                    {"data": "" ,render:function( data, type, row ){
                      var element = '';
                      var period_code = row['periodcode'];
                      var code = row['CODE'];
                      var chkb_value = period_code + '|' + code;

                      if(row['trans_guid'] != '' && row['missing_b2b'] == ''){
                        element += '<input type="checkbox" class="data-check" id="consign_checkbox" name="consign_checkbox[]" value="' + chkb_value + '" checked="checked">';
                      }else{
                        element += '';
                      }

                      return element;

                      }
                    },
                    {"data" : "var_amount"},
                    {"data" : "cost_amt"},
                    {"data" : "inv_amt"},
                    {"data" : "periodcode"},
                    {"data" : "CODE"},
                    {"data" : "supplier"},
                    {"data" : "missing_b2b"},
                    {"data" : "total_row"},
                    {"data" : "total_posted"},
                    {"data" : "total_outlet"},
                    {"data" : "diff_outlet"},
                    {"data" : "trans_guid"},
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
            "zeroRecords": "<?php echo '<b>No Variance Found.</b>'; ?>",
          },
         "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).closest('tr').css({"cursor": "pointer"});

            if(aData['trans_guid'] == '' || aData['missing_b2b'] != ''){
              $(nRow).addClass('bg-danger');
            }
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

});
</script>

<script type="text/javascript">
  
  function expiry_clear()
  {
    $(function() {
        $(this).find('[name="date_from"]').val("");
        $(this).find('[name="date_to"]').val("");
    });
  }

  function get_layout_email_recipient(date_start,date_end,retailer){

    $.ajax({
      url : "<?php echo site_url('Consignment/consignment_email_statement');?>",
      dataType: 'html',
      method: "POST",
      data:{date_start:date_start,date_end:date_end,retailer:retailer},
      beforeSend : function() {
        $('.btn').button('loading');
      },
      complete: function() {
        $('.btn').button('reset');
      },
      success: function(html) {         
        $('#email-recipient-layout').html(html);
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });

    $('#email-recipient-layout').removeClass('hidden');
  }

</script>