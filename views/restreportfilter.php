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
              <form role="form" method="POST" id="myForm" action="<?php echo site_url('Restreport/read_report');?>">

              <div class="col-md-2"><b>Company</b><span style="color:red"> *<span></div>
              <div class="col-md-4">
                <select id="company" name="company" class="form-control" required>
                  <option value="">Select Company</option><!-- <option value="<?php if(isset($company)){ echo $company; }else{echo "Select Company";}?>"><?php if(isset($company_name)){ echo $company_name; }else{echo "Select Company";}?></option>  -->
                  <?php
                  foreach($details->result() as $row)
                  { 
                    echo '<option value="'.$row->acc_guid.'">'.$row->acc_name.'</option>';
                  }
                  ?>
                </select>
              </div>
              <div class="clearfix"></div><br>
                
              <div class="col-md-2"><b>Report Type</b><span style="color:red"> *<span></div>
              <div class="col-md-4">
                 <select id="report_type" name="report_type" class="form-control" required>
                  <option value="">Select Report Type</option>
                  <!-- <option value="<?php if(isset($sreport_type)){ echo $sreport_type; }else{echo "";}?>"><?php if(isset($sreport_type_description)){ echo $sreport_type_description; }else{echo "Select Report Type";}?></option> -->
                  <?php
                  foreach($report_type->result() as $row)
                  { 
                    echo '<option value="'.$row->code.'">'.$row->description.'</option>';
                  }
                  ?>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>RefNo</b></div>
              <div class="col-md-4">
                  <input class="form-control" name="RefNo" type="text">
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Supplier Name</b></div>
              <div class="col-md-4">
                <select id="supplier_name" name="supplier_name[]" class="select2 form-control" multiple>
                  <option value="">Select Company</option>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Location</b></div>
              <div class="col-md-4">
                 <select id="location" name="location[]" class="select2 form-control" multiple>
                  <option value="">Select Company</option>
                </select>
              </div>
              <div class="clearfix"></div><br>              

              <div class="col-md-2"><b>Date</b></div>
              <div class="col-md-4">
                <label><span>Start Date</span></label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name="start_date" autocomplete="off" id="start_date" type="text" class="datepicker form-control input-sm"  >
                </div>
              </div>
              <div id="cend_date" class="col-md-4">
                <label><span>End Date</span></label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name="end_date" autocomplete="off" id="end_date" type="text" class="datepicker form-control input-sm"  >
                </div>
              </div>
              <div class="clearfix"></div><br>              


              <div class="col-md-12">
                
                <button type="submit" id="submit_report" class="btn btn-primary"><i class="fa fa-search"></i> Check</button>
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
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
          <h3 class="box-title"><?php if(isset($sreport_type_description)){ echo $sreport_type_description; }else{echo "No Report Type Selected";}?> <?php if(isset($company_name)){ echo '-<b>'.$company_name.'</b>'; }else{echo "";}?></option> <?php if(isset($ssupplier_name_description)){ echo '-'.$ssupplier_name_description; }else{echo "";}?></h3><br>
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
                    <table id="dyntable" class="table table-bordered table-hover" >
                      <form id="formPO" method="post" action="<?php echo site_url('general/prints')?>">
                        <thead>
                            <tr>
                                <!--Begin=Column Header-->
                                <?php echo $tablecolumnhead;?>
<!--                                 <th>Code</th>
                                <th>Name</th>
                                <th>Po Date</th>
                                <th>Expiry Date</th>
                                <th>Amount</th>
                                <th>Tax</th>
                                <th>Total Incl Tax</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th><input type="checkbox" id="check-all"></th> -->
                                <!--End=Column Header-->
                            </tr>
                        </thead>
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload()">Close</button>
      </div>
    </div>

  </div>
</div>


<script>  
$(document).ready(function(){  

$('.datepicker').datepicker({
      forceParse: false,
      autoclose: true,
      format: 'yyyy-mm-dd'
});
// $('.select2').select2();
// $('#location').select2();
// $('#cend_date').hide();
// xcompany = $('#company').val();
// // alert(xcompany);
// $.ajax({
//     url:"<?php echo base_url(); ?>index.php/Restreport/fetch_supplier",
//     method:"POST",
//     data:{company:xcompany},
//     success:function(data){
//       $('#supplier_name').html(data);
//       // alert(data);
//     }
// });

$(document).on('change', '#company', function(){
         company = $(this).val();
         // alert(company);
         $.ajax({
              url:"<?php echo base_url(); ?>index.php/Restreport/fetch_supplier",
              method:"POST",
              data:{company:company},
              success:function(data){
                $('#supplier_name').html(data);
                // alert(data);
              }
         });  
});      

$(document).on('change', '#company', function(){
         company = $(this).val();
         // alert(company);
         $.ajax({
              url:"<?php echo base_url(); ?>index.php/Restreport/fetch_location",
              method:"POST",
              data:{company:company},
              success:function(data){
                $('#location').html(data);
                $('#location').select2();
                // alert(data);
              }
         });  
});

$(document).on('change', '#start_date,#end_date', function(){
  start_date = $('#start_date').val();
  end_date = $('#end_date').val();

  var id = $(this).attr('id');

  if((start_date == '') || (start_date == null) ||(end_date == '') || (end_date == null))
  {
  }
  else
  {
    if(start_date > end_date)
    { 
      if(id == 'start_date')
      {
        alert('Start Date cannot later than End Date');
        start_date = $(this).val('');
      }
      else if(id == 'end_date')
      {
        alert('End Date cannot earlier than Start Date');
        end_date = $(this).val('');
      }
      
    }
  }
});


$(document).on('click', '#submit_report', function(){
  start_date = $('#start_date').val();
  end_date = $('#end_date').val();

  var id = $(this).attr('id');

    if((end_date == '') || (end_date == null))
    { 
    }
    else
    {
      if((start_date == '') || (start_date == null))
      {
        alert('Start Date cannot be empty');
        $('#end_date').val('');
        return false;
      }
    }
});
                
// alert();


  <?php if(($datatable == '') || ($columns == '')){ ?>

    $('#dyntable').DataTable( {
        responsive: {
            details: false
        }
    });

  <?php
  }
  else
  { 
  ?>

    $('#dyntable').DataTable({
    "buttons": [{extend: 'excelHtml5',exportOptions: { orthogonal: 'export' }},/*'excel',  'print'*/],
    "columnDefs": [{ "orderable": false, "targets": 0 }],
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [1 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : true,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "70vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    data:<?php echo $datatable;?>,
    columns: <?php echo $columns;?>,
    //'fixedHeader' : false,
    dom:'<"row"<"col-sm-2" l><"col-sm-4" B><"col-sm-6" f>>rtip',
    //dom: "<'row'<'col-sm-9'l>" + "<'col-sm-3'f>>" +'rtip'

    });

  <?php } ?>


});
</script>
