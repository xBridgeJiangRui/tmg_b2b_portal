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
         <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?> ">
          <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>
<!--         <a class="btn btn-app " style="color:#008D4C" href="<?php echo $confirmed ?>">
            <i class="fa fa-check-square"></i> View Confirmed GR
        </a>  -->
        <a class="btn btn-app pull-right"  style="color:#000000"  onclick="bulk_print()" >
            <i class="fa fa-print"></i> Print
        </a>
              <a class="btn btn-app pull-right" id="grn_bulk_print_einv"  style="color:#000000">
                    <i class="fa fa-file-pdf-o"></i> Bulk Print E-Inv
              </a>        
<!--         <a class="btn btn-app pull-right"  style="color:#000000"  onclick="bulk_accept()" >
            <i class="fa fa-object-group"></i> Bulk Confirm
        </a> -->


        <?php 
          if(in_array('BGEI',$_SESSION['module_code']))
          {
            if($_REQUEST['status'] != 'Invoice Generated') 
            {
            ?>
<!--               <a class="btn btn-app pull-right" id="grn_bulk_print_einv"  style="color:#000000">
                    <i class="fa fa-file-pdf-o"></i> Bulk Print E-Inv
              </a> -->
              <a class="btn btn-app pull-right" id="grn_bulk_generate_e_invoice"  style="color:#000000">
              <i class="fa fa-file-pdf-o"></i> Bulk Generate E-Inv
              </a>
            <?php
            }
          }
          ?>


        <?php if(in_array('CPON',$_SESSION['module_code'])) { ?>

        <button class="btn btn-app" data-toggle="modal" data-target="#pocheckpomodal"><i class="fa fa-file-text-o"></i>Check GRN Status</button>   

        <?php } ?> 
         
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
              <form role="form" method="POST" id="myForm" action="<?php echo site_url('general/po_filter');?>">
              <div class="col-md-2"><b>GR Ref No</b></div>
              <div class="col-md-4">
                 <input id="po_num" name="po_num" type="text" autocomplete="off" class="form-control pull-right">
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>GR Status</b></div>
              <div class="col-md-4">
                <select name="po_status" class="form-control">
                  <?php foreach($po_status->result() as $row){ ?>
                    <option value="<?php echo $row->code ?>" 
                      <?php if($_REQUEST['status'] == $row->code)
                      {
                        echo 'selected';
                      } 
                      ?>
                    > 
                    <?php echo $row->reason; ?></option>
                 <?php } ?>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>GR Date Range<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-4">
                <input required id="daterange" name="daterange" type="datetime" class="form-control pull-right" id="reservationtime" readonly>
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger"  onclick="date_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>E-INV Date From<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="expiry_from" name="expiry_from" type="datetime" value="" readonly class="form-control pull-right">
              </div>
              <div class="col-md-2"><b>E-INV Date To<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="expiry_to" name="expiry_to" type="datetime" class="form-control pull-right" readonly value="" onchange="CompareDate()">
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
              <div class="col-md-4">
                <select name="period_code" class="form-control">
                  <option value="">None</option>
                  <?php foreach($period_code->result() as $row){ ?>
                    <option value="<?php echo $row->period_code ?>" 
                      <?php if(isset($_SESSION['filter_period_code'])){
                      if($_SESSION['filter_period_code'] == $row->period_code)
                      {
                        echo 'selected';
                      } }
                      ?>
                    > 
                    <?php echo $row->period_code; ?></option>
                 <?php } ?>
                </select> 
              </div>

              <div class="col-md-12">
                <input type="hidden" name="current_location" class="form-control pull-right" value="<?php echo $_REQUEST['loc']; ?>">
                <input type="hidden" name="frommodule" class="form-control pull-right" value="<?php echo $_SESSION['frommodule'] ?>">
                
                <button type="submit" id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                <a href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a>

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
          <h3 class="box-title"><b>Goods Received</b></h3> &nbsp;

          <span class="pill_button"><?php 

          if ($_REQUEST['status'] == '') {
            $status = 'new';
          } else if ($_REQUEST['status'] == 'geinv') {
            $status = 'New - Viewed - Printed';
          } else {
            $status = $_REQUEST['status'];
          }


          echo ucfirst($status) ?></span>

          <span class="pill_button"><?php 

          if(in_array($check_loc, $hq_branch_code_array)) {
            echo 'All Outlet';
          } else {

            echo $_REQUEST['loc'];

          } ?>

          </span>

          <?php if ($_REQUEST['p_f'] != '' || $_REQUEST['p_t'] != '' ) { ?>

          <span class="pill_button"><?php 


          echo 'GR Date Range : '. $_REQUEST['p_f'].' <i class="fa fa-arrow-right" aria-hidden="true"></i> '.$_REQUEST['p_t'];  ?>
            

          </span>

          <?php } ?>

          <?php if ($_REQUEST['e_f'] != '' || $_REQUEST['e_t'] != '' ) { ?>

          <span class="pill_button"><?php 


          echo 'E-Inv Date Range : '.$_REQUEST['e_f'].' <i class="fa fa-arrow-right" aria-hidden="true"></i> '.$_REQUEST['e_t'];  ?>
            

          </span>

          <?php } ?>

          <?php 

          if(isset($_SESSION['filter_period_code']))
            {

          if ($_SESSION['filter_period_code'] != ''  ) { ?>

          <span class="pill_button"><?php 


          echo $_SESSION['filter_period_code'];  ?>
            

          </span>

          <?php } } ?>

          <?php if ($_REQUEST['r_n'] != '') { ?>

          <span class="pill_button"><?php 


          echo $_REQUEST['r_n'];  ?>
            

          </span>

          <?php } ?>

          <br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
      <div class="box-body">
      <div class="col-md-12">
        <br>
        <div>
            <div class="row">
                <div class="col-md-12"  style="overflow-x:auto">

                  <form role="form" method="POST" id="submit_batch_e_invoice_form" action="<?php echo site_url('Panda_gr/bulk_convert_e_invoice');?>">

                    <table id="paloi" class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                            <?php // var_dump($_SESSION); ?>
                                <th>GRN Refno</th>
                                <th>GRDA Status</th>
                                <th>Outlet</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>GRN Date</th>
                                <th>Supplier Inv/DO Date</th>
                                <th>Supplier <br>Inv No</th>
                                <th>E-Inv No</th>
                                <th>E-Inv Date</th>
                                <th>DO No</th>
                                <th>GRN Supplier Copy</th>
                                <th>Inv Amt</th>
                                <th>Tax</th>
                                <th>Total Inc Tax</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th><input type="checkbox" id="check-all"></th>
                            </tr>
                        </thead>
                    </table>
                  </form>
                </div>
            </div>
          </div>
             <!-- <p><a href="Panda_home/logout">Logout</a></p> -->
        </div> 
      </div>
    </div>
