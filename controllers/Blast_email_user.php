<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blast_email_user extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->load->database();
    $this->load->library('pagination');
    $this->load->library('form_validation');
    $this->load->library(array('session'));
    $this->load->library('session');
    $this->load->helper('html');

    //load the department_model
  }

  public function index()
  {
    if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
    {
      $get_acc = $this->db->query("SELECT acc_guid,acc_name FROM lite_b2b.acc WHERE isactive = '1' ORDER BY acc_name ASC ");

      $get_supplier = $this->db->query("SELECT supplier_guid,supplier_name FROM lite_b2b.set_supplier WHERE isactive = '1' AND suspended = '0' ORDER BY supplier_name ASC ");

      $get_module = $this->db->query("SELECT user_group_guid,user_group_name FROM lite_b2b.set_user_group WHERE isactive = '1' AND module_group_guid = '6595A39AD4AE11E7861FA81E8453CCF0' ORDER BY user_group_name ASC ");

      $data = array(
        'get_acc' => $get_acc->result(),
        'get_supplier' => $get_supplier->result(),
        'get_module' => $get_module->result(),
      );

      $this->load->view('header');
      $this->load->view('email_template/email_user_group', $data);
      $this->load->view('footer');

    } 
    else
    {
      $this->session->set_flashdata('message', 'Session Expired! Please relogin');
      redirect('#');
    }
  }

  public function list_tb()
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
      2=>'type',
      3=>'email_group_name',
      4=>'description',
      5=>'activate',
      6=>'created_at',
      7=>'created_by',
      8=>'updated_at',
      9=>'updated_by',
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

    $sql = "SELECT a.*,b.acc_name FROM lite_b2b.set_email a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid";
    
    $query = "SELECT * FROM ( ".$sql." ) aa ".$like_first_query.$like_second_query.$order_query.$limit_query;
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
        $nestedData['type'] = $row->type;
        $nestedData['acc_name'] = $row->acc_name;
        $nestedData['customer_guid'] = $row->customer_guid;
        $nestedData['email_group_name'] = $row->email_group_name;
        $nestedData['description'] = $row->description;
        $nestedData['created_at'] = $row->created_at;
        $nestedData['created_by'] = $row->created_by;
        $nestedData['updated_at'] = $row->updated_at;
        $nestedData['updated_by'] = $row->updated_by;
        $nestedData['activate'] = $row->activate;
        if($row->activate == '' || $row->activate == 'null' || $row->activate == null || $row->activate == '0')
        {
            $nestedData['active'] = 'No';
        }
        else
        {
            $nestedData['active'] = 'Yes';
        }
        
        
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

  public function list_tb_child()
  {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0); 

    $guid = $this->input->post("guid");

    $data = $this->db->query("SELECT a.`guid`, a.`email_group_guid`, a.`customer_guid`, a.`supplier_guid`, a.`user_email`, a.`created_at`, a.`created_by`, a.`updated_by`, a.`updated_at`,a.`is_active`, IF(a.`is_active` = '1' , 'Yes', 'No') AS active, c.`acc_name`,IFNULL(d.`supplier_name`,'') AS supplier_name FROM lite_b2b.set_email_group a INNER JOIN lite_b2b.`set_email` b ON a.`email_group_guid` = b.`guid` INNER JOIN lite_b2b.acc c ON a.`customer_guid` = c.`acc_guid` LEFT JOIN lite_b2b.`set_supplier` d ON a.`supplier_guid` = d.`supplier_guid` WHERE a.`email_group_guid` = '$guid' ");

    //echo $this->db->last_query(); die;

    $output = array(
      "data" => $data->result(),
    );

    echo json_encode($output);
  }

  public function fetch_user()
  {
    $customer_guid = $this->input->post("customer_guid");
    $supplier_guid = $this->input->post("supplier_guid");
    
    $content = $this->db->query("SELECT b.user_guid, b.user_id, b.user_name FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.set_user b ON a.`user_guid` = b.`user_guid` AND b.`isactive` = '1' WHERE a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid' GROUP BY a.user_guid,a.`supplier_guid`,a.`customer_guid` ");

    $body_content = $content->row('body_content');

    $data = array(
      "para1" => 'true',
      "content" => $content->result(),
      //"body_content" => $body_content,
    );

    echo json_encode($data);
  }

  public function fetch_user_by_module()
  {
    $customer_guid = $this->input->post("customer_guid");
    //$supplier_guid = $this->input->post("supplier_guid");
    $module_group_guid = $this->input->post("module_guid");
    
    $content = $this->db->query("SELECT * FROM lite_b2b.set_user a INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.user_guid = b.user_guid WHERE a.user_group_guid = '$module_group_guid' AND b.customer_guid = '$customer_guid' AND a.user_id LIKE '%@%' GROUP BY a.user_id; ");

    $body_content = $content->row('body_content');

    $data = array(
      "para1" => 'true',
      "content" => $content->result(),
      //"body_content" => $body_content,
    );

    echo json_encode($data);
  }

  public function fetch_cc_user()
  {
    $user_guid = $this->input->post("user_guid");
    $customer_guid = $this->input->post("customer_guid");
    $supplier_guid = $this->input->post("supplier_guid");
    
    $content = $this->db->query("SELECT b.user_guid, b.user_id, b.user_name FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.set_user b ON a.`user_guid` = b.`user_guid` AND b.`isactive` = '1' WHERE a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid' AND b.user_guid != '$user_guid' GROUP BY a.user_guid,a.`supplier_guid`,a.`customer_guid` ");

    //echo $this->db->last_query(); die;

    $body_content = $content->row('body_content');

    $data = array(
      "para1" => 'true',
      "content" => $content->result(),
      //"body_content" => $body_content,
    );

    echo json_encode($data);
  }

  public function add_email_group()
  {
    $user_guid = $_SESSION['user_guid'];
    $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');
    $user_id = $this->db->query("SELECT a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');
    $add_retailer = $this->input->post('add_retailer');
    $add_type = $this->input->post('add_type');
    $add_group_name = $this->input->post('add_group_name');
    $add_description = $this->input->post('add_description');
    $add_active = $this->input->post('add_active');

    // if($get_header_footer->num_rows() == 0)
    // {
    //   $data = array(
    //     'para1' => 'false',
    //     'msg' => 'Data Not Found.',
    //     );    
    //   echo json_encode($data);  
    //   exit(); 
    // }
    //print_r($add_content); die;

    $data = array(
        'guid' => $guid,
        'customer_guid' => $add_retailer,
        'type' => $add_type,
        'email_group_name' => $add_group_name,
        'description' => $add_description,
        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
        'created_by' => $user_id,
        'activate' => $add_active,
    );
    $this->db->insert('lite_b2b.set_email', $data);

    $error = $this->db->affected_rows();

    if($error > 0){

        $data = array(
       'para1' => 'true',
       'msg' => 'Insert Successfully.',
        );    
        echo json_encode($data);   
        exit();
    }
    else
    {   
        $data = array(
        'para1' => 'false',
        'msg' => 'Error to insert.',
        );    
        echo json_encode($data);  
        exit(); 
    }
  }

  //no use
  public function add_email_details()
  {
    $user_guid = $_SESSION['user_guid'];
    $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');
    $user_id = $this->db->query("SELECT a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');
    $main_guid = $this->input->post('main_guid');
    $add_retailer_child = $this->input->post('add_retailer_child');
    $add_supplier_child = $this->input->post('add_supplier_child');
    $add_user_child = $this->input->post('add_user_child');
    $s_email = $this->input->post('s_email');
    //$add_cc_user = $this->input->post('add_cc_user');
    $add_active = $this->input->post('add_active_child');

    $email_address = implode(',',$s_email);

    $get_user = $this->db->query("SELECT a.user_id,a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$add_user_child'")->result_array();

    if(count($get_user) > 0 )
    {
      $email_address = $get_user[0]['user_id'];
    }

    $check_data_1 = $this->db->query("SELECT guid FROM lite_b2b.set_email_group WHERE email_group_guid = '$main_guid' AND user_email = '$email_address' ");

    if($check_data_1->num_rows() > 0)
    {
        $data = array(
            'para1' => 'false',
            'msg' => 'Duplicate Email Address.',
            );    
        echo json_encode($data);  
        exit(); 
    }

    $data = array(
        'guid' => $guid,
        'email_group_guid' => $main_guid,
        'customer_guid' => $add_retailer_child,
        'supplier_guid' => $add_supplier_child,
        //'user_guid' => $add_user_child,
        'user_email' => $email_address,
        //'cc_email' => implode(",",$add_cc_user),
        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
        'created_by' => $user_id,
        'is_active' => $add_active,
    );
    $this->db->insert('lite_b2b.set_email_group', $data);

    $error = $this->db->affected_rows();

    if($error > 0){

        $data = array(
       'para1' => 'true',
       'msg' => 'Insert Successfully.',
        );    
        echo json_encode($data);   
        exit();
    }
    else
    {   
        $data = array(
        'para1' => 'false',
        'msg' => 'Error to insert.',
        );    
        echo json_encode($data);  
        exit(); 
    }
  }

  public function add_email_multiple()
  {
    $user_guid = $_SESSION['user_guid'];
    $user_id = $this->db->query("SELECT a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');
    $main_guid = $this->input->post('main_guid');
    $add_module_guid = $this->input->post('add_module_guid');
    $add_retailer_child = $this->input->post('add_retailer_child');
    $add_supplier_child = $this->input->post('add_supplier_child');
    $add_user_child = $this->input->post('add_user_child');
    $add_active = $this->input->post('add_active_child');

    if($add_user_child == '')
    {
      $email_merge = $this->input->post('s_email');
      $set_email = 'true';
    }
    else
    {
      $email_merge = $add_user_child;
      $set_email = '';
    }
    //$email_address = implode(',',$add_user_child);
    
    foreach($email_merge as $row => $value)
    {
      $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

      if($set_email != 'true')
      {
        $get_user = $this->db->query("SELECT a.user_id,a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$value'")->result_array();

        if(count($get_user) > 0 )
        {
          $email_address = $get_user[0]['user_id'];
        }
      }
      else
      {
        $email_address = $value;
      }

      $check_data_1 = $this->db->query("SELECT guid FROM lite_b2b.set_email_group WHERE email_group_guid = '$main_guid' AND user_email = '$email_address' ");
      //echo $this->db->last_query(); die;
      if($check_data_1->num_rows() > 0)
      {
        continue;
      }
  
      $data = array(
          'guid' => $guid,
          'email_group_guid' => $main_guid,
          'customer_guid' => $add_retailer_child,
          'supplier_guid' => $add_supplier_child,
          //'user_guid' => $add_user_child,
          'user_email' => $email_address,
          //'cc_email' => implode(",",$add_cc_user),
          'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
          'created_by' => $user_id,
          'is_active' => $add_active,
      );
      $this->db->insert('lite_b2b.set_email_group', $data);
    }

    $error = $this->db->affected_rows();

    if($error > 0){

        $data = array(
       'para1' => 'true',
       'msg' => 'Insert Successfully.',
        );    
        echo json_encode($data);   
        exit();
    }
    else
    {   
        $data = array(
        'para1' => 'false',
        'msg' => 'Error to insert.',
        );    
        echo json_encode($data);  
        exit(); 
    }
  }

  public function edit_email_user_group()
  {
      $user_guid = $_SESSION['user_guid'];
      $user_id = $this->db->query("SELECT a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');
      $guid = $this->input->post('guid');
      $customer_guid = $this->input->post('customer_guid');
      $type = $this->input->post('doc_type');
      $email_group_name = $this->input->post('email_group_name');
      $description = $this->input->post('description');
      $is_active = $this->input->post('is_active');
      //$editable.html(dom.html($edit_content) || dom.emptyPara);

      //print_r($template_guid); die;

      $check_data = $this->db->query("SELECT guid FROM lite_b2b.set_email WHERE guid = '$guid'");

      if($check_data->num_rows() == 0)
      {
        $data = array(
          'para1' => 'false',
          'msg' => 'Data Not Found.',
          );    
        echo json_encode($data);  
        exit(); 
      }

      $data = array(
        'customer_guid' => $customer_guid,
        'type' => $type,
        'email_group_name' => $email_group_name,
        'description' => $description,
        'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
        'updated_by' => $user_id,
        'activate' => $is_active,
      );
      $this->db->where('guid', $guid);
      $this->db->update('lite_b2b.set_email', $data);

      $error = $this->db->affected_rows();

      if($error > 0){

          $data = array(
             'para1' => 'true',
             'msg' => 'Update Successfully.',
          );    
          echo json_encode($data);   
          exit();
      }
      else
      {   
          $data = array(
          'para1' => 'false',
          'msg' => 'Error to update.',
          );    
          echo json_encode($data);  
          exit(); 
      }
  }

  public function delete_email_user_group()
  {
    $user_guid = $_SESSION['user_guid'];
    $guid = $this->input->post('guid');

    $check_data = $this->db->query("SELECT guid FROM lite_b2b.set_email WHERE guid = '$guid'");

    if($check_data->num_rows() == 0)
    {
        $data = array(
            'para1' => 'false',
            'msg' => 'Data Not Found.',
            );    
        echo json_encode($data);  
        exit(); 
    }

    $delete_data = $this->db->query("DELETE FROM `lite_b2b`.`set_email` WHERE `guid` = '$guid' ");

    $delete_child = $this->db->query("DELETE FROM `lite_b2b`.`set_email_group` WHERE email_group_guid = '$guid'");

    $error = $this->db->affected_rows();

    if($error > 0){

        $data = array(
           'para1' => 'true',
           'msg' => 'Delete Successfully.',
        );    
        echo json_encode($data);   
        exit();
    }
    else
    {   
        $data = array(
        'para1' => 'false',
        'msg' => 'Error to delete.',
        );    
        echo json_encode($data);  
        exit(); 
    }
  }

  public function delete_email_details()
  {
    $user_guid = $_SESSION['user_guid'];
    $main_guid = $this->input->post('main_guid');
    $guid = $this->input->post('c_guid');

    //print_r($guid); die;

    $check_data = $this->db->query("SELECT guid FROM lite_b2b.set_email_group WHERE email_group_guid = '$main_guid' AND guid = '$guid'");

    if($check_data->num_rows() == 0)
    {
        $data = array(
            'para1' => 'false',
            'msg' => 'Data Not Found.',
            );    
        echo json_encode($data);  
        exit(); 
    }

    $delete_data = $this->db->query("DELETE FROM `lite_b2b`.`set_email_group` WHERE `guid` = '$guid' AND email_group_guid = '$main_guid'");

    $error = $this->db->affected_rows();

    if($error > 0){

        $data = array(
           'para1' => 'true',
           'msg' => 'Delete Successfully.',
        );    
        echo json_encode($data);   
        exit();
    }
    else
    {   
        $data = array(
        'para1' => 'false',
        'msg' => 'Error to delete.',
        );    
        echo json_encode($data);  
        exit(); 
    }
  }

  public function edit_email_activate()
  {
    $user_guid = $_SESSION['user_guid'];
    $user_id = $this->db->query("SELECT a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');
    $main_guid = $this->input->post('main_guid');
    $details = $this->input->post('details');
    $is_active = $this->input->post('isactive_val');

    $count_array = count($details);
    $implode_guid = implode("','",$details);
    $implode_guid = "'".$implode_guid."'";

    if($is_active == '0')
    {
      $check_deactive = $this->db->query("SELECT guid FROM lite_b2b.set_email_group WHERE email_group_guid = '$main_guid'")->num_rows();
      $check_details = $this->db->query("SELECT guid FROM lite_b2b.set_email_group WHERE email_group_guid = '$main_guid' AND guid IN ($implode_guid)")->num_rows();

      if($check_deactive == $check_details)
      {
        $data = array(
          'para1' => 'false',
          'msg' => 'Error Deactive all data. Please remain one Email Address.',
        );    
        echo json_encode($data);  
        exit(); 
      }
    }

    $check_data = $this->db->query("SELECT guid FROM lite_b2b.set_email_group WHERE email_group_guid = '$main_guid' AND guid IN ($implode_guid) ")->num_rows();
    $now = $this->db->query("SELECT NOW() as now")->row('now');

    if($check_data == $count_array)
    {
      $update_data = $this->db->query("UPDATE `lite_b2b`.`set_email_group` SET `updated_at` = '$now', `updated_by` = '$user_id', `is_active` = '$is_active' WHERE email_group_guid = '$main_guid' AND guid IN ($implode_guid) AND is_active != '$is_active' ");
    }
    else
    {
      $data = array(
        'para1' => 'false',
        'msg' => 'Error Update Get Data Not Match.',
      );    
      echo json_encode($data);  
      exit();
    }

    //echo $this->db->last_query();die;
  
    $error = $this->db->affected_rows();

    if($error > 0){

        $data = array(
           'para1' => 'true',
           'msg' => 'Update Successfully.',
        );    
        echo json_encode($data);   
        exit();
    }
    else
    {   
        $data = array(
        'para1' => 'false',
        'msg' => 'Error to update.',
        );    
        echo json_encode($data);  
        exit(); 
    }
  }

  public function file_upload()
  {
    $customer_guid = $_SESSION['customer_guid'];

    $email_group_guid = $this->input->post('email_group_guid');
    //$active = $this->db->query("SELECT a.isactive FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid  INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'")->row('isactive');

    //$user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');

    $user_id = $this->db->query("SELECT a.user_name FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_name');


    $file_uuid = $this->db->query("SELECT REPLACE(LOWER(UUID()),'-','') AS uuid")->row('uuid');
    $now = $this->db->query("SELECT NOW() as now")->row('now');

    $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'online_form', 'main_path', 'REG');

    $defined_path = $file_config_main_path; // /media/b2b-pdf/reg_import/;
    //print_r($defined_path); die;

    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0);
    ini_set('post_max_size', '64M');
    ini_set('upload_max_filesize', '64M');

    $config['upload_path']          = $defined_path;
    $config['allowed_types']        = '*';
    $config['max_size']             = 50000;
    $config['file_name'] = $file_uuid;
    // var_dump( $this->input->post('file') );die; 

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file')) {
      $error = array('error' => $this->upload->display_errors());

      if (null != $error) {
        $data = array(
          'para1' => 1,
          'msg' => 'Error do upload.',
        );
        echo json_encode($data);
        exit();
      } //close else

    } else {
      $data = array('upload_data' => $this->upload->data());

      // print_r($_FILES["file"]);

      $filename = $defined_path . $data['upload_data']['file_name'];

      //  Include PHPExcel_IOFactory
      $this->load->library('Excel');

      $inputFileName = $filename;

      //  Read your Excel workbook
      try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
      } catch (Exception $e) {

        $error_message = $this->lang->line('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        $xerror = $this->db->error();
        $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
        $this->message->error_message_with_status($xerror['message'], '1', '');
        exit();
      }

      unlink($filename);
    }
    //Get worksheet dimensions
    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    // $sheetCount = $sheet->getSheetCount();

    for ($row = 1; $row <= 1; $row++) {
      //  Read a row of data into an array
      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
      //  Insert row data array into your database of choice here

    }

    // print_r($objPHPExcel->getSheetCount());die;

    //$header_array = array();

    $header_array = ['Retailer Guid', 'Supplier Guid', 'Email'];

    $checking_array = array();

    //make array into string with comma
    $exheader = '';
    $exchild_main = '';
    $set_supplier_exchild = '';
    $update_syntax = '';

    $values = '';

    foreach ($rowData as $eheader) {
      foreach ($eheader as $row) {
        if ($row == '' || $row == null) {
          continue;
        } else {
          $row = $row;
        }

        $checking_array[] = $row;
        $update_syntax .= $row . ' = ' . 'VALUES(' . $row . '),';
      } //close small foreacH
    } //close loop header array

    $update_syntax = rtrim($update_syntax, ',');

    $result = array_diff($header_array, $checking_array); //compare form

    if (null != $result) {

      $message = '';

      foreach ($result as $header) {
        $message .= $header . ' ';
      }

      $data = array(
        'para1' => 1,
        'msg' => $message . 'header is required.',
      );
      echo json_encode($data);
      // $error_message = $message.$this->lang->line('header_is_required');
      // $xerror = $this->db->error();
      // $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
      // $this->message->error_message_with_status($xerror['message'], '1', '');
      exit();
    } //close if

    $result = array_diff($checking_array, $header_array); //compare form

    if (null != $result) {

      $message = '';

      foreach ($result as $header) {
        $message .= $header . ' ';
      }

      $data = array(
        'para1' => 1,
        'msg' => $message . 'header is not exists.',
      );
      echo json_encode($data);
      // $error_message = $message.$this->lang->line('header_is_not_exist');
      // $xerror = $this->db->error();
      // $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
      // $this->message->error_message_with_status($xerror['message'], '1', '');
      exit();
    } //close if

    $check_escape_header_index = array();

    foreach ($rowData as $eheader) {
      foreach ($eheader as $key => $row) {
        if ($row == '' || $row == null) {
          $check_escape_header_index[] = $key;

          continue;
        } else {
          $row = $row;
        }

        $exheader .= $row . ',';
      } //close loop through row
    } //close loop header array

    $r = '0';
    for ($xrow = 2; $xrow <= $highestRow; $xrow++) {
      //  Read a row of data into an array
      $xrowData = $sheet->rangeToArray('A' . $xrow . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);

      $search_array = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

      $type_search = array_search('Retailer Guid', $search_array[0]);

      $exchild = '';

      //if($this->isEmptyRow(reset($xrowData))) { continue; }

      foreach ($xrowData as $echild) {
        foreach ($echild as $key => $row2) {
          if ($key == $type_search) {
            if (!($row2 == '' && $row2 == null)) {
              $query_acc = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$row2' ")->result_array();
              //echo $this->db->last_query(); die;
              $r++;
              if (count($query_acc) == 0) {
                $data = array(
                  'para1' => 1,
                  'msg' => 'Invalid Retailer Name : ' . $row2 . '.',
                );
                echo json_encode($data);
                exit();
              } //close num rows
            } //close else
          } //close itemcode
        } //close foreach td itemcode
      } //close loop row

    } //close foreach child data checking

    $r = '0';
    for ($xrow = 2; $xrow <= $highestRow; $xrow++) {
      //  Read a row of data into an array
      $xrowData = $sheet->rangeToArray('B' . $xrow . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);

      $search_array = $sheet->rangeToArray('B' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

      $type_search = array_search('Supplier Guid', $search_array[0]);

      $exchild = '';

      //if($this->isEmptyRow(reset($xrowData))) { continue; }

      foreach ($xrowData as $echild) {
        foreach ($echild as $key => $row2) {
          if ($key == $type_search) {
            if (!($row2 == '' && $row2 == null)) {
              
              $query_supplier = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$row2' AND isactive = '1' AND suspended = '0' ")->result_array();

              $r++;
              if (count($query_supplier) == 0) {
                $data = array(
                  'para1' => 1,
                  'msg' => 'Invalid Supplier or not active or suspended : ' . $row2 ,
                );
                echo json_encode($data);
                exit();
              } //close num rows
            } //close else

          } //close itemcode
        } //close foreach td itemcode
      } //close loop row

    } //close foreach child data checking

    $r = '0';
    for ($xrow = 2; $xrow <= $highestRow; $xrow++) {
      //  Read a row of data into an array
      $xrowData = $sheet->rangeToArray('C' . $xrow . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);

      $search_array = $sheet->rangeToArray('C' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

      $type_search = array_search('Email', $search_array[0]);

      $exchild = '';

      //if($this->isEmptyRow(reset($xrowData))) { continue; }

      foreach ($xrowData as $echild) {
        foreach ($echild as $key => $row2) {
          if ($key == $type_search) {
            if (!($row2 == '' && $row2 == null)) {
              
              $atPos = mb_strpos($row2, '@');
              // Select the domain
              $domain = mb_substr($row2, $atPos + 1);
          
              if(filter_var($row2, FILTER_VALIDATE_EMAIL) && checkdnsrr($domain . '.', 'MX')) 
              {
                $query_email = $this->db->query("SELECT * FROM lite_b2b.set_email_group WHERE user_email = '$row2' AND email_group_guid = '$email_group_guid'")->result_array();

                $r++;
  
                if (count($query_email) > 0) {
                  $data = array(
                    'para1' => 1,
                    'msg' => 'Duplicate Email in the Same Email Group : ' . addslashes($row2) ,
                  );
                  echo json_encode($data);
                  exit();
                } //close num rows
              }
              else 
              {
                $data = array(
                  'para1' => 1,
                  'msg' => 'Invalid Email Address : ' . addslashes($row2) ,
                );
                echo json_encode($data);
                exit();
              }
            } //close else

          } //close itemcode
        } //close foreach td itemcode
      } //close loop row

    } //close foreach child data checking

    $lexheader = rtrim($exheader, ',');

    $string = '';
    $string_main = '';
    $typehead = '';
    $valuechild = '';
    $i = '0';

    for ($xrow = 2; $xrow <= $highestRow; $xrow++) {
      //  Read a row of data into an array
      $xrowData = $sheet->rangeToArray('A' . $xrow . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);

      $search_array = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

      $exchild = '';

      //if($this->isEmptyRow(reset($xrowData))) { continue; }

      foreach ($xrowData as $echild) {
        unset($checking_child);
        $v = 0;
        foreach ($echild as $key => $row2) {

          if (in_array($key, $check_escape_header_index)) {
            continue;
          }

          $exchild .= "'" . addslashes($row2) . "',";

          $v++;
        } //close foreach
        $new_uuid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');

        $exchild_main .= "(" . $exchild . "'$new_uuid','$email_group_guid','$now','$user_id','1'),";
        
      } //5
      $i++;
    } //close loop row

    $exchild_main = rtrim($exchild_main, ',');

    if ($exchild_main == '' || $exchild_main == null) {
      $data = array(
        'para1' => 1,
        'msg' => 'No Data.',
      );
      echo json_encode($data);
      exit();
    }

    $insert_main = $this->db->query("INSERT INTO lite_b2b.set_email_group (`customer_guid`,`supplier_guid`,`user_email`,`guid`,`email_group_guid`,`created_at`,`created_by`,`is_active`) VALUES $exchild_main ");

    //echo $this->db->last_query(); die;

    $error = $this->db->affected_rows();

    if($error > 0) {

      $select_remove_dup = 0;

      $check_duplicate = $this->db->query("SELECT a.guid,a.user_email,COUNT(a.user_email) AS count_email FROM lite_b2b.set_email_group a
      WHERE a.email_group_guid = '$email_group_guid'
      AND a.is_active = '1'
      GROUP BY a.user_email
      HAVING count_email > 1")->result_array();
      
      if(count($check_duplicate) > 0)
      {
        $guid_duplicate = implode("','",array_filter(array_column($check_duplicate,'guid')));

        $email_duplicate = implode("','",array_filter(array_column($check_duplicate,'user_email')));

        $select_remove_dup = count($this->db->query("SELECT * FROM lite_b2b.set_email_group WHERE `guid` NOT IN ('$guid_duplicate') AND user_email IN ('$email_duplicate') AND email_group_guid = '$email_group_guid' AND is_active = '1'")->result_array());
  
        $remove_dup = $this->db->query("DELETE FROM lite_b2b.set_email_group WHERE `guid` NOT IN ('$guid_duplicate') AND user_email IN ('$email_duplicate') AND email_group_guid = '$email_group_guid' AND is_active = '1'");
      }

      $data = array(
        'para1' => 0,
        'msg' => 'Successfully Import. Removed Duplicate Email : '. $select_remove_dup,

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error Import.',

      );
      echo json_encode($data);
    }

    // }//close else for success upload file
  } //close file upload

  public function delete_email_multiple()
  {
    $user_guid = $_SESSION['user_guid'];
    $user_id = $this->db->query("SELECT a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');
    $main_guid = $this->input->post('main_guid');
    $details = $this->input->post('details');

    $count_array = count($details);
    $implode_guid = implode("','",$details);
    $implode_guid = "'".$implode_guid."'";

    $check_details = $this->db->query("SELECT `guid` FROM lite_b2b.set_email_group WHERE email_group_guid = '$main_guid' AND guid IN ($implode_guid)")->result_array();

    if(count($check_details) != $count_array)
    {
      $data = array(
        'para1' => 'false',
        'msg' => 'Error Find Data Not Tally.',
      );    
      echo json_encode($data);  
      exit(); 
    }

    $delete_data = $this->db->query("DELETE FROM `lite_b2b`.`set_email_group` WHERE guid IN ($implode_guid) AND email_group_guid = '$main_guid'");

    //echo $this->db->last_query();die;
  
    $error = $this->db->affected_rows();

    if($error > 0){

        $data = array(
           'para1' => 'true',
           'msg' => 'Delete Successfully.',
        );    
        echo json_encode($data);   
        exit();
    }
    else
    {   
        $data = array(
        'para1' => 'false',
        'msg' => 'Error to delete.',
        );    
        echo json_encode($data);  
        exit(); 
    }
  }
}
?>