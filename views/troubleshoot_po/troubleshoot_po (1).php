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

              <div class="col-md-2"><b>Vendor Code</b></div>
              <div class="col-md-4">
                 <select name="vendor_code" id="vendor_code" class="form-control">
                  <option value="">None</option>
                  <?php
                  foreach($supcus->result() as $row)
                  {
                  ?>

                    <option value="<?=$row->Code;?>"><?=$row->Code;?> - <?=$row->Name;?></option>

                  <?php
                  }
                  ?>
                </select>
              </div>

              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>PO Status</b></div>
              <div class="col-md-4">
                <select name="po_status" id="po_status" class="form-control">
                  <?php
                  foreach($po_status->result() as $row)
                  {
                  ?>

                    <option value="<?=$row->code;?>"><?=$row->reason;?></option>

                  <?php
                  }
                  ?>
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
                <select name="period_code" id="period_code" class="form-control">
                  <option value="">None</option>

                  <?php
                  foreach($period_code->result() as $row)
                  {
                  ?>

                    <option value="<?= $row->period_code;?>"><?= $row->period_code;?></option>
                  <?php
                  }
                  ?>


                </select> 
              </div>
              
              <div class="clearfix"></div><br>

              <div class="col-md-12">

                
                <button type="button" id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                <a href="<?php echo site_url('po_new');?>" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a>
                
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

<!-- 
          <span class="pill_button">
            test
</span>
 -->

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
                    <table id="po_new_table" class="table table-bordered table-hover" >
                      <!-- <form id="formPO" method="post" action="<?php echo site_url('general/prints')?>"> -->
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
                      <!-- </form> -->
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


po_new_table = function()
{
  if ( $.fn.DataTable.isDataTable('#po_new_table') ) {
    $('#po_new_table').DataTable().destroy();
  }
  

  var po_num = $('#po_num').val();
  var po_status = $('#po_status').val();
  var daterange = $('#daterange').val();
  var expiry_from = $('#expiry_from').val();
  var expiry_to = $('#expiry_to').val();
  var period_code = $('#period_code').val();
  var vendor_code = $('#vendor_code').val();

  var table;

  table = $('#po_new_table').DataTable({
    "columnDefs": [ {"targets": 3 ,"visible": false},{"targets": 0 ,"orderable": false}],
    "serverSide": true, 
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
    // "sScrollY": "30vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    // "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Troubleshoot_po/po_new_table');?>",
        "type": "POST",
        data : {po_num:po_num,po_status:po_status,daterange:daterange,expiry_from:expiry_from,expiry_to:expiry_to,period_code:period_code,vendor_code:vendor_code},
        complete:function()
        {
        },
    },
    //'fixedHeader' : false,
    columns: [
              // {"data" : "is_estore",render: function ( data, type, row ) {
              //   if (data == 1) { ischecked = 'checked' } else { ischecked = '' }
              //   return '<input '+ischecked+' type="checkbox" class="form-checkbox" disabled>';
              // }},
              // {"data" : "BRANCH_CODE"},
                {"data":"RefNo"},
                {"data":"gr_refno"},
                {"data":"loc_group"},
                {"data":"SCode"},
                {"data":"SName"},
                {"data":"PODate"},
                {"data":"DeliverDate"},
                {"data":"DueDate"},
                {"data":"Total"},
                {"data":"gst_tax_sum"},
                {"data":"total_include_tax"},
                {"data":"status"},
                {"data":"portal_description"},
                {"data":"RefNo" ,render: function ( data, type, row ) {
                if (data == 1) { ischecked = 'checked' } else { ischecked = '' }
                return '<button style="float:left" class="btn btn-sm btn-info" role="button"><i class="glyphicon glyphicon-eye-open"></i></button>';
                }},
                {"data":"RefNo" ,render: function ( data, type, row ) {
                if (data == 1) { ischecked = 'checked' } else { ischecked = '' }
                return '<input type="checkbox" class="form-checkbox" '+ischecked+' />';
                }},
             ],
    // dom: "<'row'<'col-sm-2 'l > <'col-sm-4' > <'col-sm-6' f>  " + "<'col-sm-1' <'toolbar_list'>>>" +'rt<"row" <".col-md-4 remove_padding" i>  <".col-md-8" p> >',

    "buttons": [
    {
        extend: 'excelHtml5',
        exportOptions: { orthogonal: 'export' }
    },

    ],

    dom:'<"row"<"col-sm-2" l><"col-sm-4" B><"col-sm-6" f>>rtip',

    // "pagingType": "simple_numbers",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {

      // $(nRow).closest('tr').css({"cursor": "pointer"});

      $(nRow).attr('RefNo', aData['RefNo']);
      $(nRow).attr('gr_refno', aData['gr_refno']);
      $(nRow).attr('loc_group', aData['loc_group']);
      $(nRow).attr('scode', aData['scode']);
      $(nRow).attr('sname', aData['sname']);
      $(nRow).attr('podate', aData['podate']);
      $(nRow).attr('delivery_date', aData['delivery_date']);
      $(nRow).attr('expiry_date', aData['expiry_date']);
      $(nRow).attr('total', aData['total']);
      $(nRow).attr('gst_tax_sum', aData['gst_tax_sum']);
      $(nRow).attr('total_include_tax', aData['total_include_tax']);
      $(nRow).attr('status', aData['status']);
      $(nRow).attr('rejected_remark', aData['rejected_remark']);
      $(nRow).attr('refno', aData['refno']);
      $(nRow).attr('refno', aData['refno']);

    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  // $('#po_new_table_filter').find('input').off('keyup.DT input.DT');
  // $("div.remove_padding").css({"text-align":"left"});

  // var searchDelay = null;
     
  // $('#po_new_table_filter').find('input').on('keyup', function(e) {
  //     var search = $(this).val();
  //     if (e.keyCode == 13) {
  //         table.search(search).draw();
  //         reset = 1;
  //     }//close keycode
  // });//close keyup function

  // $('.remove_padding_right').css({'padding-left':'0'});

}//close po_new_table


po_new_table();


$(document).on('click','#search',function(){
  po_new_table();
});


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
     if(list_id.length > 1)
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