<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="container-fluid">
    <br>
    <?php
    if ($this->session->userdata('message')) {
    ?>
      <div class="alert alert-success text-center" style="font-size: 18px">
        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
        <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
      </div>
    <?php
    }
    ?>

    <?php
    if ($this->session->userdata('warning')) {
    ?>
      <div class="alert alert-danger text-center" style="font-size: 18px">
        <?php echo $this->session->userdata('warning') <> '' ? $this->session->userdata('warning') : ''; ?>
        <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
      </div>
    <?php
    }
    ?>

    <div class="col-md-12">
        <a class="btn btn-app" href="<?php echo site_url('b2b_gr/gr_list') ?>">
            <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location') ?>">
            <i class="fa fa-bank"></i> Outlet
        </a>
        <!-- <?php if(in_array('CPON',$_SESSION['module_code'])) { ?>
            <button class="btn btn-app" data-toggle="modal" data-target="#pocheckpomodal"><i class="fa fa-file-text-o"></i>Check GRN Status</button>   
        <?php } ?>  -->

        <a class="btn btn-app" style="color:#fa7f0c" onclick="filter_status(4)" title="New" >
          <i class="fa fa-file-o"></i> Unread  
          <!-- <small> (New) </small>  <small> (Viewed & Printed) </small> -->
        </a>
        <a class="btn btn-app" style="color:#5693f5" onclick="filter_status(3)" title="Viewed & Printed">
          <i class="fa fa-file-word-o"></i> Read  
        </a>
        <a class="btn btn-app" style="color:#a320f5" onclick="filter_status(5)" title="Viewed & Printed">
          <i class="fa fa-list"></i> All 
        </a>

        <a class="btn btn-app pull-right"  style="color:#000000"  onclick="bulk_print()" >
            <i class="fa fa-print"></i> Print
        </a>
        <a class="btn btn-app pull-right" id="grn_bulk_print_einv"  style="color:#000000">
            <i class="fa fa-file-pdf-o"></i> Bulk Print E-Inv
        </a> 
        <?php 
          if(in_array('BGEI',$_SESSION['module_code']))
          {
            ?>
              <a class="btn btn-app pull-right" id="grn_bulk_generate_e_invoice"  style="color:#000000">
              <i class="fa fa-file-pdf-o"></i> Bulk Generate E-Inv
              </a>
            <?php
          }
        ?>
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
                <div class="col-md-2"><b>PO Ref No</b></div>
                <div class="col-md-4">
                  <input id="po_num" name="po_num" type="text" autocomplete="off" class="form-control pull-right">
                </div>

                <div class="clearfix"></div><br>
                <div class="col-md-2"><b>GR Ref No</b></div>
                <div class="col-md-4">
                  <input id="gr_num" name="gr_num" type="text" autocomplete="off" class="form-control pull-right">
                </div>
                <div class="clearfix"></div><br>

                <div class="col-md-2"><b>GR Status</b></div>
                <div class="col-md-4">
                  <select id="po_status" name="po_status" class="form-control">
                  <?php foreach ($po_status->result() as $row) { ?>
                      <option value="<?php echo $row->code ?>" <?php if (strtolower($_REQUEST['status']) == strtolower($row->code)) {
                                                                  echo 'selected';
                                                                }
                                                                ?>>
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
                  <a class="btn btn-danger" onclick="date_clear()">Clear</a>
                </div>
                <div class="clearfix"></div><br>

                <!-- <div class="col-md-2"><b>Doc Date From<br>(YYYY-MM-DD)</b></div>
                <div class="col-md-2">
                  <input id="expiry_from" name="expiry_from" type="datetime" value="" readonly class="form-control pull-right">
                </div>
                <div class="col-md-2"><b>Doc Date To<br>(YYYY-MM-DD)</b></div>
                <div class="col-md-2">
                  <input id="expiry_to" name="expiry_to" type="datetime" class="form-control pull-right" readonly value="" onchange="CompareDate()">
                </div>
                <div class="col-md-2">
                  <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
                </div>
                <div class="clearfix"></div><br>

                <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
                <div class="col-md-4">
                  <select id="period_code" name="period_code" class="form-control">
                    <option value="">None</option>
                    <?php foreach ($period_code->result() as $row) { ?>
                      <option value="<?php echo $row->period_code ?>" <?php if (isset($_SESSION['filter_period_code'])) {
                          if ($_SESSION['filter_period_code'] == $row->period_code) {
                            echo 'selected';
                          }
                        }
                        ?>>
                        <?php echo $row->period_code; ?></option>
                    <?php } ?>
                  </select> 
                </div>

                <div class="clearfix"></div><br> -->

                <div class="col-md-12">
                  <button id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                  <button id="reset" class="btn btn-default"><i class="fa fa-repeat"></i> Reset</button>
                </div>
                <!--Bulk print form here -->
                <form target="_blank" action="<?php echo site_url('general/merge_jasper_pdf') ?>" id="bulk_print_form" method="post">
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

            <span class="pill_button" id="status_tag">
              
            <?php 

              if ($_REQUEST['status'] == '') {
                $status = 'new';
              } else if ($_REQUEST['status'] == 'geinv') {
                $status = 'New - Viewed - Printed';
              } else {
                $status = $_REQUEST['status'];
              }


              echo ucfirst($status) ?></span>

            <span class="pill_button" id="outlet_tag">
              <?php

              if (in_array($check_loc, $hq_branch_code_array)) {
                echo 'All Outlet';
              } else {

                echo $location_description->row('BRANCH_CODE') . ' - ' . $location_description->row('branch_desc');
              } ?>

            </span>

            <span class="pill_button hidden" id="po_date_tag">

            </span>

            <span class="pill_button hidden" id="exp_date_tag">

            </span>

            <span class="pill_button hidden" id="period_code_tag">

            </span>

            <span class="pill_button hidden" id="ref_no_tag">

            </span>

            <span class="pill_button hidden" id="po_ref_no_tag">

            </span>


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
                  <div class="col-md-12" style="overflow-x:auto">
                  <form role="form" method="POST" id="submit_batch_e_invoice_form" target="_blank" action="<?php echo site_url('B2b_gr/bulk_convert_e_invoice');?>" >
                    <table id="table_list" class="table table-bordered table-hover">
                      <!-- <form id="formPO" method="post" action="<?php echo site_url('general/prints') ?>"> -->
                        <thead>
                          <tr>
                            <th>GRN Refno</th>
                            <th>GRDA Status</th>
                            <th>PO Refno</th>
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
                      </form>
                    </table>

                  </div>
                </div>

                <!-- Modal -->
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
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
  function date_clear() {
    $(function() {
      $(this).find('[name="daterange"]').val("");
    });
  }

  function expiry_clear() {
    $(function() {
      $(this).find('[name="expiry_from"]').val("");
      $(this).find('[name="expiry_to"]').val("");
      $('#search').removeAttr('disabled');
    });
  }
</script>

<script type="text/javascript">
  function CompareDate() {
    var dateOne = $('input[name="expiry_from"]').val(); //Year, Month, Date
    var dateTwo = $('input[name="expiry_to"]').val(); //Year, Month, Date
    if (dateOne > dateTwo) {
      alert("Expiry To : " + dateTwo + " Cannot Be a date before " + dateOne + ".");
      $('#search').attr('disabled', 'disabled');
    } else {
      $('#search').removeAttr('disabled');
    }

  }
</script>

<script type="text/javascript">
  function bulk_print() {
    var list_id = [];
    $(".data-check:checked").each(function() {
      list_id.push(this.value);
    });
    if (list_id.length > 0) {
      var form = document.getElementById("bulk_print_form");
      var element1 = document.createElement("input"); 
      var element2 = document.createElement("input");  
      element1.setAttribute("type", "hidden");
      element2.setAttribute("type", "hidden");
      
      element1.value=list_id;
      element1.name="id";
      form.appendChild(element1);  

      element2.value="GRN";
      element2.name="type";
      form.appendChild(element2);

      document.body.appendChild(form);
      $('#bulk_print_form').submit();
    } else {
      alert('No data selected');
    }
  }
</script>

<script>
  $(document).ready(function() {
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

        var details = [];
        var invno_array = [];

        var table = $('#table_list').DataTable();
        var grda_check = 0; 
        var status_check = 0; 
        table.rows().nodes().to$().each(function(){
        
        if($(this).find('td').find('input[type="checkbox"]').is(':checked'))
        {
            details.push($(this).find('td').find('input[type="checkbox"]').val());
            refno = $(this).find('td').find('input[type="checkbox"]').attr('refno');
            invno = $(this).find('td').find('input[type="checkbox"]').attr('invno');
            grda = $(this).find('td').find('input[type="checkbox"]').attr('grda_status');
            status = $(this).find('td').find('input[type="checkbox"]').attr('doc_status');
            // alert($(this).find('td').find('input[type="checkbox"]').attr('grda_status'));
            // invno_array.push($(this).find('td').find('input[name="docno_value_array[]"]').val());
            if(grda != '' && grda != null && grda != 'null')
            {
              grda_check = 1;

            }
            if(status == 'Invoice Generated' )
            {	
              status_check = 1;
            }

            invno_array.push({'refno':refno, 'invno':invno});
        }

        // if($(this).find('td').find('input[type="checkbox"]').is(':checked'))
        // {
        //     //details.push($(this).find('td').find('input[type="checkbox"]').val());

        //     // invno_array.push($(this).find('td').find('input[name="docno_value_array[]"]').val());
            
        // }
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


        if((invno_array == '') || (invno_array == null) || (invno_array == 'null'))
        {
        alertmodal('Please select at least one record(s) to proceed.');
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
                    //var status = "<?php echo $_REQUEST[''];?>";
                    var loc = "<?php echo $_REQUEST['gr_loc'];?>";
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
                            url:"<?= site_url('B2b_gr/update_invno');?>",
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
                                    $('#submit_batch_e_invoice_form').append('<input type="text" id="details" name="details" value="'+details+'"> ');
                                    //console.log(details); 
                                    $('#submit_batch_e_invoice_form').submit();
                                    //$('.btn').button('loading');
                                    setTimeout(function() {
                                      location.reload();
                                    }, 300);
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

        methodd += '<form role="form" method="POST" id="submit_batch_print_e_invoice_form" target="_blank" action="<?php echo site_url('B2b_gr/bulk_print_e_invoice');?>">';

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
            url:"<?php echo site_url('B2b_gr/einv_main_table');?>",
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
                'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
                'searching'   : true,
                'ordering'    : true,
                'order'       : [ [0 , 'asc'] ],
                'info'        : true,
                'autoWidth'   : false,
                "bPaginate": true, 
                "bFilter": true, 
                "sScrollY": "12vh", 
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
                setTimeout(function() {
                  location.reload();
                }, 300);
            });//close confirmation yes


        });//close bulk_print_einv
    });//close grn_bulk_print_einv
  });
