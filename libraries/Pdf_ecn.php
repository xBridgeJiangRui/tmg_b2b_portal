
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('tcpdf/tcpdf.php');



class Pdf_ecn extends TCPDF {

  //Page header
  
public function Header() {
 

 
        $this->SetFont('helvetica', '', 9.5);
        ob_start();
        $html = ' ';


        $this->WriteHTML($html, true, 0, true, 0);
     ob_end_clean();
  }
 
  // Page footer
  public function Footer() {

    $com = new Panda("");
    $test = $com->get_serverdb();

    // Create connection
    $conn = new mysqli($test['servername'],$test['username'],$test['password'],$test['dbname']);    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    if(isset($_REQUEST['refno']))
    {
      $refno = $_REQUEST['refno'];
    }
    else
    {
      $refno = $this->RefNo;
    }

    if(isset($_REQUEST['transtype']))
    {
      $transtype = $_REQUEST['transtype'];
    }
    else
    {
      $transtype = $this->transtype;
    }

    $customer_guid = $_SESSION['customer_guid'];

    $header = "SELECT * FROM b2b_summary.ecn_main WHERE refno = '$refno' AND type = '$transtype' AND customer_guid = '$customer_guid' ";
    $result = $conn->query($header);
    $header = $result->fetch_assoc();

    $created_by = "SELECT * FROM lite_b2b.set_user  WHERE user_guid = '".$header['created_by']."' AND acc_guid = '$customer_guid' ";
    $result = $conn->query($created_by);
    // print_r($result);die;
    // foreach($result as $row) {
    // print_r($row['user_name']);
    // // do something with each row
    // }die;
    $created_by = $result->fetch_assoc();
 
   // Make more space in footer for additional text
    $this->SetY(-25);

    // CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
/*$this->Cell(0, 0, $refno, 0, 1);
$this->write1DBarcode($refno, 'C39', '', '', '', 300, 2, $style, 'N');*/

    $this->SetFont('helvetica', 'I', 8);

    // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

    // New line in footer
    $this->Ln(0);

    // First line of 3x "sometext"
    // $this->MultiCell(55, 10, 'Issued on', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    // $this->MultiCell(55, 10, $header['created_at']   , 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    // $this->MultiCell(55, 10, $created_by['user_name'] , 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

    // New line for next 3 elements
    $this->Ln(10);

    // Second line of 3x "sometext"
    // $this->MultiCell(55, 10, 'Posted on', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    // $this->MultiCell(55, 10,''  , 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    // $this->MultiCell(55, 10, '', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

    $this->MultiCell(55, 10, 'Issued on', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $this->MultiCell(55, 10, $header['created_at']   , 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $this->MultiCell(55, 10, $created_by['user_name'] , 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

    $this->MultiCell(500, 40, 'Prepared by _____________  Checked by _____________  Approved by _____________  Accepted by _____________', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
  }
}

/* End of file Pdfheaderfooter.php */