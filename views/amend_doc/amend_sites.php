<style type="text/css">
.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: black;
}

.content-wrapper{
  min-height: 850px !important; 
}

</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <!-- head -->
        <div class="box-header with-border">
          <h3 class="box-title">Reset/Hide Document</h3><br>
          <div class="box-tools pull-right">
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
          </div>
        </div>
        <!-- head -->
        <!-- body -->
        <div class="box-body">
          <div class="col-md-12">
            <div class="row">

              <div class="col-md-2"><b>Retailer Name</b></div>
              <div class="col-md-4">
                <input tpye="text" class="form-control" id="retailer_name" name="retailer_name" value="<?php echo $customer_name?>" disabled />
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Action Type</b></div>
              <div class="col-md-4">
                <select name="action_type" id="action_type" class="form-control">
                  <option value="" disabled selected>-Select Action-</option>
                  <option value="status_to_new">Status to New</option>
                  <?php if(in_array('IAVA',$this->session->userdata('module_code')))
                  {
                    ?>
                    <!-- <option value="status_to_accept">New to Accepted</option> -->
                     <?php
                  }
                  ?>
                  <option value="hide_the_data">Hide Document</option>
                  <?php if(in_array('IAVA',$this->session->userdata('module_code')))
                  {
                    ?>
                    <option value="show_the_data">Show Document</option>
                    <option value="before_go_live">Hide Before Go Live Document</option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Document Type</b></div>
              <div class="col-md-4">
                <select name="doc_type" id="doc_type" class="form-control">
                  <option value="" disabled selected>-Select Action Type-</option>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Search RefNo</b></div>
              <div class="col-md-4">
                <input type="text" class="form-control" id="insert_refno" name="insert_refno" disabled="disabled" placeholder="Please Select Document Type" />
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>RefNo</b></div>
              <div class="col-md-4">
                <select class="form-control select2" multiple="multiple" id="ref_num" name="ref_num">
                  <option value='' disabled>-Please input RefNo-</option>
                </select>
              </div>
              <div class="col-md-2">
                <button id="location_all_dis" class="btn btn-sm btn-danger" type="button" >X</button>
              </div>
              <div class="clearfix"></div><br>

              <?php if(in_array('IAVA',$this->session->userdata('module_code')))
              {
                ?>
                <div class="col-md-2"><b>Period Code</b></div>
                <div class="col-md-4">
                  <select class="form-control select2" name="period_code" id="period_code" disabled="true" >
                    <option value='' disabled>-Hide Before Go Live Documents-</option>
                  </select>
                </div>
                <div class="clearfix"></div><br>
                <?php
              }
              ?>

              <div class="col-md-2"><b>Remark</b></div>
              <div class="col-md-4">
                <input type="text" class="form-control" id="insert_remark" name="insert_remark" placeholder="Please Insert Remark" />
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-12">
                <span id="btn_name"></span>
              </div>

            </div>
          </div>
        </div>
        <!-- body -->

      </div>
    </div>
  </div>

</div>
</div>
<script>
$(document).ready(function() {

  $(document).on('click', '#location_all_dis', function(){
    // alert();
    $("#ref_num option").prop('selected',false);
    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('change', '#action_type', function(){
    // alert();
    $('#doc_type').val('');
    $('#ref_num').select2().html('');
    $('#period_code').select2().html('');
    var action_type = $('#action_type').val();

    if(action_type == 'status_to_new')
    {
      $('#btn_name').html('<button type="button" id="update_data" class="btn btn-primary" ><i class="fa fa-refresh"> Reset</i> </button>');
      <?php if(in_array('IAVA',$this->session->userdata('module_code')))
      {
        ?>
        $('#doc_type').html('<option value="" disabled selected>-Select Document Type-</option><option value="pomain">Purchase Order (PO)</option>');
        //<option value="grmain">Goods Received Note (GRN)</option> <option value="grda">Goods Received Diff Advice (GRDA)</option> <option value="dbnotemain">Purchase Return DN (PRDN)</option> <option value="cnnotemain"> Purchase Return CN (PRCN)</option> <option value="cndn_amt">Purchase DN/CN (PDN)</option> <option value="pci">Promotion Claim Tax Invoice (PCI)</option> <option value="display_incentive">Display Incentive (DI)</option>
        <?php
      }
      else
      {
        ?>
        $('#doc_type').html('<option value="" disabled selected>-Select Document Type-</option><option value="pomain">PO</option>');
        <?php
      }
      ?>
      //<option value="grmain">GRN</option> <option value="grda">GRDA</option> <option value="dbnotemain">PRDN</option> <option value="cnnotemain">PRCN</option> <option value="cndn_amt">PDN</option> <option value="pci">Promo Tax Invoice</option> <option value="display_incentive">Display Incentive</option>
    }
    else if(action_type == 'status_to_accept')
    {
      $('#btn_name').html('<button type="button" id="update_data" class="btn btn-primary" ><i class="fa fa-refresh"> Reset</i> </button>');
      <?php if(in_array('IAVA',$this->session->userdata('module_code')))
      {
        ?>
        $('#doc_type').html('<option value="" disabled selected>-Select Document Type-</option><option value="pomain">Purchase Order (PO)</option>');
        <?php
      }
      ?>
      //<option value="grmain">Goods Received Note (GRN)</option> <option value="grda">Goods Received Diff Advice (GRDA)</option> <option value="dbnotemain">Purchase Return DN (PRDN)</option> <option value="cnnotemain"> Purchase Return CN (PRCN)</option> <option value="cndn_amt">Purchase DN/CN (PDN)</option> <option value="pci">Promotion Claim Tax Invoice (PCI)</option> <option value="display_incentive">Display Incentive (DI)</option>
    }
    else if(action_type == 'hide_the_data')
    {
      $('#btn_name').html('<button type="button" id="update_data" class="btn btn-primary" ><i class="fa fa-eye-slash"> Hide</i> </button>');
      $('#doc_type').html('<option value="" disabled selected>-Select Document Type-</option><option value="pomain">Purchase Order (PO)</option><option value="grmain">Goods Received Note (GRN)</option> <option value="grda">Goods Received Diff Advice (GRDA)</option> <option value="dbnotemain">Purchase Return DN (PRDN)</option> <option value="cnnotemain"> Purchase Return CN (PRCN)</option> <option value="cndn_amt">Purchase DN/CN (PDN)</option> <option value="pci">Promotion Claim Tax Invoice (PCI)</option> <option value="display_incentive">Display Incentive (DI)</option>');
    }
    else if(action_type == 'show_the_data')
    {
      $('#btn_name').html('<button type="button" id="update_data" class="btn btn-primary" ><i class="fa fa-eye"> Show</i> </button>');
      <?php if(in_array('IAVA',$this->session->userdata('module_code')))
      {
        ?>
        $('#doc_type').html('<option value="" disabled selected>-Select Document Type-</option><option value="pomain">Purchase Order (PO)</option><option value="grmain">Goods Received Note (GRN)</option> <option value="grda">Goods Received Diff Advice (GRDA)</option> <option value="dbnotemain">Purchase Return DN (PRDN)</option> <option value="cnnotemain"> Purchase Return CN (PRCN)</option> <option value="cndn_amt">Purchase DN/CN (PDN)</option> <option value="pci">Promotion Claim Tax Invoice (PCI)</option> <option value="display_incentive">Display Incentive (DI)</option>');
        <?php
      }
      else
      {
        ?>
        $('#doc_type').html('<option value="" disabled selected>-Select Document Type-</option>');
        <?php
      }
      ?>
    }
    else if(action_type == 'before_go_live')
    {
      $('#btn_name').html('<button type="button" id="update_data" class="btn btn-primary" ><i class="fa fa-eye-slash"> Hide</i> </button>');
      <?php if(in_array('IAVA',$this->session->userdata('module_code')))
      {
        ?>
        $('#doc_type').html('<option value="" disabled selected>-Select Document Type-</option><option value="pomain">Purchase Order (PO)</option><option value="grmain">Goods Received Note (GRN)</option> <option value="grda">Goods Received Diff Advice (GRDA)</option> <option value="dbnotemain">Purchase Return DN (PRDN)</option> <option value="cnnotemain"> Purchase Return CN (PRCN)</option> <option value="cndn_amt">Purchase DN/CN (PDN)</option> <option value="pci">Promotion Claim Tax Invoice (PCI)</option> <option value="display_incentive">Display Incentive (DI)</option>');
        <?php
      }
      ?>
    }
    else
    {
      $('#btn_name').html('');
      $('#doc_type').html('<option value="" disabled selected>-Select Action Type-</option>');
    }

  });//CLOSE ONCLICK  

  $(document).on('change', '#doc_type', function(){
    
    $('#insert_refno').removeAttr("disabled");
    $('#ref_num').removeAttr("disabled");
    $('#period_code').prop("disabled",true);
    $('#insert_refno').attr("placeholder","Please Insert Ref No for searching...");
    $('#insert_refno').val('');
    $('#ref_num').select2().html('');
    $('#period_code').select2().html('');
    var doc_type = $('#doc_type').val();
    var action_type = $('#action_type').val();

    if(action_type == 'before_go_live')
    {
      $('#insert_refno').prop("disabled",true);
      $('#ref_num').prop("disabled",true);
      $('#period_code').removeAttr("disabled");
      <?php
      if(in_array('IAVA',$this->session->userdata('module_code')))
      {
        ?>
        $.ajax({
          url : "<?php echo site_url('amend_doc/fetch_period_code'); ?>",
          method:"POST",
          data:{doc_type:doc_type},
          success:function(data)
          {
           json = JSON.parse(data); 
           if (json.para1 == '1') {
             alert(json.msg);
           }
           else
           {
             code = '';

             code = '<option value="" selected>Please Select Period Code</option>';

             Object.keys(json['period_code']).forEach(function(key) {

             code += '<option value="'+json['period_code'][key]['period_code']+'">'+json['period_code'][key]['period_code']+'</option>';

             });
             $('#period_code').select2().html(code);
           }
              
          }
        });
        <?php
      }
      ?>
    }
  });//CLOSE ONCLICK  

  $(document).on('change', '#insert_refno', function(){
    
    var refno = $(this).val();
    var doc_type = $('#doc_type').val();
    var action_type = $('#action_type').val();

    if((doc_type == '') || (doc_type == 'null') || (doc_type == null))
    {
      alert('Please insert Document Type.');
      return;
    }

    if((action_type == '') || (action_type == 'null') || (action_type == null))
    {
      alert('Please insert Action Type.');
      return;
    }

    if(refno != '')
    {
       $.ajax({
       url : "<?php echo site_url('amend_doc/fetch_ref_no'); ?>",
       method:"POST",
       data:{refno:refno,doc_type:doc_type,action_type:action_type},
       success:function(data)
       {

        json = JSON.parse(data); 
        if (json.para1 == '1') {
          alert(json.msg);
        }
        else
        {
          code = '';

          Object.keys(json['query_data']).forEach(function(key) {

          code += '<option value="'+json['query_data'][key]['refno']+'">'+json['query_data'][key]['refno']+'</option>';

          });
          $('#ref_num').select2().html(code);
        }
           
       }
      });
    }
    else
    {
      $('#ref_num').select2().html('<option value="" disabled>No RefNo</option>');
    }
  });//CLOSE ONCLICK  

  $(document).on('click','#update_data',function(){

    var ref_no = $('#ref_num').val();
    var doc_type = $('#doc_type').val();
    var action_type = $('#action_type').val();
    var remark = $('#insert_remark').val();

    if(action_type != 'before_go_live')
    {
      var period_code = '';

      if((ref_no == '') || (ref_no == 'null') || (ref_no == null))
      {
        alert('Please insert Ref Number.');
        return;
      }
    }
    else
    {
      var period_code = $('#period_code').val();

      if((period_code == '') || (period_code == 'null') || (period_code == null))
      {
        alert('Please select Period Code.');
        return;
      }
    }

    if((doc_type == '') || (doc_type == 'null') || (doc_type == null))
    {
      alert('Please insert Document Type.');
      return;
    }

    if((action_type == '') || (action_type == 'null') || (action_type == null))
    {
      alert('Please insert Action Type.');
      return;
    }

    if((remark == '') || (remark == 'null') || (remark == null))
    {
      alert('Please insert Remark.');
      return;
    }

    confirmation_modal('Are you sure want to do Action?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
          url:"<?php echo site_url('amend_doc/run_amend_function');?>",
          method:"POST",
          data:{ref_no:ref_no,doc_type:doc_type,action_type:action_type,period_code:period_code,remark:remark},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);

            if (json.para1 == '1') {
              $("#alertmodal").modal('hide');
              alert(json.msg);
              $('.btn').button('reset');
            }else{
              $("#alertmodal").modal('hide');
              alert(json.msg);
              location.reload();
              // setTimeout(function() {
              //   $('.btn').button('reset');
              //   vendor_table(register_guid);
              // }, 300);
            }//close else
          }//close success
      });//close ajax
    });//close document yes click
  });
});
</script>
