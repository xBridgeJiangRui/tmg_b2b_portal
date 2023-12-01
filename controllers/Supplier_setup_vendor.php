<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_setup_vendor extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //$this->load->helper('url');
    //$this->load->helper(array('form', 'url'));
    $this->load->database();
    //$this->load->library('pagination');
    //$this->load->library('form_validation');
    //$this->load->library(array('session'));
    $this->load->library('session');
    //$this->load->helper('html');
    $this->api_url = 'http://20.212.51.33';
  }

  public function index()
  {
    if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
    {
        $customer_guid = $_SESSION['customer_guid'];
        $get_new_status = $this->db->query("SELECT b.`acc_guid`,b.`acc_name`, COUNT(b.`acc_name`) 
        AS numbering,IF(b.acc_guid = '$customer_guid' ,'1','2') AS sort FROM 
        lite_b2b.tmp_auto_mapping a INNER JOIN lite_b2b.acc b ON a.`customer_guid` 
        = b.acc_guid WHERE a.pending = '1' GROUP BY a.`customer_guid` 
        ORDER BY sort ASC , b.acc_name ASC");

        $data = array(
          'get_new_status' => $get_new_status,
          'customer_guid' => $customer_guid,
        );
        
        $this->load->view('header');
        $this->load->view('auto_mapping/vendor_list', $data);  
        $this->load->view('footer');
    } else {
        $this->session->set_flashdata('message', 'Session Expired! Please relogin');
        redirect('#');
    }
  }

  public function mapping_table()
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
        0=>'guid',
        1=>'acc_name',
        2=>'supplier_name',
        3=>'supplier_reg_no',
        4=>'vendor_code',
        5=>'pending',
        6=>'backend_type',
        7=>'created_at',
        8=>'created_by',
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

    $sql = "SELECT b.acc_name,a.* FROM lite_b2b.tmp_auto_mapping AS a INNER JOIN lite_b2b.acc AS b ON a.customer_guid = b.acc_guid ORDER BY b.acc_name ASC";

    $query = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query.$order_query.$limit_query;

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

        $nestedData['guid'] = $row->guid;
        $nestedData['backend_type'] = $row->backend_type;
        $nestedData['customer_guid'] = $row->customer_guid;
        $nestedData['acc_name'] = $row->acc_name;
        $nestedData['supplier_guid'] = $row->supplier_guid;
        $nestedData['supplier_name'] = $row->supplier_name;
        $nestedData['supplier_reg_no'] = $row->supplier_reg_no;
        $nestedData['vendor_code'] = $row->vendor_code;
        $nestedData['pending'] = $row->pending;
        $nestedData['created_at'] = $row->created_at;
        $nestedData['created_by'] = $row->created_by;

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
 
  public function update_status()
  {
    $details = $this->input->post("details");

    $url = $this->api_url;

    $to_shoot_url = "http://20.212.51.33/rest_b2b/index.php/Auto_mapping_vendor_code/flag_auto_vendor_code_status";

    //echo $to_shoot_url;die;
    $data = array();

    $cuser_name = 'ADMIN';
    $cuser_pass = '1234';

    $ch = curl_init($to_shoot_url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
    curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($details));
    $result = curl_exec($ch);
    $output = json_decode($result);

    //echo $result;die;

    curl_close($ch);

    if($output->status == "true")
    {
      $data = array(
        'para1' => 'true',
        'msg' => 'Update Successfully.',
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

  public function mapping_data_update()
  {
    $customer_guid = $_SESSION['customer_guid'];
    $user_guid = $_SESSION['user_guid'];
    $now_time = $this->db->query("SELECT NOW() AS now_time")->row('now_time');

    $logs_1 = array(
      'movement_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
      'customer_guid' => $customer_guid,
      'user_guid' => $user_guid,
      'action' => 'sync_mapping_code',
      'module' => 'mapping_data_update',
      'value' => $now_time,
      'created_at' => $now_time,
    );   
    $this->db->insert('lite_b2b.supplier_movement',$logs_1);

    $afrows = $this->db->affected_rows();

    if($afrows == 0) 
    {
      $data = array(
        'para1' => 'false',
        'msg' => 'Error Insert Log',
      );    
      echo json_encode($data); 
      exit();
    }
    
    $url = $this->api_url;
    //get method
    $to_shoot_url = "http://20.212.51.33/rest_b2b/index.php/Auto_mapping_vendor_code/auto_mapping_v3";

    //echo $to_shoot_url;die;
    $data = array();

    $cuser_name = 'ADMIN';
    $cuser_pass = '1234';

    $ch = curl_init($to_shoot_url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
    curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($details));
    $result = curl_exec($ch);
    $output = json_decode($result);

    //echo $result;die;

    curl_close($ch);

    if($output->status == "true")
    {
      $data = array(
        'para1' => 'true',
        'msg' => $output->message,
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
