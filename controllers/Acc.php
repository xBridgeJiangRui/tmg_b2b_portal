<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Acc extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Acc_model');
        $this->load->library('form_validation');        
		$this->load->library('datatables');
    }

    public function index()
    {
    	if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
	    	$data['details'] = $this->db->query("SELECT * from acc");
	    	$this->load->view('header');
		    $this->load->view('acc/acc_list', $data);
		    $this->load->view('footer');
		}
		else
		{
			redirect('login_c');
		}
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Acc_model->json();
    }

    public function read($id) 
    {
    	if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
	        $row = $this->Acc_model->get_by_id($id);
	        if ($row) {
	            $data = array(
			'acc_guid' => $row->acc_guid,
			'isactive' => $row->isactive,
			'acc_name' => $row->acc_name,
			'acc_regno' => $row->acc_regno,
			'acc_gstno' => $row->acc_gstno,
			'acc_taxcode' => $row->acc_taxcode,
			'acc_add1' => $row->acc_add1,
			'acc_add2' => $row->acc_add2,
			'acc_add3' => $row->acc_add3,
			'acc_add4' => $row->acc_add4,
			'acc_postcode' => $row->acc_postcode,
			'acc_state' => $row->acc_state,
			'acc_country' => $row->acc_country,
			'created_at' => $row->created_at,
			'created_by' => $row->created_by,
			'updated_at' => $row->updated_at,
			'updated_by' => $row->updated_by,
		    );
	            $this->load->view('header');
	            $this->load->view('acc/acc_read', $data);
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
	        $data = array(
	            'button' => 'Create',
	            'action' => site_url('acc/create_action'),
		    'acc_guid' => set_value('acc_guid'),
		    'isactive' => set_value('isactive'),
		    'acc_name' => set_value('acc_name'),
		    'acc_regno' => set_value('acc_regno'),
		    'acc_gstno' => set_value('acc_gstno'),
		    'acc_taxcode' => set_value('acc_taxcode'),
		    'acc_add1' => set_value('acc_add1'),
		    'acc_add2' => set_value('acc_add2'),
		    'acc_add3' => set_value('acc_add3'),
		    'acc_add4' => set_value('acc_add4'),
		    'acc_postcode' => set_value('acc_postcode'),
		    'acc_state' => set_value('acc_state'),
		    'acc_country' => set_value('acc_country'),
		    'created_at' => set_value('created_at'),
		    'created_by' => set_value('created_by'),
		    'updated_at' => set_value('updated_at'),
		    'updated_by' => set_value('updated_by'),
			);
	        $this->load->view('header');
	        $this->load->view('acc/acc_form', $data);
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

	        if ($this->form_validation->run() == FALSE) {
	            $this->create();
	        } else {
	            $data = array(
	            	'acc_guid' => $guid,
			'isactive' => $this->input->post('isactive',TRUE),
			'acc_name' => $this->input->post('acc_name',TRUE),
			'acc_regno' => $this->input->post('acc_regno',TRUE),
			'acc_gstno' => $this->input->post('acc_gstno',TRUE),
			'acc_taxcode' => $this->input->post('acc_taxcode',TRUE),
			'acc_add1' => $this->input->post('acc_add1',TRUE),
			'acc_add2' => $this->input->post('acc_add2',TRUE),
			'acc_add3' => $this->input->post('acc_add3',TRUE),
			'acc_add4' => $this->input->post('acc_add4',TRUE),
			'acc_postcode' => $this->input->post('acc_postcode',TRUE),
			'acc_state' => $this->input->post('acc_state',TRUE),
			'acc_country' => $this->input->post('acc_country',TRUE),
			'created_at' => $datetime,
			'created_by' => $_SESSION['userid'],
			'updated_at' => $datetime,
			'updated_by' => $_SESSION['userid'],
		    );

	            $this->Acc_model->insert($data);
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
	        $row = $this->Acc_model->get_by_id($_SESSION['guid']);

	        if ($row) {
	            $data = array(
	                'button' => 'Update',
	                'action' => site_url('acc/update_action'),
			'acc_guid' => set_value('acc_guid', $row->acc_guid),
			'isactive' => set_value('isactive', $row->isactive),
			'acc_name' => set_value('acc_name', $row->acc_name),
			'acc_regno' => set_value('acc_regno', $row->acc_regno),
			'acc_gstno' => set_value('acc_gstno', $row->acc_gstno),
			'acc_taxcode' => set_value('acc_taxcode', $row->acc_taxcode),
			'acc_add1' => set_value('acc_add1', $row->acc_add1),
			'acc_add2' => set_value('acc_add2', $row->acc_add2),
			'acc_add3' => set_value('acc_add3', $row->acc_add3),
			'acc_add4' => set_value('acc_add4', $row->acc_add4),
			'acc_postcode' => set_value('acc_postcode', $row->acc_postcode),
			'acc_state' => set_value('acc_state', $row->acc_state),
			'acc_country' => set_value('acc_country', $row->acc_country),
			'created_at' => set_value('created_at', $row->created_at),
			'created_by' => set_value('created_by', $row->created_by),
			'updated_at' => set_value('updated_at', $row->updated_at),
			'updated_by' => set_value('updated_by', $row->updated_by),
		    );
	            $this->load->view('header');
	            $this->load->view('acc/acc_form', $data);
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

	        if ($this->form_validation->run() == FALSE) {
	            $this->update($_SESSION['guid']);
	        } else {
	            $data = array(
			'isactive' => $this->input->post('isactive',TRUE),
			'acc_name' => $this->input->post('acc_name',TRUE),
			'acc_regno' => $this->input->post('acc_regno',TRUE),
			'acc_gstno' => $this->input->post('acc_gstno',TRUE),
			'acc_taxcode' => $this->input->post('acc_taxcode',TRUE),
			'acc_add1' => $this->input->post('acc_add1',TRUE),
			'acc_add2' => $this->input->post('acc_add2',TRUE),
			'acc_add3' => $this->input->post('acc_add3',TRUE),
			'acc_add4' => $this->input->post('acc_add4',TRUE),
			'acc_postcode' => $this->input->post('acc_postcode',TRUE),
			'acc_state' => $this->input->post('acc_state',TRUE),
			'acc_country' => $this->input->post('acc_country',TRUE),
			'updated_at' => $datetime,
			'updated_by' => 'admin',
		    );

	            $this->Acc_model->update($_SESSION['guid'], $data);
	            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Update Record Success<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
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
	        $row = $this->Acc_model->get_by_id($_SESSION['guid']);

	        if ($row) {
	            $this->Acc_model->delete($_SESSION['guid']);
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
	$this->form_validation->set_rules('isactive', 'isactive', 'trim|required');
	$this->form_validation->set_rules('acc_name', 'acc name', 'trim|required');
	$this->form_validation->set_rules('acc_regno', 'acc regno', 'trim|required');
	$this->form_validation->set_rules('acc_gstno', 'acc gstno', 'trim|required');
	$this->form_validation->set_rules('acc_taxcode', 'acc taxcode', 'trim|required');
	$this->form_validation->set_rules('acc_add1', 'acc add1', 'trim|required');
	$this->form_validation->set_rules('acc_add2', 'acc add2', 'trim|required');
	$this->form_validation->set_rules('acc_add3', 'acc add3', 'trim|required');
	$this->form_validation->set_rules('acc_add4', 'acc add4', 'trim|required');
	$this->form_validation->set_rules('acc_postcode', 'acc postcode', 'trim|required');
	$this->form_validation->set_rules('acc_state', 'acc state', 'trim|required');
	$this->form_validation->set_rules('acc_country', 'acc country', 'trim|required');
	// $this->form_validation->set_rules('created_at', 'created at', 'trim|required');
	// $this->form_validation->set_rules('created_by', 'created by', 'trim|required');
	// $this->form_validation->set_rules('updated_at', 'updated at', 'trim|required');
	// $this->form_validation->set_rules('updated_by', 'updated by', 'trim|required');

	$this->form_validation->set_rules('acc_guid', 'acc_guid', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}
?>