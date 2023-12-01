<style type="text/css">
#text
{
  color:blue;
  }

  .success-container{
    left: 50%;
    top:100%;
    width:600px;
    transform: translate(-50%, -50%);
    position:fixed;
}

.modalbox.success {
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
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
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
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
  background: #D33724; /*confirmation alert color*/
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

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    display: flex;
    white-space: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar {
  width: 10px;
  height: 5px;
  background-color: #F5F5F5;           /* width of the entire scrollbar */
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
  border-radius: 10px;
  background-color: #F5F5F5;       /* color of the tracking area */
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
  background-color: #B7BABF; /* color of the scrolling */
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: black;
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

              <h4 class="text-bold part1" style="margin-left: 15px; ">Part 1: Organizational Information</h4><br>

                <form action="<?php echo site_url('Registration/register_update')?>?register_guid=<?php echo $_REQUEST['register_guid'] ?>" method="post" id="myForm">
                    <div class="form-row">
                    <div class="form-group col-md-6">

                    <label for="exampleInputEmail1">Company Name <span class="text-danger">*</span> </label>

                    <?php foreach ($register->result() as $key) { ?>
                      <input type="text" class="form-control" id="comp_name" name="comp_name"  aria-describedby="emailHelp" value="<?php echo $key->comp_name?>" readonly required="true">
                    <?php } ?>

                    </div>
                    </div>

                    <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Supply Type</label><br>

                      <?php if($register->num_rows() == 0) { ?>
                        <input type="checkbox" class="set_reset" id="outright" name="outright" value="outright">
                        <label for="vehicle1" style="margin-left: 5px;"> OUTRIGHT</label><br>
                        <input type="checkbox" class="set_reset" id="consignment" name="consignment" value="consignment">
                        <label for="vehicle1" style="margin-left: 5px;"> CONSIGNMENT</label><br>
                        </div>
                      <?php } ?>

                      <?php foreach ($register->result() as $key) { ?>
                      <input type="checkbox" class="set_reset" id="outright" name="supply_type" value="outright"<?php if($key->supply_type == 'outright'){ ?> checked> <?php }else{?> > <?php }?>
                      <label for="vehicle1" style="margin-left: 5px;"> OUTRIGHT</label><br>
                        
                      <input type="checkbox" class="set_reset" id="consignment" name="supply_type" value="consignment"<?php if($key->supply_type == 'consignment'){ ?> checked> <?php }else{?> > <?php } ?>
                       <label for="vehicle1" style="margin-left: 5px;"> CONSIGNMENT</label><br>

                      </div>
                      <?php } ?>

                     <div class="form-group col-md-12">
                     <label for="exampleInputEmail1">Company Registration No <span class="text-danger">*</span> </label>
                      
                      <?php if($register->num_rows() == 0) { ?>
                        <input type="text" class="form-control" id="comp_no" name="comp_no" aria-describedby="emailHelp" required="true">
                      <?php } ?>

                     <?php foreach ($register->result() as $key) { ?>
                     <input type="text" class="form-control" id="comp_no" name="comp_no" aria-describedby="emailHelp" value="<?php echo $key->comp_no ?>" readonly required="true">
                     <?php } ?>
                     </div>
                     
                    <!--<div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Company Registration</label>
                    <input type="text" class="form-control" id="Company Registration" aria-describedby="emailHelp" placeholder="Company Registration">
                  
                  </div>-->
                  <?php if($register->num_rows() != 0)
                  {
                   foreach ($register->result() as $key) {
                   ?>
                   <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Business Address/Billing Address <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control set_reset" id="comp_add" name="comp_add"  aria-describedby="emailHelp" placeholder="Business Address/Billing Address" value="<?php echo $key->comp_add ?>"  required="true">
                   </div>


                   <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Postcode <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control set_reset" id="comp_post" name="comp_post"  aria-describedby="emailHelp" placeholder="Postcode" value="<?php echo $key->supplier_postcode ?>" required="true">
                  </div>

                   <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">State <span class="text-danger">*</span> </label>
                    <select class="form-control set_reset" id="comp_state" name="comp_state" required="true">
                      <option value="Johor" <?php if('Johor' == $key->supplier_state ){ echo "selected";} ?> >Johor</option>
                      <option value="Kedah" <?php if('Kedah' == $key->supplier_state ){ echo "selected";} ?> >Kedah</option>
                      <option value="Kelantan" <?php if('Kelantan' == $key->supplier_state ){ echo "selected";} ?> >Kelantan</option>
                      <option value="Malacca" <?php if('Malacca' == $key->supplier_state ){ echo "selected";} ?> >Malacca</option>
                      <option value="Negeri Sembilan" <?php if('Negeri Sembilan' == $key->supplier_state ){ echo "selected";} ?>>Negeri Sembilan</option>
                      <option value="Pahang" <?php if('Pahang' == $key->supplier_state ){ echo "selected";} ?> >Pahang</option>
                      <option value="Penang" <?php if('Penang' == $key->supplier_state ){ echo "selected";} ?> >Penang</option>
                      <option value="Perak" <?php if('Perak' == $key->supplier_state ){ echo "selected";} ?> >Perak</option>
                      <option value="Perlis" <?php if('Perlis' == $key->supplier_state ){ echo "selected";} ?> >Perlis</option>
                      <option value="Selangor" <?php if('Selangor' == $key->supplier_state ){ echo "selected";} ?> >Selangor</option>
                      <option value="Terengganu" <?php if('Terengganu' == $key->supplier_state ){ echo "selected";} ?> >Terengganu</option>
                      <option value="Sabah" <?php if('Sabah' == $key->supplier_state ){ echo "selected";} ?> >Sabah</option>
                      <option value="Sarawak" <?php if('Sarawak' == $key->supplier_state ){ echo "selected";} ?> >Sarawak</option>
                    </select>
                    <!-- <input type="text" class="form-control" id="comp_state" name="comp_state"  aria-describedby="emailHelp" placeholder="State" value="<?php echo $key->supplier_state ?>" required="true"> -->
              
                  </div>

                   <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Billing Email Address </label><span class="text-danger">*</span>
                    <input type="email" class="form-control set_reset" id="comp_mail" name="comp_mail" aria-describedby="emailHelp" placeholder="Email Address" value="<?php echo $key->org_email ?>" required="true">
                   </div>

                   <div class="form-group col-md-4">
                   <label for="exampleInputEmail1">Phone <span class="text-danger">*</span> </label>
                    <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="comp_contact" name="comp_contact" aria-describedby="emailHelp" value="<?php echo $key->comp_contact ?>" required="true">
                   </div>

                   <div class="form-group col-md-4">
                   <label for="exampleInputEmail1">Fax </label>
                   <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="comp_fax" name="comp_fax" aria-describedby="emailHelp" value="<?php echo $key->comp_fax ?>">
                   </div>

                   <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Business Description </label><br>
                    <input type="checkbox" id="Bread" name="bus_desc" value="Bread" <?php if($key->bus_desc == 'Bread'){ ?> checked> <?php } ?>
                      
                    <label for="Bread" style="margin-left: 5px;"> Bread</label>
                    <input type="checkbox" id="Fresh" name="bus_desc" value="Fresh" style="margin-left: 10px;"<?php if($key->bus_desc == 'Fresh'){ ?> checked> <?php } ?>
                      
                    <label for="Fresh" style="margin-left: 5px;"> Fresh</label><br>
                    <div class="form-group col-md-6" style="margin-left: -15px;">
                    <label for="exampleInputEmail1">Others:</label>
                    <input type="text" class="form-control set_reset" id="bus_desc_others"   name="bus_desc_others" aria-describedby="emailHelp" value="<?php echo $key->bus_desc_others ?>">
                    </div>
                    
                    </div>

                    <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Retailer Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="acc_name" name="acc_name" aria-describedby="emailHelp" value="<?php echo $key->acc_name ?>" readonly required="true"  >
                    </div>

                     <a href="javascript:void(0);" class="addbtn" title="Add field"><span class="fa fa-plus" style="float: right; margin-top: -15px;"></span></a>

                    <div class="form-group col-md-6 vendor" id="vendor">
                    <button type="button" style="float: right;" class="btn btn-xs btn-default" id="add_code_modal" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>'>Add Code</button>
                    <label for="exampleInputEmail1">Vendor Code (refer to Retailer) <span class="text-danger">*</span></label>
                      <?php 
                              $part5 = $key->acc_no;

                              $array =  explode(',', $part5);


                                ?>
                                <select class="form-control select2 vendor_select2 set_reset" name="acc_no[]"" required="true" multiple="multiple">
                                <!-- <option value="<?php echo $items ?>" selected><?php echo $items ?></option> -->
                                <?php
                                foreach ($myArray as $row)
                                {
                                  if(in_array($row,$array))
                                  {
                                    $selected = 'selected';
                                  }
                                  else
                                  {
                                    $selected = '';
                                  }
                                  ?>
                                  <!-- <option value="<?php echo $items ?>" selected><?php echo $items ?></option> -->

                                  <option value="<?php echo addslashes($row)?>" <?php echo $selected?>> <?php echo $row?></option>
                                <?php
                                }
                                ?>
                              </select><br><br>
                      
                       <!-- <?php 
                              $part5 = $key->acc_no;

                              $array =  explode(',', $part5);
                    
                              foreach ($array as $items) {
                              echo "  <input type='text' class='form-control' id='acc_no' name='acc_no[]' aria-describedby='emailHelp' value=$items ><br>";
                              }
                              ?>
                    -->
                    </div>

                    <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Vendor Code Remark</label>
                      <?php 
                              $part5 = $key->vendor_code_remark;

                              $array =  explode(',', $part5);

                              $array = array_filter($array);
                                ?>
                                <select class="form-control select2 vendor_select2 set_reset" name="acc_no_other[]"" multiple="multiple">
                                <!-- <option value="<?php echo $items ?>" selected><?php echo $items ?></option> -->
                                <?php
                                foreach ($array as $row)
                                {
                                  ?>
                                  <!-- <option value="<?php echo $items ?>" selected><?php echo $items ?></option> -->

                                  <option value="<?php echo addslashes($row)?>" selected > <?php echo $row?></option>
                                <?php
                                }
                                ?>
                              </select>
                    </div>

                    <h4 class=" text-bold " style="margin-left: 15px;">Part 2: Vendor/ Use Account Information</h4><br>
                    <div >
                      <div>
                        <?php 
                              $numbers = $key->ven_name;

                              $array =  explode('/', $numbers);

                              $count_array = count($array);

                        ?>

                        <a href="javascript:void(0);" class="add_button" title="Add field" id="add_btn_vendor" count_array="<?php echo $count_array; ?>"><span class="fa fa-plus" style="float: right; margin-top: -15px;"></span></a>
                     

                      </div>
                    </div>  

                    <div class="info">
                      <div class="row" style="padding-left: 15px;">

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Name <span class="text-danger">*</span></label>
                                <?php
                            if($key->ven_name == null || $key->ven_name == '')
                            {
                                   echo " <input type='text' class='form-control check_name set_reset' id='ven_name' name='ven_name[]' aria-describedby='emailHelp' placeholder='Name' required pattern='[^/,]+' title='You cannot use special characters : / '><br> ";
                            }
                            else{
                            ?>
                              <?php 
                              $numbers = $key->ven_name;

                              $array =  explode('/', $numbers);
                    
                              foreach ($array as $item) {
                              echo " <input type='text' class='form-control check_name set_reset' id='ven_name' name='ven_name[]' aria-describedby='emailHelp' value='$item' placeholder='Name' required pattern='[^/,]+' title='You cannot use special characters : / '><br>";
                              }}
                              ?>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Designation </label>
                              <?php
                            if($key->ven_designation == null || $key->ven_designation == '')
                            {
                                   echo " <input type='text' class='form-control set_reset' id='ven_designation'  name='ven_designation[]' aria-describedby='emailHelp' placeholder='Designation' required pattern='[^/,]+' title='You cannot use special characters : / '><br> ";
                            }
                            else{
                            ?>

                            <?php 
                              $numbers1 = $key->ven_designation;

                              $array =  explode('/', $numbers1);
                    
                              foreach ($array as $item) {
                              echo " <input type='text' class='form-control set_reset' id='ven_designation' name='ven_designation[]' aria-describedby='emailHelp' placeholder='Designation' value='$item' required pattern='[^/,]+' title='You cannot use special characters : / '><br>";
                              }}
                              ?>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Phone No <span class="text-danger">*</span></label>
                             <?php
                            if($key->ven_phone == null || $key->ven_phone == '')
                            {
                                   echo " <input type='text' class='form-control set_reset' id='ven_phone'  name='ven_phone[]' aria-describedby='emailHelp' placeholder='Phone No' required pattern='[^/,]+' title='You cannot use special characters : / '><br> ";
                            }
                            else{
                            ?>

                            <?php 
                              $numbers2 = $key->ven_phone;

                              $array =  explode('/', $numbers2);
                    
                              foreach ($array as $item) {
                              echo " <input type='text' class='form-control set_reset' id='ven_phone'  name='ven_phone[]' aria-describedby='emailHelp' placeholder='Phone No' value='$item' required pattern='[^/,]+' title='You cannot use special characters : / '><br>";
                              }}
                              ?>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Email Address <span class="text-danger">*</span></label>
                            <?php
                            if($key->ven_email == null || $key->ven_email == '')
                            {
                                   echo " <input type='email' class='form-control check_email set_reset' id='ven_email' name='ven_email[]' aria-describedby='emailHelp' placeholder='Email Address' required pattern='[^/,]+' title='You cannot use special characters : / '><br> ";
                            }
                            else{
                            ?>
                            <?php 
                              $numbers3 = $key->ven_email;

                              $array =  explode('/', $numbers3);
                    
                              foreach ($array as $item) {
                              echo " <input type='email' class='form-control check_email set_reset' id='ven_email' name='ven_email[]' aria-describedby='emailHelp' placeholder='Email Address' value='$item' required pattern='[^/,]+' title='You cannot use special characters : / '><br>";
                              }}
                              ?>
                          </div>

                          <div class="form-group col-md-2 check_content">
                          <label for="exampleInputEmail1">Agency/Outlet Requests</label>
                            <?php
                            if($key->ven_agency == null || $key->ven_agency == '')
                            { 
                              // $i = '0';
                              // $id ='ven_agency'.$i;
                              // $id_hidden ='hidden_val'.$i;
                              ?>
                              <input type="text" value='' name="hidden_val[]" id="hidden_val0" hidden>
                              <select class='form-control select2 select2_agency set_reset' name='ven_agency[]' required='true' multiple="multiple" id="ven_agency0">
                                <option value="All" >All</option>
                              <?php

                              foreach ($ven_agency_sql->result() as $row) {
                              ?>
                              <option value="<?php echo addslashes($row->branch_code) ?>"><?php echo $row->branch_name; ?> - <?php echo $row->branch_code; ?></option>
                              
                              <?php
                              }
                              ?>
                              </select>
                              <?php
                            }
                            else{
                            ?>
                             <?php 
                              $numbers4 = $key->ven_agency;
                              $array =  explode('/', $numbers4);
                              $i = '0';
                               
                              foreach ($array as $item) {
                                $new_item =  explode('/', $item);
                                $new_item =  implode('', $new_item);
                                $new_item = "".$new_item."";
                                $new_item = explode(',',$new_item);
                                $id ='ven_agency'.$i;
                                $id_hidden ='hidden_val'.$i;

                                // $(this).select2('data').id;
                                ?>
                                <input type="text" value=<?php echo $item;?> name="hidden_val[]" id="hidden_val<?php echo $i ?>" hidden>
                                <select class="form-control select2 select2_agency set_reset" name="ven_agency[]" id="ven_agency<?php echo $i ?>"  multiple="multiple" required="true">

                                <option value="All" >All</option>
                                <?php
                                
                                foreach ($ven_agency_sql->result() as $row) {

                                 if(in_array($row->branch_code,$new_item))
                                  {
                                    $selected = 'selected';
                                  }
                                  else
                                  {
                                    $selected = '';
                                  }
                                      
                                   ?>
                                   <option value="<?php echo addslashes($row->branch_code)?>" <?php echo $selected?> > <?php echo $row->branch_name;?> - <?php echo $row->branch_code;?></option>
                                   <?php
                                  
                                }
                                ?>
                                  </select><br><br>
                                  
                                  
                                <?php
                                  // echo "<select class='form-control' name='ven_agency[]' required='true'> <option value=$item >$item</option></select> <br>";
                                $i++;
                              }
                              ?>
                              
                            <?php
                            }

                              ?>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">User Status </label>

                          <?php
                            if($key->isdelete == null || $key->isdelete == 0)
                            {
                               $numbers4 = $key->ven_agency;

                              $array =  explode('/', $numbers4);
                    
                              foreach ($array as $item) {
                              echo "<p>Deactive</p><br>";
                              }
                            }
                            else{
                            ?>
                             <?php 
                              $numbers4 = $key->ven_agency;

                              $array =  explode('/', $numbers4);
                    
                              foreach ($array as $item) {
                              echo "<p>Active</p><input  class='isdelete' id='isdelete' name='isdelete' type='checkbox'  value='0' style='float:right;margin-right:20px;margin-top:-25px;' /><br>";
                              }}
                              ?>
                        
                          </div>

                     </div>

                    </div>
                    <!--append here -->
                    <div class="field_wrapper">
                    </div>

                    <br>
                  <?php } //close foreach register ?> 
                <?php 
                } // if register num rows equal to 0
                else
                {
                  ?>
                   <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Business Address/Billing Address <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control set_reset" id="comp_add" name="comp_add"  aria-describedby="emailHelp" placeholder="Business Address/Billing Address" required="true">
                   </div>


                   <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Postcode <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control set_reset" id="comp_post" name="comp_post"  aria-describedby="emailHelp" placeholder="Postcode" required="true">
              
                  </div>

                   <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">State <span class="text-danger">*</span> </label>
                    <select class="form-control set_reset" id="comp_state" name="comp_state" required="true">
                      <option value="Johor" >Johor</option>
                      <option value="Kedah" >Kedah</option>
                      <option value="Kelantan" >Kelantan</option>
                      <option value="Malacca" >Malacca</option>
                      <option value="Negeri Sembilan" >Negeri Sembilan</option>
                      <option value="Pahang" >Pahang</option>
                      <option value="Penang">Penang</option>
                      <option value="Perak" >Perak</option>
                      <option value="Perlis" >Perlis</option>
                      <option value="Selangor"  >Selangor</option>
                      <option value="Terengganu" >Terengganu</option>
                      <option value="Sabah">Sabah</option>
                      <option value="Sarawak">Sarawak</option>
                    </select>
                   <!--  <input type="text" class="form-control" id="comp_state" name="comp_state"  aria-describedby="emailHelp" placeholder="State" required="true"> -->
              
                  </div>

                   <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Email Address </label>
                    <input type="email" class="form-control set_reset" id="comp_mail" name="comp_mail" aria-describedby="emailHelp" placeholder="Email Address" required="true">
                   </div>

                   <div class="form-group col-md-4">
                   <label for="exampleInputEmail1">Phone <span class="text-danger">*</span> </label>
                    <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="comp_contact" name="comp_contact" aria-describedby="emailHelp" required="true">
                   </div>

                   <div class="form-group col-md-4">
                   <label for="exampleInputEmail1">Fax </label>
                   <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="comp_fax" name="comp_fax" aria-describedby="emailHelp">
                   </div>

                   <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Business Description </label><br>

                    <input type="checkbox" id="Bread" name="Bread" value="Bread" style="margin-left: 10px;">
                    <label for="Bread" style="margin-left: 5px;"> Bread</label>

                    <input type="checkbox" id="Fresh" name="Fresh" value="Fresh" style="margin-left: 10px;">
                    <label for="Fresh" style="margin-left: 5px;"> Fresh</label><br>

                    <div class="form-group col-md-6" style="margin-left: -15px;">
                    <label for="exampleInputEmail1">Others:</label>
                    <input type="text" class="form-control set_reset" id="bus_desc_others" name="bus_desc_others" aria-describedby="emailHelp">
                    </div>
              
                    </div>

                    <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Retailer Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="acc_name" name="acc_name" aria-describedby="emailHelp">
                    </div>

                     <a href="javascript:void(0);" class="addbtn" title="Add field"><span class="fa fa-plus" style="float: right; margin-top: -15px;"></span></a>

                    <div class="form-group col-md-6 vendor" id="vendor">
                    <label for="exampleInputEmail1">Vendor Code (refer to Retailer) </label>
                      <select class="form-control" name="acc_no[]" >
                          <?php
                          foreach ($vendor_code_sql->result() as $row)
                          {
                            ?>
                            <option value="<?php echo addslashes($row->supplier_group_name)?>"> <?php echo $row->supplier_group_name;?></option>
                            <?php
                          }
                        ?>
                      </select><br>
                    </div>


                    <h4 class=" text-bold " style="margin-left: 15px;">Part 2: Vendor/ Use Account Information</h4><br>
                    <div >
                      <div>

                        <a href="javascript:void(0);" class="add_button" title="Add field" count_array="1"><span class="fa fa-plus" style="float: right; margin-top: -15px;"></span></a>
                     

                      </div>
                    </div>

                    <div class="info">
                      <!-- <div class="row"> -->

                         <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Name </label>
                          <input type="text"  class="form-control ven_name check_name set_reset" id="ven_name1"  name="ven_name[]" aria-describedby="emailHelp" placeholder="Name" required pattern='[^/,]+' title='You cannot use special characters : / '>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Designation </label>
                          <input type="text" class="form-control set_reset"  id="ven_designation" name="ven_designation[]" aria-describedby="emailHelp" placeholder="Designation" required pattern='[^/,]+' title='You cannot use special characters : / '>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Phone No </label>
                          <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="ven_phone" name="ven_phone[]" aria-describedby="emailHelp" placeholder="Phone No" required pattern='[^/,]+' title='You cannot use special characters : / '>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Email Address </label>
                          <input type="email" class="form-control set_reset" id="ven_email check_email" name="ven_email[]" aria-describedby="emailHelp" placeholder="Email Address" required pattern='[^/,]+' title='You cannot use special characters : / '>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Agency/Outlet Requests</label>
                          <input type="text" name="hidden_val[]" id="hidden_val0" hidden>
                          <select class="form-control select2 select2_agency set_reset" name="ven_agency[]" multiple="multiple" id="ven_agency0">
                            <?php foreach ($ven_agency_sql->result() as $key) 
                            { ?>
                              <option value="<?php echo $key->branch_code ?>"><?php echo $key->branch_name;?> - <?php echo $key->branch_code;?></option>
                            <?php } ?>
                            </select>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">User Status </label>
                          <?php echo "<p>Deactive</p><br>";?>
                          
                          </div>
                     <!-- </div> -->
                    </div>

                     <div class="field_wrapper">
                     </div>
                  <?php } //close else ?> 
                    
                  <div class="note" style="margin-left: 15px;">
                  <h5 class="text-bold">
                  <a href="<?php echo base_url("assets/MEMO.pdf") ?>" download ><span style="color:red;">Refer to Rexbridge Memo Charges.</span></a>
                  </h5>
                  <!--There will be a one off RM300 Registration Fees and monthly subscriptions incur once Register. -->
                  <h5 class="text-bold">
                    RM300 Registration Fees charge per Retailer.
                  </h5>
                  <h5>
                    Please contact <span class="text-bold"> xBridge Support Team </span> @ <span><a href="mailto:support@xbridge.my">support@xbridge.my</a></span> or call us @ +60177451185 / +0177159340 should you require further clarifications on registration process and access to <span class="text-bold">xBridge B2B portal</span>.
                  </h5>
                <hr style="width:100%;border-width:2px;color:black;background-color:black">
                  <h4>
                    <b>xBridge B2B Portal Training is <span ><u style="background-color: yellow;">OPTIONAL</u></span>. If interested please complete this Training form together with payment of the Training Fees: RM200 (for 2 pax), additional RM100 for each subsequent participant.</b>
                  </h4><br>
                  </div>
                    <h4 class=" text-bold " style="margin-left: 15px;">Part 1: Organizational Information</h4><br>

                    <div class="form-group col-md-6" >
                    <label for="exampleInputEmail1">Company Name</label>
                    <?php if($register->num_rows() != 0)
                    {
                     foreach ($register->result() as $key) { ?>
                    <input type="text" class="form-control" id="part_comp_name" name="part_comp_name" aria-describedby="emailHelp"   value="<?php echo $key->comp_name ?>" readonly>
                    
                    </div>

                    <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Email Address</label>
                    <input type="email" class="form-control set_reset" id="comp_email" name="comp_email" aria-describedby="emailHelp" placeholder="Email Address" value="<?php echo $key->org_part_email ?>"><br>
                    </div>


                     <h4 class=" text-bold " style="margin-left: 15px;">Part 2:Participant Information</h4><br>
                      <div class="field1">
                         <div>
                             <a href="javascript:void(0);" class="add" title="Add field"><span class="fa fa-plus" style="float: right; margin-top: -15px;"></span></a>
                         </div>
                      </div>

                    <div class="details"  >

                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Name</label>
                             <?php 
                              $part1 = $key->part_name;

                              $array =  explode('/', $part1);
                    
                              foreach ($array as $items) {
                              echo " <input type='text' class='form-control set_reset' id='part_name'  name='part_name[]' aria-describedby='emailHelp' value='$items' pattern='[^/,]+' title='You cannot use special characters : / ' ><br>";
                              }
                              ?>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1">IC No </label>
                            <?php 
                              $part2 = $key->part_ic;

                              $array =  explode('/', $part2);
                    
                              foreach ($array as $items) {
                              echo " <input type='text' class='form-control set_reset' id='part_ic'  name='part_ic[]' aria-describedby='emailHelp' value='$items' pattern='[^/,]+' title='You cannot use special characters : / ' ><br>";
                              }
                              ?>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="exampleInputEmail1">Mobile Phone No</label>
                            <?php 
                              $part3 = $key->part_mobile;

                              $array =  explode('/', $part3);
                    
                              foreach ($array as $items) {
                              echo " <input type='text' class='form-control set_reset' id='part_mobile'  name='part_mobile[]' aria-describedby='emailHelp' value='$items' pattern='[^/,]+' title='You cannot use special characters : / '><br>";
                              }
                              ?>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1">Email Address </label>
                               <?php 
                              $part4 = $key->part_email;

                              $array =  explode('/', $part4);
                    
                              foreach ($array as $items) {
                              echo " <input type='email' class='form-control set_reset' id='part_email'  name='part_email[]' aria-describedby='emailHelp' value='$items' pattern='[^/,]+' title='You cannot use special characters : / '><br>";
                              }
                              ?>
                        </div>
                        
                      <?php } //close foreach
                    }
                    else // else if register num rows equal to 0
                    { ?>
                      <input type="text" class="form-control" id="comp_name" name="comp_name" aria-describedby="emailHelp" >
                    
                      </div>

                      <div class="form-group col-md-6">
                      <label for="exampleInputEmail1">Email Address</label>
                      <input type="email" class="form-control" id="comp_email set_reset" name="comp_email" aria-describedby="emailHelp" placeholder="Email Address" ><br>
                      </div>


                     <h4 class=" text-bold " style="margin-left: 15px;">Part 2:Participant Information</h4><br>
                      <div class="field1">
                         <div>
                             <a href="javascript:void(0);" class="add" title="Add field"><span class="fa fa-plus" style="float: right; margin-top: -15px;"></span></a>
                         </div>
                      </div>

                    <div class="details"  >

                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Name</label>
                             <?php 
                              echo " <input type='text' class='form-control set_reset' id='part_name' placeholder='Name' name='part_name[]' aria-describedby='emailHelp' required pattern='[^/,]+' title='You cannot use special characters : / '><br>";
                              ?>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1">IC No </label>
                            <?php 
                              echo " <input type='text' class='form-control set_reset' id='part_ic' placeholder='IC' name='part_ic[]' aria-describedby='emailHelp' required pattern='[^/,]+' title='You cannot use special characters : / '><br>";
                              ?>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="exampleInputEmail1">Mobile Phone No</label>
                            <?php 
                              echo " <input type='text' class='form-control set_reset' id='part_mobile' placeholder='Phone No'  name='part_mobile[]' aria-describedby='emailHelp' required pattern='[^/,]+' title='You cannot use special characters : / '><br>";
                              ?>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1">Email Address </label>
                              <?php 
                              echo " <input type='email' class='form-control set_reset' id='part_email' placeholder='Email Address'  name='part_email[]' aria-describedby='emailHelp' required pattern='[^/,]+' title='You cannot use special characters : / '><br>";
                              ?>
                        </div>
                    <?php } ?>
                    </div>

                    <div class="field">
                    </div>


                  <div class="note2" style="margin-left: 15px;">
                  <h5 class="text-md-left ">

                  Payment can be made thru Internet Banking or Account Payable Cheque based on the below bank details:

                  </h5>

                    <ul style="list-style-type: lower-alpha;">

                         <li> Account Name : <span class="text-bold">REXBRIDGE SDN BHD (1106802H)</span></li>
                         <li> Name of bank : <span class="text-bold"> Public Bank </span></li>
                         <li> Account number: <span class="text-bold"> 3198918900 </span></li>

                    </ul>

                 <h5 class="text-md-left ">

                  Please email the <b>bank receipt</b> to <a href="mailto:support@xbridge.my">support@xbridge.my</a> for issuance of official receipt:

                 </h5>

                    <ul style="list-style-type: lower-alpha;">

                        <li> Company Name & Registration No </li>
                        <li>  Email</li>
                        <li> Contact person</li>

                    </ul>

                   <h5>
                    Please contact <span class="text-bold"> xBridge Support Team </span> @ <span><a href="mailto:support@xbridge.my">support@xbridge.my</a></span> or call us @ +60177451185 / +0177159340 should you require further clarifications on registration process and access to <span class="text-bold">xBridge B2B portal</span>.
                  </h5><br>
                 </div>

                  <?php if($register->num_rows() != 0) { ?>
                  <button title="Submit"  data-toggle="modal" data-target="#exampleModal" id="submit-data" type="button" class="btn btn-md btn-success"><i class="glyphicon glyphicon-save" aria-hidden="true"></i>&nbsp&nbspSubmit</button>
                  <?php 
                  }
                  else
                  {
                    ?>

                    <button title="" data-toggle="modal" data-target="#exampleModal" id="submit-no-data" type="button" class="btn btn-md btn-success"><i class="glyphicon glyphicon-save" aria-hidden="true"></i>&nbsp&nbspSubmit</button>
                    <?php
                  } 
                  ?>

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                 <div class="modal-dialog" role="document">
                  <div class="">
                    <div class="modal-body">
                      <div class="success-container">
                       <div class="row">
                        <div class="modalbox success  center animate">
                          <div class="icon">
                            <span class="glyphicon glyphicon-alert"></span>
                          </div>
                              <h1>Confirmation!</h1>
                                <p>Your information has been edited.
                                  <br>Do proceed to update if you are wish to save your changes.</p>
                                  
                                 <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                      <button type="update" class="btn btn-success">Yes</button>
                                 </div>
                        </div>
                     
                      </div>
                     </div>
                    </div>
                  
                  </div>
                </div>
              </div>

                  <?php 
                  if($register->num_rows() != 0 )
                  {
                    $form_status = $key->form_status;

                    if($form_status == 'Registered')
                    {
                      $disabled = 'disabled'; 
                    }
                    else
                    {
                      $disabled = '';
                    }
                  }

                  ?>
                <button id="resetBtn" type="reset" class="btn btn-md btn-info reset"><i class="glyphicon glyphicon-refresh" aria-hidden="true"></i> &nbspReset</button>
                <!-- <input type="button" name="btn" onclick="valthisform()" value="Proceed"  id="submitBtn" data-toggle="modal"  class="btn btn-primary btn-md" /> -->
                <button id="submitBtn" name="btn" type="button" onclick="valthisform()" data-toggle="modal" class="btn btn-md btn-primary"><i class="fa fa-wrench" aria-hidden="true"></i> &nbspProceed</button>
                <button data-toggle="modal" data-target="#emailModal" id="email_btn" type="button" class="btn btn-md btn-info bg-maroon" ><i class="fa fa-send" aria-hidden="true"></i> &nbspEmail</button>
                <button id="completebtn" type="button" class="btn btn-md btn-warning" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' <?php if($register->num_rows() != 0) { echo $disabled; }?>><i class="fa fa-check" aria-hidden="true"></i>&nbspRegistered</button>
                <!-- data-target="#confirm-submit" -->
                </form> 
                     <div id="confirm-submit" class="modal fade" role="dialog">
                       <div class="modal-dialog">

                         <!-- Modal content-->
                         <div class="modal-content">
                         <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                         <h4 class="modal-title">Registration Details Update</h4>
                         </div>
                          <div class="modal-body">
                          <?php foreach ($register->result() as $key ) { ?>
                          <p>Retailer Name: <?php echo $key->acc_name;?><p>
                          <p>Company Name: <?php echo $key->comp_name;?></p>
                          <p>Registration No: <?php echo $key->register_no;?><p>
                        
                          <?php } ?>

                          <table class="table" id="table1">
                          <thead><br>
                          <h5>Vendor Code</h5>
                          </thead>
                           <th> Vendor Code</th>
                            <th> Mapping Status</th>
                          <tbody>
                           <tr> 
                            <?php foreach ($register->result() as $key ) { ?>
                            
                              <td id="accno">  
                                <?php 
                                $part3 = $key->acc_no;

                                $array =  explode(',', $part3);
                    
                                foreach ($array as $items) {
                                echo "<div id='acno' value=$items >$items</div>"; 
                                
                                //echo count('#text');
                                
                                 //echo count('.vc');
                                 }
                             
                             ?>
                               
                                <?php
                               echo "<p id='demo' ></p>"; 
                            ?>
                          

                             </td> 

                              <td>    

                              <?php 
                                 // $item = $this->db->query("SELECT a.acc_no FROM register_child a INNER JOIN set_supplier_group b ON a.acc_no = b.supplier_group_name");
                                $register_guid = $_REQUEST['register_guid'];
                              
                                $part3 = $key->acc_no;
                        
                                $array =  explode(',', $part3);
                                foreach($array as $items)
                                {
                                  $item = $this->db->query("SELECT * FROM lite_b2b.set_supplier_group WHERE supplier_group_name = '$items' AND customer_guid = '$customer_guid' AND supplier_guid = '$supplier_guid' ");

                                  if($item->num_rows() > 0 ) {
                                   echo "<div id='acno1' >Mapped</div>"; 
                                  }
                                  else 
                                  
                                    echo "<div id='acno1' > Not Map</div>"; 
                                     //echo "<div id='demo2'></div><br>";
                                  }
                                  ?>
                                


                              <?php
                               echo "<p id='demo3' ></p>"; 
                              ?>
                         
                              

                            </td> 

                             
                           </tr>
                         
                       
                           <?php } ?>
   
                            </tbody>
                          
                          </table>
                            <button type="update" class="btn btn-default" style="margin-left:500px;" id="vendor_update" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>'>Update</button>


                          <h5>User Details </h5>

                          <table class="table" id="table2">
                          <thead>                            
                           <th> Vendor Name</th>
                           <th> Vendor Email</th>
                           <th> User Group</th>
                            <!-- <th> Vendor Status</th> -->
                            <th><?php foreach ($register->result() as $key ) { ?> <?php echo $key->acc_name;?><?php } ?> </th>
                            <th> Other Retailer </th>
                          </thead>                            
                          <tbody>
                            <?php 
                            if($table_array != '')
                            {
                              foreach($table_array as $key=>$row) 
                              {
                                echo '<tr>';
                                foreach($row as $row1)
                                {
                                  echo '<td>'.$row1.'</td>';
                                }
                                echo '</tr>';
                              ?>
                              <?php
                              }
                            }
                            ?>
                            

                            </tbody>
                          </table>
                            <button type="update" class="btn btn-default" style="margin-left:500px;" id="user_update" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>'>Update</button>
                          <h5>User Details Mapping </h5>

                         <table class="table" id="table3">
                          <thead>
                           <th> Vendor Email</th>
                           <th> Vendor Code</th>
                            <!-- <th> Vendor Status</th> -->
                            <th><?php foreach ($register->result() as $key ) { ?> <?php echo $key->acc_name;?><?php } ?> </th>
                            <!-- <th> Others </th> -->
                          </thead>

                          <tbody>
                            <?php 
                            if($table_array != '')
                            {
                              foreach($table_array2 as $key=>$row) 
                              {
                                echo '<tr>';
                                foreach($row as $row1)
                                {
                                  echo '<td>'.$row1.'</td>';
                                }
                                echo '</tr>';
                              ?>
                              <?php
                              }
                            }
                            ?>                            
                            </tbody>
                          </table>
                            <button type="update" class="btn btn-default" style="margin-left:500px;" id="mapping_update" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>'>Update</button>

                          <h5>User Email Subscription </h5>

                           <table class="table" id="table4">
                            <thead>
                             <th> Vendor Email</th>
                             <th> Subscribe</th>
                             <th> Schedule</th>
                            </thead>

                            <tbody>
                              <?php 
                              if($table_array3 != '')
                              {
                                foreach($table_array3 as $key=>$row) 
                                {
                                  echo '<tr>';
                                  foreach($row as $row1)
                                  {
                                    echo '<td>'.$row1.'</td>';
                                  }
                                  echo '</tr>';
                                ?>
                                <?php
                                }
                              }
                              ?>                            
                              </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                           <button type="update" class="btn btn-default" id="email_subscription" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>'>Update</button>
                          
                        </div>
                      </div>

                   </div>
                  </div> <!-- end proceed modal -->
             
                  <div id="emailModal" class="modal fade" role="dialog" >
                    <div class="modal-dialog" style="width:1000px;">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Email Details</h4>
                        </div>

                        <div class="modal-body table-responsive modal-control-size">
                          <!-- <h5>Email Details </h5> -->

                          <table class="table" id="email_tb">
                          <thead>                            
                           <th style="width:80px;"> Vendor Email</th>
                           <th> Reset Link</th>
                            <!-- <th> Vendor Status</th> -->
                           <th><input type="checkbox" class="form-checkbox" onclick="checkedAllfinal ();" id="checkall_input_table" /></th>
                          </thead>                            
                          <tbody>
                            <?php 
                            if($table_array != '')
                            {
                              foreach($email_array as $key=>$row) 
                              {
                                echo '<tr>';
                                foreach($row as $row1)
                                {
                                  echo '<td>'.$row1.'</td>';
                                }
                                echo '</tr>';
                              ?>
                              <?php
                              }
                            }
                            ?>
                            </tbody>
                          </table>

                        </div>

                        <div class="modal-footer">
                          <button type="update" class="btn btn-default" id="send_email" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>'>Send</button>
                        </div>
                      </div>
                    </div>
                  </div> <!-- End email modal -->
                  <?php
                    if(isset($_REQUEST['openModal']) == 1)
                    { 
                    ?>
                      <script>
                        $(function(){
                          $('#confirm-submit').modal('show');
                        });
                      </script>
                    <?php  
                    }
                    ?>
                </div>   
              
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<script type="text/javascript">
  function valthisform()
{
    var checkboxs=document.getElementsByName("supply_type");
    var okay=false;
    for(var i=0,l=checkboxs.length;i<l;i++)
    {
        if(checkboxs[i].checked)
        {
            okay=true;
            break;
        }
        
    }
    if(okay != true)
    {
      alert("Please select the supply type to proceed.");
    }
    else
    {
      register_guid = "<?php echo $register_guid;?>"
      $($(this).attr("confirm-submit")).modal("show");
      history.pushState("", document.title, 'register_form_edit?register_guid='+register_guid+'&modal');
    }
}

var checked=false;
function checkedAllfinal () {
 
    var aa =  document.getElementsByName("checkall_input_table[]");
    checked = document.getElementById('checkall_input_table').checked;
    //alert(aa); die;
    for (var i =0; i < aa.length; i++) 
    {
        aa[i].checked = checked;
    }
}


</script>

<script type="text/javascript">
//var button = $('#submit-data');
var button = $('#submitBtn');
//var button_complete = $('#completebtn');
$('#myForm :input').not(button).bind('keyup change', function () {
    // get all that inputs that has changed
    var changed = $('#myForm :input').not(button).filter(function () {
        if (this.type == 'radio' || this.type == 'checkbox') {
            return this.checked != $(this).data('default');
        } else {
            return this.value != $(this).data('default');
        }
    });
    // disable if none have changed - else enable if at least one has changed
    //$('#submit-data').prop('disabled', !changed.length);
    $('#submitBtn').prop('disabled', changed.length);
    $('#completebtn').prop('disabled', changed.length);
});


</script>

<script type="text/javascript">
     $(".isdelete").change(function(event){
        if(this){
            alert("Successfully deactive vendor details");
        } else {
            alert("Successfully deactive vendor details.");
        }

});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#add_btn_vendor').show();
    var x = 1;
    var y = 0;
    var clicks = 0;
    var maxField = 16; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    //Initial field counter is 1
    //Once add button is clicked
    $(addButton).click(function(){
      var number_data = $(this).attr('count_array');
      t = x + parseInt(number_data); 
      $('.note').css({"margin-top":"60px"});
      $('#submitBtn').prop("disabled",true); // disabled proceed btn
      var fieldHTML = '<div class= "test" ><div class="form-group col-md-2"><label for="exampleInputEmail1">Name <span class="text-danger">*</span></label><input type="text"  class="form-control ven_name check_name set_reset" id="ven_name1"  name="ven_name[]" aria-describedby="emailHelp" placeholder="Name" required pattern="[^/,]+" title="You cannot use special characters : / "></div><div class="form-group col-md-2"> <label for="exampleInputEmail1">Designation </label><input type="text" class="form-control set_reset"  id="ven_designation" name="ven_designation[]" aria-describedby="emailHelp" placeholder="Designation" pattern="[^/,]+" title="You cannot use special characters : / "></div>  <div class="form-group col-md-2"><label for="exampleInputEmail1">Phone No <span class="text-danger">*</span></label><input type="text" data-mask="000-000-0000" class="form-control set_reset" id="ven_phone" name="ven_phone[]" aria-describedby="emailHelp" placeholder="Phone No" required pattern="[^/,]+" title="You cannot use special characters : / "></div> <div class="form-group col-md-2"><label for="exampleInputEmail1">Email Address <span class="text-danger">*</span> </label><input type="email" class="form-control check_email set_reset" id="ven_email" name="ven_email[]" aria-describedby="emailHelp" placeholder="Email Address" required pattern="[^/,]+" title="You cannot use special characters : / "></div><div class="form-group col-md-3" style="margin-left:10px;"><label for="exampleInputEmail1">Agency/Outlet Special Requests</label><input type="text" name="hidden_val[]" id="hidden_val'+t+'" hidden><p id="append_field'+t+'"></p></div><a href="#" class="remove_field"><i class="fa fa-times" style="margin-bottom:60px;margin-left:15px;"></a></div>'; //New input field html             
     var fieldHTML2 = '<select class="form-control select2 set_reset select2_agency_add'+t+'" multiple="multiple" name="ven_agency_add[]" required="true" id="ven_agency'+t+'" ><option value="All">All</option>  <?php foreach ($ven_agency_sql->result() as $key ) { ?><option value="<?php echo addslashes($key->branch_code) ?>"><?php echo addslashes($key->branch_name)?> - <?php echo $key->branch_code;?></option><?php } ?></select>';
        
        //Check maximum number of input fields
        if(y < maxField){ 
          var id_append1 = '#append_field'+t;
          var select2_agency_add = '.select2_agency_add'+t;
          var ven_agency = '#ven_agency'+t;
          t++;
          x++; //Increment field counter
          $(wrapper).append(fieldHTML);
          $(id_append1).append(fieldHTML2);
          $(select2_agency_add).select2();//Add field html
          
         $(ven_agency).on('change', function (e) {
            
           part1 = 'input[id="hidden_val';
           part2 = '"]';
           ven1 = '#ven_agency';
           chkall1 = '#ven_agency';
           chkall2 = ' > option';

           for(i=1;i<t;i++)
           {
             part3 = part1+i+part2;
             ven3 = ven1+i; 
             chkall3 = chkall1+i+chkall2;
             tri_chg = ven1+i; 

             var data = $(ven3).val();

             if(data == 'All'){
                 $(chkall3).prop("selected", "selected");
                 $(ven3).trigger("change");
             }else
             {
               $(part3).val(data).trigger("change");
             }
            }
          
         });// end of change to hidden

          // check name duplicate
         $(".check_name").change(function(){
          var arr = new Array();
          var arr1 = new Array();
           $(".check_name").each(function(){
                if($(this).val() != '')
                {
                  arr.push($(this).val());
                }
            });

            $(".check_email").each(function(){
              if($(this).val() != '')
              {
                arr1.push($(this).val());
              }
            });
           //alert(arr);
           for(var i=0; i<arr.length;i++){
               for(var j=i+1;j<arr.length;j++){
                  if(arr[i]==arr[j])
                  {
                      alert(JSON.stringify(arr[i]) + " Already Exist");
                      $('#submit-data').prop("disabled",true); 
                      return;
                  }
                  else
                  {
                    $('#submit-data').prop("disabled",false); 
                    //return;
                  }
               }
           }
          for(var i=0; i<arr1.length;i++){
               for(var j=i+1;j<arr1.length;j++){
                  if(arr1[i]==arr1[j])
                  {
                      alert(JSON.stringify(arr1[i]) + " Already Exist");
                      $('#submit-data').prop("disabled",true); 
                      return;
                  }
                  else
                  {
                    $('#submit-data').prop("disabled",false); 
                    //return;
                  }
               }
           }

        });

         //check email duplicate
        $(".check_email").change(function(){
          var arr = new Array();
          var arr1 = new Array();
           $(".check_name").each(function(){
                if($(this).val() != '')
                {
                  arr.push($(this).val());
                }
            });

            $(".check_email").each(function(){
              if($(this).val() != '')
              {
                arr1.push($(this).val());
              }
            });
           //alert(arr);
           for(var i=0; i<arr.length;i++){
               for(var j=i+1;j<arr.length;j++){
                  if(arr[i]==arr[j])
                  {
                      alert(JSON.stringify(arr[i]) + " Already Exist");
                      $('#submit-data').prop("disabled",true); 
                      return;
                  }
                  else
                  {
                    $('#submit-data').prop("disabled",false); 
                    //return;
                  }
               }
           }

          for(var i=0; i<arr1.length;i++){
               for(var j=i+1;j<arr1.length;j++){
                  if(arr1[i]==arr1[j])
                  {
                      alert(JSON.stringify(arr1[i]) + " Already Exist");
                      $('#submit-data').prop("disabled",true); 
                      return;
                  }
                  else
                  {
                    $('#submit-data').prop("disabled",false); 
                    //return;
                  }
               }
           }               
        });
            
        }
        else
        {
          alert('Maximum field can be add are out of range');
          $('#add_btn_vendor').hide();
        }

        var number = $(this).attr('count_array');
        //alert(x);
        y = x + parseInt(number); 
        //alert(y);
        if(y == 7 ){

        alert('Additional fees will be charge up to 5 persons');

        }
        if(y == 12 ){

        alert('Additional fees will be charge up to 5 persons');

        }
       
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_field', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--;
        y--; //Decrement field counter
        //$('#add_btn_vendor').show();
    });

   });
