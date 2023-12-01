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


</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h2 class="text-center">User Account Creation Form </h2><br>
          <!-- <button type="button" style="float: right;" class="btn btn-xs btn-default" id="ctrl_p"><i class="fa fa-print"></i> Print View</button> -->
             <br>
              <h4 class="text-bold part1" style="margin-left: 15px; ">Part 1: Organizational Information</h4><br>

                <form action="<?php echo site_url('Registration_new/register_vendor_update')?>?register_guid=<?php echo $_REQUEST['register_guid'] ?>" method="post" id="myForm">
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
                                    $('#confirm-submit').modal('show');
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
                    <input type="text" class="form-control" id="comp_no" name="comp_no" aria-describedby="emailHelp" value="<?php echo $key->comp_no ?>" readonly required="true">
                    <?php } ?>
                    </div>
                    <br><br>

                  <?php if($register->num_rows() != 0)
                  {
                   foreach ($register->result() as $key) {
                   ?>
                    <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Retailer Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="acc_name" name="acc_name" aria-describedby="emailHelp" value="<?php echo $key->acc_name ?>" readonly required="true"  >
                    </div>

                    <div class="form-group col-md-3" id="vendor">
                    <?php if(in_array('IAVA',$this->session->userdata('module_code')))
                    {
                     ?>
                      <button type="button" style="float: right;" <?php echo $disabled?> class="btn btn-xs btn-default" id="add_code_modal" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>'>Add Code</button>
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

                    <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Supply Type <span class="text-danger">*</span> </label><br>

                      <input type="checkbox" class="set_reset supply_type" id="outright" name="supply_outright" value="outright" <?php echo $disabled?> <?php 
                      if($key->supply_outright == 'outright')
                      { 
                        ?> checked
                        > 
                        <?php 
                      }else if($key->memo_type == 'outright')
                      {?> checked
                        > 
                        <?php 
                      }
                      else
                      {?> > <?php } ?>
                      <label for="vehicle1" style="margin-left: 5px;margin-right: 5px;"> OUTRIGHT</label>
                        
                      <input type="checkbox" class="set_reset supply_type" id="consignment" name="supply_consignment" value="consignment" <?php echo $disabled?> <?php 
                      if($key->supply_consignment == 'consignment'){ 
                        ?> checked
                        > <?php 
                      }else if($key->memo_type == 'consignment')
                      {?> checked
                        > 
                        <?php 
                      }
                      else{?> > <?php } ?>
                       <label for="vehicle1" style="margin-left: 5px;"> CONSIGNMENT</label>

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
                      <button id="info_btn" type="button" <?php echo $disabled?> class="btn btn-xs btn-default" style="float: right;margin-bottom:15px;" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>'><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Add</button>
                     <?php
                    }
                    ?>  
                    </h4>
                  </div>

                  <div class="info">
                    <div class="row" style="padding-left:25px;padding-right:25px;">
                      <table id="part2_tb" class="table table-hover" width="100%" cellspacing="0" >
                        <thead>
                        <tr>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Designation</th>
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
                  
                  <h5>
                    Please contact <span class="text-bold"> xBridge Registration Team </span> @ <span><a href="mailto:register@xbridge.my">register@xbridge.my</a></span> or call us @ +60 17-715 9340 / +60 17-215 3088 should you require further clarifications on registration process and access to <span class="text-bold">xBridge B2B portal</span>.
                  </h5>
                  <br>
                  </div>

                  <div class="note2" style="margin-left: 15px;">
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
                        <li>  Email</li>
                        <li> Contact person</li>

                    </ul>
                    <br>
                 </div>

                  <?php if(in_array('IAVA',$this->session->userdata('module_code')))
                  {
                   ?>
                    <?php if($register->num_rows() != 0) { ?>

                    <!-- <button title="Save" data-toggle="modal" data-target="#saveModal" id="save_btn" type="button" class="btn btn-md btn-default"><i class="fa fa-save" aria-hidden="true" <?php echo $disabled?>></i>&nbsp&nbspSave</button> -->

                    <button title="Submit" onclick="valthisform()" data-toggle="modal" data-target="#exampleModal" id="submit-data" type="button" class="btn btn-md btn-success"><i class="glyphicon glyphicon-save" aria-hidden="true" <?php echo $disabled?>></i>&nbsp&nbsp<?php echo $button_name?></button>
                    <?php 
                    }
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
                    <button id="completebtn" type="button" class="btn btn-md btn-warning" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>'  <?php echo $disabled_special ?>><i class="fa fa-check" aria-hidden="true"></i>&nbspRegistered</button>
                   <?php
                  }
                  ?>
                </form> 
                <!-- data-target="#confirm-submit" proceed modal -->
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
                                  
                                    echo "<div id='acno1' > Not Mapped</div>"; 
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
                            <button type="update" class="btn btn-default" style="margin-left:500px;" id="vendor_update" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' <?php echo $disabled?> >Update</button>


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
                            <button type="update" class="btn btn-default" style="margin-left:500px;" id="user_update" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' <?php echo $disabled?> >Update</button>
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
                            <button type="update" class="btn btn-default" style="margin-left:500px;" id="mapping_update" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' <?php echo $disabled?> >Update</button>

                          <h5>User Email Subscription </h5>

                           <table class="table" id="table4">
                            <thead>
                             <th> Vendor Email</th>
                             <th> Status</th>
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
                           <button type="update" class="btn btn-default" id="email_subscription" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>' <?php echo $disabled?> >Update</button>
                          
                        </div>
                      </div>

                   </div>
                  </div> <!-- end proceed modal -->
             
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
                          <button type="update" class="btn btn-default" id="send_email" register_guid ='<?php echo $_REQUEST['register_guid'] ?>' customer_guid ='<?php echo $customer_guid ?>'>Send</button>
                        </div>
                      </div>
                    </div>
                  </div> <!-- End email modal -->
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
  var part2_tb_count = $('#part2_tb_count').val();

  if(part2_tb_count == '0')
  {
    $('#submit-data').removeAttr('data-target');
    alert('Please Insert Part 2 Section');
    $('#info_btn').focus();
    return;
  }

}

function proceed_form()
{
  if(part2_tb_count == '0')
  {
    $('#submit-data').removeAttr('data-target');
    alert('Please Insert Part 2 Section');
    $('#info_btn').focus();
    return;
  }
  else
  {
    //$($(this).attr("proceed_modal")).modal("show");
    register_guid = "<?php echo $register_guid;?>"
     $($(this).attr("confirm-submit")).modal("show");
    history.pushState("", document.title, 'register_forms_vendor?register_guid='+register_guid+'&modal');
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
  text3 += "Not Mapped"+ "<br>";
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
  text4 += "Not Mapped"+ "<br>";
  }

  document.getElementById("demo4").innerHTML = text4;

  var vq= $('.ven_name');
   for (var x = 0; x < vq.length; x++)
  {
  text5 += "Not Mapped"+ "<br>";
  }

  document.getElementById("demo5").innerHTML = text5;

   var vz= $('.ven_name');
   for (var x = 0; x < vz.length; x++)
  {
  text6 += "Not Mapped" +"<br>";
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

</script>


<script type="text/javascript">
$('document').ready(function(){
register_guid = "<?php echo $_REQUEST['register_guid'];?>";
retailer_name = $('#comp_name').val();
company_name = $('#acc_name').val();
vendor_table = function(register_guid)
{ 
  $.ajax({
    type: "POST",
    url: "<?php echo site_url('Registration_new/add_vendor_tb');?>",
    data :{register_guid:register_guid},
    dataType: 'json',
    success: function(data){
              if (  $.fn.DataTable.isDataTable( '#part2_tb' ) ) {
                $('#part2_tb').DataTable().clear().destroy()
      }

    $('#part2_tb').DataTable({
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
              element += '<button id="edit_ven_btn" type="button"  title="EDIT" class="btn btn-xs btn-info" register_guid="'+row['register_guid']+'" customer_guid="'+row['customer_guid']+'" register_c_guid="'+row['register_c_guid']+'" register_mapping_guid="'+row['register_mapping_guid']+'" ven_name="'+row['ven_name']+'" ven_designation="'+row['ven_designation']+'" ven_phone="'+row['ven_phone']+'" ven_email="'+row['ven_email']+'" ven_agency="'+row['ven_agency']+'" ven_code="'+row['ven_code']+'" vendor_code_remark="'+row['vendor_code_remark']+'" ><i class="fa fa-edit"></i></button>';

              element += '<button id="active_btn" type="button" style="margin-left:5px;" title="REMOVE" class="btn btn-xs btn-danger"  register_guid="'+row['register_guid']+'" register_c_guid="'+row['register_c_guid']+'" ven_name="'+row['ven_name']+'" ven_designation="'+row['ven_designation']+'" ven_phone="'+row['ven_phone']+'" ven_email="'+row['ven_email']+'" ven_agency="'+row['ven_agency']+'" ven_code="'+row['ven_code']+'" isdelete="'+row['isdelete']+'" ><i class="fa fa-trash"></i></button>';
            }
             
          <?php
          }
          ?>
        
          return element;

        }},
        { "data": "ven_name" },
        { "data": "ven_designation" },
        { "data": "ven_phone" },
        { "data": "ven_email" },
        { "data": "ven_agency" },
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
      //     exportOptions: {columns: [ 1,2,3,4,5,6,7 ]}, /*, footer: true*/ 
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
      },
    }); //close datatable

    } 
  });
}

