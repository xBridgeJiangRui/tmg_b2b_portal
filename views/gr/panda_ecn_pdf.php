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
        
       <table cellspacing="0" cellpadding="0"> 
        <tbody> 
          <tr> 
            <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;">
              
              Goods Received Difference Advice issued by

            </td>

            <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;">
              
              Issued to

            </td>

          </tr>

          <tr>

            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              
              <b><?php echo $supcus_supplier->row('Name') ?></b>
              
            </td>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              
              <b><?php echo $supcus_customer->row('BRANCH_NAME') ?></b>
              
            </td>

          </tr>

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              
              Co Reg No: <?php echo $supcus_supplier->row('reg_no') ?>
              
            </td>

            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              
              Co Reg No: <?php echo $retailer_acc->row('acc_regno') ?> 
              
            </td>

          </tr>

          <tr>

            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              
              <table>
                <td><?php echo $supcus_supplier->row('Add1') ?> 
                <br><?php echo $supcus_supplier->row('Add2') ?> 
                <br><?php echo $supcus_supplier->row('Add3') ?> 
                <br><?php echo $supcus_supplier->row('Add4') ?> <br>
                </td>
              </table>
            </td>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              <table>
                <td><?php echo $supcus_customer->row('BRANCH_ADD') ?> 
                <br><?php echo $supcus_customer->row('Add2') ?> 
                <br><?php echo $supcus_customer->row('Add3') ?> 
                <br><?php echo $supcus_customer->row('Add4') ?> <br>
                </td>
              </table>
            </td>

          </tr>

          <tr>

            <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              
              <table> 
                <td><br><br><b>Tel:</b><?php echo $supcus_supplier->row('Tel') ?> <b>  Fax:</b> <?php echo $supcus_supplier->row('Fax') ?>  </td> 
              </table>
              
              
            </td>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              
              <table>
                <td><br><br><b>Tel:</b><?php echo  $supcus_customer->row('BRANCH_TEL') ?> <b>  Fax:</b> <?php echo  $supcus_customer->row('BRANCH_FAX') ?>  </td> 
              </table>
              
              
            </td>

          </tr>

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              <table>
                <td><b>Outlet:</b> <?php echo $supcus_customer->row('BRANCH_CODE') ?> - <?php echo $supcus_customer->row('BRANCH_NAME') ?>
                <br><b>GRN RefNo:</b> <?php echo $query_data->row('refno') ?></td>
                
                <!--hide by jr -->
                <!--
                <td>
                  <b>Outlet:</b> <?php echo $supcus_customer->row('BRANCH_CODE') ?> -  <?php echo $supcus_customer->row('BRANCH_NAME') ?>
                  <b><br>Received Loc:</b> <?php echo $supcus_customer->row('Code') ?>- <?php echo $supcus_customer->row('Name') ?> 
                </td>-->


              </table>
              
              
            </td>
             <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              <table>
                <td colspan="2"><b>Code :</b> <?php echo $supcus_supplier->row('Code') ?> <b><br>Supplier CN No:</b> <?php echo $query_data->row('ext_doc1') ?></td>
                <td> <b><br>CN Date:</b><?php echo $query_data->row('ext_date1') ?></td>
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
                  
                  <td  style="height:60px;border: 1px solid black;" nowrap=""><p style=""> </p><p style="font-size:12px;text-align: center;"><b>Credit Note</b></p></td>



                </tr>

         <tr>

                  <td style="height:60px; text-align: center; border: 1px solid black;" colspan="2"><p style="text-align:left;"> Supplier CN No</p><p style="font-size:12px;"><b> <?php echo $query_data->row('ext_doc1') ?></b></p><p style="font-size:12px;"></p></td>
                


                </tr>


        </tbody>
      
        </table>


  </td>
</tr>

    </table>
    
  </div>

    

