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
        <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=">
          <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>

        <?php echo $hide_url ?>


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

              <div class="col-md-2"><b>PO Status</b></div>
              <div class="col-md-4">
<!--                 <select name="po_status" class="form-control">
                  <?php foreach($po_status->result() as $row){ ?>
                    <option value="<?php echo $row->code ?>" 
                      <?php if(strtolower($_REQUEST['status']) == strtolower($row->code))
                      {
                        echo 'selected';
                      } 
                      ?>
                    > 
                    <?php echo $row->reason; ?></option>
                 <?php } ?>
                </select> -->

                <input type="text" class="form-control" value="HSFP" readonly>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>PO Date Range<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-4">
                <input required id="daterange" name="daterange" type="datetime" class="form-control pull-right" id="reservationtime" readonly>
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger"  onclick="date_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Expired Date From<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="expiry_from" name="expiry_from" type="datetime" value="" readonly class="form-control pull-right">
              </div>
              <div class="col-md-2"><b>Expired Date To<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="expiry_to" name="expiry_to" type="datetime" class="form-control pull-right" readonly value="" onchange="CompareDate()">
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
              <div class="col-md-4">
                <select name="period_code" id="period_code" class="form-control">
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
              
              <div class="clearfix"></div><br>

              <div class="col-md-12">
                <input type="hidden" name="current_location" id="current_location" class="form-control pull-right" value="<?php echo $_REQUEST['loc']; ?>">
                <input type="hidden" name="frommodule" id="frommodule" class="form-control pull-right" value="<?php echo $_SESSION['frommodule'] ?>">
                
                <button type="button" id="search_hide_po" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                <a href="<?php echo site_url('PO_hide/view_status')?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=&first=1" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a>
                
              </div>

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
          <h3 class="box-title"><b>Purchase Order</b></h3> &nbsp;

          <span class="pill_button"><?php 

          echo ucfirst($status) ?></span>

          <span class="pill_button"><?php 

          if(in_array($check_loc, $hq_branch_code_array)) {
            echo 'All Outlet';
          } else {

            echo $location_description->row('BRANCH_CODE').' - '.$location_description->row('branch_desc');

          } ?>

          </span>



          <span class="pill_button" id="pill_button_po_date_range" style="display: none;"></span>



          <span class="pill_button" id="pill_button_expiry_date_range" style="display: none;"></span>



          <span class="pill_button" id="pill_button_period_code" style="display: none;"></span>

     

          <span class="pill_button" id="pill_button_refno" style="display: none;"></span>

 


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
                    <table id="hide_po_table" class="table table-bordered table-hover" >
                      <form id="formPO" method="post" action="<?php echo site_url('general/prints')?>">
                        <thead>
                            <tr>
                            <?php // var_dump($_SESSION); ?>
                                <!--Begin=Column Header-->
                                <th>PO Refno</th>
                                <th>GRN Refno</th>
                                <th>Outlet</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Po Date</th>
                                <th>Expiry Date</th>
                                <th>Amount</th>
                                <th>Tax</th>
                                <th>Total Incl Tax</th>
                                <th>Status</th>
                                <th>Hide Remark</th>
                                <th>Action</th>
                                <!--End=Column Header-->
                            </tr>
                        </thead>
                      </form>
            </table>
             
        </div>
    </div>

<!-- Modal -->
<div id="postatusmodal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center">Check PO Status</h4>
      </div>
      <div class="modal-body">
        <p><input type="text" id="po_refno"  name="po_refno" class="form-control">
        <center><span style="font-weight: bolder;" id="webindex_result"></span></center>  </p>
  <center><span style="font-weight: bolder;" id="po_check_grn_refno_result"></span></center>  </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload()">Close</button>
      </div>
    </div>

  </div>
</div>

