<style type="text/css">
.alignleft {
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
          <h3 class="box-title">Online Registration Application</h3>
          <div class="box-tools pull-right">
            <!--<?php if ($_SESSION['user_group_name'] == 'SUPER_ADMIN') { ?>-->
            <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-plus" aria-hidden="true"></i>&nbsp&nbspCreate</button>


              <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
              
                  <!-- Modal content-->
                  <form action="<?php echo site_url('Registration/transaction') ?>" method="post"><div class="modal-content">
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
                     <!-- <select class="form-control" name="comp_no">
                          <option value="">-Select-</option>
                          <?php foreach ($supplier as $key) { ?>
                            <option value="<?php echo $key->reg_no ?>"><?php echo $key->reg_no?></option>
                          <?php } ?>
                          </select> -->
              
                  </div>
                  <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Vendor Code<span class="text-danger">*</span> </label>
                    <select class="select2 form-control" id="acc_no" name="acc_no[]" multiple="multiple" style="width:100%;">
                      <option value='' disabled>-Please select the supplier-</option>
                    </select>
                    <!-- <input type="text" class="form-control" id="acc_no" name="acc_no"  aria-describedby="emailHelp" placeholder="Vendor Code" required="true"> -->
              
                  </div>
                    <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Company Email<span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="comp_email" name="comp_email"  aria-describedby="emailHelp" placeholder="Company Email" required="true">
              
                  </div>
                    </div>
                    <div class="modal-footer">
                       <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      <button type="submit"  class="btn btn-success" >Save</button>
                    </div>
                  </div></form>
              
                </div>
              </div>

            
           <!--  <button title="" id="search_message_details" type="button" class="btn btn-xs btn-primary"   ><i class="fa fa-search" aria-hidden="true"></i>Search</button> -->
            <!--<?php } ?>-->
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
          <div id="">
          
                  <table id="register" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead style="white-space: nowrap;">
                    <tr>
                        <th>Action</th>
                        <th>Registration No</th>
                        <th>Supplier Name</th>
                        <th>Retailer Name</th>
                        <th>Received by</th>
                        <th>Vendor Code</th>
                        <th>No.of Register</th>
                        <th>No.of Participants</th>
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
          "columnDefs": [{ className: "alignleft", targets: [1,2] }],
          "serverSide": true, 
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [11 , 'desc'] ],
          'info'        : true,
          'autoWidth'   : false,
          "bPaginate": true, 
          "bFilter": true, 

          "sScrollY": "100%", 
          "sScrollX": "100%", 
          "sScrollXInner": "100%", 

          "bScrollCollapse": true,
          "ajax": {
              "url": "<?php echo site_url('Registration/register_table');?>",
              "type": "POST",
             

             
          },
          columns: [
                    { "data": "action" },
                    { "data": "register_no" },
                    { "data": "supplier_name" },
                    { "data": "acc_name" },
                    { "data": "comp_email" },
                    { "data": "acc_no" },
                    { "data": "cnt" },
                    { "data": "part_cnt" },
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
            $(nRow).attr('register_no', aData['register_no']);
            
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
          url : "<?php echo site_url('Registration/fetch_reg_no'); ?>",
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
          url : "<?php echo site_url('Registration/fetch_reg_no'); ?>",
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

    var register_guid = $(this).attr('register_guid');
    var register_no = $(this).attr('register_no');
    var supplier_name = $(this).attr('supplier_name');
    var acc_name = $(this).attr('acc_name');
    var comp_email = $(this).attr('comp_email');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Edit Online Registration Application');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control input-sm" id="edit_reg_guid" value="'+register_guid+'" /></div>';

    methodd += '<div class="col-md-12"><label>Reg No</label><input type="text" class="form-control input-sm" id="edit_reg_no" value="'+register_no+'" /></div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label><input type="text" class="form-control input-sm" id="edit_supp_name" value="'+supplier_name+'" readonly/></div>';

    methodd += '<div class="col-md-12"><label>Retailer Name</label><input type="text" class="form-control input-sm" id="edit_acc_name" value="'+acc_name+'" readonly/></div>';

    methodd += '<div class="col-md-12"><label>Received by</label><input type="email" class="form-control input-sm" id="edit_email" value="'+comp_email+'" required/></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="btn_update" class="btn btn-success" value="Update"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

      $(document).off('click','#btn_update').on('click','#btn_update',function(){
      var edit_reg_guid = $('#edit_reg_guid').val();
      var edit_reg_no = $('#edit_reg_no').val();
      var edit_email = $('#edit_email').val();

      if((edit_reg_no == '') || (edit_reg_no == null))
      {
        alert('Registration No must have value.');
        return
      }//close checking for posted table_ss

      if((edit_email == '') || (edit_email == null))
      {
        alert('Received by must have value.');
        return
      }//close checking for posted table_ss

      $.ajax({
            url:"<?php echo site_url('Registration/edit_reg_app');?>",
            method:"POST",
            data:{edit_reg_guid:edit_reg_guid,edit_email:edit_email,edit_reg_no:edit_reg_no},
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


    });//close create_group_add

  });

});
</script>
