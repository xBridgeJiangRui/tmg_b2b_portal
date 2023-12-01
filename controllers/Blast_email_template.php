<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blast_email_template extends CI_Controller
{

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
    $this->load->model('Send_email_model');
    //load the department_model
  }

  public function index()
  {
    if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
      $get_email_group = $this->db->query("SELECT a.guid,CONCAT(a.type,' - ',a.email_group_name,' - ',a.description) AS description FROM lite_b2b.set_email a WHERE a.activate = '1'");

      $get_acc = $this->db->query("SELECT  a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY acc_name ASC");

      $data = array(
        'get_email_group' => $get_email_group->result(),
        'get_acc' => $get_acc->result(),
      );

      $this->load->view('header');
      $this->load->view('email_template/list_template', $data);
      $this->load->view('footer');
    } else {
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
    $search = $this->input->post("search");
    $search = $search['value'];
    $col = 0;
    $dir = "";

    if (!empty($order)) {
      foreach ($order as $o) {
        $col = $o['column'];
        $dir = $o['dir'];
      }
    }

    if ($dir != "asc" && $dir != "desc") {
      $dir = "desc";
    }

    $valid_columns = array(
      0 => 'template_guid',
      1 => 'type',
      2 => 'mail_type',
      3 => 'mail_subject',
      4 => 'description',
      //5 => 'remark',
      5 => 'is_active',
      6 => 'created_at',
      7 => 'created_by',
      8 => 'updated_at',
      9 => 'updated_by',
    );

    if (!isset($valid_columns[$col])) {
      $order = null;
    } else {
      $order = $valid_columns[$col];
    }

    if ($order != null) {
      // $this->db->order_by($order, $dir);
      $order_query = "ORDER BY " . $order . "  " . $dir;
    }

    $like_first_query = '';
    $like_second_query = '';

    if (!empty($search)) {
      $x = 0;
      foreach ($valid_columns as $sterm) {
        if ($x == 0) {
          // $this->db->like($sterm,$search);
          $like_first_query = "WHERE $sterm LIKE '%" . $search . "%'";
        } else {
          // $this->db->or_like($sterm,$search);
          $like_second_query .= "OR $sterm LIKE '%" . $search . "%'";
        }
        $x++;
      }
    }
    // $this->db->limit($length,$start);
    $limit_query = " LIMIT " . $start . " , " . $length;

    $sql = "SELECT a.*,b.url_link FROM lite_b2b.email_template a LEFT JOIN lite_b2b.email_template_pdf b ON a.template_guid = b.template_guid WHERE a.is_active != '2' ";

    $query = "SELECT * FROM ( " . $sql . " ) aa " . $like_first_query . $like_second_query . $order_query . $limit_query;
    $result = $this->db->query($query);
    //echo $this->db->last_query(); die;

    if (!empty($search)) {
      $query_filter = "SELECT * FROM ( " . $sql . " ) a " . $like_first_query . $like_second_query;
      $result_filter = $this->db->query($query_filter)->result();
      $total = count($result_filter);
    } else {
      $total = $this->db->query($sql)->num_rows();
    }
    $data = array();

    foreach ($result->result() as $row) {
      $nestedData['template_guid'] = $row->template_guid;
      $nestedData['type'] = $row->type;
      $nestedData['mail_type'] = $row->mail_type;
      $nestedData['description'] = $row->description;
      $nestedData['mail_subject'] = $row->mail_subject;
      $nestedData['body_header'] = $row->body_header;
      $nestedData['body_content'] = $row->body_content;
      $nestedData['body_footer'] = $row->body_footer;
      $nestedData['remark'] = $row->remark;
      $nestedData['created_at'] = $row->created_at;
      $nestedData['created_by'] = $row->created_by;
      $nestedData['updated_at'] = $row->updated_at;
      $nestedData['updated_by'] = $row->updated_by;
      $nestedData['is_active'] = $row->is_active;
      $nestedData['is_replace'] = $row->is_replace;
      $nestedData['is_editable'] = $row->is_editable;
      $nestedData['is_pdf'] = $row->is_pdf;
      $nestedData['url_link'] = $row->url_link;

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

  public function add_template()
  {
    $user_guid = $_SESSION['user_guid'];
    $template_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS template_guid")->row('template_guid');
    $user_id = $this->db->query("SELECT a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');
    $add_mail_subject = $this->input->post('add_mail_subject');
    $add_mail_description = $this->input->post('add_mail_description');
    $add_active = $this->input->post('add_active');
    $add_replace = $this->input->post('add_replace');
    $add_content = $this->input->post('add_content');
    $add_type = $this->input->post('add_type');
    $add_mail_type = $this->input->post('add_mail_type');

    //$get_header_footer = $this->db->query("SELECT a.body_header, a.body_footer FROM lite_b2b.email_template a WHERE a.type = 'FMT' AND a.mail_type = 'format' AND a.is_active = '2' LIMIT 1");

    $get_header = $this->db->query("SELECT a.value AS body_header FROM lite_b2b.config a WHERE a.type = 'header_val' AND a.module = 'email_temp' AND a.device = 'web' AND a.code = 'EHTMP' LIMIT 1")->row('body_header');

    $get_footer = $this->db->query("SELECT a.value AS body_footer FROM lite_b2b.config a WHERE a.type = 'footer_val' AND a.module = 'email_temp' AND a.device = 'web' AND a.code = 'EFTMP' LIMIT 1")->row('body_footer');

    // if ($get_header_footer->num_rows() == 0) {
    //   $data = array(
    //     'para1' => 'false',
    //     'msg' => 'Data Not Found.',
    //   );
    //   echo json_encode($data);
    //   exit();
    // }
    //print_r($add_content); die;

    $data = array(
      'template_guid' => $template_guid,
      'mail_subject' => $add_mail_subject,
      'description' => $add_mail_description,
      'type' => $add_type,
      'mail_type' => $add_mail_type,
      //'body_header' => $get_header,
      'body_content' => $add_content,
      //'body_footer' => $get_footer,
      'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
      'created_by' => $user_id,
      'is_active' => $add_active,
      'is_replace' => $add_replace,
      'is_editable' => 1,
    );

    $this->db->insert('lite_b2b.email_template', $data);

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 'true',
        'msg' => 'Insert Successfully.',
      );
      echo json_encode($data);
      exit();
    } else {
      $data = array(
        'para1' => 'false',
        'msg' => 'Error to insert.',
      );
      echo json_encode($data);
      exit();
    }
  }

  public function edit_template()
  {
    $user_guid = $_SESSION['user_guid'];
    $template_guid = $this->input->post('template_guid');
    $edit_mail_subject = $this->input->post('edit_mail_subject');
    $edit_mail_description = $this->input->post('edit_mail_description');
    $edit_active = $this->input->post('edit_active');
    $edit_content = $this->input->post('edit_content');
    $edit_type = $this->input->post('edit_type');
    $edit_mail_type = $this->input->post('edit_mail_type');
    $edit_replace = $this->input->post('edit_replace');
    $edit_editable = $this->input->post('edit_editable');
    $edit_pdf = $this->input->post('edit_pdf');

    //$editable.html(dom.html($edit_content) || dom.emptyPara);

    //print_r($template_guid); die;

    $check_data = $this->db->query("SELECT template_guid FROM lite_b2b.email_template WHERE template_guid = '$template_guid'");

    if ($check_data->num_rows() == 0) {
      $data = array(
        'para1' => 'false',
        'msg' => 'Data Not Found.',
      );
      echo json_encode($data);
      exit();
    }

    $user_id = $this->db->query("SELECT a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');

    $data = array(
      'mail_subject' => $edit_mail_subject,
      'description' => $edit_mail_description,
      'type' => $edit_type,
      'mail_type' => $edit_mail_type,
      'body_content' => $edit_content,
      'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
      'updated_by' => $user_id,
      'is_active' => $edit_active,
      'is_replace' => $edit_replace,
      'is_editable' => $edit_editable,
      'is_pdf' => $edit_pdf,
    );
    $this->db->where('template_guid', $template_guid);
    $this->db->update('lite_b2b.email_template', $data);

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 'true',
        'msg' => 'Update Successfully.',
      );
      echo json_encode($data);
      exit();
    } else {
      $data = array(
        'para1' => 'false',
        'msg' => 'Error to update.',
      );
      echo json_encode($data);
      exit();
    }
  }

  public function fetch_content()
  {
    $template_guid = $this->input->post("template_guid");

    $get_header = $this->db->query("SELECT a.value AS body_header FROM lite_b2b.config a WHERE a.type = 'header_val' AND a.module = 'email_temp' AND a.device = 'web' AND a.code = 'EHTMP' LIMIT 1")->row('body_header');

    $get_footer = $this->db->query("SELECT a.value AS body_footer FROM lite_b2b.config a WHERE a.type = 'footer_val' AND a.module = 'email_temp' AND a.device = 'web' AND a.code = 'EFTMP' LIMIT 1")->row('body_footer');

    $content = $this->db->query("SELECT CONCAT('$get_header',body_content,'$get_footer') AS content,body_content FROM lite_b2b.email_template WHERE template_guid = '$template_guid' ");

    $body_content = $content->row('body_content');

    $data = array(
      "para1" => 'true',
      "content" => $content->result(),
      "body_content" => $body_content,
    );

    echo json_encode($data);
  }

  public function send_the_template()
  {
    $mail_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS mail_guid")->row('mail_guid');
    $template_guid = $this->input->post('template_guid');
    $email_group = $this->input->post('email_group');
    $add_new_email = $this->input->post('add_new_email');
    $acc_guid = $this->input->post('acc_guid');
    $reply_to = 'support@xbridge.my';
    $to_email = [];
    $cc_email_list = '';
    $temp = [];
    $filename = '';
    $attachment_file_path = '';
    // print_r($template_guid); echo '<br>';
    //print_r($add_new_email); die;

    //$get_email_template_info = $this->lite_b2b_model->get_email_template($description, $type, $mail_type);

    $get_header = $this->db->query("SELECT a.value AS body_header FROM lite_b2b.config a WHERE a.type = 'header_val' AND a.module = 'email_temp' AND a.device = 'web' AND a.code = 'EHTMP' LIMIT 1")->row('body_header');

    $get_footer = $this->db->query("SELECT a.value AS body_footer FROM lite_b2b.config a WHERE a.type = 'footer_val' AND a.module = 'email_temp' AND a.device = 'web' AND a.code = 'EFTMP' LIMIT 1")->row('body_footer');

    $get_email_template_info = $this->db->query("SELECT a.`mail_subject`,a.`body_content`,a.is_pdf
        FROM lite_b2b.`email_template` AS a
        WHERE a.`template_guid` = '$template_guid'
        AND a.`is_active` = '1'
        LIMIT 1");

    $is_pdf =  $get_email_template_info->row('is_pdf');
        
    if($is_pdf == '1')
    {
      $url_link = $this->db->query("SELECT a.`url_link`
      FROM lite_b2b.`email_template_pdf` AS a
      WHERE a.`template_guid` = '$template_guid'
      LIMIT 1")->row('url_link');

      $attachment_file_path = $_SERVER["DOCUMENT_ROOT"].$url_link;

      $filename = basename($attachment_file_path);
    }

    //$get_email_list = $this->lite_b2b_model->get_email_user_list($supplier_guid, $customer_guid)->result_array();

    if($email_group != '')
    {
      $get_email_list = $this->db->query("SELECT a.`type`, a.`email_group_name`, a.`description`, b.`category`, b.`customer_guid`, b.`supplier_guid`, b.`user_guid`, b.`user_email`, b.`user_email`, b.`email_name`,b.cc_email FROM lite_b2b.`set_email` AS a INNER JOIN lite_b2b.`set_email_group` AS b ON a.`guid` = b.`email_group_guid` WHERE a.guid = '$email_group' AND b.`is_active` = '1'")->result_array();

      // send email by each
      foreach ($get_email_list as $key => $value) {
        $to_email[] = $value['user_email'];

        $cc_email_list =  explode(',',$value['cc_email']);
      }
      if($add_new_email != '')
      {
        $to_email = array_merge( $to_email , $add_new_email );
      }
    }
    else 
    {
      $add_new_email = implode(',', $add_new_email);
      $to_email = array_unique(explode(',', $add_new_email));
    }

    // print_r($to_email);
    // print_r($cc_email_list);
    // die;
    if ($to_email == '' || $to_email == 'null' || $to_email == null || $to_email == []) {
      $data = array(
        "para1" => '1',
        "msg" => 'Invalid To Email Address',
      );
      echo json_encode($data);
      exit();
    }

    //$replace_key = ['%current_date%', '%retailer%', '%filename%', '%status%', '%reason%'];
    //$replace_value = [date('Y-m-d'), $retailer_name, $filename, 'Invalid', $description];
    //$content = str_replace($replace_key, $replace_value, $get_email_template_info->row('body_content'));
    $content =  $get_email_template_info->row('body_content');

    foreach ($to_email as $row => $value) {
      
      // print_r($value);
      // die;
      $email_address = $value;
      // send email to user

      $this->Send_email_model->send_mailjet_third_party(
        $email_address,
        '',
        $get_header . $content . $get_footer,
        $get_email_template_info->row('mail_subject'),
        '',
        $cc_email_list,
        $attachment_file_path,
        $reply_to,
        $filename
      );

      $this->mail_to_log(
        $acc_guid,
        $mail_guid,
        $template_guid,
        $email_address,
        $get_header . $content. $get_footer,
        $get_email_template_info->row('mail_subject'),
        $cc_email_list,
        $reply_to,
        $attachment_file_path,
        $filename
      );
    }

    $data = array(
      "para1" => 'true',
      "msg" => 'Send Successfully'
    );

    echo json_encode($data);
  }

  public function mail_to_log($acc_guid,$mail_guid,$template_guid,$email_address,$content,$subject,$cc_email,$reply_to,$attachment_file_path,$filename)
  {
    $log_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS log_guid")->row('log_guid');
    $user_guid = $_SESSION['user_guid'];
    $user_id = $this->db->query("SELECT a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');

    if($cc_email == '' || $cc_email == null || $cc_email == 'null' || $cc_email == [])
    {
      $cc_email = '';
    }
    else
    {
      $cc_email = implode(",",$cc_email);
      $cc_email = "".$cc_email."";
    }

    $data = array(
      'log_guid' => $log_guid,
      'mail_guid' => $mail_guid,
      'customer_guid' => $acc_guid,
      'template_guid' => $template_guid,
      'email_address' => $email_address,
      'cc_email_address' => $cc_email,
      'reply_to' => $reply_to,
      'subject' => $subject,
      'file_path' => $attachment_file_path,
      'file_name' => $filename,
      'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
      'created_by' => $user_id,
    );
    $this->db->insert('lite_b2b.log_mailbox', $data);

    return;
  }

  public function upload_template_pdf()
  {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0);
    ini_set('post_max_size', '10M');
    ini_set('upload_max_filesize', '10M');
    //set php.ini upload_max_filesize=64M
    //$acc_guid = $_SESSION['customer_guid'];
    $session_guid = $_SESSION['user_guid'];
    $file_uuid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $cur_date = $this->db->query("SELECT now() as now")->row('now');
    $created_at = $this->db->query("SELECT now() as now")->row('now');
    //print_r($file_config_main_path); die;
    $file_name = $this->input->post('file_name');
    $pdf_template_guid = $this->input->post('pdf_template_guid');
    $file_config_main_path = $this->file_config_b2b->file_path_name($acc_guid, 'web', 'attachment_docs', 'main_path', 'TMPDOC');
    $file_config_sec_path = $this->file_config_b2b->file_path_name($acc_guid, 'web', 'attachment_docs', 'sec_path', 'TMPDOCPATH');
    //print_r($term_type); die;  

    $check_pdf_exists = $this->db->query("SELECT * FROM lite_b2b.email_template_pdf WHERE template_guid = '$pdf_template_guid' ");
    //echo $this->db->last_query(); die;

    if ($check_pdf_exists->num_rows() >= 1) {
        $r_url = $check_pdf_exists->row('url_link');
        $r_file_name = basename($r_url);
        $r_unlink_path = $file_config_main_path . "$pdf_template_guid/$r_file_name";
        unlink($r_unlink_path);
        $update_data = $this->db->query("DELETE FROM lite_b2b.email_template_pdf WHERE template_guid = '$pdf_template_guid' ");
    }

    $defined_path = $file_config_main_path . $pdf_template_guid . '/';
    // $defined_path = $file_config_main_path . $acc_guid . '/' . $supplier_guid . '/';
    // $defined_path_1 = $file_config_main_path . $acc_guid . '/' . $supplier_guid . '/' . $user_guid . '/';
    // $defined_path_2 = $file_config_main_path . $acc_guid . '/' . $supplier_guid . '/' . $user_guid . '/' . $term_type . '/';
    //print_r($defined_path); die;

    $extension = explode('.', $file_name);
    $file_type = end($extension);
    // $last_key = count($extension) - 1;

    $running_number = rand();
    $file_name = 'Attachement_'.$running_number.'.'.$file_type;

    if (!file_exists($defined_path)) {
        mkdir($defined_path, 0777);
    }

    //if want add date uncomment here @@@@@
    // $cur_date = str_replace(' ', '_', $cur_date);
    // $cur_date = str_replace(':', '', $cur_date);
    // $file_name = $cur_date . '_' . $file_name;
    // $file_name = str_replace('[', '', $file_name);
    // $file_name = str_replace(']', '', $file_name);

    $url_link_path = $file_config_sec_path . $pdf_template_guid . '/' . $file_name;
    // $unlink_path_check = $file_config_sec_path . $acc_guid . '/' . $supplier_guid . '/' . $user_guid . '/' . $term_type . '/' . $file_name . '';

    // if(file_exists($unlink_path)){
    // unlink($unlink_path);
    // }

    // $check_path = $file_config_main_path . $pdf_template_guid . '/' . $file_name;

    // if (file_exists($check_path)) {
    //     $data = array(
    //         'para1' => 1,
    //         'msg' => 'Document File Name Exists.',
    //     );
    //     echo json_encode($data);
    //     exit();
    // }

    $config['upload_path']          = $defined_path;
    $config['allowed_types']        = '*';
    $config['max_size']             = 500000000;
    $config['file_name'] = $file_name;
    //var_dump( $this->input->post('file') );die; 
    //print_r($this->input->post());die;
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file')) {
        $error = array('error' => $this->upload->display_errors());

        if (null != $error) {
            $data = array(
                'para1' => 1,
                'msg' => $this->upload->display_errors(),
            );
            echo json_encode($data);
            exit();
        } //close else

    } else {
        $data = array('upload_data' => $this->upload->data());

        $insert_data = $this->db->query("INSERT INTO `lite_b2b`.`email_template_pdf` (`pdf_guid`, `template_guid`,`url_link`, `is_active` ,`created_at`, `created_by`) VALUES ('$file_uuid', '$pdf_template_guid', '$url_link_path' , '1' ,'$created_at', '$session_guid');");

    }

    $error = $this->db->affected_rows();

    if ($error > 0) {

        $data = array(
            'para1' => 0,
            'msg' => 'Upload Completed.',
            //'link' => $url_link,
        );
        echo json_encode($data);
        exit();
    } else {
        $data = array(
            'para1' => 1,
            'msg' => 'Error Upload Data.',
            //'link' => 'Unknown URL.',

        );
        echo json_encode($data);
        exit();
    }
  }

  public function insert_batch_email()
  {
    $mail_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS mail_guid")->row('mail_guid');
    $user_guid = $_SESSION['user_guid'];
    $user_id = $this->db->query("SELECT a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');
    $template_guid = $this->input->post('template_guid');
    $email_group = $this->input->post('email_group');
    $add_new_email = array_filter($this->input->post('add_new_email'));
    $acc_guid = $this->input->post('acc_guid');
    $email_type = 'email_template';
    $json_param = '';

    $count_email = count($add_new_email);
    
    if($count_email > 0 )
    {
      $add_new_email = implode(',', $add_new_email);
      $to_email = array_unique(explode(',', $add_new_email));

      foreach($to_email as $row => $value)
      {
        $foreach_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS mail_guid")->row('mail_guid');
        $email_address = $value;

        $check_duplicate_email = $this->db->query("SELECT * FROM lite_b2b.blast_email_list WHERE template_guid = '$template_guid' AND email_add = '$email_address' AND `status` = '0'")->result_array();

        if(count($check_duplicate_email) > 0)
        {
          continue;
        }

        // $data_array = array(
        //   '%retailer_name%' => 'TFVM',
        //   '%start_date%' => '2023-03-01',
        // );

        // $json_param = json_encode($data_array);

        //print_r($json_param); die;
        
        $data = array(
          'customer_guid' => $acc_guid,
          'email_guid' => $foreach_guid,
          'template_guid' => $template_guid,
          'email_add' => $email_address,
          'email_type' => $email_type,
          'json_param' => $json_param,
          'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
          'created_by' => $user_id,
        );
        $this->db->insert('lite_b2b.blast_email_list', $data);
      }
    }
    
    if($email_group != '')
    {
      $get_group_email = $this->db->query("SELECT `guid`, email_group_guid, user_email FROM lite_b2b.set_email_group WHERE email_group_guid = '$email_group' AND `is_active` = '1' AND user_email LIKE '%@%' ");

      if($get_group_email->num_rows() > 0)
      {
        foreach($get_group_email->result() as $row)
        {
          $foreach_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS mail_guid")->row('mail_guid');
          $email_address = $row->user_email;
  
          $check_duplicate_email = $this->db->query("SELECT * FROM lite_b2b.blast_email_list WHERE template_guid = '$template_guid' AND email_add = '$email_address' AND `status` = '0'")->result_array();
  
          if(count($check_duplicate_email) > 0)
          {
            continue;
          }

          // $data_array = array(
          //   '%retailer_name%' => 'TFVM',
          //   '%start_date%' => '2023-03-01',
          // );
  
          // $json_param = json_encode($data_array);
  
          //print_r($json_param); die;
          
          $data = array(
            'customer_guid' => $acc_guid,
            'email_guid' => $foreach_guid,
            'template_guid' => $template_guid,
            'email_add' => $email_address,
            'email_type' => $email_type,
            'json_param' => $json_param,
            'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
            'created_by' => $user_id,
          );
          $this->db->insert('lite_b2b.blast_email_list', $data);
        }
      }
    }

    $error = $this->db->affected_rows();

    if($error > 0)
    {
      $data = array(
        "para1" => 'true',
        "msg" => 'Insert to Batch Successfully'
      );
    }
    else
    {
      $data = array(
        "para1" => 'false',
        "msg" => 'Failed to Insert'
      );
    }

    echo json_encode($data);
  }
}