vendor_table(register_guid);

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
  history.pushState("", document.title, 'register_forms_vendor?register_guid='+register_guid);
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
  var table_name1 = 'register_add_user_child';
  var table_name2 = 'register_add_user_main';

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
          window.location = window.location.href + "&openModal=1";
          }, 300);
          location.reload();
        }//close else
      }//close success
    });//close ajax
});//close acno

$(document).on('click','#user_update',function(){

  var register_guid = $(this).attr('register_guid');
  var customer_guid = $(this).attr('customer_guid');
  var table_name1 = 'register_add_user_child';
  var table_name2 = 'register_add_user_main';
  var table_name3 = 'register_add_user_child_mapping';

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
            window.location = window.location.href + "&openModal=1";
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
  var table_name1 = 'register_add_user_child';
  var table_name2 = 'register_add_user_main';

  var details = [];

  $('#table3 tbody tr').each(function(){
    
    var vendor_email = $(this).find('td:eq(0)').text();
    var vendor_code = $(this).find('td:eq(1)').find('select').val();
    var retailer = $(this).find('td:eq(2)').text();
    var other = $(this).find('td:eq(3)').text();

    details.push({'vendor_email':vendor_email,'vendor_code':vendor_code,'retailer':retailer,'other':other});

  });
  //console.log(details); die;

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
          window.location = window.location.href + "&openModal=1";
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
  var memo_type = $(this).attr('memo_type');
  var table_main = 'register_add_user_main';
  shoot_link = 0;
  var details = [];

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

    if(report_guid == '' || report_guid == null)
    {
      shoot_link = shoot_link+1;
      // alert(shoot_link+'***'+user_group);
    }
    details.push({'vendor_email':vendor_email,'report_guid':report_guid});

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
      });//close document yes click
    });//close ajax
  }
  else
  {
    alert('Report Type Cannot Be Empty Value');return;
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

  methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="add_vendor_code" class="btn btn-success" value="Create"> <input name="sendsubmit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

  modal.find('.modal-footer').html(methodd_footer);
  modal.find('.modal-body').html(methodd);

  setTimeout(function(){
    $('#add_code').select2();
  },300);

});//close add vendor code

