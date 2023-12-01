
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('tcpdf/tcpdf.php');

class Daily_attachment extends TCPDF
{

    //Page header

    //     public function Header()
    //     {



    //         $this->SetFont('helvetica', '', 9.5);
    //         ob_start();
    //         $html = ' ';


    //         $this->WriteHTML($html, true, 0, true, 0);
    //         ob_end_clean();
    //     }

    //     // Page footer
    //     public function Footer()
    //     {


    //         // Make more space in footer for additional text
    //         $this->SetY(-25);

    //         // CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
    //         /*$this->Cell(0, 0, $refno, 0, 1);
    // $this->write1DBarcode($refno, 'C39', '', '', '', 300, 2, $style, 'N');*/

    //         $this->SetFont('helvetica', 'I', 8);

    //         // Page number
    //         $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

    //         // New line in footer
    //         $this->Ln(0);

    //         // First line of 3x "sometext"
    //         $this->MultiCell(55, 10, 'Issued on', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    //         $this->MultiCell(55, 10, '', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    //         $this->MultiCell(55, 10, '', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

    //         // New line for next 3 elements
    //         $this->Ln(10);

    //         // Second line of 3x "sometext"
    //         $this->MultiCell(55, 10, 'Posted on', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    //         $this->MultiCell(55, 10, '', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    //         $this->MultiCell(55, 10, '', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

    //         $this->MultiCell(500, 40, 'Prepared by _____________  Checked by _____________  Approved by _____________  Accepted by _____________', 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    // }
}

/* End of file Pdfheaderfooter.php */