<div class="content-wrapper" style="">

  <section class="content" style="">
        
    <div class="row">
            <div class="col-md-12 col-xs-12">
              <!-- DIRECT CHAT -->
              <div class="box direct-chat direct-chat-warning">

                <div class="box-header ui-sortable-handle">


                  <h3 class="box-title">

                    Number : <b><?php echo $ticket->row('ticket_number') ?></b>

                    &nbsp;

                    Status : <b><?php echo $ticket->row('ticket_status') ?></b>
                    
                  </h3>
                  <!-- tools box -->
<?php if(in_array('OAT',$this->session->userdata('module_code')))
{
?>                  
                  <div class="box-tools pull-right">
                    <?php if ($_SESSION['user_group_name'] == 'SUPER_ADMIN') { ?>
                      <?php if($ticket->row('hide') == 0)
                            {
                      ?>
                      <button id="hide_ticket" type="button" class="btn btn-xs btn-danger"   
                        ><i class="fa fa-flag"></i> Hide Ticket
                      </button>
                      <?php
                            }
                            else
                            {
                      ?>
                      <button id="unhide_ticket" type="button" class="btn btn-xs btn-success"   
                        ><i class="fa fa-flag"></i> Unhide Ticket
                      </button>
                      <?php
                            }
                      ?>
                      <button title="" data-toggle="modal" data-target="#change_status_modal" type="button" class="btn btn-xs btn-primary"   
                        ><i class="fa fa-flag"></i> Status
                      </button>

                      <button title="" data-toggle="modal" data-target="#assigned_modal" type="button" class="btn btn-xs btn-primary"   
                        ><i class="fa fa-user"></i> Assign
                      </button>
                    <?php } ?>
                    <!-- <span data-toggle="tooltip" title="" style="background-color: #3c8dbc" class="badge" data-original-title="3 New Messages">3</span> -->
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <!-- <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="Contacts">
                      <i class="fa fa-comments"></i></button> -->
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>

                  </div>
<?php
}
?>
                  <!-- /. tools -->
                </div>
 
                <!-- /.box-header -->
                <div class="box-body">
                  <div class="col-xs-12">
                  <table style="overflow: auto;display: block;font-size: 1em">
                       <tr>
                        <td> Retailer Name</td>
                        <td>: <b><?php echo $ticket->row('retailer_name');?></b></td>
                        
                       </tr>
                        <tr>
                        <td> Supplier Name</td>
                        <td>: <b><?php echo $ticket->row('supplier_name')?></b></td>
                        
                      </tr>
                      <tr>
                        <td> Category</td>
                        <td>: <b><?php echo $ticket->row('name') ?></b></td>
                        
                      </tr>

                        <td> Sub Category</td>
                        <td>: <b><?php echo $ticket->row('sub_name') ?></b></td>

                        
                      </tr>
                      <tr>

                        <td> Created at</td>
                        <td>: <b><?php echo $ticket->row('created_at') ?></b></td>

                        
                      </tr>
                      <tr>

                        <td> Created by</td>
                        <td>: <b><?php echo $ticket->row('created_name') ?></b></td>

                        
                      </tr>
                      <?php if ($_SESSION['user_group_name'] == 'SUPER_ADMIN') { ?>
                      <tr>
                        
                        
                        <td> Assigned</td>
                        <td>: <b><?php echo $ticket->row('user_name') ?></b></td>
                        
                      </tr>
                      <tr>
                        <td> Resolved Reason</td>
                        <td>: <b><?php echo $ticket->row('rr_name') ?></b></td>

                        <td> Resolved Remark</td>
                        <td>: <b><?php echo $ticket->row('resolved_remark') ?></b></td>
                      </tr>
                      <?php } ?>
           

                    </table>
                    </div>
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages">
                    <!-- Message. Default to the left -->

                    <?php foreach ($ticket_child->result() as $key) { ?>
                      <?php if ($key->messages_type == 'U') { if($key->hide == 0){$disabled = '<span class="glyphicon glyphicon-triangle-bottom"></span>';$btn="danger";}else{$disabled = '<span class="glyphicon glyphicon-triangle-top"></span>';$btn="success";}?>
                        <div class="direct-chat-msg right">
                          <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-right"><?php echo $key->user_name ?></span>
                            <?php if(in_array('SHTICCH',$_SESSION['module_code']))
                            {
                            ;?>
                             <button hide="<?php echo $key->hide;?>" ticket_c_guid="<?php echo $key->ticket_c_guid;?>" id="hide_chat" class="btn-xs btn-<?php echo $btn;?> pull-left"><?php echo $disabled;?></button>
                             <?php
                            }
                            ;?>
                            <span class="direct-chat-timestamp pull-left"><?php echo $key->created_at ?></span>
                          </div>
                          <!-- /.direct-chat-info -->
                          <img class="direct-chat-img" src="//www.gravatar.com/avatar/591979a746b57c8ed09bca89133daeff?s=80&amp;d=mm" alt="message user image">

                          <!-- /.direct-chat-img -->

                          <div class="direct-chat-text">
                            <?php echo $key->messages ?>
                              

                          </div>
                             <div class="attachment" style="margin-top: 10px;">

                          <?php if($key->ticket_file !== '' || $key->ticket_file !== '' ){?>
                            
                          <?php foreach ($ticket_child->result() as $valuee) { $file= explode(',',$valuee->ticket_file);  $file_download = explode(',',$valuee->ticket_path);

                          ?>

                          <?php  foreach ($file_download as $value2) { 



                            //echo $filename_download[$key] . ' -- ' .$value1.'<br>';

                          ?>
               
                          <?php if ($value2 !== '' || $value2 !== null){?>
                          <?php if($key->ticket_c_guid == $valuee->ticket_c_guid){ ?>
                         <p> Attachment: <a href="<?php echo $path.'/';?><?php echo substr($value2, strrpos($value2, '/' )+1)."\n"?>" download="<?php echo substr($value2, stripos($value2, '-' )+1);?>" ><?php echo $key->ticket_file ?></a></p>
                          <?php }} ?>

                            
                          <?php }} ?>
                          <?php }?>

                          
                          </div>
                        </div>


                          <!-- /.direct-chat-text -->
                        <!-- </div> -->
                      <?php } else if ($key->messages_type == 'A') { if($key->hide == 0){$disabled = '<span class="glyphicon glyphicon-triangle-bottom"></span>';$btn="danger";}else{$disabled = '<span class="glyphicon glyphicon-triangle-top"></span>';$btn="success";}?>

                        <div class="direct-chat-msg">
                          <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left"><?php echo $key->user_name ?></span>
                            <?php if(in_array('SHTICCH',$_SESSION['module_code']))
                            {
                            ;?>
                            <button hide="<?php echo $key->hide;?>" ticket_c_guid="<?php echo $key->ticket_c_guid;?>" id="hide_chat" class="btn-xs btn-<?php echo $btn;?> pull-right"><?php echo $disabled;?></button>
                            <?php
                            }
                            ;?>
                            <span class="direct-chat-timestamp pull-right"><?php echo $key->created_at ?></span>
                          </div>

                          <!-- /.direct-chat-info -->
                          <img class="direct-chat-img" src="<?php echo base_url('asset/dist/img_sunshine/icon-512.png');?>" alt="message user image">
                          <!-- /.direct-chat-img -->
                          <div class="direct-chat-text">
                            <?php echo $key->messages ?>

                          </div>
                          <div class="attachment" style="margin-left:50px;margin-top: 10px;">

                          <?php if($key->ticket_file !== '' || $key->ticket_file !== '' ){?>
                            
                          <?php foreach ($ticket_child->result() as $valuee) { $file= explode(',',$valuee->ticket_file);  $file_download = explode(',',$valuee->ticket_path);

                          ?>

                          <?php  foreach ($file_download as $value2) { 



                            //echo $filename_download[$key] . ' -- ' .$value1.'<br>';

                          ?>
               
                          <?php if ($value2 !== '' || $value2 !== null){?>
                          <?php if($key->ticket_c_guid == $valuee->ticket_c_guid){ ?>
                           <p> Attachment: <a href="<?php echo $path.'/';?><?php echo substr($value2, strrpos($value2, '/' )+1)."\n"?>" download="<?php echo substr($value2, strrpos($value2, '-' )+1);?>" ><?php echo $key->ticket_file ?></a></p>
                          <?php }} ?>

                            
                          <?php }} ?>
                          <?php }?>

                          
                          </div>

                          <!-- /.direct-chat-text -->
                        </div>

                          <!--<?php foreach ($ticket->result() as $value) { $filename = explode(',',$value->ticket_file);  $filename_download = explode(',',$value->ticket_path);

                          ?>

                          <?php  foreach ($filename as $key => $value1) { 



                            //echo $filename_download[$key] . ' -- ' .$value1.'<br>';

                          ?>
               
                           <?php if ($value1 !== '' || $value1 !== null){?>
                           <p> <a href="<?php echo base_url('asset/ticket'.$path.'/').$value1;?>" download="<?php echo $value1?>" ><?php echo $value1;?></a></p>
                           <?php } ?>

                        
                            <?php }} ?>-->
                      <?php } ?>
                    <?php } ?>

                  </div>

                  <!-- Contacts are loaded here -->
                  <div class="direct-chat-contacts">
                    <ul class="contacts-list">
                      <li>
                        <a href="#">

                          <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Count Dracula
                                  <small class="contacts-list-date pull-right">2/28/2015</small>
                                </span>
                            <span class="contacts-list-msg">How have you been? I was...</span>
                          </div>
                          <!-- /.contacts-list-info -->
                        </a>
                      </li>
                      <!-- End Contact Item -->
                     
                    </ul>
                    <!-- /.contatcts-list -->
                  </div>
                  <!-- /.direct-chat-pane -->
                </div>
                <!-- /.box-body -->

                 <?php if($ticket->row('ticket_status') !== 'Closed' || $ticket->row('ticket_status') === null ) {?>      
                <div class="box-footer">
                  <form method="post" action="<?php echo site_url('Ticket/ticket_messages_send')?>" enctype="multipart/form-data" >
     
                      <input type="hidden" id="hide_ticket_guid" name="ticket_guid" required="true" value="<?php echo $_REQUEST['t_g'] ?>">
                      <textarea class="summernote_textarea" name="messages" placeholder="Type Message ..." class="form-control" required="true"></textarea>

                      <div class="upload" style="margin-bottom: 10px;  ">
                      <span class="btn btn-default btn-file"> Choose Files <input type="file" id="uploads" name="myFile1[]" multiple /></span>
                       <ul id="Dash" style=" list-style:decimal";></ul>
                      </div>
               

                      <center><button type="submit" name="submit" class="btn btn-flat" style="background-color: #3c8dbc;color: white">Send</button></center>
                    
                  </form>
                </div>
                <?php } ?>
 
                <!-- /.box-footer-->
              </div>
              <!--/.direct-chat -->
            </div>


            <!-- /.col -->
          </div>


          
  
  </section>

