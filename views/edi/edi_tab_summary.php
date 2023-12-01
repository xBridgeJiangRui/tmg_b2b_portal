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

.li_hover:hover{
  font-weight: bold;
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
            <li class="li_hover"><a href="<?php echo $tab_3;?>" style="color:black">Method & Format</a></li>
            <li class="active"><a class="css_tab" href="#tab_4" >Summary</a></li>
            <?php
          }
          else
          {
            ?>
            <li class="disabled"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Information</a></li>
            <li class="disabled"><a href="#tab_2" data-toggle="tab" aria-expanded="false" >Column Setting</a></li>
            <li class="disabled"><a href="#tab_3" data-toggle="tab" aria-expanded="false">Method & Format</a></li>
            <li class="active"><a class="css_tab" href="#tab_4" data-toggle="tab" aria-expanded="true">Summary</a></li>
            <?php
          }
          ?>
          <!-- <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li> -->
        </ul>
        
        <div class="tab-content" >
          <div class="tab-pane active" id="tab_4">
          <div class="box-body">
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
            <!-- <div class="col-md-12"> -->
              <div class="box box-primary">
              <table class="table table-bordered table-striped dataTable" id="tab_one_table" style="width: 50%;">
                <thead style="white-space: nowrap;">
                <tr>
                  <th>Status</th>
                  <th>Retailer Name</th> 
                  <th>Supplier Name</th>
                  <th>Supplier Code</th>
                  <th>Doc Type</th>
                </tr>
                </thead>
                <tbody> 
                </tbody>

              </table>

              <br>

              <table class="table table-bordered table-striped dataTable" id="tab_two_table" style="width: 50%;">
                <thead style="white-space: nowrap;">
                <tr>
                  <th>File Name</th> 
                  <th>Extra File Name</th> 
                  <th>Export Split Batch</th>
                  <th>Export Method</th> 
                  <th>File Format</th> 
                  <th>Show Header</th> 
                  <th>Date Format</th> 
                  <th>Round Decimal</th> 
                </tr>
                </thead>
                <tbody> 
                </tbody>

              </table>
              <!-- </div> -->
              <br>

              <table class="table table-bordered table-striped dataTable" id="tab_three_table" style="width: 50%;">
                <thead style="white-space: nowrap;">
                <tr>
                  <th>B2B Send File</th>
                  <th>Host</th>
                  <th>Username</th>
                  <th>Password</th>
                  <th>Port</th>
                  <th>Path</th>
                  <th>Local File Path</th> 
                </tr>
                </thead>
                <tbody> 
                </tbody>

              </table>
              </div>
              <!-- </div> -->

            <!-- <div class="col-md-6"> -->
            <h4 class="box-title">Selected Main Field
            <div class="pull-right">
            </div>
            </h4>
            <div class="box box-warning">
            <table class="table table-bordered table-striped dataTable" id="main_table" style="width: 50%;">
                <thead style="white-space: nowrap;">
                  <tr>
                    <th>Sequence</th> 
                    <th>B2B Field</th>
                    <th>Default Value</th>
                    <th>Supplier Field</th>
                    <th>Cross Reference</th>
                    <th>Different B2B Field</th>
                    <th>Table Position</th>
                  </tr>
                </thead>
                <tbody> 
                </tbody>

              </table>
              </div>
              <!-- </div> -->

              <!-- <div class="col-md-6"> -->
              <!-- <span id="append_child_tb"></span> -->
              <h4 class="box-title">Selected Child Field
              <div class="pull-right"> 
              </div>
              </h4> 

              <div class="box box-warning"> 
                <table class="table table-bordered table-striped dataTable" id="child_table" style="width: 50%;"> 
                  <thead style="white-space: nowrap;"> 
                    <tr> 
                      <th>Sequence</th> 
                      <th>B2B Field</th>
                      <th>Default Value</th>
                      <th>Supplier Field</th>
                      <th>Cross Reference</th>
                      <th>Different B2B Field</th>
                      <th>Table Position</th>
                    </tr>
                  </thead> 
                  <tbody> 
                  </tbody> 
                </table> 
              </div>
             
            <!-- </div> -->
          </div> <!-- box body-->
          </div>
          <br/>
          <div class="box-footer">

            <button id="back_edi" type="button" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> Back</button>
            <button id="next_edi" type="button" class="btn btn-primary"  style="float:right;"><i class="fa fa-arrow-circle-right"></i> Done</button>
       
          </div>
          
          </div>
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- nav-tabs-custom -->
    </div>
  </div>

