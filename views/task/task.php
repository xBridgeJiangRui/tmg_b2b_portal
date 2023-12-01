<body class="hold-transition skin-blue sidebar-mini">
 <div class="content-wrapper">

<section class="content-header">
  <h1>
    Task List
    <!-- <small>View Your Record</small> -->
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"> Home</a></li>
    <li class="active">Task List</li>
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
                    <li><a  data-toggle="modal" data-target="#excel_update_modal"><i class="fa fa-plus"></i>Add Task</a></li>
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
        <form action="<?php echo site_url('email_task/add_task')?>" method="POST" id="form" class="form-horizontal" enctype="multipart/form-data" >
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title">Add Task</h3>
          </div>
          <div class="modal-body form">
            
            <div class="form-body">
              	
              		<div class="row">
              			<div class="col-sm-12">
              				<div class="form-group">
			                  <label for="code" class="col-sm-3 control-label">Task Code</label>

			                  <div class="col-sm-9">
			                    <input type="text" class="form-control" id="code" name="code" >
			                  </div>
			                </div>
			                <div class="form-group">
			                  <label for="description" class="col-sm-3 control-label">Task Description</label>

			                  <div class="col-sm-9">
			                    <input type="text" class="form-control" id="description" name="description" >
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

    // Setup - add a text input to each footer cell
    /*$('#ttable thead tr').clone(true).appendTo( '#ttable thead' );
    $('#ttable thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );*/

    var table = $('#ttable').DataTable({
      "columnDefs": [{ "orderable": false, "targets": 0 }],
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