</div>

<div class="modal fade" id="change_status_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="<?php echo site_url('Ticket/change_ticket_status')?>" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4>Change Status</h4>
                
              </div>
              <div class="modal-body" style="display: flow-root;">
                  <div class="col-md-12">
                    <label>Status</label>
                    <select id="status" class="form-control" name="status">
                      <?php foreach ($ticket_status as $key) { ?>
                        <option value="<?php echo $key ?>"><?php echo $key ?></option>
                      <?php } ?>

                    </select>

                    <span id="resolved_ticket_reason_wrap" style="display: none">
                    <label>Resolved Reason</label>
                    <select class="form-control" name="resolved_reason" id="resolved_reason">
                       <option value="">— Select —</option>
                       <?php foreach ($ticket_resolved_reason->result() as $key) { ?>
                        <option value="<?php echo $key->rr_guid ?>"><?php echo $key->rr_name ?></option>
                      <?php } ?>
                            
                    </select>
                    </span>

                    <span id="resolved_ticket_remark_wrap" style="display: none">
                    <label>Resolved Remark</label>
                    <input type="text" class="form-control" name="resolved_remark">
                    </span>

                    <input type="hidden" name="ticket_guid" value="<?php echo $_REQUEST['t_g'] ?>">
                  </div>

              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input type="submit" value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
