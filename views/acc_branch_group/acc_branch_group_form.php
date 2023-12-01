<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
<div class="row">
    <div class="col-md-12">
      
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $button ?> Account Branch Group</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">


        <div class="col-md-6 form-horizontal">
            <form action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Group Name</label>
                    <div class="col-sm-10"><?php echo form_error('group_name') ?>
                        <input type="text" class="form-control" name="group_name" id="group_name" placeholder="Group Name" value="<?php echo $group_name; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="inputPassword3" class="col-sm-2 control-label">Concept</label>
                    <div class="col-sm-10"><?php echo form_error('concept') ?>
                        <select name="concept" id="concept" class="form-control">
                            <option selected data-default style="display: none;" <?php echo $disabled?> ><?php echo $concept_select?></option>
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
                            <input type="checkbox"  name="isactive" id="isactive"
                             placeholder="Isactive" value="1" 
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
        <input type="hidden" name="branch_group_guid" value="<?php echo $branch_group_guid; ?>" /> 
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