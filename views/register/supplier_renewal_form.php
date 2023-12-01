<style type="text/css">

.content {
margin-top:2%;
}

.blink
{background-color:yellow;animation:blink 1s;animation-iteration-count:infinite;}

</style>

<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
  <div class="container">
    <section class="content" >
      <div class="box box-info" >
        <div class="box-header">
            <h3 class='box-title' style="padding-left:13px;"><b>Renewal Form</b></h3>
        </div>
        <div class="box-body" style ="font-size: 16px;">
            <?php
            foreach ($get_renewal->result() as $key) {
                if($key->renewal_guid == '' || $key->renewal_guid == 'null' || $key->renewal_guid == null)
                {
                    $value = date('d-M-Y', strtotime($key->renewal_start_at));
                    $value1 = date('d-M-Y', strtotime($key->renewal_end_at));
                }
                else
                {
                    $value = date('d-M-Y', strtotime($key->new_start_date));
                    $value1 = date('d-M-Y', strtotime($key->new_end_date));
                }
                
                
                ?>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2"><b>Retailer Name </b></div>
                        <div class="col-md-4"><?php echo $key->acc_name?></div>
                        <div class="clearfix"></div><br>

                        <div class="col-md-2"><b>Supplier Name </b></div>
                        <div class="col-md-4"><?php echo $key->supplier_name?></div>
                        <div class="clearfix"></div><br>

                        <div class="col-md-2"><b>Registration No </b></div>
                        <div class="col-md-4"><?php echo $key->reg_no?></div>
                        <div class="clearfix"></div><br>

                        <div class="col-md-2"><b>Template Name </b></div>
                        <div class="col-md-4"><?php echo $key->template_description?></div>
                        <div class="clearfix"></div><br>

                        <div class="col-md-2"><b>Template Fee </b></div>
                        <div class="col-md-4">RM <?php echo number_format($key->subsequent_fee,2)?></div>
                        <div class="clearfix"></div><br>

                        <div class="col-md-2"><b>Start Date </b></div>
                        <div class="col-md-4"><?php echo $value?></div>
                        <div class="clearfix"></div><br>

                        <div class="col-md-2"><b>Expired Date </b></div>
                        <div class="col-md-4"><?php echo $value1?></div>
                        <div class="clearfix"></div><br>
                    </div>
                </diiv>

            <?php
            } // end foreach here
            ?>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button id="view_pdf" style="float:left;margin-left:5px;" class="btn btn-warning" type="button" ><i class="fa fa-file"></i> &nbsp; Renewal Form </button>

          <!-- <button id="view_appendix" style="float:left;margin-left:5px;" class="btn btn-warning" type="button" ><i class="fa fa-file"></i> &nbsp; Appendix </button> -->

          <button id="accept_btn" style="float:right;" class="btn btn-success btn_form" type="button" ><i class="fa fa-check"></i> &nbsp; Agree </button>
          <!-- <button id="reject_btn" style="float:right;margin-right:5px;" class="btn btn-danger btn_form" type="button" ><i class="fa fa-close"></i> &nbsp; Decline </button> -->
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
$('document').ready(function(){

    renewal_guid = "<?php echo $_REQUEST['link'];?>";

    $(document).on('click', '.btn_form', function(event){

        var check_btn = $(this).attr('id');
 
        if(check_btn == 'accept_btn')
        {
            var modal = 'Accept';
            var status = '1';
        }
        else if(check_btn == 'reject_btn')
        {
            var modal = 'Decline';
            var status = '2';
        }
        else
        {
            alert('Invalid Process.Please Contact Admin.');
            return;
        }

        confirmation_modal("Are you sure want to "+modal+" ?");
        
        $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        
        $.ajax({
            url:"<?php echo site_url('Supplier_renewal/update_status') ?>",
            method:"POST",
            data:{renewal_guid:renewal_guid,status:status,modal:modal},
            beforeSend:function(){
            $('.btn').button('loading');
            },
            success:function(data)
            {
            json = JSON.parse(data);
            if (json.para1 == 'false') {
                $('#alertmodal').modal('hide');
                $('.btn').button('reset');
                alert(json.msg);
                location.reload();
            }else{
                $('.btn').button('reset');
                $('#alertmodal').modal('hide');
                alert(json.msg);
                location.reload();
            }//close else
            }//close success
        });//close ajax 
        });//close document yes click
    });//close update button


    $(document).on('click', '#view_pdf', function(event){

        var supplier_name = "<?php echo str_replace(' ','_',$supplier_name); ?>";
        var link = "<?php echo $link; ?>";
        var file_path = "<?php echo $file_path; ?>";
        //alert(file_path); die;
        
        var modal = $("#large-modal").modal();

        modal.find('.modal-title').html('Preview Form');

        methodd = '';

        methodd += '<div class="col-md-12">';

        methodd += '<label>Renewal Form</label>';

        methodd += '<embed src="<?php echo site_url('Invoice/view_renewal_report?link=');?>'+link+'&supplier_name='+supplier_name+'" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';

        if(file_path != '')
        {
            methodd += '<label>Appendix Form</label>';

            methodd += '<embed src="'+file_path+'" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';
        }

        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> </span> </p>';

        modal.find('.modal-body').html(methodd);
        modal.find('.modal-footer').html(methodd_footer);

        // setTimeout(function () { 
        //     alert('Please Download / Sign and send to us.');
        // }, 300);

    });//close update button

    //disable inspect element
    document.onkeydown = function(e) {
      if(event.keyCode == 123) {
         return false;
      }
      if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
         return false;
      }
      if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
         return false;
      }
      if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
         return false;
      }
      if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
         return false;
      }
    }

});
</script>