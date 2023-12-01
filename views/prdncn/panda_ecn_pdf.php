<html><head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- <title>Invoice</title> -->
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/AdminLTE.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/ionicons.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/font-awesome.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/bootstrap.min.css')?>">


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<style type="text/css">

  #right-table thead tr th {
    border: groove !important;
    text-align: center;
}

  #right-table tbody tr td {
    border: groove !important;
    text-align: center;
}

.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        border: 1px solid;

        padding-left: 5px;
        padding-right: 5px;
            /*white-space: nowrap;*/
}

 
}


   

}

</style>


</head>
     
        

<body style="  overflow-x: unset;
    overflow-y: unset;">

<div class="wrapper" style="overflow-x: unset;
    overflow-y: unset;">
  <!-- Main content -->
  <section class="invoice">
 

<div class="col-xs-12 table-responsive">
<table class="table table-striped" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
<tr>
<td style="width: 80%;text-align: left">
        
       <table cellspacing="0" cellpadding="3"> 
        <tbody> 
          <tr> 
            <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;">
              
              &nbsp; Purchase Return Credit Note issued by

            </td>

            <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;">
              
              &nbsp;Issued to Supplier

            </td>

          </tr>

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;font-size:13px">
              
              <b><?php echo $header[0]['doc_name_reg']?></b>
              
            </td>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;font-size:13px;">
              
              <b><?php echo $header[0]['branch_name']?></b>
              
            </td>


          </tr>

          <tr>
            

            <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              
              <!-- Co Reg No: <?php echo $supcus_supplier->row('reg_no') ?> -->
              &nbsp;<?php echo $header[0]['reg_sup']?><br>
              &nbsp;<?php echo $header[0]['Add1']?><br> 
              &nbsp;<?php echo $header[0]['Add2']?><br>
              &nbsp;<?php echo $header[0]['Add3']?><br><br>
              &nbsp;Tel : <?php echo $header[0]['contact']?><br> 
              
            </td>

            <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              
              <!-- Co Reg No: <?php echo $supcus_customer->row('reg_no') ?>  -->
              &nbsp;<?php echo $header[0]['reg_no']?><br> 
              &nbsp;<?php echo $header[0]['address1']?><br> 
              &nbsp;<?php echo $header[0]['address2']?><br>
              &nbsp;<?php echo $header[0]['address3']?><br>
              &nbsp;<?php echo $header[0]['branch_add']?><br>
              &nbsp;<?php echo $header[0]['contactnumber']?><br>

            </td>
          </tr>

<!--           <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              <table>
                <td><?php echo $supcus_customer->row('Add1') ?> 
                <br><?php echo $supcus_customer->row('Add2') ?> 
                <br><?php echo $supcus_customer->row('Add3') ?> 
                <br><?php echo $supcus_customer->row('Add4') ?> <br>
                </td>
              </table>
            </td>

            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              
              <table>
                <td><?php echo $supcus_supplier->row('Add1') ?> 
                <br><?php echo $supcus_supplier->row('Add2') ?> 
                <br><?php echo $supcus_supplier->row('Add3') ?> 
                <br><?php echo $supcus_supplier->row('Add4') ?> <br>
                </td>
              </table>
            </td>

          </tr> -->

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              <table>
                <td><b>&nbsp;Sup Code :</b> <?php echo $header[0]['supplier']?></td>
              </table> 
            </td>            
            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              <table>
                <td><b>Location :</b> <?php echo $header[0]['loc_desc'];?></td>
              </table> 
            </td>

          </tr>

          <tr>

            <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              <table>
                <td><b>&nbsp;DN No:</b><?php echo $header[0]['refno'];?></td>
                <td><b>&nbsp;DN Date:</b><?php echo $header[0]['docdate'];?></td>
              </table> 
            </td>            
            <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              <table>
                <!-- <td><b>CN Date:</b><?php echo $header[0]['sup_cn_date'];?></td> -->
                <td><b>CN Date:</b><?php echo date("d/m/Y",strtotime($query_data->row('ext_date1'))).' '.date('D', strtotime($query_data->row('ext_date1')));?></td>
              </table> 
            </td>

          </tr>          
        </tbody>
       </table>
 
   
       
  
</td>
<td style="width: 20%;">


        
        <table id="right-table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;height:500px;">
        
        <tbody style="height:500px;"> 
                <tr>
                  
                  <td  style="height:60px;border: 1px solid black;" nowrap=""><p style=""> </p><p style="font-size:12px;text-align: center;"><b>E-Credit Note</b></p></td>



                </tr>

         <tr>

                  <td style="height:43px; text-align: center; border: 1px solid black;" colspan="2"><p style="text-align:left;"> Supplier CN No</p><p style="font-size:12px;"><b><?php echo $header[0]['sup_cn_no'];?><?php if($header[0]['sup_cn_no'] != $query_data->row('ext_doc1')){echo '<br>'.'['.$query_data->row('ext_doc1').']';}?></b></p></td>


                </tr>


        </tbody>
      
        </table>


  </td>
</tr>

    </table>
    
  </div>

    

