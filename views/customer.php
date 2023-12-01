<!-- Content Wrapper. Contains page content -->

<style>
  .imagess {
    opacity: 1;
    display: block;
    width: 100%;
    height: auto;
    transition: .5s ease;
    backface-visibility: hidden;
  }

  .middle {
    transition: .5s ease;
    opacity: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    text-align: center;
  }

  .img_wrap:hover .imagess {
    opacity: 0.3;
  }

  .img_wrap:hover .middle {
    opacity: 1;
  }

  .buttonn {
    background-color: #ef5411;
    color: white;
    font-size: 13px;
    padding: 1em 1em;
    outline: none;
    box-shadow: 2px 4px #888888;
  }
</style>

<div class="content-wrapper">
  <div class="container-fluid">
    <br>
    <?php
    if ($this->session->userdata('message')) {
    ?>
      <div class="alert alert-success text-center" style="font-size: 18px">
        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
        <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
      </div>
    <?php
    }
    ?>

    <?php
    if ($this->session->userdata('warning')) {
    ?>
      <div class="alert alert-danger text-center" style="font-size: 18px">
        <?php echo $this->session->userdata('warning') <> '' ? $this->session->userdata('warning') : ''; ?>
        <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
      </div>
    <?php
    }
    ?>

    <?php //  var_dump($_SESSION) 
    ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Select Customer (Click on the logo button to select customer)</h3><br>
            <!-- <?php echo $title_accno ?> -->
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
            </div>
          </div>
          <div class="box-body">

            <!-- <form action="<?php echo site_url('login_c/customer_setsession'); ?>" method='post'> -->
            <span class="append_blocked"></span>
            <?php $i = 1;
            foreach ($customer->result() as $row) {

              if ($i == 1) {
                $last = $row->seq;
              }

              if ($last != $row->seq) {
                echo '<div class="row"></div><hr>';
                $last = $row->seq;
              }

            ?>
              <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 img_wrap">

                <img src="<?php echo $row->logo; ?>" alt="<?php echo $row->acc_name; ?>" class="imagess img-square" style="width:100%">
                <div class="middle">
                  <!-- <button class="buttonn img-circle" name="customer" type="submit" id="choose_acc<?php echo $i ?>" value="<?php echo $row->acc_guid ?>" hidden><?php echo $row->acc_name; ?></button> -->

                  <button class="buttonn img-circle" name="choose_retailer" id="choose_retailer" type="button" acc_guid="<?php echo $row->acc_guid ?>" seq="<?php echo $i ?>" register_guid="<?php echo $row->register_guid ?>"  m_l="<?php echo $row->maintenance ?>" m_d="<?php echo $row->maintenance_date ?>"><?php echo $row->acc_name; ?></button>


                </div>

              </div>

            <?php
              $i++;
            }
            ?>

            </form>

          </div>
        </div>
      </div>
    </div>
    <?php // echo var_dump($_SESSION);    die; 
    ?>
  </div>
