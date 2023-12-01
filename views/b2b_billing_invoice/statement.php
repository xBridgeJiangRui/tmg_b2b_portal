<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B Official Statement Supplier Selection</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
<!--           <div class="box-body">
          <div class="col-md-12">
              <div class="col-md-1">
                <label>Retailer</label>
              </div>
              <div class="col-md-6">
                <?php echo $customer_drop_down;?>
              </div>
          </div>

          </div> -->

          <div class="box-body">
          <div class="col-md-12">
              <div class="col-md-1">
                <label>Supplier</label>
              </div>
              <div class="col-md-6">
                <?php echo $supplier_drop_down;?>
              </div>
          </div>

          </div>


          <div class="box-body">

              <div class="col-md-4">
                  <div class="col-md-3">
                    <label>Date From</label>
                  </div>
                  <div class="col-md-9">
                      <input type="text" id="date_from" class="form-control datepickers" name="date_from" autocomplete="off" readonly style="background-color: white;">
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="col-md-3">
                    <label>Date To</label>
                  </div>
                  <div class="col-md-9">
                      <input type="text" id="date_to" class="form-control datepickers" name="date_to" autocomplete="off" readonly style="background-color: white;">
                  </div>
              </div>
              <div class="col-md-4">
                <button type="button" id="refresh_supplier_account" class="pull-left btn btn-primary">View</button>
              </div>

          </div>

      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Statement</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
          <div class="box-body">

            <div id="statement">
                <embed id="embed" height="750px" width="100%" src="<?php echo site_url('b2b_billing_invoice_controller/statement_report') ;?>">
            </div>

          </div>
      </div>
    </div>
  </div>

</div>
</div>

