<style>
.content-wrapper{
  min-height: 700px !important; 
}

.btn-app:hover
{
  background: #99ccff;
  font-weight: bold;
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
          <h3 class="box-title">ONE OFF Form</h3>
          <div class="box-tools pull-right">
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
          <table id="register" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead style="white-space: nowrap;">
            <tr>
                <th>Retailer Name</th>
                <th>Supplier Name</th>
                <th>Template Name</th>
                <th>Template Amount </th>
                <th>Template Start Date</th>
                <th>Template End Date</th>
                <th>Form No</th>
                <th>Form Type</th>
                <th>Created at</th>
                <th>Created by</th>
                <th>Updated at</th>
                <th>Updated by</th>
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
  $('#register').DataTable({
    "columnDefs": [
    { className: "alignright", targets: [3] },
    { className: "alignleft", targets: '_all' },],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [9, 'desc'],[7, 'desc'], ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "60%", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Registration_new/one_off_table');?>",
        "type": "POST",
    },
    columns: [
              { "data": "acc_name" },
              { "data": "supplier_name" },
              { "data": "template_name" },
              { "data": "template_amount" },
              { "data": "template_start_date" },
              { "data": "template_end_date" },
              { "data": "form_no" },
              { "data": "form_type" },
              { "data": "created_at" },
              { "data": "created_by" },
              { "data": "updated_at" },
              { "data": "updated_by" },

             ],
    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      $(nRow).attr('one_off_guid', aData['one_off_guid']);
      <?php if(in_array('IAVA',$this->session->userdata('module_code')))
      {
        ?>
        if(aData['isinvoice'] == '0' )
        {
          $(nRow).find('td:eq(0)').css({"background-color":"#ff9999","color":"black"});
          $(nRow).find('td:eq(1)').css({"background-color":"#ff9999","color":"black"});
          $(nRow).find('td:eq(2)').css({"background-color":"#ff9999","color":"black"});
          $(nRow).find('td:eq(3)').css({"background-color":"#ff9999","color":"black"});
          $(nRow).find('td:eq(4)').css({"background-color":"#ff9999","color":"black"});
          $(nRow).find('td:eq(5)').css({"background-color":"#ff9999","color":"black"});
          $(nRow).find('td:eq(6)').css({"background-color":"#ff9999","color":"black"});
          $(nRow).find('td:eq(7)').css({"background-color":"#ff9999","color":"black"});
          $(nRow).find('td:eq(8)').css({"background-color":"#ff9999","color":"black"});
          $(nRow).find('td:eq(9)').css({"background-color":"#ff9999","color":"black"});
          $(nRow).find('td:eq(10)').css({"background-color":"#ff9999","color":"black"});
          $(nRow).find('td:eq(11)').css({"background-color":"#ff9999","color":"black"});
        }
      <?php
      }
      ?>
    },
    "initComplete": function( settings, json ) {
      interval();
    }
  });//close datatable
});
</script>
