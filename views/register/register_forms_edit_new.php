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

.cell_breakWord{
  word-break: break-all;
  max-width: 1px;
}
.alignleft
{
  text-align: left;
}

input:focus {
  background-color: #ccebff;
}

.summary_info {
  min-height: fit-content;
  border: 1px solid white;
  border-radius: 4px;
  padding: 9px;
  background-color: ghostwhite;
  /* margin-bottom: 20px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  box-shadow: inset 0 1px 1px rgba(0,0,0,.05); */

}

.summary_info:hover {
  border: 1px solid #51c4f5;
}

.proceed_user_details_css {
  width: 100%;
  table-layout: fixed;
  border-spacing: 5px;
}

.proceed_user_details_css > th, td {
  word-wrap: break-word;
  border: 0px solid black;
  padding: 8px;
}
</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h2 class="text-center">Online Registration Form </h2> 
          <button type="button" style="float: right;" class="btn btn-xs btn-default" id="ctrl_p"><i class="fa fa-print"></i> Print View</button>
          <br>
              <h4 class="text-bold part1" style="margin-left: 15px;">Please complete and submit this Online Registration Form and provide full details to TMG B2B.</h4> 
              <h4 class="text-bold part1" style="margin-left: 15px; ">Part 1: Organizational Information</h4><br>
                
                <form action="<?php echo site_url('Registration_new/register_update')?>?register_guid=<?php echo $_REQUEST['register_guid'] ?>" method="post" id="myForm">
                    <div class="form-row">
                    <div class="form-group col-md-6">
                    <span class="add_save_status"></span> <!--status save button -->
                    <span class="count_part2_tb"></span>
                    <?php 
                      if($register->num_rows() != 0 )
                        {
                          foreach($register->result() as $key)
                          {
                            $form_status = $key->form_status;

                            if(!in_array('IAVA',$this->session->userdata('module_code')))
                            {
                              if($form_status == 'New' || $form_status == 'Processing' || $form_status == 'Emailed' || $form_status == 'Registered')
                              {
                                $disabled = 'disabled'; 
                                $readonly = 'readonly'; 
                                $button_name = 'Update';
                              }
                              else
                              {
                                $disabled = ''; 
                                $readonly = ''; 
                                $button_name = 'Submit';
                              }
                            }
                            else
                            {
                              if($form_status == 'Registered')
                              {
                                $disabled = 'disabled'; 
                                $readonly = 'readonly'; 
                              }
                              else
                              {
                                $disabled = ''; 
                                $readonly = ''; 
                              }

                              if($form_status == '' || $form_status == 'Save-Progress' || $form_status == 'Sent' )
                              {
                                $button_name = 'Submit';
                              }
                              else
                              {
                                $button_name = 'Update';
                              }

                              if(isset($_REQUEST['openModal']) == 1)
                              { 
                                ?>
                                <script>
                                  $(function(){
                                    //$('#proceed_modal').modal('show');
                                    $('#proceed_modal').modal('show');
                                  });
                                </script>
                                <?php  
                              }
                            }
                          }
                        }
                      ?>
                    <label for="exampleInputEmail1">Company Name <span class="text-danger">*</span> </label>

                    <?php foreach ($register->result() as $key) { ?>
                      <input type="text" class="form-control" id="comp_name" name="comp_name"  aria-describedby="emailHelp" value="<?php echo $key->comp_name?>" readonly required="true">
                    <?php } ?>

                    </div>
                    </div>

                     <div class="form-group col-md-6">
                     <label for="exampleInputEmail1">Company Registration No <span class="text-danger">*</span> </label>

                     <?php foreach ($register->result() as $key) { ?>
                     <input type="text" class="form-control" id="comp_no" name="comp_no" aria-describedby="emailHelp" value="<?php echo $key->comp_no ?>" readonly required="true" <?php echo $readonly?>>
                     <?php } ?>
                     </div>

                  <?php if($register->num_rows() != 0)
                  {
                   foreach ($register->result() as $key) {
                   ?>
                   <input type="hidden" id="term_hidden" name="term_hidden" value=<?php echo $key->term_download?> disabled>
                   <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Supply Type</label> <span class="text-danger">*</span> <br>
                      <input type="checkbox" class="set_reset supply_type" id="outright" name="supply_outright" value="outright" disabled <?php 
                      if($key->supply_outright == 'outright')
                      { 
                        ?> checked
                        > 
                        <?php 
                      }else if($key->memo_type == 'outright' || $key->memo_type == 'waive_outright')
                      {?> checked
                        > 
                        <?php 
                      }
                      else
                      {?> > <?php } ?>
                      <label for="vehicle1" style="margin-left: 5px;margin-right: 5px;"> OUTRIGHT</label>
                        
                      <input type="checkbox" class="set_reset supply_type" id="consignment" name="supply_consignment" value="consignment" disabled <?php 
                      if($key->supply_consignment == 'consignment'){ 
                        ?> checked
                        > <?php 
                      }else if($key->memo_type == 'consignment' || $key->memo_type == 'waive_consign' )
                      {?> checked
                        > 
                        <?php 
                      }
                      else{?> > <?php } ?>
                       <label for="vehicle1" style="margin-left: 5px;"> CONSIGNMENT</label><br>

                      </div>

                   <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Business Address/Billing Address <span class="text-danger">*</span> </label>
                    <textarea class="form-control set_reset" style="resize: none;" id="comp_add" name="comp_add" rows="4" cols="10" <?php echo $readonly?>><?php echo $key->comp_add ?></textarea>
                   
                   </div>


                   <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Postcode <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control set_reset" id="comp_post" name="comp_post"  aria-describedby="emailHelp" placeholder="Postcode" value="<?php echo $key->supplier_postcode ?>" required="true" <?php echo $readonly?>>
                  </div>

                   <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">State <span class="text-danger">*</span> </label>
                    <input type="hidden" class="form-control set_reset" id="hidden_state" name="hidden_state" value="<?php echo $key->supplier_state ?>" disabled>
                    <select class="form-control set_reset" id="comp_state" name="comp_state" required="true"  <?php echo $disabled?>>
                      <option value="" <?php if($key->supplier_state == "" ){ echo "selected";} ?> >--Select State--</option>
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
                      <option value="Kuala Lumpur" <?php if('Kuala Lumpur' == $key->supplier_state ){ echo "selected";} ?> >Kuala Lumpur</option>
                      <option value="Singapore" <?php if('Singapore' == $key->supplier_state ){ echo "selected";} ?> >Singapore</option>
                    </select>
                    <!-- <input type="text" class="form-control" id="comp_state" name="comp_state"  aria-describedby="emailHelp" placeholder="State" value="<?php echo $key->supplier_state ?>" required="true"> -->
              
                  </div>

                   <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Billing Email Address </label><span class="text-danger">*</span>
                    <input type="email" class="form-control set_reset" id="comp_mail" name="comp_mail" aria-describedby="emailHelp" placeholder="Email Address" value="<?php echo $key->org_email ?>" required="true" <?php echo $readonly?>>
                   </div>

                   <div class="form-group col-md-6">
                   <label for="exampleInputEmail1">Billing Phone No.<span class="text-danger">*</span> </label>
                    <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="billing_contact" name="billing_contact" aria-describedby="emailHelp" value="<?php echo $key->billing_contact ?>" required="true" <?php echo $readonly?>>
                   </div>

                   <div class="form-group col-md-6">
                   <label for="exampleInputEmail1">Phone No.<span class="text-danger">*</span> </label>
                    <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="comp_contact" name="comp_contact" aria-describedby="emailHelp" value="<?php echo $key->comp_contact ?>" required="true" <?php echo $readonly?>>
                   </div>

                   <div class="form-group col-md-6">
                   <label for="exampleInputEmail1">Second Phone No. </label>
                    <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="second_comp_contact" name="second_comp_contact" aria-describedby="emailHelp" value="<?php echo $key->second_comp_contact ?>" <?php echo $readonly?>>
                   </div>

                   <div class="form-group col-md-6">
                   <label for="exampleInputEmail1">Fax </label>
                   <input type="text" data-mask="000-000-0000" class="form-control set_reset" id="comp_fax" name="comp_fax" aria-describedby="emailHelp" value="<?php echo $key->comp_fax ?>" <?php echo $readonly?>>
                   </div>

                  <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Business Description </label><span class="text-danger">*</span>
                    <input type="hidden" class="form-control set_reset" id="hidden_desc" name="hidden_desc" value="<?php echo $key->business_description ?>" disabled>
                    <select class="form-control set_reset" id="business_desc" name="business_desc" required="true" <?php echo $disabled?>>
                      <option value="" <?php if($key->business_description == "" ){ echo "selected";} ?> >--Select Business Description--</option>
                      <option value="Bread" <?php if('Bread' == $key->business_description ){ echo "selected";} ?> >Bread</option>
                      <option value="Fresh" <?php if('Fresh' == $key->business_description ){ echo "selected";} ?> >Fresh</option>
                      <option value="Frozen Foods" <?php if('Frozen Foods' == $key->business_description ){ echo "selected";} ?> >Frozen Foods</option>
                      <option value="Beverages" <?php if('Beverages' == $key->business_description ){ echo "selected";} ?> >Beverages & Drinks</option>
                      <option value="Dairy Products" <?php if('Dairy Products' == $key->business_description ){ echo "selected";} ?>>Dairy Products</option>
                      <option value="Snacks" <?php if('Snacks' == $key->business_description ){ echo "selected";} ?> >Snacks</option>
                      <option value="Cigarette" <?php if('Cigarette' == $key->business_description ){ echo "selected";} ?> >Cigarette</option>
                      <option value="Electric Appliances" <?php if('Electric Appliances' == $key->business_description ){ echo "selected";} ?> >Electric Appliances</option>
                      <option value="Others" <?php if('Others' == $key->business_description ){ echo "selected";} ?> >Others</option>
 
                    </select>

                    </div>

                    <div class="form-group col-md-6""> 
                      <label for="exampleInputEmail1">Others:</label> 
                      <input type="text" class="form-control set_reset" id="bus_desc_others"  name="bus_desc_others" aria-describedby="emailHelp" value="<?php echo $key->bus_desc_others ?>" readonly <?php echo $disabled?>> </div>

                    <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Retailer Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="acc_name" name="acc_name" aria-describedby="emailHelp" value="<?php echo $key->acc_name ?>" readonly required="true"  >
                    </div>


                    <div class="form-group col-md-6" id="vendor">
                     <?php if(in_array('IAVA',$this->session->userdata('module_code')))
                     {
                      ?>
                      <button type="button" style="float: right;" class="btn btn-xs btn-default"  <?php echo $disabled?> id="add_code_modal" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>'>Add Code</button>
                      <?php
                     }
                     ?>
                    
                    <label for="exampleInputEmail1">Vendor Code (refer to Retailer) <span class="text-danger">*</span></label>
                      <?php 
                              $part5 = $key->acc_no;

                              $array =  explode(',', $part5);


                                ?>
                                <select class="form-control select2 vendor_select2 set_reset" name="acc_no[]" id="acc_no" required="true" multiple="multiple" disabled>
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

                    </div>
                    
                <?php } //close foreach register ?> 
                <?php 
                } // if register num rows equal to 0
                ?>
                  <!-- Start Part 2 Vendor Here -->
                  <div class="form-group col-md-12">
                    <h4 class=" text-bold " >Part 2: Login Account(s) Information <span class="text-danger">(*Login-ID is create based on unique email address)</span>  
                    <?php if(in_array('IAVA',$this->session->userdata('module_code')))
                     {
                      ?>
                      <button id="info_btn" type="button" class="btn btn-xs btn-default" <?php echo $disabled?> style="float: right;margin-bottom:15px;" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' ><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Add</button>
                      <?php
                     }
                     ?>
                    </h4>
                  </div>

                  <div class="info">
                    <div class="row" style="padding-left:25px;padding-right:25px;">
                      <table id="part2_tb" class="table table-hover" width="100%" cellspacing="0" >
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


                 <?php if(in_array('IAVA',$this->session->userdata('module_code')))
                 {
                  ?>
                  <?php if($register->num_rows() != 0) { ?>
                  
                  <!-- <button title="Save" onclick="save_form()"  data-toggle="modal" data-target="#saveModal" id="save_btn" type="button" class="btn btn-md btn-default" <?php echo $disabled?>><i class="fa fa-save" aria-hidden="true" ></i>
                  &nbspSave</button> -->

                  <button title="Submit" onclick="valthisform()" data-toggle="modal" data-target="#exampleModal" id="submit-data" type="button" class="btn btn-md btn-success" <?php echo $disabled?> ><i class="glyphicon glyphicon-save" aria-hidden="true"></i>&nbsp&nbsp<?php echo $button_name?></button>
                  <?php 
                  }
                 }
                 ?>

                <!--Confirmation Modal-->
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
                                  <br>Do proceed to update if you are wish to SUBMIT Progress.</p>
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

              <!-- Save alert -->
                <div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                  <br>Do proceed to update if you are wish to SAVE Progress.</p>
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
                foreach($register->result() as $key)
                {
                  $form_status = $key->form_status;

                  if($form_status == 'Processing' || $form_status == 'Emailed' )
                  {
                    $disabled_special = ''; 
                  }
                  else
                  {
                    $disabled_special = 'disabled';
                  }
                }
              }
              ?>
              <?php if(in_array('IAVA',$this->session->userdata('module_code')))
              {
               ?>
              <button id="submitBtn" name="btn" type="button" onclick="proceed_form()" data-toggle="modal" class="btn btn-md btn-primary"><i class="fa fa-wrench" aria-hidden="true"></i> &nbspProceed</button>
              <button data-toggle="modal" data-target="#emailModal" id="email_btn" type="button" class="btn btn-md btn-info bg-maroon" ><i class="fa fa-send" aria-hidden="true"></i> &nbspEmail</button>
              <button id="completebtn" type="button" class="btn btn-md btn-warning" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' <?php echo $disabled_special ?>><i class="fa fa-check" aria-hidden="true"></i>&nbspRegistered</button>
              <!-- <button title="Terms" id="term_btn" type="button" class="btn btn-md btn-primary" ><i class="fa fa-file" aria-hidden="true"></i>&nbsp&nbspTerms</button> -->
               <?php
              }
              ?>
            </form> 
          </div>
        </div> <!--remove this two div if dont want seperate. -->

