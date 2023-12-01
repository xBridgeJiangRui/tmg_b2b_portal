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
       <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?>&status=<?php echo $_SESSION['check_status']; ?>">
          <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>

        <?php if($paybyinvoice_got_grda == '0' && $accpt_gr_status != 'Confirmed' && $accpt_gr_status != 'confirmed')//pay by invoice
        {
          $einv_filename =ltrim($xcheck_einv_filepath, '/');
          if(file_exists($einv_filename))
          { 
        ?>
<!--             <button title="Confirm"  onclick="confirm_modal2('<?php echo site_url('general/confirm'); ?>?refno=<?php echo $_REQUEST['trans'] ?>&customer_guid=<?php echo $_SESSION['customer_guid'] ?>&table=grmain&col_guid=refno&loc=<?php echo $_REQUEST['loc'] ?>')" 
              type="button" class="btn btn-app" style="color:#008D4C"  data-toggle="modal" data-target="#confirm_gr" data-name="<?php echo $_REQUEST['trans'] ?>" >
                            <i class="fa fa-check"></i>Confirm GR
            </button> -->
       <?php
          }
        }
        ?>

        <?php if($paybyinvoice_got_grda == '1' && $get_DN_detail->num_rows() > 0 && $accpt_gr_status != 'Confirmed' && $accpt_gr_status != 'confirmed')//pay by invoice
        {
          $ecn_status = '';
          foreach($get_DN_detail->result() as $row2)
          {
            if($row2->ecn_guid == 'Pending') 
            {
              //echo 'Generates e-CN';
              $ecn_status .= '1';
            }
            // if($row2->ecn_guid != 'Pending' && $row->posted == '0') 
            // {
            //   echo 'Regenerate e-CN';
            // }
            // if($row2->ecn_guid != 'Pending') 
            // {
            //   echo 'View E-CN';
            // }
          }
          if($ecn_status == '' || $ecn_status == null)
          {
          ?>
<!--           <button title="Confirm"  onclick="confirm_modal2('<?php echo site_url('general/confirm'); ?>?refno=<?php echo $_REQUEST['trans'] ?>&customer_guid=<?php echo $_SESSION['customer_guid'] ?>&table=grmain&col_guid=refno&loc=<?php echo $_REQUEST['loc'] ?>')" 
          type="button" class="btn btn-app" style="color:#008D4C"  data-toggle="modal" data-target="#confirm_gr" data-name="<?php echo $_REQUEST['trans'] ?>" >
                        <i class="fa fa-check"></i>Confirm GR
        </button> -->
          <?php
          }
        }
        ?>

        <?php if($_SESSION['frommodule'] == 'panda_gr' &&$get_DN_detail->num_rows() == 0 && $paybyinvoice_got_grda == '1' && $accpt_gr_status != 'Confirmed' && $accpt_gr_status != 'confirmed') 
          { 
        ?>
<!--         <button title="Confirm"  onclick="confirm_modal2('<?php echo site_url('general/confirm'); ?>?refno=<?php echo $_REQUEST['trans'] ?>&customer_guid=<?php echo $_SESSION['customer_guid'] ?>&table=grmain&col_guid=refno&loc=<?php echo $_REQUEST['loc'] ?>')" 
          type="button" class="btn btn-app" style="color:#008D4C"  data-toggle="modal" data-target="#confirm_gr" data-name="<?php echo $_REQUEST['trans'] ?>" >
                        <i class="fa fa-check"></i>Confirm GR
        </button> -->
        <?php 
          } 
        ?>
  </div>

