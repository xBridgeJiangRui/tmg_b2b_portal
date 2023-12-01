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

<div class="col-md-12">
        <a class="btn btn-app" href="<?php echo site_url('CusAdmin_controller/supplier_checklist')?>">
          <i class="fa fa-arrow-left"></i> Back
        </a>
         
  </div>
<div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $title ?></h3>
          <div class="box-tools pull-right">
          <!-- <button title="Subscription" onclick="create_new()" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#create_new"  
            data-table="<?php echo 'announcement' ?>"
            data-mode="<?php echo 'create' ?>"
            data-customer_guid = "<?php echo $_SESSION['customer_guid'] ?>"            
            ><i class="glyphicon glyphicon-plus"></i>Create
          </button> -->

          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
            <div id="accconceptCheck">
              <!--  left -->
                <div class="col-md-6">
                  <div class="panel panel-default">
                    <div class="panel-body">
                     <?php echo form_open_multipart('CusAdmin_controller/add_image?customer_guid='.$customer_guid.'&supcus_guid='.$supcus_guid);?>
                     
                      <b>Files Allowed : jpg/png/pdf</b><br>
                      <b>Max Allowed Size: 10MB</b><br>
                      <div class="thumbnail">
                        <img style="height: 240px;" id="output"/>
                      </div>
                      
                      <input type="file" name="userfile" size="20" onchange="loadFile(event)" />
                      <br>
                     <input type="submit" name="submit" class="btn btn-success" value="Submit" >
                    </form>
                    </div>
                  </div>
                </div> 
                <!-- end left -->
                <!-- right -->
                <div class="col-md-6">
                  <div class="panel panel-default">
                    <div class="panel-body"> 
                      <b>Docs In Folder</b><br>
                         <table id="acc" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Title</th>
                        <th>Action</th>
                      </tr> 
                     </thead>
                    <tbody>
                      
                      <?php 
                          // $rootDir = $url; // __DIR__ = C:\xampp\htdocs\CodeWall
                          // $allFiles = array_diff(scandir($rootDir . "/"), [".", ".."]); // Use array_diff to remove both period values eg: ("." , "..")
                          // print_r($redirect_file);
                          foreach($redirect_file as $value)
                          {
                            // print_r($value['file_name']);
                            echo "<tr>";
                              //echo "<a href ='".base_url().$doc_url."/".$value."' target='u_blank'>$value</a><br>" ;
                            echo "<td><a href ='".$value['file_path']."' target='u_blank' download>".$value['file_name']."</a></td>
 
                              <td><a title='del' class='btn btn-xs btn-danger' style='text-align: right' href ='".site_url('CusAdmin_controller/unlink?supcus_guid=').$_REQUEST['supcus_guid']."&customer_guid=".$_REQUEST['customer_guid']."&title=".$value['file_name']."'><i class='glyphicon glyphicon-trash'></i></a></td>
                             </tr> " ;
                          }
                       ?>  
                     
                     </tbody>
                   </table>
                    </div>
                  </div>
                </div> 
                <!-- end right  -->
            </div>  
        </div>

      </div>
    </div>
  </div>
   
<!-- nothing ends after -->
</div>
</div> 
<script>
  function hide_modal()
  {
    $('#sup_checklist_action').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text('Supplier Check List Action')
      modal.find('[name="customer_guid"]').val(button.data('customer_guid'))       
      modal.find('[name="code"]').val(button.data('code')) 
      modal.find('[name="supcus_guid"]').val(button.data('supcus_guid')) 
      modal.find('[name="PIC"]').val(button.data('pic')) 
      modal.find('[name="PAYMENT"]').val(button.data('payment')) 
      modal.find('[name="IsActive"]').val(button.data('isactive')) 
      modal.find('[name="STATUS"]').val(button.data('status')) 
    });
  }

 $(function() {
    $('input[name="docdate"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
         locale: {
            format: 'YYYY-MM-DD'
        },
         
    }, 
  );
});

  $(function() {
    $('input[name="published_date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: true, 
        timePickerIncrement: 30,
        ampm: true,
         locale: {
            format: 'YYYY-MM-DD HH:mm:ss'
        },
         
    }, 
  );
});


</script>



<script>
var inp = document.getElementById("get-files");
// Access and handle the files 

for (i = 0; i < inp.files.length; i++) {
    let file = inp.files[i];
    // do things with file
}
</script>