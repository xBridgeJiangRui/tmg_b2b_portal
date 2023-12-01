<!-- <footer class="main-footer">
    <div class="pull-right hidden-xs">
      Policy:&nbsp;<a href="https://b2b.xbridge.my/admin_files/Privacy%20Policy%20(ENGLISH).pdf" target="_blank">(EN)</a> <a href="https://b2b.xbridge.my/admin_files/Privacy%20Policy%20(BM).pdf" target="_blank">(BM)</a>&nbsp;<b>+6017-745-1185/+6017-715-9340</b>  &nbsp<img src="<?php echo base_url('asset/dist/img_sunshine/icon-512.png');?>" class="img-circle" alt="User Image" style="height: 32px">
    </div>
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="http://www.xbridge.my">Rexbridge Sdn. Bhd.</a></strong> All rights
    reserved.
</footer>
 -->


<!-- <?php $ticket_topic = $this->db->query("SELECT * FROM ticket_topic ORDER BY name ")->result() ?>
<?php 
if($this->session->userdata('customer_guid') == '' || $this->session->userdata('customer_guid') == null)
{
$retailer_name = $this->db->query("SELECT DISTINCT e.logo,d.acc_guid,e.acc_name,e.seq FROM `set_user` AS a 
                    INNER JOIN `set_user_branch` b ON a.user_guid = b.user_guid
                    INNER JOIN `acc_branch` c ON b.branch_guid = c.branch_guid 
                    INNER JOIN `acc_concept` d ON c.concept_guid = d.concept_guid 
                    INNER JOIN `acc` e ON d.`acc_guid` = e.`acc_guid` 
                    where a.user_id = '".$_SESSION['userid']."' 
                    and e.isactive = '1' 
                    and a.module_group_guid = '".$_SESSION['module_group_guid']."' 
                    order by e.seq asc,e.row_seq ASC" )->result() ;
}
else
{
  $retailer_name =  $this->db->query("SELECT DISTINCT e.logo,d.acc_guid,e.acc_name,e.seq FROM `set_user` AS a 
                    INNER JOIN `set_user_branch` b ON a.user_guid = b.user_guid
                    INNER JOIN `acc_branch` c ON b.branch_guid = c.branch_guid 
                    INNER JOIN `acc_concept` d ON c.concept_guid = d.concept_guid 
                    INNER JOIN `acc` e ON d.`acc_guid` = e.`acc_guid` 
                    where a.user_id = '".$_SESSION['userid']."' 
                    and e.isactive = '1' 
                    and a.module_group_guid = '".$_SESSION['module_group_guid']."' AND e.acc_guid = '".$this->session->userdata('customer_guid')."'
                    order by e.seq asc,e.row_seq ASC" )->result() ;
}?>
<?php $supplier_name = $this->db->query("SELECT * FROM set_supplier ORDER BY supplier_name ")->result() ?> -->
  <!-- /.control-sidebar s-->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
<!-- <button style="" class="open-button" onclick="openForm()"><i class="fa fa-comments-o" aria-hidden="true"></i></button> -->

<div class="modal alertmodal fade" id="alertmodal" role="dialog" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog  modal-sm alertmodal-dialog">
    <div class="modal-header" style="padding: 10px;background-color:white; ">
                <button type="button" class="close"  aria-label="Close">
                  <span aria-hidden="true">&nbsp;</span></button>
                  <h4 class="modal-title"></h4>
              </div>
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <p class="icons"></p><br>
          <p class="msg"></p>                    
        </center>
        
      </div>
      <div class="modal-footer button">
      </div>
    </div>
  </div>
</div> 

<div id="medium-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>

      <div class="modal-body table-responsive modal-control-size">
        
      </div>

      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>

<div id="static-medium-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title"></h4>
      </div>

      <div class="modal-body table-responsive modal-control-size">
        
      </div>

      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>

<div id="xlarge-modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>

      <div class="modal-body table-responsive modal-control-size">
        
      </div>

      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>

<div id="large-modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>

      <div class="modal-body table-responsive modal-control-size">
        
      </div>

      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>

<div id="large-modal1" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>

      <div class="modal-body table-responsive modal-control-size">
        
      </div>

      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>


<!-- small modal -->
<div id="small-modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body table-responsive" id="1233">
        <form id="small-modal-form">
         <div id="small-modal-fields"></div>
          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success saveadd" onclick=""></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="chat-popup" id="activity_box" style="width:300px;background-color: white;bottom:50px;">

    <h3 style="margin-top: 10px"><i class="fa fa-info-circle" aria-hidden="true"></i>Activities</h3>


      <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list ui-sortable" style="max-height: 400px;overflow-y: scroll;" id="activity_box_body">
             
              </ul>
            </div>


    <button type="button" id="cancel_button" class="btn btn-block btn-danger" style="border: none;border-radius: 0;">Close</button>

</div>

<!-- <div class="chat-popup" id="chat_box" >
  <form action="<?php echo site_url('Ticket/user_open_ticket')?>" class="form-container" method="post" id="chat" enctype="multipart/form-data" ">
    <h3 style="margin-top: 0px"><i class="fa fa-ticket" aria-hidden="true"></i>Create Ticket</h3>

    <div class="tooltip9"><i class="fa fa-question-circle"></i>
      <span class="tooltiptext">In order for us to serve you better, here is where you can directly contact us about the issue you had faced. If you cant find any category that is related to your issue, kindly let us know. Thanks for cooperation.</span>
    </div>

    <div class="form-group">
      <label>Retailer</label>
      <select class="form-control" name="acc_guid" id="acc_guid" required>
        <option value="">-Select-</option>
        <?php foreach ($retailer_name as $key) {?>
          <option value="<?php echo $key->acc_guid ?>"><?php echo $key->acc_name ?></option>
        <?php } ?>
      </select>
     <label>Supplier</label>
        
      <select class="form-control" name="supplier_guid"  id="supplier_guid" required>
       <option value="">-Select-</option>
      </select>
      <label>Category</label>
      <select class="form-control" name="topic_guid" id="topic_guid" required>
        <option value="">-Select-</option>
        <?php foreach ($ticket_topic as $key) { ?>
          <option value="<?php echo $key->t_topic_guid ?>"><?php echo $key->name ?></option>
        <?php } ?>
      </select>

    </div>
    <div class="form-group">
      <label>Sub Category</label>
      <select class="form-control" name="sub_topic_guid" id="sub_topic_guid" required>
       <option value="">-Please Pick Topic-</option>
      </select>
      
    </div>
    <label for="msg" id="msg"><b>Message</b></label>
    <textarea class="summernote_textarea_ticket" style="min-height: 60px;" placeholder="Message.." name="messages" id="ticket_summernote_message" required="required"></textarea>
      
   <div class="upload" style="margin-bottom: 10px;  ">
   <span class="btn btn-default btn-file"> Choose Files <input type="file" id="uploadBtn" name="myFile[]" multiple /></span>
    <ul id="myFile" style=" list-style:decimal";></ul>
   </div>

    <button id="submit_ticket" type="submit" name="submit" class="btn_chat">Submit</button>
    <button type="button" class="btn_chat cancel" onclick="closeForm()">Close</button>
  </form>
</div> -->

<script type="text/javascript">
$(document).ready(function () {
  var attachments = document.getElementById('uploadBtn');
  var clickCount = 0;
 
  //var attachments = document.getElementById('uploadBtn');
  
  $("#uploadBtn").click(function () {
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
$('#uploadBtn').change(function(){

       var acc_guid = $(this).attr('acc_guid');
       var supp_guid = $(this).attr('supp_guid');
       var topic_guid = $(this).attr('topic_guid');
       var sub_topic_guid = $(this).attr('sub_topic_guid');
       var msg = $(this).attr('msg');
       var uploadBtn = $(this).attr('uploadBtn');
      
        var attachments = document.getElementById('uploadBtn');
        var muse = document.getElementById('myFile');
 
   
        var item = '';


        for(var i=0; i<attachments.files.length; i++) {

          item += '<li >' +'<a href="#" id="'+ i +'" "></a>&nbsp&nbsp'  + attachments.files.item(i).name +
            
            '</li>';


          console.log(attachments.files.item(i).name);
         

        }
 
        $('#myFile').html(item);
       

       // $('.dlt-attch').on('click',function(){

         // document.getElementById('uploadBtn').value = '';
        
          //});


});
</script>


<script>

alertmodal = function(data){

  $("#alertmodal").modal();
  $('#alertmodal .button').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
  $('#alertmodal .icons').html('<i class="fa fa-exclamation-circle fa-5x" style="color:red;"></i>');
  $('#alertmodal .msg').html(data);
  $('#alertmodal .modal-title').html('Information');
};

confirmation_modal = function(data){
  $("#alertmodal").modal();
  $('#alertmodal .button').html('<button type="button" class="btn btn-danger pull-right btn-gap" data-dismiss="modal" style="float:left">No</button><button type="button" class="btn btn-success pull-right btn-gap" id="confirmation_yes" style="margin-right:5px;">Yes</button>');
  $('#alertmodal .icons').html('<i class="fa fa-question fa-5x"></i>');
  $('#alertmodal .msg').html(data);
  $('#alertmodal .modal-title').html('Confirmation');
  };

informationalertmodal = function(button,icons,msg,title){
    var modal = $("#alertmodal").modal();
    modal.find('.button').html(button);
    modal.find('.icons').html(icons);
    modal.find('.msg').html(msg);
    modal.find('.modal-title').html(title);
    $('.btn').button('loading');
  }//function information alert modal standarized method


function openForm() {
  document.getElementById("chat_box").style.display = "block";
}

function closeForm() {
 
    acc_guid = $('#acc_guid').val();
         supp_guid = $('#supplier_guid').val();
         topic_guid = $('#topic_guid').val();
         sub_topic_guid = $('#sub_topic_guid').val();
         msg = $('#msg').val();
         uploadBtn = $('#uploadBtn').val();



         if(acc_guid !== '' || supp_guid !== '' || topic_guid !== '' || sub_topic_guid !== '' || msg !== '' || uploadBtn !== '' )  {


        var test = confirm('Are you sure to exit?');

       {
         if (test == true)
          {

            document.getElementById("chat").reset();
            document.getElementById("chat_box").style.display = "none";
            document.getElementById("myFile").style.display = "none";
            location.reload();
            return true;
          }
 
       else

         {
    
           return false;
            //document.getElementById("chat_box").style.display = "block";
 
         }
    }
}
else
{

 document.getElementById("chat_box").style.display = "none";
}
}

$("select[name='topic_guid']").change(function(){

  topic_guid = $(this).val();

    $.ajax({
            url:"<?php echo site_url('Ticket/get_subtopic');?>",
            method:"POST",
            data:{topic_guid:topic_guid},
            success:function(data)
            { 
              json = JSON.parse(data);
              
              html = '<option value="" disabled selected readonly>-Select-</option>';

              for(i = 0; i < json['ticket_sub_topic'].length; i++)
              {
                html +='<option ';
                        

                html +='value="'+json['ticket_sub_topic'][i].t_sub_topic_guid+'">'+json['ticket_sub_topic'][i].name+'</option>';
              } 

              $("select[name='sub_topic_guid']").html(html)

            }//close succcess
        });//close ajax

  

})

$("select[name='acc_guid']").change(function(){


  acc_guid = $(this).val();

    $.ajax({
            url:"<?php echo site_url('Ticket/get_supplier');?>",
            method:"POST",
            data:{acc_guid:acc_guid},
            success:function(data)
            { 
              json = JSON.parse(data);
              
              html = '<option value="" disabled selected readonly>-Select-</option>';

              for(i = 0; i < json['ticket_supplier'].length; i++)
              {
                html +='<option ';
                        

                html +='value="'+json['ticket_supplier'][i].supplier_guid+'">'+json['ticket_supplier'][i].supplier_name+'</option>';
              } 

              $("select[name='supplier_guid']").html(html)
               $("select[name='supplier_guid']").prop('required',true);

            }//close succcess
        });//close ajax

  

})


</script>

<!-- <script type="text/javascript">
  
$(document).ready(function () {
$('.summernote_textarea_ticket').summernote({

  minHeight: 100,
  maxHeight: 100,    

  toolbar: [
  ['insert', ['picture']],
  ], 

  });

})

</script> -->

<!-- <script type="text/javascript">

$("#notifications_button").click(function(){

var time = '<?php echo $this->db->query("SELECT now() as now")->row('now');?>'

var user_guid = '<?php echo $_SESSION['user_guid']; ?>';

$.ajax({
  url:"<?php echo site_url('General/clear_notification/');?>",
  method:"POST",
  data:{user_guid:user_guid,time:time},
  success:function(data)
  { 
    $("#notifications_new_count").remove();
  }//close success
});//close ajax


});//close simple_receive_buton

$(document).ready(function () {

$('#notifications_menu').on('scroll', function() {

notifications_loading = $('#notifications_loading').val()

        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {

          if (notifications_loading == '' ) {

          notification_li_length = $("#notifications_menu > li").length;

          var user_guid = '<?php echo $_SESSION['user_guid']; ?>';

          $.ajax({
            url:"<?php echo site_url('General/get_notification/');?>",
            method:"POST",
            data:{notification_li_length:notification_li_length,user_guid:user_guid},
            beforeSend : function() {

            html = ''

            html += '<li class="notification_spinner"><a><i class="fa fa-spinner" aria-hidden="true"></i></a></li>'

            $("#notifications_menu").append(html);
            $("#notifications_loading").val('1');

            },
            complete: function() {
            /*$('#notifications_menu .notification_spinner').fadeOut();*/
            $('#notifications_menu .notification_spinner').remove();
            
            },   
            success:function(data)
            { 
              json = JSON.parse(data);

              if (json.length == '0') {
                $("#notifications_loading").val('1');
                
              } else {

                $("#notifications_loading").val('');
              }

              html = ''

              for(i = 0; i < json.length; i++)
              {
                html += '<li> <a href="'+json[i].link+'" title="'+json[i].message+'"> <i class="fa fa-ticket"></i> '+json[i].message+'<br><small class="pull-right"> '+json[i].created_at+'</small> </a> </li>'
              } 

              

              $("#notifications_menu").append(html);
            }//close success
          });//close ajax

          }
            
        }
    })

})

 </script> -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url('asset/plugins/jQuery/jquery-2.2.3.min.js')?>"></script>

<!-- Bootstrap 3.3.6 -->
<!-- closing this because this cause multiple input error -->
 <script src="<?php echo base_url('asset/bootstrap/js/bootstrap.min.js')?>"></script> 

<!-- Slimscroll -->
<script src="<?php echo base_url('asset/plugins/slimScroll/jquery.slimscroll.min.js')?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url('asset/plugins/fastclick/fastclick.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('asset/dist/js/app.min.js')?>"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('asset/dist/js/demo.js')?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url('asset/plugins/daterangepicker/daterangepicker.js')?>"></script>
<link rel="stylesheet" href="<?php echo base_url('asset/plugins/datepicker/datepicker3.css');?>">
<script src="<?php echo base_url('asset/plugins/datepicker/bootstrap-datepicker.js');?>"></script>

<!-- DataTables -->
<script src="<?php echo base_url('asset/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('asset/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('asset/plugins/datatables/dataTables.buttons.min.js')?>"></script>
<script src="<?php echo base_url('asset/plugins/datatables/buttons.flash.min.js')?>"></script>
<script src="<?php echo base_url('asset/plugins/datatables/buttons.print.min.js')?>"></script>
<script src="<?php echo base_url('asset/plugins/datatables/buttons.html5.min.js')?>"></script>
<script src="<?php echo base_url('asset/plugins/datatables/jszip.min.js')?>"></script>
<!--  <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js" > </script>
 <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js" > </script>
 <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js" > </script>
 <script src=" https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js" > </script>
 <script src=" https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" > </script>
 -->
<script src="<?php echo base_url('asset/plugins/select2/select2.full.min.js')?>"></script>
<!--<script src="<?php echo base_url('asset/plugins/summernote/summernote.js')?>"></script>-->
<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/summernote.js');?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
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





    });

</script>

<script type="text/javascript">
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
  });
</script>

<script type="text/javascript">
     $("#check-all").click(function () {
        $(".data-check").prop('checked', $(this).prop('checked'));
    });
    document.addEventListener('contextmenu', function(e){
    e.preventDefault();
    });
</script>

<!-- <script type="text/javascript">
  // function get_report_menu(backend, backend_member , frontend, lite_b2b)
  // {   
  //   var xlocation = "<?php echo $_SESSION["query_loc"];?>";
  //   var location = xlocation.replace(/'/g, "");

  //   var xquery_supcode = "<?php echo $_SESSION["query_supcode"];?>";
  //   var query_supcode = xquery_supcode.replace(/'/g, "");
  //   // alert(string);
  //   $.ajax({
  //   url:"<?php echo base_url(); ?>/index.php/dashboard/cust_get_ip",
  //   method:"POST",
  //   data:{},
  //   dataType:"json",
  //   success:function(data){
  //                   $.ajax({
  //                         type: "get",        
  //                         url: data.ip+"/rest_client/index.php/report_hub?web_module="+lite_b2b+"&db_backend="+backend+"&db_member="+backend_member+"&db_frontend="+frontend+"&location="+location+"&query_supcode="+query_supcode,//shoot url to rest client                           
  //                         beforeSend : function() {
  //                           $('#report_menu_btn').button('loading');
  //                         },
  //                         complete: function() {
  //                           $('#report_menu_btn').button('reset');
  //                         },                    
  //                         success: function(data) 
  //                         { 
  //                           $("#menu").html(data);
  //                         },
  //                         error: function()
  //                         {
  //                           $("#menu").html('<center>No data found</center>');
  //                         }
  //                       });
  //           } 
  //   }); 
  // }

   function preview(url,title)
  {
    tab = window.open(url);
    tab.document.body.innerHTML = '<title>Panda Report - '+title+'</title><object width="100%" height="100%" data="'+url+'"></object>';
  }

  function close_report()
  {
    $(".preview-jasper").attr("hidden", "hidden");
  }

$(document).ready(function () {
  var usergroup = "<?php echo $_SESSION['user_group_name'];?>";
  // alert(usergroup);
  if(usergroup == "SUPER_ADMIN")
  {
      $.ajax({
      url: '<?php echo site_url('Menu_loop_all_new')?>',
      type: 'GET',
      success: function(data){
        // alert(data);
        $('#jasper_menu').replaceWith(data);
      }//close sucess
    });//close ajax
  }
  else
  {
       $.ajax({
       url: '<?php echo site_url('Menu_loop_user_new')?>',
      type: 'GET',
       success: function(data){
         // alert(data);
         $('#jasper_menu').replaceWith(data);
      }//close sucess
   });//close ajax

      //$.ajax({
      //url: '<?php echo site_url('Menu_loop_all')?>',
      //type: 'GET',
      //success: function(data){
        // alert(data);
        //$('#jasper_menu').replaceWith(data);
      //}//close sucess
    //});//close ajax      
  }

});
</script> -->

<!-- <script>
    $(document).ready(function () {
        $('#paloi').DataTable({
            "processing": true,
            "serverSide": true,
            "buttons": [
            {
                extend: 'excelHtml5',
                exportOptions: { orthogonal: 'export' }
            },
             /*'excel',  'print'*/ 
            ],
            /*dom: 'lBfrtip',*/
            dom:'<"row"<"col-sm-2" l><"col-sm-4" B><"col-sm-6" f>>rtip',
            lengthMenu: [
                [ 10, 25, 50, 99999999 ],
                [ '10', '25', '50', 'Show all' ]
            ],
            "ajax":{
         "url": "<?php echo $datatable_url ?>",
         "dataType": "json",
         "type": "POST",
         "data":{  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' }
                       },
<?php if($_SESSION['frommodule'] == 'panda_po_2' ) { ?>
      "columns": [
              { "data": "refno" },
              { "data": "gr_refno" },
              { "data": "loc_group" },
              { "data": "code" },
              { "data": "name" },
              { "data": "podate" },
              { "data": "delivery_date" },
              { "data": "expiry_date" },
              { "data": "total" },
              { "data": "gst_tax_sum" },
              { "data": "total_include_tax" },
              { "data": "status" },
              { "data": "rejected_remark" },
              { "data": "button" },
              { "data": "box" },
           ],
            "order": [[ 5, "desc" ]],
            "columnDefs": [
              { 
                "targets": [ 13,14 ], //first column
                "orderable": false, //set not orderable
              },
            ],
            // "stateSave": true,

<?php }; ?>

<?php if($_SESSION['frommodule'] == 'panda_po_2_new' ) { ?>
      "columns": [
              { "data": "refno" },
              { "data": "gr_refno" },
              { "data": "loc_group" },
              { "data": "scode" },
              { "data": "sname" },
              { "data": "podate" },
              { "data": "delivery_date" },
              { "data": "expiry_date" },
              { "data": "total", render:function( data, type, row){
                var element = '';

                element+= '<span class="pull-right">'+parseFloat(row['total']).toFixed(2)+'</span>';

                return element;
              }},
              { "data": "gst_tax_sum", render:function( data, type, row){
                var element = '';

                element += '<span class="pull-right">'+parseFloat(row['gst_tax_sum']).toFixed(2)+'</span>';

                return element;
              }},
              { "data": "total_include_tax", render:function( data, type, row){
                var element = '';

                element += '<span class="pull-right">'+parseFloat(row['total_include_tax']).toFixed(2)+'</span>';

                return element;
              }},
              { "data": "status" },
              { "data": "rejected_remark" },
              { "data": "refno", render:function( data, type, row ){

                var element = '';

                  <?php
                  if(in_array('HFSP',$_SESSION['module_code']) && $_REQUEST['status']=='' && $this->session->userdata('customer_guid') != '8D5B38E931FA11E79E7E33210BD612D3')
                  {
                  ?>
                    element += '<a href="<?php echo site_url('panda_po_2_new/po_child');?>?trans='+row['refno']+'&loc=<?php echo $_REQUEST["loc"];?>" style="float:left" class="btn-sm btn-info" role="button"><span class="glyphicon glyphicon-eye-open"></span></a>';

                    element += '<a onclick="hide_modal()" role="button" class="btn-sm btn-danger"  data-toggle="modal" data-target="#otherstatus" style="float:left" data-refno="'+row['refno']+'" data-loc="<?php echo $_REQUEST["loc"];?>"><span class="glyphicon glyphicon-eye-open"></span></a>';

                  <?php
                  }
                  else
                  {
                  ?>
                    element += '<a href="<?php echo site_url('panda_po_2_new/po_child');?>?trans='+row['refno']+'&loc=<?php echo $_REQUEST["loc"];?>&accpt_po_status='+row['status']+'" style="float:left" class="btn-sm btn-info" role="button"><span class="glyphicon glyphicon-eye-open"></span></a>';
                  <?php
                  }
                  ?>

                  return element;
              }},
              { "data": "refno", render:function( data, type, row ){
                var element = '';
                var n = fruits.includes(row['refno']);
                if(n == true)
                {
                  element += '<input type="checkbox" class="data-check" value="'+row['refno']+'" checked>';
                }
                else
                {
                  element += '<input type="checkbox" class="data-check" value="'+row['refno']+'">';
                }

                return element;
              }},
           ],
           "order": [[ 5, "desc" ]],
            "columnDefs": [
              { 
                "targets": [ 13,14 ], //first column
                "orderable": false, //set not orderable
              },
            ],
            "fnDrawCallback": function( oSettings ) {
              checkall = 0;
                alert(checkall);
                $(".data-check").each(function() {
                  
                  if(!$(this).is(':checked'))
                  {
                    checkall = checkall+1;
                    // alert('haha'+checkall);
                  }
                  // check_refno.push(this.value);
                  // fruits.push(this.value);
                });
                alert('final'+checkall);
                if(checkall == 0){$("#check-all").prop("checked", true);}else{$("#check-all").prop("checked", false);}
            },
            // "initComplete": function( settings, json ) {
            //   alert(checkall);
            //     if(checkall == 1){$("#check-all").prop("checked", false);}
            // },
            // "stateSave": false,

<?php }; ?>

<?php if($_SESSION['frommodule'] == 'panda_gr' ) { ?>
      "columns": [
              { "data": "refno" },
              { "data": "grda_status" },
              { "data": "loc_group" },
              { "data": "code" },
              { "data": "name" },
              { "data": "grdate" },
              { "data": "docdate" },
              { "data": "dono" },
              { "data": "invno" },
              { "data": "cross_ref" },
              { "data": "total" },
              { "data": "gst_tax_sum" },
              { "data": "total_include_tax" },
              { "data": "status" },
              { "data": "button" },
              { "data": "box" },
           ],
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {

              $(nRow).find('td').find('input[type="checkbox"]').attr('name', 'bulk[]');
              $(nRow).attr('refno', aData['refno']);
              $(nRow).attr('invno', aData['invno']);

           },             
            "columnDefs": [
              { 
                "targets": [ 14,15 ], //first column
                "orderable": false, //set not orderable
              },
            ],
            "stateSave": true,   
           
<?php }; ?>
//desmond add for new datatable
<?php if($_SESSION['frommodule'] == 'panda_gr_new' ) { ?>
      "columns": [
              { "data": "refno" },
              { "data": "grda_status", render:function( data, type, row ){
                var element = '';

                element += '<a href="<?php echo site_url('panda_grda_new/grda_child?trans=');?>'+row['grda_status']+'&loc='+'<?php echo $_REQUEST['loc'];?>'+'">'+row['grda_status']+'</a>';

                return element;
              }},
              { "data": "loc_group" },
              { "data": "code" },
              { "data": "name" },
              { "data": "grdate" },
              { "data": "docdate" },
              { "data": "dono" },
              { "data": "invno" },
              { "data": "cross_ref" },
              { "data": "total", render:function( data, type, row){
                var element = '';

                element+= '<span class="pull-right">'+parseFloat(row['total']).toFixed(2)+'</span>';

                return element;
              }},
              { "data": "gst_tax_sum", render:function( data, type, row){
                var element = '';

                element += '<span class="pull-right">'+parseFloat(row['gst_tax_sum']).toFixed(2)+'</span>';

                return element;
              }},
              { "data": "total_include_tax", render:function( data, type, row){
                var element = '';

                element += '<span class="pull-right">'+parseFloat(row['total_include_tax']).toFixed(2)+'</span>';

                return element;
              }},
              { "data": "status" },
              { "data": "refno", render:function( data, type, row){

                var element = '';

                element += '<a href="<?php echo site_url('panda_gr_new/gr_child');?>?trans='+row['refno']+'&loc='+'<?php echo $_REQUEST["loc"];?>'+'&accpt_gr_status='+row['status']+' style="float:left" class="btn btn-sm btn-info" role="button"><span class="glyphicon glyphicon-eye-open"></span></a>';

                return element;
              }},
              { "data": "refno", render:function( data, type, row){
                var element = '';

                element += '<input type="checkbox" class="data-check" value="'+row['refno']+'">';

                return element;
              }},
           ],
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {

              $(nRow).find('td').find('input[type="checkbox"]').attr('name', 'bulk[]');
              $(nRow).attr('refno', aData['refno']);
              $(nRow).attr('invno', aData['invno']);

           },
            "columnDefs": [
              { 
                "targets": [ 12,13,14,15 ], //first column
                "orderable": false, //set not orderable
              },
            ],
            // "stateSave": true,   
           
<?php }; ?>


<?php if($_SESSION['frommodule'] == 'panda_grda' ) { ?>
      "columns": [
              { "data": "refno" },
              { "data": "loc_group" },
              { "data": "transtype" },
              { "data": "code" },
              { "data": "name" },
              { "data": "sup_cn_no" },
              { "data": "sup_cn_date" },
              { "data": "dncn_date" },
              { "data": "varianceamt" },
              { "data": "status" },
              { "data": "button" },
              { "data": "box" },
           ]   ,         
            "columnDefs": [
              { 
                "targets": [ 10,11 ], //first column
                "orderable": false, //set not orderable
              },
            ],
            "stateSave": false,   
<?php }; ?>

<?php if($_SESSION['frommodule'] == 'panda_grda_new' ) { ?>
      "columns": [
              { "data": "refno" },
              { "data": "loc_group" },
              { "data": "transtype" },
              { "data": "code" },
              { "data": "name" },
              { "data": "sup_cn_no" },
              { "data": "sup_cn_date" },
              { "data": "dncn_date" },
              { "data": "varianceamt" },
              { "data": "status" },
              { "data": "refno", render:function( data, type, row){

                var element = '';

                element += '<a href="<?php echo site_url('panda_grda_new/grda_child');?>?trans='+row['refno']+'&loc='+'<?php echo $_REQUEST["loc"];?>'+'&accpt_gr_status='+row['status']+' style="float:left" class="btn btn-sm btn-info" role="button"><span class="glyphicon glyphicon-eye-open"></span></a>';

                return element;
              }},
              { "data": "refno", render:function( data, type, row){
                var element = '';

                element += '<input type="checkbox" class="data-check" value="'+row['refno']+'">';

                return element;
              }},
           ],
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {

              $(nRow).find('td').find('input[type="checkbox"]').attr('name', 'bulk[]');
              $(nRow).attr('refno', aData['refno']);

           },
            "columnDefs": [
              { 
                "targets": [ 10,11 ], //first column
                "orderable": false, //set not orderable
              },
            ],
            // "stateSave": true,   
           
<?php }; ?>

<?php if($_SESSION['frommodule'] == 'panda_prdncn' ) { ?>
      "columns": [
              { "data": "refno" },
              { "data": "locgroup" },
              { "data": "type" },
              { "data": "code" },
              { "data": "name" },
              { "data": "docdate" },
              { "data": "docno" },
              { "data": "amount" },
              { "data": "gst_tax_sum" },
              { "data": "total_incl_tax" },
              { "data": "status" },
              { "data": "button" },
              { "data": "box" },
           ],

           "columnDefs": [
              { 
                "targets": [ 11,12 ], //first column
                "orderable": false, //set not orderable
              },
            ],
<?php }; ?>


<?php if($_SESSION['frommodule'] == 'panda_pdncn' ) { ?>
      "columns": [
              { "data": "refno" },
              { "data": "loc_group" },
              { "data": "code" },
              { "data": "name" },
              { "data": "trans_type" },
              { "data": "docno" },
              { "data": "docdate" },
              { "data": "amount" },
              { "data": "gst_tax_sum" },
              { "data": "amount_include_tax" },
              { "data": "status" },
              { "data": "button" },
              { "data": "box" },
           ],
           "columnDefs": [
              { 
                "targets": [ 11,12 ], //first column
                "orderable": false, //set not orderable
              },
            ],  
<?php }; ?>

<?php if($_SESSION['frommodule'] == 'panda_pci' ) { ?>
      "columns": [
              { "data": "inv_refno" },
              { "data": "promo_refno" },
              { "data": "loc_group" },
              { "data": "sup_code" },
              { "data": "sup_name" },
              { "data": "docdate" },
              { "data": "total_bf_tax" },
              { "data": "gst_value" },
              { "data": "total_af_tax" },
              { "data": "status" },
              { "data": "button" },
              { "data": "box" },
           ],
           "columnDefs": [
              { 
                "targets": [ 10,11 ], //first column
                "orderable": false, //set not orderable
              },
            ],   
<?php }; ?>

<?php if($_SESSION['frommodule'] == 'panda_di' ) { ?>
      "columns": [
              { "data": "inv_refno" },
              { "data": "loc_group" },
              { "data": "sup_code" },
              { "data": "sup_name" },
              { "data": "docdate" },
              { "data": "datedue" },
              { "data": "total_net" },
              { "data": "status" },
              { "data": "button" },
              { "data": "box" },
           ],
           "columnDefs": [
              { 
                "targets": [ 8,9 ], //first column
                "orderable": false, //set not orderable
              },
            ],
<?php }; ?>

<?php if($_SESSION['frommodule'] == 'panda_return_collection' ) { ?>
      "columns": [
              { "data": "batch_no" },
              { "data": "location" },
              { "data": "prdn_refno" },
              { "data": "doc_date" },
              { "data": "expiry_date" },
              { "data": "sup_code" },
              { "data": "sup_name" },
              { "data": "status" },
              { "data": "canceled" },
              { "data": "button" },
           ]   ,

           "columnDefs": [
              { 
                "targets": [ 9 ], //first column
                "orderable": false, //set not orderable
              },
            ],
<?php }; ?>

<?php if($_SESSION['frommodule'] == 'panda_gr_download' ) { ?>
      "columns": [
              { "data": "refno" },
              { "data": "grda_status" },
              { "data": "loc_group" },
              { "data": "code" },
              { "data": "name" },
              { "data": "grdate" },
              { "data": "docdate" },
              { "data": "dono" },
              { "data": "invno" },
              { "data": "cross_ref" },
              { "data": "total" },
              { "data": "gst_tax_sum" },
              { "data": "total_include_tax" },
              { "data": "status" },
              { "data": "button" },
              { "data": "box" },
           ],
            "columnDefs": [
              { 
                "targets": [ 14,15 ], //first column
                "orderable": false, //set not orderable
              },
            ],
            "stateSave": true,   
           
<?php }; ?>


      });
    });
</script> -->

<!-- <script>
   $(document).ready(function () {
       $('#sup_checklist').DataTable({
         "buttons": [
            {
                extend: 'excelHtml5',
                exportOptions: { orthogonal: 'export' }
            },
             /*'excel',  'print'*/ 
            ],
            /*dom: 'lBfrtip',*/
            dom:'<"row"<"col-sm-2" l><"col-sm-4" B><"col-sm-6" f>>rtip',
            lengthMenu: [
                [ 10, 25, 50, 99999999 ],
                [ '10', '25', '50', 'Show all' ]
            ],
            "processing": true,
            "serverSide": true,
            "sScrollX": "100%", 
            "sScrollXInner": "100%", 
            "bScrollCollapse": true,
            "ajax":{
         "url": "<?php echo $datatable_url ?>",
         "dataType": "json",
         "type": "POST",
         "data":{  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' }
                       },
        
      "columns": [
              <?php if(in_array('UAP',$_SESSION['module_code']))
              {
              ?>
              { "data": "action" },
              <?php
              }
              ?>
              { "data": "type" },
              { "data": "AccountCode" },
              { "data": "code" },
              { "data": "name" },
              { "data": "sup_name" },
              { "data": "reg_no" },
              { "data": "block" },
              { "data": "remark1" },
              { "data": "PIC" },
              { "data": "invoice_no" },
              { "data": "training_pax" },
              { "data": "tel" },
              { "data": "PAYMENT"  },
              { "data": "IsActive",
              "render": function ( data, type, row, meta ) {
                    if(data =='1')
                    {
                      return type === 'export' ? data :
                       "<input type='checkbox' value='1' checked readonly tabindex=-1 >"

                    }
                    else
                    {
                      return type === 'export' ? data :
                        "<input type='checkbox' value='0' readonly tabindex=-1 >"

                    }
                  }
               },
                 {"data" : "ACCEPT_FORM",
                "render": function ( data, type, row, meta ) {
                    if(data =='RECEIVED')
                    {
                      return type === 'export' ? data :
                        "<input type='checkbox' value='RECEIVED' checked readonly tabindex=-1 >"
                    }
                    else
                    {
                      return type === 'export' ? data :
                        "<input type='checkbox' value='PENDING' readonly tabindex=-1 >"

                    }
                  }
                },
              
               {"data" : "REG_FORM",
                "render": function ( data, type, row, meta ) {
                    if(data =='RECEIVED')
                    {

                      return type === 'export' ? data :
                      "<input type='checkbox' value='RECEIVED' checked readonly tabindex=-1 >"
                    }
                    else
                    {
                      return type === 'export' ? data :
                        "<input type='checkbox' value='PENDING' readonly tabindex=-1 >"

                    }
                  }
             },
            
              { "data": "STATUS" },  
              { "data": "supply_type" },  
              
           ],
            "columnDefs": [
              { 
                "targets": [3,4], //first column
                "orderable": true, //set not orderable 

              },

            ],
            "stateSave": true,

            "createdRow": function( row, data, dataIndex ) {
             if ( data['IsActive'] == "0" ) {   
              $(row).addClass('red');  
              }  
    }
      });
    }); 
</script> -->

 
<script>
  // $(function () {
  //   $("#reg_supplier").DataTable({
  //       "order": [[ 2, "asc" ]]
  //       ,stateSave: true
  //   });
  // });
  //   $(function () {
  //   $("#group_supplier").DataTable({
  //       "order": [[ 2, "asc" ]]
  //       ,stateSave: true
  //   });
  // });
  $(function () {
    $("#email_subscription").DataTable({
        "order": [[ 0, "asc" ],[ 1, "asc" ]]
        ,stateSave: true
    });
  });
   $(function () {
    $("#email_subscription2").DataTable({
        "order": [[ 0, "asc" ],[ 1, "asc" ]]
        ,stateSave: true
    });
  });
  $(function () {   
    $("#fax_list").DataTable({    
        "order": [[ 5, "desc" ]]    
    });   
  });
  $(function () {   
    $("#report_jasper").DataTable();    
  });   
   $(function () {
    $("#report_tools").DataTable({
        "order": [[ 1, "asc" ]]
        ,stateSave: true
    });
  });
</script>
<script>
  $(function () {
    $("#example11").DataTable({
        "order": [[ 3, "asc" ]]
        ,stateSave: true
    });
  });
</script>
<script>
  // $(function () {
  //   $("#acc1").DataTable({
  //       "order": [[ 3, "asc" ]]
  //       ,stateSave: true
  //   });
  // });
</script>
<script>
  $(function () {
    $("#topup").DataTable({
        "order": [[ 0, "asc" ],[ 1, "asc" ]]
        ,stateSave: true
    });
  });
</script>
<script>
  $(function () {
    $("#finance").DataTable({
        "order": [[ 0, "asc" ],[ 1, "desc" ]]
        ,stateSave: true
    });
  });
</script>
<script>
  $(function () {
    $("#bybankindate").DataTable({
        "order": [[ 0, "asc" ],[ 1, "desc" ]]
        ,stateSave: true
    });
  });
</script>
<script>
  $(function () {
    $("#pettycash").DataTable({
        "order": [[ 2, "desc" ],[3, "desc"]],
        "iDisplayLength": 50
        ,stateSave: true
    });
  });
</script>
<script>
  $(function () {
    $("#petty_batch").DataTable({
        "order": [[ 1, "desc" ],[2, "desc"]],
        "iDisplayLength": 50
        ,stateSave: true
    });
  });
</script>
<script>
  $(function () {
    $("#otherrecon").DataTable({
        "order": [[ 0, "asc" ],[ 1, "desc" ]]
        ,stateSave: true
    });
  });
</script>

<script type="text/javascript">
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
  });
</script>
<script>

$(document).ready(function() {
    var table = $('#example').removeAttr('width').DataTable( {
        scrollX:        true,
        scrollCollapse: true,
        paging:         true,
        columnDefs: [
            { width: 100, targets: 0 }
        ],
        fixedColumns: true
    } );
} );

  $(function () {
    $("#example1").DataTable();
    $("#from_cus").DataTable();
    $("#to_cus").DataTable();
    $("#acc_concept").DataTable({
        scrollX:        true,
        scrollCollapse: true,
        paging:         true,
        
        fixedColumns: true,

        "order": [[ 4, "desc" ], [ 3, 'desc' ]]
    });
    $("#acc_branch_group").DataTable({
        scrollX:        true,
        scrollCollapse: true,
        paging:         true,
        
        fixedColumns: true,

        "order": [[ 4, "desc" ], [ 3, 'desc' ]]
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "lengthMenu": [[5, 25, 50, -1], [5, 25, 50, "All"]]
    });
    $("#user_module").DataTable({
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        searching: false,
        ordering: false,
        fixedColumns: true,

        "order": [[ 2, "desc" ], [ 4, 'desc' ]]
    });
  });

  $('#reservationtime').daterangepicker({
    timePicker: false, 
    timePickerIncrement: 5, 
    format: 'MM/DD/YYYY h:mm A'
  });
/*
   $("#anjing").click(function(){
    
    
    success: function($data){
      $('.target').html(data)
      $("#please").modal('show');
    }
    
  });
*/
</script>



<script type="text/javascript">

function check()
{
    var answer=confirm("Confirm want to delete record ?");
    return answer;
}

</script>

<script type="text/javascript">
    $(document).ready(function() {
    $("input[name$='branch_mode']").click(function() {
        var test = $(this).val();

        $("div.desc").hide();
        $("div.descDefault").hide();
        $("#method" + test).show();
    });
});
</script>

<script type="text/javascript">
    $(document).ready(function() {
    $("input[name$='supplier_mode']").click(function() {
        var test = $(this).val();

        $("div.desc").hide();
        $("div.descDefault").hide();
        $("#method" + test).show();
    });
});
</script>
<!-- <script>
    

    $(document).ready(function(){
      $('#sidebar-menu').trigger('click');
    });
</script> -->

<script>// view user branch
$(document).ready(function(){
  
  // $(document).on('click', '#viewbranch', function(e){
    
  //   e.preventDefault();
    
  //   var uid = $(this).data('id');   // it will get id of clicked row
  //   var customer_guid = $(this).data('customer_guid');   // it will get id of clicked row
    
  //   $('#dynamic-content').html(''); // leave it blank before ajax call
  //   $('#modal-loader').show();      // load ajax loader
    
  //   $.ajax({
  //     url: '<?php echo base_url('viewbranch.php')?>',
  //     // url: 'getuser.php',
  //     type: 'POST',
  //     data: 'id='+uid+'&customer_guid='+customer_guid,
  //     dataType: 'html'
  //   })
  //   .done(function(data){
  //     console.log(data);  
  //     $('#dynamic-content').html('');    
  //     $('#dynamic-content').html(data); // load response 
  //     $('#modal-loader').hide();      // hide ajax loader 
  //   })
  //   .fail(function(){
  //     $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
  //     $('#modal-loader').hide();
  //   });
    
  // });
  
});

</script>




<script type="text/javascript">
function check()
{
    var answer=confirm("Confirm want to delete record ?");
    return answer;
}


$(document).ready(function (){
   var table = $('#example').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': '/lab/jquery-datatables-checkboxes/ids-arrays.php',
      'columnDefs': [
         {
            'targets': 8,
            'checkboxes': {
               'selectRow': true
            }
         }
      ],
      'select': {
         'style': 'multi'
      },
      'order': [[1, 'asc']]
   });

   // Handle form submission event
   $('#formPO').on('submit', function(e){
      var form = this;

      var rows_selected = table.column(8).checkboxes.selected();

      // Iterate over all selected checkboxes
      $.each(rows_selected, function(index, rowId){
         // Create a hidden element
         $(form).append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'select-all[]')
                .val(rowId)
         );
      });
   });
   
});