<!-- panel 1 -->
  <div class="row">
  <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo 'Header Detail '; ?></h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
      <div class="box-body">
        <div class="col-md-12">
            <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 
                  <div style="overflow-x:auto;">
                    <table id="einvoice_header" class="tablesorter table table-striped table-bordered table-hover" style="font-size:12px;width:100%"> 
                      <form method="post" action="<?php echo site_url('panda_gr/edit_gr_header?refno='.$_REQUEST['trans'].'&loc='.$_REQUEST['loc'] ); ?>" id="form_EGRH" name="form_EGRH" >
                        <thead>
                        <tr>
                          <!-- <th>Refno</th> -->
                          <th style="width:12%">Supplier Invoice No</th>
                          <th style="width:14%">Supplier Delivery Order No</th>
                          <th style="width:10%">Supplier Inv/DO Date</th>
                          <!-- <th>GR Date</th> -->
                          <th style="width:8%">Amount Exc Tax</th>
                          <th style="width:3%">Tax</th>
                          <th style="width:8%">Amount Inc Tax</th>
                          <th style="width:12%">E-Invoice No</th>
                          <th style="width:8%">E-Invoice Date</th>
                          <th style="width:13%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $h = 0;
                        foreach($check_header->result() as $row)
                        {
                          $h++;
                          if($check_header->row('status') == 'Invoice Generated') {
                            $e_readonly = 'readonly';
                          }
                          else
                          {
                            $e_readonly = '';
                          }
                          ?>
                            <tr einvno="<?php echo $row->einvno;?>" einv_date="<?php echo $row->einv_date;?>">
                            <!-- <td><?php echo $row->RefNo?> -->
                            <input type="hidden" name="line[]" value="<?php echo $h?>">
                            <!-- </td> -->
                            <td data-toggle="tooltip" data-placement="bottom" title="<?php echo $row->ori_inv_no?>">
                            <input class="form-control" type="text" readonly name="invno" value="<?php echo $row->ori_inv_no?>">
                            <input  class="form-control" type="<?php echo $hidden_text ?>" name="ext_invno[]" value="<?php echo $row->ori_inv_no?>" autocomplete="off">
                            </td>

                            <td data-toggle="tooltip" data-placement="bottom" title="<?php echo $row->DONo?>">
                            <input class="form-control" type="text" readonly name="dono" value="<?php echo $row->DONo?>">
                            <input class="form-control" type="<?php echo $hidden_text ?>" name="ext_dono[]" value="<?php echo $row->DONo?>" autocomplete="off"> 
                            </td>

                            <td  data-toggle="tooltip" data-placement="bottom" title="<?php echo $row->DocDate?>">
                            <input class="form-control" type="text" readonly name="docdate[]" value="<?php echo $row->DocDate?>"> 
                            <input class="form-control" type="<?php echo $hidden_text ?>" name="ext_docdate[]" placeholder="YYYY-MM-DD" value="<?php echo $row->DocDate?>" autocomplete="off"> 
                            </td> 

                            <!-- <td data-toggle="tooltip" data-placement="bottom" title="<?php echo $row->GRDate?>"> -->
                            <!-- <input class="form-control" type="text" readonly name="grdate" value="<?php echo $row->GRDate ?>"> -->
                            <!-- <input class="form-control" type="hidden" name="ext_grdate[]" value="">  -->
                            <!-- </td> -->

                            <td style="text-align: right">
                              <?php echo number_format($row->total_include_tax,2)?></td>
                            <td style="text-align: right"><?php echo number_format($row->gst_tax_sum,2)?></td>
                            <td style="text-align: right"><?php echo number_format($row->total_include_tax,2)?></td>

                            <td data-toggle="tooltip" data-placement="bottom" title="<?php echo $row->einvno?>">
                            <input id="e_einvno" <?php echo $e_readonly;?> class="form-control change_value" type="text" name="einvno[]" value="<?php echo $row->einvno?>" autocomplete="off">
                            </td>
                            <td  data-toggle="tooltip" data-placement="bottom" title="<?php echo $row->einv_date?>">
                            <?php if($check_header->row('status') == 'Invoice Generated')
                            {
                            ;?>
                              <input id="e_einvdate" <?php echo $e_readonly;?> class="form-control change_value" type="text" name="einv_dates[]" placeholder="YYYY-MM-DD" value="<?php echo $row->einv_date?>" autocomplete="off">
                            <?php
                            }
                            else
                            {
                            ?>
                              <input id="e_einvdate" class="form-control change_value" type="text" name="einv_date[]" placeholder="YYYY-MM-DD" value="<?php echo $backdate?>" autocomplete="off" readonly> 
                            <?php
                            }
                            ?>
                            </td>
                            <td>
                              <?php if($check_header->row('status') == '' || $check_header->row('status') == 'viewed' || $check_header->row('status') == 'printed') { ?>
                              <button id="save_einvoice_row" type="button" class="btn btn-xs btn-primary" disabled ><i class="glyphicon glyphicon-edit"></i> Save</button>
                              <?php } ?>
                            </td>
                            </tr>
                          <?php
                        }
                        ?>
                        </tbody>
                          <input type="hidden" name="header_refno" value="<?php echo $_REQUEST['trans']?>">  
                          <input type="hidden" name="header_loc" value="<?php echo $_REQUEST['loc']?>">                                
                      </form>
                    </table>

                    <table id="einvoice_display" class="tablesorter table table-striped table-bordered table-hover" style="font-size:12px;width:100%"> 
                      <form method="post" action="<?php echo site_url('panda_gr/edit_gr_header?refno='.$_REQUEST['trans'].'&loc='.$_REQUEST['loc'] ); ?>" id="form_EGRH" name="form_EGRH" >
                        <thead>
                        <tr>
                          <th style="width:12%">GRN Supplier Copy</th>
                          <th style="width:14%">GRN Refno</th>
                          <!-- <th>Do No</th> -->
                          <th style="width:10%">GRN Date</th>
                          <th style="width:8%">Amount Exc Tax</th>
                          <th style="width:3%">Tax</th>
                          <th style="width:8%">Amount Inc Tax</th>
                          <th style="width:9%">Rounding Adjustment</th>
                          <?php if($_SESSION['customer_guid'] == '13EE932D98EB11EAB05B000D3AA2838A')
                          {
                            ?>
                            <th style="width:5%">Term</th>
                            <th style="width:7%">Due Date</th>
                            <th style="width:3%;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;">Amount Inc Tax</th>
                            <th style="width:3%;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;">Amount Inc Tax</th>
                            <th style="width:3%;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;">Amount Inc Tax</th>
                            <?php
                          }
                          else
                          {
                            ?>
                            <th style="width:8%;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;">Amount Inc Tax</th>
                            <th style="width:9%;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;">Amount Inc Tax</th>
                            <th style="width:5%;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;">Amount Inc Tax</th>
                          <!-- <th>Action</th> -->
                            <?php
                          }
                          ?>

                          
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $h = 0;
                        foreach($check_header->result() as $row)
                        {
                          $h++;
                          if($check_header->row('status') == 'Invoice Generated') {
                            $e_readonly = 'readonly';
                          }
                          else
                          {
                            $e_readonly = '';
                          }
                          ?>
                          <tr einvno="<?php echo $row->einvno;?>" einv_date="<?php echo $row->einv_date;?>" >
                            <td><?php echo $row->cross_ref?>
                            <input type="hidden" name="line[]" value="<?php echo $h?>">
                            <input id="e_gr_refno" type="hidden" value="<?php echo $row->RefNo?>">
                            </td>
                            <td data-toggle="tooltip" data-placement="bottom" title="<?php echo $row->RefNo?>">
                            <?php echo $row->RefNo?>
                            </td>
                            <td  data-toggle="tooltip" data-placement="bottom" title="<?php echo $row->GRDate?>">
                            <?php echo $row->GRDate?>
                            </td> 

                            <td style="text-align: right">
                              <?php echo number_format($row->after_amount,2)?></td>
                            <td style="text-align: right"><?php echo number_format($row->gst_tax_sum,2)?></td>
                            <td style="text-align: right"><?php echo number_format($row->after_amount,2)?></td>
                            <td style="text-align: right"><?php echo number_format($row->rounding_adj,2) ?></td>
                            <?php if($_SESSION['customer_guid'] == '13EE932D98EB11EAB05B000D3AA2838A')
                            {
                              ?>
                              <td style="text-align: center"><?php echo $row->term ?></td>
                              <td style="text-align: right;white-space: nowrap;"><?php echo $row->duedate?></td>
                              <td style="text-align: right;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;"><?php echo number_format($row->Total,2)?></td>
                              <td style="text-align: right;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;"><?php echo number_format($row->Total,2)?></td>
                              <td style="text-align: right;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;"><?php echo number_format($row->Total,2)?></td>
                              <?php
                            }
                            else
                            {
                              ?>
                              <td style="text-align: right;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;"><?php echo number_format($row->Total,2)?></td>
                              <td style="text-align: right;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;"><?php echo number_format($row->Total,2)?></td>
                              <td style="text-align: right;visibility:hidden;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;"><?php echo number_format($row->Total,2)?></td>
                              <?php
                            }
                            ?>
                            </tr>
                          <?php
                        }
                        ?>
                        </tbody>
                          <input type="hidden" name="header_refno" value="<?php echo $_REQUEST['trans']?>">  
                          <input type="hidden" name="header_loc" value="<?php echo $_REQUEST['loc']?>">                                
                      </form>
                    </table>
                  <!-- original is $paybyinvoice_got_grda == '1' -->
                  <?php if($paybyinvoice_got_grda == '0') { ?>
                    <?php if($get_DN_detail->num_rows() > 0) {
                     ?>
                    <hr style="border: 1px solid #bfb8b8;">
                    <form  method="post" id="form_ECN" name="form_ECN" >
                    <table id="ecn_table" class="tablesorter table table-striped table-bordered table-hover" style="font-size:12px;width:100%"> 
                       <input type="hidden" name="index_no" id="index_no">
                        <thead>
                        <tr>
                          <th style="width:12%">GRDA DN Refno</th>
                          <th style="width:14%">Supplier CN No</th>
                          <th style="width:10%">Supplier CN Date</th>
<!--                           <th>Sup CN No.</th>
                          <th>Sup CN Date</th> -->
                          <!-- <th>Variance Amount</th> -->
                          <th style="width:8%">Amount Exc Tax</th>
                          <th style="width:3%">Tax</th>
                          <th style="width:8%">Amount Incl Tax</th>
                          <th style="width:12%">E-CN No</th>
                          <th style="width:8%">E-CN Date</th>
                          <th style="width:13%;border-right: 1px solid white;">Action</th>
                          <!-- <th style="width:2%;visibility:hidden"></th> -->
                          <?php
                          if($check_upload_grn_cn == 1)
                          {
                          ?>
                          <!-- <th>Uploaded Document</th> -->
                          <?php
                          }
                          ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                          $i = 0;
                        foreach($get_DN_detail->result() as $row)
                        {

                          ?>
                            <tr ext_doc1="<?php echo $row->ext_doc1;?>" ext_date1="<?php echo $row->ext_date1;?>" file_path="<?php echo $row->file_path;?>">
                              <td><?php echo $row->RefNo.'-'.$row->transtype?></td>
                              <td><?php echo $row->sup_cn_no?></td>
                              <td><?php echo $row->sup_cn_date?></td>
<!--                               <td data-toggle="tooltip" data-placement="top"
                                <?php if($row->transtype == 'GQV'){ ?>
                                  title="Variance in Qty"
                                <?php } elseif($row->transtype == 'IAV') { ?> 
                                  title="Variance in Cost"
                                <?php } elseif($row->transtype == 'GRV') { ?> 
                                  title="Rebate Value"
                                <?php } else { } ?> 
                              ><?php echo $row->transtype ?></td> -->
                              <!-- ori $row->posted == '1' -->
                              <!-- <td style="text-align: right"><?php echo number_format($row->VarianceAmt,2)?></td> -->
                              <td style="text-align: right"><?php echo number_format($row->VarianceAmt+$row->gst_tax_sum,2)  ?></td>
                              <td style="text-align: right"><?php echo number_format($row->gst_tax_sum,2)?></td>
                              <td style="text-align: right"><?php echo number_format($row->VarianceAmt+$row->gst_tax_sum,2)  ?></td>
                              <td><input class="form-control change_value" type="text" name="ext_doc1[]" value="<?php echo $row->ext_doc1 ?>" id="ext_sup_cn_no<?php echo $i;?>" required autocomplete="off" <?php if($row->ecn_guid != 'Pending') { echo 'readonly' ;} ?>  ></td>
                              <td>
                              <?php if($row->ecn_guid != 'Pending')
                              {
                              ;?>
                                <input class="form-control change_value" type="text" name="ext_date1s[]" value="<?php echo $row->ext_date1 ?> " required autocomplete="off" readonly>
                              <?php
                              }
                              else
                              {
                              ?>
                                <input class="form-control change_value" type="text" name="ext_date1[]" value="<?php echo $backdate ?> " required autocomplete="off" readonly> 
                              <?php
                              }
                              ?>
                              </td>
                              <!-- <td></td> -->
                              <td colspan="2">
                                <!-- for new and havent generate e_cn -->
                                <?php if($row->ecn_guid == 'Pending') 
                                  {
                                ?>
                                <?php
                                if($row->file_path != '')
                                {
                                ?>
                                          <!-- <button type="button" value="<?php echo $i;?>" class="index_get btn btn-xs btn-success" RefNo="<?php echo $row->RefNo?>" transtype="<?php echo $row->transtype;?>"  >Generates e-CN</button> -->
                                          <button type="button" value="<?php echo $i;?>" class="index_get btn btn-xs btn-primary" RefNo="<?php echo $row->RefNo?>" transtype="<?php echo $row->transtype;?>" disabled ><i class="glyphicon glyphicon-edit"></i> Save</button><br>
                                <?php
                                }
                                else
                                {
                                ?>
                                          <!-- <button type="button" class="btn btn-xs btn-success"  onclick="alert('Please Upload Supplier CN attachment first')"  >Generates e-CN</button> -->
                                          <button type="button" class="btn btn-xs btn-primary"  onclick="alert('Please Upload Supplier CN attachment first')" disabled ><i class="glyphicon glyphicon-edit"></i> Save</button><br>
                                <?php
                                }
                                ;?>

                                <!-- <button type="button" value="<?php echo $i;?>" class="index_get btn btn-xs btn-success" RefNo="<?php echo $row->RefNo?>" transtype="<?php echo $row->transtype;?>"  >Generates e-CN</button> -->
                                <?php
                                if($check_upload_grn_cn == 1)
                                {
                                ?>
                                <button class="btn btn-xs btn-primary" type="button" id="upload_grn_cn_doc" refno="<?php echo $_REQUEST['trans'] ?>"transtype="<?php echo $row->transtype ?>">Upload <br>Supplier<br> CN</button>
                                <?php
                                }
                                ?>

                                <?php } ?>
                                <!-- END generate e_cn -->  
                                <!-- for update e_cn -->
                                <?php if($row->ecn_guid != 'Pending') 
                                  {
                                ?>
                                <?php if($row->ecn_guid != 'Pending') 
                                  {
                                ?>
                                <a target="_blank" href="<?php echo site_url('panda_gr/view_ecn?refno='.$row->RefNo.'&transtype='.$row->transtype) ?>" class="btn btn-xs btn-warning" ><i class="glyphicon glyphicon-eye-open"></i>View E-CN</a>
                                <?php } ?><br>
                                <!-- <button class="btn btn-xs btn-danger" onclick="$('#form_ECN').submit()"> -->
                                <!--                                   <button type="button" value="<?php echo $i;?>" class="index_get btn btn-xs btn-danger" RefNo="<?php echo $row->RefNo?>" transtype="<?php echo $row->transtype;?>"  RefNo="<?php echo $row->RefNo?>" transtype="<?php echo $row->transtype;?>" >
                                Regenerate e-CN</button> -->
                                <?php
                                if($check_upload_grn_cn == 1)
                                {
                                ?>
                                <button class="btn btn-xs btn-primary" type="button" id="upload_grn_cn_doc" refno="<?php echo $_REQUEST['trans'] ?>" transtype="<?php echo $row->transtype ?>">Upload Supplier CN</button><br>
                                <?php
                                }
                                ?>                                
                                <?php } ?>
                                <!-- END generate e_cn -->
                                <!-- for update e_cn -->
                                <!-- <?php if($row->ecn_guid != 'Pending') 
                                  {
                                ?>
                                <a target="_blank" href="<?php echo site_url('panda_gr/view_ecn?refno='.$row->RefNo.'&transtype='.$row->transtype) ?>" class="btn btn-xs btn-warning" ><i class="glyphicon glyphicon-heart"></i>new method E-CN</a>
                                <?php } ?> -->
                                <!-- END generate e_cn -->
                          <input type="hidden" name="gr_refno" value="<?php echo $_REQUEST['trans']?>">  
                          <input type="hidden" name="gr_loc" value="<?php echo $_REQUEST['loc']?>">                                
                          <input type="hidden" name="ecn_refno[]" value="<?php echo $row->RefNo?>"> 
                          <input type="hidden" name="ecn_type[]" value="<?php echo $row->transtype?>">
                          <input type="hidden" name="ecn_varianceamt[]" value="<?php echo $row->VarianceAmt?>"> 
                          <input type="hidden" name="ecn_tax_rate[]" value="<?php echo '0' ?>"> 
                          <input type="hidden" name="ecn_gst_tax_sum[]" value="<?php echo $row->gst_tax_sum?>"> 
                          <input type="hidden" name="ecn_total_incl_tax[]" value="<?php echo $row->VarianceAmt+$row->gst_tax_sum   ?>"> 
                          <input type="hidden" name="ecn_customer_guid[]" value="<?php echo $_SESSION['customer_guid'] ?>">
                          <input type="hidden" name="ecn_loc[]" value="<?php echo $_REQUEST['loc'] ?>"> 
                          
                          <input type="hidden" name="ecn_rows[]" value="<?php echo $row->rowx ?>">
                          <input type="hidden" name="sup_cn_no[]" value="<?php echo $row->rowx ?>">
                          <input type="hidden" name="sup_cn_date[]" value="<?php echo $row->rowx ?>">

                          <?php 
                          if($check_upload_grn_cn == 1)
                          {
                          if($row->file_path != '')
                          {
                          ?>
                          <!-- <a target="_blank" href="<?php echo base_url($row->file_path).'?time='.date("Ymdhs");?>"><button class="btn btn-xs btn-success" type="button"">View Supplier CN</button></a> -->
                          <a target="_blank" href="<?php echo site_url('Upload/view_upload?parameter='.$row->RefNo.'-'.$row->transtype.'&parameter2='.$file_supplier_guid.'&parameter3='.'grn_cn').'&time='.date("Ymdhs");?>"><button class="btn btn-xs btn-success" type="button"">View Supplier CN</button></a>

                          <?php
                          }
                          }
                          ?>                              
                              </td>

                            </tr>
                          <?php
                      $i++;
                    }
                        ?>
                           </form>
                        </tbody>
                    </table>
                  <?php }  // close of first loop
                } // close the paybyinvoice flag
                ?>
                  </div> 
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php //  echo $paybyinvoice_got_grda ?>
<?php // echo var_dump($aaa) ?>
<!--  panel 2 -->
<?php if($paybyinvoice_got_grda == '0' && $open_panel3 == '1'){ ?>
<div class="row" >
  <div class="col-md-12">
      <div class="box box-default <?php echo $open_panel2 ?>">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo 'Item Detail'; ?></h3> 
           
          <div class="box-tools pull-right " style="display: inline-flex;">
            <?php
              if ($child_result_validation >= '1')
              {
                if($get_DN_detail->num_rows() > 0)
                {
                  if($check_ecn_main->num_rows() > 0)
                  {
                    if($H_consign != '1')
                    {
                      ?>
                      <button id="generate_einv" class="btn btn-xs btn-success" ><i class="glyphicon glyphicon-floppy-saved"></i>  Generate E-Invoice</button><br>
                       <!-- onclick="$('#formSBNC').submit()"  -->
                      <?php 
                    }
                  }
                  else
                  {
                    ?>
                      <button id="not_generate_ecn_generate_einv" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-floppy-saved"></i>  Generate E-Invoice & E-CN</button><br>
                    <?php
                  }
                }
                else
                {
                  if($H_consign != '1')
                  {
                  ?>
                  <button id="generate_einv" class="btn btn-xs btn-success" ><i class="glyphicon glyphicon-floppy-saved"></i>  Generate E-Invoice</button><br>
                  <!-- onclick="$('#formSBNC').submit()" -->
                  <?php
                  }
                }
              } 
            ?>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="<?php echo $item_detail_icon ?>"></i></button>
          </div>
        </div>
      <div class="box-body">
            <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 
              <form method="post" action="<?php echo site_url('panda_gr/supplier_check?trans='); ?><?php echo $_REQUEST['trans'] ?>" id="formSBNC" name="formSBNC" >
                 <div style="overflow-x:auto;">
                    <table id="smstable" class="tablesorter table table-striped table-bordered table-hover"> 
                        <thead>
                        <tr>
                          <th>No.</th>
                          <th>Itemcode/<br>barcode</th>
                          <!-- <th>Barcode</th> -->
                          <th>Description</th>
                          <th>PS / Ctn Qty</th>
                          <th>Unit Price Before Disc</th>
                          <!-- requested to close as requestedby Mr.Loo
                            <th>Item Disc Description</th> 
                          -->
                          <th>Item Disc</th>
                          <th>Total Bill Disc Prorated</th>
                          <th>Unit Price After Disc</th>
                          
                          <th>Received Quantity</th>
                          <th>Invoice Qty</th>
                          <th>Invoice Unit Cost</th>
                          <th style="color:red;display:none"><input type="checkbox" onClick="selectall_activate(this);"   />Supplier Check  </th>

                          <th>Total Amount Excl Tax</th>
                          <!-- <th>Total Tax Amount</th> -->
                          <th>Total Amount Incl Tax</th>
<!--                           <th>GRDA Type</th>
                          <th>Variance Amount</th> -->
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if($child_result_validation >= '1')
                        {
                          foreach($check_child as $row => $value)
                          {
                            ?>
                              <tr>
                                <td><?php echo $value['line']?>
                                  <input type="hidden" name="line[]" value="<?php echo $value['line'] ?>">
                                </td>
                                <td><?php echo $value['itemcode']?>
                                  <input type="hidden" name="itemcode[]" value="<?php echo $value['itemcode'] ?>">/<br><?php echo $value['barcode']?>
                                  <input type="hidden" name="barcode[]" value="<?php echo $value['barcode'] ?>">
                                </td>
                                <!-- <td><?php echo $value['barcode']?>
                                  <input type="hidden" name="barcode[]" value="<?php echo $value['barcode'] ?>">
                                </td> -->
                                <td><?php echo $value['description']?>
                                  <input type="hidden" name="description[]" value="<?php echo $value['description'] ?>">
                                </td>
                                <td><?php echo $value['packsize']; ?>
                                  <input type="hidden" name="packsize[]" value="<?php echo $value['packsize'] ?>">
                                </td>
                                <td style="text-align: right"><?php echo  number_format($value['unitprice'],2)?>
                                  <input type="hidden" name="unitprice[]" value="<?php echo $value['unitprice'] ?>">
                                </td>
                                <!--  requested to close
                                <td><?php echo $value['disc_desc']?> -->
                                  <input type="hidden" name="disc_desc[]" value="<?php echo $value['disc_desc'] ?>">
                                <!-- </td>  -->
                              
                                <td style="text-align: right"><?php echo number_format($value['discamt'],2)?>
                                  <input type="hidden" name="discamt[]" value="<?php echo $value['discamt'] ?>">
                                </td>
                                <td style="text-align: right"><?php echo number_format($value['unit_disc_prorate'],2)?>
                                  <input type="hidden" name="unit_disc_prorate[]" value="<?php echo $value['unit_disc_prorate'] ?>">
                                </td>
                                <td style="text-align: right"><?php echo number_format($value['unit_price_bfr_tax'],2)?>
                                  <input type="hidden" name="unit_price_bfr_tax[]" value="<?php echo $value['unit_price_bfr_tax'] ?>">
                                </td>
                                
                                <td><?php echo $value['qty'];echo ' '; echo $value['um'];?>
                                  <?php if($pay_by_invoice == '1')
                                  {
                                    ?>
                                    <input type="hidden" name="qty[]" value="<?php echo $value['inv_qty'] ?>">
                                    <input type="hidden" name="um[]" value="<?php echo $value['um'] ?>">
                                    <?php
                                  }
                                  else
                                  {
                                    ?>
                                    <input type="hidden" name="qty[]" value="<?php echo $value['qty'] ?>">
                                    <input type="hidden" name="um[]" value="<?php echo $value['um'] ?>">
                                    <?php
                                  }
                                  ?>
                                </td>
                                <td><?php echo $value['inv_qty'];echo ' '; echo $value['um'];?></td>
                                <td style="text-align: right"><?php echo number_format($value['inv_unitprice'],2);?></td>
                                <td style="display:none">
                                  <input type="checkbox" name="supcheck[]" class="ahshengcheckbox"  value='1'  >
                                  <input type='hidden' name='supcheck2[]' class="hiddencheckbox" value='0'> 
                                </td>

                                <td style="text-align: right"><?php echo number_format($value['totalprice'],2)?>
                                  <input type="hidden" name="totalprice[]" value="<?php echo $value['totalprice'] ?>"> 
                                </td>
                                <!-- <td style="text-align: right"><?php echo number_format($value['gst_tax_amount'],2)?>-->
                                  <input type="hidden" name="gst_tax_amount[]" value="<?php echo $value['gst_tax_amount'] ?>">
                                </td> 
                                <td style="text-align: right"><?php echo number_format($value['gst_unit_total'],2)?>
                                  <input type="hidden" name="gst_unit_total[]" value="<?php echo $value['gst_unit_total'] ?>">
                                </td>
                                <!--<td style="text-align: right"><?php echo  $value['grda_type'] ?>
                                  
                                </td>
                                <td style="text-align: right"><?php echo  $value['grda_remark']?> -->
                                  
                                </td>
                              </tr>
                            <?php
                        }
                      }
                      else
                      {
                        echo 'Child Data Not Found';
                      }
                        ?>
                        </tbody>
                      </table>
                    </div> 

                    <?php
                        foreach($check_header->result() as $row)
                        {
                          ?>
                            <input type="hidden" name="location" value="<?php echo $_REQUEST['loc'] ?>">
                            <input type="hidden" name="branch_code" value="<?php echo $row->Location ?>">
                              <input type="hidden" name="H_refno" value="<?php echo $row->RefNo?>">
                              <input type="hidden" name="H_invno" value="<?php echo $row->InvNo ?>">
                              <input type="hidden" name="H_dono" value="<?php echo $row->DONo ?>">
                              <input type="hidden" name="H_docdate" value="<?php echo $row->DocDate ?>">
                              <input type="hidden" name="H_grdate" value="<?php echo $row->GRDate?>">
                              <input type="hidden" name="H_total" value="<?php echo $row->Total ?>"> 
                              <input type="hidden" name="H_gst_tax_sum" value="<?php echo $row->gst_tax_sum ?>">
                              <input type="hidden" name="H_total_include_tax" value="<?php echo $row->total_include_tax ?>">
                              <input type="hidden" name="H_subtotal1" value="<?php echo $row->subtotal1 ?>">
                             
                          <?php
                        }
                        ?>
            </div>
        </div>
      </div>
    </div>
  </div> 
<?php } ?>
<!-- panel 3 -->
<?php if($open_panel3 == '0') { ?>
 <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo 'E-Invoice Revise : [';echo $version; echo ']' ?></h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
      <div class="box-body">
        <?php // echo $check_einv_filepath ?>
        <div class="col-md-12" style="height: 500px;">
                 

          <?php 

            $ua = strtolower($_SERVER['HTTP_USER_AGENT']);


            if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) { // && stripos($ua,'mobile') !== false) { ?>

            <!--   <embed src="https://docs.google.com/gview?embedded=true&url=<?php echo $check_einv_filepath; ?>&amp;embedded=true" width="100%"  style="border: none;height:20em"/>  -->

                <embed src="<?php echo site_url('Panda_gr/fetch_e_invoice_pdf?trans=').$_REQUEST['trans']; ?>" width="100%" height="500px" style="border: none;"/> This browser does not support PDFs. Please download the PDF to view it: <a href="<?php echo $check_einv_filepath; ?>">Download PDF</a> 

            <?php  } else { ?>
              
            <!-- <embed src="<?php echo $check_einv_filepath; ?>" width="100%" height="500px" style="border: none;"/> This browser does not support PDFs. Please download the PDF to view it: <a href="<?php echo $check_einv_filepath; ?>">Download PDF</a>  -->
                <embed src="<?php echo site_url('Panda_gr/fetch_e_invoice_pdf?trans=').$_REQUEST['trans']; ?>" width="100%" height="500px" style="border: none;"/> This browser does not support PDFs. Please download the PDF to view it: <a href="<?php echo $check_einv_filepath; ?>">Download PDF</a> 

            <?php } ?>


                
        </div>
      </div>

    </div>
