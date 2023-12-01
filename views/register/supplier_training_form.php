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
          <h2 class="text-center">Online Training Form </h2> 
          <!-- <button type="button" style="float: right;" class="btn btn-xs btn-default" id="ctrl_p"><i class="fa fa-print"></i> Print View</button> -->
              <h4>
                <b>xBridge B2B Portal Training is <span ><u style="background-color: yellow;">OPTIONAL</u></span>. If interested please complete this Training form together with payment of the Training Fees: RM200 (for 2 pax), additional RM100 for each subsequent participant.</b>
              </h4>

              <h4 class="text-bold part1" style="margin-left: 15px; ">Part 1: Organizational Information</h4><br>
                
                <form action="<?php echo site_url('Supplier_registration/training_update')?>?link=<?php echo $_REQUEST['link'] ?>" method="post" id="myForm">
                    <div class="form-row">
                    <div class="form-group col-md-6">
                    <span class="add_save_status"></span> <!--status save button -->
                    <span class="count_participant_tb"></span>
                    <?php 
                      if($training->num_rows() != 0 )
                        {
                          foreach($training->result() as $key)
                          {
                            $form_status = $key->form_status;

                            if($form_status == 'New' || $form_status == 'Processing' || $form_status == 'Emailed' || $form_status == 'Registered')
                            {
                              $disabled = 'disabled'; 
                              $readonly = 'readonly'; 
                            }
                            else
                            {
                              $disabled = ''; 
                              $readonly = ''; 
                            }
                          }
                        }
                      ?>
                    <label for="exampleInputEmail1">Company Name <span class="text-danger">*</span> </label>

                    <?php foreach ($training->result() as $key) { ?>
                      <input type="text" class="form-control" id="comp_name" name="comp_name"  aria-describedby="emailHelp" value="<?php echo $key->comp_name?>" readonly required="true">
                    <?php } ?>

                    </div>
                    </div>

                     <div class="form-group col-md-6">
                     <label for="exampleInputEmail1">Company Registration No <span class="text-danger">*</span> </label>

                     <?php foreach ($training->result() as $key) { ?>
                     <input type="text" class="form-control" id="comp_no" name="comp_no" aria-describedby="emailHelp" value="<?php echo $key->comp_no ?>" readonly required="true">
                     <?php } ?>
                     </div>

                  <?php if($training->num_rows() != 0)
                  {
                   foreach ($training->result() as $key) {
                   ?>

                    <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Retailer Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="acc_name" name="acc_name" aria-describedby="emailHelp" value="<?php echo $key->acc_name ?>" readonly required="true"  >
                    </div>
                  
                    <div class="form-group col-md-3" id="vendor">

                    <label for="exampleInputEmail1">Vendor Code (refer to Retailer) <span class="text-danger">*</span></label>
                      <?php 
                              $part5 = $key->acc_no;

                              $array =  explode(',', $part5);


                                ?>
                                <select class="form-control select2 vendor_select2 set_reset" name="acc_no[]"" required="true" multiple="multiple" disabled>
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

                    <div class="form-group col-md-2">
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
                       <label for="vehicle1" style="margin-left: 5px;margin-right: 5px;"> CONSIGNMENT</label>

                      </div>

                    <div class="form-group col-md-4" >
                      <label for="exampleInputEmail1">Billing Email Address </label><span class="text-danger">*</span>
                      <input type="email" class="form-control set_reset" id="comp_email" name="comp_email" aria-describedby="emailHelp" placeholder="Email Address" value="<?php echo $key->org_part_email ?>" required="true" <?php echo $readonly?>>
                   </div>
        
                <?php } //close foreach register ?> 
                <?php 
                } // if register num rows equal to 0
                ?>
                  <!-- Start Part 2 Vendor Here -->
                  <div class="form-group col-md-12">
                    <h4 class=" text-bold " >Part 2: Participant Information
                    <?php 
                    if($training->num_rows() != 0 )
                    {
                      foreach($training->result() as $key)
                      {
                        $form_status = $key->form_status;

                        if($form_status == 'New' || $form_status == 'Processing' || $form_status == 'Emailed' || $form_status == 'Registered')
                        {
                          
                        }
                        else
                        {
                          ?>
                          <button id="part_btn" type="button" class="btn btn-xs btn-default" style="float: right;margin-bottom:15px;" training_guid ='<?php echo $_REQUEST['link'] ?>' customer_guid ='<?php echo $customer_guid ?>'><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Add</button>
                          <?php
                        }
                      }
                    }
                    ?>
                    </h4>
                  </div>

                  <div class="info">
                    <div class="row" style="padding-left:25px;padding-right:25px;">
                      <table id="participant_tb" class="table table-hover" width="100%" cellspacing="0" >
                        <thead style="white-space: nowrap;">
                        <tr>
                            <th>Action</th>
                            <th>Name</th>
                            <th>IC NO</th>
                            <th>Mobile Phone No</th>
                            <th>Email Address</th>
                        </tr
                        </thead>
                        <tbody>
                          
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div class="note" style="margin-left: 15px;">

                  <h5>
                    Please contact <span class="text-bold"> xBridge Registration Team </span> @ <span><a href="mailto:register@xbridge.my">register@xbridge.my</a></span> or call us @ +60 17-715 9340 / +60 17-215 3088 should you require further clarifications on training registration , schedules and reservations.
                  </h5>
                </div>
                <br>
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

                  Please email the <b>bank receipt</b> to <a href="mailto:billing@xbridge.my">billing@xbridge.my</a> for issuance of official receipt:

                 </h5>

                    <ul style="list-style-type: lower-alpha;">

                        <li> Company Name & Registration No </li>
                        <li>  Email</li>
                        <li> Contact person</li>

                    </ul>

                   <br>
                 </div>

                <?php 
                if($training->num_rows() != 0 )
                {
                  foreach($training->result() as $key)
                  {
                    $form_status = $key->form_status;

                    if($form_status == 'New' || $form_status == 'Processing' || $form_status == 'Emailed' || $form_status == 'Registered')
                    {
                      
                    }
                    else
                    {
                      ?>
                      <button title="Save" onclick="save_form()" data-toggle="modal" data-target="#saveModal" id="save_btn" type="button" class="btn btn-md btn-default"><i class="fa fa-save" aria-hidden="true"></i>&nbsp&nbspSave</button>

                      <button title=""  data-toggle="modal" onclick="valthisform()" data-target="#exampleModal" id="submit-data" type="button" class="btn btn-md btn-success"><i class="glyphicon glyphicon-save" aria-hidden="true"></i>
                      &nbsp&nbspSubmit</button>
                      <?php
                    }
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
                            <span class="fa fa-question"></span>
                          </div>
                              <h1>Confirmation</h1>
                                <p>Your action is to <b>SUBMIT</b> this Form.
                                  <br>Participant User(s) : <b><span id="modal_participant_no"></span></b>
                                  <br><span style="background: yellow">Please ensure all information is correct.</span>
                                </p>
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
                            <span class="fa fa-question"></span>
                          </div>
                              <h1>Confirmation</h1>
                                <p>Your action is to <b>SAVE</b> this Form.
                                </p>
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
            </form> 
          </div>
        </div> <!--remove this two div if dont want seperate. -->
        
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
  var checkboxs=document.getElementsByClassName("supply_type");
  var okay=false;
  var email_part = $('#comp_email').val();
  var participant_tb_count = $('#participant_tb_count').val();
  $('#modal_participant_no').html(participant_tb_count);
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

  if(email_part == '')
  {
    $('#submit-data').removeAttr('data-target');
    alert("Please insert Billing Email.");
    $("#comp_email").focus();
    return;
  }

  if(email_part != '')
  { 
    if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email_part))
    {
      $('#submit-data').removeAttr('data-target');
      alert('Invalid Training Email');
      $("#comp_email").focus();
      return;
    }
  }
  
  if(participant_tb_count == '0')
  {
    $('#submit-data').removeAttr('data-target');
    alert('Please Insert Part 2 Section');
    $('#info_btn').focus();
    return;
  }

}