</script>


<script type="text/javascript">
$(document).ready(function(){
    var maxField = 100; //Input fields increment limitation
    var addButton = $('.add'); //Add button selector
    var wrapper = $('.field'); //Input field wrapper
    var fieldHTML = '<div class="parts"><div class="form-group col-md-4"> <label for="exampleInputEmail1">Name</label><input type="text" class="form-control set_reset" name="part_name[]" id="part_name" aria-describedby="emailHelp" placeholder="Name" pattern="[^/,]+" title="You cannot use special characters : / "> </div><div class="form-group col-md-3"><label for="exampleInputEmail1">IC No </label><input type="text" data-mask="000000-00-0000" class="form-control set_reset" name="part_ic[]" id="part_ic" aria-describedby="emailHelp" placeholder=IC No" pattern="[^/,]+" title="You cannot use special characters : / "></div> <div class="form-group col-md-2"><label for="exampleInputEmail1">Mobile Phone No</label><input type="text" data-mask="000-000-0000" class="form-control set_reset" name="part_mobile[]" aria-describedby="emailHelp" placeholder="Phone No" pattern="[^/,]+" title="You cannot use special characters : / "> </div><div class="form-group col-md-2"><label for="exampleInputEmail1">Email Address </label><input type="email" class="form-control set_reset" name="part_email[]" id="part_email" aria-describedby="emailHelp" placeholder="Email Address " pattern="[^/,]+" title="You cannot use special characters : / "></div><a href="#" class="remove_field"><i class="fa fa-times" style="margin-bottom:60px;margin-left:15px;"></i></a></div> '; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        $('#submitBtn').prop("disabled",true); // disabled proceed btn
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_field', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });

});
</script>
<script type="text/javascript">
$(document).ready(function(){

    var maxField = 5; //Input fields increment limitation
    var addButton = $('.addbtn'); //Add button selector
    var wrapper = $('.vendor'); //Input field wrapper
    var fieldHTML = '<div class="parts"><a href="#" class="remove_field"><i class="fa fa-times" style="float:right;margin-top:-15px;"></i></a><input type="text" class="form-control acc_no set_reset" id="acc_no1" name="acc_no_other[]" aria-describedby="emailHelp" placeholder="Other"  required="true" ><br></div>'; //New input field html 
    // <select class="form-control acc_no" name="acc_no[]"" required="true"><option value="">-Select-</option><?php foreach ($myArray as $row) { ?> <option value="<?php echo addslashes($row)?>"> <?php echo $row; ?></option> <?php } ?></select>

    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
      $('#submitBtn').prop("disabled",true); // disabled proceed btn
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
   
 
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_field', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });




});



