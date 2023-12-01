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
        <a class="btn btn-app" href="<?php echo site_url('Consignment_report/consignment_sales_statement_by_supcode');?>?status=<?php echo $_REQUEST['status'];?>&loc=<?php echo $_REQUEST['loc'];?>&period_code=<?php echo $_REQUEST['period_code'];?>">
          <i class="fa fa-arrow-left"></i> Back
        </a>

<!--         <a class="btn btn-app" href="<?php echo site_url('Consignment_report/consignment_location_by_supcode');?>">
          <i class="fa fa-bank"></i> Outlet
        </a> -->
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <!-- head -->
        <div class="box-header with-border">
          <h3 class="box-title">Header</h3><br>
            <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- head -->
        <!-- body -->
          <div class="box-body">
            <div class="col-md-12">
              <div class="row">
                <table id="consigment_sales_statement_list_table" class="table table-bordered table-hover" >
                  <form id="consigment_sales_statement_form" method="post" action="<?php echo site_url('Consignment_report/consignment_generate_e_invoices_by_supcode')?>">
                    <thead>
                      <tr>
                      <!-- <th>Refno</th> -->
                      <!-- <th>Date trans</th> -->
                      <!-- <th>Outlet</th> -->
                      <th>Code</th>
                      <th>Name</th>
                      <th>Date From</th>
                      <th>Date To</th>
                      <th>Total Amount</th>
                      <!-- <th>Sup Inv No</th> -->
                      <th>Supplier Invoice No</th>
                      <th>Supplier Invoice Date</th>
                      <!-- <th>Total Incl Tax</th> -->
                      <!-- <th>Status</th> -->
                      <!-- <th>Action</th> -->
                      <?php if(($consignment_sales_statement_sup_inv_no == '' || $consignment_sales_statement_sup_inv_no == null || $consignment_sales_statement_status != 'Invoice Generated' || $consignment_sales_statement_status != 'Invoice Generated') && $inv_generate_count == 0)
                      {
                      ?>
                      <th>Action</th>
                      <th>Generate</th>
                      <?php
                      }
                      ?>
                      <?php if($consignment_sales_statement_sup_inv_no == '' || $consignment_sales_statement_sup_inv_no == null || $consignment_sales_statement_status != 'Invoice Generated')
                      {
                      ?>
                      <!-- <th>Reject</th> -->
                      <?php
                      }
                      ?>
                      <?php if($show_upload_e_invoice == 1)
                      {
                      ?>
                      <th>File</th>
                      <?php
                      }
                      ?>
                      <!-- <th>Action</th> -->
                      <!-- <th>Generate</th> -->
                      <!-- <th>Reject</th>                       -->
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($consignment_sales_statement_header as $row)
                      {
                      ?>
                      <tr>
                      <!-- <td><?php echo $row->refno;?></td> -->
                      <!-- <td><?php echo $row->date_trans;?></td> -->
                      <!-- <td><?php echo $row->locgroup;?></td> -->
                      <td><?php echo $row->supcus_code;?></td>
                      <td><?php echo $row->supcus_name;?></td>
                      <td><?php echo $row->date_from;?></td>
                      <td><?php echo $row->date_to;?></td>
                      <td><?php echo number_format($row->total,2,'.',',');?></td>
                      <!-- <td><?php echo $row->sup_doc_no;?></td> -->
                      <?php if(($consignment_sales_statement_sup_inv_no == '' || $consignment_sales_statement_sup_inv_no == null || $consignment_sales_statement_status != 'Invoice Generated') && $inv_generate_count == 0)
                      {
                        $hide = '';
                      }
                      else
                      {
                        $hide = 'disabled';
                      }
                      ?>
                      <td><input class="form-control" type="text" id="b2b_inv_no" name="b2b_inv_no" value="<?php echo $row->b2b_inv_no?>" required <?php echo $hide;?> autocomplete="off"><input class="form-control" type="hidden" name="trans_guid" id="trans_guid" value="<?php echo $row->trans_guid?>" ></td>                      
                      <?php if(($consignment_sales_statement_sup_inv_no == '' || $consignment_sales_statement_sup_inv_no == null || $consignment_sales_statement_status != 'Invoice Generated') && $inv_generate_count == 0)
                      {
                        // $readonly = '';
                        // $color = 'style="background-color:white;"';
                        $readonly = 'style="background-color:white;" readonly';
                      }
                      else
                      {
                        $readonly = 'disabled';
                        // $readonly = 'READONLY';
                      }
                      ?>                      
                      <td><input  id="consign_invoice_date" name="consign_invoice_date" type="datetime" class="form-control pull-right" required <?php echo $readonly;?> autocomplete="off" value="<?php echo $row->sup_doc_date;?>"></td>
                      <!-- <td><?php echo $row->sup_doc_date;?></td> -->
                      <!-- <td><?php echo $row->total_inc_tax;?></td> -->
                      <!-- <td><?php echo $row->status;?></td> -->
                      <!-- <td><button type="button" id="header_save" class="btn btn-success">Save</button></td> -->
                      <?php if(($consignment_sales_statement_sup_inv_no == '' || $consignment_sales_statement_sup_inv_no == null || $consignment_sales_statement_status != 'Invoice Generated') && $inv_generate_count == 0)
                      {
                      ?>
                        <td><button type="button" id="header_save" class="btn btn-success">Save</button></td>                   
                        <?php if($this->session->userdata('customer_guid') != '1')
                        {
                        ?>
                        <td>
                        <?php if($show_upload_e_invoice == 1)
                        {
                        ?>
                          <!-- <button class="btn btn-warning" type="button" id="upload_invoice_doc">Upload Supplier Invoice</button> -->
                          <?php if($exists_consign_inv_file == 1)
                          {
                          ?>
                            <!-- <a target="_blank" href="<?php echo base_url($target_dir).'?time='.date("Ymdhs");?>"><button class="btn btn-success" type="button"">View Supplier Inv</button></a> -->
                            <button type="submit" class="btn btn-primary">Generate E invoice</button>
                          <?php
                          } 
                          else
                          {
                          ?>
                            <!-- <button type="button" onclick="alert('Please Upload Your Invoice First')" class="btn btn-success">Not Upload</button> -->
                            <button type="button" onclick="alert('Please Upload Your Invoice First')" class="btn btn-primary">Generate E invoice</button>
                          <?php
                          }
                          ?>
                        <?php
                        }
                        else
                        {
                        ?>
                          <button type="submit" class="btn btn-primary">Generate E invoice</button>
                        <?php
                        }
                        ?>     
                        </td>
                        <?php
                        }
                        else
                        {
                        ?>
                        <td>Under Maintainence</td>
                        <?php
                        }
                        ;?>
                        <!-- <td>Under Maintainence</td> -->
                        <!-- <td>Under Maintainence</td> -->
                      <?php
                      }
                      ?>
                      <?php if($show_upload_e_invoice == 1)
                      {
                      ?><td>
                        <button class="btn btn-warning" type="button" id="upload_invoice_doc">Upload Supplier Invoice</button>
                        <?php if($exists_consign_inv_file == 1)
                        {
                        ?>
                          <a target="_blank" href="<?php echo site_url('Upload/view_upload?parameter='.$file_upload_supcode.'&parameter2='.$file_upload_supplier_guid.'&parameter3='.$file_upload_type).'&time='.date("Ymdhs").'&company_id='.$_REQUEST['company_id'];?>"><button class="btn btn-success" type="button">View Supplier Inv</button></a>
                          <!-- <button type="submit" class="btn btn-primary">Generate E invoice</button> -->
                        <?php
                        } 
                        else
                        {
                        ?>
                          <button type="button" onclick="alert('Please Upload Your Invoice First')" class="btn btn-success">Not Upload</button>
                          <!-- <button type="button" onclick="alert('Please Upload Your Invoice First')" class="btn btn-primary">Generate E invoice</button> -->
                        </td>
                        <?php
                        }
                        ?>
                      <?php
                      }
                      ?> 
                                           
                      <?php if($consignment_sales_statement_sup_inv_no == '' || $consignment_sales_statement_sup_inv_no == null || $consignment_sales_statement_status != 'Invoice Generated')
                      {
                      ?>
                        <!-- <td><button id="consignment_reject" type="button" class="btn btn-danger">Reject</button></td> -->
                      <?php
                      }
                      ?>
<!--                       <td><button type="button" id="header_save" class="btn btn-success">Save</button></td>
                      <td><button type="submit" class="btn btn-primary">Generate E invoice</button></td>    -->           
                      </tr>
                      <?php
                      }
                      ?>

                      <!-- <td><button id="consignment_reject" type="button" class="btn btn-danger">Reject</button></td>                       -->
                      <input  id="loc" name="loc" type="hidden" value="<?php echo $_REQUEST['loc'];?>" readonly>
                      <input  id="period_code" name="period_code" type="hidden" value="<?php echo $_REQUEST['period_code'];?>" readonly>
                      <input  id="status" name="status" type="hidden" value="<?php echo $_REQUEST['status'];?>" readonly>
                      <input  id="submit_period" name="submit_period" type="hidden" value="<?php echo substr($row->date_from, 0, 7);?>" readonly>
                      <input  id="submit_supcode" name="submit_supcode" type="hidden" value="<?php echo $row->unique_key;?>" readonly>
                      <input  id="date_trans" name="date_trans" type="hidden" value="<?php echo $row->date_trans;?>" readonly>
                      <input  id="company_id" name="company_id" type="hidden" value="<?php echo $_REQUEST['company_id'];?>" readonly>
                    </tbody>
                  </form>
                </table>
              </div>                 

              <div class="row">
              </div>     

              <div class="row">
              </div>      

            </div>
          </div>
        <!-- body -->

      </div>
    </div>
    
  </div>


