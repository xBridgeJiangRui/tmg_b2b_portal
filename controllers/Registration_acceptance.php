<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Registration_acceptance extends CI_Controller
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

            $query_pending = $this->db->query("SELECT b.`acc_name`, c.supplier_name, a.* FROM lite_b2b.`reg_acceptance` a INNER JOIN lite_b2b.acc b ON a.`customer_guid` = b.`acc_guid` INNER JOIN lite_b2b.`set_supplier` c ON a.`supplier_guid` = c.`supplier_guid` WHERE a.`status` IN ('', 'Pending') ;");

            $query_normal = $this->db->query("SELECT a.`acc_name`, a.`comp_name`, a.`comp_contact`, a.`comp_email` FROM lite_b2b.register_new a WHERE a.`isacceptance` = '0' AND a.`form_status` = 'Registered' AND a.`customer_guid` = 'B00CA0BE403611EBA2FC000D3AC8DFD7'");

            $data = array(
                'supplier' => $supplier->result(),
                'acc' => $acc->result(),
                'query_pending' => $query_pending,
                'query_normal' => $query_normal,

            );
            $this->load->view('header');
            $this->load->view('register/acceptance_list', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function acceptance_table()
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
            0=>'acceptance_guid',
            1=>'acc_name',
            2=>'supplier_name',
            3=>'status',
            4=>'created_at',
            5=>'created_by',
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
            $sql = "SELECT a.*, b.acc_name, c.`supplier_name` FROM lite_b2b.reg_acceptance a INNER JOIN lite_b2b.acc b ON a.`customer_guid` = b.`acc_guid` INNER JOIN lite_b2b.`set_supplier` c ON a.`supplier_guid` = c.`supplier_guid`";
        }
        else
        {
            $sql = "SELECT a.*, b.acc_name, c.`supplier_name` FROM lite_b2b.reg_acceptance a INNER JOIN lite_b2b.acc b ON a.`customer_guid` = b.`acc_guid` INNER JOIN lite_b2b.`set_supplier` c ON a.`supplier_guid` = c.`supplier_guid` WHERE a.supplier_guid IN ($supplier_guid) AND customer_guid = '$acc_guid'";
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
            
            $nestedData['acceptance_guid'] = $row->acceptance_guid;
            $nestedData['customer_guid'] = $row->customer_guid;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['supplier_name'] = $row->supplier_name;
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
        $acceptance_guid = $this->input->post("acceptance_guid");

        $select_data = $this->db->query("SELECT * FROM lite_b2b.reg_acceptance WHERE acceptance_guid = '$acceptance_guid'");

        if($select_data->num_rows() == 1)
        {
            $update_data = $this->db->query("UPDATE lite_b2b.reg_acceptance SET `status` = 'Accepted' WHERE acceptance_guid = '$acceptance_guid'");
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
        $acceptance_guid = $this->input->post("acceptance_guid");

        $select_data = $this->db->query("SELECT * FROM lite_b2b.reg_acceptance WHERE acceptance_guid = '$acceptance_guid'");

        if($select_data->num_rows() == 1)
        {
            $acc_guid = $select_data->row('customer_guid');
            $supplier_guid = $select_data->row('supplier_guid');
            $register_guid = $select_data->row('register_guid');
            $url = $select_data->row('url');
            $file_name = basename($url);
            $file_config_main_path = $this->file_config_b2b->file_path_name($acc_guid,'web','reg_acpt','main_path','REGACPT');
            $unlink_path = $file_config_main_path.$acc_guid.'/'.$register_guid.'/'.$file_name.'';

            if(file_exists($unlink_path)){

                //unlink($unlink_path);
                $update_reg_data = $this->db->query("UPDATE lite_b2b.register_new SET `isacceptance` = '0' WHERE register_guid = '$register_guid' AND supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND isacceptance = '1'");
                $update_data = $this->db->query("UPDATE lite_b2b.reg_acceptance SET `status` = 'Rejected' WHERE acceptance_guid = '$acceptance_guid'");
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

      $vendor = $this->db->query("SELECT a.`memo_type`, b.`customer_guid`, b.`supplier_guid`, b.`user_guid`, d.`supplier_name`, d.`reg_no`, e.`acc_name`, c.`status`, c.`url` FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.`supplier_guid` = b.`supplier_guid` AND a.customer_guid = b.`customer_guid` LEFT JOIN lite_b2b.`reg_acceptance` c ON a.`register_guid` = c.`register_guid` AND b.`customer_guid` = c.`customer_guid` AND b.`supplier_guid` = c.`supplier_guid` INNER JOIN lite_b2b.`set_supplier` d ON b.`supplier_guid` = d.`supplier_guid` INNER JOIN lite_b2b.`acc` e ON b.`customer_guid` = e.`acc_guid` WHERE a.isacceptance = '0' AND a.`customer_guid` = '$type_val' GROUP BY a.`register_guid`");
      //echo $this->db->last_query(); die;
      $data = array(
          'vendor' => $vendor->result(),
      );

      echo json_encode($data);
    }

    public function normal_term_tb()
    {
      $query_normal = $this->db->query("SELECT a.`acc_name`,a.`comp_name`,a.`comp_contact`, a.`second_comp_contact`, a.`comp_email`,a.`update_at` FROM lite_b2b.register_new a WHERE a.`isacceptance` = '0' AND a.`form_status` = 'Registered' AND a.`customer_guid` = 'B00CA0BE403611EBA2FC000D3AC8DFD7' ");

      //echo $this->db->last_query(); die;
      $data = array(
          'query_normal' => $query_normal->result(),
      );

      echo json_encode($data);
    }
}
