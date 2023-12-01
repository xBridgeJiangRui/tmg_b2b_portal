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
         <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?> ">
          <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>
        <a class="btn btn-app " style="color:#008D4C" href="<?php echo $confirmed ?>">
            <i class="fa fa-check-square"></i> View Confirmed GR
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
              <form role="form" method="POST" id="myForm" action="<?php echo site_url('general/po_filter');?>">
              <div class="col-md-2"><b>GR Ref No</b></div>
              <div class="col-md-4">
                 <input id="po_num" name="po_num" type="text" autocomplete="off" class="form-control pull-right">
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>GR Status</b></div>
              <div class="col-md-4">
                <select name="po_status" class="form-control">
                  <?php foreach($po_status->result() as $row){ ?>
                    <option value="<?php echo $row->code ?>" 
                      <?php if($_REQUEST['status'] == $row->code)
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

              <div class="col-md-2"><b>GR Date Range<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-4">
                <input required id="daterange" name="daterange" type="datetime" class="form-control pull-right" id="reservationtime" readonly>
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger"  onclick="date_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Doc Date From<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="expiry_from" name="expiry_from" type="datetime" value="" readonly class="form-control pull-right">
              </div>
              <div class="col-md-2"><b>Doc Date To<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="expiry_to" name="expiry_to" type="datetime" class="form-control pull-right" readonly value="" onchange="CompareDate()">
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
              <div class="col-md-4">
                <select name="period_code" class="form-control">
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

              <div class="col-md-12">
                <input type="hidden" name="current_location" class="form-control pull-right" value="<?php echo $_REQUEST['loc']; ?>">
                <input type="hidden" name="frommodule" class="form-control pull-right" value="<?php echo $_SESSION['frommodule'] ?>">
                
                <button type="submit" id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                <a href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a>

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
          <h3 class="box-title"><b>Goods Received Download</b></h3> &nbsp;

          <span class="pill_button"><?php 

          if ($_REQUEST['status'] == '') {
            $status = 'new';
          } else {
            $status = $_REQUEST['status'];
          }


          echo ucfirst($status) ?></span>

          <span class="pill_button"><?php 

          if(in_array($check_loc, $hq_branch_code_array)) {
            echo 'All Outlet';
          } else {

            echo $_REQUEST['loc'];

          } ?>

          </span>

          <?php if ($_REQUEST['p_f'] != '' || $_REQUEST['p_t'] != '' ) { ?>

          <span class="pill_button"><?php 


          echo 'GR Date Range : '. $_REQUEST['p_f'].' <i class="fa fa-arrow-right" aria-hidden="true"></i> '.$_REQUEST['p_t'];  ?>
            

          </span>

          <?php } ?>

          <?php if ($_REQUEST['e_f'] != '' || $_REQUEST['e_t'] != '' ) { ?>

          <span class="pill_button"><?php 


          echo 'Expired Date Range : '.$_REQUEST['e_f'].' <i class="fa fa-arrow-right" aria-hidden="true"></i> '.$_REQUEST['e_t'];  ?>
            

          </span>

          <?php } ?>

          <?php 

          if(isset($_SESSION['filter_period_code']))
            {

          if ($_SESSION['filter_period_code'] != ''  ) { ?>

          <span class="pill_button"><?php 


          echo $_SESSION['filter_period_code'];  ?>
            

          </span>

          <?php } } ?>

          <?php if ($_REQUEST['r_n'] != '') { ?>

          <span class="pill_button"><?php 


          echo $_REQUEST['r_n'];  ?>
            

          </span>

          <?php } ?>

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
                    <table id="paloi" class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                            <?php // var_dump($_SESSION); ?>
                                <th>GR Refno</th>
                                <th>GRDA Status</th>
                                <th>Outlet</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>GR Date</th>
                                <th>Doc Date</th>
                                <th>Inv No</th>
                                <th>E-Inv No</th>
                                <th>Doc No</th>
                                <th>Cross Ref</th>
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
 
<?php //  echo var_dump($_SESSION); ?>
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
  function date_clear()
  {
    $(function() {
        $(this).find('[name="daterange"]').val("");
    });
  }

  function expiry_clear()
  {
    $(function() {
        $(this).find('[name="expiry_from"]').val("");
        $(this).find('[name="expiry_to"]').val("");
    });
  }
</script>

<script type="text/javascript">
   function CompareDate() {
       var dateOne = $('input[name="expiry_from"]').val(); //Year, Month, Date
       var dateTwo = $('input[name="expiry_to"]').val(); //Year, Month, Date
        if (dateOne > dateTwo) {
            alert("Date To : "+dateTwo+" is Larger than Date From : "+dateOne+".");
            $('#search').attr('disabled','disabled');
        }
        else 
        {
           $('#search').removeAttr('disabled');
        }

    }
</script>
<script type="text/javascript">
  function bulk_print()
  {
    var list_id = [];
    $(".data-check:checked").each(function() {
            list_id.push(this.value);
    });
      if(list_id.length > 1)
    {
      // alert('use merge');
            $.ajax({
            type: "POST",
            data: {id:list_id},
            url: "<?php echo site_url('general/merge_pdf?po_type=GRN&loc='.$_REQUEST['loc'])?>",
            dataType: "JSON",
            success: function(data)
            { 
                // alert(data.link_url);
                if(data.link_url)
                {
                   
                   var newwin = window.open(data.link_url); 
                    newwin.onload = function() {

                      setTimeout(function(){

                        var url_link = data.pdf_file;

                        $.ajax({
                                type: "POST",
                                data: {url_link:url_link},
                                url: "<?php echo site_url('general/unlink_file')?>",
                                dataType: "JSON",
                                success: function(data)
                                { 
                                  alert('delete success'+data);
                                }//close success
                              });//close ajax

                      },1000);
                    
                    };//close onload
                }
                else
                {
                    alert('Failed.');
                }
                
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error Opening data');
            }
        });
    }
    else if(list_id.length > 0)
    {
        if(confirm('Are you sure open this '+list_id.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {id:list_id},
                url: "<?php echo site_url('general/ajax_bulk_print?loc='.$_REQUEST['loc'])?>",
                dataType: "JSON",
                success: function(data)
                { 
                    //alert(data.link_url);
                    if(data.link_url)
                    {
                      data.link_url.forEach(function(element){
                        window.open(element); 
                      });
                       
                    }
                    else
                    {
                        alert('Failed.');
                    }
                    
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error Opening data');
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
<script type="text/javascript">
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
$(document).ready(function(){ 
  $(document).on('click','#view_po_data',function(){
      var gr_refno = $(this).attr('gr_refno');

      $.ajax({
        url:"<?php echo site_url('Panda_gr/view_po_grn') ?>",
        method:"POST",
        data:{gr_refno:gr_refno},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          var modal = $("#medium-modal").modal();

          modal.find('.modal-title').html('Purchase Order Details');

          methodd = '';

          methodd += '<table class="table table-bordered table-striped" id="view_po_tb" width="100%"><thead><th>PO Refno</th></thead></table>';

          methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

          modal.find('.modal-footer').html(methodd_footer);
          modal.find('.modal-body').html(methodd);
        
          setTimeout(function(){
            if ($.fn.DataTable.isDataTable('#view_po_tb')) {
              $('#view_po_tb').DataTable().destroy();
            }

            $('#view_po_tb').DataTable({
            "columnDefs": [

            ],
            'processing'  : true,
            'paging'      : false,
            'lengthChange': true,
            'lengthMenu'  : [ [10, 25, 50, 999999], [10, 25, 50, "ALL"] ],
            'searching'   : false,
            'ordering'    : false,
            'order'       : [ [0 , 'asc'] ],
            'info'      : true,
            'autoWidth'   : false,
            "bPaginate": false, 
            "bFilter": true, 
            "sScrollY": "60vh", 
            "sScrollX": "100%", 
            "sScrollXInner": "100%", 
            "bScrollCollapse": true,
              data: json['content'],
              columns: [
              
              { "data": "porefno"},
                    
              ],
              dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
              "language": {
                            "lengthMenu": "Show _MENU_",
                            "infoEmpty": "No records available",
                            "infoFiltered": "(filtered from _MAX_ total records)",
                            "zeroRecords": "<?php echo '<b>No Record Found. Please Contact Support.</b>'; ?>",
                    }, 
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              // $(nRow).closest('tr').css({"cursor": "pointer"});
              // $(nRow).attr('poex_guid', aData['poex_guid']);
              // $(nRow).attr('status', aData['status']);
            },
            "initComplete": function( settings, json ) {
              interval();
            },
            });//close datatable

          },300);
          $('.btn').button('reset');
        }//close success
      });//close ajax 
  });//close modal view po
});//close ready
</script>