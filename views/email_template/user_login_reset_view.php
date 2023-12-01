<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>TMG B2B</title>
  <style type="text/css">
  body {margin: 0; padding: 0; min-width: 100%!important;}
  img {height: auto;}
  .content {width: 100%; max-width: 600px;}
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
@keyframes blink { 50% { background-color:#fff ; }  }
  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  </style>
</head>

<body yahoo bgcolor="#f6f8f1">
<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>
    <!--[if (gte mso 9)|(IE)]>
      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
    <![endif]-->     
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="background: #e40202;" class="header">
          <table width="70" align="left" border="0" cellpadding="0" cellspacing="0">  
            <tr>
              <td height="70" style="padding: 0 20px 20px 0;">
                <img style="border-radius: 15px;" class="fix" src="https://supplier.tunasmanja.com/asset/dist/img_sunshine/icon-512.png" width="70" height="70" border="0" alt="" />
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;">  
            <tr>
              <td height="70">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="subhead" style="padding: 0 0 0 3px;">
                      <!-- B2B -->
                    </td>
                  </tr>
                  <tr>
                    <td class="h1" style="padding: 5px 0 0 0;">
                      Tunas Manja Sdn Bhd
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      <tr>
        <td class="innerpadding borderbottom">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="h2">
                Thank You for choosing TMG B2B!
              </td>
            </tr>
            <tr>
              <td class="h2">
                Login Details
              </td>
            </tr>
<!--             <tr>
              <td class="bodycopy">
                We are please to announce the release of PO module and please find the below your login account information.
              </td>
            </tr> -->
          </table>
        </td>
      </tr>

      <tr>
        <td class="innerpadding borderbottom">
          <table width="115" align="left" border="0" cellpadding="0" cellspacing="0">  
            <tr>
              <td height="115" style="padding: 0 20px 20px 0;">
                <img class="fix" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/article1.png" width="115" height="115" border="0" alt="" />
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="380" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <table class="col380" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 380px;">  
            <tr>
              <td class="bodycopy">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">

                  <?php if( $customer_name->row('acc_name') != '')
                  {
                  ?>
                    <tr>
                      <td class="bodycopy">
                           <b>Retailer</b> : <?php echo $customer_name->row('acc_name');?>   
                      </td>
                    </tr> 
                  <?php
                  }
                  ?>
                  <?php if( $supplier_detail->row('supplier_name') != '')
                  {
                  ?>           
                  <tr>
                    <td class="bodycopy">
                         <b>Company Name</b> : <?php echo $supplier_detail->row('supplier_name');?>   
                    </td>
                  </tr> 
                  <?php
                  }
                  ?>
                  <?php if( $supplier_detail->row('reg_no') != '')
                  {
                  ?>            
                  <tr>
                    <td class="bodycopy">
                         <b>Registration Number</b> : <?php echo $supplier_detail->row('reg_no');?>   
                    </td>
                  </tr>
                  <?php
                  }
                  ?>
                  <?php if( $supplier_code->row('vendor_code') != '')
                  {
                  ?>             
                  <tr>
                    <td class="bodycopy">
                         <b>Vendor Code</b> : <?php echo $supplier_code->row('vendor_code');?>   
                    </td>
                  </tr>  
                  <?php
                  }
                  ?>
                  <tr>
                    <td class="bodycopy">
                      <b>Webpage</b> : 
                      <a href="https://supplier.tunasmanja.com/">https://supplier.tunasmanja.com/</a>
                    </td>
                  </tr> 
                </table>
                
                <table width="100%" border="1" cellspacing="1" cellpadding="1">
                  <?php if( $user_detail->row('user_id') != '')
                  {
                  ?>   
                  <tr>
                    <td class="bodycopy">
                         <b>Login ID</b> : <?php echo $user_detail->row('user_id');?>   
                    </td>
                  </tr>
                  <?php
                  }
                  ?>
                  <?php if( $reset_url != '')
                  {
                  ?> 
                  <tr>
                    <td class="bodycopy">
                      <b>Password</b> : Please <b><a style="background-color: yellow; " href="<?php echo $reset_url;?>">CLICK</a></b> to key in your password.
                    </td>
                  </tr> 
                  <?php
                  }
                  ?>
<!--                   <tr>
                    <td style="padding: 20px 0 0 0;">
                      <table class="buttonwrapper" style="background-color:red;" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td height="45">
                            <a href="https://b2b.xbridge.my/"><div class="button" height="45">Login Now</div></a>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr> -->

                </table>
              </td>
            </tr>            
          </table>
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      <!-- <tr>
        <td class="innerpadding borderbottom">
          <img class="fix" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/wide.png" width="100%" border="0" alt="" />
        </td>
      </tr> -->
<!--       <tr>
        <td class="innerpadding bodycopy">
          Please send us a copy of payment slip to support@xbridge.my once payment is made for Registration fees.
        </td>
      </tr> -->
      <tr>
        <td class="innerpadding bodycopy">
            You may check out our user manual guide to start exploring our B2B portal.
            <!-- <br> Our awesome Sunshine B2B mobile app is available at Google Play Store and iOS Store. Go get it now!  -->
        </td>
      </tr>            
        
      <tr>
        <td class="innerpadding bodycopy">
          Our aim to facilitate the relationship between supplier or manufacturer to retail and wholesale businesses replacing paper transaction.
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
    <!--[if (gte mso 9)|(IE)]>
          </td>
        </tr>
    </table>
    <![endif]-->
    </td>
  </tr>
</table>
</body>
</html>