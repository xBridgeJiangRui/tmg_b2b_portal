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

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    display: flex;
    white-space: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar {
  width: 10px;
  height: 5px;
  background-color: #F5F5F5;           /* width of the entire scrollbar */
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
  border-radius: 10px;
  background-color: #F5F5F5;       /* color of the tracking area */
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
  background-color: #B7BABF; /* color of the scrolling */
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: black;
}
</style>
<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Email Template List</h3><br>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>
            
            <button id="create_btn" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Create </button>

            <?php
            }
            ?>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
          </div>
        </div>
          <div class="box-body">
            <table class="table table-bordered table-striped dataTable" id="list_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;">
                <tr>
                  <th>Action</th>
                  <th>Type</th> 
                  <th>Mail Type</th>
                  <th>Mail Subject</th>
                  <th>Description</th>
                  <th>Is Active</th>
                  <th>Created At</th>
                  <th>Created By</th>
                  <th>Updated At</th>
                  <th>Updated By</th>

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

<script>
$(document).ready(function () {
  setTimeout(function(){
    $('#large-modal').attr({"data-backdrop":"static","data-keyboard":"false"}); //to remove clicking outside modal for closing
    $('#medium-modal').attr({"data-backdrop":"static","data-keyboard":"false"}); //to remove clicking outside modal for closing
  },300);

  $('#list_table').DataTable({
    "columnDefs": [
    {"targets": 0 ,"orderable": false},
    ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : true,
    'ordering'    : true, 
    'order'       : [  [6 , 'desc'] ],
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
        "url": "<?php echo site_url('Blast_email_template/list_tb');?>",
        "type": "POST",
    },
    columns: [
            { "data" : "template_guid" ,render:function( data, type, row ){

              var element = '';
              var element1 = row['is_replace'];
              var element2 = row['is_active'];
              var element3 = row['is_editable'];
              var element4 = row['is_pdf'];

              <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
              {
                ?>
                //btn-block btn-xs
                if(element3 == '1')
                {
                  element += '<button id="edit_btn" type="button"  title="EDIT" class="btn btn-block btn-xs btn-info" template_guid="'+row['template_guid']+'" mail_subject="'+row['mail_subject']+'" description="'+row['description']+'" is_active="'+row['is_active']+'" status_type="'+row['type']+'" mail_type="'+row['mail_type']+'" is_editable="'+row['is_editable']+'" is_pdf="'+row['is_pdf']+'" is_replace="'+row['is_replace']+'"><i class="fa fa-edit"></i></button>';

                  element += '<button id="view_content_btn" title="Content" class="btn btn-block btn-xs btn-primary" template_guid="'+row['template_guid']+'" ><i class="fa fa-eye"></i></button>';

                  if(element4 == '1')
                  {
                    var top_css = 'margin-top:5px;';

                    element += '<button id="pdf_btn" type="button"  title="PDF" class="btn btn-block btn-xs btn-danger" template_guid="'+row['template_guid']+'" url_link="'+row['url_link']+'" ><i class="fa fa-file"></i></button>';
                  }

                }
                else
                {
                  element += '<button id="view_content_btn" title="Content" class="btn btn-block btn-xs btn-primary" template_guid="'+row['template_guid']+'" ><i class="fa fa-eye"></i></button>';
                }

                if(element1 == '0' && element2 == '1')
                {
                  element += '<button id="send_content_btn" title="Send" class="btn btn-block btn-xs btn-warning" template_guid="'+row['template_guid']+'" ><i class="fa fa-send"></i></button>';
                }

                <?php
              }
              ?>

              return element;
            }},
            { "data" : "type" },
            { "data" : "mail_type" },
            { "data" : "mail_subject" },
            { "data" : "description" },
            { "data" : "is_active" },
            { "data" : "created_at" },
            { "data" : "created_by" },
            { "data" : "updated_at" },
            { "data" : "updated_by" },
          ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
    //   $(nRow).attr('content', aData['content']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $(document).on('click','#create_btn',function(){

    var modal = $("#large-modal").modal();

    modal.find('.modal-title').html('New Email Template');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><label>Mail Subject</label> <input type="text" class="form-control input-sm" id="add_mail_subject" /> </div>';

    methodd += '<div class="col-md-6"><label>Description</label> <input type="text" class="form-control input-sm" id="add_mail_description" /> </div>';

    methodd += '<div class="col-md-6"><label>Type</label> <input type="text" class="form-control input-sm" id="add_type" /> </div>';

    methodd += '<div class="col-md-6"><label>Mail Type</label> <input type="text" class="form-control input-sm" id="add_mail_type" /> </div>';

    methodd += '<div class="col-md-6"><label>Active</label><select class="form-control" name="add_active" id="add_active"> <option value="" disabled selected>-Select Activate-</option> <option value="1"> YES </option>  <option value="0"> NO </option>  </select> </div>';

    methodd += '<div class="col-md-6"><label>Replace Value</label><select class="form-control" name="add_replace" id="add_replace"> <option value="" disabled selected>-Select Replace Value-</option> <option value="1"> YES </option>  <option value="0"> NO </option>  </select> </div>';

    methodd += '<div class="col-md-12"><label>Mail Content</label><textarea class="summernote_textarea" name="add_content" id="add_content" rows="10" cols="30" class="form-control"></textarea> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="submit_button" class="btn btn-primary"> Create </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('.select2').select2();
      
      $('.summernote_textarea').summernote({
        minHeight: 200,
        maxHeight: 250,    
        lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
        fontSizes: ['8','9','10','11','12','13','14','15','16','17','18','19','20','24','36'],
        toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['height', ['height']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['codeview',]],
        ], 
      });

    },300);

  });//close modal create

  $(document).on('click','#submit_button',function(){
    var add_mail_subject = $('#add_mail_subject').val();
    var add_mail_description = $('#add_mail_description').val();
    var add_active = $('#add_active').val();
    var add_replace = $('#add_replace').val();
    var add_content = $('#add_content').val();
    var add_type = $('#add_type').val();
    var add_mail_type = $('#add_mail_type').val();
    
    if((add_mail_subject == '') || (add_mail_subject == null) || (add_mail_subject == 'null'))
    {
      alert('Cannot Empty Mail Subject.');
      return;
    }

    if((add_active == '') || (add_active == null) || (add_active == 'null'))
    {
      alert('Please Select active status.');
      return;
    }

    if((add_replace == '') || (add_replace == null) || (add_replace == 'null'))
    {
      alert('Please Select replace value status.');
      return;
    }

    if((add_content == '') || (add_content == null) || (add_content == 'null'))
    {
      alert('Cannot Empty Mail Content.');
      return;
    }

    if((add_type == '') || (add_type == null) || (add_type == 'null'))
    {
      alert('Cannot Empty Type. Ex: PO document.');
      return;
    }

    if((add_mail_type == '') || (add_mail_type == null) || (add_mail_type == 'null'))
    {
      alert('Cannot Empty Mail Type. Ex : EDI Module.');
      return;
    }
    confirmation_modal('Are you sure to proceed Create New Template?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_template/add_template') ?>",
        method:"POST",
        data:{add_mail_subject:add_mail_subject,add_mail_description:add_mail_description,add_active:add_active,add_replace:add_replace,add_content:add_content,add_type:add_type,add_mail_type:add_mail_type},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == '1') {
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            alert(json.msg);
            location.reload();
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation

  });//close submit process

  $(document).on('click','#view_content_btn',function(){
    var template_guid = $(this).attr('template_guid');

    $.ajax({
        url:"<?php echo site_url('Blast_email_template/fetch_content');?>",
        method:"POST",
        data:{template_guid:template_guid},
        dataType: 'JSON',
        beforeSend:function(){
            $('.btn').button('loading');
        },
        success:function(data)
        {
            $('.btn').button('reset');
        },//close success
        complete:function(data)
        { 
            json = JSON.parse(data['responseText']);
            $("#large-modal").show();
            var modal = $("#large-modal").modal();
            modal.find('.modal-title').html('Body Content');
            methodd = '';
            methodd +='<div class="col-md-12">';
            methodd += '<div class="col-md-12">'+json['content'][0]['content']+'</div>';
            methodd += '</div>';
            methodd_footer = '<p class="full-width"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';
            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);
        }
    });//close ajax
  });

  $(document).on('click','#edit_btn',function(){

    var template_guid = $(this).attr('template_guid');
    var mail_subject = $(this).attr('mail_subject');
    var description = $(this).attr('description');
    var is_active = $(this).attr('is_active');
    var status_type = $(this).attr('status_type');
    var mail_type = $(this).attr('mail_type');
    var is_replace = $(this).attr('is_replace');
    var is_editable = $(this).attr('is_editable');
    var is_pdf = $(this).attr('is_pdf');

    $.ajax({
        url:"<?php echo site_url('Blast_email_template/fetch_content');?>",
        method:"POST",
        data:{template_guid:template_guid},
        dataType: 'JSON',
        beforeSend:function(){
            $('.btn').button('loading');
        },
        success:function(data)
        {
            $('.btn').button('reset');
        },//close success
        complete:function(data)
        {
            json = JSON.parse(data['responseText']);
            //json = JSON.parse(data);
            //$("#large-modal").show();
            body_content = decodeURI(encodeURI(json.body_content));

            var modal = $("#large-modal").modal();
            modal.find('.modal-title').html('Edit Email Template');

            methodd = '';

            methodd += '<div class="col-md-12">';

            methodd += '<div class="col-md-12"><input type="hidden" class="form-control input-sm" id="template_guid" value="'+template_guid+'"/> </div>';

            methodd += '<div class="col-md-12"><label>Mail Subject</label> <input type="text" class="form-control input-sm" id="edit_mail_subject" value="'+mail_subject+'"/> </div>';

            methodd += '<div class="col-md-6"><label>Description</label> <input type="text" class="form-control input-sm" id="edit_mail_description" value="'+description+'"/> </div>';

            methodd += '<div class="col-md-6"><label>Type</label> <input type="text" class="form-control input-sm" id="edit_type" value="'+status_type+'"/> </div>';

            methodd += '<div class="col-md-6"><label>Mail Type</label> <input type="text" class="form-control input-sm" id="edit_mail_type" value="'+mail_type+'"/> </div>';

            methodd += '<div class="col-md-6"><label>Active</label><select class="form-control" name="edit_active" id="edit_active"> <option value="" disabled selected>-Select Activate-</option> <option value="1"> YES </option>  <option value="0"> NO </option>  </select> </div>';

            methodd += '<div class="col-md-6"><label>Replace</label><select class="form-control" id="edit_replace" name="edit_replace" required="true" > <option value="" disabled selected>-Select Activate-</option> <option value="1">YES</option> <option value="0" >NO</option> </select></div>';

            methodd += '<div class="col-md-6"><label>Editable</label><select class="form-control" id="edit_editable" name="edit_editable" required="true" > <option value="" disabled selected>-Select Activate-</option> <option value="1">YES</option> <option value="0" >NO</option> </select></div>';

            methodd += '<div class="col-md-6"><label>PDF</label><select class="form-control" id="edit_pdf" name="edit_pdf" required="true" ><option value="" disabled selected>-Select Activate-</option> <option value="1">YES</option> <option value="0" >NO</option> </select></div>';

            methodd += '<div class="col-md-12"><label>Mail Content</label><textarea class="summernote_textarea" name="edit_content" id="edit_content" rows="10" cols="30" class="form-control">'+body_content+'</textarea> </div>';

            //methodd += '<div class="col-md-12">'+json['content'][0]['content']+'</div>';

            methodd += '</div>';

            methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="update_button" class="btn btn-primary"> Update </button></span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);

            setTimeout(function(){
                $('#edit_active').val(is_active);
                $('#edit_replace').val(is_replace);
                $('#edit_editable').val(is_editable);
                $('#edit_pdf').val(is_pdf);
                
                $('.select2').select2();

                $('.summernote_textarea').summernote({
                    minHeight: 200,
                    maxHeight: 250,    
                    lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                    fontSizes: ['8','9','10','11','12','13','14','15','16','17','18','19','20','24','36'],
                    toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['height', ['height']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                    ], 
                });
            },300);
        }
    });//close ajax
  });//close modal create

  $(document).on('click','#update_button',function(){
    var template_guid = $('#template_guid').val();
    var edit_mail_subject = $('#edit_mail_subject').val();
    var edit_mail_description = $('#edit_mail_description').val();
    var edit_active = $('#edit_active').val();
    var edit_content = $('#edit_content').val();
    var edit_type = $('#edit_type').val();
    var edit_mail_type = $('#edit_mail_type').val();
    var edit_replace = $('#edit_replace').val();
    var edit_editable = $('#edit_editable').val();
    var edit_pdf = $('#edit_pdf').val();
    
    if((edit_mail_subject == '') || (edit_mail_subject == null) || (edit_mail_subject == 'null'))
    {
      alert('Cannot Empty Mail Subject.');
      return;
    }

    if((edit_active == '') || (edit_active == null) || (edit_active == 'null'))
    {
      alert('Please Select active status.');
      return;
    }

    if((edit_content == '') || (edit_content == null) || (edit_content == 'null'))
    {
      alert('Cannot Empty Mail Content.');
      return;
    }

    if((edit_type == '') || (edit_type == null) || (edit_type == 'null'))
    {
      alert('Cannot Empty Type. Ex: PO document.');
      return;
    }

    if((edit_mail_type == '') || (edit_mail_type == null) || (edit_mail_type == 'null'))
    {
      alert('Cannot Empty Mail Type. Ex : EDI Module.');
      return;
    }

    if((edit_replace == '') || (edit_replace == null) || (edit_replace == 'null'))
    {
      alert('Please Select replace status.');
      return;
    }

    if((edit_editable == '') || (edit_editable == null) || (edit_editable == 'null'))
    {
      alert('Please Select editable status.');
      return;
    }

    if((edit_pdf == '') || (edit_pdf == null) || (edit_pdf == 'null'))
    {
      alert('Please Select pdf status.');
      return;
    }

    confirmation_modal('Are you sure to proceed Update Template?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_template/edit_template') ?>",
        method:"POST",
        data:{template_guid:template_guid,edit_mail_subject:edit_mail_subject,edit_mail_description:edit_mail_description,edit_active:edit_active,edit_content:edit_content,edit_type:edit_type,edit_mail_type:edit_mail_type,edit_replace:edit_replace,edit_editable:edit_editable,edit_pdf:edit_pdf},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == '1') {
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            alert(json.msg);
            location.reload();
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation

  });//close submit process

  $(document).on('click','#send_content_btn',function(){

    var template_guid = $(this).attr('template_guid');

    var modal = $("#large-modal").modal();

    modal.find('.modal-title').html('Send Email Group');

    methodd = '';

    methodd += '<div class="col-md-12"><input type="hidden" class="form-control input-xm" id="template_guid" value="'+template_guid+'" readonly/> </div>';

    methodd += '<div class="col-md-6"><label>Retailer Name</label> <select class="form-control select2" name="acc_guid" id="acc_guid"> <option value="" disabled selected>-Select Email Group-</option>  <?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-6"><label>Email Group</label> <select class="form-control select2" name="email_group" id="email_group"> <option value="" disabled selected>-Select Email Group-</option>  <?php foreach($get_email_group as $row) { ?> <option value="<?php echo $row->guid?>"><?php echo addslashes($row->description) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>New Email Address (To)</label> <select class="form-control select2" name="add_new_email" id="add_new_email" multiple="multiple" > </select>  </div>';

    methodd += ' <div class="clearfix"></div><br> <span id="append_group"></span>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="send_button" class="btn btn-danger"> Send </button> <button type="button" id="insert_email_btn" class="btn btn-warning"> Send By Batch </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('.select2').select2();

      $("#add_new_email").select2({
        //data: json.cc_email,
        tags: true,
        tokenSeparators: [',', ''],
        createTag :function (params) 
          {
            if(!isEmail(params.term)){
                return {
                    text: params.term,
                };
            }
            return {
              id: params.term,
              text: params.term,
            };
          }
      });

      $(document).off('change', '#email_group').on('change', '#email_group', function(){
        var guid = $(this).val();

        if(guid != '')
        {
          $('#append_group').html('<div class="col-md-12"> <div class="box box-info"> <div class="box-body"> <table id="list_user_tb" class="table table-bordered table-striped" width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr> <th>Retailer Name</th> <th>Supplier Name</th> <th>Email Address</th> <th>Is Active</th> </tr> </thead> <tbody> </tbody></table> </div> </div> </div>');
        }

        $.ajax({
          url : "<?php echo site_url('Blast_email_user/list_tb_child');?>",
          method: "POST",
          data:{guid:guid},
          beforeSend : function() {
            //$('.btn').button('loading');
          },
          complete: function() {
           //$('.btn').button('reset');
          },
          success : function(data)
          {  
            json = JSON.parse(data);

            if ($.fn.DataTable.isDataTable('#list_user_tb')) {
                $('#list_user_tb').DataTable().destroy();
            }

            $('#list_user_tb').DataTable({
              "columnDefs": [
              {"targets": 0 ,"orderable": false},
              ],
              'processing'  : true,
              'paging'      : true,
              'lengthChange': true,
              'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
              'searching'   : true,
              'ordering'    : true,
              'order'       : [ ],
              'info'        : true,
              'autoWidth'   : false,
              "bPaginate": true, 
              "bFilter": true, 
              "sScrollY": "50vh", 
              "sScrollX": "100%", 
              "sScrollXInner": "100%", 
              "bScrollCollapse": true,
              data: json['data'],
              columns: [
                      { "data" : "acc_name" },
                      { "data" : "supplier_name" },
                      { "data" : "user_email" },
                      // { "data" : "email_name" },
                      // { "data" : "category" },
                      { "data" : "active" },
                      ],
              dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
              "language": {
                  "lengthMenu": "Show _MENU_",
                  "infoEmpty": "No records available",
                  "infoFiltered": "(filtered from _MAX_ total records)",
                  "zeroRecords": "<span><?php echo '<b>No Record Found.</b>'; ?></span>",
              }, 
              "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                  // $(nRow).attr('status', aData['status']);
                  if(aData['active'] == 'Yes')
                  {
                    $(nRow).find('td:eq(0)').css({"background-color":"#29ff69","color":"black"});
                    $(nRow).find('td:eq(1)').css({"background-color":"#29ff69","color":"black"});
                    $(nRow).find('td:eq(2)').css({"background-color":"#29ff69","color":"black"});
                    $(nRow).find('td:eq(3)').css({"background-color":"#29ff69","color":"black"});
                  }
                  // else
                  // {
                  //   $(nRow).find('td:eq(0)').css({"background-color":"#faddaa","color":"black"});
                  //   $(nRow).find('td:eq(1)').css({"background-color":"#faddaa","color":"black"});
                  //   $(nRow).find('td:eq(2)').css({"background-color":"#faddaa","color":"black"});
                  //   $(nRow).find('td:eq(3)').css({"background-color":"#faddaa","color":"black"});
                  //   $(nRow).find('td:eq(4)').css({"background-color":"#faddaa","color":"black"});
                  //   $(nRow).find('td:eq(5)').css({"background-color":"#faddaa","color":"black"});
                  // }
              },
              "initComplete": function( settings, json ) {
                  interval();
              },
            });//close datatable
          }//close success
        });//close ajax
      });
    },300);

  });

  $(document).on('click','#send_button',function(){
    var template_guid = $('#template_guid').val();
    var email_group = $('#email_group').val();
    var add_new_email = $('#add_new_email').val();
    var acc_guid = $('#acc_guid').val();

    if((acc_guid == '') || (acc_guid == null) || (acc_guid == 'null'))
    {
      alert('Please Select Retailer.');
      return;
    }

    if((template_guid == '') || (template_guid == null) || (template_guid == 'null'))
    {
      alert('Invalid Email Template.');
      return;
    }

    if((add_new_email == '') || (add_new_email == null) || (add_new_email == 'null'))
    {
      if((email_group == '') || (email_group == null) || (email_group == 'null'))
      {
        alert('Please Select Email User Group.');
        return;
      }
    }
    
    confirmation_modal('Are you sure to proceed Send this Template?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_template/send_the_template') ?>",
        method:"POST",
        data:{template_guid:template_guid,email_group:email_group,add_new_email:add_new_email,acc_guid:acc_guid},
        beforeSend:function(){
          swal.fire({
            allowOutsideClick: false,
            title: 'Processing...',
            showCancelButton: false,
            showConfirmButton: false,
            onOpen: function () {
            swal.showLoading()
            }
          });
          $('.btn').button('loading');
        },
        complete: function() {
          $('.btn').button('reset');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == '1') {
            $('#alertmodal').modal('hide');
            msg_data = json.msg.replace(/\\n/g,"\n");
            Swal.fire({
              title: msg_data, 
              text: '', 
              type: "error",
              allowOutsideClick: false,
              showConfirmButton: true,
            });
          }else{
            $('#alertmodal').modal('hide');
            $('#large-modal').modal('hide');
            msg_data = json.msg.replace(/\\n/g,"\n");
            //alert(json.msg.replace(/\\n/g,"\n"));
            Swal.fire({
              title: msg_data, 
              text: '', 
              type: "success",
              allowOutsideClick: false,
              showConfirmButton: true,
            });
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation

  });//close submit process

  $(document).on('click','#insert_email_btn',function(){
    var template_guid = $('#template_guid').val();
    var email_group = $('#email_group').val();
    var add_new_email = $('#add_new_email').val();
    var acc_guid = $('#acc_guid').val();

    if((acc_guid == '') || (acc_guid == null) || (acc_guid == 'null'))
    {
      alert('Please Select Retailer.');
      return;
    }

    if((template_guid == '') || (template_guid == null) || (template_guid == 'null'))
    {
      alert('Invalid Email Template.');
      return;
    }

    if((add_new_email == '') || (add_new_email == null) || (add_new_email == 'null'))
    {
      if((email_group == '') || (email_group == null) || (email_group == 'null'))
      {
        alert('Please Select Email User Group.');
        return;
      }
    }
    
    confirmation_modal('Are you sure to proceed Send this Template?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Blast_email_template/insert_batch_email') ?>",
        method:"POST",
        data:{template_guid:template_guid,email_group:email_group,add_new_email:add_new_email,acc_guid:acc_guid},
        beforeSend:function(){
          swal.fire({
            allowOutsideClick: false,
            title: 'Processing...',
            showCancelButton: false,
            showConfirmButton: false,
            onOpen: function () {
            swal.showLoading()
            }
          });
          $('.btn').button('loading');
        },
        complete: function() {
          $('.btn').button('reset');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == '1') {
            $('#alertmodal').modal('hide');
            msg_data = json.msg.replace(/\\n/g,"\n");
            Swal.fire({
              title: msg_data, 
              text: '', 
              type: "error",
              allowOutsideClick: false,
              showConfirmButton: true,
            });
          }else{
            $('#alertmodal').modal('hide');
            $('#large-modal').modal('hide');
            msg_data = json.msg.replace(/\\n/g,"\n");
            //alert(json.msg.replace(/\\n/g,"\n"));
            Swal.fire({
              title: msg_data, 
              text: '', 
              type: "success",
              allowOutsideClick: false,
              showConfirmButton: true,
            });
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation

  });//close submit process

  function isEmail(myVar){
    var regEmail = new RegExp('^[0-9a-z._-]+@{1}[0-9a-z.-]{2,}[.]{1}[a-z]{2,5}$','i');
    return regEmail.test(myVar);
  }

  $(document).on('click','#pdf_btn',function(){
    //alert('123'); die;
    var pdf_template_guid = $(this).attr('template_guid');
    var url_data = $(this).attr('url_link');
    //alert(url_data); die;

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Upload Term Sheet');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<input type="hidden" class="form-control input-sm" id="pdf_template_guid" value="'+pdf_template_guid+'" />';

    methodd += '<div class="col-md-12"><label>File</label></div><div class="col-md-10"><input id="edit_upload_file" type="file" class="form-control" accept=".pdf"></div>';

    methodd += '<div class="col-md-2"><button type="button" class="btn btn-danger" id="edit_reset_input" >Reset</button></div>';

    if(url_data != '' && url_data != 'null' && url_data != null)
    {
      methodd += '<div class="col-md-12"><label>Attachement</label><embed src="'+url_data+'" width="100%" height="500px" style="border: none;" id="pdf_view"/></div>';
    }

    methodd += '</div>';

    methodd_footer = '';

    methodd_footer += '<p class="full-width"><span class="pull-right"><span id="edit_button_file_form"></span>';

    methodd_footer += '<input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

  });

  $(document).on('change','#edit_upload_file',function(e){
    
    var edit_fileName = e.target.files[0].name;
    var pdf_template_guid = $('#pdf_template_guid').val();

    //alert(term_sheet); die;
    if((pdf_template_guid == '') || (pdf_template_guid == null) || (pdf_template_guid == 'null'))
    {
      alert('Unable to find Template Guid');
      return;
    }

    if(edit_fileName != '')
    { 
      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" edit_fileName="'+edit_fileName+'" style="margin-right:5px;"> Upload</button>');
    }
    else
    { 
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
    var pdf_template_guid = $('#pdf_template_guid').val();

    if((pdf_template_guid == '') || (pdf_template_guid == null) || (pdf_template_guid == 'null'))
    {
      alert('Template Guid Error');
      return;
    }

    confirmation_modal('Are you sure want to Upload?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#edit_upload_file')[0].files[0]);
      formData.append('file_name', edit_file_name);
      formData.append('pdf_template_guid', pdf_template_guid);
      //console.log(formData); die;
      $.ajax({
          url:"<?= site_url('Blast_email_template/upload_template_pdf');?>",
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

