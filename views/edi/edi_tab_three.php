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

.disabled{
    pointer-events:none;
    opacity:0.7;
}

.css_tab{
  background-color: #abe4f5 !important;
  font-weight: bold;
}

.li_hover:hover{
  font-weight: bold;
}

.edi_header {
    margin: 0px 0 10px 0;
    font-size: 22px;
    border-bottom: 1px solid #eee;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: black;
}

.nav-tabs-custom > .nav-tabs{
    white-space: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
    width: 100%;
    display: flex;
}

.nav-tabs-custom > .nav-tabs::-webkit-scrollbar {
  width: 10px;
  height: 9px;
  background-color: #F5F5F5;           /* width of the entire scrollbar */
}

.nav-tabs-custom > .nav-tabs::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
  border-radius: 10px;
  background-color: #F5F5F5;       /* color of the tracking area */
}

.nav-tabs-custom > .nav-tabs::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
  background-color: #B7BABF; /* color of the scrolling */
}

.input-group-addon {
  margin-top: -27px;
  margin-right: 15px;
  float:right;
  background-color: transparent !important; 
  border: 0px solid;
}

</style>
<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <!-- Custom Tabs -->
      <h2 class="edi_header">Setup EDI Subscriber</h2>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <?php if($status == '1')
          {
            ?>
            <li class="li_hover"><a href="<?php echo $tab_1;?>" style="color:black">Information</a></li>
            <li class="li_hover"><a href="<?php echo $tab_2;?>" style="color:black">Column Setting</a></li>
            <li class="active"><a class="css_tab" href="#tab_3" >Method & Format</a></li>
            <li class="li_hover"><a href="<?php echo $tab_summary;?>" style="color:black">Summary</a></li>
            <?php
          }
          else
          {
            ?>
            <li class="disabled"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Information</a></li>
            <li class="disabled"><a href="#tab_2" data-toggle="tab" aria-expanded="false" >Column Setting</a></li>
            <li class="active"><a class="css_tab" href="#tab_3" data-toggle="tab" aria-expanded="true">Method & Format</a></li>
            <li class="disabled"><a href="#tab_4" data-toggle="tab" aria-expanded="false">Summary</a></li>
            <?php
          }
          ?>
          <!-- <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li> -->
        </ul>
        <div class="tab-content" >
          <div class="tab-pane active" id="tab_3">
            <div class="box-body">
             <div class="row">
              <div class="col-md-12">
                <!--Outright -->
                <div class="box box-primary">
                  <div class="box-header">
                    <!-- <h3 class="box-title">Outright Template Information</h3> -->
                    <div class="box-tools pull-right">
                      <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> -->
                      </button>
                    </div>
                  </div>
                  <div class="box-body">
                    <div class="loader" style="text-align: center;" ><img src="<?php echo base_url('assets/loading2.gif') ?>"></div>
                    <div class="tab-content tab_body"></div>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
              </div>
            </div>
          </div>
          <br/>
          <div class="box-footer">

            <button id="back_edi" type="button" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> Back</button>
            <button id="next_edi" type="button" class="btn btn-primary"  style="float:right;"><i class="fa fa-arrow-circle-right"></i> Next</button>

            <?php
            if($status == '1')
            {
              ?>
              <button id="skip_edi" type="button" class="btn btn-warning" style="float:right;margin-right:5px;"> Skip </button>
              <?php
            }
            ?>

            
       
          </div>
          
          </div>
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- nav-tabs-custom -->
    </div>
  </div>

</div>
</div>