function choose_supplier_type(data)
{
  var type = data;

  if(data == 'Supplier')
  {
    $('#multipleSupplier').html('');
    $('#methodSupplier').css('display' ,'block');
    $('#methodSupplierGroup').css('display' ,'none');
    $('#supplier_multiple').prop('checked', false);
  }else if(data == 'SupplierGroup')
  {
    $('#multipleSupplier').html('');
    $('#methodSupplier').css('display' ,'none');
    $('#methodSupplierGroup').css('display' ,'block');
    $('#supplier_multiple').prop('checked', false);
  }
}
</script>
<style type="text/css">
  input[type="checkbox"][readonly] {
  pointer-events: none !important;
}

</style>
<style>
.dataTables_wrapper .dataTables_processing {
background-color:#3C8DBC; 
}
</style>
 


</body>
</html>

<!-- <script>
interval = function(){
  var interval =  setInterval(function(){
      $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();

     clearInterval(interval);
    }, 300);//adjust the table column;
};


$(document).ready(function(){ 

  // $(document).on('click','#open_log_ticket',function(){
  //   $('.btn-group-fab').toggleClass('active');
  // });

  // $('has-tooltip').tooltip();

  // button_variable = '';

  // button_variable += '<div class="btn-group-fab active" role="group" aria-label="FAB Menu"> <div>  ';

  // button_variable+= '<button type="button" class="btn btn-main btn-primary has-tooltip" id="open_log_ticket" data-placement="left" title="More"> <i class="fa fa-plus"></i> </button>';

  // button_variable+='<button type="button" class="btn btn-sub btn-info has-tooltip" data-placement="left" onclick="openForm()" title="Open Ticket"> <i class="fa fa-comments-o"></i> </button>';

  // <?php
  // if(isset($activity_logs_section))
  // {
  // ?>
  // // button_variable+='<div class="tooltip bs-tooltip-top" role="tooltip"> <div class="arrow"></div> <div class="tooltip-inner"> Some tooltip text! </div> </div>';
  //   button_variable+=' <button type="button" class="btn btn-sub btn-danger has-tooltip" data-placement="left" title="Logs" id="activity_logs_button" section="<?=$activity_logs_section;?>"> <i class="fa fa-bars"></i> </button> ';
  // <?php
  // }
  // else
  // {
  // ?>

  //   button_variable+=' <button type="button" class="btn btn-sub btn-danger has-tooltip" data-placement="left" title="Activity logs" id="activity_logs_button" section=""> <i class="fa fa-bars"></i> </button> ';

  // <?php
  // }
  // ?>
  

  // button_variable += '</div> </div>';

  // // <button style="right:20px;top:50px;background-color: #555; color: white; padding: 16px 20px; border: none; cursor: pointer; opacity: 0.8; position: fixed;border-radius: 50px; z-index: 1000000;" onclick="openForm()"><i class="fa fa-comments-o" aria-hidden="true"></i></button>

  // // <button style="" class="open-button" onclick="openForm()"><i class="fa fa-comments-o" aria-hidden="true"></i></button>

  // $('body').append(button_variable);


  // start = 0;
  // end = 10;

  // $(document).on('click','#activity_logs_button',function(){

  //     $('#activity_box').show();

  //     <?php
  //     if(isset($activity_logs_section))
  //     {
  //     ?>

  //       if(start>0)
  //       {
  //         return;
  //       }

  //       <?php
  //       if(isset($_REQUEST['loc']))
  //       {
  //       ?>
  //         var loc = '<?= $_REQUEST["loc"];?>';
  //       <?php
  //       }
  //       else
  //       {
  //       ?>
  //         var loc = 'HQ';
  //       <?php
  //       }
  //       ?>

  //       var section = $(this).attr('section');

  //       $.ajax({

  //           url:"<?php echo site_url('User_log/logs');?>",
  //           method:"POST",
  //           data:{section:section,start:start,end:end,loc:loc},
  //           beforeSend:function()
  //           { 
  //             loading_li = 1;
  //             // $('.btn').button('loading');
  //             html = ''

  //             html += '<li class="notification_spinner"><a><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></a></li>'

  //             $("#activity_box_body").append(html);
              
  //           },
  //           success:function(data)
  //           {

  //             json = JSON.parse(data);

  //             if(json['logs'] == '')
  //             { 
  //               $('#activity_box').find('#activity_box_body').html('<label>No activities recently...</label>');

  //               $('#activity_box_body .notification_spinner').remove();
                
  //               loading_li = 0;
  //             }
  //             else
  //             {
  //               $('#activity_box').find('#activity_box_body').html(json['logs']);

  //               $('#activity_box_body .notification_spinner').remove();

  //               loading_li = 0;

  //             }

              
  //           }//close success
  //       });//close ajax

  //     <?php
  //     }
  //     else
  //     {
  //     ?>

  //       $('#activity_box').find('#activity_box_body').html('<label>No activities recently...</label>');

  //       $('#activity_box_body .notification_spinner').remove();
        
  //       loading_li = 0;

  //     <?php
  //     }
  //     ?>

      

  // });//close activity_logs_button



  // loading_li = 0;
  // final_stop = 0;

  // $("#activity_box_body").on( 'scroll', function(){
  //    console.log('Event Fired');

  //    // alert($(this).scrollTop()+$(this).innerHeight()+50'--'+$(this)[0].scrollHeight);

  //    if($(this).scrollTop() + $(this).innerHeight() + 50 >= $(this)[0].scrollHeight) {

  //     <?php
  //     if(isset($activity_logs_section))
  //     {
  //     ?>
  //       var section = '<?=$activity_logs_section;?>';
  //     <?php
  //     }
  //     else
  //     {
  //     ?>
  //       var section = '';
  //     <?php
  //     }
  //     ?>
      


  //     // end = start;

  //     if(loading_li == 0)
  //     {


  //       <?php
  //       if(isset($_REQUEST['loc']))
  //       {
  //       ?>
  //         var loc = '<?= $_REQUEST["loc"];?>';
  //       <?php
  //       }
  //       else
  //       {
  //       ?>
  //         var loc = 'HQ';
  //       <?php
  //       }
  //       ?>
  //       if(final_stop == 0)
  //       {
  //       $.ajax({

  //           url:"<?php echo site_url('User_log/logs');?>",
  //           method:"POST",
  //           data:{section:section,start:start,end:end,loc:loc},
  //           beforeSend:function()
  //           { 
  //             loading_li = 1;
  //             // $('.btn').button('loading');
  //             html = ''

  //             html += '<li class="notification_spinner"><a><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></a></li>'

  //             $("#activity_box_body").append(html);
              
  //           },
  //           success:function(data)
  //           {

  //             json = JSON.parse(data);
  //             console.log(json['haha']);
  //             if(json['logs'] != '' && json['logs'] != null)
  //             {
  //               setTimeout(function(){
  //                 $('#activity_box').find('#activity_box_body').append(json['logs']);

  //                 $('#activity_box_body .notification_spinner').remove();

  //                 loading_li = 0;
  //                 start = start+end;
  //               },300);//close time out
  //             }
  //             else
  //             {
  //               final_stop = 1;
  //               $('#activity_box_body .notification_spinner').remove();

  //                 loading_li = 0;
  //             }


              

  //           }//close success
  //       });//close ajax
  //     }//close final_stop

  //     }

  //    }//close if

  // });


  $(document).on('click','#cancel_button',function(){

    $(this).closest('div.chat-popup').hide();

  });//close cancel_button

$(document).on('show.bs.modal', '.modal', function (e) {

    if (e.namespace === 'bs.modal') {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    }
    
});

<?php
if(isset($activity_logs_section))
{
?>
      setTimeout(function(){
        $('#activity_logs_button').click();
      },300);
<?php
}
?>

});//close document ready
</script> -->
<!-- if needed the close notification refer desmond -->
<script type="text/javascript">
  $(document).on('click','#cancel_button',function(){

    $(this).closest('div.chat-popup').hide();

        $.ajax({
            url:"<?php echo site_url('General/create_close_notification');?>",
            method:"POST",
            success:function(data)
            { 
              json = JSON.parse(data);

            }//close succcess
        });//close ajax

  });//close cancel_button
