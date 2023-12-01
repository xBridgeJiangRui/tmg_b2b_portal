<style>
.content-wrapper{
  min-height: 850px !important; 
}

.alignright {
  text-align: right;
}

.alignleft
{
  text-align: left;
}
</style>
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
          <!-- <?php if($_SESSION['customer_guid'] != '8D5B38E931FA11E79E7E33210BD612D3'){echo $hide_url;} ?> -->
          <!-- <a class="btn btn-app" style="color:#367FA9" href="<?php echo $other ?>">
            <i class="fa fa-external-link-square"></i> View Others
          </a> -->
<?php if(in_array('UEXD',$_SESSION['module_code']))
{
;?>
          <a class="btn btn-app pull-right"  href="<?php echo site_url('External_doc/upload_doc?parameter='.$charge_type);?>">
            <i class="fa fa-print"></i> Upload Doc
          </a>
<?php
}
;?> 
          
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
              <form role="form" method="POST" id="myForm" action="<?php echo site_url('general/po_filter');?>">
              <div class="col-md-2"><b>Ref No</b></div>
              <div class="col-md-4">
                 <input id="refno" name="refno" type="text" autocomplete="off" class="form-control pull-right">
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Status</b></div>
              <div class="col-md-4">
                <select class="form-control" id='status' name='status'>
                  <?php echo $status_string;?>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Date From<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="date_from" name="date_from" type="datetime" value="" readonly class="form-control pull-right">
              </div>
              <div class="col-md-2"><b>Date To<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="date_to" name="date_to" type="datetime" class="form-control pull-right" readonly value="">
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>
              
              <div class="col-md-12">
                
                <button type="button" id="search" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                <button type="button" id="reset" class="btn btn-default"><i class="fa fa-repeat"></i> Reset</a></button>

                <!-- <button type="submit" id="submit_form" class="btn btn-primary"><i class="fa fa-search"></i> Searchs</button> -->
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                <!-- <a href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=&first=1" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a> -->
                
              </div>
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
          <h3 class="box-title">
            <b><?php echo $table_name; ?></b>
          </h3> &nbsp;