</div>
 
<script type="text/javascript">
$(document).ready(function () {
  var attachments = document.getElementById('uploads');
  var clickCount = 0;
 
  //var attachments = document.getElementById('uploadBtn');
  
  $("#uploads").click(function () {
    var numFiles = attachments.files.length;
   //var numFiles = $("input:file", this)[0].files.length;
    if (numFiles !== 0 || clickCount > 1)
    {
  
      var kiki = confirm("Are you sure to make your second selection? You might lost your first file selection.");
          if(kiki == false)
        {
          return false;
        }

        else
        {
            return true;
        }
    } 

        else if (clickCount > 0 || numFiles === 0)
    {
          return true;
    }

        else 
    {
          
          clickCount++;

          return true;
    }
  });
});



</script>




<script>
$('#uploads').change(function(){

       var acc_guid = $(this).attr('acc_guid');
       var supp_guid = $(this).attr('supp_guid');
       var topic_guid = $(this).attr('topic_guid');
       var sub_topic_guid = $(this).attr('sub_topic_guid');
       var msg = $(this).attr('msg');
       var uploadBtn = $(this).attr('uploads');
      
        var attachments = document.getElementById('uploads');
        var muse = document.getElementById('Dash');
 
   
        var item = '';


        for(var i=0; i<attachments.files.length; i++) {

          item += '<li >' +'<a href="#" id="'+ i +'" "></a>&nbsp&nbsp'  + attachments.files.item(i).name +
            
            '</li>';


          console.log(attachments.files.item(i).name);
         

        }
 
        $('#Dash').html(item);
       

       // $('.dlt-attch').on('click',function(){

         // document.getElementById('uploadBtn').value = '';
        
          //});


});
</script>


