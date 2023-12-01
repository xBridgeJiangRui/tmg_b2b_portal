<body class="hold-transition skin-blue sidebar-mini">
 <div class="content-wrapper">

<section class="content-header">
  <h1>
    Report Type Relation
    <!-- <small>View Your Record</small> -->
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"> Home</a></li>
    <li class="active">Report Type Relation</li>
  </ol>
</section>

<section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
	          <h3 class="box-title">List</h3>
	          <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a  data-toggle="modal" data-target="#excel_update_modal"><i class="fa fa-plus"></i>Add Relation</a></li>
                  </ul>
                </div>
              </div>
	        </div>
            <div class="box-body">
              
              <hr>
                  <table class="table table-bordered table-striped dataTable" id="ttable"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                      <thead style="white-space: nowrap;">
                              <tr>
              			<?php foreach ($query->list_fields() as $key) { ?>
                        <th><?php echo $key; ?></th>
                      	<?php } ?>
                              </tr>
                      </thead>
                      <tbody> 

                              <?php foreach ($query->result() as $key1) { ?>
                              <tr>
              			<?php foreach ($query->list_fields() as $key) { ?>
                        <td><?php echo $key1->$key; ?></td>
                      	<?php } ?>




                              </tr>
                              <?php } ?>


                      </tbody>
                  </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              
            </div>
          </div>
      </div>
    </section>

  </div>
</body>

<div class="modal fade" id="excel_update_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="<?php echo site_url('report_type/add_relation')?>" method="POST" id="form" class="form-horizontal" enctype="multipart/form-data" >
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title">Add</h3>
          </div>
          <div class="modal-body form">
            
            <div class="form-body">
              	
              		<div class="row">
              			<div class="col-sm-12">
              				<div class="form-group">
			                  <label for="customer_guid" class="col-sm-3 control-label">Customer</label>

			                  <div class="col-sm-9">
                          <select class="select2 form-control" style="width: 100%" id="customer_guid" name="customer_guid[]" multiple="multiple">
                            <?php foreach ($customer->result() as $key) { ?>
                              <option value="<?php echo $key->acc_guid ?>"><?php echo $key->acc_name ?></option>
                            <?php } ?>
                            
                          </select>
			                    
			                  </div>
			                </div>

                      <div class="form-group">
                        <label for="user_guid" class="col-sm-3 control-label">User</label>

                        <div class="col-sm-9">
                          <select class="select2 form-control" style="width: 100%" id="user_guid" name="user_guid[]" multiple="multiple">
                            <?php foreach ($set_user->result() as $key) { ?>
                              <option value="<?php echo $key->user_guid ?>"><?php echo $key->user_name ?></option>
                            <?php } ?>
                            
                          </select>
                          
                        </div>
                      </div>

			                <div class="form-group">
			                  <label for="report_code" class="col-sm-3 control-label">Report Code</label>

			                  <div class="col-sm-9">
			                    <select class="form-control" id="report_code" name="report_code">
			                    	<?php foreach ($report_type->result() as $key) { ?>
			                    		<option value="<?php echo $key->code ?>"><?php echo $key->code ?> - <?php echo $key->description ?></option>
			                    	<?php } ?>
			                    </select>
			                  </div>
			                </div>

			                <div class="form-group">
			                  <label for="isactive" class="col-sm-3 control-label">Active</label>

			                  <div class="col-sm-9">
			                    <input type="checkbox" id="isactive" name="isactive">
			                  </div>
			                </div>

            			</div>
            		</div>
              	

            </div>
            
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-sm btn-primary">Save</button>
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          </div>   
        </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {

    $("#customer_all").click(function(){
      if($(this).prop("checked") == true){
          $("#customer_guid").attr("disabled" , 'true')
      }
      else if($(this).prop("checked") == false){
          $("#customer_guid").removeAttr("disabled");
      }
    })

    var table = $('#ttable').DataTable({
      //"columnDefs": [{ "orderable": false, "targets": 0 }],
      'paging'      : true,
      'lengthChange': true,
      'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       : [ [1 , 'asc'] ],
      'info'        : true,
      //'scrollX'     : true,
      //'orderCellsTop': true,
      //'fixedHeader': true,
      dom: 'lBfrtip',

       buttons: [
            {
                extend: 'copyHtml5',
                /*exportOptions: {
                    columns: [ 1,2,3 ]
                }
*/            },
            {
                extend: 'excelHtml5',
                /*exportOptions: {
                    columns: [ 1,2,3 ]
                }
*/            },
            {
                extend: 'pdfHtml5',
                /*exportOptions: {
                    columns: [ 1,2,3 ]
                }
*/            },
            {
                extend: 'print',
                /*exportOptions: {
                    columns: [ 1,2,3 ]
                }
*/            },
            //'colvis'
        ]
    })
  })
</script>
