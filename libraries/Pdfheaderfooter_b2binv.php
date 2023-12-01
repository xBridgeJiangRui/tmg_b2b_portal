
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('tcpdf/tcpdf.php');



class Pdfheaderfooter_b2binv extends TCPDF {

  //Page header
  public function Header() {



$servername = "127.0.0.1";
$username = "panda_web";
$password = "web@adnap";
$dbname = "b2b_invoice";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$name = "SELECT `value` FROM company_profile WHERE `type` = 'name'";
$result = $conn->query($name);
$name = $result->fetch_assoc();

$reg_no = "SELECT `value` FROM company_profile WHERE `type` = 'reg_no'";
$result = $conn->query($reg_no);
$reg_no = $result->fetch_assoc();

$tel = "SELECT `value` FROM company_profile WHERE `type` = 'tel'";
$result = $conn->query($tel);
$tel = $result->fetch_assoc();

$fax = "SELECT `value` FROM company_profile WHERE `type` = 'fax'";
$result = $conn->query($fax);
$fax = $result->fetch_assoc();

$add1 = "SELECT `value` FROM company_profile WHERE `type` = 'add1'";
$result = $conn->query($add1);
$add1 = $result->fetch_assoc();

$add2 = "SELECT `value` FROM company_profile WHERE `type` = 'add2'";
$result = $conn->query($add2);
$add2 = $result->fetch_assoc();

$add3 = "SELECT `value` FROM company_profile WHERE `type` = 'add3'";
$result = $conn->query($add3);
$add3 = $result->fetch_assoc();

$email = "SELECT `value` FROM company_profile WHERE `type` = 'email'";
$result = $conn->query($email);
$email = $result->fetch_assoc();

$invoice_number = $_SESSION['inv_no'];

$guid = $_SESSION['guid'];

$qrcode_link = site_url('i_d?g='.$guid);



  $image_file = K_PATH_IMAGES.'logo.jpg';
        $this->Image($image_file, 20, 10, 85, 85, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetFont('helvetica', '', 9.5);

        $html = 

        '<table cellspacing="0" cellpadding="1" >
    <tr>
        <td width="80%"><b>'.$name['value'] .' ('.$reg_no['value'].') </b>
        <br />'.$add1['value'].'
        <br />'.$add2['value'].'
        <br />'.$add3['value'].'
        <br />Tel: '.$tel['value'].' Fax: '.$fax['value'].'
        <br />Email: '.$email['value'].'

        </td>
        

        <td style="font-size: 14px; " width="20%"><b>'.$invoice_number.'</b><br></td>
    </tr>
    
</table>



<br>
<hr>
<br>
';


        $this->WriteHTML($html, true, 0, true, 0);
        // QRCODE,M : QR-CODE Medium error correction
 // new style
$style = array(
    'border' => false,
    'padding' => 0,
    'fgcolor' => array(0,0,0),
    'bgcolor' => false
);
// QRCODE,H : QR-CODE Best error correction
//invoice number only
$this->write2DBarcode($qrcode_link, 'QRCODE,M', 497, 30, 60, 60, $style, 'N');

//filepath 
/*$this->write2DBarcode($_SERVER['DOCUMENT_ROOT'] .'invoice/invoice/B2B_'.$invoice_number['invoice_number'].'.pdf', 'QRCODE,H', 497, 30, 60, 60, $style, 'N');*/
 
  }
 
  // Page footer
  public function Footer() {
    // Position at 15 mm from bottom
    $this->SetY(-20);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
  }
}

/* End of file Pdfheaderfooter.php */