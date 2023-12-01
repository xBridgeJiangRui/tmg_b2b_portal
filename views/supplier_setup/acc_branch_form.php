<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
<div class="row">
    <div class="col-md-12">
      
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $button ?> Account Branch</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <div class="col-md-6 form-horizontal">
            <form class="" role="form" action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                   <label for="varchar" class="col-sm-2 control-label">Branch Code </label>
                    <div class="col-sm-10"><?php echo form_error('branch_code') ?>
                        <input type="text" class="form-control" name="branch_code" id="branch_code" placeholder="Branch Code" value="<?php echo $branch_code; ?>" />
                    </div>
                </div>
                <div class="form-group">
                   <label for="varchar" class="col-sm-2 control-label">Branch Name </label>
                    <div class="col-sm-10"><?php echo form_error('branch_name') ?>
                        <input type="text" class="form-control" name="branch_name" id="branch_name" placeholder="Branch Name" value="<?php echo $branch_name; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="varchar" class="col-sm-2 control-label">Branch Regno </label>
                    <div class="col-sm-10"><?php echo form_error('branch_regno') ?>
                        <input type="text" class="form-control" name="branch_regno" id="branch_regno" placeholder="Branch Regno" value="<?php echo $branch_regno; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="varchar" class="col-sm-2 control-label">Branch Gstno </label>
                    <div class="col-sm-10"><?php echo form_error('branch_gstno') ?>
                        <input type="text" class="form-control" name="branch_gstno" id="branch_gstno" placeholder="Branch Gstno" value="<?php echo $branch_gstno; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="varchar" class="col-sm-2 control-label">Branch Fax </label>
                    <div class="col-sm-10"><?php echo form_error('branch_fax') ?>
                        <input type="text" class="form-control" name="branch_fax" id="branch_fax" placeholder="Branch Taxcode" value="<?php echo $branch_fax; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="varchar" class="col-sm-2 control-label">Branch Add1 </label>
                    <div class="col-sm-10"><?php echo form_error('branch_add1') ?>
                        <input type="text" class="form-control" name="branch_add1" id="branch_add1" placeholder="Branch Add1" value="<?php echo $branch_add1; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="varchar" class="col-sm-2 control-label">Branch Add2 </label>
                    <div class="col-sm-10"><?php echo form_error('branch_add2') ?>
                        <input type="text" class="form-control" name="branch_add2" id="branch_add2" placeholder="Branch Add2" value="<?php echo $branch_add2; ?>" />
                    </div>
                </div>
            
        </div>
        <div class="col-md-6 form-horizontal">
            
                <div class="form-group"> 
                    <label for="inputEmail3" class="col-sm-2 control-label">Branch Add3 </label>
                    <div class="col-sm-10"><?php echo form_error('branch_add3') ?>
                        <input type="text" class="form-control" name="branch_add3" id="branch_add3" placeholder="Branch Add3" value="<?php echo $branch_add3; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="inputPassword3" class="col-sm-2 control-label">Branch Add4 </label>
                    <div class="col-sm-10"><?php echo form_error('branch_add4') ?>
                        <input type="text" class="form-control" name="branch_add4" id="branch_add4" placeholder="Branch Add4" value="<?php echo $branch_add4; ?>" />
                    </div>
                </div>
                <div class="form-group"> 
                    <label for="inputEmail3" class="col-sm-2 control-label">Branch Postcode </label>
                    <div class="col-sm-10"><?php echo form_error('branch_postcode') ?>
                        <input type="text" class="form-control" name="branch_postcode" id="branch_postcode" placeholder="Branch Postcode" value="<?php echo $branch_postcode; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Branch State </label>
                    <div class="col-sm-10"><?php echo form_error('branch_state') ?>
                        <input type="text" class="form-control" name="branch_state" id="branch_state" placeholder="Branch State" value="<?php echo $branch_state; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Branch Country </label>
                    <div class="col-sm-10"><?php echo form_error('branch_country') ?>
                        <input type="text" class="form-control" name="branch_country" id="branch_country" placeholder="Branch Country" value="<?php echo $branch_country; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Branch Group </label>
                    <div class="col-sm-10"><?php echo form_error('concept') ?>
                        <select name="branch_group" id="concept" class="form-control">
                            <option selected data-default style="display: none; "<?php echo $disabled ?> ><?php echo $branch_group_select?></option>
                            <?php 
                            foreach ($branch_group as $row)
                                {
                                    ?>
                                    <option value="<?php echo $row->branch_group_guid?>"><?php echo $row->group_name?></option>
                                    <?php
                                }
                            ?>     
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Concept</label>
                    <div class="col-sm-10"><?php echo form_error('concept') ?>
                        <select name="concept" id="concept" class="form-control">
                            <option selected data-default style="display: none; "<?php echo $disabled ?> ><?php echo $concept_select?></option>
                            <?php 
                            foreach ($concept as $row)
                                {
                                    ?>
                                    <option><?php echo $row->concept_name?></option>
                                    <?php
                                }
                            ?>     
                        </select>
                    </div>
                </div>

                 <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label><?php echo form_error('isactive') ?>
                            <input type="hidden" name="isactive" id="isactive" value="0" />
                                <input type="checkbox" name="isactive" id="isactive" value='1'
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
        <input type="hidden" name="created_at" value="<?php echo $created_at; ?>" /> 
        <input type="hidden" name="created_by" value="<?php echo $created_by; ?>" /> 
        <input type="hidden" name="branch_guid" value="<?php echo $branch_guid; ?>" /> 
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