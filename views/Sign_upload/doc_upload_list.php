<style type="text/css">
.alignleft {
  text-align: left;
  white-space: nowrap;
}

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
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Documents Listing</h3>
          <div class="box-tools pull-right">
            <?php
            if(in_array('IAVA',$_SESSION['module_code']))
              {?>
            <button id="upload_doc_list" class="btn btn-xs btn-default">
              <i class="glyphicon glyphicon-plus"></i> Upload
            </button>
            <?php
            }
            ?>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
            <table id="doc_list_tb" class="table table-hover" >
              <thead style="white-space: nowrap;">
              <tr>
                <th>Action</th>
                <th>Annoucenment</th>
                <th>Company Name</th>
                <th>User Name</th>
                <th>Uploaded By</th>
                <th>Uploaded At</th>

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
<script src="<?php echo base_url('assets/plugins/timepicker/bootstrap-timepicker.min.js')?>"></script>
<script>
$(document).ready(function() {
  $('#doc_list_tb').DataTable({
    "columnDefs": [{"targets": 0 ,"orderable": false}],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [5 , 'DESC'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "fixedColumns": true,
    "sScrollY": "50vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Sign_upload_doc/doc_list_table');?>",
        "type": "POST",
    },
    columns: [

             { "data": "document_guid" ,render:function( data, type, row ){

                var element = '';
                var element1 = row['url_value'];

                element += '<a href='+element1+' target="_blank"><button id="view_pdf" type="button" title="View/Download" class="btn btn-sm btn-warning" ><i class="fa fa-download"></i></button></a>  ';

                //element += '<button id="donwload_pdf" type="button" title="DOWNLOAD" class="btn btn-sm btn-warning" ><i class="fa fa-download"></i></button>  ';
                element += '<button id="delete_pdf" type="button" title="DELETE" class="btn btn-sm btn-danger" document_guid = "'+data+'" supplier_guid = '+row['supplier_guid']+' supplier_name = "'+row['supplier_name']+'" announcement_guid = '+row['announcement_guid']+'><i class="fa fa-trash"></i></button>  ';

                return element;
       
              }},
              { "data" : "title" },
              { "data" : "supplier_name" },
              { "data" : "user_name" },
              { "data" : "created_by" },
              { "data" : "created_at" },
              
             ],
    //dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'Brtip',
     buttons: [
       { extend: 'excelHtml5',
         exportOptions: {columns: [1,2,3,4,5]} /*, footer: true */},

       { extend: 'csvHtml5',  
         exportOptions: {columns: [1,2,3,4,5]} /*, footer: true*/ },
         ],
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      $(nRow).attr('reset_guid', aData['reset_guid']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $(document).on('click','#delete_pdf',function(){

    var document_guid = $(this).attr('document_guid');

    var supplier_guid = $(this).attr('supplier_guid');

    var supplier_name = $(this).attr('supplier_name');

    var announcement_guid = $(this).attr('announcement_guid');

    if(document_guid == '')
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

  $(document).on('click','#upload_doc_list',function(){

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Upload Document');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-12"><label>Annoucenment</label><select class="form-control select2 " id="ann_guid" name="ann_guid"> <option value="" selected disabled>-Select Annoucenment-</option> <?php foreach ($ann_data as $key) { ?> <option value="<?php echo $key->announcement_guid ?>"> <?php echo addslashes($key->title)?> </option> <?php } ?> </select></div>'

    methodd += '<div class="col-md-12"><label>Supplier</label><select class="form-control select2 get_supplier_guid" id="sup_guid" name="sup_guid"> <option value="">-Select Supplier-</option> <?php foreach ($supplier as $key) { ?> <option value="<?php echo $key->supplier_guid ?>"> <?php echo addslashes($key->supplier_name)?> </option> <?php } ?> </select></div>';

    methodd += '<div class="col-md-12"><label>User</label><select class="select2 form-control" id="user_data" name="user_data" multiple="multiple" disabled></select></div>';

    methodd += '<div class="col-md-12"><b>File</b></div>';

    methodd += '<div class="col-md-10"> <input id="edit_upload_file" type="file" class="form-control" accept=".pdf,.xlsx,.xls,.docx"> </div> <div class="col-md-2"><button type="button" class="btn btn-danger" id="edit_reset_input" >Reset</button> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"> <span id="edit_button_file_form"></span><input name="sendsubmit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';


    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function() {
      $('#ann_guid').select2();
      $('#sup_guid').select2();
      $('#user_data').select2();
      $('.get_supplier_guid').change(function(){

       var type_val = $('.get_supplier_guid').val();

       if(type_val != '')
       {
          $.ajax({
          url : "<?php echo site_url('Sign_upload_doc/fetch_user'); ?>",
          method:"POST",
          data:{type_val:type_val},
          success:function(result)
          {

           json = JSON.parse(result); 

              code = '';

              Object.keys(json['Code']).forEach(function(key) {

                code += '<option value="'+json['Code'][key]['user_guid']+'" selected>'+json['Code'][key]['user_name']+' - '+json['Code'][key]['user_id']+'</option>';

              });
           $('#user_data').select2().html(code);
          }
         });
       }
       else
       {
          $('#user_data').select2().html('<option value="" disabled>Please select the supplier</option>');
       }
          
      });//close selection
    }, 300);
  });//close submit_button

  $(document).on('change','#edit_upload_file',function(e){
    
    var edit_fileName = e.target.files[0].name;
    if(edit_fileName != '')
    { 
      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" edit_fileName="'+edit_fileName+'" style="margin-right:5px;"> Upload</button>');
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
      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" style="margin-top: 25px;" edit_fileName="'+edit_file+'"> Upload</button>');
    }
  });//close reset_input

  $(document).on('click','#edit_submit_button',function(){

    var edit_file_name = $('#edit_submit_button').attr('edit_fileName');

    var announcement_guid = $('#ann_guid').val();

    var supplier_guid = $('#sup_guid').val();

    var user_guid = $('#user_data').val();
    //alert(user_guid);die;
    //alert(user_guid); die;
    if((supplier_guid == '') || (supplier_guid == null) || (supplier_guid == 'null'))
    {
      alert('Please Select Supplier/Company.');
      return;
    }
    
    if((user_guid == '') || (user_guid == null) || (user_guid == 'null'))
    {
      alert('This Supplier No have any user.');
      return;
    }

    if(edit_file_name == '')
    {
      alert('Undefined File. Please choose again.');
      return;
    }

    if((announcement_guid == '') || (announcement_guid == null) || (announcement_guid == 'null'))
    {
      alert('Undefined Annoucement GUID. Please choose again.');
      return;
    }

    confirmation_modal('Are you sure want to Upload?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#edit_upload_file')[0].files[0]);
      formData.append('file_name', edit_file_name);
      formData.append('announcement_guid', announcement_guid);
      formData.append('supplier_guid', supplier_guid);
      formData.append('user_guid', user_guid);
      //console.log(formData); die;
      $.ajax({
          url:"<?= site_url('Sign_upload_doc/upload_docs_list');?>",
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
});
</script>
