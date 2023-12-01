<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FAQ extends CI_Controller {
    
    public function __construct()
	{
		parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        $this->load->library('pagination');
        $this->load->library('form_validation');

	}
    
    
    public function index()
    {
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
                if ($_SERVER['HTTPS'] !== "on") 
            {
            $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];   
            header("Location: $url");
            } 
                
                $user_guid = $_SESSION['user_guid'];


                if ( isset($_REQUEST['sv']) ) {

                    $search_value = $this->input->post('search_value');

                    $faq = $this->db->query("SELECT title, description, file_name FROM lite_b2b.faq WHERE active = '1' AND title LIKE '%$search_value%' ORDER BY seq ASC");
                } else{

                    $faq = $this->db->query("SELECT title, description, file_name FROM lite_b2b.faq WHERE active = '1' AND lang_type = 'EN' ORDER BY seq ASC");

                    $search_value = '';

                }

                $this->panda->get_uri();

                /*if ($_SESSION['user_group_name'] != "SUPER_ADMIN" ) {
                    $invoice_list = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main WHERE biller_guid = '$supplier_guid' AND inv_status != 'New' ");
                } else{

                    $invoice_list = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main ");

                }
*/
                
  
                $data = array(

                'faq' => $faq,
                'search_value' => $search_value
                
                );

                $this->panda->get_uri();
                $this->load->view('header');
                $this->load->view('faq/main', $data);
                $this->load->view('footer');  

            }
            else
            {
                $this->session->set_flashdata('message', 'Session Expired! Please relogin');
                redirect('#');
            }  
        
    }

        public function change_language()
    {
         
            $language_type = $_REQUEST['lt'];

            $faq = $this->db->query("SELECT title, description, file_name FROM lite_b2b.faq WHERE active = '1' AND lang_type = '$language_type' ORDER BY seq ASC")->result();            


            $data = array(
                'faq' => $faq
            );

            echo json_encode($data);
        
            

   
    }






}
?>