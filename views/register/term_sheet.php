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
          <h3 class="box-title">Term Sheet Report Data</h3>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>
            <!-- <button id="upload_term" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Upload Docs</button> -->
            <?php
            }
            ?>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >

          <table id="term_tb" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead > <!--style="white-space: nowrap;"-->
            <tr>
                <th>Action</th>
                <th>Retailer Name</th>
                <th>Supplier Name</th>
                <th>Service Date</th>
                <th>Billing Start Date</th>
                <th>One Off Start Date</th>
                <th>One Off End Date</th>
                <th>One Off Price</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Updated By</th>
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
  $('#term_tb').DataTable({
    "columnDefs": [{"targets": 0 ,"orderable": false},
    { "width": "10%", "targets": 0 }
    ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [8 , 'DESC'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    // "sScrollY": "30vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Registration_upload_doc/term_sheet_table');?>",
        "type": "POST",
    },
    columns: [

             { "data": "data_guid" ,render:function( data, type, row ){

                var element = '';

                <?php
                if(in_array('IAVA',$this->session->userdata('module_code')))
                {
                ?>
                  element += '<button id="edit_btn" style="margin-left:5px;" title="Approve" class="btn btn-sm btn-info" data_guid="'+row['data_guid']+'" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" register_guid="'+row['register_guid']+'" acc_name="'+row['acc_name']+'" new_supplier_name="'+row['new_supplier_name']+'" service_date="'+row['service_date']+'" billing_start_date="'+row['billing_start_date']+'" one_off_start_date="'+row['one_off_start_date']+'" one_off_end_date="'+row['one_off_end_date']+'" one_off_price="'+row['one_off_price']+'"  checking_type="'+row['checking_type']+'"><i class="fa fa-edit"></i></button>';

                <?php
                }
                ?>

                element += '<button id="term_btn" style="margin-left:5px;" title="Content" class="btn btn-sm btn-success" register_guid="'+row['register_guid']+'" one_off_status="'+row['one_off_status']+'"  ><i class="fa fa-eye"></i></button>';
                

                return element;
       
              }},
             { "data": "acc_name" },
             { "data": "new_supplier_name" },
             { "data": "service_date" },
             { "data": "billing_start_date" },
             { "data": "one_off_start_date" },
             { "data": "one_off_end_date" },
             { "data": "one_off_price" },
             { "data": "created_at" },
             { "data": "updated_at" },
             { "data": "updated_by" },

             ],
    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      $(nRow).attr('guid', aData['guid']);
      
    },
    "initComplete": function( settings, json ) {
      interval();
    }
  });//close datatable

  $(document).on('click','#edit_btn',function()
  {
    var data_guid = $(this).attr('data_guid');
    var register_guid = $(this).attr('register_guid');
    var customer_guid = $(this).attr('customer_guid');
    var supplier_guid = $(this).attr('supplier_guid');
    var acc_name = $(this).attr('acc_name');
    var new_supplier_name = $(this).attr('new_supplier_name');
    var service_date = $(this).attr('service_date');
    var billing_start_date = $(this).attr('billing_start_date');
    var one_off_start_date = $(this).attr('one_off_start_date');
    var one_off_end_date = $(this).attr('one_off_end_date');
    var one_off_price = $(this).attr('one_off_price');
    var one_off_price = one_off_price.replace("<b>", "").replace("</b>","").replace("*","").replace("RM ","").replace(" only per annum","");
    var checking_type = $(this).attr('checking_type');
    //alert(url_data); die;

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Preview Term Sheet');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<input type="hidden" class="form-control input-sm" id="data_guid" value="'+data_guid+'" readonly/>';

    methodd += '<input type="hidden" class="form-control input-sm" id="customer_guid" value="'+customer_guid+'" readonly/>';

    methodd += '<input type="hidden" class="form-control input-sm" id="supplier_guid" value="'+supplier_guid+'" readonly/>';

    methodd += '<input type="hidden" class="form-control input-sm" id="register_guid" value="'+register_guid+'" readonly/>';

    methodd += '<div class="col-md-6"><label>Retailer Name</label><input type="text" class="form-control input-sm" id="acc_name" autocomplete="off" required="true" value="'+acc_name+'" readonly/></div>';

    methodd += '<div class="col-md-6"><label>Supplier Name</label><input type="text" class="form-control input-sm" id="new_supplier_name" autocomplete="off" required="true" value="'+new_supplier_name+'" readonly/></div>';

    methodd += '<div class="col-sm-6"><label>Service Date</label> <div class="input-group"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input name="service_date" id="service_date" type="text" class="datepicker form-control input-sm" value="<?php echo date('Y-m-d');?>" autocomplete="off" > </div> </div>';

    methodd += '<div class="col-sm-6"><label>Billing Start Date</label> <div class="input-group"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input name="billing_start_date" id="billing_start_date" type="text" class="datepicker form-control input-sm" value="<?php echo date('Y-m-d');?>" autocomplete="off" > </div> </div>';

    if(checking_type == '1')
    {
      methodd += '<div class="col-sm-6"><label>One Off Start Date</label> <div class="input-group"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input name="one_off_start_date" id="one_off_start_date" type="text" class="datepicker form-control input-sm" value="<?php echo date('Y-m-d');?>" autocomplete="off" > </div> </div>';

      methodd += '<div class="col-sm-6"><label>One Off End Date</label> <div class="input-group"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input name="one_off_end_date" id="one_off_end_date" type="text" class="datepicker form-control input-sm" value="<?php echo date('Y-m-d');?>" autocomplete="off" > </div> </div>';

      methodd += '<div class="col-md-6"><label>One Off Price</label><input type="email" class="form-control " id="one_off_price" autocomplete="off" required="true" value="'+one_off_price+'"/></div>';
    }

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"> <input name="sendsumbit" type="button" class="btn btn-default " data-dismiss="modal" value="Close"> </span><span class="pull-right"> <input name="update_btn" id="update_btn"  type="button" class="btn btn-success" value="Update"> </span> </p>';

    modal.find('.modal-body').html(methodd);
    modal.find('.modal-footer').html(methodd_footer);

    setTimeout(function(){
      $('#service_date').val(service_date);
      $('#billing_start_date').val(billing_start_date);
      $('#one_off_start_date').val(one_off_start_date);
      $('#one_off_end_date').val(one_off_end_date);
      $('.datepicker').datepicker({
        forceParse: false,
        autoclose: true,
        format: 'yyyy-mm-dd'
      });

      $('#one_off_start_date').change(function(){
        var type_val = $('#one_off_start_date').val();
        var year = new Date(type_val).getFullYear();
        var month = new Date(type_val).getMonth();
        var day = new Date(type_val).getDate();
        var new_type_val = new Date(year + 1, month, day - 1).toISOString().slice(0,10);;
        $('#one_off_end_date').val(new_type_val);
        //alert(new_type_val); die;
      });//close selection
    },300);

  });

  $(document).on('click','#update_btn',function(){
    
    var data_guid = $('#data_guid').val();
    var customer_guid = $('#customer_guid').val();
    var supplier_guid = $('#supplier_guid').val();
    var register_guid = $('#register_guid').val();
    var service_date = $('#service_date').val();
    var billing_start_date = $('#billing_start_date').val();
    var one_off_start_date = $('#one_off_start_date').val();
    var one_off_end_date = $('#one_off_end_date').val();
    var one_off_price = $('#one_off_price').val();

    //alert(setting_guid); die;
    if((data_guid == '') || (data_guid == 'null') || (data_guid == null))
    {
      alert('Invalid Data. Please Refresh Page');
      return;
    }

    if((customer_guid == '') || (customer_guid == 'null') || (customer_guid == null))
    {
      alert('Invalid Data. Please Refresh Page');
      return;
    }

    if((supplier_guid == '') || (supplier_guid == 'null') || (supplier_guid == null))
    {
      alert('Invalid Data. Please Refresh Page');
      return;
    }

    if((register_guid == '') || (register_guid == 'null') || (register_guid == null))
    {
      alert('Invalid Data. Please Refresh Page');
      return;
    }

    if((service_date == '') || (service_date == 'null') || (service_date == null))
    {
      alert('Please insert Service Date.');
      return;
    }

    if((billing_start_date == '') || (billing_start_date == 'null') || (billing_start_date == null))
    {
      alert('Please insert Billing Start Date');
      return;
    }

    //alert(rejected); die;
    confirmation_modal("Are you sure want to Update?");
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Registration_upload_doc/edit_term_data');?>",
        method:"POST",
        data:{data_guid:data_guid,customer_guid:customer_guid,supplier_guid:supplier_guid,register_guid:register_guid,service_date:service_date,billing_start_date:billing_start_date,one_off_start_date:one_off_start_date,one_off_end_date:one_off_end_date,one_off_price:one_off_price},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('#alertmodal').modal('hide');
            alert(json.msg);
            setTimeout(function() {
              location.reload();
            }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });

  $(document).on('click','#term_btn',function(){
    var register_guid = $(this).attr('register_guid');
    var one_off_status = $(this).attr('one_off_status');
    //alert(register_guid); die;

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Preview Term Sheet');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<embed src="<?php echo site_url('Invoice/view_report_term?reg_guid=');?>'+register_guid+'&form_type=normal" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';

    if(one_off_status == '1')
    {
      methodd += '<embed src="<?php echo site_url('Invoice/view_term_special?reg_guid=');?>'+register_guid+'&form_type=special" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';
    }
  
    methodd += '</div>';

    methodd_footer = '<p class="full-width"> <span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default " data-dismiss="modal" value="Close"> </span> </p>';

    modal.find('.modal-body').html(methodd);
    setTimeout(function () { 
      modal.find('.modal-footer').html(methodd_footer);
    }, 1500);

  });

});
</script>
