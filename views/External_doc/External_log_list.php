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
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Miscellaneous Document Log </h3>
          <span class="pill_button" style="color:#66ff99;"><small>Data will only show 6 months.</small></span>
          
          <div class="box-tools pull-right">
<!--             <a class="btn btn-xs btn-warning" target="_blank" href="<?php echo site_url('Amend_doc/amend_sites');?>">
              <i class="fa fa-file"></i> Hide/Reset Doc
            </a> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >

          <table id="misc_tb" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead > <!--style="white-space: nowrap;"-->
            <tr>
                <th>Retailer Name</th>
                <th>Supplier Name</th>
                <th>Miscellaneous Document</th>
                <th>Document Type</th>
                <th>File Name</th>
                <th>Action Type</th>
                <th>Created At</th>
                <th>Created By</th>
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
  $('#misc_tb').DataTable({
    "columnDefs": [ ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'ALL'] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [6 , 'DESC'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "60vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('External_doc/misc_log_table');?>",
        "type": "POST",
    },
    columns: [

             { "data": "acc_name" },
             { "data": "supplier_name" },
             { "data": "doc_charge_type" },
             { "data": "doc_type" },
             { "data": "file_name" },
             { "data": "action_type" },
             { "data": "created_at" },
             { "data": "created_by" },

             ],
    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('guid', aData['guid']);
      
    },
    "initComplete": function( settings, json ) {
      interval();
    }
  });//close datatable
});
</script>
