<style type="text/css">
.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: black;
}

.content-wrapper{ 
  min-height: 750px !important;   
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
          <h3 class="box-title">Upload Document</h3><br>
          <div class="box-tools pull-right">
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
          </div>
        </div>
        <!-- head -->
        <!-- body -->
        <div class="box-body">

          <div class="col-md-12">
            <div class="row">
              <div class="col-md-2"><b>Announcement</b></div>
              <div class="col-md-4">
                <input id="title" type="text" class="form-control" value='<?php echo $announcement_title?>' readonly>
              </div>
              <div class="clearfix"></div><br>
              <?php 
              if(($upload_docs_value == 1) && ($supplier_num_rows > 0))
              {
              ?>
              <div class="col-md-2"><b>Supplier</b></div>
              <div class="col-md-4">
                <select name="supplier_type" id="supplier_type" class="form-control">
                  <option value="" selected disabled>-Please Select Supplier-</option>
                  <?php foreach($get_supplier as $row)
                  {
                    ?>
                    <option value="<?php echo $row->supplier_guid?>" ><?php echo $row->supplier_name?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <div class="clearfix"></div><br>
              <?php
              }
              ?>
              <div class="col-md-2"><b>File</b></div>
              <div class="col-md-4">
                <input id="edit_upload_file" type="file" class="form-control" accept=".pdf,.xlsx,.xls,.docx">
              </div>
              <div class="col-md-4">
                <span id="edit_button_file_form"></span><button type="button" class="btn btn-danger" id="edit_reset_input" >Reset</button>
              </div>
              
              <div class="clearfix"></div><br>
              <?php foreach ($check_url as $key) {
                ?>
                 <div class="col-md-6"><b>Document : <?php echo $key->supplier_name?></b></div>
                 <div class="col-md-2">
                   <button type="button" class="btn btn-primary" id="view_docs" file_name = '<?php echo $key->url_value?>' supplier_name = '<?php echo $key->supplier_name?>'>View</button>

                  <button type="button" class="btn btn-danger" id="delete_docs" document_guid = '<?php echo $key->document_guid?>' supplier_guid = '<?php echo $key->supplier_guid?>' supplier_name = '<?php echo addslashes($key->supplier_name) ?>' announcement_guid = '<?php echo $key->announcement_guid?>'>Delete</button>
                 </div>
                 <div class="clearfix"></div><br>
                <?php
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php 
  if($check_num_rows !=0 )
  {
    ?>
    <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Document : <span id="append_title"></span></h3><br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
      <div class="box-body">
        <span id="append_view_docs">Please Click The View Button to see the PDF</span>

          </div>
        </div>
      </div>
    </div>
  <?php
  }
  ?>
  


</div>
</div>
<script>
$(document).ready(function() {
  var announcement_guid = '<?php echo $announcement_guid?>';
  var announcement_title = '<?php echo $announcement_title?>';
  var file_name = '<?php echo $file_name?>';
  var check_num_rows = '<?php echo $check_num_rows?>'; // sign document
  var supplier_num_rows = '<?php echo $supplier_num_rows?>'; // supplier got how many rows
  var upload_docs_value = '<?php echo $upload_docs_value?>';
  //alert(announcement_title);die;
  $(document).on('change','#edit_upload_file',function(e){
    
    var edit_fileName = e.target.files[0].name;
    if(edit_fileName != '')
    { 
      if((upload_docs_value == 1) && (supplier_num_rows == 0))
      {
        if(check_num_rows == 0)
        {
          $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" edit_fileName="'+edit_fileName+'" style="margin-right:5px;"> Upload</button>');
        }
      }
      else if (upload_docs_value == 1)
      {
        if(check_num_rows != supplier_num_rows)
        {
          $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" edit_fileName="'+edit_fileName+'" style="margin-right:5px;"> Upload</button>');
        }
      }
      else
      {
        if(check_num_rows == 0)
        {
          $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" edit_fileName="'+edit_fileName+'" style="margin-right:5px;"> Upload</button>');
        }
      }
    }
    else
    { 
      //$('#button_file_form').remove();
      $('#edit_submit_button').remove();
    }
  });//close upload file

  $(document).on('click','#edit_reset_input',function(){

    $('#edit_upload_file').val('');

    var edit_file = $('#edit_upload_file')[0].files[0];

    if(edit_file === undefined)
    {
      $('#edit_submit_button').remove();
    }
    else
    { 
      if((upload_docs_value == 1) && (supplier_num_rows == 0))
      {
        if(check_num_rows == 0)
        {
          $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" style="margin-top: 25px;" edit_fileName="'+edit_file+'"> Upload</button>');
        }
      }
      else if (upload_docs_value == 1)
      {
        if(check_num_rows != supplier_num_rows)
        {
          $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" style="margin-top: 25px;" edit_fileName="'+edit_file+'"> Upload</button>');
        }
      }
      else
      {
        if(check_num_rows != 0)
        {
          $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" style="margin-top: 25px;" edit_fileName="'+edit_file+'"> Upload</button>');
        }
      }

    }
  });//close reset_input

  $(document).on('click','#edit_submit_button',function(){

    var edit_file_name = $('#edit_submit_button').attr('edit_fileName');

    var announcement_guid = '<?php echo $announcement_guid?>';

    if(supplier_num_rows != 0)
    { 
      if(upload_docs_value == 1)
      {
        var supplier_guid = $('#supplier_type').val();
        if((supplier_guid == '') || (supplier_guid == null) || (supplier_guid == 'null'))
        {
          alert('Please Select Supplier.');
          return;
        }
      }
      else
      {
        var supplier_guid = 'no_data';
      }

    }
    else
    {
      var supplier_guid = 'no_data';
    }


    if(edit_file_name == '')
    {
      alert('Undefined File. Please choose again.');
      return;
    }

    if(announcement_guid == '')
    {
      alert('Undefined GUID. Please choose again.');
      return;
    }

    confirmation_modal('Are you sure want to Upload?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#edit_upload_file')[0].files[0]);
      formData.append('file_name', edit_file_name);
      formData.append('announcement_guid', announcement_guid);
      formData.append('supplier_guid', supplier_guid);
      //console.log(formData); die;
      $.ajax({
          url:"<?= site_url('Sign_upload_doc/upload_docs');?>",
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
              location.reload();

          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });//close submit_button


  $(document).on('click','#delete_docs',function(){

    var document_guid = $(this).attr('document_guid');

    var announcement_guid = $(this).attr('announcement_guid');

    if(supplier_num_rows != 0)
    {
      if(upload_docs_value == 1)
      {
        var supplier_guid = $(this).attr('supplier_guid');
        var supplier_name = $(this).attr('supplier_name');
        if((supplier_guid == '') || (supplier_guid == null) || (supplier_guid == 'null'))
        {
          alert('Please Select Supplier.');
          return;
        }
      }
      else
      {
        var supplier_guid = 'no_data';
        var supplier_name = 'Undefined';
      }
    }
    else
    {
      var supplier_guid = 'no_data';
      var supplier_name = 'Undefined';
    }

    if((document_guid == '') || (document_guid == 'null') || (document_guid == null))
    {
      alert('Undefined GUID. Please contact support or refresh page.');
      return;
    }
    
    confirmation_modal('Are you sure want to remove all document for <br> <b> Company : ' +supplier_name+ ' </b> with the user(s) ?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      //console.log(formData); die;
      $.ajax({
          url:"<?php echo site_url('Sign_upload_doc/delete_url_file');?>",
          method:"POST",
          data:{document_guid:document_guid,supplier_guid:supplier_guid,announcement_guid:announcement_guid},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('#alertmodal').modal('hide');
              $('.btn').button('reset');
            }else{
              alert(json.msg);
              $('.btn').button('reset');
              location.reload();
            }//close else
          }//close success
        });//close ajax
    });//close document yes click
  });//close submit_button

  $(document).on('click','#view_docs',function(){

    var url_value = $(this).attr('file_name');
    var supplier_name = $(this).attr('supplier_name');

    $('#append_title').html(supplier_name);
    $('#append_view_docs').html('<div class="col-md-12"> <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> <?php $ua = strtolower($_SERVER['HTTP_USER_AGENT']); if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) { // && stripos($ua,'mobile') !== false) { ?> <embed src="https://docs.google.com/gview?embedded=true&url='+url_value+'&amp;embedded=true" width="100%"  style="border: none;height:20em"/> <?php  } else { ?> <?php if($file_name[0] != 'HTTP/1.1 404 Not Found') { ?> <embed src="'+url_value+'" width="100%" height="500px" style="border: none;"/> If browser does not support PDFs. Please download the PDF to view it: <a href="'+url_value+'">Download PDF</a> <?php } else { echo 'pdf not found'; } ?> <?php } ?> </div> </div>')

  });//close submit_button
});
</script>
