 <?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_setup extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('User_setup_model');
        $this->load->library('form_validation');        
	    $this->load->library('datatables');
    }

    function index()
    {
        $data = array(

            'user' => $this->db->query("SELECT * FROM set_user"),

            'user_group' => $this->db->query("SELECT * FROM set_user_group"),

            'user_module' => $this->db->query("SELECT a.*,b.`user_group_name`,c.`module_name` FROM set_user_module a INNER JOIN set_user_group b ON a.`user_group_guid` = b.`user_group_guid`INNER JOIN set_module c ON a.`module_guid` = c.`module_guid`"),

            );
        $this->load->view('header');
        $this->load->view('user_setup', $data);
        $this->load->view('user_setup_modal', $data);
        $this->load->view('footer');
    }

    public function module_group_form()
    {
        if($this->input->post('module_group') == 'Select Module Group')
        {
            $this->session->set_flashdata('warning', 'Please Select Valid Module Group');
            redirect('module_setup');
        };
        
            $table = 'set_module_group';
            $col_guid = 'module_group_guid';

            $module_group = explode('->', $this->input->post('module_group'));
            //check data exist or not
            $check_data = $this->db->query("SELECT * FROM set_module_group  WHERE module_group_guid = '".$module_group[0]."' ");

            if($check_data->num_rows() > 0)// update data
            {
                $guid = $check_data->row('module_group_guid');
                $data = array(

                    'module_group_seq' => $this->input->post('seq'),
                    'module_group_name' =>$module_group[1],
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->update_data($table,$col_guid, $guid, $data);
                $this->session->set_flashdata('message', 'Record Updated');
                    redirect('module_setup');
            }
            else // insert data
            {

                $data = array(

                    'module_group_guid' => $module_group[0],
                    'module_group_seq' => $this->input->post('seq'),
                    'module_group_name' => $module_group[1],
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->insert_data($table, $data);
                $this->session->set_flashdata('message', 'Record Inserted');
                    redirect('module_setup');
            }
        
    }


    public function module_form()
    {
            $table = 'set_module';
            $col_guid = 'module_guid';

            $module_group = explode('->', $this->input->post('module_group'));
            //check data exist or not
            $check_data = $this->db->query("SELECT * FROM set_module  WHERE module_guid = '".$this->input->post('guid')."' ");

            if($check_data->num_rows() > 0)// update data
            {
                $guid = $check_data->row('module_guid');
                $data = array(
                    'module_group_guid' => $this->input->post('module_group'),
                    'module_seq' => $this->input->post('seq'),
                    'module_name' =>$this->input->post('name'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->update_data($table,$col_guid, $guid, $data);
                $this->session->set_flashdata('message', 'Record Updated');
                    redirect('module_setup');
            }
            else // insert data
            {
                $data = array(

                    'acc_module_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid'),
                    'acc_module_group_guid' => $this->input->post('module_group'),
                    'acc_module_seq' => $this->input->post('seq'),
                    'acc_module_name' =>$this->input->post('name'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->insert_data($table, $data);
                $this->session->set_flashdata('message', 'Record Inserted');
                    redirect('module_setup');
            }
        
    }

    public function delete()
    {
            $guid = $_REQUEST['guid'];
            $table = $_REQUEST['table'];
            $col_guid = $_REQUEST['col_guid'];
            $this->Acc_master_setup_model->delete_data($table, $col_guid, $guid);
            $this->session->set_flashdata('message', 'Record Deleted');
                redirect('module_setup');
    }

}