<?php if($consignment_sales_statement_sup_inv_no != '' && $consignment_sales_statement_status == 'Invoice Generated' && $consignment_sales_statement_sup_inv_no != '' && $consignment_sales_statement_sup_inv_no != null && $show_e_invoice == '1')
{
?>
<div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Consignment E - invoice</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="consignment_sales_statement_child_body">

          <div class="col-md-12">
            <div id="embed_e_invoice_consign">
                <embed id="consignment_e_invoice_child_embed" height="750px" width="100%" src="<?php echo site_url('Consignment_report/consignment_e_invoice_by_supcode_view').'?report_type=consignment_e_invoice_by_supcode'.'&trans_guid='.$consignment_sales_statement_sup_inv_no.'&supcode='.$consignment_sales_statement_sup_code.'&company_id='.$_REQUEST['company_id'];?>"></embed>
            </div>

              <!-- If report not showing, please click this link: <a id="consignment_e_invoice_child_link" href="" download>View Report</a> -->
        </div>

        </div>

      </div>
    </div><!--row-->
  </div>
<?php
}
else
{
 if($consignment_sales_statement_sup_inv_no != '' && $consignment_sales_statement_sup_inv_no != null && $show_e_invoice == '1')
{
?>
<div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Temp Consignment E - invoice View</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="consignment_sales_statement_child_body">

          <div class="col-md-12">
            <div id="embed_e_invoice_consign">
                <embed id="consignment_e_invoice_child_embed" height="750px" width="100%" src="<?php echo site_url('Consignment_report/consignment_e_invoice_by_supcode_view').'?report_type=consignment_e_invoice_by_supcode'.'&trans_guid='.$consignment_sales_statement_sup_inv_no.'&supcode='.$consignment_sales_statement_sup_code;?>"></embed>
            </div>

              <!-- If report not showing, please click this link: <a id="consignment_e_invoice_child_link" href="" download>View Report</a> -->
        </div>

        </div>

      </div>
    </div><!--row-->
  </div>
<?php  
} 
}
?>

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
        <div class="box-body" id="consignment_sales_statement_child_body">

          <div class="col-md-12">
            <div id="embed_consign">
              <table id="consigment_sales_statement_by_supcode_list_table" class="table table-bordered table-hover" >
                <form id="consigment_sales_statement_form" method="post" action="<?php echo site_url('general/prints')?>">
                  <thead>
                    <tr>
                    <th>Retailer Name</th>
                    <th>Refno</th>
                    <th>Date trans</th>
                    <th>Outlet</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Date From</th>
                    <th>Date To</th>
                    <th>Total Amount</th>
                    <th>Supplier Invoice No</th>
                    <th>Supplier Invoice Date</th>
                    <!-- <th>Total Incl Tax</th> -->
                    <th>Status</th>
                    <th>Action</th>
                    <!-- <th><input type="checkbox" id="check-all"></th> -->
                    </tr>
                  </thead>
                </form>
              </table>              
            </div>

              <!-- If report not showing, please click this link: <a id="consignment_sales_statement_child_link" href="" download>View Report</a> -->
        </div>

        </div>

      </div>
    </div><!--row-->    
  </div>
   
