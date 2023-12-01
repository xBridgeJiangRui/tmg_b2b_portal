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
          <h3 class="box-title">Topic</h3>
          <div class="box-tools pull-right">
          <button title="" data-toggle="modal" data-target="#create_topic_modal" type="button" class="btn btn-xs btn-primary"   
            ><i class="glyphicon glyphicon-plus"></i>Create
          </button>
          <button id="delete_topic_button" title="" data-toggle="modal" data-target="#delete_topic_modal" type="button" class="btn btn-xs btn-danger"   
            ><i class="glyphicon glyphicon-trash"></i>Delete
          </button>

          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="">
                  <form action="<?php echo site_url('Ticket/delete_topic')?>" method="POST" id="topic_form">
                    <table id="topic_table" class="table table-bordered table-hover" width="100%" cellspacing="0">
                      <thead>
                      <tr>
                          <th><input type="checkbox" id="t_topic_guid_check_all"  onclick="checkedAlltopic ();"></th>
                          <th>Topic</th>
                          <th>Created at</th>
                          <th>Created by</th>
                          <th>Updated at</th>
                          <th>Updated by</th>
                          

                      </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($ticket_topic as $key) { ?>
                          <tr>
                            <td><input class="t_topic_guid" type="checkbox" name="t_topic_guid[]" value="<?php echo $key->t_topic_guid ?>"></td>
                            <td><a style="cursor: pointer;" class="topic_edit" data-name="<?php echo $key->name ?>" data-guid="<?php echo $key->t_topic_guid ?>"><?php echo $key->name ?></a>
                              <span class="pull-right">
                              <?php

                              $topic_guid = $key->t_topic_guid;
                              $count_ticket = $this->db->query("SELECT * FROM ticket WHERE topic_guid = '$topic_guid' ")->num_rows(); 
                              
                               if ($count_ticket > 0) {
                                echo $count_ticket.'<i class="fa fa-ticket"></i>';
                              } ?>

                              </span>
                            </td>
                            <td><?php echo $key->created_at ?></td>
                            <td><?php echo $key->created_by ?></td>
                            <td><?php echo $key->updated_at ?></td>
                            <td><?php echo $key->updated_by ?></td>

                          </tr>
                        <?php } ?>
                    
                      </tbody>
                    </table>
                  </form>
             
              </div>  
        </div>

      </div>
      </div>
      <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Sub Topic</h3>
          <div class="box-tools pull-right">
          <button title="" data-toggle="modal" data-target="#create_sub_topic_modal" type="button" class="btn btn-xs btn-primary"   
            ><i class="glyphicon glyphicon-plus"></i>Create
          </button>
          <button id="delete_sub_topic_button" title="" data-toggle="modal" data-target="#delete_sub_topic_modal" type="button" class="btn btn-xs btn-danger"   
            ><i class="glyphicon glyphicon-trash"></i>Delete
          </button>

          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="">
                  <form action="<?php echo site_url('Ticket/delete_sub_topic')?>" method="POST" id="subtopic_form">
                    <table id="sub_topic_table" class="table table-bordered table-hover" width="100%" cellspacing="0">
                      <thead>
                      <tr>
                          <th><input type="checkbox" id="t_sub_topic_guid_check_all" onclick="checkedAllsubtopic ();"></th>
                          <th>SubTopic</th>
                          <th>Topic</th>
                          <th>Created at</th>
                          <th>Created by</th>
                          <th>Updated at</th>
                          <th>Updated by</th>
                          

                      </tr>
                      </thead>
                      <tbody>

                        <?php foreach ($ticket_sub_topic as $key) { ?>
                          <tr>
                            <td><input class="t_sub_topic_guid" type="checkbox" name="t_sub_topic_guid[]" value="<?php echo $key->t_sub_topic_guid ?>"></td>
                            <td><a style="cursor: pointer;" class="sub_topic_edit" data-name="<?php echo $key->name ?>" data-guid="<?php echo $key->t_sub_topic_guid ?>"><?php echo $key->name ?></a>

                              <span class="pull-right">
                              <?php

                              $topic_guid = $key->t_sub_topic_guid;
                              $count_ticket = $this->db->query("SELECT * FROM ticket WHERE sub_topic_guid = '$topic_guid' ")->num_rows(); 
                              
                               if ($count_ticket > 0) {
                                echo $count_ticket.'<i class="fa fa-ticket"></i>';
                              } ?>

                              </span>

                            </td>
                            <td><?php echo $key->topic_name ?></td>
                            <td><?php echo $key->created_at ?></td>
                            <td><?php echo $key->created_by ?></td>
                            <td><?php echo $key->updated_at ?></td>
                            <td><?php echo $key->updated_by ?></td>

                          </tr>
                        <?php } ?>
               
                      </tbody>
                    </table>
                  </form>
             
              </div>  
        </div>

      </div>
      </div>
      <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Resolved Reason</h3>
          <div class="box-tools pull-right">
          <button title="" data-toggle="modal" data-target="#create_rr_modal" type="button" class="btn btn-xs btn-primary"   
            ><i class="glyphicon glyphicon-plus"></i>Create
          </button>
          <button id="delete_rr_button" title="" data-toggle="modal" data-target="#delete_rr_modal" type="button" class="btn btn-xs btn-danger"   
            ><i class="glyphicon glyphicon-trash"></i>Delete
          </button>

          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="">
                  <form action="<?php echo site_url('Ticket/delete_rr')?>" method="POST" id="rr_form">
                    <table id="rr_table" class="table table-bordered table-hover" width="100%" cellspacing="0">
                      <thead>
                      <tr>
                          <th><input type="checkbox" id="rr_guid_check_all" onclick="checkedAllrr ();"></th>
                          <th>Topic</th>
                          <th>Created at</th>
                          <th>Created by</th>
                          

                      </tr>
                      </thead>
                      <tbody>

                        <?php foreach ($ticket_resolved_reason as $key) { ?>
                          <tr>
                            <td><input class="rr_guid" type="checkbox" name="rr_guid[]" value="<?php echo $key->rr_guid ?>"></td>
                            <td><a style="cursor: pointer;" class="sub_topic_edit" data-name="<?php echo $key->rr_name ?>" data-guid="<?php echo $key->rr_guid ?>"><?php echo $key->rr_name ?></a>

                              <span class="pull-right">
                              <?php

                              $rr_guid = $key->rr_guid;
                              $count_ticket = $this->db->query("SELECT * FROM ticket WHERE resolved_reason = '$rr_guid' ")->num_rows(); 
                              
                               if ($count_ticket > 0) {
                                echo $count_ticket.'<i class="fa fa-ticket"></i>';
                              } ?>

                              </span>

                            </td>
                            <td><?php echo $key->created_at ?></td>
                            <td><?php echo $key->created_by ?></td>

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
  </div>
   
<!-- nothing ends after -->
</div>
</div>

<div class="modal fade" id="create_topic_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="<?php echo site_url('Ticket/add_topic')?>" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4>Add Topic</h4>
                
              </div>
              <div class="modal-body" style="display: flow-root;">
                  <div class="col-md-12">
                    <label>Title</label>
                    <input required="true" type="text" id="" name="topic" class="form-control" value="" >
                  </div>

              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input type="submit" value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
</div>

<div class="modal fade" id="delete_topic_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4>Delete Topic</h4>
                
              </div>
              <div class="modal-body" style="display: flow-root;">
                  <div class="col-md-12">
                    <label style="color: red">Are you sure you want to delete?</label><br>
                    <label style="color: red">The sub topic assigned to it will deleted too</label>
                  </div>

              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input form="topic_form" type="submit"value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
</div>


<div class="modal fade" id="create_sub_topic_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="<?php echo site_url('Ticket/add_sub_topic')?>" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4>Add Sub Topic</h4>
                
              </div>
              <div class="modal-body" style="display: flow-root;">

                  <div class="col-md-12">
                    <label>Parent Title</label>
                    <select class="form-control" name="t_topic_guid">

                      <?php foreach ($ticket_topic as $key) { ?>
                        <option value="<?php echo $key->t_topic_guid ?>"><?php echo $key->name ?></option>
                      <?php } ?>
                      
                    </select>
                  </div>

                  <div class="col-md-12">
                    <label>Title</label>
                    <input required="true" type="text" id="" name="subtopic" class="form-control" value="" >
                  </div>

              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input type="submit" value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
</div>

<div class="modal fade" id="delete_sub_topic_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4>Delete Sub Topic</h4>
                
              </div>
              <div class="modal-body" style="display: flow-root;">
                  <div class="col-md-12">
                    <label style="color: red">Are you sure you want to delete?</label><br>
                  </div>

              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input form="subtopic_form" type="submit"value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
</div>

<div class="modal fade" id="create_rr_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="<?php echo site_url('Ticket/add_rr')?>" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4>Add Resolve Reason</h4>
                
              </div>
              <div class="modal-body" style="display: flow-root;">
                  <div class="col-md-12">
                    <label>Title</label>
                    <input required="true" type="text" id="" name="topic" class="form-control" value="" >
                  </div>

              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input type="submit" value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
</div>

<div class="modal fade" id="delete_rr_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4>Delete Resolve Reason</h4>
                
              </div>
              <div class="modal-body" style="display: flow-root;">
                  <div class="col-md-12">
                    <label style="color: red">Are you sure you want to delete?</label><br>
                  </div>

              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input form="rr_form" type="submit"value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
</div>

<div class="modal fade" id="edit_topic_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="<?php echo site_url('Ticket/update_topic')?>" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4>Edit Topic</h4>
                
              </div>
              <div class="modal-body" style="display: flow-root;">
                  <div class="col-md-12">
                    <label>Title</label>
                    <input required="true" type="text" id="" name="topic" class="form-control" value="" >
                    <input required="true" type="hidden" id="" name="guid" class="form-control" value="" >
                  </div>

              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input type="submit" value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
</div>

<div class="modal fade" id="edit_sub_topic_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="<?php echo site_url('Ticket/update_sub_topic')?>" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4>Edit Sub Topic</h4>
                
              </div>
              <div class="modal-body" style="display: flow-root;">

                  <div class="col-md-12">
                    <label>Title</label>
                    <input required="true" type="text" id="" name="subtopic" class="form-control" value="" >
                    <input required="true" type="hidden" id="" name="guid" class="form-control" value="" >
                  </div>

              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input type="submit" value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
</div>

<script type="text/javascript">
  
var checked=false;
function checkedAlltopic () {
    var aa =  document.getElementsByName("t_topic_guid[]");
    checked = document.getElementById('t_topic_guid_check_all').checked;
     
    for (var i =0; i < aa.length; i++) 
    {
        aa[i].checked = checked;
    }
 }
  
var checked=false;
function checkedAllsubtopic () {
    var aa =  document.getElementsByName("t_sub_topic_guid[]");
    checked = document.getElementById('t_sub_topic_guid_check_all').checked;
     
    for (var i =0; i < aa.length; i++) 
    {
        aa[i].checked = checked;
    }
 }

var checked=false;
function checkedAllrr () {
    var aa =  document.getElementsByName("rr_guid[]");
    checked = document.getElementById('rr_guid_check_all').checked;
     
    for (var i =0; i < aa.length; i++) 
    {
        aa[i].checked = checked;
    }
 }


$('#delete_topic_button').click(function() {
      checked = $("input[class=t_topic_guid]:checked").length;

      if(!checked) {
        alert("You must check at least one invoice.");
        return false;
      }

    });

$('#delete_sub_topic_button').click(function() {
      checked = $("input[class=t_sub_topic_guid]:checked").length;

      if(!checked) {
        alert("You must check at least one invoice.");
        return false;
      }

    });

$('#delete_rr_button').click(function() {
      checked = $("input[class=rr_guid]:checked").length;

      if(!checked) {
        alert("You must check at least one invoice.");
        return false;
      }

    });

</script>


<script>
  $(document).ready(function () {    
    $('#topic_table').DataTable({
      "columnDefs": [{}],
      'paging'      : true,
      "columnDefs": [{ "orderable": false, "targets": 0 }],
      'lengthChange': true,
      'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       : [ [1 , 'asc'] ],
      'info'        : true,
      'autoWidth'   : true,
      dom: 'frtip',
    })

  })
</script>

<script>
  $(document).ready(function () {    
    $('#sub_topic_table').DataTable({
      "columnDefs": [{}],
      'paging'      : true,
      "columnDefs": [{ "orderable": false, "targets": 0 }],
      'lengthChange': true,
      'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       : [ [1 , 'asc'] ],
      'info'        : true,
      'autoWidth'   : true,
      dom: 'frtip',
    })

  })
</script>

<script>
  $(document).ready(function () {    
    $('#rr_table').DataTable({
      "columnDefs": [{}],
      'paging'      : true,
      "columnDefs": [{ "orderable": false, "targets": 0 }],
      'lengthChange': true,
      'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       : [ [1 , 'asc'] ],
      'info'        : true,
      'autoWidth'   : true,
      dom: 'frtip',
    })

  })
</script>


<script type="text/javascript">
  
$('.topic_edit').click(function(){

var name = $(this).attr('data-name')
var guid = $(this).attr('data-guid')
var modal = $("#edit_topic_modal").modal();


modal.find('input[name=topic]').val(name);
modal.find('input[name=guid]').val(guid);

})

</script>

<script type="text/javascript">
  
$('.sub_topic_edit').click(function(){

var name = $(this).attr('data-name')
var guid = $(this).attr('data-guid')
var modal = $("#edit_sub_topic_modal").modal();


modal.find('input[name=subtopic]').val(name);
modal.find('input[name=guid]').val(guid);

})

</script>