</script>
<script type="text/javascript">

   $(".chcktbl1").click(function () {  
            var rdata = $(this).attr("register_guid"); // reading the id of the checkbox through data-id   
            console.log(rdata);  
            //alert(rdata);  
            $.ajax({  
                type: "Post",  
                contentType: "application/json; charset=utf-8",  
                url: "<?php echo site_url('Registration/register_update') ?>",  
                data: '{eid: ' + rdata + '}',  
                dataType: "json",  
                success: function (response) {  
                    if (response != 0) {  
                        alert("Data Update Successfully!!!!");  
                        location.reload();  
                    }  
                },  
                error: function (response) {  
                    if (response != 1) {  
                        alert("Error!!!!");  
                    }  
                }  
            });  
        });  
    
</script>
<script> 
var resetButtons = document.getElementsByClassName('reset');

// Loop through each reset buttons to bind the click event
for(var i=0; i<resetButtons.length; i++){
  resetButtons[i].addEventListener('click', resetForm);
}

/**
 * Function to hard reset the inputs of a form.
 *
 * @param object event The event object.
 * @return void
 */
function resetForm(event){

  event.preventDefault();
  
  var form = event.currentTarget.form;
  var inputs = form.querySelectorAll('.set_reset');
  //var checkboxs = form.querySelectorAll('checkbox');
  $('.set_reset').val('').trigger('change');
  $('.set_reset').prop('checked',false);
  inputs.forEach(function(input, index){
    input.value = null;

  });




}
 </script>

 <script type="text/javascript">
