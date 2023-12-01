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

.blinker {
  animation: blink-animation 5s steps(10, start) infinite;
  -webkit-animation: blink-animation 1s steps(10, start) infinite;
  background-color: yellow;
  font-weight: bold;
  font-size:24px;
  color:black;
}

@keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
@-webkit-keyframes blink-animation {
  to {
    visibility: hidden;
  }
}

</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Auto Generate EINV/ECN List</h3>
          <div class="box-tools pull-right">
          <?php if(in_array('IAVA',$this->session->userdata('module_code')))
          {
            ?>
            <button id="create_btn" style="margin-left:5px;" title="Create" class="btn btn-xs btn-primary modal_btn" ><i class="fa fa-edit"></i> Add New </button>
            <!-- <button id="remove_btn" style="margin-left:5px;" title="Remove" class="btn btn-xs btn-danger" ><i class="fa fa-edit"></i> Remove Code </button> -->
            <?php
          }
          ?>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >

          <table id="table1" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead > <!--style="white-space: nowrap;"-->
            <tr>
                <!-- <th>Action</th> -->
                <th>
                  <input type="checkbox" id="checkall_input_table" name="checkall_input_table" table_id="table1">
                </th> 
                <th>Action</th>
                <th>Retailer</th>
                <th>Supplier Name</th>
                <th>Supplier Code</th>
                <th>Days</th>
                <th>Created At</th>
                <th>Created By</th>
                <th>Status</th>

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
  $('#table1').DataTable({
    "columnDefs": [{"targets": [0,1],"orderable": false},
    ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [7 , 'DESC'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    // "sScrollY": "60vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    // "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Auto_settings/doc_list_table');?>",
        "type": "POST",
    },
    columns: [
                { "data": "customer_guid" ,render:function( data, type, row ){

                    var element = '';
                    <?php

                    if(in_array('IAVA',$this->session->userdata('module_code')))
                    {
                    ?>
                        element += '<input type="checkbox" class="form-checkbox" name="flag_checkbox" id="flag_checkbox" guid ="'+row['guid']+'" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" supplier_code="'+row['supplier_code']+'"/>';

                    <?php
                    }
                    ?>
                    return element;
        
                }},
                { "data": "supplier_guid" ,render:function( data, type, row ){

                var element = '';
                <?php

                if(in_array('IAVA',$this->session->userdata('module_code')))
                {
                ?>
                    element += '<button id="edit_data_btn" type="button" style="margin-left:2px;margin-top:2px;" title="EDIT" class="btn btn-xs btn-info" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" acc_name="'+row['acc_name']+'" supplier_name="'+row['supplier_name']+'" supplier_name="'+row['supplier_name']+'" auto_days="'+row['auto_days']+'" is_active="'+row['is_active']+'" ><i class="fa fa-edit"></i></button>';

                <?php
                }
                ?>
                return element;

                }},
                { "data": "acc_anme" },
                { "data": "supplier_name" },
                { "data": "supplier_code" },
                { "data": "auto_days" },
                { "data": "created_by" },
                { "data": "created_at" },
                { "data": "is_active" ,render:function( data, type, row ){

                    var element = '';

                    if(data == '1')
                    {
                        element = 'Active';
                    }
                    else
                    {
                        element = 'Inactive';
                    }

                    return element;
                }},

             ],
    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      $(nRow).attr('guid', aData['guid']);

    //   if(aData['pending'] == 1 )
    //   {
    //     $(nRow).find('td:eq(0)').css({"background-color":"#80ffaa","color":"black"});
    //     $(nRow).find('td:eq(1)').css({"background-color":"#80ffaa","color":"black"});
    //     $(nRow).find('td:eq(2)').css({"background-color":"#80ffaa","color":"black"});
    //     $(nRow).find('td:eq(3)').css({"background-color":"#80ffaa","color":"black"});
    //     $(nRow).find('td:eq(4)').css({"background-color":"#80ffaa","color":"black"});
    //     $(nRow).find('td:eq(5)').css({"background-color":"#80ffaa","color":"black"});
    //     $(nRow).find('td:eq(6)').css({"background-color":"#80ffaa","color":"black"});
    //     $(nRow).find('td:eq(7)').css({"background-color":"#80ffaa","color":"black"});
    //     $(nRow).find('td:eq(8)').css({"background-color":"#80ffaa","color":"black"});
          
    //   }

      
    },
    "initComplete": function( settings, json ) {
      interval();
    }
  });//close datatable

  $(document).on('change','#checkall_input_table',function(){
    var id = $(this).attr('table_id');

    var table = $('#'+id).DataTable();

    if($(this).is(':checked'))
    {
        table.rows().nodes().to$().each(function(){

            $(this).find('td').find('#flag_checkbox').prop('checked',true)

        });//close small loop
    }
    else
    {
        table.rows().nodes().to$().each(function(){

            $(this).find('td').find('#flag_checkbox').prop('checked',false)

        });//close small loop
    }//close else

  });//close checkbox all set_group_table

  $(document).on('click', '#create_btn', function(event){

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('New Setting Days');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><label>Retailer Name</label> <select class="form-control select2" name="add_acc_name" id="add_acc_name"> <option value="">-Select-</option> <?php foreach($acc_data as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo $row->acc_name?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label> <select class="form-control select2" name="add_supplier_name" id="add_supplier_name"> <option value="">-Select-</option> </select> </div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label> <select class="form-control select2" name="add_supplier_code" id="add_supplier_code" multiple="multiple"> </div>';

    methodd += '<div class="col-md-12"><label>Duration Days</label> <input type="text" class="form-control " id="add_days" name="add_days" autocomplete="off" /> </div>';

    methodd += '<div class="col-md-12"><label>Status</label> <select class="form-control select2" name="add_status" id="add_status"> <option value="">-Select-</option> <option value="1">Active</option> <option value="0">Inactive</option></select> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="create_button" class="btn btn-primary"> Create </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
        $('.select2').select2();

        $('#add_acc_name').change(function(){

            var type_val = $('#add_acc_name').val();

            if(type_val != '')
            {
                $.ajax({
                url : "<?php echo site_url('Auto_settings/fetch_supplier'); ?>",
                method:"POST",
                data:{type_val:type_val},
                success:function(result)
                {

                    json = JSON.parse(result); 

                    supplier = '';

                    Object.keys(json['supplier']).forEach(function(key) {

                        supplier += '<option value="'+json['supplier'][key]['supplier_guid']+'">'+json['supplier'][key]['supplier_name']+'</option>';

                    });
                    $('#add_supplier_name').html(supplier);
                }
                });
            }
            else
            {
                $('#add_supplier_name').select2().html('<option value="" disabled>Please select the retailer</option>');
            }
        
        });//close selection

        $('#add_supplier_name').change(function(){

            var type_val_acc = $('#add_acc_name').val();
            var type_val_supp = $('#add_supplier_name').val();

            if(type_val_acc != '' && type_val_supp != '')
            {
                $.ajax({
                url : "<?php echo site_url('Auto_settings/fetch_supplier_code'); ?>",
                method:"POST",
                data:{type_val_acc:type_val_acc,type_val_supp:type_val_supp },
                success:function(result)
                {

                    json = JSON.parse(result); 

                    code = '';

                    Object.keys(json['code']).forEach(function(key) {

                        code += '<option value="'+json['code'][key]['supplier_code']+'">'+json['code'][key]['supplier_code']+'</option>';

                    });
                    $('#add_supplier_code').html(code);
                }
                });
            }
            else
            {
                $('#add_supplier_code').select2().html('<option value="" disabled>Please select the retailer & supplier</option>');
            }

        });//close selection

    },300);

  });//close update button

  $(document).on('click','#create_button',function(){
    var add_acc_name = $('#add_acc_name').val();
    var add_supplier_name = $('#add_supplier_name').val();
    var add_supplier_code = $('#add_supplier_code').val();
    var add_days = $('#add_days').val();
    var add_status = $('#add_status').val();

    if((add_acc_name == '') || (add_acc_name == null) || (add_acc_name == 'null'))
    {
      alert('Retailer Name must have value.');
      return
    }//close checking for posted table_ss

    if((add_supplier_name == '') || (add_supplier_name == null) || (add_supplier_name == 'null'))
    {
      alert('Supplier Name must have value.');
      return
    }//close checking for posted table_ss

    if((add_supplier_code == '') || (add_supplier_code == null) || (add_supplier_code == 'null') || (add_supplier_code == []) )
    {
      alert('Invalid Supplier Code.');
      return
    }//close checking for posted table_ss

    if((add_days == '') || (add_days == null) || (add_days == 'null'))
    {
      alert('Please Insert Days Duration.');
      return
    }//close checking for posted table_ss

    if(add_status == '' || add_status == 'null' || add_status == null)
    {
        alert('Please Select Status.');
        return
    }

    confirmation_modal('Are you sure want to Create New Setting?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
            url:"<?php echo site_url('Auto_settings/add_new_setting');?>",
            method:"POST",
            data:{add_acc_name:add_acc_name,add_supplier_name:add_supplier_name,add_supplier_code:add_supplier_code,add_days:add_days,add_status:add_status},
            beforeSend:function(){
              $('.btn').button('loading');
            },
            success:function(data)
            {
              json = JSON.parse(data);
              if (json.para1 == '1') {
                alert(json.msg.replace(/\\n/g,"\n"));
                $('.btn').button('reset');
                location.reload();
              }else{
                alert(json.msg.replace(/\\n/g,"\n"));
                // setTimeout(function() {
                $('.btn').button('reset');
                location.reload();
                // }, 300);
              }//close else
            }//close success
      });//close ajax
    });//close document yes click
  });//close edit

  $(document).on('click', '#remove_btn', function(event){

    var pending_status = $('#modal_flag_status').val();
    var table = $('#table1').DataTable();
    var details = [];
    var modal = '';

    if(pending_status == '1')
    {
        modal = 'Active';
    }
    else if(pending_status == '0')
    {
        modal = 'Deactive';
    }
    else
    {
        alert('Please Select Status.')
        return;
    }
    
    table.rows().nodes().to$().each(function(){
      if($(this).find('td').find('#flag_checkbox').is(':checked'))
      {
        customer_guid = $(this).find('td').find('#flag_checkbox').attr('customer_guid');
        supplier_guid = $(this).find('td').find('#flag_checkbox').attr('supplier_guid');
        supplier_code = $(this).find('td').find('#flag_checkbox').attr('supplier_code');

        details.push({'customer_guid':customer_guid,'supplier_guid':supplier_guid,'supplier_code':supplier_code});
      }
    });//close small loop

    if(details == '' || details == 'null' || details == null)
    {
      alert('Please Select Checkbox.');
      return;
    }
    //console.log($details); die;
    confirmation_modal("Are you sure want to Remove to "+modal+"?");

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Auto_settings/remove_code') ?>",
            method:"POST",
            data:{details:details},
            beforeSend:function(){
            $('.btn').button('loading');
            },
            success:function(data)
            {
            json = JSON.parse(data);
            if (json.para1 == 'false') {
                $('#alertmodal').modal('hide');
                $('.btn').button('reset');
                alert(json.msg);
                location.reload();
            }else{
                $('.btn').button('reset');
                $('#alertmodal').modal('hide');
                alert(json.msg);
                location.reload();
            }//close else
            }//close success
        });//close ajax 
    });//close document yes click
  });//close update button


});
</script>
