<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Registration_dashboard extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    //load the department_model
  }

  public function index()
  {
    if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
    {
      $customer_guid = $_SESSION['customer_guid'];

      $exlcude_retailer_where = "WHERE a.customer_guid != '13EE932D98EB11EAB05B000D3AA2838A' ";
      $exlcude_retailer_and = "AND a.customer_guid != '13EE932D98EB11EAB05B000D3AA2838A' ";

      $get_data = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.acc_guid = '$customer_guid'");

      $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY acc_name ASC");

      $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY acc_name ASC");
  
      $acc_name = 'All Retailers';
  
      $acc_guid = 'all';
  
      $total = $this->db->query("SELECT COUNT(*) AS count_total FROM lite_b2b.register_new a $exlcude_retailer_where")->row('count_total');

      $no_action = $this->db->query("SELECT COUNT(*) AS count_new FROM lite_b2b.register_new a WHERE a.`form_status` = '' $exlcude_retailer_and")->row('count_new');
  
      $percent_no_action = round(($no_action / $total) * 100,2);
  
      $registered = $this->db->query("SELECT COUNT(*) AS count_registered FROM lite_b2b.register_new a WHERE a.`form_status` = 'Registered' $exlcude_retailer_and")->row('count_registered');
  
      $percent_reg = round(($registered / $total) * 100,2);
  
      $new = $this->db->query("SELECT COUNT(*) AS count_new FROM lite_b2b.register_new a WHERE a.`form_status` = 'New' $exlcude_retailer_and")->row('count_new');
  
      $percent_new = round(($new / $total) * 100,2);
  
      $processing = $this->db->query("SELECT COUNT(*) AS count_processing FROM lite_b2b.register_new a WHERE a.`form_status` = 'Processing' $exlcude_retailer_and")->row('count_processing');
  
      $percent_processing = round(($processing / $total) * 100,2);
  
      $save_progress = $this->db->query("SELECT COUNT(*) AS count_save_process FROM lite_b2b.register_new a WHERE a.`form_status` = 'Save-Progress' $exlcude_retailer_and")->row('count_save_process');
  
      $percent_save_progress = round(($save_progress / $total) * 100,2);
  
      $send = $this->db->query("SELECT COUNT(*) AS count_send FROM lite_b2b.register_new a WHERE a.`form_status` = 'Send' $exlcude_retailer_and")->row('count_send');
  
      $percent_send = round(($send / $total) * 100);
  
      $advance = $this->db->query("SELECT COUNT(*) AS count_advance FROM lite_b2b.register_new a WHERE a.`form_status` = 'Advance' $exlcude_retailer_and")->row('count_advance');
  
      $percent_advance = round(($advance / $total) * 100,2);

      $archived = $this->db->query("SELECT COUNT(*) AS count_archived FROM lite_b2b.register_new a WHERE a.`form_status` = 'Archived' $exlcude_retailer_and")->row('count_archived');
  
      $percent_archived = round(($archived / $total) * 100,2);

      $terminate = $this->db->query("SELECT COUNT(*) AS count_terminate FROM lite_b2b.register_new a WHERE a.`form_status` = 'Terminated' $exlcude_retailer_and")->row('count_terminate');
  
      $percent_terminate = round(($terminate / $total) * 100,2);

      $consignment = $this->db->query("SELECT COUNT(a.register_guid) AS count_consignment FROM lite_b2b.register_new a 
      LEFT JOIN b2b_invoice.`template_settings_general` b
      ON a.memo_type = b.template_guid
      WHERE a.form_status = 'Registered'
      AND (a.memo_type LIKE '%consign%' OR b.template_group = 'Consign' )
      $exlcude_retailer_and")->row('count_consignment');
  
      $percent_consignment = round(($consignment / $registered) * 100,2);

      $outright = $this->db->query("SELECT COUNT(a.register_guid) AS count_outright FROM lite_b2b.register_new a 
      LEFT JOIN b2b_invoice.`template_settings_general` b
      ON a.memo_type = b.template_guid
      WHERE a.form_status = 'Registered'
      AND (a.memo_type LIKE '%outright%' OR b.template_group = 'Outright' )
      $exlcude_retailer_and")->row('count_outright');
  
      $percent_outright = round(($outright / $registered) * 100,2);

      $both = $this->db->query("SELECT COUNT(a.register_guid) AS count_both
      FROM lite_b2b.register_new a 
      WHERE a.form_status = 'Registered'
      AND a.memo_type IN ('both')
      $exlcude_retailer_and")->row('count_both');
  
      $percent_both = round(($both / $registered) * 100,2);

      $data = array(
        'customer_name' => $get_data->row('acc_name'),
        'acc_guid' =>  $get_data->row('acc_guid'),
        'get_acc' => $get_acc->result(),
        'total' => $total,
        'registered' => $registered,
        'percent_no_action' => is_nan($percent_no_action) ? '0' :  $percent_no_action,
        'no_action' => $no_action,
        'percent_reg' => is_nan($percent_reg) ? '0' :  $percent_reg,
        'new' => $new,
        'percent_new' => is_nan($percent_new) ? '0' :  $percent_new, 
        'processing' => $processing,
        'percent_processing' => is_nan($percent_processing) ? '0' :  $percent_processing,
        'save_progress' => $save_progress,
        'percent_save_progress' => is_nan($percent_save_progress) ? '0' : $percent_save_progress,
        'send' => $send,
        'percent_send' => is_nan($percent_send) ? '0' : $percent_send,
        'advance' => $advance,
        'percent_advance' => is_nan($percent_advance) ? '0' : $percent_advance,
        'archived' => $archived,
        'percent_archived' => is_nan($percent_archived) ? '0' : $percent_archived,
        'terminate' => $terminate,
        'percent_terminate' => is_nan($percent_terminate) ? '0' : $percent_terminate,
        'outright' => $outright,
        'percent_outright' => is_nan($percent_outright) ? '0' : $percent_outright,
        'consignment' => $consignment,
        'percent_consignment' => is_nan($percent_consignment) ? '0' : $percent_consignment,
        'both' => $both,
        'percent_both' => is_nan($percent_both) ? '0' : $percent_both,
      );

      $this->load->view('header');
      $this->load->view('register/dashboard', $data);  
      $this->load->view('footer');
    } else {
      $this->session->set_flashdata('message', 'Session Expired! Please relogin');
      redirect('#');
    }
  }

  public function add_new_summary()
  {
    $customer_guid = $this->input->post('acc_guid');

    if($customer_guid == 'all')
    {
      $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY acc_name ASC");
  
      $acc_name = 'All Retailers';
  
      $acc_guid = 'all';
  
      $total = $this->db->query("SELECT COUNT(*) AS count_total FROM lite_b2b.register_new a ")->row('count_total');

      $no_action = $this->db->query("SELECT COUNT(*) AS count_new FROM lite_b2b.register_new a WHERE a.`form_status` = '' ")->row('count_new');
  
      $percent_no_action = round(($no_action / $total) * 100,2);
  
      $registered = $this->db->query("SELECT COUNT(*) AS count_registered FROM lite_b2b.register_new a WHERE a.`form_status` = 'Registered'  ")->row('count_registered');
  
      $percent_reg = round(($registered / $total) * 100,2);
  
      $new = $this->db->query("SELECT COUNT(*) AS count_new FROM lite_b2b.register_new a WHERE a.`form_status` = 'New' ")->row('count_new');
  
      $percent_new = round(($new / $total) * 100,2);
  
      $processing = $this->db->query("SELECT COUNT(*) AS count_processing FROM lite_b2b.register_new a WHERE a.`form_status` = 'Processing' ")->row('count_processing');
  
      $percent_processing = round(($processing / $total) * 100,2);
  
      $save_progress = $this->db->query("SELECT COUNT(*) AS count_save_process FROM lite_b2b.register_new a WHERE a.`form_status` = 'Save-Progress' ")->row('count_save_process');
  
      $percent_save_progress = round(($save_progress / $total) * 100,2);
  
      $send = $this->db->query("SELECT COUNT(*) AS count_send FROM lite_b2b.register_new a WHERE a.`form_status` = 'Send' ")->row('count_send');
  
      $percent_send = round(($send / $total) * 100);
  
      $advance = $this->db->query("SELECT COUNT(*) AS count_advance FROM lite_b2b.register_new a WHERE a.`form_status` = 'Advance' ")->row('count_advance');
  
      $percent_advance = round(($advance / $total) * 100,2);

      $archived = $this->db->query("SELECT COUNT(*) AS count_archived FROM lite_b2b.register_new a WHERE a.`form_status` = 'Archived' ")->row('count_archived');
  
      $percent_archived = round(($archived / $total) * 100,2);

      $terminate = $this->db->query("SELECT COUNT(*) AS count_terminate FROM lite_b2b.register_new a WHERE a.`form_status` = 'Terminated' ")->row('count_terminate');
  
      $percent_terminate = round(($terminate / $total) * 100,2);
    }
    else
    {
      $get_data = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.acc_guid = '$customer_guid'");

      $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY acc_name ASC");
  
      $acc_name = $get_data->row('acc_name');
  
      $acc_guid =  $get_data->row('acc_guid');
  
      $total = $this->db->query("SELECT COUNT(*) AS count_total FROM lite_b2b.register_new a WHERE a.`customer_guid` = '$customer_guid' ")->row('count_total');

      $no_action = $this->db->query("SELECT COUNT(*) AS count_new FROM lite_b2b.register_new a WHERE a.`form_status` = '' AND a.`customer_guid` = '$customer_guid' ")->row('count_new');
  
      $percent_no_action = round(($no_action / $total) * 100,2);
  
      $registered = $this->db->query("SELECT COUNT(*) AS count_registered FROM lite_b2b.register_new a WHERE a.`form_status` = 'Registered' AND a.`customer_guid` = '$customer_guid' ")->row('count_registered');
  
      $percent_reg = round(($registered / $total) * 100,2);
  
      $new = $this->db->query("SELECT COUNT(*) AS count_new FROM lite_b2b.register_new a WHERE a.`form_status` = 'New' AND a.`customer_guid` = '$customer_guid' ")->row('count_new');
  
      $percent_new = round(($new / $total) * 100,2);
  
      $processing = $this->db->query("SELECT COUNT(*) AS count_processing FROM lite_b2b.register_new a WHERE a.`form_status` = 'Processing' AND a.`customer_guid` = '$customer_guid' ")->row('count_processing');
  
      $percent_processing = round(($processing / $total) * 100,2);
  
      $save_progress = $this->db->query("SELECT COUNT(*) AS count_save_process FROM lite_b2b.register_new a WHERE a.`form_status` = 'Save-Progress' AND a.`customer_guid` = '$customer_guid' ")->row('count_save_process');
  
      $percent_save_progress = round(($save_progress / $total) * 100,2);
  
      $send = $this->db->query("SELECT COUNT(*) AS count_send FROM lite_b2b.register_new a WHERE a.`form_status` = 'Send' AND a.`customer_guid` = '$customer_guid' ")->row('count_send');
  
      $percent_send = round(($send / $total) * 100,2);
  
      $advance = $this->db->query("SELECT COUNT(*) AS count_advance FROM lite_b2b.register_new a WHERE a.`form_status` = 'Advance' AND a.`customer_guid` = '$customer_guid' ")->row('count_advance');
  
      $percent_advance = round(($advance / $total) * 100,2);

      $archived = $this->db->query("SELECT COUNT(*) AS count_archived FROM lite_b2b.register_new a WHERE a.`form_status` = 'Archived' AND a.`customer_guid` = '$customer_guid' ")->row('count_archived');
  
      $percent_archived = round(($archived / $total) * 100,2);

      $terminate = $this->db->query("SELECT COUNT(*) AS count_terminate FROM lite_b2b.register_new a WHERE a.`form_status` = 'Terminated' AND a.`customer_guid` = '$customer_guid'")->row('count_terminate');
  
      $percent_terminate = round(($terminate / $total) * 100,2);

      $consignment = $this->db->query("SELECT COUNT(a.register_guid) AS count_consignment FROM lite_b2b.register_new a 
      LEFT JOIN b2b_invoice.`template_settings_general` b
      ON a.memo_type = b.template_guid
      WHERE a.form_status = 'Registered'
      AND (a.memo_type LIKE '%consign%' OR b.template_group = 'Consign' )
      AND a.`customer_guid` = '$customer_guid'")->row('count_consignment');
  
      $percent_consignment = round(($consignment / $registered) * 100,2);

      $outright = $this->db->query("SELECT COUNT(a.register_guid) AS count_outright FROM lite_b2b.register_new a 
      LEFT JOIN b2b_invoice.`template_settings_general` b
      ON a.memo_type = b.template_guid
      WHERE a.form_status = 'Registered'
      AND (a.memo_type LIKE '%outright%' OR b.template_group = 'Outright' )
      AND a.`customer_guid` = '$customer_guid'")->row('count_outright');
  
      $percent_outright = round(($outright / $registered) * 100,2);

      $both = $this->db->query("SELECT COUNT(a.register_guid) AS count_both
      FROM lite_b2b.register_new a 
      WHERE a.form_status = 'Registered'
      AND a.memo_type IN ('both')
      AND a.`customer_guid` = '$customer_guid'")->row('count_both');
  
      $percent_both = round(($both / $registered) * 100,2);

    }

    $data = array(
      "para1" => 'true',
      'customer_name' => $acc_name,
      'acc_guid' => $acc_guid,
      'get_acc' => $get_acc->result(),
      'total' => $total,
      'no_action' => $no_action,
      'percent_no_action' => is_nan($percent_no_action) ? '0' :  $percent_no_action,
      'registered' => $registered,
      'percent_reg' => is_nan($percent_reg) ? '0' :  $percent_reg,
      'new' => $new,
      'percent_new' => is_nan($percent_new) ? '0' :  $percent_new, 
      'processing' => $processing,
      'percent_processing' => is_nan($percent_processing) ? '0' :  $percent_processing,
      'save_progress' => $save_progress,
      'percent_save_progress' => is_nan($percent_save_progress) ? '0' : $percent_save_progress,
      'send' => $send,
      'percent_send' => is_nan($percent_send) ? '0' : $percent_send,
      'advance' => $advance,
      'percent_advance' => is_nan($percent_advance) ? '0' : $percent_advance,
      'archived' => $archived,
      'percent_archived' => is_nan($percent_archived) ? '0' : $percent_archived,
      'terminate' => $terminate,
      'percent_terminate' => is_nan($percent_terminate) ? '0' : $percent_terminate,
      'outright' => $outright,
      'percent_outright' => is_nan($percent_outright) ? '0' : $percent_outright,
      'consignment' => $consignment,
      'percent_consignment' => is_nan($percent_consignment) ? '0' : $percent_consignment,
      'both' => $both,
      'percent_both' => is_nan($percent_both) ? '0' : $percent_both,

    );

    echo json_encode($data);
    
  }
 
}