<div class="col-xs-12 table-responsive">
<table class="table table-striped" style="border-collapse: collapse; width: 100%;">
          <table border="1">
            <tr style ="text-align:center;"> 
              <th style="width:2.71%;"><b>No</b></th>
              <th style="width:10%;"><b>Itemcode<br>Barcode</b></th>
              <th style="width:15%;"><b>Description</b></th>
              <th style="width:2%;"><b>P/S</b></th>
              <th style="width:7.71%;"><b>Reason</b></th>
              <th style="width:11%;"><b>PO Refno</b></th>
              <th style="width:5.71%;"><b>GRN/PO Price</b></th>
              <th style="width:5.71%;"><b>Received Qty</b></th>
              <th style="width:5.71%;"><b>Supplier Inv Price</b></th>
              <th style="width:5.71%;"><b>Supplier Inv Qty</b></th>
              <th style="width:5.71%;"><b>Unit Price Bfr Tax</b></th>
              <th style="width:5.71%;"><b>Quantity</b></th>
              <th style="width:5.71%;"><b>Total Amt Excl Tax</b></th>
              <th style="width:5.71%;"><b>Total Tax Amt</b></th>
              <th style="width:5.71%;"><b>Total Amt Incl Tax</b></th> 
            </tr>

            <tbody>
                <?php $i = 1; 
                foreach($query_data->result() as $row) { 
                    ?>
     
                <tr style="" >
    
                <td style="width:2.71%;text-align:center;"><?php echo $i ?></td>
                <td style="width:10%;text-align:left">&nbsp;&nbsp;<?php echo $row->itemcode ?>
                  <br>&nbsp;&nbsp;<?php echo $row->barcode; ?></td>
                <td style="width:15%;text-align:left">&nbsp;&nbsp;<?php echo $row->description; ?></td>
                <td style="width:2%;text-align:right"> <?php echo $row->packsize; ?> &nbsp;&nbsp;</td>
                <td style="width:7.71%;text-align:left"> <?php echo $row->reason; ?></td>
                <td style="width:11%;text-align:left"> <?php echo $row->porefno; ?></td>
                <td style="width:5.71%;text-align:right"> <?php echo number_format($row->pounitprice,2); ?>&nbsp;&nbsp;</td>
                <td style="width:5.71%;text-align:right"> <?php echo $row->qty; ?>&nbsp;&nbsp;</td>
                <td style="width:5.71%;text-align:right"> <?php echo number_format($row->inv_netunitprice,2); ?>&nbsp;&nbsp;</td>
                <td style="width:5.71%;text-align:right"> <?php echo $row->inv_qty; ?>&nbsp;&nbsp;</td>
                <td style="width:5.71%;text-align:right"> <?php echo number_format($row->netunitprice,2); ?>&nbsp;&nbsp;</td>
                <td style="width:5.71%;text-align:right"> <?php echo $row->qty; ?>&nbsp;&nbsp;</td>
                <td style="width:5.71%;text-align:right"> <?php echo number_format($row->variance_amt,2); ?>&nbsp;&nbsp;</td>
                <td style="width:5.71%;text-align:right"> <?php echo number_format($row->tax_amount,2); ?>&nbsp;&nbsp;</td>
                <td style="width:5.71%;text-align:right"> <?php echo number_format($row->total_gross,2); ?>&nbsp;&nbsp;</td>
                </tr>
                    
                <?php $i = $i+1; } ?>

              </tbody>
            </table>
</div>
   <div class="col-xs-12 table-responsive">
                <table class="table table-striped" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
                <tr>
                <td style="width: 55%;text-align: left">  
                       <table cellspacing="0" cellpadding="0"> 
                        <tbody> 
                          <tr> 
                            <td style="border: 1px solid black;">Important Note: </td> 
                          </tr> 
                          <tr> 
                            <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">This Debit Advice Is to notify your Company that qty received by us does not tallied with the qty specified in your Tax Invoice No <?php echo $query_data->row('invno') ?> Kindly Issued us a credit note within <?php echo $within_date ?> days from the date hereof failure which we will not proceed with payment of this invoice.
                            </td>
              </tr> 
             </tbody>
            </table> 
            </td>
            <td style="width: 45%;"> 
                    <table id="right-table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                    <thead>
                            <tr> 
                                <th style="width:100%;text-align: center;border: 1px solid black;" colspan = "2"><b>Document Summary</b></th> 
                             </tr>
                    </thead>
                    <tbody> 
                        <tr> 
                        <td style="width:70%;text-align: center;border: 1px solid black;" nowrap="">Total Gross Amount</td>
                        <td style="width:30%;text-align: right;border: 1px solid black;" nowrap=""> <?php echo number_format($query_data->row('amount'),2) ?> &nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                        <td style="text-align: center; border: 1px solid black;" >Total Amount Exclude Tax</td>
                        <td style="text-align: right; border: 1px solid black;" > <?php echo number_format($query_data->row('amount'),2) ?> &nbsp;&nbsp;</td>
                        </tr>

                    <tr>
                        <td style="text-align: center; border: 1px solid black;" >Total Tax Amount</td>
                        <td style="text-align: right; border: 1px solid black;" > <?php echo number_format($query_data->row('tax_amount'),2) ?> &nbsp;&nbsp;</td>
                    </tr> 
                    <tr> 
                        <td style="text-align: center; border: 1px solid black;" >Total Amount Include Tax</td>
                        <td style="text-align: right; border: 1px solid black;" > <?php echo number_format($query_data->row('total_incl_tax'),2) ?> &nbsp;&nbsp;</td>
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