</div>
</div>
 <?php } ?>
<!-- panel 4 -->
<div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php  echo $title; ?></h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
      <div class="box-body">
        <?php if(in_array('IAVAa',$this->session->userdata('module_code')))
        {
          ?>
          <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 
            <div id="accconceptCheck">
              <embed id="embed" height="750px" width="100%" src="<?= $request_link_gr; ?>"></embed>
            </div>
          </div>
          <?php
        }
        else
        {
          ?>
          <div class="col-md-12">
                  <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 

                  <?php 

                  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);


                  if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) {
                  // && stripos($ua,'mobile') !== false) { ?>

                    <embed src="https://docs.google.com/gview?embedded=true&url=<?php echo $filename; ?>&amp;embedded=true" width="100%" style="border: none;height:20em"/> 

                  <?php  } else { ?>

                  <?php if($file_headers[0] != 'HTTP/1.1 404 Not Found') { ?>
                                          <embed src="<?php echo $filename; ?>" width="100%" height="500px" style="border: none;"/> This browser does not support PDFs. Please download the GRN PDF to view it: <a href="<?php echo $filename; ?>">Download PDF</a> 
                                      <?php } else 
                                          {  
                                            echo 'pdf not found'; 
                                          }
                                      ?>


                  <?php } ?>
                      
                  </div>
          </div>
          <?php
        }
        ?>
      </div>

    </div>
