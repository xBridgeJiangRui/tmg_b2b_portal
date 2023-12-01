
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
<?php // echo var_dump($_SESSION); ?>
<div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Announcement</h3>
          <div class="box-tools pull-right">
          
          <button type="button" class="btn btn-xs btn-primary" id="add_annoucement"><i class="glyphicon glyphicon-plus"></i> Create </button>
<!--           <button title="Subscription" onclick="create_new()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#create_new"  
            data-table="<?php echo 'announcement' ?>"
            data-mode="<?php echo 'create' ?>"
            data-customer_guid = "<?php echo $_SESSION['customer_guid'] ?>"            
            ><i class="glyphicon glyphicon-plus"></i>Create
          </button> -->

          <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
          
                  <table id="announcement" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Title</th>
                        <th>Published Date</th>
                        <th>Published</th>
                        <th>Doc Date</th>
                        

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($announcement->result() as $row) { ?>
                      <tr>
                        <td> 
                          
                          <button type="button" id="edit_annoucement" class="btn btn-xs btn-primary" 
                        edit_detail="#edit_detail"  
                        announcement_guid="<?php echo $row->announcement_guid ?>"
                        docdate="<?php echo $row->docdate ?>"
                        title="<?php echo $row->title ?>"
                        content="<?php echo str_replace('"', "&quot;", "$row->content")   ?>"
                        acknowledgement="<?php echo $row->acknowledgement ?>"
                        pdfstatus="<?php echo $row->pdf_status ?>"
                        mandatory="<?php echo $row->mandatory ?>"
                        agreementstatus="<?php echo $row->agree ?>"
                        header="<?php echo $row->header ?>"
                        button1="<?php echo $row->button1 ?>"
                        is_upload_doc="<?php echo $row->upload_docs ?>"
                        ><i class="fa fa-edit"></i>
                          </button>

                          <button type="button" class="btn btn-xs btn-info" id="add_upload_file" title="UPLOAD FILE" announcement_guid="<?php echo $row->announcement_guid ?>"><i class="fa fa-file"></i></button>

                          <button title="publish_detail" onclick="publish_detail()" type="button" class="btn btn-xs btn-success" data-toggle="modal" 
                        data-target="#publish_detail"  
                        data-announcement_guid="<?php echo $row->announcement_guid ?>"
                        data-publish_at="<?php echo $row->publish_at ?>"
                        ><i class="glyphicon glyphicon-eye-open"></i></i>
                          </button>
                          

                          <button title="Unpublish" onclick="delete_modal('<?php echo site_url('CusAdmin_controller/unpublish_announcement_guid'); ?>?announcement_guid=<?php echo $row->announcement_guid?>')" type="button" class="btn btn-xs btn-warning" 
                          data-toggle="modal"  
                          data-target="#delete" 
                          data-action="Revoke Publish" 
                          data-title="<?php echo $row->title; ?>" ><i class="glyphicon glyphicon-eye-close"></i>
                          </button>

                          <button title="Delete" onclick="delete_modal('<?php echo site_url('CusAdmin_controller/delete_announcement_guid'); ?>?announcement_guid=<?php echo $row->announcement_guid?>')" type="button" class="btn btn-xs btn-danger" 
                          data-toggle="modal"  
                          data-target="#delete" 
                          data-action="Delete" 
                          data-title="<?php echo $row->title; ?>" ><i class="glyphicon glyphicon-trash"></i>
                          </button>
                        </td>
                        <td><?php echo $row->title ?></td>
                        <td><?php echo $row->publish_at ?></td>
                        <td><input type="checkbox" value="<?php echo $row->posted ?>" <?php if($row->posted == '1') {  echo 'checked'; }  ?> disabled > 
                        </td>
                        <td><?php echo $row->docdate ?></td>
                        
                      </tr>
                    <?php } ?>
                    </tbody>
                  </table>
             
              </div>  
        </div>

      </div>
    </div>
  </div>
   
<!-- nothing ends after -->
</div>
</div>
 
