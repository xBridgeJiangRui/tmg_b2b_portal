<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>

<!-- info div -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Account concept</h3>
          <div class="text-center">
            <span style="font-size: 18px" class="label label-success"><?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span>
          </div>
          <div class="box-tools pull-right">
          <a href="<?php echo site_url('acc_concept/create')?>"><button class="btn btn-xs btn-primary" ><i class="glyphicon glyphicon-plus"></i> Create</button></a>

            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >

                  <table id="acc_concept" class="table table-bordered table-hover" width="100%" cellspacing="0">
                 
                    <thead>
                    <tr >
                        <th>Action</th>
                        <th>Isactive</th>
                        <th>Account Name</th>
                        <th>Concept Name</th>
                        <th>Updated At</th>
                        <th>Updated By</th>
                        <th>Created At</th>
                        <th>Created By</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($details->result() as $row)
                    {
                      ?>
                      <tr>
                      <td>
                        <a title="Edit" href="<?php echo site_url('acc_concept/update')?>?guid=<?php echo $row->concept_guid?>" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-pencil "></i></a>
                        
                        <button title="Delete" onclick="confirm_modal('<?php echo site_url('acc_concept/delete'); ?>?guid=<?php echo $row->concept_guid?>')" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete" data-name="<?php echo $row->concept_name?>" ><i class="glyphicon glyphicon-trash"></i></button>
                        
                      </td>
                      <td><?php echo $row->isactive?></td>
                      <td><?php echo $row->acc_name?></td>
                      <td><?php echo $row->concept_name?></td>
                      <td><?php echo $row->updated_at?></td>
                      <td><?php echo $row->updated_by?></td>
                      <td><?php echo $row->created_at?></td>
                      <td><?php echo $row->created_by?></td>
                      </tr>
                      <?php
                    }
                    ?>
                    
                    </tbody>
                  </table>
          
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /info div -->

</div>
</div>
<!-- confirm modal @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<div class="modal fade" id="delete" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <div class="modal-body">
                <h4 class="modal_detail" style="text-align: center"></h4>
            </div>
            <div class="modal-footer" style="text-align: center">
            <span id="preloader-delete"></span>
                <a id="url" href=""><button type="submit" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button></a>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End confirm modal modal -->

<script type="text/javascript">

  function confirm_modal(delete_url)
  {
    $('#delete').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm delete ' + button.data('name') + '?')
    document.getElementById('url').setAttribute("href" , delete_url );
    });
  }


    
</script>