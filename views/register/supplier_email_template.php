<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>A Simple Responsive HTML Email</title>
  <style type="text/css">
  body {margin: 0; padding: 0; min-width: 100%!important;}
  img {height: auto;}
  .content {width: 100%; max-width: 1000px;}
  .header {padding: 40px 30px 20px 30px;}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .subhead {font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px;}
  .h1, .h2, .bodycopy {color: #153643; font-family: sans-serif;}
  .h1 {font-size: 33px; line-height: 38px; font-weight: bold;}
  .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
  .bodycopy {font-size: 16px; line-height: 22px;}
  .button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
  .button a {color: #ffffff; text-decoration: none;}
  .footer {padding: 20px 30px 15px 30px;}
  .footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}
  .footercopy a {color: #ffffff; text-decoration: underline;}

  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
  body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
  }

a{

color:purple;
text-decoration: none;

}

  a:link {
    color:purple;
  text-decoration: none;
}

a:visited {
  color:purple;
  text-decoration: none;
}

#btn_url:hover{
  background: #99ccff;
}

  </style>
</head>

<body yahoo bgcolor="#f6f8f1">
<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
      <tr >
        <td style="background: #e40202;" class="header" >
          <table width="70" align="left" border="0" cellpadding="0" cellspacing="0">  
            <tr>
              <?php if($store_logo != '')
              {
                ?>
                <td height="70" style="padding: 0 20px 20px 0;padding-left: 20px;">
                <img style="border-radius: 15px;" class="fix" src="<?php echo $store_logo; ?>" width="70" height="60" border="0" alt="" >
                </td>
                <?php
              }
              ?>
            </tr>
          </table>

          <table class="col425" align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 700px;">  
            <tr>
              <td class='h1' align="left">
                 <?php if( $customer_name->row('acc_doc_name') != '')
                 {
                 ?>
                   <?php echo str_replace('-',' ',$customer_name->row('acc_doc_name')); ?>
                 <?php
                 }
                 ?>
              </td>
              <td class='h1' >

              </td>
            </tr>
          </table>
          <br>
        </td>
      </tr>

      <tr> 
        <td class="innerpadding borderbottom">
          <table class="col425" align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
          <tr>
            <td align ='center'> 
              <img style="border-radius: 15px;width:100px;height:100px;" class="fix" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/article1.png" border="0" alt="" />
            </td>

            <td align ='center'>
                <h3 class="text-info">
                   Please click on the link below and fill up the <?php echo $form_name?> for Sunshine B2B Portal.
                </h3>
                <p class="lead" align ='left' style="padding-left: 40px;">
                <table class="col425" align="center" border="0" cellpadding="0" cellspacing="0" style="width: 70%;padding-left: 40px;">
                  <tr >
                    <td align="center">
                      Retailer Name
                    </td>
                    <td>
                      <b>
                        <?php if( $customer_name->row('acc_name') != '')
                        {
                        ?>
                          <?php echo $customer_name->row('acc_name'); ?>
                        <?php
                        }
                        ?>
                      </b>
                    </td>
                  </tr>
                  <tr>
                    <td align="center">
                      Supplier Name
                    </td>
                    <td>
                       <b>
                        <?php if( $supplier_detail->row('supplier_name') != '')
                        {
                        ?>
                          <?php echo $supplier_detail->row('supplier_name'); ?>
                        <?php
                        }
                        ?>

                        <?php if( $supplier_detail->row('reg_no') != '')
                        {
                        ?>
                          (<?php echo $supplier_detail->row('reg_no'); ?>)
                        <?php
                        }
                        ?>
                      </b>
                    </td>
                  </tr>          
                </table>
                </p>
                <table class="buttonwrapper" style="background-color:#66ccff;border-radius: 15px;" border="0" cellspacing="0" cellpadding="0">
                  <tr id="btn_url">
                    <td height="45">
                      <a href=<?php echo $url?>><div class="button" height="45" style="color:black;"><?php echo $form_name?></div></a>
                    </td>
                  </tr>
                </table>
                <br>
                <p align ='left' style="padding-left: 40px;"><mark>Please fill up the correct information for us to process.</mark>
                <br>
                Please contact B2B Team if you need further clarifications on registration process.
                <br>
                Thank You.</p>
            </td></tr>
          </table>
        </td>
      </tr>         
      <tr>
        <td class="footer" bgcolor="#44525f">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center" class="footercopy">
                16-05-V01, Tower B, Vertical Business Suite II,Bangsar South City, <br>No. 8, Jalan Kerinchi, 59200 Kuala Lumpur.<br/>
                Tel: +6017 - 745 1185/+6017 - 715 9340<br>
                Fax: +603 - 2242 2781<br>
                Email: support@xbridge.my

              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
</table>
</body>
</html>