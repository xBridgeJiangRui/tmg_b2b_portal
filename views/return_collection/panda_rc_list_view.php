<style type="text/css">
.pull-center {
  text-align: center;
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
         <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?> ">
          <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>
  </div>

    <!-- filter by -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <!-- head -->
        <div class="box-header with-border">
          <h3 class="box-title">Filter By</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- head -->
        <!-- body -->
        <div class="box-body">
          <div class="col-md-12">
            <div class="row">
              <form role="form" method="POST" id="myForm" action="<?php echo site_url('general/po_filter');?>">
              <div class="col-md-2"><b>STRB Ref No</b></div>
              <div class="col-md-4">
                 <input id="po_num" name="po_num" type="text" autocomplete="off" class="form-control pull-right">
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Collection Status</b></div>
              <div class="col-md-4">
                <select name="po_status" class="form-control">
                  <?php foreach($po_status->result() as $row){ ?>
                    <option value="<?php echo $row->code ?>" 
                      <?php if($_REQUEST['status'] == $row->code && $_REQUEST['status'] != '')
                      {
                        echo 'selected';
                      } 
                      ?>
                    > 
                    <?php echo $row->reason; ?></option>
                 <?php } ?>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Document Date Range<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-4">
                <input required id="daterange" name="daterange" type="datetime" class="form-control pull-right" id="reservationtime" readonly>
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger"  onclick="date_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Expired Date From<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="expiry_from" name="expiry_from" type="datetime" value="" readonly class="form-control pull-right">
              </div>
              <div class="col-md-2"><b>Expired Date To<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="expiry_to" name="expiry_to" type="datetime" class="form-control pull-right" readonly value="" onchange="CompareDate()">
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
              <div class="col-md-4">
                <select name="period_code" class="form-control">
                  <option value="">None</option>
                  <?php foreach($period_code->result() as $row){ ?>
                    <option value="<?php echo $row->period_code ?>" 
                      <?php if(isset($_SESSION['filter_period_code'])){
                      if($_SESSION['filter_period_code'] == $row->period_code)
                      {
                        echo 'selected';
                      } }
                      ?>
                    > 
                    <?php echo $row->period_code; ?></option>
                 <?php } ?>
                </select> 
              </div>
              
              <div class="clearfix"></div><br>

              <div class="col-md-12">
                <input type="hidden" name="current_location" class="form-control pull-right" value="<?php echo $_REQUEST['loc']; ?>">
                <input type="hidden" name="frommodule" class="form-control pull-right" value="<?php echo $_SESSION['frommodule'] ?>">
                
                <button type="submit" id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                <a href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a>
                
              </div>
                <?php // var_dump($_SESSION) ?>
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
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Stock Return Batch</b></h3> &nbsp;

          <span class="pill_button"><?php 

          if ($_REQUEST['status'] == '') {
            $status = 'All';
          } else if ($_REQUEST['status'] == '0') {
            $status = 'Pending Accept';
          } else if ($_REQUEST['status'] == '2') {
            $status = 'Pending PRDN';
          } else if ($_REQUEST['status'] == '3') {
            $status = 'Pending PRDN';
          } else if ($_REQUEST['status'] == '4') {
            $status = 'NA';
          } else if ($_REQUEST['status'] == '8') {
            $status = 'Disupute';
          } else if ($_REQUEST['status'] == '9') {
            $status = 'Cancel';
          }


          echo ucfirst($status) ?></span>

          <span class="pill_button"><?php 

          if(in_array($check_loc, $hq_branch_code_array)) {
            echo 'All Outlet';
          } else {

            echo $_REQUEST['loc'];

          } ?>

          </span>

          <?php if ($_REQUEST['p_f'] != '' || $_REQUEST['p_t'] != '' ) { ?>

          <span class="pill_button"><?php 


          echo 'STRB Date Range : '. $_REQUEST['p_f'].' <i class="fa fa-arrow-right" aria-hidden="true"></i> '.$_REQUEST['p_t'];  ?>
            

          </span>

          <?php } ?>

          <?php if ($_REQUEST['e_f'] != '' || $_REQUEST['e_t'] != '' ) { ?>

          <span class="pill_button"><?php 


          echo 'Expired Date Range : '.$_REQUEST['e_f'].' <i class="fa fa-arrow-right" aria-hidden="true"></i> '.$_REQUEST['e_t'];  ?>
            

          </span>

          <?php } ?>

          <?php 

          if(isset($_SESSION['filter_period_code']))
            {

          if ($_SESSION['filter_period_code'] != ''  ) { ?>

          <span class="pill_button"><?php 


          echo $_SESSION['filter_period_code'];  ?>
            

          </span>

          <?php } } ?>

          <?php if ($_REQUEST['r_n'] != '') { ?>

          <span class="pill_button"><?php 


          echo $_REQUEST['r_n'];  ?>
            

          </span>

          <?php } ?>

          <br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
      <div class="box-body">
      <div class="col-md-12">
        <br>
        <div>
            <div class="row">
                <div class="col-md-12"  style="overflow-x:auto"> 
                    <table id="paloi" class="table table-bordered table-hover" >
                        <thead style="white-space: nowrap;">
                            <tr>
                            <?php // var_dump($_SESSION); ?>
                                <!--Begin=Column Header-->
                                <th>STRB Refno</th>
                                <th>Outlet</th>
                                <th>PRDN Refno</th>
                                <th>Document Date</th>
                                <th>Due Date</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Due In Day(s)</th>
                                <th>Cancel Reason</th>
                                <th>Accepted at</th>
                                <th>Accepted by</th>
                                <th>Action</th>
                                <!--End=Column Header-->
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
          </div>
             <!-- <p><a href="Panda_home/logout">Logout</a></p> -->
        </div> 
      </div>
    </div>
</div>
</div>
 
<?php //   echo var_dump($_SESSION); ?>
</div>
</div>

<script>
$(function() {
  $('input[name="daterange"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
  });
  //$('#daterange').data('daterangepicker').setStartDate('<?php echo date('Y-m-d', strtotime('-7 days')) ?>');
  //$('#daterange').data('daterangepicker').setEndDate('<?php echo date('Y-m-d') ?>');
  $(this).find('[name="daterange"]').val("");
});
</script>
 