<script type="text/javascript">
  
$('#status').change(function()
{
    if (this.value == 'Closed') {

        $('#resolved_ticket_reason_wrap').show();

        $("#resolved_reason").prop('required',true);

        $('#resolved_ticket_remark_wrap').show();

        $("#resolved_remark").prop('required',true);

    } else {

        $('#resolved_ticket_reason_wrap').hide();

        $("#resolved_reason").prop('required',false);

        $('#resolved_ticket_remark_wrap').hide();

        $("#resolved_remark").prop('required',false);
    }



})

</script>

<div class="modal fade" id="assigned_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="<?php echo site_url('Ticket/assigned_user')?>" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4>Assigned</h4>
                
              </div>
              <div class="modal-body" style="display: flow-root;">
                  <div class="col-md-12">
                    <label>Staff(Super Admin)</label>
                    <select id="status" class="form-control" name="assigned">
                      <?php foreach ($super_admin->result() as $key) { ?>
                        <option value="<?php echo $key->user_guid ?>"><?php echo $key->user_name .'-'.$key->user_id ?></option>
                      <?php } ?>

                    </select>

                    <input type="hidden" name="ticket_guid" value="<?php echo $_REQUEST['t_g'] ?>">
                  </div>

              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input type="submit" value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
</div>


<script src="<?php echo base_url('asset/jquery.min.js')?>"></script>

<div style="padding-left: 130px;background-color:white; " style="display:none;">

  <input type="file" id="document_attachment_doc" style="display:none;"/>

  <img id="blah" src="#" alt="your image" style="display:none;"/>

</div>

<script>

