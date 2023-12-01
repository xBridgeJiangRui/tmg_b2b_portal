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
    <div class="col-md-12">
      <div class="box box-default">
        <!-- head -->
        <div class="box-header with-border">
          <h3 class="box-title">Archived Document</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- head -->
        <!-- body -->
        <div class="box-body">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-2"><b>Retailer Name</b></div>
              <div class="col-md-4">
                <select name="acc_guid" id="acc_guid" class="form-control select2">
                  <option value="" disabled selected>-Select Retailer Name-</option>
                  <?php 
                    foreach($get_acc as $row)
                    {
                        ?>
                        <option value="<?php echo $row->acc_guid?>"><?php echo $row->acc_name?></option>
                        <?php
                    }
                  ?>
                </select>
              </div>
              <div class="clearfix"></div><br>
              
              <div class="col-md-2"><b>Document Type</b></div>
              <div class="col-md-4">
                <select name="doc_type" id="doc_type" class="form-control select2">
                  <option value="" disabled selected>-Select Document Type-</option>
                  <option value="pomain">Purchase Order (PO)</option>
                  <option value="grmain">Goods Received Note (GRN)</option> 
                  <option value="grmain_dncn">Goods Received Diff Advice (GRDA)</option> 
                  <option value="dbnotemain">Purchase Return DN (PRDN)</option> 
                  <option value="cnnotemain"> Purchase Return CN (PRCN)</option> 
                  <option value="pdn_amt">Purchase DN (PDN)</option> 
                  <option value="pcn_amt">Purchase CN (PCN)</option> 
                  <option value="promo_taxinv">Promotion Claim Tax Invoice (PCI)</option> 
                  <option value="discheme_taxinv">Display Incentive (DI)</option>
                  <option value="other_doc">Accouting Document</option>
                </select>
              </div>
              <div class="clearfix"></div><br>
              
              <span id="append_other_doc"></span>
              
              <div class="col-md-2"><b>RefNo</b></div>
              <div class="col-md-4">
                <input type="text" class="form-control" id="insert_refno" name="insert_refno" placeholder="Please Insert RefNo Here" autocomplete = "off"/>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Supplier Code (Optional)</b></div>
              <div class="col-md-4">
                <input type="text" class="form-control" id="insert_scode" name="insert_scode" autocomplete = "off"/>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-12">

                <?php
                if(in_array('IAVA',$_SESSION['module_code']))
                {
                  ?>
                  <button type="button" id="search_data" class="btn btn-primary" ><i class=""></i> Search </button>
                  <?php
                }
                ?>

              </div>

            </div>
          </div>
        </div>
        <!-- body -->
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Report</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concepts">
          <div id="accconceptCheck">


              <span id="append_report"></span>
    
            

          </div>

        </div>

      </div>
    </div>
  </div>
  
</div>
</div>
<script>
$(document).ready(function() {

    $(document).on('change', '#doc_type', function(){
        var doc_type = $(this).val();
        var acc_guid = $('#acc_guid').val();

        //alert(doc_type);die;

        if(acc_guid == '' || acc_guid == null || acc_guid == 'null')
        {
            alert('Please Select Retailer.');
            return;
        }

        if(doc_type == 'other_doc')
        {
            $.ajax({
                url:"<?php echo site_url('Archived_download/fetch_other_doc') ?>",
                method:"POST",
                data:{doc_type:doc_type,acc_guid:acc_guid},
                beforeSend:function(){
                //$('.btn').button('loading');
                },
                success:function(data)
                {
                    json = JSON.parse(data);
                    if (json.para1 == 'false') {
                        alert(json.msg);
                    //$('.btn').button('reset');
                    }else{
                        //$('.btn').button('reset');
                        vendor = '';
                        body = '';

                        body += '<div class="col-md-2"><b>Other Document Type</b></div> <div class="col-md-4"> <select name="other_doc_type" id="other_doc_type" class="form-control select2"> </select> </div> <div class="clearfix"></div><br>';

                        Object.keys(json['query']).forEach(function(key) {

                            vendor += '<option value="'+json['query'][key]['code']+'" >'+json['query'][key]['description']+' </option>';

                        });

                        $('#append_other_doc').html(body);
                        $('#other_doc_type').select2().html(vendor);
                        //$('#append_branch').select2().html(json.option);
                    }
                }//close success
            });//close ajax 
        }
        else
        {
            $('#append_other_doc').html('');
        }

    });//CLOSE ONCLICK  

    $(document).on('click', '#search_data', function(){
        var acc_guid = $('#acc_guid').val();
        var doc_type = $('#doc_type').val();
        var refno = $('#insert_refno').val();
        var scode = $('#insert_scode').val();
        var other_doc_type = $('#other_doc_type').val();

        //alert(doc_type);die;

        if(acc_guid == '' || acc_guid == null || acc_guid == 'null')
        {
            alert('Please Select Retailer.');
            return;
        }

        if(doc_type == '' || doc_type == null || doc_type == 'null')
        {
            alert('Please Select Document Type.');
            return;
        }

        if(refno == '' || refno == null || refno == 'null')
        {
            alert('Please Insert RefNo.');
            return;
        }

        $.ajax({
            url:"<?php echo site_url('Archived_download/fetch_report') ?>",
            method:"POST",
            data:{doc_type:doc_type,acc_guid:acc_guid,refno:refno,scode:scode,other_doc_type:other_doc_type},
            beforeSend:function(){
              $('.btn').button('loading');
              $('#append_report').html('');
            },
            success:function(data)
            {
              $('.btn').button('reset');
              json = JSON.parse(data);
              if (json.para1 == 'false') {
                alert(json.msg);
                //$('.btn').button('reset');
              }else{
                if(json.redirect == 'true')
                {
                  $('#append_report').html('<p>Please Click here to view PDF : <a href="'+json.url+'" target="_blank">View PDF 1</a> </p> <br/> <p>Please Click here to view PDF : <a href="'+json.url2+'" target="_blank">View PDF 2</a> </p> ' );
                  //window.open(json.url, '_blank');
                }
                else
                {
                  $('#append_report').html('<embed id="embed" height="500px" width="100%" src="'+json.url+'"></embed>');
                }
              }
            }//close success
        });//close ajax 
    });//CLOSE ONCLICK  
});
</script>
