<?php
$customer_guid = $this->session->userdata('customer_guid');
$user_guid = $this->session->userdata('user_guid');

$this->db->query("SET @customer_guid = '$customer_guid'");
$this->db->query("SET @user_guid = '$user_guid'");
;?>


<!--  @@@@@@@@@@@@@@@@@@@@@@ Start Register Supplier modal @@@@@@@@@@@@@@@@ -->
<div class="modal fade" id="selected_modal2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Acceptance</h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('CusAdmin_controller/acknowledge') ?>" method="POST" id="form" class="form-horizontal">
                      <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="announcement_guid" id="announcement_guid"/> 
                            <input type="hidden" name="user_guid" id="user_guid"/>  
                            <div class="col-md-9">
                            <textarea name="content" rows="10" cols="30" class="form-control" disabled></textarea>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <!-- <button type="submit" id="sendButton" class="btn btn-sm btn-primary">I AGREE</button> -->
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" >Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@ End Register Supplier modal @@@@@@@@@@@@@@@@@@@@@@@@@ -->
<!--  @@@@@@@@@@@@@@@@@@@@@@ Start Register Supplier modal @@@@@@@@@@@@@@@@ -->
<?php foreach($check_announcement_acknowledgement->result() as $index => $row){ ?>
<div class="modal fade" id="auto_modal<?php echo $index;?>" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title"><?php echo $row->header;?></h3>
            </div>
            <form action="<?php echo site_url('CusAdmin_controller/acknowledge') ?>" method="POST" id="form" class="form-horizontal">
            <div class="modal-body form">
                
                      <div class="form-body">
                        <div class="form-group">

                            <input type="hidden" name="announcement_guid" id="announcement_guid" value="<?php echo $row->announcement_guid ?>"/> 
                            <input type="hidden" name="user_guid" id="user_guid" value='<?php echo $_SESSION['user_guid'] ?>'/>  

                            <?php if ($row->pdf_status == 1) { ?>

                              <div class="col-sm-12">
                                 <?php
                                 $i = 1;
                                 $file_name_array = explode("-+0+-",$row->content);
                                 $guid = $row->announcement_guid;
                                 //https://file.xbridge.my/b2b-pdf/ann_doc/
                                 foreach($file_name_array as $row1)
                                 {
                                  ?>
                                    <embed src="<?php echo 'https://file.xbridge.my/b2b-pdf/ann_doc/'.$session_guid.'/'.$guid.'/'.$row1.'.pdf'; ?>" width="100%" height="500px" style="border: none;"/>
                                 <?php
                                 }

                                 if(($row->agree == 1) && ($row->upload_docs == 1)&& ($row->need_docs == 0))
                                 {
                                   ?>
                                   <div class="checkbox">
                                    <label>
                                      <input type="checkbox" id="terms" value="1"/> 
                                      &nbsp <span style="background-color: #FFFF00">I Agree the <span style="font-weight: bold"><?php echo $row->title ?></span></span>
                                    </label>
                                  </div>
                                   <?php  
                                 }   
                                 ?>                     

                                
                              </div>
                            

                          <?php } else { ?>
                            
                            <div class="col-md-9">
                            <?php echo $row->content ?>
                            </div>
                          <?php } ?>


                        </div>
                    </div>
                  
                  </div>
                  <div class="modal-footer">
                    <?php if($row->agree == 1)
                    {
                    ;?>
                      <?php if(($row->upload_docs == 1)&& ($row->need_docs == 0))
                      {
                      ?>
                      <button type="submit" id="sendButton" class="btn btn-xm btn-primary agree_btn"><?php echo $row->button1;?></button>
                      <?php
                      }
                      else
                      {
                        if($row->upload_docs == 0)
                        {
                          ?>
                          <button type="submit" id="sendButton" class="btn btn-xm btn-primary"><?php echo $row->button1;?></button>
                          <?php  
                        }
                        else
                        {
                          ?>
                          <a href="<?php echo $row->upload_link;?>" target="_blank"><button id="redirect_pdf" type="button" title="EDIT" class="btn btn-xm btn-warning" > <i class="glyphicon glyphicon-open"></i> Upload Signed Copy</button></a>
                          <?php  
                        }
                      }
                    }
                    else
                    {
                    ;?>
                    <?php
                      if($row->upload_docs == 1)
                        {
                         ?>
                          <a href="<?php echo $row->upload_link;?>" target="_blank"><button id="redirect_pdf" type="button" title="EDIT" class="btn btn-xm btn-warning" > <i class="glyphicon glyphicon-open"></i> Upload Signed Copy</button></a>
                          <?php                      
                        }
                        else
                        {
                          ?>
                          <a href="" class="btn btn-primary btn-flat next_modal" data-dismiss="modal"><?php echo $row->button1;?></a>
                          <?php
                        }
                    }
                    ?>
                    <?php if($row->mandatory == 1)
                    {
                    ;?>
                      <?php if($row->need_docs == 0)
                        {
                          ?>
                          <a href="<?php echo site_url('login_c/logout')?>" class="btn btn-default btn-flat">Cancel</a>
                          
                          <?php  
                        }
                        else
                        {
                          ?>
                           <a href="" class="btn btn-default btn-flat next_modal" data-dismiss="modal">Cancel</a>
                          <?php  
                        }
                    }
                    else
                    {
                    ;?>
                      <a href="" class="btn btn-default btn-flat next_modal" data-dismiss="modal">Cancel</a>
                    <?php
                    }
                    ?>                      
                      <!-- <a href="<?php echo site_url('login_c/logout')?>" class="btn btn-default btn-flat">Cancel</a> -->
                  </div>
                  </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php } ?>

