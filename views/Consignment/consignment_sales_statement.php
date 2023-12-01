<style type="text/css">
  #acc_branch{
    height: auto;
    overflow-y: scroll;

  }
  #acc_concepts{
    height: auto;
    overflow-x: auto;

  }
  .select2-container--default .select2-selection--multiple .select2-selection__choice
  {
    background: #3c8dbc;
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
<?php // echo var_dump($_SESSION); ?>
  <div class="col-md-12">
        <a class="btn btn-app" href="<?php echo site_url('Consignment_report/consignment_location');?>">
          <i class="fa fa-bank"></i> Outlet
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
        <form id="consignment_form" action="<?php echo site_url('Consignment_report/consignment_redirect_location');?>" method='post'>
          <div class="box-body">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-2"><b>Status</b></div>
                <div class="col-md-2">
                  <select id="consign_status" name="consign_status" class="form-control" required>
                    <?php foreach($consignment_sales_statement_status as $row)
                    {
                    ?>
                        <option value="<?php echo $row->code;?>"<?php if($status == $row->code){echo "selected";}?>><?php echo $row->reason;?></option> 
                    <?php
                    }
                    ?>
                  </select>
                </div>
                <div class="clearfix"></div><br>
              </div>

              <div class="row">
                <div class="col-md-2"><b>Period Code</b></div>
                <div class="col-md-2">
                  <select id="consign_period_code" name="consign_period_code" class="form-control" required>
                    <option value="ALL">ALL</option>
                    <?php foreach($period_code as $row)
                    {
                    ?>
                        <option value="<?php echo $row->period_code;?>"<?php if($speriod_code == $row->period_code){echo "selected";}?>><?php echo $row->period_code;?></option> 
                    <?php
                    }
                    ?>
                  </select>
                </div>
                <div class="clearfix"></div><br>
              </div>              

              <div class="row">
                <div class="col-md-12">
                  <button type="button" id="consign_button" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                </div>
              </div>       

              <div class="row">
              </div>     

              <div class="row">
              </div>      

            </div>
          </div>
        </form>
        <!-- body -->

      </div>
    </div>
    
  </div>

<div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Consign Report</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concepts">
          <table id="consigment_sales_statement_list_table" class="table table-bordered table-hover" >
            <form id="consigment_sales_statement_form" method="post" action="<?php echo site_url('general/prints')?>">
              <thead>
                <tr>
                <th>Refno</th>
                <th>Date trans</th>
                <th>Outlet</th>
                <th>Code</th>
                <th>Name</th>
                <th>Date From</th>
                <th>Date To</th>
                <th>Amount</th>
                <th>Sup Doc No</th>
                <th>Sup Doc Date</th>
                <th>Total Incl Tax</th>
                <th>Status</th>
                <th>Action</th>
                <th><input type="checkbox" id="check-all"></th>
                </tr>
              </thead>
            </form>
          </table>
        </div>

      </div>
    </div>
  </div>
   
<!-- nothing ends after -->
</div>
</div>

<script>

$(document).ready(function(){


$(document).on('click','#consign_button',function(){

  var form = $(this).closest('form').attr('action');
  var consign_status = $('#consign_status').val();
  var consign_period_code = $('#consign_period_code').val();
  var new_form = form+'?status='+consign_status+'&loc=<?= $loc;?>&period_code='+consign_period_code;
// alert(consign_period_code);return;

  $(this).closest('form').attr('action',new_form);

  $('#consignment_form').submit();

});//close submit location

var table;
    table = $('#consigment_sales_statement_list_table').DataTable({
      "columnDefs": [ {"targets": 13 ,"orderable": false}],
      "serverSide": true, 
      'processing'  : true,
      'paging'      : true,
      'lengthChange': true,
      'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       : [ [1 , 'desc'] ],
      'info'        : true,
      'autoWidth'   : false,
      "bPaginate": true, 
      "bFilter": true, 
      "sScrollY": "40vh", 
      "sScrollX": "100%", 
      "sScrollXInner": "100%", 
      "bScrollCollapse": true,
      "ajax": {
          "url": "<?php echo $datatable_url;?>",
          "type": "POST",
          // complete:function()
          // {
          //   if(reset == 1)
          //   {
          //     $('#list tbody tr:eq(0)').click();
          //   }

          //   reset = 0;
          // },
      },
      //'fixedHeader' : false,
      columns: [{ data: "refno"},
                { data: "date_trans"},
                { data: "locgroup"},
                { data: "supcus_code"},
                { data: "supcus_name"},
                { data: "date_from"},
                { data: "date_to"},
                { data: "amount"},
                { data: "sup_doc_no"},
                { data: "sup_doc_date"},
                { data: "total_inc_tax"},
                { data: "status"},
                { data: "action"},
                { data: "refno",render: function ( data, type, row ) {
                  if (data == 1) { ischecked = '☑' } else { ischecked = '☐' }
                  return ischecked;
                }}],
      dom: "<'row'<'col-sm-2'l><'col-sm-4'><'col-sm-6'f>>rtip",
      // "oLanguage": {
      // "sLengthMenu": "Show MENU ",
      // },
      // "pagingType": "simple",
      "fnCreatedRow": function( nRow, aData, iDataIndex ) {
        $(nRow).attr('TRANS_GUID', aData['TRANS_GUID']);
        $(nRow).attr('post_status', aData['post_status']);
      },
      "initComplete": function( settings, json ) {
        interval();
      }
    });//close datatable

        $('.table').on( 'draw.dt', function () {
  
            var id = $(this).closest('table').attr('id');

            // alert($('#paloi_wrapper').html());

            setTimeout(function(){

              var table = $('#'+id).DataTable();

              // alert($('#paloi_wrapper').find('div.row').html());

              if((table.settings()[0]['_buttons'] == undefined) && (table.settings()[0]['oInit']['buttons'] == undefined))
              {

                if($('#buttons_append').is(':visible'))
                {
                  return;
                }

                checking = 0;

                $('#'+id+'_wrapper').find('div.row').find('div[class*=col-sm]').each(function(){

                  if($(this).html() == '')
                  { 

                    checking = 1;
                    var xclass = $(this).attr('class');

                    var buttons = new $.fn.dataTable.Buttons(table, {
                       buttons: [
                         // 'copyHtml5',
                         // 'excelHtml5',
                          {
                            extend: 'excelHtml5',
                            filename: 'Consignment Sales Statement List'
                          },
                         // 'csvHtml5',
                         // 'pdfHtml5'
                      ]
                    }).container().appendTo($(this));

                    return;
                  }

                });//close each

                

                if(checking == 1)
                {
                  return;
                }

                $('#'+id+'_wrapper').find('div.row').after('<div id="buttons_append"></div>');

                // $('#paloi_length').append('<div id="buttons_append" style="background-color:red;">asdadsadas</div>');

                var buttons = new $.fn.dataTable.Buttons(table, {
                   buttons: [
                     // 'copyHtml5',
                     // 'excelHtml5',
                      {
                        extend: 'excelHtml5',
                        filename: 'Consignment Sales Statement List'
                      },
                     // 'csvHtml5',
                     // 'pdfHtml5'
                  ]
                }).container().appendTo($('#buttons_append'));

                

              }


            },300);

        });//close datatable add excel button
});//close document ready function


</script>
