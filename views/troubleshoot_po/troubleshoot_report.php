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

<form id="report_search_form" method="post" action="<?php echo site_url('Troubleshoot_po/troubleshoot_report_search')?>">

              <div class="col-md-2"><b>Customer</b></div>
              <div class="col-md-4">
                 <select name="customer_guid" id="customer_guid" class="form-control">
                  <?php
                  foreach($acc->result() as $row)
                  {
                  ?>

                    <?php

                      if(isset($_REQUEST['customer_guid']) && $_REQUEST['customer_guid'] == $row->acc_guid)
                      {
                        $selected = 'selected';
                      }
                      else
                      {
                        $selected = '';
                      }

                    ?>

                    <option <?= $selected;?> value="<?=$row->acc_guid;?>"><?=$row->acc_name;?></option>

                  <?php
                  }
                  ?>
                </select>
              </div>

              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Report Type</b></div>
              <div class="col-md-4">
                 <select name="report_guid" id="report_guid" class="form-control">
                  <?php
                  foreach($report_type->result() as $row)
                  {
                  ?>

                    <?php

                      if(isset($_REQUEST['report_guid']) && $_REQUEST['report_guid'] == $row->report_guid)
                      { 
                        $selected = 'selected';
                      }
                      else
                      {
                        $selected = '';
                      }

                    ?>
                    <option <?=$selected;?> value="<?=$row->report_guid;?>"><?=$row->report_name;?></option>

                  <?php
                  }
                  ?>
                </select>
              </div>



              
              <div class="clearfix"></div><br>

              <div class="col-md-12">

                
                <button type="button" id="search" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>

                <a href="<?php echo site_url('Troubleshoot_po');?>" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a>
                
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

          <span id="parameter_span">
            <?php
            if(isset($all_customer_query) && $all_customer_query <= 0)
            {
            ?>
              <span class="pill_button"> Under All Retailer</span>
            <?php
            }
            ?>
          </span>

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
                    <table id="set_report_query_table" class="table table-bordered table-hover" >
                      <!-- <form id="formPO" method="post" action="<?php echo site_url('general/prints')?>"> -->
                        <thead>
                            <tr>
                            <?php
                            if(isset($_REQUEST['customer_guid']) && isset($_REQUEST['report_guid']) && (count($header) > 0) && ($query != '') )
                            {
                              foreach ($header as $row)
                              {
                              ?>
                                <th><?=$row;?></th>
                              <?php
                              }
                            }
                            else
                            {
                            ?>
                              <th>Messages</th>
                            <?php
                            }
                            ?>

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



</div>
        </div>
        
        </div>
    </div>
</div>
</div>
</div>
</div>


<script>  
 $(document).ready(function(){  


<?php
if(isset($_REQUEST['customer_guid']) && isset($_REQUEST['report_guid']) && (count($header) > 0) && ($query != '') )
{
?>


set_report_query_table = function()
{
  if ( $.fn.DataTable.isDataTable('#set_report_query_table') ) {
    $('#set_report_query_table').DataTable().destroy();
  }

  var table;

  table = $('#set_report_query_table').DataTable({
    // "columnDefs": [ {"targets": [13,14] ,"orderable": false}],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
    'searching'   : true,
    // 'ordering'    : true,
    // 'order'       : [ [0 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    // "sScrollY": "30vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    // "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Troubleshoot_po/set_report_query_table');?>",
        "type": "POST",
        data : {header:<?= json_encode($header);?>,query:'<?=urlencode($query);?>'},
        complete:function()
        {
        },
    },
    //'fixedHeader' : false,
    columns: [
              <?=$datatable_columns;?>
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

      // $(nRow).attr('RefNo', aData['RefNo']);


    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

}//close set_report_query_table

set_report_query_table();

<?php
}
else
{
?>

// set_report_query_table();

$('#set_report_query_table').DataTable();


<?php
}
?>



$(document).on('click','#search',function(){

  var report_guid = $('#report_guid').val();
  var customer_guid = $('#customer_guid').val();

  if(customer_guid == '' || customer_guid == null)
  {
    alert('Please select a customer to proceed.');
    return;
  }

  if(report_guid == '' || report_guid == null)
  {
    alert('Vendor Code must have value.');
    return;
  }


  $('#report_search_form').submit();
  // set_report_query_table();
});

});//close document ready

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