</div>
</div>
 
<?php //  echo var_dump($_SESSION); ?>
</div>
</div>

<div id="pocheckpomodal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">

  <div class="modal-dialog modal-sm">



    <!-- Modal content-->

    <div class="modal-content">

      <div class="modal-header">

        <h4 class="modal-title text-center">Check PO NO(By GRN No)</h4>

      </div>

      <div class="modal-body">

        <p><input type="text" id="grn_check_po_refno"  name="grn_check_po_refno" class="form-control" autofocus>

        <center><span style="font-weight: bolder;" id="grn_check_po_refno_result"></span></center>  </p>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload()">Close</button>

      </div>

    </div>



  </div>

</div>

<script>  

 $(document).ready(function(){ 
  $(document).on('paste', '#grn_check_po_refno', function(e) {
    e.preventDefault();
    var withoutSpaces = e.originalEvent.clipboardData.getData('Text');
    withoutSpaces = withoutSpaces.replace(/\s+/g, '');
    $(this).val(withoutSpaces);
  });

  $(document).on('keypress','#grn_check_po_refno',function(e) {
      if(e.which == 32) {
        event.preventDefault();
        return false;
      }//close function for click space
  });//close keypress funhction

      $('#grn_check_po_refno').keyup(function(){ 
           var grn_check_po_refno = $('#grn_check_po_refno').val();  
           if(grn_check_po_refno != '')  
           {  
              $.ajax({  
                     url:"<?php echo site_url('general/check_po_no'); ?>",  
                     method:"POST",  
                     dataType:"json",
                     data:{grn_check_po_refno:grn_check_po_refno},  
                     success:function(data)
                     {    
                     // alert(data.xmessage);                     
                         if(data.count == 0)
                          {
                            $('#grn_check_po_refno').css('border', '2px green solid');
                            $('#grn_check_po_refno_result').html(data.xmessage);   
                          } 
                          else if(data.count == 1)
                          {
                            $('#grn_check_po_refno').css('border', '2px green solid');
                            $('#grn_check_po_refno_result').html(data.xmessage);    
                          }                         
                          else
                          {
                            $('#grn_check_po_refno').css('border', '2px red solid');
                            $('#grn_check_po_refno_result').html(data.xmessage);                              
                          }          
                           
                     }  
                });  
           }  
      });



$(document).on('click','#grn_bulk_generate_e_invoice',function(){

  //alert('Sorry Under Maintenance'); die;
  
  var details = [];
  var invno_array = [];

  var table = $('#paloi').DataTable();
  var grda_check = 0; 
  var status_check = 0; 
  table.rows().nodes().to$().each(function(){
    
    if($(this).find('td').find('input[type="checkbox"]').is(':checked'))
    {
      details.push($(this).find('td').find('input[type="checkbox"]').val());
      grda = $(this).find('td').find('input[type="checkbox"]').attr('grda_status');
      status = $(this).find('td').find('input[type="checkbox"]').attr('doc_status');
      // alert($(this).find('td').find('input[type="checkbox"]').attr('grda_status'));
      // invno_array.push($(this).find('td').find('input[name="docno_value_array[]"]').val());
      if(grda != '' && grda != null && grda != 'null')
      {
        // alert(grda);
        grda_check = 1;
      }

      if(status == 'Invoice Generated' )
      {	
        // alert(grda);
        status_check = 1;
      }

      // invno_array.push({'refno':$(this).attr('refno'), 'invno':$(this).attr('grda_status')});
    }

    if($(this).find('td').find('input[type="checkbox"]').is(':checked'))
    {
      details.push($(this).find('td').find('input[type="checkbox"]').val());

      // invno_array.push($(this).find('td').find('input[name="docno_value_array[]"]').val());
      invno_array.push({'refno':$(this).attr('refno'), 'invno':$(this).attr('invno')});
    }

    

  });//close small loop
  // console.log(invno_array);
  // alert(grda_check);
  if(grda_check == 1)
  {
    alert("Cannot Select GRN with GRDA");
    return;
  }

  if(status_check == 1)
  {
    alert("Cannot Select Invoice Generated");
    return;
  }

  if((details == '') || (details == null))
  {
    alertmodal('Please select at least one record(s) to proceed.');
    return;
  }

  if((invno_array == '') || (invno_array == null))
  {
    alertmodal('Please select at least one record(s) to proceed.');
    return;
  }
  else if(invno_array.length > '5')
  {
    alertmodal('Please select only maximum 5 GRN to proceed.');
    return;
  }

  $.ajax({
            url:"<?php echo site_url('e_document/fetch_grmain_proposed');?>",
            method:"POST",
            dataType: 'json',
            data:{invno_array:invno_array},
            beforeSend:function(){
              // $('.btn').button('loading');
            },
            success:function(data)
            {
              console.log(data);
              // alert(data);
              // Object.keys(data).forEach(function(key) {
              //   alert(data[key]['refno']);
              // });


              var modal = $('#medium-modal').modal();
              var status = "<?php echo $_REQUEST['status'];?>";
              var loc = "<?php echo $_REQUEST['loc'];?>";
              modal.find('.modal-title').html('Edit Entry');

              methodd = '<div class="row">';

              methodd += '<div class="col-md-12" style="padding:0;">';


              Object.keys(data).forEach(function(key) {

                methodd += '<div class="col-md-6" style="padding-bottom:10px;"><label>GRN RefNo</label><input type="text" class="form-control" value="'+data[key]['refno']+'" readonly /></div> ';
                methodd += '<div class="col-md-6" style="padding-bottom:10px;"><label>Invoice No</label><input type="text" class="form-control loop_invno" refno="'+data[key]['refno']+'" value="'+data[key]['invno']+'" spellcheck="false" /></div> ';
                methodd += '<input type="hidden" name="status" value="'+status+'"/>';
                methodd += '<input type="hidden" name="loc" value="'+loc+'"/>';

              });


              methodd += '</div></div>';


              methodd_footer ='<p class="full-width"> ';

              methodd_footer += ' <span class="pull-left"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Cancel"> </span>';

              methodd_footer += '<span class="pull-right"> <input name="sendsumbit" type="button" id="save_invno_grn" class="btn btn-default" value="Save"></span>';

              methodd_footer += '</p>';

              modal.find('.modal-footer').html(methodd_footer);
              modal.find('.modal-body').html(methodd);

              $(document).off('click', '#save_invno_grn').on('click', '#save_invno_grn', function(){

                var new_invno = [];

                $(modal.find('.loop_invno')).each(function(){

                  new_invno.push({'refno':$(this).attr('refno'), 'invno':$(this).val()});

                });

                if((new_invno == '') || (new_invno == null))
                {
                  alertmodal('Error Occur. Please refresh page and try again.');
                  return;
                }

                $.ajax({
                      url:"<?= site_url('Panda_gr/update_invno');?>",
                      method:"POST",
                      data:{new_invno:new_invno},
                      beforeSend : function()
                      { 
                        $('.btn').button('loading');
                      },
                      complete : function()
                      { 
                      },
                      success:function(data)
                      { 
                        json = JSON.parse(data);

                        if (json.para1 == '1') {
                          informationalertmodal(json.button,json.icons,json.msg,'Error');
                          $('.btn').button('reset');
                        }//close if
                        else
                        { 

                          // $('#medium-modal').modal('hide');
                          informationalertmodal(json.button,json.icons,json.msg,'<?php echo $this->lang->line('alert_modal_title_information'); ?>');

                          setTimeout(function() {

                          confirmation_modal('Update Successfully. Are you sure to convert GRN(s) to E-Invoice?');

                            $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
                              $('#submit_batch_e_invoice_form').append('<input type="hidden" name="status" value="<?php echo $_REQUEST["status"];?>"> <input type="hidden" name="loc" value="<?php echo $_REQUEST["loc"];?>">');
                              $('#submit_batch_e_invoice_form').submit();
                              $('.btn').button('loading');
                            });//close confirmation yes

                            $('.btn').button('reset');
                          }, 1000);

                        }//close else

                      }//close succcess
                    });//close ajax


              });//close save inv_grn_no
              // location.reload();

            }//close success
          });//close aja

});//close grn_bulk_generate_e_invoice



