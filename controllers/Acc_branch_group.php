<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Acc_branch_group extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Acc_branch_group_model');
        $this->load->library('form_validation');        
	   $this->load->library('datatables');
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $data['details'] = $this->Acc_branch_group_model->get_all();
            $this->load->view('header');
            $this->load->view('acc_branch_group/acc_branch_group_list', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }

    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Acc_branch_group_model->json();
    }

    public function read($id) 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $row = $this->Acc_branch_group_model->get_by_id($id);
            if ($row) {
                $data = array(
    		'concept_guid' => $row->concept_guid,
    		'branch_group_guid' => $row->branch_group_guid,
    		'isactive' => $row->isactive,
    		'group_name' => $row->group_name,
    		'created_at' => $row->created_at,
    		'created_by' => $row->created_by,
    		'updated_at' => $row->updated_at,
    		'updated_by' => $row->updated_by,
            'concept_name' => $row->concept_name,
    	    );
                $this->load->view('header');
                $this->load->view('acc_branch_group/acc_branch_group_read', $data);
                $this->load->view('footer');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger text-center" style="font-size: 18px">Record Not Found<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect(site_url('Profile_setup'));
            }
        }
        else
        {
            redirect('login_c');
        }
    }

    public function create() 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $query_concept = $this->db->query("SELECT * FROM acc_concept a INNER JOIN acc b ON a.`acc_guid` = b.`acc_guid` WHERE a.isactive = 1");
            $concept = $query_concept->result();

            $data = array(
                'button' => 'Create',
                'action' => site_url('acc_branch_group/create_action'),
    	    'concept_guid' => set_value('concept_guid'),
    	    'branch_group_guid' => set_value('branch_group_guid'),
    	    'isactive' => set_value('isactive'),
    	    'group_name' => set_value('group_name'),
            'created_at' => set_value('created_at'),
            'created_by' => set_value('created_by'),
            'disabled' => 'disabled',
            'concept_select' => 'Select Concept',
            'concept' => $concept,
    	   );
            $this->load->view('header');
            $this->load->view('acc_branch_group/acc_branch_group_form', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }
    }
    
    public function create_action() 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $this->_rules();

            $query_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid");
            $guid = $query_guid->row('guid');

            $query_now = $this->db->query("SELECT NOW() AS datetime");
            $datetime = $query_now->row('datetime');

            $query_concept_guid = $this->db->query("SELECT concept_guid FROM acc_concept WHERE concept_name = '".$this->input->post('concept')."' ");
            $concept_guid = $query_concept_guid->row('concept_guid');


            if ($this->form_validation->run() == FALSE) {
                $this->create();
            } else {

                $check_data = $this->db->query("SELECT * from acc_branch_group where concept_guid = '$concept_guid' and group_name = '".$this->input->post('group_name')."' ");
                if($check_data->num_rows() > 0)
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Record Already Exist.<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect(site_url('Profile_setup'));
                }
                $data = array(
    		'concept_guid' => $concept_guid,
            'branch_group_guid' => $guid,
    		'isactive' => $this->input->post('isactive',TRUE),
    		'group_name' => $this->input->post('group_name',TRUE),
    		'created_at' => $datetime,
    		'created_by' => $_SESSION['userid'],
    		'updated_at' => $datetime,
    		'updated_by' => $_SESSION['userid'],
    	    );

                $this->Acc_branch_group_model->insert($data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Create Record Success<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect(site_url('Profile_setup'));
            }
        }
        else
        {
            redirect('login_c');
        }
    }
    
    public function update() 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $_SESSION['guid'] = $_REQUEST['guid'];
            $row = $this->Acc_branch_group_model->get_by_id($_SESSION['guid']);

            $query_concept = $this->db->query("SELECT concept_name,concept_guid FROM acc_concept WHERE isactive = '1'");
            $concept = $query_concept->result();

            if ($row) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('acc_branch_group/update_action'),
    		'concept_guid' => set_value('concept_guid', $row->concept_guid),
    		'branch_group_guid' => set_value('branch_group_guid', $row->branch_group_guid),
    		'isactive' => set_value('isactive', $row->isactive),
    		'group_name' => set_value('group_name', $row->group_name),
    		'created_at' => set_value('created_at', $row->created_at),
    		'created_by' => set_value('created_by', $row->created_by),
    		'updated_at' => set_value('updated_at', $row->updated_at),
    		'updated_by' => set_value('updated_by', $row->updated_by),
            'disabled' => '',
            'concept_select' => $row->concept_name,
            'concept' => $concept,
    	    );
                $this->load->view('header');
                $this->load->view('acc_branch_group/acc_branch_group_form', $data);
                $this->load->view('footer');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger text-center" style="font-size: 18px">Record Not Found<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect(site_url('Profile_setup'));
            }
        }
        else
        {
            redirect('login_c');
        }
    }
    
    public function update_action() 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $this->_rules();

            $query_now = $this->db->query("SELECT NOW() AS datetime");
            $datetime = $query_now->row('datetime');

            $query_concept_guid = $this->db->query
            ("SELECT concept_guid FROM acc_concept WHERE concept_name = '".$this->input->post('concept')."' ");
            $concept_guid = $query_concept_guid->row('concept_guid');

            if ($this->form_validation->run() == FALSE) {
                $this->update($_SESSION['guid']);
            } else {
                $data = array(
    		'concept_guid' => $concept_guid,
    		'isactive' => $this->input->post('isactive',TRUE),
    		'group_name' => $this->input->post('group_name',TRUE),
    		'created_at' => $this->input->post('created_at',TRUE),
    		'created_by' => $this->input->post('created_by',TRUE),
    		'updated_at' => $datetime,
    		'updated_by' => $_SESSION['userid'],
    	    );

                $this->Acc_branch_group_model->update($_SESSION['guid'], $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Update Record Success<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect(site_url('Profile_setup'));
            }
        }
        else
        {
            redirect('login_c');
        }
    }
    
    public function delete($id) 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $_SESSION['guid'] = $_REQUEST['guid'];
            $row = $this->Acc_branch_group_model->get_by_id($_SESSION['guid']);

            if ($row) {
                $this->Acc_branch_group_model->delete($_SESSION['guid']);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Delete Record Success<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect(site_url('Profile_setup'));
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger text-center" style="font-size: 18px">Record Not Found<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect(site_url('Profile_setup'));
            }
        }
        else
        {
            redirect('login_c');
        }
    }

    public function _rules() 
    {
	
    	//$this->form_validation->set_rules('isactive', 'isactive', 'trim|required');
    	$this->form_validation->set_rules('group_name', 'group name', 'trim|required');
        $this->form_validation->set_rules('concept', 'concept name', 'trim|required');

    	$this->form_validation->set_rules('branch_group_guid', 'branch_group_guid', 'trim');
    	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Acc_branch_group.php */
/* Location: ./application/controllers/Acc_branch_group.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-12-23 13:03:44 */
/* http://harviacode.com */