<script type="text/javascript">
$(function() {
  $('input[name="expiry_from"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
  $(this).find('[name="expiry_from"]').val("");
});
</script>

<script type="text/javascript">
$(function() {
  $('input[name="expiry_to"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
  $(this).find('[name="expiry_to"]').val("");
});
</script>

<script type="text/javascript">
  function date_clear()
  {
    $(function() {
        $(this).find('[name="daterange"]').val("");
    });
  }

  function expiry_clear()
  {
    $(function() {
        $(this).find('[name="expiry_from"]').val("");
        $(this).find('[name="expiry_to"]').val("");
    });
  }
</script>

<script type="text/javascript">
   function CompareDate() {
       var dateOne = $('input[name="expiry_from"]').val(); //Year, Month, Date
       var dateTwo = $('input[name="expiry_to"]').val(); //Year, Month, Date
       if (dateOne > dateTwo) {
            alert("Expiry To : "+dateTwo+" Cannot Be a date before "+dateOne+".");
            $('#search').attr('disabled','disabled');
        }
        else 
        {
           $('#search').removeAttr('disabled');
        }

    }
</script>

<script>
$(document).ready(function () {    
  setTimeout(function(){
    $('#large-modal').attr({"data-backdrop":"static","data-keyboard":"false"}); //to remove clicking outside modal for closing
  },300);

  $(document).on('click','#btn_image',function(){
    var refno = $(this).attr('refno');
    var outlet = $(this).attr('outlet');
    var period_code = $(this).attr('period_code');
    var image_type = $(this).attr('image_type');

	  //alert(image_type); die;
    if((refno == '') || (refno == null) || (refno == 'null'))
    {
      alert('Invalid Get STRB RefNo.');
      return;
    }

    if((period_code == '') || (period_code == null) || (period_code == 'null'))
    {
      alert('Invalid Get Period Code.');
      return;
    }

    if((image_type == '') || (image_type == null) || (image_type == 'null'))
    {
      alert('Invalid Get Image Details.');
      return;
    }

    $.ajax({
        url:"<?php echo site_url('Panda_return_collection/strb_view_image') ?>",
        method:"POST",
        data:{refno:refno,period_code:period_code,image_type:image_type,outlet:outlet},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            var url = json.file_path_list;
            var name = url.toString().substring(url.lastIndexOf('/') + 1);
            
            var modal = $("#large-modal").modal();

            modal.find('.modal-title').html( 'STRB RefNo : <b>' + refno + '</b>');

            methodd = '';

            //methodd +='<div class="col-md-12">';

            //methodd += '<input type="hidden" class="form-control input-sm" id="supplier_guid" value="'+supplier_guid+'" readonly/>';

            Object.keys(url).forEach(function(key) {

              var before_name = url[key].toString().split('?')[0];
              var after_name = before_name.toString().split("/").slice(-1)[0];
              
              methodd += '<div class="col-md-12"><label>'+after_name+'<span id="alert'+key+'"></span></label>';

              methodd += '<input style="float:right;" type="button" id="show'+key+'" class="btn btn-primary view_image_btn" value="Show" path_url="'+url[key]+'" key="'+key+'" image_name="'+after_name+'" >';

              methodd += '<input style="float:right;margin-right:5px;" type="button" id="show'+key+'" class="btn btn-warning dl_image_btn" value="Download" path_url="'+url[key]+'" key="'+key+'">';

              methodd += '</div>';

              methodd += '<div class="clearfix"></div><br>';

              methodd += '<div class="col-md-12"><span id="image'+key+'"></span></div>';

            });

            //methodd += '</div>';

            methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);

            setTimeout(function(){

              $(document).off('click', '.view_image_btn').on('click', '.view_image_btn', function(){
                var path_url = $(this).attr('path_url');
                var key = $(this).attr('key');
                var image_name = $(this).attr('image_name');
                var value = $(this).val();
                $(this).hide();
                
                if(value == 'Show') 
                {
                  $.ajax({
                    url:"<?php echo site_url('General/strb_show_image_logs');?>",
                    method:"POST",
                    data:{refno:refno,image_name:image_name,},
                    beforeSend:function(){
                      $('.btn').button('loading');
                    },
                    success:function(data)
                    {
                      json = JSON.parse(data);
                      if (json.para1 == 'false') {
                        $('.btn').button('reset');
                        $('#alert'+key+'').html(' - '+json.msg);
                      }else{
                        $('.btn').button('reset');
                        //$(this).attr('value','Hide');
                        //$(this).attr('class','btn btn-danger view_image_btn');
                        $('#image'+key+'').html('<embed src="'+path_url+'" width="100%" height="800px" style="border: none;" toolbar="0" id="image_view'+key+'"/>');
                      }//close else
                    }//close success
                  });//close ajax
                }
                
                // if(value == 'Hide') 
                // {
                //   $(this).attr('value','Show');
                //   $(this).attr('class','btn btn-primary view_image_btn');
                //   $('#image'+key+'').html('');
                //   //alert('show image'); die;
                // }
              });//close modal create

              $(document).off('click', '.dl_image_btn').on('click', '.dl_image_btn', function(){
                var path_url = $(this).attr('path_url');
                var value = $(this).val();
                
                if(value == 'Download') 
                {
                  var form = document.createElement('a');
                  form.href = path_url;
                  form.download = path_url;
                  document.body.appendChild(form);
                  form.click();
                  alert('Download Successful'); 
                  $(this).attr('value','Download Complete');
                  $(this).prop('disabled', true);
                } 
              });//close modal create
            },300);
          }//close else
        }//close success
      });//close ajax 
  });//close image process
});
</script>
