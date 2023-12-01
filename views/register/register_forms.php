<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h2 class="text-center">Online Registration Form </h2> <br>
               <?php foreach ($register->result() as $key) {  ?>
                 <a type="button" class="btn btn-xs btn-primary" style="float:right;" href="register_form_edit?register_guid=<?php echo $key->register_guid ?>"  ><i class="glyphicon glyphicon-pencil"></i></a>
               <?php  } ?>
          <!--<?php if ($new_supplier > 1) { //echo $this->db->last_query();?>
              <div class="dropdown">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Retailers: </label>
                    <?php foreach ($supplier as $key) { ?>
                       <div class="col-sm-8" style="padding-left: 10px;padding-right: 0px;">
                         <select id="lang" class="form-control input-sm">
                           <option value="">-Select-</option>
                           <option value="<?php echo $key->acc_guid ?>"><?php echo $key->acc_name?></option>
                         
                         </select>
                       </div>
                       <?php } ?>-->
                  </div>
              </div>
          <!--<?php } ?>-->
            <br>
              <h4 class="text-bold part1" style="margin-left: 15px; ">Part 1: Organizational Information</h4><br>

                <form action="<?php echo site_url('Registration/register')?>" method="post">
                 <div class="form-row">
                  <div class="form-group col-md-6">

                   <label for="exampleInputEmail1">Company Name <span class="text-danger">*</span> </label>
                            <?php foreach ($register->result() as $key) { //echo $this->db->last_query();?>
                                 <input type="text" class="form-control" id="comp_name" name="comp_name"  aria-describedby="emailHelp" value="<?php echo $key->comp_name ?>" readonly >
                          <?php } ?>

                    </div>

              
                  </div>

                    
                    <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Supply Type</label><br>

                    <?php foreach ($register->result() as $key) { ?>
                      <input type="checkbox" id="outright" name="supply_type" readonly value="outright"<?php if($key->supply_type == 'outright'){ ?> checked> <?php } ?>
                      
                        <label for="vehicle1" style="margin-left: 5px;"> OUTRIGHT</label><br>
                        
                           <input type="checkbox" id="consignment" readonly name="supply_type" value="consignment"<?php if($key->supply_type == 'consignment'){ ?> checked> <?php } ?>

                       
                        <label for="vehicle1" style="margin-left: 5px;"> CONSIGNMENT</label><br>
                      </div>
                      <?php } ?>

                     <div class="form-group col-md-12">
                         <label for="exampleInputEmail1">Company Registration No <span class="text-danger">*</span> </label>
                            <?php foreach ($register->result() as $key) { ?>
                                 <input type="text" class="form-control" id="comp_no" name="comp_no" aria-describedby="emailHelp" value="<?php echo $key->comp_no ?>" readonly >
                          <?php } ?>

      
   
                  </div>
                  <br><br>

                    <!--<div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Company Registration</label>
                    <input type="text" class="form-control" id="Company Registration" aria-describedby="emailHelp" placeholder="Company Registration">
              
                  </div>-->
                  <?php foreach ($register->result() as $key) { ?>
                    <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Business Address/Billing Address <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="comp_add" name="comp_add"  aria-describedby="emailHelp" placeholder="Business Address/Billing Address" value="<?php echo $key->comp_add ?>"  readonly >
                   </div>


                   <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Postcode <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="comp_post" name="comp_post"  aria-describedby="emailHelp" placeholder="Postcode" value="<?php echo $key->supplier_postcode ?>" readonly >
              
                  </div>

                   <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">State <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="comp_state" name="comp_state"  aria-describedby="emailHelp" placeholder="State" value="<?php echo $key->supplier_state ?>" readonly >
              
                  </div>

                   <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Email Address </label>
                    <input type="text" class="form-control" id="comp_mail" value="<?php echo $key->comp_email ?>" name="comp_mail" aria-describedby="emailHelp" readonly>
              
                  </div>
                   <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Phone <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="comp_contact" name="comp_contact" aria-describedby="emailHelp" value="<?php echo $key->comp_contact ?>" readonly >
              
                  </div>
                   <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Fax </label>
                    <input type="text" class="form-control" id="comp_fax" name="comp_fax" aria-describedby="emailHelp" value="<?php echo $key->comp_fax ?>" readonly>
              
                  </div>

                     <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Business Description </label><br>
                         <input type="checkbox" id="Bread" name="bus_desc" readonly value="Bread" <?php if($key->bus_desc == 'Bread'){ ?> checked> <?php } ?>
                      
                          <label for="Bread" style="margin-left: 5px;"> Bread</label>
                            <input type="checkbox" id="Fresh" name="bus_desc" readonly value="Fresh" style="margin-left: 10px;"<?php if($key->bus_desc == 'Fresh'){ ?> checked> <?php } ?>
                      
                              <label for="Fresh" style="margin-left: 5px;"> Fresh</label><br>
                              <div class="form-group col-md-6" style="margin-left: -15px;">
                                <label for="exampleInputEmail1">Others:</label>
                                    <input type="text" class="form-control" id="bus_desc_others"  readonly name="bus_desc_others" aria-describedby="emailHelp" value="<?php echo $key->bus_desc_others ?>">
                                  </div>
              
                    </div>
                   <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Retailer Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="acc_name" name="acc_name" aria-describedby="emailHelp" value="<?php echo $key->acc_name ?>"   readonly>
                   </div>

                    <div class="form-group col-md-6 vendor">
                    <label for="exampleInputEmail1">Vendor Code (refer to Retailer) <span class="text-danger">*</span></label>
                       <?php 
                              $part5 = $key->acc_no;

                              $array =  explode(',', $part5);
                    
                              foreach ($array as $items) {
                              echo "  <input type='text' readonly class='form-control' id='acc_no' name='acc_no[]' aria-describedby='emailHelp' value=$items    ><br>";
                              }
                              ?>
                   
                    </div>


                  <h4 class=" text-bold " style="margin-left: 15px;">Part 2: Vendor/ Use Account Information</h4><br>
                    <div class="field_wrapper">
                       <div>
                        <!--<a href="javascript:void(0);" class="add_button" title="Add field"><span class="fa fa-plus" style="float: right; margin-top: -15px;"></span></a>-->
                       </div>
                     </div>

                    <div class="info">

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Name <span class="text-danger">*</span></label>
                             <?php 
                              $numbers = $key->ven_name;

                              $array =  explode('/', $numbers);
                    
                              foreach ($array as $item) {
                              echo  " <input type='text' readonly class='form-control' id='ven_name'  name='ven_name[]' aria-describedby='emailHelp' value=$item ><br>";
                              }
                              ?>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Designation </label>
                           <?php 
                              $numbers1 = $key->ven_designation;

                              $array =  explode('/', $numbers1);
                    
                              foreach ($array as $item) {
                              echo " <input type='text' readonly class='form-control' id='ven_designation'  name='ven_designation[]' aria-describedby='emailHelp' value=$item ><br>";
                              }
                              ?>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Phone No <span class="text-danger">*</span></label>
                           <?php 
                              $numbers2 = $key->ven_phone;

                              $array =  explode('/', $numbers2);
                    
                              foreach ($array as $item) {
                              echo " <input type='text' readonly class='form-control' id='ven_phone'  name='ven_phone[]' aria-describedby='emailHelp' value=$item  ><br>";
                              }
                              ?>
                          </div>

                          <div class="form-group col-md-2">
                          <label for="exampleInputEmail1">Email Address <span class="text-danger">*</span></label>
                          <?php 
                              $numbers3 = $key->ven_email;

                              $array =  explode('/', $numbers3);
                    
                              foreach ($array as $item) {
                              echo " <input type='text' readonly class='form-control' id='ven_email'  name='ven_email[]' aria-describedby='emailHelp' value=$item  ><br>";
                              }
                              ?>
                          </div>

                          <div class="form-group col-md-4">
                          <label for="exampleInputEmail1">Agency/Outlet Requests</label>
                            <?php
                            if($key->ven_agency == null || $key->ven_agency == '')
                            {
                                   echo " <input type='text' readonly class='form-control' ><br> ";
                            }
                            else{
                            ?>
                             <?php 
                              $numbers4 = $key->ven_agency;

                              $array =  explode('/', $numbers4);
                    
                              foreach ($array as $item) {
                              echo "<select class='form-control' readonly name='ven_agency[]'' required='true'> <option value=$item >$item</option></select> <br>";
                              }}
                              ?>
                          </div>

                    </div>
                <?php } ?>

                  <div class="note" style="margin-left: 15px;margin-top: 100px;">
                  <h5 class="text-bold">
                  There will be a one off RM300 Registration Fees and monthly subscriptions incur once Register.  <a href="<?php echo base_url("asset/MEMO.pdf") ?>" download >Refer to Rexbridge Memo Charges.</a>
                  </h5>

                  <h5>
                    Please contact <span class="text-bold"> xBridge Support Team </span> @ <span><a href="mailto:support@xbridge.my">support@xbridge.my</a></span> or call us @ +60177451185 / +0177159340 should you require further clarifications on registration process and access to <span class="text-bold">xBridge B2B portal</span>.
                  </h5>
               
                  <h5>
                    xBridge B2B Portal Training is <span ><u style="background-color: yellow;">OPTIONAL</u></span>. If interested please complete this Training form together with payment of the Training Fees: RM200 (for 2 pax), additional RM100 for each subsequent participant.
                  </h5><br>
                  </div>
                    <h4 class=" text-bold " style="margin-left: 15px;">Part 1: Organizational Information</h4><br>

                    <div class="form-group col-md-6" >
                    <label for="exampleInputEmail1">Company Name</label>
                     <?php foreach ($register->result() as $key) { ?>
                    <input type="text" class="form-control" id="comp_name" name="comp_name" aria-describedby="emailHelp" readonly  value="<?php echo $key->acc_name ?>" readonly>
                    
                    </div>

                    <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Email Address</label>
                    <input type="email" class="form-control" id="comp_email" name="comp_email" aria-describedby="emailHelp" value="<?php echo $key->comp_email ?>" readonly><br>
                    </div>

                     <h4 class=" text-bold " style="margin-left: 15px;">Part 2:Participant Information</h4><br>

                      <div class="field">
                         <div>
                             <!--<a href="javascript:void(0);" class="add" title="Add field"><span class="fa fa-plus" style="float: right; margin-top: -15px;"></span></a>-->

                         </div>
                      </div>

                    <div class="details" >

                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Name</label>
                            <?php 
                              $part1 = $key->part_name;

                              $array =  explode('/', $part1);
                    
                              foreach ($array as $items) {
                              echo " <input type='text' readonly class='form-control' id='part_name'  name='part_name[]' aria-describedby='emailHelp' value=$items  ><br>";
                              }
                              ?>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">IC No </label>
                           <?php 
                              $part2 = $key->part_ic;

                              $array =  explode('/', $part2);
                    
                              foreach ($array as $items) {
                              echo " <input type='text' readonly class='form-control' id='part_ic'  name='part_ic[]' aria-describedby='emailHelp' value=$items  ><br>";
                              }
                              ?>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="exampleInputEmail1">Mobile Phone No</label>
                           <?php 
                              $part3 = $key->part_mobile;

                              $array =  explode('/', $part3);
                    
                              foreach ($array as $items) {
                              echo " <input type='text' readonly class='form-control' id='part_mobile'  name='part_mobile[]' aria-describedby='emailHelp' value=$items ><br>";
                              }
                              ?>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="exampleInputEmail1">Email Address </label>
                              <?php 
                              $part4 = $key->part_email;

                              $array =  explode('/', $part4);
                    
                              foreach ($array as $items) {
                              echo " <input type='text' readonly class='form-control' id='part_email'  name='part_email[]' aria-describedby='emailHelp'  value=$items  ><br>";
                              }
                              ?>
                        </div>
                      <?php } ?>
                    </div>

                  <div class="note2" style="margin-left: 15px;">
                  <h5 class="text-md-left ">

                  Payment can be made thru Internet Banking or Account Payable Cheque based on the below bank details:

                  </h5>

                    <ul style="list-style-type: lower-alpha;">

                         <li> Account Name : <span class="text-bold">REXBRIDGE SDN BHD</span></li>
                         <li> Name of bank : <span class="text-bold"> Public Bank </span></li>
                         <li> Account number: <span class="text-bold"> 3198918900 </span></li>

                    </ul>

                 <h5 class="text-md-left ">

                  Please email the <b>bank receipt</b> to <a href="mailto:support@xbridge.my">support@xbridge.my</a> for issuance of official receipt::

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
                     <button title=""  onclick="window.location='register_admin';" id="send_registration_details" type="button" class="btn btn-sm btn-info"><i class=" glyphicon glyphicon-play" aria-hidden="true"></i>&nbsp&nbspProceed
                     </button>
                
                </div>   
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = ' <div class="form-group col-md-2"><label for="exampleInputEmail1">Name <span class="text-danger">*</span></label><input type="text" class="form-control" id="ven_name"  name="ven_name[]" aria-describedby="emailHelp" placeholder="Name"></div><div class="form-group col-md-2"> <label for="exampleInputEmail1">Designation </label><input type="text" class="form-control"  id="ven_designation" name="ven_designation[]" aria-describedby="emailHelp" placeholder="Designation"></div>  <div class="form-group col-md-2"><label for="exampleInputEmail1">Phone No <span class="text-danger">*</span></label><input type="text" class="form-control" id="ven_phone" name="ven_phone[]" aria-describedby="emailHelp" placeholder="Phone No"></div> <div class="form-group col-md-2"><label for="exampleInputEmail1">Email Address <span class="text-danger">*</span> </label><input type="text" class="form-control" id="ven_email" name="ven_email[]" aria-describedby="emailHelp" placeholder="Email Address "></div>  <div class="form-group col-md-4"><label for="exampleInputEmail1">Agency/Outlet Special Requests</label><input type="text" class="form-control" id="ven_agency" name="ven_agency[]" aria-describedby="emailHelp" placeholder="Agency/Outlet Special Requests"></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});
</script>


