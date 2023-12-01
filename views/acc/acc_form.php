<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
<div class="row">
    <div class="col-md-12">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $button ?> Account</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <div class="col-md-6 form-horizontal">
            <form class="" role="form" action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                   <label for="varchar" class="col-sm-2 control-label">Acc Name </label>
                    <div class="col-sm-10"><?php echo form_error('acc_name') ?>
                        <input type="text" class="form-control" name="acc_name" id="acc_name" placeholder="Acc Name" value="<?php echo $acc_name; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="varchar" class="col-sm-2 control-label">Acc Regno </label>
                    <div class="col-sm-10"><?php echo form_error('acc_regno') ?>
                        <input type="text" class="form-control" name="acc_regno" id="acc_regno" placeholder="Acc Regno" value="<?php echo $acc_regno; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="varchar" class="col-sm-2 control-label">Acc Gstno </label>
                    <div class="col-sm-10"><?php echo form_error('acc_gstno') ?>
                        <input type="text" class="form-control" name="acc_gstno" id="acc_gstno" placeholder="Acc Gstno" value="<?php echo $acc_gstno; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="varchar" class="col-sm-2 control-label">Acc Taxcode </label>
                    <div class="col-sm-10"><?php echo form_error('acc_taxcode') ?>
                        <input type="text" class="form-control" name="acc_taxcode" id="acc_taxcode" placeholder="Acc Taxcode" value="<?php echo $acc_taxcode; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="varchar" class="col-sm-2 control-label">Acc Add1 </label>
                    <div class="col-sm-10"><?php echo form_error('acc_add1') ?>
                        <input type="text" class="form-control" name="acc_add1" id="acc_add1" placeholder="Acc Add1" value="<?php echo $acc_add1; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="varchar" class="col-sm-2 control-label">Acc Add2 </label>
                    <div class="col-sm-10"><?php echo form_error('acc_add2') ?>
                        <input type="text" class="form-control" name="acc_add2" id="acc_add2" placeholder="Acc Add2" value="<?php echo $acc_add2; ?>" />
                    </div>
                </div>
            
        </div>
        <div class="col-md-6 form-horizontal">
            
                <div class="form-group"> 
                    <label for="inputEmail3" class="col-sm-2 control-label">Acc Add3 </label>
                    <div class="col-sm-10"><?php echo form_error('acc_add3') ?>
                        <input type="text" class="form-control" name="acc_add3" id="acc_add3" placeholder="Acc Add3" value="<?php echo $acc_add3; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="inputPassword3" class="col-sm-2 control-label">Acc Add4 </label>
                    <div class="col-sm-10"><?php echo form_error('acc_add4') ?>
                        <input type="text" class="form-control" name="acc_add4" id="acc_add4" placeholder="Acc Add4" value="<?php echo $acc_add4; ?>" />
                    </div>
                </div>
                <div class="form-group"> 
                    <label for="inputEmail3" class="col-sm-2 control-label">Acc Postcode </label>
                    <div class="col-sm-10"><?php echo form_error('acc_postcode') ?>
                        <input type="text" class="form-control" name="acc_postcode" id="acc_postcode" placeholder="Acc Postcode" value="<?php echo $acc_postcode; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="inputPassword3" class="col-sm-2 control-label">Acc State </label>
                    <div class="col-sm-10"><?php echo form_error('acc_state') ?>
                        <input type="text" class="form-control" name="acc_state" id="acc_state" placeholder="Acc State" value="<?php echo $acc_state; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="inputPassword3" class="col-sm-2 control-label">Acc Country </label>
                    <div class="col-sm-10"><?php echo form_error('acc_country') ?>
                        <input type="text" class="form-control" name="acc_country" id="acc_country" placeholder="Acc Country" value="<?php echo $acc_country; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                             
                            <label><?php echo form_error('isactive') ?>
                            <input type="hidden" name="isactive" id="isactive" value="0" />
                                <input type="checkbox" name="isactive" id="isactive" value="1" 
                                <?php
                                    if($isactive == 1)
                                    {
                                        ?>
                                        checked
                                        <?php
                                    }
                                 ?>/> Active
                            </label>
                        </div>
                    </div>
                </div>
            
        </div>
         
        </div>
        <!-- /.box-body -->
        <div class="box-footer text-right">
            <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
        <a href="<?php echo site_url('Profile_setup') ?>" class="btn btn-default">Cancel</a>
        </form>
        </div>
      </div>
      <!-- /.box -->

    </div>

  </div>
   
</div>
</div>