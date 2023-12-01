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
          <!-- <?php if($_SESSION['customer_guid'] != '8D5B38E931FA11E79E7E33210BD612D3'){echo $hide_url;} ?> -->
          <!-- <a class="btn btn-app" style="color:#367FA9" href="<?php echo $other ?>">
            <i class="fa fa-external-link-square"></i> View Others
          </a> -->
          <!-- <a class="btn btn-app pull-right"  href="<?php echo site_url('External_doc/upload');?>">
            <i class="fa fa-print"></i> Upload Doc
          </a>  -->
          
  </div>
  <!-- filter by -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <!-- head -->
        <div class="box-header with-border">
          <h3 class="box-title">Upload Doc</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- head -->
        <!-- body -->
        <div class="box-body">
          <div class="col-md-12">
            <div class="row">
              <form role="form" method="POST" id="myForm" action="<?php echo site_url('general/po_filter');?>">

              <div class="col-md-2"><b>Doc Charge Type</b></div>
              <div class="col-md-4">
                <select class="form-control" name="charge_type" id="charge_type">
                  <option value="" disabled selected>--SELECT--</option>
                  <option value="external_doc" <?php if('external_doc' ==  $charge_type ){ echo "selected";} ?>>External Doc</option>
                  <option value="other_doc" <?php if('other_doc' ==  $charge_type ){ echo "selected";} ?>>Others Doc</option>
                  <option value="archived_doc" <?php if('archived_doc' ==  $charge_type ){ echo "selected";} ?>>Archived Doc</option>
                  <option value="extra_doc" <?php if('extra_doc' ==  $charge_type ){ echo "selected";} ?>>Extra Doc</option>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Doc Type</b></div>
              <div class="col-md-4">
                <select class="form-control" name="doc_type" id="doc_type">
                  <option value="" disabled selected>--SELECT--</option>
                  <option value="PO">PO</option>
                  <option value="GRN">GRN</option>
                  <option value="GRDA">GRDA</option>
                  <option value="PRDN">PRDN</option>
                  <option value="PRCN">PRCN</option>
                  <option value="PDN">PDN</option>
                  <option value="PCI">Promo Tax Invoice</option>
                  <option value="DI">Display Incentive</option>
                  <option value="ACC">Account Document</option>
                  <option value="SI">Sale Invoice</option>
                </select>
                 <!-- <input id="refno" name="refno" type="text" autocomplete="off" class="form-control pull-right" disabled value="<?php echo $doc_type_description;?>"> -->
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Upload Doc Format</b></div>
              <div class="col-md-4">
                 <input id="refno" name="refno" type="text" autocomplete="off" class="form-control pull-right" disabled value="<?php echo $upload_doc_format_description;?>">
                 <small style="color:red;font-weight:bold;font-style:italic;">Example File Name: ABCPO21110123_A123_2021-12-31_200.00</small>
        </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Upload Doc</b></div>
              <div class="col-md-4">
                 <input id="file_upload" name="file_upload[]" type="file" autocomplete="off" class="form-control pull-right" value="<?php echo $upload_doc_format_description;?>" accept=".pdf" multiple>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Upload Doc List</b></div>
              <div class="col-md-8">
                 <table class="table table-bordered table-hover">
                  <thead>
                      <th>No</th><th>File Name</th><th>Message</th>
                  </thead>
                  <tbody id="table_promp">
                  </tbody>
                 </table>
              </div>
              <div class="clearfix"></div><br>
              
              <div class="col-md-12">
                
                <button type="button" id="upload_file" class="btn btn-primary"><i class="fa fa-search"></i> Upload</button>
                <button type="button" id="reset" class="btn btn-default"><i class="fa fa-repeat"></i> Reset</a></button>

                <!-- <button type="submit" id="submit_form" class="btn btn-primary"><i class="fa fa-search"></i> Searchs</button> -->
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                <!-- <a href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=&first=1" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a> -->
                
              </div>
                </form>
            </div>
          </div>
        </div>
        <!-- body -->

      </div>
    </div>
    
  </div>
  <!-- filter by -->