</script>
<script>
  var po_ref_no = '';
  var ref_no = '';
  var status = '';
  var datefrom = '';
  var dateto = '';
  var exp_datefrom = '';
  var exp_dateto = '';
  var period_code = '';
  var loc = '';

  $(document).ready(function() {
    main_table = function(po_ref_no, ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc) {

      if ($.fn.DataTable.isDataTable('#table_list')) {
        $('#table_list').DataTable().destroy();
      }

      var table;

      table = $('#table_list').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "order": [
          [0, "desc"]
        ],
        "columnDefs": [{
            "targets": [9, 10, 11],
            "className": "alignright",
          },
          {
            "targets": [17, 18], //first column
            "orderable": false, //set not orderable
          }
        ],
        "ajax": {
          "url": "<?php echo site_url('B2b_gr/gr_datatable') ?>",
          "type": "POST",
          "data": function(data) {
            data.po_ref_no = po_ref_no
            data.ref_no = ref_no
            data.status = status
            data.datefrom = datefrom
            data.dateto = dateto
            data.exp_datefrom = exp_datefrom
            data.exp_dateto = exp_dateto
            data.period_code = period_code
            data.loc = loc
            data.type = 'gr'
          },
        },
        "columns": [
            { "data": "refno" },
            { "data": "grda_status" },
            { "data": "porefno" },
            { "data": "loc_group" },
            { "data": "supplier_code" },
            { "data": "supplier_name" },
            { "data": "grdate" },
            { "data": "docdate" },
            { "data": "dono" },
            { "data": "einvno" },
            { "data": "einvdate" },
            { "data": "invno" },
            { "data": "cross_ref" },
            { "data": "total" , render:function( data, type, row ){
              var element = '';
              <?php
              if(in_array('HBTN',$_SESSION['module_code']))
              {
                ?>
                  element += '';
                <?php
              }
              else
              {
                ?>
                element += data;
                <?php
              }
              ?>
              return element;

              }},
            { "data": "gst_tax_sum" , render:function( data, type, row ){

              var element = '';
              <?php
              if(in_array('HBTN',$_SESSION['module_code']))
              {
                ?>
                  element += '';
                <?php
              }
              else
              {
                ?>
                element += data;
                <?php
              }
              ?>
              return element;

              }},
            { "data": "total_include_tax" , render:function( data, type, row ){

              var element = '';
              <?php
              if(in_array('HBTN',$_SESSION['module_code']))
              {
                ?>
                  element += '';
                <?php
              }
              else
              {
                ?>
                element += data;
                <?php
              }
              ?>
              return element;

              }},
            { "data": "status" },
            { "data": "button" , render:function( data, type, row ){

              var element = '';
              <?php
              if(in_array('HBTN',$_SESSION['module_code']))
              {
                ?>
                  element += '';
                <?php
              }
              else
              {
                ?>
                element += data;
                <?php
              }
              ?>
              return element;

              }},
            { "data": "box" , render:function( data, type, row ){

              var element = '';
              <?php
              if(in_array('HBTN',$_SESSION['module_code']))
              {
                ?>
                  element += '';
                <?php
              }
              else
              {
                ?>
                element += data;
                <?php
              }
              ?>
              return element;

            }},
        ],
        //dom: 'lBfrtip',
        dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
        buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
        ]

      });
    }

    main_table(po_ref_no, ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);

  });

  $('#search').click(function() {

    po_ref_no = $('#po_num').val();
    ref_no = $('#gr_num').val();
    status = $('#po_status').val();
    daterange = $('#daterange').val();
    period_code = $('#period_code').val();
    daterange = daterange.split(" - ");
    datefrom = daterange[0];
    dateto = daterange[1];
    exp_datefrom = $('#expiry_from').val();
    exp_dateto = $('#expiry_to').val();
    loc = "<?php echo $_SESSION['gr_loc']; ?>";

    if (po_ref_no != '') {
      $('#po_ref_no_tag').removeClass("hidden").html(po_ref_no);
    } else {
      $('#po_ref_no_tag').addClass("hidden").html('');
    }

    if (ref_no != '') {
      $('#ref_no_tag').removeClass("hidden").html(ref_no);
    } else {
      $('#ref_no_tag').addClass("hidden").html('');
    }

    if (status != '') {
      if(status == 'gr_completed'){
        status = 'GRN Completed';
        $('#status_tag').removeClass("hidden").html(status[0].toUpperCase() + status.substring(1));
      } else {
        $('#status_tag').removeClass("hidden").html(status[0].toUpperCase() + status.substring(1));
      }
    }
    else{
      $('#status_tag').removeClass("hidden").html('NEW');
    }

    if (daterange != '') {
      $('#po_date_tag').removeClass("hidden").html('GR Date Range : ' + datefrom + ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' + dateto);
    } else {
      $('#po_date_tag').addClass("hidden").html('');
    }

    // if (exp_datefrom != '' && exp_dateto != '') {
    //   $('#exp_date_tag').removeClass("hidden").html('Expired Date Range : ' + exp_datefrom + ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' + exp_dateto);
    // } else {
    //   $('#exp_date_tag').addClass("hidden").html('');
    // }

    // if (period_code != '') {
    //   $('#period_code_tag').removeClass("hidden").html(period_code);
    // } else {
    //   $('#period_code_tag').addClass("hidden").html('');
    // }

    main_table(po_ref_no, ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);

  })

  $('#reset').click(function() {

    po_ref_no = '';
    ref_no = '';
    status = '';
    datefrom = '';
    dateto = '';
    exp_datefrom = '';
    exp_dateto = '';
    period_code = '';
    loc = '';

    $('#po_num').val('');
    $('#gr_num').val('');
    $('#po_status').val('');
    $('#daterange').val('');
    $('#period_code').val('');
    $('#expiry_from').val('');
    $('#expiry_to').val('');

    $('#status_tag').html('New');
    $('#po_date_tag').addClass("hidden").html('');
    $('#exp_date_tag').addClass("hidden").html('');
    $('#period_code_tag').addClass("hidden").html('');
    $('#ref_no_tag').addClass("hidden").html('');
    $('#po_ref_no_tag').addClass("hidden").html('');


    main_table(po_ref_no, ref_no, status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);

  })

  function filter_status(data){
    if(data == '1'){
      new_status = 'accepted';
      $('#po_status').val('accepted');
    }else if(data == '2'){
      new_status = 'rejected';
      $('#po_status').val('rejected');
    }else if(data == '3'){
      new_status = 'READ';
      $('#po_status').val('READ');
      $('#status_tag').removeClass("hidden").html('Read');
    }else if(data == '4'){
      new_status = '';
      $('#po_status').val('');
      $('#status_tag').removeClass("hidden").html('Unread');
    }else if(data == '5'){
      new_status = 'ALL';
      $('#po_status').val('ALL');
      $('#status_tag').removeClass("hidden").html('All');
    } else {
      new_status = '';
      $('#po_status').val('');
    }

    main_table(po_ref_no, ref_no, new_status, datefrom, dateto, exp_datefrom, exp_dateto, period_code, loc);
  }
</script>