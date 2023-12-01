<style>
.content-wrapper{
  min-height: 750px !important; 
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

  <div class="col-md-12">
 
  </div>
  <!-- filter by -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <!-- head -->
        <div class="box-header with-border">
          <h3 class="box-title">Upload File</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- head -->
        <!-- body -->
        <div class="box-body">
          <div class="col-md-12">
            <div class="row">
              <form role="form" method="POST" id="myForm" action="<?php echo site_url('panda_other_doc/upload_excel_file_acc_doc');?>" enctype="multipart/form-data">
              <div class="col-md-2"><b>Ref No</b></div>
              <div class="col-md-4">
                 <input id="other_doc_refno" name="other_doc_refno" type="text" autocomplete="off" class="form-control pull-right">
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Doc Typr</b></div>
              <div class="col-md-4">
                <select name="doctype" class="form-control">
                  <?php foreach($drop_down->result() as $row)
                  {
                    if($status == $row->code)
                    {
                      $other_doc_selected = 'selected';
                    }
                    else
                    {
                      $other_doc_selected = ''; 
                    }
                  ?>
                      <option value="<?php echo $row->code;?>" <?php echo $other_doc_selected;?>><?php echo $row->description;?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Supplier Code</b></div>
              <div class="col-md-4">
                <select name="supcode" class="form-control" required>
                  <option value="">Please Select</option>
                  <?php foreach($supcode->result() as $row)
                  {
                    if($status == $row->Code)
                    {
                      $other_doc_selected = 'selected';
                    }
                    else
                    {
                      $other_doc_selected = ''; 
                    }
                  ?>
                      <option value="<?php echo $row->Code;?>"><?php echo $row->Code.' - ';?> <?php echo $row->Name;?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>

              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>File</b></div>
              <div class="col-md-4">
                <input required id="file" name="file" type="file" class="form-control pull-right" accept=".xlsx">
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-12">
                
                <button type="submit" id="search" class="btn btn-primary"> Upload</button>

                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                
              </div>
                </form>
            </div>
          </div>
        </div>
        <!-- body -->

      </div>
    </div>
    
  </div>
  <!-- filter by -->

  <div class="row">
    <div class="col-md-12">

      <div class="col-md-12">
        <br>
        <div>

        <!-- Modal -->
        <div id="postatusmodal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-sm">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title text-center">Check PO Status</h4>
              </div>
              <div class="modal-body">
                <p><input type="text" id="po_refno"  name="po_refno" class="form-control">
                <center><span style="font-weight: bolder;" id="webindex_result"></span></center>  </p>
        	<center><span style="font-weight: bolder;" id="po_check_grn_refno_result"></span></center>  </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload()">Close</button>
              </div>
            </div>

          </div>
          </div>
        </div>
      </div>
        
        </div>
    </div>
</div>
</div>