function save_form()
{
  var email_part = $('#comp_email').val();

  if(email_part == '')
  {
    $('#save_btn').removeAttr('data-target');
    alert("Please insert Billing Email.");
    $("#comp_email").focus();
    return;
  }
  else
  {
    if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email_part))
    {
      $('#save_btn').removeAttr('data-target');
      alert('Invalid Billing Email');
      $("#comp_email").focus();
      return;
    }
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
var button = $('#submit-data');
//var button = $('#submitBtn');

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
    $('#submit-data').prop('disabled', !changed.length);
    //$('#submitBtn').prop('disabled', changed.length);
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

$('#submit').click(function(){
    alert('submitting');
    $('#myForm').submit();
});
</script>


<script type="text/javascript">
$('document').ready(function(){
training_guid = "<?php echo $_REQUEST['link'];?>";
var comp_name = $('#comp_name').val();

participant_table = function(training_guid)
{ 
  $.ajax({
    type: "POST",
    url: "<?php echo site_url('Training_user/participant_tb');?>",
    data :{training_guid:training_guid},
    dataType: 'json',
    success: function(data){
              if (  $.fn.DataTable.isDataTable( '#participant_tb' ) ) {
                $('#participant_tb').DataTable().clear().destroy()
      }

    $('#participant_tb').DataTable({
      ordering: false,
      data: data,
      columns: [
        { "data": "training_c_guid", render: function(data, type, row){
          var element = '';
          var icon = '';
          var title = '';

          if((row['form_status'] == 'New') || (row['form_status'] == 'Processing') || (row['form_status'] == 'Emailed') || (row['form_status'] == 'Registered'))
          {
            element += '';
          }
          else
          {
            element += '<button id="edit_part_btn" type="button" title="EDIT" class="btn btn-xs btn-info" training_guid="'+row['training_guid']+'" customer_guid="'+row['customer_guid']+'" training_c_guid="'+row['training_c_guid']+'" part_name="'+row['part_name']+'" part_ic="'+row['part_ic']+'" part_mobile="'+row['part_mobile']+'" part_email="'+row['part_email']+'" ><i class="fa fa-edit"></i></button>';

            element += '<button id="active_btn" type="button" style="margin-left:5px;" title="REMOVE" class="btn btn-xs btn-danger"  training_guid="'+row['training_guid']+'" training_c_guid="'+row['training_c_guid']+'" part_name="'+row['part_name']+'" part_ic="'+row['part_ic']+'" part_mobile="'+row['part_mobile']+'" part_email="'+row['part_email']+'" isdelete="'+row['isdelete']+'"  ><i class="fa fa-trash"></i></button>';
          }          
        
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
      //     exportOptions: {columns: [1,2,3,4,5]}
      //   },
      //   { extend: 'excelHtml5',
      //     messageTop: 'Part2 Participant Information'+': '+company_name+' @ '+retailer_name,
      //     exportOptions: {columns: [1,2,3,4,5 ]}
      //   },

      //   { extend: 'print',
      //     messageTop: 'Part2 Participant Information'+': '+company_name+' @ '+retailer_name,
      //     exportOptions: {columns: [1,2,3,4,5 ]}, /*, footer: true*/ 
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

participant_table(training_guid);

$(document).on('click','#add_code_modal',function(){

  var training_guid = $(this).attr('training_guid');
  var customer_guid = $(this).attr('customer_guid');

  var modal = $("#medium-modal").modal();

  modal.find('.modal-title').html('Add Vendor Code');

  methodd = '';

  methodd +='<div class="col-md-12">';

  methodd += '<div class="col-md-12"><input hidden type="hidden" class="form-control input-sm" id="hidden_reg" value="'+training_guid+'" /></div>';

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
    var table_name2 = 'training_user_main';
    var code = $('#add_code').val();
    var training_guid = $('#hidden_reg').val();
    var customer_guid = $('#hidden_cust').val();

    if((code == '') || (code == null))
    {
      alert("Cannot empty select box")
      return;
    }//close checking for posted table_ss

    $.ajax({
          url:"<?php echo site_url('Training_user/add_vendor_code');?>",
          method:"POST",
          data:{code:code,training_guid:training_guid,customer_guid:customer_guid,table_name2:table_name2},
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

$(document).on('click','#part_btn',function(){

    var training_guid = $(this).attr('training_guid');
    var customer_guid = $(this).attr('customer_guid');

    var count = $('#participant_tb').dataTable().fnGetData().length;

    if(count != 0)
    {
      if(count >= 2)
      {
        alert('Additional fees will be charge for each persons');
      }
    }

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Create Participant Information');

    methodd = '';

    methodd +='<div class="col-md-12">';

    methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="training_guid" value="'+training_guid+'"/></div>';

    methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="customer_guid" value="'+customer_guid+'"/></div>';

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

});//close create part2 train

$(document).on('click','#create_part',function(){

    var training_guid = $('#training_guid').val();
    var customer_guid = $('#customer_guid').val();
    var part_name = $('#part_name').val();
    var part_ic = $('#part_ic').val();
    var part_mobile = $('#part_mobile').val();
    var part_email = $('#part_email').val();

    if(part_name == '' || part_name == null || part_name == 'null')
    {
      alert('Please insert Name');
      $("#part_name").focus();
      return;
    }

    if(!/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/.test(part_name))
    {
      alert('Name Cannot Have Numbering');
      $("#part_name").focus();
      return;
    }

    if(part_ic == '' || part_ic == null || part_ic == 'null')
    {
      alert('Please insert IC Number');
      $("#part_ic").focus();
      return;
    }

    if((!/(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))-([0-9]{2})-([0-9]{4})$/g.test(part_ic)) 
      && (!/(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))([0-9]{2})([0-9]{4})$/g.test(part_ic)))
    {
      alert('Invalid IC Number');
      $("#part_ic").focus();
      return;
    }

    if(part_mobile == '' || part_mobile == null || part_mobile == 'null')
    {
      alert('Please insert Phone');
      $("#part_mobile").focus();
      return;
    }

    if(!/^([0-9])[-\0-9]{8,11}$/g.test(part_mobile))
    {
      alert('Invalid Contact Number.');
      $("#part_mobile").focus();
      return;
    }

    if(part_email == '' || part_email == null || part_email == 'null')
    {
      alert('Please insert Email');
      $("#part_email").focus();
      return;
    }

    if( ! /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(part_email))
    {
      alert('Invalid Email');
      $("#part_email").focus();
      return;
    }

    $.ajax({
          url:"<?php echo site_url('Supplier_registration/add_training_info');?>",
          method:"POST",
          data:{training_guid:training_guid,customer_guid:customer_guid,part_name:part_name,part_ic:part_ic,part_mobile:part_mobile,part_email:part_email},
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
                participant_table(training_guid);
              }, 300);
            }//close else
          }//close success
        });//close ajax
});//close part2 train add button

$(document).on('click','#edit_part_btn',function(){

  var training_guid = $(this).attr('training_guid');
  var customer_guid = $(this).attr('customer_guid');
  var training_c_guid = $(this).attr('training_c_guid');
  var part_name = $(this).attr('part_name');
  var part_ic = $(this).attr('part_ic');
  var part_mobile = $(this).attr('part_mobile');
  var part_email = $(this).attr('part_email');

  var modal = $("#medium-modal").modal();

  modal.find('.modal-title').html('Edit Participant Information');

  methodd = '';

  methodd +='<div class="col-md-12">';

  methodd += '<input type="hidden" class="form-control input-sm" id="training_guid" value="'+training_guid+'" readonly/>';

  methodd += '<input type="hidden" class="form-control input-sm" id="customer_guid" value="'+customer_guid+'" readonly/>';

  methodd += '<input type="hidden" class="form-control input-sm" id="training_c_guid" value="'+training_c_guid+'" readonly/>';

  methodd += '<div class="col-md-6"><label>Name</label><input type="text" class="form-control input-sm" id="part_name" autocomplete="off" required="true" value="'+part_name+'"/></div>';

  methodd += '<div class="col-md-6"><label>IC NO</label><input type="text" class="form-control input-sm" id="part_ic" autocomplete="off" required="true" value="'+part_ic+'"/></div>';

  methodd += '<div class="col-md-6"><label>Mobile Phone No</label><input type="text" class="form-control input-sm" id="part_mobile" autocomplete="off" required="true" value="'+part_mobile+'"/></div>';

  methodd += '<div class="col-md-6"><label>Email Address</label><input type="email" class="form-control " id="part_email" autocomplete="off" required="true" value="'+part_email+'"/></div>';

  methodd += '</div>';

  methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="edit_part" class="btn btn-success" value="Save"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

  modal.find('.modal-footer').html(methodd_footer);
  modal.find('.modal-body').html(methodd);
});//close edit part2 train

$(document).on('click','#edit_part',function(){

    var training_guid = $('#training_guid').val();
    var customer_guid = $('#customer_guid').val();
    var training_c_guid = $('#training_c_guid').val();
    var part_name = $('#part_name').val();
    var part_ic = $('#part_ic').val();
    var part_mobile = $('#part_mobile').val();
    var part_email = $('#part_email').val();

    if(part_name == '' || part_name == null || part_name == 'null')
    {
      alert('Please insert Name');
      $("#part_name").focus();
      return;
    }

    if(!/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/.test(part_name))
    {
      alert('Name Cannot Have Numbering');
      $("#part_name").focus();
      return;
    }

    if(part_ic == '' || part_ic == null || part_ic == 'null')
    {
      alert('Please insert IC Number');
      $("#part_ic").focus();
      return;
    }

    if((!/(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))-([0-9]{2})-([0-9]{4})$/g.test(part_ic)) 
      && (!/(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))([0-9]{2})([0-9]{4})$/g.test(part_ic)))
    {
      alert('Invalid IC Number');
      $("#part_ic").focus();
      return;
    }

    if(part_mobile == '' || part_mobile == null || part_mobile == 'null')
    {
      alert('Please insert Phone');
      $("#part_mobile").focus();
      return;
    }

    if(!/^([0-9])[-\0-9]{8,11}$/g.test(part_mobile))
    {
      alert('Invalid Contact Number.');
      $("#part_mobile").focus();
      return;
    }

    if(part_email == '' || part_email == null || part_email == 'null')
    {
      alert('Please insert Email');
      $("#part_email").focus();
      return;
    }

    if( ! /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(part_email))
    {
      alert('Invalid Email');
      $("#part_email").focus();
      return;
    }

    $.ajax({
          url:"<?php echo site_url('Supplier_registration/edit_part_info_training');?>",
          method:"POST",
          data:{training_guid:training_guid,customer_guid:customer_guid,training_c_guid:training_c_guid,part_name:part_name,part_ic:part_ic,part_mobile:part_mobile,part_email:part_email},
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
                participant_table(training_guid);
              }, 300);
            }//close else
          }//close success
        });//close ajax
});//close part2 train add button

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
  var training_guid = $(this).attr('training_guid');
  var training_c_guid = $(this).attr('training_c_guid');
  var isdelete = $(this).attr('isdelete');

  confirmation_modal('Are you sure want to Delete?');
  $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
  $.ajax({
    url:"<?php echo site_url('Supplier_registration/active_status_training');?>",
    method:"POST",
    data:{training_guid:training_guid,training_c_guid:training_c_guid,isdelete:isdelete},
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
          participant_table(training_guid);
        }, 300);
      }//close else
    }//close success
  });//close ajax
  });//close document yes click
});

$(document).on('change','.set_reset',function(){

  if (typeof(Storage) !== "undefined") {
    var myStorage = window.sessionStorage;
    var email_part = $('#comp_email').val();

    var storage1 = sessionStorage.setItem("comp_email", email_part);

  }
});

if($('#comp_email').val() == '')
{
  $('#comp_email').val(sessionStorage.getItem("comp_email"));
}

});
</script>