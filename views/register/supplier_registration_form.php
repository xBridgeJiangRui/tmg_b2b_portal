<style type="text/css">
  #text {
    color: blue;
  }

  .success-container {
    left: 50%;
    top: 100%;
    width: 600px;
    transform: translate(-50%, -50%);
    position: fixed;
  }

  .modalbox.success {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    background: #fff;
    padding: 25px 25px 15px;
    text-align: center;
    margin-top: 300px;
  }

  .modalbox.success.animate .icon {
    -webkit-animation: fall-in 0.75s;
    -moz-animation: fall-in 0.75s;
    -o-animation: fall-in 0.75s;
    animation: fall-in 0.75s;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
  }

  .modalbox.success h1 {
    font-family: 'Montserrat', sans-serif;
  }

  .modalbox.success p {
    font-family: 'Open Sans', sans-serif;
  }

  .modalbox.success .icon {
    position: relative;
    margin: 0 auto;
    margin-top: -75px;
    background: #D33724;
    /*confirmation alert color*/
    height: 100px;
    width: 100px;
    border-radius: 50%;
  }

  .modalbox.success .icon span {
    postion: absolute;
    font-size: 4em;
    color: #fff;
    text-align: center;
    padding-top: 20px;
  }

  .center {
    float: none;
    margin-left: auto;
    margin-right: auto;
    /* stupid browser compat. smh */
  }

  .center .change {
    clearn: both;
    display: block;
    font-size: 10px;
    color: #ccc;
    margin-top: 10px;
  }

  @-webkit-keyframes fall-in {
    0% {
      -ms-transform: scale(3, 3);
      -webkit-transform: scale(3, 3);
      transform: scale(3, 3);
      opacity: 0;
    }

    50% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
      opacity: 1;
    }

    60% {
      -ms-transform: scale(1.1, 1.1);
      -webkit-transform: scale(1.1, 1.1);
      transform: scale(1.1, 1.1);
    }

    100% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
    }
  }

  @-moz-keyframes fall-in {
    0% {
      -ms-transform: scale(3, 3);
      -webkit-transform: scale(3, 3);
      transform: scale(3, 3);
      opacity: 0;
    }

    50% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
      opacity: 1;
    }

    60% {
      -ms-transform: scale(1.1, 1.1);
      -webkit-transform: scale(1.1, 1.1);
      transform: scale(1.1, 1.1);
    }

    100% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
    }
  }

  @-o-keyframes fall-in {
    0% {
      -ms-transform: scale(3, 3);
      -webkit-transform: scale(3, 3);
      transform: scale(3, 3);
      opacity: 0;
    }

    50% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
      opacity: 1;
    }

    60% {
      -ms-transform: scale(1.1, 1.1);
      -webkit-transform: scale(1.1, 1.1);
      transform: scale(1.1, 1.1);
    }

    100% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
    }
  }

  @-webkit-keyframes plunge {
    0% {
      margin-top: -100%;
    }

    100% {
      margin-top: 25%;
    }
  }

  @-moz-keyframes plunge {
    0% {
      margin-top: -100%;
    }

    100% {
      margin-top: 25%;
    }
  }

  @-o-keyframes plunge {
    0% {
      margin-top: -100%;
    }

    100% {
      margin-top: 25%;
    }
  }

  @-moz-keyframes fall-in {
    0% {
      -ms-transform: scale(3, 3);
      -webkit-transform: scale(3, 3);
      transform: scale(3, 3);
      opacity: 0;
    }

    50% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
      opacity: 1;
    }

    60% {
      -ms-transform: scale(1.1, 1.1);
      -webkit-transform: scale(1.1, 1.1);
      transform: scale(1.1, 1.1);
    }

    100% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
    }
  }

  @-webkit-keyframes fall-in {
    0% {
      -ms-transform: scale(3, 3);
      -webkit-transform: scale(3, 3);
      transform: scale(3, 3);
      opacity: 0;
    }

    50% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
      opacity: 1;
    }

    60% {
      -ms-transform: scale(1.1, 1.1);
      -webkit-transform: scale(1.1, 1.1);
      transform: scale(1.1, 1.1);
    }

    100% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
    }
  }

  @-o-keyframes fall-in {
    0% {
      -ms-transform: scale(3, 3);
      -webkit-transform: scale(3, 3);
      transform: scale(3, 3);
      opacity: 0;
    }

    50% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
      opacity: 1;
    }

    60% {
      -ms-transform: scale(1.1, 1.1);
      -webkit-transform: scale(1.1, 1.1);
      transform: scale(1.1, 1.1);
    }

    100% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
    }
  }

  @keyframes fall-in {
    0% {
      -ms-transform: scale(3, 3);
      -webkit-transform: scale(3, 3);
      transform: scale(3, 3);
      opacity: 0;
    }

    50% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
      opacity: 1;
    }

    60% {
      -ms-transform: scale(1.1, 1.1);
      -webkit-transform: scale(1.1, 1.1);
      transform: scale(1.1, 1.1);
    }

    100% {
      -ms-transform: scale(1, 1);
      -webkit-transform: scale(1, 1);
      transform: scale(1, 1);
    }
  }

  @-moz-keyframes plunge {
    0% {
      margin-top: -100%;
    }

    100% {
      margin-top: 15%;
    }
  }

  @-webkit-keyframes plunge {
    0% {
      margin-top: -100%;
    }

    100% {
      margin-top: 15%;
    }
  }

  @-o-keyframes plunge {
    0% {
      margin-top: -100%;
    }

    100% {
      margin-top: 15%;
    }
  }

  @keyframes plunge {
    0% {
      margin-top: -100%;
    }

    100% {
      margin-top: 15%;
    }
  }

  .layout-top-nav {
    padding-right: 0px !important;
  }

  .select2-container--default .select2-selection--multiple .select2-selection__rendered {
    display: flex;
    white-space: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
  }

  .select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar {
    width: 10px;
    height: 5px;
    background-color: #F5F5F5;
    /* width of the entire scrollbar */
  }

  .select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    background-color: #F5F5F5;
    /* color of the tracking area */
  }

  .select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
    background-color: #B7BABF;
    /* color of the scrolling */
  }

  .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: black;
  }

  .cell_breakWord {
    word-break: break-all;
    max-width: 1px;
  }

  .alignleft {
    text-align: left;
  }

  input:focus {
    background-color: #ccebff;
  }

  @media print {

    #col1,
    #col2 {
      display: none;
    }

    #crumbs,
    #header_smallmenu,
    #header_search {
      display: none;
    }

    .solidblockmenu {
      display: none;
    }

    .addthis_toolbox addthis_default_style {
      display: none;
    }

    #hbfooter,
    .hbfooter {
      display: none;
    }

    body {
      background-image: none;
    }


    #part2_tb tr {
      page-break-inside: avoid;
      page-break-after: auto
    }
  }

  .blink {
    background-color: yellow;
    animation: blink 1s;
    animation-iteration-count: infinite;
  }
