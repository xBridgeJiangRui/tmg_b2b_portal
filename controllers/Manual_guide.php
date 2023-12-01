<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class manual_guide extends CI_Controller {
    
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
            $customer_guid =  $_SESSION['customer_guid'];
            $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid,'web','manual_guide','sec_path','MNLGS');
            $defined_path = $file_config_main_path;

            if ( isset($_REQUEST['sv']) ) {

                $search_value = $this->input->post('search_value');

                $manual_guide = $this->db->query("SELECT mg.title, mg.description, mg.file_name FROM lite_b2b.mc_guide mg INNER JOIN mc_guide_c mgc ON mg.guide_guid = mgc.guide_guid WHERE mg.active = '1' AND mgc.customer_guid = '$customer_guid' AND mg.title LIKE '%$search_value%' ORDER BY mg.seq ASC");
            } else{

                $manual_guide = $this->db->query("SELECT mg.title, mg.description, mg.file_name FROM lite_b2b.mc_guide mg INNER JOIN mc_guide_c mgc ON mg.guide_guid = mgc.guide_guid WHERE mg.active = '1' AND mg.lang_type = 'EN' AND mgc.customer_guid = '$customer_guid' ORDER BY mg.seq ASC");

                $search_value = '';

            }

            $data = array(
                'manual_guide' => $manual_guide,
                'search_value' => $search_value,
                'defined_path' => $defined_path,
            );

            $this->load->view('header');
            $this->load->view('manual_guide/main', $data);
            $this->load->view('footer');  

        }else{
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }  
        
    }

    public function change_language()
    {
        $language_type = $_REQUEST['lt'];
        $customer_guid = $_SESSION['customer_guid'];
            
        $manual_guide = $this->db->query("SELECT mg.title, mg.description, mg.file_name FROM lite_b2b.mc_guide mg INNER JOIN mc_guide_c mgc ON mg.guide_guid = mgc.guide_guid WHERE mg.active = '1' AND mg.lang_type = '$language_type' AND mgc.customer_guid = '$customer_guid' ORDER BY mg.seq ASC")->result();

        $data = array(
            'manual_guide' => $manual_guide
        );

        echo json_encode($data);
        
    }





}
?>