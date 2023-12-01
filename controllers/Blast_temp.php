<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class blast_temp extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {

        $this->load->view('welcome_message');
    }   


    public function attachment()
    {
        $file = $_REQUEST['file'];

        //echo $file;die;

        if($file == 'Bataras_STRB_announcement')
        {
            $type = 'attachment';
            // $file = ('uploads/hwathai/Hwa Thai_B2B letter to suppliers.pdf');  
            $file = ('uploads/bataras/Bataras_STRB_announcement.pdf'); 
            $b64Doc = chunk_split(base64_encode(file_get_contents($file)));
            $pdf_b64 = base64_decode($b64Doc);
            date_default_timezone_set("Asia/Kuala_Lumpur");
            $pdf_name1 = 'Bataras_STRB_announcement';
            header("Content-type: application/pdf");
            header('Content-Disposition: '.$type.'; filename="'.$pdf_name1.'.pdf"'); 
            echo $pdf_b64;
            die;            
        }
        else if($file == 'Bataras_phase2_announcement')
        {
            $type = 'attachment';
            // $file = ('uploads/hwathai/Hwa Thai_B2B letter to suppliers.pdf');  
            $file = ('uploads/bataras/B2B Letter Phase 2.pdf'); 
            $b64Doc = chunk_split(base64_encode(file_get_contents($file)));
            $pdf_b64 = base64_decode($b64Doc);
            date_default_timezone_set("Asia/Kuala_Lumpur");
            $pdf_name1 = 'Bataras_phase2_announcement';
            header("Content-type: application/pdf");
            header('Content-Disposition: '.$type.'; filename="'.$pdf_name1.'.pdf"'); 
            echo $pdf_b64;
            die;            
        }  
        else if($file == 'Bataras_consign_announcement')
        {
	    //echo 1;die;
            $type = 'attachment';
            // $file = ('uploads/hwathai/Hwa Thai_B2B letter to suppliers.pdf');  
            $file = ('/var/www/html/blast_email/uploads/bataras/B2B Letter Phase 2.pdf'); 
            $b64Doc = chunk_split(base64_encode(file_get_contents($file)));
            $pdf_b64 = base64_decode($b64Doc);
	    //echo $pdf_b64;die;
            date_default_timezone_set("Asia/Kuala_Lumpur");
            $pdf_name1 = 'Bataras_phase2_announcement';
            header("Content-type: application/pdf");
            header('Content-Disposition: '.$type.'; filename="'.$pdf_name1.'.pdf"'); 
            echo $pdf_b64;
            die;            
        }     
    }            
} ?>