</div>
</div>


<?php if($show_grda_pdf == '1'){ ?>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php  echo 'GRDA'; ?></h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
      <div class="box-body">

        <?php if(in_array('IAVAa',$this->session->userdata('module_code')))
        {
          ?>
          <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 
            <div id="accconceptCheck">
              <embed id="embed" height="750px" width="100%" src="<?= $request_link_grda; ?>"></embed>
            </div>
          </div>
          <?php
        }
        else
        {
          ?>
          <div class="col-md-12">
                  <div class="col-md-12"  style="overflow-x:auto;overflow-y:auto"> 


                    <?php 

              $ua = strtolower($_SERVER['HTTP_USER_AGENT']);

              if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) { // && stripos($ua,'mobile') !== false) { ?>

                <embed src="https://docs.google.com/gview?embedded=true&url=<?php echo $grda_filename; ?>&amp;embedded=true" width="100%" style="border: none;height:20em"/> 

              <?php  } else { ?>

              <?php if($grda_file_headers[0] != 'HTTP/1.1 404 Not Found') { ?>
                                      <embed src="<?php echo $grda_filename; ?>" width="100%" height="500px" style="border: none;"/> This browser does not support PDFs. Please download the GRDA PDF to view it: <a href="<?php echo $grda_filename; ?>">Download PDF</a> 
                                  <?php } else 
                                      {  
                                        echo 'pdf not found'; 
                                      }
                                  ?>


              <?php } ?>
                      
                  </div>
          </div>
          <?php
        }
        ?>
      </div>

    </div>
