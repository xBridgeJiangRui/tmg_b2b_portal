<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
<div class="row">
    <div class="col-md-12">
      
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $button ?> Account Concept</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

        <div class="col-md-6 form-horizontal">
            <form action="<?php echo $action; ?>" method="post">
                <div class="form-group"> 
                    <label for="inputEmail3" class="col-sm-2 control-label">Concept</label>
                    <div class="col-sm-10"><?php echo form_error('concept_name') ?>
                        <input type="text" class="form-control" name="concept_name" id="concept_name" placeholder="Concept Name" value="<?php echo $concept_name; ?>" />
                    </div>
                </div>
                <div class="form-group">
                     
                    <label for="inputPassword3" class="col-sm-2 control-label">Account</label>
                    <div class="col-sm-10"><?php echo form_error('acc') ?>
                        <select name="acc" id="acc" class="form-control">
                            <option selected data-default style="display: none;" <?php echo $disabled ?> ><?php echo $acc_select?></option>
                            <?php 
                            foreach ($acc as $row)
                                {
                                    ?>
                                    <option><?php echo $row->acc_name?></option>
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
        