$('#submitBtn').click(function() {

        var text = "";
        var text2 = "";
        var text3 = "";
        var text4 = "";
        var text5 = "";
        var text6 = "";

        var vc= $('.acc_no');
     
        for (var i = 0; i < vc.length; i++)
        {
        text += vc[i].value + "<br>";
        }

        document.getElementById("demo").innerHTML = text;

        var kc= $('.acc_no');
         
        for (var x = 0; x < kc.length; x++)
        {
        text3 += "Not Map"+ "<br>";
        }

        document.getElementById("demo3").innerHTML = text3;


        var vn= $('.ven_name');
         for (var x = 0; x < vn.length; x++)
        {
        text2 += vn[x].value + "<br>";
        }

        document.getElementById("demo2").innerHTML = text2;

        var vt= $('.ven_name');
         for (var x = 0; x < vn.length; x++)
        {
        text4 += "Not Map"+ "<br>";
        }

        document.getElementById("demo4").innerHTML = text4;

        var vq= $('.ven_name');
         for (var x = 0; x < vq.length; x++)
        {
        text5 += "Not Map"+ "<br>";
        }

        document.getElementById("demo5").innerHTML = text5;

         var vz= $('.ven_name');
         for (var x = 0; x < vz.length; x++)
        {
        text6 += "Not Map" +"<br>";
        }

        document.getElementById("demo6").innerHTML = text6;

      if ($('#table2 >tbody >tr > td').length > 6){

        $("#demo2").css("background-color", "white");
        $("#demo2 ").css("color", "black");
        $("#demo4").css("background-color", "white");
        $("#demo4 ").css("color", "black");
        $("#demo5").css("background-color", "white");
        $("#demo5 ").css("color", "black");
        $("#demo6").css("background-color", "white");
        $("#demo6 ").css("color", "black");
     

        }

        else
        {

        $("#demo2").css("background-color", "red");
        $("#demo2 ").css("color", "white");
        $("#demo4").css("background-color", "red");
        $("#demo4 ").css("color", "white");
        $("#demo5").css("background-color", "red");
        $("#demo5 ").css("color", "white");
        $("#demo6").css("background-color", "red");
        $("#demo6 ").css("color", "white");

        }
      
       
    
});


