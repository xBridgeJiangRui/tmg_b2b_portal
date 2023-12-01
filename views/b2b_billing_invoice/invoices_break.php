<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">B2B Monthly Billing Invoices Break</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
          <div class="box-body">

            <table class="table table-bordered table-striped dataTable" id="ttable"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
              <thead style="white-space: nowrap;">
                      <tr>
                        <?php if ($_SESSION['user_group_name'] == "SUPER_ADMIN") { ?>
                        <th >Name</th> 
                        <?php } ?>
                          <th >Invoice Number</th>
                          <th >Year-Month</th>
                          <th >Total Amount</th>
                          <?php if ($_SESSION['user_group_name'] == "SUPER_ADMIN") { ?>
                          <th >Invoice Status</th>
                          <?php } ?>
                          <th >Created At</th>
                      </tr>
              </thead>
              <tbody> 

                      <?php foreach ($invoice->result() as $value) { ?>
                      <tr>
                      <?php if ($_SESSION['user_group_name'] == "SUPER_ADMIN") { ?>
                      <td><?php echo $value->name ?></td>
                      <?php } ?>
                      <td> 
                        <a target="framename" href="<?php echo site_url('b2b_billing_invoice_controller/invoices_process_break?g=');?><?php echo $value->inv_guid;?>"> <?php echo $value->invoice_number ?>
                        </a> 
                      </td>
                      <td><?php echo $value->period_code ?></td>
                      <td><?php echo $value->final_amount ?></td>
                      <?php if ($_SESSION['user_group_name'] == "SUPER_ADMIN") { ?>
                      <td><?php echo $value->inv_status ?></td>
                      <?php } ?>
                      <td><?php echo $value->created_at ?></td>



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

