<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller {
    
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
    
    
    public function index()
	{
        $this->load->view('login_page');
	}
    

	public function login_form()
    {
        $this->form_validation->set_rules('userID', 'UserID', 'trim|required');
        $this->form_validation->set_rules('userpass', 'Password', 'trim|required');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->load->view('login_page');
        }
        else
        {   
            
            $userID = $this->input->post('userID');
            $userpass = $this->input->post('userpass');
            
            $result  = $this->Login_model->login_data($userID, $userpass);
            if($result > 0)
            {
                //set the session variables
                $sessiondata = array(
                              
                    'userID' => $userID,
                    'userpass' => $userpass,
                    'loginuser' => TRUE
                             
                );
                         
                $this->session->set_userdata($sessiondata);
                // redirect("main_controller/home", $sessiondata);
                echo "<script> alert('succesfully loged in');</script>";
                    // $this->load->view('home', $sessiondata);
                redirect("Acc", $sessiondata);
            }
            else
            {
                echo "<scriptb> alert('authentication failed');</script>";
                $this->load->view('login_page');
            }
            
        }
    }

    public function test()
    {
            
        // $this->panda->load('index', 'welcome_message');
        $this->load->view('header');
        $this->load->view('welcome_message');
        $this->load->view('footer');
            
    }

}
?>