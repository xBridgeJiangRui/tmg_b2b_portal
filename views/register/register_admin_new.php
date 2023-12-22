<style type="text/css">
.alignleft {
  text-align: left;
}

.content-wrapper{
  min-height: 750px !important; 
}

.btn-app:hover
{
  background: #99ccff;
  font-weight: bold;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: black;
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
          <h3 class="box-title">Online Registration Application</h3>
          <div class="box-tools pull-right">

            <?php if($acceptance_path != 'hide')
            {
              ?>
              <a type="button" class="btn btn-xs btn-warning" href="<?php echo base_url("assets/Guide_Upload_Term_Sheet_and_Accpetance_Form.pdf") ?>" download ><i class="fa fa-file-text"></i> Manual Guide Upload Acceptance </a>
              <?php
            }
            ?>

            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>

            <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-plus" aria-hidden="true"></i>&nbsp&nbspCreate</button>

            <button type="button" class="btn btn-xs btn-warning" id="send_batch_btn"><i class="glyphicon glyphicon-send" aria-hidden="true"></i>&nbsp&nbspSend</button>

            <button id="import_attribute" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Import Excel</button>

            <?php
            }
            ?>

            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog">
            
                <!-- Modal content-->
                <form action="<?php echo site_url('Registration_new/transaction') ?>" method="post"><div class="modal-content">
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
                   <select class="form-control get_supp_value select2" name="comp_name" style="width:100%;">
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
                    <option value='' disabled>-Please select the supplier-</option>
                  </select>
            
                </div>
                  <div class="form-group col-md-12">
                  <label for="exampleInputEmail1">Company Email<span class="text-danger">*</span> </label>
                  <input type="text" class="form-control" id="comp_email" name="comp_email"  aria-describedby="emailHelp" placeholder="Company Email" required="true">
            
                </div>

                <div class="form-group col-md-12">
                  <label for="exampleInputEmail1">Company Contact 1<span class="text-danger">*</span> </label>
                  <input type="text" class="form-control" id="contact1" name="contact1"  aria-describedby="emailHelp" placeholder="Company Contact 1" required="true">
            
                </div>

                <div class="form-group col-md-12">
                  <label for="exampleInputEmail1">Company Contact 2 </label>
                  <input type="text" class="form-control" id="contact2" name="contact2"  aria-describedby="emailHelp" placeholder="Company Contact 2" >
            
                </div>

                <div class="form-group col-md-12">
                  <label for="exampleInputEmail1">Memo Type<span class="text-danger">*</span> </label>
                  <select class="select2 form-control" name="memo_type" id="memo_type" style="width:100%;">
                  <option value="">-Select-</option>
                  <option value="outright">Outright</option>
                  <option value="outright_iks">Outright IKS</option>
                  <option value="consignment">Consignment</option>
                  <option value="both">Outright & Consignment</option>
                  <option value="waive_outright">Waive Outright</option> 
                  <option value="waive_consign">Waive Consignment</option>
                  <?php foreach($get_memo_type as $row) { ?> <option value="<?php echo $row->template_guid?>"><?php echo $row->template_name?></option> <?php } ?>
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

          </div> <!--end pull right button -->
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
                        <th>No.of Participants</th>
                        <th>Status</th>
                        <th>Waive B2B</th>
                        <th>Updated At</th>
                        <th>Updated By</th>
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
</div>
<script>
$(document).ready(function() {
    $('#register').DataTable({
          // "columnDefs": [ {"targets": 2 ,"visible": false}],
          "columnDefs": [{ "orderable": false, "targets": [0,1]},
          <?php if(in_array('IAVA',$this->session->userdata('module_code')))
          {
            ?>
            { visible: true, targets: [0,5,6,10,11,12,13,14]}
            <?php
          }
          else
          {
            ?>
            { visible: false, targets: [0,5,6,9,10,11,12,13,14,15]}
            <?php
          }
          ?>
          ],
          "serverSide": true, 
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [],
          'info'        : true,
          'autoWidth'   : false,
          "bPaginate": true, 
          "bFilter": true, 

          "sScrollY": "100%", 
          "sScrollX": "100%", 
          "sScrollXInner": "100%", 

          "bScrollCollapse": true,
          "ajax": {
              "url": "<?php echo site_url('Registration_new/register_table');?>",
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
                      else if(data == 'outright_iks')
                      {
                        element = 'Outright IKS';
                      }
                      else if(data != '')
                      {
                        element = data;
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
                    { "data": "part_cnt" },
                    { "data": "form_status" },
                    { "data": "transfer_b2b" , render:function( data, type, row ){

                    var element = '';

                    if(data == '1')
                    {
                      element += 'Yes';
                    }
                    else
                    {
                      element += 'No';
                    }

                    return element;

                    }},
                    { "data": "update_at" },
                    { "data": "update_by" },
                    { "data": "create_at" },
                    { "data": "create_by" },

                   ],

          <?php if(in_array('IAVA',$this->session->userdata('module_code')))
          {
            ?>
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>Brtip", 
            buttons: [
                    'excel'
                ],
            <?php
          }
          else
          {
            ?>
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
            <?php
          }
          ?>
          // "pagingType": "simple",
          "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('register_guid', aData['register_guid']);
            $(nRow).attr('register_no', aData['register_no']);

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
                $(nRow).find('td:eq(14)').css({"background-color":"#ffff33","color":"black"});
                $(nRow).find('td:eq(15)').css({"background-color":"#ffff33","color":"black"});
              }
              else if(aData['form_status'] == 'Archived')
              {
                $(nRow).find('td:eq(0)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(1)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(2)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(3)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(4)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(5)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(6)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(7)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(8)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(9)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(10)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(11)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(12)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(13)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(14)').css({"background-color":"#ff6b6b","color":"black"});
                $(nRow).find('td:eq(15)').css({"background-color":"#ffff33","color":"black"});
              }
            <?php
            }
            ?>
            
            // $(nRow).attr('status', aData['status']);
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
          $('#acc_no').select2().html('<option value="" disabled>Please select the supplier</option>');
       }
          
      });//close selection

  $(document).on('click','#btn_edit_form',function(){

    var supplier_guid = $(this).attr('supplier_guid');
    var register_guid = $(this).attr('register_guid');
    var register_no = $(this).attr('register_no');
    var supplier_name = $(this).attr('supplier_name');
    var acc_name = $(this).attr('acc_name');
    var comp_email = $(this).attr('comp_email');
    var edit_acc_no = $(this).attr('edit_acc_no');
    var acc_no_array = edit_acc_no.split(",");
    var memo_type = $(this).attr('memo_type');
    var form_status = $(this).attr('form_status');
    var comp_contact = $(this).attr('comp_contact');
    var sec_comp_contact = $(this).attr('second_comp_contact');
    var url_link = "https://tunasmanja.xbridge.my/index.php/Supplier_registration/register_form_edit?link="+register_guid;
    var comp_no = $(this).attr('comp_no');
    var outright_template = $(this).attr('outright_template');
    var consignment_template = $(this).attr('consignment_template');
    var cap_template = $(this).attr('cap_template');
    var waive_template = $(this).attr('waive_template');
    var outright_start_date = $(this).attr('outright_start_date');
    var consign_start_date = $(this).attr('consign_start_date');
    var cap_start_date = $(this).attr('cap_start_date');
    var cap_end_date = $(this).attr('cap_end_date');
    var waive_start_date = $(this).attr('waive_start_date');
    var waive_end_date = $(this).attr('waive_end_date');
    var is_invoice = $(this).attr('isinvoice');
    var transfer_b2b = $(this).attr('transfer_b2b');

    if(outright_start_date == '0000-00-00')
    {
      outright_start_date = '';
    }

    if(consign_start_date == '0000-00-00')
    {
      consign_start_date = '';
    }

    if(cap_start_date == '0000-00-00')
    {
      cap_start_date = '';
    }

    if(cap_end_date == '0000-00-00')
    {
      cap_end_date = '';
    }

    if(waive_start_date == '0000-00-00')
    {
      waive_start_date = '';
    }

    if(waive_end_date == '0000-00-00')
    {
      waive_end_date = '';
    }

    var modal = $("#large-modal").modal();

    modal.find('.modal-title').html('Edit Online Registration Application');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<input type="hidden" class="form-control input-sm" id="store_form_status" value="'+form_status+'" />';

    methodd += '<input type="hidden" class="form-control input-sm" id="edit_reg_guid" value="'+register_guid+'" />';

    methodd += '<div class="col-md-6"><label>URL LINK</label> <input type="text" class="form-control input-sm" id="copy_link" value="'+url_link+'" readonly/> </div>';

    // methodd += '<div class="col-md-6"><label>Retailer Name</label><input type="text" class="form-control input-sm" id="edit_acc_name" value="'+acc_name+'" readonly/></div>';

    // methodd += '<div class="col-md-6"><label>Form No</label><input type="text" class="form-control input-sm" id="edit_reg_no" value="'+register_no+'" readonly/></div>';

    methodd += '<div class="col-md-6"><label>Supplier Name</label><input type="text" class="form-control input-sm" id="edit_supp_name" value="'+supplier_name+'" /></div>';

    methodd += '<div class="col-md-6"><label>Reg No</label><input type="text" class="form-control input-sm" id="edit_comp_no" value="'+comp_no+'" /></div>';

    methodd += '<div class="col-md-6"><label>Received by</label><input type="email" class="form-control input-sm" id="edit_email" value="'+comp_email+'" required/></div>';

    methodd += '<div class="col-md-6"><label>Phone No</label><input type="email" class="form-control input-sm" id="edit_comp_contact" value="'+comp_contact+'" required/></div>';

    methodd += '<div class="col-md-6"><label>Phone No 2</label><input type="email" class="form-control input-sm" id="edit_sec_comp_contact" value="'+sec_comp_contact+'" required/></div>';

    methodd += '<div class="col-md-6"><label>Memo Type</label> <select class="form-control" name="edit_memo_type" id="edit_memo_type"> <option value="">-Select-</option> <option value="outright" >Outright</option> <option value="outright_iks" >Outright IKS</option> <option value="consignment">Consignment</option> <option value="both">Outright & Consignment</option> <option value="waive_outright">Waive Outright</option> <option value="waive_consign">Waive Consignment</option> <?php foreach($get_memo_type as $row) { ?> <option value="<?php echo $row->template_guid?>"><?php echo $row->template_name?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-6"><label>Form Status</label> <select class="form-control" name="edit_form_status" id="edit_form_status"> <option value="">-Select-</option> <option value="Advance" >Advance</option> <option value="Processing" > Processing </option> <option value="Registered" > Registered </option> <option value="Terminated" > Terminated </option>  </select> </div>';

    methodd += '<div class="col-md-6"><label>Billing Invoice</label> <select class="form-control" name="edit_is_invoice" id="edit_is_invoice"> <option value="">-Select-</option> <option value="0" > Yes </option> <option value="1" > Billed </option> <option value="2" > No (Disable to bill) </option> </select> </div>';

    methodd += '<div class="col-md-6"><label>Waive B2B</label> <select class="form-control" name="edit_transfer_b2b" id="edit_transfer_b2b"> <option value="">-Select-</option> <option value="1"> YES - Waive Registration Fees </option> <option value="0" > NO </option> </select> </div>';

    methodd += '<div class="col-md-12"><label>Vendor Code</label> <select class="select2 form-control" id="edit_acc_no" name="edit_acc_no" multiple="multiple" ></select> </div>';

    methodd +='<div class="clearfix"></div><br>';

    if(form_status == 'Registered')
    {
      methodd += '<div class="col-md-6"><label>Outright Template </label> <select class="form-control select2" name="edit_outright_template" id="edit_outright_template"> <option value="">-Select-</option> <?php foreach($get_outright_template as $row) { ?> <option value="<?php echo $row->template_guid?>"><?php echo $row->template_name?></option> <?php } ?></select> </div>';

      methodd += '<div class="col-md-6"><label>Outright Start Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="edit_outright_start" id="edit_outright_start" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';

      methodd += '<div class="col-md-6"><label>Consignment Template </label> <select class="form-control select2" name="edit_consign_template" id="edit_consign_template"> <option value="">-Select-</option> <?php foreach($get_consign_template as $row) { ?> <option value="<?php echo $row->template_guid?>"><?php echo $row->template_name?></option> <?php } ?></select> </div>';

      methodd += '<div class="col-md-6"><label>Consignment Start Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="edit_consign_start" id="edit_consign_start" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';

      // methodd +='<div class="col-md-12">';

      methodd += '<div class="col-md-6"><label>Cap Template </label> <select class="form-control select2" name="edit_cap_template" id="edit_cap_template"> <option value="">-Select-</option> <?php foreach($get_cap_template as $row) { ?> <option value="<?php echo $row->template_guid?>"><?php echo $row->template_name?></option> <?php } ?></select> </div>';

      methodd += '<div class="col-md-6"><label>Cap Start Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="edit_cap_start" id="edit_cap_start" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';

      methodd += '<div class="col-md-6"><label>Cap End Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="edit_cap_end" id="edit_cap_end" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';

      methodd +='<div class="clearfix"></div>';

      methodd += '<div class="col-md-6"><label>Waive Template </label> <select class="form-control select2" name="edit_waive_template" id="edit_waive_template"> <option value="">-Select-</option> <?php foreach($get_waive_template as $row) { ?> <option value="<?php echo $row->template_guid?>"><?php echo $row->template_name?></option> <?php } ?></select> </div>';

      methodd += '<div class="col-md-6"><label>Waive Start Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="edit_waive_start" id="edit_waive_start" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';

      methodd += '<div class="col-md-6"><label>Waive End Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="edit_waive_end" id="edit_waive_end" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';
    }

    // methodd +='</div> <div class="clearfix"></div><br>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="btn_update" class="btn btn-success" value="Update"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function() {
       $('#edit_memo_type').val(memo_type);

       $('#edit_outright_template').val(outright_template);
       $('#edit_outright_start').val(outright_start_date);
       //$('#edit_outright_start').datepicker("setDate", outright_start_date );

       $('#edit_consign_template').val(consignment_template);
       $('#edit_consign_start').val(consign_start_date);

       $('#edit_cap_template').val(cap_template);
       $('#edit_cap_start').val(cap_start_date);
       $('#edit_cap_end').val(cap_end_date);

       $('#edit_waive_template').val(waive_template);
       $('#edit_waive_start').val(waive_start_date);
       $('#edit_waive_end').val(waive_end_date);

       $('#edit_is_invoice').val(is_invoice);

       $('#edit_transfer_b2b').val(transfer_b2b);
       
       $('.select2').select2();
       $('#edit_memo_type').select2(); 
       $('#edit_form_status').val(form_status); 
       var type_val = supplier_guid;
       
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

      $('#edit_cap_start').change(function(){
        var cap_date_val = $('#edit_cap_start').val();

        if(cap_date_val != '')
        {
          var cap_someDate = new Date(cap_date_val);
          //var cap_dd = cap_someDate.getDate();
          var cap_mm = cap_someDate.getMonth();
          var cap_y = cap_someDate.getFullYear();   
          var cap_c = new Date(cap_y + 1, cap_mm + 1 , 0);
          var cap_newDate = new Date(cap_c);

          var cap_result = cap_newDate.toLocaleDateString("fr-CA", { // you can use undefined as first argument
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
          });

          $('#edit_cap_end').val(cap_result);
          $('#edit_cap_end').datepicker("setDate", cap_result );
        }
      });//close selection

      $('#edit_waive_start').change(function(){
        var waive_date_val = $('#edit_waive_start').val();
        //alert(waive_date_val); die;
        if(waive_date_val != '')
        {
          var waive_someDate = new Date(waive_date_val);
          //var waive_dd = waive_someDate.getDate();
          var waive_mm = waive_someDate.getMonth();
          var waive_y = waive_someDate.getFullYear();   
          var waive_c = new Date(waive_y + 1, waive_mm + 1 , 0);
          var waive_cnewDate = new Date(waive_c);

          var waive_result = waive_cnewDate.toLocaleDateString("fr-CA", { // you can use undefined as first argument
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
          });

          $('#edit_waive_end').val(waive_result);
          $('#edit_waive_end').datepicker("setDate", waive_result );
        }
      });//close selection

      $('.datepicker').datepicker({
       forceParse: false,
       autoclose: true,
       todayHighlight: true,
       format: 'yyyy-mm-dd'
      });
    }, 300);
  }); // close

  $(document).on('click','#copy_link',function(){
    var url_link = $(this).val();
    $('#copy_link').select()
    document.execCommand('copy');
    alert('Copy to clipboard');
  });

  $(document).on('click','#btn_update',function(){
    var edit_reg_guid = $('#edit_reg_guid').val();
    var edit_email = $('#edit_email').val();
    var edit_acc_no = $('#edit_acc_no').val();
    var edit_memo_type = $('#edit_memo_type').val();
    var edit_form_status = $('#edit_form_status').val();
    var edit_comp_contact = $('#edit_comp_contact').val();
    var edit_sec_comp_contact = $('#edit_sec_comp_contact').val();
    var edit_supp_name = $('#edit_supp_name').val();
    var edit_comp_no = $('#edit_comp_no').val();

    var store_form_status = $('#store_form_status').val();
    var edit_outright_template = $('#edit_outright_template').val();
    var edit_consign_template = $('#edit_consign_template').val();
    var edit_cap_template = $('#edit_cap_template').val();
    var edit_waive_template = $('#edit_waive_template').val();
    var edit_outright_start = $('#edit_outright_start').val();
    var edit_consign_start = $('#edit_consign_start').val();
    var edit_cap_start = $('#edit_cap_start').val();
    var edit_cap_end = $('#edit_cap_end').val();
    var edit_waive_start = $('#edit_waive_start').val();
    var edit_waive_end = $('#edit_waive_end').val();
    var edit_is_invoice = $('#edit_is_invoice').val();
    var edit_transfer_b2b = $('#edit_transfer_b2b').val();

    if((edit_supp_name == '') || (edit_supp_name == null) || (edit_supp_name == 'null'))
    {
      alert('Supplier Name must have value.');
      return
    }//close checking for posted table_ss

    if((edit_comp_no == '') || (edit_comp_no == null) || (edit_comp_no == 'null'))
    {
      alert('Reg No must have value.');
      return
    }//close checking for posted table_ss

    if((edit_reg_guid == '') || (edit_reg_guid == null) || (edit_reg_guid == 'null'))
    {
      alert('Invalid GUID.');
      return
    }//close checking for posted table_ss

    if((edit_memo_type == '') || (edit_memo_type == null) || (edit_memo_type == 'null'))
    {
      alert('Please Select Memo Type.');
      return
    }//close checking for posted table_ss

    if((edit_is_invoice == '') || (edit_is_invoice == null) || (edit_is_invoice == 'null'))
    {
      alert('Please Select Bill Invoice.');
      return
    }//close checking for posted table_ss

    if(edit_form_status == '' || edit_form_status == 'null' || edit_form_status == null)
    {
      edit_form_status = 'default';
    }

    if((edit_transfer_b2b == '') || (edit_transfer_b2b == null) || (edit_transfer_b2b == 'null'))
    {
      alert('Please Select Waive B2B.');
      return
    }

    if(edit_form_status != 'Terminated' && edit_form_status != 'Advance' )
    {
      if((edit_acc_no == '') || (edit_acc_no == null) || (edit_acc_no == 'null') )
      {
        alert('Please Select Vendor Code.');
        return
      }//close checking for posted table_ss

      if((edit_email == '') || (edit_email == null) || (edit_email == 'null'))
      {
        alert('Received by must have value.');
        return
      }//close checking for posted table_ss

      if((edit_comp_contact == '') || (edit_comp_contact == null) || (edit_comp_contact == 'null'))
      {
        alert('Please Insert Phone No.');
        return
      }//close checking for posted table_ss

      if(edit_sec_comp_contact == edit_comp_contact)
      {
        alert('Phone No 2 Cannot same with Phone No 1.');
        return
      }

      if(store_form_status == 'Registered_disable')
      {
        if(edit_memo_type == 'both')
        {
          if(edit_outright_template == '' || edit_consign_template == '')
          {
            alert('Please select outright and consignment template.');
            return;
          }
          else
          {
            if(edit_outright_start == '' || edit_consign_start == '')
            {
              alert('Please select outright and consignment template start date.');
              return;
            }
          }
        }

        if(edit_memo_type == 'outright')
        {
          if(edit_consign_template != '')
          {
            alert('Invalid select consignment template due to is OUTRIGHT type.');
            return;
          }

          if(edit_outright_template == '')
          {
            alert('Please select outright template.');
            return;
          }
          else
          {
            if(edit_outright_start == '')
            {
              alert('Please select outright start date.');
              return;
            }
          }
        }

        if(edit_memo_type == 'consignment')
        {
          if(edit_outright_template != '')
          {
            alert('Invalid select outright template due to is CONSIGNMENT type.');
            return;
          }
          
          if(edit_consign_template == '')
          {
            alert('Please select consignment template.');
            return;
          }
          else
          {
            if(edit_consign_start == '')
            {
              alert('Please select consignment start date.');
              return;
            }
          }
        }

        if(edit_cap_template == '')
        {
          if(edit_cap_start != '' || edit_cap_end != '')
          {
            alert('Please remove cap start date and end date or select cap template to proceed.');
            return;
          }
        }

        if(edit_waive_template == '')
        {
          if(edit_waive_start != '' || edit_waive_end != '')
          {
            alert('Please remove waive start date and end date or select waive template to proceed.');
            return;
          }
        }

        if(edit_cap_template != '')
        {
          if(edit_cap_start != '')
          {
            if(edit_cap_end == '')
            {
              alert('Please Insert Cap End Date.');
              return;
            }
          }
          else
          {
            alert('Please Insert Cap Start Date.');
            return;
          }

          if(edit_cap_start != '' && edit_cap_end != '')
          {
            if(edit_cap_end < edit_cap_start)
            {
              alert('Cap End Date cannot less than Cap Start Date');
              return;
            }
          }
        }

        if(edit_waive_template != '')
        {
          if(edit_waive_start != '')
          {
            if(edit_waive_end == '')
            {
              alert('Please Insert Waive End Date.');
              return;
            }
          }
          else
          {
            alert('Please Insert Waive Start Date.');
            return;
          }

          if(edit_waive_start != '' && edit_waive_end != '')
          {
            if(edit_waive_end < edit_waive_start)
            {
              alert('Waive End Date cannot less than Waive Start Date');
              return;
            }
          }
        }
      }
    }


    confirmation_modal('Are you sure want to Update Settings?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
            url:"<?php echo site_url('Registration_new/edit_reg_app');?>",
            method:"POST",
            data:{edit_reg_guid:edit_reg_guid,edit_email:edit_email,edit_acc_no:edit_acc_no,edit_memo_type:edit_memo_type,edit_form_status:edit_form_status,edit_comp_contact:edit_comp_contact,edit_sec_comp_contact:edit_sec_comp_contact,edit_supp_name:edit_supp_name,edit_comp_no:edit_comp_no,edit_outright_template:edit_outright_template,edit_consign_template:edit_consign_template,edit_cap_template:edit_cap_template,edit_waive_template:edit_waive_template,edit_outright_start:edit_outright_start,edit_consign_start:edit_consign_start,edit_cap_start:edit_cap_start,edit_cap_end:edit_cap_end,edit_waive_start:edit_waive_start,edit_waive_end:edit_waive_end,edit_is_invoice:edit_is_invoice,edit_transfer_b2b:edit_transfer_b2b},
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

  $(document).on('click','#import_attribute',function(){
    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Import File');

    methodd = '';

    methodd +='<div id="myDropZone" class="dropzone" style="height:20px;"><center><label class="vertical-center" id="output" for="upload_file">Select a file to continue</label></center> </div>';

    methodd += '<div class="row" style="padding-top:10px;">';
    methodd += '<form id="excel_file_form">';
    methodd += '<div class="col-md-6">';
    methodd += '<label for="upload_file" class="btn btn-block btn-primary">Select File</label>';
    methodd += '</div>';
    methodd += '<div class="col-md-6" style="margin-bottom:10px;">';
    methodd += '<button type="button" class="btn btn-block btn-danger" id="reset_input">Reset</button>';
    methodd += '</div>';
    methodd += '<div class="col-md-6">';
    methodd += '<input type="file" name="photo" id="upload_file" accept=".xls,.xlsx,.csv" style="margin-right:50px;"/>';
    methodd += '</div>';
    methodd += '</form>';
    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);
  });//close import

  $(document).on('change','#upload_file',function(e){

    var fileName = e.target.files[0].name;

    if(fileName != '')
    { 
      $('#submit_button').remove();

      $('#excel_file_form').append('<div class="col-md-12" ><button type="button" id="submit_button" class="btn btn-block btn-success" style="margin-top:10px;">Submit</button></div>');

      $('#output').html(fileName);

    }
    else
    { 
      $('#output').html('No files selected');
      $('#submit_button').remove();
    }
  });//close upload file

  $(document).on('click','#reset_input',function(){

    $('#upload_file').val('');

    var file = $('#upload_file')[0].files[0];

    if(file === undefined)
    {
      $('#output').html('No files selected');
        $('#submit_button').remove();
    }
    else
    { 
      var fileName = file.name;

      $('#submit_button').remove();

        $('#excel_file_form').append('<button type="button" class="btn btn-block btn-success" id="submit_button" style="margin-top:10px;">Submit</button>');

        $('#output').html(fileName);
    }
  });//close reset_input

  $(document).on('click','#submit_button',function(){

    confirmation_modal('Are you sure want to Submit?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#upload_file')[0].files[0]);

      $.ajax({
          url:"<?= site_url('Registration_new/file_upload');?>",
          method:"POST",
          data: formData,
          processData: false, // Don't process the files
          contentType: false, // Set content type to false as jQuery will tell the server its a query string request
          beforeSend : function()
          { 
            $('.btn').button('loading');
          },
          complete : function()
          { 
            $('.btn').button('reset');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            $('#alertmodal').modal('hide');
            if (json.para1 == '1') {
              alert(json.msg);
              //alert(json.msg.replace(/\\n/g,"\n"));
              $('.btn').button('reset');
              $('#upload_file').val('');
              $('#output').html('No files selected');
              $('#submit_button').remove();

            }else{

              $('#medium-modal').modal('hide');
              $('#alertmodal').modal('hide');
              alert(json.msg.replace(/\\n/g,"\n"));
              location.reload();
              // setTimeout(function() {
              // $('#upload_file').val('');
              // $('#output').html('No files selected');
              // $('#submit_button').remove();
              // }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });//close submit_button

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
    var table_name1 = 'register_new';
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
    var table_name1 = 'register_new';
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

  $(document).on('click', '#btn_archive', function(event){
    var register_guid = $(this).attr('register_guid');
    var form_status = $(this).attr('form_status');
    var shoot_link = 0;

    if(register_guid == '' || register_guid == null || register_guid == 'null')
    {
      shoot_link = shoot_link+1;
      alert('Invalid GUID.');
    }

    if(form_status == 'Archived')
    {
      $msg = 'Are you sure reset the Archive status?';
    }
    else
    {
      $msg = 'Are you sure want to Archive?';
    }

    if(shoot_link == 0)
    {
      confirmation_modal($msg);
      $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
          url:"<?php echo site_url('Registration_new/set_archive') ?>",
          method:"POST",
          data:{register_guid:register_guid},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);

            if(json.para1 == 'false')
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

  $(document).on('click','#btn_upload_acceptance',function(){
    var register_guid = $(this).attr('register_guid');
    var supplier_name = $(this).attr('supplier_name');
    var acc_name = $(this).attr('acc_name');
    var supplier_guid = $(this).attr('supplier_guid');
    var customer_guid = $(this).attr('customer_guid');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Upload Acceptance Form');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<input type="hidden" id="register_guid_data" value="'+register_guid+'" readonly/>';

    methodd += '<input type="hidden" id="supplier_guid_data" value="'+supplier_guid+'" readonly/>';

    methodd += '<input type="hidden" id="customer_guid_data" value="'+customer_guid+'" readonly/>';

    methodd += '<div class="col-md-4"><b>Retailer Name</b></div><div class="col-md-8">'+acc_name+' </div><div class="clearfix"></div><br>';

    methodd += '<div class="col-md-4"><b>Supplier Name</b></div><div class="col-md-8">'+supplier_name+' </div><div class="clearfix"></div><br>';

    methodd += '<div class="col-md-8"><b>Acceptance Form</b>';

    methodd += '</div><div class="col-md-8"><input id="edit_upload_file" type="file" class="form-control" accept=".pdf"></div>';

    methodd += '<div class="col-md-4"><span id="edit_button_file_form"></span></div><div class="clearfix"></div><br>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"> <a type="button" class="btn btn-warning" href="<?php echo $acceptance_path.'CKS_xBridge_B2B_Letter_of_Acceptance_Form.pdf' ?>" target="_blank" ><i class="fa fa-file-text"></i> Download Acceptance Form </a> </span><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);
  });//close upload acceptance form

  $(document).on('change','#edit_upload_file',function(e){
    
    var edit_fileName = e.target.files[0].name;
    if(edit_fileName != '')
    { 
      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" edit_fileName="'+edit_fileName+'" style="margin-right:5px;" > Upload</button><button type="button" class="btn btn-danger" id="edit_reset_input" >Reset</button>');
    }
    else
    { 
      //$('#button_file_form').remove();
      $('#edit_submit_button').remove();
      $('#edit_reset_input').remove();
    }
  });//close upload file

  $(document).on('click','#edit_reset_input',function(){

    $('#edit_upload_file').val('');

    var edit_file = $('#edit_upload_file')[0].files[0];

    if(edit_file === undefined)
    {
      $('#edit_submit_button').remove();
      $('#edit_reset_input').remove();
    }
    else
    { 
      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" style="margin-top: 25px;" edit_fileName="'+edit_file+'" > Upload</button><button type="button" class="btn btn-danger" id="edit_reset_input" >Reset</button>');
    }
  });//close reset_input

  $(document).on('click','#edit_submit_button',function(){

    var edit_file_name = $('#edit_submit_button').attr('edit_fileName');
    var register_guid_data = $('#register_guid_data').val();
    var supplier_guid_data = $('#supplier_guid_data').val();
    var customer_guid_data = $('#customer_guid_data').val();
    //alert(term_type); die;
    if((register_guid_data == '') || (register_guid_data == null) || (register_guid_data == 'null'))
    {
      alert('Invalid Data. Please Contact Support.');
      return;
    }

    if((supplier_guid_data == '') || (supplier_guid_data == null) || (supplier_guid_data == 'null'))
    {
      alert('Invalid Data. Please Contact Support.');
      return;
    }

    if((customer_guid_data == '') || (customer_guid_data == null) || (customer_guid_data == 'null'))
    {
      alert('Invalid Data. Please Contact Support.');
      return;
    }

    confirmation_modal('Are you sure want to Upload?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#edit_upload_file')[0].files[0]);
      formData.append('file_name', edit_file_name);
      formData.append('register_guid_data', register_guid_data);
      formData.append('supplier_guid_data', supplier_guid_data);
      formData.append('customer_guid_data', customer_guid_data);
      //console.log(formData); die;
      $.ajax({
          url:"<?= site_url('Registration_new/upload_acceptance_form');?>",
          method:"POST",
          data: formData,
          processData: false, // Don't process the files
          contentType: false, // Set content type to false as jQuery will tell the server its a query string request
          beforeSend : function()
          { 
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            
            if (json.para1 == '1') {
              $('#alertmodal').modal('hide');
              alert(json.msg);
              $('.btn').button('reset');
              $('#upload_file').val('');
              $('#submit_button').remove();

            }else{
              $('.btn').button('reset');
              $('#alertmodal').modal('hide');
              $('#edit_submit_button').hide();
              alert(json.msg);
              setTimeout(function() { 
                location.reload();
                //registration_modal();
              }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });//close submit_button

  $(document).on('click','#view_acceptance',function(){
    var url_data = $(this).attr('acceptance_url');
    //alert(url_data); die;

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Preview Acceptance Form');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<embed src="'+url_data+'" width="100%" height="500px" style="border: none;" id="pdf_view"/>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> </span> </p>';

    modal.find('.modal-body').html(methodd);
    modal.find('.modal-footer').html(methodd_footer);

  });
  
});
</script>
