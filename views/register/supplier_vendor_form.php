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
          <h2 class="text-center">User Account Creation Form </h2>
          <!-- <button type="button" style="float: right;" class="btn btn-xs btn-default" id="ctrl_p"><i class="fa fa-print"></i> Print View</button> -->
              <h4 class="text-bold part1" style="margin-left: 15px; ">Part 1: Organizational Information</h4><br>

                <form action="<?php echo site_url('Supplier_registration/register_vendor_update')?>?link=<?php echo $_REQUEST['link'] ?>" method="post" id="myForm">
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
                    <label for="exampleInputEmail1">Vendor Code (refer to Retailer) <span class="text-danger">*</span></label>
                      <?php 
                              $part5 = $key->acc_no;

                              $array =  explode(',', $part5);

                                ?>
                                <select class="form-control select2 vendor_select2 set_reset" name="acc_no[]" id="acc_no" required="true" multiple="multiple" disabled >
                               
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
                    <?php 
                    if($register->num_rows() != 0 )
                    {
                        foreach($register->result() as $key)
                        {
                          $form_status = $key->form_status;

                          if($form_status == 'New' || $form_status == 'Processing' || $form_status == 'Emailed' || $form_status == 'Registered')
                          {
                            //$disabled = 'display: none;'; 
                          }
                          else
                          {
                            //$disabled = '';
                            ?>
                            <button id="info_btn" type="button" class="btn btn-xs btn-default" style="float: right;margin-bottom:15px;" register_guid ='<?php echo $_REQUEST['link'] ?>' customer_guid ='<?php echo $customer_guid ?>'><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Add</button>
                            <?php
                          }
                        }
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

                 <?php 
                    if($register->num_rows() != 0 )
                    {
                      foreach($register->result() as $key)
                      {
                        $form_status = $key->form_status;

                        if($form_status == 'New' || $form_status == 'Processing' || $form_status == 'Emailed' || $form_status == 'Registered')
                        {
                          //$disabled = 'display: none;'; 

                        }
                        else
                        {
                          //$disabled = '';
                          ?>
                          <button title="Save" data-toggle="modal" onclick="saveform()" data-target="#saveModal" id="save_btn" type="button" class="btn btn-md btn-default"><i class="fa fa-save" aria-hidden="true"></i>&nbsp&nbspSave</button>

                          <button title="Submit" data-toggle="modal" onclick="valthisform()" data-target="#exampleModal" id="submit-data" type="button" class="btn btn-md btn-success"><i class="glyphicon glyphicon-save" aria-hidden="true"></i>&nbsp&nbspSubmit</button>
                          <?php
                        }
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
                            <span class="fa fa-question"></span>
                          </div>
                              <h1>Confirmation</h1>
                                <p>Your action is to <b>SUBMIT</b> this Form.
                                  <br>Login Account(s) : <b><span id="modal_reg_no"></span></b>
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
  $('#modal_reg_no').html(part2_tb_count);

  if(part2_tb_count == '0')
  {
    $('#submit-data').removeAttr('data-target');
    alert('Please Insert Part 2 Section');
    $('#info_btn').focus();
    return;
  }
  else
  {
    $('#submit-data').attr("data-target","#exampleModal");
  }
}

function saveform()
{
  var part2_tb_count = $('#part2_tb_count').val();

  if(part2_tb_count == '0')
  {
    $('#save_btn').removeAttr('data-target');
    alert('Please Insert Part 2 Section');
    $('#info_btn').focus();
    return;
  }
  else
  {
    $('#save_btn').attr("data-target","#saveModal");
  }
}

</script>

<script type="text/javascript">
$('document').ready(function(){
register_guid = "<?php echo $_REQUEST['link'];?>";
retailer_name = $('#comp_name').val();
company_name = $('#acc_name').val();
vendor_table = function(register_guid)
{ 
  $.ajax({
    type: "POST",
    url: "<?php echo site_url('Supplier_registration/add_vendor_tb');?>",
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

          if((row['form_status'] == 'New') || (row['form_status'] == 'Processing') || (row['form_status'] == 'Emailed') || (row['form_status'] == 'Registered'))
          {
            element += '';
          }
          else
          {
            element += '<button id="edit_ven_btn" type="button"  title="EDIT" class="btn btn-xs btn-info" register_guid="'+row['register_guid']+'" customer_guid="'+row['customer_guid']+'" register_c_guid="'+row['register_c_guid']+'" register_mapping_guid="'+row['register_mapping_guid']+'" ven_name="'+row['ven_name']+'" ven_designation="'+row['ven_designation']+'" ven_phone="'+row['ven_phone']+'" ven_email="'+row['ven_email']+'" ven_agency="'+row['ven_agency']+'" ven_code="'+row['ven_code']+'" vendor_code_remark="'+row['vendor_code_remark']+'" ><i class="fa fa-edit"></i></button>';

            element += '<button id="active_btn" type="button" style="margin-left:5px;" title="REMOVE" class="btn btn-xs btn-danger"  register_guid="'+row['register_guid']+'" register_c_guid="'+row['register_c_guid']+'" ven_name="'+row['ven_name']+'" ven_designation="'+row['ven_designation']+'" ven_phone="'+row['ven_phone']+'" ven_email="'+row['ven_email']+'" ven_agency="'+row['ven_agency']+'" ven_code="'+row['ven_code']+'" isdelete="'+row['isdelete']+'" ><i class="fa fa-trash"></i></button>';
          }
        
          return element;

        }},
        { "data": "ven_name" },
        { "data": "ven_designation" },
        { "data": "ven_phone" },
        { "data": "ven_email" },
        { "data": "ven_agency" , render: function(data, type, row){ 
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
          url:"<?php echo site_url('Supplier_registration/add_vendor_code');?>",
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

    modal.find('.modal-title').html('Create Login Account Information');

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
          url:"<?php echo site_url('Supplier_registration/add_vendor_info_vens');?>",
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

  modal.find('.modal-title').html('Edit Login Account Information');

  methodd = '';

  methodd +='<div class="col-md-12">';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_guid" value="'+register_guid+'"/></div>';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="customer_guid" value="'+customer_guid+'"/></div>';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_c_guid" value="'+register_c_guid+'"/></div>';

  methodd += '<div class="col-md-6"><input type="hidden" class="form-control input-sm" id="register_mapping_guid" value="'+register_mapping_guid+'"/></div>';

  methodd += '<div class="col-md-6"><label>Name</label><input type="text" class="form-control input-sm" id="ven_name" autocomplete="off" required="true" value="'+ven_name+'"/></div>';

  methodd += '<div class="col-md-6"><label>Designation</label><input type="text" class="form-control input-sm" id="ven_designation" autocomplete="off" required="true" value="'+ven_designation+'"/></div>';

  methodd += '<div class="col-md-6"><label>Phone No</label><input type="text" class="form-control input-sm" id="ven_phone" autocomplete="off" required="true" value="'+ven_phone+'"/></div>';

  methodd += '<div class="col-md-6"><label>Email Address</label><input type="email" class="form-control " id="ven_email" autocomplete="off" required="true" value="'+ven_email+'"/></div>'

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
          url:"<?php echo site_url('Supplier_registration/edit_vendor_info_vens');?>",
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
    url:"<?php echo site_url('Supplier_registration/active_status_vendor');?>",
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