<style type="text/css">
  #acc_branch{
    height: auto;
    overflow-y: scroll;

  }
  #acc_concepts{
    height: auto;
    overflow-x: auto;

  }
  .select2-container--default .select2-selection--multiple .select2-selection__choice
  {
    background: #3c8dbc;
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
<?php // echo var_dump($_SESSION); ?>

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
<!--               <form role="form" method="POST" id="myForm" action="<?php echo site_url('general/po_filter');?>"> -->
              <div class="col-md-2"><b>Date From<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="consign_from" name="consign_from" type="datetime" value="" readonly class="form-control pull-right">
              </div>
              <div class="col-md-2"><b>Date To<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-2">
                <input  id="consign_to" name="consign_to" type="datetime" class="form-control pull-right" readonly value="">
              </div>
              <div class="col-md-1">
                <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
              </div>

              <div class="clearfix"></div><br>
              
                <!-- </form> -->
            </div>

            <div class="row">
<!--               <form role="form" method="POST" id="myForm" action="<?php echo site_url('general/po_filter');?>"> -->

              <div class="col-md-2"><b>Code</b></div>
              <div class="col-md-8">
                <select id="consign_code" name="consign_code[]" class="select2 form-control" multiple required>
                  <option value="">Please Select One Code</option> 
                  <?php foreach($code as $row){ ?>
                    <option value="<?php echo $row->Code ?>"> 
                    <?php echo $row->Code.' - '.$row->Name; ?></option>
                 <?php } ?>
                </select>
              </div>

              <div class="col-md-2">
<!--                     <button id="consign_code_all" class="btn btn-primary" >ALL</button>
                    <button id="consign_code_all_dis" class="btn btn-danger" >X</button> -->
              </div>

              <div class="clearfix"></div><br>

                <!-- </form> -->
            </div>       

            <div class="row">
<!--               <form role="form" method="POST" id="myForm" action="<?php echo site_url('general/po_filter');?>"> -->
              <div class="col-md-2"><b>Location</b></div>
              <div class="col-md-8">
                <select id="consign_location" name="consign_location[]" class="form-control select2" multiple required>
                  <?php foreach($location as $row){ ?>
                    <option value="<?php echo $row->branch_code ?>"> 
                    <?php echo $row->branch_code.' - '.$row->branch_desc; ?></option>
                 <?php } ?>
                </select>
              </div>
              <div class="col-md-2">
                    <button id="consign_location_all" class="btn btn-primary" >ALL</button>
                    <button id="consign_location_all_dis" class="btn btn-danger" >X</button>
              </div>
              <div class="clearfix"></div><br>
                <!-- </form> -->
                    
            </div>     

            <div class="row">
              <div class="col-md-2">
                <a class="btn btn-success" id="refresh_report">Submit</a>
              </div>
            </div>      

          </div>
        </div>
        <!-- body -->

      </div>
    </div>
    
  </div>

<div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Consign Report</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concepts">
          <div id="accconceptCheck">
            <embed id="embed" height="750px" width="100%" src="<?php echo site_url('Consignment_report/consignment_sales_report_view');?>?link2="></embed>
          </div>

        </div>

      </div>
    </div>
  </div>
   
<!-- nothing ends after -->
</div>
</div>
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url('asset/plugins/jQuery/jquery-2.2.3.min.js')?>"></script>
<script type="text/javascript">
$(document).ready(function(){ 
  
  // $(document).on('click','#consign_code',function(){
  //     $('#consign_to').val("");
  // });

  $(document).on('click', '#consign_code_all', function(){
    // alert();
    $("#consign_code option").prop('selected',true);
    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('click', '#consign_code_all_dis', function(){
    // alert();
    $("#consign_code option").prop('selected',false);
    $(".select2").select2();
  });//CLOSE ONCLICK

  $(document).on('click', '#consign_location_all', function(){
    // alert();
    $("#consign_location option").prop('selected',true);
    $(".select2").select2();
  });//CLOSE ONCLICK  

  $(document).on('click', '#consign_location_all_dis', function(){
    // alert();
    $("#consign_location option").prop('selected',false);
    $(".select2").select2();
  });//CLOSE ONCLICK  
});
// $(document).ready(function(){
// alert();
// });

// var today = '<?php echo date('Y-m-d');?>';
var today = '<?php echo date('Y-m-d', strtotime(date('Y-m-01') . " - 1 month"));?>';
$(function() {
  $('input[name="consign_from"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    startDate: today,
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
    
  },function(start) {

    // alert(moment(start, 'DD-MM-YYYY').add(31, 'days'));
    qenddate = moment(start, 'DD-MM-YYYY').add(30, 'days');
    enddate = moment(start, 'DD-MM-YYYY').endOf('month');
    // var maxDate = start.addDays(5);
    // alert(maxDate);

        $('input[name="consign_to"]').daterangepicker({
          locale: {
            format: 'YYYY-MM-DD'
          },
          "minDate": start,
          "maxDate": qenddate,
          startDate: enddate,
          singleDatePicker: true,
          showDropdowns: true,
          autoUpdateInput: true,
        });
    });
  $(this).find('[name="consign_from"]').val(today);
});


$(function() {
  mend = '<?php echo date('Y-m-t', strtotime(date('Y-m-d') . " - 30 days"));?>';
  $('input[name="consign_to"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    "minDate": today,
    "maxDate": mend,
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
  $(this).find('[name="consign_to"]').val(mend);
});

  function expiry_clear()
  {
    $(function() {
        $(this).find('[name="consign_from"]').val("");
        $(this).find('[name="consign_to"]').val("");
    });
  }

  $(document).on('change','#consign_from',function(){
      $('#consign_to').val("");
  });

  $(document).on('click','#refresh_report',function(){

    var link = $('#embed').attr('src');

    var datestart = $('#consign_from').val();
    var dateend = $('#consign_to').val();

    var consign_code = $('#consign_code').val();

    var consign_location = $('#consign_location').val();

    if(datestart == '' || datestart == null)
    {
      alert('Please Choose Start Date');
      return;
    }

    if(dateend == '' || dateend == null)
    {
      alert('Please Choose End Date');
      return;
    }
 
    if(consign_code == '' || consign_code == null)
    {
      alert('Please Choose Code');
      return;
    }   

    if(consign_location == '' || consign_location == null)
    {
      alert('Please Choose Location');
      return;
    }    

    var a_link = $('#jasper_a_link').attr('href');

    var new_link = link+'&Date_From='+datestart+'&Date_To='+dateend;
    var a_new_link = a_link+'&Date_From='+datestart+'&Date_To='+dateend;

    $('#embed').attr('src', new_link);

    $('#jasper_a_link').attr('href', a_new_link);
   
    var clone = $('#accconceptCheck embed').clone(); 
    // $('#accconceptCheck embed').remove(); 
    // $('#accconceptCheck').append(clone)


    var details = [];

    var Code = $('#consign_code').val();

    var Location = $('#consign_location').val();

    var user_guid = '<?php echo $this->session->userdata('user_guid');?>';

    var session_guid = '<?php echo $this->session->userdata('user_logs');?>';

    if(Location == '' || Location == null)
    {
      alert('Please Choose Location');
      return;
    }  

    if(Code == '' || Code == null)
    {
      alert('Please Choose Code');
      return;
    } 

    Object.keys(Location).forEach(function(key) {

         details.push({'session_guid': session_guid, 'user_guid': user_guid, 'field':'location', 'value':Location[key]});

    });//close location

    var Array_status = Array.isArray(Code);

    Object.keys(Code).forEach(function(key) {

        if(Array_status == true)
          {
              push_code = Code[key];
          }
          else
          {
              push_code = Code;
          }

          details.push({'session_guid': session_guid, 'user_guid': user_guid, 'field':'consign_vendor_code', 'value':push_code});

    });//close code

    $.ajax({
            url:"<?php echo site_url('Consignment_report/consignment_sales_report_rest');?>",
            method:"POST",
            data:{details:details},
            beforeSend:function(){
              // alert();
              // $('.btn').button('loading');
            },
            success:function(data)
            {
              // alert();
              // json = JSON.parse(data);
              var id = data;
              var xid = id.substr(id.length - 1);
              // alert(xid);
              if(xid == '1')
              {
                $('#accconceptCheck embed').remove(); 
                $('#accconceptCheck').append(clone)
              }
              else
              {
                $('#accconceptCheck embed').remove(); 
                $('#accconceptCheck').append("Error occur")
              }


            }//close succcess
      });//close ajax

  });

</script>

