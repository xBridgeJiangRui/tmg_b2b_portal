<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Acc_master_setup extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Acc_master_setup_model');
        $this->load->library('form_validation');        
	    $this->load->library('datatables');
    }

    function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['system_admin'] == 1 )
        {
            if(isset($_REQUEST['acc_module_group_guid']))
            {
                $_SESSION['acc_module_group_guid'] = $_REQUEST['acc_module_group_guid'];
            }
            else
            {
                $result = $this->db->query("SELECT acc_module_group_guid FROM acc_module_group ORDER BY updated_at DESC LIMIT 1 ");
                $_SESSION['acc_module_group_guid'] = $result->row('acc_module_group_guid');
            }

            $data = array(

                'account_user' => $this->db->query("SELECT * FROM acc_user a INNER JOIN acc_user_group b ON a.`acc_user_group_guid` = b.`acc_user_group_guid` order by a.updated_at desc"),

                'account_user_group' => $this->db->query("SELECT a.* FROM acc_user_group a ORDER BY a.updated_at DESC"),

                'account_module_group' => $this->db->query("SELECT a.* FROM acc_module_group a order by a.updated_at desc"),

                'account_module' => $this->db->query("SELECT * FROM acc_module a INNER JOIN acc_module_group b ON a.`acc_module_group_guid` = b.`acc_module_group_guid`
                    where b.`acc_module_group_guid` = '".$_SESSION['acc_module_group_guid']."' order by acc_module_Seq asc;"),

                'account_user_module' => $this->db->query("SELECT a.*,b.`acc_module_name`,c.`user_group_name` FROM acc_user_module a INNER JOIN acc_module b ON a.`acc_module_guid` = b.`acc_module_guid` 
                INNER JOIN acc_user_group c ON a.`acc_user_group_guid` = c.`acc_user_group_guid` order by a.updated_at desc"),

                'select_user_group' => $this->db->query("SELECT * FROM acc_user_group a where isactive = 1 ORDER BY a.updated_at DESC "),

                'select_module_group' => $this->db->query("SELECT * FROM acc_module_group where acc_module_group_guid = 
                    '".$_SESSION['acc_module_group_guid']."'"),

                );
            $this->load->view('header');
            $this->load->view('acc_master_setup', $data);
            $this->load->view('acc_master_setup_modal', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }
    }

    public function module_group_form()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['system_admin'] == 1 )
        {
            $table = 'acc_module_group';
            $col_guid = 'acc_module_group_guid';
            //check data exist or not
            $check_data = $this->db->query("SELECT * FROM acc_module_group  WHERE acc_module_group_guid = '".$this->input->post('guid')."' OR acc_module_group_name = '".$this->input->post('name')."' ");

            if($check_data->num_rows() > 0)// update data
            {
                $guid = $check_data->row('acc_module_group_guid');
                $data = array(

                    'acc_module_group_seq' => $this->input->post('seq'),
                    'acc_module_group_name' =>$this->input->post('name'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->update_data($table,$col_guid, $guid, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
            }
            else // insert data
            {
                $checkseq = $this->db->query("SELECT * FROM acc_module_group WHERE acc_module_group_seq = '".$this->input->post('seq')."'");
                if($checkseq->num_rows() > 0)
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Sequence Already Exist!<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
                }
                $data = array(

                    'acc_module_group_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid'),
                    'acc_module_group_seq' => $this->input->post('seq'),
                    'acc_module_group_name' =>$this->input->post('name'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->insert_data($table, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
            }
        }
        else
        {
            redirect('login_c');
        }
        
    }


    public function module_form()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['system_admin'] == 1 )
        {
           $table = 'acc_module';
            $col_guid = 'acc_module_guid';
            //check data exist or not
            $check_data = $this->db->query("SELECT * FROM acc_module  WHERE acc_module_guid = '".$this->input->post('guid')."' OR acc_module_name = '".$this->input->post('name')."' ");

            if($check_data->num_rows() > 0)// update data
            {
                $guid = $check_data->row('acc_module_guid');
                $data = array(
                    'acc_module_group_guid' => $_SESSION['acc_module_group_guid'],
                    'acc_module_seq' => $this->input->post('seq'),
                    'acc_module_code' => strtoupper($this->input->post('code')),
                    'acc_module_name' =>$this->input->post('name'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->update_data($table,$col_guid, $guid, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
            }
            else // insert data
            {
                $checkseq = $this->db->query("SELECT * FROM acc_module WHERE acc_module_seq = '".$this->input->post('seq')."' AND acc_module_group_guid = '".$_SESSION['acc_module_group_guid']."' ");
                $checkcode = $this->db->query("SELECT * FROM acc_module WHERE acc_module_code = '".$this->input->post('code')."' AND acc_module_group_guid = '".$_SESSION['acc_module_group_guid']."' ");
                if($checkseq->num_rows() > 0)
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Sequence Already Exist!<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
                }

                if($checkcode->num_rows() > 0)
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Module Code Already Exist!<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
                }

                $data = array(

                    'acc_module_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid'),
                    // 'acc_module_group_guid' => $this->input->post('module_group'),
                    'acc_module_group_guid' => $this->input->post('module_group_guid'),
                    'acc_module_seq' => $this->input->post('seq'),
                    'acc_module_code' => strtoupper($this->input->post('code')),
                    'acc_module_name' => $this->input->post('name'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->insert_data($table, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
            }
        }
        else
        {
            redirect('login_c');
        }
        
    }


    public function user_form()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['system_admin'] == 1 )
        {
            $table = 'acc_user';
            $col_guid = 'acc_user_guid';
            //check data exist or not
            $check_data = $this->db->query("SELECT * FROM acc_user  WHERE acc_user_guid = '".$this->input->post('guid')."' ");

            if($check_data->num_rows() > 0)// update data
            {
                $guid = $check_data->row('acc_user_guid');
                $data = array(
                    'acc_user_group_guid' => $this->input->post('user_group'),
                    'acc_user_id' => $this->input->post('userid'),
                    'acc_user_name' =>$this->input->post('name'),
                    'acc_user_password' =>$this->input->post('password'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->update_data($table,$col_guid, $guid, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
            }
            else // insert data
            {
                $check_data = $this->db->query("SELECT * FROM acc_user WHERE acc_user_id = '".$this->input->post('userid')."' ");

                if($check_data->num_rows() > 0)
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">UserID Already Exist. Please try with different UserID<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
                };

                if($this->input->post('user_group') == 'Select Group')
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Please Select Valid User Group<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
                }

                $data = array(

                    'acc_user_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid'),
                    'acc_user_group_guid' => $this->input->post('user_group'),
                    'acc_user_id' => $this->input->post('userid'),
                    'acc_user_name' =>$this->input->post('name'),
                    'acc_user_password' =>$this->input->post('password'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->insert_data($table, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
            }
        }
        else
        {
            redirect('login_c');
        }
        
    }


    public function user_group_form()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['system_admin'] == 1 )
        {
            $table = 'acc_user_group';
            $col_guid = 'acc_user_group_guid';
            //check data exist or not
            $check_data = $this->db->query("SELECT * FROM acc_user_group  WHERE acc_user_group_guid = '".$this->input->post('guid')."' OR user_group_name = '".$this->input->post('group_name')."' ");

            if($check_data->num_rows() > 0)// update data
            {
                $guid = $check_data->row('acc_user_group_guid');
                $data = array(

                    'user_group_name' => $this->input->post('group_name'),
                    'isactive' =>$this->input->post('active'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->update_data($table,$col_guid, $guid, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
            }
            else // insert data
            {
                $data = array(

                    'acc_user_group_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid'),
                    'user_group_name' => $this->input->post('group_name'),
                    'isactive' =>$this->input->post('active'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->insert_data($table, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
            }
        }
        else
        {
            redirect('login_c');
        }
        
    }

    public function user_module_form()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['system_admin'] == 1 )
        {
            $table = 'acc_user_module';
            $col_guid = 'acc_user_module_guid';
            //check data exist or not
            $check_data = $this->db->query("SELECT * FROM acc_user_module  WHERE acc_user_module_guid = '".$this->input->post('guid')."' OR acc_module_guid = '".$this->input->post('module')."' AND acc_user_group_guid = '".$this->input->post('user_group')."'");

            if($check_data->num_rows() > 0)// update data
            {
                $guid = $check_data->row('acc_user_module_guid');
                $data = array(
                    'acc_module_guid' => $this->input->post('module'),
                    'acc_user_group_guid' => $this->input->post('user_group'),
                    'isenable' =>$this->input->post('enable'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->update_data($table,$col_guid, $guid, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
            }
            else // insert data
            {
                if($this->input->post('user_group') == 'Select User Group')
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Please Select Valid User Group<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
                };

                if($this->input->post('module') == 'Select Module')
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Please Select Valid Module<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
                };


                $data = array(
                    'acc_user_module_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid'),
                    'acc_module_guid' => $this->input->post('module'),
                    'acc_user_group_guid' => $this->input->post('user_group'),
                    'isenable' =>$this->input->post('enable'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->Acc_master_setup_model->insert_data($table, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
            }
        }
        else
        {
            redirect('login_c');
        }
        
    }

    public function check()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['system_admin'] == 1 )
        {

            $guid = $this->input->post('guid[]');       
            $active = $this->input->post('active[]');
            
            $data = array();

            foreach($guid as $i => $id) {
                $data[] = [$_REQUEST['col_guid'] => $id, $_REQUEST['col_check'] => $active[$i] ];
            }

            $this->db->update_batch($_REQUEST['table'], $data, $_REQUEST['col_guid']);
            // echo $this->db->last_query();die;
            redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
        }
        else
        {
            redirect('login_c');
        }
    }


    public function delete()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['system_admin'] == 1 )
        {
            $guid = $_REQUEST['guid'];
            $table = $_REQUEST['table'];
            $col_guid = $_REQUEST['col_guid'];
            $this->Acc_master_setup_model->delete_data($table, $col_guid, $guid);
            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Deleted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
        }
        else
        {
            redirect('login_c');
        }
    }

    public function delete_user_group()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['system_admin'] == 1 )
        {
            $guid = $_REQUEST['guid'];
            $table = $_REQUEST['table'];
            $col_guid = $_REQUEST['col_guid'];
            $this->Acc_master_setup_model->delete_user_group($table, $col_guid, $guid);
            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Deleted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('acc_master_setup?acc_module_group_guid='.$_SESSION['acc_module_group_guid']);
        }
        else
        {
            redirect('login_c');
        }
    }

}