</div>
</div>
<?php } ?>






</div>
</div>

<script type="text/javascript">
  function form_submit(refno , type)
  { 

// var para = [];

//       $('input[name="ext_doc1[]"]').each(function() {

//       if(($(this).val() == '') || ($(this).val() == ' ') || ($(this).val() == null))
//       {  
//         para.push('1');
//       }
//       else
//       {
//         para.push('0');
//       }
//   });

// var uniqueArray = Array.from(new Set(para));

//   if(jQuery.inArray("1", uniqueArray) >= 0)
//   {
//       alert('canot null 1');
//       return;
//   }

// var para = [];

//   $('input[name="ext_date1[]"]').each(function() {
//       if(($(this).val() == '') || ($(this).val() == ' ') || ($(this).val() == null))
//       {  
//         para.push('1');
//       }
//       else
//       {
//         para.push('0');
//       }
//   });


// var uniqueArray = Array.from(new Set(para));

//   if(jQuery.inArray("1", uniqueArray) >= 0)
//   {
//       alert('canot null 2');
//       return;
//   }


    //window.location.reload();
    $("#form_ECN").attr("action", "<?php echo site_url('panda_gr/generate_ecn');?>?refno="+refno+"&transtype="+type);
    $("#form_ECN").attr("target", "_blank");
    $("#form_ECN").submit();
// window.location.reload();

  }

  function confirm_modal2(confirm_url)
  {
    $('#confirm_gr').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

    var modal = $(this)
    modal.find('.modal_detail').text('Confirm GR ' + button.data('name') + '?')
    document.getElementById('url_confirm').setAttribute("href" , confirm_url );
    });
  }

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
   // window.location.reload(true);
}, 30000);
</script>
<script type="text/javascript">
$(function() {
  $('input[name="ext_docdatess[]"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
 /* $(this).find('[name="ext_docdate[]"]').val("");*/
});
$(function() {
  mend = '<?php echo date($gr_back_date);?>';

  // alert(mend);
  $('input[name="einv_datess[]"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    "minDate": mend,
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });
 /* $(this).find('[name="ext_docdate[]"]').val("");*/
});
</script>
<script type="text/javascript">
$(function() {
  mend = '<?php echo date($backdate);?>';
  $('input[name="ext_date1ss[]"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
    "minDate": mend,
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
  });/*
  $(this).find('[name="ext_date1[]"]').val("");*/
});

