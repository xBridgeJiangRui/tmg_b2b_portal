<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
  <?php
  if($this->session->userdata('message'))
  {
    ?>
    <div class="alert alert-success text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>

  <?php
  if($this->session->userdata('warning'))
  {
    ?>
    <div class="alert alert-danger text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('warning') <> '' ? $this->session->userdata('warning') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>
<div class="col-md-12">
       <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?> ">
          <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>
        <?php 
        if($check_uploaded_image_strb == '1')
        {
          ?>
          <a class="btn btn-app" id="view_image" style="float:right";>
          <i class="fa fa-file-image-o"></i> Image
          </a>
          <?php
        }
        ?>
  </div>
<?php 
            if(in_array('VPRDN',$_SESSION['module_code']) && $this->session->userdata('customer_guid') == '1F90F5EF90DF11EA818B000D3AA2CAA9' )
            // || $this->session->userdata('customer_guid') == '403810171FA711EA9BB8E4E7491C3E1E'
            // || $this->session->userdata('customer_guid') == '13EE932D98EB11EAB05B000D3AA2838A'
              {
                if($xtype == 'DEBIT')
                {
            ?>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php  echo 'e-CN'; ?></h3><br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <div class="box-body">
          <div class="col-md-12">
                <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 
                  <table id="smstable" class="tablesorter table table-striped table-bordered table-hover"> 
                    <form  method="post" id="form_ECN" name="form_ECN" >
                      <thead>
                        <tr>
                          <th>PRDN Refno</th>
                          <th>Type</th>
                          <th>Sup CN No.</th>
                          <th>Sup CN Date</th>
                          <th>Amount</th>
                          <th>Tax Amount</th>
                          <th>Total Incl Tax</th>
                          <th>Action</th>
                          <?php
                          if($check_upload_cn == 1)
                          {
                          ?>
                          <th>Uploaded Document</th>
                          <?php
                          }
                          ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($sup_cn_header->result() as $row){ ?>
                          <td><?php echo $row->RefNo ?></td>
                          <td><?php echo 'PRDNCN' ?></td>
                           <td><input class="form-control" type="text" name="sup_cn_no[]" value="<?php echo $row->sup_cn_no ?> " required autocomplete="off" <?php if($row->status == 'cn_generated') { echo 'readonly' ;} ?>  > </td>
                              <td><input class="form-control" type="text" name="sup_cn_date[]" value="<?php echo $row->sup_cn_date ?> " required autocomplete="off" <?php if($row->status == 'cn_generated') { echo 'readonly' ;} ?> > </td>
                          <td style="text-align: right"><?php echo number_format($row->Amount,2)?></td>
                          <td style="text-align: right"><?php echo number_format($row->gst_tax_sum,2)?></td>
                          <td style="text-align: right"><?php echo number_format($row->Amount+$row->gst_tax_sum,2)  ?></td> 
                          <td>
                              <?php if($check_status->row('status') == 'cn_generated')
                            {
                            ;?>
                                <a target="_blank" href="<?php echo site_url('panda_prdncn/view_ecn?refno='.$row->RefNo.'&transtype='.'PRDNCN') ?>" class="btn btn-xs btn-primary" ><i class="glyphicon glyphicon-eye-open"></i>View E-CN</a>

                                <?php 
                                if($valid_reupload_time == '1' && $exists_upload_cn_file == '0' && $check_upload_cn == '1')
                                {
                                  ?>
                                  <button class="btn btn-xs btn-warning" type="button" id="upload_cn_doc" refno="<?php echo $row->RefNo ?>">Re-Upload Supplier CN</button>
                                  <?php
                                }
                                ?>

                            <?php
                            }
                            ;?>
                            <?php if($check_status->row('status') != 'cn_generated')
                            {
                                  if($exists_upload_cn_file == 1)
                                  {
                            ?>
                                      <button class="btn btn-xs btn-success" type="button" onclick="form_submit('<?php echo $row->RefNo ?>','PRDNCN')" class="close" data-dismiss="alert">Generate E-CN</button>
                            <?php
                                  }
                                  else
                                  {
                            ?>
                                      <button id="disabled_alert_cn" class="btn btn-xs btn-success" type="button" class="close" onclick="alert('Please Upload Supplier CN first')" data-dismiss="alert">Generate E-CN</button>
                            <?php
                                  }
                                  if($check_upload_cn == 1)
                                  {
                            ;?>
                                <button class="btn btn-xs btn-primary" type="button" id="upload_cn_doc" refno="<?php echo $row->RefNo ?>">Upload Supplier CN</button>
                            <?php
                                  }
                            }
                            ;?>   



                                <input type="hidden" name="gr_refno" value="<?php echo $_REQUEST['trans']?>">  
                            <input type="hidden" name="prdn_loc" value="<?php echo $_REQUEST['loc']?>">                                
                            <input type="hidden" name="ecn_refno[]" value="<?php echo $row->RefNo?>"> 
                            <input type="hidden" name="ecn_type[]" value="<?php echo 'PRDNCN'?>">
                            <input type="hidden" name="ecn_varianceamt[]" value="<?php echo $row->Amount?>"> 
                            <input type="hidden" name="ecn_tax_rate[]" value="<?php echo '0' ?>"> 
                            <input type="hidden" name="ecn_gst_tax_sum[]" value="<?php echo $row->gst_tax_sum?>"> 
                            <input type="hidden" name="ecn_total_incl_tax[]" value="<?php echo $row->Amount+$row->gst_tax_sum   ?>"> 
                          <input type="hidden" name="ecn_customer_guid[]" value="<?php echo $_SESSION['customer_guid'] ?>">
                          <input type="hidden" name="ecn_loc[]" value="<?php echo $_REQUEST['loc'] ?>"> 
                          <input type="hidden" name="ecn_rows[]" value="<?php echo $row->rowx ?>">
                          <input type="hidden" name="current_loc" value="<?php echo $_REQUEST['loc'] ?>">
                            
                          </td>
                          <?php 
                          if($check_upload_cn == 1)
                          {
                            if($exists_upload_cn_file == 1)
                            {
                              if($valid_reupload_time == '1')
                              {
                                ?>
                                <td>
                                  Uploaded <a target="_blank" href="<?php echo site_url('Upload/view_upload?parameter='.$row->RefNo.'&parameter2='.$file_supplier_guid.'&parameter3='.$file_upload_type);?>"><button class="btn btn-xs btn-success" type="button"">View Supplier CN</button></a> 

                                  <button class="btn btn-xs btn-danger" type="button" id="unlink_path" db_refno="<?php echo $_REQUEST['trans']?>" >Remove Document</button>

                                </td>
                                <?php
                              }
                              else
                              {
                                ?>
                                <td>Uploaded <a target="_blank" href="<?php echo site_url('Upload/view_upload?parameter='.$row->RefNo.'&parameter2='.$file_supplier_guid.'&parameter3='.$file_upload_type);?>"><button class="btn btn-xs btn-success" type="button"">View Supplier CN</button></a></td>
                                <?php
                              }

                            }
                          else
                          {
                          ?>
                          <td>Not Upload</td>
                          <?php  
                          }
                          }
                          ?>
                        <?php }?>
                      </tbody>
                    </form>
                  </table>
                </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php }} ?>


  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php  echo $title; ?></h3><br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
      <div class="box-body">
        <div class="col-md-12">
                <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto">

                  <?php 

                  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);


                  if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) { // && stripos($ua,'mobile') !== false) { ?>

                  <embed src="https://docs.google.com/gview?embedded=true&url=<?php echo $filename; ?>&amp;embedded=true" width="100%" style="border: none;height:20em"/> 

                  <?php  } else { ?>

                  <?php if($file_headers[0] != 'HTTP/1.1 404 Not Found') { ?>
                        <embed src="<?php echo $filename; ?>" width="100%" height="500px" style="border: none;"/> If browser does not support PDFs. Please download the PDF to view it: <a href="<?php echo $filename; ?>">Download PDF</a> 
                    <?php } else 
                        {  
                          echo 'pdf not found'; 
                        }
                    ?>


                  <?php } ?>

                 
                    
                </div>
        </div>
      </div>
    </div>
