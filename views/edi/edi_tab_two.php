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

.li_hover:hover{
  font-weight: bold;
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
      <!-- Custom Tabs -->
      <h2 class="edi_header">Setup EDI Subscriber</h2>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <?php if($status == '1')
          {
            ?>
            <li class="li_hover"><a href="<?php echo $tab_1;?>" style="color:black">Information</a></li>
            <li class="active"><a class="css_tab" href="#tab_2">Column Setting</a></li>
            <li class="li_hover"><a href="<?php echo $tab_3;?>" style="color:black">Method & Format</a></li>
            <li class="li_hover"><a href="<?php echo $tab_summary;?>" style="color:black">Summary</a></li>
            <?php
          }
          else
          {
            ?>
            <li class="disabled"><a href="#tab_1" data-toggle="tab" aria-expanded="false" >Information</a></li>
            <li class="active"><a class="css_tab" href="#tab_2" data-toggle="tab" aria-expanded="true" >Column Setting</a></li>
            <li class="disabled"><a href="#tab_3" data-toggle="tab" aria-expanded="false">Method & Format</a></li>
            <li class="disabled"><a href="#tab_4" data-toggle="tab" aria-expanded="false">Summary</a></li>
            <?php
          }
          ?>
        </ul>
        <div class="tab-content" >
          <div class="tab-pane active" id="tab_2">
            <div class="box-body">
              <div class="col-md-1" style="margin-top: 5px;"><b>ERP Type</b></div>
              <div class="col-md-3" style="margin-bottom: 5px;">
                <select name="erp_type" id="erp_type" class="form-control select2" multiple="multiple">
                  <option value="SAP">SAP ERP</option>
                  <option value="JDE">JD Edwards ERP</option> 
                </select>
              </div>
	            <div class="col-md-3">
                <button id="location_all" class="btn btn-sm btn-primary" >
                  ALL
                </button>
                <button id="location_all_dis" class="btn btn-sm btn-danger" >
                  RESET
                </button>
	              <button id="erp_search" class="btn btn-sm btn-default" >
                  <i class="fa fa-search"></i> Search
                </button>
              </div>
              <div class="col-md-12">
              <?php
              if(in_array('IAVA',$this->session->userdata('module_code')))
              {
              ?>
                <button id="extra_selected" class="btn btn-xs btn-primary" style="float:right;">
                  <i class="fa fa-plus"></i> Add Extra
                </button>
              <?php
              }
              ?>
              </div>
              <div class="col-md-12">
              <h4 class="box-title">Main Table
              <div class="pull-right">
              <?php
              if(in_array('IAVA',$this->session->userdata('module_code')))
              {
              ?>
                <button id="view_child" class="btn btn-xs btn-warning">
                  <i class="fa fa-eye"></i> Show Child Table
                </button>

                <span id="append_hide_child"></span>
                
		            <!-- <button id="testbtn" class="btn btn-xs btn-primary">
                  <i class="fa fa-plus"></i> Add Extra
                </button> -->

                <!-- <button id="update_selected" class="btn btn-xs btn-success"> -->
                  <!-- <i class="fa fa-save"></i> Save -->
                <!-- </button> -->
              <?php
              }
              ?>
              </div>
              </h4>
              <div class="box box-primary">
              <table class="table table-bordered table-striped dataTable" id="main_table" style="width: 50%;">
                <thead style="white-space: nowrap;">
                  <tr>
                    <th>No.</th>
                    <th>Doc Type</th> 
                    <th>Column</th>
                    <th>ERP Type</th>
                    <th>Data Type</th>
                    <th>Length</th>
                    <th>Description</th>
                    <th>Column Name</th>
                    <th>Default Value</th>
                    <th>Sequence</th>
                    <th>Cross Reference</th>
                  </tr>
                </thead>
                <tbody> 
                </tbody>

              </table>
              </div>
              </div>

              <div class="col-md-12">
              <span id="append_child_tb"></span>
<!--               <h4 class="box-title">Child Table </h4> 
              <div class="box box-warning"> 
                <table class="table table-bordered table-striped dataTable" id="child_table" style="width: 50%;"> 
                  <thead style="white-space: nowrap;"> 
                    <tr> 
                      <th>Doc Type</th> 
                      <th>Column</th> 
                      <th>Column Name</th>
                      <th>Default Value</th>
                      <th>Sequence</th> 
                      <th>Cross Reference</th>
                    </tr>
                  </thead> 
                  <tbody> 
                  </tbody> 
                </table> 
              </div> -->
             
              </div>
            </div>

          <br/>
          <div class="box-footer">

            <button id="back_edi" type="button" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> Back</button>
	          <button id="next_edi" type="button" class="btn btn-primary" style="float:right;"><i class="fa fa-arrow-circle-right"></i> Save </button>
            <?php
            if($show_button == '1')
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
  var link_doc = '<?php echo $link_doc ?>';
  var show_child_tb = '<?php echo $show_child_tb ?>';
  var erp_type = '';

  if(show_child_tb== '1')
  {
    setTimeout(function(){
      $("#view_child").trigger( "click" );
    },300);
  }
  
  //main table
  main_table = function(erp_type) {

  if ($.fn.DataTable.isDataTable('#main_table')) {
        $('#main_table').DataTable().destroy();
    }

  var table;
  table = $('#main_table').DataTable({
    "columnDefs": [
    // {"targets": 0 ,"orderable": false},
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
    "sScrollY": "40vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Edi_setup/tab_two_main_tb');?>",
        "type": "POST",
	      "data": function(data) {
          data.tab_guid = tab_guid
          data.link_doc = link_doc
          data.erp_type = erp_type
        },
        //data : {tab_guid:tab_guid,link_doc:link_doc},
    },
    columns: [
            { "data" : "empty" ,render:function( data, type, row , meta ){

              var element = '';
              var element1 = row['doc_table'];

              element = meta.row + meta.settings._iDisplayStart + 1;


              return element;
            }},
            { "data" : "doc_type" ,render:function( data, type, row ){

              var element = '';
              var element1 = row['doc_table'];

              element +=  data;

              return element;
            }},
            { "data" : "column_name" ,render:function( data, type, row ){

              var element = '';
              var element1 = row['column_length'];
              var element2 = row['column_data'];
              var element3 = row['seq_data'];
              var element4 = row['default_data'];
              var element5 = row['different_b2b_field_data'];
              var element6 = '';

              if((element2 != '' && element2 != null &&  element2 != 'null' &&  element2 != 'undefined') || (element3 != '' && element3 != null &&  element3 != 'null' &&  element3 != 'undefined'))
              {
                checked = 'checked';
              }
              else
              {
                checked = '';
              }

              if(element2 == '' || element2 == null || element2 == 'null' || element2 == 'undefined')
              {
                element2 = '';
              }

              if(element3 == '' || element3 == null || element3 == 'null' || element3 == 'undefined')
              {
                element3 = '';
              }

              if(element4 == '' || element4 == null || element4 == 'null' || element4 == 'undefined')
              {
                element4 = '';
              }

              if(element5 == '' || element5 == null || element5 == 'null' || element5 == 'undefined')
              {
                element5 = '';
              }

              if(element5 != '' && element5 != null && element5 != 'null' && element5 != 'undefined')
              {
                element6 = '1';
              }

              <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
              {
                ?>
                element += data;

                element += '<input type="checkbox" class="form-checkbox pull-right" id="final_check" name="final_check" table_position = "'+row['table_position']+'" column_name = "'+row['column_name']+'" column_length = "'+row['column_length']+'" column_value = "'+element2+'" seq = "'+element3+'" default_value = "'+element4+'" cross_val = "'+element5+'" cross_ref = "'+element6+'" '+checked+' hidden/>';

                <?php
              }
              ?>

              return element;
            }},
            { "data" : "erp_type"},
            { "data" : "column_datatype"},
            { "data" : "column_length"},
            { "data" : "column_description"},
            { "data" : "empty" ,render:function( data, type, row ){

              var element = '';
              var element1 = row['column_data'];

              if(element1 == '' || element1 == null || element1 == 'null' || element1 == 'undefined')
              {
                element1 = '';
              }

              <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
              {
                ?>
                  
                element += '<input type="text" class="edit_text" id="edit_column_value" name="edit_column_value" value="'+element1+'">';

                <?php
              }
              ?>

              return element;
            }},
            { "data" : "empty" ,render:function( data, type, row, meta ){

              var element = '';
              var element1 = row['default_data'];
              var idlength = meta.row + meta.settings._iDisplayStart + 1;

              if(element1 == '' || element1 == null || element1 == 'null' || element1 == 'undefined')
              {
                element1 = '';
              }

              <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
              {
                ?>
                  
                element += '<input type="text" class="edit_text" id="edit_default_value" name="edit_default_value" style="width:12vh;" value="'+element1+'" idlength="'+idlength+'">';

                element += '<span class="default_length'+idlength+'"></span>';

                <?php
              }
              ?>

              return element;
            }},
            { "data" : "empty" ,render:function( data, type, row ){

              var element = '';
              var element1 = row['seq_data'];

              if(element1 == '' || element1 == null || element1 == 'null' || element1 == 'undefined')
              {
                element1 = '';
              }

              <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
              {
                ?>
                  
                element += '<input type="number" class="edit_text" id="edit_seq" name="edit_seq" style="width:10vh;" value="'+element1+'" min="1">';

                <?php
              }
              ?>

              return element;
            }},
            { "data" : "empty" ,render:function( data, type, row , meta){

              var element = '';
              var element1 = row['cross_ref_data'];
              var element2 = row['different_b2b_field_data'];
              var addname = meta.row + meta.settings._iDisplayStart + 1;
              var html ='';

              if(element1 == '' || element1 == null || element1 == 'null' || element1 == 'undefined' || element1 == '0' )
              {
                element2 = '';
                checked = '';
              }
              else
              {
                checked = 'checked';

                html = '<select class="form-control select2 class_put_value'+addname+'" name="select_cross_ref" id="select_cross_ref" '+element2+'> <option value="" disabled selected>-Select-</option> <?php foreach($get_itemmaster as $row) { ?> <option value="<?php echo $row->COLUMN_NAME?>"><?php echo addslashes($row->COLUMN_NAME) ?></option>  <?php } ?></select>';

                setTimeout(function(){
                  $('.class_put_value'+addname+'').val(element2);  
                },300);
              }

              <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
              {
                ?>
                element += '<input type="checkbox" class="form-checkbox pull-left" id="cross_ref" name="cross_ref" style="margin-right:5px;" '+checked+'/>';

                element += '<span id="item_tb">'+html+'</span>';

                <?php
              }
              ?>

              return element;
            }},
          ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rt',

    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('final_amount', aData['final_amount']);
      if(aData['table_position'] == 1 )
      {
        //if added columns add the code here
        $(nRow).find('td:eq(0)').css({"background-color":"#b1cdfa","color":"black"});
        $(nRow).find('td:eq(1)').css({"background-color":"#b1cdfa","color":"black"});
        $(nRow).find('td:eq(2)').css({"background-color":"#b1cdfa","color":"black"});
        $(nRow).find('td:eq(3)').css({"background-color":"#b1cdfa","color":"black"});
        $(nRow).find('td:eq(4)').css({"background-color":"#b1cdfa","color":"black"});
        $(nRow).find('td:eq(5)').css({"background-color":"#b1cdfa","color":"black"});
        $(nRow).find('td:eq(6)').css({"background-color":"#b1cdfa","color":"black"});
        $(nRow).find('td:eq(7)').css({"background-color":"#b1cdfa","color":"black"});
        $(nRow).find('td:eq(8)').css({"background-color":"#b1cdfa","color":"black"});
        $(nRow).find('td:eq(9)').css({"background-color":"#b1cdfa","color":"black"});
        $(nRow).find('td:eq(10)').css({"background-color":"#b1cdfa","color":"black"});
      }
      else if(aData['table_position'] == 2 )
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#9ee6f7","color":"black"});
        $(nRow).find('td:eq(1)').css({"background-color":"#9ee6f7","color":"black"});
        $(nRow).find('td:eq(2)').css({"background-color":"#9ee6f7","color":"black"});
        $(nRow).find('td:eq(3)').css({"background-color":"#9ee6f7","color":"black"});
        $(nRow).find('td:eq(4)').css({"background-color":"#9ee6f7","color":"black"});
        $(nRow).find('td:eq(5)').css({"background-color":"#9ee6f7","color":"black"});
        $(nRow).find('td:eq(6)').css({"background-color":"#9ee6f7","color":"black"});
        $(nRow).find('td:eq(7)').css({"background-color":"#9ee6f7","color":"black"});
        $(nRow).find('td:eq(8)').css({"background-color":"#9ee6f7","color":"black"});
        $(nRow).find('td:eq(9)').css({"background-color":"#9ee6f7","color":"black"});
        $(nRow).find('td:eq(10)').css({"background-color":"#9ee6f7","color":"black"});
      }
      else
      {

      }
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable
  }
	
  main_table(erp_type);

  //child table view
  $(document).on('click','#view_child',function(){
    $('#view_child').hide();
    $('#append_hide_child').html('<button id="hide_child" class="btn btn-xs btn-danger"> <i class="fa fa-eye-slash"></i> Hide Child Table </button>');

    $('#append_child_tb').html('<h4 class="box-title">Child Table </h4> <div class="box box-warning"> <table class="table table-bordered table-striped dataTable" id="child_table" style="width: 50%;"> <thead style="white-space: nowrap;"> <tr> <th>No.</th> <th>Doc Type</th> <th>Column</th> <th>ERP Type</th> <th>Data Type</th> <th>Length</th> <th>Description</th> <th>Column Name</th> <th>Default Value</th> <th>Sequence</th> <th>Cross Reference</th> </tr> </thead> <tbody> </tbody> </table> </div>');
	
    var erp_type = $('#erp_type').val(); 

    if(erp_type == 'null' || erp_type == null)
    {
	    erp_type = '';
    }

    //child table
    child_table = function(erp_type) {

    if ($.fn.DataTable.isDataTable('#child_table')) {
        $('#child_table').DataTable().destroy();
    }

    var table;
    table = $('#child_table').DataTable({
      "columnDefs": [
      {"targets": 0 ,"orderable": false},
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
      "sScrollY": "40vh", 
      "sScrollX": "100%", 
      "sScrollXInner": "100%", 
      "bScrollCollapse": true,
      "ajax": {
          "url": "<?php echo site_url('Edi_setup/tab_two_child_tb');?>",
          "type": "POST",
	  "data": function(data) {
            data.tab_guid = tab_guid
            data.link_doc = link_doc
            data.erp_type = erp_type
          },
          //data : {tab_guid:tab_guid,link_doc:link_doc},
      },
      columns: [
              { "data" : "empty" ,render:function( data, type, row , meta ){

                var element = '';

                element = meta.row + meta.settings._iDisplayStart + 1;


                return element;
              }},
              { "data" : "doc_type" ,render:function( data, type, row ){

                var element = '';
                var element1 = row['doc_table'];

                //element += element1.charAt(0).toUpperCase() + element1.slice(1) + ' - ' + data;
                element += data;

                return element;
              }},
              { "data" : "column_name" ,render:function( data, type, row ){

                var element = '';
                var element1 = row['column_length'];
                var element2 = row['column_data'];
                var element3 = row['seq_data'];
                var element4 = row['default_data'];
                var element5 = row['different_b2b_field_data'];
                var element6 = '';

                if((element2 != '' && element2 != null &&  element2 != 'null' &&  element2 != 'undefined') || (element3 != '' && element3 != null &&  element3 != 'null' &&  element3 != 'undefined'))
                {
                  checked = 'checked';
                }
                else
                {
                  checked = '';
                }

                if(element2 == '' || element2 == null || element2 == 'null' || element2 == 'undefined')
                {
                  element2 = '';
                }

                if(element3 == '' || element3 == null || element3 == 'null' || element3 == 'undefined')
                {
                  element3 = '';
                }

                if(element4 == '' || element4 == null || element4 == 'null' || element4 == 'undefined')
                {
                  element4 = '';
                }

                if(element5 == '' || element5 == null || element5 == 'null' || element5 == 'undefined')
                {
                  element5 = '';
                }

                if(element5 != '' && element5 != null && element5 != 'null' && element5 != 'undefined')
                {
                  element6 = '1';
                }

                <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
                {
                  ?>
                  element += data;

                  element += '<input type="checkbox" class="form-checkbox pull-right" id="c_final_check" name="c_final_check" c_table_position = "'+row['table_position']+'" c_column_name = "'+row['column_name']+'" c_column_length = "'+row['column_length']+'" c_column_value = "'+element2+'" c_seq = "'+element3+'" c_default_value = "'+element4+'"  c_cross_val = "'+element5+'"  c_cross_ref = "'+element6+'" '+checked+' hidden/>';

                  <?php
                }
                ?>

                return element;
              }},
              { "data" : "erp_type"},
              { "data" : "column_datatype"},
              { "data" : "column_length"},
              { "data" : "column_description"},
              { "data" : "empty" ,render:function( data, type, row ){

                var element = '';
                var element1 = row['column_data'];

                if(element1 == '' || element1 == null || element1 == 'null' || element1 == 'undefined')
                {
                  element1 = '';
                }

                <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
                {
                  ?>
                    
                  element += '<input type="text" class="edit_text" id="c_edit_column_value" name="c_edit_column_value"  value="'+element1+'">';

                  <?php
                }
                ?>

                return element;
              }},
              { "data" : "empty" ,render:function( data, type, row, meta ){

                var element = '';
                var element1 = row['default_data'];
                var c_idlength = meta.row + meta.settings._iDisplayStart + 1;

                if(element1 == '' || element1 == null || element1 == 'null' || element1 == 'undefined')
                {
                  element1 = '';
                }

                <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
                {
                  ?>
                    
                  element += '<input type="text" class="edit_text" id="c_edit_default_value" name="c_edit_default_value" style="width:12vh;" value="'+element1+'" c_idlength="'+c_idlength+'">';

                  element += '<span class="c_default_length'+c_idlength+'"></span>';

                  <?php
                }
                ?>

                return element;
              }},
              { "data" : "empty" ,render:function( data, type, row ){

                var element = '';
                var element1 = row['seq_data'];
                
                if(element1 == '' || element1 == null || element1 == 'null' || element1 == 'undefined')
                {
                  element1 = '';
                }

                <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
                {
                  ?>
                    
                  element += '<input type="text" class="edit_text" id="c_edit_seq" name="c_edit_seq" style="width:10vh;" value="'+element1+'">';

                  <?php
                }
                ?>

                return element;
              }},
              { "data" : "empty" ,render:function( data, type, row , meta){

                var element = '';
                var element1 = row['cross_ref_data'];
                var element2 = row['different_b2b_field_data'];
                var addname = meta.row + meta.settings._iDisplayStart + 1;
                var html ='';

                if(element2 == '' || element2 == null || element2 == 'null' || element2 == 'undefined')
                {
                  element2 = '';
                  checked = '';
                }
                else
                {
                  checked = 'checked';

                  html = '<select class="form-control select2 c_class_put_value'+addname+'" name="select_cross_ref" id="select_cross_ref" '+element2+'> <option value="" disabled selected>-Select-</option> <?php foreach($get_itemmaster as $row) { ?> <option value="<?php echo $row->COLUMN_NAME?>"><?php echo addslashes($row->COLUMN_NAME) ?></option>  <?php } ?></select>';

                  setTimeout(function(){
                    $('.c_class_put_value'+addname+'').val(element2);  
                  },300);
                }

                <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
                {
                  ?>
                  element += '<input type="checkbox" class="form-checkbox pull-left" id="c_cross_ref" name="c_cross_ref" style="margin-right:5px;" '+checked+'/>';

                  element += '<span id="c_item_tb">'+html+'</span>';

                  <?php
                }
                ?>

                return element;
              }},
            ],
      dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rt',

      "fnCreatedRow": function( nRow, aData, iDataIndex ) {
        //$(nRow).attr('final_amount', aData['final_amount']);
        if(aData['table_position'] == 1 )
        {
          //if added columns add the code here
          $(nRow).find('td:eq(0)').css({"background-color":"#b1cdfa","color":"black"});
          $(nRow).find('td:eq(1)').css({"background-color":"#b1cdfa","color":"black"});
          $(nRow).find('td:eq(2)').css({"background-color":"#b1cdfa","color":"black"});
          $(nRow).find('td:eq(3)').css({"background-color":"#b1cdfa","color":"black"});
          $(nRow).find('td:eq(4)').css({"background-color":"#b1cdfa","color":"black"});
          $(nRow).find('td:eq(5)').css({"background-color":"#b1cdfa","color":"black"});
          $(nRow).find('td:eq(6)').css({"background-color":"#b1cdfa","color":"black"});
          $(nRow).find('td:eq(7)').css({"background-color":"#b1cdfa","color":"black"});
          $(nRow).find('td:eq(8)').css({"background-color":"#b1cdfa","color":"black"});
          $(nRow).find('td:eq(9)').css({"background-color":"#b1cdfa","color":"black"});
          $(nRow).find('td:eq(10)').css({"background-color":"#b1cdfa","color":"black"});
        }
        else if(aData['table_position'] == 2 )
        {
          $(nRow).find('td:eq(0)').css({"background-color":"#9ee6f7","color":"black"});
          $(nRow).find('td:eq(1)').css({"background-color":"#9ee6f7","color":"black"});
          $(nRow).find('td:eq(2)').css({"background-color":"#9ee6f7","color":"black"});
          $(nRow).find('td:eq(3)').css({"background-color":"#9ee6f7","color":"black"});
          $(nRow).find('td:eq(4)').css({"background-color":"#9ee6f7","color":"black"});
          $(nRow).find('td:eq(5)').css({"background-color":"#9ee6f7","color":"black"});
          $(nRow).find('td:eq(6)').css({"background-color":"#9ee6f7","color":"black"});
          $(nRow).find('td:eq(7)').css({"background-color":"#9ee6f7","color":"black"});
          $(nRow).find('td:eq(8)').css({"background-color":"#9ee6f7","color":"black"});
          $(nRow).find('td:eq(9)').css({"background-color":"#9ee6f7","color":"black"});
          $(nRow).find('td:eq(10)').css({"background-color":"#9ee6f7","color":"black"});
        }
        else
        {

        }
        
      },
      "initComplete": function( settings, json ) {
        setTimeout(function(){
          interval();
        },300);
      }
    });//close datatable
    }
    
     child_table(erp_type);
  });//close modal create

  //input main column value
  $(document).on('change','#edit_column_value',function(){
    var name_val = $(this).val();
    var currentRow=$(this).closest("tr"); 
    var col1=currentRow.find("td:eq(7)").find("#edit_column_value").val(); 
    var col2=currentRow.find("td:eq(9)").find("#edit_seq").val();
    var length=currentRow.find("td:eq(2)").find("#final_check").attr('column_length');

    if(name_val != '')
    {
      if(!/^[^-\s].*[\w]*$/.test(name_val))
      {
        alert('Invalid Value. Please insert character first.');
        $(this).val('');
        //$(this).focus();
        var data_uncheck_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',false);
        return;
      }
    }

    // if(name_val.length > length)
    // {
    //   alert('Invalid Value Length. Please insert within '+length+' characters.');
    //   $(this).val('');
    //   $(this).focus();
    //   var data_uncheck_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',false);
    //   return;
    // }
    
    var data_put_batch=currentRow.find("td:eq(2)").find("#final_check").attr('column_value',col1);

    if( ((col1 != '') && (col1 != null) && (col1 != 'null')) && ((col2 != '') && (col2 != null) && (col2 != 'null'))) 
    {
      var data_check_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',true);
    }
    else
    {
      var data_uncheck_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',false);
    }
  });//close main column value

  //input main default value
  $(document).on('change','#edit_default_value',function(){
    var currentRow=$(this).closest("tr"); 
    var idlength = $(this).attr('idlength');
    var col1=currentRow.find("td:eq(7)").find("#edit_column_value").val(); 
    var col2=currentRow.find("td:eq(9)").find("#edit_seq").val();
    var col3=currentRow.find("td:eq(8)").find("#edit_default_value").val(); 

    var data_put_batch=currentRow.find("td:eq(2)").find("#final_check").attr('default_value',col3);
    if((col3 != '') || (col3 != null) || (col3 != 'null'))
    {
      if(((col1 != '') && (col1 != null) && (col1 != 'null')) && ((col2 != '') && (col2 != null) && (col2 != 'null'))) 
      {
        var data_check_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',true);
        //$('.default_length'+idlength).html('<b style="color:black;"> Length : '+col3.length+' </b>');
      }
      else
      {
        var data_uncheck_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',false);
        //$('.default_length'+idlength).html('');
      }
    }
  });//close main column value

  //input main default value
  $(document).on('keyup','#edit_default_value',function(){
    var currentRow=$(this).closest("tr"); 
    var idlength = $(this).attr('idlength');
    var col1=currentRow.find("td:eq(7)").find("#edit_column_value").val(); 
    var col2=currentRow.find("td:eq(9)").find("#edit_seq").val();
    var col3=currentRow.find("td:eq(8)").find("#edit_default_value").val(); 
    var length=currentRow.find("td:eq(2)").find("#final_check").attr('column_length');

    if(col3.length > length)
    {
      alert('Invalid Value Length. Please insert within '+length+' characters.');
      $(this).val('');
      //$(this).focus();
      $('.default_length'+idlength).html('');
      //var data_uncheck_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',false);
      return;
    }

    if((col3 != '') || (col3 != null) || (col3 != 'null'))
    {
      if(((col1 != '') && (col1 != null) && (col1 != 'null')) && ((col2 != '') && (col2 != null) && (col2 != 'null'))) 
      {
        // var data_check_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',true);
        $('.default_length'+idlength).html('<b style="color:black;"> Length : '+col3.length+' </b>');
      }
      else
      {
        // var data_uncheck_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',false);
        $('.default_length'+idlength).html('');
      }
    }
  });//close main column value

  //input main seq
  $(document).on('change','#edit_seq',function(){
    var seq_val = $(this).val();
    var table = $('#main_table').DataTable();
    var currentRow=$(this).closest("tr"); 
    var col1=currentRow.find("td:eq(7)").find("#edit_column_value").val(); 
    var col2=currentRow.find("td:eq(9)").find("#edit_seq").val();
    var col3=currentRow.find("td:eq(8)").find("#edit_default_value").val(); 
    var shoot_link = 0;

    if(!/^[0-9]*$/.test(seq_val))
    {
      alert('Invalid Value. Please insert numbering. Starting with Seq 1.');
      $(this).val('');
      //$(this).focus();
      return;
    }

    table.rows().nodes().to$().each(function(){
      if($(this).find('td').find('#final_check').is(':checked'))
      {
        var seq = $(this).find('td').find('#final_check').attr('seq');

        if(col2 == seq)
        {
          alert('Duplicate Sequence Number.');
          shoot_link++;
          col2 = '';
          return;
        }
      }
    });//close small loop

    if(shoot_link == 0)
    {
      var data_put_batch=currentRow.find("td:eq(2)").find("#final_check").attr('seq',col2);
    }
    else
    {
      var data_put_batch=currentRow.find("td:eq(2)").find("#final_check").attr('seq',col2);
      $(this).val('');
      //$(this).focus();
    }
    
    if(((col1 != '') && (col1 != null) && (col1 != 'null')) && ((col2 != '') && (col2 != null) && (col2 != 'null'))) 
    {
      var data_check_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',true);
    }
    else
    {
      var data_uncheck_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',false);
    }
  });//close main seq

  //checking checkbox main
  $(document).on('click','#final_check',function(){
    alert('Ouch! Please dont click me.');
    var currentRow=$(this).closest("tr"); 
    if($(this).is(":checked")) 
    {
      var debug_uncheck=currentRow.find("td:eq(2)").find("#final_check").prop('checked',false);
    }
    else 
    {
      var debug_uncheck=currentRow.find("td:eq(2)").find("#final_check").prop('checked',true);
    }
    return;
  });//close checkbox main

  //itemmaster table selection
  $(document).on('click','#cross_ref',function(){
    setTimeout(function(){
      interval();
    },300);
    var currentRow=$(this).closest("tr"); 

    var col1=currentRow.find("td:eq(2)").find("#final_check").attr('cross_val',''); 
    var col2=currentRow.find("td:eq(2)").find("#final_check").attr('cross_ref',''); 

    if($(this).is(':checked'))
    {
      var col3=currentRow.find("td:eq(2)").find("#final_check").attr('cross_ref','1'); 

      var data_put_cross=currentRow.find("td:eq(10)").find("#item_tb").html('<select class="form-control select2" name="select_cross_ref" id="select_cross_ref" > <option value="" disabled selected>-Select-</option> <?php foreach($get_itemmaster as $row) { ?> <option value="<?php echo $row->COLUMN_NAME?>"><?php echo addslashes($row->COLUMN_NAME) ?></option> <?php } ?></select>');

      //$('#select_cross_ref').select2();
    }
    else
    {
      var col3=currentRow.find("td:eq(2)").find("#final_check").attr('cross_ref','0'); 
      var data_put_cross=currentRow.find("td:eq(10)").find("#item_tb").html('');
    }//close else
  });//close modal create

  //choose itemmaster value
  $(document).on('change','#select_cross_ref',function(){
    var currentRow=$(this).closest("tr"); 
    var crossval=currentRow.find("td:eq(10)").find("#select_cross_ref").val(); 
    var data_put_cross=currentRow.find("td:eq(2)").find("#final_check").attr('cross_val',crossval);
  });//close modal create

  //input child column value
  $(document).on('change','#c_edit_column_value',function(){
    var c_name_val = $(this).val();
    var c_currentRow=$(this).closest("tr"); 
    var c_col1=c_currentRow.find("td:eq(7)").find("#c_edit_column_value").val(); 
    var c_col2=c_currentRow.find("td:eq(8)").find("#c_edit_seq").val();
    var c_length=c_currentRow.find("td:eq(2)").find("#c_final_check").attr('c_column_length');

    if(!/^[^-\s].*[\w]*$/.test(c_name_val))
    {
      alert('Invalid Value. Please insert character first.');
      $(this).val('');
      //$(this).focus();
      var data_uncheck_batch=currentRow.find("td:eq(2)").find("#c_final_check").prop('checked',false);
      return;
    }

    // if(c_name_val.length > c_length)
    // {
    //   alert('Invalid Value Length. Please insert within '+c_length+' characters.');
    //   $(this).val('');
    //   $(this).focus();
    //   var data_uncheck_batch=currentRow.find("td:eq(2)").find("#c_final_check").prop('checked',false);
    //   return;
    // }

    var c_data_put_batch=c_currentRow.find("td:eq(2)").find("#c_final_check").attr('c_column_value',c_col1);

    if( ((c_col1 != '') && (c_col1 != null) && (c_col1 != 'null')) && ((c_col2 != '') && (c_col2 != null) && (c_col2 != 'null'))) 
    {
      var c_data_check_batch=c_currentRow.find("td:eq(2)").find("#c_final_check").prop('checked',true);
    }
    else
    {
      var c_data_uncheck_batch=c_currentRow.find("td:eq(2)").find("#c_final_check").prop('checked',false);
    }
  });//close child column value

  //input child default value
  $(document).on('change','#c_edit_default_value',function(){
    var currentRow=$(this).closest("tr"); 
    var col1=currentRow.find("td:eq(7)").find("#c_edit_column_value").val(); 
    var col2=currentRow.find("td:eq(9)").find("#c_edit_seq").val();
    var col3=currentRow.find("td:eq(8)").find("#c_edit_default_value").val(); 

    var data_put_batch=currentRow.find("td:eq(2)").find("#c_final_check").attr('c_default_value',col3);
    if((col3 != '') || (col3 != null) || (col3 != 'null'))
    {
      if(((col1 != '') && (col1 != null) && (col1 != 'null')) && ((col2 != '') && (col2 != null) && (col2 != 'null'))) 
      {
        var data_check_batch=currentRow.find("td:eq(2)").find("#c_final_check").prop('checked',true);
      }
      else
      {
        var data_uncheck_batch=currentRow.find("td:eq(2)").find("#c_final_check").prop('checked',false);
      }
    }
  });//close main column value

  //input child default value
  $(document).on('keyup','#c_edit_default_value',function(){
    var currentRow=$(this).closest("tr"); 
    var c_idlength = $(this).attr('c_idlength');
    var col1=currentRow.find("td:eq(7)").find("#c_edit_column_value").val(); 
    var col2=currentRow.find("td:eq(9)").find("#c_edit_seq").val();
    var col3=currentRow.find("td:eq(8)").find("#c_edit_default_value").val(); 
    var c_length=currentRow.find("td:eq(2)").find("#c_final_check").attr('c_column_length');

    if(col3.length > c_length)
    {
      alert('Invalid Value Length. Please insert within '+c_length+' characters.');
      $(this).val('');
      //$(this).focus();
      $('.c_default_length'+idlength).html('');
      //var data_uncheck_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',false);
      return;
    }

    if((col3 != '') || (col3 != null) || (col3 != 'null'))
    {
      if(((col1 != '') && (col1 != null) && (col1 != 'null')) && ((col2 != '') && (col2 != null) && (col2 != 'null'))) 
      {
        // var data_check_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',true);
        $('.c_default_length'+c_idlength).html('<b style="color:black;"> Length : '+col3.length+' </b>');
      }
      else
      {
        // var data_uncheck_batch=currentRow.find("td:eq(2)").find("#final_check").prop('checked',false);
        $('.c_default_length'+c_idlength).html('');
      }
    }

  });//close main column value

  //input child seq
  $(document).on('change','#c_edit_seq',function(){
    var c_seq_val = $(this).val();
    var c_table = $('#child_table').DataTable();
    var c_currentRow=$(this).closest("tr"); 
    var c_col1=c_currentRow.find("td:eq(7)").find("#c_edit_column_value").val(); 
    var c_col2=c_currentRow.find("td:eq(9)").find("#c_edit_seq").val();
    var c_shoot_link = 0;

    if(!/^[0-9]*$/.test(c_seq_val))
    {
      alert('Invalid Value. Please insert numbering.Starting with Seq 1');
      $(this).val('');
      //$(this).focus();
      return;
    }

    c_table.rows().nodes().to$().each(function(){
      if($(this).find('td').find('#c_final_check').is(':checked'))
      {
        var c_seq = $(this).find('td').find('#c_final_check').attr('c_seq');

        if(c_col2 == c_seq)
        {
          alert('Duplicate Sequence Number.');
          c_shoot_link++;
          c_col2 = '';
          return;
        }
      }
    });//close small loop

    if(c_shoot_link == 0)
    {
      var c_data_put_batch=c_currentRow.find("td:eq(2)").find("#c_final_check").attr('c_seq',c_col2);
    }
    else
    {
      var c_data_put_batch=c_currentRow.find("td:eq(2)").find("#c_final_check").attr('c_seq',c_col2);
      $(this).val('');
      //$(this).focus();
    }
    
    if(((c_col1 != '') && (c_col1 != null) && (c_col1 != 'null')) && ((c_col2 != '') && (c_col2 != null) && (c_col2 != 'null'))) 
    {
      var c_data_check_batch=c_currentRow.find("td:eq(2)").find("#c_final_check").prop('checked',true);
    }
    else
    {
      var c_data_check_batch=c_currentRow.find("td:eq(2)").find("#c_final_check").prop('checked',false);
    }
  });//close child seq

  //itemmaster table selection
  $(document).on('click','#c_cross_ref',function(){
    setTimeout(function(){
      interval();
    },300);
    var currentRow=$(this).closest("tr"); 

    var col1=currentRow.find("td:eq(2)").find("#c_final_check").attr('c_cross_val',''); 
    var col2=currentRow.find("td:eq(2)").find("#c_final_check").attr('c_cross_ref',''); 

    if($(this).is(':checked'))
    {
      var col3=currentRow.find("td:eq(2)").find("#c_final_check").attr('c_cross_ref','1'); 

      var data_put_cross_c=currentRow.find("td:eq(10)").find("#c_item_tb").html('<select class="form-control select2" name="select_cross_ref_c" id="select_cross_ref_c" > <option value="" disabled selected>-Select-</option> <?php foreach($get_itemmaster as $row) { ?> <option value="<?php echo $row->COLUMN_NAME?>"><?php echo addslashes($row->COLUMN_NAME) ?></option> <?php } ?></select>');

      //$('#select_cross_ref_c').select2();
    }
    else
    {
      var col3=currentRow.find("td:eq(2)").find("#c_final_check").attr('c_cross_ref','0'); 
      var data_put_cross_c=currentRow.find("td:eq(10)").find("#c_item_tb").html('');
    }//close else
  });//close modal create

  //choose itemmaster value
  $(document).on('change','#select_cross_ref_c',function(){
    var currentRow=$(this).closest("tr"); 
    var crossval_c=currentRow.find("td:eq(10)").find("#select_cross_ref_c").val(); 
    var attr_put_cross_c=currentRow.find("td:eq(2)").find("#c_final_check").attr('c_cross_val',crossval_c);
  });//close modal create

  //checking checkbox child
  $(document).on('click','#c_final_check',function(){
    alert('Ouch! Please dont click me.');
    var c_currentRow=$(this).closest("tr"); 
    
    if($(this).is(":checked")) 
    {
      var c_debug_uncheck=c_currentRow.find("td:eq(2)").find("#c_final_check").prop('checked',false);
    }
    else 
    {
      var c_debug_uncheck=c_currentRow.find("td:eq(2)").find("#c_final_check").prop('checked',true);
    }
    return;
  });//close checkbox child

  $(document).on('click','#hide_child',function(){
    $('#view_child').show();
    $('#append_hide_child').html('');
    $('#append_child_tb').html('');
  });//close hide child table

  $(document).on('click','#extra_selected',function(){
    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Add Extra Coloumn');

    methodd = '';

    methodd += '<div class="col-md-12">';
    
    methodd += '<div class="col-md-12"><input type="hidden" class="form-control input-sm" id="tb_position" value="1" /></div>';

    methodd += '<div class="col-md-12"><label>Column Name</label><input type="text" class="form-control input-sm" id="add_column" /></div>';

    methodd += '<div class="col-md-12"><label>Column Data Type</label><input type="text" class="form-control input-sm" id="column_data_type" /></div>';

    methodd += '<div class="col-md-12"><label>Column Data Type</label> <select class="form-control select2" name="column_data_type" id="column_data_type" > <?php foreach($get_datatype as $row) { ?> <option value="<?php echo $row->value?>"><?php echo addslashes($row->value) ?> - <?php echo addslashes($row->description) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Column Length</label><input type="number" class="form-control input-sm" id="column_length" /></div>';
    
    methodd += '<div class="col-md-12"><label>Description</label><input type="text" class="form-control input-sm" id="column_description" /></div>';

    methodd += '<div class="col-md-12"><label>ERP Type</label> <select class="form-control select2" name="add_erp_type" id="add_erp_type" multiple="true"> <?php foreach($get_erp as $row) { ?> <option value="<?php echo $row->value?>"><?php echo addslashes($row->value) ?></option> <?php } ?></select> </div>';

    // methodd += '<div class="col-md-12"><label>Table Position</label><select class="form-control select2" name="add_position" id="add_position"> <option value="1"> Main Table </option> <option value="2" > Child Table </option> </select></div>';

    methodd += '<div></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="add_button" class="btn btn-primary extra_add_selected"> Create </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('.select2').select2();
    },300);
  });//close hide child table

  $(document).on('click','#add_button',function(){
   
    var add_column = $('#add_column').val();
    //var add_position = $('#add_position').val();
    var column_data_type = $('#column_data_type').val();
    var column_length = $('#column_length').val();
    var column_description = $('#column_description').val();
    var add_erp_type = $('#add_erp_type').val();

    if((add_column == '') || (add_column == null) || (add_column == 'null'))
    {
      alert('Please Insert Column Name.');
	    return;
    }

    if((column_length == '') || (column_length == null) || (column_length == 'null'))
    {
      alert('Please Insert Column Length.');
      return;
    }

    // if((add_position == '') || (add_position == null) || (add_position == 'null'))
    // {
    //   alert('Please Select Position.');
    //   return;
    // }

    confirmation_modal('Are you sure want Save Add Extra Selected Field.');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Edi_setup/add_extra_column') ?>",
        method:"POST",
        data:{add_column:add_column,link_doc:link_doc,column_length:column_length,column_description:column_description,column_data_type:column_data_type,add_erp_type:add_erp_type},
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
            $('#main_table').DataTable().ajax.reload(null, false);
            $('#child_table').DataTable().ajax.reload(null, false);
            }, 300); 
          }
         
        }//close success
      });//close ajax 
    });//close document yes click
  });//close add select column

  $(document).on('click','#next_edi',function(){
    var main_details = [];
    var child_details = [];
    var main_tb = $('#main_table').DataTable();
    var child_tb = $('#child_table').DataTable();
    var column_value = ''; //column_value
    var seq = ''; //seq
    var shoot_link = 0;
    var error_cross_val = 0;

    main_tb.rows().nodes().to$().each(function(){
        
      if($(this).find('td').find('#final_check').is(':checked'))
      {
        column_name = $(this).find('td').find('#final_check').attr('column_name');

        column_value = $(this).find('td').find('#final_check').attr('column_value');

        default_value = $(this).find('td').find('#final_check').attr('default_value');

        seq = $(this).find('td').find('#final_check').attr('seq');

        cross_ref = $(this).find('td').find('#final_check').attr('cross_ref');

        cross_val = $(this).find('td').find('#final_check').attr('cross_val');

        table_position = $(this).find('td').find('#final_check').attr('table_position');

        if((table_position == '') || (table_position == 'null') || (table_position == null))
        {
          shoot_link = shoot_link+1;
          alert('Please Refresh Page.');
        }

        if((default_value == '') || (default_value == 'null') || (default_value == null))
        {
          default_value = '';
        }

        if(cross_ref == '1')
        {
          if((cross_val == '') || (cross_val == 'null') || (cross_val == null))
          {
            shoot_link = shoot_link+1;
            error_cross_val = '1';
          }
          //cross_ref = '0';
        }
        else
        {
          cross_ref = '0';
        }

        main_details.push({'b2b_field':column_name,'default_value':default_value,'seq':seq,'supplier_field':column_value,'cross_ref':cross_ref,'different_b2b_field':cross_val,'position':table_position});

        main_details.sort(dynamicSort("seq"));
      }
      
    });//close small loop

    child_tb.rows().nodes().to$().each(function(){
        
      if($(this).find('td').find('#c_final_check').is(':checked'))
      {
        c_column_name = $(this).find('td').find('#c_final_check').attr('c_column_name');

        c_column_value = $(this).find('td').find('#c_final_check').attr('c_column_value');

        c_default_value = $(this).find('td').find('#c_final_check').attr('c_default_value');

        c_seq = $(this).find('td').find('#c_final_check').attr('c_seq');

        c_cross_ref = $(this).find('td').find('#c_final_check').attr('c_cross_ref');

        c_cross_val = $(this).find('td').find('#c_final_check').attr('c_cross_val');

        c_table_position = $(this).find('td').find('#c_final_check').attr('c_table_position');

        if((c_table_position == '') || (c_table_position == 'null') || (c_table_position == null))
        {
          shoot_link = shoot_link+1;
          alert('Please Refresh Page.');
        }

        if((c_default_value == '') || (c_default_value == 'null') || (c_default_value == null))
        {
          c_default_value = '';
        }

        if(c_cross_ref == '1')
        {
          if((c_cross_val == '') || (c_cross_val == 'null') || (c_cross_val == null))
          {
            shoot_link = shoot_link+1;
            error_cross_val = '1';
          }
        }
        else
        {
          cross_ref = '0';
        }

        child_details.push({'b2b_field':c_column_name,'default_value':c_default_value,'seq':c_seq,'supplier_field':c_column_value,'cross_ref':c_cross_ref,'different_b2b_field':c_cross_val,'position':c_table_position});

        child_details.sort(dynamicSort("seq"));
      }
      
    });//close small loop

    if((child_details != '') || (child_details != null) || (child_details != 'null'))
    {
      if(main_details == '' || main_details == null || main_details == 'null')
      {
        alert('Please input both Main and Child Table before proceed.');
        return;
      }
    }
    else
    {
      child_details = '';
    }

    if(main_details == '' || main_details == null || main_details == 'null')
    {
      alert('Please input main table data first before proceed.');
      return;
    }

    if(error_cross_val == '1')
    {
      alert('Your setting is not complete. Please select Cross Reference.');
      return;
    }

    // console.log(main_details); 
    // console.log(child_details); die;
    if(shoot_link == 0)
    {
      confirmation_modal('Are you sure want Save Main Selected Field.');
      $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
          url:"<?php echo site_url('Edi_setup/selected_process') ?>",
          method:"POST",
          data:{main_details:main_details,child_details:child_details,tab_guid:tab_guid},
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
              setTimeout(function() {
              //location.reload();
              }, 300); 
            }
            else
            {
              $('.btn').button('reset');
              $('#alertmodal').modal('hide');
              alert(json.msg);
              location.reload();
              //window.location = "<?= site_url('Edi_setup/tab_three?link=');?>"+tab_guid;
            }
           
          }//close success
        });//close ajax 
      });//close document yes click
    }// check got error or not
  });//close redirect
  
  //Comparer Function    
  function dynamicSort(prop) {    
      return function(a, b) {    
        return a[prop] - b[prop];  
      } 
  }

  $(document).on('click','#erp_search',function(){

    var erp_type = $('#erp_type').val();
    var hide_btn = $('#hide_child').val();
	
    main_table(erp_type);

    if(hide_btn == '')
    {
	    child_table(erp_type);
    }
  });//close

  $(document).on('click','#skip_edi',function(){
    window.location = "<?= site_url('Edi_setup/tab_three?link=');?>"+tab_guid;
  });//close redirect

  $(document).on('click','#back_edi',function(){
    window.location = "<?= site_url('Edi_setup/tab_one?link=');?>"+tab_guid;
  });//close redirect

  $(document).on('click', '#location_all', function(){
    $("#erp_type option").prop('selected',true);
    $(".select2").select2();
    die;
  });//CLOSE ONCLICK  

  $(document).on('click', '#location_all_dis', function(){
    $("#erp_type option").prop('selected',false);
    $(".select2").select2();
    die;
  });//CLOSE ONCLICK  

});
</script>

