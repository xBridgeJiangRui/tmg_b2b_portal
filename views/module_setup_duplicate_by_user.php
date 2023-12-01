<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
  <?php
  if($this->session->userdata('message'))
  {
     echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; 
  }
  ?>
<?php // echo var_dump($_SESSION); ?>
  <div class="row">
    <!-- from panel -->
    <div class="col-md-6">
    	<select name=from id="from" class=form-control>
        <option value=""></option>
    		<?php foreach($from_customer->result() as $row){ ?>
    		<option value="<?php echo $row->acc_guid ?>" <?php if($_REQUEST['from'] == $row->acc_guid) { echo 'selected'; } ?> > <?php echo $row->acc_name ?></option>
    	<?php } ?>
    	</select>
    	<br>
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">From Customer</h3>
          <div class="box-tools pull-right"> 

            <button class="btn btn-xs btn-success" id="duplicate_button">Duplicate</button>

            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
         
          </div>
        </div>
        <!-- /.box-header -->

        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
          <form id="formACCconcept" method="post" action="<?php echo site_url('supplier_setup/check')?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                  <table id="from_cus" class="table table-bordered table-hover" width="100%" cellspacing="0">
                 
                    <thead>
                    <tr>
                        <!-- <th>Isactive</th> -->
                        <th>Supplier Name</th>
                        <th><input type="checkbox" class="form-checkbox" id="check_all"/></th>
                        <!-- <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
                    </tr>
                    </thead>
                    <tbody>             
                    </tbody>
                  </table>
                </form>
              </div>  
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /info div -->
    </div>   
    <!-- from panel end-->
    <!-- to panel -->
    <div class="col-md-6">
    	<select name=to id="to" class=form-control>	
    		<option value=""></option>
    		<?php foreach($to_customer->result() as $row){ 
    			?>
    		<option value="<?php echo $row->acc_guid ?>"  <?php if($_REQUEST['to'] == $row->acc_guid) { echo 'selected'; } ?>> <?php echo $row->acc_name ?></option>
    	<?php } ?>
    	</select>
    	<br>
      <div class="box box-warning">
<!--         <div class="box-header with-border">
          <h3 class="box-title">To </h3>
          <div class="box-tools pull-right">  
          	<?php if($_REQUEST['filter'] == 'false') { ?>
          		<a href="<?php echo site_url('module_setup/duplicate_user') ?>?from=<?php echo $_REQUEST['from'] ?>&to=<?php echo $_REQUEST['to'] ?>&filter=true" 
                class="btn btn-xs btn-danger" 
                ><i class="fa fa-filter" aria-hidden="true"></i>Filter Mode : OFF</a> 
             <?php } else { ?>
             <a href="<?php echo site_url('module_setup/duplicate_user') ?>?from=<?php echo $_REQUEST['from'] ?>&to=<?php echo $_REQUEST['to'] ?>&filter=false" 
                class="btn btn-xs btn-success" 
                ><i class="fa fa-filter" aria-hidden="true"></i>Filter Mode : ON</a>
             <?php } ?> 
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
         
          </div>
        </div> -->
        <!-- /.box-header -->

        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
          <form id="formACCconcept" method="post" action="<?php echo site_url('supplier_setup/check')?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                  <table id="to_cus" class="table table-bordered table-hover" width="100%" cellspacing="0">
                 
                    <thead>
                    <tr>
                        <!-- <th>Isactive</th> -->
                        <th>Supplier Name</th> 
<!--                         <th>Created At</th>
                        <th>Created By</th> -->
                        <th>Updated At</th>
                        <!-- <th>Updated By</th> -->
                    </tr>
                    </thead>
                    <tbody>       
                    </tbody>
                  </table>
                </form>
              </div>  
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /info div -->
    </div>   
    <!-- to panel end -->
  </div> <!-- row 1 end -->
</div>
</div>
<!--@@@@@@@@@@@@@2 modalv@@@@@@@@@@@@@@@@@@@@@@@@ -->
<!--  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ module group modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="transfer_supplier_user_based_on_company" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"> 
                <h3 class="modal-title">Form</h3>
            </div>
            <div class="modal-body form">
            	<div class="modal-body">
                	<h4 class="confirmation_text" style="text-align: center"></h4>
            	</div>
                <form action="<?php echo site_url('module_setup/transfer_supplier')?>" method="POST" id="form" class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group"> 
                             <div class="col-md-9">
                                <input type="hidden" name="mode" class="form-control" required maxlength="60">
                                <input type="hidden" name="guid" class="form-control" required maxlength="60">
                                <input type="hidden" name="to_acc_guid" class="form-control" required maxlength="60">
                                <input type="hidden" name="sup_name" class="form-control"  required maxlength="60">
                                <input type="hidden" name="from_acc_guid" class="form-control"  required maxlength="60">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --> 
<!-- load modal header for cc card -->
<div  class="modal fade" id="add_modal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog  modal-lg">

     <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" >&times;</button>
          <h4 class="modal-title">Loading</h4>
        </div>
        <div class="modal-body">
              
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="">Close</button>
        </div>
      </div>


  </div>
