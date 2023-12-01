<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Registration_upload_doc extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper(array('form','url'));
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->library('Panda_PHPMailer'); 
         
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $acc_guid = $_SESSION['customer_guid'];

            $supplier = $this->db->query("SELECT supplier_name,supplier_guid FROM lite_b2b.set_supplier WHERE isactive = '1' ORDER BY supplier_name ASC");

            $acc = $this->db->query("SELECT * FROM lite_b2b.acc WHERE isactive = '1' ");

            $query_pending = $this->db->query("SELECT a.`memo_type`, b.`customer_guid`, b.`supplier_guid`, b.`user_guid`, d.`supplier_name`, d.`reg_no`, e.`acc_name`, c.`term_type`, c.`status`, c.`url` FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.`supplier_guid` = b.`supplier_guid` AND a.customer_guid = b.`customer_guid` LEFT JOIN lite_b2b.`reg_upload_doc` c ON b.`customer_guid` = c.`customer_guid` AND b.`supplier_guid` = c.`supplier_guid` INNER JOIN lite_b2b.`set_supplier` d ON b.`supplier_guid` = d.`supplier_guid` INNER JOIN lite_b2b.`acc` e ON b.`customer_guid` = e.`acc_guid` WHERE a.term_download = '1' AND c.status = 'Pending' GROUP BY a.`register_guid`,c.`upload_guid`");

            $query_normal = $this->db->query("SELECT * FROM( SELECT a.`memo_type`, b.`customer_guid`, b.`supplier_guid`, b.`user_guid`, d.`supplier_name`, d.`reg_no`, e.`acc_name`, c.`term_type`, IFNULL(c.`status`, 'NO FILE') AS `status`, c.`url` FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.`supplier_guid` = b.`supplier_guid` AND a.customer_guid = b.`customer_guid` LEFT JOIN lite_b2b.`reg_upload_doc` c ON b.`customer_guid` = c.`customer_guid` AND b.`supplier_guid` = c.`supplier_guid` INNER JOIN lite_b2b.`set_supplier` d ON b.`supplier_guid` = d.`supplier_guid` INNER JOIN lite_b2b.`acc` e ON b.`customer_guid` = e.`acc_guid` WHERE a.term_download = '1' AND a.`memo_type` IN ('outright','consignment','both') GROUP BY a.`register_guid`,c.`upload_guid` )aa WHERE aa.status IN ('NO FILE','Rejected')");

            $query_special = $this->db->query("SELECT * FROM ( SELECT a.`memo_type`, b.`customer_guid`, b.`supplier_guid`, b.`user_guid`, d.`supplier_name`, d.`reg_no`, e.`acc_name`, COUNT(DISTINCT c.`term_type`) AS counting FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.`supplier_guid` = b.`supplier_guid` AND a.customer_guid = b.`customer_guid` LEFT JOIN lite_b2b.`reg_upload_doc` c ON b.`customer_guid` = c.`customer_guid` AND b.`supplier_guid` = c.`supplier_guid` INNER JOIN lite_b2b.`set_supplier` d ON b.`supplier_guid` = d.`supplier_guid` INNER JOIN lite_b2b.`acc` e ON b.`customer_guid` = e.`acc_guid` WHERE a.term_download = '1' AND a.`memo_type` NOT IN ('outright','consignment','both') GROUP BY a.`supplier_guid`)a WHERE (a.counting = '1' OR a.counting = '0')");

            $data = array(
                'supplier' => $supplier->result(),
                'acc' => $acc->result(),
                'query_pending' => $query_pending,
                'query_normal' => $query_normal,
                'query_special' => $query_special,

            );
            $this->load->view('header');
            $this->load->view('register/term_list', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function term_list_table()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $user_guid = $_SESSION['user_guid'];
        $acc_guid = $_SESSION['customer_guid'];
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
            0=>'upload_guid',
            1=>'acc_name',
            2=>'supplier_name',
            3=>'user_name',
            4=>'term_type',
            5=>'status',
            6=>'created_at',
            7=>'created_by',
            //6=>'upload_docs',
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

        $get_supplier = $this->db->query("SELECT a.* FROM lite_b2b.set_supplier_user_relationship a WHERE a.`user_guid` = '$user_guid'");

        $supplier_guid = array();
        foreach($get_supplier->result() as $row)
        {
            $supplier_guid[] = $row->supplier_guid;
        }

        $supplier_guid = array_filter($supplier_guid);
        $supplier_guid = implode("','", $supplier_guid);
        $supplier_guid = "'".$supplier_guid."'";
        //print_r($supplier_guid); die;
        if(in_array('IAVA',$_SESSION['module_code']))
        {
            $sql = "SELECT a.*, b.acc_name, c.`supplier_name`, d.`user_name` FROM lite_b2b.reg_upload_doc a INNER JOIN lite_b2b.acc b ON a.`customer_guid` = b.`acc_guid` INNER JOIN lite_b2b.`set_supplier` c ON a.`supplier_guid` = c.`supplier_guid` LEFT JOIN lite_b2b.`set_user` d ON a.`user_guid` = d.`user_guid` GROUP BY a.`supplier_guid`,a.`term_type`,a.`customer_guid` ";
        }
        else
        {
            $sql = "SELECT a.*, b.acc_name, c.`supplier_name`, d.`user_name` FROM lite_b2b.reg_upload_doc a INNER JOIN lite_b2b.acc b ON a.`customer_guid` = b.`acc_guid` INNER JOIN lite_b2b.`set_supplier` c ON a.`supplier_guid` = c.`supplier_guid` INNER JOIN lite_b2b.`set_user` d ON a.`user_guid` = d.`user_guid` WHERE a.supplier_guid IN ($supplier_guid) AND customer_guid = '$acc_guid' GROUP BY a.`supplier_guid`,a.`term_type`,a.`customer_guid` ";
        }

        $query = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query.$order_query.$limit_query;

        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

        $result = $this->db->query($query);

        //echo $this->db->last_query(); die;

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
            
            $nestedData['upload_guid'] = $row->upload_guid;
            $nestedData['customer_guid'] = $row->customer_guid;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['user_name'] = $row->user_name;
            $nestedData['user_guid'] = $row->user_guid;
            $nestedData['term_type'] = $row->term_type;
            $nestedData['status'] = $row->status;
            $nestedData['url'] = $row->url;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['created_by'] = $row->created_by;
            $nestedData['rejected'] = $row->rejected;
            
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

    public function term_approval()
    {
        $upload_guid = $this->input->post("upload_guid");

        $select_data = $this->db->query("SELECT * FROM lite_b2b.reg_upload_doc WHERE upload_guid = '$upload_guid'");

        if($select_data->num_rows() == 1)
        {
            $update_data = $this->db->query("UPDATE lite_b2b.reg_upload_doc SET `status` = 'Accepted' WHERE upload_guid = '$upload_guid'");
        }

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
               'para1' => 0,
               'msg' => 'Approve Succesfully.',
               //'link' => $url_link,
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Error do Approval.',
            //'link' => 'Unknown URL.',

            );    
            echo json_encode($data);  
            exit(); 
        }
    }

    public function term_rejection()
    {
        $upload_guid = $this->input->post("upload_guid");

        $select_data = $this->db->query("SELECT * FROM lite_b2b.reg_upload_doc WHERE upload_guid = '$upload_guid'");

        if($select_data->num_rows() == 1)
        {
            $acc_guid = $select_data->row('customer_guid');
            $supplier_guid = $select_data->row('supplier_guid');
            $user_guid = $select_data->row('user_guid');
            $term_type = $select_data->row('term_type');
            $url = $select_data->row('url');
            $file_name = basename($url);
            $file_config_main_path = $this->file_config_b2b->file_path_name($acc_guid,'web','reg_docs','main_path','REGTERM');
            $unlink_path = $file_config_main_path.$acc_guid.'/'.$supplier_guid.'/'.$user_guid.'/'.$term_type.'/'.$file_name.'';
            //$unlink_path = "/media/b2b-pdf/reg_doc/$acc_guid/$supplier_guid/$user_guid/$term_type/$file_name";
            if(file_exists($unlink_path)){

                //unlink($unlink_path);
                $update_data = $this->db->query("UPDATE lite_b2b.reg_upload_doc SET `status` = 'Rejected' WHERE upload_guid = '$upload_guid'");
            }
            else
            {
                $data = array(
                'para1' => 1,
                'msg' => 'Cannot Find The PDF file.',
                //'link' => 'Unknown URL.',

                );    
                echo json_encode($data);  
                exit(); 
            }

            //$update_data = $this->db->query("UPDATE lite_b2b.reg_upload_doc SET `status` = 'Rejected', `rejected` = '1' WHERE upload_guid = '$upload_guid'");
        }

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
               'para1' => 0,
               'msg' => 'Rejected Succesfully.',
               //'link' => $url_link,
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Error do Rejection.',
            //'link' => 'Unknown URL.',

            );    
            echo json_encode($data);  
            exit(); 
        }
    }

    public function fetch_data()
    {
      $customer_guid = $_SESSION['customer_guid'];
      $type_val = $this->input->post('type_val');

      $vendor = $this->db->query("SELECT a.`memo_type`, b.`customer_guid`, b.`supplier_guid`, b.`user_guid`, d.`supplier_name`, d.`reg_no`, e.`acc_name`, c.`status`, c.`url` FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.`supplier_guid` = b.`supplier_guid` AND a.customer_guid = b.`customer_guid` LEFT JOIN lite_b2b.`reg_upload_doc` c ON b.`customer_guid` = c.`customer_guid` AND b.`supplier_guid` = c.`supplier_guid` INNER JOIN lite_b2b.`set_supplier` d ON b.`supplier_guid` = d.`supplier_guid` INNER JOIN lite_b2b.`acc` e ON b.`customer_guid` = e.`acc_guid` WHERE a.term_download = '1' AND a.`customer_guid` = '$type_val' GROUP BY a.`register_guid`");
      //echo $this->db->last_query(); die;
      $data = array(
          'vendor' => $vendor->result(),
      );

      echo json_encode($data);
    }

    public function fetch_term()
    {
      $customer_guid = $this->input->post('acc_guid');
      $supplier_guid = $this->input->post('type_val'); // supplier guid

      $registration = $this->db->query("SELECT a.`memo_type`, b.`customer_guid`, b.`supplier_guid`, b.`user_guid`, d.`supplier_name`, d.`reg_no`, e.`acc_name`, c.`status`, c.`url` FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.`supplier_guid` = b.`supplier_guid` AND a.customer_guid = b.`customer_guid` LEFT JOIN lite_b2b.`reg_upload_doc` c ON b.`customer_guid` = c.`customer_guid` AND b.`supplier_guid` = c.`supplier_guid` INNER JOIN lite_b2b.`set_supplier` d ON b.`supplier_guid` = d.`supplier_guid` INNER JOIN lite_b2b.`acc` e ON b.`customer_guid` = e.`acc_guid` WHERE a.term_download = '1' AND a.`customer_guid` = '$customer_guid' AND a.`supplier_guid` = '$supplier_guid' GROUP BY a.`register_guid` LIMIT 1");
      //echo $this->db->last_query(); die;
      $reg_memo_type = $registration->row('memo_type');
      $user_guid = $registration->row('user_guid');

      if(($reg_memo_type == 'outright') || ($reg_memo_type == 'consignment') || ($reg_memo_type == 'both'))
      {
        $select_option = '<option value="normal_term"> Term Sheet </option>';
        $check_reg_doc = $this->db->query("SELECT a.* FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND a.`status` IN ('Pending','Accepted')");
        $upload = $check_reg_doc->num_rows();
      }
      else
      {
        $check_reg_doc = $this->db->query("SELECT a.* FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND a.`status` IN ('Pending','Accepted')");
        //echo $this->db->last_query();die;
        $upload = $check_reg_doc->num_rows();
        //print_r($upload); die;
        if($upload == '0')
        {
            $select_option = '<option value="normal_term"> Term Sheet </option><option value="special_term"> Special Term Sheet </option>';
        }
        if($upload == '1')
        {
            if($check_reg_doc->row('term_type') == 'normal_term')
            {
                $select_option = '<option value="special_term"> Special Term Sheet </option>';
            }
            else
            {
                $select_option = '<option value="normal_term"> Term Sheet </option> ';
            }
            
        }
        else if($upload == '2')
        {
            $select_option = '<option value="all_uploaded" selected > ALL UPLOADED </option>';
        }

      }

      //echo $this->db->last_query(); die;
      $data = array(
          'select_option' => $select_option,
          'user_guid' => $user_guid
      );

      echo json_encode($data);
    }

    public function normal_term_tb()
    {
      $query_normal = $this->db->query("SELECT * FROM ( SELECT IFNULL(c.`status`,'NO FILE') AS `status`, a.`memo_type`, a.`update_at`, b.`customer_guid`, b.`supplier_guid`, b.`user_guid`, d.`supplier_name`, d.`reg_no`, e.`acc_name`, c.`term_type`, f.`setting_guid` FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.`supplier_guid` = b.`supplier_guid` AND a.customer_guid = b.`customer_guid` LEFT JOIN lite_b2b.`reg_upload_doc` c ON b.`customer_guid` = c.`customer_guid` AND b.`supplier_guid` = c.`supplier_guid` INNER JOIN lite_b2b.`set_supplier` d ON b.`supplier_guid` = d.`supplier_guid` INNER JOIN lite_b2b.`acc` e ON b.`customer_guid` = e.`acc_guid` LEFT JOIN lite_b2b.`reg_upload_settings` f ON a.`supplier_guid` = f.`supplier_guid` AND a.`customer_guid` = f.`customer_guid` AND a.`memo_type` = f.`memo_type` WHERE a.term_download = '1' AND a.`memo_type` IN ('outright', 'consignment', 'both') GROUP BY a.`register_guid`, c.`upload_guid` )aa WHERE aa.status IN ('NO FILE','Rejected')");
      //echo $this->db->last_query(); die;
      $data = array(
          'query_normal' => $query_normal->result(),
      );

      echo json_encode($data);
    }

    public function special_term_tb()
    {
      $query_special = $this->db->query("SELECT * FROM (SELECT a.`memo_type`, a.`update_at`, b.`customer_guid`, b.`supplier_guid`, b.`user_guid`, d.`supplier_name`, d.`reg_no`, e.`acc_name`, COUNT(DISTINCT c.`term_type`) AS counting, IF(a.memo_type = 'outright_iks', 'ONE OFF 200', f.`template_name`) AS template_name, g.`setting_guid` FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.`supplier_guid` = b.`supplier_guid` AND a.customer_guid = b.`customer_guid` LEFT JOIN lite_b2b.`reg_upload_doc` c ON b.`customer_guid` = c.`customer_guid` AND b.`supplier_guid` = c.`supplier_guid` INNER JOIN lite_b2b.`set_supplier` d ON b.`supplier_guid` = d.`supplier_guid` INNER JOIN lite_b2b.`acc` e ON b.`customer_guid` = e.`acc_guid` LEFT JOIN b2b_invoice.`template_settings_general` f ON a.`memo_type` = f.`template_guid` LEFT JOIN lite_b2b.`reg_upload_settings` g ON a.`supplier_guid` = g.`supplier_guid` AND a.`customer_guid` = g.`customer_guid` AND a.`memo_type` = g.`memo_type` WHERE a.term_download = '1' AND a.`memo_type` NOT IN ('outright', 'consignment', 'both') GROUP BY a.`supplier_guid`) a WHERE (a.counting = '1' OR a.counting = '0')");
      //echo $this->db->last_query(); die;
      $data = array(
          'query_special' => $query_special->result(),
      );

      echo json_encode($data);
    }

    public function setting_status()
    {
        $setting_guid = $this->input->post("setting_guid");
        $customer_guid = $this->input->post("customer_guid");
        $supplier_guid = $this->input->post("supplier_guid");
        $memo_type = $this->input->post("memo_type");
        $action_type = $this->input->post("action_type");
        $action_status = $this->input->post("action_status");
        $now = $this->db->query("SELECT now() as now")->row('now');
        
        $session_guid = $_SESSION['user_guid'];
        $user_id = $this->db->query("SELECT user_id FROM lite_b2b.set_user WHERE user_guid = '$session_guid' ")->row('user_id');

        if($setting_guid == 'nodata')
        {
            $setting_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
        }
        
        if($action_type == 'add')
        {
          $insert_data = $this->db->query("INSERT INTO `lite_b2b`.`reg_upload_settings` (`setting_guid`, `customer_guid`, `supplier_guid`, `memo_type`, `created_at`, `created_by`,`action_status`) VALUES ('$setting_guid', '$customer_guid', '$supplier_guid', '$memo_type', '$now', '$user_id','$action_status');");  
        }

        if($action_type == 'remove')
        {
          $remove_data = $this->db->query("DELETE FROM lite_b2b.reg_upload_settings WHERE setting_guid = '$setting_guid'");  
        }

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
               'para1' => 0,
               'msg' => 'Succesfully.',
               //'link' => $url_link,
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Data No Recorded',
            //'link' => 'Unknown URL.',

            );    
            echo json_encode($data);  
            exit(); 
        }
        
    }

    public function term_sheet()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && in_array('IAVA', $_SESSION['module_code']))
        { 
            $acc_guid = $_SESSION['customer_guid'];

            $supplier = $this->db->query("SELECT supplier_name,supplier_guid FROM lite_b2b.set_supplier WHERE isactive = '1' ORDER BY supplier_name ASC");

            $acc = $this->db->query("SELECT * FROM lite_b2b.acc WHERE isactive = '1' ");

            $data = array(
                'supplier' => $supplier->result(),
                'acc' => $acc->result(),
            );
            $this->load->view('header');
            $this->load->view('register/term_sheet', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function term_sheet_table()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $user_guid = $_SESSION['user_guid'];
        $acc_guid = $_SESSION['customer_guid'];
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
            0=>'data_guid',
            1=>'acc_name',
            2=>'new_supplier_name',
            3=>'service_date',
            4=>'billing_start_date',
            5=>'one_off_start_date',
            6=>'one_off_end_date',
            7=>'one_off_price',
            8=>'created_at',
            9=>'updated_at',
            10=>'updated_by',

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

        //print_r($supplier_guid); die;

        $sql = "SELECT a.*, b.acc_name, c.`supplier_name` AS new_supplier_name, IF(a.`one_off_start_date` = '1001-01-01' AND a.`one_off_end_date` = '1001-01-01','0','1') AS one_off_status, d.`memo_type`, IF(d.`memo_type` = 'outright' OR d.`memo_type` = 'consignment' OR d.`memo_type` = 'both' , '0','1') AS checking_type FROM lite_b2b.reg_term_data a INNER JOIN lite_b2b.acc b ON a.`customer_guid` = b.`acc_guid` INNER JOIN lite_b2b.`set_supplier` c ON a.`supplier_guid` = c.`supplier_guid` INNER JOIN lite_b2b.register_new d ON a.`register_guid` = d.`register_guid` WHERE a.customer_guid != '13EE932D98EB11EAB05B000D3AA2838A' ";
        
        $query = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query.$order_query.$limit_query;

        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

        $result = $this->db->query($query);

        //echo $this->db->last_query(); die;

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
            
            $nestedData['data_guid'] = $row->data_guid;
            $nestedData['customer_guid'] = $row->customer_guid;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['new_supplier_name'] = $row->new_supplier_name;
            $nestedData['register_guid'] = $row->register_guid;
            $nestedData['one_off_status'] = $row->one_off_status;
            $nestedData['service_date'] = $row->service_date;
            $nestedData['billing_start_date'] = $row->billing_start_date;
            if($row->one_off_status == '1')
            {
                $nestedData['one_off_start_date'] = $row->one_off_start_date;
                $nestedData['one_off_end_date'] = $row->one_off_end_date;
                $nestedData['one_off_price'] = $row->one_off_price;
            }
            else
            {
                $nestedData['one_off_start_date'] = '';
                $nestedData['one_off_end_date'] = '';
                $nestedData['one_off_price'] = '';
            }

            $nestedData['created_at'] = $row->created_at;
            $nestedData['updated_at'] = $row->updated_at;
            $nestedData['updated_by'] = $row->updated_by;
            $nestedData['memo_type'] = $row->memo_type;
            $nestedData['checking_type'] = $row->checking_type;

            
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

    public function edit_term_data()
    {
        $data_guid = $this->input->post("data_guid");
        $customer_guid = $this->input->post("customer_guid");
        $supplier_guid = $this->input->post("supplier_guid");
        $register_guid = $this->input->post("register_guid");
        $service_date = $this->input->post("service_date");
        $billing_start_date = $this->input->post("billing_start_date");
        $one_off_start_date = $this->input->post("one_off_start_date");
        $one_off_end_date = $this->input->post("one_off_end_date");
        $one_off_price = $this->input->post("one_off_price");
        $user_name = $this->db->query("SELECT a.user_name FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_name');
        $updated_at = $this->db->query("SELECT now() as now")->row('now');

        $check_data = $this->db->query("SELECT data_guid FROM lite_b2b.reg_term_data WHERE data_guid = '$data_guid' AND customer_guid = '$customer_guid' AND supplier_guid = '$supplier_guid'");

        if($check_data->num_rows() == 0)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'Data Not Found.',
            );    
            echo json_encode($data);  
            exit(); 
        }

        if($one_off_start_date == '')
        {
            $one_off_start_date = '1001-01-01';
        }

        if($one_off_end_date == '')
        {
            $one_off_end_date = '1001-01-01';
        }

        if($one_off_price != '')
        {
            $new_one_off_price = "<b> *RM ".number_format((float)$one_off_price, 2, '.', '')." only per annum</b>";
        }

        $data = array(  
            'service_date' => $service_date,
            'billing_start_date' => $billing_start_date,
            'one_off_start_date' => $one_off_start_date,
            'one_off_end_date' => $one_off_end_date,
            'one_off_price' => $new_one_off_price,
            'updated_at' => $updated_at,
            'updated_by' => $user_name,
          );
      
        $this->db->where('data_guid', $data_guid);
        $this->db->where('customer_guid', $customer_guid);
        $this->db->where('supplier_guid', $supplier_guid);
        $this->db->update('lite_b2b.reg_term_data', $data);

        $error = $this->db->affected_rows();

        if($error > 0){

        $data = array(
            'para1' => 'true',
            'msg' => 'Update Successfully',

            );    
            echo json_encode($data);   
        }
        else
        {   
            $data = array(
            'para1' => 'false',
            'msg' => 'Error Update.',

            );    
            echo json_encode($data);   
        }
    }
}
