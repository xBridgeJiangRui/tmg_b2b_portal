<!--  @@@@@@@@@@@@@@@@@@@@@@ Start Register Supplier modal @@@@@@@@@@@@@@@@ -->
<div class="modal fade" id="regsupplier" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Create Supplier</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('supplier_setup_new/create') ?>" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" value="" name="guid"/> 
                            <input type="hidden" value="" name="table"/> 
                            <input type="hidden" value="" name="mode"/> 
                        <label class="control-label col-md-3">Old Supplier Name<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <input type="hidden" name="old_supplier_name" class="form-control" autocomplete="off" required> 
                                <input type="text" name="old_supplier_name_display" class="form-control" autocomplete="off" required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" value="" name="guid"/> 
                            <input type="hidden" value="" name="table"/> 
                            <input type="hidden" value="" name="mode"/> 
                        <label class="control-label col-md-3">Supplier Name<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <input type="text" name="supplier_name" class="form-control" autocomplete="off" required> 
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3">Company Registration No.<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <input type="text" name="reg_no" autocomplete="off" class="form-control" required> 
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3">GST No. </label>
                            <div class="col-md-9">
                                <input type="text" name="gst_no" autocomplete="off" class="form-control"> 
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <select name="isactive" class="form-control" id="inactive_supplier" onchange="status_confirmation()"> 
                                  <option value="1">Active</option>
                                  <option value="0">Inactive</option> 
                                </select>
                            </div>
                        </div>
<!--                         <div class="form-group">
                        <label class="control-label col-md-3">Payment Term</label>
                            <div class="col-md-9">
                                <input type="text" name="payment_term" autocomplete="off" class="form-control"> 
                            </div>
                        </div> -->
                        <div class="form-group">
                        <label class="control-label col-md-3">Verification ID<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <input type="password" id="verification" name="verification" class="form-control"> 
        <span id="result_verification"></span>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" id="supplier_submit" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@ End Register Supplier modal @@@@@@@@@@@@@@@@@@@@@@@@@ -->