</div>
</div>
<?php //  echo var_dump($_SESSION); ?>
</div>
</div>

<!-- small modal -->

<script type="text/javascript">
$(document).ready(function(){


  $(document).on('click','#upload_cn_doc',function(){
    var refno = $(this).attr('refno');
    var doc_type = "<?php echo $xtype;?>";
    var loc = "<?php echo $_REQUEST['loc'];?>";
    // alert(refno);return;
    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Upload Supplier CN');

    methodd = '';
    methodd +='<form action="<?php echo site_url('Upload/upload_prdn_cn');?>" method="post" enctype="multipart/form-data" id="form_upload_prdn_cn">';
    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-6"><label>Refno</label><input type="text" id="add_refno" class="form-control input-sm" placeholder="Refno" style="text-transform:uppercase" value='+refno+' name="upload_cn_refno" readonly required/></div>';

    methodd += '<div class="col-md-6"><label>File (Only PDF allow)</label><input type="file" id="add_group_name" class="form-control input-sm" name="upload_cn_doc" accept=".pdf" placeholder="Please Choose File" required/><input type="hidden" name="upload_prdn_cn_doc_type" value="'+doc_type+'"/><input type="hidden" name="upload_prdn_cn_loc" value="'+loc+'"/></div>';

    methodd += '</div>';
    methodd +='<input type="submit" id="upload_cn_doc_submit_button_hide" style="display:none;"></form>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="upload_cn_doc_submit_button" class="btn btn-success" value="Upload"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

  });

  $(document).on('click','#upload_cn_doc_submit_button',function(){
     // $("#form_upload_prdn_cn").submit();
     $("#upload_cn_doc_submit_button_hide").trigger('click');
  });

  setTimeout(function(){
    $('#large-modal').attr({"data-backdrop":"static","data-keyboard":"false"}); //to remove clicking outside modal for closing
  },300);

  $(document).on('click','#view_image',function(){
    var refno = "<?php echo $strb_refno;?>";
    var period_code = "<?php echo $strb_docdate;?>";
    var image_type = 'STRB'

	  //alert(image_type); die;
    if((refno == '') || (refno == null) || (refno == 'null'))
    {
      alert('Invalid Get STRB RefNo.');
      return;
    }

    if((period_code == '') || (period_code == null) || (period_code == 'null'))
    {
      alert('Invalid Get Period Code.');
      return;
    }

    if((image_type == '') || (image_type == null) || (image_type == 'null'))
    {
      alert('Invalid Get Image Details.');
      return;
    }

    $.ajax({
        url:"<?php echo site_url('Panda_return_collection/strb_view_image') ?>",
        method:"POST",
        data:{refno:refno,period_code:period_code,image_type:image_type},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            var url = json.file_path_list;
            var name = url.toString().substring(url.lastIndexOf('/') + 1);
            
            var modal = $("#large-modal").modal();

            modal.find('.modal-title').html( 'STRB RefNo : <b>' + refno + '</b>');

            methodd = '';

            //methodd +='<div class="col-md-12">';

            //methodd += '<input type="hidden" class="form-control input-sm" id="supplier_guid" value="'+supplier_guid+'" readonly/>';

            Object.keys(url).forEach(function(key) {

              var before_name = url[key].toString().split('?')[0];
              var after_name = before_name.toString().split("/").slice(-1)[0];
              
              methodd += '<div class="col-md-12"><label>'+after_name+'</label>';

              methodd += '<input style="float:right;" type="button" id="show'+key+'" class="btn btn-primary view_image_btn" value="Show" path_url="'+url[key]+'" key="'+key+'">';

              methodd += '<input style="float:right;margin-right:5px;" type="button" id="show'+key+'" class="btn btn-warning dl_image_btn" value="Download" path_url="'+url[key]+'" key="'+key+'">';

              methodd += '</div>';

              methodd += '<div class="clearfix"></div><br>';

              methodd += '<div class="col-md-12"><span id="image'+key+'"></span></div>';

            });

            //methodd += '</div>';

            methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);

            setTimeout(function(){

              $(document).off('click', '.view_image_btn').on('click', '.view_image_btn', function(){
                var path_url = $(this).attr('path_url');
                var key = $(this).attr('key');
                var value = $(this).val();
                $(this).hide();
                
                if(value == 'Show') 
                {
                  //$(this).attr('value','Hide');
                  //$(this).attr('class','btn btn-danger view_image_btn');
                  $('#image'+key+'').html('<embed src="'+path_url+'" width="100%" height="800px" style="border: none;" toolbar="0" id="image_view'+key+'"/>');
                  //alert('show image'); die;
                }
                
                // if(value == 'Hide') 
                // {
                //   $(this).attr('value','Show');
                //   $(this).attr('class','btn btn-primary view_image_btn');
                //   $('#image'+key+'').html('');
                //   //alert('show image'); die;
                // }
              });//close modal create

              $(document).off('click', '.dl_image_btn').on('click', '.dl_image_btn', function(){
                var path_url = $(this).attr('path_url');
                var value = $(this).val();
                
                if(value == 'Download') 
                {
                  var form = document.createElement('a');
                  form.href = path_url;
                  form.download = path_url;
                  document.body.appendChild(form);
                  form.click();
                  alert('Download Successful'); 
                  $(this).attr('value','Download Complete');
                  $(this).prop('disabled', true);
                } 
              });//close modal create
            },300);
          }//close else
        }//close success
      });//close ajax 
  });//close image process

  $(document).on('click','#unlink_path',function(){
    var db_refno = $(this).attr('db_refno');

    if((db_refno == '') || (db_refno == null) || (db_refno == 'null'))
    {
      alert('Invalid Get PRDN Refno.');
      return;
    }

    $.ajax({
        url:"<?php echo site_url('Panda_prdncn/cn_file_unlink') ?>",
        method:"POST",
        data:{db_refno:db_refno},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            alert(json.msg);
            $('.btn').button('reset');
          }
          else
          {
            $('.btn').button('reset');
            alert(json.msg);
            setTimeout(function(){
              location.reload();
            },300);
          }
        }//close success
    });//close ajax
  }); // close unlink file


});//close document ready

  function form_submit(refno , type)
  { 
    //window.location.reload();
  $("#form_ECN").attr("action", "<?php echo site_url('panda_prdncn/generate_ecn');?>?refno="+refno+"&transtype="+type); 
   // $("#form_ECN").attr("target", "_blank");
    $("#form_ECN").submit();


  }
$(function() {
  $('input[name="sup_cn_date[]"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
 /* $(this).find('[name="ext_docdate[]"]').val("");*/
});
</script>