</div>

<script>
$(document).ready(function () {    
  var tab_guid = '<?php echo $link ?>';

  $('#tab_one_table').DataTable({
    "columnDefs": [],
    "serverSide": true, 
    'processing'  : false,
    'paging'      : true,
    'lengthChange': false,
    //'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : false,
    'ordering'    : false, 
    //'order'       : [  [6 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : true,
    //"sScrollY": "50vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Edi_setup/tab_one_tb');?>",
        "type": "POST",
        data : {tab_guid:tab_guid},
    },
    columns: [
            { "data" : "edi_status"},
            { "data" : "acc_name" },
            { "data" : "supplier_name" },
            { "data" : "supplier_code" },
            { "data" : "doc_type" },
          ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rt',
    // buttons: [
    //   { extend: 'excelHtml5',
    //     exportOptions: {columns: [1,2,3,4,5,6]}},

    //   { extend: 'csvHtml5',  
    //     exportOptions: {columns: [1,2,3,4,5,6]}},
    //     ],
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('final_amount', aData['final_amount']);
      if(aData['edi_status'] == 'Completed' )
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#69ff7f","color":"black"});
      }
      else
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#54a8f7","color":"black"});
      }
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $('#tab_two_table').DataTable({
    "columnDefs": [
    // {"targets": 0 ,"orderable": false},
    // {"targets": 0 ,"visible": false},
    ],
    "serverSide": true, 
    'processing'  : false,
    'paging'      : true,
    'lengthChange': false,
    //'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : false,
    'ordering'    : false, 
    //'order'       : [  [6 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : true,
    //"sScrollY": "50vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Edi_setup/final_tab_tb');?>",
        "type": "POST",
        data : {tab_guid:tab_guid},
    },
    columns: [
            { "data" : "export_file_name_format" },
            { "data" : "export_add_extra_name" },
            { "data" : "split_batch" ,render:function( data, type, row ){

              var element = '';

              if(data == '1')
              {
                element = 'Yes';
              }
              else
              {
                element = '';
              }

              return element;
            }},
            { "data" : "export_method" },
            { "data" : "export_format" },
            { "data" : "export_header" ,render:function( data, type, row ){

              var element = '';

              if(data == '1')
              {
                element = 'Yes';
              }
              else
              {
                element = '';
              }

              return element;
            }},
            { "data" : "export_date_format" },
            { "data" : "export_round_decimal" },
          ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rt',
    // buttons: [
    //   { extend: 'excelHtml5',
    //     exportOptions: {columns: [1,2,3,4,5,6]}},

    //   { extend: 'csvHtml5',  
    //     exportOptions: {columns: [1,2,3,4,5,6]}},
    //     ],
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('final_amount', aData['final_amount']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $('#tab_three_table').DataTable({
    "columnDefs": [
    <?php
    if(!in_array('IAVA',$this->session->userdata('module_code')))
    {
    ?>
    {"targets": 3 ,"visible": false},
    <?php
    }
    ?>
    // {"targets": 0 ,"orderable": false},
    // {"targets": 0 ,"visible": false},
    ],
    "serverSide": true, 
    'processing'  : false,
    'paging'      : true,
    'lengthChange': false,
    //'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : false,
    'ordering'    : false, 
    //'order'       : [  [6 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : true,
    //"sScrollY": "50vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Edi_setup/final_tab_tb');?>",
        "type": "POST",
        data : {tab_guid:tab_guid},
    },
    columns: [
          { "data" : "issend" ,render:function( data, type, row ){

            var element = '';

            if(data == '1')
            {
              element = 'Yes';
            }
            else
            {
              element = '';
            }

            return element;
            }},
            { "data" : "sftp_host" },
            { "data" : "sftp_username" },
            { "data" : "sftp_password" ,render:function( data, type, row ){

            var element = '';

            if((data != '') && (data != 'null') && (data != null))
            {
              element = '<input type="password" class="form-control input-sm" style="width:80%;" id="sftp_password" name="sftp_password" value="'+data+'" autocomplete = "off" readonly/>';

              <?php
              if(in_array('IAVA',$this->session->userdata('module_code')))
              {
              ?>
                element += '<button id="pass_show" type="button" class="btn-xs btn-info" style="margin-left:2px;"><i class="fa fa-eye"></i></button>';
              <?php
              }
              ?>

            }
            else
            {
              element = '';
            }

            return element;
            }},
            { "data" : "sftp_port" },
            { "data" : "sftp_remote_path" },
            { "data" : "local_file_path" },
          ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rt',
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('final_amount', aData['final_amount']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $('#main_table').DataTable({
    "columnDefs": [
    //{"targets": 0 ,"orderable": false},
    ],
    "serverSide": true, 
    'processing'  : false,
    'paging'      : true,
    'lengthChange': false,
    //'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : false,
    'ordering'    : false, 
    //'order'       : [  [0 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : true,
    "sScrollY": "50vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Edi_setup/selected_main_tb');?>",
        "type": "POST",
        data : {tab_guid:tab_guid},
    },
    columns: [
            { "data" : "seq" },
            { "data" : "b2b_field" },
            { "data" : "default_value" },
            { "data" : "supplier_field" },
            { "data" : "cross_ref" ,render:function( data, type, row ){

              var element = '';

              if(data == '1')
              {
                element = 'Yes';
              }
              else
              {
                element = '';
              }

              return element;
            }},
            { "data" : "different_b2b_field" },
            { "data" : "position" ,render:function( data, type, row ){

              var element = '';

              if(data == '1')
              {
                element = 'Main Table';
              }
              else
              {
                element = 'Child Table';
              }

              return element;
            }},
          ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rt',
    // buttons: [
    //   { extend: 'excelHtml5',
    //     exportOptions: {columns: [1,2,3,4,5,6]}},

    //   { extend: 'csvHtml5',  
    //     exportOptions: {columns: [1,2,3,4,5,6]}},
    //     ],
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('final_amount', aData['final_amount']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $('#child_table').DataTable({
    "columnDefs": [
    //{"targets": 0 ,"orderable": false},
    ],
    "serverSide": true, 
    'processing'  : false,
    'paging'      : true,
    'lengthChange': false,
    //'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : false,
    'ordering'    : false, 
    //'order'       : [  [0 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : true,
    "sScrollY": "50vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Edi_setup/selected_child_tb');?>",
        "type": "POST",
        data : {tab_guid:tab_guid},
    },
    columns: [
            { "data" : "seq" },
            { "data" : "b2b_field" },
            { "data" : "default_value" },
            { "data" : "supplier_field" },
            { "data" : "cross_ref" ,render:function( data, type, row ){

              var element = '';

              if(data == '1')
              {
                element = 'Yes';
              }
              else
              {
                element = '';
              }

              return element;
            }},
            { "data" : "different_b2b_field" },
            { "data" : "position" ,render:function( data, type, row ){

              var element = '';

              if(data == '1')
              {
                element = 'Main Table';
              }
              else
              {
                element = 'Child Table';
              }

              return element;
            }},
          ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rt',
    // buttons: [
    //   { extend: 'excelHtml5',
    //     exportOptions: {columns: [1,2,3,4,5,6]}},

    //   { extend: 'csvHtml5',  
    //     exportOptions: {columns: [1,2,3,4,5,6]}},
    //     ],
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('final_amount', aData['final_amount']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable


  $(document).on('click','#next_edi',function(){
    confirmation_modal('Are you sure want to complete setup?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Edi_setup/done_setup_edi') ?>",
        method:"POST",
        data:{tab_guid:tab_guid},
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
            window.location = "<?= site_url('Edi_setup');?>";
          }
         
        }//close success
      });//close ajax 
    });//close document yes click
  });//close redirect

  $(document).on('click','#pass_show',function(){
    var x = document.getElementById("sftp_password");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  });

  $(document).on('click','#back_edi',function(){
    window.location = "<?= site_url('Edi_setup/tab_three?link=');?>"+tab_guid;
  });//close redirect

});
</script>

