<style>
.content-wrapper{
  min-height: 700px !important; 
}

.btn-app:hover
{
  background: #99ccff;
  font-weight: bold;
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
          <h3 class="box-title">User Account Creation Form</h3>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>
            <button type="button" class="btn btn-xs btn-warning" id="send_batch_btn"><i class="glyphicon glyphicon-send" aria-hidden="true"></i>&nbsp&nbspSend</button>

            <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-plus" aria-hidden="true"></i>&nbsp&nbspCreate</button>

            <?php
            }
            ?>

              <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
              
                  <!-- Modal content-->
                  <form action="<?php echo site_url('Registration_new/transaction_vendor') ?>" method="post"><div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Registration Transaction</h4>
                    </div>
                    <div class="modal-body">
                     
                   <div class="form-group col-md-12">
                  
                    <label for="exampleInputEmail1">Retailer Name <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" id="acc_name" name="acc_name"  aria-describedby="emailHelp"
                         value="<?php echo $retailer ?>" readonly>
                       
                  </div>
                  <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Supplier Name <span class="text-danger">*</span> </label>
                     <select class="form-control get_supp_value" name="comp_name">
                          <option value="">-Select-</option>
                          <?php foreach ($supplier as $key) { ?>
                            <option value="<?php echo $key->supplier_guid ?>"><?php echo $key->supplier_name?></option>
                          <?php } ?>
                          </select>
              
                  </div>
                    <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Reg No <span class="text-danger">*</span> </label>
                    <span id="append_reg_no"><input type="text" class="form-control" id="comp_no" name="comp_no"  aria-describedby="emailHelp" placeholder="Please select the supplier" readonly required></span>
            
                  </div>
                  <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Vendor Code<span class="text-danger">*</span> </label>
                    <select class="select2 form-control" id="acc_no" name="acc_no[]" multiple="multiple" style="width:100%;">
                      <option value=''>-Please select the supplier-</option>
                    </select>

                  </div>
                    <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Company Email<span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="comp_email" name="comp_email"  aria-describedby="emailHelp" placeholder="Vendor Code" required="true">
              
                  </div>

                  <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Memo Type<span class="text-danger">*</span> </label>
                    <select class="form-control" name="memo_type" id="memo_type">
                    <option value="">-Select-</option>
                    <option value="outright">Outright</option>
                    <option value="consignment">Consignment</option>
                    <option value="both">Outright & Consignment</option>
                    <option value="69A20CEDB78311EBAFC1000D3AA2838A">Outright 450</option>
                    </select>
              
                  </div>

                  <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Form Merge<span class="text-danger">*</span> </label>
                    <select class="form-control" name="form_merge" id="form_merge">
                    <option value="">-Select-</option>
                    <option value="1">Merge</option>
                    <option value="0">No Merge</option>
                    </select>
              
                  </div>

                    </div>
                    <div class="modal-footer">
                       <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      <button type="submit"  class="btn btn-success" >Save</button>
                    </div>
                  </div></form>
              
                </div>
              </div>

          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
          <div id="">
          
                  <table id="register" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead style="white-space: nowrap;">
                    <tr>
                        <th>
                           <input type="checkbox" id="checkall_input_table" name="checkall_input_table" table_id="register">
                        </th> 
                        <th>Action</th>
                        <th>Form No</th>
                        <th>Supplier Name</th>
                        <th>Retailer Name</th>
                        <th>Memo Type</th>
                        <th>Received by</th>
                        <th>Vendor Code</th>
                        <th>No.of Register</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Created by</th>
                        <th>Updated at</th>
                        <th>Updated by</th>
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
</div>
<script>
$(document).ready(function() {
    $('#register').DataTable({
          // "columnDefs": [ {"targets": 2 ,"visible": false}],
          "columnDefs": [{ "orderable": false, "targets": [0,1]},
          <?php if(in_array('IAVA',$this->session->userdata('module_code')))
          {
            ?>
            { visible: true, targets: [0,5,6,9,10,11,12,13]}
            <?php
          }
          else
          {
            ?>
            { visible: false, targets: [0,5,6,9,10,11,12,13]}
            <?php
          }
          ?>],
          "serverSide": true, 
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [12, 'asc'] ],
          'info'        : true,
          'autoWidth'   : false,
          "bPaginate": true, 
          "bFilter": true, 
          "sScrollY": "100%", 
          "sScrollX": "100%", 
          "sScrollXInner": "100%", 
          "bScrollCollapse": true,
          "ajax": {
              "url": "<?php echo site_url('Registration_new/register_vendor_table');?>",
              "type": "POST",
          },
          columns: [
                    { "data": "register_guid" , render:function( data, type, row ){

                      var element = '';
                      var element1 = row['form_status'];

                      <?php if(in_array('IAVA',$this->session->userdata('module_code')))
                      {
                      ?>
                        if((element1 == '') || (element1 == 'null') || (element1 == null))
                        {
                          element += '<input type="checkbox" class="form-checkbox" name="send_check_box" id="send_check_box" register_guid ="'+row['register_guid']+'"/>';
                        }
                      <?php
                      }
                      ?>

                      return element;

                    }},
                    { "data": "action" },
                    { "data": "register_no" },
                    { "data": "supplier_name" },
                    { "data": "acc_name" },
                    { "data": "memo_type" , render:function( data, type, row ){

                      var element = '';
                      var element1 = row['form_status'];

                      if(data == 'outright')
                      {
                        element = 'Outright';
                      }
                      else if(data == 'consignment')
                      {
                        element = 'Consignment';
                      }
                      else if(data == 'both')
                      {
                        element = 'Outright and Consignment';
                      }
                      else if(data == '69A20CEDB78311EBAFC1000D3AA2838A')
                      {
                        element = 'Outright 450';
                      }                
                      else
                      {
                        element = '';
                      }

                      return element;

                    }},
                    { "data": "comp_email" },
                    { "data": "acc_no" },
                    { "data": "cnt" },
                    { "data": "form_status" },
                    { "data": "create_at" },
                    { "data": "create_by" },
                    { "data": "update_at" },
                    { "data": "update_by" },

                   ],
          dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
          // "pagingType": "simple",
          "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('register_guid', aData['register_guid']);
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
              ?>
              if(aData['form_status'] == 'New' )
              {
                $(nRow).find('td:eq(0)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(1)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(2)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(3)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(4)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(5)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(6)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(7)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(8)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(9)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(10)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(11)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(12)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(13)').css({"background-color":"#ffff33","color":"black"});
              }
            <?php
            }
            ?>
          },
          "initComplete": function( settings, json ) {
            interval();
          }
        });//close datatable

        $('div.dataTables_filter input').off('keyup.DT input.DT');

        var searchDelay = null;
           
        $(document).off('keyup','div.dataTables_filter input').on('keyup','div.dataTables_filter input', function(e) {
            var search = $(this).val();
            if (e.keyCode == 13) {
              var id = $(this).attr('aria-controls');
              $('#'+id).DataTable().search(search).draw();
            }//close keycode
        });

      $('.get_supp_value').change(function(){

       var type_val = $('.get_supp_value').val();

       if(type_val != '')
       {
          $.ajax({
          url : "<?php echo site_url('Registration_new/fetch_reg_no'); ?>",
          method:"POST",
          data:{type_val:type_val},
          success:function(result)
          {

           json = JSON.parse(result); 

              code = '';

              Object.keys(json['Code']).forEach(function(key) {

                code += '<input type="text" class="form-control" id="comp_no" name="comp_no"  aria-describedby="emailHelp" value="'+json['Code'][key]['reg_no']+'" required readonly>';

              });
           $('#append_reg_no').html(code);
          }
         });
       }
       else
       {
          $('#append_reg_no').html('<input type="text" class="form-control" id="comp_no" name="comp_no"  aria-describedby="emailHelp" placeholder="Please select the supplier" readonly required>');
       }
          
      });//close selection

      $('.get_supp_value').change(function(){

       var type_val = $('.get_supp_value').val();

       if(type_val != '')
       {
          $.ajax({
          url : "<?php echo site_url('Registration_new/fetch_reg_no'); ?>",
          method:"POST",
          data:{type_val:type_val},
          success:function(result)
          {

           json = JSON.parse(result); 

              vendor = '';

              Object.keys(json['vendor']).forEach(function(key) {

                vendor += '<option value="'+json['vendor'][key]['vendor_code']+'">'+json['vendor'][key]['vendor_code']+' - '+json['vendor'][key]['name']+'</option>';

              });
           $('#acc_no').select2().html(vendor);

          }
         });
       }
       else
       {
          $('#acc_no').select2().html('<option value="" disabled >Please select the supplier</option>');
       }
          
      });//close selection

  $(document).on('click','#btn_edit_form',function(){

    var register_guid = $(this).attr('register_guid');
    var register_no = $(this).attr('register_no');
    var supplier_name = $(this).attr('supplier_name');
    var acc_name = $(this).attr('acc_name');
    var comp_email = $(this).attr('comp_email');
    var edit_acc_no = $(this).attr('edit_acc_no');
    var edit_form_merge = $(this).attr('form_merge');
    var acc_no_array = edit_acc_no.split(",");
    var memo_type = $(this).attr('memo_type');
    var edit_form_status = $(this).attr('form_status');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Edit Online Registration Application');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control input-sm" id="edit_reg_guid" value="'+register_guid+'" /></div>';

    methodd += '<div class="col-md-12"><label>Reg No</label><input type="text" class="form-control input-sm" id="edit_reg_no" value="'+register_no+'" readonly/></div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label><input type="text" class="form-control input-sm" id="edit_supp_name" value="'+supplier_name+'" readonly/></div>';

    methodd += '<div class="col-md-12"><label>Retailer Name</label><input type="text" class="form-control input-sm" id="edit_acc_name" value="'+acc_name+'" readonly/></div>';

    methodd += '<div class="col-md-12"><label>Received by</label><input type="email" class="form-control input-sm" id="edit_email" value="'+comp_email+'" required/></div>';

    methodd += '<div class="col-md-12"><label>Vendor Code</label> <select class="select2 form-control" id="edit_acc_no" name="edit_acc_no" multiple="multiple" ></select> </div>';

    methodd += '<div class="col-md-12"><label>Memo Type</label> <select class="form-control" name="edit_memo_type" id="edit_memo_type"> <option value="">-Select-</option> <option value="outright" >Outright</option> <option value="consignment">Consignment</option> <option value="both">Outright & Consignment</option> <option value="69A20CEDB78311EBAFC1000D3AA2838A">Outright 450</option></select> </div>';

    methodd += '<div class="col-md-12"><label>Form Merge</label> <select class="form-control" name="edit_form_merge" id="edit_form_merge"> <option value="">-Select-</option> <option value="1" >Merge</option> <option value="0">No Merge</option></select> </div>';

    methodd += '<div class="col-md-12"><label>Form Status</label> <select class="form-control" name="edit_form_status" id="edit_form_status"> <option value="">-Select-</option> <option value="Advance" >Advance</option> <option value="Registered" > Registered </option> </select> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="btn_update" class="btn btn-success" value="Update"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function() {
       $('#edit_memo_type').val(memo_type); 
       $('#edit_form_merge').val(edit_form_merge); 
       $('#edit_form_status').val(edit_form_status); 
       var type_val = supplier_name;
       
       if(type_val != '')
       {
          $.ajax({
          url : "<?php echo site_url('Registration_new/fetch_reg_no'); ?>",
          method:"POST",
          data:{type_val:type_val,acc_no_array:acc_no_array},
          success:function(result)
          {
           json = JSON.parse(result); 
              vendor = '';
              Object.keys(json['vendor']).forEach(function(key) {
                if(json['vendor'][key]['selected'] == '1')
                { 
                  selected = 'selected';
                }
                else
                {
                  selected = '';
                }
                vendor += '<option value="'+json['vendor'][key]['vendor_code']+'" '+selected+' >'+json['vendor'][key]['vendor_code']+' - '+json['vendor'][key]['name']+'</option>';
              });
           $('#edit_acc_no').select2().html(vendor);
           
          }
         });
       }
       else
       {
          $('#edit_acc_no').select2().html('<option value="" disabled>Please select the supplier</option>');
       }
    }, 300);

  }); // close

  $(document).on('click','#btn_update',function(){
    var edit_reg_guid = $('#edit_reg_guid').val();
    var edit_email = $('#edit_email').val();
    var edit_acc_no = $('#edit_acc_no').val();
    var edit_memo_type = $('#edit_memo_type').val();
    var edit_form_merge = $('#edit_form_merge').val();
    var edit_form_status = $('#edit_form_status').val();

    if((edit_reg_guid == '') || (edit_reg_guid == null) || (edit_reg_guid == 'null'))
    {
      alert('Invalid GUID.');
      return
    }//close checking for posted table_ss
    
    if((edit_email == '') || (edit_email == null) || (edit_acc_no == 'null'))
    {
      alert('Received by must have value.');
      return
    }//close checking for posted table_ss

    if((edit_acc_no == '') || (edit_acc_no == null) || (edit_acc_no == 'null') )
    {
      alert('Please Select Vendor Code.');
      return
    }//close checking for posted table_ss

    if((edit_memo_type == '') || (edit_memo_type == null) || (edit_memo_type == 'null'))
    {
      alert('Please Select Memo Type.');
      return
    }//close checking for posted table_ss

    if((edit_form_merge == '') || (edit_form_merge == null) || (edit_form_merge == 'null'))
    {
      alert('Please Select Form Merge.');
      return
    }//close checking for posted table_ss
    
    if((edit_form_status == '') || (edit_form_status == null) || (edit_form_status == 'null'))
    {
      alert('Please Select Form Status.');
      return
    }//close checking for posted table_ss

      $.ajax({
            url:"<?php echo site_url('Registration_new/edit_reg_app_vendor');?>",
            method:"POST",
            data:{edit_reg_guid:edit_reg_guid,edit_email:edit_email,edit_acc_no:edit_acc_no,edit_memo_type:edit_memo_type,edit_form_merge:edit_form_merge,edit_form_status:edit_form_status},
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
    });//close edit

  $(document).on('change','#checkall_input_table',function(){

    var id = $(this).attr('table_id');

    var table = $('#'+id).DataTable();

    if($(this).is(':checked'))
    {
      table.rows().nodes().to$().each(function(){

        $(this).find('td').find('#send_check_box').prop('checked',true)

      });//close small loop
    }
    else
    {
      table.rows().nodes().to$().each(function(){

        $(this).find('td').find('#send_check_box').prop('checked',false)

      });//close small loop
    }//close else

  });//close checkbox all set_group_table

  $(document).on('click', '#send_batch_btn', function(event){
    var details = [];
    var table = $('#register').DataTable();
    var i = 0;
    var table_name1 = 'register_add_user_main';
    shoot_link = 0;
    table.rows().nodes().to$().each(function(){
        
      if($(this).find('td').find('#send_check_box').is(':checked'))
      {
        var register_guid = $(this).find('td').find('#send_check_box').attr('register_guid');

        if((register_guid == '')|| (register_guid == 'null')|| (register_guid == null))
        {
          shoot_link = shoot_link+1;
          alert('OPPPS..Invalid GUID.');
        }

        details.push({'register_guid':register_guid});

        i++;
      }
      
    });//close small loop

    if(details == '' || details == null || details == 'null')
    {
      shoot_link = shoot_link+1;
      alert('Please select checkbox to proceed SEND.');
    }
    
    if(shoot_link == 0)
    {
      confirmation_modal('<b>'+i+' Row(s) Selected.</b><br> Are you sure want to SEND?');
      $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
          url:"<?php echo site_url('Registration_new/batch_send_process') ?>",
          method:"POST",
          data:{details:details,table_name1:table_name1},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);

            if(json.batch == 1)
            {
              $('.btn').button('reset');
              $('#alertmodal').modal('hide');
              alert(json.msg.replace(/\\n/g,"\n"));
            }
            else
            {
              $('.btn').button('reset');
              $('#alertmodal').modal('hide');
              alert(json.msg.replace(/\\n/g,"\n"));
              setTimeout(function() {
              location.reload();
              }, 300); 
            }
           
          }//close success
        });//close ajax 
      });//close document yes click
    }
  });//close mouse click

  $(document).on('click', '#btn_delete_form', function(event){
    var register_guid = $(this).attr('register_guid');
    var shoot_link = 0;
    var table_name1 = 'register_add_user_main';
    var table_name2 = 'set_supplier_info';

    if(register_guid == '' || register_guid == null || register_guid == 'null')
    {
      shoot_link = shoot_link+1;
      alert('Invalid GUID.');
    }
    
    if(shoot_link == 0)
    {
      confirmation_modal('Are you sure want to REMOVE?');
      $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
          url:"<?php echo site_url('Registration_new/remove_online_form') ?>",
          method:"POST",
          data:{register_guid:register_guid,table_name1:table_name1,table_name2:table_name2},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);

            if(json.batch == 1)
            {
              $('.btn').button('reset');
              $('#alertmodal').modal('hide');
              alert(json.msg.replace(/\\n/g,"\n"));
            }
            else
            {
              $('.btn').button('reset');
              $('#alertmodal').modal('hide');
              alert(json.msg.replace(/\\n/g,"\n"));
              setTimeout(function() {
              location.reload();
              }, 300); 
            }
           
          }//close success
        });//close ajax 
      });//close document yes click
    }
  });//close mouse click

  $(document).on('click', '#send_btn', function(event){
    var register_guid = $(this).attr('register_guid');
    var type = $(this).attr('form_type');
    var shoot_link = 0;

    if(register_guid == '' || register_guid == null || register_guid == 'null')
    {
      shoot_link = shoot_link+1;
      alert('Invalid GUID.');
    }

    if(type == '' || type == null || type == 'null')
    {
      shoot_link = shoot_link+1;
      alert('Invalid Form Type.');
    }
    
    if(shoot_link == 0)
    {
      confirmation_modal('Are you sure want to SEND?');
      $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
          url:"<?php echo site_url('Registration_new/send_mail') ?>",
          method:"POST",
          data:{register_guid:register_guid,type:type},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);

            if(json.batch == 1)
            {
              $('.btn').button('reset');
              $('#alertmodal').modal('hide');
              alert(json.msg.replace(/\\n/g,"\n"));
            }
            else
            {
              $('.btn').button('reset');
              $('#alertmodal').modal('hide');
              alert(json.msg.replace(/\\n/g,"\n"));
              setTimeout(function() {
              location.reload();
              }, 300); 
            }
           
          }//close success
        });//close ajax 
      });//close document yes click
    }
  });//close mouse click
});
</script>