</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
  <div class="container-fluid">
    <br>

    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
          <div class="box-header with-border">
            <h2 class="text-center">Online Registration Form </h2> <br>
            <!-- <button type="button" style="float: right;" class="btn btn-xs btn-default" id="ctrl_p"><i class="fa fa-print"></i> Print View</button> -->
            <br>
            <h4 class="text-bold part1" style="margin-left: 15px;">Please complete and submit this Online Registration Form and provide full details to TMG B2B.
              <!-- <a href="<?php echo base_url("assets/Online_Registration_Form_Manual_Guide.pdf") ?>" download ><span class="blink" style="color:red;"> Online Form Manual Guide
              <?php if ($acceptance_path != 'hide') {
              ?>
                </span></a> AND <a href="<?php echo base_url("assets/Guide_Upload_Term_Sheet_and_Accpetance_Form.pdf") ?>" download ><span class="blink" style="color:red;"> Acceptance Form Manual Guide</span> </a>
                <?php
              } else {
                ?> 
                 HERE </span></a>
                <?php
              }
                ?> -->
            </h4>
            <h4 class="text-bold part1" style="margin-left: 15px;">Part 1: Organizational Information</h4><br>

            <form action="<?php echo site_url('Supplier_registration/register_update') ?>?link=<?php echo $_REQUEST['link'] ?>" method="post" id="myForm" autocomplete="off">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <span class="add_save_status"></span>
                  <!--status save button -->
                  <span class="count_part2_tb"></span>
                  <span class="count_participant_tb"></span>
                  <?php
                  if ($register->num_rows() != 0) {
                    foreach ($register->result() as $key) {
                      $form_status = $key->form_status;

                      if ($form_status == 'New' || $form_status == 'Processing' || $form_status == 'Emailed' || $form_status == 'Registered') {
                        $disabled = 'disabled';
                        $readonly = 'readonly';
                      } else {
                        $disabled = '';
                        $readonly = '';
                      }
                    }

                    if (isset($_REQUEST['termModal']) == 1) {
                  ?>
                      <script>
                        $(function() {
                          setTimeout(function() {
                            $('#term_btn').trigger('click');
                          }, 300);
                        });
                      </script>
                  <?php
                    }
                  }
                  ?>
                  <label for="exampleInputEmail1">Company Name <span class="text-danger">*</span> </label>

                  <?php foreach ($register->result() as $key) { ?>
                    <input type="text" class="form-control" id="comp_name" name="comp_name" aria-describedby="emailHelp" value="<?php echo $key->comp_name ?>" readonly required="true">
                  <?php } ?>

                </div>
              </div>

              <div class="form-group col-md-6">
                <label for="exampleInputEmail1">Company Registration No <span class="text-danger">*</span> </label>

                <?php foreach ($register->result() as $key) { ?>
                  <input type="text" class="form-control" id="comp_no" name="comp_no" aria-describedby="emailHelp" value="<?php echo $key->comp_no ?>" readonly required="true">
                <?php } ?>
              </div>

              <?php if ($register->num_rows() != 0) {
                foreach ($register->result() as $key) {
              ?>
                  <input type="hidden" id="term_hidden" name="term_hidden" value=<?php echo $key->term_download ?> disabled>
                  <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Supply Type <span class="text-danger">*</span> </label><br>

                    <input type="checkbox" class="set_reset supply_type" id="outright" name="supply_outright" value="outright" disabled <?php
                                                                                                                                                        if ($key->supply_outright == 'outright') {
                                                                                                                                                        ?> checked>
                  <?php
                                                                                                                                                        } else if ($key->memo_type == 'outright' || $key->memo_type == 'waive_outright' || $key->memo_type == 'both' || $tick_supply_type == 'Outright') { ?> checked
                    >
                  <?php
                                                                                                                                                        } else { ?> > <?php } ?>
                  <label for="vehicle1" style="margin-left: 5px;margin-right: 5px;"> OUTRIGHT</label>

                  <input type="checkbox" class="set_reset supply_type" id="consignment" name="supply_consignment" value="consignment" disabled <?php
                                                                                                                                                              if ($key->supply_consignment == 'consignment') {
                                                                                                                                                              ?> checked> <?php
                                                                                                                                                                        } else if ($key->memo_type == 'consignment' || $key->memo_type == 'waive_consign' || $key->memo_type == 'both' || $tick_supply_type == 'Consign') { ?> checked
                  >
                <?php
                                                                                                                                                                        } else { ?> > <?php } ?>
                <label for="vehicle1" style="margin-left: 5px;"> CONSIGNMENT</label>

                  </div>

                  <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Business Address/Billing Address <span class="text-danger">*</span> </label>
                    <textarea class="form-control set_reset" style="resize: none;" id="comp_add" name="comp_add" rows="4" cols="10" <?php echo $readonly ?>><?php echo $key->comp_add ?></textarea>
                  </div>


                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Postcode <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control set_reset" id="comp_post" name="comp_post" placeholder="Postcode" value="<?php echo $key->supplier_postcode ?>" required="true" <?php echo $readonly ?>>
                  </div>

                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">State <span class="text-danger">*</span> </label>
                    <input type="hidden" class="form-control set_reset" id="hidden_state" name="hidden_state" value="<?php echo $key->supplier_state ?>" disabled>
                    <select class="form-control set_reset" id="comp_state" name="comp_state" required="true" <?php echo $disabled ?>>
                      <option value="" <?php if ($key->supplier_state == "") {
                                          echo "selected";
                                        } ?>>--Select State--</option>
                      <option value="Johor" <?php if ('Johor' == $key->supplier_state) {
                                              echo "selected";
                                            } ?>>Johor</option>
                      <option value="Kedah" <?php if ('Kedah' == $key->supplier_state) {
                                              echo "selected";
                                            } ?>>Kedah</option>
                      <option value="Kelantan" <?php if ('Kelantan' == $key->supplier_state) {
                                                  echo "selected";
                                                } ?>>Kelantan</option>
                      <option value="Malacca" <?php if ('Malacca' == $key->supplier_state) {
                                                echo "selected";
                                              } ?>>Malacca</option>
                      <option value="Negeri Sembilan" <?php if ('Negeri Sembilan' == $key->supplier_state) {
                                                        echo "selected";
                                                      } ?>>Negeri Sembilan</option>
                      <option value="Pahang" <?php if ('Pahang' == $key->supplier_state) {
                                                echo "selected";
                                              } ?>>Pahang</option>
                      <option value="Penang" <?php if ('Penang' == $key->supplier_state) {
                                                echo "selected";
                                              } ?>>Penang</option>
                      <option value="Perak" <?php if ('Perak' == $key->supplier_state) {
                                              echo "selected";
                                            } ?>>Perak</option>
                      <option value="Perlis" <?php if ('Perlis' == $key->supplier_state) {
                                                echo "selected";
                                              } ?>>Perlis</option>
                      <option value="Selangor" <?php if ('Selangor' == $key->supplier_state) {
                                                  echo "selected";
                                                } ?>>Selangor</option>
                      <option value="Terengganu" <?php if ('Terengganu' == $key->supplier_state) {
                                                    echo "selected";
                                                  } ?>>Terengganu</option>
                      <option value="Sabah" <?php if ('Sabah' == $key->supplier_state) {
                                              echo "selected";
                                            } ?>>Sabah</option>
                      <option value="Sarawak" <?php if ('Sarawak' == $key->supplier_state) {
                                                echo "selected";
                                              } ?>>Sarawak</option>
                      <option value="Kuala Lumpur" <?php if ('Kuala Lumpur' == $key->supplier_state) {
                                                      echo "selected";
                                                    } ?>>Kuala Lumpur</option>
                      <option value="Singapore" <?php if ('Singapore' == $key->supplier_state) {
                                                  echo "selected";
                                                } ?>>Singapore</option>
                    </select>
                    <!-- <input type="text" class="form-control" id="comp_state" name="comp_state"  aria-describedby="emailHelp" placeholder="State" value="<?php echo $key->supplier_state ?>" required="true"> -->

                  </div>

                  <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Billing Email Address </label><span class="text-danger">*</span>
                    <input type="email" class="form-control set_reset" id="comp_mail" name="comp_mail" aria-describedby="emailHelp" placeholder="Email Address" value="<?php echo $key->org_email ?>" required="true" <?php echo $readonly ?>>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Billing Phone No.<span class="text-danger">*</span> </label>
                    <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="billing_contact" name="billing_contact" aria-describedby="emailHelp" value="<?php echo $key->billing_contact ?>" required="true" <?php echo $readonly ?>>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Phone No. <span class="text-danger">*</span> </label>
                    <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="comp_contact" name="comp_contact" aria-describedby="emailHelp" value="<?php echo $key->comp_contact ?>" required="true" <?php echo $readonly ?>>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Second Phone No. </label>
                    <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="second_comp_contact" name="second_comp_contact" aria-describedby="emailHelp" value="<?php echo $key->second_comp_contact ?>" <?php echo $readonly ?>>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Fax </label>
                    <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="comp_fax" name="comp_fax" aria-describedby="emailHelp" value="<?php echo $key->comp_fax ?>" <?php echo $readonly ?>>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Business Description <span class="text-danger">*</span> </label>
                    <input type="hidden" class="form-control set_reset" id="hidden_desc" name="hidden_desc" value="<?php echo $key->business_description ?>" disabled>
                    <select class="form-control set_reset" id="business_desc" name="business_desc" required="true" <?php echo $disabled ?>>
                      <option value="" <?php if ($key->business_description == "") {
                                          echo "selected";
                                        } ?>>--Select Business Description--</option>
                      <option value="Bread" <?php if ('Bread' == $key->business_description) {
                                              echo "selected";
                                            } ?>>Bread</option>
                      <option value="Fresh" <?php if ('Fresh' == $key->business_description) {
                                              echo "selected";
                                            } ?>>Fresh</option>
                      <option value="Frozen Foods" <?php if ('Frozen Foods' == $key->business_description) {
                                                      echo "selected";
                                                    } ?>>Frozen Foods</option>
                      <option value="Beverages" <?php if ('Beverages' == $key->business_description) {
                                                  echo "selected";
                                                } ?>>Beverages & Drinks</option>
                      <option value="Dairy Products" <?php if ('Dairy Products' == $key->business_description) {
                                                        echo "selected";
                                                      } ?>>Dairy Products</option>
                      <option value="Snacks" <?php if ('Snacks' == $key->business_description) {
                                                echo "selected";
                                              } ?>>Snacks</option>
                      <option value="Cigarette" <?php if ('Cigarette' == $key->business_description) {
                                                  echo "selected";
                                                } ?>>Cigarette</option>
                      <option value="Electric Appliances" <?php if ('Electric Appliances' == $key->business_description) {
                                                            echo "selected";
                                                          } ?>>Electric Appliances</option>
                      <option value="Others" <?php if ('Others' == $key->business_description) {
                                                echo "selected";
                                              } ?>>Others</option>

                    </select>

                    <!-- <input type="checkbox" class="set_reset" id="Bread" name="bus_bread" value="Bread" <?php echo $disabled ?> <?php if ($key->bus_bread == 'Bread') { ?> checked> <?php } ?> <label for="Bread" style="margin-left: 5px;"> Bread</label> -->


                    <!-- <input type="checkbox" class="set_reset" id="Fresh" name="bus_fresh" value="Fresh" <?php echo $disabled ?> style="margin-left: 10px;"<?php if ($key->bus_fresh == 'Fresh') { ?> checked> <?php } ?> <label for="Fresh" style="margin-left: 5px;"> Fresh</label><br> -->

                  </div>

                  <div class="form-group col-md-6""> 
                      <label for=" exampleInputEmail1">Others:</label>
                    <input type="text" class="form-control set_reset" id="bus_desc_others" name="bus_desc_others" aria-describedby="emailHelp" value="<?php echo $key->bus_desc_others ?>" readonly <?php echo $disabled ?>>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Retailer Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="acc_name" name="acc_name" aria-describedby="emailHelp" value="<?php echo $key->acc_name ?>" readonly required="true">
                  </div>

                  <div class="form-group col-md-6" id="vendor">
                    <label for="exampleInputEmail1">Vendor Code (refer to Retailer) <span class="text-danger">*</span></label>
                    <?php
                    $part5 = $key->acc_no;
                    $array =  explode(',', $part5);
                    ?>
                    <select class="form-control select2 vendor_select2 set_reset" name="acc_no[]"" required=" true" multiple="multiple" disabled>
                      <?php
                      foreach ($myArray as $row) {
                        if (in_array($row, $array)) {
                          $selected = 'selected';
                        } else {
                          $selected = '';
                        }
                      ?>
                        <option value="<?php echo addslashes($row) ?>" <?php echo $selected ?>> <?php echo $row ?></option>
                      <?php
                      }
                      ?>
                    </select><br><br>

                  </div>


                <?php } //close foreach register 
                ?>
              <?php
              } // if register num rows equal to 0
              ?>
              <!-- Start Part 2 Vendor Here -->
              <div class="form-group col-md-12">
                <h4 class=" text-bold ">Part 2: Login Account(s) Information <span class="text-danger">(*Login-ID is create based on unique email address)</span>
                  <?php
                  if ($register->num_rows() != 0) {
                    foreach ($register->result() as $key) {
                      $form_status = $key->form_status;

                      if ($form_status == 'New' || $form_status == 'Processing' || $form_status == 'Emailed' || $form_status == 'Registered') {
                        //$disabled = 'display: none;'; 

                      } else {
                        //$disabled = '';
                  ?>
                        <button id="info_btn" type="button" class="btn btn-xs btn-default" style="float: right;margin-bottom:15px;" register_guid='<?php echo $_REQUEST['link'] ?>' customer_guid='<?php echo $customer_guid ?>'><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Add</button>
                  <?php
                      }
                    }
                  }
                  ?>

                </h4>
              </div>

              <div class="info">
                <div class="row" style="padding-left:25px;padding-right:25px;">
                  <table id="part2_tb" class="table table-hover" width="100%" cellspacing="0">
                    <thead style="white-space: nowrap;">
                      <tr>
                        <th>Action</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>User Group</th>
                        <th>Phone No</th>
                        <th>Email Address</th>
                        <th>Outlet Mapping Request</th>
                        <th>Vendor Code</th>
                        <th>Vendor Code Remark</th>

                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>
              </div>

              <br>

              <div class="note" style="margin-left: 15px;">
                <h5 class="text-bold">
                <?php 
                if($register->num_rows() != 0)
                {
                  foreach($register_charge_type->result() as $key)
                  { 
                    $memo_type = $key->memo_type;
                    $pdf_template_type = $key->template_type;
                    ?>
                    <input type="hidden" id="hidden_memo" name="hidden_memo" value="<?php echo $key->memo_type ?>" disabled>
                    <?php
                    if($pdf_template_type == 'outright' || $pdf_template_type == 'Outright' || $pdf_template_type == 'waive_outright')
                    {
                      ?>
                      There will be one off RM300 Registration Fees and monthly subscriptions incur once Register. Refer to Page 1 <a href="<?php echo $defined_path.'TMG_B2B_Registration_MEMO.pdf' ?>" download ><span style="color:red;">TMG Memo Charges</span></a>
                      <?php
                    }
                    else if($pdf_template_type == 'consignment' || $pdf_template_type == 'Consign' || $pdf_template_type == 'waive_consign')
                    {
                      ?>
                      There will be one off RM300 Registration Fees and monthly subscriptions count based on outlet usage once Register @ TMG B2B Portal. Refer to Page 1 <a href="<?php echo $defined_path.'TMG_B2B_Registration_MEMO.pdf' ?>" download ><span style="color:red;">TMG Memo Charges</span></a>
                      <?php
                    }
                    else if($pdf_template_type == 'both')
                    {
                      ?>
                      There will be one off RM300 Registration Fees and monthly subscriptions count once Register @ TMG B2B Portal. Refer to Page 1 <a href="<?php echo $defined_path.'TMG_B2B_Registration_MEMO.pdf' ?>" download ><span style="color:red;">TMG Memo Charges</span></a>
                      <?php
                    }
                    else if($pdf_template_type == 'outright_iks')
                    {
                      ?>
                      There will be one off RM200 Registration Fees and yearly subscriptions fees RM200 incur once Register. Refer to Page 1 <a href="<?php echo $defined_path.'TMG_B2B_Registration_MEMO.pdf' ?>" download ><span style="color:red;">TMG Memo Charges</span></a>
                      <?php
                    }
                    else
                    {
                      ?>
                      Please Contact xBridge Registration Team to get your Memo Charges Details.
                      <?php
                    }
                  }
                }
                ?>
                </h5>
                
                <h5>
                Please contact <span class="text-bold"> xBridge Registration Team </span> @ <span><a href="mailto:registertmg@xbridge.my">registertmg@xbridge.my</a></span> or call us @ +6017 215 3088 / +6017 715 9340 should you require further clarifications on registration process and access to <span class="text-bold">TMG B2B portal</span>.
                </h5>
              </div> 
              <?php
              if ($form_status == 'New' || $form_status == 'Processing' || $form_status == 'Emailed' || $form_status == 'Registered') {
                //$disabled = 'disabled'; 
              } else {
              ?>
                <button title="Save" data-toggle="modal" data-target="#saveModal" id="save_btn" type="button" class="btn btn-md btn-default" onclick="save_form()"><i class="fa fa-save" aria-hidden="true"></i> &nbsp&nbspSave</button>
                <button title="Submit" data-toggle="modal" onclick="valthisform()" data-target="#exampleModal" id="submit-data" type="button" class="btn btn-md btn-success"><i class="glyphicon glyphicon-save" aria-hidden="true"></i>&nbsp&nbspSubmit</button>
                <!-- <button title="Submit" style="display: none;" id="term_btn" type="button" class="btn btn-md btn-success"><i class="glyphicon glyphicon-save" aria-hidden="true"></i>&nbsp&nbspSubmit</button> -->
              <?php
              }
              ?>
          </div> <!-- registration part close div -->
        </div> <!-- registration part close div -->
        <!-- TRAINING PART -->
        <?php
        if ($reg_memo_type != 'outright_iks') {
        ?>
          <!-- <div class="row">
            <div class="col-md-12">
              <div class="box box-default">
                <div class="box-header with-border">
                  <div class="form-row">
                    <h4>
                      <b>xBridge B2B Portal Training is <span><u style="background-color: yellow;">OPTIONAL</u></span>. If interested please complete this Training form together with payment of the Training Fees: RM200 (for 2 pax), additional RM100 for each subsequent participant.</b>
                    </h4><br>
                  </div>
                  <h4 class=" text-bold " style="margin-left: 15px;">Part 1: Organizational Information</h4><br>

                  <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Company Name</label>
                    <?php if ($register->num_rows() != 0) {
                      foreach ($register->result() as $key) { ?>
                        <input type="text" class="form-control" id="comp_name" name="comp_name" aria-describedby="emailHelp" value="<?php echo $key->comp_name ?>" readonly>

                  </div>

                  <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Email Address</label>
                    <input type="email" class="form-control set_reset" id="comp_email" name="comp_email" aria-describedby="emailHelp" placeholder="Email Address" value="<?php echo $key->org_part_email ?>" <?php echo $readonly ?>><br>
                  </div>

                  <h4 class=" text-bold " style="margin-left: 15px;">Part 2:Participant Information
                    <?php
                        if ($register->num_rows() != 0) {
                          foreach ($register->result() as $key) {
                            $form_status = $key->form_status;

                            if ($form_status == 'New' || $form_status == 'Processing' || $form_status == 'Emailed' || $form_status == 'Registered') {
                            } else {
                    ?>
                          <button id="part_btn" type="button" class="btn btn-xs btn-default" style="float: right;margin-bottom:15px;margin-right:15px;" register_guid='<?php echo $_REQUEST['link'] ?>' customer_guid='<?php echo $customer_guid ?>'><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Add</button>
                    <?php
                            }
                          }
                        }
                    ?>

                  </h4>
              <?php } //close foreach
                    }
              ?>
              <div class="info">
                <div class="row" style="padding-left:25px;padding-right:25px;">
                  <table id="participant_tb" class="table table-hover" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Name</th>
                        <th>IC NO</th>
                        <th>Mobile Phone No</th>
                        <th>Email Address</th>

                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>
              </div>
              <br>
              <div class="note2" style="margin-left: 15px;">
                <h5>
                  Please contact <span class="text-bold"> xBridge Registration Team </span> @ <span><a href="mailto:register@xbridge.my">register@xbridge.my</a></span> or call us @ +60 17-715 9340 / +60 17-215 3088 should you require further clarifications on training registration , schedules and reservations.
                </h5>
                <br>
                <h5 class="text-md-left ">

                  Payment can be made through Internet Banking or Account Payable Cheque based on the below bank details:

                </h5>

                <ul style="list-style-type: lower-alpha;">

                  <li> Account Name : <span class="text-bold">REXBRIDGE SDN BHD (1106802H)</span></li>
                  <li> Name of bank : <span class="text-bold"> Public Bank </span></li>
                  <li> Account number: <span class="text-bold"> 3198918900 </span></li>

                </ul>

                <h5 class="text-md-left ">

                  Please email the <b>bank receipt</b> to <a href="mailto:billing@xbridge.my">billing@xbridge.my</a> for issuance of official receipt:

                </h5>

                <ul style="list-style-type: lower-alpha;">

                  <li> Company Name & Registration No </li>
                  <li> Email</li>
                  <li> Contact person</li>

                </ul>

                <br>
              </div>
                </div>
              </div>
            </div>
          </div> -->
        <?php
        } ?>
        <!-- confimation alert -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog" role="document">
            <div class="">
              <div class="modal-body">
                <div class="success-container">
                  <div class="row">
                    <div class="modalbox success center animate">
                      <div class="icon">
                        <span class="fa fa-question"></span>
                      </div>
                      <h1>Confirmation</h1>
                      <p><span style="background: yellow">Please ensure all information is correct.</span>
                        <br>Yes, to <b>SUBMIT</b> this online registration form.
                        <br>Registered Login Account(s) : <b><span id="modal_reg_no"></span></b>
                      </p>
                      <div class="modal-footer" style="text-align: center">
                        <button type="button" class="btn btn-secondary confirmation_no_btn" data-dismiss="modal">No</button>
                        <button type="update" class="btn btn-success" id="last_btn">Yes</button>
                        <button type="button" style="display: none;" class="btn btn-success" id="btn_yes">Yes</button>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Save alert -->
        <div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog" role="document">
            <div class="">
              <div class="modal-body">
                <div class="success-container">
                  <div class="row">
                    <div class="modalbox success  center animate">
                      <div class="icon">
                        <span class="fa fa-question"></span>
                      </div>
                      <h1>Confirmation</h1>
                      <p>Your action is to <b>SAVE</b> this Form.
                      </p>
                      <div class="modal-footer" style="text-align: center">
                        <button type="button" class="btn btn-secondary confirmation_no_btn" data-dismiss="modal">No</button>
                        <button type="update" class="btn btn-success">Yes</button>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- <button id="resetBtn" type="reset" class="btn btn-md btn-info reset"><i class="glyphicon glyphicon-refresh" aria-hidden="true"></i> Reset</button> -->

        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    store_memo_type = '';
    store_memo_type = "<?php echo $reg_memo_type; ?>";

    function valthisform() {
      var checkboxs = document.getElementsByClassName("supply_type");
      var okay = false;
      var email_1 = $('#comp_mail').val();
      var email_part = $('#comp_email').val();
      var comp_add = $('#comp_add').val();
      var comp_post = $('#comp_post').val();
      var billing_contact = $('#billing_contact').val();
      var comp_contact = $('#comp_contact').val();
      var second_comp_contact = $('#second_comp_contact').val();
      var comp_fax = $('#comp_fax').val();
      var part2_tb_count = $('#part2_tb_count').val();
      var participant_tb_count = $('#participant_tb_count').val();
      var business_desc = $('#business_desc').val();
      var bus_desc_others = $('#bus_desc_others').val();
      var comp_state = $('#comp_state').val();
      $('#modal_reg_no').html(part2_tb_count);
      $('#modal_participant_no').html(participant_tb_count);
      for (var i = 0, l = checkboxs.length; i < l; i++) {
        if (checkboxs[i].checked) {
          $('#submit-data').attr("data-target", "#exampleModal");
          okay = true;
          break;
        }
      }
      if (okay != true) {
        $('#submit-data').removeAttr('data-target');
        alert("Please select the supply type to proceed.");
        $(".supply_type").focus();
        return;
      }

      if (comp_add == '') {
        $('#submit-data').removeAttr('data-target');
        alert("Please insert Business Address/Billing Address.");
        $("#comp_add").focus();
        return;
      }

      if (comp_post == '') {
        $('#submit-data').removeAttr('data-target');
        alert("Please insert Postcode.");
        $("#comp_post").focus();
        return;
      } else {
        if (!/^[0-9]{5,6}$/g.test(comp_post)) {
          $('#submit-data').removeAttr('data-target');
          alert('Invalid Postcode');
          $("#comp_post").focus();
          return;
        }
      }

      if (comp_state == '') {
        $('#submit-data').removeAttr('data-target');
        alert("Please select State.");
        $("#comp_state").focus();
        return;
      }

      if (email_1 == '') {
        $('#submit-data').removeAttr('data-target');
        alert("Please insert Billing Email.");
        $("#comp_mail").focus();
        return;
      } else {
        if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email_1)) {
          $('#submit-data').removeAttr('data-target');
          alert('Invalid Billing Email');
          $("#comp_mail").focus();
          return;
        }
      }

      if (billing_contact == '') {
        $('#submit-data').removeAttr('data-target');
        alert("Please insert Billing Contact.");
        $("#billing_contact").focus();
        return;
      } else {
        if (!/^([0-9])[-\0-9]{8,11}$/g.test(billing_contact)) {
          $('#submit-data').removeAttr('data-target');
          alert('Invalid Billing Contact Number.');
          $("#billing_contact").focus();
          return;
        }
      }

      if (comp_contact == '') {
        $('#submit-data').removeAttr('data-target');
        alert("Please insert Contact.");
        $("#comp_contact").focus();
        return;
      } else {
        if (!/^([0-9])[-\0-9]{8,11}$/g.test(comp_contact)) {
          $('#submit-data').removeAttr('data-target');
          alert('Invalid Contact Number.');
          $("#comp_contact").focus();
          return;
        }
      }

      if (second_comp_contact != '') {
        if (!/^([0-9])[-\0-9]{8,11}$/g.test(second_comp_contact)) {
          $('#submit-data').removeAttr('data-target');
          alert('Invalid Second Contact Number.');
          $("#second_comp_contact").focus();
          return;
        }

        if (second_comp_contact == comp_contact) {
          $('#submit-data').removeAttr('data-target');
          alert('Contact Number Repeated.');
          $("#second_comp_contact").focus();
          return;
        }
      }

      if (comp_fax != '') {
        if (!/^([0-9])[-\0-9]{8,11}$/g.test(comp_fax)) {
          $('#submit-data').removeAttr('data-target');
          alert('Invalid Fax Number');
          $("#comp_fax").focus();
          return;
        }
      }

      if (business_desc == '') {
        $('#submit-data').removeAttr('data-target');
        alert('Please Select Business Description');
        $("#business_desc").focus();
        return;
      } else {
        if (business_desc == 'Others') {
          if (bus_desc_others == '') {
            $('#submit-data').removeAttr('data-target');
            alert('Please Insert Value for Others Business Description.');
            $("#bus_desc_others").focus();
            return;
          }
        }
      }

      if (part2_tb_count == '0') {
        $('#submit-data').removeAttr('data-target');
        alert('Please Insert Part 2 Section');
        $('#info_btn').focus();
        return;
      }

      // if (store_memo_type != 'outright_iks') {
      //   if (participant_tb_count != '0') {
      //     if (email_part == '') {
      //       $('#submit-data').removeAttr('data-target');
      //       alert("Please insert Participant Email.");
      //       $("#comp_email").focus();
      //       return;
      //     }
      //   }

      //   if (email_part != '') {
      //     if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email_part)) {
      //       $('#submit-data').removeAttr('data-target');
      //       alert('Invalid Training Email');
      //       $("#comp_email").focus();
      //       return;
      //     }
      //   }
      // }

    }

    function save_form() {
      var email_1 = $('#comp_mail').val();
      var email_part = $('#comp_email').val();
      var comp_add = $('#comp_add').val();
      var comp_post = $('#comp_post').val();
      var comp_contact = $('#comp_contact').val();
      var comp_fax = $('#comp_fax').val();
      var comp_state = $('#comp_state').val();
      var business_desc = $('#business_desc').val();
      var billing_contact = $('#billing_contact').val();

      if (comp_add == '') {
        $('#save_btn').removeAttr('data-target');
        alert("Please insert Business Address/Billing Address.");
        $("#comp_add").focus();
        return;
      }

      if (comp_post == '') {
        $('#save_btn').removeAttr('data-target');
        alert("Please insert Postcode.");
        $("#comp_post").focus();
        return;
      } else {
        if (!/^[0-9]{5,6}$/g.test(comp_post)) {
          $('#save_btn').removeAttr('data-target');
          alert('Invalid Postcode');
          $("#comp_post").focus();
          return;
        }
      }

      if (comp_state == '') {
        $('#save_btn').removeAttr('data-target');
        alert("Please select State.");
        $("#comp_state").focus();
        return;
      }

      if (email_1 == '') {
        $('#save_btn').removeAttr('data-target');
        alert("Please insert Billing Email.");
        $("#comp_mail").focus();
        return;
      } else {
        if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email_1)) {
          $('#save_btn').removeAttr('data-target');
          alert('Invalid Billing Email');
          $("#comp_mail").focus();
          return;
        }
      }

      if (billing_contact == '') {
        $('#save_btn').removeAttr('data-target');
        alert("Please insert Billing Contact.");
        $("#billing_contact").focus();
        return;
      } else {
        if (!/^([0-9])[-\0-9]{8,11}$/g.test(billing_contact)) {
          $('#save_btn').removeAttr('data-target');
          alert('Invalid Billing Contact Number.');
          $("#billing_contact").focus();
          return;
        }
      }

      if (comp_contact == '') {
        $('#save_btn').removeAttr('data-target');
        alert("Please insert Contact.");
        $("#comp_contact").focus();
        return;
      } else {
        if (!/^([0-9])[-\0-9]{8,11}$/g.test(comp_contact)) {
          $('#save_btn').removeAttr('data-target');
          alert('Invalid Contact Number.');
          $("#comp_contact").focus();
          return;
        }
      }

      if (comp_fax != '') {
        if (!/^([0-9])[-\0-9]{8,11}$/g.test(comp_fax)) {
          $('#save_btn').removeAttr('data-target');
          alert('Invalid Fax Number');
          $("#comp_fax").focus();
          return;
        }
      }

      if (business_desc == '') {
        $('#save_btn').removeAttr('data-target');
        alert("Please select Business Description.");
        $("#business_desc").focus();
        return;
      }

      // if (store_memo_type != 'outright_iks') {
      //   if (email_part != '') {
      //     if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email_part)) {
      //       $('#save_btn').removeAttr('data-target');
      //       alert('Invalid Training Email');
      //       $("#comp_email").focus();
      //       return;
      //     }
      //   }
      // }


      $('#save_btn').attr("data-target", "#saveModal");
    }
  </script>

  <script type="text/javascript">
    var button = $('#submitBtn');
    $('#myForm :input').not(button).bind('keyup change', function() {
      // get all that inputs that has changed
      var changed = $('#myForm :input').not(button).filter(function() {
        if (this.type == 'radio' || this.type == 'checkbox') {
          return this.checked != $(this).data('default');
        } else {
          return this.value != $(this).data('default');
        }
      });
      // disable if none have changed - else enable if at least one has changed
      $('#submitBtn').prop('disabled', changed.length);
    });
  </script>

  <script type="text/javascript">
    $('document').ready(function() {

      if (<?php echo $register_child->num_rows() ?> == 0) {
        $('#term_btn').hide();
      }

      register_guid = "<?php echo $_REQUEST['link']; ?>";
      retailer_name = $('#comp_name').val();
      company_name = $('#acc_name').val();
      vendor_table = function(register_guid) {
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('Supplier_registration/vendor_tb'); ?>",
          data: {
            register_guid: register_guid
          },
          dataType: 'json',
          success: function(data) {
            if ($.fn.DataTable.isDataTable('#part2_tb')) {
              $('#part2_tb').DataTable().clear().destroy()
            }

            $('#part2_tb').DataTable({
              columnDefs: [{
                className: "alignleft",
                targets: [0]
              }],

              ordering: false,
              sScrollY: "25vh",
              data: data,
              columns: [{
                  "data": "register_c_guid",
                  render: function(data, type, row) {
                    var element = '';

                    if ((row['form_status'] == 'New') || (row['form_status'] == 'Processing') || (row['form_status'] == 'Emailed') || (row['form_status'] == 'Registered')) {
                      element += '';
                    } else {
                      element += '<button id="edit_ven_btn" type="button"  title="EDIT" class="btn btn-xs btn-info" register_guid="' + row['register_guid'] + '" customer_guid="' + row['customer_guid'] + '" register_c_guid="' + row['register_c_guid'] + '" register_mapping_guid="' + row['register_mapping_guid'] + '" ven_name="' + row['ven_name'] + '" ven_designation="' + row['ven_designation'] + '" ven_phone="' + row['ven_phone'] + '" ven_email="' + row['ven_email'] + '" ven_agency="' + row['ven_agency'] + '" ven_code="' + row['ven_code'] + '" vendor_code_remark="' + row['vendor_code_remark'] + '" user_group_guid="'+row['user_group_guid']+'" ><i class="fa fa-edit"></i></button>';

                      element += '<button id="active_btn" type="button" style="margin-left:5px;" title="REMOVE" class="btn btn-xs btn-danger"  register_guid="' + row['register_guid'] + '" register_c_guid="' + row['register_c_guid'] + '" ven_name="' + row['ven_name'] + '" ven_designation="' + row['ven_designation'] + '" ven_phone="' + row['ven_phone'] + '" ven_email="' + row['ven_email'] + '" ven_agency="' + row['ven_agency'] + '" ven_code="' + row['ven_code'] + '" isdelete="' + row['isdelete'] + '" ><i class="fa fa-trash"></i></button>';
                    }

                    return element;

                  }
                },
                {
                  "data": "ven_name"
                },
                {
                  "data": "ven_designation"
                },
                { "data": "user_group_name" },
                {
                  "data": "ven_phone"
                },
                {
                  "data": "ven_email"
                },
                {
                  "data": "ven_agency",
                  render: function(data, type, row) {
                    var element = '';

                    element += '<span class="cell_breakWord">' + data + '</span>';

                    return element;
                  }
                },
                {
                  "data": "ven_code"
                },
                {
                  "data": "vendor_code_remark"
                },

              ],
              dom: "<'row'" + ">" + 'rtp',
              // buttons: [
              //   { extend: 'copyHtml5',
              //     messageTop: 'Part2 Login Account(s) Information'+': '+company_name+' @ '+retailer_name,
              //     exportOptions: {columns: [1,2,3,4,5,6,7]}
              //   },
              //   { extend: 'excelHtml5',
              //     messageTop: 'Part2 Login Account(s) Information'+': '+company_name+' @ '+retailer_name,
              //     exportOptions: {columns: [ 1,2,3,4,5,6,7 ]}
              //   },
              //   { extend: 'print',
              //     messageTop: 'Part2 Login Account(s) Information'+': '+company_name+' @ '+retailer_name,
              //     exportOptions: {columns: [ 1,2,3,4,5,6,7 ]}, /*, footer: true*/ 
              //     customize: function ( win )
              //     {
              //       $(win.document.body).css( 'font-size', '12pt' )
              //       $(win.document.body).find( 'td' ).css( 'word-break', 'break-all' ,'max-width', '50%');
              //     }
              //   },
              // ],
              "fnCreatedRow": function(nRow, aData, iDataIndex) {
                $(nRow).attr('isdelete', aData['isdelete']);
              },
              "footerCallback": function(row, data, start, end, display, iDataIndex) {
                var value_data = $('#part2_tb').DataTable().data().length;
                $('.count_part2_tb').html('<input type="hidden" id="part2_tb_count" name="part2_tb_count" value=' + value_data + ' readonly> ');
                if(value_data > 0)
                {
                  $('#info_btn').hide();
                }
                else
                {
                  $('#info_btn').show();
                }
              },
            }); //close datatable
          }
        });
      }

      participant_table = function(register_guid) {
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('Supplier_registration/participant_tb'); ?>",
          data: {
            register_guid: register_guid
          },
          dataType: 'json',
          success: function(data) {
            if ($.fn.DataTable.isDataTable('#participant_tb')) {
              $('#participant_tb').DataTable().clear().destroy()
            }

            $('#participant_tb').DataTable({
              ordering: false,
              data: data,
              columns: [{
                  "data": "register_c_guid",
                  render: function(data, type, row) {
                    var element = '';

                    if ((row['form_status'] == 'New') || (row['form_status'] == 'Processing') || (row['form_status'] == 'Emailed') || (row['form_status'] == 'Registered')) {
                      element += '';
                    } else {
                      element += '<button id="edit_part_btn" type="button" title="EDIT" class="btn btn-xs btn-info" register_guid="' + row['register_guid'] + '" customer_guid="' + row['customer_guid'] + '" register_c_guid="' + row['register_c_guid'] + '" part_name="' + row['part_name'] + '" part_ic="' + row['part_ic'] + '" part_mobile="' + row['part_mobile'] + '" part_email="' + row['part_email'] + '" ><i class="fa fa-edit"></i></button>';

                      element += '<button id="active_btn" type="button" style="margin-left:5px;" title="REMOVE" class="btn btn-xs btn-danger"  register_guid="' + row['register_guid'] + '" register_c_guid="' + row['register_c_guid'] + '" part_name="' + row['part_name'] + '" part_ic="' + row['part_ic'] + '" part_mobile="' + row['part_mobile'] + '" part_email="' + row['part_email'] + '" isdelete="' + row['isdelete'] + '"  ><i class="fa fa-trash"></i></button>';
                    }

                    return element;

                  }
                },
                {
                  "data": "part_name"
                },
                {
                  "data": "part_ic"
                },
                {
                  "data": "part_mobile"
                },
                {
                  "data": "part_email"
                },
              ],
              dom: "<'row'" + ">" + 'rtp',
              // buttons: [
              //   { extend: 'copyHtml5',
              //     messageTop: 'Part2 Participant Information'+': '+company_name+' @ '+retailer_name,
              //     exportOptions: {columns: [1,2,3,4]}
              //   },
              //   { extend: 'excelHtml5',
              //     messageTop: 'Part2 Participant Information'+': '+company_name+' @ '+retailer_name,
              //     exportOptions: {columns: [ 1,2,3,4]}
              //   },

              //   { extend: 'print',
              //     messageTop: 'Part2 Participant Information'+': '+company_name+' @ '+retailer_name,
              //     exportOptions: {columns: [ 1,2,3,4]}, /*, footer: true*/
              //     customize: function ( win )
              //     {
              //       $(win.document.body).css( 'font-size', '12pt' )
              //       $(win.document.body).find( 'td' ).css( 'word-break', 'break-all' ,'max-width', '50%');
              //     } 
              //   },
              // ],
              "footerCallback": function(row, data, start, end, display) {
                var value_data = $('#participant_tb').DataTable().data().length;
                $('.count_participant_tb').html('<input type="hidden" id="participant_tb_count" name="participant_tb_count" value=' + value_data + ' readonly>');
              },
            }); //close datatable

          }
        });
      }

      vendor_table(register_guid);
      participant_table(register_guid);

      $(document).on('click', '#add_code_modal', function() {

        var register_guid = $(this).attr('register_guid');
        var customer_guid = $(this).attr('customer_guid');

        var modal = $("#medium-modal").modal();

        modal.find('.modal-title').html('Add Vendor Code');

        methodd = '';

        methodd += '<div class="col-md-12">';

        methodd += '<div class="col-md-12"><input hidden type="hidden" class="form-control input-sm" id="hidden_reg" value="' + register_guid + '" /></div>';

        methodd += '<div class="col-md-12"><input hidden type="hidden" class="form-control input-sm" id="hidden_cust" value="' + customer_guid + '" /></div>';

        methodd += '<div class="col-md-12"><label>Vendor Code</label><select id="add_code" name="add_code" class="form-control" multiple="multiple" ><?php foreach ($add_vendor_code as $row) { ?> <option value="<?php echo $row->vendor_code; ?>"><?php echo $row->vendor_code; ?></option> <?php } ?> </select></div>';

        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="add_vendor_code" class="btn btn-success" value="Add"> <input name="sendsubmit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);

        setTimeout(function() {
          $('#add_code').select2();
        }, 300);

      }); //close add vendor code

      $(document).on('click', '#add_vendor_code', function() {

        //var table_name1 = 'register_child_new';
        var table_name2 = 'register_new';
        var code = $('#add_code').val();
        var register_guid = $('#hidden_reg').val();
        var customer_guid = $('#hidden_cust').val();

        if ((code == '') || (code == null)) {
          alert("Cannot empty select box")
          return;
        } //close checking for posted table_ss

        $.ajax({
          url: "<?php echo site_url('Supplier_registration/add_vendor_code'); ?>",
          method: "POST",
          data: {
            code: code,
            register_guid: register_guid,
            customer_guid: customer_guid,
            table_name2: table_name2
          },
          beforeSend: function() {
            $('.btn').button('loading');
          },
          success: function(data) {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            } else {
              alert(json.msg);
              $('.btn').button('reset');
              location.reload();
            } //close else
          } //close success
        }); //close ajax
      }); //close add 

      $(document).on('click', '#info_btn', function() {

        var register_guid = $(this).attr('register_guid');
        var customer_guid = $(this).attr('customer_guid');

        var count = $('#part2_tb').dataTable().fnGetData().length;

        // if (count != 0) {
        //   if (count % 5 == 0) {
        //     alert('Additional fees will be charge up to 5 persons');
        //   }
        // }

        if(count >= 1)
        {
          alert('Login Account(s) maximum 1 persons.');
          return;
        }

        var modal = $("#medium-modal").modal();

        modal.find('.modal-title').html('Create Login Account Information');

        methodd = '';

        methodd += '<div class="col-md-12">';

        methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_guid" value="' + register_guid + '"/></div>';

        methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="customer_guid" value="' + customer_guid + '"/></div>';

        methodd += '<div class="col-md-6"><label>Name</label><input type="text" class="form-control input-sm" id="ven_name" autocomplete="off" required="true"/></div>';

        methodd += '<div class="col-md-6"><label>Designation</label><input type="text" class="form-control input-sm" id="ven_designation" autocomplete="off" required="true"/></div>';

        methodd += '<div class="col-md-6"><label>Phone No</label><input type="text" class="form-control input-sm" id="ven_phone" autocomplete="off" required="true"/></div>';

        methodd += '<div class="col-md-6"><label>Email Address</label><input type="email" class="form-control " id="ven_email" autocomplete="off" required="true"/></div>';

        methodd += '<div class="col-md-12"><label>User Group</label><select class="form-control select2" name="add_user_group[]" id="add_user_group" required="true" disabled><option value="">-SELECT DATA-</option><?php foreach ($get_user_group->result() as $row) { ?> <option value="<?php echo $row->user_group_guid ?>"><?php echo $row->user_group_name; ?></option> <?php } ?></select></div>';

        methodd += '<div class="col-md-12"><label>Outlet Mapping Request</label><button id="location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button><select class="form-control select2 select2_agency" name="ven_agency[]" id="ven_agency"  multiple="multiple" required="true"><?php foreach ($ven_agency_sql->result() as $row) { ?> <option value="<?php echo addslashes($row->branch_code) ?>"><?php echo $row->branch_name; ?> - <?php echo $row->branch_code; ?></option> <?php } ?></select></div>';

        methodd += '<div class="col-md-12"><label>Vendor Code</label><select class="form-control select2 vendor_select2" name="ven_code[]" id="ven_code" required="true" multiple="multiple"><?php foreach ($myArray as $row) {
                                                                                                                                                                                                if (in_array($row, $array)) {
                                                                                                                                                                                                  $selected = 'selected';
                                                                                                                                                                                                } else {
                                                                                                                                                                                                  $selected = '';
                                                                                                                                                                                                } ?> <option value="<?php echo addslashes($row) ?>" <?php echo $selected ?>> <?php echo $row ?></option> <?php } ?></select></div>';

        methodd += '<div class="col-md-6" style="margin-top:5px;"><label>Vendor Code Remark (Optional) </label><div class="parts"><input type="text" class="form-control" id="remark_no" placeholder="Other"></div></div>';

        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="create_ven" class="btn btn-success" value="Create"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);

        setTimeout(function() {
          $('#add_user_group').val('<?php echo $get_user_group_guid ?>');
          $('#ven_agency').select2();
          $('#ven_code').select2();
        }, 300);

      }); //close create part2 vendor

      $(document).on('click', '#create_ven', function() {

        var register_guid = $('#register_guid').val();
        var customer_guid = $('#customer_guid').val();
        var ven_name = $('#ven_name').val();
        var ven_designation = $('#ven_designation').val();
        var ven_phone = $('#ven_phone').val();
        var ven_email = $('#ven_email').val();
        var ven_agency = $('#ven_agency').val();
        var ven_code = $('#ven_code').val();
        var remark_no = $('#remark_no').val();
        var add_user_group = $('#add_user_group').val();

        if (ven_name == '' || ven_name == null || ven_name == 'null') {
          alert('Please insert Name');
          $("#ven_name").focus();
          return;
        }

        if (!/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/.test(ven_name)) {
          alert('Name Cannot Have Numbering');
          $("#ven_name").focus();
          return;
        }

        if (ven_designation == '' || ven_designation == null || ven_designation == 'null') {
          alert('Please insert Designation');
          $("#ven_designation").focus();
          return;
        }

        if (!/^\s*[a-zA-Z.\s]+\s*$/.test(ven_designation)) {
          alert('Designation Cannot Have Numbering');
          $("#ven_designation").focus();
          return;
        }

        if (ven_phone == '' || ven_phone == null || ven_phone == 'null') {
          alert('Please insert Phone');
          $("#ven_phone").focus();
          return;
        }

        if (!/^([0-9])[-\0-9]{8,11}$/g.test(ven_phone)) {
          alert('Invalid Contact Number.');
          $("#ven_phone").focus();
          return;
        }

        if (ven_email == '' || ven_email == null || ven_email == 'null') {
          alert('Please insert Email');
          $("#ven_email").focus();
          return;
        }

        if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(ven_email)) {
          alert('Invalid Email');
          $("#ven_email").focus();
          return;
        }

        if (ven_agency == '' || ven_agency == null || ven_agency == 'null') {
          alert('Please insert Outlet Mapping Request');
          $("#ven_agency").focus();
          return;
        }

        if (ven_code == '' || ven_code == null || ven_code == 'null') {
          alert('Please insert Vendor Code');
          $("#ven_code").focus();
          return;
        }

        $.ajax({
          url: "<?php echo site_url('Supplier_registration/add_vendor_info'); ?>",
          method: "POST",
          data: {
            register_guid: register_guid,
            customer_guid: customer_guid,
            ven_name: ven_name,
            ven_designation: ven_designation,
            ven_phone: ven_phone,
            ven_email: ven_email,
            ven_agency: ven_agency,
            ven_code: ven_code,
            remark_no: remark_no,
            add_user_group:add_user_group
          },
          beforeSend: function() {
            $('.btn').button('loading');
          },
          success: function(data) {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            } else {
              $("#medium-modal").modal('hide');
              alert(json.msg);
              setTimeout(function() {
                $('.btn').button('reset');
                vendor_table(register_guid);
                if (json.check_child_data > 0) {
                  $('#term_btn').show();
                }
              }, 300);
            } //close else
          } //close success
        }); //close ajax
      }); //close part2 ven add button

      $(document).on('click', '#edit_ven_btn', function() {

        var customer_guid = $(this).attr('customer_guid');
        var register_guid = $(this).attr('register_guid');
        var register_c_guid = $(this).attr('register_c_guid');
        var register_mapping_guid = $(this).attr('register_mapping_guid');
        var ven_name = $(this).attr('ven_name');
        var ven_designation = $(this).attr('ven_designation');
        var ven_phone = $(this).attr('ven_phone');
        var ven_email = $(this).attr('ven_email');
        var ven_agency = $(this).attr('ven_agency');
        var ven_code = $(this).attr('ven_code');
        var ven_agency = ven_agency.split(',');
        var ven_code = ven_code.split(',');
        var vendor_code_remark = $(this).attr('vendor_code_remark');
        var user_group_guid = $(this).attr('user_group_guid');

        if (vendor_code_remark == null || vendor_code_remark == 'null') {
          vendor_code_remark = '';
        }

        var modal = $("#medium-modal").modal();

        modal.find('.modal-title').html('Edit Login Account Information');

        methodd = '';

        methodd += '<div class="col-md-12">';

        methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_guid" value="' + register_guid + '"/></div>';

        methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="customer_guid" value="' + customer_guid + '"/></div>';

        methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_c_guid" value="' + register_c_guid + '"/></div>';

        methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_mapping_guid" value="' + register_mapping_guid + '"/></div>';

        methodd += '<div class="col-md-6"><label>Name</label><input type="text" class="form-control input-sm" id="ven_name" autocomplete="off" required="true" value="' + ven_name + '"/></div>';

        methodd += '<div class="col-md-6"><label>Designation</label><input type="text" class="form-control input-sm" id="ven_designation" autocomplete="off" required="true" value="' + ven_designation + '"/></div>';

        methodd += '<div class="col-md-6"><label>Phone No</label><input type="text" class="form-control input-sm" id="ven_phone" autocomplete="off" required="true" value="' + ven_phone + '"/></div>';

        methodd += '<div class="col-md-6"><label>Email Address</label><input type="email" class="form-control " id="ven_email" autocomplete="off" required="true" value="' + ven_email + '"/></div>';

        methodd += '<div class="col-md-12"><label>User Group</label><select class="form-control select2" name="edit_user_group[]" id="edit_user_group" required="true" disabled><option value="">-SELECT DATA-</option><?php foreach ($get_user_group->result() as $row) { ?> <option value="<?php echo $row->user_group_guid ?>"><?php echo $row->user_group_name; ?></option> <?php } ?></select></div>';

        methodd += '<div class="col-md-12"><label>Outlet Mapping Request</label><button id="location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button><select class="form-control select2 select2_agency" name="ven_agency[]" id="ven_agency"  multiple="multiple" required="true"><?php foreach ($ven_agency_sql->result() as $row) { ?> <option value="<?php echo addslashes($row->branch_code) ?>"><?php echo $row->branch_name; ?> - <?php echo $row->branch_code; ?></option> <?php } ?></select></div>';

        methodd += '<div class="col-md-12"><label>Vendor Code</label><select class="form-control select2 vendor_select2" name="ven_code" id="ven_code" required="true" multiple="multiple"><?php foreach ($myArray as $row) {
                                                                                                                                                                                              if (in_array($row, $array)) {
                                                                                                                                                                                                $selected = 'selected';
                                                                                                                                                                                              } else {
                                                                                                                                                                                                $selected = '';
                                                                                                                                                                                              } ?> <option value="<?php echo addslashes($row) ?>" <?php echo $selected ?>> <?php echo $row ?></option> <?php } ?></select></div>';

        methodd += '<div class="col-md-12"><label>Vendor Code Remark</label><input type="text" class="form-control " id="ven_code_remark" autocomplete="off" required="true" value="' + vendor_code_remark + '" /></div>';

        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="edit_ven" class="btn btn-success" value="Save"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);

        setTimeout(function() {
          $('#ven_agency').val(ven_agency);
          $('#ven_code').val(ven_code);
          $('#edit_user_group').val(user_group_guid);
          $('#ven_agency').select2();
          $('#ven_code').select2();

        }, 300);

      }); //close edit part2 vendor

      $(document).on('click', '#edit_ven', function() {

        var register_guid = $('#register_guid').val();
        var customer_guid = $('#customer_guid').val();
        var register_c_guid = $('#register_c_guid').val();
        var register_mapping_guid = $('#register_mapping_guid').val();
        var ven_name = $('#ven_name').val();
        var ven_designation = $('#ven_designation').val();
        var ven_phone = $('#ven_phone').val();
        var ven_email = $('#ven_email').val();
        var ven_agency = $('#ven_agency').val();
        var ven_code = $('#ven_code').val();
        var ven_code_remark = $('#ven_code_remark').val();
        var edit_user_group = $('#edit_user_group').val();

        if (ven_name == '' || ven_name == null || ven_name == 'null') {
          alert('Please insert Name');
          $("#ven_name").focus();
          return;
        }

        if (!/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/.test(ven_name)) {
          alert('Name Cannot Have Numbering');
          $("#ven_name").focus();
          return;
        }

        if (ven_designation == '' || ven_designation == null || ven_designation == 'null') {
          alert('Please insert Designation');
          $("#ven_designation").focus();
          return;
        }

        if (!/^\s*[a-zA-Z.\s]+\s*$/.test(ven_designation)) {
          alert('Designation Cannot Have Numbering');
          $("#ven_designation").focus();
          return;
        }

        if (ven_phone == '' || ven_phone == null || ven_phone == 'null') {
          alert('Please insert Phone');
          $("#ven_phone").focus();
          return;
        }

        if (!/^([0-9])[-\0-9]{8,11}$/g.test(ven_phone)) {
          alert('Invalid Contact Number.');
          $("#ven_phone").focus();
          return;
        }

        if (ven_email == '' || ven_email == null || ven_email == 'null') {
          alert('Please insert Email');
          $("#ven_email").focus();
          return;
        }

        if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(ven_email)) {
          alert('Invalid Email');
          $("#ven_email").focus();
          return;
        }

        if (ven_agency == '' || ven_agency == null || ven_agency == 'null') {
          alert('Please insert Outlet Mapping Request');
          $("#ven_agency").focus();
          return;
        }

        if (ven_code == '' || ven_code == null || ven_code == 'null') {
          alert('Please insert Vendor Code');
          $("#ven_code").focus();
          return;
        }

        $.ajax({
          url: "<?php echo site_url('Supplier_registration/edit_vendor_info'); ?>",
          method: "POST",
          data: {
            register_guid: register_guid,
            customer_guid: customer_guid,
            register_c_guid: register_c_guid,
            register_mapping_guid: register_mapping_guid,
            ven_name: ven_name,
            ven_designation: ven_designation,
            ven_phone: ven_phone,
            ven_email: ven_email,
            ven_agency: ven_agency,
            ven_code: ven_code,
            ven_code_remark: ven_code_remark,
            edit_user_group:edit_user_group
          },
          beforeSend: function() {
            $('.btn').button('loading');
          },
          success: function(data) {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            } else {
              $("#medium-modal").modal('hide');
              alert(json.msg);
              setTimeout(function() {
                $('.btn').button('reset');
                vendor_table(register_guid);
              }, 300);
            } //close else
          } //close success
        }); //close ajax
      }); //close part2 ven add button

      $(document).on('click', '#part_btn', function() {

        var register_guid = $(this).attr('register_guid');
        var customer_guid = $(this).attr('customer_guid');

        var count = $('#participant_tb').dataTable().fnGetData().length;

        if (count != 0) {
          if (count >= 2) {
            alert('Additional fees will be charge for each persons');
          }
        }

        var modal = $("#medium-modal").modal();

        modal.find('.modal-title').html('Create Participant Information');

        methodd = '';

        methodd += '<div class="col-md-12">';

        methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_guid" value="' + register_guid + '"/></div>';

        methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="customer_guid" value="' + customer_guid + '"/></div>';

        methodd += '<div class="col-md-6"><label>Name</label><input type="text" class="form-control input-sm" id="part_name" autocomplete="off" required="true"/></div>';

        methodd += '<div class="col-md-6"><label>IC NO</label><input type="text" class="form-control input-sm" id="part_ic" autocomplete="off" required="true"/></div>';

        methodd += '<div class="col-md-6"><label>Mobile Phone No</label><input type="text" class="form-control input-sm" id="part_mobile" autocomplete="off" required="true"/></div>';

        methodd += '<div class="col-md-6"><label>Email Address</label><input type="email" class="form-control " id="part_email" autocomplete="off" required="true"/></div>';

        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="create_part" class="btn btn-success" value="Create"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);

        // setTimeout(function(){
        //   $('#ven_agency').select2();
        //   $('#ven_code').select2();
        // },300);

      }); //close create part2 train

      $(document).on('click', '#create_part', function() {

        var register_guid = $('#register_guid').val();
        var customer_guid = $('#customer_guid').val();
        var part_name = $('#part_name').val();
        var part_ic = $('#part_ic').val();
        var part_mobile = $('#part_mobile').val();
        var part_email = $('#part_email').val();

        if (part_name == '' || part_name == null || part_name == 'null') {
          alert('Please insert Name');
          $("#part_name").focus();
          return;
        }

        if (part_ic == '' || part_ic == null || part_ic == 'null') {
          alert('Please insert IC Number');
          $("#part_ic").focus();
          return;
        }

        if ((!/(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))-([0-9]{2})-([0-9]{4})$/g.test(part_ic)) &&
          (!/(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))([0-9]{2})([0-9]{4})$/g.test(part_ic))) {
          alert('Invalid IC Number');
          $("#part_ic").focus();
          return;
        }

        if (part_mobile == '' || part_mobile == null || part_mobile == 'null') {
          alert('Please insert Phone');
          $("#part_mobile").focus();
          return;
        }

        if (!/^([0-9])[-\0-9]{8,11}$/g.test(part_mobile)) {
          alert('Invalid Contact Number.');
          $("#part_mobile").focus();
          return;
        }

        if (part_email == '' || part_email == null || part_email == 'null') {
          alert('Please insert Email');
          $("#part_email").focus();
          return;
        }

        if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(part_email)) {
          alert('Invalid Email');
          $("#part_email").focus();
          return;
        }

        $.ajax({
          url: "<?php echo site_url('Supplier_registration/add_part_info'); ?>",
          method: "POST",
          data: {
            register_guid: register_guid,
            customer_guid: customer_guid,
            part_name: part_name,
            part_ic: part_ic,
            part_mobile: part_mobile,
            part_email: part_email
          },
          beforeSend: function() {
            $('.btn').button('loading');
          },
          success: function(data) {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            } else {
              $("#medium-modal").modal('hide');
              alert(json.msg);
              setTimeout(function() {
                $('.btn').button('reset');
                participant_table(register_guid);
              }, 300);
            } //close else
          } //close success
        }); //close ajax
      }); //close part2 train add button

      $(document).on('click', '#edit_part_btn', function() {

        var register_guid = $(this).attr('register_guid');
        var customer_guid = $(this).attr('customer_guid');
        var register_c_guid = $(this).attr('register_c_guid');
        var part_name = $(this).attr('part_name');
        var part_ic = $(this).attr('part_ic');
        var part_mobile = $(this).attr('part_mobile');
        var part_email = $(this).attr('part_email');

        var modal = $("#medium-modal").modal();

        modal.find('.modal-title').html('Edit Participant Information');

        methodd = '';

        methodd += '<div class="col-md-12">';

        methodd += '<input type="hidden" class="form-control input-sm" id="register_guid" value="' + register_guid + '" readonly/>';

        methodd += '<input type="hidden" class="form-control input-sm" id="customer_guid" value="' + customer_guid + '" readonly/>';

        methodd += '<input type="hidden" class="form-control input-sm" id="register_c_guid" value="' + register_c_guid + '" readonly/>';

        methodd += '<div class="col-md-6"><label>Name</label><input type="text" class="form-control input-sm" id="part_name" autocomplete="off" required="true" value="' + part_name + '"/></div>';

        methodd += '<div class="col-md-6"><label>IC NO</label><input type="text" class="form-control input-sm" id="part_ic" autocomplete="off" required="true" value="' + part_ic + '"/></div>';

        methodd += '<div class="col-md-6"><label>Mobile Phone No</label><input type="text" class="form-control input-sm" id="part_mobile" autocomplete="off" required="true" value="' + part_mobile + '"/></div>';

        methodd += '<div class="col-md-6"><label>Email Address</label><input type="email" class="form-control " id="part_email" autocomplete="off" required="true" value="' + part_email + '"/></div>';

        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="edit_part" class="btn btn-success" value="Save"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
      }); //close edit part2 train

      $(document).on('click', '#edit_part', function() {

        var register_guid = $('#register_guid').val();
        var customer_guid = $('#customer_guid').val();
        var register_c_guid = $('#register_c_guid').val();
        var part_name = $('#part_name').val();
        var part_ic = $('#part_ic').val();
        var part_mobile = $('#part_mobile').val();
        var part_email = $('#part_email').val();

        if (part_name == '' || part_name == null || part_name == 'null') {
          alert('Please insert Name');
          $("#part_name").focus();
          return;
        }

        if (part_ic == '' || part_ic == null || part_ic == 'null') {
          alert('Please insert IC Number');
          $("#part_ic").focus();
          return;
        }

        if ((!/(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))-([0-9]{2})-([0-9]{4})$/g.test(part_ic)) &&
          (!/(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))([0-9]{2})([0-9]{4})$/g.test(part_ic))) {
          alert('Invalid IC Number');
          $("#part_ic").focus();
          return;
        }

        if (part_mobile == '' || part_mobile == null || part_mobile == 'null') {
          alert('Please insert Phone');
          $("#part_mobile").focus();
          return;
        }

        if (!/^([0-9])[-\0-9]{8,11}$/g.test(part_mobile)) {
          alert('Invalid Contact Number.');
          $("#part_mobile").focus();
          return;
        }

        if (part_email == '' || part_email == null || part_email == 'null') {
          alert('Please insert Email');
          $("#part_email").focus();
          return;
        }

        if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(part_email)) {
          alert('Invalid Email');
          $("#part_email").focus();
          return;
        }

        $.ajax({
          url: "<?php echo site_url('Supplier_registration/edit_part_info'); ?>",
          method: "POST",
          data: {
            register_guid: register_guid,
            customer_guid: customer_guid,
            register_c_guid: register_c_guid,
            part_name: part_name,
            part_ic: part_ic,
            part_mobile: part_mobile,
            part_email: part_email
          },
          beforeSend: function() {
            $('.btn').button('loading');
          },
          success: function(data) {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            } else {
              $("#medium-modal").modal('hide');
              alert(json.msg);
              setTimeout(function() {
                $('.btn').button('reset');
                participant_table(register_guid);
              }, 300);
            } //close else
          } //close success
        }); //close ajax
      }); //close part2 train add button

      $(document).on('click', '#save_btn', function() {
        $('.add_save_status').html('<input type="hidden" id="save_status" name="save_status" value="1" readonly/>');
      });

      //set status for submit form 
      $(document).on('click', '#submit-data', function() {
        $('.add_save_status').html('<input type="hidden" id="save_status" name="save_status" value="0" readonly/>');
      });

      $(document).on('click', '#location_all', function() {
        // alert();
        $("#ven_agency option").prop('selected', true);
        $(".select2").select2();
        die;
      }); //CLOSE ONCLICK  

      $(document).on('click', '#location_all_dis', function() {
        // alert();
        $("#ven_agency option").prop('selected', false);
        $(".select2").select2();
        die;
      }); //CLOSE ONCLICK  

      $(document).on('click', '#ctrl_p', function() {
        window.print();
      });

      $(document).on('click', '#active_btn', function() {
        var register_guid = $(this).attr('register_guid');
        var register_c_guid = $(this).attr('register_c_guid');
        var isdelete = $(this).attr('isdelete');
        //confirmation_modal('Are you sure want to Active/Deactive?');
        confirmation_modal('Are you sure want to Delete?');
        $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function() {
          $.ajax({
            url: "<?php echo site_url('Supplier_registration/active_status'); ?>",
            method: "POST",
            data: {
              register_guid: register_guid,
              register_c_guid: register_c_guid,
              isdelete: isdelete
            },
            beforeSend: function() {
              $('.btn').button('loading');
            },
            success: function(data) {
              json = JSON.parse(data);
              if (json.para1 == '1') {
                $('#alertmodal').modal('hide');
                alert(json.msg);
                $('.btn').button('reset');
              } else {
                $('#alertmodal').modal('hide');
                alert(json.msg);
                setTimeout(function() {
                  $('.btn').button('reset');
                  vendor_table(register_guid);
                  participant_table(register_guid);
                  if (json.check_child_data > 0) {
                    $('#term_btn').show();
                  } else {
                    $('#term_btn').hide();
                  }
                }, 300);
              } //close else
            } //close success
          }); //close ajax
        }); //close document yes click
      });

      $(document).on('change', '#business_desc', function() {

        var description = $(this).val();

        if (description == 'Others') {
          $('#bus_desc_others').attr('readonly', false);
          if ($('#bus_desc_others').val() == '') {
            $('#bus_desc_others').val(sessionStorage.getItem("bus_desc_others"));
          } else {
            $('#bus_desc_others').val($('#bus_desc_others').val());
          }

        } else if ((description == '')) {
          $('#bus_desc_others').attr('readonly', true);
          $('#bus_desc_others').val('');
        } else {
          $('#bus_desc_others').attr('readonly', true);
          $('#bus_desc_others').val('');
        }

      });

      $(document).on('keyup', '.set_reset', function() {

        if (typeof(Storage) !== "undefined") {
          var myStorage = window.sessionStorage;
          var email_1 = $('#comp_mail').val();
          var email_part = $('#comp_email').val();
          var comp_add = $('#comp_add').val();
          var comp_post = $('#comp_post').val();
          var comp_contact = $('#comp_contact').val();
          var comp_fax = $('#comp_fax').val();
          var bus_desc_others = $('#bus_desc_others').val();
          var billing_contact = $('#billing_contact').val();

          var storage1 = sessionStorage.setItem("comp_mail", email_1);
          var storage2 = sessionStorage.setItem("comp_email", email_part);
          var storage3 = sessionStorage.setItem("comp_add", comp_add);
          var storage4 = sessionStorage.setItem("comp_post", comp_post);
          var storage5 = sessionStorage.setItem("comp_contact", comp_contact);
          var storage6 = sessionStorage.setItem("comp_fax", comp_fax);
          var storage7 = sessionStorage.setItem("bus_desc_others", bus_desc_others);
          var storage10 = sessionStorage.setItem("billing_contact", billing_contact);
        }
      });

      $(document).on('change', '#comp_state', function() {

        if (typeof(Storage) !== "undefined") {
          var myStorage = window.sessionStorage;
          var comp_state = $('#comp_state').val();
          var storage8 = sessionStorage.setItem("comp_state", comp_state);
        }
      });

      $(document).on('change', '#business_desc', function() {

        if (typeof(Storage) !== "undefined") {
          var myStorage = window.sessionStorage;
          var business_desc = $('#business_desc').val();
          var storage9 = sessionStorage.setItem("business_desc", business_desc);
        }
      });

      if ($('#comp_mail').val() == '') {
        $('#comp_mail').val(sessionStorage.getItem("comp_mail"));
      }

      if ($('#comp_email').val() == '') {
        $('#comp_email').val(sessionStorage.getItem("comp_email"));
      }

      if ($('#comp_add').val() == '') {
        $('#comp_add').val(sessionStorage.getItem("comp_add"));
      }

      if ($('#comp_post').val() == '') {
        $('#comp_post').val(sessionStorage.getItem("comp_post"));
      }

      if ($('#billing_contact').val() == '') {
        $('#billing_contact').val(sessionStorage.getItem("billing_contact"));
      }

      if ($('#comp_contact').val() == '') {
        $('#comp_contact').val(sessionStorage.getItem("comp_contact"));
      }

      if ($('#comp_fax').val() == '') {
        $('#comp_fax').val(sessionStorage.getItem("comp_fax"));
      }

      if ($('#bus_desc_others').val() == '') {
        if ($('#bus_desc_others').val() == 'Others') {
          $('#bus_desc_others').attr('readonly', false);
          $('#bus_desc_others').val(sessionStorage.getItem("bus_desc_others"));
        }
      }

      if ($('#hidden_state').val() != '') {
        $('#comp_state').val($('#comp_state').val());
        $('#comp_state').trigger('change');
      } else if ($('#comp_state').val() == '') {
        if (sessionStorage.getItem("comp_state") != $('#comp_state').val()) {
          $('#comp_state').val(sessionStorage.getItem("comp_state"));
        }
      } else {
        $('#comp_state').val($('#comp_state').val());
        $('#comp_state').trigger('change');
      }

      if ($('#hidden_desc').val() != '') {
        $('#business_desc').val($('#business_desc').val());
        $('#business_desc').trigger('change');
      } else if ($('#business_desc').val() == '') {
        $('#business_desc').val(sessionStorage.getItem("business_desc"));
      } else {
        $('#business_desc').val($('#business_desc').val());
        $('#business_desc').trigger('change');
      }

      if ($('#bus_desc_others').val() == '') {
        $('#bus_desc_others').val(sessionStorage.getItem("bus_desc_others"));
        if ($('#bus_desc_others').val() != '') {
          $('#bus_desc_others').attr('readonly', false);
        }
      }

      $('body').on('hidden.bs.modal', '#medium-modal', function() {
        history.pushState("", document.title, 'register_form_edit?link=' + register_guid);
      })

      $(document).on('click', '#term_btn', function() {
        history.pushState("", document.title, 'register_form_edit?link=' + register_guid + '&termModal');
        var supplier_name = $('#comp_name').val();
        var term_download = $('#term_hidden').val();
        var hidden_memo = $('#hidden_memo').val();
        //alert(hidden_memo); die;
        var modal = $("#medium-modal").modal();

        modal.find('.modal-title').html('Preview Term Sheet');

        methodd = '';

        methodd += '<div class="col-md-12">';

        methodd += '<embed src="<?php echo site_url('Invoice/view_report_term?reg_guid='); ?>' + register_guid + '&form_type=normal#toolbar=0" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';

        if ((hidden_memo != 'outright') && (hidden_memo != 'consignment') && (hidden_memo != 'both')) {
          methodd += '<embed src="<?php echo site_url('Invoice/view_term_special?reg_guid='); ?>' + register_guid + '&form_type=special#toolbar=0" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';
        }

        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="next_btn" class="btn btn-primary" value="Next" > </span> <span class="pull-left"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> </span> </p>';

        modal.find('.modal-body').html(methodd);
        setTimeout(function() {
          modal.find('.modal-footer').html(methodd_footer);
        }, 1500);

      });
      //<input type="button" id="next_btn" class="btn btn-success" value="Next">

      $(document).on('click', '#btn_yes', function() {

        customer_guid = "<?php echo $customer_guid; ?>";
        supplier_guid = "<?php echo $supplier_guid; ?>";
        var hidden_memo = $('#hidden_memo').val();
        $.ajax({
          url: "<?php echo site_url('Supplier_registration/insert_terms_data'); ?>",
          method: "POST",
          data: {
            register_guid: register_guid,
            hidden_memo: hidden_memo,
            customer_guid: customer_guid,
            supplier_guid: supplier_guid
          },
          beforeSend: function() {
            $('.btn').button('loading');
          },
          success: function(data) {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              history.pushState("", document.title, 'register_form_edit?link=' + register_guid + '&termModal');
              location.reload();
            } else {
              $('.btn').button('reset');
              history.pushState("", document.title, 'register_form_edit?link=' + register_guid + '&termModal');
              var supplier_name = $('#comp_name').val();
              var term_download = $('#term_hidden').val();
              var dl_name = supplier_name.replace(/\s/g, '_');

              var modal = $("#static-medium-modal").modal();

              modal.find('.modal-title').html('Term Sheet');

              methodd = '';

              methodd += '<span class="blink" style="color:red;font-size:20px;"><b>To avoid interruptions in your login, please sign and chop the below term sheet, and upload it to the B2B portal when you log in.</b></span>';

              methodd += '<div class="col-md-12">';

              methodd += '<embed src="<?php echo site_url('Invoice/view_report_term?reg_guid='); ?>' + register_guid + '&form_type=normal&supplier_name=' + dl_name + '" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';

              if ((hidden_memo != 'outright') && (hidden_memo != 'consignment') && (hidden_memo != 'both')) {
                methodd += '<embed src="<?php echo site_url('Invoice/view_term_special?reg_guid='); ?>' + register_guid + '&form_type=special&supplier_name=' + dl_name + '" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';
              }

              methodd += '</div>';

              methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="download_btn" class="btn btn-success" value="Submit" supplier_name ="' + supplier_name + '"> </span> </p>';

              modal.find('.modal-body').html(methodd);
              setTimeout(function() {

                modal.find('.modal-footer').html(methodd_footer);
              }, 1500);
            } //close else
          } //close success
        }); //close ajax


      });

      $(document).on('click', '#download_btn', function() {

        $('#last_btn').trigger('click');
        // var hidden_memo = $('#hidden_memo').val();

        // if((hidden_memo == '') || (hidden_memo == 'null') || (hidden_memo == null) || (hidden_memo == 'undefined'))
        // {
        //   alert('Invalid Get Data. Please refresh page.');
        //   return;
        // }

        // var supplier_name = $(this).attr('supplier_name').replace(/\s/g,'_');

        // // var form = document.createElement('a');
        // // form.href = '<?php echo site_url('Invoice/view_report_term?reg_guid='); ?>'+register_guid+'&form_type=normal&supplier_name='+supplier_name;
        // // form.download = '<?php echo site_url('Invoice/view_report_term?reg_guid='); ?>'+register_guid+'&form_type=normal&supplier_name='+supplier_name;
        // // form.setAttribute("target", "_blank");
        // // document.body.appendChild(form);
        // // form.click();
        // //var w1 = window.open('<?php echo site_url('Invoice/view_report_term?reg_guid='); ?>'+register_guid+'&form_type=normal&supplier_name='+supplier_name+'"','_blank');

        // if((hidden_memo != 'outright') && (hidden_memo != 'consignment') && (hidden_memo != 'both'))
        // {
        //   // var w2 = window.open('<?php echo site_url('Invoice/view_term_special?reg_guid='); ?>'+register_guid+'&form_type=special&supplier_name='+supplier_name+'"','_blank');
        //   var check_dl_normal = $('#dl_normal_btn').attr('clicked');
        //   var check_dl_special = $('#dl_special_btn').attr('clicked');

        //   if(check_dl_normal != '1' )
        //   {
        //     alert('Please Download Term Sheet by clicking the Yellow button.');
        //     return;
        //   }

        //   if(check_dl_special != '1')
        //   {
        //     alert('Please Download Special Term Sheet by clicking the Yellow button.');
        //     return;
        //   }
        // }
        // else
        // {
        //   var check_dl_normal = $('#dl_normal_btn').attr('clicked');

        //   if(check_dl_normal != '1')
        //   {
        //     alert('Please Download Term Sheet by clicking the Yellow button.');
        //     return;
        //   }
        // }

        // $.ajax({
        //   url:"<?php echo site_url('Supplier_registration/is_download'); ?>",
        //   method:"POST",
        //   data:{register_guid:register_guid},
        //   beforeSend:function(){
        //     $('.btn').button('loading');
        //   },
        //   success:function(data)
        //   {
        //     json = JSON.parse(data);
        //     if (json.para1 == '1') {
        //       alert(json.msg);
        //       $("#static-medium-modal").modal('hide');
        //       $('.btn').button('reset');
        //       setTimeout(function () { 
        //         $('#last_btn').trigger('click'); 
        //       }, 300);
        //     }else{
        //       alert(json.msg);
        //       $("#static-medium-modal").modal('hide');
        //       $('.btn').button('reset');
        //       setTimeout(function () { 
        //         $('#last_btn').trigger('click'); 
        //       }, 300);
        //     }//close else
        //   }//close success
        // });//close ajax
      });

      $(document).on('click', '#next_btn', function() {
        $("#medium-modal").modal('hide');
        $('#submit-data').trigger('click');
        $('.layout-top-nav').css("padding-right", "");
      });

      $(document).on('click', '.confirmation_no_btn', function() {
        setTimeout(function() {
          $('.layout-top-nav').css("padding-right", "");
        }, 300);
      });

      $(document).on('click', '#dl_special_btn', function() {
        $(this).attr('clicked', '1');
        //alert('you clicked'); die;
      });

      $(document).on('click', '#dl_normal_btn', function() {
        $(this).attr('clicked', '1');
        // alert('you clicked'); die;
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