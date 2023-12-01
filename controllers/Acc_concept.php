<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Acc_concept extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Acc_concept_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $data['details'] = $this->db->query("SELECT a.*,b.`acc_name` FROM acc_concept a INNER JOIN acc b ON a.`acc_guid`=b.`acc_guid`");
            $this->load->view('header');
            $this->load->view('acc_concept/acc_concept_list', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }
        
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Acc_concept_model->json();
    }

    public function read($id) 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $row = $this->Acc_concept_model->get_by_id($id);
            if ($row) {
                $data = array(
    		'acc_guid' => $row->acc_guid,
    		'concept_guid' => $row->concept_guid,
    		'isactive' => $row->isactive,
    		'concept_name' => $row->concept_name,
    		'created_at' => $row->created_at,
    		'created_by' => $row->created_by,
    		'updated_at' => $row->updated_at,
    		'updated_by' => $row->updated_by,
            'acc_name' => $row->acc_name
    	    );
                $this->load->view('header');
                $this->load->view('acc_concept/acc_concept_read', $data);
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
            // get data for account option
            $query_account = $this->db->query("SELECT acc_name FROM lite_b2b.acc");
            $acc = $query_account->result();
            
            $data = array(
                'button' => 'Create',
                'action' => site_url('acc_concept/create_action'),
        	    'acc_guid' => set_value('acc_guid'),
        	    'concept_guid' => set_value('concept_guid'),
        	    'isactive' => set_value('isactive'),
        	    'concept_name' => set_value('concept_name'),
        	    'created_at' => set_value('created_at'),
        	    'created_by' => set_value('created_by'),
        	    'updated_at' => set_value('updated_at'),
        	    'updated_by' => set_value('updated_by'),
                'disabled' => 'disabled',
                'acc_select' => 'Select Account',
                'acc' => $acc
        	);
            $this->load->view('header');
            $this->load->view('acc_concept/acc_concept_form', $data);
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

            $query_acc_guid = $this->db->query("SELECT acc_guid FROM lite_b2b.acc WHERE acc_name = '".$this->input->post('acc')."' ");
            $acc_guid = $query_acc_guid->row('acc_guid');

            if ($this->form_validation->run() == FALSE) {
                $this->create();
            } else {

                $check_data = $this->db->query("SELECT * FROM acc_concept where concept_name = '".$this->input->post('concept_name')."' and acc_guid = '".$acc_guid."'");
                if($check_data->num_rows() > 0)
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Record Already Exist.<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect(site_url('Profile_setup'));
                }
                $data = array(
    		      'acc_guid' => $acc_guid,
                    'concept_guid' => $guid,
    		      'isactive' => $this->input->post('isactive',TRUE),
    		      'concept_name' => $this->input->post('concept_name',TRUE),
    		      'created_at' => $datetime,
    		      'created_by' => $_SESSION['userid'],
    		      'updated_at' => $datetime,
    		      'updated_by' => $_SESSION['userid']
    	       );

                $this->Acc_concept_model->insert($data);
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
            $row = $this->Acc_concept_model->get_by_id($_SESSION['guid']);

            $query_account = $this->db->query("SELECT acc_name FROM lite_b2b.acc WHERE isactive = '1'");
            $acc = $query_account->result();

            if ($row) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('acc_concept/update_action'),
            		'acc_guid' => set_value('acc_guid', $row->acc_guid),
            		'concept_guid' => set_value('concept_guid', $row->concept_guid),
            		'isactive' => set_value('isactive', $row->isactive),
            		'concept_name' => set_value('concept_name', $row->concept_name),
                    'disabled' => '',
                    'acc_select' => $row->acc_name,
                    'acc' => $acc,
            	);
                $this->load->view('header');
                $this->load->view('acc_concept/acc_concept_form', $data);
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

            $query_acc_guid = $this->db->query("SELECT acc_guid FROM lite_b2b.acc WHERE acc_name = '".$this->input->post('acc')."' ");
            $acc_guid = $query_acc_guid->row('acc_guid');

            if ($this->form_validation->run() == FALSE) {
                $this->update($_SESSION['guid']);
            } else {
                $data = array(
    		'acc_guid' => $acc_guid,
    		'isactive' => $this->input->post('isactive',TRUE),
    		'concept_name' => $this->input->post('concept_name',TRUE),
    		'updated_at' => $datetime,
    		'updated_by' => $_SESSION['userid'],
    	    );

                $this->Acc_concept_model->update($_SESSION['guid'], $data);
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
            $row = $this->Acc_concept_model->get_by_id($_SESSION['guid']);

            if ($row) {
                $this->Acc_concept_model->delete($_SESSION['guid']);
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
	$this->form_validation->set_rules('concept_name', 'concept name', 'trim|required');
    $this->form_validation->set_rules('acc', 'account name', 'trim|required');

	$this->form_validation->set_rules('concept_guid', 'concept_guid', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Acc_concept.php */
/* Location: ./application/controllers/Acc_concept.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-12-23 13:03:45 */
/* http://harviacode.com */