$(document).on('show.bs.modal', '.modal', function (e) {

    if (e.namespace === 'bs.modal') {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    }
    
});
</script>
      <?php
      if(isset($activity_logs_section))
      {return;
      ?>  
        $.ajax({
            url:"<?php echo site_url('General/check_for_close_notification');?>",
            method:"POST",
            success:function(data)
            { 
              json = JSON.parse(data);

              if(json['close_status'] == 0)
              {
                setTimeout(function(){
                  $('#activity_logs_button').click();
                },300);
              }//close if

              

            }//close succcess
        });//close ajax

            
      <?php
      }
      ?>


<?php
if($this->session->userdata('idle_time') > 0)
{
?>

<script type="text/javascript">

var idleTime = 0;
$(document).ready(function () {

    //Increment the idle time counter every minute.
    idleInterval = setInterval(timerIncrement, 60000); // 1 minute

    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
      console.log('mouse')
        idleTime = 0;
    });

    $(this).mousedown(function (e) {
      console.log('mouse down')
        idleTime = 0;
    });

    $(this).scroll(function (e) {
      console.log('scroll')
        idleTime = 0;
    });

    $(this).keypress(function (e) {
      console.log('keypress')
        idleTime = 0;
    });

});

function timerIncrement() {
    idleTime = idleTime + 1;
    console.log(idleTime);
    if (idleTime >= '<?php echo $this->session->userdata('idle_time');?>') { // 20 minutes

      if(!$("embed").is(":visible")){

        if (confirm("No action for too long. Please press 'OK' to refresh.")) {
          // Save it!
          idleTime = 0;
          window.location.reload();
        } else {
          // Do nothing!
          console.log('No refresh.');
          idleTime = 0;
          clearInterval(idleInterval);
          idleInterval = setInterval(timerIncrement, 60000);
        }
      }//close do this for none embeded page only

    
        // window.location.reload();
        // alert('idle 10 second');
    }
}
</script>   


<?php
}
?>
<!-- <script type="text/javascript">
    $(document).ready(function() {

      $("#submit_ticket").click(function(){
          var value = $('#ticket_summernote_message').val();
          if(value == '' || value == null)
          {
                alert('Please Key In Message');
          }
      });

      <?php if(in_array('OAT',$this->session->userdata('module_code')) && $this->session->userdata('module_code') != '')
      {
      ?>      
        setInterval(function(){
            $.ajax({

                  url:"<?php echo site_url('Ticket/ticket_notification_count');?>",
                  method:"POST",
                  dataType:"JSON",
                  data:null,
                 
                  success:function(data)
                  {
                    // alert(data.New);
                    if(data.New != 0)
                    {
                          // $('#helpdesk').load("header");
                          $('#helpdesk').html('<span id="helpdesk" class="label label-danger" style="padding: 3px;right: 0; margin-top:-13px;position: absolute;">'+data.New+'</span>');
                    } 
                    else
                    {
                          $('#helpdesk').html('<p id="helpdesk"></p>');
                    } 
                  }//close success
              });  
        }, 3000); 
      <?php 
      }
      ?>  
    });

</script> -->