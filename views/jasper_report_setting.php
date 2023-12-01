<style type="text/css">
  #acc_branch{
    height: auto;
    overflow-y: scroll;

  }
  #acc_branch_group,#acc_concept{
    height: auto;
    overflow-y: scroll;

  }
</style>
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
          <h3 class="box-title">Report Tools</h3>
          <div class="box-tools pull-right">
          <button title="Subscription" onclick="create_new()" id="breport" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#edit_report"  
            data-table="<?php echo 'email_list' ?>"
            data-mode="<?php echo 'create' ?>"
            data-customer_guid = "<?php echo $_SESSION['customer_guid'] ?>"            
            ><i class="glyphicon glyphicon-plus"></i>Create</button>

            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
          <form id="formACCconcept" method="post" action="<?php echo site_url('supplier_setup/check')?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                  <table id="report_jasper" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Seq</th>
                        <th>Action</th>
                        <!-- <th>Jasper Server URL</th> -->
                        <th>Report Folder</th>
                        <th>Hide</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($report_list->result() as $row) { ?>
                      <tr>
                        <td><?php echo $row->seq;?></td>
                        <td> 
                        <button type="button" data-toggle="modal" data-target="#edit_report" id="areport" savetype="Save" description="<?php echo $row->Description;?>" childID="<?php echo $row->childID;?>" seq="<?php echo $row->seq;?>" hide="<?php echo $row->hide;?>" web_index="<?php echo $row->web_index;?>" report_guid="<?php echo $row->report_guid;?>" value="<?php echo $row->Description;?>" columnguid="child_guid" class="editquery btn btn-xs btn-primary"><i style="font-size:10px" class="glyphicon glyphicon-pencil
                        "></i></button>
                        <button type="button" value="<?php echo $row->childID;?>" data-toggle="modal" data-target="#delete" description="<?php echo $row->Description;?>" id="deletereport" class="deletequery btn btn-xs btn-danger" style="margin:3px"><i style="font-size:10px" class="glyphicon glyphicon-trash"></i></button>

                        </td>
                        <!-- <td><?php echo $row->jasper_report_url ?></td> -->
                        <td><?php echo $row->web_index ?></td>
                        <td><?php echo $row->hide ?></td>
                      </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </form>
              </div>  
        </div>

      </div>
    </div>
  </div>
   
<!-- nothing ends after -->
</div>
</div>

  <script>
$(document).ready(function() {    
        $('#seq').keyup(function(){

            var seq = $('#seq').val();
            // alert(seq);die;
            var action = $('#actions').val();
            // alert(action);
            var original = $("#seqoriginal").val();

                 $.ajax({
                      url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/check_seq_avaibility",
                      method:"POST",
                      data:{seq:seq},
                      success:function(data){
                        // alert(data);
                        if(data == 1)
                        {
                           if(seq == original)
                           {
                              $("#seq").css("border","2px solid purple");
                              $("#actions1").prop("disabled",false);
                              var web_index = $('#web_index').val();
                          // alert(web_index);die;
                              var action = $('#actions').val();
                              var webindexoriginal = $("#webindexoriginal").val();

                              // alert(action);
                               $.ajax({
                                    url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/check_reportmenu_avaibility",
                                    method:"POST",
                                    data:{web_index:web_index},
                                    success:function(data){
                                      // alert(data);
                                      if(data == 1)
                                      {
                                          if(web_index == webindexoriginal)
                                          {
                                            $("#web_index").css("border","2px solid purple");
                                            $("#actions1").prop("disabled",false);
                                          }
                                          else
                                          {
                                            $("#web_index").css("border","2px solid red");
                                            $("#actions1").prop("disabled",true);
                                      
                                          }
                                      }
                                      else
                                      {
                                         $("#web_index").css("border","2px solid green");
                                         $("#actions1").prop("disabled",false);
                                      }

                                    }
                               });
                           }
                           else
                           { 
                              $("#seq").css("border","2px solid red");
                              $("#actions1").prop("disabled",true);
                           }
                        }
                        else
                        {
                          
                           $("#seq").css("border","2px solid green");
                           $("#actions1").prop("disabled",false);
                           var web_index = $('#web_index').val();
                          // alert(web_index);die;
                          var action = $('#actions').val();
                          var webindexoriginal = $("#webindexoriginal").val();
                          // alert(action);
                           $.ajax({
                                url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/check_reportmenu_avaibility",
                                method:"POST",
                                data:{web_index:web_index},
                                success:function(data){
                                  // alert(data);
                                  if(data == 1)
                                  {
                                    if(web_index == webindexoriginal)
                                    {
                                        $("#web_index").css("border","2px solid purple");
                                       $("#actions1").prop("disabled",false);
                                    }
                                    else
                                    {
                                       $("#web_index").css("border","2px solid red");
                                       $("#actions1").prop("disabled",true);
                                    }
                                  }
                                  else
                                  {
                                     $("#web_index").css("border","2px solid green");
                                     $("#actions1").prop("disabled",false);
                                  }

                                }
                           });
                        }
                      }
                 });


       });

$(document).on('change', '#seq', function(){

              var seq = $('#seq').val();
            // alert(seq);die;
            var action = $('#actions').val();
            // alert(action);
            var original = $("#seqoriginal").val();

                 $.ajax({
                      url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/check_seq_avaibility",
                      method:"POST",
                      data:{seq:seq},
                      success:function(data){
                        // alert(data);

                        if(data == 1)
                        {
                           if(seq == original)
                           {
                              $("#seq").css("border","2px solid purple");
                              $("#actions1").prop("disabled",false);
                              var web_index = $('#web_index').val();
                          // alert(web_index);die;
                              var action = $('#actions').val();
                              var webindexoriginal = $("#webindexoriginal").val();

                              // alert(action);
                               $.ajax({
                                    url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/check_reportmenu_avaibility",
                                    method:"POST",
                                    data:{web_index:web_index},
                                    success:function(data){
                                      // alert(data);
                                      if(data == 1)
                                      {
                                          if(web_index == webindexoriginal)
                                          {
                                            $("#web_index").css("border","2px solid purple");
                                            $("#actions1").prop("disabled",false);
                                          }
                                          else
                                          {
                                            $("#web_index").css("border","2px solid red");
                                            $("#actions1").prop("disabled",true);
                                      
                                          }
                                      }
                                      else
                                      {
                                         $("#web_index").css("border","2px solid green");
                                         $("#actions1").prop("disabled",false);
                                      }

                                    }
                               });
                           }
                           else
                           { 
                              $("#seq").css("border","2px solid red");
                              $("#actions1").prop("disabled",true);
                           }
                        }
                        else
                        {
                          
                           $("#seq").css("border","2px solid green");
                           $("#actions1").prop("disabled",false);
                           var web_index = $('#web_index').val();
                          // alert(web_index);die;
                          var action = $('#actions').val();
                          var webindexoriginal = $("#webindexoriginal").val();
                          // alert(action);
                           $.ajax({
                                url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/check_reportmenu_avaibility",
                                method:"POST",
                                data:{web_index:web_index},
                                success:function(data){
                                  // alert(data);
                                  if(data == 1)
                                  {
                                    if(web_index == webindexoriginal)
                                    {
                                        $("#web_index").css("border","2px solid purple");
                                        $("#actions1").prop("disabled",false);
                                    }
                                    else
                                    {
                                       $("#web_index").css("border","2px solid red");
                                       $("#actions1").prop("disabled",true);
                                    }
                                  }
                                  else
                                  {
                                     $("#web_index").css("border","2px solid green");
                                     $("#actions1").prop("disabled",false);
                                  }

                                }
                           });
                        }
                      }
                 });

});

$(document).on('click', '#deletereport', function(){
// alert('haha');
  var childID = $(this).val();
  var description = $(this).attr('description');
  // alert(childID);
  // alert(description);
  $("#reportchildID").val(childID);
  $("#deletereportdescription").text("Are you sure want to remove this report ("+description+") ?");
});

$(document).on('click', '#breport', function(){
  // alert();
  action_type = "Add";
  $("#actions").val(action_type);
  $("#actions1").val(action_type);

  $.ajax({
    url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/fetch_report_dropdown_all",
    method:"POST",
    // data:{childID:childID},
    success:function(data){
      // alert(data);
      $("#parentreport").html(data);
    }
});

});

$(document).on('click', '#areport', function(){

// alert('haha');
action_type = $(this).attr("savetype");
reportname = $(this).attr("description");
hidestatus = $(this).attr("hide");
seq = $(this).attr("seq");
web_index = $(this).attr("web_index");
childID = $(this).attr("childID");
report_guid = $(this).attr("report_guid");
// alert(report_guid);
$("#report_tittle").text(reportname);
$("#seq").val(seq);
$("#childID").val(childID);
$("#descriptionss").val(reportname);
$("#web_index").val(web_index);
$("#actions").val(action_type);
$("#actions1").val(action_type);
$("#webindexoriginal").val(web_index);
$("#ori_web_index").val(web_index);
$("#seqoriginal").val(seq);
if(hidestatus == 1)
{
  $("#hidestatus").prop("checked",true);
}
else
{
  $("#hidestatus").prop("checked",false);
}
// alert();
$.ajax({
    url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/fetch_report_dropdown",
    method:"POST",
    dataType:"JSON", 
    data:{childID:childID,report_guid:report_guid},
    success:function(data){
      // alert(JSON.stringify(data));
      // alert(data.dropdown);
      $("#parentreport").html(data.dropdown);
      $("#jasper_report_url").html(data.jasper_report_url);
      $("#jasper_report_folder").html(data.jasper_report_folder);
    }
});

});


    $("#web_index").on({
        keydown: function(e) {
          if ((e.which === 32) || (e.which === 191)){
              document.documentElement.style.overflow = 'hidden';  // firefox, chrome
              document.body.scroll = "no"; // ie only
              $('#result_reportmenu').text('Space and "/" character is not allow in Report Index.');
              $('#modal-warning').modal('show');
              return false;
            // $('').modal('show');
          } 
          else
          {
              $('#result_reportmenu').text('');
          }  
        },
        change: function() {
          this.value = this.value.replace(/\s/g, "");
        }
      });

    $('#web_index').keyup(function(){

            var web_index = $('#web_index').val();
            // alert(web_index);die;
            var action = $('#actions').val();
            var webindexoriginal = $("#webindexoriginal").val();
            // alert(action);
                 $.ajax({
                      url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/check_reportmenu_avaibility",
                      method:"POST",
                      data:{web_index:web_index},
                      success:function(data){
                        // alert(data);
                        if(data == 1)
                        {
                          if(web_index == webindexoriginal)
                          { 
                            $("#web_index").css("border","2px solid purple");
                             $("#actions1").prop("disabled",false);
                             var seq = $('#seq').val();
                            // alert(seq);die;
                            var action = $('#actions').val();
                            // alert(action);
                            var original = $("#seqoriginal").val();

                                 $.ajax({
                                      url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/check_seq_avaibility",
                                      method:"POST",
                                      data:{seq:seq},
                                      success:function(data){
                                        // alert(data);
                                        if(data == 1)
                                        {
                                            if(seq == original)
                                            {
                                               $("#seq").css("border","2px solid purple");
                                               $("#actions1").prop("disabled",false);
                                            }
                                            else
                                            {
                                               $("#seq").css("border","2px solid red");
                                               $("#actions1").prop("disabled",true);
                                              
                                            }

                                        }
                                        else
                                        {
                                          
                                           $("#seq").css("border","2px solid green");
                                           $("#actions1").prop("disabled",false);
                                        }
                                      }
                                 }); 
                          }
                          else
                          {
                             $("#web_index").css("border","2px solid red");
                             $("#actions1").prop("disabled",true);
                          }
                        }
                        else
                        {
                           $("#web_index").css("border","2px solid green");
                           $("#actions1").prop("disabled",false);
                            var seq = $('#seq').val();
                            // alert(seq);die;
                            var action = $('#actions').val();
                            // alert(action);
                            var original = $("#seqoriginal").val();

                             var seq = $('#seq').val();
                            // alert(seq);die;
                            var action = $('#actions').val();
                            // alert(action);
                            var original = $("#seqoriginal").val();

                                 $.ajax({
                                      url:"<?php echo base_url(); ?>index.php/Report_jasper_controller/check_seq_avaibility",
                                      method:"POST",
                                      data:{seq:seq},
                                      success:function(data){
                                        // alert(data);
                                        if(data == 1)
                                        {
                                            if(seq == original)
                                            {
                                               $("#seq").css("border","2px solid purple");
                                               $("#actions1").prop("disabled",false);
                                            }
                                            else
                                            {
                                               $("#seq").css("border","2px solid red");
                                               $("#actions1").prop("disabled",true);
                                              
                                            }

                                        }
                                        else
                                        {
                                          
                                           $("#seq").css("border","2px solid green");
                                           $("#actions1").prop("disabled",false);
                                        }
                                      }
                                 }); 
                        }

                      }
                 });


       });

     });  
  </script>