<div class="modal fade" id="selected_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> Acceptance Form-->
                <h3 class="modal-title acceptance_title"></h3>
            </div>
            <div class="modal-body form">
                <form action="<?php echo site_url('CusAdmin_controller/acknowledge') ?>" method="POST" id="form" class="form-horizontal">
                  <input type="hidden" name="announcement_guid" id="announcement_guid" value="<?php echo $row->announcement_guid ?>"/> 
                            <input type="hidden" name="user_guid" id="user_guid" value='<?php echo $_SESSION['user_guid'] ?>'/> 
                      <div class="form-body">
                        <div id="selected_modal_embed" class="col-sm-12">

                        
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" >Close</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@ End Register Supplier modal @@@@@@@@@@@@@@@@@@@@@@@@@ -->






<!--  @@@@@@@@@@@@@@@@@@@@@@ Notification modal @@@@@@@@@@@@@@@@ -->
<div class="modal fade" id="notification_modal" role="dialog" style="overflow-y:auto;" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Notification</h3>
            </div>
            <form action="<?php echo site_url('CusAdmin_controller/acknowledge') ?>" method="POST" id="form" class="form-horizontal">
            <div class="modal-body form" style="min-height: 300px;">

                <div class="row">
                  <div class="col-md-12">

                    <?php
                    if($notification->num_rows() > 0)
                    {
                    ?>

                    <ul class="nav nav-tabs">

                    <?php
                      $i=0;

                      foreach($notification->result() as $row)
                      {
                      ?>

                        <li class="<?= $i == 0 ? 'active' : '' ;?>" id="li_<?php echo $row->notification_guid;?>"><a id="id_<?php echo $row->notification_guid;?>" href="#tab_<?php echo $row->notification_guid;?>" data-toggle="tab" aria-expanded="<?= $i == 0 ? 'true' : 'false' ;?>"><?php echo $row->description;?></a></li>

                      <?php
                        $i++;
                      }
                      ?>
                      
                      </ul>
                    <?php
                    }
                    ?>
                    


                    <div class="tab-content">

                    <?php

                    if($notification->num_rows() > 0)
                    {
                      $ii=0;

                      foreach($notification->result() as $row)
                      {
                      ?>

                        <div class="tab-pane fade in <?= $ii == 0 ? 'active' : '' ;?>" id="tab_<?php echo $row->notification_guid;?>">

                          <br>

                          <?php
                          $exec_query = str_replace("@user_mapped_loc",$this->session->userdata("query_loc"),$row->query);
                          $header = $this->db->query($exec_query.' LIMIT 1');
                          // echo $this->db->last_query();

                          $array = $header->result();

                          $array = json_decode(json_encode($array));

                          if($header->num_rows() > 0)
                          {
                          ?>
                          

                          <table id="<?php echo $row->notification_guid;?>" class="table table-bordered table-hover">
                            <thead>
                              <tr>

                                <?php

                                

                                foreach($array[0] as $header => $value){
                                  echo "<th>".$header."</th>";
                                }

                                ?>

                              </tr>
                            </thead>
                          </table>
                        <?php  
                        }
                        else
                        {
                          echo "No Notification";
                        }
                        ?>
                        </div>

                      <?php
                        $ii++;
                      }

                      }
                      else
                      {
                        echo 'No Notification';
                      }
                      ?>

                    </div>
    

                  
                  </div>
                </div>

                  
            </div>
                  <div class="modal-footer">

                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>

                  </div>
                  </form>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="new-medium-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>

      <div class="modal-body table-responsive modal-control-size">
        
      </div>

      <div class="modal-footer">
        <button type="button" id="new-medium-modal-close" class="btn btn-success" data-dismiss="modal">OK</button>
        
      </div>
    </div>
  </div>
