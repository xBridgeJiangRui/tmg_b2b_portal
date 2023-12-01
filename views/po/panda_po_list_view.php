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
          <a class="btn btn-app " style="color:#008D4C" href="<?php echo $accepted ?>">
            <i class="fa fa-check-square"></i> View Accepted
          </a>
          <a class="btn btn-app" style="color:#D73925" href="<?php echo $rejected ?>">
            <i class="fa fa-window-close"></i> View Rejected
          </a>

          <?php if($_SESSION['customer_guid'] != '8D5B38E931FA11E79E7E33210BD612D3'){echo $hide_url;} ?>

          <!-- <a class="btn btn-app" style="color:#367FA9" href="<?php echo $other ?>">
            <i class="fa fa-external-link-square"></i> View Others
          </a> -->
          <a class="btn btn-app pull-right"  style="color:#000000"  onclick="bulk_print()" >
            <i class="fa fa-print"></i> Print
          </a>
          <?php 
          if(in_array('BAPO',$_SESSION['module_code']))
          {
            if($_REQUEST['status'] == '' || $_REQUEST['status'] == null || $_REQUEST['status'] == 'viewed')
            {
            ?>
              <a class="btn btn-app pull-right" id="po_bulk_accept"  style="color:#000000">
              <i class="fa fa-check-circle"></i> Bulk Accept
              </a>
            <?php
            }
          }
          ?>
          <?php if(in_array('CPOS',$_SESSION['module_code'])) { ?>
            <button class="btn btn-app" data-toggle="modal" data-target="#postatusmodal"><i class="fa fa-file-text-o"></i>Check PO Status</button>   
        <?php } ?>  
          
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
              <div class="col-md-2"><b>PO Ref No</b></div>
              <div class="col-md-4">
                 <input id="po_num" name="po_num" type="text" autocomplete="off" class="form-control pull-right">
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>PO Status</b></div>
              <div class="col-md-4">
                <select name="po_status" class="form-control">
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
                </select>
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
              
              <div class="clearfix"></div><br>

              <div class="col-md-12">
                <input type="hidden" name="current_location" class="form-control pull-right" value="<?php echo $_REQUEST['loc']; ?>">
                <input type="hidden" name="frommodule" class="form-control pull-right" value="<?php echo $_SESSION['frommodule'] ?>">
                
                <button type="submit" id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                <a href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=&first=1" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a>
                
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
          <h3 class="box-title"><b>Purchase Order</b></h3> &nbsp;

          <span class="pill_button"><?php 

          if ($_REQUEST['status'] == '') {
            $status = 'new';
          } else if ($_REQUEST['status'] == 'gr_completed') {

            echo 'GRN Completed';

          }else {
            $status = $_REQUEST['status'];
          }


          echo ucfirst($status) ?></span>

          <span class="pill_button"><?php 

          if(in_array($check_loc, $hq_branch_code_array)) {
            echo 'All Outlet';
          } else {

            echo $location_description->row('BRANCH_CODE').' - '.$location_description->row('branch_desc');

          } ?>

          </span>

          <?php if ($_REQUEST['p_f'] != '' || $_REQUEST['p_t'] != '' ) { ?>

          <span class="pill_button"><?php 


          echo 'PO Date Range : '. $_REQUEST['p_f'].' <i class="fa fa-arrow-right" aria-hidden="true"></i> '.$_REQUEST['p_t'];  ?>
            

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
          </div>
        </div>
      <div class="box-body">
      <div class="col-md-12">
        <br>
        <div>
            <div class="row">
                <div class="col-md-12"  style="overflow-x:auto"> 
                    <table id="paloi" class="table table-bordered table-hover" >
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
                                <th>Delivery Date</th>
                                <th>Expiry Date</th>
                                <th>Amount</th>
                                <th>Tax</th>
                                <th>Total Incl Tax</th>
                                <th>Status</th>
                                <th>Reject Remark</th>
                                <th>Action</th>
                                <th><input type="checkbox" id="check-all"></th>
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

<script type="text/javascript">
  function bulk_print()
  {
    var list_id = [];
    $(".data-check:checked").each(function() {
            list_id.push(this.value);
    });
     if(list_id.length > 0)
    {
      // alert('use merge');
            $.ajax({
            type: "POST",
            data: {id:list_id},
            url: "<?php echo site_url('general/merge_pdf?loc='.$_REQUEST['loc'].'&po_type=PO')?>",
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

    else
    {
        alert('no data selected');
    }
  }

</script>

</script>
<script type="text/javascript">
  function viewothers()
  {
    $('#viewothers').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal_detail').text('Confirm Hide ' + button.data('name') + '?')
      modal.find('[name="refno"]').val(button.data('refno'))
      modal.find('[name="customer_guid"]').val(button.data('customer_guid'))
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="col_guid"]').val(button.data('col_guid'))
      modal.find('[name="loc"]').val(button.data('loc'))
      modal.find('[name="name"]').val(button.data('name'))
  
    });
  }
  
</script>
<script>
    function ahsheng() 
    {
      location.href = '<?php echo site_url('general/view_status') ?>?status='+$('#reason').val()+'&loc=HQ';
    }
</script>
<script>
$(document).ready(function() {
    $(document).on('click', '#preview_po_item_line', function(e) {
          var refno = $(this).attr('refno');

          var modal = $("#medium-modal").modal();

          modal.find('.modal-title').html('PO Preview Item Line');

          methodd = '';

          methodd +='<table class="table table-bordered table-striped" id="preview_po_item_line_table" width="100%"><thead><th>Line</th><th>Itemcode</th><th>Qty</th><th>Price</th><th>Description</th></thead></table>';

          methodd +='</div>';


          methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

          modal.find('.modal-footer').html(methodd_footer);
          modal.find('.modal-body').html(methodd);

          $('#preview_po_item_line_table').DataTable({
            'processing'  : true,
          });

          $('#preview_po_item_line_table_processing').css({'z-index':'1040'}).show();


          setTimeout(function(){

           $.ajax({
                url:"<?php echo site_url('Panda_po_2/preview_po_item_line'); ?>",
                method:"POST",
                data: {refno:refno},
                success:function(data)
                { 
                  json = JSON.parse(data);
                  // alert(json);return;
                  if ( $.fn.DataTable.isDataTable('#preview_po_item_line_table') ) {
                    $('#preview_po_item_line_table').DataTable().destroy();
                  }

                  $('#preview_po_item_line_table').DataTable({
                    // "columnDefs": [ {"targets": 1 ,"visible": false}],
                    'processing'  : true,
                    "sScrollY": "40vh", 
                    "sScrollX": "100%", 
                    "sScrollXInner": "100%", 
                    'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
                    "bScrollCollapse": true,
                    // "pagingType": "simple",
                    'order'       : [ [0 , 'asc'] ],
                    data: json['po_item_line'],
                    columns: [  
                              {data: "Line"},
                              {data: "Itemcode"},
                              {data: "Qty", render:function( data, type, row ){
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
                              {data: "TotalPrice", render:function( data, type, row ){
                                var element = ''
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
                                  element += parseFloat(data).toFixed(2);
                                  <?php
                                }
                                ?>
                                //element = parseFloat(data).toFixed(2);
                                return element;
                              }},                              
                              {data: "Description"}
                             ],   
                    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
                    "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                      // $(nRow).attr('id', aData['RefNo']);
                    },
                    "initComplete": function( settings, json ) {
                      setTimeout(function(){
                        interval();
                      },300);
                    }
                  });//close datatatable

                }//close succcess
          });//close ajax
        },300);          

    });
});
</script>