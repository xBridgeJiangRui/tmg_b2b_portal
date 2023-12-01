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
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Filter</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <div class="col-md-6 form-horizontal">
              <form class="" role="form" action="<?php echo site_url('training/staff')?>" method="post">
                  <div class="form-group">
                       
                      <label for="varchar" class="col-sm-2 control-label">Session </label>
                      <div class="col-sm-6">
                        <select name="session" id="session" class="form-control">
                           <!--  <option value="<?php echo $branch_group_select_guid ?>" selected data-default style="display: none; "<?php echo $disabled ?> ><?php echo $branch_group_select?></option> -->
                            <option value="all">View All</option>
                            <?php 
                            foreach ($session->result() as $row)
                                {
                                  if($row->session == $selected_session)
                                  {
                                    $selected = 'selected';
                                  }
                                  else
                                  {
                                    $selected = '';
                                  }
                                    ?>
                                    <option value="<?php echo $row->session?>" <?php echo $selected;?>><?php echo $row->session?></option>
                                    <?php
                                }
                            ?>     
                        </select>
                      </div>

                      <div class="col-sm-4">
                          <button type="submit" class="btn btn-primary">Submit</button> 
                      </div>

                  </div>

              </form>
            </div>
         
        </div>
        </div>
      </div>
      <!-- /.box -->

    </div>



<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Training List</h3>
          <div class="box-tools pull-right">

          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div id="" style="max-height: 410px;
        overflow-y: auto;
    overflow-x: hidden;">
          
                  <table id="table1" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>IC</th>
                        <th>User ID</th>
                        <th>Company Name</th>
                        <th>Status</th>
                        <th>Session</th>
                        <th>Attend at</th>
                        <th>Updated by(staff)</th>
                        <th>Updated at(staff)</th>
                        <th>Action</th>
                        

                    </tr>
                    </thead>
                    <tbody>

                      <?php foreach($checking_attended->result() as $row) { ?>
                      <tr>
                        <td><?php echo $row->name ?></td>
                        <td><?php echo $row->i_c ?></td>
                        <td><?php echo $row->user_id ?></td>
                        <td><?php echo $row->company_name ?></td>
                        <td>

                          <?php if ($row->status == 0) {
                            echo 'Unattend ';
                          } else if ($row->status == 1) {
                            echo 'Signed Attendance <i class="fa fa-check"></i>';
                          } else if ($row->status == 2) {
                            echo 'Cancel <i class="fa fa-times"></i>';
                          } else{
                            echo $row->status;
                          } ?>

                          

                        </td>

                        <td>

                          <?php if ($row->session == 1) {
                            echo '1st Session(Morning) ';
                          } else if ($row->session == 2) {
                            echo '2nd Session(Afternoon)';
                          } else if ($row->session == 3) {
                            echo '7-11-19 Session(Morning)';
                          } 
                          ?>


                        </td>
                        <td><?php echo $row->attended_at ?></td>
                        <td><?php echo $row->updated_by ?></td>
                        <td><?php echo $row->updated_at ?></td>
                        <td>

                          <button title="Sign" type="button" class="btn btn-xs btn-primary"
                          ta_guid="<?php echo $row->ta_guid ?>"
                          name = "<?php echo $row->name ?>"
                          status="<?php echo '1' ?>"
                          ><i class="fa fa-check"></i>
                          </button>

                          <button title="Cancel" type="button" class="btn btn-xs btn-danger"
                          ta_guid="<?php echo $row->ta_guid ?>"
                          name = "<?php echo $row->name ?>"
                          status="<?php echo '2' ?>"
                          ><i class="fa fa-times"></i>
                          </button>

                          <button title="Cancel" type="button" class="btn btn-xs btn-warning"
                          ta_guid="<?php echo $row->ta_guid ?>"
                          name = "<?php echo $row->name ?>"
                          status="<?php echo '0' ?>"
                          ><i class="fa fa-refresh"></i>
                          </button>

                          <!-- <button title="Cancel" type="button" class="btn btn-xs btn-danger"
                          name="<?php echo $row->name ?>"
                          i_c="<?php echo $row->i_c ?>"
                          user_id="<?php echo $row->user_id ?>"
                          company_name="<?php echo $row->company_name ?>"
                          status="<?php echo $row->status ?>"
                          attended_at="<?php echo $row->attended_at ?>"
                          remark="<?php echo $row->remark ?>"
                          ><i class="fa fa-edit"></i>
                          </button> -->




                        </td>
                        
                      </tr>
                    <?php } ?>

                    </tbody>
                  </table>
             
              </div>  
        </div>

      </div>



    </div>
  </div>


<!-- nothing ends after -->
</div>
</div>

<div class="modal fade" id="modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form id="submit_form" action="" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <span class="modal-title"></span>
                
              </div>
              <div class="modal-body" style="display: inline-block;">                 
                  
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
 


<script>
  $(document).ready(function () {    
    $('#table1').DataTable({
      "columnDefs": [{}],
      'paging'      : true,
      'lengthChange': true,
      'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       : [ [0 , 'asc'] ],
      'info'        : true,
      'autoWidth'   : true,
      dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
    })

  $(document).on('click', '#table1 .btn-primary,.btn-danger,.btn-warning', function(){

  var modal = $("#modal").modal();

  var ta_guid = $(this).attr('ta_guid')
  var status = $(this).attr('status')
  var name = $(this).attr('name')

  if (status == 0) {

  var title = 'Reset Status'
  var description = 'This will reset status of <b>' +name+ '</b> to status <b>Unattend</b> , are you sure?'

  } else if (status == 1) {

  var title = 'Sign Attendance'
  var description = 'This will change status of <b>' +name+ '</b> to status <b>Signed Attendance</b> , are you sure?'

  } else if (status == 2) {

  var title = 'Cancel Attendance'
  var description = 'This will change status of <b>' +name+ '</b> to status <b>Cancel</b> , are you sure?'

  }

  modal.find('.modal-title').html('<h3>'+title+' - '+name+'</h3>');

  modal.find('#submit_form').attr("action","<?php echo site_url('Training/change_status_attendance')?>");

  methodd = '';

  methodd +='<div class="row">';

  methodd += '<div class="col-md-12">'+description+'</div>';

  methodd += '<input type="hidden" name="ta_guid" value="'+ta_guid+'">';
  methodd += '<input type="hidden" name="status" value="'+status+'">';

  methodd += '</div>';

  modal.find('.modal-body').html(methodd);

  modal.find('select[name="lang_type"]').val(lang_type);

})





  })

</script>
