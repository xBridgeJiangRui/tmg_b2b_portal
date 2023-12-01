
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('tcpdf/tcpdf.php');



class Pdf_invoice_json extends TCPDF {

  //Page header
  public function Header() {

$com = new Panda("");
$test = $com->get_serverdb();


$conn = new mysqli($test['servername'],$test['username'],$test['password'],$test['dbname']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


if(isset($_REQUEST['trans']))
{
  $refno = $_REQUEST['trans'];
  $customer_guid = $_SESSION['customer_guid'];
  // echo $customer_guid;die;

$header_customer_guid = "SELECT customer_guid FROM b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ";
$result = $conn->query($header_customer_guid);
$header_customer_guid = $result->fetch_assoc();


// $header_customer_info = "SELECT BRANCH_CODE,BRANCH_NAME,REPLACE(BRANCH_ADD,'\n','<br>') AS BRANCH_ADD, BRANCH_TEL, BRANCH_FAX,comp_reg_no , gst_no FROM backend.`cp_set_branch` JOIN (SELECT comp_reg_no , gst_no FROM backend.`companyprofile` LIMIT 1 )a ";
// $result = $conn->query($header_customer_info);
// $header_customer_info = $result->fetch_assoc();




$header = "SELECT * FROM b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ";
$result = $conn->query($header);
$header = $result->fetch_assoc();



$customer_hq_branch_info = "SELECT * FROM b2b_summary.cp_set_branch WHERE SET_SUPPLIER_CODE = 'HQ' AND SET_CUSTOMER_CODE = 'HQ' AND customer_guid = '$customer_guid' ";
$result = $conn->query($customer_hq_branch_info);
$customer_hq_branch_info = $result->fetch_assoc();



$gr_info = "SELECT 
a.`loc_group` as Location
, a.`supplier_code` as Code
, a.`supplier_name` as Name
, a.`Invno`
FROM b2b_summary.grmain_info AS a 
LEFT JOIN b2b_summary.grmain_proposed AS b 
ON a.refno = b.refno 
AND a.customer_guid = b.customer_guid where a.refno = '$refno' and a.customer_guid = '$customer_guid'";
$result = $conn->query($gr_info);
$gr_info = $result->fetch_assoc();


$supcus_customer = "SELECT * FROM b2b_summary.supcus WHERE Code = '".$gr_info['Location']."' AND customer_guid = '$customer_guid' ";
$result = $conn->query($supcus_customer);
$supcus_customer = $result->fetch_assoc();

$supcus_supplier = "SELECT * FROM b2b_summary.supcus WHERE Code = '".$gr_info['Code']."' AND customer_guid = '$customer_guid' ";
$result = $conn->query($supcus_supplier);
$supcus_supplier = $result->fetch_assoc();


$customer_branch_info = "SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '".$gr_info['Location']."' AND customer_guid = '$customer_guid'";
$result = $conn->query($customer_branch_info);
$customer_branch_info = $result->fetch_assoc();

 $loc_dec = "SELECT name FROM b2b_summary.supcus WHERE code = '".$customer_branch_info['SET_SUPPLIER_CODE']."' AND customer_guid = '$customer_guid'";
$result = $conn->query($loc_dec);
$loc_dec = $result->fetch_assoc();

        $this->SetFont('helvetica', '', 9.5);
        ob_start();
        $html = '
<table class="table table-striped" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
<tr>
<td style="width: 80%;text-align: left">
        
       <table cellspacing="0" cellpadding="0">
         
        <tbody>
          
          <tr>
            
            <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;">
              
              Purchase from Registered GST Supplier

            </td>

            <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;">
              
              Goods Received Note Issued by

            </td>

          </tr>

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              
              <b>'.$supcus_supplier['Name'].'</b>
              
            </td>

            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              
              <b>'.$customer_branch_info['BRANCH_NAME'].' </b>
              
            </td>

          </tr>

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              
              Co Reg No: '.$supcus_supplier['reg_no'].'  
              
            </td>

            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              
              Co Reg No: '.$supcus_customer['reg_no'].'  
              
            </td>

          </tr>

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              <table>
                
                <td>'.$supcus_supplier['Add1'].'
                <br>'.$supcus_supplier['Add2'].'
                <br>'.$supcus_supplier['Add3'].'
                <br>'.$supcus_supplier['Add4'].'<br>
                </td>

              </table>
              
              
            </td>

            <td style="border-left: 1px solid black;border-right: 1px solid black;">
              <table>
                
                <td>'.$customer_branch_info['BRANCH_ADD'].'<br>
                </td>

              </table>
              
              
            </td>

          </tr>

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              
              <table>
                
                <td><br><br><b>Tel:</b> '.$supcus_supplier['Tel'].' <b>  Fax:</b> '.$supcus_supplier['Fax'].'</td>

              </table>
              
              
            </td>

             <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              
              <table>
                
                <td><br><br><b>Tel:</b> '.$customer_branch_info['BRANCH_TEL'].' <b>  Fax:</b> '.$customer_branch_info['BRANCH_FAX'].'</td>

              </table>
              
              
            </td>

          </tr>

          <tr>
            
            <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              
              <table>
                
                <td><b>Sup Code:</b> '.$gr_info['Code'].' - '.$gr_info['Name'].' <b><br>Received Loc:</b> '.$gr_info['Location'].' - '.$loc_dec['name'].'</td>

              </table>
              
              
            </td>

             <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
              
              <table>
                
                <td colspan="2"><b>Tax Invoice No:</b> '.$header['invno'].' <b><br>Delivery Order:</b> '.$header['dono'].'</td>

                <td  ><b>Invoice Date:</b> '.$header['inv_date'].' <b><br>Ref No:</b> '.$header['refno'].'</td>

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
                  
                  <td  style="height:60px;border: 1px solid black;" nowrap=""><p style=""> </p><p style="font-size:12px;text-align: center;"><b>E-Invoice</b></p></td>



                </tr>

         <tr>

                  <td style="height:60px; text-align: center; border: 1px solid black;" colspan="2"><p style="text-align:left;"> Inv No</p><p style="font-size:12px;"><b>'.$header['einvno'].'</b></p></td>


                </tr>


        </tbody>
      
        </table>


  </td>
</tr>

    </table>
    ';


        $this->WriteHTML($html, true, 0, true, 0);
        ob_end_clean();
      }//close isset
  
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

    

    if(isset($_REQUEST['trans']))
    {
      $refno = $_REQUEST['trans'];
    }
    else
    {
      $refno = $this->RefNo;
    }
    $customer_guid = $_SESSION['customer_guid'];
    $header_customer_guid = "SELECT customer_guid FROM b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ";
    $result = $conn->query($header_customer_guid);
    $header_customer_guid = $result->fetch_assoc();

    $header = "SELECT * FROM b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ";
    $result = $conn->query($header);
    $header = $result->fetch_assoc();

    $created_by = "SELECT * FROM lite_b2b.set_user WHERE user_guid = '".$header['created_by']."' AND acc_guid = '$customer_guid' ";
    $result = $conn->query($created_by);
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
    // $this->MultiCell(55, 10, $header['created_at']  , 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    // $this->MultiCell(55, 10, $header['created_by'], 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

    // New line for next 3 elements
    $this->Ln(10);

    // Second line of 3x "sometext"
    // $this->MultiCell(55, 10, 'Posted on', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    // $this->MultiCell(55, 10, $header['posted_at']  , 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    // $this->MultiCell(55, 10, $header['posted_by'], 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $this->MultiCell(55, 10, 'Issued on', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $this->MultiCell(55, 10, $header['created_at']  , 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $this->MultiCell(55, 10, $created_by['user_name'], 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

    $this->MultiCell(500, 40, 'Prepared by _____________  Checked by _____________  Approved by _____________  Accepted by _____________', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
  }
}

/* End of file Pdfheaderfooter.php */