<script>
  function view_query()
  {
    $('#queries').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="announcement_guid"]').val(button.data('announcement_guid'))      
      modal.find('[name="content"]').val(button.data('content'))
    });
  }

  function publish_detail()
  {
    $('#publish_detail').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Publish and Post')
      modal.find('[name="announcement_guid"]').val(button.data('announcement_guid'))       
      modal.find('[name="publish_at"]').val(button.data('publish_at')) 
    });
  }

  function create_new()
  {
    $('#create_new').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Add New')
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
    });
  }

  function delete_modal(delete_url)
  {
    $('#delete').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm ' + button.data('action') + ' <<' + button.data('title') + '>>?')
    document.getElementById('url').setAttribute("href" , delete_url );
    });
  }
</script>

<script>
 $(function() {
    $('input[name="docdate"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
         locale: {
            format: 'YYYY-MM-DD'
        },
         
    }, 
  );
});

  $(function() {
    $('input[name="published_date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: true, 
        timePickerIncrement: 30,
        ampm: true,
         locale: {
            format: 'YYYY-MM-DD HH:mm:ss'
        },
         
    }, 
  );
});
</script>

<script>
$(document).ready(function() {
  $(document).on('click','#add_annoucement',function(){

    var modal = $("#large-modal").modal();

    modal.find('.modal-title').html('Add Announcement');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-6"><label>Title</label><input type="text" class="form-control input-sm" id="add_title" /></div>';

    methodd += '<div class="col-sm-6"> <label>Date</label> <div class="input-group"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input name="add_docdate" id="add_docdate" type="text" class="datepicker form-control input-sm" value="<?php echo date('Y-m-d');?>" autocomplete="off" > </div> </div>';

    methodd += '<div class="col-md-12"><label>Content</label><textarea class="summernote_textarea" name="add_content" id="add_content" rows="10" cols="30" class="form-control"></textarea></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="submit_add_btn" class="btn btn-success" value="Create"> <input name="cancel_btn" type="button" class="btn btn-default" data-dismiss="modal" value="Close" onClick="window.location.reload();"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('.datepicker').datepicker({
        forceParse: false,
        autoclose: true,
        format: 'yyyy-mm-dd'  
      });

      $('.summernote_textarea').summernote({
        minHeight: 200,
        maxHeight: 250,    
        toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']],
        ], 
        });
    },300);
  }); // close

  $(document).on('click','#submit_add_btn',function(){
    var docdate = $('#add_docdate').val();
    var title = $('#add_title').val();
    var content = $('#add_content').val();

    if((docdate == '') || (docdate == null))
    {
      alert("Please select Doc Date.")
      return;
    }

    if((title == '') || (title == null))
    {
      alert("Please insert Title.")
      return;
    }

    if((content == '') || (content == null))
    {
      alert("Please insert Content.")
      return;
    }

    $.ajax({
          url:"<?php echo site_url('CusAdmin_controller/creat_new');?>",
          method:"POST",
          data:{docdate:docdate,title:title,content:content},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            }else{
              alert(json.msg);
              $('.btn').button('reset');
              location.reload();
            }//close else
          }//close success
        });//close ajax
  });//close add 

  $(document).on('click','#edit_annoucement',function(){

    var announcement_guid = $(this).attr('announcement_guid');
    var docdate = $(this).attr('docdate');
    var title = $(this).attr('title');
    var content = $(this).attr('content');
    var acknowledgement = $(this).attr('acknowledgement');
    var pdfstatus = $(this).attr('pdfstatus');
    var mandatory = $(this).attr('mandatory');
    var agreementstatus = $(this).attr('agreementstatus');
    var editheader = $(this).attr('header');
    var editbutton1 = $(this).attr('button1');
    var is_upload_doc = $(this).attr('is_upload_doc');

    var modal = $("#large-modal").modal();

    modal.find('.modal-title').html('Edit Announcement');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-12"><input type="hidden" readonly class="form-control input-sm" id="edit_guid" name="edit_guid" value="'+announcement_guid+'" /></div>';

    methodd += '<div class="col-md-6"><label>Title</label><input type="text" class="form-control input-sm" id="edit_title" value="'+title+'" /></div>';

    methodd += '<div class="col-sm-6"> <label>Date</label> <div class="input-group"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input name="edit_docdate" id="edit_docdate" type="text" class="datepicker form-control input-sm" value="<?php echo date('Y-m-d');?>" autocomplete="off" > </div> </div>';

    methodd += '<div class="col-md-6"><label>Header Name</label><input type="text" class="form-control input-sm" id="edit_header" value="'+editheader+'" /></div>';

    methodd += '<div class="col-md-6"><label>Button Name</label><input type="text" class="form-control input-sm" id="edit_button1" value="'+editbutton1+'" /></div>';

    methodd += '<div class="form-group"><div class="col-md-6 checkbox"><label><input type="checkbox" id="edit_acknowledgement" value="1"/>Acknowledgement</label></div></div>';

    methodd += '<div class="form-group"><div class="col-md-6 checkbox"><label><input type="checkbox" id="edit_pdfstatus" value="1"/>PDF</label></div></div>';

    methodd += '<div class="form-group"><div class="col-md-6 checkbox"><label><input type="checkbox" id="edit_mandatory" value="1"/>Mandatory</label></div></div>';

    methodd += '<div class="form-group"><div class="col-md-6 checkbox"><label><input type="checkbox" id="edit_agreementstatus" value="1"/>Agreement Status</label></div></div>';

    methodd += '<div class="form-group"><div class="col-md-6 checkbox"><label><input type="checkbox" id="edit_is_upload_doc" value="1"/>Upload Documents</label></div></div>';

    methodd += '<div class="col-md-12"><label>Content</label><textarea class="summernote_textarea" name="edit_content" id="edit_content" rows="10" cols="30" class="form-control">'+content+'</textarea></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="submit_edit_btn" class="btn btn-success" value="Update"> <input name="cancel_btn" type="button" class="btn btn-default" data-dismiss="modal" value="Close" onClick="window.location.reload();"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('#edit_docdate').val(docdate);
      $('.datepicker').datepicker({
        forceParse: false,
        autoclose: true,
        format: 'yyyy-mm-dd'  
      });

      if(acknowledgement == 1)
      {
        $('#edit_acknowledgement').prop('checked',true);
      }

      if(pdfstatus == 1)
      {
        $('#edit_pdfstatus').prop('checked',true);
      }

      if(mandatory == 1)
      {
        $('#edit_mandatory').prop('checked',true);
      }

      if(agreementstatus == 1)
      {
        $('#edit_agreementstatus').prop('checked',true);
      }

      if(is_upload_doc == 1)
      {
        $('#edit_is_upload_doc').prop('checked',true);
      }

      $('.summernote_textarea').summernote({
        minHeight: 200,
        maxHeight: 250,    
        toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']],
        ], 
        });
    },300);
  }); // close

  $(document).on('click','#submit_edit_btn',function(){

    var announcement_guid = $('#edit_guid').val();
    var docdate = $('#edit_docdate').val();
    var title = $('#edit_title').val();
    var content = $('#edit_content').val();
    var acknowledgement = '';
    var pdfstatus = '';
    var mandatorystatus = '';
    var agreementstatus = '';
    var header = $('#edit_header').val();
    var button1 = $('#edit_button1').val();
    var mode = 'detail';
    var is_upload_doc = '';
    var upload_link = '';
    
    if((announcement_guid == '') || (announcement_guid == null))
    {
      alert("Unable to Update due to null GUID.")
      return;
    }

    if((docdate == '') || (docdate == null))
    {
      alert("Please select Doc Date.")
      return;
    }

    if((title == '') || (title == null))
    {
      alert("Please insert Title.")
      return;
    }

    if((content == '') || (content == null))
    {
      alert("Please insert Content.")
      return;
    }

    if($('#edit_pdfstatus').is(':checked'))
    {
      if((header == '') || (header == null))
      {
        alert("Please insert Header Name.")
        return;
      }

      if((button1 == '') || (button1 == null))
      {
        alert("Please insert Button Name.")
        return;
      }
    }

    if($('#edit_acknowledgement').is(':checked'))
    {
      acknowledgement = '1';
      
    }
    else
    {
      acknowledgement = '0';
    }
    
    if($('#edit_pdfstatus').is(':checked'))
    {
      pdfstatus = '1';
      
    }
    else
    {
      pdfstatus = '0';
    }

    if($('#edit_mandatory').is(':checked'))
    {
      mandatorystatus = '1';
      
    }
    else
    {
      mandatorystatus = '0';
    }

    if($('#edit_agreementstatus').is(':checked'))
    {
      agreementstatus = '1';
      
    }
    else
    {
      agreementstatus = '0';
    }

    if($('#edit_is_upload_doc').is(':checked'))
    {
      is_upload_doc = '1';
      //https://staging.xbridge.my/
      upload_link = 'https://staging.xbridge.my/index.php/Sign_upload_doc/doc_upload_sites?announcement_guid='+announcement_guid+'"';
    }
    else
    {
      is_upload_doc = '0';
      upload_link = '';
    }

    $.ajax({
          url:"<?php echo site_url('CusAdmin_controller/update');?>",
          method:"POST",
          data:{announcement_guid:announcement_guid,docdate:docdate,title:title,content:content,acknowledgement:acknowledgement,pdfstatus:pdfstatus,mandatorystatus:mandatorystatus,agreementstatus:agreementstatus,header:header,button1:button1,mode:mode,is_upload_doc:is_upload_doc,upload_link:upload_link},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            }else{
              alert(json.msg);
              $('.btn').button('reset');
              location.reload();
            }//close else
          }//close success
        });//close ajax
  });//close add 
  
  $(document).on('click','#add_upload_file',function(){

    var announcement_guid = $(this).attr('announcement_guid');
    
    if((announcement_guid == '') || (announcement_guid == 'null') || (announcement_guid == null))
    {
      alert('Invalid GUID');
      return;
    }

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Upload File');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-12"><input type="hidden" readonly class="form-control input-sm" id="check_guid" name="check_guid" value="'+announcement_guid+'" /></div>';

    methodd += '<div class="col-md-8"><label for="edit_upload_file">File</label><input id="edit_upload_file" type="file" class="form-control" accept=".pdf,.xlsx,.xls"></div>';

    methodd += '<div class="col-md-4"><span id="edit_button_file_form"></span><button type="button" class="btn btn-danger" id="edit_reset_input" style="margin-top:22px;">Reset</button></div>';

    methodd += '<div class="col-md-12"><label>URL</label><input type="text" id="edit_url_value" readonly class="form-control"></span></div>';

    methodd += '<div class="col-md-12"> <span id="append_url_data"> </span></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="cancel_btn" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function() {
       if(announcement_guid != '')
       {
          $.ajax({
          url : "<?php echo site_url('CusAdmin_controller/fetch_url'); ?>",
          method:"POST",
          data:{announcement_guid:announcement_guid},
          success:function(result)
          {

           json = JSON.parse(result); 

              code = '';

              Object.keys(json['Code']).forEach(function(key) {

                code += '<label>URL Data</label> <input type="text" id="edit_url_value" readonly class="form-control" value="'+json['Code'][key]['url_data']+'">';
                
              });
           $('#append_url_data').html(code);
          }
         });
       }
       else
       {
          $('#append_url_data').html('<input type="text" class="form-control" id="comp_no" name="comp_no"  aria-describedby="emailHelp" placeholder="Please select the supplier" readonly required>');
       }
    }, 300);
  }); // close

  $(document).on('change','#edit_upload_file',function(e){
    
    var edit_fileName = e.target.files[0].name;
    if(edit_fileName != '')
    { 
      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" edit_fileName="'+edit_fileName+'" style="margin-top:22px;margin-right:5px;"> Upload</button>');
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
      $('#edit_url_value').val('');

    }
    else
    { 
      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" style="margin-top: 22px;" edit_fileName="'+edit_file+'"> Upload</button>');
    }
  });//close reset_input

  $(document).on('click','#edit_submit_button',function(){

    var edit_file_name = $('#edit_submit_button').attr('edit_fileName');

    var announcement_guid = $('#check_guid').val();

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

      $.ajax({
          url:"<?= site_url('CusAdmin_controller/upload_ann_link');?>",
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
              $('#edit_url_value').val(json.link);

          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });//close submit_button
});
</script>
