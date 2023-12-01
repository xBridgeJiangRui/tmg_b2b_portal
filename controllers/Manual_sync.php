<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class manual_sync extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->library('form_validation');        
	    $this->load->library('datatables');
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {   
            $location = $_SESSION['location'];

            $data = array (
                'location' => $this->db->query("SELECT * from config order by location asc"),
                );
            
            $this->load->view('header');
            $this->load->view('manual_sync/sync',$data);
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function submit_sync()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $bizdate = $this->input->post('bizdate');
            $location = $this->input->post('location');

            if($bizdate >= date("Y-m-d"))
            {
                $this->session->set_flashdata('warning', 'Invalid Date');
                redirect('manual_sync');
            };

            $check_db = $this->db->query("select sync_db from config where location = '$location'")->row('sync_db');

            $table = $check_db.'.sqlscript';

            $script = "UPDATE backend.acc_trans_c2 set hq_update = 0 where bizdate = '$bizdate' and outlet = '$location';";

            $data = array (
                'refno' => $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid ")->row('guid'),
                'sqlscript' => $script,
                'createddatetime' => $this->db->query("SELECT now() as now ")->row('now'),
                'createdby' => $_SESSION['userid'],
                'status' => '0',
                'keyfield' => 'Cash Mgmt',
                );

            $this->General_model->insert_data($table, $data);

            $logtable = 'user_log';
            $logdata = array (
                'trans_guid' => $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid ")->row('guid'),
                'module' => 'manual_sync',
                'field' => 'Sync',
                'value_guid' => $bizdate,
                'value_from' => $location,
                'created_at' => $this->db->query("SELECT now() as now ")->row('now'),
                'created_by' => $_SESSION['userid'],
                );

            $this->General_model->insert_log($logtable, $logdata);
            redirect('manual_sync');

        }
        else
        {
            redirect('#');
        }
    }
}