<!--           <span class="pill_button"></span>

          <span class="pill_button"></span> -->

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
                <div class="col-md-12"  style="overflow-x:auto"> 
                    <table id="list_view_table" class="table table-bordered table-hover" >
                      <form id="list_view_form" method="post" action="<?php echo site_url('general/prints')?>">
                        <thead>
                            <tr>
                              <?php 
                              foreach($header_data as $row)
                              {
                              ?>
                                <th><?php echo $row;?></th>
                              <?php
                              }
                              ?>
                            </tr>
                        </thead>
                      </form>
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
<script src="<?php echo base_url('asset/plugins/jQuery/jquery-2.2.3.min.js')?>"></script>
<script>
$(document).ready(function() {

  status = $('#status').val();
  refno = $('#refno').val();
  date_from = $('#date_from').val();
  date_to = $('#date_to').val();

  list_view_table = function()
  {
    // alert();
    var charge_type = "<?php echo $charge_type;?>";
    if ($.fn.DataTable.isDataTable( '#list_view_table' ) ) {
         $('#list_view_table').DataTable().clear().destroy()
    }
    // alert();
    $('#list_view_table').DataTable({
      // "columnDefs": [ ],
      "serverSide": true, 
      'processing'  : true,
      'paging'      : true,
      'lengthChange': true,
      'lengthMenu'  : [ [10,25,50,100,9999999999], ['10','25','50','100','ALL'] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       :  <?php echo $sorting_data;?>,
      'info'        : true,
      'autoWidth'   : false,
      "bPaginate": true, 
      "bFilter": true, 
      // "sScrollY": "35vh", 
      "sScrollX": "100%", 
      "sScrollXInner": "100%", 
      "bScrollCollapse": true,
      "columnDefs": [ {targets: 8 ,orderable: false},
      { className: "alignright", targets: [4] },
      { className: "alignleft", targets: '_all' },
      <?php if(in_array('IAVA',$this->session->userdata('module_code')))
      {
        ?>
        { visible: true, targets: '_all'}
        <?php
      }
      else
      {
        ?>
        { visible: false, targets: [5,6,7]}
        <?php
      }
      ?>],
      "ajax": {
          "url": "<?php echo site_url('External_doc/list_view_table');?>",
          "type": "POST",
          "data" : {charge_type:charge_type,status:status,refno:refno,date_from:date_from,date_to:date_to},
      },
      columns: [
                <?php echo $footer_data;?>
               ],
      <?php if(in_array('IAVA',$this->session->userdata('module_code')))
      {
        ?>
        dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>Brtip", 
        <?php
      }
      else
      {
        ?>
        dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip", 
        <?php
      }
      ?>
      // "pagingType": "simple",
      "fnCreatedRow": function( nRow, aData, iDataIndex ) {
        //$(nRow).attr('AutoKey', aData['AutoKey']);
        
      },
      "initComplete": function( settings, json ) {
        interval();
      }
    });//close datatable
    $("#ttable_wrapper .row .col-sm-12").css("overflow" , "auto");
    $("#ttable_wrapper .row .col-sm-12").css("max-height" , "310px");
  }
  
  list_view_table();

  $(document).on('click','#remove_data',function(event){
    var doc_type = $(this).attr('doc_type');
    var charge_type = $(this).attr('charge_type');
    var refno = $(this).attr('refno');
    var supcode = $(this).attr('supcode');
    var uploaded_at = $(this).attr('uploaded_at');

    if((doc_type == '') || (doc_type == 'null') || (doc_type == null))
    {
      alert('Invalid Doc Type.');
      return
    }

    if((charge_type == '') || (charge_type == 'null') || (charge_type == null))
    {
      alert('Invalid Document Type.');
      return
    }

    if((refno == '') || (refno == 'null') || (refno == null))
    {
      alert('Invalid RefNo.');
      return
    }

    if((supcode == '') || (supcode == 'null') || (supcode == null))
    {
      alert('Invalid Sup Code.');
      return
    }

    if((uploaded_at == '') || (uploaded_at == 'null') || (uploaded_at == null))
    {
      alert('Invalid Uploaded Time.');
      return
    }

    confirmation_modal('Are you sure want to Remove?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('External_doc/remove_doc');?>",
        method:"POST",
        data:{doc_type:doc_type,charge_type:charge_type,refno:refno,supcode:supcode,uploaded_at:uploaded_at},
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
            $('#alertmodal').modal('hide');
            alert(json.msg);
            setTimeout(function() {
              $('.btn').button('reset');
              list_view_table();
            }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });

});

$(document).on('click','#search',function(event){
  var dateOne = $('input[name="date_from"]').val(); //Year, Month, Date
  var dateTwo = $('input[name="date_to"]').val(); //Year, Month, Date
  if(dateOne == '' && dateTwo != '')
  {
       alert("Date From cannot be empty");
       return;
       // $('#search').attr('disabled','disabled');
  }

  if(dateOne != '' && dateTwo == '')
  {
       alert("Date To cannot be empty");
       return;
       // $('#search').attr('disabled','disabled');
  }
    
  if (dateOne > dateTwo)
  {
      alert("Date To : "+dateTwo+" Cannot Be a date before "+dateOne+".");

      // $('#search').attr('disabled','disabled');
  }
  status = $('#status').val();
  refno = $('#refno').val();
  date_from = $('#date_from').val();
  date_to = $('#date_to').val();
  // alert(status);
  list_view_table();
});

$(document).on('click','#reset',function(event){
  // $('#status').val('');
  $("#status").val($("#status option:first").val());
  $('#refno').val('');
  $('#date_from').val('');
  $('#date_to').val('');

  status = $('#status').val();
  refno = $('#refno').val();
  date_from = $('#date_from').val();
  date_to = $('#date_to').val();
  // alert(status);
  list_view_table();
});

$(function() {
  $('input[name="date_from"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
  $(this).find('[name="date_from"]').val("");
});

$(function() {
  $('input[name="date_to"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
  $(this).find('[name="date_to"]').val("");
});

function expiry_clear()
{
  $(function() {
      $(this).find('[name="date_from"]').val("");
      $(this).find('[name="date_to"]').val("");
  });
}

</script>