<script>  
 $(document).ready(function(){  

  $(document).on('click', '#po_bulk_accept', function(e) {
      var list_id = [];
      $(".data-check:checked").each(function() {
            list_id.push(this.value);
      });

      // alert(list_id.length);
      

      $.ajax({  
       url:"<?php echo site_url('Panda_po_2/bulk_accept'); ?>",  
       method:"POST",  
       data:{list_id:list_id},  
       success:function(data)
       {                         
          if(data == 1)
          {
            alert('PO Accepted');
            location.reload();
          }
          else
          {
            alert('Error Occur');
            location.reload();

          }
                   
       }  
      });  
  });


  $(document).on('paste', '#po_refno', function(e) {
    e.preventDefault();
    var withoutSpaces = e.originalEvent.clipboardData.getData('Text');
    withoutSpaces = withoutSpaces.replace(/\s+/g, '');
    $(this).val(withoutSpaces);
  });

  $(document).on('keypress','#po_refno',function(e) {
      if(e.which == 32) {
        event.preventDefault();
        return false;
      }//close function for click space
  });//close keypress funhction

      $('#po_refno').keyup(function(){  
           var po_refno = $('#po_refno').val();  
           if(po_refno != '')  
           {  
              $.ajax({  
                     url:"<?php echo site_url('general/check_po_status'); ?>",  
                     method:"POST",  
                     data:{po_refno:po_refno},  
                     success:function(data)
                     {                         
                         if(data.substring(1,2)  == 1)
                          {
                            $('#po_refno').css('border', '2px green solid');
                            $('#webindex_result').html(data.substring(2));   
                          } 
                          else if(data == ' ')
                          {
                              $('#po_refno').css('border', '2px blue solid');
                              $('#webindex_result').html('Please Wait......');   
                          }                         
                          else
                      {
                              $('#po_refno').css('border', '2px red solid');
                              $('#webindex_result').html(data.substring(2));                             
                          }         
                             
                     }  
                });  

                           $.ajax({  

                           url:"<?php echo site_url('general/check_grn_no'); ?>",  

                           method:"POST",  

                           dataType:"json",

                           data:{po_check_grn_refno:po_refno},  

                           success:function(data)

                           {                  

                               if(data.count == 0)

                                {

                                  $('#po_check_grn_refno').css('border', '2px green solid');

                                  $('#po_check_grn_refno_result').html(data.xmessage);   

                                } 

                                else if(data.count == 1)

                                {

                                  $('#po_check_grn_refno').css('border', '2px green solid');

                                  $('#po_check_grn_refno_result').html(data.xmessage);    

                                }                         

                                else

                                {

                                  $('#po_check_grn_refno').css('border', '2px red solid');

                                  $('#po_check_grn_refno_result').html(data.xmessage);                              

                                }                                 

                           }  

                          });  

           }  
      });


      status_request = "<?= isset($_REQUEST['status']) ? $_REQUEST['status'] : '' ;?>";

      loc_request = "<?= isset($_REQUEST['loc']) ? $_REQUEST['loc'] : '' ;?>";

      $('#hide_po_table').DataTable({
          // "columnDefs": [ {"targets": 2 ,"visible": false}],
          "columnDefs": [{ "orderable": false, "targets": 12 }],
          "serverSide": true, 
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [0 , 'asc'] ],
          'info'        : true,
          'autoWidth'   : false,
          "bPaginate": true, 
          "bFilter": true, 
          // "sScrollY": "30vh", 
          // "sScrollX": "100%", 
          // "sScrollXInner": "100%", 
          "bScrollCollapse": true,
          "ajax": {
              "url": "<?php echo $datatable_url;?>",
              "type": "POST",
              "data" : {status:status_request,loc:loc_request},
          },
          columns: [
                    { "data": "refno" },
                    { "data": "gr_refno" },
                    { "data": "location" },
                    { "data": "code" },
                    { "data": "name" },
                    { "data": "podate" },
                    { "data": "expiry_date" },
                    { "data": "total" },
                    { "data": "gst_tax_sum" },
                    { "data": "total_include_tax" },
                    { "data": "status" },
                    { "data": "hide_reason" },
                    { "data": "button" },
                   ],
          dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
          // "pagingType": "simple",
          "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('RefNo', aData['refno']);
            // $(nRow).attr('status', aData['status']);
          },
          "initComplete": function( settings, json ) {
            interval();
          }
        });//close datatable

        $('div.dataTables_filter input').off('keyup.DT input.DT');

        var searchDelay = null;
           
        $(document).off('keyup','div.dataTables_filter input').on('keyup','div.dataTables_filter input', function(e) {
            var search = $(this).val();
            if (e.keyCode == 13) {
              var id = $(this).attr('aria-controls');
              $('#'+id).DataTable().search(search).draw();
            }//close keycode
        });//close keyup function



  $(document).on('click','#search_hide_po',function(){

    var frommodule = $('#frommodule').val();
    var current_location = $('#current_location').val();
    var po_num = $('#po_num').val();
    var daterange = $('#daterange').val();
    var expiry_from = $('#expiry_from').val();
    var expiry_to = $('#expiry_to').val();
    var period_code = $('#period_code').val();


    if(daterange != '')
    {
      var [start_date_x,end_date_x] = daterange.split(' - ');

      $('#pill_button_po_date_range').html('PO Date Range : '+start_date_x+'<i class="fa fa-arrow-right"></i>'+end_date_x).show();
    }
    else
    {
      $('#pill_button_po_date_range').hide();
    }



    if(expiry_from != '' && expiry_to != null)
    {
      $('#pill_button_expiry_date_range').html('Expired Date Range : '+expiry_from+' <i class="fa fa-arrow-right"></i> '+expiry_to).show();
    }
    else
    {
      $('#pill_button_expiry_date_range').hide();
    }



    if(po_num != '')
    {
      $('#pill_button_refno').html(po_num).show();
    }
    else
    {
      $('#pill_button_refno').hide();
    }



    if(period_code != '')
    {
      $('#pill_button_period_code').html(period_code).show();
    }
    else
    {
      $('#pill_button_period_code').hide();
    }



    if( $.fn.DataTable.isDataTable('#hide_po_table') ) {

      $('#hide_po_table').DataTable().destroy();

    }//close else datatable

    status_request = "<?= isset($_REQUEST['status']) ? $_REQUEST['status'] : '' ;?>";

    loc_request = "<?= isset($_REQUEST['loc']) ? $_REQUEST['loc'] : '' ;?>";

    $('#hide_po_table').DataTable({
        // "columnDefs": [ {"targets": 2 ,"visible": false}],
        "columnDefs": [{ "orderable": false, "targets": 11 }],
        "serverSide": true, 
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [ [0 , 'asc'] ],
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true, 
        "bFilter": true, 
        // "sScrollY": "30vh", 
        // "sScrollX": "100%", 
        // "sScrollXInner": "100%", 
        "bScrollCollapse": true,
        "ajax": {
            "url": "<?php echo site_url('PO_hide/view_table_filter');?>",
            "type": "POST",
            "data" : {status:status_request,loc:loc_request,frommodule:frommodule,current_location:current_location,po_num:po_num,daterange:daterange,expiry_from:expiry_from,expiry_to:expiry_to,period_code:period_code},
        },
        columns: [
                  { "data": "refno" },
                  { "data": "gr_refno" },
                  { "data": "location" },
                  { "data": "code" },
                  { "data": "name" },
                  { "data": "podate" },
                  { "data": "expiry_date" },
                  { "data": "total" },
                  { "data": "gst_tax_sum" },
                  { "data": "total_include_tax" },
                  { "data": "status" },
                  { "data": "button" },
                 ],
        dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
        // "pagingType": "simple",
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
          $(nRow).attr('RefNo', aData['refno']);
          // $(nRow).attr('status', aData['status']);
        },
        "initComplete": function( settings, json ) {
          interval();
        }
      });//close datatable

      $('div.dataTables_filter input').off('keyup.DT input.DT');

      var searchDelay = null;
         
      $(document).off('keyup','div.dataTables_filter input').on('keyup','div.dataTables_filter input', function(e) {
          var search = $(this).val();
          if (e.keyCode == 13) {
            var id = $(this).attr('aria-controls');
            $('#'+id).DataTable().search(search).draw();
          }//close keycode
      });//close keyup function


  });//close search filter button



  $(document).on('click','.hide_po_modal',function(){

    var modal = $('#otherstatus').modal();

    var refno = $(this).attr('refno');
     var loc_group = $(this).attr('loc_group');

    modal.find('form').attr('action','<?php echo site_url("general/po_unhide");?>');
    modal.find('form').find('input[name="refno"]').val(refno);
    modal.find('form').find('input[name="loc"]').val(loc_group);
    modal.find('.modal_detail').html('Confirm Unhide '+ refno + '?');
    
    $('button[data-dismiss="modal"]').prop("onclick", null).off("click");
  });



  $(document).on('mouseover','#hide_po_table tbody tr',function(){

    
    var RefNo = $(this).attr('RefNo');

    var _this = $(this);

    if($(this).attr('hover') === undefined)
    {

      $.ajax({  
         url:"<?php echo site_url('PO_hide/check_supplier'); ?>",  
         method:"POST",  
         data:{RefNo:RefNo},  
         success:function(data)
         {

            json = JSON.parse(data);

            if((json['created_at'] == null) || (json['created_at'] == '') || (json['created_by'] == null) || (json['created_by'] == ''))
            {

            }
            else
            {
              _this.closest('tr').find('td').attr({'data-toggle':'tooltip','data-html':'true',  'data-original-title':'<p style="text-align:left;"><b>Hide By : '+json['created_by']+'</b><br><b>Hide at : '+json['created_at']+'</b></p>'});
            }

            

         }
       });

    }

    $(this).attr('hover','true');

    // $(this).find('td').attr({'data-toggle':'tooltip','data-html':'true','title':'<em>Tooltip</em> <u>with</u> <b>HTML</b>'});

    // data-toggle="tooltip" data-html="true" title="<em>Tooltip</em> <u>with</u> <b>HTML</b>

  });//close table hover


});  
 </script>  
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
            alert("Expiry To : "+dateTwo+" Cannot Be a date before "+dateOne+".");
            $('#search').attr('disabled','disabled');
        }
        else 
        {
           $('#search').removeAttr('disabled');
        }

    }
</script>

<script type="text/javascript">
  function hide_modal()
  {
    $('#otherstatus').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal_detail').text('Confirm Hide ' + button.data('refno') + '?')
      modal.find('[name="refno"]').val(button.data('refno'))
      modal.find('[name="loc"]').val(button.data('loc'))
    });
  }
  
</script>
</div>
        </div>
        
        </div>
    </div>
</div>
</div>
</div>
</div>
