<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class registration extends CI_Controller {
    
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->load->database();
    $this->load->library('pagination');
    $this->load->library('form_validation');
    $this->load->library('session');
    $this->load->model('Registration_model');
    $this->load->library('datatables');
    $this->load->library('Panda_PHPMailer');
    $this->local_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','local_ip','LIP');
  }
    
  public function index()
  {
    if($this->session->userdata('loginuser') == true)
    {
      $session_id = $_REQUEST['session_id'];
      $supplier_group_guid = $this->session->userdata('supplier_group_guid');
      $name = $this->db->query("SELECT * from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('acc_guid'); 
      $supplier = $this->db->query("SELECT * FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('supplier_guid'); 
      $sessiondata = array (
         'name' => $name,
         'supplier' =>$supplier,
         'session_id' => $session_id,

         );
      $this->session->set_userdata($sessiondata);
      $this->load->view('register/register.php', $sessiondata);   
    }
  }

  //add vendor site..
  public function register_vendor()
  {
    //$supplier = $this->db->query("SELECT * FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'  ")->result();
    $supplier = $this->db->query("SELECT a.* FROM set_supplier a ORDER BY a.supplier_name ASC")->result();
    $retailer = $this->db->query("SELECT DISTINCT c.acc_name FROM  acc c INNER JOIN set_supplier_group a ON c.acc_guid = a.customer_guid WHERE a.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('acc_name');

    $data = array(
      'supplier' => $supplier,
      'retailer' => $retailer
    );

    $this->load->view('header'); 
    $this->load->view('register/register_vendor', $data);  
    $this->load->view('footer' );  
  }

  //add vendor site..
  public function register_vendor_table()
  {
    ini_set('memory_limit', -1); 
    if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
    {
        $columns = array(
            0 => 'register_no',
            1 => 'supplier_name',
            2 => 'acc_name',
            3 => 'comp_email',
            4 => 'acc_no',
            5 => 'create_at',
            6 => 'create_by',
            7 => 'register_guid',
            8 => 'cnt',

        );
    }
    else
    {
        $columns = array(
            0 => 'register_no',
            1 => 'supplier_name',
            2 => 'acc_name',
            3 => 'create_at',
            4 => 'create_by',
            5 => 'action',
        );
    }


    $user_guid = $_SESSION['user_guid'];
    $user_group = $_SESSION['user_group_name'];
    $limit = $this->input->post('length');
    $start = $this->input->post('start');
    $order = $this->input->post('order');
    $dir = "";
    //$totalData = $this->Registration_model->register()->row('numrow');
    $customer_guid = $_SESSION['customer_guid'];
    $totalData = $this->db->query("SELECT COUNT(*) as numrow FROM lite_b2b.register_new WHERE customer_guid = '$customer_guid' ")->row('numrow');
    $totalFiltered = $totalData;

    $order_query = "";

    if(!empty($order))
    {
      foreach($order as $o)
      {
          $col = $o['column'];
          $dir= $o['dir'];

          $order_query .= $columns[$col]." ".$dir.",";

      }
    }   
    $dir = '';    
    $order_query = rtrim($order_query,',');

    $query = "
        SELECT 
        aa.`supplier_info_guid`,
        aa.`register_no`,
        aa.`supplier_name`,
        aa.`acc_name`,
        aa.`comp_email`,
        aa.`acc_no`,
        aa.`create_at`,
        aa.`create_by`,
        aa.`register_guid`,
        aa.`cnt`,
        aa.`part_cnt`,
        aa.`customer_guid`,
        aa.`form_status` 
      FROM
        (SELECT 
          c.`supplier_name`,
          e.`supplier_info_guid`,
          a.*,
          d.* 
        FROM
          register_add_user_main a 
          INNER JOIN acc b 
            ON b.acc_guid = a.`customer_guid` 
          LEFT JOIN set_supplier c 
            ON c.supplier_guid = a.`supplier_guid` 
          LEFT JOIN set_supplier_info e 
            ON a.`register_guid` = e.register_guid 
          LEFT JOIN 
            (SELECT DISTINCT 
              register_guid AS register_id,
              COUNT(`ven_name`) AS cnt, 
              COUNT(`part_name`) AS part_cnt
            FROM
              register_add_user_child 
            GROUP BY register_guid) d 
            ON a.`register_guid` = d.register_id) aa 
            WHERE aa.customer_guid = '".$_SESSION['customer_guid']."' ";

    $totalData = $this->db->query($query)->num_rows();
    $totalFiltered = $totalData;

    if(empty($this->input->post('search')['value']))
    {
        $posts = $this->Registration_model->allposts($query,$limit,$start,$order_query,$dir);
        //echo $this->db->last_query(); die;
    }
    else 
    {
        $search = $this->input->post('search')['value']; 

        $posts =  $this->Registration_model->posts_search($query,$limit,$start,$search,$order_query,$dir);

        $totalFiltered = $this->Registration_model->posts_search_count($query,$search);
    }

    $data = array();
    if(!empty($posts))
    {
        foreach ($posts as $post)
        {
            $nestedData['register_no'] = $post->register_no;
            $nestedData['supplier_name'] = $post->supplier_name;
            $nestedData['acc_name'] = $post->acc_name;
            $nestedData['comp_email'] = $post->comp_email;
            $nestedData['create_at'] = $post->create_at; 
            $nestedData['create_by'] = $post->create_by;
            $nestedData['cnt'] = $post->cnt; 
            //$nestedData['part_cnt'] = $post->part_cnt;
            $nestedData['acc_no'] = $post->acc_no;
            
           
           if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN'  ) {

            $nestedData['action'] = '<a register_guid='.$post->register_guid.'id="view_ticket" class="btn btn-xs btn-primary" type="button" href="register_forms_vendor?register_guid='.$post->register_guid.'"><i class="glyphicon glyphicon-pencil"></i></a><a register_guid='.$post->register_guid.'id="view_ticket" class="btn btn-xs btn-info" type="button" href="send_mail?session_id='.$post->supplier_info_guid.'" style="margin-left:5px;"><i class="glyphicon glyphicon-send"></i></a>';
                //<a register_guid='.$post->register_guid.'id="view_ticket" class="btn btn-xs btn-primary" type="button" href="register_form_view?register_guid='.$post->register_guid.'" style="margin-left:5px;"><i class="glyphicon glyphicon-eye-open"></i></a>
           } 
            $data[] = $nestedData;

        }
    }               

    $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
    
    echo json_encode($json_data); 
  }

  //add vendor site..
  public function transaction_vendor()
  {
    $comp_name = $this->input->post('comp_name');
    $register_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS register_guid")->row('register_guid');
    $register_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS register_c_guid")->row('register_c_guid');
    $supplier_query = $this->db->query("SELECT a.* FROM set_supplier a WHERE supplier_guid = '$comp_name' ORDER BY a.supplier_name ASC");
    $supplier_guid = $supplier_query->row('supplier_guid');
    $supplier_name = $supplier_query->row('supplier_name');
    $supplier_info_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS supplier_info_guid")->row('supplier_info_guid');
    $active = $this->db->query("SELECT a.isactive FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid  INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'")->row('isactive');
    $supplier = $this->db->query("SELECT a.*,b.* FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('supplier_guid');
    // $retailer = $this->db->query("SELECT acc_name FROM  acc c INNER JOIN register_new a ON c.acc_guid = a.customer_guid WHERE a.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('acc_name');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
    $session_id = $this->db->query("SELECT supplier_group_guid FROM set_supplier_group a WHERE a.`supplier_guid`= '$supplier_guid'")->row('supplier_group_guid');
    //a.`customer_guid`= '".$_SESSION['customer_guid']." ' AND
    $re_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(register_no, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS re_no  FROM `register`  WHERE  SUBSTRING(register_no, - 8, 4) = CONCAT( RIGHT(YEAR(NOW()), 2), LPAD(MONTH(NOW()), 2, 0) )")->row('re_no');
    $todaydate = date('Ydhs');
    $todaydate2 = substr($todaydate, 2);
    $register_no = $this->db->query("SELECT concat( '$todaydate2', '$re_no' ) as refno")->row('refno');
    $comp_no = $this->input->post('comp_no');
    $acc_name = $this->input->post('acc_name');
    $acc_no = $this->input->post('acc_no');
    
    $comp_email = $this->input->post('comp_email');

    $create_at = $this->db->query("SELECT now() as now")->row('now');
    $update_at = $this->db->query("SELECT now() as now")->row('now');

    $acc_no = implode(",",$acc_no);
    $acc_no = "".$acc_no."";

    $data = array(
      'register_guid' => $register_guid,
      'customer_guid' => $_SESSION['customer_guid'],
      'supplier_guid' => $supplier_guid,
      'create_at' => $create_at,
      'create_by' => $user_id,
      'update_at' => $update_at,
      'update_by' => $user_id,
      'isactive' => $active,
      'comp_email' =>$comp_email,
      'register_no' => $register_no,
      'comp_name' => $supplier_name,
      'comp_no' => $comp_no,
      'acc_name' =>$acc_name,
      'acc_no' =>$acc_no,
      'store_code' => $acc_no,
      'isactive' => 1
    );
     
    $this->db->insert('register_add_user_main', $data);

    redirect('Registration/register_vendor');
  }

  //add vendor site..
  public function register_forms_vendor()
  {
    $register_guid = $_REQUEST['register_guid'];

    $register = $this->db->query("SELECT a.* FROM lite_b2b.register_add_user_main a WHERE a.`register_guid` = '$register_guid'");

    $register_child = $this->db->query("SELECT a.*, b.`register_mapping_guid`, GROUP_CONCAT(b.`mapping_type` ORDER BY b.`mapping_type` DESC) AS mapping_type, GROUP_CONCAT(b.`ven_agency`) AS ven_agency, GROUP_CONCAT(b.`ven_code`) AS ven_code FROM lite_b2b.register_add_user_child a LEFT JOIN lite_b2b.`register_child_mapping` b ON a.`register_c_guid` = b.`register_c_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    $register_child_training = $this->db->query("SELECT a.* FROM lite_b2b.register_add_user_child a WHERE a.`register_guid` = '$register_guid' AND part_type = 'training' ");
    
    $customer_guid = $register->row('customer_guid');          

    $acc_branch = $this->db->query("SELECT a.NAME FROM b2b_summary.`supcus` a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid LIMIT 0, 100");

    $ven_agency_sql = $this->db->query("SELECT aa.*, bb.branch_desc FROM (SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid' AND a.branch_code IN (".$_SESSION['query_loc'].") AND a.isactive = '1') aa INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code ORDER BY aa.is_hq DESC, branch_code ASC ");
    
    $get_supp = $this->db->query("SELECT supplier_guid FROM register_add_user_main b WHERE b.`register_guid` = '$register_guid'");

    $supplier_guid = $get_supp->row('supplier_guid');

    $vendor_code_sql = $this->db->query("SELECT b.`supplier_name`, a.supplier_group_name FROM lite_b2b.set_supplier_group a INNER JOIN lite_b2b.`set_supplier` b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.supplier_guid = '$supplier_guid' GROUP BY  supplier_name,supplier_group_name ");
    
    $add_vendor_code = $this->db->query("SELECT a.`code` AS vendor_code FROM b2b_summary.supcus a WHERE a.customer_guid = '$customer_guid' GROUP BY customer_guid,`code` ");

    $vendor = $register->row('store_code');
    $myArray_1 = explode(',', $vendor);
    $myArray = array_filter($myArray_1); //show vendor code array

    $user_details = $this->db->query("SELECT a.`customer_guid`, a.`register_guid`, b.`register_c_guid`, b.`ven_name`, b.`ven_email`, c.`ven_agency` FROM lite_b2b.register_add_user_main a LEFT JOIN lite_b2b.register_add_user_child b ON a.`register_guid` = b.`register_guid` LEFT JOIN lite_b2b.register_add_user_child_mapping c ON b.`register_c_guid` = c.`register_c_guid` WHERE b.`register_guid` = '$register_guid' AND b.`part_type` = 'registration' AND c.mapping_type = 'outlet' ORDER BY b.`created_at` ASC"); 

    $table_array = array(); 
    foreach ($user_details->result() as $row)
    {
      $part1 = $row->ven_email;
      $part2 = $row->ven_name;
      $loc_group_array = $row->ven_agency;

      $check_exists = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '".$register->row('customer_guid')."'");
        // echo $this->db->last_query().';<br>';
      if($check_exists->num_rows() > 0 )
      {
        $dis_msg = 'Mapped';
      }
      else
      {
        $dis_msg = 'Not Map';
      }

      $check_exists2 = $this->db->query("SELECT GROUP_CONCAT(acc_name) as acc_name FROM lite_b2b.set_user a INNER JOIN lite_b2b.acc b ON a.`acc_guid` = b.acc_guid WHERE a.user_id = '$part1' AND a.acc_guid != '".$register->row('customer_guid')."' GROUP BY a.user_guid");
      // echo $this->db->last_query().';<br>';
      if($check_exists2->num_rows() > 0 )
      {
        $dis_msg2 = $check_exists2->row('acc_name');
      }
      else
      {
        $dis_msg2 = 'Not Map';
      }     

      $user_group_dropdown_array = $this->db->query("SELECT * FROM set_user_group WHERE module_group_guid = '".$this->session->userdata('module_group_guid')."'");

      $check_user_group_dropdown = $this->db->query("SELECT * FROM lite_b2b.set_user a WHERE a.user_id = '$part1' AND a.acc_guid = '".$register->row('customer_guid')."' GROUP BY a.user_guid");
      // echo $this->db->last_query().';<br>';
      $user_group_dropdown = '<select class="" style="width:100%" name="user_group_down[]">';
      $user_group_dropdown .= '<option value="">Please Select</option>';
      if($user_group_dropdown_array->num_rows() > 0 )
      {
        foreach($user_group_dropdown_array->result() as $u_dropdown)
        {
          if($u_dropdown->user_group_guid == $check_user_group_dropdown->row('user_group_guid'))
          {
            // echo $this->db->last_query();die;
            $user_group_selected = 'selected';
          }
          else
          {
            $user_group_selected = '';
          }
          $user_group_dropdown .= '<option value="'.$u_dropdown->user_group_guid.'"'.$user_group_selected.'>'.$u_dropdown->user_group_name.'</option>';
        }
        $user_group_dropdown .= '</select>';
      }
      else
      {
        $user_group_dropdown = 'contact admin';
      }   
      $loc_group_input = '<input type="hidden" name="loc_group_group_hidden" value="'.$loc_group_array.'"';                      

      $data2[] = array($part2,$part1,$user_group_dropdown,$dis_msg,$dis_msg2,$loc_group_input);
    }// end proceed User Details

    foreach ($user_details->result() as $row)
    {
      $part1 = $row->ven_email;
      $part2 = $row->ven_name;

      $check_exists = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '".$register->row('customer_guid')."'");
      // echo $this->db->last_query().';<br>';
      if($check_exists->num_rows() > 0 )
      {
        $dis_msg = 'Mapped';
      }
      else
      {
        $dis_msg = 'Not Map';
      }

      $check_exists2 = $this->db->query("SELECT GROUP_CONCAT(acc_name) as acc_name FROM lite_b2b.set_user a INNER JOIN lite_b2b.acc b ON a.`acc_guid` = b.acc_guid WHERE a.user_id = '$part1' AND a.acc_guid != '".$register->row('customer_guid')."' GROUP BY a.user_guid");
      // echo $this->db->last_query().';<br>';
      if($check_exists2->num_rows() > 0 )
      {
        $dis_msg2 = $check_exists2->row('acc_name');
      }
      else
      {
        $dis_msg2 = 'Not Map';
      }  

      $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');

      $selected_vendor_code_query = $this->db->query("SELECT * FROM lite_b2b.set_supplier_user_relationship a WHERE a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid' AND user_guid = '$get_user_guid'");
      // echo $this->db->last_query();
      $selected_vendor_code_array = array();
      foreach($selected_vendor_code_query->result() as $selected_code)
      {
        $selected_vendor_code_array[] = $selected_code->supplier_group_guid;   
      }  
      // print_r($selected_vendor_code_array);die;

      $vendor_code_query = $this->db->query("SELECT * FROM lite_b2b.set_supplier_group a WHERE a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'");   
      // echo $this->db->last_query();die;
      $vendor_code_dropdown = '<select style="width:100%" class="selectpicker" multiple>';
      $mapped_string = '';
      foreach($vendor_code_query->result() as $dropdown)
      {
        if(in_array($dropdown->supplier_group_guid,$selected_vendor_code_array))
        {
          $selected = 'selected';
          $mapped_string .= $dropdown->supplier_group_name.',';     
        }
        else
        {
          // echo 1;
          $selected = '';  
          $mapped_string .= '';  
        }
        $vendor_code_dropdown .= '<option value="'.$dropdown->supplier_group_name.'"'.$selected.' >'.$dropdown->supplier_group_name.'</option>';
      }
      $vendor_code_dropdown .= '</select>';

      if($mapped_string == '')
      {
        $mapped_string = 'Not Map';  
      }
      else
      {
        $mapped_string = rtrim($mapped_string,',');
      }
      $data3[] = array($part1,$vendor_code_dropdown,$mapped_string);
    }// end proceed User Details Mapping

    // email array 
    $key = 0;  // added new
    foreach ($user_details->result() as $row)
    {
      $reset_pass_link = '';
      $part1 = $row->ven_email;
    
      $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');
      $empty = '';
      $check_reset_link = $this->db->query("SELECT * FROM lite_b2b.reset_pass_list WHERE user_guid = '$get_user_guid' AND customer_guid = '$customer_guid'");
      // echo $this->db->last_query();
      if($check_reset_link->num_rows() > 0)
      {
        $reset_url = 'https://b2b.xbridge.my/index.php/Key_in/key_in?si='.$check_reset_link->row('reset_guid').'&ug='.$check_reset_link->row('user_guid');
        $reset_pass_link = $reset_url;
        $duplicate = 0;
      }
      else
      {
        $reset_pass_link = 'No reset link';
        $duplicate = 1;
      }
      $reset_link_text = '<div style="display:flex;"><input class="form-control" type="text" id="copy_link_'.$key.'" value="'.$reset_pass_link.'" readonly>&nbsp;&nbsp;<i class="fa fa-copy" id="copy_link" seq="'.$key.'"></i></div>';
      $checkbox = '<input type="checkbox" class="form-checkbox" id="check_email_link" name="checkall_input_table[]" table_id="email_tb" supplier_guid="'.$supplier_guid.'" reset_g= "'.$check_reset_link->row('reset_guid').'" customer_guid="'.$customer_guid.'" duplicate="'.$duplicate.'" link="'.$reset_pass_link.'" u_g="'.$get_user_guid.'" vendor_email='.$part1.'/>';
      $data4[] = array($part1,$reset_link_text,$checkbox);

      $key++; // added new
    }

    //proceed table email array
    foreach ($user_details->result() as $row)
    {
      $part1 = $row->ven_email;
      $check_exists2 = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '".$user_details->row('customer_guid')."'");

      $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');

      $selected_report = $this->db->query("SELECT * FROM check_email_schedule a WHERE a. user_guid = '$get_user_guid'");
      // echo $this->db->last_query();
      $selected_report_array = array();
      foreach($selected_report->result() as $selected_code)
      {
        $selected_report_array[] = $selected_code->report_guid;   
      }  

      $report_type = $this->db->query("SELECT * from set_report_query where active = '1' AND report_guid = 'AAF708CA914A11E887B5000D3AA2838A' order by report_type , report_name asc");

      $report_type_dropdown = '<select style="width:100%" class="selectpicker" multiple>';
      $mapped_string = '';
      foreach($report_type->result() as $dropdown)
      {
        if(in_array($dropdown->report_guid,$selected_report_array))
        {
          $selected = 'selected';
          $mapped_string .= $dropdown->report_name.',';     
        }
        else
        {
          // echo 1;
          $selected = '';  
          $mapped_string .= '';  
        }
        $report_type_dropdown .= '<option value="'.$dropdown->report_guid.'"'.$selected.' >'.$dropdown->report_name.'</option>';
      }
      $report_type_dropdown .= '</select>';

      $empty = '';
      $email_list_status = $this->db->query("SELECT GROUP_CONCAT(DISTINCT report_name) as report_name from check_email_schedule WHERE user_guid = '$get_user_guid' GROUP BY user_guid")->row('report_name');
      if($email_list_status == '' || $email_list_status == null)
      {
          $email_list_status = 'No report schedule';
      }
      $email_subscribe_status = $this->db->query("SELECT GROUP_CONCAT(DISTINCT report_name) as report_name from check_email_schedule WHERE user_guid = '$get_user_guid' GROUP BY user_guid")->row('report_name');
      if($email_subscribe_status == '' || $email_subscribe_status == null)
      {
          $email_subscribe_status = 'Email not subscribe';
      }          
      $data5[] = array($part1,$report_type_dropdown,$email_list_status);  
    } 

    if($user_details->num_rows() == 0)
    {
      $data2 = '';
      $data3 = '';
      $data4 = '';
      $data5 = '';
    }
       
    $data = array(
      'supplier_guid' => $supplier_guid,
      'customer_guid' => $customer_guid,
      'register' => $register,
      'register_child' => $register_child,
      'register_child_training' => $register_child_training,
      'acc_branch' => $acc_branch,
      'ven_agency_sql' => $ven_agency_sql, // outlet array
      //'vendor_code_sql' => $vendor_code_sql, // not use 
      'myArray' =>$myArray, // Vendor Code (refer to Retailer) array
      'add_vendor_code' => $add_vendor_code->result(), // add vendor code
      'table_array' => $data2,
      'table_array2' => $data3,
      'email_array' => $data4,
      'table_array3' => $data5,
    );

    $this->load->view('header'); 
    $this->load->view('register/register_forms_vendor', $data);  
    $this->load->view('footer' );  
  }
  
  //add vendor site..
  public function register_vendor_update() 
  {
    if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
    {
      $register_guid = $_REQUEST['register_guid'];
      $register = $this->db->query("SELECT * FROM lite_b2b.register_add_user_main a INNER JOIN lite_b2b.set_supplier_info b ON a.register_guid = b.register_guid WHERE a.`register_guid` = '$register_guid' ");
      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');

      $comp_mail = $this->input->post('comp_mail');
      $comp_no = $this->input->post('comp_no');
      $comp_add = $this->input->post('comp_add');
      $comp_email = $this->input->post('comp_email');
      $comp_contact = $this->input->post('comp_contact');
      $comp_fax = $this->input->post('comp_fax');
      $comp_post = $this->input->post('comp_post');
      $comp_state = $this->input->post('comp_state');
      $supply_outright = $this->input->post('supply_outright'); //new
      $supply_consignment = $this->input->post('supply_consignment'); //new
      $bus_bread = $this->input->post('bus_bread'); // new
      $bus_fresh = $this->input->post('bus_fresh'); // new
      $bus_desc_others = $this->input->post('bus_desc_others');
      $acc_name = $this->input->post('acc_name');
      $acc_no = $this->input->post('acc_no');
      $acc_no_other = $this->input->post('acc_no_other');
      $isdelete = $this->input->post('isdelete');
      $created_at = $this->db->query("SELECT now() as now")->row('now');
      $updated_at = $this->db->query("SELECT now() as now")->row('now');
      $store_code = $register->row('store_code'); 
      $myArray = explode(',', $store_code);
      $diff_code = array_diff($acc_no, $myArray);
      $combine = array_merge($myArray, $diff_code);

      $data = array(
        'comp_add' =>$comp_add,
        'comp_contact' =>$comp_contact,
        'comp_fax' =>$comp_fax,
        'acc_name' =>$acc_name,
        'acc_no' =>implode(',', $acc_no),
        'store_code' => implode(",",$combine),
        'vendor_code_remark' =>implode(',', $acc_no_other),
        'org_email' =>$comp_mail,
        'org_part_email' =>$comp_email,
        'update_at' => $updated_at,
        'update_by' => $user_id,
        'isactive' => 0,
        'form_status' => 'Pending'
      );
       
      $this->db->where('register_guid', $register_guid);
      $this->db->update('register_add_user_main', $data);

      $data = array(
        'supply_outright' =>$supply_outright,
        'supply_consignment' =>$supply_consignment,
        'bus_bread' =>$bus_bread,
        'bus_fresh' =>$bus_fresh,
        'bus_desc_others' =>$bus_desc_others,
        'supplier_add' => $comp_add,
        'supplier_postcode' => $comp_post,
        'supplier_state' => $comp_state
          
      );
      
      $this->db->where('register_guid', $register_guid);
      $this->db->update('set_supplier_info', $data);
       
      // if(count($diff_code) != 0) {

      //  $coding = implode(",",$combine);

      //  $this->db->query("UPDATE register_child SET store_code ='$coding' WHERE register_guid = '$register_guid' ");

      // }

      redirect(site_url('Registration/register_forms_vendor?register_guid='.$register_guid.''));
    }  
    else
    {
      redirect('login_c');
    }
  }

  //add vendor site..
  public function add_vendor_tb()
  {
    $register_guid = $this->input->post('register_guid');
    $register_child = $this->db->query("SELECT a.*, GROUP_CONCAT(b.`register_mapping_guid` ORDER BY b.`mapping_type` DESC) AS register_mapping_guid, GROUP_CONCAT(b.`mapping_type` ORDER BY b.`mapping_type` DESC) AS mapping_type, GROUP_CONCAT(b.`ven_agency`) AS ven_agency, GROUP_CONCAT(b.`ven_code`) AS ven_code, c.`customer_guid` FROM lite_b2b.register_add_user_child a LEFT JOIN lite_b2b.register_add_user_child_mapping b ON a.`register_c_guid` = b.`register_c_guid` INNER JOIN lite_b2b.register_add_user_main c ON a.`register_guid` = c.`register_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid ORDER BY created_at ASC");
    //echo $this->db->last_query(); die;

    echo json_encode($register_child->result());
  }

  //add vendor site..
  public function add_vendor_info_vens()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $ven_name = $this->input->post('ven_name');
    $ven_designation = $this->input->post('ven_designation');
    $ven_phone = $this->input->post('ven_phone');
    $ven_email = $this->input->post('ven_email');
    $ven_agency = $this->input->post('ven_agency');
    $ven_code = $this->input->post('ven_code');
    $c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $m_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $m_guid_1 = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
    $get_supp = $this->db->query("SELECT supplier_guid FROM register_new b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');
    
    $ven_agency = implode(",",$ven_agency);
    $ven_agency = "".$ven_agency."";

    $ven_code = implode(",",$ven_code);
    $ven_code = "".$ven_code."";

    $register_child = $this->db->query("SELECT a.* FROM lite_b2b.register_add_user_child a WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    foreach ($register_child->result() as $key) 
    {
      $check_ven_name= $key->ven_name; 
      $check_ven_email = $key->ven_email;  

      if($ven_name == $check_ven_name)
      {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Name.',

          );    
          echo json_encode($data); 
          die; 
      }
      
      if($ven_email == $check_ven_email)
      {
          $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Email.',

          );    
          echo json_encode($data);  
          die;
      }
    }

    $data_1 = array(   
      'update_at' => $updated_at,
      'update_by' => $user_id
    );

    $this->db->where('register_guid', $register_guid);
    $this->db->update('register_add_user_main', $data_1);

    $data_2 = array(   
      'supplier_guid' => $supplier_guid,
      'register_guid' => $register_guid,
      'register_c_guid' => $c_guid,
      'ven_name' => $ven_name,
      'ven_designation' => $ven_designation,
      'ven_phone' => $ven_phone,
      'ven_email' => $ven_email,
      'ven_name' => $ven_name,
      'isdelete' => 0,
      'part_type' => 'registration',
      'created_at' => $updated_at,
      'created_by' => $user_id
    );

    $this->db->insert('register_add_user_child', $data_2);

    $data_3 = array(   
      'register_guid' => $register_guid,
      'register_c_guid' => $c_guid,
      'register_mapping_guid' => $m_guid,
      'ven_agency' => $ven_agency,
      'mapping_type' => 'outlet'
    );

    $this->db->insert('register_add_user_child_mapping', $data_3);

    $data_4 = array(   
      'register_guid' => $register_guid,
      'register_c_guid' => $c_guid,
      'register_mapping_guid' => $m_guid_1,
      'ven_code' => $ven_code,
      'mapping_type' => 'code'
    );

    $this->db->insert('register_add_user_child_mapping', $data_4);

    $error = $this->db->affected_rows();

    if($error > 0){

         $data = array(
          'para1' => 0,
          'msg' => 'Add Successfully',

          );    
          echo json_encode($data);   
    }
    else
    {   
        $data = array(
        'para1' => 1,
        'msg' => 'Error.',

        );    
        echo json_encode($data);   
    }
  }

  //add vendor site..
  public function edit_vendor_info_vens()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $register_c_guid = $this->input->post('register_c_guid');
    $register_mapping_guid = $this->input->post('register_mapping_guid');
    $ven_name = $this->input->post('ven_name');
    $ven_designation = $this->input->post('ven_designation');
    $ven_phone = $this->input->post('ven_phone');
    $ven_email = $this->input->post('ven_email');
    $ven_agency = $this->input->post('ven_agency');
    $ven_code = $this->input->post('ven_code');
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
    $get_supp = $this->db->query("SELECT supplier_guid FROM register_new b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');
    
    $ven_agency = implode(",",$ven_agency);
    $ven_agency = "".$ven_agency."";

    $ven_code = implode(",",$ven_code);
    $ven_code = "".$ven_code."";

    $register_mapping_guid = explode(",",$register_mapping_guid);
    $register_mapping_guid_1 = $register_mapping_guid[0]; // outlet guid
    $register_mapping_guid_2 = $register_mapping_guid[1]; // code guid

    $register_child = $this->db->query("SELECT a.* FROM lite_b2b.register_add_user_child a WHERE a.`register_guid` = '$register_guid' AND a.`register_c_guid` != '$register_c_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    foreach ($register_child->result() as $key) 
    {
      $check_ven_name= $key->ven_name; 
      $check_ven_email = $key->ven_email;  

      if($ven_name == $check_ven_name)
      {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Name.',

          );    
          echo json_encode($data); 
          die; 
      }
      
      if($ven_email == $check_ven_email)
      {
          $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Email.',

          );    
          echo json_encode($data);  
          die;
      }
    }

    $data_2 = array(   
      'ven_name' => $ven_name,
      'ven_designation' => $ven_designation,
      'ven_phone' => $ven_phone,
      'ven_email' => $ven_email,
      'ven_name' => $ven_name,
      'isdelete' => 0,
    );

    $this->db->where('register_c_guid', $register_c_guid);
    $this->db->update('register_add_user_child', $data_2);

    $data_3 = array(   
      'ven_agency' => $ven_agency,
      'mapping_type' => 'outlet'
    );

    $this->db->where('register_mapping_guid', $register_mapping_guid_1);
    $this->db->update('register_add_user_child_mapping', $data_3);

    $data_4 = array(   
      'ven_code' => $ven_code,
      'mapping_type' => 'code'
    );

    $this->db->where('register_mapping_guid', $register_mapping_guid_2);
    $this->db->update('register_add_user_child_mapping', $data_4);

    $data_1 = array(   
      'update_at' => $updated_at,
      'update_by' => $user_id
    );

    $this->db->where('register_guid', $register_guid);
    $this->db->update('register_add_user_main', $data_1);

    $error = $this->db->affected_rows();

    if($error > 0){

         $data = array(
          'para1' => 0,
          'msg' => 'Edit Successfully',

          );    
          echo json_encode($data);   
    }
    else
    {   
        $data = array(
        'para1' => 1,
        'msg' => 'Error.',

        );    
        echo json_encode($data);   
    }
  }

  // END HERE ADD VENDOR SITE @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function new_user()
  {
    if($this->session->userdata('loginuser') == true)
    {
      $session_id = $_REQUEST['session_id'];
      $supplier_group_guid = $this->session->userdata('supplier_group_guid');
      $name = $this->db->query("SELECT acc_name from acc a INNER JOIN set_supplier_group b ON a.acc_guid = b.customer_guid where acc_guid = '".$_REQUEST['session_id']."'")->row('acc_name'); 
      $acc_regno = $this->db->query("SELECT * from acc where acc_guid = '".$_REQUEST['session_id']."'")->row('acc_regno'); 
      $session_id = $this->db->query("SELECT a.supplier_group_guid  FROM set_supplier_group a INNER JOIN set_supplier b ON a.`supplier_guid` = b.`supplier_guid` INNER JOIN acc c ON a.`customer_guid` = c.`acc_guid` WHERE a.customer_guid = '".$_REQUEST['session_id']."'")->row('supplier_group_guid');
      $supplier = $this->db->query("SELECT * FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.supplier_group_guid =  '".$_REQUEST['session_id']."'")->result(); 
      $new_supplier = $this->db->query("SELECT  * FROM set_supplier_group a INNER JOIN acc b ON a.`customer_guid` = b.`acc_guid` LEFT JOIN set_supplier c ON a.`supplier_guid` = c.supplier_guid WHERE a.`supplier_guid` > 1 ORDER BY a.`supplier_guid` ASC ");
      $acc_branch = $this->db->query("SELECT a.NAME FROM b2b_summary.`supcus` a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid ");

      $data = array (
        'name' => $name,
        'supplier' =>$supplier,
        'acc_regno' => $acc_regno,
        'session_id' => $session_id,
        'new_supplier' => $new_supplier,
        'session_id' => $session_id,
        'acc_branch' => $acc_branch,
      );
  
      $this->load->view('register/header2'); 
      $this->load->view('register/new_user' ,$data );  
    }
  }

  public function register()
  {
        $session_id = $_REQUEST['session_id'];
        $register_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS register_guid")->row('register_guid');
        $register_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS register_c_guid")->row('register_c_guid');
        $customer_guid = $this->db->query("SELECT acc_guid from acc a INNER JOIN set_supplier_group b ON a.acc_guid = b.customer_guid WHERE b.supplier_group_guid = '".$_REQUEST['session_id']."'")->row('acc_guid'); 
        $supplier_guid = $this->db->query("SELECT * FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid             INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.supplier_group_guid ='".$_REQUEST['session_id']."'")->row('supplier_guid');
        $supplier_info_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS supplier_info_guid")->row('supplier_info_guid');
        $active = $this->db->query("SELECT a.isactive FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid  INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.supplier_group_guid ='".$_REQUEST['session_id']."'")->row('isactive');
        $supplier = $this->db->query("SELECT * FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.supplier_group_guid =  '$session_id'")->result();
        $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
        $re_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(register_no, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS re_no  FROM `register`  WHERE  SUBSTRING(register_no, - 8, 4) = CONCAT( RIGHT(YEAR(NOW()), 2), LPAD(MONTH(NOW()), 2, 0) )")->row('re_no');
        $todaydate = date('Ym');    
        $todaydate2 = substr($todaydate, 2);

        $register_no = $this->db->query("SELECT concat( '$todaydate2', '$re_no' ) as refno")->row('refno');
        $comp_no = $this->input->post('comp_no');
        $comp_add = $this->input->post('comp_add');
        $comp_post = $this->input->post('comp_post');
        $comp_state = $this->input->post('comp_state');
        $comp_email = $this->input->post('comp_email');
        $comp_contact = $this->input->post('comp_contact');
        $comp_fax = $this->input->post('comp_fax');
        $supply_type = $this->input->post('supply_type');
        $bus_desc = $this->input->post('bus_desc');
        $bus_desc_others = $this->input->post('bus_desc_others');
        $acc_name = $this->input->post('acc_name');
        $acc_no = $this->input->post('acc_no');
        $ven_name = $this->input->post('ven_name');
        $ven_designation = $this->input->post('ven_designation');
        $ven_phone = $this->input->post('ven_phone');
        $ven_email = $this->input->post('ven_email');
        $ven_agency = $this->input->post('ven_agency');
        $comp_name = $this->input->post('comp_name');
        $comp_email = $this->input->post('comp_email');
        $part_name = $this->input->post('part_name');
        $part_ic = $this->input->post('part_ic');
        $part_mobile = $this->input->post('part_mobile');
        $part_email = $this->input->post('part_email');
        $create_at = $this->db->query("SELECT now() as now")->row('now');
        $update_at = $this->db->query("SELECT now() as now")->row('now');

        $data = array(

                'supplier_info_guid' => $supplier_info_guid,
                'supplier_add' => $comp_add,
                'supplier_postcode' => $comp_post,
                'supplier_state' => $comp_state,
                'register_guid' => $register_guid
                

            );
           //print_r($data);die;

        $this->db->insert('set_supplier_info', $data);

        $data = array(

                'register_guid' => $register_guid,
                'customer_guid' => $customer_guid,
                'supplier_guid' => $supplier_guid,
                'session_id' => $session_id,
                'create_at' => $create_at,
                'create_by' => $user_id,
                'update_at' => $update_at,
                'update_by' => $user_id,
                'isactive' => $active,
                'comp_email' =>$comp_email,
                'register_no' => $register_no

            );
           //print_r($data);die;
        $this->db->insert('register', $data);

        $data = array(

                'register_c_guid' => $register_c_guid,
                'register_guid' => $register_guid,
                'supplier_guid' => $supplier_guid,
                'session_id' => $session_id,
                'comp_name' => $comp_name,
                'comp_no' => $comp_no,
                'comp_email' =>$comp_email,
                'comp_add' =>$comp_add,
                'comp_contact' =>$comp_contact,
                'comp_fax' =>$comp_fax,
                'supply_type' =>$supply_type,
                'bus_desc' =>$bus_desc,
                'bus_desc_others' =>$bus_desc_others,
                'acc_name' =>$acc_name,
                'acc_no' =>implode(',',$acc_no),
                'ven_name' =>implode('/',$ven_name),
                'ven_designation' =>implode('/',$ven_designation),
                'ven_phone' =>implode('/', $ven_phone),
                'ven_email' =>implode('/', $ven_email),
                'ven_agency' =>implode('/',$ven_agency),
                'part_name' => implode('/', $part_name),
                'part_ic' => implode('/', $part_ic),
                'part_mobile' => implode('/', $part_mobile),
                'part_email' => implode('/', $part_email),
                 'isdelete' => 1
            );

        
                //print_r($data);die;
        $this->db->insert('register_child', $data);

        echo "<script> alert('Your are successfully been registered! We will response to you as soon as possible.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/Registration/register_thank' </script>";
  }

  public function register_admin()
  {
        $supplier = $this->db->query("SELECT a.* FROM set_supplier a ORDER BY a.supplier_name ASC")->result();
        $retailer = $this->db->query("SELECT DISTINCT c.acc_name FROM  acc c INNER JOIN set_supplier_group a ON c.acc_guid = a.customer_guid WHERE a.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('acc_name');
        $data = array(

                 'supplier' => $supplier,
                 'retailer' => $retailer
                
            );

        $this->load->view('header'); 
        $this->load->view('register/register_admin', $data);  
        $this->load->view('footer' );  

  }

  public function register_table()
  {

            ini_set('memory_limit', -1); 
            if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
            {
                $columns = array(

                    0 => 'register_guid',
                    1 => 'register_no',
                    2 => 'supplier_name',
                    3 => 'acc_name',
                    4 => 'comp_email',
                    5 => 'acc_no',
                    6 => 'cnt',
                    7 => 'part_cnt',
                    8 => 'form_status',
                    9 => 'create_at',
                    10 => 'create_by',
                    11 => 'update_at',
                    12 => 'update_by',



                );
            }
            else
            {
                $columns = array(
                    // 0 => 'supplier_group_guid',
                    // 1 => 'supplier_name',
                    // 2 => 'acc_name',
                    // 3 => 'create_at',
                    // 4 => 'create_by',
                    // 5 => 'action',

                    0 => 'register_no',
                    1 => 'supplier_name',
                    2 => 'acc_name',
                    3 => 'create_at',
                    4 => 'create_by',
                    5 => 'action',
                );
            }


            $user_guid = $_SESSION['user_guid'];
            $user_group = $_SESSION['user_group_name'];
            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = $this->input->post('order');
            $dir = "";
            $customer_guid = $_SESSION['customer_guid'];
            $totalData = $this->Registration_model->register($customer_guid)->row('numrow');
            $totalFiltered = $totalData;

            $order_query = "";

            if(!empty($order))
            {
              foreach($order as $o)
              {
                  $col = $o['column'];
                  $dir= $o['dir'];

                  $order_query .= $columns[$col]." ".$dir.",";

              }
            }   
            $dir = '';    
            $order_query = rtrim($order_query,',');

            $query = "
                SELECT 
                  aa.`supplier_info_guid`,
                  aa.`register_no`,
                  aa.`supplier_name`,
                  aa.`acc_name`,
                  aa.`comp_email`,
                  aa.`acc_no`,
                  aa.`create_at`,
                  aa.`create_by`,
                  aa.`update_at`,
                  aa.`update_by`,
                  aa.`register_guid`,
                  aa.`cnt`,
                  aa.`part_cnt`,
                  aa.`customer_guid`,
                  aa.`form_status`
                FROM
                  (SELECT 
                    b.acc_name,
                    c.`supplier_name`,
                    e.`supplier_info_guid`,
                    a.*,
                    d.* 
                  FROM
                    register a 
                    INNER JOIN acc b 
                      ON b.acc_guid = a.`customer_guid` 
                    LEFT JOIN set_supplier c 
                      ON c.supplier_guid = a.`supplier_guid` 
                    LEFT JOIN set_supplier_info e 
                      ON a.`register_guid` = e.register_guid 
                    LEFT JOIN 
                      (SELECT DISTINCT 
                        acc_no AS acc_no,
                        ven_name,
                        register_guid AS register_id,
                        part_name,
                        form_status,
                        LENGTH(ven_name) - LENGTH(REPLACE(ven_name, '/', '')) + 1 AS cnt,
                        IF(part_name = '' ,'0', LENGTH(part_name) - LENGTH(REPLACE(part_name, '/', ''))+1) AS part_cnt 
                      FROM
                        register_child 
                      GROUP BY register_guid) d 
                      ON ven_name = d.ven_name 
                      AND part_name = d.part_name 
                      AND a.`register_guid` = d.register_id) aa 
                      WHERE aa.customer_guid = '".$_SESSION['customer_guid']."' ";
            // if ($user_group == 'SUPER_ADMIN' ) {
            //     $query = "  SELECT 
            //                 DISTINCT f.`register_no`,b.`supplier_group_guid` , d.`supplier_name`, c.`acc_name` , f.`comp_email`, `acc_no`, f.`create_at` , f.`create_by`, f.`register_guid`,`cnt` , `part_cnt`
            //                 FROM
            //                 set_supplier_group b 
            //                 INNER JOIN acc c 
            //                 ON c.acc_guid = b.`customer_guid` 
            //                 LEFT JOIN set_supplier d 
            //                 ON d.supplier_guid = b.`supplier_guid` 
            //                 LEFT JOIN set_user e 
            //                 ON e.supplier_guid = b.supplier_guid 
            //                 LEFT JOIN register f 
            //                  ON d.supplier_guid = f.supplier_guid 
            //                 LEFT JOIN (SELECT DISTINCT acc_no AS acc_no, ven_name , register_guid ,part_name, LENGTH(ven_name) - LENGTH(REPLACE(ven_name, '/', '')) + 1 AS cnt, LENGTH(part_name) - LENGTH(REPLACE(part_name, '/', '')) + 1 AS part_cnt
            //                 FROM register_child 
            //                 GROUP BY register_guid) h ON ven_name = h.ven_name AND part_name = h.part_name AND f.`register_guid` = h.register_guid
            //                 WHERE b.customer_guid ='".$_SESSION['customer_guid']."' ";

            // }
            // else{
            // $query = "SELECT * FROM set_supplier_group b INNER JOIN acc c  ON c.acc_guid = b.`customer_guid` LEFT JOIN set_supplier d ON        d.supplier_guid = b.`supplier_guid` LEFT JOIN set_user e 
            //           ON e.supplier_guid = b.supplier_guid LEFT JOIN register f ON b.supplier_group_guid = f.session_id ";
            // }

            if(empty($this->input->post('search')['value']))
            {
                $posts = $this->Registration_model->allposts($query,$limit,$start,$order_query,$dir);
                //echo $this->db->last_query(); die;
            }
            else 
            {
                $search = $this->input->post('search')['value']; 

                $posts =  $this->Registration_model->posts_search($query,$limit,$start,$search,$order_query,$dir);

                $totalFiltered = $this->Registration_model->posts_search_count($query,$search);
            }

            $data = array();
            if(!empty($posts))
            {
                foreach ($posts as $post)
                {
                    $nestedData['register_no'] = $post->register_no;
                    $nestedData['supplier_name'] = $post->supplier_name;
                    $nestedData['acc_name'] = $post->acc_name;
                    $nestedData['comp_email'] = $post->comp_email;
                    $nestedData['create_at'] = $post->create_at; 
                    $nestedData['create_by'] = $post->create_by;
                    $nestedData['update_at'] = $post->update_at; 
                    $nestedData['update_by'] = $post->update_by;
                    $nestedData['cnt'] = $post->cnt; 
                    $nestedData['part_cnt'] = $post->part_cnt;
                    $nestedData['acc_no'] = $post->acc_no;
                    $nestedData['form_status'] = $post->form_status;

                   if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN'  ) {

                    $nestedData['action'] = '<a register_guid='.$post->register_guid.'id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_form_edit?register_guid='.$post->register_guid.'"><i class="glyphicon glyphicon-pencil"></i></a><a register_guid='.$post->register_guid.'id="view_ticket" title="SEND" class="btn btn-xs btn-warning" type="button" href="send_mail?session_id='.$post->supplier_info_guid.'" style="margin-left:5px;" ><i class="glyphicon glyphicon-send"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" register_guid="'.$post->register_guid.'" register_no="'.$post->register_no.'" supplier_name="'.$post->supplier_name.'" acc_name="'.$post->acc_name.'" comp_email="'.$post->comp_email.'" style="margin-top:5px;"><i class="fa fa-edit"></i></a>';
                    
                        //<a register_guid='.$post->register_guid.'id="view_ticket" class="btn btn-xs btn-primary" type="button" href="register_form_view?register_guid='.$post->register_guid.'" style="margin-left:5px;"><i class="glyphicon glyphicon-eye-open"></i></a>
                   } 
                    $data[] = $nestedData;

                }
            }               

            $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
            echo json_encode($json_data); 
  }

  public function register_form_view()
  {
          $register_guid = $_REQUEST['register_guid'];
          $register = $this->db->query("SELECT * FROM register_child a INNER JOIN register b ON a.register_guid = b.register_guid LEFT JOIN set_supplier_info d ON d.register_guid = b.register_guid  WHERE b.`register_guid` = '$register_guid' ");


         $data = array(

             'register' => $register,


         );
           //print_r($data);die;

           $this->load->view('header'); 
           $this->load->view('register/register_forms', $data);  
           $this->load->view('footer' );  
  }

  public function register_form_edit()
  {      
      $register_guid = $_REQUEST['register_guid'];
      //$customer_guid = $_SESSION['customer_guid'];

      $register = $this->db->query("SELECT a.*,b.customer_guid AS customer_guid,b.register_no,d.* FROM register_child a INNER JOIN register b ON a.register_guid = b.register_guid LEFT JOIN set_supplier_info d ON d.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid'");
      $customer_guid = $register->row('customer_guid');          

      $new_supplier = $this->db->query("SELECT a.*,c.* FROM set_supplier_group a INNER JOIN acc b ON a.`customer_guid` = b.`acc_guid` LEFT JOIN set_supplier c ON a.`supplier_guid` = c.supplier_guid WHERE a.`supplier_guid` > 1 ORDER BY a.`supplier_guid` ASC ");
      $acc_branch = $this->db->query("SELECT a.NAME FROM b2b_summary.`supcus` a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid LIMIT 0, 100");
      $ven_agency_sql = $this->db->query("SELECT aa.*, bb.branch_desc FROM (SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid' AND a.branch_code IN (".$_SESSION['query_loc'].") AND a.isactive = '1') aa INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code ORDER BY aa.is_hq DESC, branch_code ASC ");
      //echo $this->db->last_query(); die;
      $get_supp = $this->db->query("SELECT supplier_guid FROM register b WHERE b.`register_guid` = '$register_guid'");
      $supplier_guid = $get_supp->row('supplier_guid');
      $vendor_code_sql = $this->db->query("SELECT b.`supplier_name`, a.supplier_group_name FROM lite_b2b.set_supplier_group a INNER JOIN lite_b2b.`set_supplier` b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.supplier_guid = '$supplier_guid' GROUP BY  supplier_name,supplier_group_name ");
      $add_vendor_code = $this->db->query("SELECT a.`code` AS vendor_code FROM b2b_summary.supcus a WHERE a.customer_guid = '$customer_guid' GROUP BY customer_guid,`code` ");

      $vendor = $register->row('store_code');
      $vendor_1 = $vendor_code_sql->row('supplier_group_name');

      $myArray_1 = explode(',', $vendor);
      //$myArray_2 = explode(',', $vendor_1);
      //$myArray = array_unique(array_merge($myArray_1,$myArray_2));
      $myArray = array_filter($myArray_1); //show vendor code array
      //print_r($myArray); die;
      $table_array = array(); 
          // print_r($register->result());die;
      foreach ($register->result() as $row)
      {
        $part1 = $row->ven_email;
        $part2 = $row->ven_name;
        $loc_group_array = $row->ven_agency;

        $array =  explode('/', $part1);
        $array2 =  explode('/', $part2);
        $array3 =  explode('/',  $loc_group_array);
        // print_r($array3);
        foreach($array as $key => $row2)
        {
          $check_exists = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '".$array[$key]."' AND acc_guid = '".$register->row('customer_guid')."'");
          // echo $this->db->last_query().';<br>';
          if($check_exists->num_rows() > 0 )
          {
            $dis_msg = 'Mapped';
          }
          else
          {
            $dis_msg = 'Not Map';
          }

          $check_exists2 = $this->db->query("SELECT GROUP_CONCAT(acc_name) as acc_name FROM lite_b2b.set_user a INNER JOIN lite_b2b.acc b ON a.`acc_guid` = b.acc_guid WHERE a.user_id = '".$array[$key]."' AND a.acc_guid != '".$register->row('customer_guid')."' GROUP BY a.user_guid");
          // echo $this->db->last_query().';<br>';
          if($check_exists2->num_rows() > 0 )
          {
            $dis_msg2 = $check_exists2->row('acc_name');
          }
          else
          {
            $dis_msg2 = 'Not Map';
          }     

          $user_group_dropdown_array = $this->db->query("SELECT * FROM set_user_group WHERE module_group_guid = '".$this->session->userdata('module_group_guid')."'");

          $check_user_group_dropdown = $this->db->query("SELECT * FROM lite_b2b.set_user a WHERE a.user_id = '".$array[$key]."' AND a.acc_guid = '".$register->row('customer_guid')."' GROUP BY a.user_guid");
          // echo $this->db->last_query().';<br>';
          $user_group_dropdown = '<select class="" style="width:100%" name="user_group_down[]">';
          $user_group_dropdown .= '<option value="">Please Select</option>';
          if($user_group_dropdown_array->num_rows() > 0 )
          {
            foreach($user_group_dropdown_array->result() as $u_dropdown)
            {
              if($u_dropdown->user_group_guid == $check_user_group_dropdown->row('user_group_guid'))
              {
                // echo $this->db->last_query();die;
                $user_group_selected = 'selected';
              }
              else
              {
                $user_group_selected = '';
              }
              $user_group_dropdown .= '<option value="'.$u_dropdown->user_group_guid.'"'.$user_group_selected.'>'.$u_dropdown->user_group_name.'</option>';
            }
            $user_group_dropdown .= '</select>';
          }
          else
          {
            $user_group_dropdown = 'contact admin';
          }   
          $loc_group_input = '<input type="hidden" name="loc_group_group_hidden" value="'.$array3[$key].'"';                      
          // echo 1;
          // $table_array_email[] = $array[$key];
          // $table_array_name[] = $array2[$key];
          // $table_array_retailer[] = $dis_msg;
          // $table_array_other_retailer[] = $dis_msg2;
          $data2[] = array($array2[$key],$array[$key],$user_group_dropdown,$dis_msg,$dis_msg2,$loc_group_input);
          // $data2['vendor_email'] = $table_array_email;
          // $data2['vendor_name'] = $table_array_name;
          // $data2['vendor_retailer'] = $table_array_retailer;
          // $data2['vendor_other_retailer'] = $table_array_other_retailer;
        }
        // $table_array = $data;
        // print_r($data2);
        // print_r($table_array);die;
        // print_r($array);die;
      } 

      $table_array = array(); 
      // print_r($register->result());die;
      foreach ($register->result() as $row)
      {
        $part1 = $row->ven_email;
        $part2 = $row->ven_name;
        $array =  explode('/', $part1);
        $array2 =  explode('/', $part2);
        foreach($array as $key => $row2)
        {
          $check_exists = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '".$array[$key]."' AND acc_guid = '".$register->row('customer_guid')."'");
          // echo $this->db->last_query().';<br>';
          if($check_exists->num_rows() > 0 )
          {
            $dis_msg = 'Mapped';
          }
          else
          {
            $dis_msg = 'Not Map';
          }

          $check_exists2 = $this->db->query("SELECT GROUP_CONCAT(acc_name) as acc_name FROM lite_b2b.set_user a INNER JOIN lite_b2b.acc b ON a.`acc_guid` = b.acc_guid WHERE a.user_id = '".$array[$key]."' AND a.acc_guid != '".$register->row('customer_guid')."' GROUP BY a.user_guid");
          // echo $this->db->last_query().';<br>';
          if($check_exists2->num_rows() > 0 )
          {
            $dis_msg2 = $check_exists2->row('acc_name');
          }
          else
          {
            $dis_msg2 = 'Not Map';
          }  

          $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '".$array[$key]."' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');

          $selected_vendor_code_query = $this->db->query("SELECT * FROM lite_b2b.set_supplier_user_relationship a WHERE a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid' AND user_guid = '$get_user_guid'");
          // echo $this->db->last_query();
          $selected_vendor_code_array = array();
          foreach($selected_vendor_code_query->result() as $selected_code)
          {
            $selected_vendor_code_array[] = $selected_code->supplier_group_guid;   
          }  
          // print_r($selected_vendor_code_array);die;

          $vendor_code_query = $this->db->query("SELECT * FROM lite_b2b.set_supplier_group a WHERE a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'");   
          // echo $this->db->last_query();die;
          $vendor_code_dropdown = '<select style="width:100%" class="selectpicker" multiple>';
          $mapped_string = '';
          foreach($vendor_code_query->result() as $dropdown)
          {
            if(in_array($dropdown->supplier_group_guid,$selected_vendor_code_array))
            {
              $selected = 'selected';
              $mapped_string .= $dropdown->supplier_group_name.',';     
            }
            else
            {
              // echo 1;
              $selected = '';  
              $mapped_string .= '';  
            }
            $vendor_code_dropdown .= '<option value="'.$dropdown->supplier_group_name.'"'.$selected.' >'.$dropdown->supplier_group_name.'</option>';
          }
          $vendor_code_dropdown .= '</select>';

          // echo 1;
          // $table_array_email[] = $array[$key];
          // $table_array_name[] = $array2[$key];
          // $table_array_retailer[] = $dis_msg;
          // $table_array_other_retailer[] = $dis_msg2;
          if($mapped_string == '')
          {
            $mapped_string = 'Not Map';  
          }
          else
          {
            $mapped_string = rtrim($mapped_string,',');
          }
          $data3[] = array($array[$key],$vendor_code_dropdown,$mapped_string);
          // $data2['vendor_email'] = $table_array_email;
          // $data2['vendor_name'] = $table_array_name;
          // $data2['vendor_retailer'] = $table_array_retailer;
          // $data2['vendor_other_retailer'] = $table_array_other_retailer;
        }   
      }                  
      
      // email array 
      foreach ($register->result() as $row)
      {
        $reset_pass_link = '';
        $part1 = $row->ven_email;
        //$part2 = $row->ven_name;
        $array =  explode('/', $part1);
        //$array2 =  explode('/', $part2);
        foreach($array as $key => $row2)
        {
          $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '".$array[$key]."' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');
          $empty = '';
          $check_reset_link = $this->db->query("SELECT * FROM lite_b2b.reset_pass_list WHERE user_guid = '$get_user_guid' AND customer_guid = '$customer_guid'");
          // echo $this->db->last_query();
          if($check_reset_link->num_rows() > 0)
          {
            $reset_url = 'https://b2b.xbridge.my/index.php/Key_in/key_in?si='.$check_reset_link->row('reset_guid').'&ug='.$check_reset_link->row('user_guid');
            $reset_pass_link = $reset_url;
            $duplicate = 0;
          }
          else
          {
            $reset_pass_link = 'No reset link';
            $duplicate = 1;
          }
          $reset_link_text = '<div style="display:flex;"><input class="form-control" type="text" id="copy_link_'.$key.'" value="'.$reset_pass_link.'" readonly>&nbsp;&nbsp;<i class="fa fa-copy" id="copy_link" seq="'.$key.'"></i></div>';
          $checkbox = '<input type="checkbox" class="form-checkbox" id="check_email_link" name="checkall_input_table[]" table_id="email_tb" supplier_guid="'.$supplier_guid.'" reset_g= "'.$check_reset_link->row('reset_guid').'" customer_guid="'.$customer_guid.'" duplicate="'.$duplicate.'" link="'.$reset_pass_link.'" u_g="'.$get_user_guid.'" vendor_email='.$array[$key].'/>';
          $data4[] = array($array[$key],$reset_link_text,$checkbox);
        } 
      }    

      foreach ($register->result() as $row)
      {
        $part1 = $row->ven_email;
        $check_exists2 = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '".$part1."' AND acc_guid = '".$register->row('customer_guid')."'");
        //$part2 = $row->ven_name;
        $array =  explode('/', $part1);
        //$array2 =  explode('/', $part2);
        foreach($array as $key => $row2)
        {
          $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '".$array[$key]."' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');

          $selected_report = $this->db->query("SELECT * FROM check_email_schedule a WHERE a. user_guid = '$get_user_guid'");
          // echo $this->db->last_query();
          $selected_report_array = array();
          foreach($selected_report->result() as $selected_code)
          {
            $selected_report_array[] = $selected_code->report_guid;   
          }  

          $report_type = $this->db->query("SELECT * from set_report_query where active = '1' AND report_guid = 'AAF708CA914A11E887B5000D3AA2838A' order by report_type , report_name asc");

          $report_type_dropdown = '<select style="width:100%" class="selectpicker" multiple>';
          $mapped_string = '';
          foreach($report_type->result() as $dropdown)
          {
            if(in_array($dropdown->report_guid,$selected_report_array))
            {
              $selected = 'selected';
              $mapped_string .= $dropdown->report_name.',';     
            }
            else
            {
              // echo 1;
              $selected = '';  
              $mapped_string .= '';  
            }
            $report_type_dropdown .= '<option value="'.$dropdown->report_guid.'"'.$selected.' >'.$dropdown->report_name.'</option>';
          }
          $report_type_dropdown .= '</select>';

          $empty = '';
          $email_list_status = $this->db->query("SELECT GROUP_CONCAT(DISTINCT report_name) as report_name from check_email_schedule WHERE user_guid = '$get_user_guid' GROUP BY user_guid")->row('report_name');
          if($email_list_status == '' || $email_list_status == null)
          {
              $email_list_status = 'No report schedule';
          }
          $email_subscribe_status = $this->db->query("SELECT GROUP_CONCAT(DISTINCT report_name) as report_name from check_email_schedule WHERE user_guid = '$get_user_guid' GROUP BY user_guid")->row('report_name');
          if($email_subscribe_status == '' || $email_subscribe_status == null)
          {
              $email_subscribe_status = 'Email not subscribe';
          }          
          $data5[] = array($array[$key],$report_type_dropdown,$email_list_status);
        } 
      } 

      if($register->num_rows() == 0)
      {
        $data2 = '';
        $data3 = '';
        $data4 = ''; // email
        $data5 = '';
      }     

      $data = array(

         'register' => $register,
         'new_supplier' => $new_supplier,
         'acc_branch' => $acc_branch,
         'ven_agency_sql' => $ven_agency_sql,
         'vendor_code_sql' => $vendor_code_sql,
         'myArray' =>$myArray,
         'supplier_guid' => $supplier_guid,
         'customer_guid' => $customer_guid,
         'table_array' => $data2,
         'table_array2' => $data3,
         'add_vendor_code' => $add_vendor_code->result(),
         'email_array' => $data4,
         'table_array3' => $data5,
      );

      
       //print_r($data);die;

       $this->load->view('header'); 
       $this->load->view('register/register_forms_edit', $data);  
       $this->load->view('footer' );  
  }
  

  public function register_update() 
  {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
           $register_guid = $_REQUEST['register_guid'];
           $register = $this->db->query("SELECT * FROM register_child a INNER JOIN register b ON a.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid' ");
           
           $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
           
           $hidden_val = $this->input->post('hidden_val');
           $ven_agency_add = $this->input->post('ven_agency_add');
           $comp_mail = $this->input->post('comp_mail');
           $comp_no = $this->input->post('comp_no');
           $comp_add = $this->input->post('comp_add');
           $comp_email = $this->input->post('comp_email');
           $comp_contact = $this->input->post('comp_contact');
           $comp_fax = $this->input->post('comp_fax');
           $comp_post = $this->input->post('comp_post');
           $comp_state = $this->input->post('comp_state');
           $supply_type = $this->input->post('supply_type');
           $bus_desc = $this->input->post('bus_desc');
           $bus_desc_others = $this->input->post('bus_desc_others');
           $acc_name = $this->input->post('acc_name');
           $acc_no = $this->input->post('acc_no');
           $acc_no_other = $this->input->post('acc_no_other');
           $ven_name = $this->input->post('ven_name');
           $ven_designation = $this->input->post('ven_designation');
           $ven_phone = $this->input->post('ven_phone');
           $ven_email = $this->input->post('ven_email');
           $ven_agency = $this->input->post('ven_agency');
           $ven_ven = $this->input->post('ven_ven');
           $comp_name = $this->input->post('comp_name');
           //$comp_email = $this->input->post('comp_email');
           $part_name = $this->input->post('part_name');
           $part_ic = $this->input->post('part_ic');
           $part_mobile = $this->input->post('part_mobile');
           $part_email = $this->input->post('part_email');
           $isdelete = $this->input->post('isdelete');
           $created_at = $this->db->query("SELECT now() as now")->row('now');
           $updated_at = $this->db->query("SELECT now() as now")->row('now');
           //print_r($hidden_val); die;
           
          $store_code = $register->row('store_code'); 
          $myArray = explode(',', $store_code);

          $diff_code = array_diff($acc_no, $myArray);

          $combine = array_merge($myArray, $diff_code);

           $data = array(
                // 'comp_email' => $comp_mail,
                 'update_at' => $updated_at,
                 'update_by' => $user_id,
                 'isactive' => 0
            );
            

            $this->db->where('register_guid', $register_guid);
            $this->db->update('register', $data);

            if($register->num_rows() != 0 )
            {
                $data = array(
                    //'register_guid' => $register_guid,
                    'comp_no' => $comp_no,
                    'org_email' =>$comp_mail,
                    'org_part_email' =>$comp_email,
                    'comp_add' =>$comp_add,
                    'comp_contact' =>$comp_contact,
                    'comp_fax' =>$comp_fax,
                    'supply_type' =>$supply_type,
                    'bus_desc' =>$bus_desc,
                    'bus_desc_others' =>$bus_desc_others,
                    'acc_name' =>$acc_name,
                    'acc_no' =>implode(',', $acc_no),
                    'vendor_code_remark' =>implode(',', $acc_no_other),
                    'ven_name' =>implode('/',$ven_name),
                    'ven_designation' =>implode('/',$ven_designation),
                    'ven_phone' =>implode('/', $ven_phone),
                    'ven_email' =>implode('/', $ven_email),
                    'ven_agency' =>implode('/',$hidden_val),
                    'part_name' => implode('/', $part_name),
                    'part_ic' => implode('/', $part_ic),
                    'part_mobile' => implode('/', $part_mobile),
                    'part_email' => implode('/', $part_email),
                    'isdelete' => $isdelete,
                    'form_status' => 'In-Progress'
                    
                );
                 
                    //print_r($data);die;
                $this->db->where('register_guid', $register_guid);
                $this->db->update('register_child', $data);

                $data = array(


                    'supplier_add' => $comp_add,
                    'supplier_postcode' => $comp_post,
                    'supplier_state' => $comp_state
                   
                );
                    //print_r($data);die;
                $this->db->where('register_guid', $register_guid);
                $this->db->update('set_supplier_info', $data);
            }
            else
            {
                 $data = array(
                    'register_guid' => $register_guid,
                    'comp_name' => $comp_name,
                    'comp_no' => $comp_no,
                    'org_email' =>$comp_mail,
                    'org_part_email' =>$comp_email,
                    'comp_add' =>$comp_add,
                    'comp_contact' =>$comp_contact,
                    'comp_fax' =>$comp_fax,
                    'supply_type' =>$supply_type,
                    'bus_desc' =>$bus_desc,
                    'bus_desc_others' =>$bus_desc_others,
                    'acc_name' =>$acc_name,
                    'acc_no' =>implode(',', $acc_no),
                    'vendor_code_remark' =>implode(',', $acc_no_other),
                    'ven_name' =>implode('/',$ven_name),
                    'ven_designation' =>implode('/',$ven_designation),
                    'ven_phone' =>implode('/', $ven_phone),
                    'ven_email' =>implode('/', $ven_email),
                    'ven_agency' =>implode('/',$hidden_val),
                    'part_name' => implode('/', $part_name),
                    'part_ic' => implode('/', $part_ic),
                    'part_mobile' => implode('/', $part_mobile),
                    'part_email' => implode('/', $part_email),
                    'isdelete' => $isdelete,
                    'form_status' => 'In-Progress'
                    
                );
                 
                    //print_r($data);die;
                $this->db->insert('register_child', $data);

                $data = array(

                    'supplier_add' => $comp_add,
                    'supplier_postcode' => $comp_post,
                    'supplier_state' => $comp_state
                   
                );
                    //print_r($data);die;
                $this->db->where('register_guid', $register_guid);
                $this->db->update('set_supplier_info', $data);
            }

            if(count($diff_code) != 0) {

            $coding = implode(",",$combine);

            $this->db->query("UPDATE register_child SET store_code ='$coding' WHERE register_guid = '$register_guid' ");

           }

            //} else {
            echo "<div id='myModal' class='modal'><div class='modal-content'><span class='close'>&times;</span><p>Some text in the Modal..</p></div></div>";
            //echo '<script> location.reload(); </script>';
            redirect(site_url('Registration/register_form_edit?register_guid='.$register_guid.''));
           // }
        }  
        else
        {
            redirect('login_c');
        }
  }

  public function register_update_new() 
  {
    if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
    {
      $register_guid = $_REQUEST['register_guid'];
      $register = $this->db->query("SELECT * FROM register_child a INNER JOIN register b ON a.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid' ");
      
      //$register_c_guid = $register->row('register_c_guid');

      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');

      $isdelete = $this->input->post('isdelete');
      $created_at = $this->db->query("SELECT now() as now")->row('now');
      $updated_at = $this->db->query("SELECT now() as now")->row('now');

      $comp_name = $this->input->post('comp_name');
      $comp_no = $this->input->post('comp_no');
      $comp_add = $this->input->post('comp_add');
      $comp_contact = $this->input->post('comp_contact');
      $comp_fax = $this->input->post('comp_fax');
      $acc_name = $this->input->post('acc_name');
      $acc_no = $this->input->post('acc_no');
      $acc_no = implode(',', $acc_no);
      $store_code = $register->row('store_code'); 
      $acc_no_other = $this->input->post('acc_no_other');
      $vendor_code_remark = implode(',', $acc_no_other);
      $comp_mail = $this->input->post('comp_mail');
      $comp_email = $this->input->post('comp_email');
      
      $insert_main = $this->db->query("INSERT INTO lite_b2b.register_new (`customer_guid`,`supplier_guid`,`register_guid`,`create_by`,`create_at`,`update_by`,`update_at`,`isactive`,`isset`,`register_no`,`comp_email`,`isinvoice`,`isinvoice_training`,`comp_name`,`comp_no`,`comp_add`,`comp_contact`,`comp_fax`,`acc_name`,`acc_no`,`store_code`,`vendor_code_remark`,`org_email`,`org_part_email`,`form_status`)
        SELECT `customer_guid`,`supplier_guid`,`register_guid`,`create_by`,`create_at`,`update_by`,`update_at`,`isactive`,`isset`,`register_no`,`comp_email`,`isinvoice`,`isinvoice_training`, '$comp_name' , '$comp_no','$comp_add','$comp_contact','$comp_fax','$acc_name','$acc_no','$store_code','$vendor_code_remark','$comp_mail','$comp_email','Migrated'
        FROM lite_b2b.register 
        WHERE register_guid = '$register_guid' ");

      $supply_type = $this->input->post('supply_type');
      $bus_desc = $this->input->post('bus_desc');
      $bus_desc_others = $this->input->post('bus_desc_others');

      if($supply_type == 'consignment')
      {
        $update_supplier_info = $this->db->query("UPDATE `lite_b2b`.`set_supplier_info` SET `supply_consignment` = '$supply_type' WHERE `register_guid` = '$register_guid'");
      }
      else
      {
        $update_supplier_info = $this->db->query("UPDATE `lite_b2b`.`set_supplier_info` SET `supply_outright` = '$supply_type' WHERE `register_guid` = '$register_guid'");
      }


      if($bus_desc == 'Bread')
      {
        $update_supplier_info = $this->db->query("UPDATE `lite_b2b`.`set_supplier_info` SET `bus_bread` = '$bus_desc' , `bus_desc_others` = '$bus_desc_others'WHERE `register_guid` = '$register_guid'");
      }
      else
      {
        $update_supplier_info = $this->db->query("UPDATE `lite_b2b`.`set_supplier_info` SET `bus_fresh` = '$bus_desc' , `bus_desc_others` = '$bus_desc_others' WHERE `register_guid` = '$register_guid'");
      }

      $ven_name = $this->input->post('ven_name');
      $ven_designation = $this->input->post('ven_designation');
      $ven_phone = $this->input->post('ven_phone');
      $ven_email = $this->input->post('ven_email');
      $hidden_val = $this->input->post('hidden_val');
      
      //print_r($ven_name); die;
      $array_num = count($ven_name); 
      $filter_array = array_filter($ven_name);

      if(count($filter_array) != 0)
      {
        for($i=0;$i<$array_num;$i++)
        { 
          unset($val_name);
          unset($val_designation);
          unset($val_phone);
          unset($val_email);
          unset($val_outlet);
          $register_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
          $register_mapping_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
          $register_mapping_guid_1 = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
          $val_name = $ven_name[$i];
          $val_designation = $ven_designation[$i];
          $val_phone = $ven_phone[$i];
          $val_email = $ven_email[$i];
          $val_outlet = $hidden_val[$i];
          
          $insert_child = $this->db->query("INSERT INTO lite_b2b.register_child_new (`supplier_guid`,`register_guid`,`register_c_guid`,`ven_name`,`ven_designation`,`ven_phone`,`ven_email`,`isdelete`,`part_type`,`created_at`,`created_by`)
          SELECT `supplier_guid`,`register_guid`, '$register_c_guid' ,'$val_name','$val_designation','$val_phone','$val_email', '0', 'registration', '$created_at', 'super_testing'
          FROM lite_b2b.register_child 
          WHERE register_guid = '$register_guid'");

          $insert_mapping_outlet = $this->db->query("INSERT INTO `lite_b2b`.`register_child_mapping` (`register_guid`, `register_c_guid`, `register_mapping_guid`, `mapping_type`, `ven_agency`) VALUES ('$register_guid', '$register_c_guid', '$register_mapping_guid', 'outlet', '$val_outlet');");

          $insert_mapping_code = $this->db->query("INSERT INTO `lite_b2b`.`register_child_mapping` (`register_guid`, `register_c_guid`, `register_mapping_guid`, `mapping_type`, `ven_code`) VALUES ('$register_guid', '$register_c_guid', '$register_mapping_guid_1', 'code','$acc_no');");

        }
      }
      
      $part_name = $this->input->post('part_name');
      $part_ic = $this->input->post('part_ic');
      $part_mobile = $this->input->post('part_mobile');
      $part_email = $this->input->post('part_email');
      $array_num_part = count($part_name);
      $filter_array_part = array_filter($part_name);

      if(count($filter_array_part) != 0)
      {
        for($p=0;$p<$array_num_part;$p++)
        {
          unset($val_part_name);
          unset($val_part_ic);
          unset($val_part_mobile);
          unset($val_part_email);
          $register_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
          $val_part_name = $part_name[$p];
          $val_part_ic = $part_ic[$p];
          $val_part_mobile = $part_mobile[$p];
          $val_part_email = $part_email[$p];

          $insert_child = $this->db->query("INSERT INTO lite_b2b.register_child_new (`supplier_guid`,`register_guid`,`register_c_guid`,`part_name`,`part_ic`,`part_mobile`,`part_email`,`isdelete`,`part_type`,`created_at`,`created_by`)
          SELECT `supplier_guid`,`register_guid`, '$register_c_guid','$val_part_name','$val_part_ic','$val_part_mobile','$val_part_email', '0', 'training', '$created_at', 'super_testing'
          FROM lite_b2b.register_child 
          WHERE register_guid = '$register_guid'");
        }
      }

      $update_old = $this->db->query("UPDATE `lite_b2b`.`register_child` SET `form_status` = 'Migrated' WHERE `register_guid` = '$register_guid'");

      redirect(site_url('Registration_new/register_form_edit_new?register_guid='.$register_guid.''));
    }  
    else
    {
      redirect('login_c');
    }
  }

  public function register_save() 
  {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
          
          $register_guid = $_REQUEST['register_guid'];
          $register = $this->db->query("SELECT * FROM register_child a INNER JOIN register b ON a.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid' ");

          $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');


          $comp_no = $this->input->post('comp_no');
          $comp_add = $this->input->post('comp_add');
          $comp_email = $this->input->post('comp_email');
          $comp_contact = $this->input->post('comp_contact');
          $comp_fax = $this->input->post('comp_fax');
          $comp_post = $this->input->post('comp_post');
          $comp_state = $this->input->post('comp_state');
          $supply_type = $this->input->post('supply_type');
          $bus_desc = $this->input->post('bus_desc');
          $bus_desc_others = $this->input->post('bus_desc_others');
          $acc_name = $this->input->post('acc_name');
          $acc_no = $this->input->post('acc_no');
          $ven_name = $this->input->post('ven_name');
          $ven_designation = $this->input->post('ven_designation');
          $ven_phone = $this->input->post('ven_phone');
          $ven_email = $this->input->post('ven_email');
          $ven_agency = $this->input->post('ven_agency');
          $comp_name = $this->input->post('comp_name');
          $comp_email = $this->input->post('comp_email');
          $part_name = $this->input->post('part_name');
          $part_ic = $this->input->post('part_ic');
          $part_mobile = $this->input->post('part_mobile');
          $part_email = $this->input->post('part_email');
          $isdelete = $this->input->post('isdelete');
          $created_at = $this->db->query("SELECT now() as now")->row('now');
          $updated_at = $this->db->query("SELECT now() as now")->row('now');

          $data = array(

                 'updated_at' => $updated_at,
                 'updated_by' => $user_id,
                 'isactive' => 0

            );
           //print_r($data);die;
          
          $this->db->where('register_guid', $register_guid);
          $this->db->update('register', $data);

          //{
         
          $data = array(

                //'register_guid' => $register_guid,
                'comp_no' => $comp_no,
                'comp_email' =>$comp_email,
                'comp_add' =>$comp_add,
                'comp_contact' =>$comp_contact,
                'comp_fax' =>$comp_fax,
                'supply_type' =>$supply_type,
                'bus_desc' =>$bus_desc,
                'bus_desc_others' =>$bus_desc_others,
                'acc_name' =>$acc_name,
                'acc_no' =>implode(',', $acc_no),
                'ven_name' =>implode('/',$ven_name),
                'ven_designation' =>implode('/',$ven_designation),
                'ven_phone' =>implode('/', $ven_phone),
                'ven_email' =>implode('/', $ven_email),
                'ven_agency' =>implode('/',$ven_agency),
                'part_name' => implode('/', $part_name),
                'part_ic' => implode('/', $part_ic),
                'part_mobile' => implode('/', $part_mobile),
                'part_email' => implode('/', $part_email),
                'isdelete' => $isdelete
                
         );

             
                //print_r($data);die;
          $this->db->where('register_guid', $register_guid);
          $this->db->update('register_child', $data);

          $data = array(

                'supplier_add' => $comp_add,
                'supplier_postcode' => $comp_post,
                'supplier_state' => $comp_state    
            );

             
                //print_r($data);die;
          $this->db->where('register_guid', $register_guid);
          $this->db->update('set_supplier_info', $data);
                 
        
            //} else {
          echo "<div id='myModal' class='modal'><div class='modal-content'><span class='close'>&times;</span><p>Some text in the Modal..</p></div></div>";
               
           // }
        } 
  }

  public function send_mail() 
  {
       die;
      // die here
          $session_id = $_REQUEST['session_id'];
          $mail = new PHPMailer();
          $mail->IsSMTP();                       // telling the class to use SMTP
          $mail->SMTPDebug = 0;                  
          // 0 = no output, 1 = errors and messages, 2 = messages only.
          $mail->SMTPAuth = true;                // enable SMTP authentication
          $mail->SMTPSecure = "tls";              // sets the prefix to the servier
          $mail->Host = "mail.xbridge.my";        // sets Gmail as the SMTP server
          $mail->Port = 587;                     // set the SMTP port for the GMAIL
          $mail->Username = "admin@xbridge.my";  // Gmail username
          $mail->Password = "x123bridge"; 
          $mail->CharSet = 'windows-1250';
          $mail->SetFrom ('admin@xbridge.my', 'admin@xbridge.my');
          $mail->AddBCC ( 'danielweng57@gmail.com', 'danielweng57@gmail.com');
          $mail->addReplyTo( 'B2B-noreply@xbridge.com', 'B2B-noreply@xbridge.com');
          // $mail->AddAddress ('suriya.chandrika@pandasoftware.my', 'Suriya Chandrika Sivaneson');     
          //$mail->To( 'suriyab6@gmail.com', 'suriyab6@gmail.com');
          $mail->Subject = "Online Registration Application";
          $mail->ContentType = 'text/plain';
          $mail->IsHTML(false);
          $mail->Body = $this->local_ip."/PANDA_GITHUB/panda_b2b/index.php/Registration/new_user?session_id=$session_id"; 
         
          if(!$mail->Send())
          {
                  $error_message = "Mailer Error: " . $mail->ErrorInfo;
          } else 
          {
                  $error_message = "Successfully sent!";
          }
           redirect('Registration/register_admin');
  }

  public function register_thank()
  {
      $this->load->view('register/header2'); 
      $this->load->view('register/thank');  
  }

  public function transaction()
  {
      $comp_name = $this->input->post('comp_name');
      $register_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS register_guid")->row('register_guid');
      $register_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS register_c_guid")->row('register_c_guid');
      //$supplier_guid = $this->db->query("SELECT * FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid  ='".$_SESSION['customer_guid']."' AND supplier_name = '$comp_name' GROUP BY a.supplier_guid")->row('supplier_guid');
      $supplier_query = $this->db->query("SELECT a.* FROM set_supplier a WHERE supplier_guid = '$comp_name' ORDER BY a.supplier_name ASC");
      $supplier_guid = $supplier_query->row('supplier_guid');
      $supplier_name = $supplier_query->row('supplier_name');

      $supplier_info_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS supplier_info_guid")->row('supplier_info_guid');
      $active = $this->db->query("SELECT a.isactive FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid  INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'")->row('isactive');
      $supplier = $this->db->query("SELECT a.*,b.* FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('supplier_guid');
      $retailer = $this->db->query("SELECT acc_name FROM  acc c INNER JOIN register a ON c.acc_guid = a.customer_guid WHERE a.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('acc_name');
      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
      $session_id = $this->db->query("SELECT supplier_group_guid FROM set_supplier_group a WHERE a.`supplier_guid`= '$supplier_guid'")->row('supplier_group_guid');
      //a.`customer_guid`= '".$_SESSION['customer_guid']." ' AND
      $supplier_info_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS supplier_info_guid")->row('supplier_info_guid');
      $re_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(register_no, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS re_no  FROM `register`  WHERE  SUBSTRING(register_no, - 8, 4) = CONCAT( RIGHT(YEAR(NOW()), 2), LPAD(MONTH(NOW()), 2, 0) )")->row('re_no');
      $todaydate = date('Ydhs');
      $todaydate2 = substr($todaydate, 2);
      $register_no = $this->db->query("SELECT concat( '$todaydate2', '$re_no' ) as refno")->row('refno');
      $comp_no = $this->input->post('comp_no');
      $acc_name = $this->input->post('acc_name');
      $acc_no = $this->input->post('acc_no');
      
      $comp_email = $this->input->post('comp_email');
      $comp_post = $this->input->post('comp_post');
      $comp_state = $this->input->post('comp_state');
      //$supp_guid = $this->input->post('comp_name');
      $create_at = $this->db->query("SELECT now() as now")->row('now');
      $update_at = $this->db->query("SELECT now() as now")->row('now');

      $acc_no = implode(",",$acc_no);
      $acc_no = "".$acc_no."";

      $check_transaction = $this->db->query("SELECT * FROM register WHERE customer_guid = '".$_SESSION['customer_guid']."' AND supplier_guid = '$supplier_guid' ");

      if($check_transaction->num_rows() > 0)
      {
        echo "<script> alert('Error create new transaction due to more than one supplier under the retailer.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/Registration/register_admin' </script>";
        exit();
      }

      $data = array(

            'supplier_info_guid' => $supplier_info_guid,
            'supplier_add' => $comp_add,
            'supplier_postcode' => $comp_post,
            'supplier_state' => $comp_state,
            'register_guid' => $register_guid
            

        );
       //print_r($data);die;
       $this->db->insert('set_supplier_info', $data);

       $data = array(

            'register_guid' => $register_guid,
            'customer_guid' => $_SESSION['customer_guid'],
            'supplier_guid' => $supplier_guid,
            //'session_id' => $session_id,
            'create_at' => $create_at,
            'create_by' => $user_id,
            'update_at' => $update_at,
            'update_by' => $user_id,
            'isactive' => $active,
            'comp_email' =>$comp_email,
            'register_no' => $register_no,
            'isactive' => 1

        );
       
        $this->db->insert('register', $data);

        $data = array(

          'register_c_guid' => $register_c_guid,
          'register_guid' => $register_guid,
          'supplier_guid' => $supplier_guid,
          //'session_id' => $session_id,
          'comp_name' => $supplier_name,
          'comp_no' => $comp_no,
          'comp_email' =>$comp_email,
          'acc_name' =>$acc_name,
          'acc_no' =>$acc_no,
          'store_code' => $acc_no,

        );

        $this->db->insert('register_child', $data);
        redirect('Registration/register_admin');
  }

  public function edit_reg_app()
  {
      $edit_reg_guid = $this->input->post('edit_reg_guid');
      $edit_email = $this->input->post('edit_email');
      $edit_reg_no = $this->input->post('edit_reg_no');
      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
      $updated_at = $this->db->query("SELECT now() as now")->row('now');

      $data = array(
           'comp_email' => $edit_email,
           'register_no' => $edit_reg_no,
           'update_at' => $updated_at,
           'update_by' => $user_id,
      );

      $this->db->where('register_guid', $edit_reg_guid);
      $this->db->update('register', $data);

      $data = array(
           'comp_email' => $edit_email,
      );

      $this->db->where('register_guid', $edit_reg_guid);
      $this->db->update('register_child', $data);

      $error = $this->db->affected_rows();

      if($error > 0){

         $data = array(
          'para1' => 0,
          'msg' => 'Update Successfully',

          );    
          echo json_encode($data);   
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Update Successfully.',

          );    
          echo json_encode($data);   
      }
  }

  public function check_code()
  {
      $this->db->where('acc_no', $this->input->post('acc_no[]'));
      $query = $this->db->get('register_child');
      if($query->num_rows() >= 1)
      {
        echo 'value does exist';
      }
  }

  //created by jr for fetch reg no
  public function fetch_reg_no()
  {
        $customer_guid = $_SESSION['customer_guid'];
        $type_val = $this->input->post('type_val');
        $Code = $this->db->query("
        SELECT 
            a.reg_no,
            a.supplier_guid 
        FROM
          set_supplier a
        WHERE  a.`supplier_guid` = '$type_val'
        GROUP BY reg_no ");
        //$supplier_guid = $Code->row('supplier_guid');

        $vendor = $this->db->query("SELECT a.`code` AS vendor_code,a.name FROM b2b_summary.supcus a WHERE a.customer_guid = '$customer_guid' AND type = 'S' GROUP BY customer_guid,`code` ");
       
        $data = array(
            'Code' => $Code->result(),
            'vendor' => $vendor->result(),
        );

        echo json_encode($data);
  }
 
  public function proceed_vendor()
  {
      $register_guid = $this->input->post('register_guid');
      $customer_guid = $this->input->post('customer_guid');
      // echo $customer_guid;die;
      $table_child = $this->input->post('table_name1');
      $table_main = $this->input->post('table_name2');
      $register = $this->db->query("SELECT * FROM $table_child a INNER JOIN $table_main b ON a.register_guid = b.register_guid LEFT JOIN set_supplier_info d ON d.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid' ");
      $get_supp = $this->db->query("SELECT supplier_guid FROM $table_main b WHERE b.`register_guid` = '$register_guid'");
      $supplier_guid = $get_supp->row('supplier_guid');
      $ven_code = '';
      $vendor_code1 = $register->row('acc_no');
      $vendor_code1 =  explode(',', $vendor_code1);
      $msg = '';
      foreach ($vendor_code1 as $supplier_group_name)
      {
        $value = trim($supplier_group_name);
        $check_name = $this->db->query("SELECT * FROM lite_b2b.`set_supplier_group` a WHERE supplier_group_name = '$value' AND supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");

        if($check_name->num_rows() > 0)
        {
            $ven_code .= $supplier_group_name;
            $ven_code = "".$ven_code." ";
            $result = explode(' ', $ven_code );
            $result = implode(' ', $result );
             

            $para1 = 0;
            $msg .= 'Vendor Code :'.$supplier_group_name.' already exists.\n';

        }

        $supplier_group_name = trim($supplier_group_name);
        if($check_name->num_rows() <= 0 )
        {
          $insert_value = array(
          'supplier_guid' => $supplier_guid,
          'supplier_group_guid' => $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid'),
          'supplier_group_name' => $supplier_group_name,
          'customer_guid' => $customer_guid,
          'backend_supcus_guid' => $this->db->query("SELECT supcus_guid as  backend_supcus_guid from b2b_summary.supcus where customer_guid =  '".$customer_guid."' and code = '$supplier_group_name'")->row('backend_supcus_guid'),
          'backend_supplier_code' =>  $supplier_group_name,
          'created_at' => $this->db->query("SELECT now() as today")->row('today'),
          'created_by' => $_SESSION['userid'],
          'updated_at' => $this->db->query("SELECT now() as today")->row('today'),
          'updated_by' => $_SESSION['userid'],
        );
        $this->db->insert('lite_b2b.set_supplier_group', $insert_value);

        $para1 = 0;
        $msg .= 'Vendor Code :'.$supplier_group_name.' Mapped Successfully.\n';  

        $email_group = $this->db->query("SELECT a.user_id as email,a.user_name as first_name FROM set_user a INNER JOIN set_user_group b ON a.user_group_guid = b.user_group_guid INNER JOIN set_user_module c ON b.user_group_guid = c.user_group_guid INNER JOIN set_module d ON c.module_guid = d.module_guid INNER JOIN set_module_group e ON d.module_group_guid = e.module_group_guid WHERE a.isactive = 1 AND a.acc_guid = '$customer_guid' AND e.module_group_name = 'Panda B2B' AND c.isenable = 1 AND d.module_code = 'RENSS' AND a.acc_guid != 'D361F8521E1211EAAD7CC8CBB8CC0C93' AND a.acc_guid != '1F90F5EF90DF11EA818B000D3AA2CAA9' AND a.acc_guid != '599348EDCB2F11EA9A81000C29C6CEB2' AND a.acc_guid != '907FAFE053F011EB8099063B6ABE2862' AND a.acc_guid != '13EE932D98EB11EAB05B000D3AA2838A' GROUP BY a.user_guid");
         // AND a.acc_guid != 'D361F8521E1211EAAD7CC8CBB8CC0C93' AND a.acc_guid != '1F90F5EF90DF11EA818B000D3AA2CAA9' AND a.acc_guid != '599348EDCB2F11EA9A81000C29C6CEB2'
        // print_r($email_group->result());die;
        if($email_group->num_rows() > 0)
        {
            $b2b_summary_table = 'b2b_summary';
            $email_name = $email_group->row('first_name');
            $email_add = $email_group->row('email');
            $date = $this->db->query("SELECT now() as now")->row('now');
            $supplier_detail = $this->db->query("SELECT * FROM set_supplier WHERE supplier_guid = '$supplier_guid'");
            $supplier_vendor_code_detail = $this->db->query("SELECT code as code,name as name from $b2b_summary_table.supcus where customer_guid =  '".$_SESSION['customer_guid']."' and code = '$supplier_group_name'");
            $customer_name = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'");
            $url = 'https://b2b.xbridge.my';

            $bodyContent = '<div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-info">
                                            B2B Notification
                                        </h3>
                                        <p class="lead">
                                        Following suppliers has registered @ xBridge B2B Portal for Retailer: '.$customer_name->row('acc_name').'<br>
                                             Supplier: '.$supplier_detail->row('supplier_name').' ('.$supplier_detail->row('reg_no').')<br>Vendor Code: '.$supplier_vendor_code_detail->row('code').'<br>Date: '.$date.'
                                            <br>Note: Please turn on the B2B Flag @ Panda Backend Supplier Setup.<br>
                                            Regards,<br>
                                            <a href="'.$url.'"> xBridge B2B Portal</a>
                                        </p>
                                    </div>
                                </div>
                            </div>';
                            // echo $bodyContent;die;
            foreach($email_group->result() as $row)
            {
                $email_name = $row->first_name;
                $email_add = $row->email;
                // $email_name = 'danielweng57@gmail.com';
                // $email_add = 'danielweng57@gmail.com';                
                $subject = 'Supplier Subscription';
                // echo $email_name,$email_add;die;
                // $this->send_to_manager($email_add, $email_name, $date, $bodyContent);
                $this->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','support@xbridge.my');
                // echo 1;die;
                // echo $email_name.$email_add.'<br>';
            }
        }                

        }else
        {
          continue;
        }
        
      }
      $data = array(
      'para1' => $para1,
      'msg' => $msg,
      );  
      $error = $this->db->affected_rows();
      $check_status = 1;
      if($check_status > 0)
      {
        echo json_encode($data);
      }
      else if($error > 0)
      {
          $data = array(
          'para1' => 0,
          'msg' => 'Successfully',

          );    
          echo json_encode($data);      
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Error',

          );  
          echo json_encode($data);        
      }
  }

  public function proceed_user()
  {
      $register_guid = $this->input->post('register_guid');
      $customer_guid = $this->input->post('customer_guid');
      $table_child = $this->input->post('table_name1');
      $table_main = $this->input->post('table_name2');
      $register = $this->db->query("SELECT * FROM $table_child a INNER JOIN $table_main b ON a.register_guid = b.register_guid LEFT JOIN set_supplier_info d ON d.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid' ");
      $get_supp = $this->db->query("SELECT supplier_guid FROM $table_main b WHERE b.`register_guid` = '$register_guid'");
      $supplier_guid = $get_supp->row('supplier_guid');
      // $user_group_guid = 'F6E92188DF5D11E9814B000D3AA2838A';
      //INNER JOIN set_supplier_group c ON c.supplier_group_guid = b.session_id

      $get_default_pass = $this->db->query("SELECT MIN(supplier_group_name) as default_pass FROM lite_b2b.set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'")->row('default_pass');

      $details = $this->input->post('details');
      $details = json_encode($details);
      $details = json_decode($details);      
      // print_r($details[1]->user_group);die;
      // echo $this->db->last_query();die;

      // echo $get_default_pass->row('default_pass');die;

      $ven_name = '';
      $vendor_name = $register->row('ven_name');
      $vendor_name =  explode('/', $vendor_name); // explode / data
      
      $vendor_email = $register->row('ven_email');
      $vendor_email =  explode('/', $vendor_email); // explode / data

      $vendor_array = array_combine($vendor_name,$vendor_email); // combine it
      $msg = '';
      $para = 0;
      $i = 0;
      foreach ($vendor_array as $vendor_name => $vendor_email ) 
      {
        $insert_loc = 0;
        $user_group_guid = $details[$i]->user_group;
        $i_loc_group = $details[$i]->loc_group;
        $i++;

        if($i_loc_group == '')
        {
          $para1 = 1;
          $msg .= 'Email Address :'.$vendor_email.' duplicate successfully\n';
          $insert_loc = 0;   
          continue;       
        }    
        // print_r($user_group_guid);
        // print_r($vendor_email);
        // $check_data = $this->db->query("SELECT a.*,d.`module_group_name`,e.`user_group_name` FROM set_user a INNER JOIN set_user_module b ON a.`user_group_guid` = b.`user_group_guid` INNER JOIN set_module c ON c.`module_guid` = b.`module_guid` INNER JOIN set_module_group d ON d.`module_group_guid` = c.`module_group_guid` AND d.`module_group_guid` = a.`module_group_guid` INNER JOIN set_user_group e ON e.`user_group_guid` = a.`user_group_guid` WHERE d.`module_group_guid` = '".$_SESSION['module_group_guid']."' AND a.`user_id` = '$vendor_email' GROUP BY a.`user_id`;");
        $check_data = $this->db->query("SELECT a.* FROM set_user a WHERE a.`module_group_guid` = '".$_SESSION['module_group_guid']."' AND a.`user_id` = '$vendor_email' AND a.acc_guid = '$customer_guid' GROUP BY a.`user_id`");
        // echo $this->db->last_query();
        // echo $check_data->num_rows();die;
        // if($check_data->num_rows() > 0)
        // { 

        //   $check_data = $this->db->query("SELECT a.* FROM set_user a WHERE a.`module_group_guid` = '".$_SESSION['module_group_guid']."' AND a.`user_id` = '$vendor_email' GROUP BY a.`user_id` ");
        //   $ven_name .= $vendor_name;
        //   $ven_name = "".$ven_name." ";
        //   $result = explode(' ', $vendor_email );
        //   $result = implode(' ', $result );


        //   $para1 = 1;
        //   $msg .= 'Email address :'.$result.' already exists\n';

        // };

        if($check_data->num_rows() <= 0)
        { 
          $check_status = 1;

          $check_data2 = $this->db->query("SELECT a.* FROM set_user a WHERE a.`module_group_guid` = '".$_SESSION['module_group_guid']."' AND a.`user_id` = '$vendor_email' AND a.acc_guid != '$customer_guid' GROUP BY a.`user_guid`");
          $ven_name .= $vendor_name;
          $ven_name = "".$ven_name." ";
          // $result = explode(' ', $vendor_email );
          // $result = implode(' ', $result );

          if($check_data2->num_rows() == 1)
          {
            $i_user_guid = $check_data2->row('user_guid');
            $data = array(
            //if b2b, acc_guid will be using session customer_guid
            'acc_guid' => $customer_guid,
            'module_group_guid' => $_SESSION['module_group_guid'],
            'isactive' => 1,
            'user_guid' => $i_user_guid,
            'user_group_guid' => $user_group_guid,
            'user_id' => $vendor_email,
            'user_name' => $vendor_name,
            'user_password' => $check_data2->row('user_password'),
            'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'updated_by' => $_SESSION['userid'],
            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'created_by' => $_SESSION['userid'],
            'limited_location' => 0,
            );
            $this->db->insert('lite_b2b.set_user', $data);

            $data_temp = array(
            //if b2b, acc_guid will be using session customer_guid
            'acc_guid' => $customer_guid,
            'module_group_guid' => $_SESSION['module_group_guid'],
            'isactive' => 1,
            'user_guid' => $i_user_guid,
            'user_group_guid' => $user_group_guid,
            'user_id' => $vendor_email,
            'user_name' => $vendor_name,
            'user_password' => $check_data2->row('user_password'),
            'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'updated_by' => $_SESSION['userid'],
            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'created_by' => $_SESSION['userid'],
            'limited_location' => 0,
            );             
            $this->db->insert('lite_b2b.set_user_temp_record', $data_temp);

            $para1 = 0;
            $msg .= 'Email Address :'.$vendor_email.' duplicate successfully\n';
            $insert_loc = 1;                
          }
          else if($check_data2->num_rows() > 1)
          {
            $para1 = 1;
            $msg .= 'Email address :'.$vendor_email.' error - existed twice with same unique id.\n';
          }
          else
          {
            $i_user_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');
            $data = array(
            //if b2b, acc_guid will be using session customer_guid
            'acc_guid' => $customer_guid,
            'module_group_guid' => $_SESSION['module_group_guid'],
            'isactive' => 1,
            'user_guid' => $i_user_guid,
            'user_group_guid' => $user_group_guid,
            'user_id' => $vendor_email,
            'user_name' => $vendor_name,
            'user_password' => md5($get_default_pass),
            'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'updated_by' => $_SESSION['userid'],
            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'created_by' => $_SESSION['userid'],
            'limited_location' => 0,
            );
            $this->db->insert('lite_b2b.set_user', $data);

            $data_temp = array(
            //if b2b, acc_guid will be using session customer_guid
            'acc_guid' => $customer_guid,
            'module_group_guid' => $_SESSION['module_group_guid'],
            'isactive' => 1,
            'user_guid' => $i_user_guid,
            'user_group_guid' => $user_group_guid,
            'user_id' => $vendor_email,
            'user_name' => $vendor_name,
            'user_password' => $get_default_pass,
            'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'updated_by' => $_SESSION['userid'],
            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'created_by' => $_SESSION['userid'],
            'limited_location' => 0,
            );            
            $this->db->insert('lite_b2b.set_user_temp_record', $data_temp);

            $data_rest_list = array(
            //if b2b, acc_guid will be using session customer_guid
            'reset_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid'),
            'customer_guid' => $customer_guid,
            'user_guid' => $i_user_guid,
            'email_id' => $vendor_email,
            'is_reset' => 0,
            'reset_at' => '1001-01-01 00:00:00',
            'created_by' => $_SESSION['userid'],
            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'updated_by' => $_SESSION['userid'],
            'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'ip' => '',
            'browser' => '',
            );  
            $this->db->insert('lite_b2b.reset_pass_list', $data_rest_list);

            $para1 = 0;
            $msg .= 'Email Address :'.$vendor_email .' insert successfully\n';    
            $insert_loc = 1;  
          } 
          if($insert_loc == 1)
          {
            $branch_array = explode(',',$i_loc_group);
            $this->db->query("DELETE FROM set_user_branch WHERE user_guid = '$i_user_guid' AND acc_guid = '$customer_guid'"); 

            foreach($branch_array as $i_branch_code)
            {
              $i_branch_details = $this->db->query("SELECT b.acc_guid,a.branch_guid FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid' AND a.branch_code = '$i_branch_code'");
              // print_r($i_branch_details->result());
              if($i_branch_details->num_rows() > 0)
              {
                $data_branch = array(
                'acc_guid' =>  $customer_guid,
                'branch_guid' => $i_branch_details->row('branch_guid'),
                'user_guid' => $i_user_guid,
                'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                'created_by' => 'reg_form',
                'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                'updated_by' => $_SESSION['userid'],
                );
                $this->db->insert('set_user_branch', $data_branch);   
              }           
            }
          }

        }
        else
        {
          $check_status = 1;
          $para1 = 1;
          $msg .= 'Email address :'.$vendor_email.' already existsed.\n';
        }
        $para = $para+$para1;
        // echo $check_data->num_rows();die;
      }
      if($para == 0)
      {
        $para = 0;
      }
      else
      {
        $para = 1;
      }

      $data = array(
        'para1' => $para,
        'msg' => $msg,
      );        
      // echo $check_data->num_rows();die;
      $error = $this->db->affected_rows();

      

      if($check_status > 0)
      { 
        echo json_encode($data);
      }
      else if($error > 0)
      {
          $data = array(
          'para1' => 0,
          'msg' => 'Successfully',

          );    
          echo json_encode($data);      
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Error',

          );  
          echo json_encode($data);        
      }
  }

  public function proceed_mapping()
  {
      $register_guid = $this->input->post('register_guid');
      $customer_guid = $this->input->post('customer_guid');
      $table_child = $this->input->post('table_name1');
      $table_main = $this->input->post('table_name2');
      // $vendor_email = $this->input->post('vendor_email');
      // $vendor_code = $this->input->post('vendor_code');

      $details = $this->input->post('details');
      $details = json_encode($details);
      $details = json_decode($details);

      $get_supp = $this->db->query("SELECT supplier_guid FROM $table_main b WHERE b.`register_guid` = '$register_guid'");
      $supplier_guid = $get_supp->row('supplier_guid');
      $created_by = $this->session->userdata('userid');

      // print_r($details);
      // die;

      // print_r($vendor_email); echo '<br>';
      // print_r($vendor_code);  
      foreach($details as $row4)
      {
          $get_supplier_code = $row4->vendor_code;
          // print_r(count($row4->vendor_code));die;
          if($get_supplier_code == '')
          {
               $data = array(
                'para1' => 1,
                'msg' => 'Vendor code cannot be empty',
                );    
               echo json_encode($data);die;
          }

          foreach($get_supplier_code as $supplier_group_name)
          {
              $supplier = $this->db->query("SELECT supplier_guid FROM set_supplier_group WHERE supplier_group_name = '$supplier_group_name' and customer_guid = '$customer_guid' AND supplier_guid = '$supplier_guid'");
              if($supplier->num_rows() <= 0)
              {
                // echo "<script> alert('no vendor code mapped') </script>";
                // die;
                 $data = array(
                  'para1' => 1,
                  'msg' => 'no vendor code mapped',
                  );    
                 echo json_encode($data);die;
              }            
          }
      }

      $string_code = '';
      foreach($details as $row)
      {
        // echo '1'.'<br>';
        // $vendor_code = $row->vendor_code;
        // foreach($vendor_code as $supplier_group_name)
        // {
        //   echo $supplier_group_name.'<br>';
        // }
        // echo $row->vendor_email.'<br>';

        //$user_id = $row->user_id;// if you get from query
        $user_id = $row->vendor_email;//if you post from table
        $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$user_id' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');
        // echo $get_user_guid->row('user_guid').'<br>';
        $check_relationship = $this->db->query("SELECT * FROM set_supplier_user_relationship where user_guid = '$get_user_guid' and supplier_guid = '$supplier_guid' and customer_guid = '$customer_guid'");

        if($check_relationship->num_rows() > 0)
        {
          // echo "<script> alert('need miss loo confirmation tommorrow') </script>";
          // die;
          // $para1 = 0;
          $get_supplier_code = $row->vendor_code;

          if($supplier_guid == '' || $customer_guid == '' || $get_user_guid == '' || $supplier_guid == null || $customer_guid == null || $get_user_guid == null)
          {
               $data = array(
                'para1' => 1,
                'msg' => 'Value Empty Error',
                );    
               echo json_encode($data);die;              
          }

          $this->db->query("DELETE FROM lite_b2b.set_supplier_user_relationship WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid' AND user_guid='$get_user_guid'");    

          foreach($get_supplier_code as $supplier_group_name)
          {
            // echo '2'.'<br>';
            $supplier_group_name = $supplier_group_name;
            $supplier_group_guid = $this->db->query("SELECT * FROM set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid' AND supplier_group_name = '$supplier_group_name'")->row('supplier_group_guid');

            // $data_test = $this->db->query("SELECT '$customer_guid' as customer_guid
            //     , '$supplier_guid' as supplier_guid
            //     , '".$supplier_group_name."' as supplier_group_guid
            //     , '$get_user_guid' as user_guid
            //     , now()
            //     , '".$this->session->userdata('userid')."'
            //     ");

            $data = array(
              'customer_guid' => $customer_guid,
              'supplier_guid' => $supplier_guid,
              'supplier_group_guid' => $supplier_group_guid,
              'user_guid' => $get_user_guid,
              'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
              // 'created_by' => $this->session->userdata('userid'),
              'created_by' => $created_by,
            );
            // print_r($data);

            $this->db->replace('lite_b2b.set_supplier_user_relationship',$data);
            $this->db->query("UPDATE lite_b2b.set_user SET supplier_guid = '$supplier_guid' WHERE user_guid = '$get_user_guid' AND acc_guid = '$customer_guid'");

            // $string_code .= 'need miss loo confirmation tommorrow\n';  
            $string_code .= $supplier_group_name.' delete and mapped to '.$user_id.'\n';

          }         
        }
        else
        {
          // $get_supplier_code = $this->db->query("SELECT * FROM set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");
          $get_supplier_code = $row->vendor_code;
          foreach($get_supplier_code as $supplier_group_name)
          {
            // echo '2'.'<br>';
            $supplier_group_name = $supplier_group_name;
            $supplier_group_guid = $this->db->query("SELECT * FROM set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid' AND supplier_group_name = '$supplier_group_name'")->row('supplier_group_guid');

            // $data_test = $this->db->query("SELECT '$customer_guid' as customer_guid
            //     , '$supplier_guid' as supplier_guid
            //     , '".$supplier_group_name."' as supplier_group_guid
            //     , '$get_user_guid' as user_guid
            //     , now()
            //     , '".$this->session->userdata('userid')."'
            //     ");

            $data = array(
              'customer_guid' => $customer_guid,
              'supplier_guid' => $supplier_guid,
              'supplier_group_guid' => $supplier_group_guid,
              'user_guid' => $get_user_guid,
              'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
              // 'created_by' => $this->session->userdata('userid'),
              'created_by' => $created_by,
            );
            // print_r($data);

            $this->db->replace('lite_b2b.set_supplier_user_relationship',$data);
            $this->db->query("UPDATE lite_b2b.set_user SET supplier_guid = '$supplier_guid' WHERE user_guid = '$get_user_guid' AND acc_guid = '$customer_guid'");

            $string_code .= $supplier_group_name.' mapped to '.$user_id.'\n';

          }

        }        
      }

      $para1 = 0;
      $msg = $string_code;  
      $check_status = 1;
      $data = array(
        'para1' => $para1,
        'msg' => $msg,
      );          


      // $vendor_email = explode(' ', $vendor_email);

      // foreach($vendor_email as $row)
      // {
      //   //$user_id = $row->user_id;// if you get from query
      //   $user_id = $row;//if you post from table
      //   $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$user_id' AND customer_guid = '$customer_guid' LIMIT 1");
      //   $check_relationship = $this->db->query("SELECT * FROM set_supplier_user_relationship where user_guid = '$get_user_guid' and supplier_guid = '$supplier_guid' and customer_guid = '$customer_guid'");

      //   if($check_relationship->num_rows() > 0)
      //   {
      //     // echo "<script> alert('need miss loo confirmation tommorrow') </script>";
      //     // die;
      //     $data = array(
      //     'para1' => 1,
      //     'msg' => 'need miss loo confirmation tommorrow',
      //     );    
      //   }
      //   else
      //   {
      //     $get_supplier_code = $this->db->query("SELECT * FROM set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");

      //     foreach($get_supplier_code as $row3)
      //     {
      //       $supplier_group_guid = $row3->supplier_group_guid;
      //       $this->db->query("REPLACE INTO set_supplier_user_relationship 
      //           SELECT '$customer_guid' as customer_guid
      //           , '$supplier_guid' as supplier_guid
      //           , '".$supplier_group_guid."' as supplier_group_guid
      //           , '$get_user_guid'
      //           , now()
      //           , '".$this->session->userdata('userid')."'
      //           ");
      //     }
      //   }
      // }
    
      
      $error = $this->db->affected_rows();
      if($check_status > 0)
      {
        echo json_encode($data);
      }
      else if($check_relationship->num_rows() > 0)
      {
        echo json_encode($data);
      }
      else if($error > 0)
      {
          $data = array(
          'para1' => 0,
          'msg' => 'Successfully',

          );    
          echo json_encode($data);      
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Error',

          );  
          echo json_encode($data);        
      }
  }

  public function add_vendor_code()
  {
      $table_child = $this->input->post('table_name1');
      $table_main = $this->input->post('table_name2');
      $register_guid = $this->input->post('register_guid');
      $customer_guid = $this->input->post('customer_guid');
      $code = $this->input->post('code');

      $register = $this->db->query("SELECT a.`store_code` FROM $table_child a INNER JOIN $table_main b ON a.register_guid = b.register_guid LEFT JOIN set_supplier_info d ON d.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid' ");

      $store_code = $register->row('store_code');
      $store_code = explode(',', $store_code);
      $myArray = array_unique(array_merge($store_code,$code));
      $myArray = array_filter($myArray);
      $myArray = implode(',',$myArray);

      $this->db->query("UPDATE $table_child SET store_code = '$myArray' WHERE register_guid = '$register_guid'");

      $error = $this->db->affected_rows();

      if($error > 0)
      {
          $data = array(
          'para1' => 0,
          'msg' => 'Add Successfully',

          );    
          echo json_encode($data);      
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Add Error',

          );  
          echo json_encode($data);        
      }
  }

  public function send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module,$cc_list_string,$pdf,$reply_to)
  {
        // die;
        if($pdf != '' || $pdf != null)
        { 
            $b64Doc = chunk_split(base64_encode(file_get_contents($pdf))); 
            $filename = substr($pdf, strrpos($pdf, '/') + 1);
        }
        else
        {
            $b64Doc = ''; 
        }
        // $pdfBase64 = base64_encode(file_get_contents('uploads/qr_code/4/hah.pdf')); 
        // echo $b64Doc;die;      
        $from_email = $this->db->query("SELECT * FROM lite_b2b.mailjet_setup WHERE type = 'alert_retailer_supplier_setup' LIMIT 1");
        $to_email = $email_add;
        $to_email_name = $email_add;
        $variable = array('api_key' => '1234','secret_key' => '123456', 'module' => 'test');

        $replyto = array('Email' => $reply_to,'Name' => $reply_to);
        $from = array('Email' => $from_email->row('sender_email'),'Name' => $from_email->row('sender_name'));
        $to = array('Email' => $to_email,'Name' => $to_email_name);
        $to_array = array($to);

        if($cc_list_string != '' || $cc_list_string != null)
        {
            $test_array = explode(',',$cc_list_string);
            $cc_array=array();
            foreach($test_array as $tarray)
            {
                // echo $tarray->sender_email;
                $cc = array('Email' => $tarray,'Name' => $tarray);
                array_push($cc_array, $cc);
            }
        }
        else
        {
            $cc_array = '';  
        }

        // $Bc = array('Email' => 'desmondm520@gmail.com','Name' => 'you1');
        $bcc_array = array();
        $variable1 = array($variable);
        $variables = array('var1' => $variable1);
        // $variables_array = array($variables);
        $templateid = 1090613;
        $Subject = $email_subject;
        $TextPart = $email_subject;
        $HTMLPart = $bodyContent; 

        if($b64Doc != '')
        {
            $attachment = array('ContentType' => 'application/pdf','Filename' => $filename,'Base64Content' => $b64Doc);
            $attachment1 = array($attachment);
            $attachment_array = array($attachment);            
            $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables,'cc' => $cc_array, 'replyto' =>$replyto,'attachments' => $attachment_array);
        }
        else
        {
            $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables, 'replyto' =>$replyto);
        }
        // $data2 = array($data);
        // $data3 = array('Messages' => $data2);
        // $t = array($t, "Mary", "Peter", "Sally");

        $myJSON = json_encode($data);
        //echo $myJSON;die;
        // die here
        $to_shoot_url = $this->local_ip."/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
        $ch = curl_init(); 

        curl_setopt_array($ch, array(
          CURLOPT_URL => $to_shoot_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $myJSON,
          CURLOPT_HTTPHEADER => array(
            "x-api-key: 123456",
            "Content-Type: application/json"
          ),
        ));

        // $to_shoot_url = $this->local_ip.'/pandaapi3rdparty/index.php/email_agent/mj_sendemail';
        // $ch = curl_init($to_shoot_url); 
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "123456" ));
        // curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        // // curl_setopt($ch, CURLOPT_USERPWD, $mailjet_user.":".$mailjet_pass);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        // curl_setopt($ch,CURLOPT_POSTFIELDS, $myJSON);
        $result = curl_exec($ch);
        $result1 = json_decode($result);
        // print_r($result);die;
        $retry = 0;
        while(curl_errno($ch) == 28 && $retry < 3){
            $response = curl_exec($ch);
            $retry++;
        }

        if(!curl_errno($ch))
        {
            if(isset($result1->Messages[0]))
            {
                $status = $result1->Messages[0]->Status;
            }
            else
            {
                $status = $result1->ErrorMessage;
            }


            if($status == 'success')
            {
                $ereponse = $result1->Messages[0]->To[0]->MessageID;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>$_SESSION["userid"],
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'SUCCESS',
                    'respond_message' => $ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                    );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                return $result1->Messages[0]->Status; 
            }
            else
            {
                $ereponse = $result1->Messages[0]->Errors[0]->StatusCode;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>$_SESSION["userid"],
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'FAIL',
                    'respond_message' => $ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                    );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                return $result1->Messages[0]->Status.'_'.$ereponse;
            }

            curl_close($ch);
        }
        else
        {
                $ereponse = 'Curl error: '.curl_error($ch);

                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>$_SESSION["userid"],
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'FAIL',
                    'respond_message' => $retry.$ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                    );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                return $result1->Messages[0]->Status.$ereponse;         
        }         
  } 

  public function complete_status()
  {   
      $register_guid = $this->input->post('register_guid');
      $customer_guid = $this->input->post('customer_guid');
      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
      $updated_at = $this->db->query("SELECT now() as now")->row('now');

      $data = array(   
        'update_at' => $updated_at,
        'update_by' => $user_id
      );

      $this->db->where('register_guid', $register_guid);
      $this->db->update('register', $data);

      $data = array(
        'form_status' => 'Registered'

      );
      $this->db->where('register_guid', $register_guid);
      $this->db->update('register_child', $data);

      $error = $this->db->affected_rows();

      if($error > 0){

         $data = array(
          'para1' => 0,
          'msg' => 'Registered Successfully',

          );    
          echo json_encode($data);   
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Error.',

          );    
          echo json_encode($data);   
      }
  }     

  public function vendor_complete_status()
  {
      $register_guid = $this->input->post('register_guid');
      $customer_guid = $this->input->post('customer_guid');
      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
      $updated_at = $this->db->query("SELECT now() as now")->row('now');

      $data = array(   
        'update_at' => $updated_at,
        'update_by' => $user_id
      );

      $this->db->where('register_guid', $register_guid);
      $this->db->update('register_add_user_main', $data);

      $data = array(
        'form_status' => 'Registered'

      );
      $this->db->where('register_guid', $register_guid);
      $this->db->update('register_add_user_child', $data);

      $error = $this->db->affected_rows();

      if($error > 0){

         $data = array(
          'para1' => 0,
          'msg' => 'Registered Successfully',

          );    
          echo json_encode($data);   
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Error.',

          );    
          echo json_encode($data);   
      }
  }  

  public function email_subs_function()
  {
    $details = $this->input->post('details');
    $details = json_encode($details);
    $details = json_decode($details);      
    $msg = '';
    // print_r($details); die;

    foreach($details as $row)
    {
      if($row->duplicate == 0)
      {
        $user_guid = $row->u_g;
        $customer_guid = $row->customer_guid;
        $get_user_array = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_guid = '$user_guid' LIMIT 1");
        if($get_user_array->num_rows() <= 0)
        {
          $msg .= 'Email address :'.$row->vendor_email.' duplicate template send.\n';
          continue;
        }
        $email_name = $get_user_array->row('user_id');
        $email_add = $get_user_array->row('user_id');
        // $email_name = 'danielweng57@gmail.com';
        // $email_add = 'danielweng57@gmail.com';                
        $subject = 'Login Details';
        $customer_name = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'");
        $reset_guid = $row->reset_guid;
        $url = 'https://b2b.xbridge.my';
        $reset_link = $this->db->query("SELECT * FROM lite_b2b.reset_pass_list WHERE reset_guid = '$reset_guid'");

        $reset_url = 'https://b2b.xbridge.my/index.php/Key_in/key_in?si='.$reset_link->row('reset_guid').'&ug='.$reset_link->row('user_guid');

        $supplier_guid = $row->supplier_guid;

        $supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'");

        $supplier_code = $this->db->query("SELECT GROUP_CONCAT(DISTINCT supplier_group_name) as vendor_code FROM lite_b2b.set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");

        $email_data = array(
                'reset_detail' => $reset_link,
                'customer_name' => $customer_name,
                'user_detail' => $get_user_array,
                'reset_url' => $reset_url,
                'supplier_detail' => $supplier_detail,
                'supplier_code' => $supplier_code,
                );

        $bodyContent    = $this->load->view('email_template/user_login_reset_view',$email_data,TRUE);
        //echo $bodyContent;die;  
        // die here;    
        $send_result = $this->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','support@xbridge.my');
        $data_email = array(
          'guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
          'module' => 'key_in',
          'remark' => 'new',
          'customer_guid' => $customer_guid,
          'user_guid' => $get_user_array->row('user_guid'),
          'status' => $send_result,
          'from_email' => 'b2b_admin@xbridge.my',
          'email_id' => $email_add,
          'subject' => $subject,
          'content' => $bodyContent,
          'created_by' => $_SESSION['userid'],
          'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
          'updated_by' => $_SESSION['userid'],
          'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),          
        );   
        $this->db->insert('lite_b2b.email_send_content',$data_email);


        $msg .= 'Email address :'.$row->vendor_email.' new user template send.\n';
      }
      else if($row->duplicate == 1)
      {
        $user_guid = $row->u_g;
        $customer_guid = $row->customer_guid;
        $get_user_array = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_guid = '$user_guid' LIMIT 1");
        if($get_user_array->num_rows() <= 0)
        {
          $msg .= 'Email address :'.$row->vendor_email.' duplicate template send.\n';
          continue;
        }
        $email_name = $get_user_array->row('user_id');
        $email_add = $get_user_array->row('user_id');
        // $email_name = 'danielweng57@gmail.com';
        // $email_add = 'danielweng57@gmail.com';                
        $subject = 'Login Details';
        $customer_name = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'");
        $reset_guid = $row->reset_guid;
        $url = 'https://b2b.xbridge.my';

        $supplier_guid = $row->supplier_guid;

        $supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'");

        $supplier_code = $this->db->query("SELECT GROUP_CONCAT(DISTINCT supplier_group_name) as vendor_code FROM lite_b2b.set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");

        $email_data = array(
                'customer_name' => $customer_name,
                'user_detail' => $get_user_array,
                'supplier_detail' => $supplier_detail,
                'supplier_code' => $supplier_code,
                );

        $bodyContent    = $this->load->view('email_template/user_login_duplicate_view',$email_data,TRUE);
        //echo $bodyContent;die;  
        // die here;                 
        $send_result = $this->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','support@xbridge.my');
        $data_email = array(
          'guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
          'module' => 'key_in',
          'remark' => 'duplicate',
          'customer_guid' => $customer_guid,
          'user_guid' => $get_user_array->row('user_guid'),
          'status' => $send_result,
          'from_email' => 'b2b_admin@xbridge.my',
          'email_id' => $email_add,
          'subject' => $subject,
          'content' => $bodyContent,
          'created_by' => $_SESSION['userid'],
          'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
          'updated_by' => $_SESSION['userid'],
          'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),          
        );   
        $this->db->insert('lite_b2b.email_send_content',$data_email);


        $msg .= 'Email address :'.$row->vendor_email.' duplicate template send.\n';
      }
      else
      {
        $msg .= $row->vendor_email.' send error.\n';
      }

    }

    $data = array(
      'para1' => 1,
      'msg' => $msg,

    );  

    echo json_encode($data);
  }

  public function proceed_subscribe_email()
  {
      $register_guid = $this->input->post('register_guid');
      $customer_guid = $this->input->post('customer_guid');
      $table_main = $this->input->post('table_main');
      // $vendor_code = $this->input->post('vendor_code');

      $details = $this->input->post('details');
      $details = json_encode($details);
      $details = json_decode($details);

      $get_supp = $this->db->query("SELECT supplier_guid FROM $table_main b WHERE b.`register_guid` = '$register_guid'");
      $supplier_guid = $get_supp->row('supplier_guid');
      $created_by = $this->session->userdata('userid');

      //print_r($supplier_guid); die;
      // die;

      // print_r($vendor_email); echo '<br>';
      // print_r($vendor_code);  
      foreach($details as $row4)
      {
          $get_dropdown_guid = $row4->report_guid;
          // print_r(count($row4->vendor_code));die;
          if($get_dropdown_guid == '')
          {
               $data = array(
                'para1' => 1,
                'msg' => 'Vendor code cannot be empty',
                );    
               echo json_encode($data);die;
          }
      }

      $string_code = '';
      foreach($details as $row)
      {

        $report_guid_array = $row->report_guid;
        $user_id = $row->vendor_email;//if you post from table
        $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$user_id' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');
        // echo $get_user_guid->row('user_guid').'<br>';
        foreach($report_guid_array as $report_loop_guid)
        {   
              $report_guid = $report_loop_guid;
              $schedule_type = $this->db->query("SELECT if(report_type<>'each_trans','weekly','each_trans') as schedule_type from set_report_query where report_guid = '$report_guid' ")->row('schedule_type');
              // echo $this->db->last_query();die;
              // echo $report_guid.$schedule_type;die;
              if($schedule_type != 'each_trans')
              {
                  $schedule_type =  'daily';
                  // $day_name =  $this->input->post('day_name');
                  $day_name = $this->db->query("SELECT DAYNAME(DATE_ADD(NOW(), INTERVAL 1 DAY)) as day")->row('day');
                  $date_start = $this->db->query("SELECT $day_name as date_start from calendar")->row('date_start');
                  // echo $this->db->last_query();die;
                  // echo $date_start.$day_name;die;
              }
              else
              {
                  $day_name = $this->input->post('day_name_ever');
                  $date_start = $this->db->query("SELECT curdate() as today")->row('today');
              };
              // $day_name = $this->db->query("SELECT DAYNAME(DATE_ADD(NOW(), INTERVAL 1 DAY)) as day")->row('day');
              // echo $day_name;
              // die;
              $email_user = $this->db->query("SELECT * FROM email_list WHERE user_guid = '$get_user_guid'")->row('trans_guid');
              // echo $email_user;die;
              if($email_user == '' || $email_user == null)
              {
                  $get_user_name = $this->db->query("SELECT a.*,b.user_group_name FROM lite_b2b.set_user a INNER JOIN lite_b2b.set_user_group b ON a.user_group_guid = b.user_group_guid WHERE a.user_id = '$user_id' AND a.acc_guid = '$customer_guid' LIMIT 1");
                  $data = array(
                  'trans_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                  'customer_guid' => $customer_guid,
                  'user_guid' => $get_user_guid,
                  'first_name' => addslashes($get_user_name->row('user_name')),
                  'last_name' => '',
                  'email' => $get_user_name->row('user_id'),
                  'email_group' => $get_user_name->row('user_group_name'),
                  'isactive' => '1',
                  );
                  // print_r($data);die;
                  $this->db->insert('email_list', $data);
              }
              $email_user = $this->db->query("SELECT * FROM email_list WHERE user_guid = '$get_user_guid'")->row('trans_guid');
              // echo $this->db->last_query();die;
              // echo $email_user;die;
              $checking_duplicate = $this->db->query("SELECT * from set_report_schedule where email_list_trans_guid = '$email_user' and report_guid = '$report_guid'");
              // echo $this->db->last_query();
              // echo $checking_duplicate->num_rows();die;
          
              if($checking_duplicate->num_rows() <= 0)
              {
                    // echo 2;die;
                  $data = array(
                  'schedule_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                  'customer_guid' => $customer_guid,
                  'email_list_trans_guid' => $email_user,
                  'schedule_type' =>  $schedule_type,
                  'report_guid' => $report_guid,
                  'date_start' => $date_start,
                  'day_name' =>  $day_name,
                  'active' => '1',
                  'created_at' => $this->db->query("SELECT now() as today")->row('today'),
                  'created_by' => $_SESSION['userid'],
                  'updated_at' => $this->db->query("SELECT now() as today")->row('today'),
                  'updated_by' => $_SESSION['userid'],
                  );
                  // print_r($data);die;
                  $this->db->insert('set_report_schedule', $data); 
                  $string_code .= $user_id.' schedule successfully.\n';
              }
              else
              {
                  $string_code .= $user_id.' scheduled exists.\n';
              }
        }     

      }

      $para1 = 0;
      $msg = $string_code;  
      $check_status = 1;
      $data = array(
        'para1' => $para1,
        'msg' => $msg,
      );          

      
      $error = $this->db->affected_rows();
      if($check_status > 0)
      {
        echo json_encode($data);
      }
      else if($check_relationship->num_rows() > 0)
      {
        echo json_encode($data);
      }
      else if($error > 0)
      {
          $data = array(
          'para1' => 0,
          'msg' => 'Successfully',

          );    
          echo json_encode($data);      
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Error',

          );  
          echo json_encode($data);        
      }
  }  
}
?>