$(document).on('change','#all_checkbox_grn_einv_bulk_print',function(){
    
    var table = $('#einv_main_bulk_print_table').DataTable();
    
    var value = $(this).is(':checked') ? 1 : 0;

    if(value == 1)
    {     
        // $(this).closest('table').find('tbody').find('tr').find('td').find('input').prop('checked',true);

        table.rows().nodes().to$().each(function(){

          $(this).find('td').find('input[type="checkbox"]').prop('checked',true)

        });//close small loop

    }
    else
    {     
        // $(this).closest('table').find('tbody').find('tr').find('td').find('input').prop('checked',false);

        table.rows().nodes().to$().each(function(){

          $(this).find('td').find('input[type="checkbox"]').prop('checked',false)

        });//close small loop
    }

}); 


$(document).on('click','#grn_bulk_print_einv',function(){

  var modal = $('#medium-modal').modal();

  modal.find('.modal-title').html('Edit Entry');

  methodd = '<div class="row">';

  methodd += '<div class="col-md-12">';

  methodd += '<form role="form" method="POST" id="submit_batch_print_e_invoice_form" action="<?php echo site_url('Panda_gr/bulk_print_e_invoice');?>">';

  methodd += '<table class="table table-bordered table-hover" id="einv_main_bulk_print_table">';

  methodd += '<thead><th>RefNo</th><th><input type="checkbox" id="all_checkbox_grn_einv_bulk_print"></th></thead>';

  methodd += '</table>';

  methodd += '</form>';

  methodd += '</div></div>';


  methodd_footer ='<p class="full-width"> ';

  methodd_footer += ' <span class="pull-left"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Cancel"> </span>';

  methodd_footer += '<span class="pull-right"> <input name="sendsumbit" type="button" id="bulk_print_e_invoice" class="btn btn-default" value="Print"></span>';

  methodd_footer += '</p>';

  modal.find('.modal-footer').html(methodd_footer);
  modal.find('.modal-body').html(methodd);

  setTimeout(function(){

    $.ajax({
          url:"<?php echo site_url('Panda_gr/einv_main_table');?>",
          method:"POST",
          beforeSend:function(){
            $('.btn').button('loading');
          },
          complete:function(){
            // $('.btn').button('reset');
          },
          success:function(data)
          {
            json = JSON.parse(data);

            //clear datatables for new ajax 
            if($.fn.DataTable.isDataTable('#einv_main_bulk_print_table')){
              $('#einv_main_bulk_print_table').DataTable().destroy();
            }

            $('#einv_main_bulk_print_table').DataTable({
              "columnDefs": [{ "orderable": false, "targets": 1 }],
              'processing'  : true,
              'paging'      : true,
              'lengthChange': true,
              'lengthMenu'  : [ [10, 25, 50, 1000], [10, 25, 50, 1000] ],
              'searching'   : true,
              'ordering'    : true,
              'order'       : [ [0 , 'asc'] ],
              'info'        : true,
              'autoWidth'   : false,
              "bPaginate": true, 
              "bFilter": true, 
              "sScrollY": "20vh", 
              "sScrollX": "100%", 
              "sScrollXInner": "100%", 
              "bScrollCollapse": true,
              data: json['einv_main'],
              columns : [
                {data:'refno'},
                {data:'refno',render: function ( data, type, row ) {

                  element = '<input type="checkbox" class="form-checkbox" name="bulk_print[]" value="'+data+'">';

                  return element;
                }},
              ],
              //'fixedHeader' : false,
              dom: '<"row"<"col-sm-6"l> <"col-sm-6" f> >rtip',
              "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                $(nRow).attr('einv_guid', aData['einv_guid']);
                $(nRow).attr('customer_guid', aData['customer_guid']);
                $(nRow).attr('refno', aData['refno']);
                $(nRow).attr('invno', aData['invno']);
                $(nRow).attr('dono', aData['dono']);
                $(nRow).attr('inv_date', aData['inv_date']);
                $(nRow).attr('gr_date', aData['gr_date']);
                $(nRow).attr('revision', aData['revision']);
                $(nRow).attr('total_excl_tax', aData['total_excl_tax']);
                $(nRow).attr('tax_amount', aData['tax_amount']);
                $(nRow).attr('total_incl_tax', aData['total_incl_tax']);
                $(nRow).attr('posted', aData['posted']);
                $(nRow).attr('posted_at', aData['posted_at']);
                $(nRow).attr('posted_by', aData['posted_by']);
                $(nRow).attr('converted', aData['converted']);
                $(nRow).attr('converted_at', aData['converted_at']);
                $(nRow).attr('created_at', aData['created_at']);
                $(nRow).attr('created_by', aData['created_by']);
                $(nRow).attr('updated_at', aData['updated_at']);
                $(nRow).attr('updated_by', aData['updated_by']);
                $(nRow).attr('einvno', aData['einvno']);

               },
               "initComplete": function( settings, json ) {
                  setTimeout(function(){
                    interval();
                    $('.btn').button('reset');
                  },300);
                }
            });//close datatable


          }//close success
        });//close ajax

  }, 300);//close timeout


  $(document).off('click', '#bulk_print_e_invoice').on('click', '#bulk_print_e_invoice', function(){

    var details = [];


    var table = $('#einv_main_bulk_print_table').DataTable();

    table.rows().nodes().to$().each(function(){

      if($(this).find('td').find('input[type="checkbox"]').is(':checked'))
      {
        details.push($(this).find('td').find('input[type="checkbox"]').val());
      }

    });

    if(details == '' || details == null)
    {
      alert('Please select an RefNo to proceed.');
      return;
    }

      confirmation_modal('Are you sure to Print E-Invoice?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      $('#submit_batch_print_e_invoice_form').submit();
      $('.btn').button('loading');
    });//close confirmation yes


  });//close bulk_print_einv


});//close grn_bulk_print_einv

  $(document).on('click','#view_po_data',function(){
      var gr_refno = $(this).attr('gr_refno');

      $.ajax({
        url:"<?php echo site_url('Panda_gr/view_po_grn') ?>",
        method:"POST",
        data:{gr_refno:gr_refno},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          var modal = $("#medium-modal").modal();

          modal.find('.modal-title').html('Purchase Order Details');

          methodd = '';

          methodd += '<table class="table table-bordered table-striped" id="view_po_tb" width="100%"><thead><th>PO Refno</th></thead></table>';

          methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

          modal.find('.modal-footer').html(methodd_footer);
          modal.find('.modal-body').html(methodd);
        
          setTimeout(function(){
            if ($.fn.DataTable.isDataTable('#view_po_tb')) {
              $('#view_po_tb').DataTable().destroy();
            }

            $('#view_po_tb').DataTable({
            "columnDefs": [

            ],
            'processing'  : true,
            'paging'      : false,
            'lengthChange': true,
            'lengthMenu'  : [ [10, 25, 50, 999999], [10, 25, 50, "ALL"] ],
            'searching'   : false,
            'ordering'    : false,
            'order'       : [ [0 , 'asc'] ],
            'info'      : true,
            'autoWidth'   : false,
            "bPaginate": false, 
            "bFilter": true, 
            "sScrollY": "60vh", 
            "sScrollX": "100%", 
            "sScrollXInner": "100%", 
            "bScrollCollapse": true,
              data: json['content'],
              columns: [
              
              { "data": "porefno"},
                    
              ],
              dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
              "language": {
                            "lengthMenu": "Show _MENU_",
                            "infoEmpty": "No records available",
                            "infoFiltered": "(filtered from _MAX_ total records)",
                            "zeroRecords": "<?php echo '<b>No Record Found. Please Contact Support.</b>'; ?>",
                    }, 
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              // $(nRow).closest('tr').css({"cursor": "pointer"});
              // $(nRow).attr('poex_guid', aData['poex_guid']);
              // $(nRow).attr('status', aData['status']);
            },
            "initComplete": function( settings, json ) {
              interval();
            },
            });//close datatable

          },300);
          $('.btn').button('reset');
        }//close success
      });//close ajax 
  });//close modal view po

});//close ready
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
            alert("Date To : "+dateTwo+" is Larger than Date From : "+dateOne+".");
            $('#search').attr('disabled','disabled');
        }
        else 
        {
           $('#search').removeAttr('disabled');
        }

    }
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
            url: "<?php echo site_url('general/merge_pdf?po_type=GRN&loc='.$_REQUEST['loc'])?>",
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
<script type="text/javascript">
  function bulk_accept()
  {
    var list_id = [];
    $(".data-check:checked").each(function() {
            list_id.push(this.value);
    });
    if(list_id.length > 0)
    {
        if(confirm('Are you sure bulk accept this '+list_id.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {id:list_id},
                url: "<?php echo site_url('general/ajax_bulk_accept?loc='.$_REQUEST['loc'])?>",
                dataType: "JSON",
               
               /* success: function(data)
                { 
                     alert('done.');
                   
                    
                },*/
                error: function (jqXHR, textStatus, errorThrown)
                {
                   // alert('Error Opening data');
                    alert('done');
                    window.location.reload();
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