<script>
$(document).ready(function () {    
  var tab_guid = '<?php echo $link ?>';
  var doc_type = '<?php echo $doc_type ?>';

  if(doc_type == 'PO')
  {
    $.ajax({
      url:"<?php echo site_url('Edi_setup/edi_method_info');?>",
      method:"POST",
      data:{tab_guid:tab_guid},
      beforeSend:function(){
        $(".loader").show();
        $(".tab_body").empty();
      },
      success:function(data)
      {
        json = JSON.parse(data); 

        if(json.issend == 1)
        {
          var issend_checked = 'checked';
        }
        else
        {
          var issend_checked = '';
        }

        if(json.export_header == 1)
        {
          var checked = 'checked';
        }
        else
        {
          var checked = '';
        }

        if(json.sftp_host == null)
        {
          json.sftp_host = '';
        }

        if(json.sftp_username == null) 
        {
          json.sftp_username = '';
        }

        if(json.sftp_password == null) 
        {
          json.sftp_password = '';
        }

        if(json.sftp_port == null) 
        {
          json.sftp_port = '';
        }

        if(json.sftp_remote_path == null) 
        {
          json.sftp_remote_path = '';
        }

        if(json.export_format == null) 
        {
          json.export_format = '';
        }

        if(json.export_method == null) 
        {
          json.export_method = '';
        }

        if(json.export_round_decimal == null) 
        {
          json.export_round_decimal = '';
        }
        
        if(json.export_date_format == null)
        {
          json.export_date_format = '-';
        }

        if(json.export_file_name_format == null)
        {
          json.export_file_name_format = '';
        }

        if(json.export_add_extra_name == null)
        {
          json.export_add_extra_name = '';
        }

        if(json.local_file_path == null)
        {
          json.local_file_path = '';
        }

        methodd = '';

        methodd += '<div class="row">';

        methodd += '<div class="col-md-6" >';

        methodd += '<div class="form-group"> <div class="checkbox"> <label style="font-weight:bold;"> <input type="checkbox" id="export_header" name="export_header" '+checked+'> Reqeust Export Header </label> </div> </div>';

        methodd += '<div class="form-group"><label>Export Split Batch</label><select class="form-control" name="export_split_batch" id="export_split_batch"> <option value=""> -SELECT SPLIT BATCH- </option> <option value="1"> YES </option> <option value="0" > NO </option> </select></div>';

        methodd += '<div class="form-group"><label>Export Format</label><select class="form-control select2" name="export_format" id="export_format"> <option value=""> -SELECT EXPORT FORMAT- </option> <option value="csv"> CSV </option> <option value="txt" > TXT </option> <option value="json" disabled > JSON </option> <option value="txt" disabled> XML </option> </select></div>';

        methodd += '<div class="form-group"><label>Export Round Decimal</label><input type="number" class="form-control input-sm" id="export_round_decimal" name="export_round_decimal" value="'+json.export_round_decimal+'" min="0" max="4" /></div>';

        methodd += '<div class="form-group"><label>Export Date Separator</label><input type="text" class="form-control input-sm" id="export_date_format" name="export_date_format" value="'+json.export_date_format+'" autocomplete = "off" placeholder="Default Date Setting (2022-01-31)."/></div>';

        methodd += '<div class="form-group"><label>Export File Name Format</label><input type="text" class="form-control input-sm show_example" id="export_file_name_format" name="export_file_name_format" value="'+json.export_file_name_format+'" autocomplete = "off" placeholder="File Name Ex PANDAHYPERMARKET_0000001.csv"/></div>';

        methodd += '<div class="form-group"><label>Export Add Extra Name</label><select class="form-control show_example" name="export_add_extra_name" id="export_add_extra_name"> <option value=""> -SELECT ADD EXPORT NAME- </option> <option value="date" > Date </option> <option value="datetime"> Date & Time </option> <option value=""> No Add</option> </select></div>';

        methodd += '<b>File Name Output :</b> <span id="filename_html" style="background-color:yellow;font-weight:bold;"></span>';

        methodd +='</div>';

        methodd += '<div class="col-md-6" >';

        methodd += '<div class="form-group"> <div class="checkbox"> <label style="font-weight:bold;"> <input type="checkbox" id="issend" name="issend" '+issend_checked+'> B2B Send File (Push)</label> </div> </div> ';

        if(json.issend == '1')
        {
          methodd += '<span id="html_b2b_send"><div class="form-group"><label>Export Method</label><select class="form-control " name="export_method" id="export_method"> <option value=""> -SELECT EXPORT METHOD- </option> <option value="SFTP"> SFTP </option> <option value="FTP" > FTP </option> <option value="Azure"> Azure Server </option> </select></div>';
          
          if(json.export_method == 'Azure')
          {
            methodd += '<div class="form-group"><label>Local File Path</label><input type="text" class="form-control input-sm" id="local_file_path" name="local_file_path" value="'+json.local_file_path+'" autocomplete = "off"/></div></span>';
          }
          else
          {
            methodd += '<div class="form-group"> <label>Host</label> <input type="text" class="form-control input-sm" id="sftp_host" name="sftp_host" value="'+json.sftp_host+'" autocomplete = "off"/> </div>';

            methodd += '<div class="form-group"><label>Username</label><input type="text" class="form-control input-sm" id="sftp_username" name="sftp_username" value="'+json.sftp_username+'" autocomplete="on"/></div>';

            methodd += '<div class="form-group"><label>Password</label><input type="text" class="form-control input-sm" id="sftp_password" name="sftp_password" value="'+json.sftp_password+'" autocomplete = "off"/><span class="input-group-addon" id="view_pass"><i class="fa fa-eye"></i></span></div>';

            methodd += '<div class="form-group"><label>Port</label><input type="text" class="form-control input-sm" id="sftp_port" name="sftp_port" value="'+json.sftp_port+'" autocomplete = "off"/></div>';

            methodd += '<div class="form-group"><label>Path</label><input type="text" class="form-control input-sm" id="sftp_remote_path" name="sftp_remote_path" value="'+json.sftp_remote_path+'" autocomplete = "off"/></div></span>';
          }
        }
        else
        {
          methodd += '<span id="html_b2b_send"></span>';
        }
        
        methodd +='</div>';

        methodd += '</div>';
        
        methodd += '<div class="clearfix"></div><br>';

        //methodd_footer ='';

        //methodd_footer += '<div class="col-md-2 pull-left" ><button class="btn btn-block btn-success" id="button_confirm" tab_guid = "'+tab_guid+'" >Submit</button></div>';

        $('.tab_body').html(methodd);
        //$('.tab_footer').html(methodd_footer);
        $('.select2').select2();
        setTimeout(function(){
          // datepicker
          // $('.datepicker').datepicker({
          //     forceParse: false,
          //     autoclose: true,
          //     format: 'yyyy-mm-dd'
          // });
          //$('#add_start_date').datepicker("setDate", json.start_date);
          //$('#add_outright_exp').datepicker("setDate", json.outright_expired );
          $('#export_date_format').val(json.export_date_format);
          $('#export_round_decimal').val(json.export_round_decimal);
          $('#export_method').val(json.export_method);
          $('#sftp_remote_path').val(json.sftp_remote_path);
          $('#sftp_port').val(json.sftp_port);
          $('#sftp_password').val(json.sftp_password);
          $('#sftp_username').val(json.sftp_username);
          $('#sftp_host').val(json.sftp_host);
          $('#local_file_path').val(json.local_file_path);
          $('#export_add_extra_name').val(json.export_add_extra_name);
          $('#export_split_batch').val(json.split_batch);
          if(json.export_format != "")
          {
            $('#export_format').val(json.export_format).trigger('change');
          }

          // if(json.export_file_name_format != "")
          // {
          //   $('#export_file_name_format').val(json.export_file_name_format.split(",")).trigger('change');
          // }
          // $("#export_file_name_format").on("select2:select", function (evt) {
          //   var element = evt.params.data.element;
          //   var $element = $(element);

          //   $element.detach();
          //   $(this).append($element);
          //   $(this).trigger("change");
          // });

          $(document).on('click','#issend',function(){

            if($(this).is(':checked'))
            {
              $('#html_b2b_send').html('<div class="form-group"><label>Export Method</label><select class="form-control " name="export_method" id="export_method"> <option value=""> -SELECT EXPORT METHOD- </option> <option value="SFTP"> SFTP </option> <option value="FTP" > FTP </option> <option value="Azure"> Azure Server </option> </select></div> <div class="form-group"> <label>Host</label> <input type="text" class="form-control input-sm" id="sftp_host" name="sftp_host" autocomplete = "off"/> </div> <div class="form-group"><label>Username</label><input type="text" class="form-control input-sm" id="sftp_username" name="sftp_username" autocomplete="on"/></div> <div class="form-group"><label>Password</label><input type="text" class="form-control input-sm" id="sftp_password" name="sftp_password" autocomplete = "off"/><span class="input-group-addon" id="view_pass"><i class="fa fa-eye"></i></span></div> <div class="form-group"><label>Port</label><input type="text" class="form-control input-sm" id="sftp_port" name="sftp_port" value="'+json.sftp_port+'" autocomplete = "off"/></div> <div class="form-group"><label>Path</label><input type="text" class="form-control input-sm" id="sftp_remote_path" name="sftp_remote_path" value="'+json.sftp_remote_path+'" autocomplete = "off"/></div>');

              $('#export_method').val(json.export_method);
              $('#sftp_remote_path').val(json.sftp_remote_path);
              $('#sftp_port').val(json.sftp_port);
              $('#sftp_password').val(json.sftp_password);
              $('#sftp_username').val(json.sftp_username);
              $('#sftp_host').val(json.sftp_host);
            }
            else
            {
              $('#html_b2b_send').html('');
            }
          });

          $(document).on('click','.show_example',function(){

            var name1 = $('#export_file_name_format').val();
            var name2 = $('#export_add_extra_name').val();

            if(name2 == 'date')
            {
              var extra = '_'+<?php echo date("Ymd"); ?>;
            }
            else if(name2 == 'datetime')
            {
              var extra = '_'+<?php echo date("YmdHis"); ?>;
            }
            else
            {
              var extra = '';
            }

            var result_ex = name1+extra+'_0000001';

            $('#filename_html').html(result_ex);
            
          });

          $(document).on('change','#export_method',function(){
            
            var export_value = $(this).val();

            if(export_value == 'Azure')
            {
              $('#html_b2b_send').html('');

              $('#html_b2b_send').html('<div class="form-group"><label>Export Method</label><select class="form-control " name="export_method" id="export_method"> <option value=""> -SELECT EXPORT METHOD- </option> <option value="SFTP"> SFTP </option> <option value="FTP" > FTP </option> <option value="Azure" selected> Azure Server </option> </select></div><div class="form-group"><label>Local File Path</label><input type="text" class="form-control input-sm" id="local_file_path" name="local_file_path" value="'+json.local_file_path+'" autocomplete = "off"/></div></span>');

              $('#local_file_path').val(json.local_file_path);
            }
            else
            {
              $('#html_b2b_send').html('');

              $('#html_b2b_send').html('<div class="form-group"><label>Export Method</label><select class="form-control " name="export_method" id="export_method"> <option value=""> -SELECT EXPORT METHOD- </option> <option value="SFTP"> SFTP </option> <option value="FTP" > FTP </option> <option value="Azure"> Azure Server </option> </select></div> <div class="form-group"> <label>Host</label> <input type="text" class="form-control input-sm" id="sftp_host" name="sftp_host" autocomplete = "off"/> </div> <div class="form-group"><label>Username</label><input type="text" class="form-control input-sm" id="sftp_username" name="sftp_username" autocomplete="on"/></div> <div class="form-group"><label>Password</label><input type="text" class="form-control input-sm" id="sftp_password" name="sftp_password" autocomplete = "off"/><span class="input-group-addon" id="view_pass"><i class="fa fa-eye"></i></span></div> <div class="form-group"><label>Port</label><input type="text" class="form-control input-sm" id="sftp_port" name="sftp_port" value="'+json.sftp_port+'" autocomplete = "off"/></div> <div class="form-group"><label>Path</label><input type="text" class="form-control input-sm" id="sftp_remote_path" name="sftp_remote_path" value="'+json.sftp_remote_path+'" autocomplete = "off"/></div>');

              $('#export_method').val(export_value);
              $('#sftp_remote_path').val(json.sftp_remote_path);
              $('#sftp_port').val(json.sftp_port);
              $('#sftp_password').val(json.sftp_password);
              $('#sftp_username').val(json.sftp_username);
              $('#sftp_host').val(json.sftp_host);
            }
            
          });

        },300);
      },//close success
      complete: function() {
        $(".loader").hide();
      }
    });//close ajax
  }

  $(document).on('click','#view_pass',function(){
    var x = document.getElementById("sftp_password");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  });

  $(document).on('click','#next_edi',function(){

    var export_file_name_format = $('#export_file_name_format').val();
    var export_date_format = $('#export_date_format').val();
    var export_round_decimal = $('#export_round_decimal').val();
    var export_method = $('#export_method').val();
    var export_format = $('#export_format').val();
    var export_split_batch = $('#export_split_batch').val();
    var export_add_extra_name = $('#export_add_extra_name').val();
    var sftp_remote_path = $('#sftp_remote_path').val();
    var sftp_port = $('#sftp_port').val();
    var sftp_password = $('#sftp_password').val();
    var sftp_username = $('#sftp_username').val();
    var sftp_host = $('#sftp_host').val();
    var issend = '0';
    var export_header = '0';
    var local_file_path = $('#local_file_path').val();

    if($('#issend').is(":checked"))
    {
      if(export_method == 'Azure')
      {
        if((local_file_path == '') || (local_file_path == null) || (local_file_path == 'null'))
        {
          alert('Please Insert Local File Path.');
          return;
        }
      }
      else
      {
        if((sftp_host == '') || (sftp_host == null) || (sftp_host == 'null'))
        {
          alert('Please Insert SFTP HOST.');
          return;
        }

        if((sftp_username == '') || (sftp_username == null) || (sftp_username == 'null'))
        {
          alert('Please Insert Username.');
          return;
        }

        if((sftp_password == '') || (sftp_password == null) || (sftp_password == 'null'))
        {
          alert('Please Insert Password.');
          return;
        }

        if((sftp_port == '') || (sftp_port == null) || (sftp_port == 'null'))
        {
          alert('Please Insert Port.');
          return;
        }

        if((sftp_remote_path == '') || (sftp_remote_path == null) || (sftp_remote_path == 'null'))
        {
          alert('Please Insert Remote Path.');
          return;
        }

        if((export_method == '') || (export_method == null) || (export_method == 'null'))
        {
          alert('Please Select Export Method.');
          return;
        }
      }
    }

    if((export_format == '') || (export_format == null) || (export_format == 'null'))
    {
      alert('Please Select Export Format.');
      return;
    }

    if((export_round_decimal == '') || (export_round_decimal == null) || (export_round_decimal == 'null'))
    {
      alert('Please Insert Export Round Decimal.');
      return;
    }

    if((export_date_format == '') || (export_date_format == null) || (export_date_format == 'null'))
    {
      alert('Please Insert Export Date Format.');
      return;
    }

    if((export_file_name_format == '') || (export_file_name_format == null) || (export_file_name_format == 'null'))
    {
      alert('Please Select Export File name Format.');
      return;
    }

    if((export_split_batch == '') || (export_split_batch == null) || (export_split_batch == 'null'))
    {
      alert('Please Select Export Split Batch.');
      return;
    }

    if($('#issend').is(":checked"))
    {
      issend = '1';
    }

    if($('#export_header').is(":checked"))
    {
      export_header = '1';
    }

    confirmation_modal('Are you sure want to Update and Proceed.');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Edi_setup/update_method_info') ?>",
        method:"POST",
        data:{export_file_name_format:export_file_name_format,export_date_format:export_date_format,export_round_decimal:export_round_decimal,export_method:export_method,export_format:export_format,sftp_remote_path:sftp_remote_path,sftp_port:sftp_port,sftp_password:sftp_password,sftp_username:sftp_username,sftp_host:sftp_host,issend:issend,export_header:export_header,tab_guid:tab_guid,export_add_extra_name:export_add_extra_name,local_file_path:local_file_path,export_split_batch:export_split_batch},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if(json.para1 == 1)
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            alert(json.msg);
          }
          else
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            $("#medium-modal").modal('hide');
            alert(json.msg);
            setTimeout(function() {
              window.location = "<?= site_url('Edi_setup/final_summary?link=');?>"+tab_guid;
            //$('#main_table').DataTable().ajax.reload(null, false);
            }, 300); 
          }
         
        }//close success
      });//close ajax 
    });//close document yes click
  });//close redirect

  $(document).on('click','#back_edi',function(){
    window.location = "<?= site_url('Edi_setup/tab_two?link=');?>"+tab_guid;
  });//close redirect

  $(document).on('click','#skip_edi',function(){
    window.location = "<?= site_url('Edi_setup/final_summary?link=');?>"+tab_guid;
  });//close redirect

  });
</script>