<div class="col-xs-12 table-responsive">
<table class="table table-striped" style="border-collapse: collapse; width: 100%;">
          <table border="1" cellpadding="3">
            <tr style ="text-align:center;"> 
              <th style="width:2.71%;"><b>No</b></th>
              <th style="width:8%;"><b>Itemcode<br>Barcode</b></th>
              <th style="width:14%;"><b>Description</b></th>
              <th style="width:3%;"><b>P/S</b></th>
              <th style="width:7%;"><b>Orginal Inv Date</b></th>
              <th style="width:7.5%;"><b>Original Invoice No</b></th>
              <th style="width:6.5%;"><b>Reason</b></th>
              <th style="width:7.5%;"><b>Unit Price Before Disc</b></th>
              <th style="width:5%;"><b>Bill Disc Prorated</b></th>
              <th style="width:6%;"><b>Unit Price Before Tax</b></th>
              <th style="width:5%;"><b>Unit Tax Amount</b></th>
              <th style="width:6.5%;"><b>Unit Price After Tax</b></th>
              <th style="width:5%;"><b>Quantity</b></th>
              <th style="width:6.5%;"><b>Total Amount Before Tax</b></th>
              <th style="width:5%;"><b>Total Tax Amount</b></th>
              <th style="width:5%;"><b>Total Amount After Tax</b></th>  
            </tr>

            <tbody>
                <?php $i = 1; 
                for($i = 0; $i < count($child); $i++ )
                {
                    ?>
                <tr style="" >
    
                <td style="width:2.71%;text-align:center;"><?php echo $child[$i]['line'] ?></td>
                <td style="width:8%;text-align:left"><?php echo $child[$i]['itemcode'] ?>
                  <br><?php echo $child[$i]['barcode']; ?></td>
                <td style="width:14%;text-align:left;"><?php echo $child[$i]['description']; ?></td>
                <td style="width:3%;text-align:right"><?php echo $child[$i]['packsize']; ?></td>
                <td style="width:7%;text-align:left"><?php echo $child[$i]['ori_inv_date']; ?></td>
                <td style="width:7.5%;text-align:left"><?php echo $child[$i]['ori_inv_no']; ?></td>
                <td style="width:6.5%;text-align:left"> <?php echo $child[$i]['reason']; ?></td>
                <td style="width:7.5%;text-align:right"> <?php echo $child[$i]['netunitprice']; ?></td>
                <td style="width:5%;text-align:right"> <?php echo $child[$i]['unit_disc_prorate']; ?></td>
                <td style="width:6%;text-align:right"> <?php echo $child[$i]['unit_price_bfr_tax']; ?></td>
                <td style="width:5%;text-align:right"> <?php echo $child[$i]['gst_unit_tax']; ?></td>
                <td style="width:6.5%;text-align:right"> <?php echo $child[$i]['gst_unit_cost']; ?></td>
                <td style="width:5%;text-align:right"> <?php echo $child[$i]['qty']; ?></td>
                <td style="width:6.5%;text-align:right"> <?php echo $child[$i]['total_price_bfr_tax']; ?></td>
                <td style="width:5%;text-align:right"> <?php echo $child[$i]['gst_child_tax']; ?></td>
                <td style="width:5%;text-align:right"> <?php echo $child[$i]['totalprice']; ?></td>
                </tr>
                    
                <?php ; } ?>

              </tbody>
            </table>
</div>

   <div class="col-xs-12 table-responsive">
                <table class="table table-striped" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
                <tr>
                <td style="width: 55%;text-align: left">  
                       <table cellspacing="0" cellpadding="3"> 
                        <tbody> 
                          <tr> 
                            <td style="border: 1px solid black;">Remark <br><?php echo $header[0]['remark'];?></td> 
                            <td style="border: 1px solid black;">Doc issued by<br><br><br><hr><?php echo $header[0]['issuedby'];?></td> 
                            <td style="border: 1px solid black;">Posted on : <?php echo $header[0]['postdatetime']; ?><br><br><br><hr><?php echo $header[0]['postby'];?></td> 
                            <td style="border: 1px solid black;">Checked by: <br><br><br><hr></td> 
                          </tr> 
             </tbody>
            </table> 
            </td>
            <td style="width: 45%;"> 
                    <table id="right-table"  border="0" cellspacing="0" cellpadding="3" style="width: 100%;">
                    <thead>
                            <tr> 
<!--                                 <th style="width:20%;text-align: center;border: 1px solid black;" colspan = "2"><b>TAX @ 0%</b></th> 
                                <th style="width:20%;text-align: center;border: 1px solid black;" colspan = "2"><b>TAX @ >0%</b></th>  -->
                                <th style="width:40%;text-align: center;" colspan = "2"></th> 
                                <th style="width:60%;text-align: center;border: 1px solid black;" colspan = "2"><b>Document Summary</b></th> 
                             </tr>
                    </thead>
                    <tbody> 
                        <tr> 
                        <td style="width:20%;text-align: center;" nowrap=""></td>
                        <td style="width:20%;text-align: center;" nowrap=""></td>
                        <td style="width:40%;text-align: left;border: 1px solid black;" nowrap="">Total Exclude Tax & Surcharge</td>
                        <td style="width:20%;text-align: right;border: 1px solid black;" nowrap=""> <?php echo number_format($child[0]['subtotal1'],2) ?> &nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                        <td style="text-align: center;" ></td>
                        <td style="text-align: center;" ></td>  
                        <td style="text-align: left; border:1px solid black;" >Total Amount Include Tax</td>
                        <td style="text-align: right; border: 1px solid black;" > <?php echo number_format($child[0]['gst_main_total'],2) ?> &nbsp;&nbsp;</td>
                        </tr>
            </tbody>
        </table> 
      </td>
    </tr>
    </table>
  </div>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->


</body> </html>