<!-- nothing ends after -->
</div>
</div>
<script type="text/javascript">
$(function() {
  var date = $('#consign_invoice_date').val();
  var limit_date = "<?php echo $limit_date;?>";
  // alert(date);
  $('input[name="consign_invoice_date"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    "minDate": limit_date,
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
  if(date < '2000-01-01')
  {
    // alert(limit_date);
    $(this).find('[name="consign_invoice_date"]').val("");
  }
  else
  {
    // alert(date);
    $(this).find('[name="consign_invoice_date"]').val(date);
  }
  // $(this).find('[name="consign_invoice_date"]').val("");
});
</script>
<script>

$(document).ready(function(){

  // $(document).on('change','#consign_invoice_date',function(){
  //   alert();
  // });


  $(document).on('click','#header_save',function(){

    var limit_date = "<?php echo $limit_date;?>";
    var b2b_inv_no = $('#b2b_inv_no').val().trim();
    var consign_invoice_date = $('#consign_invoice_date').val();
    var submit_period = $('#submit_period').val();
    var submit_supcode = $('#submit_supcode').val();
    var date_trans = $('#date_trans').val();
    var company_id = "<?php echo $_REQUEST['company_id'];?>";

    if(consign_invoice_date < limit_date)
    {
      alert('Supplier Invoice Date exceeded');
      $('#consign_invoice_date').focus();
      return;
    }

    if(b2b_inv_no == '' || b2b_inv_no == null)
    {
      alert('Please Key in Supplier Invoice No');
      $('#b2b_inv_no').focus();
      return;
    }

    if(consign_invoice_date == '' || consign_invoice_date == null || consign_invoice_date == 'null')
    {
      alert('Please Key in Supplier Invoice Date');
      $('#consign_invoice_date').focus();
      return;
    }    

    if(submit_period == '' || submit_period == null)
    {
      alert('Error Occur, Please Contact Support');
      return;
    } 

    if(submit_supcode == '' || submit_supcode == null)
    {
      alert('Error Occur, Please Contact Support');
      return;
    }
    
    if(date_trans == '' || date_trans == null)
    {
      alert('Error Date Trans Occur, Please Contact Support');
      return;
    }

    // alert();
    // return;

    $.ajax({
      type: "POST",                
      url: "<?php echo site_url('Consignment_report/header_save_inv_no_by_supcode')?>", 
      data:{b2b_inv_no:b2b_inv_no,consign_invoice_date:consign_invoice_date,submit_period:submit_period,submit_supcode:submit_supcode,date_trans:date_trans,company_id:company_id},
      beforeSend : function() {
        // alert('before Send');

      },
      complete: function() {
        // alert('complete');

      },              
      success: function(data) {
        // alert(data);return;
        if(data == 1)
        {
          alert("Updated successfully.");

          var url = $('#consignment_e_invoice_child_embed').attr('src');
          var url2 = url.substring(url.lastIndexOf("&supcode="));
          // alert(url2); return;
          // alert(url.lastIndexOf("&trans_guid="));
          var url = url.substring(0, url.lastIndexOf("&trans_guid=")+1);
          // alert(url);return;
          var url = url+'trans_guid='+b2b_inv_no+url2;
          $('#consignment_e_invoice_child_embed').attr('src', url);

          $('#consignment_e_invoice_child_link').attr('href', url);
          var clone = $('#embed_e_invoice_consign embed').clone(); 
          $('#embed_e_invoice_consign embed').remove(); 
          $('#embed_e_invoice_consign').append(clone)

        }
        else
        {
          json = JSON.parse(data);
          msg= json.msg;
          if(msg != '' || msg != 'null' || msg != null)
          {
            alert(msg);
          }
          else
          {
            alert("Update unsuccessful, Please try again.");
          }
        }
        // alert(data);
      

      }//close success
    });//close ajax 

  });//onclick

  $(document).on('click','#consignment_reject',function(){
    // alert('1');return;
    var trans_guid = $('#trans_guid').val();
    var status = 'rejected';
    // alert();
    // window.location.reload();
    // var b2b_inv_no = $('#b2b_inv_no').val();
    // alert(trans_guid);return;

    $.ajax({
      type: "POST",                
      url: "<?php echo site_url('Consignment_report/update_consign_sales_statement_status')?>", 
      data:{trans_guid:trans_guid,status:status},
      beforeSend : function() {
        // alert('before Send');

      },
      complete: function() {
        // alert('complete');

      },              
      success: function(data) {
        // alert(data);return;
        if(data == 1)
        {
          alert("Reject successfully.");
          window.location.reload();return;

        }
        else
        {
          alert("Reject unsuccessful, Please try again.");
        }
        // alert(data);
      

      }//close success
    });//close ajax 

  });  

    var table;
    table = $('#consigment_sales_statement_by_supcode_list_table').DataTable({
      "columnDefs": [ {"targets": 12 ,"orderable": false}],
      "serverSide": true, 
      'processing'  : true,
      'paging'      : true,
      'lengthChange': false,
      'lengthMenu'  : [ [9999999999999999], ["ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       : [ [2 , 'desc'] ],
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
      columns: [
                { data: "company_name", render:function( data, type, row ){

                  var element = '';
                  var element1 = row['acc_name'];

                  if(data == '')
                  {
                    element += element1;
                  }
                  else
                  {
                    element += data;
                  }

                  return element;

                }},
                { data: "refno"},
                { data: "date_trans"},
                { data: "locgroup"},
                { data: "supcus_code"},
                { data: "supcus_name"},
                { data: "date_from"},
                { data: "date_to"},
                { data: "amount", render: $.fn.dataTable.render.number( ',', '.', 2,)},
                { data: "sup_doc_no"},
                { data: "sup_doc_date", render:function( data, type, row ){

                  var element = '';
                  var element2 = row['status'];
                  
                  // if(data == '0000-00-00' || data == '1001-01-01')
                  // {
                  //   element = '';
                  // }
                  // else
                  // {
                  //   element = data;
                  // }

                  if(element2 == 'Invoice Generated')
                  {
                    element = data;
                  }
                  else
                  {
                    element = '';
                  }

                  return element;

                }},
                // { data: "total_inc_tax"},
                { data: "status"},
                { data: "action"},
                // { data: "refno",render: function ( data, type, row ) {
                //   if (data == 1) { ischecked = '☑' } else { ischecked = '☐' }
                //   return ischecked;
                // }}
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

  $(document).on('click','#upload_invoice_doc',function(){
    loc = $('#loc').val();
    period_coded_code = $('#period_coded_code').val();
    status = $('#status').val();
    submit_period = $('#submit_period').val();
    submit_supcode = $('#submit_supcode').val();
    upload_date_trans = $('#date_trans').val();
    //alert(submit_period); die;
    doc_type = 'consign';
    trans_list = "<?php echo $_REQUEST['trans'];?>";
    upload_company_id = "<?php echo $_REQUEST['company_id'];?>";

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Upload Consign Invoice');
    // if print out inpost empty due to file issue too big
    methodd = '';
    methodd +='<form action="<?php echo site_url('Upload/upload_consign_inv');?>" method="post" enctype="multipart/form-data" id="form_upload_consign_inv">';
    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-6"><label>Sup Code</label><input type="text" id="upload_sup_code" class="form-control input-sm" placeholder="Supcode" style="text-transform:uppercase" value='+submit_supcode+' name="upload_sup_code" readonly required/></div>';

    methodd += '<div class="col-md-6"><label>File (Only PDF allow)</label><input type="file" id="upload_consign_inv_attachment" class="form-control input-sm" name="upload_consign_inv_attachment" accept=".pdf" placeholder="Please Choose File" required/><input type="hidden" name="upload_consign_inv_attachment_doc_type" value="'+doc_type+'"/><input type="hidden" name="upload_consign_inv_loc" id="upload_consign_inv_loc" value="'+loc+'"/></div>';

    methodd += '</div>';
    methodd +='<input type="hidden" id="upload_consign_inv_period_code" name="upload_consign_inv_period_code" value="'+submit_period+'" style="display:none;">';
    methodd +='<input type="hidden" id="upload_consign_inv_statuss" name="upload_consign_inv_statuss" value="'+status+'" style="display:none;">';
    methodd +='<input type="hidden" id="upload_consign_inv_trans_list" name="upload_consign_inv_trans_list" value="'+trans_list+'" style="display:none;">';
    methodd +='<input type="hidden" id="upload_consign_date_trans" name="upload_consign_date_trans" value="'+upload_date_trans+'" style="display:none;">';
    methodd +='<input type="hidden" id="upload_company_id" name="upload_company_id" value="'+upload_company_id+'" style="display:none;">';
    methodd +='<input type="submit" id="upload_consign_inv_submit_button_hide" style="display:none;"></form>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="upload_consign_inv_submit_button" class="btn btn-success" value="Upload"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

  });

  $(document).on('click','#upload_consign_inv_submit_button',function(){
     // $("#form_upload_prdn_cn").submit();
     $("#upload_consign_inv_submit_button_hide").trigger('click');
  });  

});

</script>