<!--  @@@@@@@@@@@@ @@@@@@@@@@ Start Register Supplier Group modal @@@@@@@@@@@@@@@@ -->
<div class="modal fade" id="regsuppliergroup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Create Supplier</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('supplier_setup_new/create_group') ?>" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" value="" name="table"/> 
                            <input type="hidden" value="" name="mode"/> 
                            <input type="hidden" value="" name="guid"/> 
                        <label class="col-md-12">Backend Group Name<span style="color:red">*</span> </label>
                            <!-- <div class="col-md-9">
                                <input type="text" name="supplier_group_name" class="form-control" required> 
                            </div> -->
                            <div class="col-md-12">
                                <select name="supplier_group_name" id="select2_erp_group" class="form-control select2" required>
                                <?php 
                                foreach($set_code->result() as $row)
                                {
                                  ?>
                                    <option required selected data-default value="<?php echo $row->code?>"><?php echo $row->name; echo " - "; echo $row->code; ?>
                                    </option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Supplier Name</label>
                            <div class="col-md-12">
                                <select name="supplier_guid" id="select2_supplier_name" class="form-control select2" required>
                                <?php
                                foreach($set_supplier->result() as $row)
                                {
                                  ?>
                                    <option required selected data-default value="<?php echo $row->supplier_guid?>"><?php echo $row->name_reg?>
                                      
                                    </option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@ End Register Supplier modal @@@@@@@@@@@@@@@@@@@@@@@@@ -->

<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ assign branch modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="user" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h3 class="modal-title">Assign User</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('supplier_setup_new/assign')?>" method="POST" id="form" class="form-horizontal">
                     <input type="hidden" id="user_guid" value="" name="guid"/> 
                    <div class="form-body">
                        <div class="form-group">
                          <label class="control-label col-md-3" for="radios">Assign User By</label>
                          <div class="col-md-9"> 
                            <label class="radio-inline" for="radios-0">
                              <input type="radio" id="supplier_multiple" name="supplier_mode" value="supplier_multiple">
                              Supplier
                            </label> 
                            <label class="radio-inline" for="radios-1">
                              <input type="radio" id="csupplier" name="supplier_mode" value="Supplier"  onclick="choose_supplier_type('Supplier')" >
                              Supplier Name
                            </label> 
                            <label class="radio-inline" for="radios-2">
                              <input type="radio" id="csupplier_group" name="supplier_mode" value="SupplierGroup"  onclick="choose_supplier_type('SupplierGroup')" >
                              Supplier Group
                            </label> 
                            <button type="button" name="add_supplier" id="add_supplier" class="pull-right btn-sm btn-success" style="display:none;">+</button>
                          </div>
                        </div>
                            <div id="methodSupplier" class="desc" style="display: none;">
                                <div class="form-group"  id="div1" >
                                <label class="control-label col-md-3">Supplier</label> 
                                    <div class="col-md-9">
                                    <select name="supplier" class="form-control" style="width: 100%;">
                                    <?php
                                        foreach($set_supplier->result() as $row)
                                        {
                                          ?>
                                             <option required data-default value="<?php echo $row->supplier_guid?>"><?php echo $row->supplier_name?></option>
                                          <?php
                                        }
                                        ?>
                                    </select>
                                    </div> 
                                </div>
                            </div>

                            <span id="multipleSupplier"></span>

                            <div id="methodSupplierGroup" class="desc" style="display: none;">
                                <div class="form-group"  id="div1">
                                <label class="control-label col-md-3">Supplier Group</label> 
                                    <div class="col-md-9">
                                      <select name="supplier_group" class="form-control" style="width: 100%;">
                                    <?php
                                        foreach($set_supplier_group->result() as $row)
                                        {
                                          ?>
                                             <option required data-default value="<?php echo $row->supplier_group_guid?>"><?php echo $row->supplier_group_name?></option>
                                          <?php
                                        }
                                        ?>
                                    </select>
                                      <!-- multiple -->
                                    <!-- <select name="supplier_group" class="form-control select2" multiple="multiple" style="width: 100%;">
                                    <?php
                                        foreach($set_supplier_group->result() as $row)
                                        {
                                          ?>
                                            <option required data-default value="<?php echo $row->supplier_group_guid?>"><?php echo $row->supplier_group_name?></option>  
                                           
                                          <?php
                                        }
                                        ?>
                                    </select> -->
                                    </div> 
                                </div>
                            </div>

                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ End assign branch modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

<div class="modal fade" id="updatebackendb2bflag" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Create Supplier</h3>
            </div>
            <div class="modal-body form">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" value="" name="table"/> 
                            <input type="hidden" value="" name="mode"/> 
                            <input type="hidden" value="" name="guid"/> 
                        <label class="control-label col-md-3">Backend Group Name<span style="color:red">*</span> </label>
                            <!-- <div class="col-md-9">
                                <input type="text" name="supplier_group_name" class="form-control" required> 
                            </div> -->
<!--                             <div class="col-md-9">
                                <select name="supplier_group_name" class="form-control" required>
                                <?php 
                                foreach($set_code->result() as $row)
                                {
                                  ?>
                                    <option required selected data-default value="<?php echo $row->code?>"><?php echo $row->name; echo " => "; echo $row->code; ?>
                                    </option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div> -->
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Supplier Name</label>
<!--                             <div class="col-md-9">
                                <select name="supplier_guid" class="form-control" required>
                                <?php
                                foreach($set_supplier->result() as $row)
                                {
                                  ?>
                                    <option required selected data-default value="<?php echo $row->supplier_guid?>"><?php echo $row->name_reg?>
                                      
                                    </option>
                                  <?php
                                }
                                ?>
                                </select>
                            </div> -->
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" id="ajax_code" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                <!-- </form> -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
$( document ).ready(function() {

    $(document).on("keyup","#verification",function() {

      var verification_id = $(this).val();

      //alert(verification_id);

      // alert(varification_id);

        $.ajax({

        url:"<?php echo base_url(); ?>index.php/Supplier_setup_new/check_verification_id",

        method:"POST",

        data:{verification_id:verification_id},

        success:function(data){

          if(data == 1)

          {

            $('#result_verification').html('<span style="color:green">Right Verification Code</span>');

            $('#supplier_submit').prop('disabled',false);

          }

          else

          {

            $('#result_verification').html('<span style="color:red">Wrong Verification Code</span>');

            $('#supplier_submit').prop('disabled',true);



          }

        }

        }); 

    });

    $(document).on("click","#supplier_multiple",function() {
        var user_guid = $('#user_guid').val();
        // alert(user_guid);

        $.ajax({

        url:"<?php echo base_url(); ?>index.php/Supplier_setup_new/check_user_assign",

        method:"POST",

        data:{user_guid:user_guid},

        success:function(data){
            if(data == 1)
            {
                    $.ajax({

                    url:"<?php echo base_url(); ?>index.php/Supplier_setup_new/get_supplier_multiple",

                    method:"POST",

                    dataType:"json",

                    data:{user_guid:user_guid},

                    success:function(data){
                      $('#csupplier').prop('checked', false);
                      $('#csupplier_group').prop('checked', false);
                      $('#methodSupplier').css('display' ,'none');
                      $('#methodSupplierGroup').css('display' ,'none');
                      // alert(data.countdropdown);
                      $('#multipleSupplier').append(data.dropdown);
                      $('#supplier_multiple_startup').val(data.countdropdown);
                      $('#add_supplier').css('display' ,'block');
                      $('.supplier_code').select2();

                    }

                    });           
            }
            else
            {
                    $.ajax({

                    url:"<?php echo base_url(); ?>index.php/Supplier_setup_new/check_supplier_multiple",

                    method:"POST",

                    data:{user_guid:user_guid},

                    success:function(data){
                      $('#csupplier').prop('checked', false);
                      $('#csupplier_group').prop('checked', false);
                      $('#methodSupplier').css('display' ,'none');
                      $('#methodSupplierGroup').css('display' ,'none');
                      // alert(data.countdropdown);
                      $('#multipleSupplier').append(data);
                      $('#add_supplier').css('display' ,'block');
                      $('.supplier_code').select2();

                    }

                    }); 
            }
        }

        }); 

        // $.ajax({

        // url:"<?php echo base_url(); ?>index.php/Supplier_setup_new/get_supplier_multiple",

        // method:"POST",

        // dataType:"json",

        // data:{user_guid:user_guid},

        // success:function(data){
        //   $('#csupplier').prop('checked', false);
        //   $('#csupplier_group').prop('checked', false);
        //   $('#methodSupplier').css('display' ,'none');
        //   $('#methodSupplierGroup').css('display' ,'none');
        //   // alert(data.countdropdown);
        //   $('#multipleSupplier').append(data.dropdown);
        //   $('#supplier_multiple_startup').val(data.countdropdown);
        //   $('#add_supplier').css('display' ,'block');
        //   $('.supplier_code').select2();

        // }

        // }); 

    });   

    $(document).on("click","#add_supplier",function() {
        var supplier_multiple_startup = $('#supplier_multiple_startup').val();
        // alert(supplier_multiple_startup);
        var object = [];
        $('.supplier_name').each(function(){
           var supplier_code = $(this).val();
           // alert(supplier_code);
           object.push({"supplier_code":supplier_code});
        });
        var details = JSON.stringify(object);

        $.ajax({

        url:"<?php echo base_url(); ?>index.php/Supplier_setup_new/check_supplier_multiple2",

        method:"POST",

        data:{supplier_code:details,no:supplier_multiple_startup},

        success:function(data){
          $('#csupplier').prop('checked', false);
          $('#csupplier_group').prop('checked', false);
          $('#methodSupplier').css('display' ,'none');
          $('#methodSupplierGroup').css('display' ,'none');
          // alert(data);
          $('#multipleSupplier').append(data);
          $('.supplier_code').select2();
          var total = parseInt(supplier_multiple_startup) + 1;
          $('#supplier_multiple_startup').val(total);
          $('.supplier_code').select2();

        }

        }); 

    });

    $(document).on("change",".supplier_name",function() {
        var no = $(this).attr('no');
        var supplier_guid = $(this).val();
        // alert(no);
        $.ajax({

        url:"<?php echo base_url(); ?>index.php/Supplier_setup_new/check_supplier_code_multiple",

        method:"POST",

        data:{no:no,supplier_guid:supplier_guid},

        success:function(data){
          // alert(data);
          $('#csupplier').prop('checked', false);
          $('#csupplier_group').prop('checked', false);
          $('#methodSupplier').css('display' ,'none');
          $('#methodSupplierGroup').css('display' ,'none');
          // alert(data);
          $('#supplier_code'+no).html(data);
          $('.supplier_code').select2();
          // $('#add_supplier').css('display' ,'block');

        }

        }); 

    });   

    $(document).on("click","#remove_supplier",function() {
        var no = $(this).attr('no');
        // alert(no);

        $('#div'+no).remove(); 

    });       

    $(document).on("change",".supplier_name",function() {
        var clicked = $(this).attr('no');
        var clicked_value = $(this).val();

          $('.supplier_name').each(function(){
             var supplier_code = $(this).val();
             var clicked_no = $(this).attr('no');
             // alert(clicked_no);
             if(clicked_no != clicked)
             {
              if(clicked_value == supplier_code)
              {     
                        alert('Cannot Select Same Supplier Name');      
                        var object = [];
                        $('.supplier_name').each(function(){
                           var supplier_code = $(this).val();
                           // alert(supplier_code);
                           object.push({"supplier_code":supplier_code});
                        });
                        var details = JSON.stringify(object);

                        $.ajax({

                        url:"<?php echo base_url(); ?>index.php/Supplier_setup_new/check_supplier_name_multiple2",

                        method:"POST",

                        data:{supplier_code:details,no:clicked},

                        success:function(data){
                          $('#csupplier').prop('checked', false);
                          $('#csupplier_group').prop('checked', false);
                          $('#methodSupplier').css('display' ,'none');
                          $('#methodSupplierGroup').css('display' ,'none');
                          $('#supplier_name'+clicked).html(data);

                          $.ajax({

                          url:"<?php echo base_url(); ?>index.php/Supplier_setup_new/check_supplier_code_multiple_empty",

                          method:"POST",

                          data:{no:clicked},

                          success:function(data){
                            $('#csupplier').prop('checked', false);
                            $('#csupplier_group').prop('checked', false);
                            $('#methodSupplier').css('display' ,'none');
                            $('#methodSupplierGroup').css('display' ,'none');
                            $('#supplier_code'+clicked).html(data);
                          }

                          });
                        }

                        }); 
              }
             }
             // else
             // {
             //  alert(2);
             // }
          });


          // $.ajax({

          // url:"<?php echo base_url(); ?>index.php/Supplier_setup_new/check_supplier_multiple2",

          // method:"POST",

          // data:{supplier_code:details,no:0},

          // success:function(data){
          //   $('#csupplier').prop('checked', false);
          //   $('#csupplier_group').prop('checked', false);
          //   $('#methodSupplier').css('display' ,'none');
          //   $('#methodSupplierGroup').css('display' ,'none');
          //   // alert(data);
          //   $('#div0').html(data);

          // }

          // });//close ajax
    });           

});
</script>
 