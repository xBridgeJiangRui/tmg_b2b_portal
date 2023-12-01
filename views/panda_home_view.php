<style>
.flex-container {
  display: flex;
}

.flex-container > div {
  padding: 40px;
}

.number_circle 
{
  /* background-color:#fff5dd; */
  background-color: #FD8B29;
  display:block;
  height:20px;
  width:20px;
  border-radius:50%;
  margin:auto;
  color:#fff;
  text-align:center;
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

  <div class="row">
    <div class="col-lg-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Home</h3><br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
      <div class="box-body" >
      <div class="col-md-3"></div>
      <div class="col-md-6">
     

        <div class="form-group">
        <p style="font-size: xx-large;word-break: break-word;">Welcome <?php echo $userid ; ?> !</p>
        <p> You are currently login to <b><?php echo $customer_name->row('acc_name') ; ?></b> profile</p>
        <!-- <p style="font-size: 13px">Please read the <a target = "_blank" href= "<?php echo base_url(); ?>user_guide/Manual_Guide.pdf">user manual</a> for more details.</p> -->
          
        </div>

        <?php //  echo var_dump($_SESSION); ?>

        </div>
<!--         <div class="col-md-3"> <a href="<?php echo site_url('dashboard/previous_announcement') ?>" class="btn btn-primary btn-xs pull-right">Read More <i class="fa fa-arrow-circle-right"></i></a></div> -->
        </div>
    </div>
</div>
</div>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-default">
            <!-- head -->
              <div class="box-header with-border">
                <h3 class="box-title">Sales Overview</h3>
                
                <div class="box-tools pull-right">
                  Date : <span><?php echo $latest_sales_datetime; ?></span>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
              </div>
              <div id="sales_overview-dashboard-layout"></div>
            <!-- ./col -->
          </div>
        </div>
      </div>

      <div class="row">
        <!-- left panel -->
        <div class="col-md-6">
          <div class="box box-default">
            <!-- head -->
              <div class="box-header with-border">
                <h3 class="box-title">Latest announcement</h3><br>
                <div class="box-tools pull-right">
                  <a href="<?php echo site_url('dashboard/previous_announcement') ?>" class="btn btn-primary btn-xs">Read More <i class="fa fa-arrow-circle-right"></i></a>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
              </div>
              <div class="box-body">
                 <!-- content --> 
                 <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-red">
                          <?php echo $announcement->row('docdate'); ?>
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-envelope bg-blue"></i>

                    <div class="timeline-item">
                      <h3 class="timeline-header"><?php echo $announcement->row('title'); ?></h3>

                      <div class="timeline-body">
                        <?php echo $announcement->row('content'); ?>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>
                </ul>
              </div>
          </div>
        </div>
        <!-- right panel -->

      <div class="col-md-6">
          <div class="box box-default">
            
              <div class="box-header with-border">
                <h3 class="box-title">Quick Acknowledgements</h3><br>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
              </div>
              <div class="box-body">
                  <?php foreach($show_announcement_sidebar->result() as $row){ ?>
                    <a class="btn"  onclick="ack_modal()"
                    data-toggle="modal" data-target="#selected_modal"
                    data-announcement_guid="<?php echo $row->announcement_guid;?>"
                    data-title="<?php echo $row->title;?>"
                    data-content="<?php echo $row->content;?>"
                    data-pdf_status="<?php echo $row->pdf_status;?>"
                    data-user_guid="<?php echo $_SESSION['user_guid'] ?>"
                     ><?php echo $row->title ?></a> - <?php echo $row->acknowledged_at ?><br>
                  <?php } ?>                
              </div>
          </div>
        </div> 
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Overview</h3>
                
                <div class="box-tools pull-right">
                  Date : <span><?php echo $latest_sales_datetime; ?></span>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                </div>

              </div>
              <div id="overview-dashboard-layout"></div>
          </div>
        </div>
      </div> 

<?php //  echo var_dump($_SESSION); ?>
</div>
</div>

<script type="text/javascript">
   $( document ).ready(function() {

    get_layout_sales_overview();
    get_layout_overview();

    var mandatory = "<?php echo $mandatory;?>";
    // // if(mandatory == 1)
    // // {
    //   $('#auto_modal').attr({
    //     "data-backdrop":"static",
    //     "data-keyboard":"false"
    //   });

    // // }
    //  $('#auto_modal').modal('show');

    
      // $.ajax({
      //       url:"<?= site_url('Query_outstanding/get_outstanding');?>",
      //       method:"POST",
      //       dataType:'JSON',
      //       beforeSend:function(){
      //         // $('.btn').button('loading');
      //         $('.btn').prop('disabled',true);
      //       },
      //       success:function(data)
      //       {   
      //         var modal = $('#new-medium-modal').modal();

      //         modal.find(".modal-title").html('Reminders');              
      //         // console.log(data.string);
      //         modal.find(".modal-body").html(data.string); 
      //         $('.btn').prop('disabled',false);
      //       }//close succcess
      //     });//close ajax




    //$(document).on('click','#new-medium-modal-close',function(){
<?php foreach($check_announcement_acknowledgement->result() as $index => $row) 
{
?>
var mandatory = "<?php echo $row->mandatory;?>";
        if(mandatory == 1)
        {
          
          $('#auto_modal<?php echo $index;?>').attr({
            "data-backdrop":"static",
            "data-keyboard":"false"
          });

          // var modal = $('#auto_modal').modal();
          var modal = $('#auto_modal'+<?php echo $index;?>).modal();

          // modal.find('a').prop('href','login_c/logout');

        }
        else
        {
          //$('#auto_modal<?php echo $index;?>').removeAttr("data-backdrop");
          //$('#auto_modal<?php echo $index;?>').removeAttr("data-keyboard");


          var modal = $('#auto_modal'+<?php echo $index;?>).modal();
          // var modal = $('#auto_modal').modal();

          // modal.find('a').removeAttr("href");
          // modal.find('a').attr('data-dismiss','modal');

        }//close else
<?php
}
?>        
    //});

});
   
</script>

<script type="text/javascript">
    function checkFileExist(urlToFile) {
    var xhr = new XMLHttpRequest();
    xhr.open('HEAD', urlToFile, false);
    xhr.send();
     
    if (xhr.status == "404") {
        return false;
    } else {
        return true;
    }
}
   function ack_modal()
  {
    $('#selected_modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var acc_guid = "<?php echo $session_guid; ?>"; 
      
      var modal = $(this);
      if(button.data('pdf_status') == 1)
      { 
        modal.find('[name="announcement_guid"]').val(button.data('announcement_guid'));
        modal.find('[name="user_guid"]').val(button.data('user_guid'));
        modal.find('[name="content"]').val(button.data('content'));
        modal.find('[name="title"]').val(button.data('title'));
        var guid = button.data('announcement_guid'); 

        var virtual_path = '';
        var content_name = button.data('content');
        //alert(virtual_path); 
        var cut_pdf = content_name.split('-+0+-');
        //alert(virtual_path);
        var file_name_url = ''; 
        Object.keys(cut_pdf).forEach(function(key) {

          // var result = checkFileExist("https://file.xbridge.my/b2b-pdf/ann_doc/"+acc_guid+"/"+guid+"/"+cut_pdf[key]+".pdf");
          // if (result == true) 
          // {
            virtual_path = "https://file.xbridge.my/b2b-pdf/ann_doc/"+acc_guid+"/"+guid+"/";
          // }
          // else
          // {
          //   virtual_path = "<?php echo $virtual_path.'/acceptance_form/'; ?>";
          // }

          file_name_url += '<embed src="'+virtual_path+cut_pdf[key]+'.pdf?time='+new Date().getTime()+'" class="selected_modal" width="100%" height="500px" style="border: none;"/>';

        });
         //alert(file_name_url);

        modal.find('.acceptance_title').html(button.data('title'));
        modal.find('#selected_modal_embed').html(file_name_url);
      }
      else
      {
        modal.find('[name="announcement_guid"]').val(button.data('announcement_guid'));
        modal.find('[name="user_guid"]').val(button.data('user_guid'));
        modal.find('[name="content"]').val(button.data('content'));
        modal.find('#selected_modal_embed').html('<div class="col-md-9"><textarea name="content" rows="10" cols="30" class="form-control" disabled>'+button.data('content')+'</textarea></div>');
        modal.find('.acceptance_title').html(button.data('title'));
      }
    });
  }
</script>

<script type="text/javascript">

  function get_layout_sales_overview(option = 'consign'){

    $.ajax({
        url: '<?php echo site_url('Dashboard/sales_overview_dashboard');?>',
        dataType: 'html',
        // type: 'POST',
        data: {
          'option': option
        },
        success: function(html) {    

          $.ajax({
            url: '<?php echo site_url('Dashboard/realtime_dashboard_date');?>',
            dataType: 'html',
            success: function(html1) {
              $('#current_datetime_sales_overview').html(html1);      
              $('#sales_overview-dashboard-layout').html(html);
            },
          });
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });

    $('#sales_overview-dashboard-layout').removeClass('hidden');
  }

</script>

<script type="text/javascript">

  function get_layout_overview(option = 'consign'){

    $.ajax({
        url: '<?php echo site_url('Dashboard/overview_dashboard');?>',
        dataType: 'html',
        // type: 'POST',
        data: {
          'option': option
        },
        success: function(html) {         
          $('#overview-dashboard-layout').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });

    $('#overview-dashboard-layout').removeClass('hidden');
  }

</script>