<!-- data-target="#proceed_modal" proceed modal -->
<div id="proceed_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"><!-- Modal content-->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Registration Details Update - <?php echo $key->register_no;?></h4>
      </div>
      <div class="modal-body">
        <?php foreach ($register->result() as $key ) { ?>
        <p><b>Retailer / Supplier Name: <mark style="backgroud-color:yellow;"> <?php echo $key->acc_name;?> - <?php echo $key->comp_name;?> </mark> </b><p>
        <?php } ?>

        <div class="row">
          <div class="col-md-12">
            <div class="box-body summary_info">
              <h4 class="box-title">Step 1 : Vendor Code
                <div class="pull-right">
                  <button type="update" class="btn btn-xs btn-primary" style="float:right;" id="vendor_update" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' <?php echo $disabled?>><i class="fa fa-save"></i>  Update Vendor Code</button>
                </div>
              </h4>
              <table id="table1" class="table table-hover" width="100%" cellspacing="0">
                <thead style="white-space: nowrap;">
                  <tr>
                    <th>Vendor Code</th>
                    <th>Mapping Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr> 
                    <?php foreach ($register->result() as $key ) { ?>
                      <td id="accno">  
                        <?php 
                          $part3 = $key->acc_no;
                          $array =  explode(',', $part3);
                          foreach ($array as $items) {
                          echo "<div id='acno' value=$items >$items</div>"; 
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

                            $supcus_vendor_code = $this->db->query("SELECT `code`,IF(b2b_registration = '1', 'Supcus B2B Flag', 'Supcus No B2B Flag') AS b2b_flag, IF(b2b_registration = '1', '#3afa14', '#ff738c') AS b2b_flag_color FROM b2b_summary.supcus WHERE `code` = '$items' AND customer_guid = '$customer_guid' ");

                            if($item->num_rows() > 0 ) 
                            {
                              echo "<div id='acno1' > <span style='background-color:#3afa14;font-weight:bold;'> Mapped</span> - <span style='background-color:".$supcus_vendor_code->row('b2b_flag_color').";font-weight:bold;'>".$supcus_vendor_code->row('b2b_flag')." ( ".$supcus_vendor_code->num_rows()." Record ) </span> </div>"; 
                            }
                            else 
                            {
                              echo "<div id='acno1' > <span style='background-color:#ff738c;font-weight:bold;'> Not Map</span> - <span style='background-color:".$supcus_vendor_code->row('b2b_flag_color').";font-weight:bold;'>".$supcus_vendor_code->row('b2b_flag')." ( ".$supcus_vendor_code->num_rows()." Record )  </span> </div>"; 
                            }
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
            </div>
          </div>

          <div class="col-md-12">
            <div class="box-body summary_info">
              <h4 class="box-title">Step 2 : User Details
                <div class="pull-right">
                <button type="update" class="btn btn-xs btn-primary" style="float:right;" id="user_update" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' <?php echo $disabled?>> <i class="fa fa-save"></i> Update User Details</button>
                </div>
              </h4>
              <table id="table2" class="table table-hover proceed_user_details_css" width="100%" cellspacing="0">
                <thead >
                  <tr>
                    <th> Vendor Name</th>
                    <th> Vendor Email</th>
                    <th style="width:30%;"> User Group</th>
                    <th > <?php foreach ($register->result() as $key ) { ?> <?php echo $key->acc_name;?><?php } ?> </th>
                    <th> Other Retailer </th>
                  </tr>
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
                        echo '<td >'.$row1.'</td>';
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
          </div>

          <div class="col-md-12">
            <div class="box-body summary_info">
              <h4 class="box-title">Step 3 : User Details Mapping
                <div class="pull-right">
                <button type="update" class="btn btn-xs btn-primary" style="float:right;" id="mapping_update" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' <?php echo $disabled?>> <i class="fa fa-save"></i> Update User Details Mapping</button>
                </div>
              </h4>
              
              <table id="table3" class="table table-hover" >
                <thead style="white-space: nowrap;">
                  <tr>
                    <th> Vendor Email</th>
                    <th> Vendor Code</th>
                    <th> <?php foreach ($register->result() as $key ) { ?> <?php echo $key->acc_name;?><?php } ?> </th>
                  </tr>
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
            </div>
          </div>

          <div class="col-md-12">
            <div class="box-body summary_info">
              <h4 class="box-title">Step 4 : User Email Subscription
                <div class="pull-right">
                <button type="update" class="btn btn-xs btn-primary" style="float:right;" id="email_subscription" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' <?php echo $disabled?>>  <i class="fa fa-save"></i> Update User Email Subscription </button>
                </div>
              </h4>
              <table id="table4" class="table table-hover" width="100%" cellspacing="0">
                <thead style="white-space: nowrap;">
                  <tr>
                    <th> Vendor Email</th>
                    <th> Daily Notification</th>
                    <th> Set Notification</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  if($table_array3 != '')
                  {
                    foreach($table_array3 as $key => $row) 
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
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <p class="full-width"><span class="pull-right"> <input name="close_btn" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>
      </div>             
      
    </div>
  </div> 
</div>
<!-- end proceed modal -->
             
<div id="emailModal" class="modal fade" role="dialog">
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
         <th> User Group</th>
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
        <button type="update" class="btn btn-default" id="send_email" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' >Send</button>
      </div>
    </div>
  </div>
</div> <!-- End email modal -->

                </div>   
            </div>
          </div>
</div>
<script type="text/javascript">
store_memo_type = '';
store_memo_type = "<?php echo $reg_memo_type; ?>";
function valthisform()
{
  var checkboxs=document.getElementsByClassName("supply_type");
  var okay=false;
  var email_1 = $('#comp_mail').val();
  var email_part = $('#comp_email').val();
  var comp_add = $('#comp_add').val();
  var comp_post = $('#comp_post').val();
  var comp_contact = $('#comp_contact').val();
  var second_comp_contact = $('#second_comp_contact').val();
  var comp_fax = $('#comp_fax').val();
  var part2_tb_count = $('#part2_tb_count').val();
  var business_desc = $('#business_desc').val();
  var bus_desc_others = $('#bus_desc_others').val();
  var comp_state = $('#comp_state').val();
  var participant_tb_count = $('#participant_tb_count').val();
  var billing_contact = $('#billing_contact').val();

  for(var i=0,l=checkboxs.length;i<l;i++)
  {
      if(checkboxs[i].checked)
      {
        $('#submit-data').attr("data-target","#exampleModal");
        okay=true;
        break;
      }
  }
  if(okay != true)
  {
    $('#submit-data').removeAttr('data-target');
    alert("Please select the supply type to proceed.");
    $(".supply_type").focus();
    return;
  }

  if(comp_add == '')
  {
    $('#submit-data').removeAttr('data-target');
    alert("Please insert Business Address/Billing Address.");
    $("#comp_add").focus();
    return;
  }

  if(comp_post == '')
  {
    $('#submit-data').removeAttr('data-target');
    alert("Please insert Postcode.");
    $("#comp_post").focus();
    return;
  }
  else
  {
    if(!/^[0-9]{5,6}$/g.test(comp_post))
    {
      $('#submit-data').removeAttr('data-target');
      alert('Invalid Postcode');
      $("#comp_post").focus();
      return;
    }
  }

  if(comp_state == '')
  {
    $('#submit-data').removeAttr('data-target');
    alert("Please select State.");
    $("#comp_state").focus();
    return;
  }

  if(email_1 == '')
  {
    $('#submit-data').removeAttr('data-target');
    alert("Please insert Billing Email.");
    $("#comp_mail").focus();
    return;
  }
  else
  {
    if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email_1))
    {
      $('#submit-data').removeAttr('data-target');
      alert('Invalid Billing Email');
      $("#comp_mail").focus();
      return;
    }
  }

  if(billing_contact == '')
  {
    $('#submit-data').removeAttr('data-target');
    alert("Please insert Billing Contact.");
    $("#billing_contact").focus();
    return;
  }
  else
  {
    if(!/^([0-9])[-\0-9]{8,11}$/g.test(billing_contact))
    {
      $('#submit-data').removeAttr('data-target');
      alert('Invalid Billing Contact Number.');
      $("#billing_contact").focus();
      return;
    }
  }

  if(comp_contact == '')
  {
    $('#submit-data').removeAttr('data-target');
    alert("Please insert Contact.");
    $("#comp_contact").focus();
    return;
  }
  else
  {
    if(!/^([0-9])[-\0-9]{8,11}$/g.test(comp_contact))
    {
      $('#submit-data').removeAttr('data-target');
      alert('Invalid Contact Number.');
      $("#comp_contact").focus();
      return;
    }
  }

  if(second_comp_contact != '')
  {
    if(!/^([0-9])[-\0-9]{8,11}$/g.test(second_comp_contact))
    {
      $('#submit-data').removeAttr('data-target');
      alert('Invalid Second Contact Number.');
      $("#comp_contact").focus();
      return;
    }

    if(second_comp_contact == comp_contact)
    {
      $('#submit-data').removeAttr('data-target');
      alert('Contact Number Repeated.');
      $("#second_comp_contact").focus();
      return;
    }
  }

  if(comp_fax != '')
  { 
    if(!/^([0-9])[-\0-9]{8,11}$/g.test(comp_fax))
    {
      $('#submit-data').removeAttr('data-target');
      alert('Invalid Fax Number');
      $("#comp_fax").focus();
      return;
    }
  }

  if(business_desc == '')
  { 
    $('#submit-data').removeAttr('data-target');
    alert('Please Select Business Description');
    $("#business_desc").focus();
    return;
  }
  else
  {
    if(business_desc == 'Others')
    { 
      if(bus_desc_others == '')
      {
        $('#submit-data').removeAttr('data-target');
        alert('Please Insert Value for Others Business Description.');
        $("#bus_desc_others").focus();
        return;
      }
    }
  }

}

function save_form()
{
  var email_1 = $('#comp_mail').val();
  var email_part = $('#comp_email').val();
  var comp_add = $('#comp_add').val();
  var comp_post = $('#comp_post').val();
  var comp_contact = $('#comp_contact').val();
  var comp_fax = $('#comp_fax').val();
  var comp_state = $('#comp_state').val();
  var business_desc = $('#business_desc').val();
  var billing_contact = $('#billing_contact').val();

  if(comp_add == '')
  {
    $('#save_btn').removeAttr('data-target');
    alert("Please insert Business Address/Billing Address.");
    $("#comp_add").focus();
    return;
  }

  if(comp_post == '')
  {
    $('#save_btn').removeAttr('data-target');
    alert("Please insert Postcode.");
    $("#comp_post").focus();
    return;
  }
  else
  {
    if(!/^[0-9]{5,6}$/g.test(comp_post))
    {
      $('#save_btn').removeAttr('data-target');
      alert('Invalid Postcode');
      $("#comp_post").focus();
      return;
    }
  }

  if(email_1 == '')
  {
    $('#save_btn').removeAttr('data-target');
    alert("Please insert Billing Email.");
    $("#comp_mail").focus();
    return;
  }
  else
  {
    if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email_1))
    {
      $('#save_btn').removeAttr('data-target');
      alert('Invalid Billing Email');
      $("#comp_mail").focus();
      return;
    }
  }

  if(billing_contact == '')
  {
    $('#save_btn').removeAttr('data-target');
    alert("Please insert Billing Contact.");
    $("#billing_contact").focus();
    return;
  }
  else
  {
    if(!/^([0-9])[-\0-9]{8,11}$/g.test(billing_contact))
    {
      $('#save_btn').removeAttr('data-target');
      alert('Invalid Billing Contact Number.');
      $("#billing_contact").focus();
      return;
    }
  }

  if(comp_contact == '')
  {
    $('#save_btn').removeAttr('data-target');
    alert("Please insert Contact.");
    $("#comp_contact").focus();
    return;
  }
  else
  {
    if(!/^([0-9])[-\0-9]{8,11}$/g.test(comp_contact))
    {
      $('#save_btn').removeAttr('data-target');
      alert('Invalid Contact Number.');
      $("#comp_contact").focus();
      return;
    }
  }

  if(comp_fax != '')
  { 
    if(!/^([0-9])[-\0-9]{8,11}$/g.test(comp_fax))
    {
      $('#save_btn').removeAttr('data-target');
      alert('Invalid Fax Number');
      $("#comp_fax").focus();
      return;
    }
  }

  if(business_desc == '')
  {
    $('#save_btn').removeAttr('data-target');
    alert("Please select Business Description.");
    $("#business_desc").focus();
    return;
  }
}

