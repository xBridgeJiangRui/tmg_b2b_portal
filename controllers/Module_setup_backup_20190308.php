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
                $call_user_module = $this->db->query("SELECT a.*,b.module_code,b.`module_name`,c.`module_group_name`,d.`user_group_name` FROM set_user_module a INNER JOIN set_module b ON a.`module_guid` = b.`module_guid`INNER JOIN set_module_group c ON a.`module_group_guid` = c.`module_group_guid`INNER JOIN set_user_group d ON d.`user_group_guid` = a.`user_group_guid` WHERE c.`module_group_guid` = '".$_SESSION['module_group_guid']."' AND d.`user_group_guid` = '".$_SESSION['user_group_guid']."'");
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

                'acc_concept' => $this->db->query("SELECT a.*,b.`acc_name` FROM acc_concept a INNER JOIN acc b ON a.`acc_guid` = b.`acc_guid` INNER JOIN acc_branch c ON c.`concept_guid` = a.`concept_guid` WHERE a.isactive = 1 GROUP BY a.`concept_guid` ORDER BY a.updated_at DESC;"),

                'branch_group' => $this->db->query("SELECT a.*,b.`concept_name` FROM acc_branch_group a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc_branch c ON c.`branch_group_guid` = a.`branch_group_guid` WHERE a.isactive = 1 GROUP BY a.`branch_group_guid` ORDER BY a.updated_at DESC"),

                'branch' => $this->db->query("SELECT b.concept_name, a.*, c.`group_name` FROM acc_branch a INNER JOIN acc_concept b ON a.`concept_guid` = b.`concept_guid` INNER JOIN acc_branch_group c ON c.`branch_group_guid` = a.`branch_group_guid` WHERE a.isactive = 1 and b.acc_guid = '".$_SESSION['customer_guid']."'  GROUP BY a.`branch_guid` ORDER BY a.updated_at DESC"),

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
                    'acc_guid' => $_SESSION['customer_guid'],
                    // 'isactive' => $this->input->post('active'),
                    'module_group_guid' => $this->input->post('module_group_guid'),
                    'user_group_guid' => $this->input->post('user_group'),
                    'user_id' => $this->input->post('userid'),
                    'user_name' =>$this->input->post('name'),
                    'user_password' => $password,
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $_SESSION['userid'],
                    );
                $this->Module_setup_model->update_data($table,$col_guid, $guid, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');

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

    public function branch_form()
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
        $this->db->query("DELETE FROM lite_b2b.`set_user` WHERE branch_guid = '".$_REQUEST['branch_guid']."' AND user_guid = '".$_REQUEST['user_guid']."' ");

        $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Deleted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect('module_setup?module_group_guid='.$_SESSION['module_group_guid']);

    }

    public function UpdateAllOutlet()
    {
        
        /*get module and which customer 1st
        then we query out all assigned or non assigned
        we make sure at least got 1 record then we find NULL 
        we fill those null outlet*/

        $module_group_guid = $_REQUEST['module_group_guid'];
        $customer_guid = $_REQUEST['customer_guid'];

        $check_unique_user_for_module = $this->db->query("SELECT user_guid from lite_b2b.set_user where acc_guid ='$customer_guid' and module_group_guid = '$module_group_guid' group by user_guid ");

        foreach($check_unique_user_for_module->result() as $row)
        {
            $check_all_data = $this->db->query("SELECT *
 FROM (
SELECT a.acc_guid, acc_name , branch_guid, branch_code, branch_name
FROM acc  AS a
INNER JOIN acc_concept AS b
ON a.acc_guid = b.acc_guid
INNER JOIN acc_branch AS c
ON b.concept_guid = c.concept_guid
WHERE a.acc_guid = '$customer_guid'
) aa
LEFT JOIN 
(
SELECT  
`acc_guid`  AS  `second_acc_guid` ,
`branch_guid`  AS  `second_branch_guid` ,
`module_group_guid`  AS  `second_module_group_guid` ,
`user_group_guid`  AS  `second_user_group_guid` ,
`user_guid`  AS  `second_user_guid` ,
`supplier_guid`  AS  `second_supplier_guid` ,
`isactive`  AS  `second_isactive` ,
`user_id`  AS  `second_user_id` ,
`user_password`  AS  `second_user_password` ,
`user_name`  AS  `second_user_name` ,
`created_at`  AS  `second_created_at` ,
`created_by`  AS  `second_created_by` ,
`updated_at`  AS  `second_updated_at` ,
`updated_by` AS  `second_updated_by`  
FROM set_user  
WHERE module_group_guid = '$module_group_guid' 
AND acc_guid  = '$customer_guid'
AND user_guid = '".$row->user_guid."'
) bb
ON aa.acc_guid = bb.second_acc_guid
AND aa.branch_guid = bb.second_branch_guid
WHERE bb.second_acc_guid IS NOT NULL limit 1");

            $check_all_pending_to_insert = $this->db->query("SELECT `second_acc_guid` ,
`second_branch_guid` ,
`second_module_group_guid` ,
`second_user_group_guid` ,
`second_user_guid` ,
`second_supplier_guid` ,
`second_isactive` ,
`second_user_id` ,
`second_user_password` ,
`second_user_name` ,
`second_created_at` ,
`second_created_by` ,
`second_updated_at` ,
`second_updated_by`  
 FROM (
SELECT a.acc_guid, acc_name , branch_guid, branch_code, branch_name
FROM acc  AS a
INNER JOIN acc_concept AS b
ON a.acc_guid = b.acc_guid
INNER JOIN acc_branch AS c
ON b.concept_guid = c.concept_guid
WHERE a.acc_guid = '$customer_guid'
) aa
LEFT JOIN 
(
SELECT  
`acc_guid`  AS  `second_acc_guid` ,
`branch_guid`  AS  `second_branch_guid` ,
`module_group_guid`  AS  `second_module_group_guid` ,
`user_group_guid`  AS  `second_user_group_guid` ,
`user_guid`  AS  `second_user_guid` ,
`supplier_guid`  AS  `second_supplier_guid` ,
`isactive`  AS  `second_isactive` ,
`user_id`  AS  `second_user_id` ,
`user_password`  AS  `second_user_password` ,
`user_name`  AS  `second_user_name` ,
`created_at`  AS  `second_created_at` ,
`created_by`  AS  `second_created_by` ,
`updated_at`  AS  `second_updated_at` ,
`updated_by` AS  `second_updated_by`  
FROM set_user  
WHERE module_group_guid = '$module_group_guid' 
AND acc_guid  = '$customer_guid'
AND user_guid = '".$row->user_guid."'
) bb
ON aa.acc_guid = bb.second_acc_guid
AND aa.branch_guid = bb.second_branch_guid
WHERE bb.second_acc_guid IS NULL ");

            if($check_all_data->num_rows() > 0)
            {
                $check_missing_branch_guid = $this->db->query("SELECT branch_guid
 FROM (
SELECT a.acc_guid, acc_name , branch_guid, branch_code, branch_name
FROM acc  AS a
INNER JOIN acc_concept AS b
ON a.acc_guid = b.acc_guid
INNER JOIN acc_branch AS c
ON b.concept_guid = c.concept_guid
WHERE a.acc_guid = '$customer_guid'
) aa
LEFT JOIN 
(
SELECT  
`acc_guid`  AS  `second_acc_guid` ,
`branch_guid`  AS  `second_branch_guid` ,
`module_group_guid`  AS  `second_module_group_guid` ,
`user_group_guid`  AS  `second_user_group_guid` ,
`user_guid`  AS  `second_user_guid` ,
`supplier_guid`  AS  `second_supplier_guid` ,
`isactive`  AS  `second_isactive` ,
`user_id`  AS  `second_user_id` ,
`user_password`  AS  `second_user_password` ,
`user_name`  AS  `second_user_name` ,
`created_at`  AS  `second_created_at` ,
`created_by`  AS  `second_created_by` ,
`updated_at`  AS  `second_updated_at` ,
`updated_by` AS  `second_updated_by`  
FROM set_user  
WHERE module_group_guid = '$module_group_guid' 
AND acc_guid  = '$customer_guid'
AND user_guid = '".$row->user_guid."'
) bb
ON aa.acc_guid = bb.second_acc_guid
AND aa.branch_guid = bb.second_branch_guid
WHERE bb.second_acc_guid IS NULL ");
 
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



}