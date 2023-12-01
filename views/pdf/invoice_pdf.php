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
            white-space: nowrap;
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
    <!-- title row -->
    <!-- <div class="row" style="border-bottom: 1px solid black;margin-bottom: 50px;">
      <div class="col-xs-12">
          
          <div>
          <img style="width: 100px;display: inline-block;" src="<?php echo base_url('asset/dist/img/logo.jpg');?>"><br>
          <address style="display: inline-block;position: absolute;font-size: 14px;margin-left: 5px">
          <b style="text-decoration-line: underline;"><?php echo $name; ?></b>(<?php echo $reg_no; ?>) <br>
          <?php echo $add1; ?><br>
          <?php echo $add2; ?> <br>
          <?php echo $add3; ?><br>
          TEL: <?php echo $tel; ?> &nbsp Email: <?php echo $email; ?><br>        
          </address>

          <div style="float: right;"><?php echo $reportheaderinfo->invoice_number; ?></div>
          
        </div>
        
      </div>

    </div> -->
    <!-- info row -->

<div class="col-xs-12 table-responsive">
<table class="table table-striped" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
<tr>
<td style="width: 60%;text-align: left">
        
       <table cellspacing="0" cellpadding="0">
         
        <tbody>
          
          <tr>
            
            <td style="border: 1px solid black;">
              
              Bill To: 

            </td>

          </tr>

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              
              <b>123</b>
              
            </td>

          </tr>

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              <table>
                
                <td style="width: 200px">123 </td>

              </table>
              
              
            </td>

          </tr>

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              
              <table>
                
                <td><br><br><b>Tel:</b> 123 <b>  Email:</b> 123</td>

              </table>
              
              
            </td>

          </tr>

        </tbody>

       </table>
 
   
       
  
</td>
<td style="width: 40%;">


        <table id="right-table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
        <thead>
                <tr>
                
                    <!-- <th style="text-align: center;" ><input type="checkbox" name="tids[]" id="tidsall" onclick="checkedAll ();"></th> -->
                    
                    <th  style="text-align: center; border: 1px solid black;"><b>Invoice No.</b></th>
                    <th style="text-align: center;border: 1px solid black;"><b>Period Code</b></th>
                    <th style="text-align: center;border: 1px solid black;"><b>Invoice Date</b></th>
               

                </tr>
        </thead>
        <tbody> 
                <tr>
                  
                  <td style="text-align: center;border: 1px solid black;" nowrap=""><?php echo $reportheaderinfo->invoice_number; ?></td>

                  <td style="text-align: center;border: 1px solid black;" nowrap=""><?php echo $reportheaderinfo->period_code; ?></td>

                  <td style="text-align: center;border: 1px solid black;" nowrap=""><?php echo DateTime::createFromFormat("Y-m-d H:i:s", $reportheaderinfo->created_at)->format("Y-m-d"); ?></td>

                </tr>

         <tr>

                  <td style="text-align: center; border: 1px solid black;" colspan="3"><b>Printed Date:</b> <?php echo date("Y-m-d"); ?></td>


                </tr>
                <tr>
                  
                  
                  <td style="text-align: center;border: 1px solid black;" colspan="3">Term <?php echo $reportheaderinfo->term; ?> Days</td>

                </tr>

        </tbody>
      
        </table>


  </td>
</tr>

    </table>
  </div>

    <br><br>
    Attention: <?php echo $reportheaderinfo->contact; ?> 
  
    <br><br>

<div class="col-xs-12 table-responsive">
<table class="table table-striped" style="border-collapse: collapse; width: 100%;">
  
  <thead>
    <tr >
            <th style="width: 17%;text-align: center; border: 1px solid black"></th>
            <th style="width: 50%; text-align: center; border: 1px solid black">
              <b>Document Transaction</b>
            </th>
            <th style="width: 10%;text-align: center; border: 1px solid black">
              <b>Fee</b>
            </th>
            <th style="width: 10%;text-align: center; border: 1px solid black">
              <b>Document</b>
            </th>
            <th style="width: 13%;text-align: center; border: 1px solid black">
              <b>Total Fee(RM)</b>
            </th>
    </tr>
  </thead>
