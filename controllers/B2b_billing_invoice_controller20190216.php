<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class b2b_billing_invoice_controller extends CI_Controller {
    
    public function __construct()
	{
		parent::__construct();
        $this->load->model('Login_model');
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper(array('form','url'));
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');

	}
    
    
    public function invoices()
    {

            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
            {   
                $user_guid = $_SESSION['user_guid'];

                $supplier_guid = $this->db->query("SELECT supplier_guid FROM lite_b2b.set_supplier_user_relationship WHERE user_guid = '$user_guid' ")->row('supplier_guid');

                if ($_SESSION['user_group_name'] != "SUPER_ADMIN" ) {
                    $invoice_list = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main WHERE biller_guid = '$supplier_guid' AND inv_status != 'New' ");
                } else{

                    $invoice_list = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main ");

                }

                
  
                $data = array(

                'invoice' => $invoice_list,
                
                );

                $this->panda->get_uri();
                $this->load->view('header');
                $this->load->view('b2b_billing_invoice/invoices', $data);
                $this->load->view('footer');  

            }
            else
            {
                redirect('main_controller');
            }  
        
    }

    public function invoices_process()
    {   
        $user_guid = $_SESSION['user_guid'];

        $supplier_guid = $this->db->query("SELECT supplier_guid FROM lite_b2b.set_supplier_user_relationship WHERE user_guid = '$user_guid' ")->row('supplier_guid');

        $invoice_number = $_REQUEST['inv'];

        $_SESSION['inv_no'] = $invoice_number;

        $checking = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main WHERE invoice_number = '$invoice_number' ")->num_rows();

        $reportchildinfo = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_child WHERE invoice_number = '$invoice_number' " );

        $reportheaderinfo = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main WHERE invoice_number = '$invoice_number' ")->row();

        if ($_SESSION['user_group_name'] == "SUPER_ADMIN") {
            
            $taxpercent = $reportheaderinfo->tax/$reportheaderinfo->subtotal_aft_disc*100;

        //payment instruction
        $abc = $this->db->query("SELECT query FROM b2b_invoice.set_report WHERE `describe` = 'payment' ")->row('query');
                 $execute = $this->db->query($abc);

        $data = array(

                'execute' => $execute, // payment instructions
                'table' => $reportchildinfo, //child
                'reportheaderinfo' => $reportheaderinfo, //header
                'checking' => $checking, //diff table view if exist
                'discountamount' => $reportheaderinfo->discount, //if = 0 not show discount row in view
                'taxpercent' =>$taxpercent,

                'name' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value'),
                'reg_no' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'reg_no' ")->row('value'),
                'tel' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'tel' ")->row('value'),
                'fax' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'fax' ")->row('value'),
                'add1' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add1' ")->row('value'),
                'add2' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add2' ")->row('value'),
                'add3' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add3' ")->row('value'),
                'email' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'email' ")->row('value'),
                'website' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'website' ")->row('value'),    

            );

              
             $name = $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value');

        if ($checking != 0 ) { 

            $haha = $this->load->view('b2b_billing_invoice/invoice_pdf', $data, true);
            /*$this->load->library('Pdf');*/
            $this->load->library('Pdfheaderfooter_b2binv');
            $pdf = new Pdfheaderfooter_b2binv('P', 'mm', 'A4', true, 'UTF-8', false);
            ob_start();
            $pdf->SetTitle($invoice_number);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->SetAuthor($name);
            $pdf->SetDisplayMode('real', 'default');
            $pdf->SetMargins(PDF_MARGIN_LEFT, 100, PDF_MARGIN_RIGHT);
            $pdf->setPageUnit('pt');
            $x = $pdf->pixelsToUnits('20');
            $y = $pdf->pixelsToUnits('20');
            $font_size = $pdf->pixelsToUnits('9.5');
            $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
            $pdf->AddPage();
            ob_start();
            $pdf->writeHTML($haha, true, false, true, false, '');
            ob_end_clean();
            $pdf->Output('B2B_'.$invoice_number.'.pdf', 'I');        

            }

            else{

                echo "<script> alert('Invoice ".$invoice_number." doest not exist in b2b_invoice, please contact support. ');</script>";
                echo "<script> document.location='" . base_url() . "/index.php/b2b_billing_invoice_controller/invoices' </script>";
            }

        }

        else if ($supplier_guid != $reportheaderinfo->biller_guid ) {
        echo "<script> alert('You are not authorized.');</script>";
        echo "<script> document.location='" . base_url() . "/index.php/b2b_billing_invoice_controller/invoices' </script>";
        }

        else{

        $taxpercent = $reportheaderinfo->tax/$reportheaderinfo->subtotal_aft_disc*100;

        //payment instruction
        $abc = $this->db->query("SELECT query FROM b2b_invoice.set_report WHERE `describe` = 'payment' ")->row('query');
                 $execute = $this->db->query($abc);

        $data = array(

                'execute' => $execute, // payment instructions
                'table' => $reportchildinfo, //child
                'reportheaderinfo' => $reportheaderinfo, //header
                'checking' => $checking, //diff table view if exist
                'discountamount' => $reportheaderinfo->discount, //if = 0 not show discount row in view
                'taxpercent' =>$taxpercent,

                'name' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value'),
                'reg_no' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'reg_no' ")->row('value'),
                'tel' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'tel' ")->row('value'),
                'fax' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'fax' ")->row('value'),
                'add1' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add1' ")->row('value'),
                'add2' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add2' ")->row('value'),
                'add3' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add3' ")->row('value'),
                'email' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'email' ")->row('value'),
                'website' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'website' ")->row('value'),    

            );

              
             $name = $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value');

        if ($checking != 0 ) { 

            $haha = $this->load->view('b2b_billing_invoice/invoice_pdf', $data, true);
            /*$this->load->library('Pdf');*/
            $this->load->library('Pdfheaderfooter_b2binv');
            $pdf = new Pdfheaderfooter_b2binv('P', 'mm', 'A4', true, 'UTF-8', false);
            ob_start();
            $pdf->SetTitle($invoice_number);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->SetAuthor($name);
            $pdf->SetDisplayMode('real', 'default');
            $pdf->SetMargins(PDF_MARGIN_LEFT, 100, PDF_MARGIN_RIGHT);
            $pdf->setPageUnit('pt');
            $x = $pdf->pixelsToUnits('20');
            $y = $pdf->pixelsToUnits('20');
            $font_size = $pdf->pixelsToUnits('9.5');
            $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
            $pdf->AddPage();
            ob_start();
            $pdf->writeHTML($haha, true, false, true, false, '');
            ob_end_clean();
            $pdf->Output('B2B_'.$invoice_number.'.pdf', 'I');        

            }

            else{

                echo "<script> alert('Invoice ".$invoice_number." doest not exist in b2b_invoice, please contact support. ');</script>";
                echo "<script> document.location='" . base_url() . "/index.php/b2b_billing_invoice_controller/invoices' </script>";

            }

        }
            
    }


}
?>