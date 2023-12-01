<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">B2B Monthly Billing Invoices</h3><br>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-12">
            <div class="row">
                <div class="col-md-2"><b>Doc Period <br>(YYYY-MM)</b></div>
                  <div class="col-md-2">
                    <select class="form-control" name="period" id="period">

                      <option value="All">All</option>
                      <?php foreach($period_list->result() as $row) { ?>
                      <option 

                      <?php

                      if(isset($_REQUEST['period']))
                      {

                         if ($_REQUEST['period'] == $row->period_code ) {
                          echo 'selected';
                        } 
                      }


                      ?>

                       value="<?php echo $row->period_code ?>"><?php echo $row->period_code ?> </option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                   <button  id="javascript_para" class="btn btn-primary" style="margin-top: 5px;"  
                      onclick="ahsheng()" >Submit</button><br>
                        <script>
                            function ahsheng() 
                                {
                                  
                                 location.href = '<?php echo site_url('b2b_billing_invoice_controller/invoices_detail') ?>?type=<?php echo $_REQUEST['type'] ?>&period='+$('#period').val();
                                }
                        </script> 
                  </div>
            </div>
        </div>
      </div>
    </div>
  </div>  
</div>
  <!-- filter end -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B Monthly Billing Invoices</h3> &nbsp;



          <?php 

          if(isset($_REQUEST['period']))
            {

          if ($_REQUEST['period'] != ''  ) { ?>

          <span class="pill_button"><?php 


          echo $_REQUEST['period'];  ?>
            

          </span>

        <?php } 

        } else { ?>

          <span class="pill_button"><?php 


          echo $period.'(Current Month)';  ?>
            

          </span>

        <?php } ?>





          <br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
          <div class="box-body" style="overflow-x:auto">

            <table class="table table-bordered table-striped dataTable" id="ttable"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;" >
              <thead style="white-space: nowrap;">
                      <tr>
                          <th >Supplier Name</th> 
                          <th >Reg No</th> 
                          <th >Customer Name</th>
                          <th >Year-Month</th>
                          <th >Document Type</th> 
                          <th >Count</th> 
                      </tr>
              </thead>
              <tbody> 

                      <?php foreach ($detail_list->result() as $value) { ?>
                      <tr>
   
                      <td><?php echo $value->supplier_name ?></td>
                      <td><?php echo $value->reg_no ?></td>
                      <td> <?php echo $value->acc_doc_name ?> </td>
                      <td><?php echo $value->period_code ?></td>
                      <td><?php echo $value->doc_type ?></td> 
                      <td>
                        <a class="doc_count_detail" style="cursor: pointer;" 
                        supplier_guid="<?php echo  $value->supplier_guid;?>" 
                        doc_type="<?php echo  $value->doc_type;?>" 
                        customer_guid="<?php echo  $value->customer_guid;?>"
                        period_code="<?php echo  $value->period_code;?>" >
                        <?php echo $value->doc_count ?>
                        </a>
                      </td> 
                      



                      </tr>
                      <?php } ?>


              </tbody>

          <!--         <tfoot>
                   <tr>
                      <td colspan="7">
                                          Select:&nbsp;
                          <a id="selectAll" href="#ckb">All</a>&nbsp;&nbsp;
                          <a id="selectNone" href="#ckb">None</a>&nbsp;&nbsp;
                          <a id="selectToggle" href="#ckb">Toggle</a>&nbsp;&nbsp;
                                      </td>
                   </tr>
                  </tfoot> -->
          </table>
              

          </div>
      </div>
    </div>
  </div>

</div>
</div>

<script>
  $(document).ready(function () {    
    $('#ttable').DataTable({
      "columnDefs": [{}],
      'paging'      : true,
      'lengthChange': true,
      'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      <?php if ($_SESSION['user_group_name'] == "SUPER_ADMIN") { ?>
      'order'       : [ [1 , 'desc'] ],
      <?php } else { ?>
      'order'       : [ [0 , 'desc'] ],
      <?php } ?>
      'info'        : true,
      'autoWidth'   : true,
      <?php if ($_SESSION['user_group_name'] == "SUPER_ADMIN") { ?>
        dom: 'Bfrtip',
      <?php }else { ?>
        dom: 'frtip',
      <?php } ?>
    })
  })
</script>

<div class="modal fade" id="doc_count_details">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
          <h4>Ref No</h4>
        
      </div>
      <div class="modal-body">
          <p id="doc_count_details_body">
            


          </p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>

<script type="text/javascript">
  
$(".doc_count_detail").click(function(){

  supplier_guid = $(this).attr('supplier_guid')

  period_code = $(this).attr('period_code')

  customer_guid = $(this).attr('customer_guid')

  doc_type = $(this).attr('doc_type')

  modal = $('#doc_count_details').modal()

  modal.find('#doc_count_details_body').html('Loading..');

  $.ajax({
    url : "<?php echo site_url('b2b_billing_invoice_controller/doc_count_details'); ?>",
    data : {supplier_guid:supplier_guid,period_code:period_code,doc_type:doc_type,customer_guid:customer_guid},

    beforeSend : function() {
    
    },
    complete: function() {
    
    },        

    success : function(result){
      
    json = JSON.parse(result);

    //alert(JSON.stringify(result[i]));return;
    html = "";
    if(doc_type == 'Accounting Document')
    {
      html += '<table class="table" id="tttable"><thead><th>Doc Number</th><th>Doc Type</th><th>Posted At</th></thead>';
    }
    else if(doc_type == 'Archived Doc')
    {
      html += '<table class="table" id="tttable"><thead><th>Doc Number</th><th>Doc Type</th><th>Posted At</th></thead>';
    }
    else if(doc_type == 'External Doc')
    {
      html += '<table class="table" id="tttable"><thead><th>Doc Number</th><th>Doc Type</th><th>Posted At</th></thead>';
    }
    else if(doc_type == 'Other Doc')
    {
      html += '<table class="table" id="tttable"><thead><th>Doc Number</th><th>Doc Type</th><th>Posted At</th></thead>';
    }
    else
    {
      html += '<table class="table" id="tttable"><thead><th>Doc Number</th><th>Outlet</th><th>Posted At</th></thead>';
    }

    html += '<tbody>';
    for(i = 0; i < json.length; i++)
    {
      html += '<tr><td>'+json[i].refno+'</td><td>'+json[i].loc_group+'</td><td>'+json[i].postdatetime+'</td></tr>'

    }
    html += '</tbody>'

    modal.find('#doc_count_details_body').html(html);


    $(document).ready(function () {    
      $('#tttable').DataTable({
        "columnDefs": [],
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [ [1 , 'desc'] ],
        'info'        : true,
        <?php if ($_SESSION['user_group_name'] == "SUPER_ADMIN") { ?>
        dom: 'Bfrtip',
        <?php }else { ?>
        dom: 'Bfrtip',
        <?php } ?>
      })
    })


    }
  });

});

</script>