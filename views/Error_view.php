<style type="text/css">
  #acc_branch{
    height: auto;
    overflow-y: scroll;

  }
  #acc_concepts{
    height: auto;
    overflow-x: auto;

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
          <h3 class="box-title">Branch Error</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concepts">
          <div id="accconceptCheck">
          <table id="branch_error_table" class="table table-bordered table-hover">
                <thead>
<!--                 <tr> -->
                  <?php
                  if(count($count_branch) > 0)
                  {
                  $table_head = $count_branch; 
                  $table_head2 = $table_head[0];
                  foreach($table_head2 as $key => $value)
                  { 
                      // $i = 0;
                      // foreach($value as $key2 => $value2)
                      // {
                  ;?> 
                          <th><?php echo $key;?></th>
                  <?php
                      // }
                  }
                  ;?>
                <!-- </tr> -->
                </thead>
                <tbody>
                  <?php 
                  foreach($count_branch as $row)
                  {
                  ;?> 
                  <tr>

                  <?php  
                    foreach($table_head2 as $key => $value)
                    { 
                        // $i = 0;
                        // foreach($value as $key2 => $value2)
                        // {
                    ;?> 
                            <td><?php echo $row->$key;?></td>
                    <?php
                        // }
                    }
                    ;?>

                  </tr>
                  <?php
                  }
                }
                  ;?>
                </tbody>
              </table>
          </div>  
        </div>

      </div>
    </div>
  </div>

<?php // echo var_dump($_SESSION); 
if($show_cp_set_branch == 1)
{?>  
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Branch Error Detail</h3>
          <select id="branch_error_type">
            <!-- <option value = "none">None</option> -->
            <option value = "0">All</option>
            <option value = "1">Error</option>
            <!-- <option value = "<?php echo $row2; ?>" <?php if(isset($_REQUEST['isactive']) && $_REQUEST['isactive'] == $row2){ echo "selected"; }?>><?php echo $row2; ?></option> -->
          </select>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concepts">
          <div id="accconceptCheck">
          <table id="branch_error_detail_table" class="table table-bordered table-hover">
                <thead>
<!--                 <tr> -->
                  <?php
                  if(count($count_branch) > 0)
                  {
                  $table_head = $cp_set_branch; 
                  $table_head2 = $table_head[0];
                  foreach($table_head2 as $key => $value)
                  { 
                      // $i = 0;
                      // foreach($value as $key2 => $value2)
                      // {
                  ;?> 
                          <th><?php echo $key;?></th>
                  <?php
                      // }
                  }
                  ;?>
                <!-- </tr> -->
                </thead>
                <tbody>
                  <?php 
                  foreach($cp_set_branch as $row)
                  {
                  ;?> 
                  <tr <?php if( (($row->BRANCH_CODE == '') || ($row->BRANCH_CODE == null)) || (($row->BRANCH_NAME == '') || ($row->BRANCH_NAME == null))  || (($row->branch_code == '') || ($row->branch_code == null)) || (($row->branch_name == '') || ($row->branch_name == null)) ) { echo 'style="background-color:pink;"'; } ?> >

                  <?php  
                    foreach($table_head2 as $key => $value)
                    {   
                        // $i = 0;
                        // foreach($value as $key2 => $value2)
                        // {
                    ;?> 
                            <td><?php echo $row->$key;?></td>
                    <?php
                        // }
                    }
                    ;?>

                  </tr>
                  <?php
                  }
                }
                  ;?>
                </tbody>
              </table>
          </div>  
        </div>

      </div>
    </div>
  </div>
<?php 
}
?>   
<!-- nothing ends after -->
</div>
</div>


<script>
$(document).ready(function() {

    var rtype = "<?php echo isset($_REQUEST['rtype']) ? $_REQUEST['rtype'] : 0;?>";
    $('#branch_error_type').val(rtype).trigger('change');

    $(document).on('change', '#branch_error_type', function(){
      var redirect_type = $(this).val();
      var url = "<?php echo site_url('Error_review');?>";
      var guid = "<?php echo $_REQUEST['guid'];?>";
      var type = "<?php echo $_REQUEST['type'];?>";
      var run_url = url+'?guid='+guid+'&type='+type;
      // alert(run_url);die;
      // alert(parameter);
      // alert(window.location.href);
      if(redirect_type == 0)
      {
        var run_url = url+'?guid='+guid+'&type='+type+'&rtype=0';
      }
      else
      {
        var run_url = url+'?guid='+guid+'&type='+type+'&rtype=1';
      }
      window.location.href = run_url;
    });//CLOSE ONCLICK

    $('#branch_error_table').DataTable({
      'processing'  : true,
      'paging'      : true,
      'lengthChange': true,
      'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       : [],
      'info'        : true,
      'autoWidth'   : false,
      "bPaginate": false, 
      "bFilter": true, 
      "sScrollY": "50vh", 
      "sScrollX": "100%", 
      "sScrollXInner": "100%", 
      "bScrollCollapse": true,                    
     // dom: 'lfrtip'
     dom: '<"row col-sm-12" <"col-sm-8" l><"col-sm-4" f> >rti',
    });//close datatable



    $('#branch_error_detail_table').DataTable({
      'processing'  : true,
      'paging'      : true,
      'lengthChange': true,
      'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       : [],
      'info'        : true,
      'autoWidth'   : false,
      "bPaginate": false, 
      "bFilter": true, 
      "sScrollY": "50vh", 
      "sScrollX": "100%", 
      "sScrollXInner": "100%", 
      "bScrollCollapse": true,                    
     // dom: 'lfrtip'
     dom: '<"row col-sm-12" <"col-sm-8" l><"col-sm-4" f> >rti',
    });//close datatable    
});
</script>