</div>
</div>
<script src="<?php echo base_url('asset/plugins/jQuery/jquery-2.2.3.min.js')?>"></script>
<script>
$(document).ready(function(){
  error = 1;
  //doc_type = "<?php echo $doc_type;?>";
  $(document).on('click','#file_upload',function(event){
    $('#table_promp').html('');
    $('#file_upload').val('');
  });
  $(document).on('change','#file_upload',function(event){
      var charge_type = $('#charge_type').val();
      var doc_type = $('#doc_type').val();

      if(charge_type == '' || charge_type == null || charge_type == 'null')
      {
        alert('Please Select Charge Type.');
        return;
      }

      if(doc_type == '' || doc_type == null || doc_type == 'null')
      {
        alert('Please Select Doc Type.');
        return;
      }

      error = 0;
      // alert();
      var table = '';
      pass_refno = '';
      var loop = 1;
      var loopfile = 0;
      var _this = $(this);
      for (i = 0; i < $(this).get(0).files.length; i++) {
          // names.push($(this).get(0).files[i].name);
          $.ajax({
                type: "POST",
                url: "<?php echo site_url('External_doc/check_format');?>",
                dataType: 'json',
                data : {filename:_this.get(0).files[i].name,charge_type:charge_type,doc_code:'LV',doc_type:doc_type},
                success: function(data){
                      // alert(data.message);
                      message = data.message;
                      file_name = data.file_name;
                      if(data.status == 'true')
                      {
                        // correct = 1;
                      }
                      else
                      {
                        error++;
                      }
                      // alert(_this.get(0).files[loopfile].name+message);
                      table +='<tr><td>'+loop+'</td>'+'<td>'+file_name+'</td><td>'+message+'</td></tr>';
                      pass_refno += data.pass_refno+',';
                      // alert(loop+''+_this.get(0).files.length);
                      if(loop == _this.get(0).files.length)
                      {
                        $('#table_promp').html(table);
                        // alert(pass_refno);
                      }
                      loopfile = loopfile+1;
                      loop = loop+1;
                      // $('#table_promp').append(table);
                }
          });
          // alert();
          // loop = i+1;
          // table +='<tr><td>'+loop+'</td>'+'<td>'+$(this).get(0).files[i].name+'</td><td>'+message+'</td></tr>';
      }
      // alert(table);
      // $('#table_promp').html(table);
      // alert(table);
  });

  $(document).on('click','#upload_file',function(event){
    //alert('haha'); die;
    var charge_type = $('#charge_type').val();
    var doc_type = $('#doc_type').val();

    if(charge_type == '' || charge_type == null || charge_type == 'null')
    {
      alert('Please Select Charge Type.');
      return;
    }

    if(doc_type == '' || doc_type == null || doc_type == 'null')
    {
      alert('Please Select Doc Type.');
      return;
    }

    if($('#file_upload').val() == '' || $('#file_upload').val() == null)
    {
      alert('Please Select File To Upload.');
      return;
    }
    
    if(error == 0)
    {
      var formData = new FormData();
      var cloopfile = 0;
      for (i = 0; i < $('#file_upload').get(0).files.length; i++) {
        formData.append('file_upload[]', $('#file_upload')[0].files[cloopfile]);
        // alert(cloopfile+''+$('#file_upload')[0].files[cloopfile].name);
        cloopfile = cloopfile+1;
        // alert();
      }
      // $.each($('#file_upload')[0].files, function(i, file) {
      //     formData.append('file_upload', file);
      // });
      formData.append('charge_type', charge_type);
      formData.append('doc_code', 'LV');
      formData.append('doc_type', doc_type);

      $.ajax({
          url:"<?php echo site_url('External_doc/upload_doc_ajax');?>",
          method:"POST",
          data: formData,
          dataType: 'json',
          processData: false, // Don't process the files
          contentType: false, // Set content type to false as jQuery will tell the server its a query string request
          beforeSend : function()
          { 
            // alertmodal('<?php echo $this->lang->line('importing_data'); ?>');
            // $('#alertmodal .icons').html('<i class="fa fa-info fa-5x"></i>');
            // $('#alertmodal .msg').html(data);
            // $('.btn').button('loading');

            // blink_interval = setInterval(blinker, 1000);

          },
          complete : function()
          { 
            // clearInterval(blink_interval);
            // $('.btn').button('reset');
          },
          success:function(data)
          {
            if(data.status == 'true')
            {
              $('#file_upload').val('');
              alert(data.message);
              location.reload();
            }
            else
            {
              alert(data.message);
              location.reload();
            }
          }//close success
      });//close ajax
      // alert(pass_refno);
      // alert('success');
    }
    else
    {
      alert('Got error cannot proceed,please refer message column.');
    }
  });

});
</script>