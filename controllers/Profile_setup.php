<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile_setup extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Profile_setup_model');
        $this->load->library('form_validation');        
	    $this->load->library('datatables');
    }

    function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            if($_SESSION['user_group_name'] == 'SUPER_ADMIN' )
            {
                if(isset($_REQUEST['concept_guid']))
                {
                    $account_branch = $this->db->query("SELECT b.concept_name, a.*, c.`group_name` FROM acc_branch a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc_branch_group c ON c.`branch_group_guid` = a.`branch_group_guid` where a.concept_guid = '".$_REQUEST['concept_guid']."' ORDER BY a.updated_at DESC");
                }
                elseif(isset($_REQUEST['branch_group_guid']))
                {
                    $account_branch = $this->db->query("SELECT b.concept_name, a.*, c.`group_name` FROM acc_branch a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc_branch_group c ON c.`branch_group_guid` = a.`branch_group_guid` where a.branch_group_guid = '".$_REQUEST['branch_group_guid']."' ORDER BY a.updated_at DESC");
                }
                else
                {
                    $account_branch = $this->db->query("SELECT b.concept_name, a.*, c.`group_name` FROM acc_branch a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc_branch_group c ON c.`branch_group_guid` = a.`branch_group_guid`    ORDER BY a.updated_at DESC");
                }

                $account = $this->db->query("SELECT * FROM acc  ");
                $account_concept =  $this->db->query("SELECT a.*,b.`acc_name` FROM acc_concept a INNER JOIN acc b ON a.`acc_guid` = b.`acc_guid`  ORDER BY a.updated_at DESC;");
                $account_branch_group = $this->db->query("SELECT a.*,b.`concept_name` FROM acc_branch_group a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid`  ORDER BY a.updated_at DESC");

            }
            /*elseif($_SESSION['user_group_name'] == 'SUPP_CLERK' || $_SESSION['user_group_name'] == 'SUPP_ADMIN' || $_SESSION['user_group_name'] == 'CUS_ADMIN'|| $_SESSION['user_group_name'] == 'CUS_CLERK' )*/
            else
            {
                if(isset($_REQUEST['concept_guid']))
                {
                    $account_branch = $this->db->query("SELECT b.concept_name, a.*, c.`group_name` FROM acc_branch a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc_branch_group c ON c.`branch_group_guid` = a.`branch_group_guid` where a.concept_guid = '".$_REQUEST['concept_guid']."' ORDER BY a.updated_at DESC");
                }
                elseif(isset($_REQUEST['branch_group_guid']))
                {
                    $account_branch = $this->db->query("SELECT b.concept_name, a.*, c.`group_name` FROM acc_branch a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc_branch_group c ON c.`branch_group_guid` = a.`branch_group_guid` where a.branch_group_guid = '".$_REQUEST['branch_group_guid']."' ORDER BY a.updated_at DESC");
                }
                else
                {
                    $account_branch = $this->db->query("SELECT b.concept_name, a.*, c.`group_name` FROM acc_branch a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc_branch_group c ON c.`branch_group_guid` = a.`branch_group_guid` where b.acc_guid = '".$_SESSION['customer_guid']."' ORDER BY a.updated_at DESC");
                }

                $account = $this->db->query("SELECT * FROM acc where acc_guid = '".$_SESSION['customer_guid']."'");
                $account_concept =  $this->db->query("SELECT a.*,b.`acc_name` FROM acc_concept a INNER JOIN acc b ON a.`acc_guid` = b.`acc_guid` where a.acc_guid = '".$_SESSION['customer_guid']."' ORDER BY a.updated_at DESC;");
                $account_branch_group = $this->db->query("SELECT a.*,b.`concept_name` FROM acc_branch_group a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid`  where b.acc_guid = '".$_SESSION['customer_guid']."'  ORDER BY a.updated_at DESC"); 
            }

            $data = array(

                'account' => $account, 
                'account_concept' => $account_concept, 
                'account_branch' => $account_branch, 
                'account_branch_group' => $account_branch_group,

                );
            $this->load->view('header');
            $this->load->view('profile_setup', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }
    }

    // public function check()
    // {
    //     if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
    //     {

    //         $guid = $this->input->post('guid[]');       
    //         $active = $this->input->post('active[]');
            
    //         $data = array();

    //         foreach($guid as $i => $id) {
    //             $data[] = [$_REQUEST['col_guid'] => $id, $_REQUEST['col_check'] => $active[$i] ];
    //         }

    //         $this->db->update_batch($_REQUEST['table'], $data, $_REQUEST['col_guid']);
    //         // echo $this->db->last_query();die;
    //         redirect('Profile_setup');
    //     }
    //     else
    //     {
    //         redirect('login_c');
    //     }
    // }

}