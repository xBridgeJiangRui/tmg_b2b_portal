<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Acc_branch extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Acc_branch_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
    	if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
		    $data['details'] = $this->Acc_branch_model->get_all();
		    $this->load->view('header');
		    $this->load->view('acc_branch/acc_branch_list', $data);
		    $this->load->view('footer');
		}
		else
		{
			redirect('login_c');
		}
    }	 	
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Acc_branch_model->json();
    }

    public function read($id) 
    {
    	if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
	        $row = $this->Acc_branch_model->get_by_id($id);
	        if ($row) {
	            $data = array(
			'concept_name' => $row->concept_name,
			'branch_guid' => $row->branch_guid,
			'isactive' => $row->isactive,
			'branch_code' => $row->branch_code,
			'branch_name' => $row->branch_name,
			'branch_regno' => $row->branch_regno,
			'branch_gstno' => $row->branch_gstno,
			'branch_fax' => $row->branch_fax,
			'branch_add1' => $row->branch_add1,
			'branch_add2' => $row->branch_add2,
			'branch_add3' => $row->branch_add3,
			'branch_add4' => $row->branch_add4,
			'branch_postcode' => $row->branch_postcode,
			'branch_state' => $row->branch_state,
			'branch_country' => $row->branch_country,
			'created_at' => $row->created_at,
			'created_by' => $row->created_by,
			'updated_at' => $row->updated_at,
			'updated_by' => $row->updated_by,
		    );
	            $this->load->view('header');
	            $this->load->view('acc_branch/acc_branch_read', $data);
	            $this->load->view('footer');
	        } else {
	            $this->session->set_flashdata('message', 'Record Not Found');
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
	    	$query_concept = $this->db->query("SELECT a.*,b.`acc_name` FROM acc_concept a INNER JOIN acc b ON a.`acc_guid` = b.`acc_guid` where a.isactive = '1' ORDER BY a.updated_at DESC;");
	        $concept = $query_concept->result();

	        $query_branch_group = $this->db->query("SELECT a.*,b.`concept_name` FROM acc_branch_group a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` where a.isactive = '1' ORDER BY a.updated_at DESC");
	        $branch_group = $query_branch_group->result();

	        $data = array(
	            'button' => 'Create',
	            'action' => site_url('acc_branch/create_action'),
		    'concept_guid' => set_value('concept_guid'),
		    'branch_guid' => set_value('branch_guid'),
		    'isactive' => set_value('isactive'),
		    'branch_code' => set_value('branch_code'),
		    'branch_name' => set_value('branch_name'),
		    'branch_regno' => set_value('branch_regno'),
		    'branch_gstno' => set_value('branch_gstno'),
		    'branch_fax' => set_value('branch_fax'),
		    'branch_add1' => set_value('branch_add1'),
		    'branch_add2' => set_value('branch_add2'),
		    'branch_add3' => set_value('branch_add3'),
		    'branch_add4' => set_value('branch_add4'),
		    'branch_postcode' => set_value('branch_postcode'),
		    'branch_state' => set_value('branch_state'),
		    'branch_country' => set_value('branch_country'),
		    'created_at' => set_value('created_at'),
		    'created_by' => set_value('created_by'),
		    'updated_at' => set_value('updated_at'),
		    'updated_by' => set_value('updated_by'),
		    'disabled' => 'disabled',
		    'concept_select' => 'Select Concept',
		    'concept' => $concept,
		    'branch_group' => $branch_group,
		    'branch_group_select' => 'Select Branch Group',
		);
	        $this->load->view('header');
	        $this->load->view('acc_branch/acc_branch_form', $data);
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

			$concept_guid = $this->input->post('concept');

	        if ($this->form_validation->run() == FALSE) {
	            $this->create();
	        } else {
	            $data = array(
			'branch_guid' => $guid,
			'concept_guid' => $concept_guid,
			'branch_group_guid' => $this->input->post('branch_group',TRUE),
			'isactive' => $this->input->post('isactive',TRUE),
			'branch_code' => $this->input->post('branch_code',TRUE),
			'branch_name' => $this->input->post('branch_name',TRUE),
			'branch_regno' => $this->input->post('branch_regno',TRUE),
			'branch_gstno' => $this->input->post('branch_gstno',TRUE),
			'branch_fax' => $this->input->post('branch_fax',TRUE),
			'branch_add1' => $this->input->post('branch_add1',TRUE),
			'branch_add2' => $this->input->post('branch_add2',TRUE),
			'branch_add3' => $this->input->post('branch_add3',TRUE),
			'branch_add4' => $this->input->post('branch_add4',TRUE),
			'branch_postcode' => $this->input->post('branch_postcode',TRUE),
			'branch_state' => $this->input->post('branch_state',TRUE),
			'branch_country' => $this->input->post('branch_country',TRUE),
			'created_at' => $datetime,
			'created_by' => $_SESSION['userid'],
			'updated_at' => $datetime,
			'updated_by' => $_SESSION['userid'],
		    );

	            $this->Acc_branch_model->insert($data);
	            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
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
	        $row = $this->Acc_branch_model->get_by_id($_SESSION['guid']);

	        $query_concept = $this->db->query("SELECT a.*,b.`acc_name` FROM acc_concept a INNER JOIN acc b ON a.`acc_guid` = b.`acc_guid` where a.isactive = '1' ORDER BY a.updated_at DESC;");
	        $concept = $query_concept->result();

	        $query_branch_group = $this->db->query("SELECT a.*,b.`concept_name` FROM acc_branch_group a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` where a.isactive = '1' ORDER BY a.updated_at DESC");
	        $branch_group = $query_branch_group->result();

	        if ($row) {
	            $data = array(
	                'button' => 'Update',
	                'action' => site_url('acc_branch/update_action'),
					'concept_guid' => set_value('concept_guid', $row->concept_guid),
					'branch_guid' => set_value('branch_guid', $row->branch_guid),
					'isactive' => set_value('isactive', $row->isactive),
					'branch_code' => set_value('branch_code', $row->branch_code),
					'branch_name' => set_value('branch_name', $row->branch_name),
					'branch_regno' => set_value('branch_regno', $row->branch_regno),
					'branch_gstno' => set_value('branch_gstno', $row->branch_gstno),
					'branch_fax' => set_value('branch_fax', $row->branch_fax),
					'branch_add1' => set_value('branch_add1', $row->branch_add1),
					'branch_add2' => set_value('branch_add2', $row->branch_add2),
					'branch_add3' => set_value('branch_add3', $row->branch_add3),
					'branch_add4' => set_value('branch_add4', $row->branch_add4),
					'branch_postcode' => set_value('branch_postcode', $row->branch_postcode),
					'branch_state' => set_value('branch_state', $row->branch_state),
					'branch_country' => set_value('branch_country', $row->branch_country),
					'created_at' => set_value('created_at', $row->created_at),
					'created_by' => set_value('created_by', $row->created_by),
					'disabled' => '',
					'concept_select' => $row->concept_name,
					'concept_guid' => $row->concept_guid,
					'concept' => $concept,
					'branch_group' => $branch_group,
		    		'branch_group_select' => $row->group_name,
		    		'branch_group_select_guid' => $row->branch_group_guid,

		    );
				$this->load->view('header');
	            $this->load->view('acc_branch/acc_branch_form', $data);
	            $this->load->view('footer');
	        } else {
	            $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Record Not Found<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
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

			/* $query_concept_guid = $this->db->query
			("SELECT concept_guid FROM acc_concept WHERE concept_name = '".$this->input->post('concept')."' ");
			$concept_guid = $query_concept_guid->row('concept_guid');*/

			$concept_guid = $this->input->post('concept');
			 
			$query_branch_guid = $this->db->query
			("SELECT branch_group_guid FROM acc_branch WHERE branch_guid = '".$_SESSION['guid']."' ");
			$branch_group_guid = $query_branch_guid->row('branch_group_guid');



	        if ($this->form_validation->run() == FALSE) {
	            $this->update($_SESSION['guid']);
	        } else {
	            $data = array(
			'concept_guid' => $concept_guid,
			'branch_group_guid' => $branch_group_guid,
			'isactive' => $this->input->post('isactive',TRUE),
			'branch_code' => $this->input->post('branch_code',TRUE),
			'branch_name' => $this->input->post('branch_name',TRUE),
			'branch_regno' => $this->input->post('branch_regno',TRUE),
			'branch_gstno' => $this->input->post('branch_gstno',TRUE),
			'branch_fax' => $this->input->post('branch_fax',TRUE),
			'branch_add1' => $this->input->post('branch_add1',TRUE),
			'branch_add2' => $this->input->post('branch_add2',TRUE),
			'branch_add3' => $this->input->post('branch_add3',TRUE),
			'branch_add4' => $this->input->post('branch_add4',TRUE),
			'branch_postcode' => $this->input->post('branch_postcode',TRUE),
			'branch_state' => $this->input->post('branch_state',TRUE),
			'branch_country' => $this->input->post('branch_country',TRUE),
			'created_at' => $this->input->post('created_at',TRUE),
			'created_by' => $this->input->post('created_by',TRUE),
			'updated_at' => $datetime,
			'updated_by' => $_SESSION['userid'],
		    );

	            $this->Acc_branch_model->update($_SESSION['guid'], $data);
	            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
	            redirect(site_url('Profile_setup'));
	        }
	    }
	    else
	    {
	    	redirect('login_c');
	    }
    }
    
    public function delete() 
    {
    	if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
	    	$_SESSION['guid'] = $_REQUEST['guid'];
	        $row = $this->Acc_branch_model->get_by_id($_SESSION['guid']);

	        if ($row) {
	            $this->Acc_branch_model->delete($_SESSION['guid']);
	            $this->session->set_flashdata('message', 'Delete Record Success');
	            redirect(site_url('Profile_setup'));
	        } else {
	            $this->session->set_flashdata('message', 'Record Not Found');
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
	$this->form_validation->set_rules('concept', 'concept name', 'trim|required');
	// $this->form_validation->set_rules('isactive', 'isactive', 'trim|required');
	$this->form_validation->set_rules('branch_code', 'branch code', 'trim|required');
	$this->form_validation->set_rules('branch_name', 'branch name', 'trim|required');
	$this->form_validation->set_rules('branch_regno', 'branch regno', 'trim|required');
	$this->form_validation->set_rules('branch_gstno', 'branch gstno', 'trim|required');
	$this->form_validation->set_rules('branch_fax', 'branch fax', 'trim|required');
	$this->form_validation->set_rules('branch_add1', 'branch add1', 'trim|required');
	$this->form_validation->set_rules('branch_add2', 'branch add2', 'trim|required');
	$this->form_validation->set_rules('branch_add3', 'branch add3', 'trim|required');
	$this->form_validation->set_rules('branch_add4', 'branch add4', 'trim|required');
	$this->form_validation->set_rules('branch_postcode', 'branch postcode', 'trim|required');
	$this->form_validation->set_rules('branch_state', 'branch state', 'trim|required');
	$this->form_validation->set_rules('branch_country', 'branch country', 'trim|required');

	$this->form_validation->set_rules('branch_guid', 'branch_guid', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Acc_branch.php */
/* Location: ./application/controllers/Acc_branch.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-12-23 13:03:44 */
/* http://harviacode.com */