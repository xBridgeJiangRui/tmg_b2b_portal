<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_email_setup extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->library('session');
    }

    public function index()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {

            $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' AND a.trial_mode = '0' ORDER BY a.acc_name ASC");

            $get_supplier = $this->db->query("SELECT a.supplier_guid, a.supplier_name FROM lite_b2b.set_supplier a WHERE a.`isactive` = '1' AND a.`suspended` = '0' ORDER BY a.supplier_name ASC");

            $get_user = $this->db->query("SELECT a.user_guid, a.user_id, a.user_name FROM lite_b2b.set_user a WHERE a.isactive = '1' GROUP BY a.user_guid");

            $get_table = $this->db->query("SELECT a.guid,a.log_table FROM lite_b2b.set_logs_query a WHERE a.`isactive` = '2' ORDER BY a.created_at ASC");

            $get_user_group = $this->db->query("SELECT a.user_group_guid,a.user_group_name FROM lite_b2b.set_user_group a WHERE a.module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0' AND isactive = '1'");

            $data = array(
                'get_supplier' => $get_supplier->result(),
                'get_acc' => $get_acc->result(),
                'get_user' => $get_user->result(),
                'get_table' => $get_table->result(),
                'get_user_group' => $get_user_group->result(),
            );

            $this->load->view('header');
            $this->load->view('notification/daily_setup', $data);
            $this->load->view('footer');

        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function notification_list_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search= $this->input->post("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";

        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc")
        {
          $dir = "desc";
        }

        $valid_columns = array(

            0 =>'rep_option_guid',
            1 =>'acc_name',
            2 =>'log_table',
            3 =>'option_description',
            4 =>'isactive',
            5 =>'created_at',
            6 =>'created_by',
            7 =>'updated_at',
            8 =>'updated_by',
        );

        if(!isset($valid_columns[$col]))
        {
          $order = null;
        }
        else
        {
          $order = $valid_columns[$col];
        }

        if($order !=null)
        {   
          // $this->db->order_by($order, $dir);

          $order_query = "ORDER BY " .$order. "  " .$dir;
        }

        $like_first_query = '';
        $like_second_query = '';

        if(!empty($search))
        {
          $x=0;
          foreach($valid_columns as $sterm)
          {
              if($x==0)
              {
                  // $this->db->like($sterm,$search);

                  $like_first_query = "WHERE $sterm LIKE '%".$search."%'";

              }
              else
              {
                  // $this->db->or_like($sterm,$search);

                  $like_second_query .= "OR $sterm LIKE '%".$search."%'";

              }
              $x++;
          }
           
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;
        
        $sql = "SELECT b.log_table,c.acc_name,a.* FROM lite_b2b.`set_report_query_option` a
        INNER JOIN lite_b2b.`set_logs_query` b
        ON a.report_guid = b.guid
        AND b.isactive IN ('1','2')
        INNER JOIN lite_b2b.acc c
        ON a.customer_guid = c.acc_guid
        ";

        $query = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query.$order_query.$limit_query;

        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

        $result = $this->db->query($query);

        // echo $this->db->last_query();

        if(!empty($search))
        {
            $query_filter = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query;
            $result_filter = $this->db->query($query_filter)->result();
            $total = count($result_filter);
        }
        else
        {
            $total = $this->db->query($sql)->num_rows();
        }


        $data = array();
        foreach($result->result() as $row)
        {
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['log_table'] = $row->log_table;
            $nestedData['option_description'] = $row->option_description;
            // $nestedData['option_code'] = $row->option_code;
            $nestedData['isactive'] = $row->isactive;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['created_by'] = $row->created_by;
            $nestedData['updated_at'] = $row->updated_at;
            $nestedData['updated_by'] = $row->updated_by;
            $nestedData['customer_guid'] = $row->customer_guid;
            $nestedData['rep_option_guid'] = $row->rep_option_guid;
            $nestedData['report_guid'] = $row->report_guid;
            $nestedData['dockey'] = $row->dockey;
            $nestedData['sync_status'] = $row->sync_status;
            $nestedData['sync_at'] = $row->sync_at;

            $data[] = $nestedData;

        }

        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);
    }

    public function process_notification_create()
    {
        $customer_guid = $_SESSION['customer_guid'];
        $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
        $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');

        $notification_retailer = $this->input->post('notification_retailer');
        $notification_guid = $this->input->post('notification_guid');
        // $report_name = $this->input->post('report_name');
        // $report_code = $this->input->post('report_code');

        $count_value = count($notification_retailer);

        $array_data = json_decode(json_encode($notification_retailer));

        $i = 0;

        foreach($array_data as $row)
        {
            $customer_guid = $row;

            $check_data = $this->db->query("SELECT * FROM lite_b2b.set_report_query_option WHERE report_guid = '$notification_guid' AND customer_guid = '$customer_guid'")->result_array();

            if(count($check_data) > 0)
            {
                continue;
            }

            $report_name = $this->db->query("SELECT log_table FROM lite_b2b.set_logs_query WHERE `guid` = '$notification_guid'")->row('log_table');
    
            $data = array(
                'rep_option_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                'customer_guid' => $customer_guid,
                'report_guid' => $notification_guid,
                'option_description' => $report_name,
                // 'option_code' => $report_code,
                'isactive' => '1',
                'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                'created_by' => $user_id,
                'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                'updated_by' => $user_id,
            );
            $this->db->insert('lite_b2b.set_report_query_option',$data);
    
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
               'msg' => 'Success Create Total : ' .$i. ' / ' .$count_value,
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 'false',
            'msg' => 'Success Create Total : ' .$i. ' / ' .$count_value,
            );    
            echo json_encode($data);  
            exit(); 
        }
    }

    public function process_notification_create_report()
    {
        $customer_guid = $_SESSION['customer_guid'];
        $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
        $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');

        $customer_guid = $this->input->post('r_notification_retailer');
        $notification_guid = $this->input->post('r_notification_guid');

        $count_value = count($notification_guid);

        $array_data = json_decode(json_encode($notification_guid));

        $i = 0;

        foreach($array_data as $row)
        {
            $notification_guid = $row;

            $check_data = $this->db->query("SELECT * FROM lite_b2b.set_report_query_option WHERE report_guid = '$notification_guid' AND customer_guid = '$customer_guid'")->result_array();

            if(count($check_data) > 0)
            {
                continue;
            }

            $report_name = $this->db->query("SELECT log_table FROM lite_b2b.set_logs_query WHERE `guid` = '$notification_guid'")->row('log_table');
    
            $data = array(
                'rep_option_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                'customer_guid' => $customer_guid,
                'report_guid' => $notification_guid,
                'option_description' => $report_name,
                // 'option_code' => $report_code,
                'isactive' => '1',
                'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                'created_by' => $user_id,
                'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                'updated_by' => $user_id,
            );
            $this->db->insert('lite_b2b.set_report_query_option',$data);
    
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
               'msg' => 'Success Create Total : ' .$i. ' / ' .$count_value,
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 'false',
            'msg' => 'Success Create Total : ' .$i. ' / ' .$count_value,
            );    
            echo json_encode($data);  
            exit(); 
        }
    }

    public function process_notification_edit()
    {
        $customer_guid = $_SESSION['customer_guid'];
        $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
        $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');

        $main_guid = $this->input->post('main_guid');
        $edit_retailer = $this->input->post('edit_retailer');
        $edit_report = $this->input->post('edit_report');
        $edit_active = $this->input->post('edit_active');
        $edit_report_name = $this->input->post('edit_report_name');
        // $edit_report_code = $this->input->post('edit_report_code');
        $now = $this->db->query("SELECT NOW() as now")->row('now');

        $check_data = $this->db->query("SELECT * FROM lite_b2b.set_report_query_option WHERE rep_option_guid = '$main_guid'")->result_array();

        if(count($check_data) == 0)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'Data Not Found.',
            );    
            echo json_encode($data); 
            die;  
        }

        $check_data_by_report = $this->db->query("SELECT * FROM lite_b2b.set_report_query_option WHERE report_guid = '$edit_report' AND customer_guid = '$edit_retailer' AND rep_option_guid != '$main_guid'")->result_array();

        if(count($check_data_by_report) > 0)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'Data Duplicate.',
            );    
            echo json_encode($data); 
            die;  
        }

        $update_data = $this->db->query("UPDATE `lite_b2b`.`set_report_query_option` SET `updated_at` = '$now', `updated_by` = '$user_id', `isactive` = '$edit_active', `option_description` = '$edit_report_name', `report_guid` = '$edit_report', `customer_guid` = '$edit_retailer'  WHERE rep_option_guid = '$main_guid' ");

        $error = $this->db->affected_rows();

        if($error > 0){
            
            $data = array(
               'para1' => 'true',
               'msg' => 'Success Edit.',
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

    public function remove_notification_report()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
  
            $guid = $this->input->post('guid');
            $delete_retailer = $this->input->post('delete_retailer');

            $check_data_main = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option a WHERE a.rep_option_guid = '$guid' AND a.customer_guid = '$delete_retailer' ")->result_array();

            if(count($check_data_main) == 0 )
            {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Data Not Found',
                );    
                echo json_encode($data);  
                exit(); 
            }

            $check_data_child = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.rep_option_guid = '$guid' AND a.customer_guid = '$delete_retailer' ")->result_array();

            if(count($check_data_child) > 0 )
            {
                $delete_data_child = $this->db->query("DELETE FROM lite_b2b.set_report_query_option_c WHERE rep_option_guid = '$guid' AND customer_guid = '$delete_retailer' ");
            }

            $delete_data_main = $this->db->query("DELETE FROM lite_b2b.set_report_query_option WHERE rep_option_guid = '$guid' AND customer_guid = '$delete_retailer' ");

            $error = $this->db->affected_rows();

            if($error > 0)
            {
                $data = array(
                    'para1' => 'True',
                    'msg' => 'Deleted Success',
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {   
                $data = array(
                'para1' => 'false',
                'msg' => 'Process Error',
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

    public function notification_user_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 
    
        $rep_option_guid = $this->input->post("rep_option_guid");
    
        $data = $this->db->query("SELECT c.acc_name,d.user_id,d.user_name,d.isactive,d.isnotification,a.* 
        FROM lite_b2b.set_report_query_option_c a
        INNER JOIN lite_b2b.set_report_query_option b
        ON a.rep_option_guid = b.rep_option_guid
        INNER JOIN lite_b2b.acc c
        ON a.customer_guid = c.acc_guid
        INNER JOIN lite_b2b.set_user d
        ON a.user_guid = d.user_guid
        AND a.customer_guid = d.acc_guid
        WHERE a.rep_option_guid = '$rep_option_guid'
        GROUP BY a.user_guid,a.customer_guid ");
    
        // echo $this->db->last_query(); die;
    
        $output = array(
            "data" => $data->result(),
        );
    
        echo json_encode($output);
    }

    public function fetch_supplier()
    {
        $add_retailer_guid = $this->input->post('add_retailer_guid');

        $query_supplier_data = $this->db->query("SELECT a.supplier_guid,a.supplier_name FROM 
        lite_b2b.set_supplier a
        INNER JOIN  lite_b2b.set_supplier_group b
        ON a.supplier_guid = b.supplier_guid
        AND a.isactive = '1'
        INNER JOIN lite_b2b.set_supplier_user_relationship c
        ON b.supplier_guid = c.supplier_guid
        AND b.customer_guid = c.customer_guid
        WHERE b.customer_guid = '$add_retailer_guid'
        GROUP BY a.supplier_guid,b.customer_guid");

        // echo $this->db->last_query(); die;

        $data = array(
            'query_supplier_data' => $query_supplier_data->result(),
        );
    
        echo json_encode($data);
    }

    public function fetch_user()
    {
        $add_retailer_guid = $this->input->post('add_retailer_guid');
        $add_supplier_guid = $this->input->post('add_supplier_guid');
        $add_user_group = $this->input->post('add_user_group');

        if($add_supplier_guid != '')
        {
            $condition = "AND a.supplier_guid = '$add_supplier_guid'";
        }
        else
        {
            $condition = '';
        }

        if($add_user_group != '')
        {
            $condition_2 = "AND d.user_group_guid = '$add_user_group'";
        }
        else
        {
            $condition_2 = '';
        }

        $query_data = $this->db->query("SELECT d.user_guid,d.user_name,d.user_id FROM 
        lite_b2b.set_supplier a
        INNER JOIN  lite_b2b.set_supplier_group b
        ON a.supplier_guid = b.supplier_guid
        AND a.isactive = '1'
        INNER JOIN lite_b2b.set_supplier_user_relationship c
        ON b.supplier_guid = c.supplier_guid
        AND b.customer_guid = c.customer_guid
        INNER JOIN lite_b2b.set_user d
        ON c.user_guid = d.user_guid
        AND c.customer_guid = d.acc_guid
        AND d.isactive = '1'
        WHERE d.acc_guid = '$add_retailer_guid'
        AND d.user_id LIKE '%@%'
        $condition
        $condition_2
        GROUP BY d.user_guid,d.acc_guid");

        // echo $this->db->last_query(); die;

        $data = array(
            'query_data' => $query_data->result(),
        );
    
        echo json_encode($data);
    }

    public function process_user_create()
    {
        $customer_guid = $_SESSION['customer_guid'];
        $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
        $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');

        $main_guid = $this->input->post('main_guid');
        $add_retailer = $this->input->post('add_retailer');
        $add_user = $this->input->post('add_user');

        $count_value = count($add_user);

        $array_data = json_decode(json_encode($add_user));

        $i = 0;

        // print_r($array_data); die;

        foreach($array_data as $row)
        {
            $user_guid = $row;

            $check_data = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$add_retailer' AND a.user_guid = '$user_guid' AND a.rep_option_guid = '$main_guid'")->result_array();
            
            // echo $this->db->last_query();die;

            if(count($check_data) > 0 )
            {
                continue;
            }

            $data = array(
                //if b2b, acc_guid will be using session customer_guid
                'customer_guid' => $add_retailer,
                'rep_option_guid_c' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                'rep_option_guid' => $main_guid,
                'user_guid' => $user_guid,
                'isactive' => '1',
                'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                'created_by' => $user_id,
                'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                'updated_by' => $user_id,
            );
            $this->db->insert('lite_b2b.set_report_query_option_c',$data);

            $error = $this->db->affected_rows();

            $update_user_notification = $this->db->query("UPDATE lite_b2b.set_user
            SET isnotification = '1'
            WHERE user_guid = '$user_guid'
            AND acc_guid = '$add_retailer'
            AND isnotification = '0' ");

            if($error > 0)
            {
                $i++;
            }
            
        }

        if($count_value == $i)
        {
            $data = array(
               'para1' => 'True',
               'msg' => 'Success Create Total : ' .$i. ' / ' .$count_value,
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 'false',
            'msg' => 'Success Create Total : ' .$i. ' / ' .$count_value,
            );    
            echo json_encode($data);  
            exit(); 
        }
    }

    public function remove_user_create()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) 
        {
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
  
            $details = $this->input->post('details');

            $count_value = count($details);

            $array_data = json_decode(json_encode($details));

            $i = 0;

            foreach($array_data as $row)
            {
		        $delete_guid = $row->d_guid;
		        $delete_customer_guid = $row->d_customer_guid;
                $delete_user_guid = $row->d_user_guid;

                $check_data_relationship = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.rep_option_guid_c = '$delete_guid' AND a.customer_guid = '$delete_customer_guid' AND a.user_guid = '$delete_user_guid' ")->result_array();

                if(count($check_data_relationship) == 0 )
                {
                    continue;
                }

                $delete_child = $this->db->query("DELETE FROM lite_b2b.set_report_query_option_c WHERE rep_option_guid_c = '$delete_guid' AND customer_guid = '$delete_customer_guid' AND user_guid = '$delete_user_guid' ");
		
                $error = $this->db->affected_rows();

                $check_after_delete = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$delete_customer_guid' AND a.user_guid = '$delete_user_guid' AND a.isactive = '1'")->result_array();

                if(count($check_after_delete) > 0)
                {
                    $update_user_notification = $this->db->query("UPDATE lite_b2b.set_user
                    SET isnotification = '1'
                    WHERE user_guid = '$delete_user_guid'
                    AND acc_guid = '$delete_customer_guid'
                    AND isnotification = '0' ");
                }
                else
                {
                    $update_user_notification = $this->db->query("UPDATE lite_b2b.set_user
                    SET isnotification = '0'
                    WHERE user_guid = '$delete_user_guid'
                    AND acc_guid = '$delete_customer_guid'
                    AND isnotification = '1' ");
                }

                if($error > 0)
                {
                    // $mapping_code_log1 = array(
                    //     'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                    //     'customer_guid' => $delete_customer_guid,
                    //     'supplier_guid' => $delete_supplier_guid,
                    //     'user_guid' => $delete_user_guid,
                    //     'old_data' => $delete_supplier_group_guid,
                    //     'new_data' => '',
                    //     'module' => 'mapping_code',
                    //     'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                    //     'created_by' => $user_id,
                    // );
                    // $this->db->insert('lite_b2b.user_account_logs',$mapping_code_log1);

                    $i++;
                }
            }

            if($count_value == $i)
            {
                $data = array(
                   'para1' => 'True',
                   'msg' => 'Deleted User Total : ' .$i. ' / ' .$count_value,
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {   
                $data = array(
                'para1' => 'false',
                'msg' => 'Deleted User Total : ' .$i. ' / ' .$count_value,
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

    public function notification_user_info()
    {
        $user_guid = $this->input->post('user_guid');
        $customer_guid = $this->input->post('customer_guid');
        
        $query_data = $this->db->query("SELECT a.user_id,d.supplier_name,e.user_group_name 
        FROM lite_b2b.set_user a 
        INNER JOIN lite_b2b.set_supplier_user_relationship b
        ON a.user_guid = b.user_guid
        AND a.acc_guid = b.customer_guid
        INNER JOIN lite_b2b.set_supplier_group c
        ON b.supplier_guid = c.supplier_guid
        AND b.customer_guid = c.customer_guid
        INNER JOIN lite_b2b.set_supplier d
        ON c.supplier_guid = d.supplier_guid
        AND d.isactive = '1'
        INNER JOIN lite_b2b.set_user_group e
        ON a.user_group_guid = e.user_group_guid
        WHERE a.user_guid = '$user_guid'
        AND a.acc_guid = '$customer_guid'
        GROUP BY d.supplier_guid,e.user_group_guid");

        // echo $this->db->last_query();die;

        $data = array(  
            'data' => $query_data->result(), 
            'data_user_id' => $query_data->row('user_id'),
        );

        echo json_encode($data);
    }
}
?>