$('#submit').click(function(){
    alert('submitting');
    $('#myForm').submit();
});
</script>

<!-- <script type="text/javascript">
$('document').ready(function(){
 var acc_no = false;
 $('#acc_no').on('blur', function(){
  var acc_no = $('#acc_no').val();
  if (acc_no == '') {
    acc_no = false;
    return;
  }
  $.ajax({
    url: '<?php echo site_url('Registration/check_code') ?>',
    type: 'post',
    data: {
      'acc_no_check' : 1,
      'acc_no' : acc_no,
    },
    success: function(response){
      if (response == 'taken' ) {
       acc_no = false;
        $('#acc_no').parent().removeClass();
        $('#acc_no').parent().addClass("form_error");
        $('#acc_no').siblings("span").text('Sorry... acc_no already taken');
      }else if (response == 'not_taken') {
        acc_no_state = true;
        $('#acc_no').parent().removeClass();
        $('#acc_no').parent().addClass("form_success");
        $('#acc_no').siblings("span").text('acc_no available');
      }
    }
  });
 });
  
</script> -->

<script type="text/javascript">
$('document').ready(function(){
<?php if(isset($_REQUEST['modal']))
{
?>
// alert();
$('#confirm-submit').modal("show");
<?php
}
?>
$('#confirm-submit').on('hidden.bs.modal', function () {
  // do something…
  // alert();
  register_guid = "<?php echo $register_guid;?>";
  history.pushState("", document.title, 'register_form_edit?register_guid='+register_guid);
})

$('body').click(function (event) 
{
   if(!$(event.target).closest('#openModal').length && !$(event.target).is('#openModal')) {
     $(".modalDialog").hide();
   }     
});

 $(document).on('click','#vendor_update',function(){

  var register_guid = $(this).attr('register_guid');
  var customer_guid = $(this).attr('customer_guid');
  var table_name1 = 'register_child';
  var table_name2 = 'register';

  $.ajax({
      url:"<?php echo site_url('Registration/proceed_vendor') ?>",
      method:"POST",
      data:{register_guid:register_guid,table_name1:table_name1,table_name2:table_name2,customer_guid:customer_guid},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        if (json.para1 == '1') {
          alert(json.msg.replace(/\\n/g,"\n"));
          $('.btn').button('reset');
        }else{
          alert(json.msg.replace(/\\n/g,"\n"));
          setTimeout(function() {
          $('.btn').button('reset');
          //window.location = window.location.href + "&openModal=1";
          }, 300);
          location.reload();
        }//close else
      }//close success
    });//close ajax

});//close acno

 $(document).on('click','#user_update',function(){

  var register_guid = $(this).attr('register_guid');
  var customer_guid = $(this).attr('customer_guid');
  var table_name1 = 'register_child';
  var table_name2 = 'register';

  var details = [];
  shoot_link = 0;
  $('#table2 tbody tr').each(function(){
    
    var vendor_name = $(this).find('td:eq(0)').text();
    var vendor_email = $(this).find('td:eq(1)').text();
    var user_group = $(this).find('td:eq(2)').find('select').val();
    var loc_group = $(this).find('td:eq(5)').find('input').val();

    // alert(user_group);
    if(user_group == '' || user_group == null)
    {
      shoot_link = shoot_link+1;
      // alert(shoot_link+'***'+user_group);
    }

    details.push({'vendor_name':vendor_name,'vendor_email':vendor_email,'user_group':user_group,'loc_group':loc_group});

  });  

  // alert('asas'+shoot_link);
  // return;
  if(shoot_link == 0)
  {
    // console.log(details);return;
    $.ajax({
        url:"<?php echo site_url('Registration/proceed_user') ?>",
        method:"POST",
        data:{register_guid:register_guid,table_name1:table_name1,table_name2:table_name2,customer_guid:customer_guid,details:details},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == '1') {
            alert(json.msg.replace(/\\n/g,"\n"));
            $('.btn').button('reset');
          }else{
            alert(json.msg.replace(/\\n/g,"\n"));
            setTimeout(function() {
            $('.btn').button('reset');
            //window.location = window.location.href + "&openModal=1";
            }, 300);
            location.reload();
          }//close else
        }//close success
      });//close ajax    
  }


});//close ven

$(document).on('click','#mapping_update',function(){

  var register_guid = $(this).attr('register_guid');
  var customer_guid = $(this).attr('customer_guid');
  var table_name1 = 'register_child';
  var table_name2 = 'register';
  //var table = document.getElementById('table3');
  //var rowLength = table.rows.length;
  // var vendor_email = [];
  // for(var i=1; i<rowLength; i++){
  //   var row = table.rows[i];

  //   var cellLength = row.cells.length;
  //   for(var y=0; y<cellLength; y++){
  //     vendor_email += row.cells[0].innerText;
  //   }
  // } 


  var details = [];

  $('#table3 tbody tr').each(function(){
    
    var vendor_email = $(this).find('td:eq(0)').text();
    var vendor_code = $(this).find('td:eq(1)').find('select').val();
    var retailer = $(this).find('td:eq(2)').text();
    var other = $(this).find('td:eq(3)').text();

    details.push({'vendor_email':vendor_email,'vendor_code':vendor_code,'retailer':retailer,'other':other});

  });

  // alert(details);

  // for(var i=0; i<rowLength; i++){
  //   var row = table.rows[i];

  //   var cellLength = row.cells.length;
  //   for(var y=0; y<cellLength; y++){
  //    var vendor_code = row.cells[1].innerText;
  //   }
  // } 

  // alert(vendor_code);
  // alert(vendor_email);
  // return

  $.ajax({
      url:"<?php echo site_url('Registration/proceed_mapping') ?>",
      method:"POST",
      data:{register_guid:register_guid,table_name1:table_name1,table_name2:table_name2,customer_guid:customer_guid,details:details},
      beforeSend:function(){
        // $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        if (json.para1 == '1') {
          alert(json.msg.replace(/\\n/g,"\n"));
          $('.btn').button('reset');
        }else{
          // alert(1);
          alert(json.msg.replace(/\\n/g,"\n"));
          setTimeout(function() {
          $('.btn').button('reset');
          //window.location = window.location.href + "&openModal=1";
          }, 300);
          location.reload();
        }//close else
      }//close success
    });//close ajax

});//close ven