</div>

<div id="reg-medium-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title"></h4>
      </div>

      <div class="modal-body table-responsive modal-control-size">
        
      </div>

      <div class="modal-footer">
        <button type="button" id="new-medium-modal-close" class="btn btn-success" data-dismiss="modal">OK</button>
        
      </div>
    </div>
  </div>
</div>

<script>

$(document).ready(function(){
    $('.agree_btn').hide();
      $.ajax({
            url:"<?= site_url('Query_outstanding/get_outstanding');?>",
            method:"POST",
            dataType:'JSON',
            beforeSend:function(){
              // $('.btn').button('loading');
              $('.btn').prop('disabled',true);
            },
            success:function(data)
            {   
              
              if(data.result > '0')
              {
                <?php
                  if($check_announcement_acknowledgement->num_rows() > 0)
                  {
                  ?>
                    <?php foreach($check_announcement_acknowledgement->result() as $index => $row) 
                    {
                    ?>
                        var mandatory = "<?php echo $row->mandatory;?>";
                        if(mandatory == 1)
                        {
                          
                          $('#auto_modal<?php echo $index;?>').attr({
                            "data-backdrop":"static",
                            "data-keyboard":"false"
                          });
                          var modal = $('#auto_modal'+<?php echo $index;?>).modal();
                        }
                        else
                        {
                          //$('#auto_modal<?php echo $index;?>').removeAttr("data-backdrop");
                          //$('#auto_modal<?php echo $index;?>').removeAttr("data-keyboard");

                          var modal = $('#auto_modal'+<?php echo $index;?>).modal();
                        }//close else
                    <?php
                    }
                    ?> 
                    // query outstanding modal
                    $(document).on('click','.next_modal',function(){
                      var modal = $('#new-medium-modal').modal();
                      modal.find(".modal-title").html(data.type);               
                      modal.find(".modal-body").html(data.string);
                      
                      setTimeout(function(){
                        $('.blink').css({'background-color':'yellow','animation': 'blink 1s', 'animation-iteration-count': 'infinite'})
                      },300);

                      if(data.force_logout == 1)
                      {
                        modal.find(".modal-footer").html('<a href="<?php echo site_url('login_c/logout')?>" class="btn btn-default btn-flat">Close</a>'); 
                        modal.find(".modal-header").find('button').html('');
                      } 
                    });
                    
                    //notification modal 
                    $(document).on('click','#new-medium-modal-close',function(){
                      <?php
                        if(in_array('VMN',$_SESSION['module_code']))
                        {
                            echo "var modal = $('#notification_modal').modal();";
                        };
                        ?>
                        //notification_table();
                    });

                  <?php
                  }
                  else
                  {
                  ?>
                    var modal = $('#new-medium-modal').modal();
                    modal.find(".modal-title").html(data.type);               
                    modal.find(".modal-body").html(data.string);
                    
                    setTimeout(function(){
                      $('.blink').css({'background-color':'yellow','animation': 'blink 1s', 'animation-iteration-count': 'infinite'})
                    },300);

                    if(data.force_logout == 1)
                    {
                      modal.find(".modal-footer").html('<a href="<?php echo site_url('login_c/logout')?>" class="btn btn-default btn-flat">Close</a>'); 
                      modal.find(".modal-header").find('button').html('');
                    } 
                    $(document).on('click','#new-medium-modal-close',function(){
                      <?php
                        if(in_array('VMN',$_SESSION['module_code']))
                        {
                            echo "var modal = $('#notification_modal').modal();";
                        };
                        ?>
                        //notification_table();
                    });
                  <?php
                  }
                  ?>
                
              }
              else
              {
                  <?php
                  if($check_announcement_acknowledgement->num_rows() > 0)
                  {
                  ?>
                  <?php foreach($check_announcement_acknowledgement->result() as $index => $row) 
                  {
                  ?>
                  var mandatory = "<?php echo $row->mandatory;?>";
                          if(mandatory == 1)
                          {
                            
                            $('#auto_modal<?php echo $index;?>').attr({
                              "data-backdrop":"static",
                              "data-keyboard":"false"
                            });

                            var modal = $('#auto_modal'+<?php echo $index;?>).modal();

                          }
                          else
                          {
                            $('#auto_modal<?php echo $index;?>').removeAttr("data-backdrop");
                            $('#auto_modal<?php echo $index;?>').removeAttr("data-keyboard");
                            var modal = $('#auto_modal'+<?php echo $index;?>).modal();

                          }//close else
                  <?php
                  }
                  ?> 

                  <?php
                  }
                  else
                  {
                  ?>
                     <?php
                        if(in_array('VMN',$_SESSION['module_code']))
                        {
                            echo "var modal = $('#notification_modal').modal();";
                        };
                        ?>
                  <?php
                  }
                  ?>

              }

             
              $('.btn').prop('disabled',false);
            }//close succcess
      });//close ajax

  <?php
  if($check_announcement_acknowledgement->num_rows() > 0)
  {
  ?>
  <?php foreach($check_announcement_acknowledgement->result() as $index => $row) 
  {
    $modal_last_id = $index;
    break;
  }
  ?>
    $(document).on('hide.bs.modal','#auto_modal<?php echo $modal_last_id;?>',function(){
    // $(document).on('hide.bs.modal','#auto_modal',function(){

  <?php
  }//close $check_announcement_acknowledgement num row
  ?>
  
  <?php
  if($check_announcement_acknowledgement->num_rows() <= 0)
  {
  ?>
    $(document).on('hide.bs.modal','#new-medium-modal',function(){

  <?php
  }
  ?>

  // <?php
  // if(in_array('VMN',$_SESSION['module_code']))
  // {
  //     echo "var modal = $('#notification_modal').modal();";
  // };
  // ?>

    setTimeout(function(){

    <?php

    if($notification->num_rows() > 0)
    {

    foreach($notification->result() as $row)
    {
      $exec_query = str_replace("@user_mapped_loc",$this->session->userdata("query_loc"),$row->query);
      $header = $this->db->query($exec_query.' LIMIT 1');

      $array = $header->result();

      $array = json_decode(json_encode($array));

      if($header->num_rows() > 0)
      {
    ?>
      
      $('#<?php echo $row->notification_guid;?>').DataTable({
        "columnDefs": [ {"targets": 0 ,"visible": false}],
        "serverSide": true,
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [],
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true,
        "bFilter": true,
        "sScrollY": "30vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
        "ajax": {
            "url": "<?php echo site_url('Notification/notification_table');?>",
            "type": "POST",
            "data" : {query:"<?php echo addslashes($row->query);?>"},
            // success:function(data){

            //   var recordsTotal = data['recordsTotal']
            //   alert(recordsTotal);

            // },
        },
        columns: [
                  <?php
                  foreach($array[0] as $header => $value){
                    echo '{ data: "'.$header.'"},';
                  }
                  ?>
                 ],
        dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
        // "pagingType": "simple",
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
          $(nRow).attr('RefNo', aData['RefNo']);
          $(nRow).attr('status', aData['status']);
          $(nRow).attr('postdatetime', aData['postdatetime']);
        },
        "initComplete": function( settings, json ) {
        }
      });//close datatable

      $('div.dataTables_filter input').off('keyup.DT input.DT');

      var searchDelay = null;
         
      $(document).off('keyup','div.dataTables_filter input').on('keyup','div.dataTables_filter input', function(e) {
          var search = $(this).val();
          if (e.keyCode == 13) {
            var id = $(this).attr('aria-controls');
            $('#'+id).DataTable().search(search).draw();
          }//close keycode
      });//close keyup function

    <?php
    }//close foreach
      }//close if query header
    }//close if notification
    ?>

  },500);//clsoe settimeout

  <?php
  if($check_announcement_acknowledgement->num_rows() <= 0)
  {
  ?>
    });

  <?php
  }//$check_announcement_acknowledgement->num_rows()
  ?>

  <?php
  if($check_announcement_acknowledgement->num_rows() > 0)
  {
  ?>
    });//close check auto_modal hide  
  <?php
  }
  ?>

  $(document).on('shown.bs.tab','a[data-toggle="tab"]', function (e) {
        // var target = $(e.target).attr("href"); // activated tab
        // alert (target);
        setTimeout(function () {
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust()
        }, 100)
        // $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust()
  }); 

  $(document).on('click','#terms',function(){

    if($('#terms').is(':checked'))
    {
      $('.agree_btn').show();
    }
    else
    {
      $('.agree_btn').hide();
    }
  });//close submit_button
   
  // for registration upload term sheet
  registration_modal = function()
  {
    $.ajax({
      url:"<?php echo site_url('Dashboard/term_upload');?>",
      method:"POST",
      dataType:'JSON',
      //data:{reset_val:reset_val,reset_guid:reset_guid,created_at:created_at,time_at:time_at},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        $('.btn').button('reset');
        //json = JSON.parse(data.check_upload_term);
        //console.log(data.check_upload_term[0].acc_name);
        if(data.doc_reqeust != data.upload)
        {
          if(data.result > 0)
          {//> 0
            var dl_name = data.check_upload_term[0].supplier_name.replace(/\s/g,'_');
            
            var modal = $("#reg-medium-modal").modal();

            modal.find('.modal-title').html('Upload Term Sheet');

            methodd = '';

            methodd +='<div class="col-md-12">';

            methodd += '<input type="hidden" id="customer_guid" value="'+data.check_upload_term[0].customer_guid+'" readonly/>';

            methodd += '<input type="hidden" id="supplier_guid" value="'+data.check_upload_term[0].supplier_guid+'" readonly/>';

            methodd += '<input type="hidden" id="term_user_guid" value="'+data.check_upload_term[0].user_guid+'" readonly/>';

            methodd += '<div class="col-md-12" style="color:red"><label>Please upload VALID term sheet with authorised signature and company stamp after login account provided.</label></div>';

            methodd += '<div class="col-md-12"><label>**To avoid login interruptions please submit the Term Sheet within the submission due date.</label></div>';

            methodd += '<div class="col-md-4"><b>Retailer Name</b></div><div class="col-md-8">'+data.check_upload_term[0].acc_name+' </div><div class="clearfix"></div><br>';

            methodd += '<div class="col-md-4"><b>Supplier Name</b></div><div class="col-md-8">'+data.check_upload_term[0].supplier_name+' ('+data.check_upload_term[0].reg_no+')</div><div class="clearfix"></div><br>';

            methodd += '<div class="col-md-4"><b>Submission Due Date </b></div><div class="col-md-8"> '+data.check_upload_term[0].last_date+'</div><div class="clearfix"></div><br>'; //'+data.check_upload_term[0].start_date+' <b>UNTIL</b>

            methodd += '<div class="col-md-8"><b>File 1 (Normal Term Sheet)</b>';

            if((data.term_url != '') && (data.term_url != 'null')  && (data.term_url != null))
            {
              methodd += '<i id="file_1" title="View PDF" class="fa fa-file" url="'+data.term_url+'" style="cursor:pointer;float:right;">&nbsp;View PDF</i>';
            }

            if((data.term_url_rejected != '') && (data.term_url_rejected != 'null')  && (data.term_url_rejected != null))
            {
              methodd += '<i id="file_1" title="View PDF" class="fa fa-file" url="'+data.term_url_rejected+'" style="cursor:pointer;float:right;">&nbsp;Rejected PDF</i>';
            }
            
            methodd += '</div><div class="col-md-8"><input id="edit_upload_file" type="file" class="form-control" accept=".pdf"></div>';

            methodd += '<div class="col-md-4"><span id="edit_button_file_form"></span></div><div class="clearfix"></div><br>';

            if(data.doc_reqeust != '1')
            {
              methodd += '<div class="col-md-8"><b>File 2 (Special Term Sheet)</b>';

              if((data.special_term_url != '') && (data.special_term_url != 'null') && (data.special_term_url != null))
              {
                methodd += '<i id="file_2" title="View PDF" class="fa fa-file" url="'+data.special_term_url+'" style="cursor:pointer;float:right;">&nbsp;View PDF</i>';
              }   

              if((data.special_term_url_rejected != '') && (data.special_term_url_rejected != 'null') && (data.special_term_url_rejected != null))
              {
                methodd += '<i id="file_2" title="View PDF" class="fa fa-file" url="'+data.special_term_url_rejected+'" style="cursor:pointer;float:right;">&nbsp;Rejected PDF</i>';
              }   

              methodd += '</div><div class="col-md-8"><input id="edit_upload_file_2" type="file" class="form-control" accept=".pdf"></div>';

              methodd += '<div class="col-md-4"><span id="edit_button_file_form_2"></span></div><div class="clearfix"></div><br>';
            }
            methodd += '</div>';

            methodd_footer = '';

            methodd_footer += '<p class="full-width"><span class="pull-right">';

            if(data.check_upload_term[0].validate == '1' && data.check_upload_term[0].status == 'Pending')
            {
              setTimeout(function() { 
                alert("Please Wait B2B to approve before using B2B Portal.");
              }, 300);
              methodd_footer += '<a href="<?php echo site_url('login_c/customer')?>" class="btn btn-danger btn-flat">Close</a> </span>';
            }
            else if(data.check_upload_term[0].block == '1' )
            {
              setTimeout(function() { 
                alert("Your Submission Due Date is over. Please upload Term Sheet document before using B2B Portal.");
              }, 300);
              methodd_footer += '<a href="<?php echo site_url('login_c/customer')?>" class="btn btn-danger btn-flat">Close</a> </span>';
            }
            else
            {
              methodd_footer += '<input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span>';
            }

            //update for btn dl term sheet
            methodd_footer += '<span class="pull-left">';

            if(data.doc_reqeust != '1')
            {
              methodd_footer += '<a style="margin-left:5px;margin-top:5px;" type="button" class="btn btn-warning" href="<?php echo site_url('Invoice/view_report_term?reg_guid=');?>'+data.reg_guid_data+'&form_type=normal&supplier_name='+dl_name+'" target="_blank" > Download Term Sheet</a>'

              methodd_footer += '<a style="margin-left:5px;margin-top:5px;" type="button" class="btn btn-warning " href="<?php echo site_url('Invoice/view_term_special?reg_guid=');?>'+data.reg_guid_data+'&form_type=special&supplier_name='+dl_name+'" target="_blank" > Download Special Term Sheet</a>'
            }
            else
            {
              methodd_footer += '<a type="button" class="btn btn-warning" href="<?php echo site_url('Invoice/view_report_term?reg_guid=');?>'+data.reg_guid_data+'&form_type=normal&supplier_name='+dl_name+'" target="_blank" >Download Term Sheet</a> ';
            }

            methodd_footer += '</span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);
          }
        }
      }//close success
    });//close ajax
  }

  //registration_modal();

  $(document).on('change','#edit_upload_file',function(e){
    
    var edit_fileName = e.target.files[0].name;
    if(edit_fileName != '')
    { 
      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" edit_fileName="'+edit_fileName+'" style="margin-right:5px;" term_type="normal_term"> Upload</button><button type="button" class="btn btn-danger" id="edit_reset_input" >Reset</button>');
    }
    else
    { 
      //$('#button_file_form').remove();
      $('#edit_submit_button').remove();
      $('#edit_reset_input').remove();
    }
  });//close upload file

  $(document).on('click','#edit_reset_input',function(){

    $('#edit_upload_file').val('');

    var edit_file = $('#edit_upload_file')[0].files[0];

    if(edit_file === undefined)
    {
      $('#edit_submit_button').remove();
      $('#edit_reset_input').remove();
    }
    else
    { 
      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button" class="btn btn-primary" style="margin-top: 25px;" edit_fileName="'+edit_file+'" term_type="normal_term"> Upload</button><button type="button" class="btn btn-danger" id="edit_reset_input" >Reset</button>');
    }
  });//close reset_input

  $(document).on('click','#edit_submit_button',function(){

    var edit_file_name = $('#edit_submit_button').attr('edit_fileName');
    var term_user_guid = $('#term_user_guid').val();
    var supplier_guid = $('#supplier_guid').val();
    var customer_guid = $('#customer_guid').val();
    var term_type = $(this).attr('term_type');
    //alert(term_type); die;
    if((term_type == '') || (term_type == null) || (term_type == 'null'))
    {
      alert('Please Select Term Sheet');
      return;
    }

    confirmation_modal('Are you sure want to Upload?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#edit_upload_file')[0].files[0]);
      formData.append('file_name', edit_file_name);
      formData.append('term_user_guid', term_user_guid);
      formData.append('supplier_guid', supplier_guid);
      formData.append('customer_guid', customer_guid);
      formData.append('term_type', term_type);
      //console.log(formData); die;
      $.ajax({
          url:"<?= site_url('Dashboard/upload_term_docs');?>",
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
              setTimeout(function() { 
                location.reload();
                //registration_modal();
              }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });//close submit_button

  $(document).on('change','#edit_upload_file_2',function(e){
    var edit_fileName_2 = e.target.files[0].name;
    if(edit_fileName_2 != '')
    { 
      $('#edit_button_file_form_2').html('<button type="button" id="edit_submit_button_2" class="btn btn-primary" edit_fileName_2="'+edit_fileName_2+'" term_type_2="special_term" style="margin-right:5px;"> Upload</button><button type="button" class="btn btn-danger" id="edit_reset_input_2" >Reset</button>');
    }
    else
    { 
      $('#edit_submit_button_2').remove();
      $('#edit_reset_input_2').remove();
    }
  });//close upload file

  $(document).on('click','#edit_reset_input_2',function(){
    $('#edit_upload_file_2').val('');

    var edit_file_2 = $('#edit_upload_file_2')[0].files[0];

    if(edit_file_2 === undefined)
    {
      $('#edit_submit_button_2').remove();
      $('#edit_reset_input_2').remove();
    }
    else
    { 
      $('#edit_button_file_form').html('<button type="button" id="edit_submit_button_2" class="btn btn-primary" style="margin-top: 25px;" edit_fileName_2="'+edit_file_2+'" term_type_2="special_term"> Upload</button><button type="button" class="btn btn-danger" id="edit_reset_input_2" >Reset</button>');
    }
  });//close reset_input

  $(document).on('click','#edit_submit_button_2',function(){
    var edit_file_name = $('#edit_submit_button_2').attr('edit_fileName_2');
    var term_user_guid = $('#term_user_guid').val();
    var supplier_guid = $('#supplier_guid').val();
    var customer_guid = $('#customer_guid').val();
    var term_type = $(this).attr('term_type_2');
    //alert(term_user_guid); die;
    if((term_type == '') || (term_type == null) || (term_type == 'null'))
    {
      alert('Please Select Term Sheet');
      return;
    }

    confirmation_modal('Are you sure want to Upload?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      var formData = new FormData();
      formData.append('file', $('#edit_upload_file_2')[0].files[0]);
      formData.append('file_name', edit_file_name);
      formData.append('term_user_guid', term_user_guid);
      formData.append('supplier_guid', supplier_guid);
      formData.append('customer_guid', customer_guid);
      formData.append('term_type', term_type);
      //console.log(formData); die;
      $.ajax({
          url:"<?= site_url('Dashboard/upload_term_docs');?>",
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
              setTimeout(function() { 
                location.reload();
              }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });//close submit_button

  $(document).on('click','#file_1',function(e){
    var url_data = $(this).attr('url');
    //alert(url_data); die;
    if((url_data == null) || (url_data == 'null') || (url_data == ''))
    {
      alert('Still not yet upload Term Sheet');
      return;
    }

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Term Sheet');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<embed src="'+url_data+'" width="100%" height="500px" style="border: none;" id="pdf_view"/>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> </span> </p>';

    modal.find('.modal-body').html(methodd);
    modal.find('.modal-footer').html(methodd_footer);

  });//close upload file

  $(document).on('click','#file_2',function(e){
    var url_data = $(this).attr('url');
    //alert(url_data); die;
    if((url_data == null) || (url_data == 'null') || (url_data == ''))
    {
      alert('Still not yet upload Term Sheet');
      return;
    }

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Term Sheet');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<embed src="'+url_data+'" width="100%" height="500px" style="border: none;" id="pdf_view"/>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> </span> </p>';

    modal.find('.modal-body').html(methodd);
    modal.find('.modal-footer').html(methodd_footer);
  });//close upload file

});//close check document ready

notification_table = function()
{
    <?php
  if(in_array('VMN',$_SESSION['module_code']))
  {
      echo "var modal = $('#notification_modal').modal();";
  };
  ?>

    setTimeout(function(){

    <?php

    if($notification->num_rows() > 0)
    {

    foreach($notification->result() as $row)
    {
      $exec_query = str_replace("@user_mapped_loc",$this->session->userdata("query_loc"),$row->query);
      $header = $this->db->query($exec_query.' LIMIT 1');

      $array = $header->result();

      $array = json_decode(json_encode($array));

      if($header->num_rows() > 0)
      {
    ?>
      
      $('#<?php echo $row->notification_guid;?>').DataTable({
        "columnDefs": [ {"targets": 0 ,"visible": false}],
        "serverSide": true,
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [],
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true,
        "bFilter": true,
        "sScrollY": "30vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
        "ajax": {
            "url": "<?php echo site_url('Notification/notification_table');?>",
            "type": "POST",
            "data" : {query:"<?php echo addslashes($row->query);?>"},
            // success:function(data){

            //   var recordsTotal = data['recordsTotal']
            //   alert(recordsTotal);

            // },
        },
        columns: [
                  <?php
                  foreach($array[0] as $header => $value){
                    echo '{ data: "'.$header.'"},';
                  }
                  ?>
                 ],
        dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
        // "pagingType": "simple",
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
          $(nRow).attr('RefNo', aData['RefNo']);
          $(nRow).attr('status', aData['status']);
          $(nRow).attr('postdatetime', aData['postdatetime']);
        },
        "initComplete": function( settings, json ) {
        }
      });//close datatable

      $('div.dataTables_filter input').off('keyup.DT input.DT');

      var searchDelay = null;
         
      $(document).off('keyup','div.dataTables_filter input').on('keyup','div.dataTables_filter input', function(e) {
          var search = $(this).val();
          if (e.keyCode == 13) {
            var id = $(this).attr('aria-controls');
            $('#'+id).DataTable().search(search).draw();
          }//close keycode
      });//close keyup function

    <?php
    }//close foreach
      }//close if query header
    }//close if notification
    ?>

  },500);//clsoe settimeout
}

</script>