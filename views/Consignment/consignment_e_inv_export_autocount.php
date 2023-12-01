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
    <!-- <form id="submit_excel" method="post" action="<?php echo $export_excel_path?>"> -->
        <a class="btn btn-app pull-right" id="download_excel" style="color:#000000">
              <i class="fa fa-file-excel-o"></i> Download Excel
        </a>
<!--         <button type="submit" class="btn btn-box-tool"> <a class="btn btn-app pull-right"style="color:#000000"><i class="fa fa-file-excel-o"></i> Download Excel
        </a>
        </button>
        <input type="text" name="excel_period_code" value="<?php echo $_REQUEST['period_code']?>">
      </form> -->
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
        <form id="consignment_form" action="<?php echo site_url('Consignment_report/consignment_e_inv_export');?>" method='post'>
          <div class="box-body">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-2"><b>Period Code</b></div>
                <div class="col-md-2">
                  <select id="consign_period_code" name="consign_period_code" class="form-control" required>
                    <!-- <option value="ALL">ALL</option> -->
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
          <h3 class="box-title">Consign Sales Statement</h3>
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
                <th>(20 chars)</th>
                <th>(Date: dd/MM/yyyy)</th>
                <th>(12 chars)</th>
                <th>(20 chars)</th>
                <th>(10 chars)</th>
                <th>(30 chars)</th>
                <th>(12 chars)</th>
                <th>(80 chars)</th>
                <th>(Number, use System Currency Rate Decimal)</th>
                <th>(20 chars)</th>
                <th>(Rich Text)</th>
                <th>(Boolean: T or F)</th>
                <th>(12 chars)</th>
                <th>(Number, use System Currency Rate Decimal)</th>
                <th>(100 chars)</th>
                <th>(10 chars)</th>
                <th>(10 chars)</th>
                <th>(8 chars)</th>
                <th>(Number, use System Currency Decimal)</th>
                <th>(Number, use System Currency Decimal)</th>
                <th>(Number, use System Currency Decimal)</th>
                </tr>
                <tr>
                <th>DocNo</th>
                <th class="no-sort">DocDate</th>
                <th class="no-sort">CreditorCode</th>
                <th class="no-sort">SupplierInvoiceNo</th>
                <th class="no-sort">JournalType</th>
                <th class="no-sort">DisplayTerm</th>
                <th class="no-sort">PurchaseAgent</th>
                <th class="no-sort">Description</th>
                <th class="no-sort">CurrencyRate</th>
                <th class="no-sort">RefNo2</th>
                <th class="no-sort">Note</th>
                <th class="no-sort">InclusiveTax</th>
                <th class="no-sort">AccNo</th>
                <th class="no-sort">ToAccountRate</th>
                <th class="no-sort">DetailDescription</th>
                <th class="no-sort">ProjNo</th>
                <th class="no-sort">DeptNo</th>
                <th class="no-sort">TaxType</th>
                <th class="no-sort">TaxableAmt</th>
                <th class="no-sort">TaxAdjustment</th>
                <th class="no-sort">Amount</th>
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

// alert();
$(document).on('click','#consign_button',function(){

  var form = $(this).closest('form').attr('action');
  var consign_status = $('#consign_status').val();
  var consign_period_code = $('#consign_period_code').val();
  var new_form = form+'?period_code='+consign_period_code;
// alert(consign_period_code);return;

  $(this).closest('form').attr('action',new_form);

  $('#consignment_form').submit();

});//close submit location

    var table;
    table = $('#consigment_sales_statement_list_table').DataTable({
      // "columnDefs": [ {"targets": 4 ,"orderable": false}],
      "serverSide": true, 
      'processing'  : true,
      'paging'      : true,
      'lengthChange': false,
      'lengthMenu'  : [ [9999999999999999], ["ALL"] ],
      'searching'   : false,
      // 'ordering'    : false,
      'order'       : [ [0 , 'asc'] ],
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
      columnDefs: [{
          orderable: false,
          targets: "no-sort"
      }],
      //'fixedHeader' : false,
      columns: [
                  { data: "DocNo"},
                  { data: "DocDate"},
                  { data: "CreditorCode"},
                  { data: "SupplierInvoiceNo"},
                  { data: "JournalType"},
                  { data: "DisplayTerm"},
                  { data: "PurchaseAgent"},
                  { data: "Description"},
                  { data: "CurrencyRate"},
                  { data: "RefNo2"},
                  { data: "Note"},
                  { data: "InclusiveTax"},
                  { data: "AccNo"},
                  { data: "ToAccountRate"},
                  { data: "DetailDescription"},
                  { data: "ProjNo"},
                  { data: "DeptNo"},
                  { data: "TaxType"},
                  { data: "TaxableAmt"},
                  { data: "TaxAdjustment"},
                  { data: "Amount"},
                ],
      dom: "<'row'<'col-sm-2'l><'col-sm-4'><'col-sm-6'f>>rti",
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

    $(document).on('click', '#download_excel', function(e) {
        // alert();
        // return;
        url= "<?php echo $export_excel_path?>";
        // alert(url);die;
        // return;
        window.location.href = url;
        check_export_interval = setInterval(check_export, 1000);
        // location.reload();
    });
});//close document ready function

check_export = function()
{ 

  $('#alertmodal .msg').fadeOut(500);
  $('#alertmodal .msg').fadeIn(500);

  var user_guid = '<?= $this->session->userdata('user_guid') ;?>';

  $.ajax({
            url:"<?php echo site_url('Consignment_report/check_download_session');?>",
            method:"POST",
            // data:{user_guid:user_guid},
            beforeSend:function(){
              $('.btn').button('loading');
            },
            success:function(data)
            {

              json = JSON.parse(data);

              if(json['done_download'] == 1)
              { 
                // alertmodal('<?php echo $this->lang->line('exported_successfully'); ?>');
                // $('#alertmodal .icons').html('<i class="fa fa-check fa-5x" style="color:green;"></i>');
                clearInterval(check_export_interval);
                $('.btn').button('reset');
                location.reload();
              }



            }//close success
          });//close ajax
}

</script>