$(document).on('click','#email_subscription',function(){
  // alert('');return;
  var register_guid = $(this).attr('register_guid');
  var customer_guid = $(this).attr('customer_guid');
  var table_main = 'register';

  shoot_link = 0;
  var details = [];

  $('#table4 tbody tr').each(function(){
    
    var vendor_email = $(this).find('td:eq(0)').text();
    var report_guid = $(this).find('td:eq(1)').find('select').val();
    

    if(report_guid == '' || report_guid == null)
    {
      shoot_link = shoot_link+1;
      // alert(shoot_link+'***'+user_group);
    }
    details.push({'vendor_email':vendor_email,'report_guid':report_guid});

  });

if(shoot_link == 0)
{
  $.ajax({
      url:"<?php echo site_url('Registration/proceed_subscribe_email') ?>",
      method:"POST",
      data:{register_guid:register_guid,customer_guid:customer_guid,details:details,table_main:table_main},
      beforeSend:function(){
        // $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        if (json.para1 == '1') {
          alert(json.msg.replace(/\\n/g,"\n"));
          $('.btn').button('reset');
        }else{
          // alert(1);
          alert(json.msg.replace(/\\n/g,"\n"));
          setTimeout(function() {
          $('.btn').button('reset');
          //window.location = window.location.href + "&openModal=1";
          }, 300);
          location.reload();
        }//close else
      }//close success
    });//close ajax
}
else
{
  alert('Report Type Cannot Be Empty Value');return;
}

});//close ven

  // setTimeout(function(){
  // $(".vendor_select2").select2({
  //   tags: true,
  //   tokenSeparators: [',']
  // });
  // },300);

  // setTimeout(function(){
  // $(".select2_agency").select2({
  //   tokenSeparators: [',']
  // })
  // },300);

