<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Module_setup extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Module_setup_model');
        $this->load->library('form_validation');        
        $this->load->library('datatables');
    }

    public function datetime()
    {
        $datetime = $this->db->query("SELECT NOW() as updated_at")->row('updated_at');
        return $datetime;
    }

    public function guid()
    {
        $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');
        return $guid;
    }

    function index()
    {
        $_SESSION['system_admin'] = $_SESSION['system_admin'];
        $_SESSION['userid'] = $_SESSION['userid'];

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
        
            if(isset($_REQUEST['module_group_guid']))
            {
                $_SESSION['module_group_guid'] = $_REQUEST['module_group_guid'];
            }
            else
            {
                $result = $this->db->query("SELECT module_group_guid FROM set_module_group ORDER BY updated_at DESC LIMIT 1 ");
                $_SESSION['module_group_guid'] = $result->row('module_group_guid');
            }

            if(isset($_REQUEST['user_group_guid']))
            {
                $_SESSION['user_group_guid'] = $_REQUEST['user_group_guid'];
                $call_user_module = $this->db->query("SELECT a.*,b.module_code,b.`module_name`,c.`module_group_name`,d.`user_group_name` FROM set_user_module a INNER JOIN set_module b ON a.`module_guid` = b.`module_guid`INNER JOIN set_module_group c ON a.`module_group_guid` = c.`module_group_guid`INNER JOIN set_user_group d ON d.`user_group_guid` = a.`user_group_guid` WHERE c.`module_group_guid` = '".$_SESSION['module_group_guid']."' AND d.`user_group_guid` = '".$_SESSION['user_group_guid']."' ");
            }
            else
            {
                // $result = $this->db->query("SELECT module_group_guid FROM set_module_group ORDER BY updated_at DESC LIMIT 1 ");
                $_SESSION['user_group_guid'] = '';
                $call_user_module = $this->db->query("SELECT a.*,b.module_code,b.`module_name`,c.`module_group_name`,d.`user_group_name` FROM set_user_module a INNER JOIN set_module b ON a.`module_guid` = b.`module_guid`INNER JOIN set_module_group c ON a.`module_group_guid` = c.`module_group_guid`INNER JOIN set_user_group d ON d.`user_group_guid` = a.`user_group_guid` WHERE c.`module_group_guid` = '".$_SESSION['module_group_guid']."' ");
            }


            $data = array(

                'module_group' => $this->db->query("SELECT * FROM set_module_group ORDER BY module_group_seq ASC"),

                'account_module_group' => $this->db->query("SELECT a.* FROM acc_module_group a order by a.updated_at desc"),

                'account_module' => $this->db->query("SELECT a.*,b.`acc_module_group_name` FROM acc_module a INNER JOIN acc_module_group b ON a.`acc_module_group_guid` = b.`acc_module_group_guid` WHERE a.isenable = 1 AND a.acc_module_group_guid = '".$_SESSION['module_group_guid']."'"),

                'call_module' => $this->db->query("SELECT a.*,c.`module_group_name` FROM set_module a INNER JOIN acc_module b ON a.`module_guid` = b.`acc_module_guid` INNER JOIN set_module_group c ON c.`module_group_guid` = a.`module_group_guid` WHERE c.`module_group_guid` = '".$_SESSION['module_group_guid']."' ORDER BY module_seq ASC"),

                'call_user' => $this->db->query("SELECT a.*,d.`module_group_name`,e.`user_group_name` FROM set_user a INNER JOIN set_user_module b ON a.`user_group_guid` = b.`user_group_guid`
                    INNER JOIN set_module c ON c.`module_guid` = b.`module_guid` INNER JOIN set_module_group d ON d.`module_group_guid` = c.`module_group_guid` AND d.`module_group_guid` = a.`module_group_guid`INNER JOIN set_user_group e ON e.`user_group_guid` = a.`user_group_guid` WHERE d.`module_group_guid` = '".$_SESSION['module_group_guid']."'  and a.acc_guid = '".$_SESSION['customer_guid']."'  GROUP BY a.user_id "),

                'call_user_module' => $call_user_module,

                'user_group' => $this->db->query("SELECT * FROM set_user_group WHERE module_group_guid = '".$_SESSION['module_group_guid']."'"),

                'select_user_group' => $this->db->query("SELECT * FROM set_user_group where isactive = 1 and module_group_guid = 
                    '".$_SESSION['module_group_guid']."'"),

                'select_module_group' => $this->db->query("SELECT * FROM set_module_group where module_group_guid = 
                    '".$_SESSION['module_group_guid']."'"),

                'acc_concept' => $this->db->query("SELECT a.*,b.`acc_name` FROM acc_concept a INNER JOIN acc b ON a.`acc_guid` = b.`acc_guid` INNER JOIN acc_branch c ON c.`concept_guid` = a.`concept_guid` WHERE a.isactive = 1 AND a.acc_guid = '".$_SESSION['customer_guid']."' GROUP BY a.`concept_guid` ORDER BY a.updated_at DESC;"),

                'branch_group' => $this->db->query("SELECT a.*,b.`concept_name` FROM acc_branch_group a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc_branch c ON c.`branch_group_guid` = a.`branch_group_guid` WHERE a.isactive = 1 AND b.acc_guid = '".$_SESSION['customer_guid']."'GROUP BY a.`branch_group_guid` ORDER BY a.updated_at DESC"),

                'branch' => $this->db->query("SELECT b.concept_name, a.*, c.`group_name`,d.branch_desc as branch_description FROM acc_branch a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc_branch_group c ON c.`branch_group_guid` = a.`branch_group_guid` INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '".$_SESSION['customer_guid']."') d ON a.branch_code = d.branch_code AND b.acc_guid = d.customer_guid WHERE a.isactive = 1 and b.acc_guid = '".$_SESSION['customer_guid']."'  GROUP BY a.`branch_guid` ORDER BY a.branch_code ASC , a.updated_at DESC"),

                );
            $this->load->view('header');
            $this->load->view('module_setup', $data);
            $this->load->view('module_setup_modal', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }
       
    }

    public function home()
    {
        
    }

    public function module_group_form()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            
            $table = 'set_module_group';
            $col_guid = 'module_group_guid';
            
            $module_group_guid = $this->input->post('module_group');

            $module_group_guid_array = json_encode($module_group_guid);

            foreach($module_group_guid as $i => $id) {

            $checkmoduleguid = $this -> db
                   -> select('module_group_guid')
                   -> from ('set_module_group')
                   -> where_in('module_group_guid', json_decode($module_group_guid_array) )
                   -> get();

            $data[] = [ 
                    'module_group_guid' => $id,
                    'module_group_seq' => '',
                    'module_group_name' => '',
                    'updated_at' => $this->datetime(),
                    'updated_by' => $_SESSION['userid'],
                    'created_at' => $this->datetime(),
                    'created_by' => $_SESSION['userid'],
                        ];
            }

            if($checkmoduleguid->num_rows() > 0)
            {
                $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Record Already Exist!<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            };

            $this->db->insert_batch($table, $data, 'module_group_guid');// insert record
            $this->db->query("UPDATE set_module_group a INNER JOIN acc_module_group b ON a.`module_group_guid` = b.`acc_module_group_guid`SET a.`module_group_name`=b.`acc_module_group_name`, a.`module_group_seq`=b.`acc_module_group_seq` ");// update record match to acc_module_group
            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
                // $module_group = explode('->', $this->input->post('module_group'));
                // //check data exist or not
                // $check_data = $this->db->query("SELECT * FROM set_module_group  WHERE module_group_guid = '".$module_group[0]."' OR module_group_name = '".$module_group[1]."'");

                // if($check_data->num_rows() > 0)// update data
                // {
                //     $guid = $check_data->row('module_group_guid');
                //     $data = array(

                //         'module_group_seq' => $this->input->post('seq'),
                //         'module_group_name' =>$module_group[1],
                //         'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                //         'updated_by' => $_SESSION['userid'],
                //         );
                //     $this->Module_setup_model->update_data($table,$col_guid, $guid, $data);
                //     $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                //         redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
                // }
                // else // insert data
                // {

                //     $data = array(

                //         'module_group_guid' => $module_group[0],
                //         'module_group_seq' => $this->input->post('seq'),
                //         'module_group_name' => $module_group[1],
                //         'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                //         'updated_by' => $_SESSION['userid'],
                //         'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                //         'created_by' => $_SESSION['userid'],
                //         );
                //     $this->Module_setup_model->insert_data($table, $data);

                //     // $check_data = $this->db->query("SELECT c.* FROM acc_module_group a INNER JOIN set_module_group b ON a.`acc_module_group_guid` = b.`module_group_guid` 
                //     //     INNER JOIN acc_module c ON a.`acc_module_group_guid` = c.`acc_module_group_guid`
                //     //     WHERE a.`acc_module_group_guid` = '".$module_group[0]."' ");
                //     // if($check_data->num_rows() > 0)
                //     // {
                //     //     $data = array();
                //     //     foreach($check_data->result() as $row)
                //     //     {
                //     //         $data[] = array(  

                //     //             'module_guid' => $row->acc_module_guid,
                //     //             'module_group_guid' => $row->acc_module_group_guid,
                //     //             'module_seq' => $row->acc_module_seq,
                //     //             'module_name' =>$row->acc_module_name,
                //     //             'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                //     //             'updated_by' => $_SESSION['userid'],
                //     //             'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                //     //             'created_by' => $_SESSION['userid'],
                //     //             );
                //     //     }
                //     //     $this->db->insert_batch('set_module', $data);
                //     //     $this->session->set_flashdata('message', 'Record Inserted');
                //     //      redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
                //     // };

                //     $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                //          redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
                    
                // }
        }
        else
        {
            redirect('login_c');
        }
        
    }

    public function user_group_form()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $table = 'set_user_group';
            $col_guid = 'user_group_guid';
            //check data exist or not
            // $check_data = $this->db->query("SELECT * FROM set_user_group  WHERE user_group_guid = '".$this->input->post('guid')."' OR user_group_name = '".$this->input->post('group_name')."' ");
            // close for testing faizul
            $check_data = $this->db->query("SELECT * FROM set_user_group  WHERE user_group_guid = '".$this->input->post('guid')."'");

            if($check_data->num_rows() > 0)// update data
            {
                $guid = $check_data->row('user_group_guid');
                $ori = array(
                    $check_data->row('user_group_name'),
                    $check_data->row('isactive'),
                    );
                $data = array(

                    'user_group_name' => strtoupper($this->input->post('group_name')),
                    // 'isactive' =>$this->input->post('active'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    );
                $this->Module_setup_model->update_data($table,$col_guid, $guid, $data);

                $variable = $this->db->query("SELECT * FROM set_user_group  WHERE user_group_guid = '".$this->input->post('guid')."' OR user_group_name = '".$this->input->post('group_name')."' ");
                $upd = array(
                    $variable->row('user_group_name'),
                    $variable->row('isactive'),
                    );
                $field = array('User Group Name', 'Active',);

                for ($x = 0; $x <= 1; $x++) {
                switch ($ori[$x]) 
                {
                    case $upd[$x]:
                        break;
                    default:
                        $log = array(
                        'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                        'module_group_guid' => '',
                        'module_group_description' => 'Module Setup',
                        'section' => 'User Group',
                        'field' => $field[$x],
                        'value_guid' => $guid,
                        'value_from' => $ori[$x],
                        'value_to' => $upd[$x],
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $_SESSION['userid'],
                        );
                        $this->db->insert('userlog', $log);
                } }

                 // echo $this->db->last_query();die;
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            }
            else // insert data
            {
                $data = array(

                    'acc_guid' => $_SESSION['customer_guid'],
                    'user_group_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid'),
                    'module_group_guid' => $this->input->post('module_group_guid'),
                    'user_group_name' => strtoupper($this->input->post('group_name')),
                    'isactive' =>$this->input->post('active'),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->Module_setup_model->insert_data($table, $data);
                // echo $this->db->last_query();die;
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);

            }
            //echo $this->db->last_query();die;
        }
        else
        {
            redirect('login_c');
        }
        
    }

    public function user_module_form()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $table = 'set_user_module';
            $col_guid = 'user_module_guid';

            $module_guid = $this->input->post('module');
            $module_guid_array = json_encode($module_guid);
            
            foreach($module_guid as $i => $id) {

            $checkmoduleguid = $this -> db
                   -> select('module_guid')
                   -> from ('set_user_module')
                   -> where ('user_group_guid', $this->input->post('user_group'))
                   -> where_in('module_guid', json_decode($module_guid_array) )
                   -> get();

            $data[] = [ 
                    'user_module_guid' => $this->guid(),
                    'acc_guid' => $_SESSION['customer_guid'],
                    'module_guid' => $id,
                    'module_group_guid' => $this->input->post('module_group_guid'),
                    'user_group_guid' => $this->input->post('user_group'),
                    'isenable' =>$this->input->post('enable'),
                    'updated_at' => $this->datetime(),
                    'updated_by' => $_SESSION['userid'],
                    'created_at' => $this->datetime(),
                    'created_by' => $_SESSION['userid'], 
                        ];
            }

            if($checkmoduleguid->num_rows() > 0)
            {
                $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Record Already Exist!<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            };

            $this->db->insert_batch($table, $data, 'user_module_guid');// insert new record with branch guid
            // echo $this->db->last_query();die;
            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);


            // check data exist or not
            // $check_data = $this->db->query("SELECT * FROM set_user_module  WHERE user_module_guid = '".$this->input->post('guid')."' or module_guid = '".$this->input->post('module')."' and module_group_guid = '".$_SESSION['module_group_guid']."' and user_group_guid = '".$this->input->post('user_group')."'");

            // if($check_data->num_rows() > 0)// update data
            // {
            //     $guid = $check_data->row('user_module_guid');
            //     $data = array(
            //         'acc_guid' => $_SESSION['acc_guid'],
            //         'module_guid' => $this->input->post('module'),
            //         'module_group_guid' => $_SESSION['module_group_guid'],
            //         'user_group_guid' => $this->input->post('user_group'),
            //         // 'isenable' =>$this->input->post('enable'),
            //         'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            //         'updated_by' => $_SESSION['userid'],
            //         );
            //     $this->Module_setup_model->update_data($table,$col_guid, $guid, $data);
            //     $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
            //         if ($_SESSION['user_group_guid'] == '') 
            //     {
            //         redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            //     }
            //     else
            //     {
            //         redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid'].'&user_group_guid='.$_SESSION['user_group_guid']);
            //     }
            // }
            // else // insert data
            // {
            //     $data = array(
            //         'user_module_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid'),
            //         'acc_guid' => $_SESSION['acc_guid'],
            //         'module_guid' => $this->input->post('module'),
            //         'module_group_guid' => $_SESSION['module_group_guid'],
            //         'user_group_guid' => $this->input->post('user_group'),
            //         'isenable' =>$this->input->post('enable'),
            //         'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            //         'updated_by' => $_SESSION['userid'],
            //         'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            //         'created_by' => $_SESSION['userid'],
            //         );
            //     $this->Module_setup_model->insert_data($table, $data);
            //     $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
            //         if ($_SESSION['user_group_guid'] == '') 
            //     {
            //         redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            //     }
            //     else
            //     {
            //         redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid'].'&user_group_guid='.$_SESSION['user_group_guid']);
            //     }
            // }
        }
        else
        {
            redirect('login_c');
        }
        
    }


    public function user_form()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $table = 'set_user';
            $col_guid = 'user_guid';
            //check data exist or not
            $check_data = $this->db->query("SELECT * FROM set_user  WHERE user_guid = '".$this->input->post('guid')."' ");

            if($check_data->num_rows() > 0)// update data
            {
                $guid = $check_data->row('user_guid');
                $ori_user = $check_data->row('user_group_guid');
                $ori_ui = $check_data->row('user_id');
                $ori_un = $check_data->row('user_name');
                $ori_up = $check_data->row('user_password');
                $ori_ug = $this->db->query("SELECT * FROM set_user_group  WHERE user_group_guid = '$ori_user' ")->row('user_group_name');
                $ori_mg = $check_data->row('module_group_guid');
                $ori_ac = $check_data->row('isactive');


                if($this->input->post('password') ==  $check_data->row('user_password'))
                {
                    $password = $this->input->post('password');
                }else
                {
                    $password = md5($this->input->post('password'));
                }

                $data = array(
                    // 'acc_guid' => $_SESSION['customer_guid'],
                    'isactive' => $this->input->post('active'),
                    'module_group_guid' => $this->input->post('module_group_guid'),
                    // 'user_group_guid' => $this->input->post('user_group'),
                    'user_id' => $this->input->post('userid'),
                    'user_name' =>$this->input->post('name'),
                    'user_password' => $password,
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    'limited_location' => $this->input->post('limited_location'),
                    );
                $this->Module_setup_model->update_data($table,$col_guid, $guid, $data);
                $this->db->query("UPDATE set_user SET user_group_guid = '".$this->input->post('user_group')."' WHERE user_guid = '$guid' AND acc_guid = '".$_SESSION['customer_guid']."'");
                // echo $this->db->last_query();die;
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');

                // check email list 
                $this->db->query("UPDATE lite_b2b.email_list set email = '".$this->input->post('userid')."', first_name = '".$this->input->post('name')."' where user_guid = '".$guid."'");

                $variable = $this->db->query("SELECT * FROM set_user WHERE user_guid = '".$this->input->post('guid')."' ");
                $upd_user = $variable->row('user_group_guid');
                $upd_ui = $variable->row('user_id');
                $upd_un = $variable->row('user_name');
                $upd_up = $variable->row('user_password');
                $upd_ug = $this->db->query("SELECT * FROM set_user_group  WHERE user_group_guid = '$upd_user' ")->row('user_group_name');
                $upd_mg = $variable->row('module_group_guid');
                $upd_ac = $variable->row('isactive');

                switch ($ori_ui) 
                {
                    case $upd_ui:
                        break;
                    default:
                        $log = array(
                        'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                        'module_group_guid' => $this->input->post('module_group_guid'),
                        'module_group_description' => $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid='".$this->input->post('module_group_guid')."'")->row('a'),
                        'section' => 'User',
                        'field' => 'User ID',
                        'value_guid' => $check_data->row('user_guid'),
                        'value_from' => $ori_ui,
                        'value_to' => $upd_ui,
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $_SESSION['userid'],
                        );
                        $this->db->insert('userlog', $log);
                }

                switch ($ori_un) 
                {
                    case $upd_un:
                        break;
                    default:
                        $log = array(
                        'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                        'module_group_guid' => $this->input->post('module_group_guid'),
                        'module_group_description' => $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid='".$this->input->post('module_group_guid')."'")->row('a'),
                        'section' => 'User',
                        'field' => 'User Name',
                        'value_guid' => $check_data->row('user_guid'),
                        'value_from' => $ori_un,
                        'value_to' => $upd_un,
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $_SESSION['userid'],
                        );
                        $this->db->insert('userlog', $log);
                }

                switch ($ori_up) 
                {
                    case $upd_up:
                        break;
                    default:
                        $log = array(
                        'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                        'module_group_guid' => $this->input->post('module_group_guid'),
                        'module_group_description' => $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid='".$this->input->post('module_group_guid')."'")->row('a'),
                        'section' => 'User',
                        'field' => 'User Password',
                        'value_guid' => $check_data->row('user_guid'),
                        'value_from' => $ori_up,
                        'value_to' => $upd_up,
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $_SESSION['userid'],
                        );
                        $this->db->insert('userlog', $log);
                }

                switch ($ori_ug) 
                {
                    case $upd_ug:
                        break;
                    default:
                        $log = array(
                        'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                        'module_group_guid' => $this->input->post('module_group_guid'),
                        'module_group_description' => $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid='".$this->input->post('module_group_guid')."'")->row('a'),
                        'section' => 'User',
                        'field' => 'User Group',
                        'value_guid' => $check_data->row('user_guid'),
                        'value_from' => $ori_ug,
                        'value_to' => $upd_ug,
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $_SESSION['userid'],
                        );
                        $this->db->insert('userlog', $log);
                }

                switch ($ori_mg) 
                {
                    case $upd_mg:
                        break;
                    default:
                        $log = array(
                        'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                        'module_group_guid' => $this->input->post('module_group_guid'),
                        'module_group_description' => $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid='".$this->input->post('module_group_guid')."'")->row('a'),
                        'section' => 'User',
                        'field' => 'User Group',
                        'value_guid' => $check_data->row('user_guid'),
                        'value_from' => $ori_mg,
                        'value_to' => $upd_mg,
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $_SESSION['userid'],
                        );
                        $this->db->insert('userlog', $log);
                }

                switch ($ori_ac) 
                {
                    case $upd_ac:
                        break;
                    default:
                        $log = array(
                        'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                        'module_group_guid' => $this->input->post('module_group_guid'),
                        'module_group_description' => $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid='".$this->input->post('module_group_guid')."'")->row('a'),
                        'section' => 'User',
                        'field' => 'Active',
                        'value_guid' => $check_data->row('user_guid'),
                        'value_from' => $ori_ac,
                        'value_to' => $upd_ac,
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $_SESSION['userid'],
                        );
                        $this->db->insert('userlog', $log);
                }

                if ($_SESSION['user_group_guid'] == '') 
                {
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
                }
                else
                {
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid'].'&user_group_guid='.$_SESSION['user_group_guid']);
                }
                    
            }
            else // insert data
            {
                $check_data = $this->db->query("SELECT a.*,d.`module_group_name`,e.`user_group_name` FROM set_user a INNER JOIN set_user_module b ON a.`user_group_guid` = b.`user_group_guid` INNER JOIN set_module c ON c.`module_guid` = b.`module_guid` INNER JOIN set_module_group d ON d.`module_group_guid` = c.`module_group_guid` AND d.`module_group_guid` = a.`module_group_guid` INNER JOIN set_user_group e ON e.`user_group_guid` = a.`user_group_guid` WHERE d.`module_group_guid` = '".$_SESSION['module_group_guid']."' AND a.`user_id` = '".$this->input->post('userid')."'GROUP BY a.`user_id`;");

                if($check_data->num_rows() > 0)
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">UserID Already Exist. Please try with different UserID.<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
                };

                if($this->input->post('user_group') == 'Select Group')
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Please Select Valid User Group<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
                };

                $data = array(
                    //if b2b, acc_guid will be using session customer_guid
                    'acc_guid' => $_SESSION['customer_guid'],
                    'module_group_guid' => $this->input->post('module_group_guid'),
                    'isactive' => $this->input->post('active'),
                    'user_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid'),
                    'user_group_guid' => $this->input->post('user_group'),
                    'user_id' => $this->input->post('userid'),
                    'user_name' =>$this->input->post('name'),
                    'user_password' =>md5($this->input->post('password')),
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    'limited_location' => $this->input->post('limited_location'),
                    );
                $this->Module_setup_model->insert_data($table, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                if ($_SESSION['user_group_guid'] == '') 
                {
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
                }
                else
                {
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid'].'&user_group_guid='.$_SESSION['user_group_guid']);
                }
            }
        }
        else
        {
            redirect('login_c');
        }
        
    }

    public function module_form()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $table = 'set_module';
            $col_guid = 'module_group_guid';
            $guid = $this->input->post('module_group_guid');

            $module = $this->input->post('module');

            $moduleguid = json_encode($module);

            foreach($module as $i => $id) {

                // check existing module guid
               $checkmoduleguid = $this -> db
                   -> select('module_guid')
                   -> from ('set_module')
                   -> where_in('module_guid', json_decode($moduleguid) )
                   -> get();
                // echo $this->db->last_query();die;
            }

            foreach ($module as $i => $id) {
                
                $data[] = [ 'module_guid' => $id,
                        // 'acc_module_group_guid' => $this->input->post('module_group'),
                        'module_group_guid' => $this->input->post('module_group_guid'),
                        // 'module_seq' => $this->input->post('seq'),
                        // 'module_name' => '',
                        'updated_at' => $this->datetime(),
                        'updated_by' => $_SESSION['userid'],
                        'created_at' => $this->datetime(),
                        'created_by' => $_SESSION['userid'],
                            ];

            }

            if($checkmoduleguid->num_rows() > 0)
            {
                $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Record Already Exist!<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            };

            $this->db->insert_batch($table, $data, 'module_guid');// insert record
            $this->db->query("UPDATE set_module a INNER JOIN acc_module b ON a.`module_guid` = b.`acc_module_guid`
                SET a.`module_name` = b.`acc_module_name`, a.`module_seq` =b.`acc_module_seq`, a.module_code = b.acc_module_code WHERE a.`module_group_guid` = '".$this->input->post('module_group_guid')."'");// update record match to acc_module
            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            //check data exist or not
            // $check_data = $this->db->query("SELECT * FROM set_module  WHERE module_guid = '".$module[0]."' ");

            // if($check_data->num_rows() > 0)// update data
            // {
            //     $guid = $check_data->row('module_guid');
            //     $data = array(
            //         'module_group_guid' => $_SESSION['module_group_guid'],
            //         'module_seq' => $this->input->post('seq'),
            //         'module_name' =>$module[1],
            //         'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            //         'updated_by' => $_SESSION['userid'],
            //         );
            //     $this->Module_setup_model->update_data($table,$col_guid, $guid, $data);
            //     $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
            //         redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            // }
            // else // insert data
            // {
            //     if($this->input->post('module') == 'Select Group')
            //     {
            //         $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Please Select Valid Module Group<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
            //         redirect('acc_master_setup');
            //     };

            //     $data = array(

            //         'module_guid' => $module[0],
            //         // 'acc_module_group_guid' => $this->input->post('module_group'),
            //         'module_group_guid' => $_SESSION['module_group_guid'],
            //         'module_seq' => $this->input->post('seq'),
            //         'module_name' => $module[1],
            //         'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            //         'updated_by' => $_SESSION['userid'],
            //         'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            //         'created_by' => $_SESSION['userid'],
            //         );
            //     $this->Module_setup_model->insert_data($table, $data);
            //     $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
            //         redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            // }
        }
        else
        {
            redirect('login_c');
        }
        
    }

    public function branch_form_backup()
    {
        $table = 'set_user';
        $col_guid = 'user_guid';
        $user_guid = $this->input->post('guid');

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            if($this->input->post('branch_mode') == 'Concept')
            {
                $concept_guid = $this->input->post('concept[]');
                $guid = json_encode($concept_guid);

                foreach($concept_guid as $i => $id) {

                    // check branch guid belong to branch group guid
                   $getbranchguid = $this -> db
                       -> select('a. branch_guid')
                       -> from ('acc_branch a')
                       -> join ('acc_concept b', 'a.concept_guid = b.concept_guid', 'inner')
                       -> where_in('b.concept_guid', json_decode($guid) )
                       -> group_by('a.branch_guid')
                       -> get('acc_branch');
                    // echo $this->db->last_query();die;
                       $result = $getbranchguid->result();
                }

                foreach ($result as $i => $id) {

                $branchguidarray = json_encode($id->branch_guid);

                $checkbranchguid = $this -> db
                       -> select('branch_guid')
                       -> from ('set_user')
                       -> where ('user_guid', $this->input->post('guid'))
                       -> where_in('branch_guid', json_decode($branchguidarray) )
                       -> get();
                    
                $data[] = [ 'user_guid' => $this->input->post('guid'),
                            'acc_guid' => $this->input->post('acc_guid'),
                            'branch_guid' => $id->branch_guid,
                            'module_group_guid' => $this->input->post('module_group_guid'),
                            'isactive' => $this->input->post('isactive'),
                            'user_group_guid' => $this->input->post('user_group_guid'),
                            'user_id' => $this->input->post('user_id'),
                            'user_name' => $this->input->post('user_name'),
                            'user_password' => $this->input->post('user_password'),
                            'updated_at' => $this->datetime(),
                            'updated_by' => $_SESSION['userid'],
                            'created_at' => $this->input->post('created_at'),
                            'created_by' => $this->input->post('created_by'), 
                            'limited_location' => $this->input->post('limited_location'), 
                            ];
                }
            };

            if($this->input->post('branch_mode') == 'BranchGroup')
            {
                $branch_group_guid = $this->input->post('branch_group[]');
                $guid = json_encode($branch_group_guid);

                foreach($branch_group_guid as $i => $id) {

                    // check branch guid belong to branch group guid
                   $getbranchguid = $this -> db
                       -> select('a. branch_guid')
                       -> from ('acc_branch a')
                       -> join ('acc_branch_group b', 'a.branch_group_guid = b.branch_group_guid', 'inner')
                       -> where_in('b.branch_group_guid', json_decode($guid) )
                       -> group_by('a.branch_guid')
                       -> get('acc_branch');
                    // echo $this->db->last_query();die;
                       $result = $getbranchguid->result();
                }

                foreach ($result as $i => $id) {
                $branchguidarray = json_encode($id->branch_guid);

                $checkbranchguid = $this -> db
                       -> select('branch_guid')
                       -> from ('set_user')
                       -> where ('user_guid', $this->input->post('guid'))
                       -> where_in('branch_guid', json_decode($branchguidarray) )
                       -> get();
                    
                $data[] = [ 'user_guid' => $this->input->post('guid'),
                            'acc_guid' => $this->input->post('acc_guid'),
                            'branch_guid' => $id->branch_guid,
                            'module_group_guid' => $this->input->post('module_group_guid'),
                            'isactive' => $this->input->post('isactive'),
                            'user_group_guid' => $this->input->post('user_group_guid'),
                            'user_id' => $this->input->post('user_id'),
                            'user_name' => $this->input->post('user_name'),
                            'user_password' => $this->input->post('user_password'),
                            'updated_at' => $this->datetime(),
                            'updated_by' => $_SESSION['userid'],
                            'created_at' => $this->input->post('created_at'),
                            'created_by' => $this->input->post('created_by'), 
                            'limited_location' => $this->input->post('limited_location'), 
                            ];
                }

            };

            if($this->input->post('branch_mode') == 'Branch')
            {
                $branch_guid = $this->input->post('branch[]');
                $branchguidarray = json_encode($branch_guid);

                foreach($branch_guid as $i => $id) {

                $checkbranchguid = $this -> db
                       -> select('branch_guid')
                       -> from ('set_user')
                       -> where ('user_guid', $this->input->post('guid'))
                       -> where_in('branch_guid', json_decode($branchguidarray) )
                       -> get();

                $data[] = [ 'user_guid' => $this->input->post('guid'),
                            'acc_guid' => $this->input->post('acc_guid'),
                            'branch_guid' => $id,
                            'module_group_guid' => $this->input->post('module_group_guid'),
                            'isactive' => $this->input->post('isactive'),
                            'user_group_guid' => $this->input->post('user_group_guid'),
                            'user_id' => $this->input->post('user_id'),
                            'user_name' => $this->input->post('user_name'),
                            'user_password' => $this->input->post('user_password'),
                            'updated_at' => $this->datetime(),
                            'updated_by' => $_SESSION['userid'],
                            'created_at' => $this->input->post('created_at'),
                            'created_by' => $this->input->post('created_by'), 
                            'limited_location' => $this->input->post('limited_location'), 
                            ];
                }
            };

            if($checkbranchguid->num_rows() > 0)
            {
                $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Record Already Exist!<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            }

            $this->Module_setup_model->delete_user_branch($table, $col_guid, $user_guid);// delete previous data that havent assign the branch
            $this->db->insert_batch($table, $data, 'user_guid');// insert new record with branch guid
            // echo $this->db->last_query();die;
            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
        }
        else
        {
            redirect('login_c');
        }
    }

    public function branch_form()
    {
        $table = 'set_user_branch';
        $col_guid = 'user_guid';
        $user_guid = $this->input->post('guid');
        $acc_guid = $_SESSION['customer_guid'];
        // echo $acc_guid;die;

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            if($this->input->post('branch_mode') == 'Concept')
            {
                $concept_guid = $this->input->post('concept[]');
                $guid = json_encode($concept_guid);

                foreach($concept_guid as $i => $id) {

                    // check branch guid belong to branch group guid
                   $getbranchguid = $this -> db
                       -> select('a. branch_guid')
                       -> from ('acc_branch a')
                       -> join ('acc_concept b', 'a.concept_guid = b.concept_guid', 'inner')
                       -> where_in('b.concept_guid', json_decode($guid) )
                       -> group_by('a.branch_guid')
                       -> get('acc_branch');
                    // echo $this->db->last_query();die;
                       $result = $getbranchguid->result();

                }

                foreach ($result as $i => $id) {

                $branchguidarray = json_encode($id->branch_guid);

                $checkbranchguid = $this -> db
                       -> select('branch_guid')
                       -> from ('set_user')
                       -> where ('user_guid', $this->input->post('guid'))
                       -> where_in('branch_guid', json_decode($branchguidarray) )
                       -> get();
                    
                // $data[] = [ 'user_guid' => $this->input->post('guid'),
                //             'acc_guid' => $this->input->post('acc_guid'),
                //             'branch_guid' => $id->branch_guid,
                //             'module_group_guid' => $this->input->post('module_group_guid'),
                //             'isactive' => $this->input->post('isactive'),
                //             'user_group_guid' => $this->input->post('user_group_guid'),
                //             'user_id' => $this->input->post('user_id'),
                //             'user_name' => $this->input->post('user_name'),
                //             'user_password' => $this->input->post('user_password'),
                //             'updated_at' => $this->datetime(),
                //             'updated_by' => $_SESSION['userid'],
                //             'created_at' => $this->input->post('created_at'),
                //             'created_by' => $this->input->post('created_by'), 
                //             ];

                $data[] = [ 'user_guid' => $this->input->post('guid'),
                            'acc_guid' => $this->input->post('acc_guid'),
                            'branch_guid' => $id->branch_guid,
                            'updated_at' => $this->datetime(),
                            'updated_by' => $_SESSION['userid'],
                            'created_at' => $this->input->post('created_at'),
                            'created_by' => $this->input->post('created_by'), 
                            ];                            
                }
            };

            if($this->input->post('branch_mode') == 'BranchGroup')
            {
                $branch_group_guid = $this->input->post('branch_group[]');
                $guid = json_encode($branch_group_guid);

                foreach($branch_group_guid as $i => $id) {

                    // check branch guid belong to branch group guid
                   $getbranchguid = $this -> db
                       -> select('a. branch_guid')
                       -> from ('acc_branch a')
                       -> join ('acc_branch_group b', 'a.branch_group_guid = b.branch_group_guid', 'inner')
                       -> where_in('b.branch_group_guid', json_decode($guid) )
                       -> group_by('a.branch_guid')
                       -> get('acc_branch');
                    // echo $this->db->last_query();die;
                       $result = $getbranchguid->result();
                }

                foreach ($result as $i => $id) {
                $branchguidarray = json_encode($id->branch_guid);

                $checkbranchguid = $this -> db
                       -> select('branch_guid')
                       -> from ('set_user')
                       -> where ('user_guid', $this->input->post('guid'))
                       -> where_in('branch_guid', json_decode($branchguidarray) )
                       -> get();
                    
                // $data[] = [ 'user_guid' => $this->input->post('guid'),
                //             'acc_guid' => $this->input->post('acc_guid'),
                //             'branch_guid' => $id->branch_guid,
                //             'module_group_guid' => $this->input->post('module_group_guid'),
                //             'isactive' => $this->input->post('isactive'),
                //             'user_group_guid' => $this->input->post('user_group_guid'),
                //             'user_id' => $this->input->post('user_id'),
                //             'user_name' => $this->input->post('user_name'),
                //             'user_password' => $this->input->post('user_password'),
                //             'updated_at' => $this->datetime(),
                //             'updated_by' => $_SESSION['userid'],
                //             'created_at' => $this->input->post('created_at'),
                //             'created_by' => $this->input->post('created_by'), 
                //             ];

                $data[] = [ 'user_guid' => $this->input->post('guid'),
                            'acc_guid' => $this->input->post('acc_guid'),
                            'branch_guid' => $id->branch_guid,
                            'updated_at' => $this->datetime(),
                            'updated_by' => $_SESSION['userid'],
                            'created_at' => $this->input->post('created_at'),
                            'created_by' => $this->input->post('created_by'), 
                            ];

                }

            };

            if($this->input->post('branch_mode') == 'Branch')
            {
                $branch_guid = $this->input->post('branch[]');
                $branchguidarray = json_encode($branch_guid);

                foreach($branch_guid as $i => $id) {

                $checkbranchguid = $this -> db
                       -> select('branch_guid')
                       -> from ('set_user')
                       -> where ('user_guid', $this->input->post('guid'))
                       -> where_in('branch_guid', json_decode($branchguidarray) )
                       -> get();

                // $data[] = [ 'user_guid' => $this->input->post('guid'),
                //             'acc_guid' => $this->input->post('acc_guid'),
                //             'branch_guid' => $id,
                //             'module_group_guid' => $this->input->post('module_group_guid'),
                //             'isactive' => $this->input->post('isactive'),
                //             'user_group_guid' => $this->input->post('user_group_guid'),
                //             'user_id' => $this->input->post('user_id'),
                //             'user_name' => $this->input->post('user_name'),
                //             'user_password' => $this->input->post('user_password'),
                //             'updated_at' => $this->datetime(),
                //             'updated_by' => $_SESSION['userid'],
                //             'created_at' => $this->input->post('created_at'),
                //             'created_by' => $this->input->post('created_by'), 
                //             ];

                $data[] = [    'user_guid' => $this->input->post('guid'),
                                'acc_guid' => $this->input->post('acc_guid'),
                                'branch_guid' => $id,
                                'updated_at' => $this->datetime(),
                                'updated_by' => $_SESSION['userid'],
                                'created_at' => $this->input->post('created_at'),
                                'created_by' => $this->input->post('created_by'), 
                            ];                            
                }
            };

            // if($checkbranchguid->num_rows() > 0)
            // {
            //     $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Record Already Exist!<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
            // if($_SESSION['system_admin'] == 1)
            // {                
            //         redirect('module_setup/admin_module?module_group_guid='.$_SESSION['module_group_guid']);
            // }
            // else
            // {
            //         redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            // }
            // }

            // $this->Module_setup_model->delete_user_branch($table, $col_guid, $user_guid);// delete previous data that havent assign the branch
            // $this->db->insert_batch($table, $data, 'user_guid');// insert new record with branch guid
            // echo $this->db->last_query();die;
            // print_r($data);die;
            if($this->input->post('branch_mode') == 'Concept' || $this->input->post('branch_mode') == 'BranchGroup')
            {
            $this->db->replace_batch($table, $data, 'user_guid');// insert new record with branch guid
            }
            else
            {
                $duser_guid = $this->input->post('guid');
                $deletestatus = $this->db->query("SELECT user_guid FROM set_user_branch WHERE user_guid = '$duser_guid' AND acc_guid = '$acc_guid'");
                // echo $this->db->last_query();die;
                if($deletestatus->num_rows() > 0) 
                {
                        $this->db->query("DELETE FROM set_user_branch WHERE user_guid = '$duser_guid' AND acc_guid = '$acc_guid'");   
                        if($this->db->affected_rows() > 0)
                        {
                            $this->db->replace_batch($table, $data, 'user_guid');  
                        }
                        else
                        {
                            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Failed to Insert Record<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                            redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);  
                        }
                }
                else
                {
                    $this->db->replace_batch($table, $data, 'user_guid');
                }
            }
            
            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Updated successfully.<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');

            redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
            
        }
        else
        {
            redirect('login_c');
        }
    }    

    public function check()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {

            $guid = $this->input->post('guid[]');       
            $active = $this->input->post('active[]');
            
            $data = array();

            foreach($guid as $i => $id) {
                $data[] = [$_REQUEST['col_guid'] => $id, $_REQUEST['col_check'] => $active[$i] ];

                $ori_data[$i] = $this->db->query("SELECT isactive FROM set_user WHERE user_guid='$id' ")->row('isactive');
                $ori_isenable[$i] = $this->db->query("SELECT isenable FROM set_user_module WHERE user_module_guid='$id' ")->row('isenable');
                $ori_isactive[$i] = $this->db->query("SELECT isactive FROM set_user_group WHERE user_group_guid='$id' ")->row('isactive');
            }

            $this->db->update_batch($_REQUEST['table'], $data, $_REQUEST['col_guid']);
            // echo $this->db->last_query();die;

            if($_REQUEST['edit'] == '1')
            {
                foreach($guid as $i => $id) {
                    $upd_isenable[$i] = $this->db->query("SELECT * FROM set_user_module WHERE user_module_guid='$id' ")->row('isenable');
                    $variable[$i] = $this->db->query("SELECT * FROM set_user_module WHERE user_module_guid='$id' ");
                    $u_module_guid[$i] = $variable[$i]->row('user_module_guid');
                    $m_group_guid[$i] = $variable[$i]->row('module_group_guid');
                    $m_group_description[$i] = $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid = '$m_group_guid[$i]' ")->row('a');

                    switch ($ori_isenable[$i]) 
                    {
                        case $upd_isenable[$i]:
                            break;
                        default:
                            $log = array(
                            'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                            'module_group_guid' => $m_group_guid[$i],
                            'module_group_description' => $m_group_description[$i],
                            'section' => 'User Module',
                            'field' => 'Enable',
                            'value_guid' => $u_module_guid[$i],
                            'value_from' => $ori_isenable[$i],
                            'value_to' => $upd_isenable[$i],
                            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                            'created_by' => $_SESSION['userid'],
                            );
                        $this->db->insert('userlog', $log);
                    }
                }
            };

            if($_REQUEST['edit'] == '2')
            {
                foreach($guid as $i => $id) {
                    $upd_data[$i] = $this->db->query("SELECT * FROM set_user  WHERE user_guid='$id' ")->row('isactive');
                    $m_group_guid[$i] = $this->db->query("SELECT * FROM set_user  WHERE user_guid='$id' ")->row('module_group_guid');
                    $m_g_description = $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid = '$m_group_guid[$i]'")->row('a');

                    switch ($ori_data[$i]) 
                    {
                        case $upd_data[$i]:
                            break;
                        default:
                            $log = array(
                            'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                            'module_group_guid' => $m_group_guid[$i],
                            'module_group_description' => $m_g_description,
                            'section' => 'User',
                            'field' => 'Active',
                            'value_guid' => $this->db->query("SELECT user_guid FROM set_user  WHERE user_guid='$id' ")->row('user_guid'),
                            'value_from' => $ori_data[$i],
                            'value_to' => $upd_data[$i],
                            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                            'created_by' => $_SESSION['userid'],
                            );
                        $this->db->insert('userlog', $log);
                    }
                }
            };

            if($_REQUEST['edit'] == '3')
            {
                foreach($guid as $i => $id) {
                    $upd_isactive[$i] = $this->db->query("SELECT * FROM set_user_group WHERE user_group_guid = '$id' ")->row('isactive');
                    /*$isactive[$i] = $upd_data[$i]->row('isactive');*/
                    /*$m_group_guid[$i] = $this->db->query("SELECT * FROM set_user  WHERE user_guid='$id' ")->row('module_group_guid');
                    $m_g_description = $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid = '$m_group_guid[$i]'")->row('a');*/

                    switch ($ori_isactive[$i]) 
                    {
                        case $upd_isactive[$i]:
                            break;
                        default:
                            $log = array(
                            'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                            'module_group_guid' => '',
                            'module_group_description' => 'Module Setup',
                            'section' => 'User Group',
                            'field' => 'Active',
                            'value_guid' => $this->db->query("SELECT * FROM set_user_group WHERE user_group_guid = '$id' ")->row('user_group_guid'),
                            'value_from' => $ori_isactive[$i],
                            'value_to' => $upd_isactive[$i],
                            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                            'created_by' => $_SESSION['userid'],
                            );
                        $this->db->insert('userlog', $log);
                    }
                }
            };
            if ($_SESSION['user_group_guid'] == '') 
                {
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
                }
                else
                {
                    redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid'].'&user_group_guid='.$_SESSION['user_group_guid']);
                }
        }
        else
        {
            redirect('login_c');
        }
    }

    public function delete_module_group()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            // $guid = $_REQUEST['guid'];
            // $table = $_REQUEST['table'];
            // $col_guid = $_REQUEST['col_guid'];
            // $this->Module_setup_model->delete_data($table, $col_guid, $guid);
            // $this->session->set_flashdata('message', 'Record Deleted');
            //     redirect('module_setup');
            $this->db->query("DELETE a.*,b.* FROM set_module_group a INNER JOIN set_module b ON a.module_group_guid = b.module_group_guid WHERE a.module_group_guid = '".$_REQUEST['guid']."'");
            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Deleted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('module_setup');
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
            $guid = $_REQUEST['guid'];
            $table = $_REQUEST['table'];
            $col_guid = $_REQUEST['col_guid'];

            if ($_REQUEST['delete'] == '1')
            {
                $check_data = $this->db->query("SELECT * FROM set_user_module WHERE user_module_guid = '$guid' ");
                $m_guid = $check_data->row('module_guid');
                $m_group_guid = $check_data->row('module_group_guid');
                $u_group_guid = $check_data->row('user_group_guid');
                $m_g_description = $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid = '$m_group_guid'")->row('a');
                $module_code = $this->db->query("SELECT * FROM set_module WHERE module_group_guid = '$m_group_guid' AND module_guid = '$m_guid' ");
                $user_group = $this->db->query("SELECT * FROM set_user_group WHERE user_group_guid = '$u_group_guid' ")->row('user_group_name');
                $field = array('Enable', 'Module Code', 'Module Description', 'User Group', 'Module Group');
                $value = array(
                    $check_data->row('isenable'), 
                    $module_code->row('module_code'), //set_module
                    $module_code->row('module_name'),
                    $user_group,
                    $m_g_description,
                    );

                for ($x = 0; $x <= 4; $x++) {
                $log = array(
                    'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                    'module_group_guid' => $m_group_guid,
                    'module_group_description' => $m_g_description,
                    'section' => 'User Module',
                    'field' => $field[$x],
                    'value_guid' => $check_data->row('user_module_guid'),
                    'value_from' => $value[$x],
                    'value_to' => 'Deleted',
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->db->insert('userlog', $log);
                }
            };

            if ($_REQUEST['delete'] == '2')
            {
                $check_data = $this->db->query("SELECT * FROM set_user WHERE user_guid = '$guid' ");
                $result = $check_data->row('user_group_guid');
                $user = $this->db->query("SELECT * FROM set_user_group  WHERE user_group_guid = '$result' ")->row('user_group_name');
                $m_group_guid = $check_data->row('module_group_guid');
                $m_g_description = $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid = '$m_group_guid'")->row('a');
                $field = array('User ID', 'User Name', 'User Password', 'User Group', 'Module Group', 'Active');
                $value = array($check_data->row('user_id'), $check_data->row('user_name'), $check_data->row('user_password'), $user, $m_g_description, $check_data->row('isactive'));

                for ($x = 0; $x <= 5; $x++) {
                $log = array(
                    'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                    'module_group_guid' => $m_group_guid,
                    'module_group_description' => $m_g_description,
                    'section' => 'User',
                    'field' => $field[$x],
                    'value_guid' => $check_data->row('user_guid'),
                    'value_from' => $value[$x],
                    'value_to' => 'Deleted',
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->db->insert('userlog', $log);
                $this->db->query("DELETE FROM set_user_branch WHERE user_guid = '$guid'");
                $this->db->query("DELETE FROM set_supplier_user_relationship WHERE user_guid = '$guid'");
                }
            };

            if ($_REQUEST['delete'] == '3')
            {
                $check_data = $this->db->query("SELECT a.*, c.module_group_name FROM set_module a INNER JOIN acc_module b ON a.module_guid = b.acc_module_guid INNER JOIN set_module_group c ON c.module_group_guid = a.module_group_guid WHERE module_guid = '$guid' ");
                $m_group_guid = $check_data->row('module_group_guid');
                $m_g_description = $this->db->query("SELECT module_group_name as a FROM set_module_group WHERE module_group_guid = '$m_group_guid'")->row('a');
                $field = array('Sequence', 'Module Code', 'Module Description', 'Module Group Name');
                $value = array(
                    $check_data->row('module_seq'),
                    $check_data->row('module_code'),
                    $check_data->row('module_name'),
                    $m_g_description,
                    );

                for ($x = 0; $x <= 3; $x++) {
                $log = array(
                    'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                    'module_group_guid' => $m_group_guid,
                    'module_group_description' => $m_g_description,
                    'section' => 'Module',
                    'field' => $field[$x],
                    'value_guid' => $check_data->row('module_guid'),
                    'value_from' => $value[$x],
                    'value_to' => 'Deleted',
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->db->insert('userlog', $log);
                }
            };

            if ($_REQUEST['delete'] == '4')
            {
                $check_data = $this->db->query("SELECT * FROM set_module_group WHERE module_group_guid = '$guid' ");
                $m_group_guid = $check_data->row('module_group_guid');
                $m_g_description = $check_data->row('module_group_name');
                $field = array('Sequence', 'Module Group Name');
                $value = array(
                    $check_data->row('module_group_seq'),
                    $check_data->row('module_group_name'),
                    );

                for ($x = 0; $x <= 1; $x++) {
                $log = array(
                    'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                    'module_group_guid' => $m_group_guid,
                    'module_group_description' => $m_g_description,
                    'section' => 'Module Group',
                    'field' => $field[$x],
                    'value_guid' => $check_data->row('module_group_guid'),
                    'value_from' => $value[$x],
                    'value_to' => 'Deleted',
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->db->insert('userlog', $log);
                }
            };

            if ($_REQUEST['delete'] == '5')
            {
                $check_data = $this->db->query("SELECT * FROM set_user_group WHERE user_group_guid = '$guid' ");
                /*$m_group_guid = $check_data->row('module_group_guid');
                $m_g_description = $check_data->row('module_group_name');*/
                $field = array('Active', 'User Group Name');
                $value = array(
                    $check_data->row('isactive'),
                    $check_data->row('user_group_name'),
                    );

                for ($x = 0; $x <= 1; $x++) {
                $log = array(
                    'trans_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as uuid")->row('uuid'),
                    'module_group_guid' => '',
                    'module_group_description' => 'Module Setup',
                    'section' => 'User Group',
                    'field' => $field[$x],
                    'value_guid' => $check_data->row('user_group_guid'),
                    'value_from' => $value[$x],
                    'value_to' => 'Deleted',
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $_SESSION['userid'],
                    );
                $this->db->insert('userlog', $log);
                }
            };
            
            $this->Module_setup_model->delete_data($table, $col_guid, $guid);
            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Deleted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);
        }
        else
        {
            redirect('login_c');
        }
    }

    

    public function delete_user_branch()
    {
        $this->db->query("DELETE FROM `set_user` WHERE branch_guid = '".$_REQUEST['branch_guid']."' AND user_guid = '".$_REQUEST['user_guid']."' ");

        $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Deleted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);

    }

    public function UpdateAllOutlet_backup()
    {
        /*get module and which customer 1st
        then we query out all assigned or non assigned
        we make sure at least got 1 record then we find NULL 
        we fill those null outlet*/

        $module_group_guid = $_REQUEST['module_group_guid'];
        $customer_guid = $_REQUEST['customer_guid'];

        $check_unique_user_for_module = $this->db->query("SELECT user_guid from set_user where acc_guid ='$customer_guid' and module_group_guid = '$module_group_guid' and limited_location = '0' group by user_guid ");

        foreach($check_unique_user_for_module->result() as $row)
        {
            $check_all_data = $this->db->query("SELECT * FROM ( SELECT a.acc_guid, acc_name , branch_guid, branch_code, branch_name FROM acc AS a INNER JOIN acc_concept AS b ON a.acc_guid = b.acc_guid INNER JOIN acc_branch AS c ON b.concept_guid = c.concept_guid WHERE a.acc_guid = '$customer_guid' ) aa LEFT JOIN ( SELECT `acc_guid` AS `second_acc_guid` , `branch_guid` AS `second_branch_guid` , `module_group_guid` AS `second_module_group_guid` , `user_group_guid` AS `second_user_group_guid` , `user_guid` AS `second_user_guid` , `supplier_guid` AS `second_supplier_guid` , `isactive` AS `second_isactive` , `user_id` AS `second_user_id` , `user_password` AS `second_user_password` , `user_name` AS `second_user_name` , `created_at` AS `second_created_at` , `created_by` AS `second_created_by` , `updated_at` AS `second_updated_at` , `updated_by` AS `second_updated_by` FROM set_user WHERE module_group_guid = '$module_group_guid' AND acc_guid = '$customer_guid' AND user_guid = '".$row->user_guid."' ) bb ON aa.acc_guid = bb.second_acc_guid AND aa.branch_guid = bb.second_branch_guid WHERE bb.second_acc_guid IS NOT NULL limit 1");


            $check_all_pending_to_insert = $this->db->query("SELECT `second_acc_guid` , `second_branch_guid` , `second_module_group_guid` , `second_user_group_guid` , `second_user_guid` , `second_supplier_guid` , `second_isactive` , `second_user_id` , `second_user_password` , `second_user_name` , `second_created_at` , `second_created_by` , `second_updated_at` , `second_updated_by` FROM ( SELECT a.acc_guid, acc_name , branch_guid, branch_code, branch_name FROM acc AS a INNER JOIN acc_concept AS b ON a.acc_guid = b.acc_guid INNER JOIN acc_branch AS c ON b.concept_guid = c.concept_guid WHERE a.acc_guid = '$customer_guid' ) aa LEFT JOIN ( SELECT `acc_guid` AS `second_acc_guid` , `branch_guid` AS `second_branch_guid` , `module_group_guid` AS `second_module_group_guid` , `user_group_guid` AS `second_user_group_guid` , `user_guid` AS `second_user_guid` , `supplier_guid` AS `second_supplier_guid` , `isactive` AS `second_isactive` , `user_id` AS `second_user_id` , `user_password` AS `second_user_password` , `user_name` AS `second_user_name` , `created_at` AS `second_created_at` , `created_by` AS `second_created_by` , `updated_at` AS `second_updated_at` , `updated_by` AS `second_updated_by` FROM set_user WHERE module_group_guid = '$module_group_guid' AND acc_guid = '$customer_guid' AND user_guid = '".$row->user_guid."' ) bb ON aa.acc_guid = bb.second_acc_guid AND aa.branch_guid = bb.second_branch_guid WHERE bb.second_acc_guid IS NULL ");


            if($check_all_data->num_rows() > 0)
            {
                $check_missing_branch_guid = $this->db->query("SELECT branch_guid FROM ( SELECT a.acc_guid, acc_name , branch_guid, branch_code, branch_name FROM acc AS a INNER JOIN acc_concept AS b ON a.acc_guid = b.acc_guid INNER JOIN acc_branch AS c ON b.concept_guid = c.concept_guid WHERE a.acc_guid = '$customer_guid' ) aa LEFT JOIN ( SELECT `acc_guid` AS `second_acc_guid` , `branch_guid` AS `second_branch_guid` , `module_group_guid` AS `second_module_group_guid` , `user_group_guid` AS `second_user_group_guid` , `user_guid` AS `second_user_guid` , `supplier_guid` AS `second_supplier_guid` , `isactive` AS `second_isactive` , `user_id` AS `second_user_id` , `user_password` AS `second_user_password` , `user_name` AS `second_user_name` , `created_at` AS `second_created_at` , `created_by` AS `second_created_by` , `updated_at` AS `second_updated_at` , `updated_by` AS `second_updated_by` FROM set_user WHERE module_group_guid = '$module_group_guid' AND acc_guid = '$customer_guid' AND user_guid = '".$row->user_guid."' ) bb ON aa.acc_guid = bb.second_acc_guid AND aa.branch_guid = bb.second_branch_guid WHERE bb.second_acc_guid IS NULL ");
 
                foreach($check_missing_branch_guid->result() as $row2)
                {
                    $data = array(
                    'acc_guid' =>  $customer_guid,
                    'branch_guid' => $row2->branch_guid,
                    'module_group_guid' =>  $module_group_guid,
                    'user_group_guid' => $check_all_data->row('second_user_group_guid'),
                    'user_guid' => $check_all_data->row('second_user_guid'),
                    'supplier_guid' => $check_all_data->row('second_supplier_guid'),
                    'isactive' =>  $check_all_data->row('second_isactive'),
                    'user_id' => $check_all_data->row('second_user_id'),
                    'user_password' => $check_all_data->row('second_user_password'),
                    'user_name' => $check_all_data->row('second_user_name'),
                    'created_at' => $this->datetime(),
                    'created_by' => 'AutoGen',
                    'updated_at' => $this->datetime(),
                    'updated_by' => $_SESSION['userid'],
                    );
                    $this->db->insert('set_user', $data);
                }
            }
        }// end foreach
        $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
        redirect('Module_setup');
    }

    public function UpdateAllOutlet()
    {
        /*get module and which customer 1st
        then we query out all assigned or non assigned
        we make sure at least got 1 record then we find NULL 
        we fill those null outlet*/

        $module_group_guid = $_REQUEST['module_group_guid'];
        $customer_guid = $_REQUEST['customer_guid'];

        $check_branch = $this->db->query("SELECT count(branch_guid) as countbranch FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid' LIMIT 1")->row('countbranch');

        $check_user_branch = $this->db->query("SELECT a.*,count(*) as cc FROM (SELECT * FROM set_user WHERE acc_guid = '$customer_guid' AND limited_location = 0 AND module_group_guid = '$module_group_guid') a LEFT JOIN (SELECT * FROM set_user_branch WHERE acc_guid = '$customer_guid') b on a.user_guid = b.user_guid GROUP BY a.user_guid having cc != '$check_branch'");
        if($check_user_branch->num_rows() > 0)
        {
            foreach($check_user_branch->result() as $row)
            {
                $user_guid = $row->user_guid;
                $acc_guid = $row->acc_guid;
                $update_user_branch = $this->db->query("SELECT b.acc_guid,a.branch_guid,c.user_guid FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid LEFT JOIN (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid' AND acc_guid = '$acc_guid') c ON a.branch_guid = c.branch_guid WHERE b.acc_guid = '$acc_guid' AND c.branch_guid IS NULL");

                foreach($update_user_branch->result() as $row2)
                {
                    $data = array(
                    'acc_guid' =>  $row2->acc_guid,
                    'branch_guid' => $row2->branch_guid,
                    'user_guid' => $user_guid,
                    'created_at' => $this->datetime(),
                    'created_by' => 'Force',
                    'updated_at' => $this->datetime(),
                    'updated_by' => $_SESSION['userid'],
                    );
                    $this->db->insert('set_user_branch', $data);
                }
                // print_r($data);die;
            }
            $afrows = $this->db->affected_rows();
            if($afrows > 0)
            {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('Module_setup');
            }
            else
            {
                $this->session->set_flashdata('message', '<div class="alert alert-danger text-center" style="font-size: 18px">Record Not Update<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('Module_setup'); 
            }
            // echo 1;die;
        }
        else
        {
            $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">All user assigned location<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('Module_setup'); 
        }
        
    }

    public function update_all_cus_all_outlet()
    {
        $module_group_guid = $_REQUEST['module_group_guid'];
        $user_guid = $_SESSION['user_guid'];
        $customer_guid = $_SESSION['customer_guid'];

        $check_super_admin = $this->db->query("SELECT * FROM set_user  AS a INNER JOIN set_user_group AS b ON a.`user_group_guid` = b.`user_group_guid` WHERE user_guid = '$user_guid' AND b.user_group_guid = '3379ECDBDB0711E7B504A81E8453CCF0' LIMIT 1");

        if($check_super_admin->num_rows() == '1')
        {
            $to_insert = $this->db->query("SELECT a.*,c.acc_guid FROM acc_branch AS a LEFT JOIN  (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid') AS b ON a.branch_guid = b.branch_guid INNER JOIN acc_concept c ON a.concept_guid = c.concept_guid WHERE b.branch_guid IS NULL");

            $get_cur_data = $this->db->query("SELECT * from set_user where user_guid = '$user_guid' limit 1");

            foreach($to_insert->result() as $row)
            {
                // $data = array(
                //     'acc_guid' =>  $customer_guid,
                //     'branch_guid' => $row->branch_guid,
                //     'module_group_guid' => $module_group_guid,
                //     'user_group_guid' => $get_cur_data->row('user_group_guid'),
                //     'user_guid' => $get_cur_data->row('user_guid'),
                //     'supplier_guid' => $get_cur_data->row('supplier_guid'),
                //     'isactive' =>  $get_cur_data->row('isactive'),
                //     'user_id' => $get_cur_data->row('user_id'),
                //     'user_password' => $get_cur_data->row('user_password'),
                //     'user_name' => $get_cur_data->row('user_name'),
                //     'created_at' => $this->datetime(),
                //     'created_by' => 'Force',
                //     'updated_at' => $this->datetime(),
                //     'updated_by' => $_SESSION['userid'],
                // );
                // $this->db->insert('set_user', $data);
                $data = array(
                    'acc_guid' =>  $row->acc_guid,
                    'branch_guid' => $row->branch_guid,
                    'user_guid' => $get_cur_data->row('user_guid'),
                    'created_at' => $this->datetime(),
                    'created_by' => 'Force',
                    'updated_at' => $this->datetime(),
                    'updated_by' => $_SESSION['userid'],
                );
                $this->db->insert('set_user_branch', $data);                
               // echo $this->db->last_query();die;
            }
            //echo var_dump($to_insert['affected_rows']);die;
            if($this->db->affected_rows() > 0)
            {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('Module_setup');
            }
            else
            {
                $this->session->set_flashdata('danger', '<div class="alert alert-success text-center" style="font-size: 18px">No records found<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('Module_setup');
            }
        }
        else
        {
             $this->session->set_flashdata('danger', '<div class="alert alert-success text-center" style="font-size: 18px">You are not authorized<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('Module_setup');
        }

    }

    public function duplicate_user()
    {
        $current_customer_guid = $_REQUEST['from'];
        $to_customer_guid = $_REQUEST['to'];
  
        if($current_customer_guid != '')
        {
            /*filter false = see what A got, B also got*/
            if( ($_REQUEST['filter'] )== 'false')
            {
                $from_customer_supplier =  $this->db->query("SELECT acc_name, a.* FROM ( SELECT b.supplier_name, b.supplier_guid , a.* FROM ( SELECT acc_guid, branch_guid, module_group_guid, user_group_guid, user_guid, isactive, user_id, user_name FROM set_user WHERE acc_guid = '$current_customer_guid' GROUP BY user_guid ) a INNER JOIN ( SELECT a.user_guid, b.* FROM set_supplier_user_relationship AS a INNER JOIN set_supplier AS b ON a.`supplier_guid` = b.`supplier_guid` WHERE customer_guid = '$current_customer_guid' AND b.isactive = '1' AND b.supplier_guid IS NOT NULL ) b ON a.user_guid =b.user_guid GROUP BY supplier_guid ORDER BY b.supplier_name, a.user_id ) a INNER JOIN acc AS b ON a.acc_guid = b.`acc_guid` ORDER BY supplier_name ASC");
                $from_customer =  $this->db->query("SELECT * from acc where isactive = '1'");
                $to_customer = $this->db->query("SELECT * from acc where acc_guid  <> '$current_customer_guid' and isactive = '1'" );
                $to_customer_supplier = $this->db->query("SELECT acc_name, a.*, 'empty' as to_supplier_guid FROM ( SELECT b.supplier_name, b.supplier_guid , a.* FROM ( SELECT acc_guid, branch_guid, module_group_guid, user_group_guid, user_guid, isactive, user_id, user_name FROM set_user WHERE acc_guid = '$to_customer_guid' GROUP BY user_guid ) a INNER JOIN ( SELECT a.user_guid, b.* FROM set_supplier_user_relationship AS a INNER JOIN set_supplier AS b ON a.`supplier_guid` = b.`supplier_guid` WHERE customer_guid = '$to_customer_guid' AND b.isactive = '1' AND b.supplier_guid IS NOT NULL ) b ON a.user_guid =b.user_guid GROUP BY supplier_guid ORDER BY b.supplier_name, a.user_id ) a INNER JOIN acc AS b ON a.acc_guid = b.`acc_guid` ORDER BY supplier_name ASC");

            }
            else
            {

                $from_customer_supplier =  $this->db->query("SELECT acc_name, a.* FROM ( SELECT b.supplier_name, b.supplier_guid , a.* FROM ( SELECT acc_guid, branch_guid, module_group_guid, user_group_guid, user_guid, isactive, user_id, user_name FROM set_user WHERE acc_guid = '$current_customer_guid' GROUP BY user_guid ) a INNER JOIN ( SELECT a.user_guid, b.* FROM set_supplier_user_relationship AS a INNER JOIN set_supplier AS b ON a.`supplier_guid` = b.`supplier_guid` WHERE customer_guid = '$current_customer_guid' AND b.isactive = '1' AND b.supplier_guid IS NOT NULL ) b ON a.user_guid =b.user_guid GROUP BY supplier_guid ORDER BY b.supplier_name, a.user_id ) a INNER JOIN acc AS b ON a.acc_guid = b.`acc_guid` ORDER BY supplier_name ASC");
                $from_customer =  $this->db->query("SELECT * from acc where isactive = '1'");
                $to_customer = $this->db->query("SELECT * from acc where acc_guid  <> '$current_customer_guid' and isactive = '1'" );
              /*  $to_customer_supplier = $this->db->query("SELECT * FROM ( SELECT acc_name, a.* FROM (SELECT b.supplier_name, b.supplier_guid, a.* FROM (SELECT acc_guid, branch_guid, module_group_guid, user_group_guid, user_guid, isactive, user_id, user_name FROM set_user WHERE acc_guid = '$current_customer_guid' GROUP BY user_guid) a INNER JOIN (SELECT a.user_guid, b.* FROM set_supplier_user_relationship AS a INNER JOIN set_supplier AS b ON a.`supplier_guid` = b.`supplier_guid` WHERE customer_guid = '$current_customer_guid' AND b.isactive = '1' AND b.supplier_guid IS NOT NULL) b ON a.user_guid = b.user_guid GROUP BY supplier_guid ORDER BY b.supplier_name, a.user_id) a INNER JOIN acc AS b ON a.acc_guid = b.`acc_guid` ORDER BY supplier_name ASC ) a LEFT JOIN ( SELECT acc_name AS to_acc_name, a.supplier_guid AS to_supplier_guid FROM (SELECT b.supplier_name, b.supplier_guid, a.* FROM (SELECT acc_guid, branch_guid, module_group_guid, user_group_guid, user_guid, isactive, user_id, user_name FROM set_user WHERE acc_guid = '$to_customer_guid' GROUP BY user_guid) a INNER JOIN (SELECT a.user_guid, b.* FROM set_supplier_user_relationship AS a INNER JOIN set_supplier AS b ON a.`supplier_guid` = b.`supplier_guid` WHERE customer_guid = '$to_customer_guid' AND b.isactive = '1' AND b.supplier_guid IS NOT NULL) b ON a.user_guid = b.user_guid GROUP BY supplier_guid ORDER BY b.supplier_name, a.user_id) a INNER JOIN acc AS b ON a.acc_guid = b.`acc_guid` ORDER BY supplier_name ASC ) b ON a.supplier_guid = b.to_supplier_guid where to_supplier_guid is null ORDER BY acc_name, supplier_name ASC");*/
              $to_customer_supplier = $this->db->query("SELECT seta.*, '$to_customer_guid' as to_supplier_guid FROM ( SELECT a.supplier_guid, supplier_name, a.isactive FROM (SELECT * FROM set_supplier WHERE isactive = '1' ) a INNER JOIN (SELECT * FROM set_user WHERE acc_guid = '$current_customer_guid' GROUP BY user_guid) c ON a.supplier_guid = c.supplier_guid WHERE c.acc_guid = '$current_customer_guid' GROUP BY c.supplier_guid ) seta LEFT JOIN ( SELECT a.supplier_guid, supplier_name, a.isactive FROM ( SELECT * FROM set_supplier WHERE isactive = '1' ) a INNER JOIN ( SELECT * FROM set_user WHERE acc_guid = '$to_customer_guid' GROUP BY user_guid ) c ON a.supplier_guid = c.supplier_guid WHERE c.acc_guid = '$to_customer_guid' GROUP BY a.supplier_guid ) setb ON seta.supplier_guid = setb.supplier_guid WHERE setb.supplier_guid IS NULL");


            }
            $data = array (
                'from_customer_supplier' => $from_customer_supplier,
                'from_customer' => $from_customer,
                'to_customer' => $to_customer,
                'to_customer_supplier' => $to_customer_supplier, 
                //'result' => $this->db->query("SELECT * from set_supplier limit 10"),
            ); 

            $this->load->view('header');
            $this->load->view('module_setup_duplicate_user', $data);
          //  $this->load->view('module_duplicate_user_modal', $data);
            $this->load->view('footer');

        }
        else
        {
            redirect('#');
        }
    }

    public function duplicate_by_user()
    {   
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $current_customer_guid = $_REQUEST['from'];
            $to_customer_guid = $_REQUEST['to'];
  

            $from_customer =  $this->db->query("SELECT * from acc ORDER BY acc_name ASC");

            $to_customer = $this->db->query("SELECT * from acc ORDER BY acc_name ASC" );


            $data = array (
                // 'from_customer_supplier' => $from_customer_supplier,
                'from_customer' => $from_customer,
                'to_customer' => $to_customer,
                // 'to_customer_supplier' => $to_customer_supplier, 
                //'result' => $this->db->query("SELECT * from set_supplier limit 10"),
            ); 

            $this->load->view('header');
            $this->load->view('module_setup_duplicate_by_user', $data);
          //  $this->load->view('module_duplicate_user_modal', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }

       
    }  
    
    public function transfer_supplier()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
           $supplier_guid = $this->input->post('guid');
           $to_acc_guid = $this->input->post('to_acc_guid');
           $from_acc_guid = $this->input->post('from_acc_guid');

           $get_all_userguid = $this->db->query("SELECT * FROM set_user WHERE user_guid  IN ( SELECT user_guid FROM  `check_user_supplier_customer_relationship`  WHERE supplier_guid = '$supplier_guid' ) AND acc_guid = '$from_acc_guid' GROUP BY user_guid "); 

           $branch_guid = $this->db->query("SELECT * FROM acc_branch AS a INNER JOIN acc_concept AS b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc AS c ON b.acc_guid = c.acc_guid  WHERE c.acc_guid = '$to_acc_guid' and a.isactive = '1' ORDER BY branch_code ASC LIMIT 1")->row('branch_guid');

           if($branch_guid == '')
           {
                $this->session->set_flashdata('danger', '<div class="alert alert-success text-center" style="font-size: 18px">Error : Missing Branch ID <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('module_setup/duplicate_user?from='.$from_acc_guid.'&to='.$to_acc_guid.'&filter=true');
           };

           //$branch_guid = $get_all_userguid->row('branch_guid');
            // insert user_guid

           //echo $branch_guid;die;
           foreach($get_all_userguid->result() as $row)
           {
                $check_exist = $this->db->query("SELECT * from set_user where user_guid = '".$row->user_guid."' and acc_guid = '".$to_acc_guid."' group by user_guid");

                if($to_acc_guid =='')
                {
                    $this->session->set_flashdata('danger', '<div class="alert alert-success text-center" style="font-size: 18px">Error : To_Acc_Guid<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    redirect('module_setup/duplicate_user?from='.$from_acc_guid.'&to='.$to_acc_guid.'&filter=true');
                }

                if($check_exist->num_rows() < 1)
                {
                    $data = array(
                    'acc_guid' => $to_acc_guid,
                    'branch_guid' => $branch_guid,
                    'module_group_guid' => $row->module_group_guid,
                    'user_group_guid' => $row->user_group_guid,
                    'user_guid' => $row->user_guid,
                    'supplier_guid' => $supplier_guid,
                    'isactive' => $row->isactive,
                    'user_id' => $row->user_id,
                    'user_password' => $row->user_password,
                    'user_name' => $row->user_name,
                    'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                    'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                    'created_by' => $_SESSION['userid'],
                    'updated_by' => $_SESSION['userid'],
                    ) ; 
                    $this->db->insert('set_user', $data);
                }; 
            }

           redirect('module_setup/duplicate_user?from='.$from_acc_guid.'&to='.$to_acc_guid.'&filter=true');

        }
        else
        {
            redirect('#');

        }
    }

    public function view_via_user()
    {
         if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
             $supplier_guid = $_REQUEST['supplier_guid'];
             $from_acc_guid = $_REQUEST['from_acc_guid'];
             $to_acc_guid = $_REQUEST['to_acc_guid'];

            $get_from_acc_name = $this->db->query("SELECT acc_name from acc where acc_guid = '$from_acc_guid' limit 1")->row('acc_name');
            $get_to_acc_name = $this->db->query("SELECT acc_name from acc where acc_guid = '$to_acc_guid' limit 1")->row('acc_name');

            $get_from_supplier_user_list = $this->db->query(" 
 SELECT user_id, user_guid, supplier_guid
 , IF(from_user_id IS NULL, '', 'Checked') AS from_user_id
 ,  IF(to_user_id IS NULL, '', 'Checked') AS to_user_id 
 , from_acc_guid
 , to_acc_guid
 , IF(to_acc_guid IS NULL,  IF(d_acc_guid IS NULL, '', 'Need Mapping'), '') AS remark
 FROM 
 (
 SELECT user_id, user_guid, acc_guid, acc_name, supplier_guid FROM check_user_supplier_customer_relationship 
 WHERE supplier_guid = '$supplier_guid'
 AND acc_guid IN ('$from_acc_guid','$to_acc_guid')
 ORDER BY user_id ASC
 ) a
 LEFT JOIN 
 /*get from*/
 (
 SELECT user_id AS from_user_id
 , user_guid AS from_user_guid
 , acc_guid AS from_acc_guid
 , acc_name AS from_acc_name
 , supplier_guid  AS from_supplier_guid
 FROM check_user_supplier_customer_relationship 
 WHERE supplier_guid = '$supplier_guid'
 AND acc_guid IN ('$from_acc_guid')
 ) b
 ON a.user_guid = b.from_user_guid
  /*get to*/
  LEFT JOIN
   (
 SELECT user_id AS to_user_id
 , user_guid AS to_user_guid
 , acc_guid AS to_acc_guid
 , acc_name AS to_acc_name
 , supplier_guid  AS to_supplier_guid
 FROM check_user_supplier_customer_relationship 
 WHERE supplier_guid = '$supplier_guid'
 AND acc_guid IN ('$to_acc_guid')
 ) c
 ON a.user_guid = c.to_user_guid
  /* need mapping alert */
 LEFT JOIN
 (
  SELECT a.user_id AS d_user_id
 , a.user_guid AS d_user_guid
 , a.acc_guid AS d_acc_guid
 , a.acc_name AS d_acc_name
 , a.supplier_guid  AS d_supplier_guid
 FROM
 (
 SELECT * FROM set_user 
 WHERE supplier_guid = '$supplier_guid'
 AND acc_guid = '$to_acc_guid'
 GROUP BY user_guid
 ) zz 
 LEFT JOIN check_user_supplier_customer_relationship AS a
 ON zz.user_guid =  a.`user_guid`
 GROUP BY a.user_guid
  ) d
  ON b.from_user_guid = d.d_user_guid
  GROUP BY user_guid
 ORDER BY user_id ASC
 ");
            //echo $this->db->last_query();die;
           
            $data = array ( 
                'get_from_acc_name' => $get_from_acc_name,
                'get_to_acc_name' => $get_to_acc_name,
                'result' => $get_from_supplier_user_list,
                'supplier_guid' =>$supplier_guid,
                'from_acc_guid' =>$from_acc_guid,
                'to_acc_guid' =>$to_acc_guid,
            );    
            $this->load->view('module_duplicate_user_modal',$data);

         }
        else
        {
            redirect('#');
        } 
    }

    public function add_via_user()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
        $supplier_guid = $_REQUEST['supplier_guid'];
        $user_guid = $_REQUEST['user_guid'];
        $from_acc_guid = $_REQUEST['from_acc_guid'];
        $to_acc_guid = $_REQUEST['to_acc_guid'];

         $branch_guid = $this->db->query("SELECT * FROM acc_branch AS a INNER JOIN acc_concept AS b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc AS c ON b.acc_guid = c.acc_guid  WHERE c.acc_guid = '$to_acc_guid' and a.isactive = '1' ORDER BY branch_code ASC LIMIT 1")->row('branch_guid');

           if($branch_guid == '')
           {
                $this->session->set_flashdata('danger', '<div class="alert alert-success text-center" style="font-size: 18px">Error : Missing Branch ID <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('module_setup/duplicate_user?from='.$from_acc_guid.'&to='.$to_acc_guid.'&filter=false');
           };

           $get_from_data = $this->db->query("SELECT * FROM set_user where user_guid = '$user_guid' and acc_guid = '$from_acc_guid' group by user_guid ");

           
           $data = array(
                    'acc_guid' => $to_acc_guid,
                    'branch_guid' => $branch_guid,
                    'module_group_guid' => $get_from_data->row('module_group_guid'),
                    'user_group_guid' => $get_from_data->row('user_group_guid'),
                    'user_guid' => $user_guid,
                    'supplier_guid' => $supplier_guid,
                    'isactive' => $get_from_data->row('isactive'),
                    'user_id' => $get_from_data->row('user_id'),
                    'user_password' => $get_from_data->row('user_password'),
                    'user_name' => $get_from_data->row('user_name'),
                    'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                    'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                    'created_by' => $_SESSION['userid'],
                    'updated_by' => $_SESSION['userid'],
                    ) ; 
            $this->db->insert('set_user', $data);

           // echo $this->db->last_query();die;
        redirect('module_setup/duplicate_user?from='.$from_acc_guid.'&to='.$to_acc_guid.'&filter=false');
        }
        else
        {
            redirect('#');
        }

    }

    public function viewbranch()
    {   
        // $branch_guid = $this->input->post('branch_guid');
        $user_guid = $this->input->post('user_guid');
        $module_group_guid = $_SESSION['module_group_guid'];
        $acc_guid = $this->session->userdata('customer_guid');

        $result = $this->db->query("SELECT a.*, b.`branch_guid`,b.`branch_name`, b.branch_code, c.`user_group_name` FROM set_user a INNER JOIN set_user_branch d ON a.user_guid = d.user_guid INNER JOIN acc_branch b ON d.`branch_guid` = b.`branch_guid`
            INNER JOIN set_user_group c ON a.`user_group_guid` = c.`user_group_guid` WHERE a.`user_guid` = '$user_guid' AND a.module_group_guid = '$module_group_guid' AND a.acc_guid = '$acc_guid' AND d.acc_guid = '$acc_guid'");
        // echo $this->db->last_query();die;
        // echo var_dump($result->result());die;

        $total = $this->db->query("SELECT a.* FROM acc a INNER JOIN acc_concept b ON a.acc_guid = b.acc_guid INNER JOIN acc_branch c ON b.concept_guid = c.concept_guid WHERE a.acc_guid = '$acc_guid' ");

        $column = '';
        $column = '<div class="table-responsive" style="height:75%;overflow:auto;"><table id="example10" class="table table-bordered table-hover"><thead><tr><th>UserID</th><th>User Group</th><th>Branch Code</th><th>Branch </th></tr></thead><tbody>';
        
        foreach($result->result() as $row)
        {
            $column .= '<tr>';
            $column .= '<td>'.$row->user_id.'</td>';
            $column .= '<td>'.$row->user_group_name.'</td>';
            $column .= '<td>'.$row->branch_code.'</td>';
            $column .= '<td>'.$row->branch_name;
            $column .= '<button id="xdelete_branch" style="float: right" title="Delete" onclick="" type="button" class="btn btn-xs btn-danger" data-toggle="modal" branch_guid="'.$row->branch_guid.'" user_guid="'.$row->user_guid.'" acc_guid="'.$row->acc_guid.'" data-target="#mdelete_branch" data-name="'.$row->branch_name.'" ><i class="glyphicon glyphicon-trash"></i></button></td>';
            $column .= '</tr>';
        }
        $column .= '</tbody></table></div>';

        $data = array(
            'table' => $column,
            'map_total' => $result->num_rows(),
            'total' => $total->num_rows()
        );

        echo json_encode($data);
    }   

    public function delete_branch()
    {
        $branch_guid = $this->input->post('branch_guid');
        $user_guid = $this->input->post('user_guid');
        $acc_guid = $this->input->post('acc_guid');
        // $result = $this->db->query("SELECT * FROM set_user_branch WHERE acc_guid = '$acc_guid' AND user_guid = '$user_guid' AND branch_guid = '$branch_guid'");
        $result = $this->db->query("DELETE FROM set_user_branch WHERE acc_guid = '$acc_guid' AND user_guid = '$user_guid' AND branch_guid = '$branch_guid'");

        $afrows = $this->db->affected_rows();

        if($afrows > 0)
        {
            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Delete Successfully<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
            redirect('module_setup');
        }
        else
        {
            $this->session->set_flashdata('message', '<div class="alert alert-danger text-center" style="font-size: 18px">Delete Unsuccessful<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
            redirect('module_setup');
        }
        print_r($result->result());

    } 




    public function cus_table()
    {

        $from_acc_guid = $this->input->post('from_acc_guid');

        $to_acc_guid = $this->input->post('to_acc_guid');

        if($to_acc_guid == '' || $to_acc_guid == null)
        {
            $xto_acc_guid = $from_acc_guid;
            $second_acc = '';
        }
        else
        {
            $xto_acc_guid = $to_acc_guid;
            $second_acc = $to_acc_guid;
        }

        $set_user = $this->db->query("SELECT a.*, IF ( b.user_guid IS NULL, 0, 1 ) AS exist_status FROM set_user a LEFT JOIN ( SELECT * FROM set_user WHERE acc_guid = '$xto_acc_guid' ) b ON a.user_guid = b.user_guid WHERE a.acc_guid = '$from_acc_guid' ");
        
        
        if($second_acc == '')
        {
            $set_user_2 = array();
        }
        else
        {
            $set_user_2 = $this->db->query("SELECT * FROM set_user WHERE acc_guid = '$to_acc_guid' ")->result();
        }


        $data = array(
            'from_cus' => $set_user->result(),
            'to_cus' => $set_user_2
        );

        echo json_encode($data);

    }//close from cus



    public function duplicate_supplier()
    {
        $details = $this->input->post('details');

        $details = json_encode($details);
        $details = json_decode($details);

        $from_acc_guid = $this->input->post('from_acc_guid');
        $to_acc_guid = $this->input->post('to_acc_guid');

        foreach($details as $row){

            $module_group_guid = $row->module_group_guid;
            $user_group_guid = $row->user_group_guid;
            $user_guid = $row->user_guid;
            $supplier_guid = $row->supplier_guid;
            $isactive = $row->isactive;
            $user_id = $row->user_id;
            $user_password = $row->user_password;
            $user_name = $row->user_name;
            $limited_location = $row->limited_location;


            $data = array(
                'acc_guid' => $to_acc_guid,
                // 'branch_guid' => $branch_guid,
                'module_group_guid' => $module_group_guid,
                'user_group_guid' => $user_group_guid,
                'user_guid' => $user_guid,
                'supplier_guid' => $supplier_guid,
                'isactive' => $isactive,
                'user_id' => $user_id,
                'user_password' => $user_password,
                'user_name' => $user_name,
                'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                'created_by' => $this->session->userdata('userid'),
                'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                'updated_by' => $this->session->userdata('userid'),
                'limited_location' => $limited_location
            );

            $this->db->insert("set_user",$data);

            $error = $this->db->affected_rows();

            if($error <= 0)
            {
                $error_message = 'Failed to duplicate';
                $xerror = $this->db->error();
                $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
                $this->message->error_message($xerror['message'], '1');
                exit();
            }//close if

        }//close foreach

        if($error > 0){
            $success_msg = 'Duplicate successfully';
            $button = '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            $this->message->success_message($button , $success_msg, '');
        }
        else
        {   
            $error_message = 'Failed to duplicate';
            $xerror = $this->db->error();
            $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
            $this->message->error_message($xerror['message'], '1');
        }//close else  



    }//close duplicate_supplier




}