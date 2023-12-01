<body class="hold-transition skin-blue sidebar-mini">
 <div class="content-wrapper">

<section class="content-header">
  <h1>
    Report Type List
    <!-- <small>View Your Record</small> -->
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"> Home</a></li>
    <li class="active">Report Type List</li>
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
                    <li><a  data-toggle="modal" data-target="#modal_add"><i class="fa fa-plus"></i>Add</a></li>
                    <li><a id="button_delete" data-toggle="modal" data-target="#modal_delete"><i class="fa fa-minus"></i>Delete</a></li>
                  </ul>
                </div>
              </div>
	        </div>
            <div class="box-body">
              
              <hr>
                  <table class="table table-bordered table-striped dataTable" id="ttable"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                      <thead style="white-space: nowrap;">
                              <tr>
                                <td><input type="checkbox" id="tidsall" onclick="checkedAll ();"></td>
              			<?php foreach ($query->list_fields() as $key) { ?>
                        <th><?php echo $key; ?></th>
                      	<?php } ?>
                              </tr>
                      </thead>
                      <tbody> 

                              <?php foreach ($query->result() as $key1) { ?>
                              <tr>
                                <td><input class="tids" type="checkbox" form="form_action" name="tids[]" value="<?php echo $key1->Code ?>" ></td>
              			<?php foreach ($query->list_fields() as $key) { ?>
                        <td>


                          <?php 

                          if ($key == 'Code') {
                            echo '<a class="details_info">'.$key1->$key.'</a>';
                          } else {
                            echo $key1->$key;
                          } ?>


                          </td>
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

<div class="modal fade" id="modal_add" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="<?php echo site_url('report_type/add')?>" method="POST" id="form" class="form-horizontal" enctype="multipart/form-data" >
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title">Add</h3>
          </div>
          <div class="modal-body form">
            
            <div class="form-body">
              	
              		<div class="row">
              			<div class="col-sm-12">
                      <div class="form-group">
                        <label for="type" class="col-sm-3 control-label">Type</label>

                        <div class="col-sm-9">
                          <select class="form-control" id="type" name="type">
                            <?php foreach ($report_type as $key) { ?>
                              <option value="<?php echo $key ?>"><?php echo $key ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
              				<div class="form-group">
			                  <label for="code" class="col-sm-3 control-label">Code</label>

			                  <div class="col-sm-9">
			                    <input type="text" class="form-control" id="code" name="code" >
			                  </div>
			                </div>
			                <div class="form-group">
			                  <label for="description" class="col-sm-3 control-label">Description</label>

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

<div class="modal fade" id="modal_delete" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="<?php echo site_url('report_type/action')?>" method="POST" id="form_action" class="form-horizontal" enctype="multipart/form-data" >
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title">Add</h3>
          </div>
          <div class="modal-body form">
            
            <div class="form-body">
                
                  <div class="row">
                    <div class="col-sm-12">

                      <p>Are you sure you want to delete selected record?</p>

                  </div>
                </div>
                

            </div>
            
          </div>
          <div class="modal-footer">
              <button type="submit" name="submit" value="delete" class="btn btn-sm btn-warning">Delete</button>
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
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
                /*exportOptions: {
                    columns: [ 1,2,3 ]
                }*/
            },
            {
                extend: 'excelHtml5',
                /*exportOptions: {
                    columns: [ 1,2,3 ]
                }*/
            },
            {
                extend: 'pdfHtml5',
                /*exportOptions: {
                    columns: [ 1,2,3 ]
                }*/
            },
            {
                extend: 'print',
                /*exportOptions: {
                    columns: [ 1,2,3 ]
                }*/
            },
            //'colvis'
        ]
    })
  })
</script>

<script type="text/javascript">
  
var checked=false;
function checkedAll () {
    var aa =  document.getElementsByName("tids[]");
    checked = document.getElementById('tidsall').checked;
     
    for (var i =0; i < aa.length; i++) 
    {
        aa[i].checked = checked;
    }
 }

$('#button_delete').click(function() {
  checked = $("input[class=tids]:checked").length;

  if(!checked) {
    alert("You must check at least one record.");
    return false;
  }

});

</script>