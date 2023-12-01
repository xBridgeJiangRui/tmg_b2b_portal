<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class policy extends CI_Controller {
    
    public function __construct()
	{
		parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        $this->load->library('pagination');
        $this->load->library('form_validation');

	}
    
    
    public function privacy_policy_en()
    {
        $this->load->view('policy/privacy_policy_en'); 
    }


    public function privacy_policy_bm()
    {
        $this->load->view('policy/privacy_policy_bm');     
    }

   





}
?>