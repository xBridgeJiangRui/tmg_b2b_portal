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

.line_css {
  margin-bottom: 20px;
  border-left: 1px solid #333;
}

.title_css {
  font-style: italic;
  font-size: 25px;
}

</style>
<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <!-- head -->
        <div class="box-header with-border">
          <h3 class="box-title">Filter By</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- head -->
        <!-- body -->
        <div class="box-body">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-2"><b>Retailer Name</b></div>
              <div class="col-md-4">
                <select class="form-control select2 get_code" name="filter_retailer" id="filter_retailer"> 
                  <option value="" disabled selected>-Select Retailer Name-</option>  
                  <?php foreach($get_acc as $row) 
                  { ?> 
                    <option value="<?php echo addslashes($row->acc_name)?>"><?php echo addslashes($row->acc_name) ?></option> 
                  <?php 
                  } ?>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Supplier Name</b></div>
              <div class="col-md-4">
                <select class="form-control select2 get_code" name="filter_supplier" id="filter_supplier"> 
                  <option value="" disabled selected>-Select Supplier Name-</option>  
                  <?php foreach($get_supplier as $row) 
                  { ?> 
                    <option value="<?php echo $row->supplier_name?>"><?php echo addslashes($row->supplier_name) ?></option> 
                  <?php 
                  } ?>
                </select>
                <!-- <input id="filter_supplier" type="text" autocomplete="off" class="form-control pull-right"> -->
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-12">
                <button id="search" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                <button id="reset" class="btn btn-default"><i class="fa fa-repeat"></i> Reset</button>
              </div>
            </div>
          </div>
        </div>
        <!-- body -->
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Item Master List </h3><span class="pill_button" style="margin-left: 10px;"><?php echo $acc_name; ?></span><span class="pill_button filter2" style="margin-left: 5px;"></span><br>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>

            <!-- <button id="create_edi" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Create EDI</button> -->

            <?php
            }
            ?>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
          </div>
        </div>
          <div class="box-body">
            <table class="table table-bordered table-striped dataTable reset_tb" id="itemmaster_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;">
                <tr>
                  <th>Action</th>
                  <!-- <th>Retailer Name</th>  -->
                  <th>Supplier Name</th>
                  <!-- <th>Supplier Code</th> -->
                  <th>Item Code</th>
                  <th>Barcode</th>
                  <!-- <th>Article NO.</th> -->
                  <th>Item Description</th>
                  <th>UM</th>
                  <th>Supplier Item Code</th>
                  <!-- <th>Supplier Article NO.</th> -->
                  <th>Supplier Item Description</th>
                  <th>Supplier UM</th>
		              <th>Start Date</th>
		              <th>End Date</th>
                  <th>Active</th>
                  <th>Import At</th>
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
  var filter_retailer = $('#filter_retailer').val();
  var filter_supplier = $('#filter_supplier').val();
  $('.filter2').hide();
  $('#filter_retailer').val('<?php echo $acc_name?>').trigger('change');
  setTimeout(function(){
    $('#large-modal').attr({"data-backdrop":"static","data-keyboard":"false"}); //to remove clicking outside modal for closing
  },300);

  main_table = function(filter_retailer, filter_supplier) {

    if ($.fn.DataTable.isDataTable('#itemmaster_table')) {
        $('#itemmaster_table').DataTable().destroy();
    }

    var table;

    table = $('#itemmaster_table').DataTable({
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
      'order'       : [  [13 , 'desc'] ],
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
          "url": "<?php echo site_url('Edi_info/itemmaster_tb');?>",
          "type": "POST",
          "data": function(data) {
            data.filter_retailer = filter_retailer
            data.filter_supplier = filter_supplier
            //data.datefrom = datefrom
          },
      },
      columns: [
              { "data" : "guid" ,render:function( data, type, row ){

                var element = '';

                <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
                {
                  ?>
                    
                  element += '<button id="edit_btn" type="button"  title="REMOVE" class="btn btn-xs btn-info" guid="'+row['guid']+'" supplier_guid="'+row['supplier_guid']+'" supplier_itemmaster_guid="'+row['supplier_itemmaster_guid']+'" b2b_item_description="'+row['b2b_item_description']+'" b2b_um="'+row['b2b_um']+'" supplier_item_code="'+row['supplier_item_code']+'" supplier_article_no="'+row['supplier_article_no']+'" supplier_item_description="'+row['supplier_item_description']+'" supplier_um="'+row['supplier_um']+'" b2b_item_code="'+row['b2b_item_code']+'" b2b_article_no="'+row['b2b_article_no']+'" b2b_barcode="'+row['b2b_barcode']+'" validity_start_date="'+row['validity_start_date']+'" validity_end_date="'+row['validity_end_date']+'"><i class="fa fa-edit"></i></button>';

                  <?php
                }
                ?>

                return element;
              }},
              // { "data" : "acc_name" ,render:function( data, type, row ){

              //   var element = '';

              //   element += '<a target="framename" href="<?php echo site_url('panda_gr/gr_child?trans=B2WGR21080004');?>&loc=HQ&accpt_gr_status=NEW">B2WGR21080004' ;

              //   return element;
              // }},
              { "data" : "supplier_name" },
              // { "data" : "supplier_code" },
              { "data" : "b2b_item_code" },
              { "data" : "b2b_barcode" },
              // { "data" : "b2b_article_no" },
              { "data" : "b2b_item_description" },
              { "data" : "b2b_um" },
              { "data" : "supplier_item_code" },
              // { "data" : "supplier_article_no" },
              { "data" : "supplier_item_description" },
              { "data" : "supplier_um" },
	            { "data" : "validity_start_date" },
              { "data" : "validity_end_date" },
              { "data" : "is_active" ,render:function( data, type, row ){

                var element = '';

                if(data == '1')
                {
                  element = 'Yes';
                }
                else
                {
                  element = 'No';
                }

                return element;
              }},
              { "data" : "import_at" },
              { "data" : "updated_at" ,render:function( data, type, row ){

                var element = '';

                if(data == '0000-00-00 00:00:00')
                {
                  element = '';
                }
                else
                {
                  element = data;
                }

                return element;
              }},
              { "data" : "user_name" },
            ],
      dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
      // buttons: [
      //   { extend: 'excelHtml5',
      //     exportOptions: {columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16]}},

      //   { extend: 'csvHtml5',  
      //     exportOptions: {columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16]}},
      //     ],
      // "pagingType": "simple",
      "fnCreatedRow": function( nRow, aData, iDataIndex ) {
        //$(nRow).attr('final_amount', aData['final_amount']);
        if(aData['is_active'] == '1')
        {
          $(nRow).find('td:eq(6)').css({"background-color":"#80ffaa","color":"black"});
          $(nRow).find('td:eq(7)').css({"background-color":"#80ffaa","color":"black"});
          $(nRow).find('td:eq(8)').css({"background-color":"#80ffaa","color":"black"});
          $(nRow).find('td:eq(9)').css({"background-color":"#80ffaa","color":"black"});
  	      $(nRow).find('td:eq(10)').css({"background-color":"#80ffaa","color":"black"});
        }
        else
        {
          //ff8080 //ffff66
          $(nRow).find('td:eq(6)').css({"background-color":"#ff8080","color":"black"});
          $(nRow).find('td:eq(7)').css({"background-color":"#ff8080","color":"black"});
          $(nRow).find('td:eq(8)').css({"background-color":"#ff8080","color":"black"});
          $(nRow).find('td:eq(9)').css({"background-color":"#ff8080","color":"black"});
          $(nRow).find('td:eq(10)').css({"background-color":"#ff8080","color":"black"});
        }
	      //$(nRow).find('td:eq(11)').css({"background-color":"#80ffaa","color":"black"});

      },
      "initComplete": function( settings, json ) {
        setTimeout(function(){
          interval();
        },300);
      }
    });//close datatable
  }

  main_table(filter_retailer,filter_supplier);

  $(document).on('click','#search',function(){

    var filter_retailer = $('#filter_retailer').val();
    var filter_supplier = $('#filter_supplier').val();

    $('.pill_button').html(filter_retailer);

    $('.filter2').show();
    $('.filter2').html(filter_supplier);
    
    main_table(filter_retailer,filter_supplier);
  });//close

  $(document).on('click','#reset',function(){

    var reset_retailer = $('#filter_retailer').val('<?php echo $customer_guid?>').trigger('change');
    var reset_supplier = $('#filter_supplier').val('').trigger('change');

    main_table(filter_retailer,filter_supplier);

  });//close modal create

  $(document).on('click','#edit_btn',function(){

    var guid = $(this).attr('guid');
    var supplier_itemmaster_guid = $(this).attr('supplier_itemmaster_guid');
    var supplier_guid = $(this).attr('supplier_guid');
    var b2b_item_description = $(this).attr('b2b_item_description');
    var b2b_um = $(this).attr('b2b_um');
    var b2b_item_code = $(this).attr('b2b_item_code');
    var b2b_article_no = $(this).attr('b2b_article_no');
    var b2b_barcode = $(this).attr('b2b_barcode');

    var supplier_item_code = $(this).attr('supplier_item_code');
    var supplier_article_no = $(this).attr('supplier_article_no');
    var supplier_item_description = $(this).attr('supplier_item_description');
    var supplier_um = $(this).attr('supplier_um');
    var start_date = $(this).attr('validity_start_date');
    var end_date = $(this).attr('validity_end_date');

    if(supplier_item_code == 'null')
    {
      supplier_item_code = '';
    }

    if(supplier_article_no == 'null')
    {
      supplier_article_no = '';
    }

    if(supplier_item_description == 'null')
    {
      supplier_item_description = '';
    }

    if(supplier_um == 'null')
    {
      supplier_um = '';
    }

    if(start_date == 'null')
    {
      start_date = '';
    }
    
    if(end_date == 'null')
    {
      end_date = '';
    }

    var modal = $("#large-modal").modal();

    modal.find('.modal-title').html('Edit Item Information : <b>BARCODE - '+b2b_barcode+'</b>');

    methodd = '';

    methodd += '<input type="hidden" class="form-control input-sm" id="guid" value="'+guid+'"/>';

    methodd += '<input type="hidden" class="form-control input-sm" id="supplier_itemmaster_guid" value="'+supplier_itemmaster_guid+'"/>';

    methodd += '<input type="hidden" class="form-control input-sm" id="supplier_guid" value="'+supplier_guid+'"/>';

    methodd += '<div class="row">';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-6" >';

    methodd += '<div class="form-group"><label class="title_css">B2B Information</label></div>';

    methodd += '<div class="form-group"><label>B2B Item Code</label><input type="text" class="form-control input-sm" id="b2b_item_code" name="b2b_item_code" autocomplete="off" value="'+b2b_item_code+'" readonly/></div>';

    // methodd += '<div class="form-group"><label>B2B Artical No.</label><input type="text" class="form-control input-sm" id="b2b_article_no" name="b2b_article_no" autocomplete="off" value="'+b2b_article_no+'" readonly/></div>';

    methodd += '<div class="form-group"><label>B2B Item Description</label><input type="text" class="form-control input-sm" id="b2b_item_description" name="b2b_item_description" autocomplete="off" value="'+b2b_item_description+'" readonly/></div>';

    methodd += '<div class="form-group"><label>B2B UM</label><input type="text" class="form-control input-sm" id="b2b_um" name="b2b_um" autocomplete="off" value="'+b2b_um+'" readonly/></div>';

    methodd +='</div>';

    methodd += '<div class="col-md-6 line_css" >';

    methodd += '<div class="form-group"><label class="title_css">Supplier Information</label></div>';

    methodd += '<div class="form-group"><label>Supplier Item Code</label><input type="text" class="form-control input-sm" id="item_code" name="item_code" autocomplete="off" value="'+supplier_item_code+'"/></div>';

    // methodd += '<div class="form-group"> <label>Supplier Article No.</label> <input type="text" class="form-control input-sm" id="item_article_no" name="item_article_no" autocomplete="off" value="'+supplier_article_no+'"/> </div>';
    
    methodd += '<div class="form-group"><label>Supplier Item Description</label><input type="text" class="form-control input-sm" id="item_description" name="item_description" autocomplete="off" value="'+supplier_item_description+'"/></div>';

    methodd += '<div class="form-group"><label>Supplier UM</label><input type="text" class="form-control input-sm" id="item_um" name="item_um" autocomplete="off" value="'+supplier_um+'"/></div>';

    methodd += '<div class="form-group"><label>Start Date</label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="start_date" id="start_date" type="text" class="datepicker form-control input-sm" autocomplete="off"></div></div>';

    methodd += '<div class="form-group"><label>End Date</label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="end_date" id="end_date" type="text" class="datepicker form-control input-sm" autocomplete="off"></div></div>';

    methodd +='</div>';

    methodd += '</div>';
    
    methodd += '<div class="clearfix"></div><br>';

    methodd +='</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="submit_button" class="btn btn-primary"> Update </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('.datepicker').datepicker({
        forceParse: false,
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
      });
      $('#start_date').val(start_date);
      $('#end_date').val(end_date);
    },300);

  });//close modal create

  $(document).on('click','#submit_button',function(){
    var guid = $('#guid').val();
    var supplier_guid = $('#supplier_guid').val();
    var supplier_itemmaster_guid = $('#supplier_itemmaster_guid').val();
    var item_code = $('#item_code').val();
    var item_article_no = $('#item_article_no').val();
    var item_description = $('#item_description').val();
    var item_um = $('#item_um').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
	//alert(end_date); die;
    if((guid == '') || (guid == null) || (guid == 'null'))
    {
      alert('Invalid Get GUID.');
      return;
    }

    if((supplier_guid == '') || (supplier_guid == null) || (supplier_guid == 'null'))
    {
      alert('Invalid Get Supplier GUID.');
      return;
    }

    if((item_code == '') || (item_code == null) || (item_code == 'null'))
    {
      alert('Please insert Supplier Item Code.');
      return;
    }

    if((item_description == '') || (item_description == null) || (item_description == 'null'))
    {
      alert('Please insert Supplier Item Description.');
      return;
    }

    if((item_um == '') || (item_um == null) || (item_um == 'null'))
    {
      alert('Please insert Supplier UM.');
      return;
    }

    if(end_date < start_date)
    {
      alert('Start date cannot smaller than End date.');
      return;
    }

    confirmation_modal('Are you sure update Itemmaster Information?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Edi_info/edit_itemmaster') ?>",
        method:"POST",
        data:{guid:guid,supplier_guid:supplier_guid,supplier_itemmaster_guid:supplier_itemmaster_guid,item_code:item_code,item_article_no:item_article_no,item_um:item_um,item_description:item_description,start_date:start_date,end_date:end_date},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            $('#large-modal').modal('hide');
            alert(json.msg);
            setTimeout(function() {
              $('#itemmaster_table').DataTable().ajax.reload(null, false);
            }, 300); 
          }//close else
        }//close success
      });//close ajax 
    });//close confirmation

  });//close submit process

});
</script>