<script>
$(document).ready(function () {
calling = 0;
$('.datepickers').datepicker({
    forceParse: false,
    autoclose: false,
    format: 'yyyy-mm-dd'
});

function sa_popup_show(){

  swal.fire({
    allowOutsideClick: false,
    title: 'Processing...',
    showCancelButton: false,
    showConfirmButton: false,
    onOpen: function () {
      swal.showLoading()
      // AJAX request simulated with setTimeout
      setTimeout(function () {
        // swal.close()
      }, 1500000)
    }
  })
}

$(document).on('click','#refresh_supplier_account',function(){
  var supplier_guid = $('#receipt_supplier').val();
  var date_from = $('#date_from').val();
  var date_to = $('#date_to').val();

  if(supplier_guid == '' || supplier_guid == null)
  {
    alert('Please Select Supplier');
    return;
  }

  if(date_from == '' || date_from == null)
  {
    alert('Please Select Date From');
    return;
  }

  if(date_to == '' || date_to == null)
  {
    alert('Please Select Date To');
    return;
  }

  if(date_from > date_to)
  {
    alert('Date To Cannot Earlier than Date From');
    return;
  }

  var session_id = '12345';
  // alert(supplier_guid+customer_guid);

  var current_link = "<?php echo site_url('b2b_billing_invoice_controller/statement_report') ;?>";

  var new_link = current_link+'?acc_code='+supplier_guid+'&date_from='+date_from+'&date_to='+date_to+'&session='+session_id;

  $.ajax({
            url:"<?php echo site_url('b2b_billing_invoice_controller/set_download_session');?>",
            method:"POST",
            dataType: 'json',
            data:{session_id:session_id},
            beforeSend:function(){
              // $('.btn').button('loading');
            },
            success:function(data)
            {
              // alert(data.para);
              if(data.para == 1)
              { 
                $('#embed').attr('src', new_link);
                sa_popup_show();
                check_export_interval = setInterval(function() { check_export(session_id) }, 2500);
                
              }

            }//close success
          });//close ajax

  // $('#embed').attr('src', new_link);
  // return;
  // sa_popup_show();
  // check_export_interval = setInterval(check_export(session_id), 1000);
});//close submit location

check_export = function(session_id)
{ 
  // calling = 0;
  if(calling == 0)
  {
    $.ajax({
              url:"<?php echo site_url('b2b_billing_invoice_controller/check_download_session');?>",
              method:"POST",
              dataType: 'json',
              data:{session_id:session_id},
              beforeSend:function(){
                // $('.btn').button('loading');
                calling = 1;
              },
              success:function(data)
              {
                // console.log('check_download_session'+data);
                // alert(data.done_download);
                // alert(session_id);

                if(data.done_download == 1)
                { 
                    $.ajax({
                              url:"<?php echo site_url('b2b_billing_invoice_controller/unset_download_session');?>",
                              method:"POST",
                              dataType: 'json',
                              data:{session_id:session_id},
                              beforeSend:function(){
                                // $('.btn').button('loading');
                              },
                              success:function(data)
                              {
                                // alert(data.para);
                                  swal.close();
                                  clearInterval(check_export_interval);
                                  calling = 0;

                              }//close success
                            });//close ajax
                    // swal.close();
                    // clearInterval(check_export_interval);
                }
                else
                {
                    calling = 0;
                }

              }//close success
            });//close ajax
  }
}

<?php if(isset($_REQUEST['customer_guid']) && isset($_REQUEST['supp_guid']))
{
?>

// alert(1);
receipt_customer_guid = "<?php echo $_REQUEST['customer_guid'];?>";
$('#receipt_customer').val("<?php echo $_REQUEST['customer_guid'];?>");
receipt_supplier_guid = "<?php echo $_REQUEST['supp_guid'];?>";
$('#receipt_supplier').val("<?php echo $_REQUEST['supp_guid'];?>");

$.ajax({
  type: "POST",
  url: "<?php echo site_url('b2b_billing_invoice_controller/get_supplier_list_dropdown');?>",
  dataType: 'json',
  data : {customer_guid:receipt_customer_guid},
  success: function(data){
      // console.log(document.title, 'statement?customer_guid='+receipt_customer_guid+'&supp_guid='+receipt_supplier_guid);
      // history.pushState("", document.title, 'statement?customer_guid='+receipt_customer_guid+'&supp_guid='+receipt_supplier_guid);
      var check_url = window.location.href;
      var refresh = window.location.protocol + "//" + window.location.host + window.location.pathname + '?customer_guid='+receipt_customer_guid+'&supp_guid='+receipt_supplier_guid;
      // path = document.title, 'statement?customer_guid='+receipt_customer_guid+'&supp_guid='+receipt_supplier_guid;
      // setTimeout(function(){
      window.history.pushState({ path: refresh }, '', refresh);
      // },300);
      $('#receipt_supplier').html(data.dropdown);
      $('#receipt_supplier').val("<?php echo $_REQUEST['supp_guid'];?>");
  }
});

<?php
}
elseif(isset($_REQUEST['customer_guid']) && !isset($_REQUEST['supp_guid']))
{
?>

// alert(2);
receipt_customer_guid = "<?php echo $_REQUEST['customer_guid'];?>";
$('#receipt_customer').val("<?php echo $_REQUEST['customer_guid'];?>");
$.ajax({
  type: "POST",
  url: "<?php echo site_url('b2b_billing_invoice_controller/get_supplier_list_dropdown');?>",
  dataType: 'json',
  data : {customer_guid:receipt_customer_guid},
  success: function(data){
      // console.log(data.dropdown);
      history.pushState("", document.title, 'statement?customer_guid='+receipt_customer_guid);
      $('#receipt_supplier').html(data.dropdown);
  }
});
receipt_supplier_guid = '';
<?php
}
elseif(!isset($_REQUEST['customer_guid']) && isset($_REQUEST['supp_guid']))
{
?>

// alert(3);
receipt_supplier_guid = "<?php echo $_REQUEST['supp_guid'];?>";
// alert(receipt_supplier_guid);
$('#receipt_supplier').val("<?php echo $_REQUEST['supp_guid'];?>");
receipt_customer_guid = '';
<?php
}
else
{
?>
// alert(2);
receipt_supplier_guid = '';
receipt_customer_guid = '';
<?php
}
?>

$(document).on('change','#receipt_customer',function(){
  var customer_guid = $(this).val();
  // alert(customer_guid);
  // return;
  $.ajax({
    type: "POST",
    url: "<?php echo site_url('b2b_billing_invoice_controller/get_supplier_list_dropdown');?>",
    dataType: 'json',
    data : {customer_guid:customer_guid},
    success: function(data){
        // console.log(data.dropdown);
        history.pushState("", document.title, 'statement?customer_guid='+customer_guid);
        receipt_supplier_guid = '';     
        $('#receipt_supplier').html(data.dropdown);
    }
  });

});

$(document).on('change','#receipt_supplier',function(){
  receipt_supplier_guid = $(this).val();
  customer_guid = $('#receipt_customer').val();
  // alert(receipt_supplier_guid);
  history.pushState("", document.title, 'statement?customer_guid='+customer_guid+'&supp_guid='+receipt_supplier_guid);
});

  $(document).on('click','#statement',function(){
    //alert('Opps.');
    var redirect = $(this).attr('direct_view');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Choose Customer');

    methodd = '';

    methodd = '<form action="<?php echo site_url('login_c/outside_view_statement');?>" method="post">';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-12"><label>Customer Name</label><select class="form-control" name="acc_guid" id="acc_guid"> <option value="">-Select-</option> <?php foreach ($customer->result() as $key) { ?> <option value="<?php echo $key->acc_guid ?>"><?php echo $key->acc_name?></option> <?php } ?> </select></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="choose_acc" class="btn btn-success" value="Submit" redirect_data='+redirect+'> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p></form>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);
    
  });

  $(document).on('click','#choose_acc',function(){
    //alert('Opps.');
    var customer_guid = $('#acc_guid').val();
    var redirect_data = $(this).attr('redirect_data');
    var location = '';

    if(customer_guid == '')
    {
      alert('Please Select Customer to Proceed View Statement');
      return;
    }

    if((redirect_data == '') || (redirect_data == 'null') || (redirect_data == null))
    {
      alert('Invalid redirect. Please Contact Support.');
      return;
    }

    if(redirect_data == 'view_statement')
    {
      location = "<?= site_url('b2b_billing_invoice_controller/statement'); ?>";
    }

    if(redirect_data == 'view_receipt')
    {
      location  = "<?= site_url('b2b_billing_invoice_controller/official_receipt');?>";
    }

    $.ajax({
          url:"<?= site_url('Login_c/outside_view_statement');?>",
          method:"POST",
          data:{customer_guid:customer_guid},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              $('#medium-modal').modal('hide');
              $('.btn').button('reset');
              window.location = location;
              //redirect(site_url('b2b_billing_invoice_controller/statement'));
            }
          }//close success
        });//close ajax
  });
  
  })
</script>