</div>
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ End module group modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

<!-- End confirm modal modal -->
<script> 
 	function delete_modal(delete_url)
  	{
   		$('#delete').on('show.bs.modal', function (event) {
   		var button = $(event.relatedTarget) 

   		var modal = $(this)
   		modal.find('.modal_detail').text('Confirm Delete <<' + button.data('report') + '>>?')
   		document.getElementById('url').setAttribute("href" , delete_url );
    });
  }
   
    function ahsheng() 
    {
      location.href = '<?php echo site_url('module_setup/duplicate_user') ?>?from='+$('#from').val()+'&to='+$('#to').val()+'&filter=<?php echo $_REQUEST["filter"] ?>';
    }

    function transfer_supplier_user_based_on_company()
  {
    $('#transfer_supplier_user_based_on_company').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)  
      var modal = $(this)
      modal.find('.modal-title').text('Duplicate')
      modal.find('[name="mode"]').val(button.data('mode'))
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="sup_name"]').val(button.data('sup_name'))
      modal.find('[name="to_acc_guid"]').val(button.data('to_acc_guid'))
      modal.find('[name="from_acc_guid"]').val(button.data('from_acc_guid'))
      modal.find('.confirmation_text').text('Are you sure you want to duplicate all user from ' +  button.data('sup_name') +' ?')
    });
  }


  function transfer_user_via_selected()
  {
    $('#transfer_user_via_selected').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)  
      var modal = $(this)
      modal.find('.modal-title').text('Duplicate')
      modal.find('[name="mode"]').val(button.data('mode'))
      modal.find('[name="guid"]').val(button.data('guid'))
      modal.find('[name="sup_name"]').val(button.data('sup_name'))
      modal.find('[name="to_acc_guid"]').val(button.data('to_acc_guid'))
      modal.find('[name="from_acc_guid"]').val(button.data('from_acc_guid'))
      modal.find('.confirmation_text').text('Are you sure you want to duplicate all user from ' +  button.data('sup_name') +' ?')
    });
  }

$(function () {
    $("#from_cus").DataTable();
  });

$(function () {
  $("#to_cus").DataTable();
});



