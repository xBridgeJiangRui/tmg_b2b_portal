<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>A Simple Responsive HTML Email</title>
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

color:black;
text-decoration: none;

}

  a:link {
    color:blue;
  text-decoration: none;
}

a:visited {
  color:purple;
  text-decoration: none;
}

  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  </style>
</head>

<body yahoo bgcolor="#f6f8f1" onload="generateQRCode()">
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
        <td bgcolor="#4778e3" class="header">
          <table width="70" align="left" border="0" cellpadding="0" cellspacing="0">  
            <tr>
              <td height="70" style="padding: 0 20px 20px 0;">
                <img style="border-radius: 15px;" class="fix" src="https://b2b.xbridge.my/asset/dist/img_sunshine/icon-512.png" width="70" height="70" border="0" alt="" />
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
                      B2B - Rexbridge Sdn Bhd
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
          <!--[if (gte mso 9)|(IE)]>
            <table width="380" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <table class="col380" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 100%;">  
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="bodycopy">
                      To <b><?php echo $email_info->row('company_name') ?></b>,<br><br>

                      Thanks for register for the training,<br><br>

                      Training Date : <b style=" color: red; "><?php echo date('j F Y',strtotime($info->row('training_date')));?></b><br>

                      Training Time :<b style=" color: red; "> <?php echo $info->row('training_time');?><br></b>

                      Training Location :<b><?php echo $info->row('training_location');?></b><br>

                      <?php 
                        if($info->row('training_location2') != '' && $info->row('training_location2') != null)
                        {
                          echo '<b>'.$info->row('training_location2').'</b><br>';
                        }

                        if($info->row('training_location3') != '' && $info->row('training_location3') != null)
                        {
                          echo '<b>'.$info->row('training_location3').'</b><br>';
                        }

                        if($info->row('training_location4') != '' && $info->row('training_location4') != null)
                        {
                          echo '<b>'.$info->row('training_location4').'</b><br>';
                        }

                        if($info->row('training_location5') != '' && $info->row('training_location5') != null)
                        {
                          echo '<b>'.$info->row('training_location5').'</b><br>';
                        }    
                      ?>

                      <a href="<?php echo $info->row('google_map_url');?>">(Google Maps)</a>
                      

                      <br><br>

                      Please find the attached QR code and training details for each of the registered participants under your company.
                      <br><br>
                      <b>You are required to flash the attached QR code before entering the training hall.</b> 
                      <b>Do remember to have the attached QR code with you while attending.</b><br><br>

 
                        <!-- Please send us a copy of payment slip to support@xbridge.my once payment is made for Registration fees and Training fees. -->
                  
                      

                      <br><br>

                       We can't wait to see you all soon!


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
      <!-- <tr>
        <td class="innerpadding borderbottom">
          <img class="fix" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/wide.png" width="100%" border="0" alt="" />
        </td>
      </tr> -->
      <!-- <tr>
        <td class="innerpadding bodycopy">
          Seats are limited per session(First come first serve)
        </td>
      </tr> -->
<!--       <tr>
        <td class="innerpadding bodycopy">
          Please send us a copy of payment slip to support@xbridge.my once payment is made for Registration fees.
        </td>
      </tr> -->
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
                <!-- &reg; --> 16-05-V01, Tower B, Vertical Business Suite II,Bangsar South City, <br>No. 8, Jalan Kerinchi, 59200 Kuala Lumpur.<br/>
                Tel: +6017 - 745 1185/+6017 - 715 9340<br>
        Fax: +603 - 2242 2781<br>
        Email: support@xbridge.my
                <!-- <a href="#" class="unsubscribe"><font color="#ffffff">Unsubscribe</font></a> 
                <span class="hide">from this newsletter instantly</span> -->
              </td>
            </tr>
            <!-- <tr>
              <td align="center" style="padding: 20px 0 0 0;">
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="37" style="text-align: center; padding: 0 10px 0 10px;">
                      <a href="http://www.facebook.com/">
                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/facebook.png" width="37" height="37" alt="Facebook" border="0" />
                      </a>
                    </td>
                    <td width="37" style="text-align: center; padding: 0 10px 0 10px;">
                      <a href="http://www.twitter.com/">
                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/twitter.png" width="37" height="37" alt="Twitter" border="0" />
                      </a>
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
</table>
</body>
</html>