<tbody>
    <?php foreach($table->result() as $row) { ?> 
    <tr  <?php if ($row->type == 'total_transaction') { ?> style="background-color: blanchedalmond;"  <?php } ?> >
      
      <td style=" <?php if ($row->type == 'total_transaction') { ?>border-bottom: 1px solid black;  <?php } ?> width: 17%; text-align: left; border-left: 1px solid black; border-right: 1px solid black;">
        
        <!-- <?php if ($row->line == 1) { 

          echo $row->description;

          }?> -->
 
      </td>
      <td style=" <?php if ($row->type == 'total_transaction') { ?>border-bottom: 1px solid black;  <?php } ?> width: 50%; border-left: 1px solid black; border-right: 1px solid black;">

        <?php echo $row->description ?>
          

      </td>
      <td style=" <?php if ($row->type == 'total_transaction') { ?>border-bottom: 1px solid black;  <?php } ?> width: 10%; text-align: center;border-left: 1px solid black; border-right: 1px solid black;">   

        <?php if ($row->type == 'description') { ?>
        <?php echo $row->value ?>
        <?php } ?>
          
      </td>
      <td style=" <?php if ($row->type == 'total_transaction') { ?>border-bottom: 1px solid black;  <?php } ?>width: 10%; text-align: center; border-left: 1px solid black; border-right: 1px solid black;">

        <?php echo $row->quantity ?>

      </td>
      <td style=" <?php if ($row->type == 'total_transaction') { ?>border-bottom: 1px solid black;  <?php } ?>width: 13%; text-align: right;border-left: 1px solid black; border-right: 1px solid black;">

        <?php echo number_format($row->amount,2) ?>
          
      </td>
      

    </tr>
    <?php } ?>
<?php if($discountamount != 0 ) { ?>

    <tr>
      <td style="border: 1px solid black;">
       Sub Total
      </td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border: 1px solid black;text-align: right"><?php echo number_format($reportheaderinfo->subtotal_bf_disc,2) ?></td>
    </tr>

   
    <tr>
      <td style="border: 1px solid black;">
        Discount(<?php 

      if ($reportheaderinfo->discount_type == 'amount'){

        echo 'Amt.';
      }

      else

        echo $reportheaderinfo->discount_value.'%';


      ?>)
      </td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border: 1px solid black;text-align: right">

        <?php echo  '-'.number_format($reportheaderinfo->discount,2); ?>

      </td>
    </tr>
    

    <tr>
      <td style="border: 1px solid black;">
       Sub Total(Discount)
      </td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border: 1px solid black;text-align: right">

        <?php echo number_format($reportheaderinfo->subtotal_aft_disc,2) ?>

      </td>
    </tr>
      <?php } else { ?>

      <tr>
        <td style="border: 1px solid black;">
         Sub Total
        </td>
        <td style="border-bottom: 1px solid black;"></td>
        <td style="border-bottom: 1px solid black;"></td>
        <td style="border-bottom: 1px solid black;"></td>
        <td style="border: 1px solid black;text-align: right">

          <?php echo number_format($reportheaderinfo->subtotal_aft_disc,2) ?>
            
          </td>
      </tr>
      <?php } ?>
    <tr>
      <td style="border: 1px solid black;">

        <?php echo $reportheaderinfo->tax_type; ?> (<?php echo $taxpercent?>%) 

      </td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border: 1px solid black;text-align: right">

        <?php echo number_format($reportheaderinfo->tax,2) ?>

      </td>
    </tr>

    <?php if($reportheaderinfo->rounding_adj != 0 ) { ?>
    <tr>
      <td style="border: 1px solid black;">
      Rounding Adj.
      </td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border: 1px solid black;text-align: right">

        <?php echo number_format($reportheaderinfo->rounding_adj,2) ?>

      </td>
    </tr>
      

    <tr style="background-color: blanchedalmond">
      <td style="border: 1px solid black;">
       Total Amount(RM) 
      </td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border: 1px solid black;text-align: right;">
        <b><?php echo number_format($reportheaderinfo->final_amount_round,2) ?></b>
      </td>
    </tr>
      <?php } else { ?>

    <tr style="background-color: blanchedalmond">
      <td style="border: 1px solid black;">
      Total Amount(RM)
      </td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border-bottom: 1px solid black;"></td>
      <td style="border: 1px solid black;text-align: right">

        <b><?php echo number_format($reportheaderinfo->final_amount,2) ?></b>

      </td>
    </tr>

      <?php }  ?>

  </tbody>

</table>
</div>
    <div>
    <?php foreach($execute->result() as $row) { ?> 
       <?php echo $row->remark ?><br>
    <?php } ?> 
  </div>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->


</body> </html>