$(document).ready(function(){

  setTimeout(function(){
    $('#from').trigger('change');
  },500);
  

  cus_table = function(from_acc_guid, to_acc_guid)
  {

    $.ajax({
          url:"<?php echo site_url('Module_setup/cus_table');?>",
          method:"POST",
          data: {from_acc_guid:from_acc_guid,to_acc_guid:to_acc_guid},
          beforeSend:function(){
            // $('.btn').button('loading');
            $('.dataTables_processing', $('#from_cus').closest('.dataTables_wrapper')).show();
            $('.dataTables_processing', $('#to_cus').closest('.dataTables_wrapper')).show();

          },
          complete:function(){
            $('.btn').button('reset');
          },
          success:function(data)
          {
            json = JSON.parse(data);

            //clear datatables for new ajax 
            if($.fn.DataTable.isDataTable('#from_cus')){
              $('#from_cus').DataTable().destroy();
            }

            if($.fn.DataTable.isDataTable('#to_cus')){
              $('#to_cus').DataTable().destroy();
            }

            $('#from_cus').DataTable({
              "columnDefs": [{ "orderable": false, "targets": 1 }],
              'processing'  : true,
              'paging'      : true,
              'lengthChange': true,
              'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
              'searching'   : true,
              'ordering'    : true,
              'order'       : [ [1 , 'asc'] ],
              'autoWidth'   : false,
              // "sScrollY": "12vh", 
              "sScrollX": "100%", 
              // "sScrollXInner": "100%", 
              "bScrollCollapse": true,
              data: json['from_cus'],
              columns : [
                        // {"data": "branch_guid"}, 
                        // {"data": "module_group_guid"}, 
                        // {"data": "user_group_guid"}, 
                        // {"data": "user_guid"}, 
                        // {"data": "supplier_guid"},
                        {"data": "user_id"},
                        // {"data": "isactive"},
                        {"data": "exist_status",render: function ( data, type, row ) {
                          if (data == 0) { ischecked = '<input type="checkbox" class="form-checkbox" acc_guid = '+row['acc_guid']+' branch_guid = '+row['branch_guid']+' module_group_guid = '+row['module_group_guid']+' user_group_guid = '+row['user_group_guid']+' user_guid = '+row['user_guid']+' supplier_guid = '+row['supplier_guid']+' isactive = '+row['isactive']+' user_id = '+row['user_id']+' user_password = '+row['user_password']+' user_name = '+row['user_name']+' limited_location = '+row['limited_location']+'  >' } else { ischecked = '<span class="label label-success" style="font-size:12px;">Existed</span>' }
                          return ischecked;
                        }},  
                        // {"data": "isactive"}, 
                        // {"data": "user_password"}, 
                        // {"data": "user_name"}, 
                        // {"data": "created_at"}, 
                        // {"data": "created_by"}, 
                        // {"data": "updated_at"}, 
                        // {"data": "updated_by"}, 
                        // {"data": "limited_location"}
              ],
              //'fixedHeader' : false,
              dom: '<"row"<"col-sm-2" l> <"col-sm-5"><"col-sm-5" f> >rtip',
              "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                // $(nRow).attr('import_guid', aData['import_guid']);
              },
              "initComplete": function( settings, json ) {
              }
            });//close datatable



            $('#to_cus').DataTable({
              "columnDefs": [{"targets": 1 ,"visible": false}],
              'processing'  : true,
              'paging'      : true,
              'lengthChange': true,
              'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
              'searching'   : true,
              'ordering'    : true,
              'order'       : [ [1 , 'desc'] ],
              'autoWidth'   : false,
              // "sScrollY": "12vh", 
              "sScrollX": "100%", 
              // "sScrollXInner": "100%", 
              "bScrollCollapse": true,
              data: json['to_cus'],
              columns : [
                        // {"data": "branch_guid"}, 
                        // {"data": "module_group_guid"}, 
                        // {"data": "user_group_guid"}, 
                        // {"data": "user_guid"}, 
                        // {"data": "supplier_guid"},
                        {"data": "user_id"},
                        // {"data": "isactive"}, 
                        // {"data": "isactive"}, 
                        // {"data": "user_password"}, 
                        // {"data": "user_name"}, 
                        // {"data": "created_at"}, 
                        // {"data": "created_by"}, 
                        {"data": "updated_at"}, 
                        // {"data": "updated_by"}, 
                        // {"data": "limited_location"}
              ],
              //'fixedHeader' : false,
              dom: '<"row"<"col-sm-2" l> <"col-sm-5"><"col-sm-5" f> >rtip',
              "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                // $(nRow).attr('import_guid', aData['import_guid']);
              },
              "initComplete": function( settings, json ) {
              }
            });//close datatable




          }//close success
        });//close ajax

  }//close from_cus_table function

  $(document).on('change','#from',function(){

    var from_acc_guid = $('#from').val();
    var to_acc_guid = $('#to').val();

    cus_table(from_acc_guid, to_acc_guid);

  });//close #from



  $(document).on('change','#to',function(){

    var from_acc_guid = $('#from').val();
    var to_acc_guid = $('#to').val();

    cus_table(from_acc_guid, to_acc_guid);

  });//close #from



  $(document).on('change','#check_all',function(){
    
    var table = $('#from_cus').DataTable();

    if($(this).is(':checked'))
    {
      table.rows('tr').nodes().to$().find('input[type="checkbox"]').prop('checked',true);
    }
    else
    {
      table.rows('tr').nodes().to$().find('input[type="checkbox"]').prop('checked',false);
    }//close else

    

    // table.rows('tr').nodes().to$().find('input[type="checkbox"]').each(function(){
    //   $(this).prop('checked',true);
    // });//close small loop


  });



  $(document).on('click','#duplicate_button',function(){

    var xstatus = $('#from_cus').DataTable().rows().data().any();
    if((xstatus == false) || (xstatus != true)){
      return;
    }//close list table

    var from_acc_guid = $('#from').val();
    var to_acc_guid = $('#to').val();

    if((to_acc_guid == '') || (to_acc_guid == null))
    {
      alertmodal('Please select a retailer to perform action.');
      return;
    }


    if(from_acc_guid == to_acc_guid)
    {
      alertmodal('Please select a different retailer to perform action.');
      return;
    }

    var details = [];

    var table = $('#from_cus').DataTable();

    table.rows('tr').nodes().to$().find('input[type="checkbox"]:checked').each(function(){

      details.push({"module_group_guid":$(this).attr('module_group_guid'),"user_group_guid":$(this).attr('user_group_guid'),"user_guid":$(this).attr('user_guid'),"supplier_guid":$(this).attr('supplier_guid'),"isactive":$(this).attr('isactive'),"user_id":$(this).attr('user_id'),"user_password":$(this).attr('user_password'),"user_name":$(this).attr('user_name'),"limited_location":$(this).attr('limited_location')});

    });//close small loop


    if((details == '') || (details == null))
    {
      alertmodal('Please select at least one supplier to proceed.');
      return;
    }


    confirmation_modal('Confirm to duplicate supplier?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){

      $.ajax({
        url:"<?php echo site_url('Module_setup/duplicate_supplier');?>",
        method:"POST",
        data:{details:details,to_acc_guid:to_acc_guid},
        success:function(data)
        {
          json = JSON.parse(data);

          if (json.para1 == '1') {
            informationalertmodal(json.button,json.icons,json.msg,'Error');
            $('.btn').button('reset');
          }//close if
          else
          {  
            informationalertmodal(json.button,json.icons,json.msg,'Information');
            setTimeout(function(){
              cus_table(from_acc_guid, to_acc_guid);
              $('#check_all').prop('checked',false);
            },400);
          }//close else

        }//close success
      });//close ajax

    });

  });//close duplicate_button







});//close document ready


</script> 