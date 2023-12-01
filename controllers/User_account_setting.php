<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_account_setting extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->library('session');
        $this->load->model('Datatable_model');
        $this->load->model('Send_email_model');
    }

    public function index()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login() && in_array('VUAMT', $_SESSION['module_code'])) 
        {
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $_SESSION['user_id'];

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' AND a.acc_guid = '$customer_guid' ORDER BY a.acc_name ASC");

            if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
            {
                $get_supplier = $this->db->query("SELECT d.supplier_guid,d.supplier_name FROM 
                lite_b2b.set_user a
                INNER JOIN lite_b2b.set_supplier_user_relationship b
                ON a.user_guid = b.user_guid
                AND a.acc_guid = b.customer_guid
                INNER JOIN lite_b2b.set_supplier_group c
                ON b.supplier_guid = c.supplier_guid
                AND a.acc_guid = c.customer_guid
                INNER JOIN lite_b2b.set_supplier d
                ON c.supplier_guid = d.supplier_guid
                WHERE a.acc_guid = '$customer_guid'
                GROUP BY d.supplier_guid
                ORDER BY d.supplier_name ASC
                ");
            }
            else
            {
                $get_supplier = $this->db->query("SELECT d.supplier_guid,d.supplier_name FROM 
                lite_b2b.set_user a
                INNER JOIN lite_b2b.set_supplier_user_relationship b
                ON a.user_guid = b.user_guid
                AND a.acc_guid = b.customer_guid
                INNER JOIN lite_b2b.set_supplier_group c
                ON b.supplier_guid = c.supplier_guid
                AND a.acc_guid = c.customer_guid
                INNER JOIN lite_b2b.set_supplier d
                ON c.supplier_guid = d.supplier_guid
                WHERE a.user_guid = '$user_guid' 
                AND a.acc_guid = '$customer_guid'
                GROUP BY d.supplier_guid
                ORDER BY d.supplier_name ASC");
            }

            $get_user = $this->db->query("SELECT a.user_guid,a.user_id,a.user_name FROM lite_b2b.set_user a WHERE a.user_guid = '$user_guid' LIMIT 1");

            $get_pending_creation = $this->db->query("SELECT a.*,b.user_id,b.user_name FROM lite_b2b.set_user_process_list a LEFT JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.status = '0' AND a.action_status IN ('PROCESS','DUPLICATE') AND a.created_by_guid = '$user_guid' AND a.customer_guid = '$customer_guid' GROUP BY a.guid")->result_array();
            
            $data = array(
                'get_supplier' => $get_supplier->result(),
                'acc_name' => $get_acc->row('acc_name'),
                'user_id' => $get_user->row('user_id'),
                'user_name' => $get_user->row('user_name'),
                'get_pending_creation' => count($get_pending_creation),
            );

            $this->load->view('header');
            $this->load->view('user_account/user_info', $data);
            $this->load->view('footer');

        } else {
            $this->session->set_flashdata('message', 'You have not rights to access.');
            redirect('dashboard');
        }
    }

    public function user_info_tb()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) 
        {
            $doc = 'user_table';
            $user_guid = $_SESSION['user_guid'];
            $customer_guid = $_SESSION['customer_guid'];
            $supplier_guid = $this->input->post("supplier_guid");
            $selected_customer_guid = $this->input->post("selected_customer_guid");
            $admin_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status >= '1' AND isactive = '1' AND admin_active = '2'")->result_array();
            $valid_admin_guid = implode("','",array_filter(array_column($admin_guid,'user_group_guid')));

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $check_user_group = $this->db->query("SELECT b.user_group_name,a.user_group_guid,a.user_guid,a.acc_guid 
            FROM lite_b2b.set_user a
            INNER JOIN lite_b2b.set_user_group b
            ON a.user_group_guid = b.user_group_guid
            INNER JOIN lite_b2b.set_supplier_user_relationship c
            ON a.user_guid = c.user_guid
            AND a.acc_guid = c.customer_guid
            WHERE a.user_guid = '$user_guid' 
            AND a.user_group_guid IN ('$valid_admin_guid')
            GROUP BY a.user_group_guid,a.acc_guid")->result_array();

            if(count($check_user_group) > 0 )
            {
                $valid_customer_guid = implode("','",array_filter(array_column($check_user_group,'acc_guid')));
            }
            else
            {
                $valid_customer_guid = '';
            }

            if($selected_customer_guid != '' && $selected_customer_guid != 'null' && $selected_customer_guid != null)
            {
                $valid_customer_guid = $selected_customer_guid;
            }

            if(in_array('IAVAa', $_SESSION['module_code']))
            {
                $condition_hide_admin = "";
            }
            else
            {
                $condition_hide_admin = "AND a.hide_admin = '0'";
            }

            $query_data = "SELECT aa.* FROM (SELECT
            e.acc_name,
            d.supplier_name,
            f.admin_active,
            f.user_group_name,
            b.supplier_guid AS relation_supplier_guid,
            IF(a.isactive = '1', 'Active', IF(a.isactive = '0', 'Deactive', IF(a.isactive = '9', 'Incomplete','') ) ) AS status_naming,
            a.*
            FROM lite_b2b.set_user a
            INNER JOIN lite_b2b.set_supplier_user_relationship b
            ON a.user_guid = b.user_guid
            INNER JOIN lite_b2b.set_supplier_group c
            ON b.supplier_guid = c.supplier_guid
            AND a.acc_guid = c.customer_guid
            INNER JOIN lite_b2b.set_supplier d
            ON c.supplier_guid = d.supplier_guid
            INNER JOIN lite_b2b.acc e
            ON a.acc_guid = e.acc_guid
            INNER JOIN lite_b2b.set_user_group f
            ON a.user_group_guid = f.user_group_guid
            WHERE c.supplier_guid = '$supplier_guid'
            AND e.acc_guid IN ('$customer_guid')
            $condition_hide_admin
            GROUP BY e.acc_guid,d.supplier_guid,a.user_guid) aa 
            ORDER BY 
            CASE WHEN aa.user_group_guid IN ('$valid_admin_guid') THEN 0 ELSE 1 END,    -- Sort by user_group_name: 'SUPER_ADMIN' first
            CASE WHEN aa.status_naming = 'Incomplete' THEN 0 ELSE 
                CASE WHEN aa.status_naming = 'Active' THEN 1 ELSE 2 END END,  -- Sort by status_naming: 'Active' first, 'Incomplete' second, and others last
            aa.status_naming";

            // print_r($query_data); die;
            $sql = "SELECT * FROM (
                $query_data
            ) zzz ";
            // echo $sql; die;
            // echo $this->db->last_query();die;
            
            $query = $this->Datatable_model->datatable_main($sql,'',$doc);
            // echo $this->db->last_query(); die;
            $fetch_data = $query->result();
            $data = array();
            if (count($fetch_data) > 0) {
                foreach ($fetch_data as $row) {
                    $tab = array();

                    $tab['acc_name'] = $row->acc_name;
                    $tab['supplier_name'] = $row->supplier_name;
                    $tab['acc_guid'] = $row->acc_guid;
                    $tab['relation_supplier_guid'] = $row->relation_supplier_guid;
                    $tab['branch_guid'] = $row->branch_guid;
                    $tab['module_group_guid'] = $row->module_group_guid;
                    $tab['user_group_guid'] = $row->user_group_guid;
                    $tab['user_guid'] = $row->user_guid;
                    $tab['isactive'] = $row->isactive;
                    $tab['user_id'] = $row->user_id;
                    // $tab['user_password'] = $row->user_password;
                    $tab['user_name'] = $row->user_name;
                    $tab['created_at'] = $row->created_at;
                    $tab['created_by'] = $row->created_by;
                    $tab['updated_at'] = $row->updated_at;
                    $tab['updated_by'] = $row->updated_by;
                    $tab['limited_location'] = $row->limited_location;
                    $tab['ismobile'] = $row->ismobile;
                    $tab['user_group_name'] = $row->user_group_name;
                    // $tab['sorting_user'] = $row->sorting_user;
                    // $tab['sorting_retailer'] = $row->sorting_retailer;
                    $tab['status_naming'] = $row->status_naming;
                    $tab['admin_active'] = $row->admin_active;
                    

                    $data[] = $tab;
                }
            } else {
                $data = '';
            }

            $output = array(
                "draw"                =>     intval($_POST["draw"]),
                "recordsTotal"        =>     intval($this->Datatable_model->general_get_all_data($sql, $doc)),
                "recordsFiltered"     =>     intval($this->Datatable_model->general_get_filtered_data($sql, $doc)),
                "data"                =>     $data
            );

            echo json_encode($output);
        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function user_registered_tb()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) 
        {
            // $user_guid = $this->input->post('user_guid');
            $user_guid = $_SESSION['user_guid'];
            $customer_guid = $_SESSION['customer_guid'];
            $supplier_guid = $this->input->post("supplier_guid");
            $admin_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status >= '1' AND isactive = '1' AND admin_active = '2'")->result_array();
            $valid_admin_guid = implode("','",array_filter(array_column($admin_guid,'user_group_guid')));
                        
            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $check_user_group = $this->db->query("SELECT b.user_group_name,a.user_group_guid,a.user_guid,a.acc_guid 
            FROM lite_b2b.set_user a
            INNER JOIN lite_b2b.set_user_group b
            ON a.user_group_guid = b.user_group_guid
            INNER JOIN lite_b2b.set_supplier_user_relationship c
            ON a.user_guid = c.user_guid
            AND a.acc_guid = c.customer_guid
            WHERE a.user_guid = '$user_guid' 
            AND a.user_group_guid IN ('$valid_admin_guid')
            GROUP BY a.user_group_guid,a.acc_guid")->result_array();

            if(count($check_user_group) > 0 )
            {
                $valid_customer_guid = implode("','",array_filter(array_column($check_user_group,'acc_guid')));
            }
            else
            {
                $valid_customer_guid = '';
            }

            $query_data = $this->db->query("SELECT aa.acc_guid,aa.acc_name,COUNT(aa.user_name) AS count_data,
            SUM(CASE WHEN aa.isactive = '9' THEN 1 ELSE 0 END) AS incomplete_user,
            SUM(CASE WHEN aa.isactive = '1' THEN 1 ELSE 0 END) AS active_user,
            SUM(CASE WHEN aa.isactive = '0' THEN 1 ELSE 0 END) AS deactive_user
            FROM
            ( SELECT e.acc_guid,e.acc_name,d.supplier_guid,d.supplier_name,a.user_name,a.isactive
            FROM 
            lite_b2b.set_user a
            INNER JOIN lite_b2b.set_supplier_user_relationship b
            ON a.user_guid = b.user_guid
            INNER JOIN lite_b2b.set_supplier_group c
            ON b.supplier_guid = c.supplier_guid
            AND b.customer_guid = c.customer_guid
            INNER JOIN lite_b2b.set_supplier d
            ON c.supplier_guid = d.supplier_guid
            INNER JOIN lite_b2b.acc e
            ON b.customer_guid = e.acc_guid
            WHERE a.acc_guid IN ('$customer_guid')
            AND d.supplier_guid = '$supplier_guid' 
            AND a.hide_admin = '0'
            GROUP BY a.user_id,a.acc_guid ) aa
            GROUP BY aa.acc_name");

            // echo $this->db->last_query();die;

            $data = array(  
                'data' => $query_data->result(), 
            );

            echo json_encode($data);
        }
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function view_vc_tb()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) 
        {
            $user_guid = $this->input->post('user_guid');
            $supplier_guid = $this->input->post('supplier_guid');
            $customer_guid = $this->input->post('customer_guid');

            // $query_data = $this->db->query("SELECT 
            // a.supplier_name,
            // b.supplier_group_name,
            // d.user_name,
            // e.name AS supcus_name
            // FROM lite_b2b.set_supplier a
            // INNER JOIN lite_b2b.set_supplier_group b
            // ON a.supplier_guid = b.supplier_guid
            // INNER JOIN lite_b2b.set_supplier_user_relationship c
            // ON b.supplier_guid = c.supplier_guid
            // AND b.customer_guid = c.customer_guid
            // INNER JOIN lite_b2b.set_user d
            // ON c.user_guid = d.user_guid
            // AND c.customer_guid = d.acc_guid
            // INNER JOIN b2b_summary.supcus e
            // ON b.supplier_group_name = e.code
            // AND c.customer_guid = e.customer_guid
            // WHERE a.supplier_guid = '$supplier_guid' 
            // AND c.user_guid = '$user_guid'
            // AND c.customer_guid = '$customer_guid'
            // GROUP BY a.supplier_guid,b.supplier_group_name,c.user_guid");

	        $query_data = $this->db->query("SELECT f.acc_guid,f.acc_name, 
            d.supplier_guid, d.supplier_name, c.supplier_group_guid, c.supplier_group_name, 
            a.user_guid, a.user_id, a.user_name, e.name AS supcus_name, 
            b.created_at, b.created_by 
            FROM lite_b2b.set_user a
            INNER JOIN lite_b2b.set_supplier_user_relationship b
            ON a.user_guid = b.user_guid 
            AND a.acc_guid = b.customer_guid
            INNER JOIN lite_b2b.set_supplier_group c
            ON b.supplier_group_guid = c.supplier_group_guid
            AND b.customer_guid = c.customer_guid
            INNER JOIN lite_b2b.set_supplier d
            ON c.supplier_guid = d.supplier_guid
            INNER JOIN b2b_summary.supcus e 
            ON c.supplier_group_name = e.code 
            AND c.customer_guid = e.customer_guid 
            INNER JOIN lite_b2b.acc f 
            ON b.customer_guid = f.acc_guid 
            WHERE b.supplier_guid = '$supplier_guid' 
            AND a.user_guid = '$user_guid'
            AND a.acc_guid = '$customer_guid'
            GROUP BY b.supplier_group_guid");

            //echo $this->db->last_query();die;

            $data = array(  
                'data' => $query_data->result(), 
            );

            echo json_encode($data);
        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function view_vo_tb()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $user_guid = $this->input->post('user_guid');
            $supplier_guid = $this->input->post('supplier_guid');
            $customer_guid = $this->input->post('customer_guid');

            $branch_total = count($this->db->query("SELECT c.branch_code, COUNT(c.branch_code) AS count_code FROM lite_b2b.acc a 
            INNER JOIN lite_b2b.acc_concept b 
            ON a.acc_guid = b.acc_guid 
            INNER JOIN lite_b2b.acc_branch c 
            ON b.concept_guid = c.concept_guid 
            AND c.`isactive` = '1' 
            WHERE a.acc_guid = '$customer_guid'
            GROUP BY c.branch_code")->result_array());

            // echo $this->db->last_query(); die;

            $query_data = $this->db->query("SELECT a.user_guid,
            a.user_id, 
            a.`user_name`,
            c.`branch_guid`,
            c.`branch_code`, 
            d.`user_group_name`,
            e.`branch_desc`,
            f.acc_guid,
            f.acc_name,
            b.created_at,
            b.created_by
            FROM lite_b2b.set_user a 
            INNER JOIN lite_b2b.set_user_branch b 
            ON a.user_guid = b.user_guid 
            AND b.acc_guid = '$customer_guid'
            INNER JOIN lite_b2b.acc_branch c 
            ON b.`branch_guid` = c.`branch_guid` 
            AND c.`isactive` = '1' 
            INNER JOIN lite_b2b.set_user_group d 
            ON a.`user_group_guid` = d.`user_group_guid`
            INNER JOIN b2b_summary.cp_set_branch e 
            ON c.branch_code = e.BRANCH_CODE
            AND e.customer_guid = '$customer_guid'
            INNER JOIN lite_b2b.acc f
            ON a.acc_guid = f.acc_guid
            WHERE a.`user_guid` = '$user_guid' 
            AND a.module_group_guid = '$module_group_guid'
            AND a.acc_guid = '$customer_guid' 
            GROUP BY c.branch_code            
            ");

            // $query_data_vc = $this->db->query("SELECT f.acc_guid,f.acc_name, 
            // d.supplier_guid, d.supplier_name, c.supplier_group_guid, c.supplier_group_name, 
            // a.user_guid, a.user_id, a.user_name, e.name AS supcus_name, 
            // b.created_at, b.created_by 
            // FROM lite_b2b.set_user a
            // INNER JOIN lite_b2b.set_supplier_user_relationship b
            // ON a.user_guid = b.user_guid 
            // AND a.acc_guid = b.customer_guid
            // INNER JOIN lite_b2b.set_supplier_group c
            // ON b.supplier_group_guid = c.supplier_group_guid
            // AND b.customer_guid = c.customer_guid
            // INNER JOIN lite_b2b.set_supplier d
            // ON c.supplier_guid = d.supplier_guid
            // INNER JOIN b2b_summary.supcus e 
            // ON c.supplier_group_name = e.code 
            // AND c.customer_guid = e.customer_guid 
            // INNER JOIN lite_b2b.acc f 
            // ON b.customer_guid = f.acc_guid 
            // WHERE b.supplier_guid = '$supplier_guid' 
            // AND a.user_guid = '$user_guid'
            // AND a.acc_guid = '$customer_guid'
            // GROUP BY b.supplier_group_guid");

            // echo $this->db->last_query();die;

            $data = array(  
                //'data_vc' => $query_data_vc->result(), 
                'data' => $query_data->result(), 
                'query_total' => $query_data->num_rows(),
                'branch_total' => $branch_total
            );

            echo json_encode($data);
        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function user_pending_creation()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $_SESSION['user_id'];

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $query_data = $this->db->query("SELECT a.*,b.user_id,b.user_name FROM lite_b2b.set_user_process_list a LEFT JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.status = '0' AND a.action_status IN ('PROCESS','DUPLICATE') AND a.created_by_guid = '$user_guid' AND a.customer_guid = '$customer_guid' GROUP BY a.guid");

            // echo $this->db->last_query(); die;

            $data = array(  
                'data' => $query_data->result(), 
            );

            echo json_encode($data);
        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function edit_process_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');

            $process_customer_guid = $this->input->post('process_customer_guid');
            $process_user_guid = $this->input->post('process_user_guid');
            $process_supplier_guid = $this->input->post('process_supplier_guid');
            $redirect_action = $this->input->post('redirect_action');
            $action_status = 'EDIT';

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $process_list = $this->db->query("SELECT a.guid FROM lite_b2b.set_user_process_list a WHERE a.user_guid = '$process_user_guid' AND a.customer_guid = '$process_customer_guid' AND a.supplier_guid = '$process_supplier_guid' AND a.action_status = '$action_status' AND a.created_by_guid = '$user_guid' GROUP BY a.user_guid, a.customer_guid LIMIT 1")->result_array();
    
            if(count($process_list) > 0)
            {
                $get_link = $process_list[0]['guid'];
            }
            else
            {
                $get_link = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');

                $data = array(
                    // 'module_group_guid' => $module_group_guid,
                    'guid' => $get_link,
                    'user_guid' => $process_user_guid,
                    'customer_guid' => $process_customer_guid,
                    'supplier_guid' => $process_supplier_guid,
                    'status' => '0',
                    'action_status' => $action_status,
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $user_id,
                    'created_by_guid' => $user_guid,
                );
                $this->db->insert('lite_b2b.set_user_process_list',$data);
            }

            if($get_link == '' || $get_link == 'null' || $get_link == null)
            {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Data Not Found. Please Try Again.',
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {               
                $data = array(
                    'para1' => 'true',
                    'get_link' => $get_link,
                );    
                echo json_encode($data);   
                exit();
            }
        } 
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function fetch_duplicate_data()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $duplicate_type = $this->input->post('duplicate_type');
            $duplicate_customer_guid = $this->input->post('duplicate_customer_guid');
            $duplicate_supplier_guid = $this->input->post('duplicate_supplier_guid');
            $admin_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status >= '1' AND isactive = '1' AND admin_active = '2'")->result_array();
            $valid_admin_guid = implode("','",array_filter(array_column($admin_guid,'user_group_guid')));

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            if($duplicate_type == 'retailer')
            {
                $query_data = $this->db->query("SELECT d.acc_guid AS value_guid ,d.acc_name AS value_name
                FROM lite_b2b.set_user a
                INNER JOIN lite_b2b.set_user_group b
                ON a.user_group_guid = b.user_group_guid
                INNER JOIN lite_b2b.set_supplier_user_relationship c
                ON a.user_guid = c.user_guid
                AND a.acc_guid = c.customer_guid
                INNER JOIN lite_b2b.acc d
                ON a.acc_guid = d.acc_guid
                WHERE a.user_guid = '$user_guid' 
                AND a.acc_guid != '$duplicate_customer_guid'
                AND a.user_group_guid IN ('$valid_admin_guid')

                GROUP BY a.user_group_guid,a.acc_guid");
            }
            else if($duplicate_type == 'supplier')
            {
                $query_data = $this->db->query("SELECT d.supplier_guid AS value_guid ,d.supplier_name AS value_name 
                FROM lite_b2b.set_user a
                INNER JOIN lite_b2b.set_supplier_user_relationship b
                ON a.user_guid = b.user_guid
                AND a.acc_guid = b.customer_guid
                INNER JOIN lite_b2b.set_supplier_group c
                ON b.supplier_guid = c.supplier_guid
                AND a.acc_guid = c.customer_guid
                INNER JOIN lite_b2b.set_supplier d
                ON c.supplier_guid = d.supplier_guid
                WHERE a.user_guid = '$user_guid' 
                AND a.acc_guid = '$duplicate_customer_guid'
                AND c.supplier_guid != '$duplicate_supplier_guid'
                GROUP BY d.supplier_guid");
            }
            else if($duplicate_type == 'other_retailer')
            {
                $query_data = $this->db->query("SELECT e.acc_name,a.supplier_name,d.user_id,d.user_name,d.user_group_guid,d.user_guid
                FROM lite_b2b.set_supplier a
                INNER JOIN lite_b2b.set_supplier_group b
                ON a.supplier_guid = b.supplier_guid
                INNER JOIN lite_b2b.set_supplier_user_relationship c
                ON b.supplier_guid = c.supplier_guid
                AND b.customer_guid = c.customer_guid
                INNER JOIN lite_b2b.set_user d
                ON c.user_guid = d.user_guid
                AND d.isactive = '1'
                INNER JOIN lite_b2b.acc e
                ON d.acc_guid = e.acc_guid
                AND d.isactive = '1'
                WHERE a.supplier_guid = '$duplicate_supplier_guid'
                AND (d.user_id LIKE '%@%' AND d.user_id NOT LIKE '%xbridge%' AND d.user_id NOT LIKE '%pandasoftware%')
                AND d.user_group_guid NOT IN ('$valid_admin_guid')
                AND e.acc_guid != '$customer_guid'
                GROUP BY d.user_guid");
            }
            else
            {
                $query_data = [];
            }

            // echo $this->db->last_query(); die;

            $result = array(
                'data' => $query_data->result(),
            );
        
            echo json_encode($result);
        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function duplicate_process()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
            $admin_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status >= '1' AND isactive = '1' AND admin_active = '2'")->result_array();
            $valid_admin_guid = implode("','",array_filter(array_column($admin_guid,'user_group_guid')));

            $duplicate_user_guid = $this->input->post('duplicate_user_guid');
            $duplicate_customer_guid = $this->input->post('duplicate_customer_guid');
            $duplicate_supplier_guid = $this->input->post('duplicate_supplier_guid');
            $select_duplicate_value = $this->input->post('select_duplicate_value');
            $duplicate_type = $this->input->post('duplicate_type');

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            if($duplicate_type == 'retailer')
            {
                $get_valid_acc = $this->db->query("SELECT d.acc_guid,d.acc_name
                FROM lite_b2b.set_user a
                INNER JOIN lite_b2b.set_user_group b
                ON a.user_group_guid = b.user_group_guid
                INNER JOIN lite_b2b.set_supplier_user_relationship c
                ON a.user_guid = c.user_guid
                AND a.acc_guid = c.customer_guid
                INNER JOIN lite_b2b.acc d
                ON a.acc_guid = d.acc_guid
                WHERE a.user_guid = '$user_guid' 
                AND a.user_group_guid IN ('$valid_admin_guid')
                AND d.acc_guid = '$select_duplicate_value'
                GROUP BY a.user_group_guid,a.acc_guid")->result_array();
        
                if(count($get_valid_acc) == 0)
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'Permission Denied.',
                    );    
                    echo json_encode($data);   
                    exit();
                }
                else
                {
                    $check_user = $this->db->query("SELECT a.* FROM lite_b2b.set_user a WHERE a.acc_guid = '$duplicate_customer_guid' AND a.user_guid = '$duplicate_user_guid' GROUP BY a.user_guid LIMIT 1");
        
                    if($check_user->num_rows() == 0)
                    {
                        $data = array(
                            'para1' => 'false',
                            'msg' => 'Data Not Found to Duplicate.',
                        );    
                        echo json_encode($data); 
                        die;  
                    }
    
                    $check_duplicate_user = $this->db->query("SELECT a.* FROM lite_b2b.set_user a WHERE a.acc_guid = '$select_duplicate_value' AND a.user_guid = '$duplicate_user_guid' GROUP BY a.user_guid LIMIT 1")->result_array();
        
                    if(count($check_duplicate_user) > 0)
                    {
                        $data = array(
                            'para1' => 'false',
                            'msg' => 'User already exists.',
                        );    
                        echo json_encode($data); 
                        die;
                    }
        
                    $data = array(
                        'acc_guid' => $select_duplicate_value,
                        'module_group_guid' => $check_user->row('module_group_guid'),
                        'user_guid' => $duplicate_user_guid,
                        'user_group_guid' => $check_user->row('user_group_guid'),
                        'user_id' => $check_user->row('user_id'),
                        'user_name' => $check_user->row('user_name'),
                        'user_password' => $check_user->row('user_password'),
                        'limited_location' => $check_user->row('limited_location'),
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $user_id,
                        // 'supplier_guid' => $supplier_guid,
                        'isactive' => '9',
                    );
                    $this->db->insert('lite_b2b.set_user',$data);
    
                    $get_link = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');
    
                    $data = array(
                        // 'module_group_guid' => $module_group_guid,
                        'guid' => $get_link,
                        'user_guid' => $duplicate_user_guid,
                        'customer_guid' => $select_duplicate_value,
                        'supplier_guid' => $duplicate_supplier_guid,
                        'status' => '0',
                        'action_status' => 'DUPLICATE',
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $user_id,
                        'created_by_guid' => $user_guid,
                    );
                    $this->db->insert('lite_b2b.set_user_process_list',$data);
                }
    
            }
            else if($duplicate_type == 'supplier')
            {
                $get_valid_supplier = $this->db->query("SELECT d.supplier_guid ,d.supplier_name
                FROM lite_b2b.set_user a
                INNER JOIN lite_b2b.set_supplier_user_relationship b
                ON a.user_guid = b.user_guid
                INNER JOIN lite_b2b.set_supplier_group c
                ON b.supplier_guid = c.supplier_guid
                AND a.acc_guid = c.customer_guid
                INNER JOIN lite_b2b.set_supplier d
                ON c.supplier_guid = d.supplier_guid
                WHERE a.user_guid = '$user_guid' 
                AND a.acc_guid = '$duplicate_customer_guid'
                AND c.supplier_guid = '$select_duplicate_value'
                GROUP BY d.supplier_guid")->result_array();

                $query_data_vc = $this->db->query("SELECT f.acc_guid,f.acc_name, 
                d.supplier_guid, d.supplier_name, c.supplier_group_guid, c.supplier_group_name, 
                a.user_guid, a.user_id, a.user_name, e.name AS supcus_name, 
                b.created_at, b.created_by 
                FROM lite_b2b.set_user a
                INNER JOIN lite_b2b.set_supplier_user_relationship b
                ON a.user_guid = b.user_guid 
                AND a.acc_guid = b.customer_guid
                INNER JOIN lite_b2b.set_supplier_group c
                ON b.supplier_group_guid = c.supplier_group_guid
                AND b.customer_guid = c.customer_guid
                INNER JOIN lite_b2b.set_supplier d
                ON c.supplier_guid = d.supplier_guid
                INNER JOIN b2b_summary.supcus e 
                ON c.supplier_group_name = e.code 
                AND c.customer_guid = e.customer_guid 
                INNER JOIN lite_b2b.acc f 
                ON b.customer_guid = f.acc_guid 
                WHERE b.supplier_guid = '$duplicate_supplier_guid' 
                AND a.user_guid = '$duplicate_user_guid'
                AND a.acc_guid = '$duplicate_customer_guid'
                GROUP BY b.supplier_group_guid")->result_array();

                // echo $this->db->last_query(); die;

                if(count($get_valid_supplier) == 0)
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'Permission Denied.',
                    );    
                    echo json_encode($data);   
                    exit();
                }
                else if(count($query_data_vc) == 0)
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'User Already Exists.',
                    );    
                    echo json_encode($data);   
                    exit();
                }
                else
                {
                    $check_user = $this->db->query("SELECT a.* FROM lite_b2b.set_user a WHERE a.acc_guid = '$duplicate_customer_guid' AND a.user_guid = '$duplicate_user_guid' GROUP BY a.user_guid LIMIT 1");
        
                    if($check_user->num_rows() == 0)
                    {
                        $data = array(
                            'para1' => 'false',
                            'msg' => 'Data Not Found to Mapping Supplier.',
                        );    
                        echo json_encode($data); 
                        die;  
                    }

                    $get_link = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');
    
                    $data = array(
                        // 'module_group_guid' => $module_group_guid,
                        'guid' => $get_link,
                        'user_guid' => $duplicate_user_guid,
                        'customer_guid' => $duplicate_customer_guid,
                        'supplier_guid' => $select_duplicate_value,
                        'status' => '0',
                        'action_status' => 'DUPLICATE',
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $user_id,
                        'created_by_guid' => $user_guid,
                    );
                    $this->db->insert('lite_b2b.set_user_process_list',$data);
                }
            }
            else
            {
                $data = array(
                    'para1' => 'false',
                    'get_link' => 'Duplicate Action Invalid.',
                );    
                echo json_encode($data);   
                exit();
            }

            
            $error = $this->db->affected_rows();

            if($error > 0)
            {
                $data = array(
                    'para1' => 'true',
                    'msg' => 'Duplicate Successful.',
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {               
                $data = array(
                    'para1' => 'false',
                    'get_link' => 'Failed to Duplicate.',
                );    
                echo json_encode($data);   
                exit();
            }
        } 
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function send_user_information()
    {   
        $subject = 'Login Details';
        $send_user_guid = $this->input->post("send_user_guid");
        $send_customer_guid = $this->input->post("send_customer_guid");
        $send_supplier_guid = $this->input->post("send_supplier_guid");
        $reset_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
        $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
        $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
        $customer_name = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$send_customer_guid'");
        $get_user_account_maintenance = $this->db->query("SELECT * FROM lite_b2b.acc_settings WHERE customer_guid = '$send_customer_guid'")->row('user_account_maintenance');
        $url = 'https://b2b.xbridge.my';
        $supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$send_supplier_guid'");
        $supplier_code = $this->db->query("SELECT GROUP_CONCAT(DISTINCT supplier_group_name) as vendor_code FROM lite_b2b.set_supplier_group WHERE supplier_guid = '$send_supplier_guid' AND customer_guid = '$send_customer_guid'");

        $get_user_array = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_guid = '$send_user_guid' AND acc_guid = '$send_customer_guid' AND isactive = '1' GROUP BY user_guid,acc_guid ");

        if($get_user_array->num_rows() == 0)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'Data Not Found.',
            );    
            echo json_encode($data);   
            exit();
        }

        $email_name = $get_user_array->row('user_id');
        $email_add = $get_user_array->row('user_id');

        $get_user_duplicate = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_guid = '$send_user_guid' AND acc_guid != '$send_customer_guid' AND isactive = '1' GROUP BY user_guid,acc_guid ");

        if($get_user_duplicate->num_rows() == 0)
        {
            $data_1 = array(   
                'reset_guid`' => $reset_guid,
                'customer_guid`' => $send_customer_guid,
                'user_guid`' => $send_user_guid,
                'email_id`' => $email_name,
                'is_reset`' => 0,
                'reset_at`' => '1001-01-01 00:00:00',
                'created_by`' => $user_id,
                'created_at`' => $updated_at,
                'updated_by`' => $user_id,
                'updated_at`' => $updated_at
            );

            $this->db->insert('reset_pass_list', $data_1);
             
            $reset_link = $this->db->query("SELECT * FROM lite_b2b.reset_pass_list WHERE reset_guid = '$reset_guid'");
    
            $reset_url = 'https://b2b.xbridge.my/index.php/Key_in/key_in?si='.$reset_link->row('reset_guid').'&ug='.$reset_link->row('user_guid');

            $email_data = array(
                'reset_detail' => $reset_link,
                'customer_name' => $customer_name,
                'user_detail' => $get_user_array,
                'reset_url' => $reset_url,
                'supplier_detail' => $supplier_detail,
                'supplier_code' => $supplier_code,
                'get_user_account_maintenance' => '0',
            );
    
            $bodyContent    = $this->load->view('email_template/user_login_reset_view',$email_data,TRUE);
            //echo $bodyContent;die;  
            // die here;    

            $send_result = $this->Send_email_model->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','support@xbridge.my','');

            if($send_result == '200')
            {
                $send_status_naming = 'Success';
            }
            else
            {
                $send_status_naming = 'Error';
            }

            $data_email = array(
                'guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                'module' => 'user_account',
                'remark' => 'new',
                'customer_guid' => $send_customer_guid,
                'user_guid' => $get_user_array->row('user_guid'),
                'status' => $send_status_naming,
                'from_email' => 'b2b_admin@xbridge.my',
                'email_id' => $email_add,
                'subject' => $subject,
                'content' => $bodyContent,
                'created_by' => $user_id,
                'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                'updated_by' => $user_id,
                'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),          
            );   
            $this->db->insert('lite_b2b.email_send_content',$data_email);
        }
        else
        {
            $email_data = array(
                'customer_name' => $customer_name,
                'user_detail' => $get_user_array,
                'supplier_detail' => $supplier_detail,
                'supplier_code' => $supplier_code,
                'get_user_account_maintenance' => '0',
            );
    
            $bodyContent    = $this->load->view('email_template/user_login_duplicate_view',$email_data,TRUE);
            //echo $bodyContent;die;  
            // die here;   
            $send_result = $this->Send_email_model->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','support@xbridge.my','');

            if($send_result == '200')
            {
                $send_status_naming = 'Success';
            }
            else
            {
                $send_status_naming = 'Error';
            }

            $data_email = array(
                'guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                'module' => 'user_account',
                'remark' => 'duplicate',
                'customer_guid' => $send_customer_guid,
                'user_guid' => $get_user_array->row('user_guid'),
                'status' => $send_status_naming,
                'from_email' => 'b2b_admin@xbridge.my',
                'email_id' => $email_add,
                'subject' => $subject,
                'content' => $bodyContent,
                'created_by' => $user_id,
                'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                'updated_by' => $user_id,
                'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),          
            );   
            $this->db->insert('lite_b2b.email_send_content',$data_email);
        }
 
        if($send_result == '200')
        {
            $msg = 'Success Send Information to : '.$email_name.'.';
        }
        else
        {
            $msg = 'Error Send Information to : '.$email_name.'.';
        }

        $data = array(
            'para1' => 'true',
            'msg' => $msg,
        );    

        echo json_encode($data);
    }

    // current no use le 
    public function duplicate_process_from_retailer()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
            $admin_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status >= '1' AND isactive = '1' AND admin_active = '2'")->result_array();
            $valid_admin_guid = implode("','",array_filter(array_column($admin_guid,'user_group_guid')));

            $duplicate_user_guid = $this->input->post('add_duplicate_user');
            $duplicate_customer_guid = $this->input->post('session_customer_guid');
            $duplicate_supplier_guid = $this->input->post('duplicate_selection_supplier');

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            if($customer_guid != $duplicate_customer_guid)
            {
                $data = array(
                    'para1' => 'false',
                    'get_link' => 'Invalid Session Process.',
                );    
                echo json_encode($data);   
                exit();
            }

            $get_valid_acc = $this->db->query("SELECT d.acc_guid,d.acc_name
            FROM lite_b2b.set_user a
            INNER JOIN lite_b2b.set_user_group b
            ON a.user_group_guid = b.user_group_guid
            INNER JOIN lite_b2b.set_supplier_user_relationship c
            ON a.user_guid = c.user_guid
            AND a.acc_guid = c.customer_guid
            INNER JOIN lite_b2b.acc d
            ON a.acc_guid = d.acc_guid
            WHERE a.user_guid = '$user_guid' 
            AND a.user_group_guid IN ('$valid_admin_guid')
            AND d.acc_guid = '$duplicate_customer_guid'
            GROUP BY a.user_group_guid,a.acc_guid")->result_array();
        
            if(count($get_valid_acc) == 0)
            {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Permission Denied.',
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {
                $check_user = $this->db->query("SELECT a.* FROM lite_b2b.set_user a WHERE a.acc_guid != '$duplicate_customer_guid'AND a.user_guid = '$duplicate_user_guid' GROUP BY a.user_guid LIMIT 1");
        
                if($check_user->num_rows() == 0)
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'Data Not Found to Duplicate.',
                    );    
                    echo json_encode($data); 
                    die;  
                }
    
                $check_duplicate_user = $this->db->query("SELECT a.* FROM lite_b2b.set_user a WHERE a.acc_guid ='$duplicate_customer_guid' AND a.user_guid = '$duplicate_user_guid' GROUP BY a.user_guid LIMIT 1")->result_array();
        
                if(count($check_duplicate_user) > 0)
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'User already exists.',
                    );    
                    echo json_encode($data); 
                    die;
                }
        
                $data = array(
                    'acc_guid' => $duplicate_customer_guid,
                    'module_group_guid' => $check_user->row('module_group_guid'),
                    'user_guid' => $duplicate_user_guid,
                    'user_group_guid' => $check_user->row('user_group_guid'),
                    'user_id' => $check_user->row('user_id'),
                    'user_name' => $check_user->row('user_name'),
                    'user_password' => $check_user->row('user_password'),
                    'limited_location' => $check_user->row('limited_location'),
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $user_id,
                    // 'supplier_guid' => $supplier_guid,
                    'isactive' => '9',
                );
                $this->db->insert('lite_b2b.set_user',$data);
    
                $get_link = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');
    
                $data = array(
                    // 'module_group_guid' => $module_group_guid,
                    'guid' => $get_link,
                    'user_guid' => $duplicate_user_guid,
                    'customer_guid' => $duplicate_customer_guid,
                    'supplier_guid' => $duplicate_supplier_guid,
                    'status' => '0',
                    'action_status' => 'DUPLICATE',
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $user_id,
                    'created_by_guid' => $user_guid,
                );
                $this->db->insert('lite_b2b.set_user_process_list',$data);
            }

            $error = $this->db->affected_rows();

            if($error > 0)
            {
                $data = array(
                    'para1' => 'true',
                    'msg' => 'Duplicate Successful.',
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {               
                $data = array(
                    'para1' => 'false',
                    'get_link' => 'Failed to Duplicate.',
                );    
                echo json_encode($data);   
                exit();
            }
        } 
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function information()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login() && in_array('VUAMT', $_SESSION['module_code'])) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0'; // 6595A39AD4AE11E7861FA81E8453CCF0
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; // 030E3C41EAF011ECA43DB2C55218ACED
            $admin_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status >= '1' AND isactive = '1' AND admin_active = '2'")->result_array();
            $valid_admin_guid = implode("','",array_filter(array_column($admin_guid,'user_group_guid')));
            
            $process_user_guid = '';
            $process_action_status = '';
            $flag_show_tab = '';
            $tab_2 = '';
            $tab_3 = '';
            $tab_summary = '';

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            if(isset($_REQUEST['link']))
            {
                $process_supplier_guid = $_REQUEST['link'];
                $process_customer_guid = $_SESSION['customer_guid'];

                $get_supplier_creation = $this->db->query("SELECT d.supplier_guid,d.supplier_name FROM 
                lite_b2b.set_user a
                INNER JOIN lite_b2b.set_supplier_user_relationship b
                ON a.user_guid = b.user_guid
                INNER JOIN lite_b2b.set_supplier_group c
                ON b.supplier_guid = c.supplier_guid
                AND b.customer_guid = c.customer_guid
                INNER JOIN lite_b2b.set_supplier d
                ON c.supplier_guid = d.supplier_guid
                WHERE a.user_guid = '$user_guid' 
                AND d.supplier_guid = '$process_supplier_guid'
                GROUP BY d.supplier_guid")->result_array();

                if(count($get_supplier_creation) == 0 )
                {
                    echo '<script>alert("Invalid URL. ERR CODE E001");window.location.href = "'.site_url('User_account_setting').'";</script>;';
                    die;
                }
            }
            else if(isset($_REQUEST['link_one']))
            {
                $link_one = $_REQUEST['link_one'];

                $get_pending_creation = $this->db->query("SELECT a.* FROM lite_b2b.set_user_process_list a WHERE a.guid = '$link_one'  GROUP BY a.guid")->result_array();

                if(count($get_pending_creation) == 0 )
                {
                    echo '<script>alert("Invalid URL. ERR CODE E002");window.location.href = "'.site_url('User_account_setting').'";</script>;';
                    die;
                }

                $process_user_guid = $get_pending_creation[0]['user_guid'];
                $process_customer_guid = $get_pending_creation[0]['customer_guid'];
                $process_supplier_guid = $get_pending_creation[0]['supplier_guid'];
                $process_action_status = $get_pending_creation[0]['action_status'];

                $get_user = $this->db->query("SELECT a.isactive FROM lite_b2b.set_user a WHERE a.user_guid = '$process_user_guid' AND a.acc_guid = '$process_customer_guid' GROUP BY a.user_guid,a.acc_guid LIMIT 1");

                $user_active_status = $get_user->row('isactive');
    
                if($user_active_status == '1' || $user_active_status == '0')
                {
                    $flag_show_tab = 'show_tab';
                    $tab_2 = site_url('User_account_setting/mapping_information?link_one='.$link_one);
                    $tab_summary = site_url('User_account_setting/final_summary?link_one='.$link_one);
                }
            }
            else
            {
                echo '<script>alert("Invalid URL.");window.location.href = "'.site_url('User_account_setting').'";</script>;';
                die;
            }

            $get_user_group = $this->db->query("SELECT a.user_group_guid,a.user_group_name FROM lite_b2b.`set_user_group` a WHERE a.module_group_guid = '$module_group_guid' AND a.isactive = '1' AND a.admin_active >= '1' ORDER BY a.user_group_name ASC");

            if($_SESSION['user_group_name'] == 'SUPER_ADMINa')
            {
                $check_user_group = $this->db->query("SELECT b.user_group_name,a.user_group_guid,a.user_guid,a.acc_guid 
                FROM lite_b2b.set_user a
                INNER JOIN lite_b2b.set_user_group b
                ON a.user_group_guid = b.user_group_guid
                INNER JOIN lite_b2b.set_supplier_user_relationship c
                ON a.user_guid = c.user_guid
                AND a.acc_guid = c.customer_guid
                WHERE a.acc_guid = '$process_customer_guid'
                AND a.user_group_guid IN ('$valid_admin_guid')
                GROUP BY a.user_group_guid,a.acc_guid")->result_array();
            }
            else
            {
                $check_user_group = $this->db->query("SELECT b.user_group_name,a.user_group_guid,a.user_guid,a.acc_guid 
                FROM lite_b2b.set_user a
                INNER JOIN lite_b2b.set_user_group b
                ON a.user_group_guid = b.user_group_guid
                INNER JOIN lite_b2b.set_supplier_user_relationship c
                ON a.user_guid = c.user_guid
                AND a.acc_guid = c.customer_guid
                WHERE a.user_guid = '$user_guid' 
                AND a.acc_guid = '$process_customer_guid'
                AND a.user_group_guid IN ('$valid_admin_guid')
                GROUP BY a.user_group_guid,a.acc_guid")->result_array();
            }

            // print_r($check_user_group); die;

            if(count($check_user_group) > 0 )
            {
                $valid_customer_guid = implode("','",array_filter(array_column($check_user_group,'acc_guid')));
            }
            else
            {
                $valid_customer_guid = '';
            }

            $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' AND a.acc_guid IN ('$valid_customer_guid') ORDER BY a.acc_name ASC");

            $get_supplier = $this->db->query("SELECT a.supplier_name FROM lite_b2b.set_supplier a WHERE a.supplier_guid = '$process_supplier_guid'");

            $get_registered_count = $this->db->query("SELECT aa.acc_guid,aa.acc_name,COUNT(aa.user_name) AS count_data
            FROM
            ( SELECT e.acc_guid,e.acc_name,d.supplier_guid,d.supplier_name,a.user_name 
            FROM 
            lite_b2b.set_user a
            INNER JOIN lite_b2b.set_supplier_user_relationship b
            ON a.user_guid = b.user_guid
            INNER JOIN lite_b2b.set_supplier_group c
            ON b.supplier_guid = c.supplier_guid
            AND b.customer_guid = c.customer_guid
            INNER JOIN lite_b2b.set_supplier d
            ON c.supplier_guid = d.supplier_guid
            INNER JOIN lite_b2b.acc e
            ON b.customer_guid = e.acc_guid
            WHERE a.acc_guid = '$process_customer_guid'
            AND d.supplier_guid = '$process_supplier_guid' 
            AND a.isactive != '9'
            AND a.hide_admin = '0'
            GROUP BY a.user_id,a.acc_guid ) aa
            GROUP BY aa.acc_name")->row('count_data');

            $get_supplier_user = $this->db->query("SELECT e.acc_name,a.supplier_name,d.user_id,d.user_name,d.user_group_guid,d.user_guid
            FROM lite_b2b.set_supplier a
            INNER JOIN lite_b2b.set_supplier_group b
            ON a.supplier_guid = b.supplier_guid
            INNER JOIN lite_b2b.set_supplier_user_relationship c
            ON b.supplier_guid = c.supplier_guid
            AND b.customer_guid = c.customer_guid
            INNER JOIN lite_b2b.set_user d
            ON c.user_guid = d.user_guid
            AND d.isactive = '1'
            INNER JOIN lite_b2b.acc e
            ON d.acc_guid = e.acc_guid
            AND d.isactive = '1'
            WHERE a.supplier_guid = '$process_supplier_guid'
            AND (d.user_id LIKE '%@%' AND d.user_id NOT LIKE '%xbridge%' AND d.user_id NOT LIKE '%pandasoftware%')
            AND d.user_group_guid NOT IN ('$valid_admin_guid')
            AND e.acc_guid != '$process_customer_guid'
            AND d.hide_admin = '0'
            GROUP BY d.user_guid");
            
            $get_notification_report = $this->db->query("SELECT a.rep_option_guid,a.option_description FROM lite_b2b.set_report_query_option a WHERE a.isactive = '1' AND a.customer_guid = '$process_customer_guid' ");

            // echo $this->db->last_query(); die;

            $data = array(
                'get_user_group' => $get_user_group->result(),
                'get_acc' => $get_acc->result(),
                'get_notification_report' => $get_notification_report->result(),
                'acc_name' => $get_acc->row('acc_name'),
                'supplier_name' => $get_supplier->row('supplier_name'),
                'get_supplier_user' => $get_supplier_user->result(),
                'process_customer_guid' => $process_customer_guid,
                'process_supplier_guid' => $process_supplier_guid,
                'process_action_status' => $process_action_status,
                'get_registered_count' => $get_registered_count,
                'link_one' => $link_one,
                'flag_show_tab' => $flag_show_tab,
                'tab_2' => $tab_2,
                'tab_summary' => $tab_summary,
                'admin_guid' => json_encode(array_filter(array_column($admin_guid,'user_group_guid'))),
            );

            $this->load->view('header');
            $this->load->view('user_account/user_create_info', $data);
            $this->load->view('footer');

        } 
        else 
        {
            $this->session->set_flashdata('message', 'You have not rights to access.');
            redirect('dashboard');
        }
    }
    
    public function list_info()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; // 030E3C41EAF011ECA43DB2C55218ACED
            $link_one = $this->input->post('link_one');
            $process_supplier_guid = $this->input->post('process_supplier_guid');

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
            {
                $get_pending_user = $this->db->query("SELECT b.*,c.acc_name,d.supplier_name FROM lite_b2b.set_user_process_list a LEFT JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid LEFT JOIN lite_b2b.acc c ON b.acc_guid = c.acc_guid LEFT JOIN lite_b2b.set_supplier d ON a.supplier_guid = b.supplier_guid WHERE a.guid = '$link_one' GROUP BY a.guid");
            }
            else
            {
                $get_pending_user = $this->db->query("SELECT b.*,c.acc_name,d.supplier_name FROM lite_b2b.set_user_process_list a LEFT JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid LEFT JOIN lite_b2b.acc c ON b.acc_guid = c.acc_guid LEFT JOIN lite_b2b.set_supplier d ON a.supplier_guid = b.supplier_guid WHERE a.guid = '$link_one' AND a.created_by_guid = '$user_guid' GROUP BY a.guid");
            }

            // $get_pending_user = $this->db->query("SELECT a.* FROM lite_b2b.set_user a WHERE a.`supplier_guid` = '$link_one' AND a.user_guid = '$link_two'");

            $procees_user_guid = $get_pending_user->row('user_guid');
            $procees_customer_guid = $get_pending_user->row('acc_guid');
            
            $get_notification_user = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option a INNER JOIN lite_b2b.set_report_query_option_c b ON a.rep_option_guid = b.rep_option_guid AND a.customer_guid = b.customer_guid WHERE b.user_guid = '$procees_user_guid' AND b.customer_guid = '$procees_customer_guid' AND b.isactive = '1'")->result_array();

            $notification_array = array_filter(array_column($get_notification_user,'rep_option_guid'));

            // echo $this->db->last_query(); die;

            $data = array(
                'acc_name' => $get_pending_user->row('acc_name'),
                'supplier_name' => $get_pending_user->row('supplier_name'),
                // 'acc_guid' => $get_pending_user->row('acc_guid'), 
                // 'branch_guid' => $get_pending_user->row('branch_guid'), 
                // 'module_group_guid' => $get_pending_user->row('module_group_guid'), 
                'user_group_guid' => $get_pending_user->row('user_group_guid'), 
                // 'user_guid' => $get_pending_user->row('user_guid'), 
                // 'supplier_guid' => $get_pending_user->row('supplier_guid'), 
                'isactive' => $get_pending_user->row('isactive'), 
                'user_id' => $get_pending_user->row('user_id'), 
                // 'user_password' => $get_pending_user->row('user_password'), 
                'user_name' => $get_pending_user->row('user_name'), 
                'created_at' => $get_pending_user->row('created_at'), 
                'created_by' => $get_pending_user->row('created_by'), 
                // 'updated_at' => $get_pending_user->row('updated_at'), 
                // 'updated_by' => $get_pending_user->row('updated_by'), 
                'limited_location' => $get_pending_user->row('limited_location'), 
                'notification' => $notification_array, 
                'auto_vendor_code' => $get_pending_user->row('auto_vendor_code'),
                // 'ismobile' => $get_pending_user->row('ismobile')
            );

            // print_r(array_shift$get_pending_user);
            // print_r($data); die;

            echo json_encode($data);

        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function fetch_module_description()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $info_user_group_guid = $this->input->post('info_user_group_guid');
            $link_one = $this->input->post('link_one');

            $query_data = $this->db->query("SELECT 
            d.`user_group_name`,
            b.`module_name`
            FROM 
            lite_b2b.set_user_module a 
            INNER JOIN lite_b2b.set_module b ON a.`module_guid` = b.`module_guid` 
            INNER JOIN lite_b2b.set_module_group c ON a.`module_group_guid` = c.`module_group_guid` 
            INNER JOIN lite_b2b.set_user_group d ON d.`user_group_guid` = a.`user_group_guid` 
            WHERE c.`module_group_guid` = '$module_group_guid' 
            AND d.`user_group_guid` = '$info_user_group_guid'");

            // echo $this->db->last_query(); die;

            $result = array(
                'data' => $query_data->result(),
                'title_group_name' => $query_data->row('user_group_name'),
            );
        
            echo json_encode($result);
        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function fetch_notification_report()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED

            $query_data = $this->db->query("SELECT b.log_table,a.* 
            FROM lite_b2b.`set_report_query_option` a
            INNER JOIN lite_b2b.`set_logs_query` b
            ON a.report_guid = b.guid
            AND b.isactive IN ('1','2')
            WHERE a.isactive = '1'
            AND a.customer_guid = '$customer_guid'");

            // echo $this->db->last_query(); die;

            $result = array(
                'data' => $query_data->result(),
            );
        
            echo json_encode($result);
        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function fetch_user_details()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $admin_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status >= '1' AND isactive = '1' AND admin_active = '2'")->result_array();
            $valid_admin_guid = implode("','",array_filter(array_column($admin_guid,'user_group_guid')));

            $add_duplicate_user = $this->input->post('add_duplicate_user');
            $add_duplicate_supplier = $this->input->post('add_duplicate_supplier');

            $query_data = $this->db->query("SELECT e.acc_name,a.supplier_name,d.user_id,d.user_name,d.user_group_guid,d.user_guid,d.limited_location,d.isactive
            FROM lite_b2b.set_supplier a
            INNER JOIN lite_b2b.set_supplier_group b
            ON a.supplier_guid = b.supplier_guid
            INNER JOIN lite_b2b.set_supplier_user_relationship c
            ON b.supplier_guid = c.supplier_guid
            AND b.customer_guid = c.customer_guid
            INNER JOIN lite_b2b.set_user d
            ON c.user_guid = d.user_guid
            AND d.isactive = '1'
            INNER JOIN lite_b2b.acc e
            ON d.acc_guid = e.acc_guid
            AND d.isactive = '1'
            WHERE a.supplier_guid = '$add_duplicate_supplier'
            AND (d.user_id LIKE '%@%' AND d.user_id NOT LIKE '%xbridge%' AND d.user_id NOT LIKE '%pandasoftware%')
            AND d.user_group_guid NOT IN ('$valid_admin_guid')
            AND e.acc_guid != '$customer_guid'
            AND d.user_guid = '$add_duplicate_user'
            GROUP BY d.user_guid");

            // echo $this->db->last_query(); die;

            $result = array(
                'data' => $query_data->result(),
            );
        
            echo json_encode($result);
        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function verify_user_info()
    {
        $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
        $user_id_input = $this->input->post('user_id_input');

        $check_user = $this->db->query("SELECT a.user_guid FROM lite_b2b.set_user a INNER JOIN lite_b2b.set_user_module b ON a.`user_group_guid` = b.`user_group_guid` INNER JOIN lite_b2b.set_module c ON c.`module_guid` = b.`module_guid` INNER JOIN lite_b2b.set_module_group d ON d.`module_group_guid` = c.`module_group_guid` AND d.`module_group_guid` = a.`module_group_guid` INNER JOIN lite_b2b.set_user_group e ON e.`user_group_guid` = a.`user_group_guid` WHERE d.`module_group_guid` = '$module_group_guid' AND a.`user_id` = '$user_id_input' GROUP BY a.`user_id`")->result_array();

        // echo $this->db->last_query(); die;
    
        if(count($check_user) > 0)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'User ID is being used',
            );    
            echo json_encode($data); 
            die;  
        }
        else
        {
            $data = array(
                'para1' => 'true',
                'msg' => 'User ID can be use.',
            );    
            echo json_encode($data); 
            die;  
        }
    }

    public function process_info()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');

            $admin_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status >= '1' AND isactive = '1' AND admin_active = '2'")->result_array();
            $valid_admin_guid = implode("','",array_filter(array_column($admin_guid,'user_group_guid')));

            $process_user_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');
            $add_customer_guid = $this->input->post('add_customer_guid');
            $add_supplier_guid = $this->input->post('add_supplier_guid');
            $add_user_id = $this->input->post('add_user_id');
            $add_user_name = $this->input->post('add_user_name');
            $add_password = $this->input->post('add_password');
            $add_user_group = $this->input->post('add_user_group');
            $add_mapping_vc = $this->input->post('add_mapping_vc');
            $add_limited_location = $this->input->post('add_limited_location');
            $add_active_status = $this->input->post('add_active_status');
            $action_type = $this->input->post('action_type');
            $link_one = $this->input->post('link_one');
            $add_duplicate_user = $this->input->post('add_duplicate_user');
            $add_notification = $this->input->post('add_notification');
            $notselected_notification = $this->input->post('notselected_notification');
            $isactive = '9';
            $valid_process = '';

            // print_r($add_user_group); die;

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $array_data_selected = json_decode(json_encode($add_notification));
            $array_data_no_selected = json_decode(json_encode($notselected_notification));

            if(count($array_data_selected) > 0 && count($array_data_no_selected) > 0)
            {
                $array_data = array_merge($array_data_selected, $array_data_no_selected);
            }
            else if(count($array_data_selected) > 0)
            {
                $array_data = $array_data_selected;
            }
            else
            {
                $array_data = $array_data_no_selected;
            }

            // print_r($array_data); die;

            if($action_type == 'process')
            {
                $check_user = $this->db->query("SELECT a.user_guid FROM lite_b2b.set_user a INNER JOIN lite_b2b.set_user_module b ON a.`user_group_guid` = b.`user_group_guid` INNER JOIN lite_b2b.set_module c ON c.`module_guid` = b.`module_guid` INNER JOIN lite_b2b.set_module_group d ON d.`module_group_guid` = c.`module_group_guid` AND d.`module_group_guid` = a.`module_group_guid` INNER JOIN lite_b2b.set_user_group e ON e.`user_group_guid` = a.`user_group_guid` WHERE d.`module_group_guid` = '$module_group_guid' AND a.`user_id` = '$add_user_id' GROUP BY a.`user_id`")->result_array();
    
                if(count($check_user) > 0)
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'Duplicate User ID.',
                    );    
                    echo json_encode($data); 
                    die;  
                }

                $data = array(
                    'acc_guid' => $add_customer_guid,
                    'module_group_guid' => $module_group_guid,
                    'user_guid' => $process_user_guid,
                    'user_group_guid' => $add_user_group,
                    'user_id' => $add_user_id,
                    'user_name' => $add_user_name,
                    'user_password' => md5($add_password),
                    'auto_vendor_code' => $add_mapping_vc,
                    'limited_location' => $add_limited_location,
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $user_id,
                    // 'supplier_guid' => $supplier_guid,
                    'isactive' => $isactive,
                );
                $this->db->insert('lite_b2b.set_user',$data);

                $check_insert_user = $this->db->query("SELECT a.user_guid FROM lite_b2b.set_user a WHERE a.user_guid = '$process_user_guid' AND a.acc_guid = '$add_customer_guid'")->result_array();

                if(count($check_insert_user) > 0)
                {
                    $valid_process = 'YES';
                }
                else
                {
                    $valid_process = 'ERROR';
                }

                $store_user_guid = $process_user_guid;
                $msg = 'Create Successful. You will proceed to next step.';
            }
            else if($action_type == 'update')
            {
                $check_user = $this->db->query("SELECT a.user_guid,a.customer_guid,b.user_id,b.user_name,b.isactive FROM lite_b2b.set_user_process_list a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.guid = '$link_one' AND a.created_by_guid = '$user_guid' AND a.customer_guid = '$add_customer_guid' GROUP BY a.guid");

                if(count($check_user) == 0)
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'Data Not Found.',
                    );    
                    echo json_encode($data); 
                    die;  
                }

                $procees_user_guid = $check_user->row('user_guid');
                $procees_user_id = $check_user->row('user_id');
                $procees_user_name = $check_user->row('user_name');
                
                $user_id_duplicate = $this->db->query("SELECT a.user_guid FROM lite_b2b.set_user a INNER JOIN lite_b2b.set_user_module b ON a.`user_group_guid` = b.`user_group_guid` INNER JOIN lite_b2b.set_module c ON c.`module_guid` = b.`module_guid` INNER JOIN lite_b2b.set_module_group d ON d.`module_group_guid` = c.`module_group_guid` AND d.`module_group_guid` = a.`module_group_guid` INNER JOIN lite_b2b.set_user_group e ON e.`user_group_guid` = a.`user_group_guid` WHERE d.`module_group_guid` = '$module_group_guid' AND a.`user_id`= '$add_user_id' AND a.user_guid != '$procees_user_guid' GROUP BY a.`user_id`")->result_array();
    
                if(count($user_id_duplicate) > 0)
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'User ID already exists.',
                    );    
                    echo json_encode($data); 
                    die;  
                }

                $update_user_guid = $check_user->row('user_guid');
                $update_isactive = $check_user->row('isactive');

                if($update_user_guid == '' || $update_user_guid == '' || $update_user_guid == '')
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'Invalid Data Process.',
                    );    
                    echo json_encode($data); 
                    die;  
                }

                if($add_customer_guid == '' || $add_customer_guid == '' || $add_customer_guid == '')
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'Invalid Data Process.',
                    );    
                    echo json_encode($data); 
                    die;  
                }

                if($update_isactive == '1' || $update_isactive == '0')
                {
                    if($add_active_status == '' || $add_active_status == 'null' || $add_active_status == null)
                    {
                        $data = array(
                            'para1' => 'false',
                            'msg' => 'Invalid User Status.',
                        );    
                        echo json_encode($data); 
                        die;  
                    }

                    $process_isactive = $add_active_status;
                }
                else
                {
                    $process_isactive = '9';
                }
                
                $select_old_user_data = $this->db->query("SELECT a.acc_guid,a.user_id,a.user_name FROM lite_b2b.set_user a WHERE a.user_guid = '$update_user_guid' AND a.isactive = '$update_isactive' AND a.acc_guid != '$add_customer_guid'");

                if($procees_user_id != $add_user_id)
                {
                    $update_user_id = $this->db->query("UPDATE lite_b2b.set_user
                    SET user_id = '$add_user_id'
                    WHERE user_guid = '$update_user_guid'
                    AND isactive = '$update_isactive'
                    AND acc_guid != '$add_customer_guid'");

                    $process_info_log1 = array(
                        'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                        'customer_guid' => $add_customer_guid,
                        'supplier_guid' => $add_supplier_guid,
                        'user_guid' => $update_user_guid,
                        'old_data' => json_encode($select_old_user_data->result()),
                        'new_data' => $add_user_id,
                        'module' => 'process_info',
                        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                        'created_by' => $user_id,
                    );
                    $this->db->insert('lite_b2b.user_account_logs',$process_info_log1);
                }

                if($procees_user_name != $add_user_name)
                {
                    $update_user_id = $this->db->query("UPDATE lite_b2b.set_user
                    SET user_name = '$add_user_name'
                    WHERE user_guid = '$update_user_guid'
                    AND isactive = '$update_isactive'
                    AND acc_guid != '$add_customer_guid'");

                    $process_info_log2 = array(
                        'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                        'customer_guid' => $add_customer_guid,
                        'supplier_guid' => $add_supplier_guid,
                        'user_guid' => $update_user_guid,
                        'old_data' => json_encode($select_old_user_data->result()),
                        'new_data' => $add_user_name,
                        'module' => 'process_info',
                        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                        'created_by' => $user_id,
                    );
                    $this->db->insert('lite_b2b.user_account_logs',$process_info_log2);
                }

                $select_all_user = $this->db->query("SELECT a.* FROM lite_b2b.set_user a WHERE a.user_guid = '$update_user_guid' AND a.acc_guid = '$add_customer_guid' AND a.isactive = '$update_isactive'");

                $data = array(
                    'module_group_guid' => $module_group_guid,
                    'user_group_guid' => $add_user_group,
                    'user_id' => $add_user_id,
                    'user_name' => $add_user_name,
                    // 'user_password' => md5($add_password),
                    'auto_vendor_code' => $add_mapping_vc,
                    'limited_location' => $add_limited_location,
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $user_id,
                    'isactive' => $process_isactive,
                );

                $process_info_log3 = array(
                    'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                    'customer_guid' => $add_customer_guid,
                    'supplier_guid' => $add_supplier_guid,
                    'user_guid' => $update_user_guid,
                    'old_data' => json_encode($select_all_user->result()),
                    'new_data' => "[" . json_encode($data) . "]",
                    'module' => 'process_info',
                    'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                    'created_by' => $user_id,
                );
                $this->db->insert('lite_b2b.user_account_logs',$process_info_log3);

                $this->db->where('user_guid', $update_user_guid);
                $this->db->where('acc_guid', $add_customer_guid);
                $this->db->where('isactive', $update_isactive);
                $this->db->update('lite_b2b.set_user',$data);

                if($process_isactive == '0')
                {
                    $msg = 'Update Successful.';
                    $valid_process = 'REMOVE';
                }
                else
                {
                    $msg = 'Update Successful. You will proceed to next step.';
                }

                if($link_one != '')
                {
                    $get_link = $link_one;
                }

                $store_user_guid = $update_user_guid;
            }
            else if($action_type == 'duplicate')
            {
                $get_valid_acc = $this->db->query("SELECT d.acc_guid,d.acc_name
                FROM lite_b2b.set_user a
                INNER JOIN lite_b2b.set_user_group b
                ON a.user_group_guid = b.user_group_guid
                INNER JOIN lite_b2b.set_supplier_user_relationship c
                ON a.user_guid = c.user_guid
                AND a.acc_guid = c.customer_guid
                INNER JOIN lite_b2b.acc d
                ON a.acc_guid = d.acc_guid
                WHERE a.user_guid = '$user_guid' 
                AND a.user_group_guid IN ('$valid_admin_guid')
                AND d.acc_guid = '$add_customer_guid'
                GROUP BY a.user_group_guid,a.acc_guid")->result_array();
            
                if(count($get_valid_acc) == 0)
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'Permission Denied.',
                    );    
                    echo json_encode($data);   
                    exit();
                }
                else
                {
                    $check_user = $this->db->query("SELECT e.acc_name,a.supplier_name,d.user_id,d.user_name,d.user_group_guid,d.user_guid,d.user_password,d.limited_location,d.isactive,d.module_group_guid
                    FROM lite_b2b.set_supplier a
                    INNER JOIN lite_b2b.set_supplier_group b
                    ON a.supplier_guid = b.supplier_guid
                    INNER JOIN lite_b2b.set_supplier_user_relationship c
                    ON b.supplier_guid = c.supplier_guid
                    AND b.customer_guid = c.customer_guid
                    INNER JOIN lite_b2b.set_user d
                    ON c.user_guid = d.user_guid
                    AND d.isactive = '1'
                    INNER JOIN lite_b2b.acc e
                    ON d.acc_guid = e.acc_guid
                    AND d.isactive = '1'
                    WHERE a.supplier_guid = '$add_supplier_guid'
                    AND (d.user_id LIKE '%@%' AND d.user_id NOT LIKE '%xbridge%' AND d.user_id NOT LIKE '%pandasoftware%')
                    AND d.user_group_guid NOT IN ('$valid_admin_guid')
                    AND e.acc_guid != '$add_customer_guid'
                    AND d.user_guid = '$add_duplicate_user'
                    GROUP BY d.user_guid LIMIT 1");
            
                    if($check_user->num_rows() == 0)
                    {
                        $data = array(
                            'para1' => 'false',
                            'msg' => 'Data Not Found to Duplicate.',
                        );    
                        echo json_encode($data); 
                        die;  
                    }

                    $duplicate_user_id = $check_user->row('user_id');

                    $check_duplicate_user = $this->db->query("SELECT a.* FROM lite_b2b.set_user a WHERE a.acc_guid ='$add_customer_guid' AND a.user_guid = '$add_duplicate_user' GROUP BY a.user_guid LIMIT 1")->result_array();
            
                    if(count($check_duplicate_user) > 0)
                    {
                        $data = array(
                            'para1' => 'false',
                            'msg' => 'User already exists.',
                        );    
                        echo json_encode($data); 
                        die;
                    }
            
                    $data = array(
                        'acc_guid' => $add_customer_guid,
                        'module_group_guid' => $check_user->row('module_group_guid'),
                        'user_guid' => $add_duplicate_user,
                        'user_group_guid' => $add_user_group,
                        'user_id' => $duplicate_user_id,
                        'user_name' => $check_user->row('user_name'),
                        'user_password' => $check_user->row('user_password'),
                        'auto_vendor_code' => $add_mapping_vc,
                        'limited_location' => $add_limited_location,
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $user_id,
                        // 'supplier_guid' => $supplier_guid,
                        'isactive' => '9',
                    );
                    
                    $this->db->insert('lite_b2b.set_user',$data);
        
                    $get_link = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');
        
                    $data_process = array(
                        // 'module_group_guid' => $module_group_guid,
                        'guid' => $get_link,
                        'user_guid' => $add_duplicate_user,
                        'customer_guid' => $add_customer_guid,
                        'supplier_guid' => $add_supplier_guid,
                        'status' => '0',
                        'action_status' => 'DUPLICATE',
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $user_id,
                        'created_by_guid' => $user_guid,
                    );
                    $this->db->insert('lite_b2b.set_user_process_list',$data_process);

                    $store_user_guid = $add_duplicate_user;
                    $msg = 'Create Successful. You will proceed to next step.';
                }
            }
            else
            {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Invalid Process.',
                );    
                echo json_encode($data);  
                exit(); 
            }

            $error = $this->db->affected_rows();

            //daily notification process here
            foreach($array_data as $row)
            {
                $rep_option_guid = $row->rep_option_guid;
                $notification_type = $row->action_type;

                if($notification_type == 'insert')
                {
                    $check_notification_data = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$add_customer_guid' AND a.user_guid = '$store_user_guid' AND a.rep_option_guid = '$rep_option_guid' AND a.isactive = '1'")->result_array();

                    if(count($check_notification_data) > 0 )
                    {
                        continue;
                    }

                    $check_notification_active = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$add_customer_guid' AND a.user_guid = '$store_user_guid' AND a.rep_option_guid = '$rep_option_guid' AND a.isactive = '0'")->result_array();

                    if(count($check_notification_active) > 0 )
                    {
                        // print_r($rep_option_guid); die;
                        $update_notification = $this->db->query("UPDATE lite_b2b.set_report_query_option_c
                        SET isactive = '1', updated_at = NOW() , updated_by = '$user_id'
                        WHERE customer_guid = '$add_customer_guid' 
                        AND user_guid = '$store_user_guid' 
                        AND rep_option_guid = '$rep_option_guid'
                        AND isactive = '0' ");
                    }
                    else
                    {
                        $data = array(
                            'customer_guid' => $add_customer_guid,
                            'rep_option_guid_c' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                            'rep_option_guid' => $rep_option_guid,
                            'user_guid' => $store_user_guid,
                            'isactive' => '1',
                            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                            'created_by' => $user_id,
                            'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                            'updated_by' => $user_id,
                        );
                        $this->db->insert('lite_b2b.set_report_query_option_c',$data);
                    }
                }
                else if($notification_type == 'delete')
                {
                    $check_notification_data = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$add_customer_guid' AND a.user_guid = '$store_user_guid' AND a.rep_option_guid = '$rep_option_guid'")->result_array();

                    if(count($check_notification_data) == 0 )
                    {
                        continue;
                    }

                    $update_notification = $this->db->query("UPDATE lite_b2b.set_report_query_option_c
                    SET isactive = '0', updated_at = NOW() , updated_by = '$user_id'
                    WHERE customer_guid = '$add_customer_guid' 
                    AND user_guid = '$store_user_guid' 
                    AND rep_option_guid = '$rep_option_guid'
                    AND isactive = '1' ");
                }
            }  

            $check_after_foreach = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$add_customer_guid' AND a.user_guid = '$store_user_guid' AND a.isactive = '1'")->result_array();

            if(count($check_after_foreach) > 0)
            {
                $update_user_notification = $this->db->query("UPDATE lite_b2b.set_user
                SET isnotification = '1'
                WHERE user_guid = '$store_user_guid'
                AND acc_guid = '$add_customer_guid'
                AND isnotification = '0' ");
            }
            else
            {
                $update_user_notification = $this->db->query("UPDATE lite_b2b.set_user
                SET isnotification = '0'
                WHERE user_guid = '$store_user_guid'
                AND acc_guid = '$add_customer_guid'
                AND isnotification = '1' ");
            }
            //end daily notification process

            $query_data_user_group = $this->db->query("SELECT a.user_guid,a.user_group_guid 
            FROM lite_b2b.set_user a 
            INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.user_guid = b.user_guid 
            AND a.acc_guid = b.customer_guid
            INNER JOIN lite_b2b.set_supplier_group c ON b.supplier_guid = c.supplier_guid 
            AND b.customer_guid = c.customer_guid 
            INNER JOIN lite_b2b.set_supplier d 
            ON c.supplier_guid = d.supplier_guid 
            WHERE a.acc_guid = '$add_customer_guid' 
            AND d.supplier_guid = '$add_supplier_guid' 
            AND a.user_group_guid IN ('$valid_admin_guid')
            AND a.isactive IN ('1','9')
            GROUP BY a.user_guid,a.acc_guid")->result_array();

            // print_r(count($query_data_user_group)); die;

            if(count($query_data_user_group) > 1)
            {
                foreach($query_data_user_group as $key => $value)
                {
                    $user_group_user_guid = $value['user_guid'];
                    $old_user_group = $value['user_group_guid'];

                    // print_r($user_group_user_guid); die;

                    if($user_group_user_guid == $store_user_guid)
                    {
                        continue;
                    }

                    // print_r($user_group_user_guid); die;

                    $check_user_group_status = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE user_group_guid = '$add_user_group' AND group_info_status >= '1' AND isactive = '1' AND admin_active = '2' ")->result_array();
    
                    if(count($check_user_group_status) > 0 )
                    {
                        $check_outright_code = $this->db->query("SELECT a.supplier_guid,a.supplier_name,b.supplier_group_name,c.`code`,c.consign 
                        FROM lite_b2b.set_supplier a
                        INNER JOIN lite_b2b.set_supplier_group b
                        ON a.supplier_guid= b.supplier_guid
                        INNER JOIN b2b_summary.supcus c
                        ON b.supplier_group_name = c.`code`
                        AND b.customer_guid = c.customer_guid
                        WHERE a.supplier_guid = '$add_supplier_guid'
                        AND b.customer_guid = '$add_customer_guid'
                        AND c.consign = '0' 
                        GROUP BY a.supplier_guid,b.supplier_group_name")->result_array();

                        if(count($check_outright_code) > 0)
                        {
                            $supp_admin_user_group = $this->db->query("SELECT user_group_guid FROM lite_b2b.set_user_group WHERE group_info_status = '2' AND module_group_guid = '$module_group_guid' AND isactive = '1'")->row('user_group_guid');
        
                            $update_user_group = $this->db->query("UPDATE lite_b2b.set_user
                            SET user_group_guid = '$supp_admin_user_group',
                            updated_at = NOW(), updated_by = '$user_id'
                            WHERE user_guid = '$user_group_user_guid'
                            AND acc_guid = '$add_customer_guid'");
        
                            $log_user_group1 = array(
                                'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                                'customer_guid' => $add_customer_guid,
                                'supplier_guid' => $add_supplier_guid,
                                'user_guid' => $user_group_user_guid,
                                'old_data' => $old_user_group,
                                'new_data' => $supp_admin_user_group,
                                'module' => 'log_user_group',
                                'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                                'created_by' => $user_id,
                            );
                            $this->db->insert('lite_b2b.user_account_logs',$log_user_group1);
                        }
                        else
                        {
                            $check_consign_code = $this->db->query("SELECT a.supplier_guid,a.supplier_name,b.supplier_group_name,c.`code`,c.consign 
                            FROM lite_b2b.set_supplier a
                            INNER JOIN lite_b2b.set_supplier_group b
                            ON a.supplier_guid= b.supplier_guid
                            INNER JOIN b2b_summary.supcus c
                            ON b.supplier_group_name = c.`code`
                            AND b.customer_guid = c.customer_guid
                            WHERE a.supplier_guid = '$add_supplier_guid'
                            AND b.customer_guid = '$add_customer_guid'
                            AND c.consign = '1' 
                            GROUP BY a.supplier_guid,b.supplier_group_name")->result_array();
        
                            if(count($check_consign_code) > 0)
                            {
                                $consign_user_group = $this->db->query("SELECT user_group_guid FROM lite_b2b.set_user_group WHERE group_info_status = '3' AND module_group_guid = '$module_group_guid' AND isactive = '1'")->row('user_group_guid');
            
                                $update_user_group = $this->db->query("UPDATE lite_b2b.set_user
                                SET user_group_guid = '$consign_user_group',
                                updated_at = NOW(), updated_by = '$user_id'
                                WHERE user_guid = '$user_group_user_guid'
                                AND acc_guid = '$add_customer_guid'");
            
                                $log_user_group2 = array(
                                    'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                                    'customer_guid' => $add_customer_guid,
                                    'supplier_guid' => $add_supplier_guid,
                                    'user_guid' => $user_group_user_guid,
                                    'old_data' => $old_user_group,
                                    'new_data' => $update_user_group,
                                    'module' => 'log_user_group',
                                    'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                                    'created_by' => $user_id,
                                );
                                $this->db->insert('lite_b2b.user_account_logs',$log_user_group2);
                            }
                        }
                    }
                }
            }

            if($error > 0){
                
                if($valid_process == 'YES')
                {
                    $get_link = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');

                    $data_process = array(
                        // 'module_group_guid' => $module_group_guid,
                        'guid' => $get_link,
                        'user_guid' => $process_user_guid,
                        'customer_guid' => $add_customer_guid,
                        'supplier_guid' => $add_supplier_guid,
                        'status' => '0',
                        'action_status' => strtoupper($action_type),
                        'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'created_by' => $user_id,
                        'created_by_guid' => $user_guid,
                    );
                    $this->db->insert('lite_b2b.set_user_process_list',$data_process);
                }
                else if($valid_process == 'REMOVE')
                {
                    $check_data_user = $this->db->query("SELECT a.*
                    FROM lite_b2b.set_user a
                    WHERE a.user_guid = '$store_user_guid'
                    AND a.acc_guid = '$add_customer_guid'
                    ")->result_array();

                    if(count($check_data_user) > 0 )
                    {
                        $get_pending_creation = $this->db->query("SELECT a.* FROM lite_b2b.set_user_process_list a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.guid = '$get_link' GROUP BY a.guid")->result_array();
    
                        if(count($get_pending_creation) > 0)
                        {
                            $delete_pending = $this->db->query("DELETE FROM lite_b2b.set_user_process_list WHERE `guid` = '$get_link' ");
                        }
                    }
                }

                $data = array(
                   'para1' => 'true',
                   'msg' => $msg,
                   'get_link' => $get_link,
                   'valid_process' => $valid_process,
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {   
                $data = array(
                'para1' => 'false',
                'msg' => 'Error Process.',
                );    
                echo json_encode($data);  
                exit(); 
            }
        } 
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function mapping_information()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login() && in_array('VUAMT', $_SESSION['module_code'])) 
        {
            $flag_show_tab = '';
            $tab_1 = '';
            $tab_3 = '';
            $tab_summary = '';

            $link_one = $_REQUEST['link_one'];
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0'; // Panda B2B Module Group
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $get_pending_creation = $this->db->query("SELECT a.* FROM lite_b2b.set_user_process_list a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.guid = '$link_one' GROUP BY a.guid")->result_array();

            if(count($get_pending_creation) == 0 )
            {
                echo '<script>alert("Invalid URL.");window.location.href = "'.site_url('User_account_setting').'";</script>;';
                die;
            }

            $process_user_guid = $get_pending_creation[0]['user_guid'];
            $process_customer_guid = $get_pending_creation[0]['customer_guid'];
            $process_supplier_guid = $get_pending_creation[0]['supplier_guid'];
            $process_action_status = $get_pending_creation[0]['action_status'];

            $get_user = $this->db->query("SELECT a.* FROM lite_b2b.set_user a WHERE a.user_guid = '$process_user_guid' AND a.acc_guid = '$process_customer_guid' GROUP BY a.user_guid,a.acc_guid LIMIT 1");

            $user_active_status = $get_user->row('isactive');

            if($user_active_status == '1' || $user_active_status == '0')
            {
                $flag_show_tab = 'show_tab';
                $tab_1 = site_url('User_account_setting/information?link_one='.$link_one);
                $tab_3 = site_url('User_account_setting/create_outlet?link_one='.$link_one);
                $tab_summary = site_url('User_account_setting/final_summary?link_one='.$link_one);
            }

            // $check_user_group = $this->db->query("SELECT b.user_group_name,a.user_group_guid,a.user_guid,a.acc_guid 
            // FROM lite_b2b.set_user a
            // INNER JOIN lite_b2b.set_user_group b
            // ON a.user_group_guid = b.user_group_guid
            // INNER JOIN lite_b2b.set_supplier_user_relationship c
            // ON a.user_guid = c.user_guid
            // AND a.acc_guid = c.customer_guid
            // WHERE a.user_guid = '$user_guid' 
        
            // GROUP BY a.user_group_guid,a.acc_guid")->result_array();

            // // print_r($check_user_group); die;

            // if(count($check_user_group) > 0 )
            // {
            //     $valid_customer_guid = implode("','",array_filter(array_column($check_user_group,'acc_guid')));
            // }
            // else
            // {
            //     $valid_customer_guid = '';
            // }

            $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' AND a.acc_guid IN ('$process_customer_guid') ORDER BY a.acc_name ASC");
        
            // $get_supplier = $this->db->query("SELECT d.supplier_guid,d.supplier_name FROM 
            // lite_b2b.set_user a
            // INNER JOIN lite_b2b.set_supplier_user_relationship b
            // ON a.user_guid = b.user_guid
            // INNER JOIN lite_b2b.set_supplier_group c
            // ON b.supplier_guid = c.supplier_guid
            // AND b.customer_guid = c.customer_guid
            // INNER JOIN lite_b2b.set_supplier d
            // ON c.supplier_guid = d.supplier_guid
            // WHERE a.user_guid = '$user_guid' 
            // AND c.customer_guid = '$process_customer_guid'
            // AND d.supplier_guid = '$process_supplier_guid'
            // GROUP BY d.supplier_guid");

            $get_supplier = $this->db->query("SELECT a.supplier_guid,a.supplier_name 
            FROM lite_b2b.set_supplier a
            WHERE a.supplier_guid = '$process_supplier_guid'
            AND a.isactive = '1'
            GROUP BY a.supplier_guid");

            // echo $this->db->last_query(); die;
            
            $total_mapping_outlet = $this->db->query("SELECT a.branch_guid AS branch_guid FROM lite_b2b.acc_branch AS a INNER JOIN lite_b2b.acc_concept AS b ON a.`concept_guid` = b.`concept_guid` INNER JOIN lite_b2b.acc_branch_group c ON c.`branch_group_guid` = a.`branch_group_guid` INNER JOIN b2b_summary.cp_set_branch d ON a.branch_code = d.branch_code AND b.acc_guid = d.customer_guid WHERE a.isactive = 1 AND b.acc_guid = '$process_customer_guid' GROUP BY a.`branch_code`")->result_array();

            $total_mapped_outlet = $this->db->query("SELECT 
            c.`branch_guid`
            FROM lite_b2b.set_user a 
            INNER JOIN lite_b2b.set_user_branch b 
            ON a.user_guid = b.user_guid 
            AND b.acc_guid = '$process_customer_guid'
            INNER JOIN lite_b2b.acc_branch c 
            ON b.`branch_guid` = c.`branch_guid` 
            AND c.`isactive` = '1' 
            INNER JOIN lite_b2b.set_user_group d 
            ON a.`user_group_guid` = d.`user_group_guid`
            INNER JOIN b2b_summary.cp_set_branch e 
            ON c.branch_code = e.BRANCH_CODE
            AND e.customer_guid = '$process_customer_guid'
            INNER JOIN lite_b2b.acc f
            ON a.acc_guid = f.acc_guid
            WHERE a.`user_guid` = '$process_user_guid' 
            AND a.module_group_guid = '$module_group_guid'
            AND a.acc_guid = '$process_customer_guid' 
            GROUP BY c.branch_code            
            ")->result_array();

            $get_mapped_outlet = implode("','",array_filter(array_column($total_mapped_outlet,'branch_guid')));

            $get_mapping_outlet = $this->db->query("SELECT 
            aa.branch_guid, 
            aa.branch_desc,
            aa.branch_code, 
            aa.selected 
            FROM 
                (
                SELECT 
                    b.`branch_guid`, 
                    e.branch_desc,
                    b.branch_code, 
                    '1' AS selected 
                FROM 
                    lite_b2b.set_user a 
                    INNER JOIN lite_b2b.set_user_branch d ON a.user_guid = d.user_guid 
                    INNER JOIN lite_b2b.acc_branch b ON d.`branch_guid` = b.`branch_guid` 
                    AND b.`isactive` = '1' 
                    INNER JOIN lite_b2b.set_user_group c ON a.`user_group_guid` = c.`user_group_guid` 
                    LEFT JOIN b2b_summary.cp_set_branch e ON b.branch_code = e.branch_code 
                    AND a.acc_guid = e.customer_guid 
                WHERE 
                    a.`user_guid` = '$process_user_guid' 
                    AND a.module_group_guid = '$module_group_guid' 
                    AND a.acc_guid = '$process_customer_guid' 
                    AND d.acc_guid = '$process_customer_guid' 
                GROUP BY 
                    b.branch_guid 
                UNION ALL 
                SELECT 
                    a.branch_guid AS branch_guid, 
                    d.`branch_desc`,
                    a.branch_code AS branch_code, 
                    '0' AS selected 
                FROM 
                    lite_b2b.acc_branch AS a 
                    INNER JOIN lite_b2b.acc_concept AS b ON a.`concept_guid` = b.`concept_guid` 
                    INNER JOIN lite_b2b.acc_branch_group c ON c.`branch_group_guid` = a.`branch_group_guid` 
                    INNER JOIN b2b_summary.cp_set_branch d
                    ON a.branch_code = d.branch_code 
                    AND b.acc_guid = d.customer_guid 
                WHERE 
                    a.isactive = 1 
                    AND b.acc_guid = '$process_customer_guid' 
                GROUP BY 
                    a.`branch_guid`
                ) aa 
            WHERE aa.branch_guid NOT IN ('$get_mapped_outlet')
            GROUP BY 
                aa.branch_code
            ");

            $get_registered_count = $this->db->query("SELECT aa.acc_guid,aa.acc_name,COUNT(aa.user_name) AS count_data
            FROM
            ( SELECT e.acc_guid,e.acc_name,d.supplier_guid,d.supplier_name,a.user_name 
            FROM 
            lite_b2b.set_user a
            INNER JOIN lite_b2b.set_supplier_user_relationship b
            ON a.user_guid = b.user_guid
            INNER JOIN lite_b2b.set_supplier_group c
            ON b.supplier_guid = c.supplier_guid
            AND b.customer_guid = c.customer_guid
            INNER JOIN lite_b2b.set_supplier d
            ON c.supplier_guid = d.supplier_guid
            INNER JOIN lite_b2b.acc e
            ON b.customer_guid = e.acc_guid
            WHERE a.acc_guid = '$process_customer_guid'
            AND d.supplier_guid = '$process_supplier_guid' 
            AND a.isactive != '9'
            AND a.hide_admin = '0'
            GROUP BY a.user_id,a.acc_guid ) aa
            GROUP BY aa.acc_name")->row('count_data');
            // echo $this->db->last_query();die;

            $data = array(
                'get_supplier' => $get_supplier->result(),
                'get_mapping_outlet' => $get_mapping_outlet->result(),
                'total_mapping_outlet' => count($total_mapping_outlet),
                'total_mapped_outlet' => count($total_mapped_outlet),
                'acc_name' => $get_acc->row('acc_name'),
                'user_name' => $get_user->row('user_name'),
                'user_id' => $get_user->row('user_id'),
                'process_customer_guid' => $process_customer_guid,
                'process_user_guid' => $process_user_guid,
                'process_action_status' => $process_action_status,
                'get_registered_count' => $get_registered_count,
                'link_one' => $link_one,
                'flag_show_tab' => $flag_show_tab,
                'user_active_status' => $user_active_status,
                'tab_1' => $tab_1,
                'tab_3' => $tab_3,
                'tab_summary' => $tab_summary,
            );

            $this->load->view('header');
            $this->load->view('user_account/user_create_mapping', $data);
            $this->load->view('footer');

        } 
        else 
        {
            $this->session->set_flashdata('message', 'You have not rights to access.');
            redirect('dashboard');
        }
    }
    
    public function list_code_tb()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $link_one = $this->input->post('link_one');
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $get_pending_creation = $this->db->query("SELECT a.* FROM lite_b2b.set_user_process_list a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.guid = '$link_one' GROUP BY a.guid")->result_array();

            if(count($get_pending_creation) == 0 )
            {
                echo '<script>alert("Invalid URL.");window.location.href = "'.site_url('User_account_setting').'";</script>;';
                die;
            }

            $process_user_guid = $get_pending_creation[0]['user_guid'];
            $process_customer_guid = $get_pending_creation[0]['customer_guid'];
            $process_supplier_guid = $get_pending_creation[0]['supplier_guid'];

            if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
            {
                $get_supplier = $this->db->query("SELECT d.supplier_guid,d.supplier_name FROM 
                lite_b2b.set_user a
                INNER JOIN lite_b2b.set_supplier_user_relationship b
                ON a.user_guid = b.user_guid
                INNER JOIN lite_b2b.set_supplier_group c
                ON b.supplier_guid = c.supplier_guid
                AND b.customer_guid = c.customer_guid
                INNER JOIN lite_b2b.set_supplier d
                ON c.supplier_guid = d.supplier_guid
                WHERE c.customer_guid = '$process_customer_guid'
                AND d.supplier_guid= '$process_supplier_guid'
                GROUP BY d.supplier_guid")->result_array();
            }
            else
            {
                $get_supplier = $this->db->query("SELECT d.supplier_guid,d.supplier_name FROM 
                lite_b2b.set_user a
                INNER JOIN lite_b2b.set_supplier_user_relationship b
                ON a.user_guid = b.user_guid
                INNER JOIN lite_b2b.set_supplier_group c
                ON b.supplier_guid = c.supplier_guid
                AND b.customer_guid = c.customer_guid
                INNER JOIN lite_b2b.set_supplier d
                ON c.supplier_guid = d.supplier_guid
                WHERE a.user_guid = '$user_guid' 
                AND c.customer_guid = '$process_customer_guid'
                AND d.supplier_guid= '$process_supplier_guid'
                GROUP BY d.supplier_guid")->result_array();
            }

            $get_supplier_guid = implode("','",array_filter(array_column($get_supplier,'supplier_guid')));

            $query_data = $this->db->query("SELECT f.acc_guid,f.acc_name, d.supplier_guid, d.supplier_name, c.supplier_group_guid, c.supplier_group_name, a.user_guid, a.user_id, a.user_name, e.name AS supcus_name, 
            b.created_at, b.created_by 
            FROM lite_b2b.set_user a
            INNER JOIN lite_b2b.set_supplier_user_relationship b
            ON a.user_guid = b.user_guid 
            AND a.acc_guid = b.customer_guid
            INNER JOIN lite_b2b.set_supplier_group c
            ON b.supplier_group_guid = c.supplier_group_guid
            AND b.customer_guid = c.customer_guid
            INNER JOIN lite_b2b.set_supplier d
            ON c.supplier_guid = d.supplier_guid
            INNER JOIN b2b_summary.supcus e 
            ON c.supplier_group_name = e.code 
            AND c.customer_guid = e.customer_guid 
            INNER JOIN lite_b2b.acc f 
            ON b.customer_guid = f.acc_guid 
            WHERE b.supplier_guid IN ('$get_supplier_guid')
            AND a.user_guid = '$process_user_guid'
            AND a.acc_guid IN ('$process_customer_guid')
            GROUP BY b.supplier_group_guid");

            // echo $this->db->last_query(); die;

            $data = array(  
                'data' => $query_data->result(), 
            );

            echo json_encode($data);

        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function fetch_assign_selection()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $type_val = $this->input->post('type_val');
            $link_one = $this->input->post('link_one');

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $get_pending_creation = $this->db->query("SELECT a.* FROM lite_b2b.set_user_process_list a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.guid = '$link_one' GROUP BY a.guid")->result_array();

            if(count($get_pending_creation) == 0 )
            {
                echo '<script>alert("Invalid URL.");window.location.href = "'.site_url('User_account_setting').'";</script>;';
                die;
            }

            $process_user_guid = $get_pending_creation[0]['user_guid'];
            $process_customer_guid = $get_pending_creation[0]['customer_guid'];

            $query_selected_mapping_code = $this->db->query("SELECT c.supplier_group_guid,c.supplier_group_name
            FROM lite_b2b.set_user a
            INNER JOIN lite_b2b.set_supplier_user_relationship b
            ON a.user_guid = b.user_guid 
            AND a.acc_guid = b.customer_guid
            INNER JOIN lite_b2b.set_supplier_group c
            ON b.supplier_group_guid = c.supplier_group_guid
            AND b.customer_guid = c.customer_guid
            INNER JOIN lite_b2b.set_supplier d
            ON c.supplier_guid = d.supplier_guid
            INNER JOIN b2b_summary.supcus e 
            ON c.supplier_group_name = e.code 
            AND c.customer_guid = e.customer_guid 
            INNER JOIN lite_b2b.acc f 
            ON b.customer_guid = f.acc_guid 
            WHERE b.supplier_guid = '$type_val'
            AND a.user_guid = '$process_user_guid'
            AND a.acc_guid = '$process_customer_guid'
            GROUP BY b.supplier_group_guid")->result_array();

            $get_selected_mapping_guid = implode("','",array_filter(array_column($query_selected_mapping_code,'supplier_group_guid')));

            $get_selected_mapping_code = implode("','",array_filter(array_column($query_selected_mapping_code,'supplier_group_name')));

            $query_data_count = $this->db->query("SELECT 
            a.supplier_group_guid,
            a.supplier_group_name, 
            b.name AS supcus_name 
            FROM 
            lite_b2b.set_supplier_group a 
            INNER JOIN b2b_summary.supcus b 
            ON a.supplier_group_name = b.code 
            AND a.customer_guid = b.customer_guid 
            INNER JOIN lite_b2b.acc c 
            ON b.customer_guid = c.acc_guid 
            WHERE a.supplier_guid = '$type_val' 
            AND a.customer_guid = '$process_customer_guid'
            GROUP BY a.supplier_group_name")->result_array();

            $query_data = $this->db->query("SELECT 
            a.supplier_group_guid,
            a.supplier_group_name, 
            b.name AS supcus_name 
            FROM 
            lite_b2b.set_supplier_group a 
            INNER JOIN b2b_summary.supcus b 
            ON a.supplier_group_name = b.code 
            AND a.customer_guid = b.customer_guid 
            INNER JOIN lite_b2b.acc c 
            ON b.customer_guid = c.acc_guid 
            WHERE a.supplier_guid = '$type_val' 
            AND a.supplier_group_guid NOT IN ('$get_selected_mapping_guid')
            AND a.supplier_group_name NOT IN ('$get_selected_mapping_code')
            AND a.customer_guid = '$process_customer_guid'
            GROUP BY a.supplier_group_name");

            // echo $this->db->last_query(); die;

            $result = array(
                'data' => $query_data->result(),
                'data_count' => count($query_data_count),
                'data_mapped_count' => count($query_selected_mapping_code),
            );
        
            echo json_encode($result);
        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function process_code()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
  
            $link_one = $this->input->post('link_one');
            $add_supplier_guid = $this->input->post('add_supplier_guid'); 
            $assign_value = $this->input->post('vendor_assign_value');

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $get_pending_creation = $this->db->query("SELECT a.* FROM lite_b2b.set_user_process_list a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.guid = '$link_one' GROUP BY a.guid")->result_array();

            if(count($get_pending_creation) == 0 )
            {
                echo '<script>alert("Invalid URL.");window.location.href = "'.site_url('User_account_setting').'";</script>;';
                die;
            }

            $process_user_guid = $get_pending_creation[0]['user_guid'];
            $process_customer_guid = $get_pending_creation[0]['customer_guid'];

            $count_value = count($assign_value);

            $array_data = json_decode(json_encode($assign_value));

            $i = 0;

            foreach($array_data as $row)
            {
                $supplier_group_guid = $row;

                $check_data_relationship = $this->db->query("SELECT a.* FROM lite_b2b.set_supplier_user_relationship a WHERE a.supplier_group_guid = '$supplier_group_guid' AND a.customer_guid = '$customer_guid' AND a.supplier_guid = '$add_supplier_guid' AND a.user_guid = '$add_user_guid' ")->result_array();

                if(count($check_data_relationship) > 0 )
                {
                    continue;
                }

                $data_code = array(
                    //if b2b, acc_guid will be using session customer_guid
                    'customer_guid' => $process_customer_guid,
                    'supplier_guid' => $add_supplier_guid,
                    'supplier_group_guid' => $supplier_group_guid,
                    'user_guid' => $process_user_guid,
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $user_id,
                );
                $this->db->insert('lite_b2b.set_supplier_user_relationship',$data_code);

                $error = $this->db->affected_rows();

                if($error > 0)
                {
                    $i++;
                }
                
            }

            if($count_value == $i)
            {
                $data = array(
                   'para1' => 'True',
                   'msg' => 'Success Mapped Code Total : ' .$i. ' / ' .$count_value,
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {   
                $data = array(
                'para1' => 'false',
                'msg' => 'Success Mapped Code Total : ' .$i. ' / ' .$count_value,
                );    
                echo json_encode($data);  
                exit(); 
            }
        } 
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function remove_process_code()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
  
            $details = $this->input->post('details');

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $count_value = count($details);

            $array_data = json_decode(json_encode($details));

            $i = 0;

            foreach($array_data as $row)
            {
		        $delete_supplier_group_guid = $row->d_supplier_group_guid;
		        $delete_user_guid = $row->d_user_guid;
                $delete_supplier_guid = $row->d_supplier_guid;
                $delete_customer_guid = $row->d_acc_guid;

                $check_data_relationship = $this->db->query("SELECT a.* FROM lite_b2b.set_supplier_user_relationship a WHERE a.supplier_group_guid = '$delete_supplier_group_guid' AND a.customer_guid = '$delete_customer_guid' AND a.supplier_guid = '$delete_supplier_guid' AND a.user_guid = '$delete_user_guid' ")->result_array();

                if(count($check_data_relationship) == 0 )
                {
                    continue;
                }

                $delete_relationship = $this->db->query("DELETE FROM lite_b2b.set_supplier_user_relationship WHERE supplier_group_guid = '$delete_supplier_group_guid' AND customer_guid = '$delete_customer_guid' AND supplier_guid = '$delete_supplier_guid' AND user_guid = '$delete_user_guid' ");
		
                $error = $this->db->affected_rows();

                if($error > 0)
                {
                    $mapping_code_log1 = array(
                        'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                        'customer_guid' => $delete_customer_guid,
                        'supplier_guid' => $delete_supplier_guid,
                        'user_guid' => $delete_user_guid,
                        'old_data' => $delete_supplier_group_guid,
                        'new_data' => '',
                        'module' => 'mapping_code',
                        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                        'created_by' => $user_id,
                    );
                    $this->db->insert('lite_b2b.user_account_logs',$mapping_code_log1);

                    $i++;
                }
                
            }

            if($count_value == $i)
            {
                $data = array(
                   'para1' => 'True',
                   'msg' => 'Deleted Mapped Code Total : ' .$i. ' / ' .$count_value,
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {   
                $data = array(
                'para1' => 'false',
                'msg' => 'Deleted Mapped Code Total : ' .$i. ' / ' .$count_value,
                );    
                echo json_encode($data);  
                exit(); 
            }
        } 
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function process_outlet()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
   
            $process_user_guid = $this->input->post('process_user_guid');
            $process_customer_guid = $this->input->post('process_customer_guid');
            $assign_value = $this->input->post('outlet_assign_value');

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $count_value = count($assign_value);

            $array_data = json_decode(json_encode($assign_value));

            $i = 0;

            foreach($array_data as $row)
            {
                $branch_guid = $row;

                $check_data_branch = $this->db->query("SELECT a.*
                FROM lite_b2b.set_user_branch a
                WHERE a.user_guid = '$process_user_guid'
                AND a.acc_guid = '$process_customer_guid'
                AND a.branch_guid = '$branch_guid' ")->result_array();

                if(count($check_data_branch) > 0 )
                {
                    continue;
                }

                $data_outlet = array(
                    'acc_guid' => $process_customer_guid,
                    'branch_guid' => $branch_guid,
                    'user_guid' => $process_user_guid,
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $user_id,
                );
                $this->db->insert('lite_b2b.set_user_branch',$data_outlet);

                $error = $this->db->affected_rows();

                if($error > 0)
                {
                    $i++;
                }
                
            }

            if($count_value == $i)
            {
                $data = array(
                   'para1' => 'True',
                   'msg' => 'Success Mapped Outlet Total : ' .$i. ' / ' .$count_value,
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {   
                $data = array(
                'para1' => 'false',
                'msg' => 'Success Mapped Outlet Total : ' .$i. ' / ' .$count_value,
                );    
                echo json_encode($data);  
                exit(); 
            }
        } 
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function remove_process_outlet()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
  
            $details = $this->input->post('details');

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $count_value = count($details);

            $array_data = json_decode(json_encode($details));

            $i = 0;

            foreach($array_data as $row)
            {
		        $delete_branch_guid = $row->d_branch_guid;
		        $delete_user_guid = $row->d_user_guid;
                $delete_customer_guid = $row->d_acc_guid;

                $check_data_branch = $this->db->query("SELECT a.*
                FROM lite_b2b.set_user_branch a
                WHERE a.user_guid = '$delete_user_guid'
                AND a.acc_guid = '$delete_customer_guid'
                AND a.branch_guid = '$delete_branch_guid' ")->result_array();

                if(count($check_data_branch) == 0 )
                {
                    continue;
                }

                $delete_relationship = $this->db->query("DELETE FROM lite_b2b.set_user_branch WHERE user_guid = '$delete_user_guid'
                AND acc_guid = '$delete_customer_guid'
                AND branch_guid = '$delete_branch_guid' ");
		
                $error = $this->db->affected_rows();

                if($error > 0)
                {
                    $mapping_outlet_log1 = array(
                        'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                        'customer_guid' => $delete_customer_guid,
                        'supplier_guid' => '',
                        'user_guid' => $delete_user_guid,
                        'old_data' => $delete_branch_guid,
                        'new_data' => '',
                        'module' => 'mapping_outlet',
                        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                        'created_by' => $user_id,
                    );
                    $this->db->insert('lite_b2b.user_account_logs',$mapping_outlet_log1);

                    $i++;
                }
                
            }

            if($count_value == $i)
            {
                $data = array(
                   'para1' => 'True',
                   'msg' => 'Deleted Mapped Outlet Total : ' .$i. ' / ' .$count_value,
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {   
                $data = array(
                'para1' => 'false',
                'msg' => 'Deleted Mapped Outlet Total : ' .$i. ' / ' .$count_value,
                );    
                echo json_encode($data);  
                exit(); 
            }
        } 
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function final_summary()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login() && in_array('VUAMT', $_SESSION['module_code'])) 
        {
            $link_one = $_REQUEST['link_one'];
            $flag_show_tab = '';
            $tab_1 = '';
            $tab_2 = '';
            $tab_summary = '';

            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     $user_guid = 'BB61A6F38AFB11E887B5000D3AA2838A';
            // }

            $get_pending_creation = $this->db->query("SELECT a.* FROM lite_b2b.set_user_process_list a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.guid = '$link_one' GROUP BY a.guid")->result_array();

            if(count($get_pending_creation) == 0 )
            {
                echo '<script>alert("Invalid URL.");window.location.href = "'.site_url('User_account_setting').'";</script>;';
                die;
            }

            $process_user_guid = $get_pending_creation[0]['user_guid'];
            $process_customer_guid = $get_pending_creation[0]['customer_guid'];
            $process_supplier_guid = $get_pending_creation[0]['supplier_guid'];
            $process_action_status = $get_pending_creation[0]['action_status'];

            $get_user = $this->db->query("SELECT a.* FROM lite_b2b.set_user a WHERE a.user_guid = '$process_user_guid' AND a.acc_guid = '$process_customer_guid' GROUP BY a.user_guid,a.acc_guid LIMIT 1");

            $user_active_status = $get_user->row('isactive');

            if($user_active_status == '1' || $user_active_status == '0')
            {
                $flag_show_tab = 'show_tab';
                $tab_1 = site_url('User_account_setting/information?link_one='.$link_one);
                $tab_2 = site_url('User_account_setting/mapping_information?link_one='.$link_one);
                $tab_3 = site_url('User_account_setting/create_outlet?link_one='.$link_one);
            }

            $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' AND a.acc_guid = '$customer_guid' ORDER BY a.acc_name ASC");

            $get_registered_count = $this->db->query("SELECT aa.acc_guid,aa.acc_name,COUNT(aa.user_name) AS count_data
            FROM
            ( SELECT e.acc_guid,e.acc_name,d.supplier_guid,d.supplier_name,a.user_name 
            FROM 
            lite_b2b.set_user a
            INNER JOIN lite_b2b.set_supplier_user_relationship b
            ON a.user_guid = b.user_guid
            INNER JOIN lite_b2b.set_supplier_group c
            ON b.supplier_guid = c.supplier_guid
            AND b.customer_guid = c.customer_guid
            INNER JOIN lite_b2b.set_supplier d
            ON c.supplier_guid = d.supplier_guid
            INNER JOIN lite_b2b.acc e
            ON b.customer_guid = e.acc_guid
            WHERE a.acc_guid = '$process_customer_guid'
            AND d.supplier_guid = '$process_supplier_guid' 
            AND a.isactive != '9'
            AND a.hide_admin = '0'
            GROUP BY a.user_id,a.acc_guid ) aa
            GROUP BY aa.acc_name")->row('count_data');

            $data = array(
                'acc_name' => $get_acc->row('acc_name'),
                'link_one' => $link_one,
                'process_customer_guid' => $process_customer_guid,
                'process_user_guid' => $process_user_guid,
                'process_supplier_guid' => $process_supplier_guid,
                'process_action_status' => $process_action_status,
                'flag_show_tab' => $flag_show_tab,
                'user_active_status' => $user_active_status,
                'get_registered_count' => $get_registered_count,
                'tab_1' => $tab_1,
                'tab_2' => $tab_2,
                'tab_3' => $tab_3,
            );

            $this->load->view('header');
            $this->load->view('user_account/user_create_summary', $data);
            $this->load->view('footer');

        } 
        else 
        {
            $this->session->set_flashdata('message', 'You have not rights to access.');
            redirect('dashboard');
        }
    }

    public function summary_info_tb()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $link_one = $this->input->post('link_one');
            $process_user_guid = $this->input->post('process_user_guid');
            $process_customer_guid = $this->input->post('process_customer_guid');
            $customer_guid = $_SESSION['customer_guid'];

            $query_data = $this->db->query("SELECT a.user_guid,
            a.user_id,
            a.user_name,
            a.isactive,
            b.user_group_name,
            d.acc_name
            FROM lite_b2b.set_user a
            INNER JOIN lite_b2b.set_user_group b
            ON a.user_group_guid = b.user_group_guid
            INNER JOIN lite_b2b.set_supplier_user_relationship c
            ON a.user_guid = c.user_guid
            AND a.acc_guid = c.customer_guid
            INNER JOIN lite_b2b.acc d
            ON c.customer_guid = d.acc_guid
            WHERE a.user_guid = '$process_user_guid' 
            AND c.customer_guid = '$process_customer_guid'
            GROUP BY a.user_guid,a.acc_guid");

            // echo $this->db->last_query(); die;

            $data = array(  
                'data' => $query_data->result(), 
            );

            echo json_encode($data);

        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function final_process()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
   
            $link_one= $this->input->post('link_one');
            $process_user_guid = $this->input->post('process_user_guid');
            $process_customer_guid = $this->input->post('process_customer_guid');
            $process_supplier_guid = $this->input->post('process_supplier_guid');
            $process_action_status = $this->input->post('process_action_status');
            $admin_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status >= '1' AND isactive = '1' AND admin_active = '2'")->result_array();
            $valid_admin_guid = implode("','",array_filter(array_column($admin_guid,'user_group_guid')));
            $isactive = '9';
            // print_r($process_action_status); die;

            if($process_action_status == 'EDIT')
            {
                $check_data_user = $this->db->query("SELECT a.*
                FROM lite_b2b.set_user a
                WHERE a.user_guid = '$process_user_guid'
                AND a.acc_guid = '$process_customer_guid'
                ")->result_array();

                if(count($check_data_user) > 0 )
                {
                    $get_pending_creation = $this->db->query("SELECT a.* FROM lite_b2b.set_user_process_list a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.guid = '$link_one' GROUP BY a.guid")->result_array();

                    if(count($get_pending_creation) > 0)
                    {
                        $delete_pending = $this->db->query("DELETE FROM lite_b2b.set_user_process_list WHERE `guid` = '$link_one' AND action_status = '$process_action_status' ");
                    }
                }

                $data = array(
                    'para1' => 'True',
                    'msg' => 'Update Successful.',
                );    
                echo json_encode($data);   
                exit();

            }
            else if($process_action_status == 'PROCESS')
            {
                $check_data_user = $this->db->query("SELECT a.*
                FROM lite_b2b.set_user a
                WHERE a.user_guid = '$process_user_guid'
                AND a.acc_guid = '$process_customer_guid'
                AND a.isactive = '$isactive' ")->result_array();
    
                $process_user_group_guid = $check_data_user[0]['user_group_guid'];
                // echo $this->db->last_query();die;
    
                if(count($check_data_user) == 0 )
                {
                    $data = array(
                        'para1' => 'false',
                        'msg' => 'Data Not Found',
                    );    
                    echo json_encode($data);  
                    exit(); 
                }
    
                $data_user = array(
                    'isactive' => '1',
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $user_id,
                );

                $this->db->where('user_guid', $process_user_guid);
                $this->db->where('acc_guid', $process_customer_guid);
                $this->db->where('isactive', $isactive);
                $this->db->update('lite_b2b.set_user',$data_user);
    
                $error = $this->db->affected_rows();
    
                if($error > 0)
                {
                    // check duplicate admin user group 
                    $query_data_user_group = $this->db->query("SELECT a.user_guid,a.user_group_guid 
                    FROM lite_b2b.set_user a 
                    INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.user_guid = b.user_guid 
                    AND a.acc_guid = b.customer_guid
                    INNER JOIN lite_b2b.set_supplier_group c ON b.supplier_guid = c.supplier_guid 
                    AND b.customer_guid = c.customer_guid 
                    INNER JOIN lite_b2b.set_supplier d 
                    ON c.supplier_guid = d.supplier_guid 
                    WHERE a.acc_guid = '$process_customer_guid' 
                    AND d.supplier_guid = '$process_supplier_guid' 
                    AND a.user_group_guid IN ('$valid_admin_guid')
                    AND a.isactive IN ('1','9')
                    GROUP BY a.user_guid,a.acc_guid")->result_array();

                    // print_r(count($query_data_user_group)); die;

                    if(count($query_data_user_group) > 1)
                    {
                        foreach($query_data_user_group as $key => $value)
                        {
                            $user_group_user_guid = $value['user_guid'];
                            $old_user_group = $value['user_group_guid'];

                            // print_r($user_group_user_guid); die;

                            if($user_group_user_guid == $process_user_guid)
                            {
                                continue;
                            }

                            // print_r($user_group_user_guid); die;

                            $check_user_group_status = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE user_group_guid = '$process_user_group_guid' AND group_info_status >= '1' AND isactive = '1' AND admin_active = '2' ")->result_array();

                            if(count($check_user_group_status) > 0 )
                            {
                                $check_outright_code = $this->db->query("SELECT a.supplier_guid,a.supplier_name,b.supplier_group_name,c.`code`,c.consign 
                                FROM lite_b2b.set_supplier a
                                INNER JOIN lite_b2b.set_supplier_group b
                                ON a.supplier_guid= b.supplier_guid
                                INNER JOIN b2b_summary.supcus c
                                ON b.supplier_group_name = c.`code`
                                AND b.customer_guid = c.customer_guid
                                WHERE a.supplier_guid = '$process_supplier_guid'
                                AND b.customer_guid = '$process_customer_guid'
                                AND c.consign = '0' 
                                GROUP BY a.supplier_guid,b.supplier_group_name")->result_array();

                                if(count($check_outright_code) > 0)
                                {
                                    $supp_admin_user_group = $this->db->query("SELECT user_group_guid FROM lite_b2b.set_user_group WHERE group_info_status = '2' AND module_group_guid = '$module_group_guid' AND isactive = '1'")->row('user_group_guid');

                                    $update_user_group = $this->db->query("UPDATE lite_b2b.set_user
                                    SET user_group_guid = '$supp_admin_user_group',
                                    updated_at = NOW(), updated_by = '$user_id'
                                    WHERE user_guid = '$user_group_user_guid'
                                    AND acc_guid = '$process_customer_guid'");

                                    $log_user_group1 = array(
                                        'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                                        'customer_guid' => $process_customer_guid,
                                        'supplier_guid' => $process_supplier_guid,
                                        'user_guid' => $user_group_user_guid,
                                        'old_data' => $old_user_group,
                                        'new_data' => $supp_admin_user_group,
                                        'module' => 'log_user_group',
                                        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                                        'created_by' => $user_id,
                                    );
                                    $this->db->insert('lite_b2b.user_account_logs',$log_user_group1);
                                }
                                else
                                {
                                    $check_consign_code = $this->db->query("SELECT a.supplier_guid,a.supplier_name,b.supplier_group_name,c.`code`,c.consign 
                                    FROM lite_b2b.set_supplier a
                                    INNER JOIN lite_b2b.set_supplier_group b
                                    ON a.supplier_guid= b.supplier_guid
                                    INNER JOIN b2b_summary.supcus c
                                    ON b.supplier_group_name = c.`code`
                                    AND b.customer_guid = c.customer_guid
                                    WHERE a.supplier_guid = '$process_supplier_guid'
                                    AND b.customer_guid = '$process_customer_guid'
                                    AND c.consign = '1' 
                                    GROUP BY a.supplier_guid,b.supplier_group_name")->result_array();

                                    if(count($check_consign_code) > 0)
                                    {
                                        $consign_user_group = $this->db->query("SELECT user_group_guid FROM lite_b2b.set_user_group WHERE group_info_status = '3' AND module_group_guid = '$module_group_guid' AND isactive = '1'")->row('user_group_guid');

                                        $update_user_group = $this->db->query("UPDATE lite_b2b.set_user
                                        SET user_group_guid = '$consign_user_group',
                                        updated_at = NOW(), updated_by = '$user_id'
                                        WHERE user_guid = '$user_group_user_guid'
                                        AND acc_guid = '$process_customer_guid'");

                                        $log_user_group2 = array(
                                            'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                                            'customer_guid' => $process_customer_guid,
                                            'supplier_guid' => $process_supplier_guid,
                                            'user_guid' => $user_group_user_guid,
                                            'old_data' => $old_user_group,
                                            'new_data' => $update_user_group,
                                            'module' => 'log_user_group',
                                            'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                                            'created_by' => $user_id,
                                        );
                                        $this->db->insert('lite_b2b.user_account_logs',$log_user_group2);
                                    }
                                }
                            }
                        }
                    }
                    // end duplicate admin user group 

                    $check_data_user = $this->db->query("SELECT a.*
                    FROM lite_b2b.set_user a
                    WHERE a.user_guid = '$process_user_guid'
                    AND a.acc_guid = '$process_customer_guid'
                    AND a.isactive = '1' ")->result_array();
    
                    if(count($check_data_user) > 0 )
                    {
                        $get_pending_creation = $this->db->query("SELECT a.* FROM lite_b2b.set_user_process_list a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.guid = '$link_one' GROUP BY a.guid")->result_array();
    
                        if(count($get_pending_creation) > 0)
                        {
                            $delete_pending = $this->db->query("DELETE FROM lite_b2b.set_user_process_list WHERE `guid` = '$link_one' AND action_status = '$process_action_status' ");
                        }

                        $this->retrieve_account_count($process_customer_guid,$process_supplier_guid,$user_id);
                    }
                    
                    $data = array(
                       'para1' => 'True',
                       'msg' => 'User Account Successfully Created',
                    );    
                    echo json_encode($data);   
                    exit();
                }
                else
                {   
                    $data = array(
                    'para1' => 'false',
                    'msg' => 'Failed to Process User' ,
                    );    
                    echo json_encode($data);  
                    exit(); 
                }
            }
            else if($process_action_status == 'DUPLICATE')
            {
                $check_data_user = $this->db->query("SELECT a.*
                FROM lite_b2b.set_user a
                WHERE a.user_guid = '$process_user_guid'
                AND a.acc_guid = '$process_customer_guid'
                AND a.isactive = '$isactive'
                ")->result_array();

                $process_user_group_guid = $check_data_user[0]['user_group_guid'];

                if(count($check_data_user) > 0 )
                {
                    $data_user = array(
                        'isactive' => '1',
                        'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                        'updated_by' => $user_id,
                    );
    
                    $this->db->where('user_guid', $process_user_guid);
                    $this->db->where('acc_guid', $process_customer_guid);
                    $this->db->where('isactive', $isactive);
                    $this->db->update('lite_b2b.set_user',$data_user);

                    $this->retrieve_account_count($process_customer_guid,$process_supplier_guid,$user_id);
                }

                $get_pending_creation = $this->db->query("SELECT a.* FROM lite_b2b.set_user_process_list a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.guid = '$link_one' GROUP BY a.guid")->result_array();

                if(count($get_pending_creation) > 0)
                {
                    // check duplicate admin user group 
                    $query_data_user_group = $this->db->query("SELECT a.user_guid,a.user_group_guid 
                    FROM lite_b2b.set_user a 
                    INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.user_guid = b.user_guid 
                    AND a.acc_guid = b.customer_guid
                    INNER JOIN lite_b2b.set_supplier_group c ON b.supplier_guid = c.supplier_guid 
                    AND b.customer_guid = c.customer_guid 
                    INNER JOIN lite_b2b.set_supplier d 
                    ON c.supplier_guid = d.supplier_guid 
                    WHERE a.acc_guid = '$process_customer_guid' 
                    AND d.supplier_guid = '$process_supplier_guid' 
                    AND a.user_group_guid IN ('$valid_admin_guid')
                    AND a.isactive IN ('1','9')
                    GROUP BY a.user_guid,a.acc_guid")->result_array();

                    // print_r(count($query_data_user_group)); die;

                    if(count($query_data_user_group) > 1)
                    {
                        foreach($query_data_user_group as $key => $value)
                        {
                            $user_group_user_guid = $value['user_guid'];
                            $old_user_group = $value['user_group_guid'];

                            // print_r($user_group_user_guid); die;

                            if($user_group_user_guid == $process_user_guid)
                            {
                                continue;
                            }

                            // print_r($user_group_user_guid); die;

                            $check_user_group_status = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE user_group_guid = '$process_user_group_guid' AND group_info_status >= '1' AND isactive = '1' AND admin_active = '2' ")->result_array();

                            if(count($check_user_group_status) > 0 )
                            {
                                $check_outright_code = $this->db->query("SELECT a.supplier_guid,a.supplier_name,b.supplier_group_name,c.`code`,c.consign 
                                FROM lite_b2b.set_supplier a
                                INNER JOIN lite_b2b.set_supplier_group b
                                ON a.supplier_guid= b.supplier_guid
                                INNER JOIN b2b_summary.supcus c
                                ON b.supplier_group_name = c.`code`
                                AND b.customer_guid = c.customer_guid
                                WHERE a.supplier_guid = '$process_supplier_guid'
                                AND b.customer_guid = '$process_customer_guid'
                                AND c.consign = '0' 
                                GROUP BY a.supplier_guid,b.supplier_group_name")->result_array();

                                if(count($check_outright_code) > 0)
                                {
                                    $supp_admin_user_group = $this->db->query("SELECT user_group_guid FROM lite_b2b.set_user_group WHERE group_info_status = '2' AND module_group_guid = '$module_group_guid' AND isactive = '1'")->row('user_group_guid');

                                    $update_user_group = $this->db->query("UPDATE lite_b2b.set_user
                                    SET user_group_guid = '$supp_admin_user_group',
                                    updated_at = NOW(), updated_by = '$user_id'
                                    WHERE user_guid = '$user_group_user_guid'
                                    AND acc_guid = '$process_customer_guid'");

                                    $log_user_group1 = array(
                                        'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                                        'customer_guid' => $process_customer_guid,
                                        'supplier_guid' => $process_supplier_guid,
                                        'user_guid' => $user_group_user_guid,
                                        'old_data' => $old_user_group,
                                        'new_data' => $supp_admin_user_group,
                                        'module' => 'log_user_group',
                                        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                                        'created_by' => $user_id,
                                    );
                                    $this->db->insert('lite_b2b.user_account_logs',$log_user_group1);
                                }
                                else
                                {
                                    $check_consign_code = $this->db->query("SELECT a.supplier_guid,a.supplier_name,b.supplier_group_name,c.`code`,c.consign 
                                    FROM lite_b2b.set_supplier a
                                    INNER JOIN lite_b2b.set_supplier_group b
                                    ON a.supplier_guid= b.supplier_guid
                                    INNER JOIN b2b_summary.supcus c
                                    ON b.supplier_group_name = c.`code`
                                    AND b.customer_guid = c.customer_guid
                                    WHERE a.supplier_guid = '$process_supplier_guid'
                                    AND b.customer_guid = '$process_customer_guid'
                                    AND c.consign = '1' 
                                    GROUP BY a.supplier_guid,b.supplier_group_name")->result_array();

                                    if(count($check_consign_code) > 0)
                                    {
                                        $consign_user_group = $this->db->query("SELECT user_group_guid FROM lite_b2b.set_user_group WHERE group_info_status = '3' AND module_group_guid = '$module_group_guid' AND isactive = '1'")->row('user_group_guid');

                                        $update_user_group = $this->db->query("UPDATE lite_b2b.set_user
                                        SET user_group_guid = '$consign_user_group',
                                        updated_at = NOW(), updated_by = '$user_id'
                                        WHERE user_guid = '$user_group_user_guid'
                                        AND acc_guid = '$process_customer_guid'");

                                        $log_user_group2 = array(
                                            'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                                            'customer_guid' => $process_customer_guid,
                                            'supplier_guid' => $process_supplier_guid,
                                            'user_guid' => $user_group_user_guid,
                                            'old_data' => $old_user_group,
                                            'new_data' => $update_user_group,
                                            'module' => 'log_user_group',
                                            'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                                            'created_by' => $user_id,
                                        );
                                        $this->db->insert('lite_b2b.user_account_logs',$log_user_group2);
                                    }
                                }
                            }
                        }
                    }
                    // end duplicate admin user group 

                    $delete_pending = $this->db->query("DELETE FROM lite_b2b.set_user_process_list WHERE `guid` = '$link_one' AND action_status = '$process_action_status' ");
                }

                $error = $this->db->affected_rows();

                if($error > 0)
                {
                    $data = array(
                        'para1' => 'True',
                        'msg' => 'Update Successful.',
                    );    
                    echo json_encode($data);   
                    exit();
                }
                else
                {   
                    $data = array(
                    'para1' => 'false',
                    'msg' => 'Failed to Process User' ,
                    );    
                    echo json_encode($data);  
                    exit(); 
                }
            }
            else
            {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Invalid Process.' ,
                );    
                echo json_encode($data);  
                exit();
            }
        } 
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function view_daily_notification_tb()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) 
        {
            $module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0';
            $user_guid = $this->input->post('user_guid');
            $customer_guid = $this->input->post('customer_guid');

            $query_data = $this->db->query("SELECT 
            a.rep_option_guid,
            c.log_table,
            a.option_description 
            FROM lite_b2b.set_report_query_option a 
            INNER JOIN lite_b2b.set_report_query_option_c b
            ON a.rep_option_guid = b.rep_option_guid
            AND a.customer_guid = b.customer_guid
            AND a.isactive = '1'
            INNER JOIN lite_b2b.set_logs_query c
            ON a.report_guid = c.guid
            WHERE a.customer_guid = '$customer_guid'    
            AND b.user_guid = '$user_guid'
            GROUP BY a.rep_option_guid
            ");

            // echo $this->db->last_query();die;

            $data = array(  
                'data' => $query_data->result(), 
            );

            echo json_encode($data);
        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function retrieve_account_count($process_customer_guid,$process_supplier_guid,$user_id)
    {
        $get_registered_count = $this->db->query("SELECT aa.acc_guid,aa.acc_name,COUNT(aa.user_name) AS count_data
        FROM
        ( SELECT e.acc_guid,e.acc_name,d.supplier_guid,d.supplier_name,a.user_name 
        FROM 
        lite_b2b.set_user a
        INNER JOIN lite_b2b.set_supplier_user_relationship b
        ON a.user_guid = b.user_guid
        INNER JOIN lite_b2b.set_supplier_group c
        ON b.supplier_guid = c.supplier_guid
        AND b.customer_guid = c.customer_guid
        INNER JOIN lite_b2b.set_supplier d
        ON c.supplier_guid = d.supplier_guid
        INNER JOIN lite_b2b.acc e
        ON b.customer_guid = e.acc_guid
        WHERE a.acc_guid = '$process_customer_guid'
        AND d.supplier_guid = '$process_supplier_guid' 
        AND a.hide_admin = '0'
        GROUP BY a.user_id,a.acc_guid ) aa
        GROUP BY aa.acc_name")->row('count_data');

        if(($get_registered_count - 1) % 5 == 0)
        {
            $get_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');

            $data = array(
                'guid' => $get_guid,
                'customer_guid' => $process_customer_guid,
                'supplier_guid' => $process_supplier_guid,
                'user_count' => $get_registered_count,
                'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                'created_by' => $user_id,
            );

            $this->db->insert('lite_b2b.set_supplier_user_count',$data);
        }

        return;       
    }
}
?>

