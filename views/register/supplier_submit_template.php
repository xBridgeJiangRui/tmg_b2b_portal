<style>
.content-wrapper {
min-height: 720px !important;
}

.content {
margin-top:5%;
height:150px;
}

.blink
{background-color:red;animation:blink 2s;animation-iteration-count:infinite;color:black;}
</style>
  
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
  <div class="container">
    <section class="content">
      <div class="box box-default" style ="text-align: center;" >
        <!-- <div class="box-header with-border"> -->
          <h1 style ="text-align: center;"><i class="fa fa-check-circle" style="color:green;font-size:3.0em;"></i></span></h1>
          <h2 style ="text-align: center;">Thank you for Registering <b>Sunshine B2B Portal</b>.</h2>
        <!-- </div> -->
        <div class="box-body">
          <h3>Your <?php echo $form_name ?> will be processed within 1 day.
          <br><?php echo $content1 ?>
          <br>Have a nice day!
          </h3>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a id="view_form" title="FORM" style="margin-top:5px;" class="btn btn-primary" type="button" href=<?php echo $reg_url?> ><i class="fa fa-file-text"></i> &nbsp; View Submitted Form</a>

          <a style="margin-left:5px;margin-top:5px;" type="button" class="btn btn-default blink" href="<?php echo $acceptance_path ?>" target="_blank" ><i class="fa fa-file-text"></i> Download Acceptance Form </a>

          <button title="Terms" id="term_btn" type="button" class="btn btn-md btn-primary" ><i class="fa fa-file" aria-hidden="true"></i>&nbsp&nbspTerms</button> 
          
          <!-- <?php 
          if(($memo_type != 'outright') && ($memo_type != 'consignment') && ($memo_type != 'both'))
          {
            ?>
            <a id="dl_normal_btn" memo_data="<?php echo $memo_type ?>" style="margin-left:5px;margin-top:5px;" type="button" class="btn btn-default blink" href="<?php echo site_url('Invoice/view_report_term?reg_guid=');?><?php echo $register_guid ?>&form_type=normal&supplier_name=<?php echo $supplier_name ?>" target="_blank" ><i class="fa fa-file-text"></i> Download Term Sheet</a> 

            <a id="dl_special_btn" memo_data="<?php echo $memo_type ?>" style="margin-left:5px;margin-top:5px;" type="button" class="btn btn-default blink" href="<?php echo site_url('Invoice/view_term_special?reg_guid=');?><?php echo $register_guid ?>&form_type=special&supplier_name=<?php echo $supplier_name ?>" target="_blank" ><i class="fa fa-file-text"></i> Download Special Term Sheet</a> 

            <?php 
            if($acceptance_path != 'hide')
            {
              ?>
              <a style="margin-left:5px;margin-top:5px;" type="button" class="btn btn-default blink" href="<?php echo $acceptance_path.'CKS_xBridge_B2B_Letter_of_Acceptance_Form.pdf' ?>" target="_blank" ><i class="fa fa-file-text"></i> Download Acceptance Form </a> 
              <?php
            }
            ?>
            <?php
          }
          else
          {
            ?>
            <a id="dl_normal_btn" memo_data="<?php echo $memo_type ?>" style="margin-top:5px;" type="button" class="btn btn-default blink" href="<?php echo site_url('Invoice/view_report_term?reg_guid=');?><?php echo $register_guid ?>&form_type=normal&supplier_name=<?php echo $supplier_name ?>" target="_blank" ><i class="fa fa-file-text"></i> Download Term Sheet</a> 

            <?php 
            if($acceptance_path != 'hide')
            {
              ?>
              <a style="margin-left:5px;margin-top:5px;" type="button" class="btn btn-default blink" href="<?php echo $acceptance_path.'CKS_xBridge_B2B_Letter_of_Acceptance_Form.pdf' ?>" target="_blank" ><i class="fa fa-file-text"></i> Download Acceptance Form </a> 
              <?php
            }
            ?>
            <?php
          }
          ?> -->
        </div>
        <!-- /.box-footer -->
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.container -->
</div>
<script type="text/javascript">
$(document).ready(function(){
  
  $('#term_btn').hide();

  setTimeout(function () { 
    $('#term_btn').click();
  }, 300);

  $(document).on('click','#term_btn',function(){

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Preview Acceptance Form');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<embed src="<?php echo $acceptance_path ?>" width="100%" height="500px" style="border: none;" toolbar="1" id="pdf_view"/>';
  
    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> </span>';

    modal.find('.modal-body').html(methodd);
    modal.find('.modal-footer').html(methodd_footer);

  });// close

});
</script>