$('.select2_agency').on('change', function (e) {
      
  var length = $('.select2_agency').length;

  part1 = 'input[id="hidden_val';
  part2 = '"]';
  ven1 = '#ven_agency';
  //ven2 = ' option:select';
  chkall1 = '#ven_agency';
  chkall2 = ' > option';

  for(i=0;i<length;i++)
  {
    part3 = part1+i+part2;
    ven3 = ven1+i; 
    chkall3 = chkall1+i+chkall2;
    tri_chg = ven1+i; 

    var data = $(ven3).val();

    if(data == 'All'){
        $(chkall3).prop("selected", "selected");
        $(tri_chg).trigger("change");
    }else
    {
      $(part3).val(data).trigger("change");
    }
  }

});

$(".check_name").change(function(){
  var arr = new Array();
  var arr1 = new Array();
  $(".check_name").each(function(){
      if($(this).val() != '')
      {
        arr.push($(this).val());
      }
  });

  $(".check_email").each(function(){
    if($(this).val() != '')
    {
      arr1.push($(this).val());
    }
  });
   //alert(arr);
   for(var i=0; i<arr.length;i++){
       for(var j=i+1;j<arr.length;j++){
          if(arr[i]==arr[j])
          {
              alert(JSON.stringify(arr[i]) + " Already Exist");
              $('#submit-data').prop("disabled",true); 
              return;
          }
          else
          {
            $('#submit-data').prop("disabled",false); 
            //return;
          }
       }
   }

    for(var i=0; i<arr1.length;i++){
       for(var j=i+1;j<arr1.length;j++){
          if(arr1[i]==arr1[j])
          {
              alert(JSON.stringify(arr1[i]) + " Already Exist");
              $('#submit-data').prop("disabled",true); 
              return;
          }
          else
          {
            $('#submit-data').prop("disabled",false); 
            //return;
          }
       }
   }                
});