$(document).on('click','#add_vendor_code',function(){

    //var table_name1 = 'register_child_new';
    var table_name2 = 'register_add_user_main';
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
  var no_reg = $('#part2_tb_count').val();
  
  confirmation_modal('Registered Login Account(s) : <b>'+no_reg+'</b><br>Are you sure want <b> Registered </b>?');
  $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
    $.ajax({
      url:"<?php echo site_url('Registration_new/complete_status_vendor');?>",
      method:"POST",
      data:{customer_guid:customer_guid,register_guid:register_guid},
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
          window.location = "<?= site_url('Registration_new/register_forms_vendor?register_guid=');?>"+register_guid;
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
  var vendor_check = 'vendor';

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
    
    if(count != 0)
    {
      if(count % 5 == 0)
      {
        alert('Additional fees will be charge up to 5 persons');
      }
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

    methodd += '<div class="col-md-12"><label>Outlet Mapping Request</label><button id="location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button><select class="form-control select2 select2_agency" name="ven_agency[]" id="ven_agency"  multiple="multiple" required="true"><?php foreach ($ven_agency_sql->result() as $row) { ?> <option value="<?php echo addslashes($row->branch_code) ?>"><?php echo $row->branch_name; ?> - <?php echo $row->branch_code; ?></option> <?php } ?></select></div>';

    methodd += '<div class="col-md-12"><label>Vendor Code</label><select class="form-control select2 vendor_select2" name="ven_code[]" id="ven_code" required="true" multiple="multiple"><?php foreach ($myArray as $row) { if(in_array($row,$array)) { $selected = 'selected'; } else { $selected = ''; } ?> <option value="<?php echo addslashes($row)?>" <?php echo $selected?>> <?php echo $row?></option> <?php }?></select></div>';

    methodd += '<div class="col-md-6" style="margin-top:5px;"><label>Vendor Code Remark (Optional) </label><div class="parts"><input type="text" class="form-control" id="remark_no" placeholder="Other"></div></div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="create_ven" class="btn btn-success" value="Create"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
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

    if(ven_name == '' || ven_name == null || ven_name == 'null')
    {
      alert('Please insert Name');
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
      return;
    }

    if( ! /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(ven_email))
    {
      alert('Invalid Email');
      return;
    }

    if(ven_agency == '' || ven_agency == null || ven_agency == 'null')
    {
      alert('Please insert Outlet Mapping Request');
      return;
    }

    if(ven_code == '' || ven_code == null || ven_code == 'null')
    {
      alert('Please insert Vendor Code');
      return;
    }

    $.ajax({
          url:"<?php echo site_url('Registration_new/add_vendor_info_vens');?>",
          method:"POST",
          data:{register_guid:register_guid,customer_guid:customer_guid,ven_name:ven_name,ven_designation:ven_designation,ven_phone:ven_phone,ven_email:ven_email,ven_agency:ven_agency,ven_code:ven_code,remark_no:remark_no},
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
  var ven_agency = ven_agency.split(',');
  var ven_code =  ven_code.split(',');
  var vendor_code_remark = $(this).attr('vendor_code_remark');

  if(vendor_code_remark == null || vendor_code_remark == 'null')
  {
    vendor_code_remark = '';
  }

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

  methodd += '<div class="col-md-12"><label>Outlet Mapping Request</label><button id="location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button><select class="form-control select2 select2_agency" name="ven_agency[]" id="ven_agency"  multiple="multiple" required="true"><?php foreach ($ven_agency_sql->result() as $row) { ?> <option value="<?php echo addslashes($row->branch_code) ?>"><?php echo $row->branch_name; ?> - <?php echo $row->branch_code; ?></option> <?php } ?></select></div>';

  methodd += '<div class="col-md-12"><label>Vendor Code</label><select class="form-control select2 vendor_select2" name="ven_code" id="ven_code" required="true" multiple="multiple"><?php foreach ($myArray as $row) { if(in_array($row,$array)) { $selected = 'selected'; } else { $selected = ''; } ?> <option value="<?php echo addslashes($row)?>" <?php echo $selected?>> <?php echo $row?></option> <?php }?></select></div>';

  methodd += '<div class="col-md-12"><label>Vendor Code Remark</label><input type="text" class="form-control " id="ven_code_remark" autocomplete="off" required="true" value="'+vendor_code_remark+'" /></div>';

  methodd += '</div>';

  methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="edit_ven" class="btn btn-success" value="Edit"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

  modal.find('.modal-footer').html(methodd_footer);
  modal.find('.modal-body').html(methodd);

  setTimeout(function(){
    $('#ven_agency').val(ven_agency);
    $('#ven_code').val(ven_code);
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

    if(ven_name == '' || ven_name == null || ven_name == 'null')
    {
      alert('Please insert Name');
      return;
    }

    if(ven_designation == '' || ven_designation == null || ven_designation == 'null')
    {
      alert('Please insert Designation');
      return;
    }

    if(ven_phone == '' || ven_phone == null || ven_phone == 'null')
    {
      alert('Please insert Phone');
      return;
    }

    if(ven_email == '' || ven_email == null || ven_email == 'null')
    {
      alert('Please insert Email');
      return;
    }

    if( ! /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(ven_email))
    {
      alert('Invalid Email');
      return;
    }

    if(ven_agency == '' || ven_agency == null || ven_agency == 'null')
    {
      alert('Please insert Outlet Mapping Request');
      return;
    }

    if(ven_code == '' || ven_code == null || ven_code == 'null')
    {
      alert('Please insert Vendor Code');
      return;
    }

    $.ajax({
          url:"<?php echo site_url('Registration_new/edit_vendor_info_vens');?>",
          method:"POST",
          data:{register_guid:register_guid,customer_guid:customer_guid,register_c_guid:register_c_guid,register_mapping_guid:register_mapping_guid,ven_name:ven_name,ven_designation:ven_designation,ven_phone:ven_phone,ven_email:ven_email,ven_agency:ven_agency,ven_code:ven_code,ven_code_remark:ven_code_remark},
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

$(document).on('click','#active_btn',function(){
  var register_guid = $(this).attr('register_guid');
  var register_c_guid = $(this).attr('register_c_guid');
  var isdelete = $(this).attr('isdelete');

  confirmation_modal('Are you sure want to Delete?');
  $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
  $.ajax({
    url:"<?php echo site_url('Registration_new/active_status_vendor');?>",
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

$(document).on('click','#save_btn',function(){
  $('.add_save_status').html('<input type="text" id="save_status" name="save_status" value="1"/>');
});

//set status for submit form 
$(document).on('click','#submit-data',function(){
  $('.add_save_status').html('<input type="hidden" id="save_status" name="save_status" value="0"/>');
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

$(document).on('click','#ctrl_p',function(){
  window.print();
});

});
</script>