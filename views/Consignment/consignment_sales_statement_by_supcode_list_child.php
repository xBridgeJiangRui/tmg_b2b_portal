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
        <a class="btn btn-app" href="<?php echo site_url('Consignment_report/consignment_sales_statement_by_supcode_list');?>?trans=<?php echo $_REQUEST['trans'];?>&status=<?php echo $_REQUEST['status'];?>&loc=<?php echo $_REQUEST['loc'];?>&period_code=<?php echo $_REQUEST['period_code'];?>&supcode=<?php echo $_REQUEST['supcode'];?>&date_trans=<?php echo $_REQUEST['date_trans'];?>&company_id=<?php echo $_REQUEST['company_id'];?>">
          <i class="fa fa-arrow-left"></i> Back
        </a>

<!--         <a class="btn btn-app" href="<?php echo site_url('Consignment_report/consignment_sales_statement_by_supcode');?>?status=<?php echo $_REQUEST['status'];?>&loc=<?php echo $_REQUEST['loc'];?>&period_code=<?php echo $_REQUEST['period_code'];?>">
          <i class="fa fa-search"></i> Browse
        </a> -->

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
                  <form id="consigment_sales_statement_form" method="post" action="<?php echo site_url('Consignment_report/consignment_generate_e_invoice')?>">
                    <thead>
                      <tr>
                      <th>Refno</th>
                      <th>Date trans</th>
                      <th>Outlet</th>
                      <th>Code</th>
                      <th>Name</th>
                      <th>Date From</th>
                      <th>Date To</th>
                      <th>Total Amount</th>
                      <!-- <th>Sup Invoice No</th> -->
                      <th>Supplier Invoice Date</th>
                      <!-- <th>Total Incl Tax</th> -->
                      <th>Supplier Invoice No</th>
                      <th>Status</th>
                      <!-- <th>Action</th> -->
<!--                       <?php if($show_consignment_e_invoice == '0' && $show_reject == '1')
                      {
                      ?>
                      <th>Action</th>
                      <th>Generate</th>
                      <?php
                      }
                      ?>
                      <?php if($show_consignment_e_invoice == '0' && $show_reject == '1')
                      {
                      ?>
                      <th>Reject</th>
                      <?php
                      }
                      ?> -->
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($consignment_sales_statement_header as $row)
                      {
                      ?>
                      <tr>
                      <td><?php echo $row->refno;?></td>
                      <td><?php echo $row->date_trans;?></td>
                      <td><?php echo $row->locgroup;?></td>
                      <td><?php echo $row->supcus_code;?></td>
                      <td><?php echo $row->supcus_name;?></td>
                      <td><?php echo $row->date_from;?></td>
                      <td><?php echo $row->date_to;?></td>
                      <td><?php echo number_format($row->amount,2,'.',',');?></td>
                      <!-- <td><?php echo $row->sup_doc_no;?></td> -->
                      <?php if($show_consignment_e_invoice == '0' && $show_reject == '1')
                      {
                        $readonly = '';
                      }
                      else
                      {
                        $readonly = 'disabled';
                      }
                      $readonly = 'disabled';
                      ?>                      
                      <td><input  id="consign_invoice_date" name="consign_invoice_date" type="datetime" class="form-control pull-right" <?php echo $readonly;?> autocomplete="off" value="<?php echo $row->sup_doc_date;?>"></td>
                      <!-- <td><?php echo $row->sup_doc_date;?></td> -->
                      <!-- <td><?php echo $row->total_inc_tax;?></td> -->
                      <?php if($show_consignment_e_invoice == '0' && $show_reject == '1')
                      {
                        $hide = '';
                      }
                      else
                      {
                        $hide = 'disabled';
                      }
                      $hide = 'disabled';
                      ?>
                      <td><input class="form-control" type="text" id="b2b_inv_no" name="b2b_inv_no" value="<?php echo $row->b2b_inv_no?>" required <?php echo $hide;?>><input class="form-control" type="hidden" name="trans_guid" id="trans_guid" value="<?php echo $row->trans_guid?>"></td>
                      <td><?php echo $row->status;?></td>
                      <!-- <td><button type="button" id="header_save" class="btn btn-success">Save</button></td> -->
<!--                       <?php if($show_consignment_e_invoice == '0' && $show_reject == '1')
                      {
                      ?>
                        <td><button type="button" id="header_save" class="btn btn-success">Save</button></td>
                        <td><button type="submit" class="btn btn-primary">Generate E invoice</button></td>
                      <?php
                      }
                      ?>
                      <?php if($show_consignment_e_invoice == '0' && $show_reject == '1')
                      {
                      ?>
                        <td><button id="consignment_reject" type="button" class="btn btn-danger">Reject</button></td>
                      <?php
                      }
                      ?> -->
                      </tr>
                      <?php
                      }
                      ?>
                      <input  id="loc" name="loc" type="hidden" value="<?php echo $_REQUEST['loc'];?>">
                      <input  id="period_code" name="period_code" type="hidden" value="<?php echo $_REQUEST['period_code'];?>">
                      <input  id="status" name="status" type="hidden" value="<?php echo $_REQUEST['status'];?>">
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

<?php if($show_consignment_e_invoice == 1)
{
?>
<!-- <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Consignment E - invoice</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body" id="consignment_sales_statement_child_body">

          <div class="col-md-12">
            <div id="embed_e_invoice_consign">
                <embed id="consignment_e_invoice_child_embed" height="750px" width="100%" src="<?php echo site_url('Consignment_report/consignment_e_invoice_view').'?report_type=consignment_e_invoice'.'&trans_guid='.$_REQUEST['trans'];?>"></embed>
            </div>

        </div>

        </div>

      </div>
    </div>
  </div> -->
<?php
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
                <embed id="consignment_sales_statement_child_embed" height="750px" width="100%" src="<?php echo site_url('Consignment_report/consignment_sales_statement_view').'?report_type=consignment_sales_statement_child'.'&trans_guid='.$_REQUEST['trans'];?>"></embed>
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
  // alert(date);
  $('input[name="consign_invoice_date"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
  if(date < '2000-01-01')
  {
    // alert();
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
  $(document).on('click','#header_save',function(){
    var trans_guid = $('#trans_guid').val();
    var b2b_inv_no = $('#b2b_inv_no').val();
    var consign_invoice_date = $('#consign_invoice_date').val();
    // alert(trans_guid+'--'+b2b_inv_no+'--'+consign_invoice_date);return;

    $.ajax({
      type: "POST",                
      url: "<?php echo site_url('Consignment_report/header_save_inv_no')?>", 
      data:{trans_guid:trans_guid,b2b_inv_no:b2b_inv_no,consign_invoice_date:consign_invoice_date},
      beforeSend : function() {
        // alert('before Send');

      },
      complete: function() {
        // alert('complete');

      },              
      success: function(data) {
        // alert(data);
        if(data == 1)
        {
          alert("Updated successfully.");

          var url = $('#consignment_e_invoice_child_embed').attr('src');
          // alert(url);die;
          $('#consignment_e_invoice_child_embed').attr('src', url);

          $('#consignment_e_invoice_child_link').attr('href', url);
          var clone = $('#embed_e_invoice_consign embed').clone(); 
          $('#embed_e_invoice_consign embed').remove(); 
          $('#embed_e_invoice_consign').append(clone)

        }
        else
        {
          alert("Update unsuccessful, Please try again.");
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


});

</script>