$(document).ready(function(){

    $(document).on('click','.index_get',function(){

      var index_no = $(this).val();

      $('#index_no').val(index_no);
      var refno = $(this).attr('RefNo');
      var type = $(this).attr('transtype');
      var ext_sup_cn_no = $('#ext_sup_cn_no'+index_no).val();
      // alert(refno+type+'--'+ext_sup_cn_no+'2323');return;
      if(ext_sup_cn_no == '' || ext_sup_cn_no == null)
      {
        alert('Please Insert Supplier CN No');
        return;
      }
      if(refno == '' || refno == null)
      {
        alert('Refno Empty,Please Contact Admin');
        return;
      }
      if(type == '' || type == null)
      {
        alert('Trans Type Empty,Please Contact Admin');
        return;
      }

      // alert();return;

        $.ajax({
            url:"<?php echo site_url('e_document/grmain_dncn_proposed');?>",
            method:"POST",
            dataType: 'json',
            data:{ext_sup_cn_no:ext_sup_cn_no,type:type,refno:refno},
            beforeSend:function(){
              // $('.btn').button('loading');
            },
            success:function(data)
            {
              // console.log(data);return;
              $('#ecn_table tbody tr').each(function(index, tr){
                // alert(index);
                if(index == index_no)
                {
                  // alert(index+index_no);
                  // alert($(this).find('tr').attr('ext_doc1'));
                  $(this).attr('ext_doc1',ext_sup_cn_no);
                }
                // alert(index+new_ext_doc1+index_no);
              });
              alert(data.message);
              check_variance();
              location.reload();

            }//close success
          });//close ajax

      // $("#form_ECN").attr("action", "<?php echo site_url('panda_gr/generate_ecn');?>?refno="+refno+"&transtype="+type);
      // $("#form_ECN").attr("target", "_blank");
      // $("#form_ECN").submit();

      // window.location.reload();

    });

    $(document).on('click','#upload_grn_cn_doc',function(){
      var refno = $(this).attr('refno');
      var doc_type = "<?php echo $_REQUEST['accpt_gr_status'];?>";
      var loc = "<?php echo $_REQUEST['loc'];?>";
      var transtype = $(this).attr('transtype');
      // alert(transtype);return;
      var modal = $("#medium-modal").modal();

      modal.find('.modal-title').html('Upload Supplier CN');

      methodd = '';
      methodd +='<form action="<?php echo site_url('Upload/upload_grn_cn');?>" method="post" enctype="multipart/form-data" id="form_upload_prdn_cn">';
      methodd +='<div class="col-md-12">';

      methodd += '<div class="col-md-6"><label>Refno</label><input type="text" id="add_refno" class="form-control input-sm" placeholder="Refno" style="text-transform:uppercase" value='+refno+' name="upload_cn_refno" readonly required/></div>';

      methodd += '<div class="col-md-6"><label>File (Only PDF allow)</label><input type="file" id="add_group_name" class="form-control input-sm" name="upload_grn_cn_doc" accept=".pdf" placeholder="Please Choose File" required/><input type="hidden" name="upload_prdn_cn_status" value="'+doc_type+'"/><input type="hidden" name="upload_prdn_cn_loc" value="'+loc+'"/><input type="hidden" name="upload_prdn_cn_transtype" value="'+transtype+'"/></div>';

      methodd += '</div>';
      methodd +='<input type="submit" id="upload_cn_doc_submit_button_hide" style="display:none;"></form>';

      methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="upload_cn_doc_submit_button" class="btn btn-success" value="Upload"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

      modal.find('.modal-footer').html(methodd_footer);
      modal.find('.modal-body').html(methodd);

    });

    $(document).on('click','#upload_cn_doc_submit_button',function(){
       // $("#form_upload_prdn_cn").submit();
       $("#upload_cn_doc_submit_button_hide").trigger('click');
    });

    $(document).on('click','#save_einvoice_row',function(){
        var einvno = $('#e_einvno').val();
        var einvdate = $('#e_einvdate').val();
        var e_gr_refno = $('#e_gr_refno').val();

        if(einvno == '')
        {
          alert('Please Enter Ext No');
          return;
        }

        if(einvdate == '')
        {
          alert('Please Enter Ext Date');
          return;
        }
        // alert(einvno+einvdate);

        $.ajax({
            url:"<?php echo site_url('e_document/grmain_proposed');?>",
            method:"POST",
            dataType: 'json',
            data:{einvno:einvno,einvdate:einvdate,e_gr_refno:e_gr_refno},
            beforeSend:function(){
              // $('.btn').button('loading');
            },
            success:function(data)
            {
              $('#einvoice_header tbody tr').each(function(index, tr){
                // alert(index);
                $(this).attr('einvno',einvno);
                // alert(index+new_ext_doc1+index_no);
              });
              // console.log(data);
              alert(data.message);
              
              check_variance();

              location.reload();

            }//close success
          });//close ajax
    });

    $(document).on('click','#not_generate_ecn_generate_einv',function(){
      var check_uploaded_grn_cn = 1;
      if('<?php echo $check_upload_grn_cn;?>' == 1)
      {
        $('#ecn_table tbody tr').each(function(){
            if($(this).attr('file_path') == '' || $(this).attr('file_path') == null || $(this).attr('file_path') == 'null')
            {
              // alert();
              check_uploaded_grn_cn = 0;
            }
        });
      }
      // alert(check_uploaded_grn_cn);
      if(check_uploaded_grn_cn == 0)
      {
          alertmodal('Please upload Supplier CN attachment first');
          return;
      }

    var e_gr_refno = $('#e_gr_refno').val();

    $.ajax({
        url:"<?php echo site_url('Panda_gr/fetch_display_message');?>",
        method:"POST",
        dataType: 'json',
        data:{refno:e_gr_refno},
        beforeSend:function(){
          // $('.btn').button('loading');
        },
        success:function(data)
        {
            display_message = data.message;
            // alert('Please Generate E-CN first and confirm your supplier cn number');
            confirmation_modal(display_message);
            $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
              // alert('haha');

              var e_gr_refno = $('#e_gr_refno').val();

              // alert(einvno+einvdate);

              $.ajax({
                  url:"<?php echo site_url('e_document/generate_all_doc_type');?>",
                  method:"POST",
                  dataType: 'json',
                  data:{e_gr_refno:e_gr_refno},
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
                    // console.log(data);
                    Swal.close();
                    alert(data.message);
                    location.reload();


                  }//close success
                });//close ajax

            });

        }//close success
      });//close ajax

    });

    $(document).on('click','#generate_einv',function(){
    // alert();
    var e_gr_refno = $('#e_gr_refno').val();

    $.ajax({
        url:"<?php echo site_url('Panda_gr/fetch_display_message');?>",
        method:"POST",
        dataType: 'json',
        data:{refno:e_gr_refno},
        beforeSend:function(){
          // $('.btn').button('loading');
        },
        success:function(data)
        {
          message = data.message;
          // console.log(data);
          // alert(data.message);return;
          // location.reload();
          confirmation_modal(message);
          $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
            // alert('haha');

            var e_gr_refno = $('#e_gr_refno').val();

            // alert(einvno+einvdate);

            $.ajax({
                url:"<?php echo site_url('e_document/generate_all_doc_type');?>",
                method:"POST",
                dataType: 'json',
                data:{e_gr_refno:e_gr_refno},
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
                  // console.log(data);
                  Swal.close();
                  alert(data.message);
                  location.reload();

                }//close success
              });//close ajax

          });

        }//close success
      });//close ajax

      // alert('Please Generate E-CN first and confirm your supplier cn number');
    });

    $(document).on('keyup','.change_value',function(){

      // alert(disabled);
      check_variance();

    });

    check_variance = function()
    {
        var disabled = '0';
        var xdisabled = '0';
        var alert_dot = '<span style="color:red;font-size:25px;">*</span>';
        $('#einvoice_header tbody tr').each(function(){

          var small_disabled = '0';
          var original_einvno = $(this).attr('einvno');
          var original_einv_date = $(this).attr('einv_date');
          // alert(original_einvno+original_einv_date);

          var new_einvno = $(this).find('td:eq(6)').find('input').val();
          var new_einv_date = $(this).find('td:eq(7)').find('input').val();


          // alert(original_einvno + '-'+new_einvno);
          // alert(original_einv_date + '-'+new_einv_date);
          if((original_einvno != new_einvno))
          {
            // alert('111');
            disabled = '1';
            small_disabled = '1';
          }

          var original_html = $(this).find('td').last().html().replace(alert_dot, '');

          // if(small_disabled == '1')
          // {
            
          //   var original_html = original_html+alert_dot;
          // }

          // $(this).find('td').last().html(original_html);
          if(small_disabled == '1')
          {
            var original_html = original_html+alert_dot;
            $(this).find('td').last().html(original_html);
            $(this).find('td').last().find('button:eq(0)').prop('disabled',false);
          }
          else
          {
            $(this).find('td').last().html(original_html);
            $(this).find('td').last().find('button:eq(0)').prop('disabled',true);
          }
        }); 


        $('#ecn_table tbody tr').each(function(){

          var small_disabled = '0';

          var original_ext_doc1 = $(this).attr('ext_doc1');
          var original_ext_date1 = $(this).attr('ext_date1');

          var new_ext_doc1 = $(this).find('td:eq(6)').find('input').val();
          var new_ext_date1 = $(this).find('td:eq(7)').find('input').val();

          if((original_ext_doc1 != new_ext_doc1))
          {
            disabled = '1';
            small_disabled = '1';
          }
          // alert(small_disabled);
          var original_html = $(this).find('td').last().html().replace(alert_dot, '');

          if(small_disabled == '1')
          {
            var original_html = original_html+alert_dot;
            $(this).find('td').last().html(original_html);
            $(this).find('td').last().find('button:eq(0)').prop('disabled',false);
          }
          else
          {
            $(this).find('td').last().html(original_html);
            $(this).find('td').last().find('button:eq(0)').prop('disabled',true);
          }

          

        }); 
        // alert(disabled);
        if(disabled == '1')
        {
          $('#not_generate_ecn_generate_einv').prop('disabled',true);
          $('#generate_einv').prop('disabled',true);
        }
        else
        {
          // alert(disabled);
          $('#not_generate_ecn_generate_einv').prop('disabled',false);
          $('#generate_einv').prop('disabled',false);
        }
    }
});

</script>