$(document).ready(function () {
$(document).on('click','#hide_ticket',function(){
var ticket_guid = "<?php echo $_REQUEST['t_g'] ?>";
// alert(ticket_guid);
      var modal = $("#medium-modal").modal();

      modal.find('.modal-title').html('Hide Ticket');

      methodd = '';
      methodd +='<form action="<?php echo site_url('Ticket/hide_ticket');?>" method="post" id="form_hide_ticket">';
      methodd +='<div class="col-md-12">';

      methodd += '<div class="col-md-6"><h5>Are you sure to hide this ticket <?php echo $ticket->row('ticket_number');?></h5></div>';

      methodd += '<div class="col-md-6"><input type="hidden" value='+ticket_guid+' name="hide_ticket_guid"/></div></form>';

      methodd += '</div>';

      methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" class="btn btn-success" id="submit_hide_ticket" value="Hide"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

      modal.find('.modal-body').html(methodd);
      modal.find('.modal-footer').html(methodd_footer);

});

$(document).on('click','#unhide_ticket',function(){
var ticket_guid = "<?php echo $_REQUEST['t_g'] ?>";
// alert(ticket_guid);
      var modal = $("#medium-modal").modal();

      modal.find('.modal-title').html('Unhide Ticket');

      methodd = '';
      methodd +='<form action="<?php echo site_url('Ticket/unhide_ticket');?>" method="post" id="form_unhide_ticket">';
      methodd +='<div class="col-md-12">';

      methodd += '<div class="col-md-6"><h5>Are you sure to unhide this ticket <?php echo $ticket->row('ticket_number');?></h5></div>';

      methodd += '<div class="col-md-6"><input type="hidden" value='+ticket_guid+' name="hide_ticket_guid"/></div></form>';

      methodd += '</div>';

      methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" class="btn btn-success" id="submit_unhide_ticket" value="Unhide"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

      modal.find('.modal-body').html(methodd);
      modal.find('.modal-footer').html(methodd_footer);

});

$(document).on('click','#submit_hide_ticket',function(){
  // alert();
$('#form_hide_ticket').submit();
});

$(document).on('click','#submit_unhide_ticket',function(){
  // alert();
$('#form_unhide_ticket').submit();
});

$(document).on('click','#hide_chat',function(){
var ticket_c_guid = $(this).attr('ticket_c_guid');
var hide = $(this).attr('hide');
// alert(hide);
// alert(ticket_c_guid);
if(hide == 0)
{
  $.ajax({
        url:"<?php echo site_url('Ticket/hide_ticket_child'); ?>",
        method:"POST",
        data:{ticket_c_guid:ticket_c_guid},
        success:function(data){
          // alert(data)
          if(data > 0)
          {
            alert('Hide Record Updated .');
            location.reload();
          }
          else
          {
            alert('Hide Record Not Update !');
          }
        }
   });
}
else
{
  $.ajax({
        url:"<?php echo site_url('Ticket/unhide_ticket_child'); ?>",
        method:"POST",
        data:{ticket_c_guid:ticket_c_guid},
        success:function(data){
          // alert(data)
          if(data > 0)
          {
            alert('Unhide Record Updated .');
            location.reload();
          }
          else
          {
            alert('Unhide Record Not Update !');
          }
        }
   });
}  

});
                        $.fn.extend({
                            placeCursorAtEnd: function () {
                                // Places the cursor at the end of a contenteditable container (should also work for textarea / input)
                                if (this.length === 0) {
                                    throw new Error("Cannot manipulate an element if there is no element!");
                                }
                                var el = this[0];
                                var range = document.createRange();
                                var sel = window.getSelection();
                                var childLength = el.childNodes.length;
                                if (childLength > 0) {
                                    var lastNode = el.childNodes[childLength - 1];
                                    var lastNodeChildren = lastNode.childNodes.length;
                                    range.setStart(lastNode, lastNodeChildren);
                                    range.collapse(true);
                                    sel.removeAllRanges();
                                    sel.addRange(range);
                                }
                                return this;
                            }
                        });
                    });

const fileInput = document.getElementById("document_attachment_doc");

fileInput.addEventListener('change', () => {
  form.submit();
});

window.addEventListener('paste', e => {
  fileInput.files = e.clipboardData.files;

    if (fileInput.files && fileInput.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {

      $('#blah').attr('src', e.target.result);

      var previous_val = $('.summernote_textarea').val();

      $('.summernote_textarea').val(previous_val+'<br>'+'<p><img style="width: 50%;" src="'+reader.result+'" data-filename="'+fileInput.files[0].name+'"><br></p>')
      $('.summernote_textarea').summernote('destroy');

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


      // $('.note-editable').summernote('focus');

      $('.note-editable').placeCursorAtEnd();

      $('.note-editable').scrollTop($('.note-editable')[0].scrollHeight);  
      
    }
    
    reader.readAsDataURL(fileInput.files[0]);
  }

});

</script>