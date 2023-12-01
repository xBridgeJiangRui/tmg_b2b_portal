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
              <div class="col-md-2"><b>Retailer Name</b></div>
              <div class="col-md-4">
                <input tpye="text" class="form-control" id="retailer_name" name="retailer_name" value="<?php echo $customer_name?>" disabled />
              </div>
              <div class="clearfix"></div><br>
              
              <div class="col-md-2"><b>Document Type</b></div>
              <div class="col-md-4">
                <select name="doc_type" id="doc_type" class="form-control">
                  <option value="" disabled selected>-Select Document Type-</option>
                  <option value="pomain">Purchase Order (PO)</option>
                  <option value="grmain">Goods Received Note (GRN)</option> 
                  <option value="grda">Goods Received Diff Advice (GRDA)</option> 
                  <option value="dbnotemain">Purchase Return DN (PRDN)</option> 
                  <option value="cnnotemain"> Purchase Return CN (PRCN)</option> 
                  <option value="cndn_amt">Purchase DN/CN (PDN)</option> 
                  <option value="pci">Promotion Claim Tax Invoice (PCI)</option> 
                  <option value="display_incentive">Display Incentive (DI)</option>
                </select>
              </div>
              <div class="clearfix"></div><br>
              
              <div class="col-md-2"><b>RefNo</b></div>
              <div class="col-md-4">
                <input type="text" class="form-control" id="insert_refno" name="insert_refno" placeholder="Please Insert RefNo Here" />
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-12">

                <button type="button" id="search_data" class="btn btn-primary" ><i class=""></i> Search </button>
                
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
          <h3 class="box-title">Hide Document List <span class="add_branch_list"></span></h3>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$_SESSION['module_code']))
            {
            ?>
              <a class="btn btn-xs btn-warning" target="_blank" href="<?php echo site_url('Amend_doc/amend_sites');?>">
                <i class="fa fa-file"></i> Hide/Reset Doc
              </a>
            <?php
            }
            ?> 
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >

          <table id="amend_tb" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead > <!--style="white-space: nowrap;"-->
            <tr>
                <th>RefNo</th>
                <th>Status</th>
                <th>Document Date</th>
                <th>Location</th>
                <th>Code</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Hide Remark</th>
                <th>Hide at</th>
                <th>Hide by</th>

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
  $('#amend_tb').DataTable({
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
                "zeroRecords": "<?php echo '<b>No Record Found. Please Select Dcoument Type to view data.</b>'; ?>",
      },
      "pagingType": "simple_numbers",
  });
  $('.remove_padding_right').css({'text-align':'left'});
  $("div.remove_padding").css({"text-align":"left"});

  $(document).on('click','#search_data',function(){

    document_type = $('#doc_type').val();
    refno = $('#insert_refno').val();
    
    if((document_type == '') || (document_type == 'null') || (document_type == null))
    {
      alert('Opps, Please Select Document Type for Searching...');
      return;
    }

    if(document_type == 'pomain')
    {
      value = 'Purchase Order'
    }
    else if(document_type == 'grmain')
    {
      value = 'Goods Received Note'
    }
    else if(document_type == 'grda')
    {
      value = 'Goods Received Diff Advice'
    }
    else if(document_type == 'dbnotemain')
    {
      value = 'Purchase Return DN'
    }
    else if(document_type == 'cnnotemain')
    {
      value = 'Purchase Return CN'
    }
    else if(document_type == 'cndn_amt')
    {
      value = 'Purchase DN/CN'
    }
    else if(document_type == 'pci')
    {
      value = 'Promotion Claim Tax Invoice'
    }
    else if(document_type == 'display_incentive')
    {
      value = 'Display Incentive Tax Invoice'
    }

    amend_document_table(document_type,refno);
    $('.add_branch_list').addClass('pill_button');
    $('.add_branch_list').html(value);

  });//close search button

  amend_document_table = function(document_type,refno)
  { 
    $.ajax({
      url : "<?php echo site_url('Amend_doc/amend_doc_table');?>",
      method: "POST",
      data:{document_type:document_type,refno:refno},
      beforeSend : function() {
          $('.btn').button('loading');
      },
      complete: function() {
          $('.btn').button('reset');
      },
      success : function(data)
      {  
        json = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#amend_tb')) {
            $('#amend_tb').DataTable().destroy();
        }

        $('#amend_tb').DataTable({
        "columnDefs": [
        { className: "alignright", targets: [6] },
        { className: "alignleft", targets: '_all' },],
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [ [1 , 'asc'] ],
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
                    { "data": "refno_val" },
                    { "data": "status" },
                    { "data": "document_date" },
                    { "data": "loc_group" },
                    { "data": "supplier_code" },
                    { "data": "supplier_name" },
                    { "data": "total" },
                    { "data": "hide_remark" },
                    { "data": "hide_at" },
                    { "data": "hide_by" },
                   ],
          dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",  
          "language": {
            "lengthMenu": "Show _MENU_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "zeroRecords": "<?php echo '<b>No Record Found.</b>'; ?>",
          },
         "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).closest('tr').css({"cursor": "pointer"});
            $(nRow).attr('refno_val', aData['refno_val']);
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
});
</script>
