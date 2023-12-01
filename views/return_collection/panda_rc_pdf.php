<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
  <?php
  if($this->session->userdata('message'))
  {
    ?>
    <div class="alert alert-success text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>

  <?php
  if($this->session->userdata('warning'))
  {
    ?>
    <div class="alert alert-danger text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('warning') <> '' ? $this->session->userdata('warning') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>
<div class="col-md-12">
       <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?> ">
          <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>
        <?php 
        if($uploaded_image == '1')
        {
          ?>
          <a class="btn btn-app" id="view_image" style="float:right";>
          <i class="fa fa-file-image-o"></i> Image
          </a>
          <?php
        }
        ?>
       <!--  <a class="btn btn-app" href="<?php echo $edit_url ?>">
          <i class="fa fa-pencil"></i> Propose Cost Change
        </a> -->
<?php if(in_array('VRB', $_SESSION['module_code'])) { 
  if($get_current_status == '0' && $set_disabled == '0' ) { ?>
        <button title="Confirm"  onclick="confirm_modal2('<?php echo site_url('general/confirm'); ?>?refno=<?php echo $_REQUEST['refno'] ?>&customer_guid=<?php echo $_SESSION['customer_guid'] ?>&table=return_collection&col_guid=refno&loc=<?php echo $_REQUEST['loc'] ?>')" 
          type="button" class="btn btn-app" style="color:#008D4C"  data-toggle="modal" data-target="#confirm_gr" data-name="<?php echo $_REQUEST['refno'] ?>"
          <?php if(isset($_REQUEST['edit'])) { echo 'disabled'; } ?>
           >
                        <i class="fa fa-check"></i>Accept
        </button>
        <!--  <button title="Reject"  onclick="reject_modal2('<?php echo site_url('general/reject_via_type'); ?>?refno=<?php echo $_REQUEST['refno'] ?>&customer_guid=<?php echo $_SESSION['customer_guid'] ?>&table=return_collection&col_guid=refno&loc=<?php echo $_REQUEST['loc'] ?>')" 
          type="button" class="btn btn-app" style="color:#D44950"  data-toggle="modal" data-target="#confirm_gr" data-name="<?php echo $_REQUEST['refno'] ?>"
          <?php if(isset($_REQUEST['edit'])) { echo 'disabled'; } ?>
           >
                        <i class="fa fa-times"></i>Reject
        </button> -->
         <!-- <button title="reject" onclick="reject_modal()" type="button" class="btn btn-app"  data-toggle="modal" data-target="#reject_rc" style="color:#D73925"
                          data-refno="<?php echo $_REQUEST['refno'] ?>"
                          data-customer_guid="<?php echo $_SESSION['customer_guid'] ?>"
                          data-table="<?php echo 'dbnote_batch' ?>"
                          data-col_guid="<?php echo 'refno' ?>"
                          data-loc="<?php echo $_REQUEST['loc'] ?>"
                          data-name="<?php echo $_REQUEST['refno']?>"
                           > <i class="fa fa-times"></i>Reject</button> -->

<?php } } ?>
  </div> 
          <!--  panel 2 -->
    <div class="row">
      <div class="col-md-12">
          <div class="box box-default  ">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo 'Item Detail'; ?></h3> 
               
              <div class="box-tools pull-right " style="display: inline-flex;">
              <?php if($hide_button == '0') { ?>
              <button class="btn btn-xs btn-success" onclick="$('#formSBNC').submit()"   ><i class="glyphicon glyphicon-floppy-saved"></i>  Submit </button><br>
            <?php } ?>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
      <div class="box-body">
        <center><div class="col-12"><h4><b style="color: red">Please liaise with RETAILER if disagree with quantity and cost of the item to be returned</b></h4></div></center>
            <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 
              <form method="post" action="<?php echo site_url('panda_return_collection/supplier_check?refno='); ?><?php echo $_REQUEST['refno'] ?>" id="formSBNC" name="formSBNC" >
                 <div style="overflow-x:auto;">
                    <table id="smstable" class="tablesorter table table-striped table-bordered table-hover"> 
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th>Itemcode/<br>Barcode</th> 
                            <th>Description</th>
                            <th>PS / Ctn Qty</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                            <th>Amount</th>
                            <th>Reason</th>
                           <!--  <th style="color:red"><input type="checkbox" onClick="selectall_activate(this);" />Supplier Check  </th> -->
                          </tr>
                        </thead>
                        <tbody>
                        <?php 
                        foreach($check_child as $row => $value)
                        {
                          ?>
                           
                            <tr>
                              <td><?php echo $value['line']?>
                                <input type="hidden" name="line[]" value="<?php echo $value['line'] ?>">
                              </td>
                              <td><?php echo $value['itemcode']?>/<br><?php echo $value['barcode']?>
                                <input type="hidden" name="itemcode[]" value="<?php echo $value['itemcode'] ?>">
                                <input type="hidden" name="barcode[]" value="<?php echo $value['barcode'] ?>">
                              </td>
                             
                              <td><?php echo $value['description']?>
                                <input type="hidden" name="description[]" value="<?php echo $value['description'] ?>">
                              </td>
                              <td><?php echo $value['packsize']; ?>
                                <input type="hidden" name="packsize[]" value="<?php echo $value['packsize'] ?>">
                              </td>
                             
                              <td><?php echo $value['qty'];echo ' '; echo $value['um'];?>
                                <input type="hidden" name="qty[]" value="<?php echo $value['qty'] ?>">
                                <input type="hidden" name="um[]" value="<?php echo $value['um'] ?>">
                              </td>
                             
                              <td style="text-align:right"><?php echo number_format($value['input_cost'],2); ?>
                                <input type="hidden" name="input_cost[]" value="<?php echo $value['input_cost'] ?>"> <br>
                                <input type="<?php echo $hidden_text ?>" step="any" style="text-align:right" name="proposed_lastcost[]" 
                                  value="<?php echo number_format($value['input_cost'],2) ?>" onclick="select()" > 
                              </td>
                              <td style="text-align:right">
                                <?php echo number_format($value['qty']*$value['input_cost'],2); ?>
                              </td>
                              <td><?php echo $value['reason']; ?>
                                <input type="hidden" name="reason[]" value="<?php echo $value['reason'] ?>">
                              </td>
                            <!--  <td>
                                <input type="checkbox" name="supcheck[]" class="ahshengcheckbox"  value='1'  >
                                <input type='hidden' name='supcheck2[]' class="hiddencheckbox" value='0'> 
                              </td>  -->
                            </tr>
                          <?php
                        }    
                        ?>
                          <input type='hidden' name='h_refno' id="h_refno"   value="<?php echo $_REQUEST['refno'] ?>"> 
                          <input type='hidden' name='location' id="location"   value="<?php echo $_REQUEST['loc'] ?>"> 
                        </tbody>
                      </table>
                    </div> 
                </div>
            </div>
          </div>
        </div>
      </div>  


<div class="row">
    <div class="col-md-12">
      <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Stock Return Batch</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
        <div class="box-body" id="acc_concepts">
          <div id="accconceptCheck">
            <?php 
            if($request_link != '')
            {
              ?>
              <embed id="embed" height="750px" width="100%" src="<?= $request_link; ?>"></embed>
              <?php
            }
            else
            {
              ?>
              <embed id="embed" height="750px" width="100%" src="<?php echo site_url('Panda_return_collection/show_report').'?report_type=RB&guid='.$stock_guid;?>"></embed>
              <?php
            }
            ?>
          </div>
        </div><!--close sccconcept--> 
      </div><!--close success--> 
    </div>
  </div>

<?php 
if(in_array('UNKNOWNNN', $_SESSION['module_code']))
{
  ?>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title"><?php  echo $title; ?> -  HQ PDF</h3><br>
              <!-- <?php echo $title_accno ?> -->
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
            </div>
          </div>
        <div class="box-body">
          <div class="col-md-12">
              <div class="col-md-12"  style="overflow-x:auto"> 
                  <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 
                  <embed id="embed" height="750px" width="100%" src="<?php echo site_url('Panda_return_collection/show_report').'?report_type=RB&guid='.$stock_guid;?>"></embed>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>

<?php
if($print_image == 1)
{
?>
<div class="row">
    <div class="col-md-12">
      <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Stock Return Batch Image(click on image to download)</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div><!-- close box header -->
          <div class="box-body" id="image">
            <div id="accconceptCheck">
              <?php
              foreach($dis_path_show_array as $img)
              {
                // echo $img->path;
              ?>
              <!-- <div id="accconceptCheck" style="height:50px;width:10px;border:1px solid black;"> -->
                <a download="<?php echo $img->download_name;?>" href="<?php echo $img->base_64; ?>"><img src="<?php echo $img->base_64; ?>" alt="<?php echo $img->name;?>"  style="height:500px;width:40%;border:1px solid black;"></a>
                <!-- <a download="<?php echo $img->name;?>" href="<?php echo $img->base_64; ?>">Download</a><br> -->
              <!-- </div> -->
              <?php
              }
              ?>

            </div>
          </div><!--close image div--> 
      </div><!--close success--> 
    </div><!--close col-md-12--> 
</div><!--close row--> 
<?php
}
?>
</div>
</div>
<script type="text/javascript">
  function selectall_activate(source) {  
    activate = document.getElementsByName('supcheck[]');

    if(source.checked)
    {
      var valieber = '1';

    }else
    {
       var valieber = '0';
    }
    for(var i=0, n=activate.length;i<n;i++) {
      activate[i].checked = source.checked;
        $('.hiddencheckbox').eq(i).val(valieber); 
    }
  }
</script>
<script type="text/javascript">
  $(document).ready(function(){
     $('input[type=checkbox]').attr('checked',false);
    $( ".ahshengcheckbox" ).click(function() {
    var indes = $(".ahshengcheckbox").index(this);
    if($(this).is(':checked'))
    {
       $('.hiddencheckbox').eq(indes).val('1');
    }
    else
    {
       $('.hiddencheckbox').eq(indes).val(0);
    }
  
    });  
  })
  setTimeout(function(){
   window.location.reload(1);
}, 300000);
</script>
<script type="text/javascript">
  function confirm_modal2(confirm_url)
  {
    $('#confirm_gr').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm Collection ' + button.data('name') + '?')
    document.getElementById('url_confirm').setAttribute("href" , confirm_url );
    });
  }

   function reject_modal2(confirm_url)
  {
    $('#confirm_gr').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Reject Collection ' + button.data('name') + '?')
    document.getElementById('url_confirm').setAttribute("href" , confirm_url );
    });
  }