$(".check_email").change(function(){
  var arr = new Array();
  var arr1 = new Array();

 $(".check_name").each(function(){
      if($(this).val() != '')
      {
        arr.push($(this).val());
      }
  });

  $(".check_email").each(function(){
    if($(this).val() != '')
    {
      arr1.push($(this).val());
    }
  });
  
   //alert(arr);
   for(var i=0; i<arr.length;i++){
       for(var j=i+1;j<arr.length;j++){
          if(arr[i]==arr[j])
          {
              alert(JSON.stringify(arr[i]) + " Already Exist");
              $('#submit-data').prop("disabled",true); 
              return;
          }
          else
          {
            $('#submit-data').prop("disabled",false); 
            //return;
          }
       }
   }

    for(var i=0; i<arr1.length;i++){
       for(var j=i+1;j<arr1.length;j++){
          if(arr1[i]==arr1[j])
          {
              alert(JSON.stringify(arr1[i]) + " Already Exist");
              $('#submit-data').prop("disabled",true); 
              return;
          }
          else
          {
            $('#submit-data').prop("disabled",false); 
            //return;
          }
       }
   }                
});

$(document).on('click','#add_code_modal',function(){

    var register_guid = $(this).attr('register_guid');
    var customer_guid = $(this).attr('customer_guid');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Add Vendor Code');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-12"><input hidden type="hidden" class="form-control input-sm" id="hidden_reg" value="'+register_guid+'" /></div>';

    methodd += '<div class="col-md-12"><input hidden type="hidden" class="form-control input-sm" id="hidden_cust" value="'+customer_guid+'" /></div>';

    methodd += '<div class="col-md-12"><label>Vendor Code</label><select id="add_code" name="add_code" class="form-control" multiple="multiple" ><?php foreach ($add_vendor_code as $row) { ?> <option value="<?php echo $row->vendor_code; ?>"><?php echo $row->vendor_code;?></option> <?php } ?> </select></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="add_vendor_code" class="btn btn-success" value="Add"> <input name="sendsubmit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('#add_code').select2();
    },300);

});//close add vendor code

$(document).on('click','#add_vendor_code',function(){

    var table_name1 = 'register_child';
    var table_name2 = 'register';
    var code = $('#add_code').val();
    var register_guid = $('#hidden_reg').val();
    var customer_guid = $('#hidden_cust').val();

    if((code == '') || (code == null))
    {
      alert("Cannot empty select box")
      return;
    }//close checking for posted table_ss

    $.ajax({
          url:"<?php echo site_url('Registration/add_vendor_code');?>",
          method:"POST",
          data:{code:code,register_guid:register_guid,customer_guid:customer_guid,table_name1:table_name1,table_name2:table_name2},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            }else{
              alert(json.msg);
              $('.btn').button('reset');
              location.reload();
            }//close else
          }//close success
        });//close ajax
});//close add 

$(document).on('click','#completebtn',function(){

  var register_guid = $(this).attr('register_guid');
  var customer_guid = $(this).attr('customer_guid');

  $.ajax({
          url:"<?php echo site_url('Registration/complete_status');?>",
          method:"POST",
          data:{customer_guid:customer_guid,register_guid:register_guid},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('.btn').button('reset');
            }else{
              alert(json.msg);
              setTimeout(function() {
              $('.btn').button('reset');
              window.location = "<?= site_url('Registration/register_form_edit?register_guid=');?>"+register_guid;
              }, 300);
              location.reload();
            }//close else
          }//close success
        });//close ajax

});//close add 

$(document).on('click','#copy_link',function(){
  // alert();
  var seq = $(this).attr('seq');
  // alert(seq);
  var val = $('#copy_link_'+seq).val();
  $('#copy_link_'+seq).select()
  document.execCommand('copy');
  alert('Copy to clipboard');
});

$(document).on('click','#send_email',function(){


  var details = [];

  shoot_link = 0;  
  $('#email_tb tbody tr').each(function(){
// alert($(this).html());
    if($(this).closest('tr').find('td').find('input[type="checkbox"]').is(':checked'))
    { 
      shoot_link = shoot_link+1; 
      var customer_guid = $(this).closest('tr').find('td').find('input[type="checkbox"]').attr('customer_guid');
      var u_g = $(this).closest('tr').find('td').find('input[type="checkbox"]').attr('u_g');
      var duplicate = $(this).closest('tr').find('td').find('input[type="checkbox"]').attr('duplicate');
      var link = $(this).closest('tr').find('td').find('input[type="checkbox"]').attr('link');
      var vendor_email = $(this).closest('tr').find('td').find('input[type="checkbox"]').attr('vendor_email');
      var reset_guid = $(this).closest('tr').find('td').find('input[type="checkbox"]').attr('reset_g');
      var supplier_guid = $(this).closest('tr').find('td').find('input[type="checkbox"]').attr('supplier_guid');
      details.push({'supplier_guid':supplier_guid,'reset_guid':reset_guid,'customer_guid':customer_guid,'u_g':u_g,'duplicate':duplicate,'link':link,'vendor_email':vendor_email});
    }
  });

  // console.log(details);return;
  // if((details == '[object Object]') || (details == null) || (details == 'null'))
  // {
  //   alert('Please select the checbox to send.');
  //   die;
  // }
if(shoot_link > 0)
{
  $.ajax({
      url:"<?php echo site_url('Registration/email_subs_function') ?>",
      method:"POST",
      data:{details:details},
      beforeSend:function(){
        // $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        if (json.para1 == '1') {
          alert(json.msg.replace(/\\n/g,"\n"));
          $('.btn').button('reset');

        }else{
          // alert(1);
          alert(json.msg.replace(/\\n/g,"\n"));
          setTimeout(function() {
          $('.btn').button('reset');
          //window.location = window.location.href + "&openModal=1";
          }, 300);
          location.reload();
        }//close else
      }//close success
    });//close ajax
}
else
{
  alert('At least one checkbox need be selected');
  return;
}

});//close add 

});
</script>