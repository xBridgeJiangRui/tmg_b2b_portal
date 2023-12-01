<style type="text/css">
  #acc_branch{
    height: 250px;
    overflow-y: scroll;

  }
  #acc_branch_group,#acc_concept{
    height: 250px;
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
     echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; 
  }
  ?>
<?php // echo var_dump($_SESSION); ?>
  <div class="row">
    <!-- from panel -->
    <div class="col-md-6">
    	<select name=from id="from" class=form-control  onchange="ahsheng()"  >
    		<?php foreach($from_customer->result() as $row){ ?>
    		<option value="<?php echo $row->acc_guid ?>" <?php if($_REQUEST['from'] == $row->acc_guid) { echo 'selected'; } ?> > <?php echo $row->acc_name ?></option>
    	<?php } ?>
    	</select>
    	<br>
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">From Customer</h3>
          <div class="box-tools pull-right"> 

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
                        <th>Action</th>
                        <!-- <th>Isactive</th> -->
                        <th>Supplier Name</th>
                        <!-- <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    	<?php foreach($from_customer_supplier->result() as $row) { ?> 
                    	<tr>
							<td></td>
							<td><?php echo $row->supplier_name ?></td>
						</tr>
						<?php } ?>               
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
    	<select name=to id="to" class=form-control  onchange="ahsheng()">	
    		<option value=""></option>
    		<?php foreach($to_customer->result() as $row){ 
    			?>
    		<option value="<?php echo $row->acc_guid ?>"  <?php if($_REQUEST['to'] == $row->acc_guid) { echo 'selected'; } ?>> <?php echo $row->acc_name ?></option>
    	<?php } ?>
    	</select>
    	<br>
      <div class="box box-success">
        <div class="box-header with-border">
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
        </div>
        <!-- /.box-header -->

        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
          <form id="formACCconcept" method="post" action="<?php echo site_url('supplier_setup/check')?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                  <table id="to_cus" class="table table-bordered table-hover" width="100%" cellspacing="0">
                 
                    <thead>
                    <tr>
                        <th>Action</th>
                        <!-- <th>Isactive</th> -->
                        <th>Supplier Name</th> 
                        <!-- <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th> -->
                    </tr>
                    </thead>
                    <tbody>
					    <?php foreach($to_customer_supplier->result() as $row) { ?> 
                    	<tr>

							<td>
								<?php if($row->to_supplier_guid != 'empty') { ?>
								<button title="Add" onclick="transfer_supplier_user_based_on_company()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#transfer_supplier_user_based_on_company"
                         		data-table="<?php echo 'set_user' ?>"
                         		data-mode="<?php echo 'Add' ?>"
                         		data-guid="<?php echo $row->supplier_guid ?>"
                         		data-sup_name="<?php echo $row->supplier_name ?>" 
                         		data-from_acc_guid="<?php echo $_REQUEST['from'] ?>" 
                         		data-to_acc_guid="<?php echo $_REQUEST['to'] ?>" >
                         		<i class="glyphicon glyphicon-plus"></i></button> 
                            <?php }else { ?>

                <a type="button" title="Add User" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#add_modal" href="<?php echo site_url("Module_setup/view_via_user?supplier_guid=".$row->supplier_guid."&from_acc_guid=".$_REQUEST['from']."&to_acc_guid=".$_REQUEST['to']) ?>" ?><i class="glyphicon glyphicon-user" ></i> </a>
                
                            <?php } ?>
							</td>

							<td><?php echo $row->supplier_name ?></td>
						</tr>
						<?php } ?>        
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
  </script> 