</script>
 <script type="text/javascript">
  function reject_modal()
  {
    $('#reject_rc').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal_detail').text('Confirm Reject Document ' + button.data('name') + '?')
      modal.find('[name="refno"]').val(button.data('refno'))
      modal.find('[name="customer_guid"]').val(button.data('customer_guid'))
      modal.find('[name="table"]').val(button.data('table'))
      modal.find('[name="col_guid"]').val(button.data('col_guid'))
      modal.find('[name="loc"]').val(button.data('loc'))
      modal.find('[name="name"]').val(button.data('name'))
  
    });
  }
</script>

<script>
$(document).ready(function () {    
  setTimeout(function(){
    $('#large-modal').attr({"data-backdrop":"static","data-keyboard":"false"}); //to remove clicking outside modal for closing
  },300);

  $(document).on('click','#view_image',function(){
    var refno = "<?php echo $_REQUEST['refno'];?>";
    var period_code = "<?php echo $_REQUEST['pc'];?>";
    var image_type = 'STRB'

	  //alert(image_type); die;
    if((refno == '') || (refno == null) || (refno == 'null'))
    {
      alert('Invalid Get STRB RefNo.');
      return;
    }

    if((period_code == '') || (period_code == null) || (period_code == 'null'))
    {
      alert('Invalid Get Period Code.');
      return;
    }

    if((image_type == '') || (image_type == null) || (image_type == 'null'))
    {
      alert('Invalid Get Image Details.');
      return;
    }

    $.ajax({
        url:"<?php echo site_url('Panda_return_collection/strb_view_image') ?>",
        method:"POST",
        data:{refno:refno,period_code:period_code,image_type:image_type},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            var url = json.file_path_list;
            var name = url.toString().substring(url.lastIndexOf('/') + 1);
            
            var modal = $("#large-modal").modal();

            modal.find('.modal-title').html( 'STRB RefNo : <b>' + refno + '</b>');

            methodd = '';

            //methodd +='<div class="col-md-12">';

            //methodd += '<input type="hidden" class="form-control input-sm" id="supplier_guid" value="'+supplier_guid+'" readonly/>';

            Object.keys(url).forEach(function(key) {

              var before_name = url[key].toString().split('?')[0];
              var after_name = before_name.toString().split("/").slice(-1)[0];
              
              methodd += '<div class="col-md-12"><label>'+after_name+'</label>';

              methodd += '<input style="float:right;" type="button" id="show'+key+'" class="btn btn-primary view_image_btn" value="Show" path_url="'+url[key]+'" key="'+key+'">';

              methodd += '<input style="float:right;margin-right:5px;" type="button" id="show'+key+'" class="btn btn-warning dl_image_btn" value="Download" path_url="'+url[key]+'" key="'+key+'">';

              methodd += '</div>';

              methodd += '<div class="clearfix"></div><br>';

              methodd += '<div class="col-md-12"><span id="image'+key+'"></span></div>';

            });

            //methodd += '</div>';

            methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);

            setTimeout(function(){

              $(document).off('click', '.view_image_btn').on('click', '.view_image_btn', function(){
                var path_url = $(this).attr('path_url');
                var key = $(this).attr('key');
                var value = $(this).val();
                $(this).hide();
                
                if(value == 'Show') 
                {
                  //$(this).attr('value','Hide');
                  //$(this).attr('class','btn btn-danger view_image_btn');
                  $('#image'+key+'').html('<embed src="'+path_url+'" width="100%" height="800px" style="border: none;" toolbar="0" id="image_view'+key+'"/>');
                  //alert('show image'); die;
                }
                
                // if(value == 'Hide') 
                // {
                //   $(this).attr('value','Show');
                //   $(this).attr('class','btn btn-primary view_image_btn');
                //   $('#image'+key+'').html('');
                //   //alert('show image'); die;
                // }
              });//close modal create

              $(document).off('click', '.dl_image_btn').on('click', '.dl_image_btn', function(){
                var path_url = $(this).attr('path_url');
                var value = $(this).val();
                
                if(value == 'Download') 
                {
                  var form = document.createElement('a');
                  form.href = path_url;
                  form.download = path_url;
                  document.body.appendChild(form);
                  form.click();
                  alert('Download Successful'); 
                  $(this).attr('value','Download Complete');
                  $(this).prop('disabled', true);
                } 
              });//close modal create
            },300);
          }//close else
        }//close success
      });//close ajax 
  });//close image process
});
</script>
