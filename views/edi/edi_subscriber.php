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

.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: black;
}
</style>
<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">EDI Subscriber List</h3><br>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>

            <button id="create_edi" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Create EDI</button>

            <?php
            }
            ?>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
          </div>
        </div>
          <div class="box-body">
            <table class="table table-bordered table-striped dataTable" id="edi_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;">
                <tr>
                  <th>Action</th>
                  <th>Retailer Name</th> 
                  <th>Supplier Name</th>
                  <th>Code</th>
                  <th>Doc Type</th>
                  <th>File</th>
                  <th>Status</th>
                  <th>Created At</th>
                  <th>Created By</th>
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
$(document).ready(function () {    
  $('#edi_table').DataTable({
    "columnDefs": [
    {"targets": 0 ,"orderable": false},
    ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : true,
    'ordering'    : true, 
    'order'       : [  [6 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "fixedColumns": true,
    "sScrollY": "50vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Edi_setup/edit_main_tb');?>",
        "type": "POST",
    },
    columns: [
            { "data" : "guid" ,render:function( data, type, row ){

              var element = '';
              var element1 = row['status'];
              var title = '';
              var color = '';
              var icon = '';

              if(element1 == 'Completed')
              {
                title = 'VIEW';
                color = 'btn-info';
                icon = 'fa fa-edit';
              }
              else
              {
                title = 'SETUP';
                color = 'btn-danger';
                icon = 'fa fa-edit';
              }

              <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
              {
                ?>
                  
                element += '<button id="view_btn" type="button"  title="'+title+'" class="btn btn-sm '+color+'" guid="'+row['guid']+'" ><i class="'+icon+'"></i></button>';

                <?php
              }
              ?>

              return element;
            }},
            { "data" : "acc_name" },
            { "data" : "supplier_name" },
            { "data" : "supplier_code" },
            { "data" : "doc_type" },
            { "data" : "issend" },
            { "data" : "status" },
            { "data" : "created_at" },
            { "data" : "created_name" },
            { "data" : "updated_at" },
            { "data" : "updated_name" },
          ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'Brtip',
    buttons: [
      { extend: 'excelHtml5',
        exportOptions: {columns: [1,2,3,4,5,6]}},

      { extend: 'csvHtml5',  
        exportOptions: {columns: [1,2,3,4,5,6]}},
        ],
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('final_amount', aData['final_amount']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $(document).on('click','#create_edi',function(){

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('New EDI Subscriber');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><label>Retailer Name</label> <select class="form-control select2 get_code" name="new_retailer" id="new_retailer"> <option value="" disabled selected>-Select Retailer Name-</option>  <?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label> <select class="form-control select2 get_code" name="new_supplier" id="new_supplier"> <option value="" disabled selected>-Select Supplier Name-</option>  <?php foreach($get_supplier as $row) { ?> <option value="<?php echo $row->supplier_guid?>"><?php echo addslashes($row->supplier_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Supplier Code</label> <select class="form-control select2" name="new_code" id="new_code"> <option value="" disabled selected>-Select Retailer & Supplier Name-</option>  </select> </div>';

    methodd += '<div class="col-md-12"><label>Doc Type</label> <select class="form-control select2" name="new_doctype" id="new_doctype"> <option value="" disabled selected>-Select Document Type-</option> <option value="PO">Purchase Order (PO)</option> <option value="GRN">Goods Received Note (GRN)</option> <option value="GRDA">Goods Received Diff Advice (GRDA)</option> <option value="PRDN">Purchase Return DN (PRDN)</option> <option value="PRCN"> Purchase Return CN (PRCN)</option> <option value="PDN">Purchase DN (PDN)</option> <option value="PCN">Purchase CN (PCN)</option> <option value="PCI">Promotion Claim Tax Invoice (PCI)</option> <option value="DI">Display Incentive (DI)</option> </select> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="submit_button" class="btn btn-primary"> Create </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('.select2').select2();

      $('.get_code').change(function(){
        var val_acc = $('#new_retailer').val();
        var val_sup = $('#new_supplier').val();

        if((val_acc != '') && (val_sup != ''))
        {
           $.ajax({
           url : "<?php echo site_url('Edi_setup/fetch_sup_code'); ?>",
           method:"POST",
           data:{val_acc:val_acc,val_sup:val_sup},
           success:function(result)
           {
              json = JSON.parse(result); 

              code = '';

              Object.keys(json['Code']).forEach(function(key) {

                code += '<option value="'+json['Code'][key]['supplier_group_name']+'">'+json['Code'][key]['supplier_group_name']+'</option>';

              });
              $('#new_code').select2().html(code);
           }
          });
        }
        else
        {
          $('#new_code').select2().html('<option value="" disabled>Please select the retailer and supplier</option>');
        }
      });//close selection
    },300);

  });//close modal create

  $(document).on('click','#submit_button',function(){
    var new_retailer = $('#new_retailer').val();
    var new_supplier = $('#new_supplier').val();
    var new_doctype = $('#new_doctype').val();
    var new_code = $('#new_code').val();

    if((new_retailer == '') || (new_retailer == null) || (new_retailer == 'null'))
    {
      alert('Invalid Retailer Name.');
      return;
    }

    if((new_supplier == '') || (new_supplier == null) || (new_supplier == 'null'))
    {
      alert('Invalid Supplier Name.');
      return;
    }

    if((new_code == '') || (new_code == null) || (new_code == 'null'))
    {
      alert('Invalid Supplier Code.');
      return;
    }

    if((new_doctype == '') || (new_doctype == null) || (new_doctype == 'null'))
    {
      alert('Invalid Document Type.');
      return;
    }

    confirmation_modal('Are you sure to proceed New Subscriber?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Edi_setup/edi_sub_new') ?>",
        method:"POST",
        data:{new_retailer:new_retailer,new_supplier:new_supplier,new_code:new_code,new_doctype:new_doctype},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == '1') {
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            alert(json.msg);
            window.location = "<?= site_url('Edi_setup/tab_one?link=');?>"+json.link;
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation

  });//close submit process
  
  $(document).on('click','#view_btn',function(){
    var tab_guid = $(this).attr('guid');
    window.location = "<?= site_url('Edi_setup/tab_one?link=');?>"+tab_guid;
  });//close modal create

  $(document).on('click', '#location_all', function(){
    // alert();
    $("#dup_new_code option").prop('selected',true);
    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('click', '#location_all_dis', function(){
    // alert();
    $("#dup_new_code option").prop('selected',false);
    $(".select2").select2();
  });//CLOSE ONCLICK  

});
</script>

