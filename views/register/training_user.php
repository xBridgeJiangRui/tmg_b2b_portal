<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h2 class="text-center">Training Form </h2> <br>
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
                       <?php } ?>
                  </div>
              </div>
          <?php } ?>-->
          <h5 style="margin-left: 15px; ">xBridge B2B Portal Training is<span style="background-color: yellow; "> OPTIONAL</span>. If interested please complete this Training form together
                with payment of the Training Fees: RM200 (for 2 pax), additional RM100 for each subsequent participant.</h5><br>

              <h4 class="text-bold part1" style="margin-left: 15px; ">Part 1: Organizational Information</h4><br>

                <form action="<?php echo site_url('Training_user/register')?>" method="post">
                 <div class="form-row">
                  <div class="form-group col-md-12">

                   <label for="exampleInputEmail1">Company Name <span class="text-danger">*</span> </label>
                        <?php foreach ($supplier as $key) { ?>
                     <input type="text" class="form-control" id="comp_name" name="comp_name"  aria-describedby="emailHelp" value="<?php echo $key->acc_name;?>" disabled>
                    <?php } ?>

                    </div>

                   <div class="form-group col-md-12">
                    <label for="exampleInputEmail1">Email Address </label>
                    <input type="text" class="form-control" id="comp_email" name="comp_email" aria-describedby="emailHelp" placeholder="Email Address">
              
                  </div>

              
                  </div>

                  <br><br>

                    <!--<div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Company Registration</label>
                    <input type="text" class="form-control" id="Company Registration" aria-describedby="emailHelp" placeholder="Company Registration">
              
                  </div>-->

               

                 

                


                  <div class="note" style="margin-left: 15px;margin-top: 100px;"><br>
               

                     <h4 class=" text-bold " >Part 2:Participant Information</h4><br>

                      <div class="field">
                         <div>
                             <a href="javascript:void(0);" class="add" title="Add field"><span class="fa fa-plus" style="float: right; margin-top: -15px;"></span></a>

                         </div>
                      </div>

                    <div class="details" >

                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" class="form-control" id="part_name" name="part_name[]" aria-describedby="emailHelp" placeholder="Name">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1">IC No </label>
                            <input type="text" data-mask="000000-00-0000" class="form-control" id="part_ic" name="part_ic[]" aria-describedby="emailHelp" placeholder="IC NO">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="exampleInputEmail1">Mobile Phone No</label>
                            <input type="text" data-mask="000-000-0000" class="form-control" id="part_mobile" name="part_mobile[]" aria-describedby="emailHelp" placeholder="Phone No">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="exampleInputEmail1">Email Address </label>
                            <input type="text" class="form-control" id="part_email" name="part_email[]" aria-describedby="emailHelp" placeholder="Email Address ">
                        </div>
   
                    </div>

                  <div class="note2" style="margin-left: 15px;margin-top: 80px;">
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
                 <button type="submit" class="btn btn-primary">Submit</button>


                </div>   
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>


<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    var maxField = 1000; //Input fields increment limitation
    var addButton = $('.add'); //Add button selector
    var wrapper = $('.field'); //Input field wrapper
    var fieldHTML = '<div class="parts"><div class="form-group col-md-4"> <label for="exampleInputEmail1">Name</label><input type="text" class="form-control" name="part_name[]" id="part_name" aria-describedby="emailHelp" placeholder="Name"> </div><div class="form-group col-md-3"><label for="exampleInputEmail1">IC No </label><input type="text" class="form-control" name="part_ic[]" id="part_ic" aria-describedby="emailHelp" placeholder=IC No"></div> <div class="form-group col-md-2"><label for="exampleInputEmail1">Mobile Phone No</label><input type="text" class="form-control" name="part_mobile[]" aria-describedby="emailHelp" placeholder="Phone No"> </div><div class="form-group col-md-2"><label for="exampleInputEmail1">Email Address </label><input type="text" class="form-control" name="part_email[]" id="part_email" aria-describedby="emailHelp" placeholder="Email Address "></div><a href="#" class="remove_field"><i class="fa fa-times" style="margin-bottom:60px;margin-left:15px;"></i></a></div> '; //New input field html 
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
    var fieldHTML = '<input type="text" class="form-control" id="acc_no" name="acc_no[]" aria-describedby="emailHelp"   ><br> '; //New input field html 
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
    $(wrapper).on('click', '.remove_field', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });

});

$(document).ready(function(){
$(":input").inputmask();



$("#comp_contact").inputmask({
mask: '999-999-9999',
placeholder: ' ',
showMaskOnHover: false,
showMaskOnFocus: false,
onBeforePaste: function (pastedValue, opts) {
var processedValue = pastedValue;

//do something with it

return processedValue;
}
});
$("#comp_fax").inputmask({
mask: '999-999-9999',
placeholder: ' ',
showMaskOnHover: false,
showMaskOnFocus: false,
onBeforePaste: function (pastedValue, opts) {
var processedValue = pastedValue;

//do something with it

return processedValue;
}
});
$("#ven_phone").inputmask({
mask: '999-999-9999',
placeholder: ' ',
showMaskOnHover: false,
showMaskOnFocus: false,
onBeforePaste: function (pastedValue, opts) {
var processedValue = pastedValue;

//do something with it

return processedValue;
}
});
$("#part_mobile").inputmask({
mask: '999-999-9999',
placeholder: ' ',
showMaskOnHover: false,
showMaskOnFocus: false,
onBeforePaste: function (pastedValue, opts) {
var processedValue = pastedValue;

//do something with it

return processedValue;
}
});
$("#part_ic").inputmask({
mask: '999999-99-9999',
placeholder: ' ',
showMaskOnHover: false,
showMaskOnFocus: false,
onBeforePaste: function (pastedValue, opts) {
var processedValue = pastedValue;

//do something with it

return processedValue;
}
});
});
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