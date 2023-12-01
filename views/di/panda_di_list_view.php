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
        <a class="btn btn-app pull-right"  style="color:#000000"  onclick="bulk_print()" >
          <i class="fa fa-print"></i> Print
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
                <form role="form" method="POST" id="myForm" action="<?php echo site_url('general/po_filter'); ?>">
                  <div class="col-md-2"><b>Invoice Ref No</b></div>
                  <div class="col-md-4">
                    <input id="po_num" name="po_num" type="text" autocomplete="off" class="form-control pull-right">
                  </div>
                  <div class="clearfix"></div><br>

                  <div class="col-md-2"><b>DI Status</b></div>
                  <div class="col-md-4">
                    <select name="po_status" class="form-control">
                      <?php foreach ($po_status->result() as $row) { ?>
                        <option value="<?php echo $row->code ?>" <?php if (strtolower($_REQUEST['status']) == strtolower($row->code)) {
                                                                    echo 'selected';
                                                                  }
                                                                  ?>>
                          <?php echo $row->reason; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="clearfix"></div><br>

                  <div class="col-md-2"><b>Doc Date Range<br>(YYYY-MM-DD)</b></div>
                  <div class="col-md-4">
                    <input required id="daterange" name="daterange" type="datetime" class="form-control pull-right" id="reservationtime" readonly>
                  </div>
                  <div class="col-md-2">
                    <a class="btn btn-danger" onclick="date_clear()">Clear</a>
                  </div>
                  <div class="clearfix"></div><br>

                  <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
                  <div class="col-md-4">
                    <select name="period_code" class="form-control">
                      <option value="">None</option>
                      <?php foreach ($period_code->result() as $row) { ?>
                        <option value="<?php echo $row->period_code ?>" <?php if (isset($_SESSION['filter_period_code'])) {
                                                                          if ($_SESSION['filter_period_code'] == $row->period_code) {
                                                                            echo 'selected';
                                                                          }
                                                                        }
                                                                        ?>>
                          <?php echo $row->period_code; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="clearfix"></div><br>

                  <div class="col-md-12">
                    <input type="hidden" name="current_location" class="form-control pull-right" value="<?php echo $_REQUEST['loc']; ?>">
                    <input type="hidden" name="frommodule" class="form-control pull-right" value="<?php echo $_SESSION['frommodule'] ?>">

                    <button type="submit" id="search" class="btn btn-primary" ><i class="fa fa-search"></i> Search</button>
                    <!-- an F5 function -->
                    <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                    <!-- an RESER function -->
                    <a href="<?php echo site_url($_SESSION['frommodule']) ?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=&first=1" class="btn btn-default"><i class="fa fa-repeat"></i> Reset</a>

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
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Display Incentive</b></h3>
          <span class="pill_button"><?php
            if ($_REQUEST['status'] == '') {
              $status = 'New';
              //$status = 'All';
            } else {
              $status = $_REQUEST['status'];
            }

            echo ucfirst($status) ?></span>

            <span class="pill_button"><?php

            if (in_array($check_loc, $hq_branch_code_array)) {
              echo 'All Outlet';
            } else {

              echo $location_description->row('BRANCH_CODE') . ' - ' . $location_description->row('branch_desc');
            } ?>

            </span>

            <?php if ($_REQUEST['p_f'] != '' || $_REQUEST['p_t'] != '') { ?>

            <span class="pill_button"><?php


              echo 'Doc Date Range : ' . $_REQUEST['p_f'] . ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' . $_REQUEST['p_t'];  ?>


            </span>

            <?php } ?>

            <?php

            if (isset($_SESSION['filter_period_code'])) {

            if ($_SESSION['filter_period_code'] != '') { ?>

            <span class="pill_button"><?php


                echo $_SESSION['filter_period_code'];  ?>


            </span>

            <?php }
            } ?>

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
                        <thead>
                            <tr>
                            <?php // var_dump($_SESSION); ?>
                                <!--Begin=Column Header-->
                                <th>Invoice Refno</th>
                                <th>Refno</th>
                                <th>Outlet</th>
                                <th>Supplier Code</th>
                                <th>Supplier Name</th>
                                <th>Documet Date</th>
                                <th>Due Date</th>
                                <th>Total Net</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th><input type="checkbox" id="check-all"></th>
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
    $(this).find('[name="daterange"]').val("");
  });
</script>

<script type="text/javascript">
  function date_clear() {
    $(function() {
      $(this).find('[name="daterange"]').val("");
    });
  }
</script>

<script type="text/javascript">
  function bulk_print()
  {
    var list_id = [];
    $(".data-check:checked").each(function() {
            list_id.push(this.value);
    });
     if(list_id.length > 1)
    {
      // alert('use merge');
            $.ajax({
            type: "POST",
            data: {id:list_id},
            url: "<?php echo site_url('general/merge_pdf?loc='.$_REQUEST['loc'].'&po_type=DI')?>",
            dataType: "JSON",
            success: function(data)
            { 
                // alert(data.link_url);
                if(data.link_url)
                {
                   
                   var newwin = window.open(data.link_url); 
                    newwin.onload = function() {

                      setTimeout(function(){

                        var url_link = data.pdf_file;

                        $.ajax({
                                type: "POST",
                                data: {url_link:url_link},
                                url: "<?php echo site_url('general/unlink_file')?>",
                                dataType: "JSON",
                                success: function(data)
                                { 
                                  alert('delete success'+data);
                                }//close success
                              });//close ajax

                      },1000);
                    
                    };//close onload
                }
                else
                {
                    alert('Failed.');
                }
                
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error Opening data');
            }
        });
    }
    else if(list_id.length > 0)
    {
        if(confirm('Are you sure open this '+list_id.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {id:list_id},
                url: "<?php echo site_url('general/ajax_bulk_print?loc='.$_REQUEST['loc'])?>",
                dataType: "JSON",
                success: function(data)
                { 
                    //alert(data.link_url);
                    if(data.link_url)
                    {
                      data.link_url.forEach(function(element){
                        window.open(element); 
                      });
                       
                    }
                    else
                    {
                        alert('Failed.');
                    }
                    
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error Opening data');
                }
            });
        }
    }
    else
    {
        alert('no data selected');
    }
  }

</script>