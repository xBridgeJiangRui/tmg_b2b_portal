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
    <div class="col-md-12" >
      <?php if(in_array('IAVA',$this->session->userdata('module_code')))
      {
        foreach($get_new_status->result() as $key)
        {
          ?>
          <a class="btn btn-app" <?php if($customer_guid == $key->acc_guid){ ?> style="background-color:#4da6ff;font-weight: bold;" <?php }?>>
            <span class="badge bg-red" style="font-size: 16px">
              <?php echo $key->numbering ?> 
            </span>
            <i class="fa fa-address-card-o" ></i> 
            <span style="font-size: 12px;color:black;"> <?php echo $key->acc_name ?> </span>
          </a> 
          <?php
        }
      }
      ?>
    </div>
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Supplier Mapping Code</h3>
          <div class="box-tools pull-right">
          <?php if(in_array('IAVA',$this->session->userdata('module_code')))
          {
            ?>
            <button id="active_btn" style="margin-left:5px;" title="Active" class="btn btn-xs btn-primary modal_btn" ><i class="fa fa-edit"></i> Set Status</button>
            <button id="sync_btn" style="margin-left:5px;" title="Sync" class="btn btn-xs btn-danger" ><i class="fa fa-edit"></i> Sync Mapping Code</button>
            <?php
          }
          ?>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >

          <table id="mapping_tb" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead > <!--style="white-space: nowrap;"-->
            <tr>
                <!-- <th>Action</th> -->
                <th>
                  <input type="checkbox" id="checkall_input_table" name="checkall_input_table" table_id="mapping_tb">
                </th> 
                <th>Retailer</th>
                <th>Supplier Name</th>
                <th>Reg No</th>
                <th>Vendor Code</th>
                <th>Status</th>
                <th>Backend Type</th>
                <th>Created At</th>
                <th>Created By</th>

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
  $('#mapping_tb').DataTable({
    "columnDefs": [{"targets": 0 ,"orderable": false},
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
        "url": "<?php echo site_url('Supplier_setup_vendor/mapping_table');?>",
        "type": "POST",
    },
    columns: [

             { "data": "guid" ,render:function( data, type, row ){

                var element = '';
                <?php

                if(in_array('IAVA',$this->session->userdata('module_code')))
                {
                ?>
                    element += '<input type="checkbox" class="form-checkbox" name="flag_checkbox" id="flag_checkbox" guid ="'+row['guid']+'" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" vendor_code="'+row['vendor_code']+'"/>';

                <?php
                }
                ?>
                return element;
       
              }},
             { "data": "acc_name" },
             { "data": "supplier_name" },
             { "data": "supplier_reg_no" },
             { "data": "vendor_code" },
             { "data": "pending"},
             { "data": "backend_type"},
             { "data": "created_at" },
             { "data": "created_by" },

             ],
    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      $(nRow).attr('guid', aData['guid']);

      if(aData['pending'] == 1 )
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#80ffaa","color":"black"});
        $(nRow).find('td:eq(1)').css({"background-color":"#80ffaa","color":"black"});
        $(nRow).find('td:eq(2)').css({"background-color":"#80ffaa","color":"black"});
        $(nRow).find('td:eq(3)').css({"background-color":"#80ffaa","color":"black"});
        $(nRow).find('td:eq(4)').css({"background-color":"#80ffaa","color":"black"});
        $(nRow).find('td:eq(5)').css({"background-color":"#80ffaa","color":"black"});
        $(nRow).find('td:eq(6)').css({"background-color":"#80ffaa","color":"black"});
        $(nRow).find('td:eq(7)').css({"background-color":"#80ffaa","color":"black"});
        $(nRow).find('td:eq(8)').css({"background-color":"#80ffaa","color":"black"});
          
      }

      
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

  $(document).on('click', '.modal_btn', function(event){

    var table = $('#mapping_tb').DataTable();
    var details_checking = [];
    
    table.rows().nodes().to$().each(function(){
      if($(this).find('td').find('#flag_checkbox').is(':checked'))
      {
        guid = $(this).find('td').find('#flag_checkbox').attr('guid');

        details_checking.push({'guid':guid});
      }
    });//close small loop

    if(details_checking == '' || details_checking == 'null' || details_checking == null)
    {
      alert('Please Select Checkbox.');
      return;
    }

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Edit Mapping Status');

    methodd = '';

    methodd += '<div class="col-md-12"><label>Status</label> <select class="form-control select2" name="modal_flag_status" id="modal_flag_status"> <option value="" disabled selected>-SELECTION-</option> <option value="1" >Active</option> <option value="0" >Deactive</option> </select> <br/></div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="flag_btn_submit" class="btn btn-primary"> Update </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
        $('.select2').select2();
    },300);

  });//close update button

  $(document).on('click', '#flag_btn_submit', function(event){

    var pending_status = $('#modal_flag_status').val();
    var table = $('#mapping_tb').DataTable();
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
        guid = $(this).find('td').find('#flag_checkbox').attr('guid');
        customer_guid = $(this).find('td').find('#flag_checkbox').attr('customer_guid');
        supplier_guid = $(this).find('td').find('#flag_checkbox').attr('supplier_guid');
        vendor_code = $(this).find('td').find('#flag_checkbox').attr('vendor_code');

        details.push({'guid':guid,'customer_guid':customer_guid,'supplier_guid':supplier_guid,'vendor_code':vendor_code,'status':pending_status});
      }
    });//close small loop

    if(details == '' || details == 'null' || details == null)
    {
      alert('Please Select Checkbox.');
      return;
    }
    //console.log($details); die;
    confirmation_modal("Are you sure want to Update to "+modal+"?");

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Supplier_setup_vendor/update_status') ?>",
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

  $(document).on('click', '#sync_btn', function(event){

    confirmation_modal("<span class='blinker' style='font-size:24px;color:red;font-weight:bold;'> Warning! </span> <br> Are you sure want to Sync To Mapping?");

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Supplier_setup_vendor/mapping_data_update') ?>",
            method:"POST",
            data:{},
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