function proceed_form()
{
    var checkboxs=document.getElementsByClassName("supply_type");
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
      //$($(this).attr("proceed_modal")).modal("show");
      register_guid = "<?php echo $register_guid;?>"
      $($(this).attr("proceed_modal")).modal("show");
      history.pushState("", document.title, 'register_form_edit_new?register_guid='+register_guid+'&modal');
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
    $('#email_btn').prop('disabled', changed.length);
});

</script>

<script type="text/javascript">
$(document).ready(function(){
    retailer_name = $('#comp_name').val();
    company_name = $('#acc_name').val();
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

<script> 
var resetButtons = document.getElementsByClassName('reset');

// Loop through each reset buttons to bind the click event
for(var i=0; i<resetButtons.length; i++){
  resetButtons[i].addEventListener('click', resetForm);
}

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

  if($('#table2 >tbody >tr > td').length > 6){

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


<script type="text/javascript">
$('document').ready(function(){
register_guid = "<?php echo $_REQUEST['register_guid'];?>";
var comp_name = $('#comp_name').val();

vendor_table = function(register_guid)
{ 
  $.ajax({
    type: "POST",
    url: "<?php echo site_url('Registration_new/vendor_tb');?>",
    data :{register_guid:register_guid},
    dataType: 'json',
    success: function(data){
              if (  $.fn.DataTable.isDataTable( '#part2_tb' ) ) {
                $('#part2_tb').DataTable().clear().destroy()
      }

    $('#part2_tb').DataTable({
      columnDefs: [ { className: "alignleft", targets: [0]}],
      ordering: false,
      fixedHeader:false,
      sScrollY: "25vh", 
      data: data,
      columns: [
        { "data": "register_c_guid", render: function(data, type, row){
          var element = '';
          var icon = '';
          var title = '';

          <?php
          if(in_array('IAVA',$this->session->userdata('module_code')))
          {
          ?>

            if(row['form_status'] == 'Registered')
            {
              element += '';
            }
            else
            {
              element += '<button id="edit_ven_btn" type="button"  title="EDIT" class="btn btn-xs btn-info" register_guid="'+row['register_guid']+'" customer_guid="'+row['customer_guid']+'" register_c_guid="'+row['register_c_guid']+'" register_mapping_guid="'+row['register_mapping_guid']+'" ven_name="'+row['ven_name']+'" ven_designation="'+row['ven_designation']+'" ven_phone="'+row['ven_phone']+'" ven_email="'+row['ven_email']+'" ven_agency="'+row['ven_agency']+'" ven_code="'+row['ven_code']+'" vendor_code_remark="'+row['vendor_code_remark']+'" user_group_guid="'+row['user_group_guid']+'" ><i class="fa fa-edit"></i></button>';

              element += '<button id="active_btn" type="button" style="margin-left:5px;" title="REMOVE" class="btn btn-xs btn-danger"  register_guid="'+row['register_guid']+'" register_c_guid="'+row['register_c_guid']+'" ven_name="'+row['ven_name']+'" ven_designation="'+row['ven_designation']+'" ven_phone="'+row['ven_phone']+'" ven_email="'+row['ven_email']+'" ven_agency="'+row['ven_agency']+'" ven_code="'+row['ven_code']+'" isdelete="'+row['isdelete']+'" ><i class="fa fa-trash"></i></button>';
            }
         
          <?php
          }
          ?>
        
          return element;

        }},
        { "data": "ven_name" },
        { "data": "ven_designation" },
        { "data": "user_group_name" },
        { "data": "ven_phone" },
        { "data": "ven_email" },
        { "data": "ven_agency", render: function(data, type, row){ 
          var element = '';

          element += '<span class="cell_breakWord">'+data+'</span>';

          return element;
        }},
        { "data": "ven_code" },
        { "data": "vendor_code_remark" },

      ],
      dom: "<'row'"+">"+'rtp',
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
      //     exportOptions: {columns: [ 1,2,3,4,5,6,7 ]},
      //     customize: function ( win )
      //     {
      //       $(win.document.body).css( 'font-size', '12pt' )
      //       $(win.document.body).find( 'td' ).css( 'word-break', 'break-all' ,'max-width', '50%');
      //     }
      //   },
      // ],
      "footerCallback": function ( row, data, start, end, display ,iDataIndex) {
          var value_data = $('#part2_tb').DataTable().data().length;
          $('.count_part2_tb').html('<input type="hidden" id="part2_tb_count" name="part2_tb_count" value='+value_data+' readonly> ');
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

participant_table = function(register_guid)
{ 
  $.ajax({
    type: "POST",
    url: "<?php echo site_url('Registration_new/participant_tb');?>",
    data :{register_guid:register_guid},
    dataType: 'json',
    success: function(data){
              if (  $.fn.DataTable.isDataTable( '#participant_tb' ) ) {
                $('#participant_tb').DataTable().clear().destroy()
      }

    $('#participant_tb').DataTable({
      ordering: false,
      data: data,
      columns: [
        { "data": "register_c_guid", render: function(data, type, row){
          var element = '';
          var icon = '';
          var title = '';

          <?php
          if(in_array('IAVA',$this->session->userdata('module_code')))
          {
          ?>

            if(row['form_status'] == 'Registered')
            {
              element += '';
            }
            else
            {
              element += '<button id="edit_part_btn" type="button" title="EDIT" class="btn btn-xs btn-info" register_guid="'+row['register_guid']+'" customer_guid="'+row['customer_guid']+'" register_c_guid="'+row['register_c_guid']+'" part_name="'+row['part_name']+'" part_ic="'+row['part_ic']+'" part_mobile="'+row['part_mobile']+'" part_email="'+row['part_email']+'" ><i class="fa fa-edit"></i></button>';

              element += '<button id="active_btn" type="button" style="margin-left:5px;" title="REMOVE" class="btn btn-xs btn-danger"  register_guid="'+row['register_guid']+'" register_c_guid="'+row['register_c_guid']+'" part_name="'+row['part_name']+'" part_ic="'+row['part_ic']+'" part_mobile="'+row['part_mobile']+'" part_email="'+row['part_email']+'" isdelete="'+row['isdelete']+'"  ><i class="fa fa-trash"></i></button>';
            }          

          <?php
          }
          ?>
          
          return element;

        }},
        { "data": "part_name" },
        { "data": "part_ic" },
        { "data": "part_mobile" },
        { "data": "part_email" },
      ],
      dom: "<'row'"+">"+'rtp',
      // buttons: [
      //   { extend: 'copyHtml5',
      //     messageTop: 'Part2 Participant Information'+': '+company_name+' @ '+retailer_name,
      //     exportOptions: {columns: [1,2,3,4]}
      //   },
      //   { extend: 'excelHtml5',
      //     messageTop: 'Part2 Participant Information'+': '+company_name+' @ '+retailer_name,
      //     exportOptions: {columns: [1,2,3,4 ]}
      //   },

      //   { extend: 'print',
      //     messageTop: 'Part2 Participant Information'+': '+company_name+' @ '+retailer_name,
      //     exportOptions: {columns: [1,2,3,4 ]}, /*, footer: true*/ 
      //     customize: function ( win )
      //     {
      //       $(win.document.body).css( 'font-size', '12pt' )
      //       $(win.document.body).find( 'td' ).css( 'word-break', 'break-all' ,'max-width', '50%');
      //     }
      //   },
      // ],
      "footerCallback": function ( row, data, start, end, display ) {
      var value_data = $('#participant_tb').DataTable().data().length;
      $('.count_participant_tb').html('<input type="hidden" id="participant_tb_count" name="participant_tb_count" value='+value_data+' readonly>');
      },
    }); //close datatable

    } 
  });
}

vendor_table(register_guid);
participant_table(register_guid);
<?php if(isset($_REQUEST['modal']))
{
?>
// alert();
$('#proceed_modal').modal("show");
<?php
}
?>

$('#proceed_modal').on('hidden.bs.modal', function () {
  // do something…
  // alert();
  register_guid = "<?php echo $register_guid;?>";
  history.pushState("", document.title, 'register_form_edit_new?register_guid='+register_guid);
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
  var table_name1 = 'register_child_new';
  var table_name2 = 'register_new';

  $.ajax({
      url:"<?php echo site_url('Registration_new/proceed_vendor') ?>",
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
  var table_name1 = 'register_child_new';
  var table_name2 = 'register_new';
  var table_name3 = 'register_child_mapping';

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

  if(shoot_link == 0)
  {
    // console.log(details);return;
    $.ajax({
        url:"<?php echo site_url('Registration_new/proceed_user') ?>",
        method:"POST",
        data:{register_guid:register_guid,table_name1:table_name1,table_name2:table_name2,table_name3:table_name3,customer_guid:customer_guid,details:details},
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
  var table_name1 = 'register_child_new';
  var table_name2 = 'register_new';

  var details = [];

  $('#table3 tbody tr').each(function(){
    
    var vendor_email = $(this).find('td:eq(0)').text();
    var vendor_code = $(this).find('td:eq(1)').find('select').val();
    var retailer = $(this).find('td:eq(2)').text();
    var other = $(this).find('td:eq(3)').text();

    details.push({'vendor_email':vendor_email,'vendor_code':vendor_code,'retailer':retailer,'other':other});

  });

  $.ajax({
      url:"<?php echo site_url('Registration_new/proceed_mapping') ?>",
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
  //alert('email_subscription');die;
  var register_guid = $(this).attr('register_guid');
  var customer_guid = $(this).attr('customer_guid');
  var table_main = 'register_new';
  shoot_link = 0;
  var details = [];
  var notselected_notification = [];

  if($('#consignment').is(':checked') && $('#outright').is(':checked'))
  {
    var combine = 'Outright And Consignment'
  }
  else if($('#consignment').is(':checked'))
  {
    var combine = 'Consignment';
  }
  else if($('#outright').is(':checked'))
  {
    var combine = 'Outright';
  }
  else
  {
    var combine = '';
  }
  
  $('#table4 tbody tr').each(function(){
    
    var vendor_email = $(this).find('td:eq(0)').text();
    var report_guid = $(this).find('td:eq(1)').find('select').val();

    if(report_guid != '' && report_guid != null && report_guid != 'null' && report_guid != 'undefined' && report_guid != undefined )
    {
      details.push({'vendor_email':vendor_email,'report_guid':report_guid,'action_status':'insert'});
    }

  });

  $('#table4 tbody tr').each(function(){
    
    var vendor_email = $(this).find('td:eq(0)').text();
    // var report_guid_no_selected = $(this).find('td:eq(1)').find('select option:not(:selected)').val();
    var report_guid_no_selected = $(this).find('td:eq(1)').find('select option:not(:selected)').map(function() {
      return $(this).val();
    }).get();

    if(report_guid_no_selected != '' && report_guid_no_selected != null && report_guid_no_selected != 'null' && report_guid_no_selected != 'undefined' && report_guid_no_selected != undefined )
    {
      details.push({'vendor_email':vendor_email,'report_guid':report_guid_no_selected,'action_status':'delete'});
    }
    
  });


  if(shoot_link == 0)
  {
    confirmation_modal('Supply Type : <b> '+combine+' </b>.<br>Are you sure want to Update?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
    $.ajax({
        url:"<?php echo site_url('Registration_new/proceed_subscribe_email') ?>",
        method:"POST",
        data:{register_guid:register_guid,customer_guid:customer_guid,details:details,table_main:table_main},
        beforeSend:function(){
          $('.btn').button('loading');
          swal.fire({
            allowOutsideClick: false,
            title: 'Processing...',
            showCancelButton: false,
            showConfirmButton: false,
            onOpen: function () {
            swal.showLoading()
            }
          });
        },
        success:function(data)
        {
          Swal.close();

          json = JSON.parse(data);
          if (json.para1 == '1') {
            $('#alertmodal').modal('hide');
            alert(json.msg.replace(/\\n/g,"\n"));
            $('.btn').button('reset');
          }else{
            // alert(1);
            $('#alertmodal').modal('hide');
            alert(json.msg.replace(/\\n/g,"\n"));
            setTimeout(function() {
            $('.btn').button('reset');
            //window.location = window.location.href + "&openModal=1";
            }, 300);
            location.reload();
          }//close else
        }//close success
      });//close document yes click
    });//close ajax
  }
  else
  {
    alert('Report Type Cannot Be Empty Value');
    return;
  }

});//close ven


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

    //var table_name1 = 'register_child_new';
    var table_name2 = 'register_new';
    var code = $('#add_code').val();
    var register_guid = $('#hidden_reg').val();
    var customer_guid = $('#hidden_cust').val();

    if((code == '') || (code == null))
    {
      alert("Cannot empty select box")
      return;
    }//close checking for posted table_ss

    $.ajax({
          url:"<?php echo site_url('Registration_new/add_vendor_code');?>",
          method:"POST",
          data:{code:code,register_guid:register_guid,customer_guid:customer_guid,table_name2:table_name2},
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

  var modal = $("#medium-modal").modal();

  modal.find('.modal-title').html('Template Settings');

  methodd = '';

  methodd +='<div class="col-md-12">';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="complete_register_guid" value="'+register_guid+'"/></div>';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="complete_customer_guid" value="'+customer_guid+'"/></div>';

  methodd += '<div class="col-md-12"><span style="font-weight:bold;font-size:16px;"> Memo Type : <mark style="background-color:yellow;"><?php echo $check_template_name ?></mark> </span> </div> <div class="clearfix"></div><br> ';

  methodd += '</div>';

  methodd +='<div class="col-md-12">';

  methodd += '<div class="col-md-12"><label>Outright Template </label> <select class="form-control select2" name="add_outright_template" id="add_outright_template"> <option value="">-Select-</option> <?php foreach($get_outright_template as $row) { ?> <option value="<?php echo $row->template_guid?>"><?php echo $row->template_name?></option> <?php } ?></select> </div>';

  methodd += '<div class="col-md-6"><label>Outright Start Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="add_outright_start" id="add_outright_start" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';

  methodd +='</div> <div class="clearfix"></div><br>';

  methodd +='<div class="col-md-12">';

  methodd += '<div class="col-md-12"><label>Consignment Template </label> <select class="form-control select2" name="add_consign_template" id="add_consign_template"> <option value="">-Select-</option> <?php foreach($get_consign_template as $row) { ?> <option value="<?php echo $row->template_guid?>"><?php echo $row->template_name?></option> <?php } ?></select> </div>';

  methodd += '<div class="col-md-6"><label>Consignment Start Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="add_consign_start" id="add_consign_start" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';

  methodd +='</div> <div class="clearfix"></div><br>';

  methodd +='<div class="col-md-12">';

  methodd += '<div class="col-md-12"><label>Cap Template </label> <select class="form-control select2" name="add_cap_template" id="add_cap_template"> <option value="">-Select-</option> <?php foreach($get_cap_template as $row) { ?> <option value="<?php echo $row->template_guid?>"><?php echo $row->template_name?></option> <?php } ?></select> </div>';

  methodd += '<div class="col-md-6"><label>Cap Start Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="add_cap_start" id="add_cap_start" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';

  methodd += '<div class="col-md-6"><label>Cap End Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="add_cap_end" id="add_cap_end" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';

  methodd +='</div> <div class="clearfix"></div><br>';

  methodd +='<div class="col-md-12">';

  methodd += '<div class="col-md-12"><label>Waive Template </label> <select class="form-control select2" name="add_waive_template" id="add_waive_template"> <option value="">-Select-</option> <?php foreach($get_waive_template as $row) { ?> <option value="<?php echo $row->template_guid?>"><?php echo $row->template_name?></option> <?php } ?></select> </div>';

  methodd += '<div class="col-md-6"><label>Waive Start Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="add_waive_start" id="add_waive_start" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';

  methodd += '<div class="col-md-6"><label>Waive End Date </label><div class="input-group date"><div class="input-group-addon"> <i class="fa fa-calendar"></i> </div><input name="add_waive_end" id="add_waive_end" type="text" class="datepicker form-control input-sm" autocomplete="off" ></div></div>';

  methodd +='</div>';

  methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="update_complete_btn" class="btn btn-success" value="Create"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

  modal.find('.modal-footer').html(methodd_footer);
  modal.find('.modal-body').html(methodd);

  setTimeout(function(){
    $('.select2').select2();

    $('#add_waive_start').change(function(){
      var waive_date_val = $('#add_waive_start').val();
      //alert(waive_date_val); die;
      if(waive_date_val != '')
      {
        var waive_someDate = new Date(waive_date_val);
        //var waive_dd = waive_someDate.getDate();
        var waive_mm = waive_someDate.getMonth();
        var waive_y = waive_someDate.getFullYear();   
        var waive_c = new Date(waive_y + 1, waive_mm + 1 , 0);
        var waive_cnewDate = new Date(waive_c);

        var waive_result = waive_cnewDate.toLocaleDateString("fr-CA", { // you can use undefined as first argument
          year: "numeric",
          month: "2-digit",
          day: "2-digit",
        });

        $('#add_waive_end').val(waive_result);
        $('#add_waive_end').datepicker("setDate", waive_result );
      }
    });//close selection

    $('#add_cap_start').change(function(){
      var date_val = $('#add_cap_start').val();

      if(date_val != '')
      {
        var someDate = new Date(date_val);
        var dd = someDate.getDate();
        var mm = someDate.getMonth();
        var y = someDate.getFullYear();   
        var c = new Date(y + 1, mm + 1 , 0);
        var newDate = new Date(c);

        var result = newDate.toLocaleDateString("fr-CA", { // you can use undefined as first argument
          year: "numeric",
          month: "2-digit",
          day: "2-digit",
        });

        $('#add_cap_end').val(result);
        $('#add_cap_end').datepicker("setDate", result );
      }
    });//close selection

    $('.datepicker').datepicker({
      forceParse: false,
      autoclose: true,
      todayHighlight: true,
      format: 'yyyy-mm-dd'
    });
  },300);

});//close create modal template

$(document).on('click','#update_complete_btn',function(){

  var register_guid = $('#complete_register_guid').val();
  var customer_guid = $('#complete_customer_guid').val();
  var no_reg = $('#part2_tb_count').val();
  var add_outright_template = $('#add_outright_template').val();
  var add_consign_template = $('#add_consign_template').val();
  var add_cap_template = $('#add_cap_template').val();
  var add_waive_template = $('#add_waive_template').val();
  var outright_name = $('#select2-add_outright_template-container').attr('title');
  var consign_name = $('#select2-add_consign_template-container').attr('title');
  var cap_name = $('#select2-add_cap_template-container').attr('title');
  var waive_name = $('#select2-add_waive_template-container').attr('title');
  var add_outright_start = $('#add_outright_start').val();
  var add_consign_start = $('#add_consign_start').val();
  var add_cap_start = $('#add_cap_start').val();
  var add_cap_end = $('#add_cap_end').val();
  var add_waive_start = $('#add_waive_start').val();
  var add_waive_end = $('#add_waive_end').val();
  var show_confirm_outright = '';
  var show_confirm_consign = '';
  var show_confirm_cap = '';
  var show_confirm_waive = '';
  var check_memo_type = "<?php echo $check_template_name ?>";

  if(register_guid == '' || register_guid == 'null' || register_guid == null )
  {
    alert('Invalid Register Data');
    return;
  }

  if(customer_guid == '' || customer_guid == 'null' || customer_guid == null )
  {
    alert('Invalid Retailer Data');
    return;
  }

  if(add_outright_template == '' && add_consign_template == '')
  {
    alert('Please select atleast one template.');
    return;
  }

  if(check_memo_type == 'BOTH')
  {
    if(add_outright_template == '' || add_consign_template == '')
    {
      alert('Please select outright and consignment template.');
      return;
    }
    else
    {
      if(add_outright_start == '' || add_consign_start == '')
      {
        alert('Please select outright and consignment template start date.');
        return;
      }
    }
  }

  if(check_memo_type == 'OUTRIGHT')
  {
    if(add_consign_template != '')
    {
      alert('Invalid select consignment template due to is OUTRIGHT type.');
      return;
    }

    if(add_outright_template == '')
    {
      alert('Please select outright template.');
      return;
    }
    else
    {
      if(add_outright_start == '')
      {
        alert('Please select outright start date.');
        return;
      }
    }
  }

  if(check_memo_type == 'CONSIGNMENT')
  {
    if(add_outright_template != '')
    {
      alert('Invalid select outright template due to is CONSIGNMENT type.');
      return;
    }
    
    if(add_consign_template == '')
    {
      alert('Please select consignment template.');
      return;
    }
    else
    {
      if(add_consign_start == '')
      {
        alert('Please select consignment start date.');
        return;
      }
    }
  }

  if(add_cap_template == '')
  {
    if(add_cap_start != '' || add_cap_end != '')
    {
      alert('Please remove cap start date and end date or select cap template to proceed.');
      return;
    }
  }

  if(add_waive_template == '')
  {
    if(add_waive_start != '' || add_waive_end != '')
    {
      alert('Please remove waive start date and end date or select waive template to proceed.');
      return;
    }
  }

  if(add_cap_template != '')
  {
    if(add_cap_start != '')
    {
      if(add_cap_end == '')
      {
        alert('Please Insert Cap End Date.');
        return;
      }
    }
    else
    {
      alert('Please Insert Cap Start Date.');
      return;
    }

    if(add_cap_start != '' && add_cap_end != '')
    {
      if(add_cap_end < add_cap_start)
      {
        alert('Cap End Date cannot less than Cap Start Date');
        return;
      }
    }

    show_confirm_cap = '<br>Cap : <b>' +cap_name+'</b>';
  }

  if(add_waive_template != '')
  {
    if(add_waive_start != '')
    {
      if(add_waive_end == '')
      {
        alert('Please Insert Waive End Date.');
        return;
      }
    }
    else
    {
      alert('Please Insert Waive Start Date.');
      return;
    }

    if(add_waive_start != '' && add_waive_end != '')
    {
      if(add_waive_end < add_waive_start)
      {
        alert('Waive End Date cannot less than Waive Start Date');
        return;
      }
    }

    show_confirm_waive = '<br>Waive : <b>' +waive_name+'</b>';
  }

  if(outright_name != '-Select-' && outright_name != '' && outright_name != null && outright_name != 'null')
  {
    show_confirm_outright = '<br>Outright : <b>' +outright_name+'</b>';
  }

  if(consign_name != '-Select-' && consign_name != '' && consign_name != null && consign_name != 'null')
  {
    show_confirm_consign = '<br>Consignment : <b>' +consign_name+'</b>';
  }

  if(outright_name != '-Select-' && outright_name != '' && outright_name != null && outright_name != 'null')
  {
    show_confirm_outright = '<br>Outright : <b>' +outright_name+'</b>';
  }

  if(consign_name != '-Select-' && consign_name != '' && consign_name != null && consign_name != 'null')
  {
    show_confirm_consign = '<br>Consignment : <b>' +consign_name+'</b>';
  }

  if(store_memo_type != 'outright_iks')
  {
    // var no_part = $('#participant_tb_count').val();
    var no_part = '0';
    var alert_confimration = '<br>Registered Training Participant(s) : <b>' +no_part+'</b>'+show_confirm_outright+''+show_confirm_consign+''+show_confirm_cap+''+show_confirm_waive+'<br>Are you sure want <b> Registered </b> ';
  }
  else
  {
    var alert_confimration = ''+show_confirm_outright+''+show_confirm_consign+''+show_confirm_cap+''+show_confirm_waive+'<br>Are you sure want <b> Registered </b>';
  }

  confirmation_modal('Registered Login Account(s) : <b>'+no_reg+'</b>'+alert_confimration+'?');
  $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
  $.ajax({
          url:"<?php echo site_url('Registration_new/complete_status');?>",
          method:"POST",
          data:{customer_guid:customer_guid,register_guid:register_guid,add_outright_template:add_outright_template,add_consign_template:add_consign_template,add_cap_template:add_cap_template,add_waive_template:add_waive_template,add_outright_start:add_outright_start,add_consign_start:add_consign_start,add_cap_start:add_cap_start,add_cap_end:add_cap_end,add_waive_start:add_waive_start,add_waive_end:add_waive_end},
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
              window.location = "<?= site_url('Registration_new/register_form_edit_new?register_guid=');?>"+register_guid;
              }, 300);
              
            }//close else
          }//close success
        });//close ajax
  });//close document yes click
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
  var vendor_check = 'register';
  
  shoot_link = 0;  
  $('#email_tb tbody tr').each(function(){
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
  
  if(shoot_link > 0)
  {
    $.ajax({
        url:"<?php echo site_url('Registration_new/email_subs_function') ?>",
        method:"POST",
        data:{details:details,vendor_check:vendor_check},
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

$(document).on('click','#info_btn',function(){

    var register_guid = $(this).attr('register_guid');
    var customer_guid = $(this).attr('customer_guid');
    var count = $('#part2_tb').dataTable().fnGetData().length;

    // if(count != 0)
    // {
    //   if(count % 5 == 0)
    //   {
    //     alert('Additional fees will be charge up to 5 persons');
    //   }
    // }

    if(count >= 2)
    {
      alert('Login Account(s) only can fill 1 Admin Person.');
      return;
    }

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Create Vendor Account Information');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_guid" value="'+register_guid+'"/></div>';

    methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="customer_guid" value="'+customer_guid+'"/></div>';

    methodd += '<div class="col-md-6"><label>Name</label><input type="text" class="form-control input-sm" id="ven_name" autocomplete="off" required="true"/></div>';

    methodd += '<div class="col-md-6"><label>Designation</label><input type="text" class="form-control input-sm" id="ven_designation" autocomplete="off" required="true"/></div>';

    methodd += '<div class="col-md-6"><label>Phone No</label><input type="text" class="form-control input-sm" id="ven_phone" autocomplete="off" required="true"/></div>';

    methodd += '<div class="col-md-6"><label>Email Address</label><input type="email" class="form-control " id="ven_email" autocomplete="off" required="true"/></div>';

    methodd += '<div class="col-md-12"><label>User Group</label><select class="form-control select2" name="add_user_group[]" id="add_user_group" required="true" disabled><option value="">-SELECT DATA-</option><?php foreach ($get_user_group->result() as $row) { ?> <option value="<?php echo $row->user_group_guid ?>"><?php echo $row->user_group_name; ?></option> <?php } ?></select></div>';

    methodd += '<div class="col-md-12"><label>Outlet Mapping Request</label><button id="location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button><select class="form-control select2 select2_agency" name="ven_agency[]" id="ven_agency"  multiple="multiple" required="true"><?php foreach ($ven_agency_sql->result() as $row) { ?> <option value="<?php echo addslashes($row->branch_code) ?>"><?php echo $row->branch_name; ?> - <?php echo $row->branch_code; ?></option> <?php } ?></select></div>';

    methodd += '<div class="col-md-12"><label>Vendor Code</label><select class="form-control select2 vendor_select2" name="ven_code[]" id="ven_code" required="true" multiple="multiple"><?php foreach ($myArray as $row) { if(in_array($row,$array)) { $selected = 'selected'; } else { $selected = ''; } ?> <option value="<?php echo addslashes($row)?>" <?php echo $selected?>> <?php echo $row?></option> <?php }?></select></div>';

    methodd += '<div class="col-md-6" style="margin-top:5px;"><label>Vendor Code Remark (Optional) </label><div class="parts"><input type="text" class="form-control" id="remark_no" placeholder="Other"></div></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="create_ven" class="btn btn-success" value="Create"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);
 
    setTimeout(function(){
      $('#add_user_group').val('<?php echo $get_user_group_guid ?>');
      $('#ven_agency').select2();
      $('#ven_code').select2();
    },300);

});//close create part2 vendor

$(document).on('click','#create_ven',function(){

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

    if(ven_name == '' || ven_name == null || ven_name == 'null')
    {
      alert('Please insert Name');
      $("#ven_name").focus();
      return;
    }

    if(!/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/.test(ven_name))
    {
      alert('Name Cannot Have Numbering');
      $("#ven_name").focus();
      return;
    }

    if(ven_designation == '' || ven_designation == null || ven_designation == 'null')
    {
      alert('Please insert Designation');
      $("#ven_designation").focus();
      return;
    }

    if(!/^\s*[a-zA-Z.\s]+\s*$/.test(ven_designation))
    {
      alert('Designation Cannot Have Numbering');
      $("#ven_designation").focus();
      return;
    }

    if(ven_phone == '' || ven_phone == null || ven_phone == 'null')
    {
      alert('Please insert Phone');
      $("#ven_phone").focus();
      return;
    }
    
    if(!/^([0-9])[-\0-9]{8,11}$/g.test(ven_phone))
    {
      alert('Invalid Contact Number.');
      $("#ven_phone").focus();
      return;
    }

    if(ven_email == '' || ven_email == null || ven_email == 'null')
    {
      alert('Please insert Email');
      $("#ven_email").focus();
      return;
    }

    if( ! /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(ven_email))
    {
      alert('Invalid Email');
      $("#ven_email").focus();
      return;
    }

    if(ven_agency == '' || ven_agency == null || ven_agency == 'null')
    {
      alert('Please insert Outlet Mapping Request');
      $("#ven_agency").focus();
      return;
    }

    if(ven_code == '' || ven_code == null || ven_code == 'null')
    {
      alert('Please insert Vendor Code');
      $("#ven_code").focus();
      return;
    }

    $.ajax({
          url:"<?php echo site_url('Registration_new/add_vendor_info');?>",
          method:"POST",
          data:{register_guid:register_guid,customer_guid:customer_guid,ven_name:ven_name,ven_designation:ven_designation,ven_phone:ven_phone,ven_email:ven_email,ven_agency:ven_agency,ven_code:ven_code,remark_no:remark_no,add_user_group:add_user_group},
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
              $("#medium-modal").modal('hide');
              alert(json.msg);
              setTimeout(function() {
                $('.btn').button('reset');
                vendor_table(register_guid);
              }, 300);
            }//close else
          }//close success
        });//close ajax
});//close part2 ven add button

$(document).on('click','#edit_ven_btn',function(){

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
  var vendor_code_remark = $(this).attr('vendor_code_remark');
  var count_ven_agency = ven_agency.split(",").length;
  var count_ven_code = ven_code.split(",").length;
  var user_group_guid = $(this).attr('user_group_guid');


  if(vendor_code_remark == null || vendor_code_remark == 'null')
  {
    vendor_code_remark = '';
  }

  var ven_agency = ven_agency.split(',');
  var ven_code =  ven_code.split(',');

  var modal = $("#medium-modal").modal();

  modal.find('.modal-title').html('Edit Vendor Account Information');

  methodd = '';

  methodd +='<div class="col-md-12">';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_guid" value="'+register_guid+'"/></div>';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="customer_guid" value="'+customer_guid+'"/></div>';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_c_guid" value="'+register_c_guid+'"/></div>';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_mapping_guid" value="'+register_mapping_guid+'"/></div>';

  methodd += '<div class="col-md-6"><label>Name</label><input type="text" class="form-control input-sm" id="ven_name" autocomplete="off" required="true" value="'+ven_name+'"/></div>';

  methodd += '<div class="col-md-6"><label>Designation</label><input type="text" class="form-control input-sm" id="ven_designation" autocomplete="off" required="true" value="'+ven_designation+'"/></div>';

  methodd += '<div class="col-md-6"><label>Phone No</label><input type="text" class="form-control input-sm" id="ven_phone" autocomplete="off" required="true" value="'+ven_phone+'"/></div>';

  methodd += '<div class="col-md-6"><label>Email Address</label><input type="email" class="form-control " id="ven_email" autocomplete="off" required="true" value="'+ven_email+'"/></div>';

  methodd += '<div class="col-md-12"><label>User Group</label><select class="form-control select2" name="edit_user_group[]" id="edit_user_group" required="true" disabled><option value="">-SELECT DATA-</option><?php foreach ($get_user_group->result() as $row) { ?> <option value="<?php echo $row->user_group_guid ?>"><?php echo $row->user_group_name; ?></option> <?php } ?></select></div>';

  methodd += '<div class="col-md-12"><label>Outlet Mapping Request ('+count_ven_agency+')</label><button id="location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button><select class="form-control select2 select2_agency" name="ven_agency[]" id="ven_agency"  multiple="multiple" required="true"><?php foreach ($ven_agency_sql->result() as $row) { ?> <option value="<?php echo addslashes($row->branch_code) ?>"><?php echo $row->branch_name; ?> - <?php echo $row->branch_code; ?></option> <?php } ?></select></div>';

  methodd += '<div class="col-md-12"><label>Vendor Code ('+count_ven_code+')</label><select class="form-control select2 vendor_select2" name="ven_code" id="ven_code" required="true" multiple="multiple"><?php foreach ($myArray as $row) { if(in_array($row,$array)) { $selected = 'selected'; } else { $selected = ''; } ?> <option value="<?php echo addslashes($row)?>" <?php echo $selected?>> <?php echo $row?></option> <?php }?></select></div>';

  methodd += '<div class="col-md-12"><label>Vendor Code Remark</label><input type="text" class="form-control " id="ven_code_remark" autocomplete="off" required="true" value="'+vendor_code_remark+'" readonly/></div>';

  methodd += '</div>';

  methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="edit_ven" class="btn btn-success" value="Edit"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

  modal.find('.modal-footer').html(methodd_footer);
  modal.find('.modal-body').html(methodd);

  setTimeout(function(){
    $('#ven_agency').val(ven_agency);
    $('#ven_code').val(ven_code);  
    $('#edit_user_group').val(user_group_guid);  
    $('#ven_agency').select2();
    $('#ven_code').select2();
  },300);

});//close edit part2 vendor

$(document).on('click','#edit_ven',function(){

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

    if(ven_name == '' || ven_name == null || ven_name == 'null')
    {
      alert('Please insert Name');
      $("#ven_name").focus();
      return;
    }

    if(!/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/.test(ven_name))
    {
      alert('Name Cannot Have Numbering');
      $("#ven_name").focus();
      return;
    }

    if(ven_designation == '' || ven_designation == null || ven_designation == 'null')
    {
      alert('Please insert Designation');
      $("#ven_designation").focus();
      return;
    }

    if(!/^\s*[a-zA-Z.\s]+\s*$/.test(ven_designation))
    {
      alert('Designation Cannot Have Numbering');
      $("#ven_designation").focus();
      return;
    }

    if(ven_phone == '' || ven_phone == null || ven_phone == 'null')
    {
      alert('Please insert Phone');
      $("#ven_phone").focus();
      return;
    }
    
    if(!/^([0-9])[-\0-9]{8,11}$/g.test(ven_phone))
    {
      alert('Invalid Contact Number.');
      $("#ven_phone").focus();
      return;
    }

    if(ven_email == '' || ven_email == null || ven_email == 'null')
    {
      alert('Please insert Email');
      $("#ven_email").focus();
      return;
    }

    if( ! /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(ven_email))
    {
      alert('Invalid Email');
      $("#ven_email").focus();
      return;
    }

    if(ven_agency == '' || ven_agency == null || ven_agency == 'null')
    {
      alert('Please insert Outlet Mapping Request');
      $("#ven_agency").focus();
      return;
    }

    if(ven_code == '' || ven_code == null || ven_code == 'null')
    {
      alert('Please insert Vendor Code');
      $("#ven_code").focus();
      return;
    }

    $.ajax({
          url:"<?php echo site_url('Registration_new/edit_vendor_info');?>",
          method:"POST",
          data:{register_guid:register_guid,customer_guid:customer_guid,register_c_guid:register_c_guid,register_mapping_guid:register_mapping_guid,ven_name:ven_name,ven_designation:ven_designation,ven_phone:ven_phone,ven_email:ven_email,ven_agency:ven_agency,ven_code:ven_code,ven_code_remark:ven_code_remark,edit_user_group:edit_user_group},
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
              $("#medium-modal").modal('hide');
              alert(json.msg);
              setTimeout(function() {
                $('.btn').button('reset');
                vendor_table(register_guid);
              }, 300);
            }//close else
          }//close success
        });//close ajax
});//close part2 ven add button

//set status for save form 

$(document).on('click','#save_btn',function(){
  $('.add_save_status').html('<input type="hidden" id="save_status" name="save_status" value="1"/>');
});

//set status for submit form 
$(document).on('click','#submit-data',function(){
  $('.add_save_status').html('<input type="hidden" id="save_status" name="save_status" value="0"/>');
});

$(document).on('click','#ctrl_p',function(){
  window.print();
});

$(document).on('click','#active_btn',function(){
  var register_guid = $(this).attr('register_guid');
  var register_c_guid = $(this).attr('register_c_guid');
  var isdelete = $(this).attr('isdelete');

  confirmation_modal('Are you sure want to Remove?');
  $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
    $.ajax({
      url:"<?php echo site_url('Registration_new/active_status');?>",
      method:"POST",
      data:{register_guid:register_guid,register_c_guid:register_c_guid,isdelete:isdelete},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        if (json.para1 == '1') {
          $('#alertmodal').modal('hide');
          alert(json.msg);
          $('.btn').button('reset');
        }else{
          $('#alertmodal').modal('hide');
          alert(json.msg);
          setTimeout(function() {
            $('.btn').button('reset');
            vendor_table(register_guid);
            participant_table(register_guid);
          }, 300);
        }//close else
      }//close success
    });//close ajax
  });//close document yes click
});

$(document).on('click', '#location_all', function(){
  // alert();
  $("#ven_agency option").prop('selected',true);
  $(".select2").select2();
  die;
});//CLOSE ONCLICK  

$(document).on('click', '#location_all_dis', function(){
  // alert();
  $("#ven_agency option").prop('selected',false);
  $(".select2").select2();
  die;
});//CLOSE ONCLICK  

$(document).on('click', '#proceed_all', function(){
  var id = $(this).attr('get_id');
  $("#"+id+"  option").prop('selected',true);
  $("#"+id+"").trigger('change');
});//CLOSE ONCLICK  

$(document).on('click', '#proceed_all_dis', function(){
  var id_dis = $(this).attr('get_id');
  $("#"+id_dis+"  option").prop('selected',false);
  $("#"+id_dis+"  option").removeAttr('selected');
  $("#"+id_dis+"").trigger('change');
});//CLOSE ONCLICK  

$(document).on('click', '#proceed_notification_all', function(){
  var id = $(this).attr('get_id');
  $("#"+id+"  option").prop('selected',true);
  $("#"+id+"").trigger('change');
});//CLOSE ONCLICK  

$(document).on('click', '#proceed_notification_remove', function(){
  var id_dis = $(this).attr('get_id');
  $("#"+id_dis+"  option").prop('selected',false);
  $("#"+id_dis+"  option").removeAttr('selected');
  $("#"+id_dis+"").trigger('change');
});//CLOSE ONCLICK 

$(document).on('change','#business_desc',function(){

  var description = $(this).val();

  if(description == 'Others')
  {
    $('#bus_desc_others').attr('readonly',false);
    if($('#bus_desc_others').val() == '')
    {
      $('#bus_desc_others').val(sessionStorage.getItem("bus_desc_others"));
    }
    else
    {
      $('#bus_desc_others').val($('#bus_desc_others').val());
    }
    
  }
  else if((description == ''))
  {
    $('#bus_desc_others').attr('readonly',true);
    $('#bus_desc_others').val('');
  }
  else
  {
    $('#bus_desc_others').attr('readonly',true);
    $('#bus_desc_others').val('');
  }

});

$(document).on('keyup','.set_reset',function(){

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

$(document).on('change','#comp_state',function(){

  if (typeof(Storage) !== "undefined") {
    var myStorage = window.sessionStorage;
    var comp_state = $('#comp_state').val();
    var storage8 = sessionStorage.setItem("comp_state", comp_state);
  }
});

$(document).on('change','#business_desc',function(){

  if (typeof(Storage) !== "undefined") {
    var myStorage = window.sessionStorage;
    var business_desc = $('#business_desc').val();
    var storage9 = sessionStorage.setItem("business_desc", business_desc);
  }
});

if($('#comp_mail').val() == '')
{
  $('#comp_mail').val(sessionStorage.getItem("comp_mail"));
}

if($('#comp_email').val() == '')
{
  $('#comp_email').val(sessionStorage.getItem("comp_email"));
}

if($('#comp_add').val() == '')
{
  $('#comp_add').val(sessionStorage.getItem("comp_add"));
}

if($('#comp_post').val() == '')
{
  $('#comp_post').val(sessionStorage.getItem("comp_post"));
}

if($('#billing_contact').val() == '')
{
  $('#billing_contact').val(sessionStorage.getItem("billing_contact"));
}

if($('#comp_contact').val() == '')
{
  $('#comp_contact').val(sessionStorage.getItem("comp_contact"));
}

if($('#comp_fax').val() == '')
{
  $('#comp_fax').val(sessionStorage.getItem("comp_fax"));
}

if($('#bus_desc_others').val() == '')
{
  if($('#bus_desc_others').val() == 'Others')
  {
    $('#bus_desc_others').attr('readonly',false);
    $('#bus_desc_others').val(sessionStorage.getItem("bus_desc_others"));
  }
}

if($('#hidden_state').val() != '')
{
  $('#comp_state').val($('#comp_state').val());
  $('#comp_state').trigger('change');
}
else if($('#comp_state').val() == '')
{
  if(sessionStorage.getItem("comp_state") != $('#comp_state').val())
  {
    $('#comp_state').val(sessionStorage.getItem("comp_state"));
  }
}
else
{
  $('#comp_state').val($('#comp_state').val());
  $('#comp_state').trigger('change');
}

if($('#hidden_desc').val() != '')
{
  $('#business_desc').val($('#business_desc').val());
  $('#business_desc').trigger('change');
}
else if($('#business_desc').val() == '')
{
  $('#business_desc').val(sessionStorage.getItem("business_desc"));
}
else
{
  $('#business_desc').val($('#business_desc').val());
  $('#business_desc').trigger('change');
}

if($('#bus_desc_others').val() == '')
{
  $('#bus_desc_others').val(sessionStorage.getItem("bus_desc_others"));
  if($('#bus_desc_others').val() != '')
  {
    $('#bus_desc_others').attr('readonly',false);
  }
}

$(document).on('click','#term_btn',function(){
  //history.pushState("", document.title, 'register_form_edit?link='+register_guid+'&termModal');
  var supplier_name = $('#comp_name').val();
  var term_download = $('#term_hidden').val();
  var hidden_memo = $('#hidden_memo').val();
  var dl_name = supplier_name.replace(/\s/g,'_');

  var modal = $("#medium-modal").modal();

  modal.find('.modal-title').html('Preview Term Sheet');

  methodd = '';

  methodd +='<div class="col-md-12">';

  methodd += '<embed src="<?php echo site_url('Invoice/view_report_term?reg_guid=');?>'+register_guid+'&form_type=normal&supplier_name='+dl_name+'" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';

  if((hidden_memo != 'outright') && (hidden_memo != 'consignment') && (hidden_memo != 'both'))
  {
    methodd += '<embed src="<?php echo site_url('Invoice/view_term_special?reg_guid=');?>'+register_guid+'&form_type=special&supplier_name='+dl_name+'" width="100%" height="500px" style="border: none;" toolbar="0" id="pdf_view"/>';
  }
 
  methodd += '</div>';

  methodd_footer = '<p class="full-width"><span class="pull-left"> <input name="sendsumbit" type="button" class="btn btn-default confirmation_no_btn" data-dismiss="modal" value="Close"> </span> <span class="pull-right"> <?php if($is_download != '1') { ?> <input type="button" id="download_btn" class="btn btn-warning" value="Download" supplier_name ="'+supplier_name+'"><?php } ?> <input id="btn_update" type="button" class="btn btn-success" value="Update"> </span> </p>';

  modal.find('.modal-body').html(methodd);
  setTimeout(function () { 
    modal.find('.modal-footer').html(methodd_footer);
  }, 1500);

});// close

$(document).on('click','#btn_update',function(){ 
  customer_guid = "<?php echo $customer_guid;?>";
  supplier_guid = "<?php echo $supplier_guid;?>";
  var hidden_memo = $('#hidden_memo').val();
  //alert(hidden_memo);die;
  $.ajax({
      url:"<?php echo site_url('Supplier_registration/insert_terms_data');?>",
      method:"POST",
      data:{register_guid:register_guid,hidden_memo:hidden_memo,customer_guid:customer_guid,supplier_guid:supplier_guid},
      beforeSend:function(){
        $('.btn').button('loading');
      },
      success:function(data)
      {
        json = JSON.parse(data);
        if (json.para1 == '1') {
          alert(json.msg);
          history.pushState("", document.title, 'register_form_edit_new?register_guid='+register_guid+'&termModal');
          location.reload();
        }else{
          alert(json.admin_msg);
          history.pushState("", document.title, 'register_form_edit_new?register_guid='+register_guid+'&termModal');
          location.reload();
        }//close else
      }//close success
  });//close ajax
});// close

$(document).on('click','#download_btn',function(){

  var hidden_memo = $('#hidden_memo').val();

  if((hidden_memo == '') || (hidden_memo == 'null') || (hidden_memo == null) || (hidden_memo == 'undefined'))
  {
    alert('Invalid Get Data. Please refresh page.');
    return;
  }

  var supplier_name = $(this).attr('supplier_name').replace(/\s/g,'_');
  var form = document.createElement('a');
  form.href = '<?php echo site_url('Invoice/view_report_term?reg_guid=');?>'+register_guid+'&form_type=normal&supplier_name='+supplier_name;
  form.download = '<?php echo site_url('Invoice/view_report_term?reg_guid=');?>'+register_guid+'&form_type=normal&supplier_name='+supplier_name;
  document.body.appendChild(form);
  form.click();

  if((hidden_memo != 'outright') && (hidden_memo != 'consignment') && (hidden_memo != 'both'))
  {
    var form = document.createElement('a');
    form.href = '<?php echo site_url('Invoice/view_term_special?reg_guid=');?>'+register_guid+'&form_type=special&supplier_name='+supplier_name;
    form.download = '<?php echo site_url('Invoice/view_term_special?reg_guid=');?>'+register_guid+'&form_type=special&supplier_name='+supplier_name;
    document.body.appendChild(form);
    form.click();
  }

  $.ajax({
    url:"<?php echo site_url('Supplier_registration/is_download');?>",
    method:"POST",
    data:{register_guid:register_guid},
    beforeSend:function(){
      $('.btn').button('loading');
    },
    success:function(data)
    {
      json = JSON.parse(data);
      if (json.para1 == '1') {
        alert(json.msg);
        $("#medium-modal").modal('hide');
        $('.btn').button('reset');
      }else{
        alert(json.msg);
        $("#medium-modal").modal('hide');
        $('.btn').button('reset');
        setTimeout(function () { 
        location.reload();
        }, 300);
      }//close else
    }//close success
  });//close ajax
});// close


}); // close document ready
</script>