</div>
<script>
  $(document).ready(function() {

    $(document).on('click', '#choose_retailer', function() {

      var acc_guid = $(this).attr('acc_guid');
      var seq = $(this).attr('seq');
      var register_guid = (typeof $(this).attr('register_guid') !== "undefined") ? $(this).attr('register_guid') : "";
      var maintenance = $(this).attr('m_l');
      var maintenance_date = $(this).attr('m_d');
      var user_cred = '<?php echo $_SESSION['user_group_name']?>';

      if (register_guid !== "") {
        if (register_guid.includes(',') == true) {
          register_guid = register_guid.split(",");

          register_guid.forEach((url) => {
            // alert(url);
            window.open(url, '_blank');
          })

        } else {
          //window.location.replace(register_guid);
          window.open(register_guid, '_blank', 1000);

        }

      }
      else if (maintenance == '1' && user_cred != 'SUPER_ADMIN') {
        var modal = $("#propose_medium-modal").modal();

        modal.find('.modal-title').html('xBridge B2B Portal Downtime');

        methodd = '';

        methodd +='<div class="col-md-12">';

        methodd += '<span style="font-size:16px;line-height:1.5;"> Attention to all Suppliers,<br><br> We would like to inform that xBridge B2B portal is performing a schedule downtime maintenance from <b><mark style="background-color:yellow;"> 25-Nov-22 (Friday) at 9:00 p.m. </mark></b> to <b><mark style="background-color:yellow;"> 26-Nov-22 (Saturday) at 11:59 p.m. </mark></b> Thus xBridge B2B Portal will not be <b><span style="color:red;">accessible during this 2 days period.</span></b> <br><br> We are sorry for the all the inconvenience caused and do let us know if there are any clarifications needed.</span>' ;

        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span> </p>';

        modal.find('.modal-body').html(methodd);
        modal.find('.modal-footer').html(methodd_footer);
      }
      else {
        //alert('Jiang Rui is doing some changes.Thank you');
        $.ajax({
          url: "<?= site_url('Query_outstanding/get_outstanding'); ?>",
          method: "POST",
          data: {
            acc_guid: acc_guid
          },
          dataType: 'JSON',
          beforeSend: function() {
            $('.btn').button('loading');
            $('.buttonn').button('loading');
          },
          success: function(data) {
            //json = JSON.parse(data);
            if (data.para1 == '1') {
              alert(data.msg);
              $('.btn').button('reset');
            } else {
              if (data.result > '0') {
                // query outstanding modal
                //alert(data.reminder_count);
                //alert(data.store_supp_guid);
                $('.btn').button('reset');
                $('.buttonn').button('reset');
                var modal = $('#propose_medium-modal').modal();
                modal.find(".modal-title").html('Payment Reminder');
                modal.find(".modal-body").html(data.string);

                setTimeout(function() {
                  $('.blink').css({
                    'background-color': '#ff6666',
                    'animation': 'blink 3s',
                    'animation-iteration-count': 'infinite'
                  })
                }, 300);

                if (data.reminder_count > 0) {
                  if ((data.store_supp_guid != '') && (data.store_supp_guid != 'null') && (data.store_supp_guid != null)) {
                    $('.append_blocked').html('<input type="hidden" id="blocked_guid" name="blocked_guid" value="' + data.store_supp_guid + '" readonly />');
                  }

                }

                if (data.force_logout == 1) {
                  $('.append_blocked').html('');
                  <?php if ($_SESSION['user_group_name'] == "OUTRIGHT_CONSIGNMENT_GROUP" || $_SESSION['user_group_name'] == "CONSIGNMENT_GROUP" || $_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == "SUPP_ADMIN" || $_SESSION['user_group_name'] == "LIMITED_SUPP_ADMIN" || $_SESSION['user_group_name'] == "LIMITED_OUTRIGHT_CONSIGNMENT_GROUP" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE'
                  || $_SESSION['user_group_name'] == 'EDI_SUPP_ADMIN') {
                  ?>
                    if (data.check_extend_data > 0) {
                      methodd_footer = '<span class="pull-left"><button type="button" id="view_invoice" class="btn btn-warning" >View & Download Invoices</button><button type="button" id="view_acc_statement" class="btn btn-warning" acc_guid="' + acc_guid + '">Account Statement</button> <button type="button" id="'+data.button_extend+'" class="btn btn-danger" acc_guid="' + acc_guid + '" user_guid="' + data.store_user_guid + '">Request Payment Extension</button> </span><input type="button" id="reminder_close" class="btn btn-default" data-dismiss="modal" value="Close">';
                    } else {
                      methodd_footer = '<span class="pull-left"><button type="button" id="view_invoice" class="btn btn-warning" >View & Download Invoices</button><button type="button" id="view_acc_statement" class="btn btn-warning" acc_guid="' + acc_guid + '">Account Statement</button></span><input type="button" id="reminder_close" class="btn btn-default" data-dismiss="modal" value="Close">';
                    }
                    // methodd_footer = '<span class="pull-left"><button type="button" id="view_invoice" class="btn btn-warning" >View & Download Invoices</button><button type="button" id="view_acc_statement" class="btn btn-warning" acc_guid="' + acc_guid + '">Account Statement</button></span><input type="button" id="reminder_close" class="btn btn-default" data-dismiss="modal" value="Close">';

                  <?php
                  } else {
                  ?>
                    methodd_footer = '<input type="button" id="reminder_close" class="btn btn-default" data-dismiss="modal" value="Close">';
                  <?php
                  } ?>

                  modal.find(".modal-footer").html(methodd_footer);
                  //modal.find(".modal-footer").html('<input type="button" id="reminder_close" class="btn btn-default" data-dismiss="modal" value="Close">'); 
                  modal.find(".modal-header").find('button').html('');
                } else {
                  <?php if ($_SESSION['user_group_name'] == "OUTRIGHT_CONSIGNMENT_GROUP" || $_SESSION['user_group_name'] == "CONSIGNMENT_GROUP" || $_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == "SUPP_ADMIN" || $_SESSION['user_group_name'] == "LIMITED_SUPP_ADMIN" || $_SESSION['user_group_name'] == "LIMITED_OUTRIGHT_CONSIGNMENT_GROUP" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE' 
                  || $_SESSION['user_group_name'] == 'EDI_SUPP_ADMIN' ) {
                  ?>
                    if (data.check_extend_data > 0) {
                      methodd_footer = '<span class="pull-left"><button type="button" id="view_invoice" class="btn btn-warning" >View & Download Invoices</button><button type="button" id="view_acc_statement" class="btn btn-warning" acc_guid="' + acc_guid + '">Account Statement</button> <button type="button" id="'+data.button_extend+'" class="btn btn-danger" acc_guid="' + acc_guid + '" user_guid="' + data.store_user_guid + '">Request Payment Extension</button> </span><button type="button" id="select_button" class="btn btn-success" acc_guid="' + acc_guid + '">OK</button>';
                    } else {
                      methodd_footer = '<span class="pull-left"><button type="button" id="view_invoice" class="btn btn-warning" >View & Download Invoices</button><button type="button" id="view_acc_statement" class="btn btn-warning" acc_guid="' + acc_guid + '">Account Statement</button></span><button type="button" id="select_button" class="btn btn-success" acc_guid="' + acc_guid + '">OK</button>';
                    }

                    //methodd_footer = '<span class="pull-left"><button type="button" id="view_invoice" class="btn btn-warning" >View & Download Invoices</button><button type="button" id="view_acc_statement" class="btn btn-warning" acc_guid="' + acc_guid + '">Account Statement</button></span><button type="button" id="select_button" class="btn btn-success" acc_guid="' + acc_guid + '">OK</button>';

                    //($_SESSION['user_group_name'] == "OUTRIGHT_CONSIGNMENT_GROUP" || $_SESSION['user_group_name'] == "CONSIGNMENT_GROUP" || $_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == "SUPP_ADMIN" || $_SESSION['user_group_name'] == "LIMITED_SUPP_ADMIN" || $_SESSION['user_group_name'] == "LIMITED_OUTRIGHT_CONSIGNMENT_GROUP" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE') 

                  <?php
                  } else {
                  ?>
                    methodd_footer = '<button type="button" id="select_button" class="btn btn-success" acc_guid="' + acc_guid + '">OK</button>';
                  <?php
                  } ?>

                  modal.find(".modal-footer").html(methodd_footer);
                }

                // $(document).on('click','#new-medium-modal-close',function(){
                //   $('#choose_acc'+seq).click();
                // });

              } //close else
              else {
                //$('#choose_acc'+seq).click();
                $('.buttonn').button('reset');
                var blocked_guid = $('#blocked_guid').val();
                var redirect_location = '';

                if ((acc_guid == '') || (acc_guid == 'null') || (acc_guid == null)) {
                  alert('Error to Get Customer.Please Contact Support.');
                  return;
                }

                if ((blocked_guid == '') || (blocked_guid == 'null') || (blocked_guid == null)) {
                  blocked_guid = '';
                }

                $.ajax({
                  url: "<?php echo site_url('Login_c/customer_setsession') ?>",
                  method: "POST",
                  data: {
                    customer: acc_guid,
                    blocked_guid: blocked_guid
                  },
                  beforeSend: function() {
                    $('.btn').button('loading');
                  },
                  success: function(data) {
                    json = JSON.parse(data);
                    redirect = json.redirect;

                    if (json.para == 1) {
                      //$('.btn').button('reset');

                      if ((redirect == '') || (redirect == 'null') || (redirect == null)) {
                        alert('Error to Redirect Dashboard.Please Contact Support.');
                        return;
                      }

                      if (redirect == 'dashboard') {
                        redirect_location = "<?= site_url('dashboard'); ?>";
                      }

                      if (redirect == 'panda_home') {
                        redirect_location = "<?= site_url('panda_home'); ?>";
                      }

                      window.location = redirect_location;
                      //redirect(site_url(redirect));

                    } else {
                      $('.btn').button('reset');
                      alert(json.msg);
                      setTimeout(function() {
                        location.reload();
                      }, 300);
                    }

                  } //close success
                }); //close ajax 
              }
            }

          } //close success
        }); //close ajax
      }


    });

    $(document).on('click', '#reminder_close', function() {
      alert('Your Account has been blocked. Please Bill the Total Amount Due to us. Thank you.');
      return;
    });

    $(document).on('click', '.close', function() {
      $('.buttonn').button('reset');
    });

    $(document).on('click', '#statement', function() {
      //alert('Opps.');
      var redirect = $(this).attr('direct_view');

      var modal = $("#medium-modal").modal();

      modal.find('.modal-title').html('Choose Customer');

      methodd = '';

      methodd = '<form action="<?php echo site_url('login_c/outside_view_statement'); ?>" method="post">';

      methodd += '<div class="col-md-12">';

      methodd += '<div class="col-md-12"><label>Customer Name</label><select class="form-control" name="acc_guid" id="acc_guid"> <option value="">-Select-</option> <?php foreach ($customer->result() as $key) { ?> <option value="<?php echo $key->acc_guid ?>"><?php echo $key->acc_name ?></option> <?php } ?> </select></div>';

      methodd += '</div>';

      methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="choose_acc" class="btn btn-success" value="Submit" redirect_data=' + redirect + '> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p></form>';

      modal.find('.modal-footer').html(methodd_footer);
      modal.find('.modal-body').html(methodd);
    });

    $(document).on('click', '#choose_acc', function() {
      //alert('Opps.');
      var customer_guid = $('#acc_guid').val();
      var redirect_data = $(this).attr('redirect_data');
      var location = '';

      if (customer_guid == '') {
        alert('Please Select Customer to Proceed View Statement');
        return;
      }

      if ((redirect_data == '') || (redirect_data == 'null') || (redirect_data == null)) {
        alert('Invalid redirect. Please Contact Support.');
        return;
      }

      if (redirect_data == 'view_statement') {
        location = "<?= site_url('b2b_billing_invoice_controller/statement'); ?>";
      }

      if (redirect_data == 'view_receipt') {
        location = "<?= site_url('b2b_billing_invoice_controller/official_receipt'); ?>";
      }

      $.ajax({
        url: "<?= site_url('Login_c/outside_view_statement'); ?>",
        method: "POST",
        data: {
          customer_guid: customer_guid
        },
        beforeSend: function() {
          $('.btn').button('loading');
        },
        success: function(data) {
          json = JSON.parse(data);
          if (json.para1 == '1') {
            $('#medium-modal').modal('hide');
            $('.btn').button('reset');
            window.location = location;
            //redirect(site_url('b2b_billing_invoice_controller/statement'));
          }
        } //close success
      }); //close ajax
    });

    //location customer set session
    $(document).on('click', '#select_button', function() {

      var customer = $(this).attr('acc_guid');
      var blocked_guid = $('#blocked_guid').val();
      var redirect_location = '';

      if ((customer == '') || (customer == 'null') || (customer == null)) {
        alert('Error to Get Customer.Please Contact Support.');
        return;
      }

      if ((blocked_guid == '') || (blocked_guid == 'null') || (blocked_guid == null)) {
        blocked_guid = '';
      }

      $.ajax({
        url: "<?php echo site_url('Login_c/customer_setsession') ?>",
        method: "POST",
        data: {
          customer: customer,
          blocked_guid: blocked_guid
        },
        beforeSend: function() {
          $('.btn').button('loading');
        },
        success: function(data) {
          json = JSON.parse(data);
          redirect = json.redirect;

          if (json.para == 1) {
            //$('.btn').button('reset');

            if ((redirect == '') || (redirect == 'null') || (redirect == null)) {
              alert('Error to Redirect Dashboard.Please Contact Support.');
              return;
            }

            if (redirect == 'dashboard') {
              redirect_location = "<?= site_url('dashboard'); ?>";
            }

            if (redirect == 'panda_home') {
              redirect_location = "<?= site_url('panda_home'); ?>";
            }

            window.location = redirect_location;
            //redirect(site_url(redirect));

          } else {
            //$('.btn').button('reset');
            alert(json.msg);
            setTimeout(function() {
              location.reload();
            }, 300);
          }

        } //close success
      }); //close ajax 
    });

    $(document).on('click', '#view_invoice', function() {
      window.location = "<?php echo site_url('b2b_billing_invoice_controller/invoices_new') ?>";
    });

    $(document).on('click', '#view_acc_statement', function() {

      var customer_guid = $(this).attr('acc_guid');

      if ((customer_guid == '') || (customer_guid == 'null') || (customer_guid == null)) {
        alert('Invalid Customer to Proceed.');
        return;
      }

      $.ajax({
        url: "<?= site_url('Login_c/outside_view_statement'); ?>",
        method: "POST",
        data: {
          customer_guid: customer_guid
        },
        beforeSend: function() {
          $('.btn').button('loading');
        },
        success: function(data) {
          json = JSON.parse(data);
          if (json.para1 == '1') {
            $('.btn').button('reset');
            window.location = "<?php echo site_url('b2b_billing_invoice_controller/statement') ?>";
            //redirect(site_url('b2b_billing_invoice_controller/statement'));
          }
        } //close success
      }); //close ajax
    });

    $(document).on('click', '#extend_days', function() {
      //alert('Opps.');
      var customer_guid = $(this).attr('acc_guid');
      var user_guid = $(this).attr('user_guid');

      if (customer_guid == '' || customer_guid == null || customer_guid == 'null') {
        alert('Invalid Process Please Refresh Page.');
        return;
      }

      if (user_guid == '' || user_guid == null || user_guid == 'null') {
        alert('Invalid Process Please Refresh Page.');
        return;
      }

      $.ajax({
        url: "<?= site_url('Query_outstanding/outside_extend_days'); ?>",
        method: "POST",
        data: {
          customer_guid: customer_guid,
          user_guid: user_guid
        },
        beforeSend: function() {
          $('.btn').button('loading');
        },
        success: function(data) {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            $('.btn').button('reset');
            $('.buttonn').button('reset');
            $('#choose_retailer').button('reset');
            $('#propose_medium-modal').modal('hide');
            //$('.skin-blue sidebar-mini  sidebar-collapse').css('padding-right','');
            alert(json.msg);
          } else {
            //$('#propose_medium-modal').modal('hide');
            $('.btn').button('reset');
            $('.buttonn').button('reset');
            $('#choose_retailer').button('reset');
            //$('.skin-blue sidebar-mini  sidebar-collapse').css('padding-right','');

            var modal = $("#propose_medium-modal").modal();

            modal.find('.modal-title').html('Request Payment Extension');

            methodd = '';

            methodd += '<div class="col-md-12">';

            methodd += '<div class="col-md-12"><input type="hidden" id="customer_guid" name="customer_guid" value="' + customer_guid + '" readonly /></div>';

            methodd += '<div class="col-md-12"><label>Supplier Name</label><select class="form-control" name="select_supplier" id="select_supplier"> </select></div>';

            methodd += '</div>';

            methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="update_extend" class="btn btn-success" value="Submit"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);

            setTimeout(function() {
              vendor = '';
              Object.keys(json['get_supplier']).forEach(function(key) {
                vendor += '<option value="' + json['get_supplier'][key]['supplier_guid'] + '" >' + json['get_supplier'][key]['supplier_name'] + '</option>';
              });
              $('#select_supplier').select2().html(vendor);
            }, 300);
          }
        } //close success
      }); //close ajax
    });

    $(document).on('click', '#update_extend', function() {
      //alert('Opps.');
      var customer_guid = $('#customer_guid').val();
      var select_supplier = $('#select_supplier').val();

      if (customer_guid == '' || customer_guid == null || customer_guid == 'null') {
        alert('Invalid Process Please Refresh Page.');
        return;
      }

      if (select_supplier == '' || select_supplier == null || select_supplier == 'null') {
        alert('Invalid Process Please Refresh Page.');
        return;
      }
      //alert(select_supplier); die; //25D9617CCD6C11E983C3000D3AA2838A
      $.ajax({
        url: "<?= site_url('Query_outstanding/update_extend_days'); ?>",
        method: "POST",
        data: {
          customer_guid: customer_guid,
          select_supplier: select_supplier
        },
        beforeSend: function() {
          $('.btn').button('loading');
        },
        success: function(data) {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            $('.btn').button('reset');
            $('#propose_medium-modal').modal('hide');
            alert(json.msg);
          } else {
            $('.btn').button('reset');
            $('#propose_medium-modal').modal('hide');
            alert(json.msg);
            location.reload();
          }
        } //close success
      }); //close ajax
    });

    //disable inspect element
    document.onkeydown = function(e) {
      if (event.keyCode == 123) {
        return false;
      }
      if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
        return false;
      }
      if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
        return false;
      }
      if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
        return false;
      }
      if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
        return false;
      }
    }

  });
</script>