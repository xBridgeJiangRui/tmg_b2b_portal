<body class="hold-transition skin-blue sidebar-mini">
 <div class="content-wrapper">

<section class="content-header">
  <h1>
    Task Relation
    <!-- <small>View Your Record</small> -->
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"> Home</a></li>
    <li class="active">Task Relation</li>
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
        <form action="<?php echo site_url('task/add_relation')?>" method="POST" id="form" class="form-horizontal" enctype="multipart/form-data" >
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title">Add Task</h3>
          </div>
          <div class="modal-body form">
            
            <div class="form-body">
              	
              		<div class="row">
              			<div class="col-sm-12">
              				<div class="form-group">
			                  <label for="customer_guid" class="col-sm-3 control-label">Customer</label>

			                  <div class="col-sm-9">
                          <div class="input-group">
                                <span class="input-group-addon">
                                  <label for="customer_all">All</label>
                                  <input type="checkbox" id="customer_all" name="customer_all" title="all">
                                </span>
                            <select class="select2 form-control" style="width: 100%" id="customer_guid" name="customer_guid[]" multiple="multiple">
                            <?php foreach ($customer->result() as $key) { ?>
                              <option value="<?php echo $key->acc_guid ?>"><?php echo $key->acc_name ?></option>
                            <?php } ?>
                            
                          </select>
                          </div>
			                    
			                  </div>
			                </div>

			                <div class="form-group">
			                  <label for="task_code" class="col-sm-3 control-label">Task Code</label>

			                  <div class="col-sm-9">
			                    <select class="form-control" id="task_code" name="task_code">
			                    	<?php foreach ($b2b_backend_process->result() as $key) { ?>
			                    		<option value="<?php echo $key->code ?>"><?php echo $key->description ?></option>
			                    	<?php } ?>
			                    </select>
			                  </div>
			                </div>

			                <div class="form-group">
			                  <label for="run_time" class="col-sm-3 control-label">Run Time</label>

			                  <div class="col-sm-9">
			                    <input type="number" class="form-control" id="run_time" name="run_time">
			                  </div>
			                </div>

			                <div class="form-group">
			                  <label for="run_time_type" class="col-sm-3 control-label">Run Type</label>

			                  <div class="col-sm-9">
			                    <select class="form-control" id="run_time_type" name="run_time_type">
			                    	<?php foreach ($interval as $key) { ?>
			                    		<option value="<?php echo $key ?>"><?php echo $key ?></option>
			                    	<?php } ?>
			                    </select>
			                  </div>
			                </div>

			                <div class="form-group">
			                  <label for="seq" class="col-sm-3 control-label">Seq</label>

			                  <div class="col-sm-9">
			                    <input type="number" class="form-control" id="seq" name="seq">
			                  </div>
			                </div>

			                <div class="form-group">
			                  <label for="isactive" class="col-sm-3 control-label">Active</label>

			                  <div class="col-sm-9">
			                    <input type="checkbox" id="isactive" name="isactive">
			                  </div>
			                </div>

			                <div class="form-group">
			                  <label for="next_run_date" class="col-sm-3 control-label">Next Run Date</label>

			                  <div class="col-sm-9">
			                    <input type="date" class="form-control" id="next_run_date" name="next_run_date">
			                  </div>
			                </div>

			                <div class="form-group">
			                  <label for="next_run_time" class="col-sm-3 control-label">Next Run Time</label>

			                  <div class="col-sm-9">
			                    <input type="time" class="form-control" id="next_run_time" name="next_run_time">
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

<div class="modal fade" id="more_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="<?php echo site_url('training/action')?>" method="POST" id="form" class="form-horizontal" enctype="multipart/form-data" >
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title"></h3>
          </div>

            


          <div class="modal-body form">
            
            <div class="form-body">
            </div>
            
          </div>
          <div class="modal-footer">
            <select class="" name="action">
              
              <option value="Registered">Registered</option>
              <option value="Generated Invoice">Generated Invoice</option>
              <option value="Attended">Attended</option>
              <option value="Cancel">Cancel</option>
              <option value="Postponed">Postponed</option>
              <option value="Paid">Paid</option>
              <option value="Delete">Delete</option>

            </select>
              <button type="submit" class="btn btn-sm btn-primary">Change status</button>
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
              <!-- <button type="button" class="btn btn-sm btn-default" onClick="window.location.reload();" data-dismiss="modal">Cancel</button> -->
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
                exportOptions: {
                    columns: [ 1,2,3 ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 1,2,3 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 1,2,3 ]
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 1,2,3 ]
                }
            },
            //'colvis'
        ]
    })
  })
</script>
