<style type="text/css">
  #acc_branch{
    height: auto;
    overflow-y: scroll;

  }
  #acc_branch_group,#acc_concept{
    height: auto;
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
    ?>
    <div class="alert alert-success text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>

  <?php
  if($this->session->userdata('warning'))
  {
    ?>
    <div class="alert alert-danger text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('warning') <> '' ? $this->session->userdata('warning') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>
<?php // echo var_dump($_SESSION); ?>
<div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Report Tools</h3>
          <div class="box-tools pull-right">
          <button title="Subscription" onclick="create_new()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#create_new"  
            data-table="<?php echo 'email_list' ?>"
            data-mode="<?php echo 'create' ?>"
            data-customer_guid = "<?php echo $_SESSION['customer_guid'] ?>"            
            ><i class="glyphicon glyphicon-plus"></i>Create</button>

            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="accconceptCheck">
          <form id="formACCconcept" method="post" action="<?php echo site_url('supplier_setup/check')?>?table=set_supplier&col_guid=supplier_guid&col_check=isactive">
                  <table id="report_tools" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Seq</th>
                        <th>Report Name</th>
                        <th>Report Type</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($report->result() as $row) { ?>
                      <tr>
                        <td> 
                          <button title="Queries" onclick="view_query()" type="button" class="btn btn-xs btn-success" data-toggle="modal" 
                        data-target="#queries"  
                        data-report_guid="<?php echo $row->report_guid ?>"
                        data-query="<?php echo $row->query ?>"
                        ><i class="fa fa-columns"></i>
                          </button>
                          <button title="Detail" onclick="edit_detail()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" 
                        data-target="#edit_detail"  
                        data-report_guid="<?php echo $row->report_guid ?>"
                        data-seq="<?php echo $row->seq ?>"
                        data-report_name="<?php echo $row->report_name ?>"
                        data-report_type="<?php echo $row->report_type ?>"
                        ><i class="fa fa-edit"></i>
                          </button>
                          <button title="Delete" onclick="delete_modal('<?php echo site_url('report_controller/delete_report_guid'); ?>?report_guid=<?php echo $row->report_guid?>')" type="button" class="btn btn-xs btn-danger" 
                          data-toggle="modal"  
                          data-target="#delete" 
                          data-report="<?php echo $row->report_name; ?>" ><i class=" glyphicon glyphicon-trash"></i></button>
                        </td>
                        <td><?php echo $row->seq ?></td>
                        <td><?php echo $row->report_name ?></td>
                        <td><?php echo $row->report_type ?></td>
                      </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </form>
              </div>  
        </div>

      </div>
    </div>
  </div>
   
<!-- nothing ends after -->
</div>
</div>
 
<script>
     function view_query()
  {
    $('#queries').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="report_guid"]').val(button.data('report_guid'))      
      modal.find('[name="query"]').val(button.data('query'))
    });
  }


   function edit_detail()
  {
    $('#edit_detail').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Edit')
      modal.find('[name="report_guid"]').val(button.data('report_guid'))       
      modal.find('[name="seq"]').val(button.data('seq')) 
      modal.find('[name="report_name"]').val(button.data('report_name')) 
      modal.find('[name="report_type"]').val(button.data('report_type')) 
    });
  }


     function create_new()
  {
    $('#create_new').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Add New')
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="mode"]').val(button.data('mode'))
    });
  }

  function delete_modal(delete_url)
  {
    $('#delete').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm Delete <<' + button.data('report') + '>>?')
    document.getElementById('url').setAttribute("href" , delete_url );
    });
  }
 

  </script>