<script type="text/javascript">
$(document).ready(function(){
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add'); //Add button selector
    var wrapper = $('.field'); //Input field wrapper
    var fieldHTML = '<div class="form-group col-md-4"> <label for="exampleInputEmail1">Name</label><input type="text" class="form-control" name="part_name[]" id="part_name" aria-describedby="emailHelp" placeholder="Name"> </div><div class="form-group col-md-4"><label for="exampleInputEmail1">IC No </label><input type="text" class="form-control" name="part_ic[]" id="part_ic" aria-describedby="emailHelp" placeholder=IC No"></div> <div class="form-group col-md-2"><label for="exampleInputEmail1">Mobile Phone No</label><input type="text" class="form-control" name="part_mobile" name="part_mobile[]" aria-describedby="emailHelp" placeholder="Phone No"> </div><div class="form-group col-md-2"><label for="exampleInputEmail1">Email Address </label><input type="text" class="form-control" name="part_email[]" id="part_email" aria-describedby="emailHelp" placeholder="Email Address "></div> '; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});
</script>
<script type="text/javascript">
//  $("select[name='supplier_guid']").change(function(){
//
//  supplier_guid = $(this).val();
//
//    $.ajax({
//            url:"<?php echo site_url('Registration/get_comp_no');?>",
//            method:"POST",
//            data:{supplier_guid:supplier_guid},
//            success:function(data)
//            { 
//              json = JSON.parse(data);
//              
//              html = '';
//
//              for(i = 0; i < json['reg_no'].length; i++)
//              {
//                html +='<option ';
//                        
//
//                html +='value="'+json['reg_no'][i].supplier_guid+'">'+json['reg_no'][i].reg_no+'</option>';
//              } 
//
//              $("select[name='comp_no']").html(html)
//
//            }//close succcess
//        });//close ajax
//
//  
//
//})
</script>
